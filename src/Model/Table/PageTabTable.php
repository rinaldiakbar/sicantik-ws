<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PageTab Model
 *
 * @property \App\Model\Table\InstansisTable|\Cake\ORM\Association\BelongsTo $Instansis
 * @property \App\Model\Table\PagesTable|\Cake\ORM\Association\BelongsTo $Pages
 *
 * @method \App\Model\Entity\PageTab get($primaryKey, $options = [])
 * @method \App\Model\Entity\PageTab newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PageTab[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PageTab|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PageTab patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PageTab[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PageTab findOrCreate($search, callable $callback = null, $options = [])
 */
class PageTabTable extends AppTable
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

        $this->setTable('page_tab');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Page', [
            'foreignKey' => 'page_id',
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
            ->scalar('label')
            ->maxLength('label', 25)
            ->requirePresence('label', 'create')
            ->notEmpty('label');

        $validator
            ->requirePresence('tab_idx', 'create')
            ->notEmpty('tab_idx');

        $validator
            ->scalar('data_labels')
            ->notEmpty('data_labels');

        $validator
            ->integer('del')
            ->notEmpty('del');

        $validator
            ->integer('del')
            ->notEmpty('del');

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
        $rules->add($rules->existsIn(['page_id'], 'Page'));

        return $rules;
    }
}
