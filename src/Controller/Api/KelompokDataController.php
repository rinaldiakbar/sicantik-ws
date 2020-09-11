<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\KelompokData;
use Cake\ORM\TableRegistry;

/**
 * KelompokData Controller
 *
 * @property \App\Model\Table\KelompokDataTable $KelompokData
 */
class KelompokDataController extends ApiController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    /*public function index()
    {
        $success = true;
        $message = '';
        $this->paginate = [
            'fields' => ['id', 'label_kelompok', 'template_data_id', 'jenis_sumber']
        ];

        $kelompokData = $this->paginate($this->KelompokData);
        $paging = $this->request->params['paging']['KelompokData'];
        $kelompokData = $this->addRowNumber($kelompokData);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $kelompokData,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }*/

    /**
     * View method
     *
     * @param string|null $id Kelompok Data id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';
        $kelompokData = $this->KelompokData->get($id, [
            'fields' => ['id', 'label_kelompok', 'template_data_id',],
            'contain' => [
                'TemplateData' => [
                    'fields' => [
                        'id', 'keterangan'
                    ]
                ]
            ],
        ]);
        $this->setResponseData($kelompokData, $success, $message);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|void Redirects on successful add, renders view otherwise.
     */
    /*public function add()
    {
        $success = false;
        $message = '';

        $kelompokData = $this->KelompokData->newEntity();
        if ($this->request->is('post')) {
            $kelompokData = $this->KelompokData->patchEntity($kelompokData, $this->request->data);
            if ($this->KelompokData->save($kelompokData)) {
                $success = true;
                $message = __('kelompok data berhasil disimpan.');
            } else {
                $message = __('kelompok data tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($kelompokData, $success, $message);
    }*/

    /**
     * Edit method
     *
     * @param string|null $id Kelompok Data id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    /*public function edit($id = null)
    {
        $success = false;
        $message = '';

        $kelompokData = $this->KelompokData->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $kelompokData = $this->KelompokData->patchEntity($kelompokData, $this->request->data);
            if ($this->KelompokData->save($kelompokData)) {
                $success = true;
                $message = __('kelompok data berhasil disimpan.');
            } else {
                $message = __('kelompok data tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($kelompokData, $success, $message);
    }*/

    /**
     * Delete method
     *
     * @param string|null $id Kelompok Data id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $kelompokData = $this->KelompokData->get($id);
        if ($this->KelompokData->delete($kelompokData)) {
            $success = true;
            $message = __('kelompok data berhasil dihapus.');
        } else {
            $message = __('kelompok data tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }

    public function getJenisSumberList()
    {
        $success = true;
        $message = '';

        $jenisSumberList = [];
        $jenisSumberList[] = array(
            'kode' => 'SQL',
            'label' => 'SQL',
        );
        $jenisSumberList[] = array(
            'kode' => 'Wizard',
            'label' => 'Wizard',
        );

        $data = array(
            'items' => $jenisSumberList
        );

        $this->setResponseData($data, $success, $message);
    }

    public function getTipeKelompokList()
    {
        $success = true;
        $message = '';

        $jenisSumberList = [];
        $jenisSumberList[] = array(
            'kode' => 'plural',
            'label' => 'Plural',
        );
        $jenisSumberList[] = array(
            'kode' => 'singular',
            'label' => 'Singular',
        );

        $data = array(
            'items' => $jenisSumberList
        );

        $this->setResponseData($data, $success, $message);
    }

    public function getWebServiceList()
    {
        $success = true;
        $message = '';

        $templateDataTable = TableRegistry::get('TemplateData');

        $kelompokData = $this->KelompokData->find('all', [
            'fields' => ['KelompokData.id', 'KelompokData.label_kelompok'],
            'contain' => [
                'TemplateData' => ['fields' => ['TemplateData.id', 'TemplateData.keterangan']]
            ],
            'conditions' => [
                'OR' => [
                    'TemplateData.keterangan ILIKE' => '%' . $this->_apiQueryString . '%',
                    'KelompokData.label_kelompok ILIKE' => '%' . $this->_apiQueryString . '%',
                ],
                'TemplateData.tipe_keluaran' => $templateDataTable::TIPE_COMBOGRID_SOURCE
            ],
            'limit' => $this->_autocompleteLimit
        ]);
        $data = array(
            'items' => $kelompokData
        );

        $this->setResponseData($data, $success, $message);
    }

    public function getCombogridConfig($id)
    {
        $success = true;
        $message = '';

        $kelompokDataTable = TableRegistry::get('KelompokData');
        $kelompokData = $kelompokDataTable->get($id);
        $data = $kelompokData->getCombogridConfig();

        $this->setResponseData($data, $success, $message);
    }
}
