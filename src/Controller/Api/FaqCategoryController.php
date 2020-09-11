<?php
namespace App\Controller\Api;

/**
 * FaqCategory Controller
 *
 * @property \App\Model\Table\FaqCategoryTable $FaqCategory
 *
 * @method \App\Model\Entity\FaqCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FaqCategoryController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index', 'view']);
    }

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
            'fields' => ['id', 'no_urut', 'nama', 'deskripsi', 'is_active'],
            'order' => ['FaqCategory.no_urut' => 'ASC'],
            'conditions' => []
        ];

        if ($this->request->getQuery('is_active') == 'T') {
            $this->paginate['conditions'][] = ['FaqCategory.is_active' => '1'];
        }

        if ($this->request->getQuery('is_detail') == 'T') {
            $this->paginate['contain'] = [
                'TopFaq' => [
                    'fields' => ['id', 'pertanyaan', 'faq_category_id'],
                    'conditions' => [
                        'TopFaq.search ILIKE' => "%{$this->_apiQueryString}%",
                    ]
                ]
            ];
        } else {
            $this->paginate['conditions']['FaqCategory.nama ILIKE'] = '%' . $this->_apiQueryString . '%';
        }

        $faq = $this->paginate($this->FaqCategory);
        $faq = $this->addRowNumber($faq);

        $paging = $this->request->params['paging']['FaqCategory'];
        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $faq,
            'total_items' => $paging['count']
        );

        $this->setResponseData($data, $success, $message);
    }

    public function getList()
    {
        $success = true;
        $message = '';
        $conditions = [];

        $categories = $this->FaqCategory->find('all', [
            'fields' => ['FaqCategory.id', 'FaqCategory.nama'],
            'conditions' => [
                'OR' => [
                    'FaqCategory.nama ILIKE' => '%' . $this->_apiQueryString . '%',
                ],
                'FaqCategory.is_active' => true
            ],
            'order' => ['FaqCategory.no_urut' => 'ASC'],
            'limit' => $this->_autocompleteLimit
        ]);
        $data = array(
            'items' => $categories
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id FaqCategory id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $faq = $this->FaqCategory->get($id, [
            'contain' => [
                'Faq' => [
                    'fields' => ['id', 'pertanyaan', 'faq_category_id'],
                    'sort' => ['no_urut' => 'ASC'],
                    'conditions' => ['is_active' => true]
                ]
            ]
        ]);

        $this->setResponseData($faq, $success, $message);
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

        $faq = $this->FaqCategory->newEntity();

        if ($this->request->is('post')) {
            $faq = $this->FaqCategory->patchEntity($faq, $this->request->data);

            if ($this->FaqCategory->save($faq)) {
                $success = true;
                $message = __('FAQ Category berhasil disimpan.');
            } else {
                $this->setErrors($faq->errors());
                $message = __('FAQ Category tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($faq, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id FaqCategory id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $faq = $this->FaqCategory->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $faq = $this->FaqCategory->patchEntity($faq, $this->request->data);

            if ($this->FaqCategory->save($faq)) {
                $success = true;
                $message = __('FAQ Category berhasil disimpan.');
            } else {
                $message = __('FAQ Category tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($faq, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id FaqCategory id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = [];

        $this->request->allowMethod(['post', 'delete']);
        $faq = $this->FaqCategory->get($id);

        if ($this->FaqCategory->delete($faq)) {
            $success = true;
            $message = __('FAQ Category berhasil dihapus.');
        } else {
            $message = __('FAQ Category tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData($data, $success, $message);
    }
}
