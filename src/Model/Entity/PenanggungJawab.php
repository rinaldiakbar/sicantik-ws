<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PenanggungJawab Entity.
 *
 * @property int $id
 * @property int $alur_pengajuan_id
 * @property int $unit_id
 * @property \App\Model\Entity\Unit $unit
 * @property int $jabatan_id
 * @property \App\Model\Entity\Jabatan $jabatan
 * @property int $pegawai_id
 * @property \App\Model\Entity\Pegawai $pegawai
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 */
class PenanggungJawab extends Entity
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
