<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Entity\CAduan;

/**
 * CAduan Model
 *
 * @property \App\Model\Table\InstansisTable|\Cake\ORM\Association\BelongsTo $Instansis
 * @property \App\Model\Table\CAduanKomentarTable|\Cake\ORM\Association\HasMany $CAduanKomentar
 * @property \App\Model\Table\CAduanLampiranTable|\Cake\ORM\Association\HasMany $CAduanLampiran
 *
 * @method \App\Model\Entity\CAduan get($primaryKey, $options = [])
 * @method \App\Model\Entity\CAduan newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CAduan[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CAduan|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CAduan patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CAduan[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CAduan findOrCreate($search, callable $callback = null, $options = [])
 */
class CAduanTable extends AppTable
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

        $this->setTable('c_aduan');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('CAduanKomentar', [
            'foreignKey' => 'c_aduan_id',
        ]);

        $this->hasMany('CAduanLampiran', [
            'foreignKey' => 'c_aduan_id'
        ]);

        $this->hasMany('Unit', [
            'foreignKey' => 'instansi_id',
            'joinType' => 'LEFT',
            'conditions' => [
                'tipe' => 'U'
            ]
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
            ->scalar('kategori')
            ->maxLength('kategori', 255)
            ->allowEmpty('kategori');

        $validator
            ->scalar('aduan')
            ->maxLength('aduan', 1000)
            ->requirePresence('aduan', 'create')
            ->notEmpty('aduan');

        $validator
            ->scalar('status')
            ->maxLength('status', 25)
            ->allowEmpty('status');

        $validator
            ->scalar('penyelesaian')
            ->maxLength('penyelesaian', 1000)
            ->allowEmpty('penyelesaian');

        $validator
            ->date('tgl_aduan')
            ->allowEmpty('tgl_aduan');

        $validator
            ->date('tgl_penyelesaian')
            ->allowEmpty('tgl_penyelesaian');

        $validator
            ->scalar('penanggung_jawab')
            ->maxLength('penanggung_jawab', 100)
            ->allowEmpty('penanggung_jawab');

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
        return $rules;
    }
}
