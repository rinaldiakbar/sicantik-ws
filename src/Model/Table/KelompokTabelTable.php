<?php
namespace App\Model\Table;

use App\Model\Entity\KelompokTabel;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;

/**
 * KelompokTabel Model
 *
 * @property \Cake\ORM\Association\BelongsTo $KelompokData
 */
class KelompokTabelTable extends AppTable
{

    const JOIN_MAIN = 'MAIN';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('kelompok_tabel');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('KelompokData', [
            'foreignKey' => 'kelompok_data_id',
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
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('nama_tabel', 'create')
            ->notEmpty('nama_tabel');

        $validator
//            ->requirePresence('tipe_join', 'create')
            ->notEmpty('tipe_join');

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
        $rules->add($rules->existsIn(['kelompok_data_id'], 'KelompokData'));
        return $rules;
    }

    public function beforeRules(Event $event, KelompokTabel $entity)
    {
        if ($entity->isNew()) {
            if (!$entity->tipe_join) {
                $entity->tipe_join = self::JOIN_MAIN;
            }
        }

        return;
    }
}
