<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Entity\Peran;
use App\Service\AuthService;

/**
 * Peran Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Unit
 * @property \Cake\ORM\Association\HasMany $Pengguna
 * @property \Cake\ORM\Association\BelongsToMany $Menu
 */
class PeranTable extends AppTable
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

        $this->setTable('peran');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id'
        ]);

        $this->hasMany('Pengguna', [
            'foreignKey' => 'peran_id'
        ]);

        $this->belongsToMany('Menu', [
            'foreignKey' => 'peran_id',
            'targetForeignKey' => 'menu_id',
            'joinTable' => 'peran_menu'
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

        $validator->add(
            'label_peran', 
            ['unique' => [
                'rule' => 'peranExists', 
                'provider' => 'table', 
                'message' => 'sudah dipakai. Mohon ganti dengan label peran.']
            ]
        );

        $validator
            ->requirePresence('label_peran', 'create')
            ->notEmpty('label_peran');

        $validator
            ->allowEmpty('home_path');

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
        $rules->add($rules->existsIn(['instansi_id'], 'Instansi'));
        return $rules;
    }

    public function getAccessibleMenu()
    {
        $allMenu = [];
        $currentUser = $this->getUser();

        // Get user accesible menu
        if ($currentUser) {
            $this->Menu->setFilteredBeforeFind(false);
            $this->setFilteredBeforeFind(false);

            $menu = $this->Menu->find('threaded');
            $menu->orderAsc('no_urut');
            AuthService::setUser($currentUser);

            if (AuthService::isSuperAdmin()) {
                $menu->select([
                    'Menu.id',
                    'label' => 'Menu.label_menu',
                    'Menu.tautan',
                    'Menu.parent_id'
                ]);
            } else {
                $menu->matching('Peran', function ($q) use ($currentUser) {
                    return $q->select([
                        'Menu.id',
                        'label' => 'Menu.label_menu',
                        'Menu.tautan',
                        'Menu.parent_id'
                    ])
                        ->where([
                            'Peran.id' => $currentUser->peran_id,
                        ])
                        ->order([
                            'Menu.no_urut' => 'ASC',
                        ]);
                });
            }
            $allMenu = $menu->toArray();
        }

        return $allMenu;
    }

    /**
     * Function to get all menu of a peran
     * @param $roleId
     * @return array
     */
    public function getExistingMenu($roleId)
    {
        $allMenu = $this->getAccessibleMenu();

        // Get Selected Menu
        $selectedMenu = $this->Menu->find();
        $selectedMenu->matching('Peran', function ($q) use ($roleId) {
            return $q->select(['Menu.id'])
                ->where([
                    'Peran.id' => $roleId
                ]);
        });
        $selectedMenuIds = $selectedMenu->extract('id')->toArray();

        $result = $this->parseSelectedMenu($allMenu, $selectedMenuIds);
        return $result;
    }

    /**
     * Function to add is_selected attribute to menu array data
     * @param $allMenuData
     * @param $selectedMenuData
     * @return array
     */
    public function parseSelectedMenu($allMenuData, $selectedMenuIds = array())
    {
        $parsedMenu = array();

        // Parse all menu and add is_selected attribute if it's included in selected menu data
        if (!empty($allMenuData)) {
            foreach($allMenuData as $index=>$menuData) {
                $parsedMenu[$index] = $menuData;
                if (in_array($menuData->id, $selectedMenuIds)) {
                    $parsedMenu[$index]['is_selected'] = true;
                } else {
                    $parsedMenu[$index]['is_selected'] = false;
                }

                if (!empty($menuData->children)) {
                    $parsedMenu[$index]['children'] = self::parseSelectedMenu($menuData->children, $selectedMenuIds);
                }
            }
        }
        return $parsedMenu;
    }

    /**
     * Check if the peran already exists
     */
    public function peranExists($value,$context)
    {
        $roles = null;

        if (isset($context['data']['id'])) {
            $roles = $this->find('all')->where(['label_peran ILIKE' => $value, 'id !=' => $context['data']['id']])->first();
        } else {
            $roles = $this->find('all')->where(['label_peran ILIKE' => $value])->first();
        }

        if (empty($roles)) {
            return true;
        }

        return false;
    }
}
