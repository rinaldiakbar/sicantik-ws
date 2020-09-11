<?php
/**
 * Created by PhpStorm.
 * User: Core
 * Date: 5/25/2017
 * Time: 8:16 PM
 */

namespace App\Controller\Gateway;

use App\Model\Table\LaporanPermasalahanTable;
use App\Service\NotificationService;
use Cake\ORM\TableRegistry;

class MessagesController extends GatewayController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Messages');
    }

    public function fetch()
    {
        $success = false;
        $message = null;
        $data = [];

        if ($this->request->is('post')) {
            $success = true;
            $user = $this->getCurrentUser();

            $messages = $this->Messages->find('all', [
                'fields' => [
                    'id', 'recipient_no', 'body', 'status'
                ],
                'conditions' => [
                    'gateway_user_id' => $user->id,
                    'status' => NotificationService::STATUS_NEW
                ],
                'order' => ['id' => 'ASC'],
                'limit' => 10
            ])->toArray();

            if ($messages) {
                $ids = [];

                foreach ($messages as $textMessage) {
                    $data[] = [
                        'id' => $textMessage->id,
                        'to' => $textMessage->recipient_no,
                        'body' => $textMessage->body,
                        'status' => $textMessage->status
                    ];
                    $ids[] = $textMessage->id;
                }

                $this->Messages->updateAll(
                    ['status' => NotificationService::STATUS_DELIVERED], // fields
                    ['id IN' => $ids] // conditions
                );
            }
        }

        $this->setResponseData($data, $success, $message);
    }

    public function forward()
    {
        $success = false;
        $message = null;
        $data = [];

        if ($this->request->is('post')) {
            $success = true;
            $user = $this->getCurrentUser();

            // Validate the request body
            $validator = new \Cake\Validation\Validator();
            $validator
                ->requirePresence('sender_no')
                ->notEmpty('sender_no', 'Sender no cannot be empty')
                ->requirePresence('message')
                ->notEmpty('message', 'Message cannot be empty');
            $errors = $validator->errors($this->request->data());

            if (empty($errors)) {
                $replyBody = null;
                $requester = $this->request->data['sender_no'];
                $body = $this->request->data['message'];

                // If it's keluhan
                if (preg_match('/^KELUHAN /i', $body, $matches)) {
                    $pesan = trim(substr($body, strlen($matches[0])));

                    // Save to table laporan_permasalahan
                    $keluhanTable = TableRegistry::get('LaporanPermasalahan');
                    $keluhan = $keluhanTable->newEntity();
                    $keluhan->dibuat_oleh = $sender;
                    $keluhan->source = LaporanPermasalahanTable::SOURCE_SMS;
                    $keluhan->permasalahan = $pesan;
                    $keluhan->instansi_id = $user->instansi_id;

                    if ($keluhanTable->save($keluhan)) {
                        $success = true;
                        $message = 'Keluhan berhasil disimpan';

                        // Send auto reply message
                        $replyBody = 'Terima kasih telah mengirimkan pengaduan kepada kami. Keluhan anda segera kami proses.';
                        NotificationService::setType(NotificationService::TYPE_SMS);
                        NotificationService::sendMessage($requester, 'Reply Keluhan', $replyBody, $user->id);
                    } else {
                        $message = 'Keluhan tidak berhasil disimpan';
                    }
                } elseif(preg_match('/^CEK /i', $body, $matches)) {
                    // If it's tracking message
                    // match alphanumeric, _, -, and / character
                    $noPermohonan = trim(substr($body, strlen($matches[0])));

                    // Get last status of that permohonan
                    $permohonanIzinTable = TableRegistry::get('PermohonanIzin');
                    $permohonanIzin = $permohonanIzinTable->find(
                        'all',
                        [
                            'fields' => ['id', 'proses_permohonan_id', 'pemohon_id', 'no_permohonan'],
                            'conditions' => ['no_permohonan' => $noPermohonan],
                            'contain' => [
                                'Pemohon' => [
                                    'fields' => [
                                        'id', 'nama'
                                    ]
                                ],
                                'LatestProsesPermohonan' => [
                                    'fields' => [
                                        'id', 'nama_proses'
                                    ]
                                ]
                            ]
                        ]
                    );

                    if ($permohonanIzin->isEmpty() == 0) {
                        $success = false;
                        $message = 'Tracking tidak valid';

                        // Set auto reply message
                        $replyBody = 'Permohonan Izin dengan nomor tersebut tidak ditemukan';
                    } else {
                        $success = true;
                        $message = 'Tracking valid';

                        // Set auto reply message
                        $permohonanIzin = $permohonanIzin->first();
                        $replyBody = sprintf(
                            'Permohonan Izin dengan nomor %s atas nama %s saat ini sedang dalam proses "%s"',
                            trim($permohonanIzin->no_permohonan),
                            trim($permohonanIzin->pemohon->nama),
                            trim($permohonanIzin->latest_proses_permohonan->nama_proses)
                        );
                    }

                    NotificationService::setType(NotificationService::TYPE_SMS);
                    NotificationService::sendMessage($requester, 'Reply Tracking', $replyBody, $user->id);
                } else {
                    $success = true;
                    $message = 'Pesan berhasil diterima';
                }
            } else {
                $this->setErrors($errors);
            }
        }

        $this->setResponseData($data, $success, $message);
    }
}