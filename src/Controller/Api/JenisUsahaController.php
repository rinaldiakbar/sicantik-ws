<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\JenisUsaha;

/**
 * JenisUsaha Controller
 *
 * @property \App\Model\Table\JenisUsahaTable $JenisUsaha
 */
class JenisUsahaController extends ApiController
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
                'id', 'kode', 'keterangan'
            ],
            'contain' => [
                'BidangUsaha' => [
                    'fields' => [
                        'BidangUsaha.kode',
                        'BidangUsaha.keterangan'
                    ]
                ]
            ],
            'conditions' => [
                'OR' => [
                    'JenisUsaha.kode ILIKE' => '%' . $this->_apiQueryString . '%',
                    'JenisUsaha.keterangan ILIKE' => '%' . $this->_apiQueryString . '%',
//                    'BidangUsaha.kode ILIKE' => '%' . $this->_apiQueryString . '%',
//                    'BidangUsaha.keterangan ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];

        $jenisUsaha = $this->paginate($this->JenisUsaha);
        $paging = $this->request->params['paging']['JenisUsaha'];
        $jenisUsaha = $this->addRowNumber($jenisUsaha);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $jenisUsaha,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id JenisUsaha id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $jenisUsaha = $this->JenisUsaha->get($id, [
            'fields' => [
                'JenisUsaha.id', 'JenisUsaha.kode', 'JenisUsaha.keterangan', 'JenisUsaha.bidang_usaha_id'
            ],
            'contain' => [
                'BidangUsaha' => [
                    'fields' => [
                        'BidangUsaha.kode',
                        'BidangUsaha.keterangan'
                    ]
                ]
            ]
        ]);

        if ($jenisUsaha->bidang_usaha) {
            $jenisUsaha->bidang_usaha->_lowername = $jenisUsaha->bidang_usaha->kode . '-' . $jenisUsaha->bidang_usaha->keterangan;
        }

        $this->setResponseData($jenisUsaha, $success, $message);
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

        $jenisUsaha = $this->JenisUsaha->newEntity();
        if ($this->request->is('post')) {
            $jenisUsaha = $this->JenisUsaha->patchEntity($jenisUsaha, $this->request->data);
            if ($this->JenisUsaha->save($jenisUsaha)) {
                $success = true;
                $message = __('Jenis Usaha berhasil disimpan.');
            } else {
                $message = __('Jenis Usaha tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($jenisUsaha, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id JenisUsaha id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $jenisUsaha = $this->JenisUsaha->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $jenisUsaha = $this->JenisUsaha->patchEntity($jenisUsaha, $this->request->data);
            if ($this->JenisUsaha->save($jenisUsaha)) {
                $success = true;
                $message = __('Jenis Usaha berhasil disimpan.');
            } else {
                $message = __('Jenis Usaha tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($jenisUsaha, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id JenisUsaha id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $jenisUsaha = $this->JenisUsaha->get($id);
        if ($this->JenisUsaha->delete($jenisUsaha)) {
            $success = true;
            $message = __('Jenis Usaha berhasil dihapus.');
        } else {
            $message = __('Jenis Usaha tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }

    public function getList()
    {
        $success = true;
        $message = '';
        $list = [];

        $jenisUsaha = $this->JenisUsaha->find('all', [
            'fields' => ['JenisUsaha.id', 'JenisUsaha.kode', 'JenisUsaha.keterangan'],
            'conditions' => [
                'OR' => [
                    'JenisUsaha.kode ILIKE' => '%' . $this->_apiQueryString . '%',
                    'JenisUsaha.keterangan ILIKE' => '%' . $this->_apiQueryString . '%',
                ]
            ],
//            'limit' => $this->_autocompleteLimit
        ]);

        foreach ($jenisUsaha as $jenis) {
            $list[] = [
                'id' => $jenis->id,
                'name' => $jenis->kode . '-' . $jenis->keterangan,
                'image' => '',
                '_lowername' => strtolower($jenis->kode . '-' . $jenis->keterangan)
            ];
        }
        
        $data = array(
            'items' => $list
        );

        $this->setResponseData($data, $success, $message);
    }
}
