<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PenanggungJawabPeran Model
 *
 * @property \App\Model\Table\PeransTable|\Cake\ORM\Association\BelongsTo $Perans
 * @property \App\Model\Table\InstansisTable|\Cake\ORM\Association\BelongsTo $Instansis
 *
 * @method \App\Model\Entity\PenanggungJawabPeran get($primaryKey, $options = [])
 * @method \App\Model\Entity\PenanggungJawabPeran newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PenanggungJawabPeran[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PenanggungJawabPeran|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PenanggungJawabPeran patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PenanggungJawabPeran[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PenanggungJawabPeran findOrCreate($search, callable $callback = null, $options = [])
 */
class PenanggungJawabPeranTable extends Table
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

        $this->setTable('penanggung_jawab_peran');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Peran', [
            'foreignKey' => 'peran_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id',
            'joinType' => 'INNER'
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('data_labels')
            ->allowEmpty('data_labels');

        $validator
            ->integer('del')
            ->requirePresence('del', 'create')
            ->notEmpty('del');

        $validator
            ->scalar('dibuat_oleh')
            ->maxLength('dibuat_oleh', 25)
            ->allowEmpty('dibuat_oleh');

        $validator
            ->date('tgl_dibuat')
            ->allowEmpty('tgl_dibuat');

        $validator
            ->scalar('diubah_oleh')
            ->maxLength('diubah_oleh', 25)
            ->allowEmpty('diubah_oleh');

        $validator
            ->date('tgl_diubah')
            ->allowEmpty('tgl_diubah');

        $validator
            ->boolean('reviewer')
            ->notEmpty('del');

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
        $rules->add($rules->existsIn(['peran_id'], 'Peran'));
        $rules->add($rules->existsIn(['instansi_id'], 'Instansi'));

        return $rules;
    }
}
