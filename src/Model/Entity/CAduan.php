<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CAduan Entity
 *
 * @property int $id
 * @property int $instansi_id
 * @property string $data_labels
 * @property int $del
 * @property string $dibuat_oleh
 * @property \Cake\I18n\FrozenDate $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\FrozenDate $tgl_diubah
 * @property string $kategori
 * @property string $aduan
 * @property string $status
 * @property string $penyelesaian
 * @property \Cake\I18n\FrozenDate $tgl_aduan
 * @property \Cake\I18n\FrozenDate $tgl_penyelesaian
 * @property string $penanggung_jawab
 *
 * @property \App\Model\Entity\Instansi $instansi
 * @property \App\Model\Entity\CAduanKomentar[] $c_aduan_komentar
 * @property \App\Model\Entity\CAduanLampiran[] $c_aduan_lampiran
 */
class CAduan extends Entity
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
        'instansi_id' => true,
        'data_labels' => true,
        'del' => true,
        'dibuat_oleh' => true,
        'tgl_dibuat' => true,
        'diubah_oleh' => true,
        'tgl_diubah' => true,
        'kategori' => true,
        'aduan' => true,
        'status' => true,
        // 'penyelesaian' => true,
        'tgl_aduan' => true,
        'tgl_penyelesaian' => true,
        // 'penanggung_jawab' => true,
        'instansi' => true,
        'no_tiket' => true,
        'c_aduan_komentar' => true,
        'c_aduan_lampiran' => true,
        'no_tiket' => true
    ];
}
