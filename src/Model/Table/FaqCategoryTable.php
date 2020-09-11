<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FaqCategory Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $Unit
 *
 * @method \App\Model\Entity\FaqCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\FaqCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FaqCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FaqCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FaqCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FaqCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FaqCategory findOrCreate($search, callable $callback = null, $options = [])
 */
class FaqCategoryTable extends AppTable
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

        $this->setTable('faq_category');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('Faq', [
            'foreignKey' => 'faq_category_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->hasMany('TopFaq', [
            'foreignKey' => 'faq_category_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
            'className' => 'Faq',
            'conditions' => function (\Cake\Database\Expression\QueryExpression $exp, \Cake\ORM\Query $query) {
                $subquery = $query
                    ->connection()
                    ->newQuery()
                    ->select(['SubTopFaq.id'])
                    ->from(['SubTopFaq' => 'faq'])
                    ->order(['SubTopFaq.no_urut' => 'DESC'])
                    ->limit(3);

                return $exp->add(['TopFaq.id IN' => $subquery]);
            }
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
            ->scalar('nama')
            ->maxLength('nama', 50)
            ->requirePresence('nama', 'create')
            ->notEmpty('nama');

        $validator
            ->scalar('deskripsi')
            ->maxLength('deskripsi', 500)
            ->allowEmpty('deskripsi');

        $validator
            ->integer('no_urut')
            ->allowEmpty('no_urut');

        $validator
            ->integer('is_active')
            ->requirePresence('is_active', 'create')
            ->notEmpty('is_active');

        $validator
            ->scalar('dibuat_oleh')
            ->maxLength('dibuat_oleh', 25)
            ->allowEmpty('dibuat_oleh');

        $validator
            ->dateTime('tgl_dibuat')
            ->allowEmpty('tgl_dibuat');

        $validator
            ->dateTime('tgl_diubah')
            ->allowEmpty('tgl_diubah');

        $validator
            ->scalar('diubah_oleh')
            ->maxLength('diubah_oleh', 25)
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
        $rules->add($rules->existsIn(['instansi_id'], 'Instansi'));

        return $rules;
    }
}
