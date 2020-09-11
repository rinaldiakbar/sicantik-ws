<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Entity\CAduanLampiran;

/**
 * CAduanLampiran Model
 *
 * @property \App\Model\Table\CAduansTable|\Cake\ORM\Association\BelongsTo $CAduans
 * @property \App\Model\Table\InstansisTable|\Cake\ORM\Association\BelongsTo $Instansis
 *
 * @method \App\Model\Entity\CAduanLampiran get($primaryKey, $options = [])
 * @method \App\Model\Entity\CAduanLampiran newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CAduanLampiran[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CAduanLampiran|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CAduanLampiran patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CAduanLampiran[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CAduanLampiran findOrCreate($search, callable $callback = null, $options = [])
 */
class CAduanLampiranTable extends AppTable
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

        $this->setTable('c_aduan_lampiran');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('CAduan', [
            'foreignKey' => 'c_aduan_id',
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
            ->scalar('file_lampiran')
            ->maxLength('file_lampiran', 255)
            ->allowEmpty('file_lampiran');

        $validator
            ->scalar('keterangan')
            ->maxLength('keterangan', 1000)
            ->requirePresence('keterangan', 'create')
            ->notEmpty('keterangan');

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
        $rules->add($rules->existsIn(['c_aduan_id'], 'CAduan'));
        return $rules;
    }
}
