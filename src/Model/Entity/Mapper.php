<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mapper Entity.
 *
 * @property int $id
 * @property int $key_id
 * @property \App\Model\Entity\Key $key
 * @property int $datatabel_id
 * @property \App\Model\Entity\Datatabel $datatabel
 * @property string $nama_datatabel
 */
class Mapper extends Entity
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
