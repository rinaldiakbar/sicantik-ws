<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MenuModule Entity
 *
 * @property int $id
 * @property string $tautan
 * @property int $menu_id
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property \Cake\I18n\Time $tgl_diubah
 * @property string $diubah_oleh
 *
 * @property \App\Model\Entity\Menu $menu
 */
class MenuModule extends Entity
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
        'id' => true,
        'tautan' => true,
        'menu_id' => true,
        'dibuat_oleh' => true,
        'tgl_dibuat' => true,
        'tgl_diubah' => true,
        'diubah_oleh' => true,
        'menu' => true
    ];
}
