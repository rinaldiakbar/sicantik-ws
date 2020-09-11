<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TemplateData Entity.
 *
 * @property int $id
 * @property int $instansi_id
 * @property \App\Model\Entity\Instansi $instansi
 * @property string $keterangan
 * @property string $tipe_keluaran
 * @property string $template_dokumen
 * @property boolean $output_as_pdf
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 * @property \App\Model\Entity\KelompokData[] $kelompok_data
 */
class TemplateData extends Entity
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
