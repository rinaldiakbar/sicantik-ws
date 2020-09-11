<?php
/**
 * Created by Indra
 * Date: 4/11/2016
 * Time: 11:36 PM
 */

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Muffin\Footprint\Auth\FootprintAwareTrait;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use App\Service\AuthService;

class ApiController extends AppController
{
    use FootprintAwareTrait;

    const SOURCE_ANDROID = 'android';

    protected $overrideAuth = false;
    protected $_defaultDateFormat = 'dd-MM-yyyy'; // format needed by FrozenDate, not PHP Date
    protected $_defaultDateTimeFormat = 'dd-MM-yyyy HH:mm:ss';
    protected $_defaultDateFormatDisplay = 'd M Y';

    protected $_apiPage = 1;
    protected $_apiLimit = 10;
    protected $_apiQueryString = '';
    protected $_autocompleteLimit = 100;
    protected $flattenResponse = false;

    protected $_userData;
    protected $_roleData;
    protected $_instansiData;
    protected $_unitData;
    protected $jenisProsesIdsPengguna = [];
    protected $jenisIzinIdsPengguna = [];

    protected $_statusCode = null;
    protected $alwaysOKHttpStatus = false;
    protected $_errors = [];
    protected $requestSource;

    protected $loadParentAuth = true;

    public function initialize()
    {
        parent::initialize();

        // Be Careful to set this as this would break date parsed in Angular
//        Time::setJsonEncodeFormat($this->_defaultDateTimeFormat);  // For any mutable DateTime
//        FrozenTime::setJsonEncodeFormat($this->_defaultDateTimeFormat);  // For any immutable DateTime

//        Date::setJsonEncodeFormat($this->_defaultDateFormat);  // For any mutable Date
//        FrozenDate::setJsonEncodeFormat($this->_defaultDateFormat);  // For any immutable Date

        if ($this->loadParentAuth) {
            $this->loadComponent('Auth', [
                'storage' => 'Memory',
                'authenticate' => [
                    'Form' => [
                        'userModel' => 'Pengguna', // is used when identifying user
                        'fields' => [
                            'username' => 'username',
                            'password' => 'password',
                        ],
                        //                    'scope' => ['Users.active' => 1]
                    ],
                    'ADmad/JwtAuth.Jwt' => [
                        'header' => 'Authorization',
                        'prefix' => 'Bearer',
                        'parameter' => 'token',
                        //                    'userModel' => 'Users',
                        'userModel' => 'Pengguna',
                        //                    'scope' => ['Users.deleted' => 0],
                        'fields' => [
                            'username' => 'id'
                        ],
                        'queryDatasource' => true
                    ],
                    'MyCustomBasic' => [
                        'header' => 'Authorization',
                        'userModel' => 'Pengguna',
                        'queryDatasource' => true
                    ],
                ],
                'unauthorizedRedirect' => false,
                'checkAuthIn' => 'Controller.initialize'
            ]);
            $this->loadComponent('RequestHandler');
        }
    }

    public function beforeFilter(Event $event)
    {
        if ($this->loadParentAuth) {
            $this->_userModel = 'Pengguna';
        }
        parent::beforeFilter($event);

        // Determine the source of this request
        if ($this->request->hasHeader('Source')) {
            $sourceHeader  = $this->request->getHeader('Source');
            $this->requestSource = strtolower($sourceHeader[0]);

            switch ($this->requestSource) {
                case self::SOURCE_ANDROID:
                    $this->alwaysOKHttpStatus = true;
                    break;
            }
        }

        if ($this->request->hasHeader('Flatten')) {
            $flattenHeader  = $this->request->getHeader('Flatten');
            $this->flattenResponse = strtoupper($flattenHeader[0]) == 'T' ? true : false;
        }

        // Parse param from caller
        $currentPage = $this->request->getQuery('page') ? (int) $this->request->getQuery("page") : 1;
        $limit = $this->request->getQuery('limit') ? (int) $this->request->getQuery('limit') : 10;
        $q = $this->request->getQuery('q') ? strtolower($this->request->getQuery('q')) : '';
        $order = $this->request->getQuery('order') ? strtolower($this->request->getQuery('order')) : '';

        if ($q === 'undefined' || $q == 'null' || !$q) {
            $q = '';
        }

        $this->_apiPage = $currentPage;
        $this->_apiLimit = $limit;
        $this->_apiQueryString = strtolower($q);
        $this->_apiQueryOrder = (substr($order, 0, 1) == '-') ? (strlen($order) > 2 ? substr($order, 1) . ' DESC' : '') : ($order ? "$order ASC": '');

        $this->paginate = [
            'limit' => $this->_apiLimit,
            'page' => $this->_apiPage
        ];

        FrozenDate::setJsonEncodeFormat($this->_defaultDateFormat);
//        $this->eventManager()->off($this->Csrf);
    }

    public function setResponseData($data, $success = true, $message = '', $changeStatusCode = true)
    {
        $message = ucfirst($message);
        $response = $this->response->withHeader('Access-Control-Allow-Origin', '*');
        $response = $response->withHeader('Access-Control-Allow-Headers', 'origin, x-requested-with, content-type, accept');
        $response = $response->withHeader('Access-Control-Allow-Methods', 'OPTIONS, PUT, GET, POST, DELETE');

        if ($this->flattenResponse) {
            if (is_array($data) && array_key_exists(0, $data)) {
                foreach ($data as $idx => $dt) {
                    $data[$idx] = $this->array_flat($dt);
                }
            } elseif (isset($data['items'])) {
                if ($data['items'] instanceof \Cake\ORM\Query) {
                    $data['items'] = $data['items']->all()->toArray();
                } elseif ($data['items'] instanceof \Cake\ORM\ResultSet) {
                    $data['items'] = $data->items;
                }

                foreach ($data['items'] as $idx => $dt) {
                    $data['items'][$idx] = $this->array_flat($dt);
                }
            } else {
                $data = $this->array_flat($data);
            }
        }

        if (!$success) {
            if ($changeStatusCode && !is_null($this->_statusCode)) {
                $response = $response->withStatus($this->_statusCode);
            } else {
                $response = $response->withStatus(422);
            }

            if ($this->alwaysOKHttpStatus) {
                $response = $response->withStatus(200);
            }

            $errors = $this->_errors;
            $this->set(compact('success', 'message', 'data', 'errors'));
            $this->set('_serialize', ['success', 'message', 'data', 'errors']);
        } else {
            $this->set(compact('success', 'message', 'data'));
            $this->set('_serialize', ['success', 'message', 'data']);
        }

        $response = $response->withType('application/json');
        $this->response = $response;
        $this->viewBuilder()->className('Json');
    }

    public function addRowNumber($items)
    {
        $no = 1;
        if ($this->_apiPage > 1) {
            $no = (($this->_apiPage - 1) * $this->_apiLimit) + 1;
        }
        $data = array();
        $visibleProperties = array();

        foreach ($items as $index => $item) {
            $data[$index]['no'] = $no++;

            if ($item instanceof Entity) {
                if (empty($visibleProperties)) {
                    $visibleProperties = $item->visibleProperties();
                }
                foreach ($visibleProperties as $visibleProperty) {
                    $data[$index][$visibleProperty] = $item->$visibleProperty;
                }
            } else {
                foreach ($item as $fieldName=>$fieldValue) {
                    $data[$index][$fieldName] = $fieldValue;
                }
            }
        }

        return $data;
    }

    protected function _addCreatedInfo($requestData)
    {
        $userData = $this->getCurrentUser();

        if (is_array($requestData)) {
            $requestData['tgl_dibuat'] = date_create();
//            $requestData['tgl_dibuat'] = new DateTime('now');
            $requestData['dibuat_oleh'] = $userData->username;
        }
        return $requestData;
    }

    protected function _addModifiedInfo($requestData)
    {
        $userData = $this->getCurrentUser();

        if (is_array($requestData)) {
            $requestData['tgl_diubah'] = date_create();
            $requestData['diubah_oleh'] = $userData->username;
        }
        return $requestData;
    }

    protected function getCurrentUserId()
    {
        return $this->Auth->user('id');
    }

    protected function getCurrentUsername()
    {
        if (!$this->_userData) {
            $this->getCurrentUser();
        }
        return $this->_userData->username;
    }

    protected function getCurrentUser()
    {
        if (!$this->_userData) {
            $userData = $this->Auth->user();

            if (!empty($userData)) {
                $this->_userData = (object) $userData;
            }
        }
        return $this->_userData;
    }

    protected function getCurrentRole()
    {
        if (!$this->_roleData) {
            $userData = $this->getCurrentUser();

            $this->loadModel('Peran');

            $peranData = $this->Peran->get($userData->peran_id, [
                'fields' => [
                    'id', 'label_peran', 'home_path'
                ]
            ]);

            if ($peranData) {
                $this->_roleData = $peranData;
            }
        }

        return $this->_roleData;
    }

    protected function getCurrentInstansi()
    {
        try {
            if (!$this->_userData) {
                $userData = $this->getCurrentUser();
            }

            if ($this->_userData && !$this->_instansiData) {
                $this->loadModel('Pengguna');
                $penggunaData = $this->Pengguna->get($this->_userData->id, [
                    'fields' => [
                        'id'
                    ],
                    'contain' => [
                        'Pegawai' => [
                            'fields' => ['Pegawai.id']
                        ],
                        'Peran' => [
                            'fields' => ['Peran.id']
                        ],
                    ]
                ]);

                $peranData = $this->Pengguna->Peran->get($penggunaData->peran->id, [
                    'contain' => [
                        'Instansi' => [
                            'fields' => ['Instansi.id', 'Instansi.nama', 'Instansi.tipe', 'Instansi.logo']
                        ]
                    ]
                ]);

                if ($penggunaData->pegawai) {
                    $pegawaiData = $this->Pengguna->Pegawai->get($penggunaData->pegawai->id, [
                        'contain' => [
                            'Instansi' => [
                                'fields' => ['Instansi.id', 'Instansi.nama', 'Instansi.tipe', 'Instansi.logo']
                            ],
                            'Unit' => [
                                'fields' => ['Unit.id', 'Unit.nama', 'Unit.tipe']
                            ]
                        ]
                    ]);

                    if (!is_null($pegawaiData->instansi)) {
                        $this->_instansiData = $pegawaiData->instansi;
                        $this->_unitData = $pegawaiData->unit; // store unit so it doesn't need query anymore if needed
                    } else {
                        if(!is_null($peranData->instansi)) {
                            $this->_instansiData = $peranData->instansi;
                        }
                    }
                }
            }

        } catch (\Exception $ex) {
            return null;
        }

        return $this->_instansiData;
    }

    protected function getCurrentUnit()
    {
        try {
            if (!$this->_userData) {
                $this->getCurrentUser();
            }

            if ($this->_userData && !$this->_unitData) {
                $this->loadModel('Pengguna');
                $penggunaData = $this->Pengguna->get($this->_userData->id, [
                    'fields' => [
                        'id'
                    ],
                    'contain' => [
                        'Pegawai' => [
                            'fields' => ['Pegawai.id']
                        ],
                        'Peran' => [
                            'fields' => ['Peran.id']
                        ],
                    ]
                ]);

                if ($penggunaData->pegawai) {
                    $pegawaiData = $this->Pengguna->Pegawai->get($penggunaData->pegawai->id, [
                        'contain' => [
                            'Unit' => [
                                'fields' => ['Unit.id', 'Unit.nama', 'Unit.tipe']
                            ]
                        ]
                    ]);

                    if (!is_null($pegawaiData->unit)) {
                        $this->_unitData = $pegawaiData->unit;
                    }
                }
            }

        } catch (\Exception $ex) {
            return null;
        }

        return $this->_unitData;
    }

    protected function getJenisProsesPengguna()
    {
        try {
            if (!$this->_userData) {
                $this->getCurrentUser();
            }

            if ($this->_userData && !$this->jenisProsesIdsPengguna) {
                $this->loadModel('Pengguna');
                $penggunaData = $this->Pengguna->find('all')
                    ->select(['id'])
                    ->where(['id' => $this->_userData->id])
                    ->contain([
                        'JenisProses' => [
                            'fields' => [
                                'JenisProses.id',
                                'JenisProsesPengguna.pengguna_id'
                            ]
                        ]
                    ])
                    ->first();

                if ($penggunaData->jenis_proses) {
                    foreach ($penggunaData->jenis_proses as $jenisProses) {
                        $this->jenisProsesIdsPengguna[] = $jenisProses->id;
                    }
                }
            }

            return $this->jenisProsesIdsPengguna;

        } catch (\Exception $ex) {
            return null;
        }
    }

    protected function getJenisIzinPengguna()
    {
        try {
            if (!$this->_userData) {
                $this->getCurrentUser();
            }

            if ($this->_userData && !$this->jenisIzinIdsPengguna) {
                $this->loadModel('Pengguna');
                $penggunaData = $this->Pengguna->find('all')
                    ->select(['id'])
                    ->where(['id' => $this->_userData->id])
                    ->contain([
                        'JenisIzin' => [
                            'fields' => [
                                'JenisIzin.id',
                                'JenisIzinPengguna.pengguna_id'
                            ]
                        ]
                    ])
                    ->first();

                if ($penggunaData->jenis_izin) {
                    foreach ($penggunaData->jenis_izin as $jenisIzin) {
                        $this->jenisIzinIdsPengguna[] = $jenisIzin->id;
                    }
                }
            }

            return $this->jenisIzinIdsPengguna;

        } catch (\Exception $ex) {
            return null;
        }
    }

    protected function _formatDate($dateToFormat, $dateFormat = 'd/m/Y')
    {
        if (trim($dateToFormat) == '') {
            return '';
        }
        $formattedDate = date($dateFormat, strtotime($dateToFormat));
        return $formattedDate;
    }

    protected function setErrors($errors)
    {
        $this->_errors = $errors;
    }

    protected function parseJsDate($jsDate)
    {
        if (!is_string($jsDate)) {
            return null;
        }
        return date_create(substr($jsDate, 0, 10));
    }

    protected function getUserSessionVars()
    {
        // Get User related data
        $user = $this->getCurrentUser();
        $instansi = $this->getCurrentInstansi();
        $unit = $this->getCurrentUnit();

        $user_id = ($user && $user->id) ? $user->id : null;
        $instansi_id = ($instansi && $instansi->id) ? $instansi->id : null;
        $unit_id = ($unit && $unit->id) ? $unit->id : null;
        $pegawai_id = ($user && $user->pegawai_id) ? $user->pegawai_id : null;
        $peran_id = ($user && $user->peran_id) ? $user->peran_id : null;

        return [
            'user_id' => $user_id,
            'instansi_id' => $instansi_id,
            'unit_id' => $unit_id,
            'pegawai_id' => $pegawai_id,
            'peran_id' => $peran_id
        ];
    }

    /**
     * Get Pemohon ID from query string or current session
     * @return int|null
     */
    protected function getPemohonIdFromQueryStringOrSession()
    {
        $pemohonId = null;

        // If pemohon_id query string is provided
        if ($this->request->getQuery('pemohon_id') && $this->request->getQuery('pemohon_id')) {
            $pemohonId = (int) $this->request->query['pemohon_id'];

            if ($pemohonId === 0) {
                $pemohonId = null;
            }
        } else {
            // Get Pemohon if user is pemohon
            AuthService::setUser($this->getCurrentUser());
            $pemohon = AuthService::getUserPemohon();

            // Validate if user is pemohon, don't load other people's perusahaan
            if ($pemohon) {
                $pemohonId = $pemohon->id;
            }
        }

        return $pemohonId;
    }

    protected function array_flat($array, $prefix = '')
    {
        $result = [];

        if (
            $array instanceof \Cake\ORM\Entity || $array instanceof \Cake\ORM\Query ||
            $array instanceof \Cake\ORM\ResultSet
        ) {
            $array = $array->toArray();
        }

        if (!is_array($array)) {
            return $array;
        }

        foreach ($array as $key => $value) {
            $new_key = $prefix . (empty($prefix) ? '' : '_') . $key;

            if (
                $value instanceof \Cake\ORM\Entity || $value instanceof \Cake\ORM\Query ||
                $value instanceof \Cake\ORM\ResultSet
            ) {
                $value = $value->toArray();
            }

            if (is_array($value) && !array_key_exists(0, $value)) {
                $result = array_merge($result, self::array_flat($value, $new_key));
            } else {
                $result[$new_key] = $value;
            }
        }

        return $result;
    }

    protected function _downloadFile($filename = null, $defaultFolder = 'upload')
    {
        set_time_limit(3600);
        $filePath = ROOT . DS;

        if (!$filename && !empty($this->request->getQuery('filename'))) {
            $filename = $this->request->getQuery('filename');
        }

        $force = $this->request->getQuery('force') == 'F' ? false : true;

        // if filename doesn't include webroot/files
        if (!preg_match('/webroot\/files/', $filename)) {
            $filePath .= 'webroot' . DS . 'files' . DS . $defaultFolder . DS . $filename;
        } else {
            $filePath .= $filename;
        }

        if (!file_exists($filePath)) {
            throw new \Exception('Path file tidak dapat diakses');
        }

        $response = $this->response->withHeader('Access-Control-Allow-Origin', '*');
        $response = $response->withHeader('Access-Control-Allow-Headers', 'origin, x-requested-with, content-type, accept');
        $response = $response->withHeader('Access-Control-Allow-Methods', 'OPTIONS, GET');
        $response = $response->withHeader('Access-Control-Expose-Headers', 'Content-Disposition');

        $response = $response->withFile(
            $filePath,
            ['download' => $force]
        );

        return $response;
    }

    public function getInstansiIdFromDataOrSession()
    {
        if (!$this->request->getData('instansi_id')) {
            $instansi = $this->getCurrentInstansi();

            if ($instansi) {
                return isset($instansi->id) ? $instansi->id : null;
            }
        }

        return $this->request->getData('instansi_id');
    }

    public function getUnitIdFromDataOrSession()
    {
        if (!$this->request->getData('unit_id')) {
            $unit = $this->getCurrentUnit();

            if ($unit) {
                return isset($unit->id) ? $unit->id : null;
            }
        }

        return $this->request->getData('unit_id');
    }

    public function setOSSLog($nib_id = null,$status = null, $keterangan = null)
    {
        $logTable = TableRegistry::get('oss_log');

        if (is_null($this->log_id)) {
        $log = $logTable->newEntity();
        $log['nib'] = $nib_id;
        $log['status'] = $status;
            $this->log_keterangan = [];
            $this->log_keterangan[0] = array($status=>$keterangan);
            $log['log'] = json_encode($this->log_keterangan);
        $logTable->save($log);

            //inisial penerimaan sudah terjadi?
            $cekLog = $logTable->find()->select();
            $cekLog->where(['nib'=>$nib_id, 'status'=>'Receive NIB']);

            if($cekLog->count()>0 && $status != 'Receive NIB' ){
                $this->log_id = $log['id'];
            }
        } else {
            $log = $logTable->get($this->log_id);
            $this->log_keterangan[count($this->log_keterangan)] = array($status=>$keterangan);
            $log['log'] = json_encode($this->log_keterangan);
            $logTable->save($log);
        }
    }
}
