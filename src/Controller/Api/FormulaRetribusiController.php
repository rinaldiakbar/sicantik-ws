<?php
namespace App\Controller\Api;

use \App\Model\Entity\FormulaRetribusi;
use Cake\Core\Exception\Exception;
use \Cake\ORM\TableRegistry;
use \Cake\Utility\Inflector;
use \Phinx\Db\Table;
use \Cake\I18n\Number;

/**
 * FormulaRetribusi Controller
 *
 * @property \App\Model\Table\FormulaRetribusiTable $FormulaRetribusi
 */
class FormulaRetribusiController extends ApiController
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
            'fields' => ['id', 'formula', 'jenis_izin_id'],
            'conditions' => [
                'OR' => [
                    'FormulaRetribusi.formula ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ],
            'contain' => [
                'JenisIzin' => [
                    'fields' => ['id', 'jenis_izin']
                ]
            ]
        ];

        $tarifItem = $this->paginate($this->FormulaRetribusi);
        $paging = $this->request->params['paging']['FormulaRetribusi'];
        $tarifItem = $this->addRowNumber($tarifItem);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $tarifItem,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Formula Retribusi id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $formulaRetribusi = $this->FormulaRetribusi->get($id, [
            'fields' => ['id', 'formula', 'jenis_izin_id'],
            'contain' => [
                'JenisIzin' => [
                    'fields' => [
                        'id', 'jenis_izin'
                    ]
                ]
            ]
        ]);

        $this->setResponseData($formulaRetribusi, $success, $message);
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

        $formulaRetribusi = $this->FormulaRetribusi->newEntity();
        if ($this->request->is('post')) {
            $formulaRetribusi = $this->FormulaRetribusi->patchEntity($formulaRetribusi, $this->request->data);
            if ($this->FormulaRetribusi->save($formulaRetribusi)) {
                $success = true;
                $message = __('Formula Retribusi berhasil disimpan.');
            } else {
                $message = __('Formula Retribusi tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($formulaRetribusi, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Formula Retribusi id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $formulaRetribusi = $this->FormulaRetribusi->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $formulaRetribusi = $this->FormulaRetribusi->patchEntity($formulaRetribusi, $this->request->data);
            if ($this->FormulaRetribusi->save($formulaRetribusi)) {
                $success = true;
                $message = __('Formula Retribusi berhasil disimpan.');
            } else {
                $this->setErrors($formulaRetribusi->errors());
                $message = __('Formula Retribusi tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($formulaRetribusi, $success, $message);
    }

    public function save()
    {
        $success = false;
        $message = '';

        $jenisIzinId = $this->request->data['jenis_izin_id'];
        $formula = $this->request->data['formula'];

        $formulaRetribusiTable = TableRegistry::get('FormulaRetribusi');
        $formulaRetribusi = $formulaRetribusiTable
            ->find('all', [
                'conditions' => ['jenis_izin_id' => $jenisIzinId]
            ])
            ->first();

        if (!$formulaRetribusi) {
            $formulaRetribusi = $this->FormulaRetribusi->newEntity();
        }

        if ($this->request->is('post')) {
            $formulaRetribusi->formula = $formula;
            $formulaRetribusi = $this->FormulaRetribusi->patchEntity($formulaRetribusi, $this->request->data);
            if ($this->FormulaRetribusi->save($formulaRetribusi)) {
                $success = true;
                $message = __('Formula Retribusi berhasil disimpan.');
            } else {
                $message = __('Formula Retribusi tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($formulaRetribusi, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Formula Retribusi id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $formulaRetribusi = $this->FormulaRetribusi->get($id);
        if ($this->FormulaRetribusi->delete($formulaRetribusi)) {
            $success = true;
            $message = __('Formula Retribusi berhasil dihapus.');
        } else {
            $message = __('Formula Retribusi tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }

    /**
     * Get all tarif item that have been set for a jenis izin
     * @param $jenisIzinId
     * @return array
     */
    protected function getTarifItem($jenisIzinId)
    {
        $tarifItemTable = TableRegistry::get('TarifItem');
        $formulaRetribusiTable = TableRegistry::get('FormulaRetribusi');

        $tarifItem = $tarifItemTable->find('all', [
            'fields' => ['id', 'nama_item', 'kode_item', 'satuan', 'jenis_izin_id'],
            'contain' => [
                'TarifHarga' => [
                    'fields' => [
                        'id', 'kategori', 'harga', 'tarif_item_id'
                    ]
                ],
                'JenisIzin' => [
                    'fields' => [
                        'id', 'jenis_izin'
                    ]
                ]
            ],
            'conditions' => ['jenis_izin_id' => $jenisIzinId]
        ]);

        $formulaRetribusi = $formulaRetribusiTable->find('all', [
            'fields' => ['id', 'formula', 'jenis_izin_id'],
            'contain' => [
                'JenisIzin' => [
                    'fields' => [
                        'id', 'jenis_izin'
                    ]
                ]
            ],
            'conditions' => ['jenis_izin_id' => $jenisIzinId]
        ])->firstOrFail();

        return [$tarifItem, $formulaRetribusi];
    }

    public function getFormSimulasi($jenisIzinId)
    {
        $success = true;
        $message = '';
        $data = [];

        list($tarifItem, $formulaRetribusi) = $this->getTarifItem($jenisIzinId);
        $data = ['tarif_item' => $tarifItem, 'formula_retribusi' => $formulaRetribusi];

        $this->setResponseData($data, $success, $message);
    }

    public function getFormRetribusi($permohonanIzinId)
    {
        $success = true;
        $message = '';
        $data = [];

        $permohonanIzinTable = TableRegistry::get('PermohonanIzin');
        $permohonanIzin = $permohonanIzinTable->find('all', [
            'fields' => ['id', 'no_permohonan', 'jenis_izin_id', 'nilai_retribusi'],
            'contain' => [
                'JenisIzin' => [
                    'fields' => ['id', 'jenis_izin']
                ]
            ],
            'conditions' => ['PermohonanIzin.id' => $permohonanIzinId]
        ])->firstOrFail();
        $permohonanIzinTable = TableRegistry::get('PermohonanIzin');

        list($tarifItem, $formulaRetribusi) = $this->getTarifItem($permohonanIzin->jenis_izin_id);
        $data = [
            'tarif_item' => $tarifItem,
            'formula_retribusi' => $formulaRetribusi,
            'permohonan_izin' => $permohonanIzin,
            'previous_calculation' => $permohonanIzinTable->RetribusiDetail->getCalculatedData($permohonanIzinId)
        ];

        $this->setResponseData($data, $success, $message);
    }

    /**
     * Calculate total tarif retribusi
     * @param $jenisIzinId
     * @return int
     */
    protected function hitung($jenisIzinId)
    {
        $total = 0;
        $formulaRetribusiTable = TableRegistry::get('FormulaRetribusi');
        $formulaRetribusi = $formulaRetribusiTable->find('all', [
            'fields' => ['id', 'formula', 'jenis_izin_id'],
            'contain' => [
                'JenisIzin' => [
                    'fields' => [
                        'id', 'jenis_izin'
                    ]
                ]
            ],
            'conditions' => ['jenis_izin_id' => $jenisIzinId]
        ])->firstOrFail();

        try {
            foreach ($this->request->data as $itemKey => $formItem) {
                eval("\${$itemKey} = {$formItem[2]};");
            }
            eval("\$total = $formulaRetribusi->formula;");
        } catch (\Exception $ex) {

        }

        return $total;
    }

    /**
     * API untuk melakukan perhitungan retribusi
     * @param $permohonanIzinId
     */
    public function hitungRetribusi($permohonanIzinId)
    {
        $success = false;
        $message = '';
        $data = [];
        $total = 0;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $permohonanIzinTable = TableRegistry::get('PermohonanIzin');
            $permohonanIzin = $permohonanIzinTable->find('all', [
                'fields' => ['id', 'no_permohonan', 'jenis_izin_id'],
                'contain' => [
                    'JenisIzin' => [
                        'fields' => ['id', 'jenis_izin']
                    ]
                ],
                'conditions' => ['PermohonanIzin.id' => $permohonanIzinId]
            ])->firstOrFail();

            $total = $this->hitung($permohonanIzin->jenis_izin_id);
            $success = true;

            $message = 'Total Retribusi yang harus dibayar Rp ' . Number::format($total, ['pattern' => '#,###']);
            $data['total'] = $total;
        }

        $this->setResponseData($data, $success, $message);
    }

    /**
     * API untuk melakukan perhitungan simulasi tarif
     * @param $jenisIzinId
     */
    public function hitungSimulasi($jenisIzinId)
    {
        $success = false;
        $message = '';
        $data = [];
        $total = 0;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $total = $this->hitung($jenisIzinId);
            $success = true;

            $message = 'Total Retribusi yang harus dibayar Rp ' . Number::format($total, ['pattern' => '#,###']);
            $data['total'] = $total;
        }

        $this->setResponseData($data, $success, $message);
    }

    /**
     * API untuk menyimpan detail perhitungan dan total perhitungan
     * @param $permohonanIzinId
     */
    public function saveForm($permohonanIzinId)
    {
        $success = true;
        $message = '';
        $data = [];
        $total = 0;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $permohonanIzinTable = TableRegistry::get('PermohonanIzin');
            $permohonanIzin = $permohonanIzinTable->find('all', [
                'fields' => ['id', 'no_permohonan', 'jenis_izin_id'],
                'contain' => [
                    'JenisIzin' => [
                        'fields' => ['id', 'jenis_izin']
                    ]
                ],
                'conditions' => ['PermohonanIzin.id' => $permohonanIzinId]
            ])->firstOrFail();

            $formulaRetribusiTable = TableRegistry::get('FormulaRetribusi');
            $formulaRetribusi = $formulaRetribusiTable->find('all', [
                'fields' => ['id', 'formula', 'jenis_izin_id'],
                'contain' => [
                    'JenisIzin' => [
                        'fields' => [
                            'id', 'jenis_izin'
                        ]
                    ]
                ],
                'conditions' => ['jenis_izin_id' => $permohonanIzin->jenis_izin_id]
            ])->firstOrFail();

            try {
                $detailData = [];
                $retribusiDetailTable = TableRegistry::get('RetribusiDetail');

                foreach ($this->request->data as $itemKey => $formItem) {
                    $harga = $formItem[0];
                    $qty = $formItem[1];
                    $subtotal = $harga * $qty;
                    $namaItem = $formItem[3];
                    $satuan = $formItem[4];

                    eval("\${$itemKey} = {$subtotal};");
                    
                    $detailData[] = [
                        'permohonan_izin_id' => $permohonanIzinId,
                        'kode_item' => $itemKey,
                        'nama_item' => $namaItem,
                        'satuan' => $satuan,
                        'harga' => $harga,
                        'jumlah' => $qty,
                        'subtotal' => $subtotal
                    ];
                }
                
                eval("\$total = $formulaRetribusi->formula;");

                // Save Nilai Retribusi and Retribusi Details
                $retribusiSaved = $retribusiDetailTable->connection()->transactional(function () use ($permohonanIzinTable, $retribusiDetailTable, $permohonanIzin, $detailData, $total) {
                    $permohonanIzin->nilai_retribusi = $total;
                    $retribusiDetails = $retribusiDetailTable->newEntities($detailData);

                    // Delete Previous Detail if any
                    $retribusiDetailTable->deleteAll([
                       'permohonan_izin_id' => $permohonanIzin->id
                    ]);

                    if ($retribusiDetailTable->saveMany($retribusiDetails) && $permohonanIzinTable->save($permohonanIzin)) {
                        return true;
                    } else {
                        return false;
                    }
                });

                if (!$retribusiSaved) {
                    throw new \Exception('Tidak berhasil menyimpan nilai retribusi');
                }

                $message = 'Total Retribusi yang harus dibayar Rp ' . Number::format($total, ['pattern' => '#,###']);
                $data['total'] = $total;
            } catch (\Exception $ex) {
                $success = false;
                $message = $ex->getMessage();
            }
        }

        $this->setResponseData($data, $success, $message);
    }
}
