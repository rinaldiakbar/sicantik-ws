<?php
namespace App\Controller\Api;

use Cake\Routing\Router;
use App\Service\UploadService;

/**
 * Faq Controller
 *
 * @property \App\Model\Table\FaqTable $Faq
 *
 * @method \App\Model\Entity\Faq[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FaqController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index', 'view', 'downloadLampiran']);
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
            'contain' => [
                'FaqCategory' => [
                    'fields' => ['id', 'nama']
                ]
            ],
            'conditions' => [
                'OR' => [
                    'Faq.pertanyaan ILIKE' => '%' . $this->_apiQueryString . '%',
                ]
            ],
            'order' => ['Faq.no_urut' => 'ASC']
        ];

        if ($this->request->getQuery('is_active') == 'T') {
            $this->paginate['conditions'][] = ['Faq.is_active' => '1'];
            $this->paginate['contain']['FaqCategory']['conditions'] = ['FaqCategory.is_active' => '1'];
        }

        if ($this->request->getQuery('category_id')) {
            $categoryId = (int) $this->request->getQuery('category_id');
            $this->paginate['conditions'][] = ['Faq.faq_category_id' => $categoryId];
        }

        $faq = $this->paginate($this->Faq);
        $faq = $this->addRowNumber($faq);
        $paging = $this->request->params['paging']['Faq'];

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $faq,
            'total_items' => $paging['count']
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Faq id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $faq = $this->Faq->get($id, [
            'contain' => [
                'FaqCategory' => ['fields' => ['id', 'nama']]
            ]
        ]);
        $faq->url_lampiran = Router::url(
            '/api/Faq/downloadlampiran/' . $faq->id,
            true
        );

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

        $faq = $this->Faq->newEntity();

        if ($this->request->is('post')) {
            $faq = $this->Faq->patchEntity($faq, $this->request->data);

            if ($this->Faq->save($faq)) {
                $success = true;
                $message = __('FAQ berhasil disimpan.');
            } else {
                $this->setErrors($faq->errors());
                $message = __('FAQ tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($faq, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Faq id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $faq = $this->Faq->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $faq = $this->Faq->patchEntity($faq, $this->request->data);

            if ($this->Faq->save($faq)) {
                $success = true;
                $message = __('FAQ berhasil disimpan.');
            } else {
                $message = __('FAQ tidak berhasil disimpan. Silahkan coba kembali.');
            }
        }

        $this->setResponseData($faq, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Faq id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = [];

        $this->request->allowMethod(['post', 'delete']);
        $faq = $this->Faq->get($id);

        if ($this->Faq->delete($faq)) {
            $success = true;
            $message = __('FAQ berhasil dihapus.');
        } else {
            $message = __('FAQ tidak berhasil dihapus. Silahkan coba kembali.');
        }

        $this->setResponseData($data, $success, $message);
    }

    /**
     * Uplaod Template Data file
     */
    public function upload()
    {
        $data = [];
        $success = false;
        $message = '';

        try {
            UploadService::setInstansiID($this->getInstansiIdFromDataOrSession());
            $uploadData = UploadService::upload('file', 'faq');
            $data['file_name'] = $uploadData['file_name'];
            $data['file_url'] = $uploadData['url'];

            $success = true;
            $message = 'Lampiran berhasil diupload';
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        $this->setResponseData($data, $success, $message);
    }

    /**
     * Download Lampiran
     * @param int $id Notifikasi Pengguna Id
     */
    public function downloadLampiran($id)
    {
        $faq= $this->Faq->get($id);
        return $this->_downloadFile($faq->file_lampiran, 'faq');
    }
}