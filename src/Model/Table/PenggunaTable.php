<?php
namespace App\Model\Table;

use App\Model\Entity\Pengguna;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Pengguna Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Perans
 * @property \Cake\ORM\Association\BelongsTo $Pegawais
 * @property \Cake\ORM\Association\BelongsToMany $JenisIzin
 * @property \Cake\ORM\Association\BelongsToMany $Unit
 */
class PenggunaTable extends AppTable
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

        $this->setTable('pengguna');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Pesan', [
            'foreignKey' => 'pengguna_id'
        ]);

        $this->belongsTo('Peran', [
            'foreignKey' => 'peran_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Pegawai', [
            'foreignKey' => 'pegawai_id'
        ]);
        
        $this->belongsToMany('JenisIzin', [
            'foreignKey' => 'pengguna_id',
            'targetForeignKey' => 'jenis_izin_id',
            'joinTable' => 'jenis_izin_pengguna'
        ]);
        
        $this->belongsToMany('JenisProses', [
            'foreignKey' => 'pengguna_id',
            'targetForeignKey' => 'jenis_proses_id',
            'joinTable' => 'jenis_proses_pengguna'
        ]);
        
        $this->belongsToMany('Unit', [
            'foreignKey' => 'pengguna_id',
            'targetForeignKey' => 'unit_id',
            'joinTable' => 'unit_pengguna'
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
            ->requirePresence('email', 'create')
            ->email('email')
            ->notEmpty('email');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->allowEmpty('pegawai_id');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->existsIn(['peran_id'], 'Peran'));
        $rules->add($rules->existsIn(['pegawai_id'], 'Pegawai'));
        return $rules;
    }

    /**
     * Check if the username already exists
     * @param $name
     * @return bool
     */
    public function usernameExists($username, $currentId = null)
    {
        $users = null;

        if ($currentId) {
            $users = $this->find('all')->where(['username' => $username, 'id !=' => $currentId])->first();
        } else {
            $users = $this->find('all')->where(['username' => $username])->first();
        }

        if (!empty($users)) {
            return true;
        }
        return false;
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

    /**
     * Check if the pengguna already exists
     */
    public static function isPenggunaExists($value, $context)
    {
        $users = null;
        $penggunaTable = TableRegistry::get('Pengguna');

        if (isset($context['data']['id'])) {
            $users = $penggunaTable->find('all')->where(['username ILIKE' => $value, 'id !=' => $context['data']['id']])->first();
        } else {
            $users = $penggunaTable->find('all')->where(['username ILIKE' => $value])->first();
        }

        if (empty($users)) {
            return true;
        }

        return false;
    }
}
