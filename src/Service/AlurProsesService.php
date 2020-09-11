<?php
/**
 * Created by PhpStorm.
 * User: core
 * Date: 02/10/16
 * Time: 11:33
 */

namespace App\Service;

use Cake\ORM\TableRegistry;
use App\Model\Table\PermohonanIzinTable;
use App\Model\Table\ProsesPermohonanTable;
use App\Service\NumberingService;
use Cake\Datasource\ConnectionManager;

class AlurProsesService extends AuthService
{
    const STATUS_PROSES = 'Proses';
    const STATUS_MENUNGGU = 'Menunggu';
    const STATUS_SELESAI = 'Selesai';
    const STATUS_DROP = 'Drop';

    const TIPE_PROSES = 'signature-report';
    const TAUTAN_PERMOHONAN_ADD = 'permohonan-izin-add';
    const TAUTAN_PERMOHONAN_EDIT = 'permohonan-izin-edit';
    const TAUTAN_FORM_ADD = 'form-add';
    const TAUTAN_REPORT = 'report';
    const TAUTAN_SIGNATURE = 'signature-report';
    const TAUTAN_RETRIBUSI = 'retribusi-add';

    private static $nextProsesId = null;

    public static function getNextProsesId() {
        return self::$nextProsesId;
    }

    /*
     * Generate Data Proses Permohonan berdasarkan permohonanId yang baru dibuat
     * @return boolean
     */
    public static function generateProsesPermohonan($permohonanIzinId)
    {
        $success = false;

        $permohonanIzinTable = TableRegistry::get('PermohonanIzin');
        $permohonanIzin = $permohonanIzinTable->get($permohonanIzinId, [
            'fields' => [
                'id',
                'jenis_permohonan',
                'jenis_izin_id'
            ]
        ]);

        if ($permohonanIzin) {
            $prosesPermohonanData = self::getAlurProses(
                $permohonanIzin->jenis_izin_id,
                $permohonanIzin->jenis_permohonan
            );

            if (!empty($prosesPermohonanData) && is_array($prosesPermohonanData)) {
                $firstFormId = null;

                if (isset($prosesPermohonanData[0])) {
                    $firstFormId = $prosesPermohonanData[0]['form_id'];
                }

                // BEGIN - Insert Daftar Proses into Proses Permohonan
                $prosesPermohonan = $permohonanIzinTable->ProsesPermohonan;
                $newProsesPermohonan = $permohonanIzinTable
                    ->ProsesPermohonan
                    ->newEntities($prosesPermohonanData);

                $saveProsesPermohonan = $prosesPermohonan
                    ->connection()
                    ->transactional(function () use (
                        $prosesPermohonan,
                        $newProsesPermohonan,
                        $permohonanIzinId
                    ) {
                        foreach ($newProsesPermohonan as $entity) {
                            $entity->permohonan_izin_id = $permohonanIzinId;
                            $prosesPermohonan->save(
                                $entity,
                                ['atomic' => false]
                            );
                        }
                    });
                // END - Insert Daftar Proses into Proses Permohonan

                if (is_null($saveProsesPermohonan)) {
                    if (self::openNextStep(
                        $permohonanIzinId,
                        null
                    )) {
                        $success = true;
                    }
                }
            }
        }
        return $success;
    }

    public static function openNextStep($permohonanIzinId, $prosesPermohonanId = null, $currentUserName = null)
    {
        $success = false;
        $isFirstProcess = false;
        $condProses = [
            'ProsesPermohonan.permohonan_izin_id' => $permohonanIzinId
        ];

        $permohonanIzinTable = TableRegistry::get('PermohonanIzin');
        $permohonanIzin = $permohonanIzinTable->get(
            $permohonanIzinId,
            [
                'fields' => [
                    'id', 'proses_permohonan_id'
                ]
            ]
        );

        if (!$prosesPermohonanId) {
            $isFirstProcess = true;
            $condProses = [
                'ProsesPermohonan.permohonan_izin_id' => $permohonanIzinId
            ];
        } else {
            $condProses = [
                'ProsesPermohonan.id' => $prosesPermohonanId
            ];
        }

        // Cek Proses Sekarang, apakah cocok dengan request yang dikirim
        $currentProcess = $permohonanIzinTable
            ->ProsesPermohonan
            ->find('all', [
                'conditions' => $condProses,
                'order' => [
                    'ProsesPermohonan.id' => 'ASC'
                ]
            ])->first();

        // Jika Proses ditemukan
        if ($currentProcess) {
            $permohonanIzinId = $currentProcess->permohonan_izin_id;

            // Update proses sekarang menjadi selesai
            $currentProcess->status = self::STATUS_SELESAI;
            $currentProcess->diproses_oleh = $currentUserName;
            $currentProcess->end_date = date("Y-m-d H:i:s");
            $permohonanIzinTable->ProsesPermohonan->save($currentProcess);

            $nextProses = $permohonanIzinTable
                ->ProsesPermohonan
                ->find('all', [
                    'conditions' => [
                        'permohonan_izin_id' => $permohonanIzinId,
                        'id >' => $currentProcess->id,
                    ],
                    'order' => [
                        'id' => 'ASC'
                    ]
                ])
                ->first();

            if ($nextProses->status == self::STATUS_MENUNGGU) { // Jika Proses berikutnya ditemukan
                $nextProses->status = self::STATUS_PROSES;
                $nextProses->start_date = date("Y-m-d H:i:s");
                if ($permohonanIzinTable->ProsesPermohonan->save($nextProses)) {
                    // Update proses_permohonan_id di tabel permohonan_izin
                    $permohonanIzin->proses_permohonan_id = $nextProses->id;
                    self::$nextProsesId = $nextProses->id;
                    
                    if ($permohonanIzinTable->save($permohonanIzin)) {
                        $success = true;
                    }
                }
            } else {
                // cek apakah proses terakhir sudah selesai
                $lastProsesFinished = $permohonanIzinTable
                    ->ProsesPermohonan
                    ->find('all', [
                        'conditions' => [
                            'permohonan_izin_id' => $permohonanIzinId,
                            'status' => self::STATUS_SELESAI
                        ],
                        'order' => [
                            'id' => 'DESC'
                        ]
                    ])
                    ->first();
                self::$nextProsesId = $nextProses->id;

                if ($lastProsesFinished) {
                    $success = true;
                }
            }
        }

        return $success;
    }

    public static function getAlurProses($jenisIzinId, $jenisPermohonan)
    {
        $prosesPermohonanData = [];
        $jenisIzinTable = TableRegistry::get('JenisIzin');
        $jenisIzin = $jenisIzinTable->get($jenisIzinId, [
            'fields' => [
                'JenisIzin.id'
            ],
            'contain' => [
                'JenisPengajuan' => [
                    'fields' => [
                        'JenisPengajuan.id',
                        'JenisPengajuan.jenis_pengajuan',
                        'JenisPengajuan.alur_proses_id',
                        'JenisPengajuan.jenis_izin_id'
                    ],
                    'conditions' => [
                        'JenisPengajuan.jenis_pengajuan' => $jenisPermohonan
                    ]
                ]
            ],
        ]);

        if ($jenisIzin) {
            $jenisPengajuan = $jenisIzin->jenis_pengajuan[0];
            $alurProsesId = $jenisPengajuan->alur_proses_id;

            $daftarProsesTable = TableRegistry::get('daftar_proses');
            $daftarProses = $daftarProsesTable->find('all', [
                'fields' => [
                    'id',
                    'alur_proses_id',
                    'jenis_proses_id',
                    'no',
                    'form_id',
                    'tautan',
                    'template_data_id',
                    'tipe'
                ],
                'contain' => [
                    'JenisProses' => [
                        'fields' => ['id', 'nama_proses', 'is_drop']
                    ],
                    'JenisIzinProses' => [
                        'fields' => [
                            'id', 'jenis_pengajuan_id', 'daftar_proses_id',
                            'form_id', 'tautan', 'template_data_id'
                        ],
                        'conditions' => [
                            'jenis_pengajuan_id' => $jenisPengajuan->id
                        ]
                    ],
                ],
                'conditions' => [
                    'alur_proses_id' => $alurProsesId
                ],
                'order' => [
                    'no' => 'ASC'
                ]
            ])->toArray();

            if ($daftarProses) {
                $prosesPermohonanData = [];

                foreach ($daftarProses as $index => $proses) {
                    $namaProses = $proses->jenis_prose->nama_proses;
                    $tautan = $proses->tautan;
                    $formId = $proses->form_id;
                    $templateDataId = $proses->template_data_id;
                    $tipe = $proses->tipe;
                    $daftarProsesId = $proses->id;
                    $overriden = false;

                    // Check if there is any overriding for the proses
                    if ($proses->jenis_izin_prose) {
                        $tautan = $proses->jenis_izin_prose->tautan;
                        $formId = $proses->jenis_izin_prose->form_id;
                        $templateDataId = $proses
                            ->jenis_izin_prose
                            ->template_data_id;
                        $overriden = true;
                    }

                    if ($index == 0) {
                        $prosesPermohonanData[] = [
                            'daftar_proses_id' => $daftarProsesId,
                            'jenis_izin_id' => $jenisIzinId,
                            'jenis_proses_id' => $proses->jenis_proses_id,
                            'status' => self::STATUS_PROSES,
                            'nama_proses' => $namaProses,
                            'tautan' => $tautan,
                            'form_id' => $formId,
                            'template_data_id' => $templateDataId,
                            'overriden' => $overriden,
                            'tipe' => $tipe
                        ];
                    } else {
                        $status = $proses->jenis_prose->is_drop ? self::STATUS_DROP : self::STATUS_MENUNGGU;
                        $prosesPermohonanData[] = [
                            'daftar_proses_id' => $daftarProsesId,
                            'jenis_izin_id' => $jenisIzinId,
                            'jenis_proses_id' => $proses->jenis_proses_id,
                            'status' => $status,
                            'nama_proses' => $namaProses,
                            'tautan' => $tautan,
                            'form_id' => $formId,
                            'template_data_id' => $templateDataId,
                            'overriden' => $overriden,
                            'tipe' => $tipe
                        ];
                        unset($status);
                    }
                }
            }
        }

        return $prosesPermohonanData;
    }

    /**
     * Format Alur Proses data to be saved as new entity
     *
     * @param [type] $alurProses
     * @return array $alurProses without ids
     */
    public static function prepareCopyAlurProses($alurProses)
    {
        if (!empty($alurProses)) {
            unset($alurProses['id']);
            unset($alurProses['tgl_dibuat']);
            unset($alurProses['dibuat_oleh']);
            unset($alurProses['tgl_diubah']);
            unset($alurProses['diubah_oleh']);

            $instansiId = (self::$instansi) ? self::$instansi->id : null;
            $alurProses['instansi_id'] = $instansiId;
            $alurProses['keterangan'] = $alurProses['keterangan'] . '-copy';

            if (!empty($alurProses['daftar_proses'])) {
                foreach ($alurProses['daftar_proses'] as $index => $alur) {
                    unset($alurProses['daftar_proses'][$index]['id']);
                    unset($alurProses['daftar_proses'][$index]['alur_proses_id']);
                    unset($alurProses['daftar_proses'][$index]['tgl_dibuat']);
                    unset($alurProses['daftar_proses'][$index]['dibuat_oleh']);
                    unset($alurProses['daftar_proses'][$index]['tgl_diubah']);
                    unset($alurProses['daftar_proses'][$index]['diubah_oleh']);
                }
            }
        }

        return $alurProses;
    }

    /**
     * Format Jenis Izin data to be saved as new entity
     *
     * @param [type] $jenisIzin
     * @return array $jenisIzin without ids
     */
    public static function prepareCopyJenisIzin($jenisIzin)
    {
        if (!empty($jenisIzin)) {
            unset($jenisIzin['id']);
            unset($jenisIzin['tgl_dibuat']);
            unset($jenisIzin['dibuat_oleh']);
            unset($jenisIzin['tgl_diubah']);
            unset($jenisIzin['diubah_oleh']);

            $instansiId = (self::$instansi) ? self::$instansi->id : null;
            $jenisIzin['instansi_id'] = $instansiId;
            $jenisIzin['jenis_izin'] = $jenisIzin['jenis_izin'] . '-copy';

            $relatedEntities = [
                'dokumen_pendukung', 'izin_paralel', 'jenis_pengajuan',
                'formula_retribusi', 'unit_terkait'
            ];

            foreach ($relatedEntities as $relatedEntity) {
                if (array_key_exists($relatedEntity, $jenisIzin) && !empty($jenisIzin[$relatedEntity])) {
                    foreach ($jenisIzin[$relatedEntity] as $index => $alur) {
                        if ($relatedEntity == 'formula_retribusi') {
                            unset($jenisIzin[$relatedEntity]['id']);
                            unset($jenisIzin[$relatedEntity]['jenis_izin_id']);
                            unset($jenisIzin[$relatedEntity]['tgl_dibuat']);
                            unset($jenisIzin[$relatedEntity]['dibuat_oleh']);
                            unset($jenisIzin[$relatedEntity]['tgl_diubah']);
                            unset($jenisIzin[$relatedEntity]['diubah_oleh']);
                        } else {
                            unset($jenisIzin[$relatedEntity][$index]['id']);
                            unset($jenisIzin[$relatedEntity][$index]['jenis_izin_id']);
                            unset($jenisIzin[$relatedEntity][$index]['tgl_dibuat']);
                            unset($jenisIzin[$relatedEntity][$index]['dibuat_oleh']);
                            unset($jenisIzin[$relatedEntity][$index]['tgl_diubah']);
                            unset($jenisIzin[$relatedEntity][$index]['diubah_oleh']);

                            switch ($relatedEntity) {
                            case 'jenis_pengajuan':
                                foreach ($jenisIzin[$relatedEntity][$index]['jenis_izin_proses'] as $idx => $jenisIzinProses) {
                                    unset($jenisIzin[$relatedEntity][$index]['jenis_izin_proses'][$idx]['jenis_pengajuan_id']);
                                }
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $jenisIzin;
    }

    public static function generateNomorIzin(
        $jenisIzinId,
        $jenisPengajuan,
        $instansiId = null,
        $unitId = null,
        $updateSequence = false
    ) {
        $number = null;

        if (!$instansiId) {
            $instansiId = (self::$instansi) ? self::$instansi->id : null;
        }

        if (!$unitId) {
            $unitId = (self::$unit && self::$unit->id) ? self::$unit->id : null;
        }

        if ($instansiId) {
            $jenisPengajuanTable = TableRegistry::get('JenisPengajuan');
            $jenisPengajuan = $jenisPengajuanTable->find(
                'all',
                [
                    'fields' => ['jenis_izin_id'],
                    'conditions' => [
                        'jenis_izin_id' => $jenisIzinId,
                        'jenis_pengajuan' => $jenisPengajuan
                    ],
                    'contain' => [
                        'Penomoran' => ['fields' => ['id']]
                    ]
                ]
            )->first();

            if ($jenisPengajuan->penomoran) {
                // Generate new data but don't update the last number
                $number = NumberingService::getFormattedNumber(
                    $jenisPengajuan->penomoran->id,
                    $instansiId,
                    $unitId,
                    $updateSequence
                );
            }
        }

        return $number;
    }

    public static function registerPermohonanIzin(
        $request
    ) {
        try {
            $validator = new \Cake\Validation\Validator();
            $validator
                ->requirePresence('jenis_izin_id')
                ->notEmpty('jenis_izin_id')
                ->requirePresence('jenis_permohonan')
                ->notEmpty('jenis_permohonan')
                ->requirePresence('pemohon_id')
                ->notEmpty('pemohon_id');

            $errors = $validator->errors($request);

            if (empty($errors)) {
                if (empty($request['instansi_id'])) {
                    $request['instansi_id'] = (self::$instansi) ? self::$instansi->id : null;
                }

                if (empty($request['unit_id'])) {
                    $request['unit_id'] = (self::$unit && self::$unit->id) ? self::$unit->id : null;
                }

                $generatedNumber = self::generateNomorIzin(
                    $request['jenis_izin_id'],
                    $request['jenis_permohonan'],
                    $request['instansi_id'],
                    $request['unit_id'], // will use user session
                    true
                );

                if ($generatedNumber) {
                    $request['no_permohonan'] = $generatedNumber;
                }

                $permohonanIzinTable = TableRegistry::get('PermohonanIzin');
                $permohonanIzin = $permohonanIzinTable->newEntity();

                if (!empty($request['perusahaan_id'])) {
                    $permohonanIzin = $permohonanIzinTable->patchEntity(
                        $permohonanIzin,
                        $request,
                        ['associated' => ['Pemohon', 'Perusahaan']]
                    );
                } else { // Jika pemohon perorangan
                    $permohonanIzin = $permohonanIzinTable->patchEntity(
                        $permohonanIzin,
                        $request,
                        ['associated' => ['Pemohon']]
                    );
                }

                if ($permohonanIzinTable->save($permohonanIzin)) {
                    if ($request['tipe_pemohon'] == PermohonanIzinTable::TIPE_PEMOHON_PERUSAHAAN) {

                        if (!empty($request['perusahaan'])) {
                            $perusahaanTable = TableRegistry::get('Perusahaan');

                            if (!empty($request['perusahaan']['jenis_usaha_ids'])) {
                                $jenisUsaha = $permohonanIzinTable->Perusahaan
                                    ->JenisUsaha->find()
                                    ->where(['id IN' => $request['perusahaan']['jenis_usaha_ids']])
                                    ->toArray();
                                $perusahaanTable->JenisUsaha->link($permohonanIzin->perusahaan, $jenisUsaha);
                            }

                            if (!empty($request['perusahaan']['bidang_usaha_ids'])) {
                                $bidangUsaha = $permohonanIzinTable->Perusahaan->BidangUsaha->find()->where([
                                    'id IN' => $request['perusahaan']['bidang_usaha_ids']
                                ])->toArray();
                                $perusahaanTable->BidangUsaha->link($permohonanIzin->perusahaan, $bidangUsaha);
                            }

                            // Link Pemohon to Perusahaan
                            $perusahaan = $perusahaanTable->get($permohonanIzin->perusahaan->id);
                            if ($perusahaan) {
                                $perusahaan->pemohon_id = $permohonanIzin->pemohon->id;
                                $perusahaanTable->save($perusahaan);
                            }
                        }
                    }

                    // Generate proses_permohonan data
                    if (self::generateProsesPermohonan($permohonanIzin->id)) {
                        $permohonanIzinTable->generateIzinParalel($permohonanIzin);
                        $permohonanIzin = $permohonanIzinTable->get($permohonanIzin->id);
                        NotificationService::sendNotification($permohonanIzin);
                        return true;
                    } else {
                        throw new \Exception('terjadi error ketika generate proses permohonan');
                    }
                } else {
                    self::$errors = $permohonanIzin->errors();
                    return false;
                }
             }
        } catch (\Exception $ex) {
            self::$errors = [$ex->getMessage()];
            return false;
        }
    }

    public static function isAllowedProcess($jenisProsesId, $jenisProsesIdsPengguna)
    {
        // Check if current User has access to that jenis_proses_id
        if ($jenisProsesIdsPengguna && is_array($jenisProsesIdsPengguna)) {
            return in_array($jenisProsesId, $jenisProsesIdsPengguna);
        }

        return true;
    }
    
    public static function switchStatus($entity)
    {
        $connection = ConnectionManager::get('default');

        //ambil id proses yang sedang berjalan
        $prosesBerjalan = $connection->execute("
            SELECT id FROM proses_permohonan
            WHERE permohonan_izin_id = {$entity->id} AND status = '" . self::STATUS_PROSES . "'"
        )->fetchAll('assoc');

            // update proses nyatakan proses selesai Perubahan
        $data['status'] = self::STATUS_SELESAI;
        $data['tgl_diubah'] = date('Y-m-d');

        if ($prosesBerjalan) {
            $connection->update('proses_permohonan', $data, [
                'id' => $prosesBerjalan[0]["id"],
                'permohonan_izin_id' => $entity->id
            ]);
        }

        $data['status'] = self::STATUS_DROP;
        $data['tgl_diubah'] = date('Y-m-d');

        if ($prosesBerjalan) {
            $connection->update('proses_permohonan', $data, [
                'id >' => $prosesBerjalan[0]["id"],
                'permohonan_izin_id' => $entity->id
            ]);
        }

        //ambil id proses terakhir
        $prosesTerakhir = $connection->execute(
            "SELECT MAX(id) AS id FROM proses_permohonan WHERE permohonan_izin_id = " . $entity->id
        )->fetchAll('assoc');

        //update permohonan sudah selesai
        $data_permohonan['status'] = self::STATUS_DROP;
        $data_permohonan['proses_permohonan_id'] = $prosesTerakhir[0]['id'];
        $data_permohonan['status_rekomendasi'] = $entity->status_rekomendasi;
        $data_permohonan['tgl_selesai'] = date('Y-m-d');

        $connection->update('permohonan_izin', $data_permohonan, ['id' => $entity->id]);
    }

    public static function switchTodrop($entity)
    {
        $prosesId = [];
        $prosesPermohonanTable = TableRegistry::get('proses_permohonan');
        $prosesId = $prosesPermohonanTable
            ->find('all', ['fields'=>['id']])
            ->where([
                'permohonan_izin_id' => $entity->id,
                'status'=>self::STATUS_DROP
            ])
            ->extract('id')
            ->toArray();

        $prosesPermohonanTable->updateAll(
            ['status'=>self::STATUS_DROP],
            ['permohonan_izin_id' => $entity->id, 'status' => self::STATUS_MENUNGGU]
        );

        if(!empty($prosesId)){
            $prosesPermohonanTable->updateAll(
                ['status'=>self::STATUS_MENUNGGU],
                ['id IN' => $prosesId]
            );
        }

        $connection = ConnectionManager::get('default');

        $data_permohonan['status'] = self::STATUS_DROP;
        $data_permohonan['tgl_selesai'] = date('Y-m-d');
        $connection->update('permohonan_izin', $data_permohonan, ['id' => $entity->id]);
    }
}
