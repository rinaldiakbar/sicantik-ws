<?php
namespace App\Model\Table;

use App\Model\Entity\JenisUsaha;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JenisUsaha Model
 *
 * @property \Cake\ORM\Association\BelongsTo $BidangUsahas
 * @property \Cake\ORM\Association\HasMany $JenisUsahaPermohonan
 * @property \Cake\ORM\Association\HasMany $Perusahaan
 */
class JenisUsahaTable extends AppTable
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

        $this->setTable('jenis_usaha');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('BidangUsaha', [
            'foreignKey' => 'bidang_usaha_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsToMany('Perusahaan', [
            'foreignKey' => 'jenis_usaha_id',
            'targetForeignKey' => 'perusahaan_id',
            'joinTable' => 'bidang_usaha_perusahaan'
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
            ->requirePresence('kode', 'create')
            ->notEmpty('kode');

        $validator
            ->requirePresence('keterangan', 'create')
            ->notEmpty('keterangan');

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
        $rules->add($rules->existsIn(['bidang_usaha_id'], 'BidangUsaha'));
        return $rules;
    }
}
