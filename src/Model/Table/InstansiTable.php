<?php
namespace App\Model\Table;

use App\Model\Entity\Instansi;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Instansi Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentInstansi
 * @property \Cake\ORM\Association\HasMany $Pegawai
 * @property \Cake\ORM\Association\HasMany $Peran
 * @property \Cake\ORM\Association\HasMany $ChildInstansi
 * @property \Cake\ORM\Association\BelongsToMany $Pengguna
 */
class InstansiTable extends AppTable
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

        $this->setTable('unit');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        /*$this->belongsTo('ParentInstansi', [
            'className' => 'Instansi',
            'foreignKey' => 'parent_id'
        ]);*/
        $this->hasMany('Peran', [
            'foreignKey' => 'instansi_id'
        ]);

        $this->hasMany('Pegawai', [
            'foreignKey' => 'instansi_id'
        ]);

        $this->hasMany('Kalender', [
            'foreignKey' => 'instansi_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->hasMany('Izin', [
            'foreignKey' => 'instansi_id',
            'dependent' => true,
            'cascadeCallbacks' => false
        ]);

        $this->hasOne('GatewayUser', [
            'foreignKey' => 'instansi_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->hasMany('RestUser', [
            'foreignKey' => 'instansi_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->hasMany('PermohonanIzin', [
            'foreignKey' => 'instansi_id',
            'dependent' => true,
            'cascadeCallbacks' => false
        ]);

        $this->hasMany('TemplateData', [
            'foreignKey' => 'instansi_id',
            'dependent' => true,
            'cascadeCallbacks' => false
        ]);

        $this->hasMany('FaqCategory', [
            'foreignKey' => 'instansi_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->hasMany('Faq', [
            'foreignKey' => 'instansi_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);
        /*$this->hasMany('Peran', [
            'foreignKey' => 'instansi_id'
        ])*/;
        /*$this->hasMany('ChildInstansi', [
            'className' => 'Instansi',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsToMany('Pengguna', [
            'foreignKey' => 'instansi_id',
            'targetForeignKey' => 'pengguna_id',
            'joinTable' => 'instansi_pengguna'
        ])*/;
        
        $this->addBehavior('Tree');
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
        $rules->add($rules->existsIn(['parent_id'], 'ParentInstansi'));
        return $rules;
    }
}
