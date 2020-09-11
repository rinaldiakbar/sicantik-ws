<?php
namespace App\Model\Table;

use App\Model\Entity\PenomoranDetail;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PenomoranDetail Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Penomorans
 * @property \Cake\ORM\Association\BelongsTo $Units
 */
class PenomoranDetailTable extends Table
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

        $this->setTable('penomoran_detail');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Penomoran', [
            'foreignKey' => 'penomoran_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Unit', [
            'foreignKey' => 'unit_id',
            'joinType' => 'INNER'
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
            ->integer('no_terakhir')
            ->allowEmpty('no_terakhir');

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
        $rules->add($rules->existsIn(['penomoran_id'], 'Penomoran'));
        $rules->add($rules->existsIn(['unit_id'], 'Unit'));
        return $rules;
    }
}
