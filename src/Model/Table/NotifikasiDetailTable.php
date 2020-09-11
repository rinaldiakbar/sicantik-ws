<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NotifikasiDetail Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Notifikasis
 * @property \Cake\ORM\Association\BelongsTo $DaftarProses
 * @property \Cake\ORM\Association\BelongsTo $Jabatans
 *
 * @method \App\Model\Entity\NotifikasiDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\NotifikasiDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\NotifikasiDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NotifikasiDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NotifikasiDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\NotifikasiDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\NotifikasiDetail findOrCreate($search, callable $callback = null, $options = [])
 */
class NotifikasiDetailTable extends Table
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

        $this->setTable('notifikasi_detail');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Notifikasi', [
            'foreignKey' => 'notifikasi_id',
            'joinType' => 'INNER'
        ]);
        
        $this->belongsTo('DaftarProses', [
            'foreignKey' => 'daftar_proses_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Jabatan', [
            'foreignKey' => 'jabatan_id',
            'joinType' => 'INNER'
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
            ->requirePresence('tipe', 'create')
            ->notEmpty('tipe');

        $validator
            ->allowEmpty('format_pesan');

        $validator
            ->requirePresence('tipe_penerima', 'create')
            ->notEmpty('tipe_penerima');

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
        $rules->add($rules->existsIn(['notifikasi_id'], 'Notifikasi'));
        $rules->add($rules->existsIn(['daftar_proses_id'], 'DaftarProses'));
        $rules->add($rules->existsIn(['jabatan_id'], 'Jabatan'));

        return $rules;
    }
}
