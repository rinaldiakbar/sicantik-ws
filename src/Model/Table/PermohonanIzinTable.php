<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\Datasource\ConnectionManager;
use ArrayObject;
use App\Model\Entity\PermohonanIzin;
use App\Model\Entity\PermohonanJenisIzin;
use App\Service\AlurProsesService;
use Psr\Log\LogLevel;
use Cake\Http\Client;

/**
 * PermohonanJenisIzin Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Pemohon
 * @property \Cake\ORM\Association\BelongsTo $Perusahaan
 * @property \Cake\ORM\Association\BelongsTo $JenisIzin
 * @property \Cake\ORM\Association\BelongsTo $ProsesPermohonan
 */
class PermohonanIzinTable extends AppTable
{
    const TIPE_PEMOHON_PERORANGAN = 'PERORANGAN';
    const TIPE_PEMOHON_PERUSAHAAN = 'PERUSAHAAN';
    const STATUS_REKOMENDASI = 'R';
    const STATUS_DITETAPKAN = 'ditetapkan';
    const STATUS_HOLD = 'Hold';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->strictDelete = true;

        $this->setTable('permohonan_izin');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->source_url = null;

        $this->belongsTo('Pemohon', [
            'foreignKey' => 'pemohon_id',
            // 'joinType' => 'INNER'
        ]);

        $this->belongsTo('Perusahaan', [
            'foreignKey' => 'perusahaan_id'
        ]);

        $this->belongsTo('JenisIzin', [
            'foreignKey' => 'jenis_izin_id'
        ]);

        $this->hasMany('ProsesPermohonan', [
            'foreignKey' => 'permohonan_izin_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->belongsTo('LatestProsesPermohonan', [
            'className' => 'ProsesPermohonan',
            'foreignKey' => 'proses_permohonan_id',
            'joinType' => 'INNER' // important for getPermohonanToSign to works
        ]);

        $this->belongsTo('JenisProyek', [
            'foreignKey' => 'jenis_proyek_id'
        ]);

        $this->hasMany('Persyaratan', [
            'foreignKey' => 'permohonan_izin_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);
        $this->hasMany('RetribusiDetail', [
            'foreignKey' => 'permohonan_izin_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);
        $this->hasMany('Izin', [
            'foreignKey' => 'permohonan_izin_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id'
        ]);

        $this->belongsTo('Unit', [
            'foreignKey' => 'unit_id'
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'tgl_pengajuan' => 'new',
                    'tgl_dibuat' => 'new',
                    'tgl_diubah' => 'existing',
                ]
            ]
        ]);

        $this->addBehavior('Muffin/Footprint.Footprint', [
            'events' => [
                'Model.beforeSave' => [
                    'dibuat_oleh' => 'new',
                    'diubah_oleh' => 'existing',
                ]
            ],
            'propertiesMap' => [
                'dibuat_oleh' => '_footprint.username',
                'diubah_oleh' => '_footprint.username',
            ],
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('no_permohonan', 'create')
            ->notEmpty('no_permohonan');

        $validator
            ->allowEmpty('keterangan');

        $validator
            ->allowEmpty('jenis_permohonan');

        $validator
            ->allowEmpty('lokasi_izin');

        $validator
            ->allowEmpty('no_izin_lama');

        $validator
            ->date('tgl_pengajuan')
            ->allowEmpty('tgl_pengajuan');

        $validator
            ->date('tgl_selesai')
            ->allowEmpty('tgl_selesai');

        $validator
            ->allowEmpty('status');

        $validator
            ->allowEmpty('jenis_proyek_id');

        $validator
            ->decimal('nilai_investasi')
            ->allowEmpty('nilai_investasi');

        $validator
            ->decimal('target_pad')
            ->allowEmpty('target_pad');

        $validator
            ->numeric('jumlah_tenaga_kerja')
            ->allowEmpty('jumlah_tenaga_kerja');

        $validator
            ->allowEmpty('dibuat_oleh');

        $validator
            ->date('tgl_dibuat')
            ->allowEmpty('tgl_dibuat');

        $validator
            ->allowEmpty('diubah_oleh');

        $validator
            ->date('tgl_diubah')
            ->allowEmpty('tgl_diubah');

        $validator
            ->numeric('nilai_retribusi')
            ->allowEmpty('nilai_retribusi');

        $validator
            ->allowEmpty('status_permohonan');

        $validator
            ->allowEmpty('status_penetapan');

        $validator
            ->integer('is_main')
            ->notEmpty('is_main');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['pemohon_id'], 'Pemohon'));
        $rules->add($rules->existsIn(['perusahaan_id'], 'Perusahaan'));
        $rules->add($rules->existsIn(['jenis_izin_id'], 'JenisIzin'));
        $rules->add($rules->existsIn(['proses_permohonan_id'], 'ProsesPermohonan'));
        return $rules;
    }

    public function beforeSave(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        parent::beforeSave($event, $entity, $options);

        if($entity->isNew()){
            // flaging status active permohonan
            $jenisIzinTable = TableRegistry::get('jenis_izin');
            $jenisIzin = $jenisIzinTable->get($entity->jenis_izin_id);

            $entity->is_active = $jenisIzin['default_active'];
            $entity->status = self::STATUS_HOLD;
        }
        if (!isset($entity->is_main)) { // If is_main is not set, set default 1
            $entity->is_main = 1;
        }

        if (isset($entity->lokasi_izin)) {
            $entity->lokasi_izin = strip_tags($entity->lokasi_izin, null);
        }

        return;
    }

    public function afterSave(Event $event, PermohonanIzin $entity)
    {
        $mapperTable = TableRegistry::get('Mapper');
        $datatabelTable = TableRegistry::get('Datatabel');

        // Get Template Data setting for Pemohon
        $allDatatabelPemohon = $datatabelTable->find()
            ->select(['id'])
            ->where(['nama_datatabel' => 'pemohon', 'use_mapper' => 1]);

        if ($allDatatabelPemohon && $entity->pemohon_id) {
            $datatabelPemohon = $allDatatabelPemohon->first();

            // Create Mapper
            if ($datatabelPemohon) {
                $mapperTable->createMapper($datatabelPemohon->id, $entity->pemohon_id, $entity->id);
            }
        }

        // Get Template Data setting for Perusahaan
        $allDatatabelPerusahaan = $datatabelTable->find()
            ->select(['id'])
            ->where(['nama_datatabel' => 'perusahaan', 'use_mapper' => 1]);

        if ($allDatatabelPerusahaan && $entity->perusahaan_id) {
            $datatabelPerusahaan = $allDatatabelPerusahaan->first();

            // Create Mapper
            if ($datatabelPerusahaan) {
                $mapperTable->createMapper($datatabelPerusahaan->id, $entity->perusahaan_id, $entity->id);
            }
        }

        // Get Template Data setting for JenisIzin
        $allDatatabelJenisIzin = $datatabelTable->find()
            ->select(['id'])
            ->where(['nama_datatabel' => 'jenis_izin', 'use_mapper' => 1]);

        if ($allDatatabelJenisIzin && $entity->jenis_izin_id) {
            $datatabelJenisIzin = $allDatatabelJenisIzin->first();

            // Create Mapper
            if ($datatabelJenisIzin) {
                $mapperTable->createMapper($datatabelJenisIzin->id, $entity->jenis_izin_id, $entity->id);
            }
        }

        /**
         * Jika rekomendasi
         */
        $this->permohonanProses($entity);

        return;
    }

    /**
     * Function to Generate Izin Paralel
     * This function is not called in afterSave to keep data of AlurProsesService::generateProsesPermohonan for Main Permohonan saved first
     * @param PermohonanIzin $permohonanIzin
     */
    public function generateIzinParalel(PermohonanIzin $permohonanIzin)
    {
        if ($permohonanIzin->is_main == 1) {
            // Get Jenis Izin
            $jenisIzinTable = TableRegistry::get('JenisIzin');
            $jenisIzin = $jenisIzinTable->find('all', [
                'fields' => ['id'],
                'contain' => ['IzinParalel'],
                'conditions' => ['id' => $permohonanIzin->jenis_izin_id]
            ])->first();

            if ($jenisIzin) {
                foreach ((array)$jenisIzin->izin_paralel as $izinParalel) {
                    // Get the Main Permohonan Izin Data and remove entity that will not be duplicated
                    $mainData = $permohonanIzin->toArray();
                    $mainData['is_main'] = 0;
                    $mainData['jenis_izin_id'] = $izinParalel->izin_paralel_id;

                    unset($mainData['id']);
                    unset($mainData['jenis_izin']);
                    unset($mainData['pemohon']);
                    unset($mainData['perusahaan']);

                    // Build New Entity and Save
                    $permohonanParalel = $this->newEntity($mainData, [
                        'associated' => ['Persyaratan']
                    ]);

                    // Save Permohonan Paralel and Generate Alur Proses
                    if ($this->save($permohonanParalel)) {
                        AlurProsesService::generateProsesPermohonan($permohonanParalel->id);
                    }
                }
            }
        }
    }


    public function getToken()
    {
        $data = array('securityKey' => array('user_akses' => 'OSS010', 'pwd_akses' => '68c52ed46b02d35863b15c96c7486412'));
        $token = null;

        $sincs = TableRegistry::get('data_sinc');
        $sinc = $sincs->find()->select();
        $sinc->where(['masa_berlaku >=' => date('Y-m-d h:i:sa')]);

        foreach ($sinc as $s) {
            $token = $s['aktif_token'];
            $this->source_url = $s['source_url'];
        }

        if (empty($token)) {

            $data = null;
            $sinc_login = TableRegistry::get('data_sinc');
            $login = $sincs->find()->select();
            $login->where(['id' => 1]);

            foreach ($login as $l) {
                $data = array('securityKey' => array('user_akses' => $l['user_akses'], 'pwd_akses' => $l['pwd_akses']));
                $this->source_url = $l['source_url'];
            }

            if (!empty($data)) {
                try {
                    $http = new Client();
                    $response = $http->post($this->source_url . '/sendSecurityKey', json_encode($data), [
                        'type' => 'json',
                        'auth' => ['username' => '', 'password' => '']
                    ]);
                    if ($response->code != 200) {
                        $this->setOSSLog(null, 'Get Token eror', $response->body);
                    } else {
                        $tblLog = TableRegistry::get('data_sinc');
                        $log = $tblLog->get(1);
                        $log['aktif_token'] = $response->json['responsendSecurityKey']['key'];
                        $log['masa_berlaku'] = $response->json['responsendSecurityKey']['masa_berlaku'];
                        $tblLog->save($log);
                        $token = $response->json['responsendSecurityKey']['key'];
                    }
                } catch (Exception $e) {
                    $this->setOSSLog(null, 'Get Token eror', $e->getMessage);
                }

            }

        }
        return $token;
    }

    public function sendStatus($entity)
    {
	return;
        $permohonanTbl = TableRegistry::get('permohonan_izin');
        $permohonanData = $permohonanTbl->find()->select();
        $permohonanData->where(['permohonan_izin.id' => $entity->id, 'nib_id is not' => null]);

        $data = null;
        $token = $this->getToken();

        foreach ($permohonanData as $pd) {
            $data = array('IZINSTATUS' => array(
                'nib' => $pd['nib_id'],
                'oss_id' => $pd['oss_id'],
                'kd_izin' => $pd['kode_oss'],
                'kd_instansi' => $pd['instansi_id'],
                'kd_status' => 40,
                'tgl_status"' => '',
                'nip_status' => '',
                'nama_status' => '',
                'keterangan' => '',
                'status_izin' => ''
            ));
            $tblLog = TableRegistry::get('oss_log');
            $log = $tblLog->newEntity();
            $log['nib'] = $token;
            $log['status'] = 'SEND STATUS';
            $log['log'] = json_encode($data);
            $tblLog->save($log);

            $http = new Client();
            $response = $http->post(
                $this->source_url . '/receiveLicenseStatus',
                json_encode($data),
                [
                    'type' => 'json',
                    'headers' => ['OSS-API-KEY' => $this->getToken()],
                    'auth' => ['username' => '', 'password' => '']
                ]
            );

            if ($response->code != 200) {
                $this->setOSSLog(null, 'Send Status > eror', $response->body);
            } else {
                $this->setOSSLog(null, 'Send Status > Ok', $response->body);
            }
            //}
        }
    }

    public function sendStatusFinal($entity)
    {
	return;
        $permohonanTbl = TableRegistry::get('permohonan_izin');
        $permohonanData = $permohonanTbl->find()->select();
        $permohonanData->where(['permohonan_izin.id' => $entity->id, 'nib_id is not' => null]);
        $data = null;
        $token = $this->getToken();

        foreach ($permohonanData as $pd) {
            if (!is_null($pd['nib_id'])) {
                $data = array('IZINFINAL' => array(
                    'nib' => $pd['nib_id'],
                    'oss_id' => $pd['oss_id'],
                    'kd_izin' => $pd['kode_oss'],
                    'nomor_izin' => $pd['no_permohonan'],
                    'tgl_terbit_izin' => $pd['mulai_berlaku'],
                    'tgl_berlaku_izin' => $pd['mulai_berlaku'],
                    'nama_ttd' => '',
                    'nip_ttd' => '',
                    'jabatan_ttd' => '',
                    'status_izin' => 50,
                    'file_izin' => '',
                    'data_pnbp' => array(
                        'kd_akun' => '',
                        'kd_penerimaan' => '',
                        'nominal' => ''
                    )
                ));

                $http = new Client();
                $response = $http->post(
                    $this->source_url . '/receiveLicense',
                    json_encode($data),
                    [
                        'type' => 'json',
                        'headers' => ['OSS-API-KEY' => $token],
                        'auth' => ['username' => '', 'password' => '']
                    ]
                );

                $this->setOSSLog($pd['nib_id'], 'SEND DATA', null);

                if ($response->code != 200) {
                    $this->setOSSLog($pd['nib_id'], 'Send Status Final > Error', $response->body);
                } else {
                    $this->setOSSLog($pd['nib_id'], 'Send Status Final > Ok', $response->body);
                }
            }
        }
    }
    
    public function permohonanProses($entity)
    {
        $connection = ConnectionManager::get('default');

        if ($entity->isDirty('status_rekomendasi') === true && $entity->status_rekomendasi && $entity->status_rekomendasi == self::STATUS_REKOMENDASI) {
            $this->prosesRekomendasi($entity);
        }

        /**
         * jalankan jika permohonan tidak di rekomendasikan & ditetapkan
         * close proses
         * close permohonan
         */
        if (($entity->isDirty('status_rekomendasi') === true && $entity->status_rekomendasi && $entity->status_rekomendasi != self::STATUS_REKOMENDASI)) {
            AlurProsesService::switchStatus($entity);
        }

        /**
         * jalankan jika permohonan di tetapkan
         * close pindahkan data permohonan ke tabel izin menandakan izin terbentuk
         */
        if ($entity->isDirty('status_penetapan') === true && $entity->status_penetapan === self::STATUS_DITETAPKAN) {
            $this->penetapan($entity);
            //Update status jika sudah di tetapkan
            $this->sendStatusFinal($entity);
        } elseif ($entity->isDirty('status_penetapan') === true && $entity->status_penetapan != self::STATUS_DITETAPKAN) {
            //update jenis proses to proses dan menunggu
            AlurProsesService::switchTodrop($entity);
            //End update jenis proses to proses dan menunggu
            $this->sendStatusFinal($entity);
        }

        /**
         * Update status setiap proses berjalan
         */
        if ($entity->isDirty('status_penetapan') === false && $entity->isDirty('proses_permohonan_id') === true) {
            $this->sendStatus($entity);
        }
    }
    
    public function prosesRekomendasi($entity)
    {
        $connection = ConnectionManager::get('default');
        $prosesBerjalan = null;

        // Cek baru di set atau tidak
        $status_rekomendasi = $connection->execute("select status_rekomendasi from permohonan_izin where id = " . $entity->id)->fetchAll('assoc');

        if (is_null($entity->getOriginal('status_rekomendasi'))) {
            //ambil id proses yang sedang berjalan
            $prosesBerjalan = $connection->execute(
                "SELECT MAX(id) as id FROM proses_permohonan WHERE permohonan_izin_id = " . $entity->id . " AND " .
                "status = '" . AlurProsesService::STATUS_PROSES . "'"
            )->fetchAll('assoc');
        } else {
            $prosesBerjalan = $connection->execute(
                "SELECT MAX(id) as id FROM proses_permohonan WHERE permohonan_izin_id = " . $entity->id . " AND " .
                "status = '" . AlurProsesService::STATUS_SELESAI . "'"
            )->fetchAll('assoc');
        }

        $data['status'] = AlurProsesService::STATUS_PROSES;
        $data['tgl_diubah'] = date('Y-m-d');

        if ($prosesBerjalan) {
            $connection->update('proses_permohonan', $data, [
                'id' => $prosesBerjalan[0]["id"],
                'permohonan_izin_id' => $entity->id
            ]);
        }

        // update proses nyatakan proses Menunggu
        $data['status'] = AlurProsesService::STATUS_MENUNGGU;
        $data['tgl_diubah'] = date('Y-m-d');

        if ($prosesBerjalan) {
            $connection->update('proses_permohonan', $data, [
                'id >' => $prosesBerjalan[0]["id"],
                'permohonan_izin_id' => $entity->id
            ]);
        }

        $data_permohonan['status'] = AlurProsesService::STATUS_PROSES;
        $data_permohonan['tgl_selesai'] = null;
        $data_permohonan['proses_permohonan_id'] = $prosesBerjalan[0]["id"];
        $data_permohonan['status_rekomendasi'] = $entity->status_rekomendasi;

        $connection->update('permohonan_izin', $data_permohonan, ['id' => $entity->id]);
    }
    
    public function penetapan($entity)
    {
        $connection = ConnectionManager::get('default');
        $data_permohonan['tgl_selesai'] = date('Y-m-d');
        $connection->update('permohonan_izin', $data_permohonan, ['id' => $entity->id]);

        $tglTerakhir = $connection->execute("
                SELECT
                    date(current_date + (masa_berlaku_izin*
                    case satuan_masa_berlaku
                        when 'T' then 365
                        when 'B' then 30
                        else 1
                        end
                    )) as akhir_berlaku
                FROM jenis_pengajuan b
                JOIN permohonan_izin c ON b.jenis_izin_id = c.jenis_izin_id
                WHERE b.jenis_pengajuan = c.jenis_permohonan AND c.id =" . $entity->id)
        ->fetchAll('assoc');

        $connection = ConnectionManager::get('default');
        $data_izin["no_izin"] = $entity->no_izin;
        $data_izin["no_izin_sebelumnya"] = $entity->no_izin_sebelumnya;
        $data_izin["permohonan_izin_id"] = $entity->id;
        $data_izin["jenis_izin_id"] = $entity->jenis_izin_id;
        $data_izin["pemohon_id"] = $entity->pemohon_id;
        $data_izin["perusahaan_id"] = $entity->perusahaan_id;
        $data_izin["keterangan"] = $entity->keterangan;
        $data_izin["mulai_berlaku"] = date('Y-m-d');
        $data_izin["instansi_id"] = $entity->instansi_id;
        $data_izin["permohonan_izin_id"] = $entity->id;

        if (!empty($tglTerakhir)) {
            $data_izin["akhir_berlaku"] = $tglTerakhir[0]['akhir_berlaku'];
        }

        $connection->insert('izin', $data_izin);

        $jenisIzinTable = TableRegistry::get('jenis_izin');
        $jenisIzin = $jenisIzinTable->get($entity->jenis_izin_id);
        
        if(!empty($jenisIzin['jenis_dokumen_id'])){
            $dokumenPemohonTabel = TableRegistry::get('dokumen_pemohon');
            $dokumenPemohon = $dokumenPemohonTabel->newEntity();
            
            $dokumenPemohon['jenis_dokumen_id'] = $jenisIzin['jenis_dokumen_id'];
            $dokumenPemohon['pemohon_id'] = $entity->pemohon_id;
            $dokumenPemohon['no_dokumen'] = $entity->no_izin;
            $dokumenPemohon['awal_berlaku'] = $data_izin["mulai_berlaku"];
            $dokumenPemohon['akhir_berlaku'] = $tglTerakhir[0]['akhir_berlaku'];
            $dokumenPemohon['lokasi_dokumen'] = '#';
            $dokumenPemohon['object_id'] = $entity->id;

            $dokumenPemohonTabel->save($dokumenPemohon);
        }
    }
    
    public function setOSSLog($nib_id = null, $status = null, $keterangan = null)
    {
        $tblLog = TableRegistry::get('oss_log');
        $log = $tblLog->newEntity();
        $log['nib'] = $nib_id;
        $log['status'] = $status;
        $log['log'] = $keterangan;
        $tblLog->save($log);
    }
}
