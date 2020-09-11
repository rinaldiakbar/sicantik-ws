<?php
namespace App\Model\Table;

use App\Model\Entity\Persyaratan;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Persyaratan Model
 *
 * @property \Cake\ORM\Association\BelongsTo $PermohonanIzin
 * @property \Cake\ORM\Association\BelongsTo $Persyaratan
// * @property \Cake\ORM\Association\HasMany $Persyaratan
 */
class PersyaratanTable extends AppTable
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

        $this->setTable('persyaratan');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Persyaratan', [
            'foreignKey' => 'persyaratan_id'
        ]);

        $this->belongsTo('PermohonanIzin', [
            'foreignKey' => 'permohonan_izin_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('JenisDokumen', [
            'foreignKey' => 'jenis_dokumen_id',
            'joinType' => 'INNER'
        ]);

        /*$this->hasMany('Persyaratan', [
            'foreignKey' => 'persyaratan_id'
        ]);*/

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

//        $validator
//            ->allowEmpty('keterangan');

        $validator
            ->requirePresence('jenis_dokumen_id', 'create')
            ->notEmpty('jenis_dokumen_id');

        $validator
            ->allowEmpty('lokasi_dokumen');

        $validator
            ->date('awal_berlaku')
            ->allowEmpty('awal_berlaku');

        $validator
            ->date('akhir_berlaku')
            ->allowEmpty('akhir_berlaku');

        $validator
            ->allowEmpty('no_dokumen');

        $validator
            ->integer('terpenuhi')
            ->allowEmpty('terpenuhi');

        $validator
            ->integer('wajib')
            ->allowEmpty('wajib');

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
        $rules->add($rules->existsIn(['permohonan_izin_id'], 'PermohonanIzin'));
        $rules->add($rules->existsIn(['persyaratan_id'], 'Persyaratan'));
        return $rules;
    }
}
