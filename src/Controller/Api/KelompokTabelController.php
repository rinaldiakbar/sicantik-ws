<?php
namespace App\Controller\Api;

use App\Model\Entity\KelompokTabel;

/**
 * KelompokTabel Controller
 *
 * @property \App\Model\Table\KelompokTabelTable $KelompokTabel
 */
class KelompokTabelController extends ApiController
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
            'contain' => ['KelompokData'],
            'conditions' => [
                'OR' => [
                    'KelompokTabel.nama_tabel ILIKE' => '%' . $this->_apiQueryString . '%',
                    'KelompokTabel.tipe_join ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];
        
        $KelompokTabel = $this->paginate($this->KelompokTabel);
        $paging = $this->request->params['paging']['KelompokTabel'];
        $KelompokTabel = $this->addRowNumber($KelompokTabel);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $KelompokTabel,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Template Tabel id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $KelompokTabel = $this->KelompokTabel->get($id, [
            'contain' => [
                'KelompokData' => [
                    'fields' => [
                        'KelompokData.label_kelompok',
                        'KelompokData.jenis_sumber',
                        'KelompokData.sql',
                    ]
                ]
            ]
        ]);

        $this->setResponseData($KelompokTabel, $success, $message);
    }

    /**
     * Get Options for Tipe Join
     */
    public function getTipeJoinList()
    {
        $success = true;
        $message = '';

        $tipeJoinList = [];
        $tipeJoinList[] = array(
            'kode' => 'DIRECT',
            'label' => 'DIRECT',
        );
        $tipeJoinList[] = array(
            'kode' => 'INNER JOIN',
            'label' => 'INNER JOIN',
        );
        $tipeJoinList[] = array(
            'kode' => 'RIGHT JOIN',
            'label' => 'RIGHT JOIN',
        );
        $tipeJoinList[] = array(
            'kode' => 'LEFT JOIN',
            'label' => 'LEFT JOIN',
        );

        $data = array(
            'items' => $tipeJoinList
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

        $KelompokTabel = $this->KelompokTabel->newEntity();
        if ($this->request->is('post')) {
            $KelompokTabel = $this->KelompokTabel->patchEntity($KelompokTabel, $this->request->data);
            if ($this->KelompokTabel->save($KelompokTabel)) {
                $success = true;
                $message = __('Template Tabel berhasil disimpan.');
            } else {
                $message = __('Template Tabel tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($KelompokTabel, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Template Tabel id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $KelompokTabel = $this->KelompokTabel->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $KelompokTabel = $this->KelompokTabel->patchEntity($KelompokTabel, $this->request->data);
            if ($this->KelompokTabel->save($KelompokTabel)) {
                $success = true;
                $message = __('Template Tabel berhasil disimpan.');
            } else {
                $this->Flash->error(__('Template Tabel tidak berhasil disimpan. Silahkan coba kembali.'));
            }
        }
        $this->setResponseData($KelompokTabel, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Template Tabel id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $KelompokTabel = $this->KelompokTabel->get($id);
        if ($this->KelompokTabel->delete($KelompokTabel)) {
            $success = true;
            $message = __('Template Tabel berhasil dihapus.');
        } else {
            $message = __('Template Tabel tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }
}
