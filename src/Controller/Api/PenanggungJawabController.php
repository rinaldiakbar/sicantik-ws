<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\PenanggungJawab;

/**
 * PenanggungJawab Controller
 *
 * @property \App\Model\Table\PenanggungJawabTable $PenanggungJawab
 */
class PenanggungJawabController extends ApiController
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
            'conditions' => [
                'OR' => [
                    'LOWER(Unit.nama) LIKE' => '%' . $this->_apiQueryString . '%',
                    'LOWER(Pegawai.nama) LIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ],
            'contain' => ['Unit', 'Pegawai']
        ];
        $penanggungJawab = $this->paginate($this->PenanggungJawab);
        $paging = $this->request->params['paging']['PenanggungJawab'];
        $penanggungJawab = $this->addRowNumber($penanggungJawab);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $penanggungJawab,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Penanggung Jawab id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $penanggungJawab = $this->PenanggungJawab->get($id, [
            'contain' => ['Unit', 'Pegawai']
        ]);

        $this->setResponseData($penanggungJawab, $success, $message);
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

        $penanggungJawab = $this->PenanggungJawab->newEntity();
        if ($this->request->is('post')) {
            $penanggungJawab = $this->PenanggungJawab->patchEntity($penanggungJawab, $this->request->data);
            if ($this->PenanggungJawab->save($penanggungJawab)) {
                $message = __('penanggung jawab berhasil disimpan.');
                $success = true;
            } else {
                $message = __('penanggung jawab tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($penanggungJawab, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Penanggung Jawab id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $penanggungJawab = $this->PenanggungJawab->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $penanggungJawab = $this->PenanggungJawab->patchEntity($penanggungJawab, $this->request->data);
            if ($this->PenanggungJawab->save($penanggungJawab)) {
                $message = __('penanggung jawab berhasil disimpan.');
                $success = true;
            } else {
                $message = __('penanggung jawab tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($penanggungJawab, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Penanggung Jawab id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';

        $this->request->allowMethod(['post', 'delete']);
        $penanggungJawab = $this->PenanggungJawab->get($id);
        if ($this->PenanggungJawab->delete($penanggungJawab)) {
            $message = __('penanggung jawab berhasil dihapus.');
            $success = true;
        } else {
            $message = __('penanggung jawab tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData(array(), $success, $message);
    }
}
