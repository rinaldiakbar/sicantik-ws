<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\Pegawai;

/**
 * Pegawai Controller
 *
 * @property \App\Model\Table\PegawaiTable $Pegawai
 */
class PegawaiController extends ApiController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Pegawai->setInstansi($this->getCurrentInstansi());
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
            'contain' => ['Instansi', 'Jabatan'],
            'conditions' => [
                'OR' => [
                    'Pegawai.nama ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Pegawai.nomor_induk ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Pegawai.posisi ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Pegawai.no_hp ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Pegawai.email ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];
        $pegawai = $this->paginate($this->Pegawai);
        $paging = $this->request->params['paging']['Pegawai'];
        $pegawai = $this->addRowNumber($pegawai);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $pegawai,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    public function getList()
    {
        $success = true;
        $message = '';

        $pegawai = $this->Pegawai->find('all', [
            'fields' => ['id', 'nama', 'instansi_id'],
            'conditions' => [
                'OR' => [
                    'Pegawai.nama ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Pegawai.nomor_induk ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Pegawai.posisi ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Pegawai.no_hp ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Pegawai.email ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ],
            'limit' => $this->_autocompleteLimit
        ]);
        
        $data = array(
            'items' => $pegawai
        );

        $this->setResponseData($data, $success, $message);
    }

    public function getListPenanggungJawab()
    {
        $success = true;
        $message = '';

        $pegawai = $this->Pegawai->find('all', [
            'fields' => ['id', 'nama', 'instansi_id'],
            'join' => [
                'pengguna' => [
                    'table' => 'pengguna',
                    'type' => 'INNER',
                    'conditions' => 'pengguna.pegawai_id = Pegawai.id',
                ],
                'penanggung_jawab_peran' => [
                    'table' => 'penanggung_jawab_peran',
                    'type' => 'INNER',
                    'conditions' => 'penanggung_jawab_peran.peran_id = pengguna.peran_id',
                ]
            ],
            'conditions' => [
                'OR' => [
                    'Pegawai.nama ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Pegawai.nomor_induk ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Pegawai.posisi ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Pegawai.no_hp ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Pegawai.email ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ],
            'limit' => $this->_autocompleteLimit
        ]);

        $data = array(
            'items' => $pegawai
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Pegawai id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $pegawai = $this->Pegawai->get($id, [
            'contain' => [
                'Instansi' => ['fields' => ['id', 'nama', 'tipe', 'parent_id']],
                'Unit' => ['fields' => ['id', 'nama', 'tipe', 'parent_id']],
                'Jabatan' => ['fields' => ['id', 'jabatan', 'nama_jabatan', 'instansi_id']]
            ]
        ]);

        $this->setResponseData($pegawai, $success, $message);
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

        $pegawai = $this->Pegawai->newEntity();

        if ($this->request->is('post')) {
//            $this->Pegawai->setFilteredBeforeSave(false);
            $pegawai = $this->Pegawai->patchEntity($pegawai, $this->request->data);

            if ($this->Pegawai->save($pegawai)) {
                $success = true;
                $message = __('pegawai berhasil disimpan.');
            } else {
                $this->setErrors($pegawai->errors());
                $message = __('pegawai tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($pegawai, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Pegawai id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $pegawai = $this->Pegawai->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
//            $this->Pegawai->setFilteredBeforeSave(false);
            $pegawai = $this->Pegawai->patchEntity($pegawai, $this->request->data);

            if ($this->Pegawai->save($pegawai)) {
                $success = true;
                $message = __('pegawai berhasil disimpan.');
            } else {
                $this->setErrors($pegawai->errors());
                $message = __('pegawai tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($pegawai, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Pegawai id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';

        $this->request->allowMethod(['post', 'delete']);
        $pegawai = $this->Pegawai->get($id);
        if ($this->Pegawai->delete($pegawai)) {
            $success = true;
            $message = __('pegawai berhasil dihapus.');
        } else {
            $this->setErrors($pegawai->getErrors());
            $message = __('pegawai tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData($pegawai, $success, $message);
    }
}
