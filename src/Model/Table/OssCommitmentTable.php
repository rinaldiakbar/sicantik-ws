<?php
namespace App\Model\Table;

use App\Model\Entity\OssCommitment;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;


/**
 * OssCommitment Model
 *
 */
class OssCommitmentTable extends AppTable
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
        $this->strictUpdate = true;

        $this->setTable('oss_commitment');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Instansi', [
            'foreignKey' => 'instansi_id'
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

     public function afterSave(Event $event, OssCommitment $entity)
     {
        $permohonanIzinTable = TableRegistry::get('permohonan_izin');

        $permohonanIzin = $permohonanIzinTable
            ->find('all')
            ->where([
                'nib_id' => $entity->nib_id,
                'oss_id' => $entity->oss_id,
                'kode_oss' => $entity->kode_izin,
            ])
            ->extract('id')
            ->toArray();

        $permohonanIzinData = $permohonanIzinTable->get($permohonanIzin[0]);
        $permohonanIzinData['is_active'] = true;
        $permohonanIzinData['status_rekomendasi'] = 'R';
        $permohonanIzinData['catatan_rekomendasi'] = $entity->keterangan;
        $permohonanIzinData['diubah_oleh'] = 'OSS';

        $permohonanIzinTable->save($permohonanIzinData);
     }
}
