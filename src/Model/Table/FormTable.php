<?php
namespace App\Model\Table;

use App\Model\Entity\Form;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Form Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Units
 * @property \Cake\ORM\Association\HasMany $Canvas
 */
class FormTable extends AppTable
{
    const TIPE_FORM = 'form';
    const TIPE_TABEL = 'tabel';
    const TIPE_TAB = 'tab';
    const TIPE_TABEL_GRID = 'tabel-grid';
    const TIPE_TABEL_STATIK = 'tabel-statik';
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

        $this->setTable('form');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Unit', [
            'foreignKey' => 'unit_id'
        ]);

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id'
        ]);

        $this->belongsTo('ServiceEksternal', [
            'foreignKey' => 'service_eksternal_id',
            'joinType' => 'LEFT'
        ]);

        $this->hasMany('DaftarProses', [
            'foreignKey' => 'form_id'

        ]);

        $this->hasMany('ProsesPermohonan', [
            'foreignKey' => 'form_id'
        ]);

        $this->hasMany('Canvas', [
            'foreignKey' => 'form_id',
            'dependent' => true,
            'cascadeCallbacks' => true
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
            ->notEmpty('nama_form');

        $validator
            ->notEmpty('key_field');

        $validator
            ->allowEmpty('otomatis_update');

        $validator
            ->allowEmpty('service_eksternal_id');

        $validator
            ->allowEmpty('target_simpan');

        $validator
            ->allowEmpty('target_path');

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
        $rules->add($rules->existsIn(['unit_id'], 'Unit'));
        return $rules;
    }
}
