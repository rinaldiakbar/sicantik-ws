<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * QueueJobs Model
 *
 * @method \App\Model\Entity\QueueJob get($primaryKey, $options = [])
 * @method \App\Model\Entity\QueueJob newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\QueueJob[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QueueJob|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\QueueJob patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QueueJob[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\QueueJob findOrCreate($search, callable $callback = null, $options = [])
 */
class QueueJobsTable extends AppTable
{
    CONST STATUS_WAITING = 'WAITING';
    CONST STATUS_IN_QUEUE = 'IN QUEUE';
    CONST STATUS_PROCESSING = 'PROCESSING';
    CONST STATUS_FAILED = 'FAILED';
    CONST STATUS_FINISHED = 'FINISHED';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('queue_jobs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

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
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->requirePresence('body', 'create')
            ->notEmpty('body');

        $validator
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->integer('priority')
            ->requirePresence('priority', 'create')
            ->notEmpty('priority');

        $validator
            ->integer('delay_time')
            ->requirePresence('delay_time', 'create')
            ->notEmpty('delay_time');

        $validator
            ->dateTime('execute_time')
            ->allowEmpty('execute_time');

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
}
