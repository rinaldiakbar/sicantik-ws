<?php
namespace App\Model\Table;

use App\Model\Entity\Izin;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Izin Model
 *
 * @property \Cake\ORM\Association\BelongsTo $JenisIzin
 * @property \Cake\ORM\Association\BelongsTo $Unit
 * @property \Cake\ORM\Association\BelongsTo $Pemohon
 * @property \Cake\ORM\Association\BelongsTo $Perusahaan
 * @property \Cake\ORM\Association\HasMany $PermohonanIzin
 * @property \Cake\ORM\Association\HasMany $ProsesPermohonan
 */
class IzinTable extends AppTable
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
        $this->strictDelete = true;

        $this->setTable('izin');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('JenisIzin', [
            'foreignKey' => 'jenis_izin_id'
        ]);

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id'
        ]);

        $this->belongsTo('Pemohon', [
            'foreignKey' => 'pemohon_id'
        ]);
        
        $this->belongsTo('Perusahaan', [
            'foreignKey' => 'perusahaan_id'
        ]);

        $this->belongsTo('PermohonanIzin', [
            'foreignKey' => 'permohonan_izin_id'
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
            ->allowEmpty('no_izin');

        $validator
            ->allowEmpty('no_izin_sebelumnya');

        $validator
            ->allowEmpty('keterangan');

        $validator
            ->date('mulai_berlaku')
            ->allowEmpty('mulai_berlaku');

        $validator
            ->date('akhir_berlaku')
            ->allowEmpty('akhir_berlaku');

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
        $rules->add($rules->existsIn(['jenis_izin_id'], 'JenisIzin'));
        $rules->add($rules->existsIn(['instansi_id'], 'Instansi'));
        $rules->add($rules->existsIn(['pemohon_id'], 'Pemohon'));
        $rules->add($rules->existsIn(['perusahaan_id'], 'Perusahaan'));
        return $rules;
    }
}
