<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\Pengguna;
use App\Service\AuthService;
use App\Service\NotificationService;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\Filesystem\File;
use Cake\Routing\Router;
use Cake\I18n\Time;

/**
 * Pengguna Controller
 *
 * @property \App\Model\Table\PenggunaTable $Pengguna
 */
class PenggunaController extends ApiController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Pengguna->setInstansi($this->getCurrentInstansi());
        $this->Pengguna->setUser($this->getCurrentUser());
    }

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow([
            'add', 'token', 'signup', 'forgotPassword', 'checkResetToken', 'resetPassword'
        ]);
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
            'fields' => [
                'id', 'username', 'email', 'peran_id', 'pegawai_id'
            ],
            'contain' => [
                'Peran' => ['fields' => ['label_peran']],
                'Pegawai' => ['fields' => ['nama']]
            ],
            'conditions' => [
                'OR' => [
                    'LOWER(Peran.label_peran) ILIKE' => '%' . $this->_apiQueryString . '%',
                    'LOWER(Pengguna.username) ILIKE' => '%' . $this->_apiQueryString . '%',
                    'LOWER(Pengguna.email) ILIKE' => '%' . $this->_apiQueryString . '%',
                ]
            ],
            'order' => [
                'username' => 'ASC'
            ]
        ];

        $pengguna = $this->paginate($this->Pengguna);
        $paging = $this->request->params['paging']['Pengguna'];
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
     * @param string|null $id Pengguna id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $success = true;
        $message = '';

        $this->Pengguna->setFilteredBeforeFind(false);
        $pengguna = $this->Pengguna->get($id, [
            'contain' => [
                'Peran', 'Pegawai',
                'JenisIzin' => [
                    'fields' => [
                        'JenisIzin.id',
                        'JenisIzin.jenis_izin',
                        'JenisIzinPengguna.pengguna_id'
                    ]
                ],
                'Unit' => [
                    'fields' => [
                        'Unit.id',
                        'Unit.nama',
                        'Unit.tipe',
                        'UnitPengguna.pengguna_id'
                    ]
                ],
                'JenisProses' => [
                    'fields' => [
                        'JenisProses.id',
                        'JenisProses.nama_proses',
                        'JenisProsesPengguna.pengguna_id'
                    ]
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
            $pengguna = $this->Pengguna->newEntity();

            if ($this->request->is('post')) {
                // Cleanup and check if username already exists
                $username = str_replace(' ', '_', $this->request->data['username']);
                $this->request->data['username'] = $username;

                $pengguna = $this->Pengguna->patchEntity(
                    $pengguna,
                    $this->request->data,
                    [
                        'associated' => [
                            'Unit._joinData',
                            'JenisIzin._joinData',
                            'JenisProses._joinData'
                        ]
                    ]
                );
                if ($this->Pengguna->save($pengguna)) {
                    $success = true;
                    $message = __('Pengguna berhasil disimpan.');
                } else {
                    $this->setErrors($pengguna->errors());
                    $message = __('Pengguna tidak berhasil disimpan. Silahkan coba kembali.');
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
     * @param string|null $id Pengguna id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $success = false;
        $message = '';

        try {
            $this->Pengguna->setFilteredBeforeFind(false);
            $this->Pengguna->Unit->setInstansi($this->getCurrentInstansi());
            $this->Pengguna->JenisIzin->setInstansi($this->getCurrentInstansi());
            $this->Pengguna->JenisProses->setInstansi($this->getCurrentInstansi());

            $pengguna = $this->Pengguna->get($id);

            if ($this->request->is(['patch', 'post', 'put'])) {
                // Cleanup and check if username already exists
                $username = str_replace(' ', '_', $this->request->data['username']);
                $this->request->data['username'] = $username;

                $pengguna = $this->Pengguna->patchEntity(
                    $pengguna,
                    $this->request->data,
                    [
                        'associated' => [
                            'Unit._joinData',
                            'JenisIzin._joinData',
                            'JenisProses._joinData'
                        ]
                    ]
                );

                if ($this->Pengguna->save($pengguna)) {
                    $success = true;
                    $message = __('Pengguna berhasil disimpan.');
                } else {
                    $this->setErrors($pengguna->errors());
                    $message = __('Pengguna tidak berhasil disimpan. Silahkan coba kembali.');
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
     * @param string|null $id Pengguna id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['delete']);
        $pengguna = $this->Pengguna->get($id);

        if ($this->Pengguna->delete($pengguna)) {
            $success = true;
            $message = __('pengguna berhasil dihapus.');
        } else {
            $message = __('pengguna tidak berhasil dihapus. Silahkan coba kembali.');
        }
        $this->setResponseData($data, $success, $message);
    }

    public function token()
    {
        $data = [];
        $token = null;
        $success = false;
        $message = '';

        try {
            $this->_userData = null;
            $this->_instansiData = null;

            $pengguna = $this->Auth->identify();
            if (!$pengguna) {
                throw new UnauthorizedException('Pengguna atau password tidak valid');
            }

            $expireTime = 12 * 3600;
            if ($this->requestSource == 'android') {
                $expireTime = 24 * 30 * 3600;
            }

            $token = JWT::encode([
                'sub' => $pengguna['id'],
                'exp' =>  time() + $expireTime
            ], Security::salt());

            $success = true;
            $message = 'Login berhasil';

        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        if ($this->requestSource == 'android') {
            $data['token'] = $token;
            $this->setResponseData($data, $success, $message);
        } else {
            $data = $token;
            $this->setResponseData($data, $success, $message);
        }
    }

    public function logout()
    {
        $this->Auth->logout();
        $this->_userData = null;
        $this->_instansiData = null;
    }

    public function profile()
    {
        $success = true;
        $message = '';

        $userId = $this->Auth->user('id');
        $data = $this->Pengguna->get($userId, [
            'fields' => [
                'id', 'username', 'email', 'peran_id', 'pegawai_id', 'instansi_id', 'related_object_name',
                'related_object_id'
            ],
            'contain' => [
                'Peran' => [
                    'fields' => ['id', 'label_peran', 'home_path']
                ],
                'Pegawai' => [
                    'fields' => ['id', 'nama']
                ]
            ]
        ]);
        $data = $data->toArray();
        $data['name'] = '';

        // Get name from pemohon
        if ($data['related_object_name'] === AuthService::PEMOHON_OBJECT) {
            AuthService::setUser($data);
            $pemohon = AuthService::getUserPemohon();

            if ($pemohon) {
                $data['name'] = $pemohon->nama;
            }
        }
        
        $currentInstansi = $this->getCurrentInstansi();
        $currentUnit = $this->getCurrentUnit();

        // Read Logo from Instansi
        if ($currentInstansi) {
            $data['instansi_id'] = $currentInstansi->id;

            // Get Logo from current instansi
            $filePath = WWW_ROOT . 'files' . DS . 'logo' . DS . $currentInstansi->logo;
            $file = new File($filePath, false);

            if ($file->exists()) {
                $data['default_logo'] = Router::url('/webroot/files/logo/' . $currentInstansi->logo, true);
            }
        }

        if ($currentUnit) {
            $data['unit_id'] = $currentUnit->id;
        } else {
            $data['unit_id'] = null;
        }

        // If no logo is provided, give default logo
        if (!isset($data['default_logo'])) {
            $data['default_logo'] = Router::url('/webroot/img/logo-ex-tech.png', true);
//            $data['default_logo'] = Router::url('/webroot/img/logo-kominfo.jpg', true);
        }

        $this->setResponseData($data, $success, $message);
    }
    
    public function getSetting()
    {
        $success = true;
        $message = '';
        $data = [
            'default_logo' => null
        ];

        $currentInstansi = $this->getCurrentInstansi();
        if ($currentInstansi) {
            // Get Logo from current instansi
            $filePath = WWW_ROOT . 'files' . DS . 'logo' . DS . $currentInstansi->logo;
            $file = new File($filePath, false);
            if ($file->exists()) {
                $data['default_logo'] = Router::url('/webroot/files/logo/' . $currentInstansi->logo, true);
            }
        }

        $this->setResponseData($data, $success, $message);
    }

    public function deleteUnit($penggunaId, $unitId)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->request->allowMethod(['delete']);
        $pengguna = $this->Pengguna->get($penggunaId);
        $unit = $this->Pengguna->Unit->find()->where(['Unit.id' => $unitId])->toArray();

        if ($pengguna && $unit)
        {
            $this->Pengguna->Unit->unlink($pengguna, $unit);
            $success = true;
            $message = __('unit berhasil dihapus.');
        }
        else {
            $message = __('unit has not been deleted. Please, try again.');
        }

        $this->setResponseData($data, $success, $message);
    }

    public function deleteJenisIzin($penggunaId, $jenisIzinId)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->Pengguna->setFilteredBeforeFind(false);

        $this->request->allowMethod(['delete']);
        $pengguna = $this->Pengguna->get($penggunaId);
        $jenisIzin = $this->Pengguna->JenisIzin->find()->where(['JenisIzin.id' => $jenisIzinId])->toArray();

        if ($pengguna && $jenisIzin) {
            $this->Pengguna->JenisIzin->unlink($pengguna, $jenisIzin);
            $success = true;
            $message = __('Jenis Izin berhasil dihapus.');
        } else {
            $message = __('Jenis Izin tidak berhasil dihapus. Silahkan coba lagi');
        }

        $this->setResponseData($data, $success, $message);
    }

    public function deleteJenisProses($penggunaId, $jenisProsesId)
    {
        $success = false;
        $message = '';
        $data = array();

        $this->Pengguna->setFilteredBeforeFind(false);

        $this->request->allowMethod(['delete']);
        $pengguna = $this->Pengguna->get($penggunaId);
        $jenisProses = $this->Pengguna->JenisProses->find()->where(['JenisProses.id' => $jenisProsesId])->toArray();

        if ($pengguna && $jenisProses) {
            $this->Pengguna->JenisProses->unlink($pengguna, $jenisProses);
            $success = true;
            $message = __('Jenis Proses berhasil dihapus.');
        } else {
            $message = __('Jenis Proses tidak berhasil dihapus. Silahkan coba lagi.');
        }

        $this->setResponseData($data, $success, $message);
    }

    /**
     * Change Password method
     *
     * @param string|null $id Pengguna id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function changePassword()
    {
        $success = false;
        $message = '';
        $data = [];

        $validator = new \Cake\Validation\Validator();
        $validator
            ->requirePresence('password')
            ->notEmpty('password', 'Please fill the new password')
            ->requirePresence('id')
            ->notEmpty('id', 'User ID cannot be empty');
        $errors = $validator->errors($this->request->data());

        if (empty($errors)) {
            $id = $this->request->data['id'];
            $pengguna = $this->Pengguna->get($id);

            if ($this->request->is(['patch', 'post', 'put'])) {
                $pengguna->password = $this->request->data['password'];
                if ($this->Pengguna->save($pengguna)) {
                    $success = true;
                    $message = __('Password berhasil diganti.');
                } else {
                    $this->setErrors($pengguna->errors());
                    $message = __('Password tidak berhasil diganti. Silahkan coba kembali.');
                }
            }
        } else {
            $this->setErrors($errors);
        }

        $this->setResponseData($data, $success, $message);
    }

    /*public function signup()
    {
        $success = false;
        $message = '';
        $data = [];

        $validator = new \Cake\Validation\Validator();
        $validator
            ->requirePresence('username')
            ->notEmpty('username', 'Please fill the username')
            ->requirePresence('email')
            ->notEmpty('email', 'Please fill the email')
            ->email('email')
            ->requirePresence('password')
            ->notEmpty('password', 'Please fill the confirm password')
            ->requirePresence('confirm')
            ->notEmpty('confirm', 'Please fill the confirm password');
        $errors = $validator->errors($this->request->data());

        if (empty($errors)) {
            $pengguna = $this->Pengguna->newEntity();

            if ($this->request->is(['patch', 'post', 'put'])) {
                // Get Peran 'pemohon'
                $peranTable = TableRegistry::get('Peran');
                $peran = $peranTable->find('all', [
                    'conditions' => [
                        'label_peran' => AuthService::PEMOHON_OBJECT
                    ]
                ])->first();

                $pengguna = $this->Pengguna->patchEntity($pengguna, $this->request->data);
                $pengguna->peran_id = $peran->id;

                if ($this->Pengguna->save($pengguna)) {
                    $success = true;
                    $message = __('Registrasi berhasil.');

                    // TODO Send registration email
                    \App\Service\NotificationService::sendMessage(
                        $pengguna->email,
                        'Registrasi',
                        'Terima kasih telah melakukan pendaftaran. 
                        Anda akan mendapatkan notifikasi jika pendaftaran anda telah disetujui.'
                    );
                } else {
                    $this->setErrors($pengguna->errors());
                    $message = __('Registrasi tidak berhasil. Silahkan coba kembali.');
                }
            }
        } else {
            $this->setErrors($errors);
        }

        $this->setResponseData($data, $success, $message);
    }*/

    public function forgotPassword()
    {
        $success = false;
        $message = '';
        $data = [];

        $validator = new \Cake\Validation\Validator();
        $validator
            ->requirePresence('username')
            ->notEmpty('username', 'Please fill the username');
        $errors = $validator->errors($this->request->data());

        if (empty($errors) && $this->request->is(['patch', 'post', 'put'])) {
            $pengguna = $this->Pengguna->find('all', [
                'conditions' => ['username' => $this->request->data['username']]
            ])->first();

            if ($pengguna) {
                $referer = $this->request->env('HTTP_REFERER');
                $token = AuthService::generateResetPasswordToken($pengguna);
                $resetPasswordLink = $referer . '/#/reset/' . $token;
                $body = "Hi {$pengguna->username}, klik link berikut ini untuk mengganti password anda {$resetPasswordLink}";

                // Send forgot password email
                $sendEmail = NotificationService::sendMessage(
                    $pengguna->email,
                    'Reset Password',
                    $body
                );

                if ($sendEmail) {
                    $success = true;
                    $message = __('Email reset password telah dikirimkan. Silahkan cek email anda.');
                } else {
                    $this->setErrors($pengguna->errors());
                    $message = __('Tidak dapat reset password. Silahkan coba kembali.');
                }
            } else {
                $message = __('Pengguna dengan username tersebut tidak ditemukan.');
            }
        } else {
            $this->setErrors($errors);
        }

        $this->setResponseData($data, $success, $message);
    }

    public function checkResetToken()
    {
        $success = false;
        $message = '';
        $data = [];

        $validator = new \Cake\Validation\Validator();
        $validator
            ->requirePresence('token')
            ->notEmpty('token', 'Invalid token');
        $errors = $validator->errors($this->request->data());

        if (empty($errors) && $this->request->is(['patch', 'post', 'put'])) {
            $data = $this->Pengguna->find('all', [
                'fields' => [
                    'id', 'username'
                ],
                'conditions' => [
                    'reset_token' => $this->request->data['token'],
                    'tgl_expired_reset >=' => Time::now()
                ]
            ])->first();

            if (!$data) {
                $message = 'Token tidak valid';
            }
            $success = true;
        }

        $this->setResponseData($data, $success, $message);
    }

    public function resetPassword()
    {
        $success = false;
        $message = '';
        $data = [];

        $validator = new \Cake\Validation\Validator();
        $validator
            ->requirePresence('token')
            ->notEmpty('token', 'Invalid token')
            ->requirePresence('password')
            ->notEmpty('password', 'Please fill the new password')
            ->requirePresence('confirm')
            ->notEmpty('confirm', 'Please fill the confirm password');
        $errors = $validator->errors($this->request->data());

        if (empty($errors) && $this->request->is(['patch', 'post', 'put'])) {
            $pengguna = $this->Pengguna->find('all', [
                'fields' => [
                    'id', 'password'
                ],
                'conditions' => [
                    'reset_token' => $this->request->data['token'],
                    'tgl_expired_reset >=' => Time::now()
                ]
            ])->firstOrFail();

            $pengguna = $this->Pengguna->patchEntity($pengguna, $this->request->data);
            if ($this->Pengguna->save($pengguna)) {
                $success = true;
                $message = 'Password berhasil diganti. Silahkan login dengan password baru anda';
            } else {
                $this->setErrors($pengguna->errors());
                $message = __('Password tidak berhasil diganti.');
            }
        }

        $this->setResponseData($data, $success, $message);
    }

    public function getUserVars()
    {
        $success = true;
        $message = '';
        $this->setResponseData($this->getUserSessionVars(), $success, $message);
    }
}
