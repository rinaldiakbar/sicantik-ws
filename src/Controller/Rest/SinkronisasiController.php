<?php

namespace App\Controller\Rest;

use Cake\ORM\TableRegistry;
use Cake\Http\Client;
use App\Service\AlurProsesService;

class SinkronisasiController extends RestController
{
    public function initialize()
    {
        parent::initialize();
        $this->autoRender = false;
        $this->Auth->allow(['postNIB', 'postData', 'sendStatus', 'sendStatusFinal', 'testSend']);
        $this->status = true;
        $this->keterangan = [];
        $this->idx = -1;
        $this->log_id = null;
        $this->log_keterangan = null;
        $this->withReturn = true;

    }

    public function index()
    {
    }

    /**
     * Penampungan ceklist
     * Terbentuk baik di proses internal ataupun lempar keluar
     */
    public function setToChecklist($nibData = null, $instansiId = null, $jenisIzinId = null)
    {
        $clTbl = TableRegistry::get('oss_checklist');
        $clEnt = $clTbl->newEntity();

        $clEnt['nib_id'] = $this->nib_id;
        $clEnt['oss_id'] = $this->oss_id;
        $clEnt['kd_izin'] = $nibData['kd_izin'];
        //$clEnt['kd_dokumen'] = $nibData['kd_dokumen'];
        $clEnt['nama_izin'] = $nibData['nama_izin'];
        $clEnt['instansi'] = $nibData['instansi'];
        $clEnt['flag_checklist'] = $nibData['flag_checklist'];
        $clEnt['instansi_id'] = $instansiId;
        $clEnt['jenis_izin_id'] = $jenisIzinId;

        $clTbl->save($clEnt);
        if (is_null($jenisIzinId)) {
            $this->forward = true;
        }
    }

    /**
     * This will be called via eval from postData.
     * @param null $nibData
     */
    private function permohonan($nibData = null)
    {
        $instansiId = null;
        $permohonan = null;
        $jenisIzinId = null;

        if ($nibData['flag_checklist'] == 'Y') {
            $tmp = substr($nibData['kd_daerah'], 0, 4);

            if (substr($tmp, 2, 2) != "00") {
                $kab = substr($tmp, 0, 2) . "." . substr($tmp, 2, 2);
            } else {
                $kab = substr($tmp, 0, 2);
            }

            $jenis_izin = substr($nibData['kd_izin'], 9, 3);
            $InstansiTbl = TableRegistry::get('unit');
            $InstansiData = $InstansiTbl->find()->select();

            if (substr($nibData['kd_daerah'], 0, 4) == '00000') {
                $InstansiData->where(['instansi_code' => substr($nibData['kd_izin'], 0, 3), 'tipe' => 'I']);
            } else {
                $InstansiData->where(['kode_daerah' => $kab, 'tipe' => 'I']);
            }

            $instansiId = $InstansiData->first()['id'];

            if (empty($instansiId)) {
                $tblLog = TableRegistry::get('oss_log');
                $log = $tblLog->newEntity();
                $this->setOSSLog($this->nib_id, 'Permohonan', 'Kode : ' . $kab . ' Instansi Id tidak di kenali');
                $this->status = false;
            }

            $jiTbl = TableRegistry::get('jenis_izin');
            $jiData = $jiTbl->find()->select();
            $jiData->where(['instansi_id' => $instansiId, 'kode_oss' => $jenis_izin]);
            $tipePemohon = empty($this->perusahaan_id) ? 'PERORANGAN' : 'PERUSAHAAN';

            if ($jiData->count() > 0) {
                foreach ($jiData as $ji) {
                    $jenisIzinId = $ji['id'];

                    $permohonan = AlurProsesService::registerPermohonanIzin(
                        ['pemohon_id' => $this->pemohon_id,
                            'perusahaan_id' => $this->perusahaan_id,
                            'jenis_izin_id' => $ji['id'],
                            'tipe_pemohon' => $tipePemohon,
                            'jenis_permohonan' => 'Baru',
                            'instansi_id' => $instansiId,
                            'nib_id' => $this->nib_id,
                            'oss_id' => $this->oss_id,
                            'kode_oss' => $nibData['kd_izin'],
                            'kd_izin' => $nibData['kd_izin'],
                            'dibuat_oleh' => 'OSS010',
                            'tgl_dibuat' => date('Y/m/d')
                        ]
                    );

                    if ($permohonan) {
                        $this->setOSSLog($this->nib_id, 'Permohonan', 'Permohonan dengan jenis izin ' . $nibData['kd_izin'] . ' Terbentuk di instansi ' . $instansiId . '|' . $this->nib_id);
                        $this->setStatus(
                            $nibData['kd_izin'],
                            true,
                            'Permohonan dengan jenis izin ' . $nibData['kd_izin'] . ' Terbentuk di daerah kode ' . $kab . '|' . $this->nib_id
                        );
                    } else {
                        $this->setOSSLog($this->nib_id, 'Error Permohonan', json_encode(AlurProsesService::getErrors()));

                        $this->status = false;
                        $this->setStatus($nibData['kd_izin'], false, json_encode(AlurProsesService::getErrors()));
                    }
                }
            } else {
                $this->setOSSLog($this->nib_id, 'Permohonan Tidak terbentuk', 'Jenis Izin : ' . $nibData['kd_izin'] . ' Belum Terdaftar di daerah ' . $kab);

                $this->status = false;
                $this->setStatus(
                    $nibData['kd_izin'],
                    $this->status,
                    'Jenis Izin : ' . $nibData['kd_izin'] . ' Belum Terdaftar di ' . $kab
                );
                $notExist = true;
                $this->setToChecklist($nibData, $instansiId, $jenisIzinId);
            }
        }
    }

    public function postNIB($sinc_id = null)
    {
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '2G');
        $this->source_url = null;
        $this->nib_id = null;
        $this->withReturn = false;
        $this->response = $this->response->withType('json');

        $data = $this->request->getData();
        $this->nib_data = $data;
        $this->setOSSLog($data['dataNIB']['nib'], 'Receive NIB', json_encode($this->request->getData()));

        $nibTbl = TableRegistry::get('nib');
        $nibData = $nibTbl->find()->select();
        $nibData->where(['nib'=>$data['dataNIB']['nib'], 'tipe_dokumen'=>$data['dataNIB']['tipe_dokumen']]);

        if ($nibData->count() > 0) {
            $response = $this->response->withStringBody(json_encode([
                'STATUS' => 2,
                'KETERANGAN' => 'NIB Sudah diproses'
            ]));

            return $response;
        } else {
            //cek tipe dokumennya
            //jika baru langsung proses
            //jika penolakan lsg penetapan drop
            /*
            (9:Original, 5:Update, 3:Pencabutan, 4:Pembatalan)
            */
            if($data['dataNIB']['tipe_dokumen'] == 3 || $data['dataNIB']['tipe_dokumen'] == 4 ){
                $permohonanIzinTbl = TableRegistry::get('permohonan_izin');
                $permohonanIzinTbl->updateAll(
                    ['status_penetapan' => 'ditolak', 'catatan_rekomendasi' => 'perubahan status dari oss'],
                    ['nib'=>$data['dataNIB']['nib']]
                );
            }else{
                $this->postData($sinc_id);
            }
        }
        if ($this->status) {
            $this->status = 1;
        } else {
            $this->status = 2;
        }

        $response = $this->response->withStringBody(
            json_encode([
                        'STATUS' => $this->status,
                        'KETERANGAN' => $this->keterangan
            ])
        );
        return $response;
    }

    public function postData($sinc_id = null, $fdata = null, $fkey = null, $fkeydata = null)
    {
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '2G');

        $key = null;
        $recData = null;
        $proses = null;
        $mainPost = false;
        $exist = 0;

        //screening berdasarkan pola data perlayer
        if (empty($fdata)) { //inisialisasi awal
            $data = $this->request->getData();
        } else { //data turunan
            $data = $fdata;
        }

        $sinchron = TableRegistry::get('data_sinc');
        $sinc = $sinchron->find()->select(['index' => 'index', 'key' => 'key', 'proses' => 'proses']);
        $sinc->where(['id' => $sinc_id]);

        foreach ($sinc as $s) {
            if (array_key_exists($s['index'], $data)){
                $recData = $data[$s['index']];
                $proses = $s['proses'];
                $key = !empty($fkey) ? $fkey : $s['key'];
            }
        }

        foreach ($recData as $idx => $val) { //validasi array atau objek
            $index = $idx;
            break;
        }

        if (is_string($index)) { // single row
            if (!empty($proses)) {
                // jalankan proses
                call_user_func(array($this, $proses), $recData[$index]);
            } else {
                $this->saveData($sinc_id, $recData, $key, $fkeydata);
            }
        } else { //multi row
            for ($i = 0; $i < count($recData);) {
                if (!empty($proses)) { // proses fungsi
                    call_user_func(array($this, $proses), $recData[$i]);
                } else {
                    $this->saveData($sinc_id, $recData[$i], $key, $fkeydata);
                }
                $i = $i + 1;
            }
        }

        if ($this->withReturn) {
            $response = $this->response->withStringBody(
                json_encode([
                    'STATUS' => $this->status,
                    'KETERANGAN' => $this->keterangan
                ])
            );
            return $response;
        }
    }

    public function saveData($sinc_id = null, $Data = null, $fkey = null, $fkeydata = null)
    {
        $keydata = null;

        if (!empty($Data)) {
            $columns = TableRegistry::get('data_sinc_detail');
            $column = $columns->find();
            $column->select(['tabel' => 'c.nama_datatabel', 'kolom' => 'b.data_kolom', 'rec_kolom' => 'data_sinc_detail.oss_kolom']);
            $column->innerjoin(['b' => 'data_kolom'], ['b.id = data_sinc_detail.data_kolom_id']);
            $column->innerjoin(['c' => 'datatabel'], ['c.id = b.datatabel_id']);
            $column->innerjoin(['d' => 'data_sinc'], ['d.id=data_sinc_detail.data_sinc_id']);

            $column->where(['data_sinc_id' => $sinc_id]);

            $column->order(['c.nama_datatabel']);
            $activeTable = null;
            $tables = null;

            foreach ($column as $c) { //screening maping per layer API
                $id = null;

                if ($activeTable != $c['tabel']) {
                    if (!empty($table)) {
                        if ($activeTable == 'pemohon' || $activeTable == 'perusahaan') {
                            $table['nib_id'] = $this->nib_id;
                        }
                        $this->setOSSLog($this->nib_id, 'Simpan data', 'Tabel : ' . $activeTable . ' data : ' . json_encode($table));

                        $tables->save($table);

                        $tableKey = substr($fkey, 0, strlen($fkey) - 3);
                        if ($tableKey == $activeTable) {
                            $fkeydata = $table['id'];
                        }

                        //Set perusahaan_id dan pemohon_id
                        if ($activeTable == 'perusahaan') {
                            $this->perusahaan_id = $table['id'];
                        } elseif ($activeTable == 'pemohon') {
                            $this->pemohon_id = $table['id'];
                        } elseif ($activeTable == 'nib') {
                            $this->nib_id = $table['nib'];
                            $this->oss_id = $table['oss_id'];
                        }
                    }
                    $activeTable = $c['tabel'];
                    $tables = TableRegistry::get($activeTable);
                    //Khusus table nib agar tidak di simpan ulang
                    if ($activeTable == 'nib' || $activeTable == 'pemohon' || $activeTable == 'perusahaan') {
                        $tnib = $tables->find()->select(['id']);
                        if ($activeTable == 'pemohon' || $activeTable == 'perusahaan') {
                            $tnib->where(['nib_id' => $this->nib_id]);
                        } else {
                            $tnib->where(['nib' => $Data['nib']]);
                        }
                        foreach ($tnib as $dnib) {
                            $id = $dnib['id'];
                        }
                    }
                    // Khusus table nib agar tidak di simpan ulang
                    if (!empty($id)) {
                        $table = $tables->get($id);
                    } else {
                        $table = $tables->newEntity();
                    }
                    if (!empty($fkeydata)) {
                        $table[$fkey] = $fkeydata;
                    }
                }
                /**parsing jenis dokumen */
                if (substr($c['rec_kolom'], 0, 8) == 'jenis_id') {
                    if ($Data[$c['rec_kolom']]=='01') {
                        $Data[$c['rec_kolom']]='KTP';
                    } else {
                        $Data[$c['rec_kolom']]='Paspor';
                    }
                }
                //Maping kolom
                $table[$c['kolom']] = $Data[$c['rec_kolom']];
            }

            if (!empty($table)) {
                if ($activeTable == 'pemohon' || $activeTable == 'perusahaan') {
                    $table['nib_id'] = $this->nib_id;
                }

                $this->setOSSLog('', 'Simpan data', 'Tabel : ' . $activeTable . ' data : ' . json_encode($table));

                $tables->save($table);

                $tableKey = substr($fkey, 0, strlen($fkey) - 3);

                if ($tableKey == $activeTable) {
                    $fkeydata = $table['id'];
                }

                // Set perusahaan_id dan pemohon_id
                if ($activeTable == 'perusahaan') {
                    $this->perusahaan_id = $table['id'];
                } elseif ($activeTable == 'pemohon') {
                    $this->pemohon_id = $table['id'];
                }
            }

            $childs = TableRegistry::get('data_sinc');
            $child = $childs->find();
            $child->select(['id' => 'id']);
            $child->where(['parent_id' => $sinc_id, 'parent_id is not' => null]);

            foreach ($child as $ch) { //screening ke child layer jika ada
                $this->postData($ch['id'], $Data, $fkey, $fkeydata);
            }
        }
    }
//Set status respon atas proses yang ada
    private function setStatus($kd_izin = null, $status = null, $uraian = null)
    {
        $this->idx = $this->idx + 1;
        $this->keterangan[$this->idx]['kd_izin'] = $kd_izin;
        if ($status) {
            $status = 1;
        } else {
            $status = 2;
        }
        $this->keterangan[$this->idx]['status'] = $status;
        $this->keterangan[$this->idx]['uraian'] = $uraian;
    }

    public function forwardCL($data = null)
    {
        $cl = array();
        $instansiId = null;
        $clTbl = TableRegistry::get('oss_checklist');
        $clData = $clTbl->find()->select([
            'id' => 'oss_checklist.id',
            'instansi_id' => 'oss_checklist.instansi_id',
            'kd_izin' => 'oss_checklist.kd_izin',
            'kd_dokumen' => 'oss_checklist.kd_dokumen',
            'nama_izin' => 'oss_checklist.nama_izin',
            'instansi' => 'oss_checklist.instansi',
            'flag_checklist' => 'oss_checklist.flag_checklist',
            'ws_url' => 'b.ws_url'
        ]);
        $clData->innerjoin(['b' => 'unit'], ['b.id = oss_checklist.instansi_id']);
        $clData->where(['nib_id' => $this->nib_id, 'jenis_izin_id is ' => null, 'oss_checklist.status_send is ' => null]);

        $clData->order(['instansi_id']);
        $cl = [];

        foreach ($clData as $cd) {
            if (!array_key_exists($cd['instansi_id'], $cl)) {
                $cl[$cd['instansi_id']] = array();
            }
            $idx = count($cl[$cd['instansi_id']]);

            $cl[$cd['instansi_id']][$idx]['instansi_id'] = $cd['instansi_id'];
            $cl[$cd['instansi_id']][$idx]['kd_izin'] = $cd['kd_izin'];
            $cl[$cd['instansi_id']][$idx]['kd_dokumen'] = $cd['kd_dokumen'];
            $cl[$cd['instansi_id']][$idx]['nama_izin'] = $cd['nama_izin'];
            $cl[$cd['instansi_id']][$idx]['instansi'] = $cd['instansi'];
            $cl[$cd['instansi_id']][$idx]['flag_checklist'] = $cd['flag_checklist'];
            $cl[$cd['instansi_id']][$idx]['ws_url'] = $cd['ws_url'];

            $clUpdate = $clTbl->get($cd['id']);
            $clUpdate['status_send'] = "true";
            $clTbl->save($clUpdate);
        }

        foreach ($cl as $cl_instansi) {
            if (!empty($cl_instansi[0]['ws_url'])) {
                $this->nib_data['dataNIB']['data_checklist'] = $cl_instansi;

                $this->setOSSLog($this->nib_data['dataNIB']['nib'], 'Inisial Forwarding', json_encode($this->nib_data));

                $http = new Client();
                $response = $http->post(
                    $cl_instansi[0]['ws_url'] /* 'http://localhost/dev/cl-api2/rest/sinkronisasi/testSend' */,
                    $this->nib_data,
                    [
                        'type' => 'application/json',
                        'headers' => ['OSS-API-KEY' => ''],
                        'auth' => ['username' => '', 'password' => ''],
                        'ssl_verify_peer' => false,
                        'ssl_verify_peer_name' => false
                    ]
                );

                if ($response->code != 200) {
                    $this->status = false;
                    $this->setStatus($cl_instansi[0]['kd_izin'], false, $response->code . "|" . $response->withStringBody);
                    $this->setOSSLog($this->nib_data['dataNIB']['nib'], 'ERROR Forwarding', $response->code . "|" . $response->withStringBody);
                } else {
                    $data = $response->withStringBody;
                    $kd_izin = null;
                    $status = null;
                    $keterangan = null;

                    if (property_exists($data, 'keterangan')) {
                        foreach ($data->keterangan as $d) {
                            if (property_exists($d, 'kd_izin')) {
                                $kd_izin = $d->kd_izin;
                            }

                            if (property_exists($d, 'status')) {
                                if ($d->status = 1) {
                                    $status = true;
                                } else {
                                    $status = false;
                                }
                            }

                            if (property_exists($d, 'uraian')) {
                                $keterangan = $d->uraian;
                            }

                            $this->setStatus($kd_izin, $status, $keterangan);
                        }
                    }

                    $this->setOSSLog($this->nib_data['dataNIB']['nib'], 'Forwarding OK', $response->withStringBody);
                }
            } else {
                $this->setStatus($cl_instansi[0]['instansi_id'], false, "tidak terhubung ke " . $cl_instansi[0]['kd_izin']);
                $this->setOSSLog($this->nib_data['dataNIB']['nib'], 'Forwarding Error', "Tidak ada url terdaftar");
            }
        }
    }

    public function sendStatus()
    {
        $data = $this->request->getData();
        $data = array('IZINSTATUS' => array(
            'nib' => $data['nib_id'],
            'oss_id' => $data['oss_id'],
            'kd_izin' => $data['kd_izin'],
            'kd_instansi' => $data['kd_instansi'],
            'kd_status' => $data['kd_status'],
            'tgl_status' => $data['tgl_status'],
            'nip_status' => $data['nip_status'],
            'nama_status' => $data['nama_status'],
            'keterangan' => $data['keterangan'],
            'status_izin' => $data['status_izin']));


        $this->setOSSLog($data['nib_id'], 'Forward Status', $data);
        $token = $this->getToken();
        $http = new Client();
        $resp = $http->post(
            $this->source_url . '/receiveLicenseStatus',
            json_encode($data),
            [
                'type' => 'json',
                'headers' => ['OSS-API-KEY' => $token],
                'auth' => ['username' => '', 'password' => '']
            ]
        );

        if ($resp->code != 200) {
            $this->setOSSLog($data['nib_id'], 'Forward Status Error', $resp->body);
        } else {
            $this->setOSSLog($data['nib_id'], 'Forward Status OK', $resp->body);
        }

        $response = $this->response->withType('json');
        $response = $response->withStatus($resp->code);
        $response->withStringBody($response->withStringBody);
        return $response;
    }

    public function sendStatusFinal()
    {
        $data = $this->request->getData();
        $data = [
            'IZINFINAL' => [
                'nib' => $data['nib_id'],
                'oss_id' => $data['oss_id'],
                'kd_izin' => $data['kd_izin'],
                'nomor_izin' => $data['nomor_izin'],
                'tgl_terbit_izin' => $data['tgl_terbit_izin'],
                'tgl_berlaku_izin' => $data['tgl_berlaku_izin'],
                'nama_ttd' => $data['nama_ttd'],
                'nip_ttd' => $data['nip_ttd'],
                'jabatan_ttd' => $data['jabatan_ttd'],
                'status_izin' => $data['status_izin'],
                'file_izin' => $data['file_izin'],
                'data_pnbp' => [
                    'kd_akun' => $data['data_pnbp']['kd_akun'],
                    'kd_penerimaan' => $data['data_pnbp']['kd_penerimaan'],
                    'nominal' => $data['data_pnbp']['nominal']
                ]
            ]
        ];


        $this->setOSSLog($data['nib_id'], 'Forward Status Final', $data);

        $token = $this->getToken();

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

        if ($response->code != 200) {
            $this->setOSSLog($data['nib_id'], 'Forward Status Final>error', $response->withStringBody);
        } else {
            $this->setOSSLog($data['nib_id'], 'Forward Status Final>ok', $response->withStringBody);
        }
    }

    public function getToken()
    {
        $data = null;/* array('securityKey' => array('user_akses' => 'OSS010', 'pwd_akses' => '68c52ed46b02d35863b15c96c7486412')); */
        $token = null;

        $sincs = TableRegistry::get('data_sinc');
        $sinc = $sincs->find()->select();
        $sinc->where(['masa_berlaku >=' => date('Y-m-d h:i:sa'), 'id' => 1]);

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
                $this->source_url = $l['pwd_akses'];
            }

            if (!empty($data)) {
                $http = new Client();
                $response = $http->post($this->source_url . '/sendSecurityKey', json_encode($data), ['type' => 'json',
                    'auth' => ['username' => '', 'password' => '']
                ]);
                if ($response->code != 200) {
                    $tblLog = TableRegistry::get('oss_log');
                    $log = $tblLog->newEntity();
                    $log['log'] = $response->withStringBody;
                    $tblLog->save($log);
                    $this->setOSSLog(null, 'Send Security>Error', $response->withStringBody);
                } else {
                    $tblLog = TableRegistry::get('data_sinc');
                    $log = $tblLog->get(1);
                    $log['aktif_token'] = $response->json['responsendSecurityKey']['key'];
                    $log['masa_berlaku'] = $response->json['responsendSecurityKey']['masa_berlaku'];
                    $tblLog->save($log);
                    $token = $response->json['responsendSecurityKey']['key'];
                }
            }
        }
        return $token;
    }

    public function testSend()
    {
        $tblLog = TableRegistry::get('oss_log');
        $log = $tblLog->newEntity();
        $log['nib'] = 'test';
        $log['status'] = 'TEST';
        $log['log'] = json_encode($this->request->getData());
        $tblLog->save($log);
    }
}
