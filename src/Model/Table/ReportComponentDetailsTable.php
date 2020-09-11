<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportComponentDetails Model
 *
 * @property \App\Model\Table\ReportComponentsTable|\Cake\ORM\Association\BelongsTo $ReportComponents
 * @property \App\Model\Table\DaftarProsesTable|\Cake\ORM\Association\BelongsTo $DaftarProses
 * @property \App\Model\Table\PegawaisTable|\Cake\ORM\Association\BelongsTo $Pegawais
 *
 * @method \App\Model\Entity\ReportComponentDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportComponentDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportComponentDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportComponentDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportComponentDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportComponentDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportComponentDetail findOrCreate($search, callable $callback = null, $options = [])
 */
class ReportComponentDetailsTable extends AppTable
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

        $this->setTable('report_component_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ReportComponents', [
            'foreignKey' => 'report_component_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('DaftarProses', [
            'foreignKey' => 'daftar_proses_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Pegawai', [
            'foreignKey' => 'pegawai_id'
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
        $rules->add($rules->existsIn(['report_component_id'], 'ReportComponents'));
        $rules->add($rules->existsIn(['daftar_proses_id'], 'DaftarProses'));
        $rules->add($rules->existsIn(['pegawai_id'], 'Pegawai'));

        return $rules;
    }
}
