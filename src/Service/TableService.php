<?php
/**
 * Business Logic for Datatabel
 * Created by Indra.
 * User: core
 * Date: 21/01/17
 * Time: 15:34
 */

namespace App\Service;

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Phinx\Db\Table;
use Cake\Log\Log;
use Psr\Log\LogLevel;

class TableService
{
    /**
     * Convenience method to write a message to Log. See Log::write()
     * for more information on writing to logs.
     *
     * @param mixed $msg Log message.
     * @param int|string $level Error level.
     * @param string|array $context Additional log data relevant to this message.
     * @return bool Success of log write.
     */
    public static function log($msg, $level = LogLevel::ERROR, $context = [])
    {
        return Log::write($level, $msg, $context);
    }

    public static function createTable($datatabelId)
    {
        $datatabelTable = TableRegistry::get('Datatabel');
        $datatabel = $datatabelTable->get($datatabelId, [
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

            $success = $connection->transactional(function ($connection) use($datatabel, $datatabelTable) {
                try {
                    $tableName = $datatabel->nama_datatabel;
                    $indexQueries = [
                        "CREATE INDEX {$tableName}_permohonan_izin_id ON {$tableName} USING btree (permohonan_izin_id)",
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
                    $sqlQuery .= "permohonan_izin_id bigint NOT NULL, ";
                    $sqlQuery .= "instansi_id bigint NOT NULL, ";
                    $sqlQuery .= "data_labels TEXT, ";
                    $sqlQuery .= "del smallint NOT NULL DEFAULT 0, ";
                    $sqlQuery .= "dibuat_oleh character varying(25), ";
                    $sqlQuery .= "tgl_dibuat date, ";
                    $sqlQuery .= "diubah_oleh character varying(25), ";
                    $sqlQuery .= "tgl_diubah date, ";

                    foreach ($datatabel->data_kolom as $dataKolomIndex => $dataKolom) {
                        // TODO make sure whether tipe_kolom will contains native postgres column type or use conversion
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
                        $datatabelTable->DataKolom->updateAll(
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
                    self::log($e->getMessage());
                    return false;
                }
            });

            return $success;
        }
        return false;
    }

    public static function alterTable($datatabelId)
    {
        $datatabelTable = TableRegistry::get('Datatabel');
        $datatabel = $datatabelTable->get($datatabelId, [
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

            $success = $connection->transactional(function ($connection) use($datatabel, $datatabelTable) {
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
                            $datatabelTable->DataKolom->save($dataKolom);

                            // Add index for the new column
                            $indexQuery = "CREATE INDEX {$tableName}_{$dataKolom->data_kolom} ON {$tableName} USING btree ({$dataKolom->data_kolom})";
                            $connection->execute($indexQuery);

                            return true;
                        }
                    }
                } catch (\Exception $e) {
                    self::log($e->getMessage());
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
    public static function tableNameExists($name)
    {
        $datatabelTable = TableRegistry::get('Datatabel');
        $datatabel = $datatabelTable->find('all')->where(['nama_datatabel' => $name])->first();
        if (!empty($datatabel)) {
            return true;
        }
        return false;
    }

    /**
     * Check whether a table has record or not
     * @param $tableName
     * @return bool
     */
    public static function hasRecord($tableName) {
        $connection = ConnectionManager::get('default');

        try {
            $results = $connection
                ->newQuery()
                ->select('*')
                ->from($tableName)
                ->limit(1)
                ->execute()
                ->fetchAll('assoc');

            if (!empty($results)) {
                return true;
            }

        } catch (\Exception $e) {
            self::log("TableService->hasRecord('$tableName') : $e->getMessage()");
        }

        return false;
    }
}