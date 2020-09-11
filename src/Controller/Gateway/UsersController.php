<?php
/**
 * Created by Indra.
 * User: Core
 * Date: 5/25/2017
 * Time: 8:07 PM
 */

namespace App\Controller\Gateway;

use Cake\Http\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\Filesystem\File;
use Cake\Routing\Router;

class UsersController extends GatewayController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('GatewayUsers');
        $this->Auth->allow(['login', 'logout']);
    }

    public function logout()
    {
        
    }

    public function login()
    {
        $data = [];
        $token = null;
        $success = false;
        $message = '';

        try {
            $this->_userData = null;

            $pengguna = $this->Auth->identify();

            if (!$pengguna) {
                throw new UnauthorizedException('Pengguna atau password tidak valid');
            }

            $expireTime = 9999999999999; // in seconds

            $token = JWT::encode([
                'sub' => $pengguna['id'],
                'exp' =>  time() + $expireTime
            ], Security::salt());

            $success = true;
            $message = 'Login berhasil';

        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        $data['token'] = $token;
        return $this->setResponseData($data, $success, $message);
    }
}