<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MenuModule Model
 *
 * @property \App\Model\Table\MenusTable|\Cake\ORM\Association\BelongsTo $Menus
 *
 * @method \App\Model\Entity\MenuModule get($primaryKey, $options = [])
 * @method \App\Model\Entity\MenuModule newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MenuModule[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MenuModule|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MenuModule patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MenuModule[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MenuModule findOrCreate($search, callable $callback = null, $options = [])
 */
class MenuModuleTable extends Table
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

        $this->setTable('menu_module');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Menu', [
            'foreignKey' => 'menu_id',
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
            ->maxLength('tautan', 500)
            ->requirePresence('tautan', 'create')
            ->notEmpty('tautan');

        $validator
            ->maxLength('dibuat_oleh', 25)
            ->allowEmpty('dibuat_oleh');

        $validator
            ->dateTime('tgl_dibuat')
            ->allowEmpty('tgl_dibuat');

        $validator
            ->dateTime('tgl_diubah')
            ->allowEmpty('tgl_diubah');

        $validator
            ->maxLength('diubah_oleh', 25)
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
        $rules->add($rules->existsIn(['menu_id'], 'Menu'));

        return $rules;
    }
}
