<?php
namespace App\Model\Table;

use App\Model\Entity\JenisPengajuan;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JenisPengajuan Model
 *
 * @property \Cake\ORM\Association\BelongsTo $JenisIzin
 * @property \Cake\ORM\Association\BelongsTo $AlurProses
 */
class JenisPengajuanTable extends AppTable
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

        $this->setTable('jenis_pengajuan');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('JenisIzin', [
            'foreignKey' => 'jenis_izin_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('AlurProses', [
            'foreignKey' => 'alur_proses_id',
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('Penomoran', [
            'foreignKey' => 'penomoran_id',
            'joinType' => 'LEFT'
        ]);

        $this->hasMany('JenisIzinProses', [
            'foreignKey' => 'jenis_pengajuan_id'
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
            ->requirePresence('jenis_pengajuan', 'create')
            ->notEmpty('jenis_pengajuan');

        $validator
            ->integer('lama_proses')
            ->allowEmpty('lama_proses');

        $validator
            ->integer('masa_berlaku_izin')
            ->allowEmpty('masa_berlaku_izin');

        $validator
            ->allowEmpty('satuan_masa_berlaku');

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
        $rules->add($rules->existsIn(['jenis_izin_id'], 'JenisIzin'));
        $rules->add($rules->existsIn(['alur_proses_id'], 'AlurProses'));
        return $rules;
    }
}
