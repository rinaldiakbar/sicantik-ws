<?php
namespace App\Model\Table;

use App\Model\Entity\Canvas;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\ORM\Entity;
use ArrayObject;

/**
 * Canvas Model
 *
 * @property \Cake\ORM\Association\BelongsTo Form
 * @property \Cake\ORM\Association\BelongsTo $Datatabel
 */
class CanvasTable extends AppTable
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

        $this->setTable('canvas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Form', [
            'foreignKey' => 'form_id',
            'joinType' => 'INNER',
        ]);
        
        $this->belongsTo('Datatabel', [
            'foreignKey' => 'datatabel_id'
        ]);

        $this->belongsTo('TemplateData', [
            'foreignKey' => 'template_data_id'
        ]);
        
        $this->hasMany('CanvasElement', [
            'foreignKey' => 'canvas_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);
        
        $this->hasMany('CanvasTab', [
            'foreignKey' => 'canvas_id',
            'dependent' => true,
            'cascadeCallbacks' => true
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

        $validator
            ->integer('tab_index')
            ->requirePresence('tab_index', 'create')
            ->notEmpty('tab_index');

        $validator
            ->requirePresence('form_type', 'create')
            ->notEmpty('form_type');

        $validator
            ->allowEmpty('dibuat_oleh');

        $validator
            ->date('tgl_dibuat')
            ->allowEmpty('tgl_dibuat');

        $validator
            ->allowEmpty('no_urut');

        $validator
            ->allowEmpty('diubah_oleh');

        $validator
            ->date('tgl_diubah')
            ->allowEmpty('tgl_diubah');

        $validator
            /*->add('initial_web_service', [
                'isUrl' => [
                    'rule'    => ['url', true], // Strict url check
                    'message' => 'Initial Web Service must be valid URL'
                ]
            ])*/
            ->allowEmpty('initial_web_service');

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
        $rules->add($rules->existsIn(['form_id'], 'Form'));
        $rules->add($rules->existsIn(['datatabel_id'], 'Datatabel'));
        $rules->add($rules->existsIn(['template_data_id'], 'TemplateData'));
        return $rules;
    }

    public function beforeSave(Event $event, Entity $entity, ArrayObject $options)
    {
        parent::beforeSave($event, $entity, $options);

        if ($entity->isNew() && !$entity->no_urut) {
            $entity->no_urut = 0;
        }
        
        return;
    }
}
