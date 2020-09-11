<?php
namespace App\Service;

use Cake\I18n\Time;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Base Service for with Authentication data
 * Created by Indra
 * Date: 25/09/16
 * Time: 23:09
 */
class AuthService 
{
    CONST PEMOHON_OBJECT = 'Pemohon';
    CONST ADMINISTRATOR_ROLE = 'Administrator';

    protected static $user;
    protected static $instansi;
    protected static $unit;

    protected static $dateFormat = 'd-m-Y';
    protected static $dbDateFormat = 'Y-m-d';
    protected static $errors = [];

    /**
     * @return string
     */
    public static function getDateFormat()
    {
        return self::$dateFormat;
    }

    /**
     * @return string
     */
    public static function getDbDateFormat()
    {
        return self::$dbDateFormat;
    }

    /**
     * Set current user
     * @param array $userData
     */
    public static function setUser($userData)
    {
        self::$user = (object) $userData;
        return;
    }

    /**
     * Set current user's instansi
     * @param $instansiId
     */
    public static function setInstansi($instansi)
    {
        self::$instansi = $instansi;
        return;
    }

    /**
     * Set current user's unit
     * @param $unitId
     */
    public static function setUnit($unit)
    {
        self::$unit = $unit;
        return;
    }

    /**
     * Get Pemohon data of a user 
     * @return App\Model\Entity\Pemohon|false
     */
    public static function getUserPemohon()
    {
        if (self::$user) {
            $pengguna = self::$user;

            if (!$pengguna || $pengguna->related_object_name != self::PEMOHON_OBJECT || !$pengguna->related_object_id) {
                return false;
            }

            $pemohonTable = TableRegistry::get('Pemohon');
            $pemohon = $pemohonTable->find('all', [
                'conditions' => [
                    'id' => $pengguna->related_object_id
                ]
            ])->first();

            if (!$pemohon) {
                return false;
            }

            return $pemohon;
        }
    }

    public static function isSuperAdmin()
    {
        if (self::$user) {
            $pengguna = self::$user;
            $peranTable = TableRegistry::get('Peran');
            $peranAdministrator = $peranTable->find('all', [
                'conditions' => [
                    'label_peran ILIKE' => self::ADMINISTRATOR_ROLE,
                    'id' => $pengguna->peran_id
                ]
            ]);

            if ($peranAdministrator->count() > 0) {
                return true;
            }
        }

        return false;
    }

    public static function generateResetPasswordToken(\App\Model\Entity\Pengguna $pengguna)
    {
        // Add two new fields
        // reset_token
        // reset_expired_at
        try {
            $penggunaTable = TableRegistry::get('Pengguna');

            $token = self::getRandomString(15);
            $now = Time::now();
            $resetExpiredDate = $now->modify('+1 days');

            $pengguna->tgl_expired_reset = $resetExpiredDate;
            $pengguna->reset_token = $token;

            if (!$penggunaTable->save($pengguna)) {
                throw new \Exception('Tidak dapat menyimpan token untuk reset password');
            }

            return $token;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public static function getRandomString($length = 5)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    public static function getErrors()
    {
        return self::$errors;
    }
}