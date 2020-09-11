<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Notifikasi Model
 *
 * @property \Cake\ORM\Association\BelongsTo $JenisIzins
 * @property \Cake\ORM\Association\HasMany $NotifikasiDetail
 *
 * @method \App\Model\Entity\Notifikasi get($primaryKey, $options = [])
 * @method \App\Model\Entity\Notifikasi newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Notifikasi[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Notifikasi|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Notifikasi patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Notifikasi[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Notifikasi findOrCreate($search, callable $callback = null, $options = [])
 */
class NotifikasiTable extends Table
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

        $this->setTable('notifikasi');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('JenisIzin', [
            'foreignKey' => 'jenis_izin_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('NotifikasiDetail', [
            'foreignKey' => 'notifikasi_id'
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'tgl_pengajuan' => 'new',
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
        $rules->add($rules->existsIn(['jenis_izin_id'], 'JenisIzin'));

        return $rules;
    }
}
