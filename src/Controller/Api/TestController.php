<?php
namespace App\Controller\Api;

use Cake\ORM\TableRegistry;
use Cake\Filesystem\Folder;

/**
 * Test Controller
 *
 */
class TestController extends ApiController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
    }

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index', 'add']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->setResponseData($this->request->data, true, 'Get Berhasil');
    }

    /**
     * Add method
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $this->setResponseData($this->request->data, true, 'Post Berhasil');
    }
}