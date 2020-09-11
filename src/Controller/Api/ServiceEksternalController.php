<?php
namespace App\Controller\Api;

/**
 * ServiceEksternal Controller
 *
 * @property \App\Model\Table\ServiceEksternalTable $ServiceEksternal
 */
class ServiceEksternalController extends ApiController
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
                    'LOWER(nama) ILIKE' => '%' . $this->_apiQueryString . '%',
                    'LOWER(deskripsi) ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ]
        ];

        $serviceEksternal = $this->paginate($this->ServiceEksternal);
        $paging = $this->request->params['paging']['ServiceEksternal'];
        $serviceEksternal = $this->addRowNumber($serviceEksternal);
        
        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $serviceEksternal,
            'total_items' => $paging['count']
        );

        $this->setResponseData($data, $success, $message);
    }

    public function getList()
    {
        $success = true;
        $message = '';

        $serviceEksternal = $this->ServiceEksternal->find('all', [
            'fields' => ['ServiceEksternal.id', 'ServiceEksternal.nama', 'ServiceEksternal.deskripsi'],
            'conditions' => [
                'OR' => [
                    'LOWER(ServiceEksternal.nama) ILIKE' => '%' . $this->_apiQueryString . '%',
                    'LOWER(ServiceEksternal.deskripsi) ILIKE' => '%' . $this->_apiQueryString . '%'
                ]
            ],
            'limit' => $this->_autocompleteLimit
        ]);

        $data = array(
            'items' => $serviceEksternal
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * Get List of Tipe Otentikasi
     *
     * @return void
     */
    public function getTipeOtentikasiList()
    {
        $success = true;
        $message = '';

        $tipeOtentikasiList = [];
        
        $tipeOtentikasiList[] = array(
            'kode' => 'No Authentication',
            'label' => 'No Authentication',
        );

        $tipeOtentikasiList[] = array(
            'kode' => 'Basic Authentication',
            'label' => 'Basic Authentication',
        );

        $data = array(
            'items' => $tipeOtentikasiList
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Service Eksternal id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $serviceEksternal = $this->ServiceEksternal->get($id, [
            'fields' => [
                'id', 'nama', 'deskripsi', 'base_url', 'tipe_otentikasi', 'username'
            ],
            'contain' => []
        ]);

        $this->setResponseData($serviceEksternal, $success, $message);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $success = false;
        $message = '';

        $serviceEksternal = $this->ServiceEksternal->newEntity();
        
        if ($this->request->is('post')) {
            $serviceEksternal = $this->ServiceEksternal->patchEntity($serviceEksternal, $this->request->getData());
            
            if ($this->ServiceEksternal->save($serviceEksternal)) {
                $success = true;
                $message = __('Service eksternal berhasil disimpan.');
            } else {
                $message = __('Service eksternal tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }
        
        $this->setResponseData($serviceEksternal, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Service Eksternal id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $serviceEksternal = $this->ServiceEksternal->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $serviceEksternal = $this->ServiceEksternal->patchEntity($serviceEksternal, $this->request->getData());
            
            if ($this->ServiceEksternal->save($serviceEksternal)) {
                $success = true;
                $message = __('Service eksternal berhasil disimpan.');
            } else {
                $message = __('Service eksternal tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($serviceEksternal, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Service Eksternal id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = [];

        $this->request->allowMethod(['post', 'delete']);
        $serviceEksternal = $this->ServiceEksternal->get($id);
        
        if ($this->ServiceEksternal->delete($serviceEksternal)) {
            $success = true;
            $message = __('Service eksternal berhasil dihapus.');
        } else {
            $message = __('Service eksternal tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData($data, $success, $message);
    }
}
