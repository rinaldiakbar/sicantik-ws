<?php
namespace App\Model\Table;

use App\Model\Entity\JenisProyek;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JenisProyek Model
 *
 * @property \Cake\ORM\Association\HasMany $PermohonanIzin
 */
class JenisProyekTable extends Table
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

        $this->setTable('jenis_proyek');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('PermohonanIzin', [
            'foreignKey' => 'jenis_proyek_id',
            'dependent' => false,
            'cascadeCallbacks' => false
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
            ->requirePresence('keterangan', 'create')
            ->notEmpty('keterangan');

        $validator
            ->allowEmpty('dibuat_oleh');

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
}
