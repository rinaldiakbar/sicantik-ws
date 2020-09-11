<?php
namespace App\Test\TestCase\Service;

use Cake\ORM\TableRegistry;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\TestSuite\IntegrationTestCase;
use App\Service\ReportGeneratorService;
use App\Model\Table\TemplateDataTable;
use Codeception\PHPUnit\ResultPrinter\Report;

/**
 * Test for ReportGeneratorServiceTest
 */
class ReportGeneratorServiceTest extends IntegrationTestCase
{
    /**
     * Undocumented function
     * vendor/bin/phpunit --filter testGenerateJrxmlReport tests/TestCase/Service/ReportGeneratorServiceTest
     *
     * @return void
     */
    public function testGenerateJrxmlReport()
    {
        try {
            $templateDataTable = TableRegistry::get('TemplateData');
            $templateData = $templateDataTable->find('all', [
                'conditions' => ['tipe_keluaran' => TemplateDataTable::TIPE_DOKUMEN]
            ])->first();

            $templateDir = new Folder(WWW_ROOT . 'files' . DS . 'template');
            $resultDir = new Folder(WWW_ROOT . 'files' . DS . 'result');
            $dataSourceDir = new Folder(WWW_ROOT . 'files' . DS . 'datasource');

            if (!$templateDir->path || !$resultDir->path || !$dataSourceDir->path) {
                throw new \Exception('Direktori Template, Result, atau DataSource tidak dapat diakses');
            }

            $templateData->template_dokumen = 'test_report2.jrxml';
            $templateFilePath = $templateDir->path . DS . $templateData->template_dokumen;

            if (!file_exists($templateFilePath)) {
                throw new \Exception('File Template tidak dapat diakses');
            }

            $sampleData = [
                'flat_data' => [
                    'type' => 'singular',
                    'data' => [
                        'nama' => 'indra',
                        'nik' => '1234677'
                    ]
                ],
                'collection_data' => [
                    'type' => 'plural',
                    'data' => [
                        [
                            'no' => '1',
                            'nilai' => '10'
                        ],
                        [
                            'no' => '2',
                            'nilai' => '20'
                        ]
                    ]
                ]
            ];

            if ($templateData) {
                ReportGeneratorService::generateJasperReport(
                    $templateData,
                    $sampleData,
                    $templateFilePath,
                    $resultDir->path,
                    $dataSourceDir->path
                );
            }

        } catch (\Exception $ex) {
            $this->fail($ex->getMessage());
        }

        // $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Undocumented function
     * vendor/bin/phpunit --filter testGenerateWordReport tests/TestCase/Service/ReportGeneratorServiceTest
     *
     * @return void
     */
    public function testGenerateWordReport()
    {
        try {
            $templateDataTable = TableRegistry::get('TemplateData');
            $templateData = $templateDataTable->find('all', [
                'conditions' => ['tipe_keluaran' => TemplateDataTable::TIPE_DOKUMEN]
            ])->first();

            $templateDir = new Folder(ROOT . DS . 'tests' . DS . 'TestCase' . DS . 'files');
            $resultDir = new Folder(ROOT . DS .'tmp' . DS . 'tests' . DS . 'result', true);
            $dataSourceDir = new Folder(ROOT . DS . 'tmp' . DS . 'tests' . DS . 'datasource', true);

            if (!$templateDir->path || !$resultDir->path || !$dataSourceDir->path) {
                throw new \Exception('Direktori Template, Result, atau DataSource tidak dapat diakses');
            }

            $templateData->template_dokumen = '_test_report.docx';
            $templateFilePath = $templateDir->path . DS . $templateData->template_dokumen;

            if (!file_exists($templateFilePath)) {
                throw new \Exception('File Template tidak dapat diakses');
            }

            $sampleData = [
                'flat' => [
                    'type' => 'singular',
                    'data' => [
                        'nama' => 'indra',
                        'nik' => '1234677',
//                        'foto_img_300' => 'webroot/files/upload/_mars.jpg'
                        'foto_img_150' => '_mars.jpg',
                        'data_qrcode_100' => 'https://google.com',
                        'data_barcode_200' => 'https://localhost'
                    ]
                ],
                /*'collection' => [
                    'type' => 'plural',
                    'data' => [
                        [
                            'no' => '1',
                            'nilai' => '10'
                        ],
                        [
                            'no' => '2',
                            'nilai' => '20'
                        ]
                    ]
                ]*/
            ];

            if ($templateData) {
                ReportGeneratorService::setSaveAsPdf(true);
                ReportGeneratorService::generateWordReport(
                    1,
                    $templateData,
                    $sampleData,
                    $templateFilePath,
                    $resultDir->path,
                    $dataSourceDir->path
                );
            }

        } catch (\Exception $ex) {
            $this->fail($ex->getMessage());
        }

        // $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Undocumented function
     * vendor/bin/phpunit --filter testSignPdf tests/TestCase/Service/ReportGeneratorServiceTest
     *
     * @return void
     */
    public function testSignPdf()
    {
        $filesPath = ROOT . DS . 'tests' . DS . 'TestCase' . DS . 'files';
        $resultDirPath = ROOT . DS .'tmp' . DS . 'tests' . DS . 'result';

        # Sign using kominfo certificate
        $pdfFile = new File($filesPath . DS . 'sample_contract.pdf');
        $p12File = new File($filesPath . DS . 'dhs.p12');
        $pass = 'lX957A';

        ReportGeneratorService::setResultFolderPath($resultDirPath);
        $signedPdfName = ReportGeneratorService::signPdf($pdfFile->path, $p12File->read(), $pass);
        $this->assertNotFalse($signedPdfName, 'Sign PDF tidak berhasil');

        # Sign using bppt certificate
        $pdfFile = new File($filesPath . DS . 'sample_doc.pdf');
        $p12File = new File($filesPath . DS . 'bppt.p12');
        $pass = 'kom1nfokom1nfo';

        ReportGeneratorService::setResultFolderPath($resultDirPath);
        $signedPdfName = ReportGeneratorService::signPdf($pdfFile->path, $p12File->read(), $pass);
        $this->assertNotFalse($signedPdfName, 'Sign PDF tidak berhasil');
    }

    /**
     * Undocumented function
     * vendor/bin/phpunit --filter testCertifyPdf tests/TestCase/Service/ReportGeneratorServiceTest
     *
     * @return void
     */
    public function testCertifyPdf()
    {
        $filesPath = ROOT . DS . 'tests' . DS . 'TestCase' . DS . 'files';
        $resultDirPath = ROOT . DS .'tmp' . DS . 'tests' . DS . 'result';

        # Sign using kominfo certificate
        $pdfFile = new File($filesPath . DS . 'sample_contract.pdf');
        $p12File = new File($filesPath . DS . 'dhs.p12');
        $pass = 'lX957A';

        ReportGeneratorService::setResultFolderPath($resultDirPath);
        $signedPdfName = ReportGeneratorService::certifyPdf($pdfFile->path, $p12File->read(), $pass);
        $this->assertNotFalse($signedPdfName, 'Sign PDF tidak berhasil');

        # Sign using bppt certificate
        $pdfFile = new File($filesPath . DS . 'sample_doc.pdf');
        $p12File = new File($filesPath . DS . 'bppt.p12');
        $pass = 'kom1nfokom1nfo';

        ReportGeneratorService::setResultFolderPath($resultDirPath);
        $signedPdfName = ReportGeneratorService::certifyPdf($pdfFile->path, $p12File->read(), $pass);
        $this->assertNotFalse($signedPdfName, 'Sign PDF tidak berhasil');
    }
}
