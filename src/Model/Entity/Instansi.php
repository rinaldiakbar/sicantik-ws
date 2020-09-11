<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Instansi Entity.
 *
 * @property int $id
 * @property string $nama
 * @property string $tipe
 * @property int $parent_id
 * @property \App\Model\Entity\ParentInstansi $parent_instansi
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 * @property \App\Model\Entity\Pegawai[] $pegawai
 * @property \App\Model\Entity\Peran[] $peran
 * @property \App\Model\Entity\ChildInstansi[] $child_instansi
 * @property \App\Model\Entity\Pengguna[] $pengguna
 */
class Instansi extends Entity
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
