<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UnitDatatabel Entity.
 *
 * @property int $id
 * @property int $datatabel_id
 * @property \App\Model\Entity\Datatabel $datatabel
 * @property int $unit_id
 * @property \App\Model\Entity\Unit $unit
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 * @property string $diubah_oleh
 */
class UnitDatatabel extends Entity
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
