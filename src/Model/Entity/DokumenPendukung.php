<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DokumenPendukung Entity.
 *
 * @property int $id
 * @property int $jenis_izin_id
 * @property \App\Model\Entity\JenisIzin $jenis_izin
 * @property string $nama_dokumen
 * @property string $status
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 */
class DokumenPendukung extends Entity
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
