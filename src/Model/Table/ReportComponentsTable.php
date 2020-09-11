<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportComponents Model
 *
 * @property \App\Model\Table\JenisIzinsTable|\Cake\ORM\Association\BelongsTo $JenisIzins
 * @property \App\Model\Table\InstansisTable|\Cake\ORM\Association\BelongsTo $Instansis
 * @property \App\Model\Table\ReportComponentDetailsTable|\Cake\ORM\Association\HasMany $ReportComponentDetails
 *
 * @method \App\Model\Entity\ReportComponent get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportComponent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportComponent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportComponent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportComponent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportComponent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportComponent findOrCreate($search, callable $callback = null, $options = [])
 */
class ReportComponentsTable extends AppTable
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

        $this->setTable('report_components');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('JenisIzin', [
            'foreignKey' => 'jenis_izin_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('ReportComponentDetails', [
            'foreignKey' => 'report_component_id'
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
        $rules->add($rules->existsIn(['jenis_izin_id'], 'JenisIzin'));
        $rules->add($rules->existsIn(['instansi_id'], 'Instansi'));

        return $rules;
    }
}
