<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PermohonanIzin Entity.
 *
 * @property int $id
 * @property string $no_permohonan
 * @property int $pemohon_id
 * @property \App\Model\Entity\Pemohon $pemohon
 * @property int $perusahaan_id
 * @property \App\Model\Entity\Perusahaan $perusahaan
 * @property string $keterangan
 * @property string $jenis_permohonan
 * @property int $izin_id
 * @property \App\Model\Entity\Izin $izin
 * @property string $no_izin_lama
 * @property \Cake\I18n\Time $tgl_pengajuan
 * @property \Cake\I18n\Time $tgl_selesai
 * @property int $proses_id
 * @property \App\Model\Entity\Prose $prose
 * @property string $status
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 */
class PermohonanIzin extends Entity
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
