<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;

/**
 * Custom tabel
 *
 * @method \App\Model\Entity\Tesw get($primaryKey, $options = [])
 * @method \App\Model\Entity\Tesw newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Tesw[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Tesw|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Tesw patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Tesw[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Tesw findOrCreate($search, callable $callback = null)
 */
class CJurnalDetailTable extends AppTable
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

        $this->setTable('c_jurnal_detail');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    	
	public function afterSave(Event $event, Entity $entity, $options)
	{
        $connection = ConnectionManager::get('default');

        //hitung saldo akhir
        $saldo_akhir = $connection->execute(
            "select max(id) as id, kode_akun, saldo from c_ledger_6 where kode_akun = '".$entity->kode_akun."' group by kode_akun, saldo"
        )->fetchAll('assoc');
        debug($saldo_akhir);
        $saldo_akhir[0]["saldo"] = $saldo_akhir[0]["saldo"] + $entity->debit - $entity->kredit;

        $data_ledger["kode_akun"] = $entity->kode_akun;
        $data_ledger["tgl_posting"] = $entity->tgl_posting;
        $data_ledger["keterangan"] = $entity->keterangan_transaksi;
        $data_ledger["debit"] = $entity->debit;
        $data_ledger["kredit"] = $entity->kredit;
        $data_ledger["instansi_id"] = $entity->instansi_id;
        $data_ledger["saldo"] = $saldo_akhir[0]["saldo"];
        //hitung saldo akhir

        $connection->insert('c_ledger_6', $data_ledger);
	}
}
