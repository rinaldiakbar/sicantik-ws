<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\JenisPengajuan;

/**
 * JenisPengajuan Controller
 *
 * @property \App\Model\Table\JenisPengajuanTable $JenisPengajuan
 */
class JenisPengajuanController extends ApiController
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
        $this->paginate = [
            'conditions' => [
                'OR' => [
                    'JenisPengajuan.jenis_pengajuan ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ],
            'contain' => ['JenisIzin', 'AlurProses']
        ];
        $jenisPengajuan = $this->paginate($this->JenisPengajuan);
        $paging = $this->request->params['paging']['JenisPengajuan'];
        $jenisPengajuan = $this->addRowNumber($jenisPengajuan);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $jenisPengajuan,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    public function getJenisList()
    {
        $success = true;
        $message = '';

        $jenisList = [];
        $jenisList[] = array(
            'kode' => 'Baru',
            'label' => 'Baru',
        );
        $jenisList[] = array(
            'kode' => 'Perpanjangan',
            'label' => 'Perpanjangan',
        );
        $jenisList[] = array(
            'kode' => 'Daftar Ulang',
            'label' => 'Perubahan',
        );

        $data = array(
            'items' => $jenisList
        );

        $this->setResponseData($data, $success, $message);
    }

    public function getSatuanList()
    {
        $success = true;
        $message = '';

        $satuanList = [];
        $satuanList[] = array(
            'kode' => 'H',
            'label' => 'Hari',
        );
        $satuanList[] = array(
            'kode' => 'B',
            'label' => 'Bulan',
        );
        $satuanList[] = array(
            'kode' => 'T',
            'label' => 'Tahun',
        );

        $data = array(
            'items' => $satuanList
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Jenis Pengajuan id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $jenisPengajuan = $this->JenisPengajuan->get($id, [
            'contain' => ['JenisIzin', 'AlurProses']
        ]);

        $this->setResponseData($jenisPengajuan, $success, $message);
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

        $jenisPengajuan = $this->JenisPengajuan->newEntity();
        if ($this->request->is('post')) {
            $jenisPengajuan = $this->JenisPengajuan->patchEntity($jenisPengajuan, $this->request->data);
            if ($this->JenisPengajuan->save($jenisPengajuan)) {
                $message = __('jenis pengajuan berhasil disimpan.');
                $success = true;
            } else {
                $message = __('jenis pengajuan tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($jenisPengajuan, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Jenis Pengajuan id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $jenisPengajuan = $this->JenisPengajuan->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $jenisPengajuan = $this->JenisPengajuan->patchEntity($jenisPengajuan, $this->request->data);
            if ($this->JenisPengajuan->save($jenisPengajuan)) {
                $message = __('jenis pengajuan berhasil disimpan.');
                $success = true;
            } else {
                $message = __('jenis pengajuan tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($jenisPengajuan, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Jenis Pengajuan id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';

        $this->request->allowMethod(['post', 'delete']);
        $jenisPengajuan = $this->JenisPengajuan->get($id);
        if ($this->JenisPengajuan->delete($jenisPengajuan)) {
            $message = __('jenis pengajuan berhasil dihapus.');
            $success = true;
        } else {
            $message = __('jenis pengajuan tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData(array(), $success, $message);
    }
}
