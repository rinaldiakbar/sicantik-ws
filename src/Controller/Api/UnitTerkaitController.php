<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\UnitTerkait;

/**
 * UnitTerkait Controller
 *
 * @property \App\Model\Table\UnitTerkaitTable $UnitTerkait
 */
class UnitTerkaitController extends ApiController
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
                    'LOWER(Unit.nama) LIKE' => '%' . $this->_apiQueryString . '%',
                    'LOWER(JenisIzin.jenis_izin) LIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ],
            'contain' => ['JenisIzin', 'Unit']
        ];
        $unitTerkait = $this->paginate($this->UnitTerkait);
        $paging = $this->request->params['paging']['UnitTerkait'];
        $unitTerkait = $this->addRowNumber($unitTerkait);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $unitTerkait,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Unit Terkait id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $unitTerkait = $this->UnitTerkait->get($id, [
            'contain' => ['JenisIzin', 'Unit']
        ]);

        $this->setResponseData($unitTerkait, $success, $message);
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

        $unitTerkait = $this->UnitTerkait->newEntity();
        if ($this->request->is('post')) {
            $unitTerkait = $this->UnitTerkait->patchEntity($unitTerkait, $this->request->data);
            if ($this->UnitTerkait->save($unitTerkait)) {
                $message = __('unit terkait berhasil disimpan.');
                $success = true;
            } else {
                $message = __('unit terkait tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($unitTerkait, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Unit Terkait id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $unitTerkait = $this->UnitTerkait->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $unitTerkait = $this->UnitTerkait->patchEntity($unitTerkait, $this->request->data);
            if ($this->UnitTerkait->save($unitTerkait)) {
                $message = __('unit terkait berhasil disimpan.');
                $success = true;
            } else {
                $message = __('unit terkait tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($unitTerkait, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Unit Terkait id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';

        $this->request->allowMethod(['post', 'delete']);
        $unitTerkait = $this->UnitTerkait->get($id);
        if ($this->UnitTerkait->delete($unitTerkait)) {
            $message = __('unit terkait berhasil dihapus.');
            $success = true;
        } else {
            $message = __('unit terkait tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData(array(), $success, $message);
    }
}
