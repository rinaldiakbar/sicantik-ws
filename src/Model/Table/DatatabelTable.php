<?php
namespace App\Model\Table;

use App\Model\Entity\Datatabel;
use Cake\Log\LogTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Aura\Intl\Exception;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

/**
 * Datatabel Model
 *
 * @property \Cake\ORM\Association\HasMany $DataKolom
 */
class DatatabelTable extends AppTable
{

    use LogTrait;

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

        $this->setTable('datatabel');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('DataKolom', [
            'foreignKey' => 'datatabel_id'
        ]);

        $this->hasMany('UnitDatatabel', [
            'foreignKey' => 'datatabel_id'
        ]);

        $this->hasMany('Canvas', [
            'foreignKey' => 'datatabel_id'
        ]);

        $this->hasMany('RestServices', [
           'foreignKey' => 'datatabel_id'
        ]);

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id'
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
            ->requirePresence('nama_datatabel', 'create')
            ->notEmpty('nama_datatabel');

        $validator
            ->requirePresence('label', 'create')
            ->notEmpty('label');

        $validator
            ->notEmpty('visible');

        $validator
            ->notEmpty('is_custom');

        $validator
            ->notEmpty('is_view');

        $validator
            ->notEmpty('use_mapper');

        $validator
            ->allowEmpty('data_labels');

        return $validator;
    }

    public function createTable($datatabelId)
    {
        $datatabel = $this->get($datatabelId, [
            'contain' => [
                'DataKolom' => [
                    'fields' => [
                        'DataKolom.id',
                        'DataKolom.datatabel_id',
                        'DataKolom.data_kolom',
                        'DataKolom.label',
                        'DataKolom.tipe_kolom',
                        'DataKolom.panjang'
                    ]
                ]
            ]
        ]);

        if (!empty($datatabel->data_kolom)) {

            $connection = ConnectionManager::get('default');
            Configure::write('debug', false);

            $success = $connection->transactional(function ($connection) use($datatabel) {
                try {
                    $tableName = $datatabel->nama_datatabel;
                    $indexQueries = [
                        "CREATE INDEX {$tableName}_instansi_id ON {$tableName} USING btree (instansi_id)",
                        "CREATE INDEX {$tableName}_data_labels ON {$tableName} USING btree (data_labels)",
                        "CREATE INDEX {$tableName}_del ON {$tableName} USING btree (del)",
                        "CREATE INDEX {$tableName}_dibuat_oleh ON {$tableName} USING btree (dibuat_oleh)",
                        "CREATE INDEX {$tableName}_tgl_dibuat ON {$tableName} USING btree (tgl_dibuat)",
                        "CREATE INDEX {$tableName}_diubah_oleh ON {$tableName} USING btree (diubah_oleh)",
                        "CREATE INDEX {$tableName}_tgl_diubah ON {$tableName} USING btree (tgl_diubah)",
                    ];

                    $sqlQuery = "CREATE TABLE IF NOT EXISTS {$tableName} ";
                    $sqlQuery .= "( id bigserial NOT NULL, ";
                    $sqlQuery .= "instansi_id bigint NOT NULL, ";
                    $sqlQuery .= "data_labels TEXT, ";
                    $sqlQuery .= "del smallint NOT NULL DEFAULT 0, ";
                    $sqlQuery .= "dibuat_oleh character varying(25), ";
                    $sqlQuery .= "tgl_dibuat date, ";
                    $sqlQuery .= "diubah_oleh character varying(25), ";
                    $sqlQuery .= "tgl_diubah date, ";

                    foreach ($datatabel->data_kolom as $dataKolomIndex => $dataKolom) {
                        switch ($dataKolom->tipe_kolom) {
                            case 'character varying':
                                $sqlQuery .= "{$dataKolom->data_kolom} {$dataKolom->tipe_kolom}({$dataKolom->panjang}), ";
                                break;
                            default:
                                $sqlQuery .= "{$dataKolom->data_kolom} {$dataKolom->tipe_kolom}, ";
                                break;
                        }
                        $indexQueries[] = "CREATE INDEX {$tableName}_{$dataKolom->data_kolom} ON {$tableName} USING btree ({$dataKolom->data_kolom})";
                    }

                    $sqlQuery .= "CONSTRAINT " . $tableName . "_pkey PRIMARY KEY (id)";
                    $sqlQuery .= ") WITH ( OIDS=FALSE)";

                    // Run the Query
                    if ($connection->execute($sqlQuery)) {
                        // Query Update all field_created into 1
                        $this->DataKolom->updateAll(
                            ['field_dibuat' => 1], // fields
                            ['datatabel_id' => $datatabel->id]); // conditions

                        // Combine the index into 1 query and execute it
                        if (!empty($indexQueries)) {
                            foreach ($indexQueries as $indexQuery) {
                                $connection->execute($indexQuery);
                            }
                        }

                        return true;
                    }
                } catch (\Exception $e) {
                    $this->log($e->getMessage());
                    return false;
                }
            });

            return $success;
        }
        return false;
    }

    public function alterTable($datatabelId)
    {
        $datatabel = $this->get($datatabelId, [
            'contain' => [
                'DataKolom' => [
                    'fields' => [
                        'DataKolom.id',
                        'DataKolom.datatabel_id',
                        'DataKolom.data_kolom',
                        'DataKolom.label',
                        'DataKolom.tipe_kolom',
                        'DataKolom.panjang',
                        'DataKolom.field_dibuat'
                    ]
                ]
            ]
        ]);

        if (!empty($datatabel->data_kolom)) {
            $connection = ConnectionManager::get('default');
//            Configure::write('debug', false);

            $success = $connection->transactional(function ($connection) use($datatabel) {
                try {
                    $tableName = $datatabel->nama_datatabel;
                    $prefixQuery = "ALTER TABLE {$tableName} ";

                    foreach ($datatabel->data_kolom as $dataKolom) {
                        if ($dataKolom->field_dibuat == 0) {
                            // Add Column
                            $sqlQuery = $prefixQuery;
                            switch ($dataKolom->tipe_kolom) {
                                case 'character varying':
                                    $sqlQuery .= "ADD COLUMN {$dataKolom->data_kolom} {$dataKolom->tipe_kolom}({$dataKolom->panjang})";
                                    break;
                                default:
                                    $sqlQuery .= "ADD COLUMN {$dataKolom->data_kolom} {$dataKolom->tipe_kolom}";
                                    break;
                            }
                            $connection->execute($sqlQuery);
                            $dataKolom->field_dibuat = 1;
                            $this->DataKolom->save($dataKolom);

                            // Add index for the new column
                            $indexQuery = "CREATE INDEX {$tableName}_{$dataKolom->data_kolom} ON {$tableName} USING btree ({$dataKolom->data_kolom})";
                            $connection->execute($indexQuery);

                        }
                    }

                    return true;
                } catch (\Exception $e) {
                    $this->log($e->getMessage());
                    return false;
                }
            });

            return $success;
        }
        return false;
    }


    /**
     * Check if the nama_datatabel name already exists
     * @param $name
     * @return bool
     */
    public function tableNameExists($name)
    {
        $datatabel = $this->find('all')->where(['nama_datatabel' => $name])->first();
        if (!empty($datatabel)) {
            return true;
        }
        return false;
    }

    public function hasInstansiField($tableName)
    {
        $tableWithNoIntansiId = [
            'desa', 'kecamatan', 'kabupaten', 'provinsi'
        ];

        return !in_array($tableName, $tableWithNoIntansiId);
    }

    public function afterSave(\Cake\Event\Event $event, \App\Model\Entity\Datatabel $entity)
    {
        // Delete all model file cache
        $folder = new \Cake\Filesystem\Folder(ROOT . DS . 'tmp/cache/models');
        $folder->delete();

        return;
    }

    public function afterDelete(\Cake\Event\Event $event, \App\Model\Entity\Datatabel $entity, \ArrayObject $options)
    {
        // Delete all model file cache
        $folder = new \Cake\Filesystem\Folder(ROOT . DS . 'tmp/cache/models');
        $folder->delete();

        return;
    }
}
