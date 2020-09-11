<?php
namespace App\Controller\Api;

/**
 * RestServices Controller
 *
 * @property \App\Model\Table\RestServicesTable $RestServices
 *
 * @method \App\Model\Entity\RestService[] paginate($object = null, array $settings = [])
 */
class RestServicesController extends ApiController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $success = true;
        $message = '';

        $this->paginate = [
            'contain' => [
                'Datatabel' => ['fields' => ['id', 'nama_datatabel']],
                'Instansi' => ['fields' => ['id', 'nama']]
            ],
            'conditions' => [
                'OR' => [
                    'Datatabel.nama_datatabel ILIKE' => '%' . $this->_apiQueryString . '%',
                    'Instansi.nama ILIKE' => '%' . $this->_apiQueryString . '%',
                ]
            ],
        ];
        $restServices = $this->paginate($this->RestServices);
        $paging = $this->request->params['paging']['RestServices'];
        $restServices = $this->addRowNumber($restServices);
        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $restServices,
            'total_items' => $paging['count']
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Rest Service id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $restService = $this->RestServices->get($id, [
            'contain' => [
                'Datatabel' => ['fields' => ['id', 'nama_datatabel']],
                'Instansi' => ['fields' => ['id', 'nama']]
            ]
        ]);

        $this->setResponseData($restService, $success, $message);
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

        $restService = $this->RestServices->newEntity();

        if ($this->request->is('post')) {
            $restService = $this->RestServices->patchEntity($restService, $this->request->getData());

            if ($this->RestServices->save($restService)) {
                $success = true;
                $message = __('The rest service has been saved.');
            } else {
                $message = __('The rest service could not be saved. Please, try again.');
            }
        }

        $this->setResponseData($restService, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Rest Service id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $restService = $this->RestServices->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $restService = $this->RestServices->patchEntity($restService, $this->request->getData());

            if ($this->RestServices->save($restService)) {
                $success = true;
                $message = __('The rest service has been saved.');
            } else {
                $message = __('The rest service could not be saved. Please, try again.');
            }
        }

        $this->setResponseData($restService, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Rest Service id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';

        $this->request->allowMethod(['post', 'delete']);
        $restService = $this->RestServices->get($id);

        if ($this->RestServices->delete($restService)) {
            $success = true;
            $message = __('The rest service has been deleted.');
        } else {
            $message = __('The rest service could not be deleted. Please, try again.');
        }

        $this->setResponseData([], $success, $message);
    }
}
