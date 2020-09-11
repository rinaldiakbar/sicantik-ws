<?php
/**
 * Created by Indra
 * Date: 5/25/2017
 * Time: 08:02 PM
 */

namespace App\Controller\Gateway;

use App\Controller\Api\ApiController;
use Cake\Event\Event;
use Muffin\Footprint\Auth\FootprintAwareTrait;

class GatewayController extends ApiController
{
    protected $loadParentAuth = false; // important to override parent auth mechanism

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Auth', [
            'storage' => 'Memory',
            'authenticate' => [
                'Form' => [
                    'userModel' => 'GatewayUsers', // is used when identifying user
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
                    'userModel' => 'GatewayUsers',
                    'scope' => ['is_active' => 1],
                    'fields' => [
                        'username' => 'id'
                    ],
                    'queryDatasource' => true
                ]
            ],
            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize'
        ]);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        
        $this->alwaysOKHttpStatus = true;
    }
} 