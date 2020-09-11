<?php
namespace App\Controller\Api;

use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Unit;
use App\Service\UploadService;

/**
 * Unit Controller
 *
 * @property \App\Model\Table\UnitTable $Unit
 */
class UnitController extends ApiController
{

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->Auth->allow(['getInstansiPublicList']);
    }

    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Unit->setInstansi($this->getCurrentInstansi());
        $this->Unit->setUnit($this->getCurrentUnit());
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

        $unitTable = $this->Unit;
        $this->paginate = [
            'contain' => ['ParentUnit'],
            'conditions' => [
                'OR' => [
                    'LOWER(Unit.nama) ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];

        $tipe = isset($this->request->query['tipe']) ? strtoupper($this->request->query["tipe"]) : null;
        if ($tipe) {
            if ($tipe == $unitTable::TIPE_INSTANSI || $tipe == $unitTable::TIPE_UNIT) {
                $this->paginate['conditions'][] = ['Unit.tipe' => $tipe];
            }
        }

        $unit = $this->paginate($this->Unit);
        $paging = $this->request->params['paging']['Unit'];
        $unit = $this->addRowNumber($unit);

        if (!empty($unit)) {
            foreach ($unit as $index => $item) {
                $translateTipe = '';
                switch ($item['tipe']) {
                    case 'I':
                        $translateTipe = 'Instansi';
                        break;
                    case 'U':
                        $translateTipe = 'Unit';
                        break;
                    default:
                        break;
                }
                $unit[$index]['label_tipe'] = $translateTipe;
            }
        }

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $unit,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    public function getUnitList()
    {
        $success = true;
        $message = '';
        $units = new \stdClass();
        $instansiId = isset($this->request->query['instansi_id']) ? $this->request->query["instansi_id"] : null;
        $peranId = isset($this->request->query['peran_id']) ? $this->request->query["peran_id"] : null;
        $this->Unit->setFilteredBeforeFind(false);

        if (!$instansiId) {
            $instansi = $this->getCurrentInstansi();

            if ($instansi) {
                $instansiId = $instansi->id;
            }
        }

        if (!is_null($peranId) && $peranId != 0) {
            $this->loadModel('Peran');
            $peran = $this->Peran->get($peranId, [
                'fields' => [
                    'id',
                    'instansi_id'
                ]
            ]);

            if ($peran && $peran->instansi_id) {
                $instansiId = $peran->instansi_id;
            }
        }

        if ($instansiId) {
            $units = $this->Unit->find('children', [
                'for' => $instansiId,
                'fields' => ['Unit.id', 'Unit.nama'],
                'conditions' => [
                    'OR' => [
                        'LOWER(Unit.nama) ILIKE' => '%' . $this->_apiQueryString . '%'
                    ],
                    'Unit.tipe' => 'U'
                ],
                'limit' => $this->_autocompleteLimit
            ]);
        } else {
            $units = $this->Unit->find('all', [
                'fields' => ['Unit.id', 'Unit.nama'],
                'conditions' => [
                    'OR' => [
                        'LOWER(Unit.nama) ILIKE' => '%' . $this->_apiQueryString . '%'
                    ],
                    'Unit.tipe' => 'U'
                ],
                'limit' => $this->_autocompleteLimit
            ]);
        }

        $data = array(
            'items' => $units
        );

        $this->setResponseData($data, $success, $message);
    }

    public function getInstansiList()
    {
        $success = true;
        $message = '';
        $units = [];

        $currentInstansi = $this->getCurrentInstansi();
        $this->Unit->setFilteredBeforeFind(false);

        // if current Instansi is set, only list all instansi from that instansi below
        if ($currentInstansi) {
            $node = $this->Unit->get($currentInstansi->id, [
                'fields' => [
                    'id', 'nama'
                ],
                'order' => [
                    'nama' => 'ASC'
                ]
            ]);
            $units[] = $node->toArray();

            // Verify if that instansi has children
            if ($this->Unit->childCount($node) > 0) {
                $children = $this->Unit
                    ->find('children', ['for' => $currentInstansi->id])
                    ->find('all', [
                        'fields' => ['id', 'nama'],
                        'conditions' => [
                            'OR' => [
                                'LOWER(nama) ILIKE' => '%' . $this->_apiQueryString . '%'
                            ],
                            'tipe' => 'I'
                        ],
                        'order' => [
                            'nama' => 'ASC'
                        ]
                    ])
                    ->toArray();
                unset($node->lft);
                unset($node->rght);
                $units = array_merge($units, $children);
            }
        } else {
            $units = $this->Unit->find('all', [
                'fields' => ['id', 'nama'],
                'conditions' => [
                    'OR' => [
                        'LOWER(Unit.nama) ILIKE' => '%' . $this->_apiQueryString . '%'
                    ],
                    'Unit.tipe' => 'I'
                ],
                'order' => [
                    'nama' => 'ASC'
                ],
                'limit' => $this->_autocompleteLimit
            ])->toArray();
        }

        $data = array(
            'items' => $units
        );

        $this->setResponseData($data, $success, $message);
    }

    public function getInstansiPublicList()
    {
        $this->getInstansiList();
    }

    public function getTipeList()
    {
        $success = true;
        $message = '';

        $listTipe = [
            'I' => 'Instansi',
            'U' => 'Unit Kerja'
        ];

        // If user has specific Instansi, he/she doesn't have Instansi as option
        if ($this->getCurrentInstansi()) {
            $listTipe = [
                'U' => 'Unit Kerja'
            ];
        }

        $data = array(
            'items' => $listTipe
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Unit id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';
        $translateTipe = '';

        $this->Unit->setFilteredBeforeFind(false);

        $unit = $this->Unit->get($id, [
            'contain' => [
                'ParentUnit',
                'Pengguna',
//                'Pegawai',
//                'Peran',
                'ChildUnit'
            ]
        ]);

        switch ($unit->tipe) {
            case 'I':
                $translateTipe = 'Instansi';
                break;
            case 'U':
                $translateTipe = 'Unit';
                break;
            default:
                break;
        }

        $unit->label_tipe = $translateTipe;

        $this->setResponseData($unit, $success, $message);
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

        $unit = $this->Unit->newEntity();
        if ($this->request->is('post')) {
            $unit = $this->Unit->patchEntity($unit, $this->request->data);
            if ($this->Unit->save($unit)) {
                $success = true;
                $message = __('unit berhasil disimpan.');
            } else {
                $message = __('unit tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($unit, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Unit id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $unit = $this->Unit->get($id, [
            'contain' => ['Pengguna']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $unit = $this->Unit->patchEntity($unit, $this->request->data);
            if ($this->Unit->save($unit)) {
                $success = true;
                $message = __('unit berhasil disimpan.');
            } else {
                $message = __('unit tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
//        $parentUnit = $this->Unit->ParentUnit->find('list', ['limit' => 200]);
        $this->setResponseData($unit, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Unit id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $unit = $this->Unit->get($id);
        if ($this->Unit->delete($unit)) {
            $success = true;
            $message = __('unit berhasil dihapus.');
        } else {
            $this->setErrors($unit->errors());
            $success = false;
            $message = __('unit tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }

    public function getHierarchy()
    {
        $success = true;
        $message = '';
        $data = [];
        $units = [];

        $currentInstansi = $this->getCurrentInstansi();
        $this->Unit->setFilteredBeforeFind(false);

        // If Current Instansi is set, then only get the child units
        if ($currentInstansi) {
            $node = $this->Unit->get($currentInstansi->id, [
                'fields' => [
                    'id', 'label' => 'nama', 'tipe', 'parent_id'
                ]
            ]);

            // Verify if that instansi has children
            if ($this->Unit->childCount($node) > 0) {

                $children = $this->Unit
                    ->find('threaded', [
                        'fields' => [
                            'id', 'label' => 'nama', 'tipe', 'parent_id'
                        ],
                        'conditions' => ['parent_id' => $currentInstansi->id]
                    ])
                    ->toArray();

                unset($node->lft);
                unset($node->rght);
                $node = $node->toArray();
                $node['children'] = $children;
                $units[] = $node;

            } else {
                // Get All Unit
                $units = $this->Unit
                    ->find('threaded', [
                        'fields' => [
                            'id', 'label' => 'nama', 'tipe', 'parent_id'
                        ],
                        'conditions' => ['id' => $currentInstansi->id]
                    ])
                    ->toArray();
            }

        } else {
            // Get All Unit
            $units = $this->Unit
                ->find('threaded', [
                    'fields' => [
                        'id', 'label' => 'nama', 'tipe', 'parent_id'
                    ]
                ])
                ->toArray();
        }

        $data['hierarchy'] = $units;

        $this->setResponseData($data, $success, $message);
    }

    public function getDaerahList()
    {
        $success = true;
        $message = '';
        $data = [];

        $desaTable = TableRegistry::get('Desa');
        $kecamatanTable = TableRegistry::get('Kecamatan');
        $kabupatenTable = TableRegistry::get('Kabupaten');
        $provinsiTable = TableRegistry::get('Provinsi');

        $queryDesa = $desaTable->find();
        $queryKecamatan = $kecamatanTable->find();
        $queryKabupaten = $kabupatenTable->find();
        $queryProvinsi = $provinsiTable->find();
        $queryDesa
            ->select([
                'Desa.id', 'Desa.kode_daerah',
                'nama_daerah' => $queryDesa->func()->concat([
                    'Desa.kode_daerah' => 'identifier',
                    '-',
                    'Desa.nama_daerah' => 'identifier'
                ])
            ])
            ->where([
                'OR' => [
                    'LOWER(Desa.kode_daerah) ILIKE' => '%' . $this->_apiQueryString . '%',
                    'LOWER(Desa.nama_daerah) ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ])
            ->limit($this->_autocompleteLimit);
        $queryKecamatan
            ->select([
                'Kecamatan.id', 'Kecamatan.kode_daerah',
                'nama_daerah' => $queryKecamatan->func()->concat([
                    'Kecamatan.kode_daerah' => 'identifier',
                    '-',
                    'Kecamatan.nama_daerah' => 'identifier'
                ])
            ])
            ->where([
                'OR' => [
                    'LOWER(Kecamatan.kode_daerah) ILIKE' => '%' . $this->_apiQueryString . '%',
                    'LOWER(Kecamatan.nama_daerah) ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ])
            ->limit($this->_autocompleteLimit);
        $queryKabupaten
            ->select([
                'Kabupaten.id', 'Kabupaten.kode_daerah',
                'nama_daerah' => $queryKabupaten->func()->concat([
                    'Kabupaten.kode_daerah' => 'identifier',
                    '-',
                    'Kabupaten.nama_daerah' => 'identifier'
                ])
            ])
            ->where([
                'OR' => [
                    'LOWER(Kabupaten.kode_daerah) ILIKE' => '%' . $this->_apiQueryString . '%',
                    'LOWER(Kabupaten.nama_daerah) ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ])
            ->limit($this->_autocompleteLimit);
        $queryProvinsi
            ->select([
                'Provinsi.id', 'Provinsi.kode_daerah',
                'nama_daerah' => $queryProvinsi->func()->concat([
                    'Provinsi.kode_daerah' => 'identifier',
                    '-',
                    'Provinsi.nama_daerah' => 'identifier'
                ])
            ])
            ->where([
                'OR' => [
                    'LOWER(Provinsi.kode_daerah) ILIKE' => '%' . $this->_apiQueryString . '%',
                    'LOWER(Provinsi.nama_daerah) ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ])
            ->limit($this->_autocompleteLimit);

        $queryKecamatan->unionAll($queryDesa);
        $queryKabupaten->unionAll($queryKecamatan);
        $queryProvinsi->unionAll($queryKabupaten);
        $allDaerah  = $queryProvinsi->toArray();
        $data['items'] = $allDaerah;

        $this->setResponseData($data, $success, $message);
    }

    /**
     * Upload Logo
     */
    public function upload()
    {
        $data = [];
        $success = false;
        $message = '';

        try {
            UploadService::setInstansiID($this->getInstansiIdFromDataOrSession());
            $uploadData = UploadService::upload('file', 'logo');
            $data['file_name'] = $uploadData['file_name'];
            $data['file_url'] = $uploadData['url'];

            $success = true;
            $message = 'Logo berhasil diupload';
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        $this->setResponseData($data, $success, $message);
    }
}