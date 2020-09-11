<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PenanggungJawabPeran Entity
 *
 * @property int $id
 * @property int $peran_id
 * @property int $instansi_id
 * @property string $data_labels
 * @property int $del
 * @property string $dibuat_oleh
 * @property \Cake\I18n\FrozenDate $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\FrozenDate $tgl_diubah
 *
 * @property \App\Model\Entity\Peran $peran
 * @property \App\Model\Entity\Instansi $instansi
 */
class PenanggungJawabPeran extends Entity
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
        'peran_id' => true,
        'instansi_id' => true,
        'data_labels' => true,
        'del' => true,
        'dibuat_oleh' => true,
        'tgl_dibuat' => true,
        'diubah_oleh' => true,
        'tgl_diubah' => true,
        'peran' => true,
        'instansi' => true,
        'reviewer' =>true
    ];
}
