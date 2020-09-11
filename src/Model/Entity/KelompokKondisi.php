<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * KelompokKondisi Entity.
 *
 * @property int $id
 * @property int $kelompok_data_id
 * @property \App\Model\Entity\KelompokData $kelompok_data
 * @property string $nama_tabel_utama
 * @property string $nama_tabel_1
 * @property string $nama_kolom_1
 * @property string $tipe_kondisi
 * @property string $nama_tabel_2
 * @property string $nama_kolom_2
 * @property string $tipe_relasi
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 */
class KelompokKondisi extends Entity
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
    ];
}
