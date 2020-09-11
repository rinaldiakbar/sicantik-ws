<?php
namespace App\Model\Table;

use App\Model\Entity\TemplateData;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Psr\Log\LogLevel;

/**
 * TemplateData Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Instansi
 * @property \Cake\ORM\Association\HasMany $KelompokData
 */
class TemplateDataTable extends AppTable
{
    use \Cake\Log\LogTrait;

    const TIPE_COMBOGRID_SOURCE = 'Combogrid JSON';
    const TIPE_DOKUMEN = 'Dokumen Cetak';
    const TIPE_JSON = 'JSON';
    const TIPE_TAMPILAN = 'Tampilan Data';
    const TIPE_XML = 'XML';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->strictUpdate = true;
        $this->strictDelete = true;

        $this->setTable('template_data');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id'
        ]);

        $this->hasMany('KelompokData', [
            'foreignKey' => 'template_data_id'
        ]);

        $this->hasMany('DaftarProses', [
            'foreignKey' => 'template_data_id'
        ]);

        $this->hasMany('ProsesPermohonan', [
            'foreignKey' => 'template_data_id'
        ]);

        $this->hasMany('Canvas', [
            'foreignKey' => 'template_data_id'
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'tgl_dibuat' => 'new',
                    'tgl_diubah' => 'existing',
                ]
            ]
        ]);

        $this->addBehavior('Muffin/Footprint.Footprint', [
            'events' => [
                'Model.beforeSave' => [
                    'dibuat_oleh' => 'new',
                    'diubah_oleh' => 'existing',
                ]
            ],
            'propertiesMap' => [
                'dibuat_oleh' => '_footprint.username',
                'diubah_oleh' => '_footprint.username',
            ],
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('keterangan');

        $validator
            ->requirePresence('tipe_keluaran', 'create')
            ->notEmpty('tipe_keluaran');

        $validator
            ->allowEmpty('template_dokumen');

        $validator
            ->allowEmpty('output_as_pdf');

        $validator
            ->allowEmpty('dibuat_oleh');

        $validator
            ->date('tgl_dibuat')
            ->allowEmpty('tgl_dibuat');

        $validator
            ->allowEmpty('diubah_oleh');

        $validator
            ->date('tgl_diubah')
            ->allowEmpty('tgl_diubah');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['instansi_id'], 'Instansi'));
        return $rules;
    }

    public function afterSave(Event $event, TemplateData $entity)
    {
        switch ($entity->tipe_keluaran) {

            case self::TIPE_COMBOGRID_SOURCE:

                if (!empty($entity->kelompok_data)) {
                    // Dummy variables to prevent error, 
                    // compare this with variables used in TemplateDataController->combogrid()
                    $user_id = null;
                    $instansi_id = null;
                    $unit_id = null;
                    $pegawai_id = null;
                    $peran_id = null;

                    $q = '';
                    $sqlQuery = '';
                    $kelompokDataTable = TableRegistry::get('KelompokData');
                    $connection = \Cake\Datasource\ConnectionManager::get('default');

                    foreach ($entity->kelompok_data as $kelompokData) {
                        try {
                            $fields = [];

                            // Get the first SQL Query and Eval SQL String
                            eval("\$sqlQuery = \"$kelompokData->sql\";");
                            $sqlQueryData = "$sqlQuery LIMIT 1 OFFSET 0";

                            // try to extract the fields from sql
                            $fields = $this->extractColumn($sqlQueryData);

                            // if not working, try to execute the sql
                            if (empty($fields)) {
                                // Run the Query and Parse the columns
                                $results = $connection->query($sqlQueryData)->fetch('assoc');
                                if ($results) {
                                    $fields = array_keys($results);
                                }
                            }

                            if (!empty ($fields)) {
                                $kelompokData->combogrid_value_col = $fields[0]; // Default to First Field
                                $kelompokData->combogrid_fields = json_encode($fields);
                                $kelompokDataTable->save($kelompokData);
                            }
                        } catch (\Exception $e) {
                            $this->log('[TemplateData]['.$entity->id.'-'.$entity->keterangan.']: Error parsing combgrid_fields', LogLevel::ERROR);
                        }
                    }
                }

            break;
        }
        return;
    }

    /**
     * Extract field names from sql query
     *
     * @param [type] $sql
     * @return void
     */
    public function extractColumn($sql) {
        $result = [];
        
        $sql = strtolower($sql);

        $selectStartIndex = strpos($sql, 'select');
        $selectEndIndex = $selectStartIndex + 6;
        $fromStartIndex = strpos($sql, 'from');
        $fromEndIndex = $fromStartIndex + 4;
        
        $sqlLength = strlen($sql);
        $columnSql = trim(substr($sql, $selectEndIndex, $fromStartIndex - $selectEndIndex));
        $columns = explode(',', $columnSql);

        if (!empty($columns)) {
            foreach ($columns as $column) {
                $fieldName = null;

                if (strpos(strtolower($column), ' as ')) {
                    $columnAlias = explode('as', $column);
                    if (count($columnAlias) == 2) {
                        $fieldName = trim($columnAlias[1]);
                    }
                } else {
                    $fieldName = trim($column);
                }

                if ($fieldName) {
                    // if field name contains table name
                    if (strpos($fieldName, '.') !== false) {
                        $splitField = explode('.', $fieldName);
                        if (count($splitField) == 2) {
                            $fieldName = $splitField[1];
                        }
                    }
                    $result[] = $fieldName;
                }
            }
        }

        return $result;
    }
}
