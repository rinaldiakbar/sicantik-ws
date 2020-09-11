<?php
namespace App\Model\Table;

use App\Model\Entity\JenisIzinProses;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JenisIzinProses Model
 *
 * @property \Cake\ORM\Association\BelongsTo $JenisIzin
 * @property \Cake\ORM\Association\BelongsTo $DaftarProses
 * @property \Cake\ORM\Association\BelongsTo $Form
 * @property \Cake\ORM\Association\BelongsTo $TemplateData
 */
class JenisIzinProsesTable extends AppTable
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

        $this->setTable('jenis_izin_proses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('JenisPengajuan', [
            'foreignKey' => 'jenis_pengajuan_id'
        ]);
        $this->belongsTo('DaftarProses', [
            'foreignKey' => 'daftar_proses_id'
        ]);
        $this->belongsTo('Form', [
            'foreignKey' => 'form_id'
        ]);
        $this->belongsTo('TemplateData', [
            'foreignKey' => 'template_data_id'
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
            ->allowEmpty('tautan');

        $validator
            ->allowEmpty('nama_proses');

        $validator
            ->allowEmpty('dibuat_oleh');

        $validator
            ->allowEmpty('form_id');

        $validator
            ->allowEmpty('template_data_id');

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
        $rules->add($rules->existsIn(['jenis_pengajuan_id'], 'JenisPengajuan'));
        $rules->add($rules->existsIn(['daftar_proses_id'], 'DaftarProses'));
        $rules->add($rules->existsIn(['template_data_id'], 'TemplateData'));
        return $rules;
    }
}
