<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Jabatan Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Instansis
 * @property \Cake\ORM\Association\HasMany $NotifikasiDetail
 * @property \Cake\ORM\Association\HasMany $PenanggungJawab
 *
 * @method \App\Model\Entity\Jabatan get($primaryKey, $options = [])
 * @method \App\Model\Entity\Jabatan newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Jabatan[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Jabatan|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Jabatan patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Jabatan[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Jabatan findOrCreate($search, callable $callback = null, $options = [])
 */
class JabatanTable extends AppTable
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

        $this->setTable('master_jabatan');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('NotifikasiDetail', [
            'foreignKey' => 'jabatan_id'
        ]);
        $this->hasMany('Pegawai', [
            'foreignKey' => 'jabatan_id'
        ]);
        $this->hasMany('PenanggungJawab', [
            'foreignKey' => 'jabatan_id'
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
            ->requirePresence('jabatan', 'create')
            ->notEmpty('jabatan');

        $validator
            ->allowEmpty('nama_jabatan');

        $validator
            ->notEmpty('instansi_id');

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
        $rules->add($rules->existsIn(['instansi_id'], 'Instansi'));

        return $rules;
    }
}
