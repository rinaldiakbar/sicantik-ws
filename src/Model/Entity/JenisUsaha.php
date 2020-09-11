<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * JenisUsaha Entity.
 *
 * @property int $id
 * @property string $kode
 * @property string $keterangan
 * @property int $bidang_usaha_id
 * @property \App\Model\Entity\BidangUsaha $bidang_usaha
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 * @property \App\Model\Entity\JenisUsahaPermohonan[] $jenis_usaha_permohonan
 * @property \App\Model\Entity\Perusahaan[] $perusahaan
 */
class JenisUsaha extends Entity
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
