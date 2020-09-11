<?php
/**
 * Upload Service
 * Created by Indra.
 * Date: 3/22/17
 * Time: 8:39 PM
 */

namespace App\Service;

use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Routing\Router;

class UploadService
{
    private static $maxSize = 1; // default max size in MB
    private static $instansiID;

    public static function setInstansiID($instansiID) {
       self::$instansiID = $instansiID;
    }

    /**
     * Generate new filename based on original name passed
     * @param $originalFileName
     * @return mixed|null|string
     */
    public static function generateFileName($originalFileName)
    {
        $newFileName = null;

        if (preg_match('/.(php|phar|phtml|html|php3|php4|php5)/', $originalFileName)) {
            throw new \Exception('Forbidden file');
        }

        if ($originalFileName) {
            // replace all char except: \w Word character (abcABC0-9_)
            $originalFileName = str_replace(' ', '', trim($originalFileName));
            $originalFileName = preg_replace('/[^\w.]/', '', $originalFileName);
            $prefix = (self::$instansiID ? self::$instansiID . '-' : '') . date('YmdHis') . '_';
            $newFileName = preg_replace('/^[0-9]{4}[01][0-9][0-3][0-9][0-9]{6}_/', $prefix, $originalFileName, 1, $numReplaced);

            if (!is_string($newFileName) || $numReplaced == 0) {
                $newFileName = $prefix . $originalFileName;
            }
        }

        return $newFileName;
    }

    /**
     * @param string $fileKey
     * @param $uploadFolder
     * @return mixed|null|string
     * @throws \Exception
     */
    public static function upload($fileKey = 'file', $uploadFolder = 'upload')
    {
        $fileData = [];
        $maxSize = Configure::read('App.maxUploadSize');

        if (!$maxSize) {
            $maxSize = self::$maxSize;
        }

        if (empty($_FILES)) {
            throw new \Exception('Tidak ada file yang diupload');
        }

        $targetDir = new Folder(WWW_ROOT . 'files' . DS . $uploadFolder, true);
        if (!$targetDir->path) {
            throw new \Exception(sprintf("Path %s tidak dapat diakses", $uploadFolder));
        }

        if (isset($_FILES[$fileKey]['size'][0])) {
            $fileData = [
                'size' => $_FILES[$fileKey]['size'][0],
                'name' => $_FILES[$fileKey]['name'][0],
                'tmp_name' => $_FILES[$fileKey]['tmp_name'][0]
            ];
        } else {
            $fileData = [
                'size' => $_FILES[$fileKey]['size'],
                'name' => $_FILES[$fileKey]['name'],
                'tmp_name' => $_FILES[$fileKey]['tmp_name']
            ];
        }

        // Validate File Size Limit
        if ($fileData['size'] >= $maxSize * 1024 * 1024) {
            throw new \Exception(sprintf("Ukuran file melebihi batas %s MB", self::$maxSize));
        }

        // TODO Validate file extension to allow jrxml only
        $fileName = UploadService::generateFileName($fileData['name']);
        $destination = $targetDir->path . DS . $fileName;

        if (!move_uploaded_file($fileData['tmp_name'], $destination)) {
            throw new \Exception("Tidak berhasil upload file");
        }

        $fileUrl = Router::url("/webroot/files/$uploadFolder/$fileName", true);

        return [
            'file_name' => $fileName,
            'url' => $fileUrl
        ];
    }
}