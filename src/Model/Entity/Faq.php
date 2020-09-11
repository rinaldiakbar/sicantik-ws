<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Faq Entity
 *
 * @property int $id
 * @property int $faq_category_id
 * @property int|null $instansi_id
 * @property string $pertanyaan
 * @property string $jawaban
 * @property int|null $no_urut
 * @property string|null $file_lampiran
 * @property int $is_active
 * @property string|null $dibuat_oleh
 * @property \Cake\I18n\FrozenTime|null $tgl_dibuat
 * @property \Cake\I18n\FrozenTime|null $tgl_diubah
 * @property string|null $diubah_oleh
 * @property string|null $search
 *
 * @property \App\Model\Entity\Instansi $instansi
 * @property \App\Model\Entity\FaqCategory $faq_category
 */
class Faq extends Entity
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
        'faq_category_id' => true,
        'instansi_id' => true,
        'pertanyaan' => true,
        'jawaban' => true,
        'no_urut' => true,
        'file_lampiran' => true,
        'is_active' => true,
        'dibuat_oleh' => true,
        'tgl_dibuat' => true,
        'tgl_diubah' => true,
        'diubah_oleh' => true,
        'search' => true,
        'instansi' => true,
        'faq_category' => true
    ];
}
