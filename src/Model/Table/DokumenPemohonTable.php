<?php
namespace App\Model\Table;

use App\Model\Entity\DokumenPemohon;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * DokumenPemohon Model
 *
 * @property \Cake\ORM\Association\BelongsTo $JenisDokumens
 *
 * @method \App\Model\Entity\DokumenPemohon get($primaryKey, $options = [])
 * @method \App\Model\Entity\DokumenPemohon newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DokumenPemohon[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DokumenPemohon|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DokumenPemohon patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DokumenPemohon[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DokumenPemohon findOrCreate($search, callable $callback = null, $options = [])
 */
class DokumenPemohonTable extends AppTable
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

        $this->setTable('dokumen_pemohon');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('JenisDokumen', [
            'foreignKey' => 'jenis_dokumen_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Pemohon', [
            'foreignKey' => 'pemohon_id',
            'joinType' => 'INNER'
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
            ->requirePresence('no_dokumen', 'create')
            ->notEmpty('no_dokumen');

        $validator
            ->requirePresence('lokasi_dokumen', 'create')
            ->notEmpty('lokasi_dokumen');

        $validator
            ->date('awal_berlaku')
            ->allowEmpty('awal_berlaku');

        $validator
            ->date('akhir_berlaku')
            ->allowEmpty('akhir_berlaku');

        $validator->allowEmpty('object_name');

        $validator->allowEmpty('object_id');

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
        $rules->add($rules->existsIn(['jenis_dokumen_id'], 'JenisDokumen'));

        return $rules;
    }

    public function afterSave(\Cake\Event\Event $event, \App\Model\Entity\DokumenPemohon $entity)
    {
        // Update all persyaratan
        $persyaratanTable = TableRegistry::get('Persyaratan');
        $persyaratan = $persyaratanTable->find('all', [
            'contain' => [
                'PermohonanIzin' => [
                    'fields' => ['id'],
                    'conditions' => ['pemohon_id' => $entity->pemohon_id]
                ]
            ],
            'conditions' => [
                'terpenuhi' => 0,
                'jenis_dokumen_id' => $entity->jenis_dokumen_id
            ]
        ]);

        if ($persyaratan->count() > 0) {
            $persyaratanIds = $persyaratan->extract('id')->toArray();
            $persyaratanTable->updateAll([
                'awal_berlaku' => $entity->awal_berlaku,
                'akhir_berlaku' => $entity->akhir_berlaku,
                'no_dokumen' => $entity->no_dokumen,
                'lokasi_dokumen' => $entity->lokasi_dokumen,
                'terpenuhi' => 1
            ], [
                'id IN' => $persyaratanIds
            ]);
        }

        return;
    }
}
