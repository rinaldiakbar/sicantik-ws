<?php
namespace App\Model\Table;

use App\Model\Entity\JenisProse;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JenisProses Model
 *
 */
class JenisProsesTable extends AppTable
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

        $this->setTable('jenis_proses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        /*$this->belongsToMany('AlurProses', [
            'foreignKey' => 'jenis_proses_id',
            'targetForeignKey' => 'alur_proses_id',
            'joinTable' => 'daftar_proses',
            'through' => 'DaftarProses',
        ]);*/
        $this->hasMany('DaftarProses', [
            'foreignKey' => 'jenis_proses_id'
        ]);
        $this->hasMany('ProsesPermohonan', [
            'foreignKey' => 'jenis_proses_id'
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
            ->requirePresence('kode', 'create')
            ->notEmpty('kode');

        $validator
            ->requirePresence('nama_proses', 'create')
            ->notEmpty('nama_proses');

        $validator
            ->allowEmpty('tautan');

        $validator
            ->allowEmpty('is_drop')
            ->boolean('is_drop');

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
}
