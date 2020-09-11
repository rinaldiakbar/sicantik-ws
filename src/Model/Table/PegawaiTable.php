<?php
namespace App\Model\Table;

use App\Model\Entity\Pegawai;
use App\Model\Entity\Pengguna;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\ORM\Entity;
use ArrayObject;

/**
 * Pegawai Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Unit
 * @property \Cake\ORM\Association\HasMany $Pengguna
 */
class PegawaiTable extends AppTable
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

        $this->setTable('pegawai');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Pegawai', [
            'foreignKey' => 'pegawai_id'
        ]);

        $this->belongsTo('Unit', [
            'foreignKey' => 'unit_id'
        ]);

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id'
        ]);

        $this->belongsTo('Jabatan', [
            'foreignKey' => 'jabatan_id'
        ]);

        $this->hasMany('Pengguna', [
            'foreignKey' => 'pegawai_id'
        ]);

        $this->hasMany('PesanDibaca', [
            'foreignKey' => 'pegawai_id',
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
            ->requirePresence('nama', 'create')
            ->notEmpty('nama');

        $validator
            ->notEmpty('nomor_induk')
            ->alphaNumeric('nomor_induk')
            ->maxLength('nomor_induk', 25);

        $validator
            ->allowEmpty('posisi');

        $validator
            ->allowEmpty('no_hp');

        $validator
            ->email('email')
            ->allowEmpty('email');

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
        $rules->add($rules->isUnique(['email']));
//        $rules->add($rules->existsIn(['unit_id'], 'Unit'));
        $rules->add($rules->existsIn(['instansi_id'], 'Instansi'));
        $rules->add($rules->existsIn(['jabatan_id'], 'Jabatan'));

        return $rules;
    }

    public function beforeDelete(Event $event, Entity $entity, ArrayObject $options) {
        parent::beforeDelete($event, $entity, $options);

        // cek pegawai sebagai pengguna
        $pengguna = $this->Pengguna->find('all', [
            'conditions' => [
                'pegawai_id' => $entity->id
            ]
        ]);

        if ($pengguna->count() > 0) {
            $entity->setError('pengguna', __('data pegawai yang anda hapus memiliki akses kedalam sistem, silakan lepaskan akses kedalam sistem terlebih dahulu.'));
            $event->stopPropagation();
        }
    }
}
