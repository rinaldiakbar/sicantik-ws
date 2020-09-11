<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Element Entity.
 *
 * @property int $id
 * @property int $canvas_id
 * @property \App\Model\Entity\Canvas $canva
 * @property int $del
 * @property int $data_kolom_id
 * @property string $label
 * @property string $type
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 * @property int $required
 */
class CanvasElement extends Entity
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
        'id' => true,
    ];
}
