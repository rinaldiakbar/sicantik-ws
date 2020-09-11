<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RetribusiDetail Entity.
 *
 * @property int $id
 * @property string $kode_item
 * @property string $nama_item
 * @property string $satuan
 * @property int $permohonan_izin_id
 * @property \App\Model\Entity\PermohonanIzin $permohonan_izin
 * @property float $harga
 * @property int $jumlah
 * @property float $subtotal
 */
class RetribusiDetail extends Entity
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
