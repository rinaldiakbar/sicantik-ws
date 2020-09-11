<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GatewayUsers Model
 *
 * @property \App\Model\Table\MessagesTable|\Cake\ORM\Association\HasMany $Messages
 *
 * @method \App\Model\Entity\GatewayUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\GatewayUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GatewayUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GatewayUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GatewayUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GatewayUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GatewayUser findOrCreate($search, callable $callback = null, $options = [])
 */
class GatewayUsersTable extends AppTable
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

        $this->setTable('gateway_users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Messages', [
            'foreignKey' => 'gateway_user_id'
        ]);

        $this->belongsTo('Instansi', [
            'className' => 'Instansi',
            'foreignKey' => 'instansi_id'
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

        $validator->add(
            'username',
            ['unique' => [
                'rule' => 'penggunaExists',
                'provider' => 'table',
                'message' => 'sudah dipakai. Mohon ganti username lain']
            ]
        );

        $validator
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->integer('is_active')
            ->requirePresence('is_active', 'create')
            ->notEmpty('is_active');

        $validator
            ->requirePresence('instansi_id', 'create')
            ->notEmpty('instansi_id');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->existsIn(['instansi_id'], 'Instansi'));

        return $rules;
    }

    /**
     * Check if the pengguna already exists
     */
    public function penggunaExists($value, $context)
    {
        $users = null;

        if (isset($context['data']['id'])) {
            $users = $this->find('all')->where(['username ILIKE' => $value, 'id !=' => $context['data']['id']])->first();
        } else {
            $users = $this->find('all')->where(['username ILIKE' => $value])->first();
        }

        if (empty($users)) {
            return true;
        }

        return false;
    }
}
