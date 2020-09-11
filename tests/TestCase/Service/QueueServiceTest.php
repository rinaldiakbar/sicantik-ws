<?php
namespace App\Test\TestCase\Service;

use App\Service\QueueService;
use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;

/**
 * Test for QueueService
 */
class QueueServiceTest extends IntegrationTestCase
{
    /**
     * Undocumented function
     * vendor/bin/phpunit --filter testCreateJob tests/TestCase/Service/QueueServiceTest
     * 
     * @return void
     */ 
	public function testCreateJob() 
    {
        if (
            !QueueService::createJob('SaveExternal', [
                'target_url' => 'http://sic-api.local/api/Test/add',
                'request_body' => ['field1' => 'value 1', 'field2' => 'value2'],
                'options' => [
                    'auth' => [
                        'username' => 'indra',
                        'password' => 'indra'
                    ]
                ]
            ])
        ) {
            $this->fail('Tidak berhasil create job');
        }
        // $this->markTestIncomplete('Not implemented yet.');
	}


    /**
     * Undocumented function
     * vendor/bin/phpunit --filter testWatch tests/TestCase/Service/QueueServiceTest
     * 
     * @return void
     */
    public function testWatch()
    {
        QueueService::watch();
    }
}
