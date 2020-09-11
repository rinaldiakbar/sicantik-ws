<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DataKolom Entity.
 *
 * @property int $id
 * @property int $datatabel_id
 * @property \App\Model\Entity\Datatabel $datatabel
 * @property string $data_kolom
 * @property string $label
 * @property int $field_dibuat
 */
class DataKolom extends Entity
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
        'field_dibuat' => true,
        'tgl_dibuat' => false,
        'diubat_oleh' => false,
        'tgl_diubah' => false,
        'diubah_oleh' => false
    ];
}
