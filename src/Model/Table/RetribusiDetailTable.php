<?php
namespace App\Model\Table;

use App\Model\Entity\RetribusiDetail;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RetribusiDetail Model
 *
 * @property \Cake\ORM\Association\BelongsTo $PermohonanIzins
 */
class RetribusiDetailTable extends AppTable
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

        $this->setTable('retribusi_detail');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('PermohonanIzin', [
            'foreignKey' => 'permohonan_izin_id',
            'joinType' => 'INNER'
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
            ->requirePresence('kode_item', 'create')
            ->notEmpty('kode_item');

        $validator
            ->requirePresence('nama_item', 'create')
            ->notEmpty('nama_item');

        $validator
            ->allowEmpty('satuan');

        $validator
            ->numeric('harga')
            ->requirePresence('harga', 'create')
            ->notEmpty('harga');

        $validator
            ->numeric('jumlah')
            ->requirePresence('jumlah', 'create')
            ->notEmpty('jumlah');

        $validator
            ->numeric('subtotal')
            ->requirePresence('subtotal', 'create')
            ->notEmpty('subtotal');

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
        $rules->add($rules->existsIn(['permohonan_izin_id'], 'PermohonanIzin'));
        return $rules;
    }

    public function getCalculatedData($permohonanIzinId) {
        $data = [];
        $retribusiDetails = $this->find('all', [
            'conditions' => [
                'permohonan_izin_id' => $permohonanIzinId
            ]
        ]);

        if ($retribusiDetails->count() > 0) {
            foreach ($retribusiDetails as $retribusiDetail) {
                $data[$retribusiDetail->kode_item] = [
                    'harga' => $retribusiDetail->harga,
                    'jumlah' => $retribusiDetail->jumlah,
                    'subtotal' => $retribusiDetail->subtotal,
                ];
            }
        }

        return $data;
    }
}
