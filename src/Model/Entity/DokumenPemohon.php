<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DokumenPemohon Entity
 *
 * @property int $id
 * @property int $jenis_dokumen_id
 * @property string $no_dokumen
 * @property string $lokasi_dokumen
 * @property \Cake\I18n\Time $awal_berlaku
 * @property \Cake\I18n\Time $akhir_berlaku
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property \Cake\I18n\Time $tgl_diubah
 * @property string $diubah_oleh
 *
 * @property \App\Model\Entity\JenisDokumen $jenis_dokumen
 */
class DokumenPemohon extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
