<?php
namespace App\Model\Table;

use App\Model\Entity\DataKolom;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DataKolom Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Datatabel
 */
class DataKolomTable extends AppTable
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

        $this->setTable('data_kolom');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Datatabel', [
            'foreignKey' => 'datatabel_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('CanvasElement', [
            'foreignKey' => 'data_kolom_id'
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
            ->requirePresence('data_kolom', 'create')
            ->notEmpty('data_kolom');

        $validator
            ->integer('field_dibuat')
            ->notEmpty('field_dibuat');

        $validator
            ->requirePresence('label', 'create')
            ->notEmpty('label');

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
        $rules->add($rules->existsIn(['datatabel_id'], 'Datatabel'));
        return $rules;
    }
}
