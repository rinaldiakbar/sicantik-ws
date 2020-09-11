<?php
namespace App\Controller\Api;

use App\Model\Entity\KelompokKondisi;

/**
 * KelompokKondisi Controller
 *
 * @property \App\Model\Table\KelompokKondisiTable $KelompokKondisi
 */
class KelompokKondisiController extends ApiController
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
                    'KelompokKondisi.nama_tabel_utama ILIKE' => '%' . $this->_apiQueryString . '%',
                    'KelompokKondisi.nama_tabel_1 ILIKE' => '%' . $this->_apiQueryString . '%',
                    'KelompokKondisi.nama_kolom_1 ILIKE' => '%' . $this->_apiQueryString . '%',
                    'KelompokKondisi.nama_tabel_2 ILIKE' => '%' . $this->_apiQueryString . '%',
                    'KelompokKondisi.nama_kolom_2 ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];

        $templateKondisi = $this->paginate($this->KelompokKondisi);
        $paging = $this->request->params['paging']['KelompokKondisi'];
        $templateKondisi = $this->addRowNumber($templateKondisi);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $templateKondisi,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Template Kondisi id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $templateKondisi = $this->KelompokKondisi->get($id, [
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

        $this->setResponseData($templateKondisi, $success, $message);
    }

    /**
 * Get Options for Tipe Kondisi
 */
    public function getTipeKondisiList()
    {
        $success = true;
        $message = '';

        $tipeKondisiList = [];
        $tipeKondisiList[] = array(
            'kode' => '=',
            'label' => '=',
        );
        $tipeKondisiList[] = array(
            'kode' => '<>',
            'label' => '<>',
        );
        $tipeKondisiList[] = array(
            'kode' => '>',
            'label' => '>',
        );
        $tipeKondisiList[] = array(
            'kode' => '<',
            'label' => '<',
        );
        $tipeKondisiList[] = array(
            'kode' => '>=',
            'label' => '>=',
        );
        $tipeKondisiList[] = array(
            'kode' => '<=',
            'label' => '<=',
        );

        $data = array(
            'items' => $tipeKondisiList
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * Get Options for Tipe Relasi
     */
    public function getTipeRelasiList()
    {
        $success = true;
        $message = '';

        $tipeRelasiList = [];
        $tipeRelasiList[] = array(
            'kode' => 'AND',
            'label' => 'AND',
        );
        $tipeRelasiList[] = array(
            'kode' => 'OR',
            'label' => 'OR',
        );

        $data = array(
            'items' => $tipeRelasiList
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

        $templateKondisi = $this->KelompokKondisi->newEntity();
        if ($this->request->is('post')) {
            $templateKondisi = $this->KelompokKondisi->patchEntity($templateKondisi, $this->request->data);
            if ($this->KelompokKondisi->save($templateKondisi)) {
                $success = true;
                $message = __('Template Kondisi berhasil disimpan.');
            } else {
                $message = __('Template Kondisi tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        $this->setResponseData($templateKondisi, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Template Kondisi id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $templateKondisi = $this->KelompokKondisi->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $templateKondisi = $this->KelompokKondisi->patchEntity($templateKondisi, $this->request->data);
            if ($this->KelompokKondisi->save($templateKondisi)) {
                $success = true;
                $message = __('Template Kondisi berhasil disimpan.');
            } else {
                $this->Flash->error(__('Template Kondisi tidak berhasil disimpan. Silahkan coba kembali.'));
            }
        }
        $this->setResponseData($templateKondisi, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Template Kondisi id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['post', 'delete']);
        $templateKondisi = $this->KelompokKondisi->get($id);
        if ($this->KelompokKondisi->delete($templateKondisi)) {
            $success = true;
            $message = __('Template Kondisi berhasil dihapus.');
        } else {
            $message = __('Template Kondisi tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }
}
