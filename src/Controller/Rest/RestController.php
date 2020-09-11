<?php
/**
 * Created by Indra
 * Date: 5/25/2017
 * Time: 08:02 PM
 */

namespace App\Controller\Rest;

use App\Controller\Api\ApiController;
use Cake\Event\Event;

class RestController extends ApiController
{
    protected $loadParentAuth = false; // important to override parent auth mechanism

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Auth', [
            'storage' => 'Memory',
            'authenticate' => [
                'Form' => [
                    'userModel' => 'RestUsers', // is used when identifying user
                    'fields' => [
                        'username' => 'username',
                        'password' => 'password',
                    ],
                    //                    'scope' => ['Users.active' => 1]
                ],
                'Basic' => [
                    'header' => 'Authorization',
                    'userModel' => 'RestUsers',
                    'queryDatasource' => true,
                    'fields' => [
                        'username' => 'username',
                        'password' => 'password',
                    ],
                    'scope' => ['is_active' => 1]
                ],
                'ADmad/JwtAuth.Jwt' => [
                    'header' => 'Authorization',
                    'prefix' => 'Bearer',
                    'parameter' => 'token',
                    'userModel' => 'RestUsers',
                    'scope' => ['is_active' => 1],
                    'fields' => [
                        'username' => 'id'
                    ],
                    'queryDatasource' => true
                ],
            ],
            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize'
        ]);
    }

    public function beforeFilter(Event $event)
    {
        $this->_userModel = 'RestUsers';
        parent::beforeFilter($event);
    }

    protected function getCurrentInstansi()
    {
        try {
            if (!$this->_userData) {
                $userData = $this->getCurrentUser();
            }

            if ($this->_userData && !$this->_instansiData) {
                $this->loadModel('RestUsers');
                $penggunaData = $this->RestUsers->get($this->_userData->id, [
                    'fields' => [
                        'id'
                    ],
                    'contain' => [
                        'Instansi' => [
                            'fields' => ['Instansi.id', 'Instansi.nama', 'Instansi.tipe', 'Instansi.logo']
                        ]
                    ]
                ]);

                if (!is_null($penggunaData->instansi)) {
                    $this->_instansiData = $penggunaData->instansi;
                }
            }

        } catch (\Exception $ex) {
            return null;
        }

        return $this->_instansiData;
    }
} 