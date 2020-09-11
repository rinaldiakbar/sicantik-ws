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
use Cake\Datasource\ConnectionManager;
use Cake\Http\Client;

class OssProsesService extends AuthService
{
    const STATUS_REKOMENDASI = 'R';
    const STATUS_PENETAPAN = 'ditetapkan';

    /*
     * Fungsi interaksi dengan oss
     */
    public static function setOSSLog($nib_id = null,$status = null, $keterangan = null)
    {
        $logTable = TableRegistry::get('oss_log');

        if (is_null($this->log_id)) {
            $log = $logTable->newEntity();
            $log['nib'] = $nib_id;
            $log['status'] = $status;
            $this->log_keterangan = [];
            $this->log_keterangan[0] = array($status=>$keterangan);
            $log['log'] = json_encode($this->log_keterangan);
            $logTable->save($log);

            // inisial penerimaan sudah terjadi?
            $cekLog = $logTable->find()->select();
            $cekLog->where(['nib'=>$nib_id, 'status'=>'Receive NIB']);

            if ($cekLog->count() > 0 && $status != 'Receive NIB') {
                $this->log_id = $log['id'];
            }
        } else {
            $log = $logTable->get($this->log_id);
            $this->log_keterangan[count($this->log_keterangan)] = array($status=>$keterangan);
            $log['log'] = json_encode($this->log_keterangan);
            $logTable->save($log);
        }
    }

    public function forwardCL($data = null)
    {
        $cl = [];
        $instansi_id = null;
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
                    $this->setStatus($cl_instansi[0]['kd_izin'], false, $response->code . "|" . $response->body);
                    $this->setOSSLog($this->nib_data['dataNIB']['nib'], 'ERROR Forwarding', $response->code . "|" . $response->body);
                } else {
                    $data = $response->body;
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

                    $this->setOSSLog($this->nib_data['dataNIB']['nib'], 'Forwarding OK', $response->body);
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
        $data = array(
            'IZINSTATUS' => [
                'nib' => $data['nib_id'],
                'oss_id' => $data['oss_id'],
                'kd_izin' => $data['kd_izin'],
                'kd_instansi' => $data['kd_instansi'],
                'kd_status' => $data['kd_status'],
                'tgl_status' => $data['tgl_status'],
                'nip_status' => $data['nip_status'],
                'nama_status' => $data['nama_status'],
                'keterangan' => $data['keterangan'],
                'status_izin' => $data['status_izin']
            ]
        );

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
        $response->body($response->body);
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
            $this->setOSSLog($data['nib_id'], 'Forward Status Final>error', $response->body);
        } else {
            $this->setOSSLog($data['nib_id'], 'Forward Status Final>ok', $response->body);
        }
    }

    public function getToken()
    {
        $data = null;
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
                    $log['log'] = $response->body;
                    $tblLog->save($log);
                    $this->setOSSLog(null, 'Send Security>Error', $response->body);
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
    
    public static function receivePostAudit($nib, $id_proyek, $oss_id, $id_izin, $kd_izin, $kd_daerah, $status_validasi, $tgl_validasi, $keterangan)
    {

        $data = [
            "receivePostAudit" => [
                "nib" => $nib,
                "id_proyek" => $id_proyek,
                "oss_id" => $oss_id,
                "id_izin" => $id_izin,
                "kd_izin" => $kd_izin,
                "kd_daerah" => $kd_daerah,
                "status_validasi" => $status_validasi,
                "tgl_validasi" => $tgl_validasi,
                "keterangan" => $keterangan
            ]
        ];

        $this->setOSSLog($nib, 'receivePostAudit', $data);
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
            $this->setOSSLog($nib_id, 'receivePostAudit>error', $response->body);
        } else {
            $this->setOSSLog($nib, 'receivePostAudit>ok', $response->body);
        }
    }

}
