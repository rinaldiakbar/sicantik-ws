<?php
namespace App\Controller\Api;

use App\Model\Table\LaporanPermasalahanTable;

/**
 * LaporanPermasalahan Controller
 *
 * @property \App\Model\Table\LaporanPermasalahanTable $LaporanPermasalahan
 *
 * @method \App\Model\Entity\LaporanPermasalahan[] paginate($object = null, array $settings = [])
 */
class LaporanPermasalahanController extends ApiController
{

    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event); // TODO: Change the autogenerated stub
        $this->LaporanPermasalahan->setInstansi($this->getCurrentInstansi());
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

        $user = $this->getCurrentUser();
        $source = (isset($this->request->params['source']) && !empty($this->request->params['source']))
                    ? $this->request->params['source'] : LaporanPermasalahanTable::SOURCE_MOBILE;

        $this->paginate = [
            'contain' => ['Instansi'],
            'conditions' => [
                'LaporanPermasalahan.source' => $source,
                'LaporanPermasalahan.dibuat_oleh' => $user->username
            ]
        ];

        $laporanPermasalahan = $this->paginate($this->LaporanPermasalahan);
        $paging = $this->request->params['paging']['LaporanPermasalahan'];
        $laporanPermasalahan = $this->addRowNumber($laporanPermasalahan);

        $data = array(
//            'limit' => $paging['perPage'],
            'limit' => 9999,
            'page' => $paging['page'],
            'items' => $laporanPermasalahan,
            'total_items' => $paging['count']
        );
        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Laporan Permasalahan id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $laporanPermasalahan = $this->LaporanPermasalahan->get($id, [
            'contain' => ['Instansi']
        ]);

        $this->setResponseData($laporanPermasalahan, $success, $message);
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

        $laporanPermasalahan = $this->LaporanPermasalahan->newEntity();

        if ($this->request->is('post')) {

            if (!isset($this->request->data['source'])) {
                $this->request->data['source'] = LaporanPermasalahanTable::SOURCE_MOBILE;
            }

            $laporanPermasalahan = $this->LaporanPermasalahan->patchEntity($laporanPermasalahan, $this->request->data);

            if ($this->LaporanPermasalahan->save($laporanPermasalahan)) {
                $message = __('The laporan permasalahan has been saved.');
                $success = true;
            } else {
                $this->setErrors($laporanPermasalahan->getErrors());
                $message = __('The laporan permasalahan could not be saved. Please, try again.');
            }
        }

        $this->setResponseData($laporanPermasalahan, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Laporan Permasalahan id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        $laporanPermasalahan = $this->LaporanPermasalahan->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $laporanPermasalahan = $this->LaporanPermasalahan->patchEntity($laporanPermasalahan, $this->request->getData());

            if ($this->LaporanPermasalahan->save($laporanPermasalahan)) {
                $message = __('The laporan permasalahan has been saved.');
                $success = true;
            } else {
                $this->setErrors($laporanPermasalahan->getErrors());
                $message = __('The laporan permasalahan could not be saved. Please, try again.');
            }
        }

        $this->setResponseData($laporanPermasalahan, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Laporan Permasalahan id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';

        $this->request->allowMethod(['post', 'delete']);
        $laporanPermasalahan = $this->LaporanPermasalahan->get($id);

        if ($this->LaporanPermasalahan->delete($laporanPermasalahan)) {
            $success = true;
            $message = ('The laporan permasalahan has been deleted.');
        } else {
            $message = __('The laporan permasalahan could not be deleted. Please, try again.');
        }

        $this->setResponseData([], $success, $message);
    }
}
