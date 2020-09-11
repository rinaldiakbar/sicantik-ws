<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PageContent Model
 *
 * @property \App\Model\Table\InstansisTable|\Cake\ORM\Association\BelongsTo $Instansis
 * @property \App\Model\Table\PagesTable|\Cake\ORM\Association\BelongsTo $Pages
 *
 * @method \App\Model\Entity\PageContent get($primaryKey, $options = [])
 * @method \App\Model\Entity\PageContent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PageContent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PageContent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PageContent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PageContent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PageContent findOrCreate($search, callable $callback = null, $options = [])
 */
class PageContentTable extends AppTable
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

        $this->setTable('page_content');
        $this->setDisplayField('title');
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
            ->requirePresence('posisi', 'create')
            ->notEmpty('posisi');

        $validator
            ->scalar('webservice')
            ->maxLength('webservice', 1000)
            ->requirePresence('webservice', 'create')
            ->notEmpty('webservice');

        $validator
            ->scalar('type_chart')
            ->maxLength('type_chart', 25)
            ->requirePresence('type_chart', 'create')
            ->notEmpty('type_chart');

        $validator
            ->requirePresence('tab_idx', 'create')
            ->notEmpty('tab_idx');

        $validator
            ->requirePresence('height', 'create')
            ->notEmpty('height');

        $validator
            ->requirePresence('width', 'create')
            ->notEmpty('width');

        $validator
            ->scalar('title')
            ->maxLength('title', 25)
            ->requirePresence('title', 'create')
            ->notEmpty('title');


        $validator
            ->scalar('data_labels')
            ->notEmpty('data_labels');

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
