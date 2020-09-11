<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\Desa;

/**
 * Desa Controller
 *
 * @property \App\Model\Table\DesaTable $Desa
 */
class DesaController extends ApiController
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
                'Kecamatan' => [
                    'fields' => [
                        'Kecamatan.kode_daerah',
                        'Kecamatan.nama_daerah'
                    ]
                ]
            ],
            'conditions' => [
                'OR' => [
                    'Desa.kode_daerah ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Desa.nama_daerah ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Kecamatan.nama_daerah ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];

        if (isset($_GET['kecamatan_id']) && !empty($_GET['kecamatan_id'])) {
            $kecamatanId = (int) $_GET['kecamatan_id'];
            if ($kecamatanId !== 0) {
                $this->paginate['conditions'][] = ['Desa.kecamatan_id' => $kecamatanId];
            }
        }

        $desa = $this->paginate($this->Desa);
        $paging = $this->request->params['paging']['Desa'];
        $desa = $this->addRowNumber($desa);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $desa,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Desa id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $desa = $this->Desa->get($id, [
            'contain' => [
                'Kecamatan' => [
                    'fields' => [
                        'Kecamatan.kode_daerah',
                        'Kecamatan.nama_daerah'
                    ]
                ]
            ]
        ]);

        $this->setResponseData($desa, $success, $message);
    }

    public function getList()
    {
        $success = true;
        $message = '';
        $conditions = [];

        if (isset($this->request->query['kecamatan_id']) && !empty($this->request->query['kecamatan_id'])) {
            $kecamatanId = (int) $this->request->query['kecamatan_id'];
            if ($kecamatanId !== 0) {
                $conditions['Desa.kecamatan_id'] = $kecamatanId;
            }
        }

        $conditions['OR'] = [
            'Desa.kode_daerah ILIKE' => '%' . $this->_apiQueryString . '%',
            'Desa.nama_daerah ILIKE' => '%' . $this->_apiQueryString . '%',
        ];
        $desa = $this->Desa->find('all', [
            'fields' => ['Desa.id', 'Desa.kode_daerah', 'Desa.nama_daerah'],
            'contain' => [
                'Kecamatan' => [
                    'fields' => [ 'Kecamatan.kode_daerah', 'Kecamatan.nama_daerah']
                ],
                'Kecamatan.Kabupaten' => [
                    'fields' => [ 'Kabupaten.kode_daerah', 'Kabupaten.nama_daerah']
                ],
                'Kecamatan.Kabupaten.Provinsi' => [
                    'fields' => [ 'kode_daerah', 'nama_daerah']
                ]
            ],
            'conditions' => $conditions,
            'order' => [
                'Desa.nama_daerah' => 'ASC'
            ],
            'limit' => $this->_autocompleteLimit
        ]);
        $data = array(
            'items' => $desa
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

        $desa = $this->Desa->newEntity();

        if ($this->request->is('post')) {
            $desa = $this->Desa->patchEntity($desa, $this->request->data);

            if ($this->Desa->save($desa)) {
                $success = true;
                $message = __('Desa berhasil disimpan.');
            } else {
                $this->setErrors($desa->errors());
                $message = __('Desa tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($desa, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Desa id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $desa = $this->Desa->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $desa = $this->Desa->patchEntity($desa, $this->request->data);

            if ($this->Desa->save($desa)) {
                $success = true;
                $message = __('desa berhasil disimpan.');
            } else {
                $message = __('desa tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($desa, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Desa id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $desa = $this->Desa->get($id);

        if ($this->Desa->delete($desa)) {
            $success = true;
            $message = __('desa berhasil dihapus.');
        } else {
            $message = __('desa tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData($data, $success, $message);
    }

    public function getParentWilayah($id)
    {
        $success = true;
        $message = '';

        $desa = $this->Desa->get($id, [
            'fields' => [
                'id',
                'kode_daerah',
                'nama_daerah'
            ],
            'contain' => [
                'Kecamatan' => [
                    'fields' => [
                        'id',
                        'kode_daerah',
                        'nama_daerah'
                    ]
                ],
                'Kecamatan.Kabupaten' => [
                    'fields' => [
                        'id',
                        'kode_daerah',
                        'nama_daerah'
                    ]
                ],
                'Kecamatan.Kabupaten.Provinsi' => [
                    'fields' => [
                        'id',
                        'kode_daerah',
                        'nama_daerah'
                    ]
                ],
            ]
        ]);

        $this->setResponseData($desa, $success, $message);
    }

}
