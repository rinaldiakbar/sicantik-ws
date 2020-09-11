<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\Izin;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;

/**
 * Izin Controller
 *
 * @property \App\Model\Table\IzinTable $Izin
 */
class IzinController extends ApiController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $success = true;
        $message = '';

        $conditions = [
            'OR' => [
                'LOWER(Izin.no_izin) ILIKE' => '%' . $this->_apiQueryString . '%',
                'LOWER(Pemohon.nama) ILIKE' => '%' . $this->_apiQueryString . '%',
                'LOWER(Perusahaan.nama_perusahaan) ILIKE' => '%' . $this->_apiQueryString . '%',
                'LOWER(JenisIzin.jenis_izin) ILIKE' => '%' . $this->_apiQueryString . '%',
                'LOWER(LatestProsesPermohonan.nama_proses) ILIKE' => '%' . $this->_apiQueryString . '%'
            ]
        ];

        // Get pemohon_id
        $pemohonId = $this->getPemohonIdFromQueryStringOrSession();
        if ($pemohonId) {
            $conditions['Izin.pemohon_id'] = $pemohonId;
        }

        $this->paginate = [
            'fields' => [
                'id', 'no_izin', 'jenis_izin_id', 'instansi_id', 'pemohon_id', 'perusahaan_id',
                'mulai_berlaku', 'akhir_berlaku', 'status'
            ],
            'contain' => [
                'JenisIzin' => [
                    'fields' => ['jenis_izin']
                ], 
                'Instansi' => [
                    'fields' => ['nama']
                ],
                'Pemohon' => [
                    'fields' => ['nama', 'no_identitas']
                ], 
                'Perusahaan' => [
                    'fields' => ['nama_perusahaan', 'npwp']
                ],
                'PermohonanIzin' => [
                    'fields' => ['id']
                ],
                'PermohonanIzin.LatestProsesPermohonan' => [
                    'fields' => [
                        'id', 'tautan', 'jenis_proses_id', 'nama_proses'
                    ]
                ]
            ],
            'order' => [
                $this->_apiQueryOrder
            ],
            'conditions' => $conditions
        ];

        $izin = $this->paginate($this->Izin);
        $paging = $this->request->params['paging']['Izin'];
        $izin = $this->addRowNumber($izin);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $izin,
            'total_items' => $paging['count']
        );

        FrozenTime::setJsonEncodeFormat($this->_defaultDateFormat);  // For any immutable Date
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Izin id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $izin = $this->Izin->get($id, [
            'fields' => [
                'id', 'no_izin', 'jenis_izin_id', 'instansi_id', 'pemohon_id', 'perusahaan_id',
                'mulai_berlaku', 'akhir_berlaku', 'tgl_dibuat'
            ],
            'contain' => [
                'Pemohon' => [
                    'fields' => [
                        'id', 'nama', 'tipe_identitas', 'no_identitas', 'nama', 'tempat_lahir', 'tgl_lahir',
                        'jenis_kelamin', 'pekerjaan', 'perusahaan_id', 'no_tlp', 'no_hp', 'email', 'alamat',
                        'desa_id', 'kecamatan_id', 'kabupaten_id', 'provinsi_id', 'kode_pos'
                    ]
                ],
                'Pemohon.Desa' => [
                    'fields' => ['id', 'kode_daerah', 'nama_daerah']
                ],
                'Pemohon.Kecamatan' => [
                    'fields' => ['id', 'kode_daerah', 'nama_daerah']
                ],
                'Pemohon.Kabupaten' => [
                    'fields' => ['id', 'kode_daerah', 'nama_daerah']
                ],
                'Pemohon.Provinsi' => [
                    'fields' => ['id', 'kode_daerah', 'nama_daerah']
                ],
                'Perusahaan' => [
                    'fields' => [
                        'id', 'nama_perusahaan', 'npwp', 'no_register', 'jenis_perusahaan', 'jumlah_pegawai',
                        'nilai_investasi', 'no_tlp', 'fax', 'email', 'alamat', 'desa_id', 'kecamatan_id',
                        'kabupaten_id', 'provinsi_id', 'kode_pos'
                    ]
                ],
                'Perusahaan.Desa' => [
                    'fields' => ['id', 'kode_daerah', 'nama_daerah']
                ],
                'Perusahaan.Kecamatan' => [
                    'fields' => ['id', 'kode_daerah', 'nama_daerah']
                ],
                'Perusahaan.Kabupaten' => [
                    'fields' => ['id', 'kode_daerah', 'nama_daerah']
                ],
                'Perusahaan.Provinsi' => [
                    'fields' => ['id', 'kode_daerah', 'nama_daerah']
                ],
                'Perusahaan.BidangUsaha' => [
                    'fields' => ['id', 'kode', 'keterangan', 'BidangUsahaPerusahaan.perusahaan_id']
                ],
                'Perusahaan.JenisUsaha' => [
                    'fields' => ['id', 'kode', 'keterangan', 'JenisUsahaPerusahaan.perusahaan_id']
                ],
                'JenisIzin' => [
                    'fields' => ['id', 'jenis_izin', 'unit_id']
                ]
            ]
        ]);

        $this->setResponseData($izin, $success, $message);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $success = false;
        $message = '';

        $izin = $this->Izin->newEntity();
        if ($this->request->is('post')) {
            $izin = $this->Izin->patchEntity($izin, $this->request->data);
            if ($this->Izin->save($izin)) {
                $success = true;
                $message = __('izin berhasil disimpan.');
            } else {
                $message = __('izin tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($izin, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Izin id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $izin = $this->Izin->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $izin = $this->Izin->patchEntity($izin, $this->request->data);
            if ($this->Izin->save($izin)) {
                $success = true;
                $message = __('izin berhasil disimpan.');
            } else {
                $message = __('izin tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($izin, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Izin id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $izin = $this->Izin->get($id);
        if ($this->Izin->delete($izin)) {
            $success = true;
            $message = __('izin berhasil dihapus.');
        } else {
            $message = __('izin tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }
}
