<?php
namespace App\Model\Table;

use App\Model\Entity\Mapper;
use Cake\Core\Exception\Exception;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Mapper Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Keys
 * @property \Cake\ORM\Association\BelongsTo $Datatabels
 */
class MapperTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('mapper');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        /*$this->belongsTo('Keys', [
            'foreignKey' => 'key_id',
            'joinType' => 'INNER'
        ]);*/
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('nama_datatabel', 'create')
            ->notEmpty('nama_datatabel');

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
//        $rules->add($rules->existsIn(['key_id'], 'Keys));
        return $rules;
    }

    /**
     * Create Mapper Record for a Datatable
     * $param $datatableId
     * @param $datatableRecordId
     * @param $keyId
     * @return bool
     */
    public function createMapper($datatabelId, $datatableRecordId, $keyId)
    {
        try {
            $datatabelTable = TableRegistry::get('Datatabel');
            $mapperTable = TableRegistry::get('Mapper');

            $datatabel = $datatabelTable->findById($datatabelId)->select(['id', 'nama_datatabel', 'use_mapper'])->first();

            $mapper = $mapperTable->find()->where(
                ['nama_datatabel' => $datatabel->nama_datatabel, 'datatabel_record_id' => $datatableRecordId, 'key_id' => $keyId]
            )->first();

            // if mapper not exists and datatabel is using mapper
            if (!$mapper && $datatabel->use_mapper == 1) {
                $mapper = $mapperTable->newEntity();
                $mapper->nama_datatabel = $datatabel->nama_datatabel;
                $mapper->datatabel_record_id = $datatableRecordId;
                $mapper->key_id = $keyId;
                if (!$mapperTable->save($mapper)) {
                    throw new \Exception(implode(';', $mapper->errors()));
                    return false;
                }
            }
        } catch (\Exception $e) {
            $this->log('Gagal menyimpan Mapper Datatabel-'.$datatabelId.' Record-'.$datatableRecordId.' Key-'.$keyId.' = '.$e->getMessage(), LogLevel::DEBUG);
            return false;
        }

        return true;
    }
}
