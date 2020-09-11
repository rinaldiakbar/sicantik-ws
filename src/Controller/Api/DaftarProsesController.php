<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

/**
 * DaftarProses Controller
 *
 * @property \App\Model\Table\DaftarProsesTable $DaftarProses
 */
class DaftarProsesController extends ApiController
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
            'contain' => ['AlurProses', 'JenisProses'],
            'conditions' => [
                'OR' => [
                    'DaftarProses.nama_proses ILIKE' => '%' . $this->_apiQueryString . '%',
                    'AlurProses.keterangan ILIKE' => '%' . $this->_apiQueryString . '%',
                    'JenisProses.kode ILIKE' => '%' . $this->_apiQueryString . '%',
                    'JenisProses.nama_proses ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];
        $daftarProses = $this->paginate($this->DaftarProses);
        $paging = $this->request->params['paging']['DaftarProses'];
        $daftarProses = $this->addRowNumber($daftarProses);
        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $daftarProses,
            'total_items' => $paging['count']
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Daftar Prose id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $daftarProses = $this->DaftarProses->get($id, [
            'contain' => ['AlurProses', 'JenisProses']
        ]);

        $this->setResponseData($daftarProses, $success, $message);
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

        $daftarProses = $this->DaftarProses->newEntity();
        if ($this->request->is('post')) {
            $daftarProses = $this->DaftarProses->patchEntity($daftarProses, $this->request->data);
            if ($this->DaftarProses->save($daftarProses)) {
                $success = true;
                $message = __('daftar proses berhasil disimpan.');
            } else {
                $message = __('daftar proses tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($daftarProses, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Daftar Prose id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $daftarProses = $this->DaftarProses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $daftarProses = $this->DaftarProses->patchEntity($daftarProses, $this->request->data);
            if ($this->DaftarProses->save($daftarProses)) {
                $success = true;
                $message = __('daftar proses berhasil disimpan.');
            } else {
                $message = __('daftar proses tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($daftarProses, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Daftar Prose id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $daftarProses = $this->DaftarProses->get($id);
        if ($this->DaftarProses->delete($daftarProses)) {
            $success = true;
            $message = __('Daftar Proses berhasil dihapus.');
        } else {
            $message = __('Daftar Proses tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData($data, $success, $message);
    }
}
