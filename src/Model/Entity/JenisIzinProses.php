<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * JenisIzinProses Entity.
 *
 * @property int $id
 * @property int $jenis_izin_id
 * @property \App\Model\Entity\JenisIzin $jenis_izin
 * @property int $daftar_proses_id
 * @property \App\Model\Entity\DaftarProses $daftar_proses
 * @property string $tautan
 * @property int $form_id
 * @property \App\Model\Entity\Form $form
 * @property int $template_data_id
 * @property \App\Model\Entity\TemplateData $template_data
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property \Cake\I18n\Time $tgl_diubah
 * @property string $diubah_oleh
 */
class JenisIzinProses extends Entity
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
