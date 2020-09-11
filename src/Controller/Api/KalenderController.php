<?php
namespace App\Controller\Api;

use App\Model\Entity\Kalender;
/**
 * Kalender Controller
 *
 * @property \App\Model\Table\KalenderTable $Kalender
 */
class KalenderController extends ApiController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Kalender->setInstansi($this->getCurrentInstansi());
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index($instansiId)
    {
        $success = true;
        $message = '';

        $this->paginate = [
            'conditions' => [
                'instansi_id' => $instansiId
            ]
        ];

        $kalender = $this->paginate($this->Kalender);
        $paging = $this->request->params['paging']['Kalender'];
        $kalender = $this->addRowNumber($kalender);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $kalender,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $instansiId Instansi Id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function getKalender($instansiId)
    {
        $success = true;
        $message = '';

        $kalenderTable = $this->Kalender;
        $kalenderHari = $kalenderTable->find('all', [
            'fields' => ['id', 'idx_hari', 'nama_hari', 'tanggal'],
            'conditions' => [
                'instansi_id' => $instansiId,
                'tipe' => $kalenderTable::TIPE_HARI
            ]
        ]);
        $kalenderTanggal = $kalenderTable->find('all', [
            'fields' => ['id', 'idx_hari', 'nama_hari', 'tanggal'],
            'conditions' => [
                'instansi_id' => $instansiId,
                'tipe' => $kalenderTable::TIPE_TANGGAL
            ]
        ]);
        $data = ['kalender_hari' => $kalenderHari, 'kalender_tanggal' => $kalenderTanggal];

        $this->setResponseData($data, $success, $message);
    }

    /**
     * Get List of Hari
     */
    public function getHariList()
    {
        $success = true;
        $message = '';
        $data = array(
            'items' => $this->Kalender->getHariList()
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Kalender id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $kalender = $this->Kalender->get($id, [
            'contain' => ['Instansi']
        ]);

        $this->setResponseData($kalender, $success, $message);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|void Redirects on successful add, renders view otherwise.
     */
    public function save()
    {
        $success = false;
        $message = '';
        $data = [];
        $dataIndex = 0;

        if ($this->request->is('post')) {
            $kalenderTable = $this->Kalender;
            $kalenderData = [];
            $hariList = $this->Kalender->getHariList();
            $requestData = $this->request->data;

            if (!empty($requestData['kalender_hari'])) {
                foreach ($requestData['kalender_hari'] as $hari) {
                    if (!isset($hari['id']) && array_key_exists($hari['idx_hari'], $hariList)) {
                        $kalenderData[$dataIndex++] = [
                            'tipe' => $kalenderTable::TIPE_HARI,
                            'idx_hari' => $hari['idx_hari'],
                            'nama_hari' => $hariList[$hari['idx_hari']],
                            'instansi_id' => $requestData['instansi_id']
                        ];
                        $dataIndex++;
                    }
                }
            }

            if (!empty($requestData['kalender_tanggal'])) {
                foreach ($requestData['kalender_tanggal'] as $tanggal) {
                    if (!isset($tanggal['id'])) {
                        $kalenderData[$dataIndex++] = [
                            'tipe' => $kalenderTable::TIPE_TANGGAL,
                            'tanggal' => $this->parseJsDate($tanggal['tanggal']),
                            'instansi_id' => $requestData['instansi_id']
                        ];
                        $dataIndex++;
                    }
                }
            }

            if (!empty($kalenderData)) {
                $kalenders = $this->Kalender->newEntities($kalenderData);

                if ($this->Kalender->saveMany($kalenders)) {
                    $success = true;
                    $message = __('Kalender berhasil disimpan.');
                } else {
                    $errors = [];

                    foreach ($kalenders as $kalender) {
                        $errors[] = $kalender->errors();
                    }
                    $this->setErrors($errors);
                    $message = __('Kalender tidak berhasil disimpan. Silahkan coba kembali.');
                }
            } else {
                $success = true;
                $message = __('Kalender berhasil disimpan.');
            }
        }

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

        $kalender = $this->Kalender->newEntity();
        if ($this->request->is('post')) {
            $kalender = $this->Kalender->patchEntity($kalender, $this->request->data);
            if ($this->Kalender->save($kalender)) {
                $success = true;
                $message = __('Kalender berhasil disimpan.');
            } else {
                $this->setErrors($kalender->errors());
                $message = __('Kalender tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($kalender, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Kalender id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $kalender = $this->Kalender->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $kalender = $this->Kalender->patchEntity($kalender, $this->request->data);
            if ($this->Kalender->save($kalender)) {
                $success = true;
                $message = __('Kalender berhasil disimpan.');
            } else {
                $this->setErrors($kalender->errors());
                $message = __('Kalender tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($kalender, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Kalender id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $kalender = $this->Kalender->get($id);

        if ($this->Kalender->delete($kalender)) {
            $success = true;
            $message = __('Kalender berhasil dihapus.');
        } else {
            $this->setErrors($kalender->errors());
            $message = __('Kalender tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }
}
