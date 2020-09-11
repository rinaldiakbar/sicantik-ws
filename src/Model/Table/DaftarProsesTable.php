<?php
namespace App\Model\Table;

use App\Model\Entity\DaftarProse;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DaftarProses Model
 *
 * @property \Cake\ORM\Association\BelongsTo $AlurProses
 * @property \Cake\ORM\Association\BelongsTo $JenisProses
 */
class DaftarProsesTable extends AppTable
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

        $this->setTable('daftar_proses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasOne('JenisIzinProses', [
            'foreignKey' => 'daftar_proses_id'
        ]);

        $this->hasMany('ProsesPermohonan', [
            'foreignKey' => 'daftar_proses_id'
        ]);

        $this->hasMany('NotifikasiDetail', [
            'foreignKey' => 'daftar_proses_id',
            'joinType' => 'LEFT'
        ]);

        $this->hasMany('ReportComponentDetails', [
            'foreignKey' => 'daftar_proses_id',
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('AlurProses', [
            'foreignKey' => 'alur_proses_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('JenisProses', [
            'foreignKey' => 'jenis_proses_id',
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('TemplateData', [
            'foreignKey' => 'template_data_id',
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('Form', [
            'foreignKey' => 'form_id',
            'joinType' => 'LEFT'
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
            ->integer('no')
            ->allowEmpty('no');

        $validator
            ->requirePresence('nama_proses', 'create')
            ->notEmpty('nama_proses');

        $validator
            ->allowEmpty('tautan');

        $validator
            ->allowEmpty('form_id');

        $validator
            ->allowEmpty('template_data_id');

        $validator
            ->notEmpty('tipe');

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
        $rules->add($rules->existsIn(['alur_proses_id'], 'AlurProses'));
        $rules->add($rules->existsIn(['jenis_proses_id'], 'JenisProses'));
        $rules->add($rules->existsIn(['template_data_id'], 'TemplateData'));
        return $rules;
    }
}
