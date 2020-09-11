<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * JenisPengajuan Entity.
 *
 * @property int $id
 * @property int $jenis_izin_id
 * @property \App\Model\Entity\JenisIzin $jenis_izin
 * @property string $jenis_pengajuan
 * @property int $alur_proses_id
 * @property \App\Model\Entity\AlurProse $alur_prose
 * @property int $lama_proses
 * @property int $masa_berlaku_izin
 * @property string $satuan_masa_berlaku
 */
class JenisPengajuan extends Entity
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
