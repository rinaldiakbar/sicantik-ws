<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\Kabupaten;

/**
 * Kabupaten Controller
 *
 * @property \App\Model\Table\KabupatenTable $Kabupaten
 */
class KabupatenController extends ApiController
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
            'contain' => [
                'Provinsi' => [
                    'fields' => [
                        'Provinsi.kode_daerah',
                        'Provinsi.nama_daerah'
                    ]
                ]
            ],
            'conditions' => [
                'OR' => [
                    'Kabupaten.kode_daerah ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Kabupaten.nama_daerah ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Provinsi.nama_daerah ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];

        if (isset($_GET['provinsi_id']) && !empty($_GET['provinsi_id'])) {
            $provinsiId = (int) $_GET['provinsi_id'];
            if ($provinsiId !== 0) {
                $this->paginate['conditions'][] = ['Kabupaten.provinsi_id' => $provinsiId];
            }
        }

        $kabupaten = $this->paginate($this->Kabupaten);
        $paging = $this->request->params['paging']['Kabupaten'];
        $kabupaten = $this->addRowNumber($kabupaten);
        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $kabupaten,
            'total_items' => $paging['count']
        );

        $this->setResponseData($data, $success, $message);
    }

    public function getList()
    {
        $success = true;
        $message = '';
        $conditions = [];

        if (isset($this->request->query['provinsi_id']) && !empty($this->request->query['provinsi_id'])) {
            $provinsiId = (int) $this->request->query['provinsi_id'];
            if ($provinsiId !== 0) {
                $conditions['Kabupaten.provinsi_id'] = $provinsiId;
            }
        }

        $conditions['OR'] = [
            'Kabupaten.kode_daerah ILIKE' => '%' . $this->_apiQueryString . '%',
            'Kabupaten.nama_daerah ILIKE' => '%' . $this->_apiQueryString . '%'
        ];
        $kabupaten = $this->Kabupaten->find('all', [
            'contain' => [
                'Provinsi' => [
                    'fields' => [ 'Provinsi.kode_daerah', 'Provinsi.nama_daerah']
                ]
            ],
            'fields' => ['Kabupaten.id', 'Kabupaten.kode_daerah', 'Kabupaten.nama_daerah'],
            'conditions' => $conditions,
            'order' => [
                'Kabupaten.nama_daerah' => 'ASC'
            ],
            'limit' => $this->_autocompleteLimit
        ]);
        $data = array(
            'items' => $kabupaten
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Kabupaten id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $kabupaten = $this->Kabupaten->get($id, [
            'contain' => [
                'Provinsi' => [
                    'fields' => [
                        'Provinsi.kode_daerah',
                        'Provinsi.nama_daerah'
                    ]
                ]
            ]
        ]);

        $this->setResponseData($kabupaten, $success, $message);
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

        $kabupaten = $this->Kabupaten->newEntity();
        if ($this->request->is('post')) {
            $kabupaten = $this->Kabupaten->patchEntity($kabupaten, $this->request->data);
            if ($this->Kabupaten->save($kabupaten)) {
                $success = true;
                $message = __('kabupaten berhasil disimpan.');
            } else {
                $message = __('kabupaten tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
//        $provinsi = $this->Kabupaten->Provinsi->find('list', ['limit' => 200]);
        $this->setResponseData($kabupaten, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Kabupaten id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $kabupaten = $this->Kabupaten->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $kabupaten = $this->Kabupaten->patchEntity($kabupaten, $this->request->data);
            if ($this->Kabupaten->save($kabupaten)) {
                $success = true;
                $message = __('kabupaten berhasil disimpan.');
            } else {
                $message = __('kabupaten tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($kabupaten, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Kabupaten id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $kabupaten = $this->Kabupaten->get($id);
        if ($this->Kabupaten->delete($kabupaten)) {
            $success = true;
            $message = __('kabupaten berhasil dihapus.');
        } else {
            $message = __('kabupaten tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData($data, $success, $message);
    }
}
