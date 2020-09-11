<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CanvasTab Entity.
 *
 * @property int $id
 * @property int $canvas_id
 * @property \App\Model\Entity\Canva $canva
 * @property string $label
 * @property int $idx
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 */
class CanvasTab extends Entity
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
