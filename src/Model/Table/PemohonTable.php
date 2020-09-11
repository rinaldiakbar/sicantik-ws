<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;;
use Cake\ORM\Entity;
use App\Model\Entity\Pemohon;

/**
 * Pemohon Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Perusahaan
 * @property \Cake\ORM\Association\BelongsTo $Desa
 * @property \Cake\ORM\Association\BelongsTo $Kecamatan
 * @property \Cake\ORM\Association\BelongsTo $Kabupaten
 * @property \Cake\ORM\Association\BelongsTo $Provinsi
 * @property \Cake\ORM\Association\HasMany $Izin
 * @property \Cake\ORM\Association\HasMany $PermohonanIzin
 */
class PemohonTable extends AppTable
{
    CONST DATA_STATUS_ACTIVE = 'active';
    CONST DATA_STATUS_INACTIVE = 'inactive';
    CONST DATA_STATUS_PENDING = 'pending';
    CONST DATA_STATUS_ALL = 'all';

    private $findDataStatus;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('pemohon');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        /*$this->belongsTo('Perusahaan', [
            'foreignKey' => 'perusahaan_id'
        ]);*/

        $this->belongsTo('Desa', [
            'foreignKey' => 'desa_id'
        ]);

        $this->belongsTo('Kecamatan', [
            'foreignKey' => 'kecamatan_id'
        ]);

        $this->belongsTo('Kabupaten', [
            'foreignKey' => 'kabupaten_id'
        ]);

        $this->belongsTo('Provinsi', [
            'foreignKey' => 'provinsi_id'
        ]);

        $this->hasMany('Izin', [
            'foreignKey' => 'pemohon_id'
        ]);

        $this->hasMany('PermohonanIzin', [
            'foreignKey' => 'pemohon_id'
        ]);

        $this->hasOne('Pengguna', [
            'foreignKey' => 'related_object_id',
            'conditions' => [
                'Pengguna.related_object_name' => 'Pemohon'
            ]
        ]);

        $this->hasMany('DokumenPemohon', [
            'foreignKey' => 'jenis_dokumen_id'
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
            ->requirePresence('tipe_identitas', 'create')
            ->notEmpty('tipe_identitas');

        $validator
            ->requirePresence('no_identitas', 'create')
            ->notEmpty('no_identitas');

        $validator
            ->allowEmpty('username');

        $validator
            ->requirePresence('nama', 'create')
            ->notEmpty('nama');

        $validator
            ->allowEmpty('tempat_lahir');

        $validator
            ->date('tgl_lahir')
            ->allowEmpty('tgl_lahir');

        $validator
            ->requirePresence('jenis_kelamin', 'create')
            ->notEmpty('jenis_kelamin');

        $validator
            ->allowEmpty('pekerjaan');

        $validator
            ->allowEmpty('no_tlp');

        $validator
            ->allowEmpty('no_hp');

        $validator
            ->email('email')
            ->allowEmpty('email');

        $validator
            ->allowEmpty('alamat');

        $validator
            ->allowEmpty('kode_pos');

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
        $rules->add($rules->isUnique(['email', 'id']));
        $rules->add($rules->existsIn(['desa_id'], 'Desa'));
        $rules->add($rules->existsIn(['kecamatan_id'], 'Kecamatan'));
        $rules->add($rules->existsIn(['kabupaten_id'], 'Kabupaten'));
        $rules->add($rules->existsIn(['provinsi_id'], 'Provinsi'));

        // Add a rule that is applied for create and update operations
        $rules->add(function ($entity, $options) {
            // Return a boolean to indicate pass/failure
            if (preg_match('/.(mailinator|yopmail)+/', $entity->email)) {
                return false;
            }
            return true;
        }, 'isFraudEmail');

        return $rules;
    }

    public function setFindDataStatus($dataStatus)
    {
        $this->findDataStatus = $dataStatus;
    }

    public function beforeFind(\Cake\Event\Event $event, \Cake\ORM\Query $query, \ArrayObject $options, $primary)
    {
        parent::beforeFind($event, $query, $options, $primary);

        if (!$this->findDataStatus) {
            $query->where([
                $this->_alias . '.data_status' => self::DATA_STATUS_ACTIVE
            ]);
        } elseif ($this->findDataStatus != 'all') {
            $query->where([
                $this->_alias . '.data_status' => $this->findDataStatus
            ]);
        }
    }
}
