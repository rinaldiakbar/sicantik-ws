<?php
/**
 * Business Logic for Numbering
 * Created by PhpStorm.
 * User: Core
 * Date: 11/20/2016
 * Time: 10:57 AM
 */

namespace App\Service;

use Cake\ORM\TableRegistry;

class NumberingService
{
    private static $bulanRomawi = array(
        '01' => 'I',
        '02' => 'II',
        '03' => 'III',
        '04' => 'IV',
        '05' => 'V',
        '06' => 'VI',
        '07' => 'VII',
        '08' => 'VIII',
        '09' => 'IX',
        '10' => 'X',
        '11' => 'XI',
        '12' => 'XII'
    );

    /**
     * Fungsi untuk menerjemahkan format nomor surat menjadi nomor yang siap digunakan
     * @param string $numberFormat
     * @param integer $lastSequence
     * @return string $newNumber newly translated number
     */
    public static function translateFormat($numberFormat, $lastSequence, $jenisIzinId = 1)
    {
        $newNumber = '';
        $start = '{';
        $end = '}';
        $pattern = sprintf(
            '/%s(.+?)%s/ims',
            preg_quote($start, '/'), preg_quote($end, '/')
        );
        $sequence = $lastSequence + 1;

        $matches = array();
        $replacementElements = array();
        preg_match_all($pattern, $numberFormat, $matches);

        if (isset($matches[0]) && isset($matches[1])) {
            foreach ($matches[1] as $key => $rawFormat) {
                ## Menggganti kode bulan dan tahun ##
                $newElement = str_replace('DD', date('d'), $rawFormat);
                $newElement = str_replace('MM', self::$bulanRomawi[date('m')], $newElement);
                $newElement = str_replace('mm', date('m'), $newElement);
                $newElement = str_replace('YYYY', date('Y'), $newElement);
                #####################################

                ### Mengganti kode nomor urut surat sesuai dengan panjang karakternya ###
                $seqLength = substr_count($newElement, 'N');//Panjang karakter nomor urut
                $seqFormat = str_repeat('N', $seqLength);
                $paddedSeq = str_pad($sequence, $seqLength, '0', STR_PAD_LEFT);
                $newElement = str_replace($seqFormat, $paddedSeq, $newElement);
                #########################################################################

                ### Mengganti kode jenis izin sesuai dengan panjang karakternya ###
                $perizinanLength = substr_count($newElement, 'J');//Panjang karakter nomor urut
                $perizinanFormat = str_repeat('J', $perizinanLength);
                $paddedPerizinan = str_pad($jenisIzinId, $perizinanLength, '0', STR_PAD_LEFT);
                $newElement = str_replace($perizinanFormat, $paddedPerizinan, $newElement);
                #########################################################################
                $replacementElements[$key] = $newElement;
            }
            $newNumber = str_replace($matches[0], $replacementElements, $numberFormat);
        }
        return $newNumber;
    }

    /**
     * Function to get formatted number
     * @param int $numberingId
     * @param int $instansiId
     * @param int|null $unitId
     * @param bool $updateLastSequence
     * @return string | null formatted number
     */
    public static function getFormattedNumber($numberingId, $instansiId, $unitId = null, $updateLastSequence = false) {
        $formattedNumber = null;
        $lastNumber = 0;
        $penomoranDetail = null;

        $penomoranTable = TableRegistry::get('Penomoran');
        $penomoranDetailTable = TableRegistry::get('PenomoranDetail');

        try {
            // Find the numbering setting
            $penomoran = $penomoranTable->get($numberingId, [
                'fields' => [
                    'id', 'format', 'no_terakhir'
                ],
                'contain' => [
                    'PenomoranDetail' => [
                        'fields' => ['id', 'penomoran_id']
                    ]
                ]
            ]);

            if ($penomoran) {
                // if detail exists, try to find matching unit's last number
                if (!empty($penomoran->penomoran_detail)) {
                    $penomoranDetail = $penomoranDetailTable->find('all', [
                        'conditions' => [
                            'penomoran_id' => $numberingId,
                            'unit_id' => $unitId
                        ]
                    ])->first();

                    if ($penomoranDetail) { // If found, get the last number from matching detail
                        $lastNumber = (int) $penomoranDetail->no_terakhir;
                    } else {
                        $lastNumber = (int) $penomoran->no_terakhir;
                    }
                } else { // If not found, get the last number from penomoran
                    $lastNumber = (int) $penomoran->no_terakhir;
                }

                // Generate formatted numbering
                $formattedNumber = self::translateFormat($penomoran->format, $lastNumber);

                if ($updateLastSequence) {
                    if ($penomoranDetail) {
                        $penomoranDetail->no_terakhir = $lastNumber + 1;
                        $penomoranDetailTable->save($penomoranDetail);
                    } else {
                        $penomoran->no_terakhir = $lastNumber + 1;
                        $penomoranTable->save($penomoran);
                    }
                }
            }
        } catch (\Exception $ex) {

        }

        return $formattedNumber;
    }

    public static function getFormattedNbrMdl($modulePath,$updateLastSequence = false){
        // penomoran permodul statik
        $modulTable = TableRegistry::get('penomoran_module');
        $module = $modulTable->find()->select();
        $module->where(['module'=>$modulePath]);        

        if ($module) {
            foreach ($module as $mod) {
                return self::getFormattedNumber($mod['penomoran_id'], $mod['instansi_id'], null, true);
            }
        } else {
            return 0;
        }
    }
}