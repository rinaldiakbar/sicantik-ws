<?php
namespace App\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventManager;
use Cake\ORM\Entity;
use Cake\Event\Event;
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
class CDeprisiasiTable extends AppTable
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

        $this->setTable('c_deprisiasi');
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
		$koneksi = ConnectionManager::get('default');
		
		$aktif_tf = $koneksi->execute("select b.tahun_fiscal as tahun_fiscal, status from c_tahun_fiscal b where b.periode_awal <= '".$entity->tgl_transaksi->format('Y-m-d')."' and b.periode_akhir >= '".$entity->tgl_transaksi->format('Y-m-d')."'")->fetchAll('assoc');
		
		
		if($aktif_tf){
			if($aktif_tf[0]['status']=='tutup'){
				throw new \Exception('Tahun Fiskal sudah di tutup silahkan rubah posting ke tahun fiscal berikutnya');
				exit();
			}			
		}else{
			throw new \Exception('Tahun Fiskal belum ada,silahkan buat terlebih dahulu tahun fiskal');
			exit();
		}
				
		$aktif_trx = $koneksi->execute("select a.tahun_fiscal as tahun_fiscal from c_deprisiasi a, c_tahun_fiscal b where a.tahun_fiscal = b.tahun_fiscal and b.periode_awal <= '".$entity->tgl_transaksi->format('Y-m-d')."' and b.periode_akhir >= '".$entity->tgl_transaksi->format('Y-m-d')."'")->fetchAll('assoc');
		
		
		if($aktif_trx){
			throw new \Exception('Transaksi sudah pernah di proses');
			exit();
		}
		
		$daftar_aset = $koneksi->execute("select * from c_aset where nilai_buku > nilai_scrapt"
            )->fetchAll('assoc');
		
		foreach($daftar_aset as $aset){
			$deprisiasi = $aset['nilai_akusisi'] * $aset['depresiasi_rate'];
			$aset['nilai_buku'] = $aset['nilai_buku'] - $deprisiasi;
						
			$koneksi->update('c_aset', $aset, ['id ' => $aset['id']]);
			
			//Sisi Kredit
			$saldo_akhir = $koneksi->execute("select saldo from c_ledger_6 where id = (select max(id) as id from c_ledger_6 where kode_akun = '".$aset['akun_deprisiasi']."')"
            )->fetchAll('assoc');
			
			$saldo_akhir[0]["saldo"] = $saldo_akhir[0]["saldo"] + $deprisiasi;			
			
            $data_ledger["del"] = 0;
			$data_ledger["kode_akun"] = $aset['akun_deprisiasi'];
            $data_ledger["tgl_posting"] = $entity->tgl_transaksi->format('Y-m-d');
			$data_ledger["tgl_transaksi"] = $entity->tgl_transaksi->format('Y-m-d');
			$data_ledger["bulan"] = $entity->tgl_transaksi->format('m');
			$data_ledger["tahun"] = $entity->tgl_transaksi->format('Y');
            $data_ledger["keterangan"] = 'deprisiasi '.$aset['nama_aset']." ".$aset['keterangan_aset'];
            $data_ledger["debit"] = 0;  
			$data_ledger["kredit"] = $deprisiasi;
			$data_ledger["instansi_id"] = $entity->instansi_id;
			$data_ledger["saldo"] = $saldo_akhir[0]["saldo"];
			$data_ledger["c_jurnal_detail_id"] = $entity->id;
			if($entity->isnew()===false){
				$data_ledger["diubah_oleh"] = $entity->dirubah_oleh;
				$data_ledger["tgl_diubah"] = date('Y-m-d');
				
				$ref_id = $koneksi->execute("select id from c_ledger_6 where kredit > 0 and c_jurnal_detail_id = ".$entity->id
				)->fetchAll('assoc');
				
				$koneksi->update('c_ledger_6', $data_ledger, ['id' => $ref_id[0]['id']]);
			}else{
				$data_ledger["diubah_oleh"] = $entity->dirubah_oleh;
				$data_ledger["tgl_diubah"] = date('Y-m-d');
				$data_ledger["tgl_dibuat"] = date('Y-m-d');
				$data_ledger["dibuat_oleh"] = $entity->dibuat_oleh;
				$koneksi->insert('c_ledger_6', $data_ledger);
			}	
				
			//Sisi Debet
			$saldo_akhir = $koneksi->execute("select saldo from c_ledger_6 where id = (select max(id) as id from c_ledger_6 where kode_akun = '".$aset['akun_beban']."')"
            )->fetchAll('assoc');
			
			$saldo_akhir[0]["saldo"] = $saldo_akhir[0]["saldo"] + $deprisiasi;			
			
            $data_ledger["del"] = 0;
			$data_ledger["kode_akun"] = $aset['akun_beban'];
            $data_ledger["tgl_posting"] = $entity->tgl_transaksi->format('Y-m-d');
			$data_ledger["tgl_transaksi"] = $entity->tgl_transaksi->format('Y-m-d');
			$data_ledger["bulan"] = $entity->tgl_transaksi->format('m');
			$data_ledger["tahun"] = $entity->tgl_transaksi->format('Y');
            $data_ledger["keterangan"] = 'deprisiasi '.$aset['nama_aset']." ".$aset['keterangan_aset'];
            $data_ledger["debit"] = $deprisiasi;  
			$data_ledger["kredit"] = 0;
			$data_ledger["instansi_id"] = $entity->instansi_id;
			$data_ledger["saldo"] = $saldo_akhir[0]["saldo"];
			$data_ledger["c_jurnal_detail_id"] = $entity->id;
			if($entity->isnew()===false){
				$data_ledger["diubah_oleh"] = $entity->dirubah_oleh;
				$data_ledger["tgl_diubah"] = date('Y-m-d');
				
				$ref_id = $koneksi->execute("select id from c_ledger_6 where kredit > 0 and c_jurnal_detail_id = ".$entity->id
				)->fetchAll('assoc');
				
				$koneksi->update('c_ledger_6', $data_ledger, ['id' => $ref_id[0]['id']]);
			}else{
				$data_ledger["diubah_oleh"] = $entity->dirubah_oleh;
				$data_ledger["tgl_diubah"] = date('Y-m-d');
				$data_ledger["tgl_dibuat"] = date('Y-m-d');
				$data_ledger["dibuat_oleh"] = $entity->dibuat_oleh;
				$koneksi->insert('c_ledger_6', $data_ledger);
			}	
		}
		
		$koneksi->execute('update c_deprisiasi set tahun_fiscal = (select tahun_fiscal from c_tahun_fiscal where periode_awal <= c_deprisiasi.tgl_transaksi and periode_akhir >= c_deprisiasi.tgl_transaksi) where id = '.$entity->id);
		
		return;
	}
}
