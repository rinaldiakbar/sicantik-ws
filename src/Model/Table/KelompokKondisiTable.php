<?php
namespace App\Model\Table;

use App\Model\Entity\KelompokKondisi;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * KelompokKondisi Model
 *
 * @property \Cake\ORM\Association\BelongsTo $KelompokData
 */
class KelompokKondisiTable extends AppTable
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

        $this->setTable('kelompok_kondisi');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('KelompokData', [
            'foreignKey' => 'kelompok_data_id',
            'joinType' => 'INNER'
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
            ->allowEmpty('nama_tabel_utama');

        $validator
            ->requirePresence('nama_tabel_1', 'create')
            ->notEmpty('nama_tabel_1');

        $validator
            ->requirePresence('nama_kolom_1', 'create')
            ->notEmpty('nama_kolom_1');

        $validator
            ->requirePresence('tipe_kondisi', 'create')
            ->notEmpty('tipe_kondisi');

        /*$validator
            ->requirePresence('nama_tabel_2', 'create')
            ->notEmpty('nama_tabel_2')*/;

        $validator
            ->requirePresence('nama_kolom_2', 'create')
            ->notEmpty('nama_kolom_2');

        $validator
            ->requirePresence('tipe_relasi', 'create')
            ->notEmpty('tipe_relasi');

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
        $rules->add($rules->existsIn(['kelompok_data_id'], 'KelompokData'));
        return $rules;
    }
}
