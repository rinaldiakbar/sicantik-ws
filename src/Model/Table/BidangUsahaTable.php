<?php
namespace App\Model\Table;

use App\Model\Entity\BidangUsaha;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BidangUsaha Model
 *
 * @property \Cake\ORM\Association\HasMany $BidangUsahaPermohonan
 * @property \Cake\ORM\Association\HasMany $JenisUsaha
 * @property \Cake\ORM\Association\HasMany $Perusahaan
 */
class BidangUsahaTable extends AppTable
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

        $this->setTable('bidang_usaha');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        
        $this->hasMany('JenisUsaha', [
            'foreignKey' => 'bidang_usaha_id'
        ]);
        $this->belongsToMany('Perusahaan', [
            'foreignKey' => 'bidang_usaha_id',
            'targetForeignKey' => 'perusahaan_id',
            'joinTable' => 'jenis_usaha_perusahaan'
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
}
