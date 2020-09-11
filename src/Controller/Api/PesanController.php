<?php
namespace App\Controller\Api;

use Cake\I18n\Time;
use Cake\Routing\Router;
use App\Service\UploadService;

/**
 * Pesan Controller
 *
 * @property \App\Model\Table\PesanTable $Pesan
 *
 * @method \App\Model\Entity\Pesan[] paginate($object = null, array $settings = [])
 */
class PesanController extends ApiController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->PesanDibaca = $this->loadModel('PesanDibaca');
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
        $conditions = [
            "(pengguna_id = {$user->id} OR pengguna_id IS NULL)"
        ];
        $tipe = $this->request->getQuery('tipe');

        if ($tipe) {
            $conditions['tipe'] = $tipe;
        }

        $conditions['OR'] = [
            'LOWER(judul) ILIKE' => '%'. $this->_apiQueryString . '%',
            'LOWER(pesan) ILIKE' => '%' . $this->_apiQueryString . '%',
            'LOWER(tipe) ILIKE' => '%' . $this->_apiQueryString . '%',
        ];

        $this->paginate = [
            'fields' => [
                'id', 'judul', 'pesan', 'tgl_dibuat', 'dibuat_oleh'
            ],
            'conditions' => $conditions
        ];

        \Cake\I18n\Time::setJsonEncodeFormat('dd-MM-Y HH:mm:ss');
        $pesan = $this->paginate($this->Pesan);
        $paging = $this->request->params['paging']['Pesan'];

        $pesan = $pesan->each(function ($value, $key) {
            $value->pesan = \Cake\Utility\Text::truncate($value->pesan, 50);
            return $value;
        });
        $pesan = $this->addRowNumber($pesan);

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $pesan,
            'total_items' => $paging['count']
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function getNotifikasi()
    {
        $success = true;
        $message = '';

        $user = $this->getCurrentUser();
        $conditions = [
            "(pengguna_id = {$user->id} OR pengguna_id IS NULL)"
        ];
        $tipe = $this->request->getQuery('tipe');

        if ($tipe) {
            $conditions['tipe'] = $tipe;
        }

        $conditions['OR'] = [
            'LOWER(Pesan.judul) ILIKE' => '%'. $this->_apiQueryString . '%',
            'LOWER(Pesan.pesan) ILIKE' => '%' . $this->_apiQueryString . '%',
            'LOWER(Pesan.grup_notifikasi) ILIKE' => '%' . $this->_apiQueryString . '%',
            'pengguna_id' => $user->id
        ];

        $this->paginate = [
            'contain' => [
                'PesanDibaca' => [
                    'fields' => [
                        'PesanDibaca.id','PesanDibaca.pesan_id','PesanDibaca.pegawai_id'
                    ]
                ]
            ],
            'fields' => [
                'Pesan.id', 'Pesan.judul', 'Pesan.pesan', 'Pesan.grup_notifikasi',
                'Pesan.tgl_dibuat', 'Pesan.dibuat_oleh', 'Pesan.file_lampiran',
                'Pesan.tautan', 'Pesan.object_id'
            ],
            'conditions' => $conditions
        ];

        \Cake\I18n\Time::setJsonEncodeFormat('dd-MM-Y HH:mm:ss');
        $pesan = $this->paginate($this->Pesan);
        $paging = $this->request->params['paging']['Pesan'];

        $total = 0;
        if ($pesan->count() > 0) {
            foreach ($pesan as $key => $value) {
                if (count($value->pesan_dibaca) == 0) {
                    $total = $total + 1;
                }
            }
        }

        $pesan = $pesan->each(function ($value, $key) {
            $value->url_lampiran = Router::url(
                '/api/Pesan/downloadlampiran/' . $value->id,
                true
            );
            
            $value->pesan = \Cake\Utility\Text::truncate($value->pesan, 50);
            return $value;
        });

        // Group By
        if ($this->request->getQuery('grouped') == 'T') {
            $pesan = $pesan->groupBy('grup_notifikasi');
        } else {
            $pesan = $this->addRowNumber($pesan);
        }

        $data = array(
            'limit' => $paging['perPage'],
            'page' => $paging['page'],
            'items' => $pesan,
            'total_items' => $total
        );

        $this->setResponseData($data, $success, $message);
    }

    /**
     * View method
     *
     * @param string|null $id Notifikasi Pengguna id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pesan = $this->Pesan->get($id);
        $pesan->url_lampiran = Router::url(
            '/api/Pesan/downloadlampiran/' . $pesan->id,
            true
        );

        $this->setResponseData($pesan, true);
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
        $pesan = $this->Pesan->newEntity();

        if ($this->request->is('post')) {
            $pesan = $this->Pesan->patchEntity($pesan, $this->request->getData());

            if ($this->Pesan->save($pesan)) {
                $message = __('Pesan berhasil disimpan.');
                $success = true;
            } else {
                $this->setErrors($pesan->errors());
                $message = __('Pesan tidak dapat disimpan, silahkan coba kembali.');
            }
        }

        $this->setResponseData($pesan, $success, $message);
    }

    /**
     * Edit method
     *
     * @param string|null $id Notifikasi Pengguna id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';
        $pesan = $this->Pesan->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $pesan = $this->Pesan->patchEntity($pesan, $this->request->getData());

            if ($this->Pesan->save($pesan)) {
                $message = __('Pesan berhasil disimpan.');
                $success = true;
            } else {
                $this->setErrors($pesan->errors());
                $message = __('Pesan tidak dapat disimpan, silahkan coba kembali.');
            }
        }

        $this->setResponseData($pesan, $success, $message);
    }

    /**
     * Delete method
     *
     * @param string|null $id Notifikasi Pengguna id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pesan = $this->Pesan->get($id);

        if ($this->Pesan->delete($pesan)) {
            $this->Flash->success(__('The pesan has been deleted.'));
        } else {
            $this->Flash->error(__('The pesan could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Download Lampiran
     * @param int $id Notifikasi Pengguna Id
     */
    public function downloadLampiran($id)
    {
        $pesan = $this->Pesan->get($id);
        return $this->_downloadFile($pesan->file_lampiran);
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
            $uploadData = UploadService::upload('file');
            $data['file_name'] = $uploadData['file_name'];
            $data['file_url'] = $uploadData['url'];

            $success = true;
            $message = 'Lampiran berhasil diupload';
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        $this->setResponseData($data, $success, $message);
    }

    public function insertPesanDibaca(){
        $success = false;
        $message = '';
        $conditions = [];
        $user = $this->getCurrentUser();
        $conditions['OR'] = [
            'LOWER(judul) ILIKE' => '%'. $this->_apiQueryString . '%',
            'LOWER(pesan) ILIKE' => '%' . $this->_apiQueryString . '%',
            'LOWER(grup_notifikasi) ILIKE' => '%' . $this->_apiQueryString . '%',
            'pengguna_id' => $user->id
        ];

        $this->paginate = [
            'contain' => [
                'PesanDibaca' => [
                    'fields' => [
                        'PesanDibaca.id','PesanDibaca.pesan_id','PesanDibaca.pegawai_id'
                    ]
                ]
            ],
            'fields' => [
                'id', 'judul', 'pesan', 'grup_notifikasi'
            ],
            'conditions' => $conditions
        ];

        $pesan  = $this->paginate($this->Pesan);
        $paging = $this->request->params['paging']['Pesan'];

        if ($pesan->count() > 0) {
            $data_pesan_dibaca = [];
            $pesan_dibaca = $this->PesanDibaca->newEntity();
            $save = 0;
            foreach ($pesan as $idx => $value) {
                if (count($value->pesan_dibaca) == 0) {
                    $data_pesan_dibaca['pesan_id'] = $value->id;
                    $data_pesan_dibaca['pegawai_id'] = $user->pegawai_id;
                    $data_pesan_dibaca['keterangan'] = 'sudah_dibaca';
                    $pesan_dibaca = $this->PesanDibaca->patchEntity($pesan_dibaca, $data_pesan_dibaca);
                    $save = $this->PesanDibaca->save($pesan_dibaca);
                }
            }

            if ($save) {
                $success = true;
                $message = __('pesan_dibaca berhasil disimpan.');
            } else {
                $message = __('pesan_dibaca tidak ditemukan. Belum ada notifikasi terbaru.');
            }
        }
        $this->setResponseData($pesan_dibaca, $success, $message);
    }
}
