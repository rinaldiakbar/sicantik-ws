<?php
namespace App\Model\Table;

use App\Model\Entity\Unit;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\ORM\Entity;

/**
 * Unit Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentUnit
 * @property \Cake\ORM\Association\HasMany $Pegawai
 * @property \Cake\ORM\Association\HasMany $Peran
 * @property \Cake\ORM\Association\HasMany $ChildUnit
 * @property \Cake\ORM\Association\BelongsToMany $Pengguna
 */
class UnitTable extends AppTable
{
    const TIPE_INSTANSI = 'I';
    const TIPE_UNIT = 'U';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('unit');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('PenomoranDetail', [
            'foreignKey' => 'unit_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        /*$this->hasMany('Pegawai', [
            'foreignKey' => 'unit_id'
        ]);*/

        $this->hasMany('ChildUnit', [
            'className' => 'Unit',
            'foreignKey' => 'parent_id'
        ]);

        $this->hasMany('PermohonanIzin', [
            'foreignKey' => 'instansi_id',
            'dependent' => true,
            'cascadeCallbacks' => false
        ]);

        $this->belongsTo('ParentUnit', [
            'className' => 'Unit',
            'foreignKey' => 'parent_id'
        ]);

        $this->belongsTo('UnitDatatabel', [
            'className' => 'UnitDatatabel',
            'foreignKey' => 'parent_id'
        ]);

        $this->belongsToMany('Pengguna', [
            'foreignKey' => 'unit_id',
            'targetForeignKey' => 'pengguna_id',
            'joinTable' => 'unit_pengguna'
        ]);

        $this->addBehavior('Tree');

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
            ->requirePresence('nama', 'create')
            ->notEmpty('nama');

        $validator
            ->requirePresence('tipe', 'create')
            ->notEmpty('tipe');

        $validator
            ->allowEmpty('kode_daerah');

        $validator
            ->url('ws_url')
            ->allowEmpty('ws_url');

        $validator
            ->allowEmpty('instansi_code');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentUnit'));
        return $rules;
    }

    public function beforeDelete(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        if ($entity->tipe == 'I' && !is_null($this->instansiSession->id)) {
            $entity->setError('unit', __('Anda tidak berhak menghapus data ini'));
            $event->stopPropagation();
        }
    }
}
