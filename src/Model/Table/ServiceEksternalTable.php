<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ServiceEksternal Model
 *
 * @method \App\Model\Entity\ServiceEksternal get($primaryKey, $options = [])
 * @method \App\Model\Entity\ServiceEksternal newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ServiceEksternal[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ServiceEksternal|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceEksternal patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceEksternal[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceEksternal findOrCreate($search, callable $callback = null, $options = [])
 */
class ServiceEksternalTable extends AppTable
{
    const BASIC_AUTHENTICATION = 'Basic Authentication';
    const NO_AUTHENTICATION = 'No Authentication';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('service_eksternal');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

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
            ->requirePresence('nama', 'create')
            ->notEmpty('nama');

        $validator
            ->allowEmpty('deskripsi');

        $validator
            ->requirePresence('base_url', 'create')
            ->add('base_url', [
                'isUrl' => [
                    'rule'    => ['url', true], // Strict url check
                    'message' => 'Base URL must be valid URL'
                ]
            ])
            ->notEmpty('base_url');

        $validator
            ->requirePresence('tipe_otentikasi', 'create')
            ->notEmpty('tipe_otentikasi');

        $validator
            ->allowEmpty('username');

        $validator
            ->allowEmpty('password');

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

        return $rules;
    }
}
