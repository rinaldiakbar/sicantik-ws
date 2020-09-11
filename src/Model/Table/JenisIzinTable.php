<?php
namespace App\Model\Table;

use App\Model\Entity\JenisIzin;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JenisIzin Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Unit
 * @property \Cake\ORM\Association\HasMany $DokumenPendukung
 * @property \Cake\ORM\Association\HasMany $IzinParalel
 * @property \Cake\ORM\Association\HasMany $JenisPengajuan
 * @property \Cake\ORM\Association\HasMany $UnitTerkait
 * @property \Cake\ORM\Association\BelongsToMany $Pengguna
 */
class JenisIzinTable extends AppTable
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
        $this->strictUpdate = true;
        $this->strictDelete = true;

        $this->setTable('jenis_izin');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Unit', [
            'foreignKey' => 'unit_id'
        ]);

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id'
        ]);

        $this->belongsTo('JenisDokumen', [
            'foreignKey' => 'jenis_dokumen_id',
            'joinType' => 'LEFT',
        ]);

        $this->hasMany('DokumenPendukung', [
            'foreignKey' => 'jenis_izin_id',
        ]);

        $this->hasMany('IzinParalel', [
            'foreignKey' => 'jenis_izin_id'
        ]);

        $this->hasMany('JenisPengajuan', [
            'foreignKey' => 'jenis_izin_id'
        ]);

        $this->hasMany('UnitTerkait', [
            'foreignKey' => 'jenis_izin_id'
        ]);

        $this->hasMany('PermohonanIzin', [
            'foreignKey' => 'jenis_izin_id'
        ]);

        $this->hasMany('ProsesPermohonan', [
            'foreignKey' => 'jenis_izin_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->hasMany('TarifItem', [
            'foreignKey' => 'jenis_izin_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->hasOne('Notifikasi', [
            'foreignKey' => 'jenis_izin_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->hasOne('ReportComponents', [
            'foreignKey' => 'jenis_izin_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->hasOne('FormulaRetribusi', [
            'foreignKey' => 'jenis_izin_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->belongsToMany('Pengguna', [
            'foreignKey' => 'jenis_izin_id',
            'targetForeignKey' => 'pengguna_id',
            'joinTable' => 'jenis_izin_pengguna'
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
            ->requirePresence('jenis_izin', 'create')
            ->notEmpty('jenis_izin');

        // $validator
            // ->requirePresence('jenis_dokumen_id', 'create')
            // ->notEmpty('jenis_dokumen_id');
        $validator->allowEmpty('jenis_dokumen_id');

        $validator
            ->allowEmpty('short_desc');

        $validator
            ->allowEmpty('dibuat_oleh');

        $validator
            ->date('tgl_dibuat')
            ->allowEmpty('tgl_dibuat');

        $validator
            ->allowEmpty('diubah_oleh');

        $validator->allowEmpty('unit_id');

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
        $rules->add($rules->existsIn(['unit_id'], 'Unit'));
        return $rules;
    }
}
