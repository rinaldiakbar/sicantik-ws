<?php
namespace App\Model\Table;

use App\Model\Entity\KelompokData;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * KelompokData Model
 *
 * @property \Cake\ORM\Association\BelongsTo $TemplateData
 */
class KelompokDataTable extends AppTable
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

        $this->setTable('kelompok_data');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('TemplateData', [
            'foreignKey' => 'template_data_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('KelompokTabel', [
            'foreignKey' => 'kelompok_data_id'
        ]);

        $this->hasMany('KelompokKolom', [
            'foreignKey' => 'kelompok_data_id'
        ]);

        $this->hasMany('KelompokKondisi', [
            'foreignKey' => 'kelompok_data_id'
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
            ->requirePresence('label_kelompok', 'create')
            ->notEmpty('label_kelompok');

        $validator
            ->requirePresence('jenis_sumber', 'create')
            ->notEmpty('jenis_sumber');

        $validator
            ->allowEmpty('sql');

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
        $rules->add($rules->existsIn(['template_data_id'], 'TemplateData'));
        return $rules;
    }
}
