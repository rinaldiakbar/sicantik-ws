<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Izin Entity.
 *
 * @property int $id
 * @property string $no_izin
 * @property string $no_izin_sebelumnya
 * @property int $jenis_izin_id
 * @property \App\Model\Entity\JenisIzin $jenis_izin
 * @property int $unit_id
 * @property \App\Model\Entity\Unit $unit
 * @property string $keterangan
 * @property int $pemohon_id
 * @property \App\Model\Entity\Pemohon $pemohon
 * @property int $perusahaan_id
 * @property \App\Model\Entity\Perusahaan $perusahaan
 * @property \Cake\I18n\Time $mulai_berlaku
 * @property \Cake\I18n\Time $akhir_berlaku
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 * @property \App\Model\Entity\PermohonanIzin[] $permohonan_izin
 * @property \App\Model\Entity\ProsesPermohonan[] $proses_permohonan
 */
class Izin extends Entity
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
        'tgl_dibuat' => false,
        'diubat_oleh' => false,
        'tgl_diubah' => false,
        'diubah_oleh' => false
    ];
}
