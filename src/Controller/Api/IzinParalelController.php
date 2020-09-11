<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\IzinParalel;

/**
 * IzinParalel Controller
 *
 * @property \App\Model\Table\IzinParalelTable $IzinParalel
 */
class IzinParalelController extends ApiController
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
                    'JenisIzin.jenis_izin ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ],
            'contain' => ['JenisIzin', 'IzinParalel']
        ];
        $izinParalel = $this->paginate($this->IzinParalel);
        $paging = $this->request->params['paging']['IzinParalel'];
        $izinParalel = $this->addRowNumber($izinParalel);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $izinParalel,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Izin Paralel id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $izinParalel = $this->IzinParalel->get($id, [
            'contain' => ['JenisIzin', 'IzinParalel', 'IzinParalel']
        ]);

        $this->setResponseData($izinParalel, $success, $message);
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

        $izinParalel = $this->IzinParalel->newEntity();
        if ($this->request->is('post')) {
            $izinParalel = $this->IzinParalel->patchEntity($izinParalel, $this->request->data);
            if ($this->IzinParalel->save($izinParalel)) {
                $message = __('izin paralel berhasil disimpan.');
                $success = true;
            } else {
                $message = __('izin paralel tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($izinParalel, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Izin Paralel id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $izinParalel = $this->IzinParalel->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $izinParalel = $this->IzinParalel->patchEntity($izinParalel, $this->request->data);
            if ($this->IzinParalel->save($izinParalel)) {
                $message = __('izin paralel berhasil disimpan.');
                $success = true;
            } else {
                $message = __('izin paralel tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($izinParalel, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Izin Paralel id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';

        $this->request->allowMethod(['post', 'delete']);
        $izinParalel = $this->IzinParalel->get($id);
        if ($this->IzinParalel->delete($izinParalel)) {
            $message = __('izin paralel berhasil dihapus.');
            $success = true;
        } else {
            $message = __('izin paralel tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData(array(), $success, $message);
    }
}
