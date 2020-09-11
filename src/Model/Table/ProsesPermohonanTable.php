<?php
namespace App\Model\Table;

use App\Model\Entity\ProsesPermohonan;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProsesPermohonan Model
 *
 * @property \Cake\ORM\Association\BelongsTo $PermohonanIzin
 * @property \Cake\ORM\Association\BelongsTo $Izin
 * @property \Cake\ORM\Association\BelongsTo $Proses
 */
class ProsesPermohonanTable extends AppTable
{
    const TIPE_REPORT = 'report';
    const TIPE_FORM = 'form';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('proses_permohonan');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('PermohonanIzin', [
            'foreignKey' => 'permohonan_izin_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('JenisIzin', [
            'foreignKey' => 'jenis_izin_id',
            'joinType' => 'INNER' // important for getPermohonanToSign
        ]);

        $this->belongsTo('JenisProses', [
            'foreignKey' => 'jenis_proses_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Form', [
            'foreignKey' => 'form_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('TemplateData', [
            'foreignKey' => 'template_data_id'
        ]);

        $this->belongsTo('DaftarProses', [
            'foreignKey' => 'daftar_proses_id'
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
            ->allowEmpty('tautan');

        $validator
            ->allowEmpty('status');

        $validator
            ->notEmpty('tipe');

        $validator
            ->allowEmpty('file_tanda_tangan');

        $validator
            ->allowEmpty('tgl_tanda_tangan');

        $validator
            ->allowEmpty('file_signed_report');

        $validator
            ->allowEmpty('tgl_signed_report');

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
        $rules->add($rules->existsIn(['jenis_izin_id'], 'JenisIzin'));
        $rules->add($rules->existsIn(['form_id'], 'Form'));
        $rules->add($rules->existsIn(['template_data_id'], 'TemplateData'));
        $rules->add($rules->existsIn(['jenis_proses_id'], 'JenisProses'));
        $rules->add($rules->existsIn(['daftar_proses_id'], 'DaftarProses'));
        return $rules;
    }
}
