<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RestUsers Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $Instansis
 *
 * @method \App\Model\Entity\RestUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\RestUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RestUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RestUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RestUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RestUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RestUser findOrCreate($search, callable $callback = null, $options = [])
 */
class RestUsersTable extends AppTable
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

        $this->setTable('rest_users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->integer('is_active')
            ->requirePresence('is_active', 'create')
            ->notEmpty('is_active');

        $validator
            ->allowEmpty('dibuat_oleh');

        $validator
            ->dateTime('tgl_dibuat')
            ->allowEmpty('tgl_dibuat');

        $validator
            ->dateTime('tgl_diubah')
            ->allowEmpty('tgl_diubah');

        $validator
            ->allowEmpty('diubah_oleh');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->existsIn(['instansi_id'], 'Instansi'));

        return $rules;
    }
}
