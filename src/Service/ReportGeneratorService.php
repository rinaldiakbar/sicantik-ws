<?php
/**
 * Created by Indra
 * Date: 3/14/17
 * Time: 11:07 PM
 */

namespace App\Service;

use Cake\Datasource\ConnectionManager;
use Cake\Http\Client;
use Cake\Utility\Inflector;
use Cake\Utility\Xml;
use Cake\Core\Configure;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use chillerlan\QRCode;
use FPDI;
use JasperPHP\JasperPHP;

class ReportGeneratorService
{
    const TIPE_KELOMPOK_DATA_SINGULAR = 'singular';
    const TIPE_KELOMPOK_DATA_PLURAL = 'plural';

    private static $outputFileName;
    private static $outputFilePath;
    private static $userVars = [];
    private static $saveAsPdf = false;
    private static $resultFolderPath = WWW_ROOT . DS . 'files' . DS . 'signed';

    /**
     * @return mixed
     */
    public static function getOutputFileName()
    {
        return self::$outputFileName;
    }

    /**
     * @param mixed $outputFileName
     */
    public static function setOutputFileName($outputFileName)
    {
        self::$outputFileName = $outputFileName;
    }

    /**
     * @return mixed
     */
    public static function getOutputFilePath()
    {
        return self::$outputFilePath;
    }

    /**
     * @param mixed $outputFilePath
     */
    public static function setOutputFilePath($outputFilePath)
    {
        self::$outputFilePath = $outputFilePath;
    }

    public static function setSaveAsPdf($asPdf)
    {
        self::$saveAsPdf = (bool) $asPdf;
    }

    /**
     * Set session user related variables
     * @param array $userVars array key=>value with key as the name of variable
     * @return void
     */
    public static function setUserVars($userVars)
    {
        self::$userVars = $userVars;
    }

    /**
     * Get the value of resultFolderPath
     */
    public static function getResultFolderPath()
    {
        return self::$resultFolderPath;
    }

    /**
     * Set the value of resultFolderPath
     *
     * @return  self
     */
    public static function setResultFolderPath($resultFolderPath)
    {
        self::$resultFolderPath = $resultFolderPath;
    }

    /**
     * @param $templateDataId
     * @param $keyId
     * @return array
     * @throws \Exception
     */
    public static function getData(\App\Model\Entity\TemplateData $templateData, $keyId, $filters)
    {
        extract($filters);
        extract(self::$userVars);

        $queryResult = [];
        if (!empty($templateData->kelompok_data)) {
            foreach ($templateData->kelompok_data as $key => $groupData) {
                $sqlQuery = '';
                $connection = ConnectionManager::get('default');

                // Get SQL Query and Eval SQL String
                eval("\$sqlQuery = \"$groupData->sql\";");

                if (!empty($sqlQuery)) {
                    $groupCode = Inflector::dasherize($groupData->label_kelompok);
                    $results = $connection->query($sqlQuery)->fetchAll('assoc');

                    // cleanse the data
                    if (!empty($results)) {
                        foreach ($results as $resultIndex => $result) {
                            foreach($result as $fieldName => $fieldValue) {
                                $results[$resultIndex][$fieldName] = htmlspecialchars($fieldValue);
                            }
                        }
                    }

                    // Parse the query result
                    if ($groupData->tipe == self::TIPE_KELOMPOK_DATA_SINGULAR && isset($results[0])) {
                        $queryResult[$groupCode]['type'] = $groupData->tipe;
                        $queryResult[$groupCode]['data'] = $results[0];
                    } else {
                        $queryResult[$groupCode]['type'] = $groupData->tipe;
                        $queryResult[$groupCode]['data'] = $results;
                    }
                }
            }
        }

        return $queryResult;
    }

    public static function generateOldJasperReport(\App\Model\Entity\TemplateData $templateData, $data, $templateFilePath, $outputPath, $dataSourcePath)
    {
        $collectionData = [];
        $singularData = [];
        $now = date('YmdHis');

        ####Load Konfigurasi Jasper###
//        $jdbc = Configure::read('JavaBridge.jdbc');
        /***Opsi 1: Dengan allow_url_include on***/
        $javaBridgeLib = Configure::read('JavaBridge.bridge_lib');
        (@include_once($javaBridgeLib))
        or
        die('Koneksi ke server Java Bridge gagal.');

        /***Opsi 2: Tanpa allow_url_include***/
        //$java_host = $this->config->item('java_host', 'java_bridge');//Load Config java_bridge.php
        //define ("JAVA_HOSTS", $java_host);
        //$temp_javabridge=$this->_jasper_folder.DIRECTORY_SEPARATOR."Java.inc";
        //(@include_once($temp_javabridge))or die('Oopps koneksi ke Jasper Report gagal...Silahkan cek koneksi ke Java Bridge dan Jasper Report di server anda.');
        ##############################

        // Parse the data
        foreach ($data as $groupKey => $groupResult) {
            // If it's singular type
            if ($groupResult['type'] == self::TIPE_KELOMPOK_DATA_SINGULAR) {
                foreach ($groupResult['data'] as $fieldName => $fieldValue) {
                    $singularData[$groupKey . '_' . $fieldName] = $fieldValue;
                }
            } else {
                foreach ($groupResult['data'] as $groupFieldName => $groupFieldValue) {
                    $collectionData['result'][$groupKey]['data'][$groupFieldName] = $groupFieldValue;
                }
            }
        }

        $hasCollection = !empty($collectionData);

        if ($hasCollection) {
            // Generate XML file for datasource
            $xmlName = $now . '-' . $templateData->id . '.xml';
            $xmlPath = $dataSourcePath . DS . $xmlName;
            $xml = Xml::build($collectionData);
            $xmlContent = $xml->asXML();
            $xmlFile = new File($xmlPath, true, 0644);

            // Write XML Content
            if (!$xmlFile->writable()) {
                throw new \Exception('XML tidak dapat ditulis');
            }

            if (!$xmlFile->write($xmlContent, 'w')) {
                throw new \Exception('Terjadi error saat menulis XML');
            }
        }

        // Set JRXML File and Output File
        $outputFileName = $now . '-' . $templateData->id . ".pdf";
        self::$outputFilePath = $outputPath . DIRECTORY_SEPARATOR . $outputFileName;

        $jcm = new \Java("net.sf.jasperreports.engine.JasperCompileManager");
        $report = $jcm->compileReport($templateFilePath);

        $jfm = new \Java("net.sf.jasperreports.engine.JasperFillManager");

        if ($hasCollection) {
            $JRXml = new \Java("net.sf.jasperreports.engine.data.JRXmlDataSource", $xmlPath, "/result//data");
        }

        $params = new \Java("java.util.HashMap");

        // mengirim parameter ke jrxml
        foreach($singularData as $singular){
            foreach ($singular as $param => $value){
                $params->put($param,$value);
            }
        }

//				$params->put("SUBREPORT_DIR",str_replace(DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR,$this->_jasper_folder.DIRECTORY_SEPARATOR."template".DIRECTORY_SEPARATOR));
//				$params->put("BASEPATH",str_replace(DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR,realpath(BASEPATH."..")));

        //		$params->put("JRXPathQueryExecuterFactory.PARAMETER_XML_DATA_DOCUMENT");
        //	   	$params->put("XML_DATE_PATTERN", "yyyy-MM-dd");
        //	    $params->put("XML_NUMBER_PATTERN", "#,##0.##");
        //   $params->put("XML_LOCALE", "Locale.ENGLISH");

        if ($hasCollection) {
            $print = $jfm->fillReport($report, $params, $JRXml);
        } else {
            $print = $jfm->fillReport($report, $params);
        }

        $jem = new \Java("net.sf.jasperreports.engine.JasperExportManager");

        $jem->exportReportToPdfFile($print, self::$outputFilePath);

        return;
    }

    public static function generateJasperReport(
        $keyId,
        \App\Model\Entity\TemplateData $templateData,
        $data,
        $templateFilePath,
        $outputPath,
        $dataSourcePath
    ) {
        $now = date('YmdHis');
        $dbConnection = [];
        $collectionData = [];
        $singularData = [];
        $allowedParams = [];
        $params = [];
        $lastJasperCommand = null;

        try {
            // Parse the data
            foreach ($data as $groupKey => $groupResult) {
                // If it's singular type
                if ($groupResult['type'] == self::TIPE_KELOMPOK_DATA_SINGULAR) {
                    foreach ($groupResult['data'] as $fieldName => $fieldValue) {
                        $singularData[$groupKey . '_' . $fieldName] = $fieldValue;
                    }
                } else {
                    foreach ($groupResult['data'] as $groupFieldName => $groupFieldValue) {
                        $collectionData['result'][$groupKey]['data'][$groupFieldName] = $groupFieldValue;
                    }
                }
            }

            $hasCollection = !empty($collectionData);

            if ($hasCollection) {
                // Generate XML file for datasource
                $xmlName = $now . '-' . $templateData->id . '.xml';
                $xmlPath = $dataSourcePath . DS . $xmlName;
                $xml = Xml::build($collectionData);
                $xmlContent = $xml->asXML();
                $xmlFile = new File($xmlPath, true, 0644);

                // Write XML Content
                if (!$xmlFile->writable()) {
                    throw new \Exception('XML tidak dapat ditulis');
                }

                if (!$xmlFile->write($xmlContent, 'w')) {
                    throw new \Exception('Terjadi error saat menulis XML');
                }

                $dbConnection = [
                    'driver' => 'xml',
                    'data_file' => $xmlPath,
                    'xml_xpath' => '/result//data'
                ];
            } else {
                $dbConfig = ConnectionManager::getConfig('default');
                $dbConnection = [
                    'driver' => 'postgres',
                    'username' => $dbConfig['username'],
                    'password' => $dbConfig['password'],
                    'host' => $dbConfig['host'],
                    'database' => $dbConfig['database'],
                    'port' => $dbConfig['port'] ?: '5432',
                ];
            }

            // Set Output File
            $outputNameWithoutExt = $now . '-' . $templateData->id;
            $outputFileName = $outputNameWithoutExt . ".pdf";
            self::$outputFilePath = $outputPath . DIRECTORY_SEPARATOR . $outputFileName;

            // Create temporary folder for the output and read the template file
            $resultDir = new Folder(WWW_ROOT . 'files' . DS . 'result', false);
            $templateFile = new File($templateFilePath, false);

            //This command will compile the `hello_world.jrxml` source file to a `hello_world.jasper` file.
            $jasper = new JasperPHP;
            $jasper->compile($templateFilePath);
            $lastJasperCommand = $jasper->output();
            $jasper->execute();

            // List available parameters from the template
            $availableParams = $jasper->listParameters($templateFilePath)->execute();
            foreach($availableParams as $paramDesc) {
                // description will be formatted like this
                $splitParamDesc = explode(" ", $paramDesc);
                $allowedParams[] = $splitParamDesc[1];
            }

            // Only pass parameters that's defined in the template
            foreach ($singularData as $key => $val) {
                if (in_array($key, $allowedParams)) {
                    $params[$key] = $val;
                }
            }

            // Create object of new jasper file
            $jasperFilePath = str_replace('jrxml', 'jasper', $templateFilePath);
            $jasperFile = new File($jasperFilePath, false);
            $options = [
//            'format' => ['pdf', 'rtf'],
                'format' => ['pdf'],
                'locale' => 'en',
                'params' => $params,
                'db_connection' => $dbConnection
            ];

            // Duplicate the jasper file
            $jasperFileCopyPath = str_replace($templateFile->name(), $outputNameWithoutExt, $jasperFilePath);
            $jasperFile->copy($jasperFileCopyPath);

            // Process the report
            $jasper = new JasperPHP;
            $jasper->process(
                $jasperFileCopyPath,
                $resultDir->path,
                $options
            );
            $lastJasperCommand = $jasper->output();
            $jasper->execute();

        } catch (\Exception $ex) {
            $message = $ex->getMessage() . ($lastJasperCommand ? "Last Jasper Command: $lastJasperCommand" : null);
            throw new \Exception($message);
        }

        return;
    }

    public static function generateOdtReport(
        $keyId,
        \App\Model\Entity\TemplateData $templateData,
        $data,
        $templateFilePath,
        $outputPath
    ) {
        if (!$data || !is_array($data)) {
            throw new \Exception('Format data untuk report tidak valid');
        }

        // Set Output File
        $now = date('YmdHis');
        $outputFileName = $now . '-' . $templateData->id . ".odt";
//        $outputFileName = $now . '-' . $templateData->id . ".pdf";
        self::$outputFilePath = $outputPath . DS . $outputFileName;

        $easyReportPath = realpath(WWW_ROOT . '/..' . '/src/Library/PHP-Easy-Report/src/EasyReport.php');
        if (!$easyReportPath) {
            return;
        }
        require $easyReportPath;

        // Initialize Easy Report
        $docGenerator = new \EasyReport($templateFilePath, $outputPath);

        // Parse the data
        $outputData = [];
        foreach ($data as $groupKey => $groupResult) {
            // If it's singular type
            if ($groupResult['type'] == self::TIPE_KELOMPOK_DATA_SINGULAR) {
                foreach ($groupResult['data'] as $fieldName => $fieldValue) {
                    $outputData[$groupKey . '_' . $fieldName] = $fieldValue;
                }
            } else {
                $outputData[$groupKey] = $groupResult['data'];
            }
        }

        /*$outputData = array(
            'name' => 'Iván Guardado Castro',
            'link' => 'ivanguardado.com',
            'visits' => array(
                array('Nombrea', 'Fecha acceso', 'Tiempo visita'),
                array('Emilio Nicolás', '20/10/2011', '5 min'),
                array('Javier López', '20/10/2011', '1m'),
                array('Adrián Mato', '19/10/2011', '2 min'),
                array('Jesús Pérez', '18/10/2011', '8 min')
            )
        );*/

        $docGenerator->create(self::$outputFilePath, $outputData);

        return;
    }

    /**
     * Generate Report using docx template
     */
    public static function generateWordReport(
        $keyId,
        \App\Model\Entity\TemplateData $templateData,
        $data,
        $templateFilePath,
        $outputPath,
        $dataSourcePath
    ) {
        // Set Output File
        $now = date('YmdHis');

        // Parse the data
        $queryResult = [];
        foreach ($data as $groupKey => $groupResult) {
            if ($groupResult['type'] == self::TIPE_KELOMPOK_DATA_PLURAL) {
                $queryResult[$groupKey] = $groupResult['data'];
            }
        }

        // Parse the data
        $collectionData = [];
        $singularData = [];
        foreach ($data as $groupKey => $groupResult) {
            // If it's singular type
            if ($groupResult['type'] == self::TIPE_KELOMPOK_DATA_SINGULAR) {
                foreach ($groupResult['data'] as $fieldName => $fieldValue) {
                    $singularData[$groupKey . '_' . $fieldName] = $fieldValue;
                }
            } else {
                $collectionData[$groupKey] = $groupResult['data'];
            }
        }

        if (!empty($collectionData) || !empty($singularData)) {
            // Set Input File and Output File
            $outputFileName = sprintf(
                "%d-%s-%d.docx",
                isset(self::$userVars['instansi_id']) ? self::$userVars['instansi_id'] : 0,
                $now,
                $keyId
            );
            self::$outputFilePath = $outputPath . DIRECTORY_SEPARATOR . $outputFileName;

            /*** BEGIN - Generating Report ***/
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templateFilePath);

            foreach ($singularData as $singularKey => $singularValue) {
                $imageProperty = self::getKeyImageProperty($singularKey, $singularValue);

                if ($imageProperty !== false) {
                    call_user_func_array([$templateProcessor, 'setImg'], $imageProperty);
                } else {
                    $templateProcessor->setValue($singularKey, $singularValue);
                }
            }

            // Set Collection Data
            foreach ($collectionData as $collectionKey => $collection) {
                $numData = count($collection);
                $doClone = true;

                foreach ($collection as $idx => $data) {
	                $idxData = '#' . ($idx+1);

                    foreach ($data as $fieldName => $fieldValue) {
                        $rowCode = $collectionKey . '_' . $fieldName;

                        // Only clone once for the first field
                        if ($doClone) {
                            $rowCodeToClone = preg_replace('/(_img)(_[0-9]+)?/i', '_img', $rowCode);
                            $templateProcessor->cloneRow($rowCodeToClone, $numData);
                            $doClone = false;
                            unset($rowCodeToClone);
                        }

                        $imageProperty = self::getKeyImageProperty($rowCode, $fieldValue, $rowCode . $idxData);

                        if ($imageProperty !== false) {
                            call_user_func_array([$templateProcessor, 'setImg'], $imageProperty);
                        } else {
                            $templateProcessor->setValue($rowCode . $idxData, $fieldValue);
                        }
                    }
                }
            }

            // Save as Docx
            $templateProcessor->saveAs(self::$outputFilePath);

            if (self::$saveAsPdf) {
                $converterConfig = Configure::read('PDFConverter');
                $wordPath = self::$outputFilePath;
                $pdfPath = str_replace('.docx', '.pdf', self::$outputFilePath);

                if ($converterConfig && $converterConfig['engine'] == 'webservice') {
                    // Request to Converter web service
                    $http = new Client();
                    $response = $http->post(
                        $converterConfig['url'],
                        [
                            'file' => fopen($wordPath, 'r')
                        ]
                    );
                    $respJson = $response->json;

                    // Download the file
                    if (!empty($respJson) && isset($respJson['download_url'])) {
                        file_put_contents($pdfPath, file_get_contents($respJson['download_url']));
                    }

                } else {
                    // Set PDF Library
                    $domPdfPath = realpath(WWW_ROOT . '/../src/Library/Dompdf');
                    \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
                    \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

                    // Save as PDF
                    $temp = \PhpOffice\PhpWord\IOFactory::load($wordPath);
                    $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($temp , 'PDF');
                    $xmlWriter->save($pdfPath, TRUE);
                }

                // Remove original docx file
                unlink($wordPath);
                self::$outputFilePath = $pdfPath;
            }
            /*** END - Generating Report ***/

            return;
        }
    }

    public static function cleanupUnusedTemplate()
    {
        // TODO remove unused template_dokumen by checking to DB first
    }

    private static function getKeyImageProperty($key, $value, $targetKey = null)
    {
        try {
            $width = 100;

            if (preg_match('/(_img)(_[0-9]+)?/i', $key, $matches)) {
                // if filename doesn't include webroot/files
                if (!preg_match('/webroot\/files/', $value)) {
                    $value = 'webroot' . DS . 'files' . DS . 'upload' . DS . $value;
                }

                $imagePath = ROOT . DS . $value;

                if (file_exists($imagePath)) {
                    $userDefinedWidth = intval(str_replace('_img_','', $matches[0]));

                    if ($userDefinedWidth > 0) {
                        $width = $userDefinedWidth;
                    }

                    if ($targetKey) {
                        $key = $targetKey;
                    }

                    // remove size suffix
                    $key = preg_replace('/(_[0-9]+)/', '', $key);

                    return [
                        $key,
                        ['src' => $imagePath, 'swh' => $width]
                    ];
                }
            } elseif (preg_match('/(_qrcode)(_[0-9]+)?/i', $key, $matches)) {
                // Create cache folder for QRCode
                $dir = new Folder(
                    ROOT . DS . 'tmp' . DS . 'cache' . DS . 'qrcode',
                    true
                );

                // Generate QR Code Image
                if ($value) {
                    $options = new QRCode\QROptions([
                        'version'    => 5,
                        'outputType' => QRCode\QRCode::OUTPUT_IMAGE_PNG,
                        'eccLevel'   => QRCode\QRCode::ECC_L,
                    ]);

                    // invoke a fresh QRCode instance
                    $qrcode = new QRCode\QRCode($options);

                    // and dump the output
                    $base64Data = $qrcode->render($value);
                    $base64Data = str_replace('data:image/png;base64,', '', $base64Data);

                    // Create image file
                    $filename = date('YmdHis') . '.png';
                    $imagePath = $dir->path . DS . $filename;
                    $data = base64_decode($base64Data);
                    $im = imagecreatefromstring($data);
                    imagealphablending($im, false);
                    imagesavealpha($im, true);
                    if ($im == false) {
                        return false;
                    }
                    imagepng($im, $imagePath); // Create the file
                    imagedestroy($im);
                }

                if (file_exists($imagePath)) {
                    $userDefinedWidth = intval(str_replace('_qrcode_','', $matches[0]));

                    if ($userDefinedWidth > 0) {
                        $width = $userDefinedWidth;
                    }

                    if ($targetKey) {
                        $key = $targetKey;
                    }

                    // remove size suffix
                    $key = preg_replace('/(_[0-9]+)/', '', $key);

                    return [
                        $key,
                        ['src' => $imagePath, 'swh' => $width]
                    ];
                }
            } elseif (preg_match('/(_barcode)(_[0-9]+)?/i', $key, $matches)) {
                // Create cache folder for QRCode
                $width = 200;
                $dir = new Folder(
                    ROOT . DS . 'tmp' . DS . 'cache' . DS . 'barcode',
                    true
                );

                // Generate Barcode Image
                if ($value) {
                    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                    $base64Data = base64_encode(
                        $generator->getBarcode(
                            $value,
                            $generator::TYPE_CODE_128,
                            2,
                            60
                        )
                    );

                    // Create image file
                    $filename = date('YmdHis') . '.png';
                    $imagePath = $dir->path . DS . $filename;
                    $data = base64_decode($base64Data);
                    $im = imagecreatefromstring($data);
                    imagealphablending($im, false);
                    imagesavealpha($im, true);
                    if ($im == false) {
                        return false;
                    }
                    imagepng($im, $imagePath); // Create the file
                    imagedestroy($im);
                }

                if (file_exists($imagePath)) {
                    $userDefinedWidth = intval(str_replace('_barcode_', '', $matches[0]));

                    if ($userDefinedWidth > 0) {
                        $width = $userDefinedWidth;
                    }

                    if ($targetKey) {
                        $key = $targetKey;
                    }

                    // remove size suffix
                    $key = preg_replace('/(_[0-9]+)/', '', $key);

                    return [
                        $key,
                        ['src' => $imagePath, 'swh' => $width]
                    ];
                }
            }
        } catch (\Exception $ex) {
            // TODO Write to log file
        }

        return false;
    }

    /**
     * Sign PDF using approval signature
     *
     * @param [type] $pdfFilePath
     * @param [type] $p12Data
     * @param [type] $certificatePassword
     * @return void
     */
    public static function signPdf($pdfFilePath, $p12Data, $certificatePassword) {
        $pdfFile = new File($pdfFilePath);
        $resultFolder = new Folder(self::$resultFolderPath);
        $signedPdfName = str_replace('.pdf', '-signed.pdf', $pdfFile->name); // Create temporary folder for signing process
        $certFolder = new Folder(ROOT . DS . 'tmp' . DS . 'cache' . DS . 'certificate', true);
        $tempFolder = new Folder($certFolder->path . DS . date('YmdHis'), true);

        // Write p12 to temporary folder
		$result = openssl_pkcs12_read($p12Data, $certificateInfo, $certificatePassword);

        // Write crt to temporary folder
        $crtFile = new File($tempFolder->path . DS . 'cert.crt', true);
        $crtFile->write($certificateInfo['cert']);

        // Write private key to temporary folder
        $keyFile = new File($tempFolder->path . DS . 'cert.key', true);
        $keyFile->write($certificateInfo['pkey']);

        // Import PDF content to FPDI
        $pdf = new \setasign\Fpdi\Tcpdf\Fpdi(
            PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, true
        );
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pageCount = $pdf->setSourceFile($pdfFilePath);

        for ($i = 1; $i <= $pageCount; $i++) {
            $pdf->SetAutoPageBreak(true);
            $tpl = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $orientation = $size['height'] > $size['width'] ? 'P' : 'L';
            $pdf->addPage($orientation);
            $pdf->useTemplate($tpl, null, null, $size['width'], $size['height'], true);
        }

        // Create approval signature using TCPDF
        $crtPath = 'file://' . $crtFile->path;
        $keyPath = 'file://' . $keyFile->path;
        $pdf->setSignature($crtPath, $keyPath, $certificatePassword, '', 1, [], 'A');
        $pdf->setTimeStamp('http://tsa.rootca.or.id');
        $pdf->Output($resultFolder->path . DS . $signedPdfName, 'F');

        $tempFolder->delete();
        return $signedPdfName;
    }

    /**
     * Sign PDF using digital signature
     *
     * @param [type] $pdfFilePath
     * @param [type] $p12Data
     * @param [type] $certificatePassword
     * @return void
     */
    public static function certifyPdf($pdfFilePath, $p12Data, $certificatePassword)
    {
        $pdfFile = new File($pdfFilePath);
        $resultFolder = new Folder(self::$resultFolderPath);
        $signedPdfName = str_replace('.pdf', '-certified.pdf', $pdfFile->name);

        // Create temporary folder for signing process
        $certFolder = new Folder(ROOT . DS . 'tmp' . DS . 'cache' . DS . 'certificate', true);
        $tempFolder = new Folder($certFolder->path . DS . date('YmdHis'), true);

        // Write p12 to temporary folder
        $p12File = new File($tempFolder->path . DS . 'cert.p12', true);
        $p12File->write($p12Data);

        // Use Farit_Pdf
        $pdf = \App\Library\Farit_Pdf\Farit_Pdf::load($pdfFile->path);

        //attaches a digital certificate
        $certificate = file_get_contents($p12File->path);

        if (empty($certificate)) {
            throw new Zend_Pdf_Exception('Cannot open the certificate file');
        }

        $pdf->attachDigitalCertificate($certificate, $certificatePassword);

        // here the digital certificate is inserted inside of the PDF document
        $renderedPdf = $pdf->renderNew();
        file_put_contents($resultFolder->path . DS . $signedPdfName, $renderedPdf);

        $tempFolder->delete();
        return $signedPdfName;
    }
}
