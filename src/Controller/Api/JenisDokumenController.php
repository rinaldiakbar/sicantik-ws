<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

/**
 * JenisDokumen Controller
 *
 * @property \App\Model\Table\JenisDokumenTable $JenisDokumen
 */
class JenisDokumenController extends ApiController
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
                    'JenisDokumen.kode ILIKE' => '%' . $this->_apiQueryString . '%',
                    'JenisDokumen.deskripsi ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];

        $jenisDokumen = $this->paginate($this->JenisDokumen);
        $paging = $this->request->params['paging']['JenisDokumen'];
        $jenisDokumen = $this->addRowNumber($jenisDokumen);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $jenisDokumen,
            'total_items' => $paging['count']
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Jenis Dokumen id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $jenisDokumen = $this->JenisDokumen->get($id, [
            'fields' => ['id', 'kode', 'deskripsi']
        ]);

        $this->setResponseData($jenisDokumen, $success, $message);
    }

    public function getList()
    {
        $success = true;
        $message = '';

        $conditions['OR'] = [
            'JenisDokumen.kode ILIKE' => '%' . $this->_apiQueryString . '%',
            'JenisDokumen.deskripsi ILIKE' => '%' . $this->_apiQueryString . '%',
        ];
        $jenisDokumen = $this->JenisDokumen->find('all', [
            'fields' => ['JenisDokumen.id', 'JenisDokumen.kode', 'JenisDokumen.deskripsi'],
            'conditions' => $conditions,
            'limit' => $this->_autocompleteLimit
        ]);
        $data = array(
            'items' => $jenisDokumen
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

        $jenisDokumen = $this->JenisDokumen->newEntity();

        if ($this->request->is('post')) {
            $jenisDokumen = $this->JenisDokumen->patchEntity($jenisDokumen, $this->request->data);
            if ($this->JenisDokumen->save($jenisDokumen)) {
                $success = true;
                $message = __('Jenis Dokumen berhasil disimpan.');
            } else {
                $message = __('Jenis Dokumen tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($jenisDokumen, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id JenisDokumen id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $jenisDokumen = $this->JenisDokumen->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $jenisDokumen = $this->JenisDokumen->patchEntity($jenisDokumen, $this->request->data);
            if ($this->JenisDokumen->save($jenisDokumen)) {
                $success = true;
                $message = __('Jenis Dokumen berhasil disimpan.');
            } else {
                $message = __('Jenis Dokumen tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($jenisDokumen, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id JenisDokumen id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);

        // Prevent delete action for 'izin' record
        if ($id == 1) {
            return $this->setResponseData([], false, 'Data ini tidak boleh dihapus');
        }

        $jenisDokumen = $this->JenisDokumen->get($id);

        if ($this->JenisDokumen->delete($jenisDokumen)) {
            $success = true;
            $message = __('Jenis Dokumen berhasil dihapus.');
        } else {
            $message = __('Jenis Dokumen tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData($data, $success, $message);
    }
}
