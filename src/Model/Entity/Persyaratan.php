<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Persyaratan Entity.
 *
 * @property int $id
 * @property int $permohonan_id
 * @property \App\Model\Entity\PermohonanIzin $permohonan_izin
 * @property int $persyaratan_id
 * @property string $keterangan
 * @property string $lokasi_dokumen
 * @property \Cake\I18n\Time $awal_berlaku
 * @property \Cake\I18n\Time $akhir_berlaku
 * @property string $no_dokumen
 * @property int $terpenuhi
 * @property int $wajib
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 * @property \App\Model\Entity\Persyaratan[] $persyaratan
 */
class Persyaratan extends Entity
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
        'id' => false,
        'tgl_dibuat' => false,
        'diubat_oleh' => false,
        'tgl_diubah' => false,
        'diubah_oleh' => false
    ];
}
