<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\Kecamatan;
/**
 * Kecamatan Controller
 *
 * @property \App\Model\Table\KecamatanTable $Kecamatan
 */
class KecamatanController extends ApiController
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
                'Kabupaten' => [
                    'fields' => [
                        'Kabupaten.kode_daerah',
                        'Kabupaten.nama_daerah'
                    ]
                ]
            ],
            'conditions' => [
                'OR' => [
                    'Kecamatan.kode_daerah ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Kecamatan.nama_daerah ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Kabupaten.nama_daerah ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];

        if (isset($_GET['kabupaten_id']) && !empty($_GET['kabupaten_id'])) {
            $kabupatenId = (int) $_GET['kabupaten_id'];
            if ($kabupatenId !== 0) {
                $this->paginate['conditions'][] = ['Kecamatan.kabupaten_id' => $kabupatenId];
            }
        }

        $kecamatan = $this->paginate($this->Kecamatan);
        $paging = $this->request->params['paging']['Kecamatan'];
        $kecamatan = $this->addRowNumber($kecamatan);
        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $kecamatan,
            'total_items' => $paging['count']
        );

        $this->setResponseData($data, $success, $message);
    }

    public function getList()
    {
        $success = true;
        $message = '';
        $conditions = [];

        if (isset($this->request->query['kabupaten_id']) && !empty($this->request->query['kabupaten_id'])) {
            $kabupatenId = (int) $this->request->query['kabupaten_id'];
            if ($kabupatenId !== 0) {
                $conditions['Kecamatan.kabupaten_id'] = $kabupatenId;
            }
        }

        $conditions['OR'] = [
            'Kecamatan.kode_daerah ILIKE' => '%' . $this->_apiQueryString . '%',
            'Kecamatan.nama_daerah ILIKE' => '%' . $this->_apiQueryString . '%',
        ];
        $kecamatan = $this->Kecamatan->find('all', [
            'fields' => ['Kecamatan.id', 'Kecamatan.kode_daerah', 'Kecamatan.nama_daerah'],
            'contain' => [
                'Kabupaten' => [
                    'fields' => [ 'Kabupaten.kode_daerah', 'Kabupaten.nama_daerah']
                ],
                'Kabupaten.Provinsi' => [
                    'fields' => [ 'kode_daerah', 'nama_daerah']
                ]
            ],
            'conditions' => $conditions,
            'order' => [
                'Kecamatan.nama_daerah' => 'ASC'
            ],
            'limit' => $this->_autocompleteLimit
        ]);
        $data = array(
            'items' => $kecamatan
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Kecamatan id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $kecamatan = $this->Kecamatan->get($id, [
            'contain' => [
                'Kabupaten' => [
                    'fields' => [
                        'Kabupaten.kode_daerah',
                        'Kabupaten.nama_daerah'
                    ]
                ]
            ]
        ]);

        $this->setResponseData($kecamatan, $success, $message);
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
        $kecamatan = $this->Kecamatan->newEntity();
        if ($this->request->is('post')) {
            $kecamatan = $this->Kecamatan->patchEntity($kecamatan, $this->request->data);
            if ($this->Kecamatan->save($kecamatan)) {
                $success = true;
                $message = __('kecamatan berhasil disimpan.');
            } else {
                $message = __('kecamatan tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($kecamatan, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Kecamatan id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $kecamatan = $this->Kecamatan->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $kecamatan = $this->Kecamatan->patchEntity($kecamatan, $this->request->data);
            if ($this->Kecamatan->save($kecamatan)) {
                $success = true;
                $message = __('kecamatan berhasil disimpan.');
            } else {
                $message = __('kecamatan tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($kecamatan, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Kecamatan id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $kecamatan = $this->Kecamatan->get($id);
        if ($this->Kecamatan->delete($kecamatan)) {
            $success = true;
            $message = __('kecamatan berhasil dihapus.');
        } else {
            $message = __('kecamatan tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData($data, $success, $message);
    }
}
