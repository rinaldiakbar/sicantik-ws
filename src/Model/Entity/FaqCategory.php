<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FaqCategory Entity
 *
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property int|null $no_urut
 * @property int $is_active
 * @property int|null $instansi_id
 * @property string|null $dibuat_oleh
 * @property \Cake\I18n\Time|null $tgl_dibuat
 * @property \Cake\I18n\Time|null $tgl_diubah
 * @property string|null $diubah_oleh
 *
 * @property \App\Model\Entity\Instansi $instansi
 * @property \App\Model\Entity\Faq[] $faq
 */
class FaqCategory extends Entity
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
        'nama' => true,
        'deskripsi' => true,
        'no_urut' => true,
        'is_active' => true,
        'instansi_id' => true,
        'dibuat_oleh' => true,
        'tgl_dibuat' => true,
        'tgl_diubah' => true,
        'diubah_oleh' => true,
        'instansi' => true,
        'faq' => true
    ];
}
