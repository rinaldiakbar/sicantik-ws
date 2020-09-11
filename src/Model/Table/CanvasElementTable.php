<?php
namespace App\Model\Table;

use App\Model\Entity\Element;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\ORM\Entity;
use ArrayObject;

/**
 * Element Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Canvas
 */
class CanvasElementTable extends AppTable
{
    const TYPE_AUTOCOMPLETE = 'autocomplete';
    const TYPE_BUTTON_SET = 'button-set';
    const TYPE_BUTTON_ACTION = 'button-action';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_DATE = 'date';
    const TYPE_EMAIL = 'email';
    const TYPE_NUMBERING = 'numbering';
    const TYPE_LABEL = 'label';
    const TYPE_NUMBER = 'number';
    const TYPE_PASSWORD = 'password';
    const TYPE_SELECT = 'select';
    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_HYPERLINK = 'hyperlink';

    const BUTTON_ACTION_ADD = 'add';
    const BUTTON_ACTION_EDIT = 'edit';
    const BUTTON_ACTION_VIEW = 'view';
    const BUTTON_ACTION_DELETE = 'delete';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('element');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Canvas', [
            'foreignKey' => 'canvas_id',
            'joinType' => 'INNER'
        ]);
        
        $this->belongsTo('DataKolom', [
            'foreignKey' => 'data_kolom_id',
            'joinType' => 'LEFT'
        ]);
        
        $this->belongsTo('KelompokData', [
            'foreignKey' => 'kelompok_data_id',
            'joinType' => 'LEFT',
            // 'conditions' => [
                // 'type' => self::TYPE_AUTOCOMPLETE
            // ]
        ]);

        $this->belongsTo('Penomoran', [
            'foreignKey' => 'penomoran_id',
            'joinType' => 'LEFT',
            // 'conditions' => [
                // 'type' => self::TYPE_NUMBERING
            // ]
        ]);

        $this->belongsTo('ServiceEksternal', [
            'foreignKey' => 'service_eksternal_id',
            'joinType' => 'LEFT',
            // 'conditions' => [
                // 'type' => self::TYPE_BUTTON_ACTION
            // ]
        ]);

        $this->hasMany('ElementOption', [
            'foreignKey' => 'element_id',
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
            ->integer('del')
            ->requirePresence('del', 'create')
            ->notEmpty('del');

        $validator
            ->integer('required')
            ->requirePresence('required', 'create')
            ->notEmpty('required');

        $validator
            ->requirePresence('label', 'create')
            ->notEmpty('label');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->allowEmpty('tombol_aksi');

        $validator
            ->allowEmpty('tombol_tautan');

        $validator
            ->allowEmpty('service_eksternal_id');

        $validator
            ->allowEmpty('target_simpan');

        $validator
            ->allowEmpty('target_path');
        
        $validator
            ->allowEmpty('tautan');

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

        $validator
            ->notEmpty('no_urut')
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
        $rules->add($rules->existsIn(['canvas_id'], 'Canvas'));
        $rules->add($rules->existsIn(['data_kolom_id'], 'DataKolom'));
        $rules->add($rules->existsIn(['service_eksternal_id'], 'ServiceEksternal'));
        return $rules;
    }

    public function beforeSave(Event $event, Entity $entity, ArrayObject $options)
    {
        parent::beforeSave($event, $entity, $options);

        switch ($entity->type) {
            case self::TYPE_BUTTON_SET:
                $entity->required = 0;

                switch ($entity->button_action) {
                    case self::BUTTON_ACTION_DELETE:
                        $entity->tombol_tautan = null;
                        break;
                }
                break;
        }

        if ($entity->isNew() && !$entity->no_urut) {
            $entity->no_urut = 0;
        }

        return;
    }
}
