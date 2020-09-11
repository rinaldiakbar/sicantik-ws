<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReportComponentDetail Entity
 *
 * @property int $id
 * @property int $report_component_id
 * @property int $daftar_proses_id
 * @property int $pegawai_id
 *
 * @property \App\Model\Entity\ReportComponent $report_component
 * @property \App\Model\Entity\DaftarProse $daftar_prose
 * @property \App\Model\Entity\Pegawai $pegawai
 */
class ReportComponentDetail extends Entity
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
