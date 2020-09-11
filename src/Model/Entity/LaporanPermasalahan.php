<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LaporanPermasalahan Entity
 *
 * @property int $id
 * @property int $instansi_id
 * @property string $data_labels
 * @property int $del
 * @property string $dibuat_oleh
 * @property \Cake\I18n\FrozenDate $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\FrozenDate $tgl_diubah
 * @property string $kategori
 * @property string $permasalahan
 * @property string $tanggapan
 * @property string $status
 * @property string $source
 *
 * @property \App\Model\Entity\Instansi $instansi
 */
class LaporanPermasalahan extends Entity
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
