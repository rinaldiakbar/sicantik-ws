<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\Provinsi;
/**
 * Provinsi Controller
 *
 * @property \App\Model\Table\ProvinsiTable $Provinsi
 */
class ProvinsiController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['getList']);
    }

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
                    'LOWER(Provinsi.kode_daerah) ILIKE' => '%' . $this->_apiQueryString . '%',
                    'LOWER(Provinsi.nama_daerah) ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];
        $provinsi = $this->paginate($this->Provinsi);
        $paging = $this->request->params['paging']['Provinsi'];
        $provinsi = $this->addRowNumber($provinsi);
        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $provinsi,
            'total_items' => $paging['count']
        );

        $this->setResponseData($data, $success, $message);
    }

    public function getList()
    {
        $success = true;
        $message = '';

        $provinsi = $this->Provinsi->find('all', [
            'fields' => ['Provinsi.id', 'Provinsi.kode_daerah', 'Provinsi.nama_daerah'],
            'conditions' => [
                'OR' => [
                    'LOWER(Provinsi.kode_daerah) ILIKE' => '%' . $this->_apiQueryString . '%',
                    'LOWER(Provinsi.nama_daerah) ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ],
            'order' => [
                'Provinsi.nama_daerah' => 'ASC'
            ],
            'limit' => $this->_autocompleteLimit
        ]);
        $data = array(
            'items' => $provinsi
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Provinsi id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $provinsi = $this->Provinsi->get($id, [
            'fields' => ['Provinsi.id', 'Provinsi.kode_daerah', 'Provinsi.nama_daerah'],
            'contain' => []
        ]);

        $this->setResponseData($provinsi, $success, $message);
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

        $provinsi = $this->Provinsi->newEntity();
        if ($this->request->is('post')) {
            $provinsi = $this->Provinsi->patchEntity($provinsi, $this->request->data);
            if ($this->Provinsi->save($provinsi)) {
                $success = true;
                $message = __('provinsi berhasil disimpan.');
            } else {
                $message = __('provinsi tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($provinsi, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Provinsi id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $provinsi = $this->Provinsi->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $provinsi = $this->Provinsi->patchEntity($provinsi, $this->request->data);
            if ($this->Provinsi->save($provinsi)) {
                $success = true;
                $message = __('provinsi berhasil disimpan.');
            } else {
                $message = __('provinsi tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($provinsi, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Provinsi id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $provinsi = $this->Provinsi->get($id);
        if ($this->Provinsi->delete($provinsi)) {
            $success = true;
            $message = __('provinsi berhasil dihapus.');
        } else {
            $message = __('provinsi tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData($data, $success, $message);
    }
}
