<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RestService Entity
 *
 * @property int $id
 * @property int $datatabel_id
 * @property int $is_active
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property \Cake\I18n\Time $tgl_diubah
 * @property string $diubah_oleh
 * @property int $instansi_id
 *
 * @property \App\Model\Entity\Datatabel $datatabel
 * @property \App\Model\Entity\Instansi $instansi
 */
class RestService extends Entity
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
