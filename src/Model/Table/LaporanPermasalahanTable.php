<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\ORM\Entity;
use ArrayObject;

/**
 * LaporanPermasalahan Model
 *
 * @property \App\Model\Table\InstansisTable|\Cake\ORM\Association\BelongsTo $Instansis
 *
 * @method \App\Model\Entity\LaporanPermasalahan get($primaryKey, $options = [])
 * @method \App\Model\Entity\LaporanPermasalahan newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LaporanPermasalahan[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LaporanPermasalahan|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LaporanPermasalahan patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LaporanPermasalahan[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LaporanPermasalahan findOrCreate($search, callable $callback = null, $options = [])
 */
class LaporanPermasalahanTable extends AppTable
{
    CONST STATUS_BARU = 'baru';
    CONST STATUS_SELESAI = 'selesai';
    CONST SOURCE_SMS = 'sms';
    CONST SOURCE_MOBILE = 'mobile';

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

        $this->setTable('laporan_permasalahan');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id'
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
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('data_labels');

        $validator
            ->allowEmpty('del');

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

        $validator
            ->allowEmpty('kategori');

        $validator
            ->requirePresence('permasalahan', 'create')
            ->notEmpty('permasalahan');

        $validator
            ->allowEmpty('tanggapan');

        $validator
            ->notEmpty('status');

        $validator
            ->notEmpty('source');

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
        $rules->add($rules->existsIn(['instansi_id'], 'Instansi'));

        return $rules;
    }

    public function beforeSave(Event $event, Entity $entity, ArrayObject $options)
    {
        parent::beforeSave($event, $entity, $options);

        if (!$entity->status) {
            $entity->status = self::STATUS_BARU;
        }

        return;
    }
}
