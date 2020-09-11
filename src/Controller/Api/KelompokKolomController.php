<?php
namespace App\Controller\Api;

use App\Model\Entity\KelompokKolom;

/**
 * KelompokKolom Controller
 *
 * @property \App\Model\Table\KelompokKolomTable $KelompokKolom
 */
class KelompokKolomController extends ApiController
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
                    'KelompokKolom.nama_tabel ILIKE' => '%' . $this->_apiQueryString . '%',
                    'KelompokKolom.nama_kolom ILIKE' => '%' . $this->_apiQueryString . '%',
                    'KelompokKolom.alias_kolom ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];

        $templateKolom = $this->paginate($this->KelompokKolom);
        $paging = $this->request->params['paging']['KelompokKolom'];
        $templateKolom = $this->addRowNumber($templateKolom);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $templateKolom,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Template Kolom id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';
        
        $templateKolom = $this->KelompokKolom->get($id, [
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

        $this->setResponseData($templateKolom, $success, $message);
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

        $templateKolom = $this->KelompokKolom->newEntity();
        if ($this->request->is('post')) {
            $templateKolom = $this->KelompokKolom->patchEntity($templateKolom, $this->request->data);
            if ($this->KelompokKolom->save($templateKolom)) {
                $success = true;
                $message = __('Template Kolom berhasil disimpan.');
            } else {
                $message = __('Template Kolom tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($templateKolom, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Template Kolom id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $templateKolom = $this->KelompokKolom->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $templateKolom = $this->KelompokKolom->patchEntity($templateKolom, $this->request->data);
            if ($this->KelompokKolom->save($templateKolom)) {
                $success = true;
                $message = __('Template Kolom berhasil disimpan.');
            } else {
                $this->Flash->error(__('Template Kolom tidak berhasil disimpan. Silahkan coba kembali.'));
            }
        }
        $this->setResponseData($templateKolom, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Template Kolom id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $templateKolom = $this->KelompokKolom->get($id);
        if ($this->KelompokKolom->delete($templateKolom)) {
            $success = true;
            $message = __('Template Kolom berhasil dihapus.');
        } else {
            $message = __('Template Kolom tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }
}
