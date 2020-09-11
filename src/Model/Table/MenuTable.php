<?php
namespace App\Model\Table;

use App\Model\Entity\Menu;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Menu Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentMenu
 * @property \Cake\ORM\Association\HasMany $ChildMenu
 * @property \Cake\ORM\Association\BelongsToMany $Peran
 */
class MenuTable extends AppTable
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
        $this->filteredBeforeFind = true;
        $this->strictDelete = true;

        $this->setTable('menu');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ParentMenu', [
            'className' => 'Menu',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildMenu', [
            'className' => 'Menu',
            'foreignKey' => 'parent_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);
        $this->hasMany('MenuModule', [
            'foreignKey' => 'menu_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);
        $this->belongsToMany('Peran', [
            'foreignKey' => 'menu_id',
            'targetForeignKey' => 'peran_id',
            'joinTable' => 'peran_menu'
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
            ->requirePresence('label_menu', 'create')
            ->notEmpty('label_menu');

        $validator
            ->requirePresence('tautan', 'create')
            ->notEmpty('tautan');

        $validator
            ->allowEmpty('icon');

        $validator
            ->integer('no_urut');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentMenu'));
        return $rules;
    }
}
