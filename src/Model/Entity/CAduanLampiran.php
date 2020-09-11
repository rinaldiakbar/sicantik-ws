<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CAduanLampiran Entity
 *
 * @property int $id
 * @property int $c_aduan_id
 * @property int $instansi_id
 * @property string $data_labels
 * @property int $del
 * @property string $dibuat_oleh
 * @property \Cake\I18n\FrozenDate $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\FrozenDate $tgl_diubah
 * @property string $file_lampiran
 * @property string $keterangan
 *
 * @property \App\Model\Entity\CAduan $c_aduan
 * @property \App\Model\Entity\Instansi $instansi
 */
class CAduanLampiran extends Entity
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
        'c_aduan_id' => true,
        'instansi_id' => true,
        'data_labels' => true,
        'del' => true,
        'dibuat_oleh' => true,
        'tgl_dibuat' => true,
        'diubah_oleh' => true,
        'tgl_diubah' => true,
        'file_lampiran' => true,
        'keterangan' => true,
        'c_aduan' => true,
        'instansi' => true
    ];
}
