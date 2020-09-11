<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pesan Model
 *
 * @property \App\Model\Table\PenggunasTable|\Cake\ORM\Association\BelongsTo $Penggunas
 *
 * @method \App\Model\Entity\Pesan get($primaryKey, $options = [])
 * @method \App\Model\Entity\Pesan newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Pesan[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Pesan|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Pesan patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Pesan[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Pesan findOrCreate($search, callable $callback = null, $options = [])
 */
class PesanTable extends AppTable
{
    const TIPE_GENERAL = 'general';
    const TIPE_ANNOUNCEMENT = 'announcement';
    const GROUP_PROSES_PENGAJUAN = 'Proses Pengajuan';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('pesan');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('PesanDibaca', [
            'foreignKey' => 'pesan_id',
        ]);

        $this->belongsTo('Pengguna', [
            'foreignKey' => 'pengguna_id',
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('grup_notifikasi');

        $validator
            ->allowEmpty('judul');

        $validator
            ->requirePresence('pesan', 'create')
            ->notEmpty('pesan');

        $validator
            ->allowEmpty('file_lampiran');

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

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['pengguna_id'], 'Pengguna'));

        return $rules;
    }

    public function beforeSave(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        parent::beforeSave($event, $entity, $options);

        if (isset($entity->pesan)) {
            $entity->pesan = strip_tags($entity->pesan, null);
        }

        return;
    }
}
