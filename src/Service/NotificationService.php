<?php
namespace App\Service;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\Core\Configure;
use App\Model\Entity\PermohonanIzin;
use App\Model\Table\PesanTable;
use App\Model\Table\PemohonTable;

/**
 * Business Logic to send notification
 */
class NotificationService
{
    CONST TYPE_EMAIL = 'email';
    CONST TYPE_SMS = 'sms';
    CONST TIPE_PENERIMA_PEMOHON = 'pemohon';
    CONST TIPE_PENERIMA_JABATAN = 'jabatan';
    CONST STATUS_NEW = 'NEW';
    CONST STATUS_FETCHED = 'FETCHED';
    CONST STATUS_SENT = 'SENT';
    CONST STATUS_FAILED = 'FAILED';
    CONST STATUS_DELIVERED = 'DELIVERED';

    private static $type = 'email'; // default notification type

    public static function setType($type) {
        $type = strtolower($type);

        if ($type == self::TYPE_SMS) {
            self::$type = $type;
        } else {
            self::$type = self::TYPE_EMAIL;
        }
    }

    public static function sendMessage($sendTo, $subject, $body, $gatewayUserId = null)
    {
        switch (self::$type) {
            case self::TYPE_SMS:
                $messageTable = TableRegistry::get('Messages');
                $message = $messageTable->newEntity();
                $message->recipient_no = $sendTo;
                $message->body = $body;
                $message->gateway_user_id = $gatewayUserId;

                if (!$messageTable->save($message)) {
                    return false;
                }

                break;
            default:
                $email = new Email('default');
                $targetEmail = $sendTo;
                $email->emailFormat('html');
                $email->setTo($targetEmail);
                $email->setSubject($subject);
                $email->send($body);
                break;
        }

        return true;
    }

    public static function parseMessage($templateString, $vars)
    {
        $translatedMessage = $templateString;

        // Replace all occurence of vars in $templateString
        foreach ($vars as $varName => $varValue) {
            $pattern = '/\{\$' . $varName . '\}/';
            $translatedMessage = preg_replace($pattern, $varValue, $translatedMessage);
        }

        return $translatedMessage;
    }

    public static function sendNotification(PermohonanIzin $permohonanIzin)
    {
        if (!$permohonanIzin->proses_permohonan_id) {
            throw new \Exception('Proses Permohonan tidak diketahui');
        }

        if (!$permohonanIzin->jenis_izin_id) {
            throw new \Exception('Jenis Izin tidak diketahui');
        }

        if (!$permohonanIzin->jenis_permohonan) {
            throw new \Exception('Jenis Permohonan tidak diketahui');
        }

        if (!$permohonanIzin->pemohon_id) {
            throw new \Exception('Pemohon tidak diketahui');
        }

        if (!$permohonanIzin->no_permohonan) {
            throw new \Exception('No Permohonan tidak diketahui');
        }

        if (!$permohonanIzin->instansi_id) {
            throw new \Exception('Instansi tidak diketahui');
        }

        // Get Pengguna Gateway for the instansi
        $penggunaGateway = null;
        $penggunaGatewayTable = TableRegistry::get('GatewayUsers');
        $findPenggunaGateway = $penggunaGatewayTable->find('all', [
            'fields' => ['id'],
            'conditions' => ['instansi_id' => $permohonanIzin->instansi_id, 'is_active'=>1]
        ]);

        if ($findPenggunaGateway->count() > 0) {
            $penggunaGateway = $findPenggunaGateway->first();
        }

        // Get daftar_proses_id from ProsesPermohonan
        $prosesPermohonanTable = TableRegistry::get('ProsesPermohonan');
        $prosesPermohonan = $prosesPermohonanTable->get($permohonanIzin->proses_permohonan_id, [
            'fields' => [
                'id', 'daftar_proses_id'
            ]
        ]);

        if (!$prosesPermohonan->daftar_proses_id) {
            return true;
        }

        // Get notification setting for that proses permohonan
        $notifikasiTable = TableRegistry::get('notifikasi');
        $notifikasi = $notifikasiTable->find('all', [
            'fields' => [
                'id', 'jenis_izin_id'
            ],
            'contain' => [
                'NotifikasiDetail' => [
                    'fields' => [
                        'id', 'notifikasi_id', 'tipe', 'format_pesan', 'tipe_penerima', 'jabatan_id'
                    ],
                    'conditions' => [ // conditions not working
                        'daftar_proses_id' => $prosesPermohonan->daftar_proses_id
                    ]
                ]
            ],
            'conditions' => [
                'jenis_izin_id' => $permohonanIzin->jenis_izin_id
            ],
        ])->first();

        // If notification setting not exists
        if (!$notifikasi || !$notifikasi->notifikasi_detail) {
            return true;
        }

        $subject = 'Notifikasi Proses Permohonan Izin - ' . $permohonanIzin->no_permohonan;

        // Get Pemohon Data
        $pemohonTable = TableRegistry::get('Pemohon');
        $pemohon = $pemohonTable->find('all', [
            'fields' => [
                'id', 'nama', 'no_hp', 'email'
            ],
            'conditions' => [
                'id' => $permohonanIzin->pemohon_id
            ]
        ])->first();

        if (!$pemohon) {
            throw new \Exception('Pemohon tidak ditemukan');
        }

        $pegawaiTable = TableRegistry::get('Pegawai');
        $penggunaTable = TableRegistry::get('Pengguna');

        // Get all Notifikasi Detail and send the message
        foreach ($notifikasi->notifikasi_detail as $notifikasiDetail) {
            $sendTo = null;
            $vars = [
                'nama_pemohon' => $pemohon->nama,
                'no_pendaftaran' => $permohonanIzin->no_permohonan,
                'telp_pemohon' => $pemohon->no_hp,
                'email_pemohon' => $pemohon->email
            ];

            // Replace variables with data
            $body = self::parseMessage($notifikasiDetail->format_pesan, $vars);

            self::setType($notifikasiDetail->tipe);
            if (self::$type == self::TYPE_SMS) {
                if (!$penggunaGateway) { continue;}
            }

            switch ($notifikasiDetail->tipe_penerima) {
                case self::TIPE_PENERIMA_JABATAN:
                    $employees = $pegawaiTable->find('all', [
                        'fields' => [
                            'id', 'email', 'no_hp'
                        ],
                        'conditions' => [
                            'jabatan_id' => $notifikasiDetail->jabatan_id,
                            'instansi_id' => $permohonanIzin->instansi_id,
                            'del' => 0
                        ]
                    ])->all();

                    if ($employees) {
                        foreach ($employees as $employee) {
                            if (self::$type == self::TYPE_SMS) {
                                $sendTo = $employee->no_hp;
                            } else {
                                $sendTo = $employee->email;

                                // Send notification for web app
                                $users = $penggunaTable->find('all', [
                                    'conditions' => ['pegawai_id' => $employee->id],
                                ]);

                                if ($users->count() > 0) {
                                    foreach ($users as $user) {
                                        self::sendUserNotification(
                                            $user->id,
                                            $subject,
                                            $body,
                                            PesanTable::GROUP_PROSES_PENGAJUAN
                                        );
                                    }
                                }
                            }
                            self::sendMessage($sendTo, $subject, $body, $penggunaGateway ? $penggunaGateway->id : null);
                        }
                    }

                    break;
                default:
                    // It will be sent to pemohon
                    if (self::$type == self::TYPE_SMS) {
                        $sendTo = $pemohon->no_hp;
                    } else {
                        $sendTo = $pemohon->email;

                        // Send notification for web app
                        $users = $penggunaTable->find('all', [
                            'conditions' => [
                                'related_object_name' => AuthService::PEMOHON_OBJECT,
                                'related_object_id' => $pemohon->id,
                                'data_status' => PemohonTable::DATA_STATUS_ACTIVE
                            ],
                        ]);

                        if ($users->count() > 0) {
                            foreach ($users as $user) {
                                self::sendUserNotification(
                                    $user->id,
                                    $subject,
                                    $body,
                                    PesanTable::GROUP_PROSES_PENGAJUAN
                                );
                            }
                        }
                    }
                    
                    self::sendMessage($sendTo, $subject, $body, $penggunaGateway ? $penggunaGateway->id : null);
                    break;
            }
        }
    }

    /**
     * Send Pesan to Pengguna
     * @param $message
     * @param $groupType
     * @param $targetUserId
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public static function sendUserNotification($targetUserId, $subject, $message, $messageGroup, $notifType = PesanTable::TIPE_GENERAL)
    {
        $pesanTable = TableRegistry::get('Pesan');

        $pesan = $pesanTable->newEntity();
        $pesan->judul = $subject;
        $pesan->pesan = $message;
        $pesan->grup_notifikasi = $messageGroup ?: null;
        $pesan->pengguna_id = $targetUserId;
        $pesan->tipe = $notifType;

        return $pesanTable->save($pesan);
    }
}