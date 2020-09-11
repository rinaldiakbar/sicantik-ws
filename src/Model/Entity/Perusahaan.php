<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Perusahaan Entity.
 *
 * @property int $id
 * @property string $nama_perusahaan
 * @property string $npwp
 * @property string $no_register
 * @property int $jenis_usaha_id
 * @property \App\Model\Entity\JenisUsaha $jenis_usaha
 * @property int $bidang_usaha_id
 * @property \App\Model\Entity\BidangUsaha $bidang_usaha
 * @property string $jenis_perusahaan
 * @property int $jumlah_pegawai
 * @property float $nilai_investasi
 * @property string $no_tlp
 * @property string $fax
 * @property string $email
 * @property string $alamat
 * @property int $desa_id
 * @property \App\Model\Entity\Desa $desa
 * @property int $kecamatan_id
 * @property \App\Model\Entity\Kecamatan $kecamatan
 * @property int $kabupaten_id
 * @property \App\Model\Entity\Kabupaten $kabupaten
 * @property int $provinsi_id
 * @property \App\Model\Entity\Provinsi $provinsi
 * @property string $kode_pos
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 * @property \App\Model\Entity\Izin[] $izin
 * @property \App\Model\Entity\Pemohon[] $pemohon
 * @property \App\Model\Entity\PermohonanIzin[] $permohonan_izin
 */
class Perusahaan extends Entity
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
        'id' => true,
        'tgl_dibuat' => false,
        'diubat_oleh' => false,
        'tgl_diubah' => false,
        'diubah_oleh' => false
    ];
}
