<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DaftarProse Entity.
 *
 * @property int $id
 * @property int $alur_proses_id
 * @property \App\Model\Entity\AlurProse $alur_prose
 * @property int $jenis_proses_id
 * @property \App\Model\Entity\JenisProse $jenis_prose
 * @property int $no
 * @property string $nama_proses
 * @property string $tautan
 * @property int $form_id
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 */
class DaftarProses extends Entity
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
