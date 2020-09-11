<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * KelompokData Entity.
 *
 * @property int $id
 * @property int $template_data_id
 * @property \App\Model\Entity\TemplateData $template_data
 * @property string $label_kelompok
 * @property string $jenis_sumber
 * @property string $sql
 * @property string $dibuat_oleh
 * @property \Cake\I18n\Time $tgl_dibuat
 * @property string $diubah_oleh
 * @property \Cake\I18n\Time $tgl_diubah
 */
class KelompokData extends Entity
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

    public function getCombogridConfig()
    {
        $config = null;
        if (!empty($this)) {
            $parsedCgFields = [];
            $cgFields = json_decode($this->combogrid_fields);
            if (!empty($cgFields)) {
                $countCgFields = count($cgFields);
                $fieldWidth = round(100 / $countCgFields);
                foreach ($cgFields as $index => $cgField) {
                    $parsedCgFields[$index]['columnName'] = $cgField;
                    $parsedCgFields[$index]['label'] = \Cake\Utility\Inflector::humanize($cgField);
                    $parsedCgFields[$index]['width'] = $fieldWidth;
                }
            }

            $config['cg_label_col'] = $this->combogrid_label_col;
            $config['cg_value_col'] = $this->combogrid_value_col;
            $config['cg_fields'] = $parsedCgFields;
            $config['cg_url'] =
                \Cake\Routing\Router::url([
                    'controller' => 'TemplateData', 'action' => 'combogrid', $this->id.'.json',
                ], true);
        }
        return $config;
    }
}
