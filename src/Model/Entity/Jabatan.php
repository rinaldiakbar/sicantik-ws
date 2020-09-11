<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Jabatan Entity
 *
 * @property int $id
 * @property int $instansi_id
 * @property string $data_labels
 * @property int $del
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 * @property string $jabatan
 * @property string $nama_jabatan
 *
 * @property \App\Model\Entity\Instansi $instansi
 * @property \App\Model\Entity\NotifikasiDetail[] $notifikasi_detail
 * @property \App\Model\Entity\PenanggungJawab[] $penanggung_jawab
 */
class Jabatan extends Entity
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
