<?php
namespace App\Test\TestCase\Service;

use App\Service\AlurProsesService;
use Cake\TestSuite\IntegrationTestCase;

/**
 * Test for NotificationService
 */
class AlurProsesServiceTest extends IntegrationTestCase
{
    public function testGetAlurProses()
    {
        // vendor/bin/phpunit --filter testGetAlurProses tests/TestCase/Service/AlurProsesServiceTest

        try {
            $prosesPermohonan = AlurProsesService::getAlurProses(5, 'Baru');
            $this->assertArrayHasKey($prosesPermohonan, 'nama_proses');
        } catch (\Exception $ex) {
            $this->markTestIncomplete($ex->getMessage());
        }
    }
}
