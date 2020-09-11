<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NotifikasiDetail Entity
 *
 * @property int $id
 * @property int $notifikasi_id
 * @property int $daftar_proses_id
 * @property string $tipe
 * @property string $format_pesan
 * @property string $tipe_penerima
 * @property int $jabatan_id
 *
 * @property \App\Model\Entity\Notifikasi $notifikasi
 * @property \App\Model\Entity\DaftarProse $daftar_prose
 * @property \App\Model\Entity\Jabatan $jabatan
 */
class NotifikasiDetail extends Entity
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
