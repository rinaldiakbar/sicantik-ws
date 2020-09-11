<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Pegawai Entity.
 *
 * @property int $id
 * @property string $nama
 * @property int $unit_id
 * @property string $posisi
 * @property string $jabatan
 * @property string $no_hp
 * @property string $email
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 * @property \App\Model\Entity\Pengguna[] $pengguna
 */
class Pegawai extends Entity
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
