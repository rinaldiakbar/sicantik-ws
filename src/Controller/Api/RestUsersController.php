<?php
/**
 * Created by Indra.
 * User: Core
 * Date: 10/14/2017
 * Time: 8:07 PM
 */

namespace App\Controller\Api;

class RestUsersController extends ApiController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('RestUsers');
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
            'contain' => [
                'Instansi' => [
                    'fields' => ['id', 'nama']
                ]
            ],
            'conditions' => [
                'OR' => [
                    'LOWER(RestUsers.username) ILIKE' => '%' . $this->_apiQueryString . '%',
                ]
            ],
            'order' => [
                'username' => 'ASC'
            ]
        ];

        $pengguna = $this->paginate($this->RestUsers);
        $paging = $this->request->params['paging']['RestUsers'];
        $pengguna = $this->addRowNumber($pengguna);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $pengguna,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id RestUsers id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $this->RestUsers->setFilteredBeforeFind(false);
        $pengguna = $this->RestUsers->get($id, [
            'contain' => [
                'Instansi' => [
                    'fields' => ['id', 'nama']
                ]
            ]
        ]);

        $this->setResponseData($pengguna, $success, $message);
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

        try {
            $pengguna = $this->RestUsers->newEntity();

            if ($this->request->is('post')) {
                // Cleanup and check if username already exists
                $username = str_replace(' ', '_', $this->request->data['username']);
                $this->request->data['username'] = $username;

                $pengguna = $this->RestUsers->patchEntity($pengguna, $this->request->data);

                if ($this->RestUsers->save($pengguna)) {
                    $success = true;
                    $message = __('Pengguna REST berhasil disimpan.');
                } else {
                    $this->setErrors($pengguna->errors());
                    $message = __('Pengguna REST tidak berhasil disimpan. Silahkan coba kembali.');
                }
            }

        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        $this->setResponseData($pengguna, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id RestUsers id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        try {
            $this->RestUsers->setFilteredBeforeFind(false);
            $pengguna = $this->RestUsers->get($id);

            if ($this->request->is(['patch', 'post', 'put'])) {
                // Cleanup and check if username already exists
                $username = str_replace(' ', '_', $this->request->data['username']);
                $this->request->data['username'] = $username;
                $pengguna = $this->RestUsers->patchEntity($pengguna, $this->request->data);

                if ($this->RestUsers->save($pengguna)) {
                    $success = true;
                    $message = __('Pengguna REST berhasil disimpan.');
                } else {
                    $this->setErrors($pengguna->errors());
                    $message = __('Pengguna REST tidak berhasil disimpan. Silahkan coba kembali.');
                }
            }
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        $this->setResponseData($pengguna, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id RestUsers id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['delete']);
        $pengguna = $this->RestUsers->get($id);
        if ($this->RestUsers->delete($pengguna)) {
            $success = true;
            $message = __('Pengguna REST berhasil dihapus.');
        } else {
            $message = __('Pengguna REST tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }
}