<?php
namespace App\Model\Table;

use App\Model\Entity\Perusahaan;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Perusahaan Model
 *
 * @property \Cake\ORM\Association\BelongsTo $JenisUsaha
 * @property \Cake\ORM\Association\BelongsTo $BidangUsaha
 * @property \Cake\ORM\Association\BelongsTo $Desa
 * @property \Cake\ORM\Association\BelongsTo $Kecamatan
 * @property \Cake\ORM\Association\BelongsTo $Kabupaten
 * @property \Cake\ORM\Association\BelongsTo $Provinsi
 * @property \Cake\ORM\Association\HasMany $Izin
 * @property \Cake\ORM\Association\HasMany $Pemohon
 * @property \Cake\ORM\Association\HasMany $PermohonanIzin
 */
class PerusahaanTable extends AppTable
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

        $this->setTable('perusahaan');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('JenisUsaha', [
            'foreignKey' => 'perusahaan_id',
            'targetForeignKey' => 'jenis_usaha_id',
            'joinTable' => 'jenis_usaha_perusahaan'
        ]);
        $this->belongsToMany('BidangUsaha', [
            'foreignKey' => 'perusahaan_id',
            'targetForeignKey' => 'bidang_usaha_id',
            'joinTable' => 'bidang_usaha_perusahaan'
        ]);

        $this->belongsTo('Pemohon', [
            'foreignKey' => 'pemohon_id'
        ]);
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
            'foreignKey' => 'perusahaan_id'
        ]);
        $this->hasMany('Pemohon', [
            'foreignKey' => 'perusahaan_id'
        ]);
        $this->hasMany('PermohonanIzin', [
            'foreignKey' => 'perusahaan_id'
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
            ->requirePresence('nama_perusahaan', 'create')
            ->notEmpty('nama_perusahaan');

        $validator
            ->allowEmpty('npwp');

        $validator
            ->allowEmpty('no_register');

        $validator
            ->allowEmpty('jenis_perusahaan');

        $validator
            ->integer('jumlah_pegawai')
            ->allowEmpty('jumlah_pegawai');

        $validator
            ->numeric('nilai_investasi')
            ->allowEmpty('nilai_investasi');

        $validator
            ->allowEmpty('no_tlp');

        $validator
            ->allowEmpty('fax');

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
        $rules->add($rules->existsIn(['pemohon_id'], 'Pemohon'));
        return $rules;
    }
}
