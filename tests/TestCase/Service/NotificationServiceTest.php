<?php
namespace App\Test\TestCase\Service;

use App\Service\NotificationService;
use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;

/**
 * Test for NotificationService
 */
class NotificationServiceTest extends IntegrationTestCase
{
	public function testSendMessage() 
    {
        // vendor/bin/phpunit --filter testSendMessage tests/TestCase/Service/NotificationServiceTest
        NotificationService::sendMessage('indra.halimm@gmail.com', 'Subject', 'This is email body');
        // $this->markTestIncomplete('Not implemented yet.');
	}

    public function testSendNotification()
    {
        // vendor/bin/phpunit --filter testSendNotification tests/TestCase/Service/NotificationServiceTest
        $permohonanIzinTable = TableRegistry::get('PermohonanIzin');
        $permohonanIzin = $permohonanIzinTable->find('all', [
            'conditions' => [
                'id' => 1384
            ]
        ])->first();

        if (!$permohonanIzin) {
            $this->markTestIncomplete('Permohonan Izin not found.');
        }

        try {
            NotificationService::sendNotification($permohonanIzin);
        } catch (\Exception $ex) {
            $this->markTestIncomplete($ex->getMessage());
        }
    }
}
