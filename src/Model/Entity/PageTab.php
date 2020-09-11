<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PageTab Entity
 *
 * @property int $id
 * @property int $instansi_id
 * @property string $data_labels
 * @property int $del
 * @property string $dibuat_oleh
 * @property \Cake\I18n\FrozenDate $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\FrozenDate $tgl_diubah
 * @property int $page_id
 * @property string $label
 * @property int $tab_idx
 *
 * @property \App\Model\Entity\Instansi $instansi
 * @property \App\Model\Entity\Page $page
 */
class PageTab extends Entity
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
        'instansi_id' => true,
        'data_labels' => true,
        'del' => true,
        'dibuat_oleh' => true,
        'tgl_dibuat' => true,
        'diubah_oleh' => true,
        'tgl_diubah' => true,
        'page_id' => true,
        'label' => true,
        'tab_idx' => true,
        'instansi' => true,
        'page' => true
    ];
}
