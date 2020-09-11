<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * QueueJob Entity
 *
 * @property int $id
 * @property string $type
 * @property string $body
 * @property string $status
 * @property int $priority
 * @property int $delay_time
 * @property \Cake\I18n\Time $execute_time
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property \Cake\I18n\Time $tgl_diubah
 * @property string $diubah_oleh
 */
class QueueJob extends Entity
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
