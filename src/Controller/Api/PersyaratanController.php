<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\Persyaratan;

/**
 * Persyaratan Controller
 *
 * @property \App\Model\Table\PersyaratanTable $Persyaratan
 */
class PersyaratanController extends ApiController
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
            'contain' => ['PermohonanIzin'],
            'conditions' => [
                'OR' => [
                    'LOWER(Persyaratan.keterangan) ILIKE' => '%' . $this->_apiQueryString . '%',
                    'LOWER(Persyaratan.no_dokumen) ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];
        $persyaratan = $this->paginate($this->Persyaratan);
        $paging = $this->request->params['paging']['Persyaratan'];
        $persyaratan = $this->addRowNumber($persyaratan);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $persyaratan,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Persyaratan id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $persyaratan = $this->Persyaratan->get($id, [
            'contain' => ['PermohonanIzin']
        ]);

        $this->setResponseData($persyaratan, $success, $message);
    }

    public function getByPermohonan($permohonanIzinId, $jenisDokumenId)
    {
        $success = false;
        $message = '';

        $persyaratan = $this->Persyaratan->find('all', [
            'fields' => [
                'no_dokumen', 'awal_berlaku', 'akhir_berlaku'
            ],
            'conditions' => [
                'jenis_dokumen_id' => $jenisDokumenId
            ],
            'contain' => [
                'PermohonanIzin' => [
                    'fields' => ['PermohonanIzin.id'],
                    'conditions' => ['PermohonanIzin.id' => $permohonanIzinId]
                ]
            ],
        ])->first();

        if ($persyaratan) {
            $success = true;
        }

        $this->setResponseData($persyaratan, $success, $message);
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

        $persyaratan = $this->Persyaratan->newEntity();
        if ($this->request->is('post')) {
            $persyaratan = $this->Persyaratan->patchEntity($persyaratan, $this->request->data);
            if ($this->Persyaratan->save($persyaratan)) {
                $success = true;
                $message = __('persyaratan berhasil disimpan.');
            } else {
                $message = __('persyaratan tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($persyaratan, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Persyaratan id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $persyaratan = $this->Persyaratan->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $persyaratan = $this->Persyaratan->patchEntity($persyaratan, $this->request->data);
            if ($this->Persyaratan->save($persyaratan)) {
                $success = true;
                $message = __('persyaratan berhasil disimpan.');
            } else {
                $message = __('persyaratan tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($persyaratan, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Persyaratan id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $persyaratan = $this->Persyaratan->get($id);
        if ($this->Persyaratan->delete($persyaratan)) {
            $success = true;
            $message = __('persyaratan berhasil dihapus.');
        } else {
            $message = __('persyaratan tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }
}
