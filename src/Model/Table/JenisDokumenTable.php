<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JenisDokumen Model
 *
 * @method \App\Model\Entity\JenisDokumen get($primaryKey, $options = [])
 * @method \App\Model\Entity\JenisDokumen newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\JenisDokumen[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JenisDokumen|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JenisDokumen patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JenisDokumen[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\JenisDokumen findOrCreate($search, callable $callback = null, $options = [])
 */
class JenisDokumenTable extends AppTable
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

        $this->setTable('jenis_dokumen');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('DokumenPendukung', [
            'foreignKey' => 'jenis_dokumen_id'
        ]);

        $this->hasMany('DokumenPemohon', [
            'foreignKey' => 'jenis_dokumen_id'
        ]);

        $this->hasMany('Persyaratan', [
            'foreignKey' => 'jenis_dokumen_id'
        ]);

        $this->hasMany('JenisIzin', [
            'foreignKey' => 'jenis_dokumen_id',
            'dependent' => false,
            'cascadeCallbacks' => false
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'tgl_pengajuan' => 'new',
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
            ->requirePresence('kode', 'create')
            ->notEmpty('kode');

        $validator
            ->requirePresence('deskripsi', 'create')
            ->notEmpty('deskripsi');

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

    public function beforeSave(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        parent::beforeSave($event, $entity, $options);

        if (isset($entity->deskripsi)) {
            $entity->deskripsi = strip_tags($entity->deskripsi, null);
        }

        return;
    }
}
