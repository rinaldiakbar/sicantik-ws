<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\Perusahaan;

/**
 * Perusahaan Controller
 *
 * @property \App\Model\Table\PerusahaanTable $Perusahaan
 */
class PerusahaanController extends ApiController
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

        $conditions['OR'] = [
            'LOWER(Perusahaan.nama_perusahaan) ILIKE' => '%' . $this->_apiQueryString . '%',
            'LOWER(Perusahaan.npwp) ILIKE' => '%' . $this->_apiQueryString . '%',
            'LOWER(Perusahaan.no_register) ILIKE' => '%' . $this->_apiQueryString . '%'
        ];

        // Get pemohon_id
        $pemohonId = $this->getPemohonIdFromQueryStringOrSession();
        if ($pemohonId) {
            $conditions['Perusahaan.pemohon_id'] = $pemohonId;
        }

        $this->paginate = [
            'fields' => [
                'id', 'nama_perusahaan', 'npwp', 'no_register', 'jenis_perusahaan'
            ],
            'conditions' => $conditions
        ];
        $perusahaan = $this->paginate($this->Perusahaan);
        $paging = $this->request->params['paging']['Perusahaan'];
        $perusahaan = $this->addRowNumber($perusahaan);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $perusahaan,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Perusahaan id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $perusahaan = $this->Perusahaan->get($id, [
            'fields' => [
                'id', 'nama_perusahaan', 'npwp', 'no_register', 'jenis_usaha_id', 'bidang_usaha_id',
                'jenis_perusahaan', 'jumlah_pegawai', 'nilai_investasi', 'no_tlp', 'fax', 'email',
                'alamat', 'desa_id', 'kecamatan_id', 'kabupaten_id', 'provinsi_id', 'kode_pos',
                'pemohon_id'
            ],
            'contain' => [
                'Desa' => [
                    'fields' => ['id', 'kode_daerah', 'nama_daerah']
                ],
                'Kecamatan' => [
                    'fields' => ['id', 'kode_daerah', 'nama_daerah']
                ],
                'Kabupaten' => [
                    'fields' => ['id', 'kode_daerah', 'nama_daerah']
                ],
                'Provinsi' => [
                    'fields' => ['id', 'kode_daerah', 'nama_daerah']
                ],
                'BidangUsaha' => [
                    'fields' => ['id', 'kode', 'keterangan', 'BidangUsahaPerusahaan.perusahaan_id']
                ],
                'JenisUsaha' => [
                    'fields' => ['id', 'kode', 'keterangan', 'JenisUsahaPerusahaan.perusahaan_id']
                ],
            ]
        ]);

        $this->setResponseData($perusahaan, $success, $message);
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

        $perusahaan = $this->Perusahaan->newEntity();
        if ($this->request->is('post')) {
            $perusahaan = $this->Perusahaan->patchEntity($perusahaan, $this->request->data);
            if ($this->Perusahaan->save($perusahaan)) {
                $success = true;
                $message = __('perusahaan berhasil disimpan.');
            } else {
                $message = __('perusahaan tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($perusahaan, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Perusahaan id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $perusahaan = $this->Perusahaan->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $perusahaan = $this->Perusahaan->patchEntity($perusahaan, $this->request->data);
            if ($this->Perusahaan->save($perusahaan)) {
                $success = true;
                $message = __('perusahaan berhasil disimpan.');
            } else {
                $message = __('perusahaan tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($perusahaan, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Perusahaan id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $perusahaan = $this->Perusahaan->get($id);
        if ($this->Perusahaan->delete($perusahaan)) {
            $success = true;
            $message = __('perusahaan berhasil dihapus.');
        } else {
            $message = __('perusahaan tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }

    public function getJenisList()
    {
        $success = true;
        $message = '';

        $jenisPerusahaanList = [];
        $jenisPerusahaanList[] = array(
            'kode' => 'Perusahaan perorangan (PO)',
            'label' => 'Perusahaan perorangan (PO)',
        );
        $jenisPerusahaanList[] = array(
            'kode' => 'Firma (Fa)',
            'label' => 'Firma (Fa)',
        );
        $jenisPerusahaanList[] = array(
            'kode' => 'Perseroan Komanditer (CV)',
            'label' => 'Perseroan Komanditer (CV)',
        );
        $jenisPerusahaanList[] = array(
            'kode' => 'Perseroan Terbatas (PT)',
            'label' => 'Perseroan Terbatas (PT)',
        );
        $jenisPerusahaanList[] = array(
            'kode' => 'Perseroan Terbatas Negara (Persero)',
            'label' => 'Perseroan Terbatas Negara (Persero)',
        );
        $jenisPerusahaanList[] = array(
            'kode' => 'Perusahaan Daerah (PD)',
            'label' => 'Perusahaan Daerah (PD)',
        );
        $jenisPerusahaanList[] = array(
            'kode' => 'Perusahaan Negara Umum (Perum)',
            'label' => 'Perusahaan Negara Umum (Perum)',
        );
        $jenisPerusahaanList[] = array(
            'kode' => 'Perusahaan Negara Jawatan (Perjan)',
            'label' => 'Perusahaan Negara Jawatan (Perjan)',
        );
        $jenisPerusahaanList[] = array(
            'kode' => 'Koperasi',
            'label' => 'Koperasi',
        );
        $jenisPerusahaanList[] = array(
            'kode' => 'Yayasan',
            'label' => 'Yayasan',
        );

        $data = array(
            'items' => $jenisPerusahaanList
        );

        $this->setResponseData($data, $success, $message);
    }
}
