<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\BidangUsaha;

/**
 * BidangUsaha Controller
 *
 * @property \App\Model\Table\BidangUsahaTable $BidangUsaha
 */
class BidangUsahaController extends ApiController
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
            'fields' => [
                'BidangUsaha.id', 'BidangUsaha.kode', 'BidangUsaha.keterangan'
            ],
            'conditions' => [
                'OR' => [
                    'BidangUsaha.kode ILIKE' => '%' . $this->_apiQueryString . '%',
                    'BidangUsaha.keterangan ILIKE' => '%' . $this->_apiQueryString . '%',
                ]
            ]
        ];

        $bidangUsaha = $this->paginate($this->BidangUsaha);
        $paging = $this->request->params['paging']['BidangUsaha'];
        $bidangUsaha = $this->addRowNumber($bidangUsaha);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $bidangUsaha,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id BidangUsaha id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $bidangUsaha = $this->BidangUsaha->get($id, [
            'fields' => [
                'BidangUsaha.id', 'BidangUsaha.kode', 'BidangUsaha.keterangan'
            ]
        ]);

        $this->setResponseData($bidangUsaha, $success, $message);
    }

    public function getList()
    {
        $success = true;
        $message = '';
        $list = [];

        $bidangUsaha = $this->BidangUsaha->find('all', [
            'fields' => ['BidangUsaha.id', 'BidangUsaha.kode', 'BidangUsaha.keterangan'],
            'conditions' => [
                'OR' => [
                    'BidangUsaha.kode ILIKE' => '%' . $this->_apiQueryString . '%',
                    'BidangUsaha.keterangan ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ],
            'limit' => $this->_autocompleteLimit
        ]);

        foreach ($bidangUsaha as $bidang) {
            $list[] = [
                'id' => $bidang->id,
                'name' => $bidang->kode . '-' . $bidang->keterangan,
                'image' => '',
                '_lowername' => strtolower($bidang->kode . '-' . $bidang->keterangan)
            ];
        }

        $data = array(
            'items' => $list
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

        $bidangUsaha = $this->BidangUsaha->newEntity();
        if ($this->request->is('post')) {
            $bidangUsaha = $this->BidangUsaha->patchEntity($bidangUsaha, $this->request->data);
            if ($this->BidangUsaha->save($bidangUsaha)) {
                $success = true;
                $message = __('Bidang Usaha berhasil disimpan.');
            } else {
                $message = __('Bidang Usaha tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($bidangUsaha, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id BidangUsaha id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $bidangUsaha = $this->BidangUsaha->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bidangUsaha = $this->BidangUsaha->patchEntity($bidangUsaha, $this->request->data);
            if ($this->BidangUsaha->save($bidangUsaha)) {
                $success = true;
                $message = __('Bidang Usaha berhasil disimpan.');
            } else {
                $message = __('Bidang Usaha tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($bidangUsaha, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id BidangUsaha id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $bidangUsaha = $this->BidangUsaha->get($id);
        if ($this->BidangUsaha->delete($bidangUsaha)) {
            $success = true;
            $message = __('Bidang Usaha berhasil dihapus.');
        } else {
            $message = __('Bidang Usaha tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }
}
