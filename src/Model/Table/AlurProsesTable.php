<?php
namespace App\Model\Table;

use App\Model\Entity\AlurProse;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AlurProses Model
 *
 */
class AlurProsesTable extends AppTable
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
        $this->strictUpdate = true;

        $this->setTable('alur_proses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('DaftarProses', [
            'foreignKey' => 'alur_proses_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id'
        ]);

        /*$this->belongsToMany('JenisProses', [
            'foreignKey' => 'alur_proses_id',
            'targetForeignKey' => 'jenis_proses_id',
            'joinTable' => 'daftar_proses',
            'through' => 'DaftarProses',
        ]);

        $this->belongsToMany('Form', [
            'foreignKey' => 'alur_proses_id',
            'targetForeignKey' => 'form_id',
            'joinTable' => 'daftar_proses',
            'through' => 'DaftarProses',
        ]);*/

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
            ->requirePresence('keterangan', 'create')
            ->notEmpty('keterangan');

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
