<?php
namespace App\Controller\Api;

use App\Model\Entity\DokumenPendukung;

/**
 * DokumenPendukung Controller
 *
 * @property \App\Model\Table\DokumenPendukungTable $DokumenPendukung
 */
class DokumenPendukungController extends ApiController
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
                    'DokumenPendukung.nama_dokumen ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ],
            'contain' => ['JenisIzin']
        ];
        $dokumenPendukung = $this->paginate($this->DokumenPendukung);
        $paging = $this->request->params['paging']['DokumenPendukung'];
        $dokumenPendukung = $this->addRowNumber($dokumenPendukung);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $dokumenPendukung,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Dokumen Pendukung id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $dokumenPendukung = $this->DokumenPendukung->get($id, [
            'contain' => ['JenisIzin']
        ]);

        $this->setResponseData($dokumenPendukung, $success, $message);
    }

    public function getStatusList()
    {
        $success = true;
        $message = '';

        $statusList = [];
        $statusList[] = array(
            'kode' => 'W',
            'label' => 'Wajib',
        );
        $statusList[] = array(
            'kode' => 'TW',
            'label' => 'Tidak Wajib',
        );

        $data = array(
            'items' => $statusList
        );

        $this->setResponseData($data, $success, $message);
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

        $dokumenPendukung = $this->DokumenPendukung->newEntity();
        if ($this->request->is('post')) {
            $dokumenPendukung = $this->DokumenPendukung->patchEntity($dokumenPendukung, $this->request->data);
            if ($this->DokumenPendukung->save($dokumenPendukung)) {
                $message = __('dokumen pendukung berhasil disimpan.');
                $success = true;
            } else {
                $this->setErrors($dokumenPendukung->errors());
                $message = __('dokumen pendukung tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($dokumenPendukung, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Dokumen Pendukung id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $dokumenPendukung = $this->DokumenPendukung->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dokumenPendukung = $this->DokumenPendukung->patchEntity($dokumenPendukung, $this->request->data);
            if ($this->DokumenPendukung->save($dokumenPendukung)) {
                $message = __('dokumen pendukung berhasil disimpan.');
                $success = true;
            } else {
                $this->setErrors($dokumenPendukung->errors());
                $message = __('dokumen pendukung tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($dokumenPendukung, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Dokumen Pendukung id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';

        $this->request->allowMethod(['post', 'delete']);
        $dokumenPendukung = $this->DokumenPendukung->get($id);
        if ($this->DokumenPendukung->delete($dokumenPendukung)) {
            $message = __('dokumen pendukung berhasil dihapus.');
            $success = true;
        } else {
            $message = __('dokumen pendukung tidak berhasil dihapus. Silahkan coba kembali.');
        }
        
        $this->setResponseData(array(), $success, $message);
    }
}
