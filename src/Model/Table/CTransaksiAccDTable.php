<?php

namespace App\Model\Table;

//use App\Model\Entity\BidangUsaha;
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
 * BidangUsaha Model
 *
 * @property \Cake\ORM\Association\HasMany $BidangUsahaPermohonan
 * @property \Cake\ORM\Association\HasMany $JenisUsaha
 * @property \Cake\ORM\Association\HasMany $Perusahaan
 */
class CTransaksiAccDTable extends AppTable
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

        $this->setTable('c_transaksi_acc_d');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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

        return $validator;
    }


    public function afterSave(Event $event, Entity $entity, $options)
    {
        $koneksi = ConnectionManager::get('default');

        /*
         * Tentukan jenis transaksi yang ada
         *  - 1 jenis transaksi bersama
         *  - per-item transaksi
         */
        $header = $koneksi->execute("
            SELECT c_jenis_transaksi_id, tgl_transaksi, tgl_posting 
            FROM c_transaksi_acc_h 
            WHERE id = " . $entity->c_transaksi_acc_h_id
        )->fetchAll('assoc');

        $tgl_transaksi = $header[0]['tgl_transaksi'];
        $tgl_posting = $header[0]['tgl_posting'];

        $aktif_tf = $koneksi->execute("
            SELECT b.tahun_fiscal as tahun_fiscal, status 
            FROM c_tahun_fiscal b 
            WHERE b.periode_awal <= '" . $tgl_posting . "' AND b.periode_akhir >= '" . $tgl_posting . "' and instansi_id = " . $entity->instansi_id
        )->fetchAll('assoc');

        if ($aktif_tf) {
            if ($aktif_tf[0]['status'] == 'tutup') {
                throw new \Exception('Tahun Fiskal sudah di tutup silahkan rubah posting ke tahun fiscal berikutnya');
                exit();
            }
        } else {
            throw new \Exception('Tahun Fiskal belum ada,silahkan buat terlebih dahulu tahun fiskal');
            exit();
        }

        if (!$entity->c_jenis_transaksi_id) {
            $jenis_transaksi = $header[0]['c_jenis_transaksi_id'];
        } else {
            $jenis_transaksi = $entity->c_jenis_transaksi_id;
        }

        $account = $koneksi->execute("
            SELECT akun_debet, akun_kredit, is_stock 
            FROM c_jenis_transaksi 
            WHERE id = " . $jenis_transaksi . " AND (instansi_id = " . $entity->instansi_id . " OR instansi_id IS NULL)"
        )->fetchAll('assoc');

        /*
        Simpan debet account
        */
        $saldo_akhir = $koneksi->execute("
            select saldo 
            from c_ledger_6 
            where 
                id = (
                    select max(id) as id 
                    from c_ledger_6 
                    where kode_akun = '" . $account[0]['akun_debet'] . "' 
                        and instansi_id = " . $entity->instansi_id . "
                )
        ")->fetchAll('assoc');

        $saldo_akhir[0]["saldo"] = $saldo_akhir[0]["saldo"] + ($entity->harga_item * $entity->jumlah_item);

        $data_ledger["del"] = 0;
        $data_ledger["kode_akun"] = $account[0]['akun_debet'];
        $data_ledger["tgl_posting"] = $tgl_posting;
        $data_ledger["tgl_posting"] = $tgl_transaksi;
        $data_ledger["bulan"] = substr($tgl_posting, 5, 2);
        $data_ledger["tahun"] = substr($tgl_posting, 0, 4);
        $data_ledger["keterangan"] = $entity->keterangan;
        $data_ledger["debit"] = ($entity->harga_item * $entity->jumlah_item);
        $data_ledger["kredit"] = 0;
        $data_ledger["instansi_id"] = $entity->instansi_id;
        $data_ledger["saldo"] = $saldo_akhir[0]["saldo"];
        $data_ledger["c_jurnal_detail_id"] = $entity->id;

        if ($entity->isnew() === false) {
            $data_ledger["diubah_oleh"] = $entity->dirubah_oleh;
            $data_ledger["tgl_diubah"] = date('Y-m-d');

            $ref_id = $koneksi->execute("
                select id from c_ledger_6 where debit > 0 and c_jurnal_detail_id = " . $entity->id
            )->fetchAll('assoc');

            $koneksi->update('c_ledger_6', $data_ledger, ['id' => $ref_id[0]['id']]);
        } else {
            $data_ledger["diubah_oleh"] = $entity->dirubah_oleh;
            $data_ledger["tgl_diubah"] = date('Y-m-d');
            $data_ledger["tgl_dibuat"] = date('Y-m-d');
            $data_ledger["dibuat_oleh"] = $entity->dibuat_oleh;
            $koneksi->insert('c_ledger_6', $data_ledger);
        }

        //hitung saldo akhir
        /*
        Simpan kredit account
        */
        $saldo_akhir = $koneksi->execute("
            select saldo 
            from c_ledger_6 
            where id = (
                select max(id) as id 
                from c_ledger_6 
                where kode_akun = '" . $account[0]['akun_kredit'] . "' and instansi_id = " . $entity->instansi_id . ")
        ")->fetchAll('assoc');

        $saldo_akhir[0]["saldo"] = $saldo_akhir[0]["saldo"] + ($entity->harga_item * $entity->jumlah_item);

        $data_ledger["del"] = 0;
        $data_ledger["kode_akun"] = $account[0]['akun_kredit'];
        $data_ledger["tgl_posting"] = $tgl_posting;
        $data_ledger["tgl_posting"] = $tgl_transaksi;
        $data_ledger["bulan"] = substr($tgl_posting, 5, 2);
        $data_ledger["tahun"] = substr($tgl_posting, 0, 4);
        $data_ledger["keterangan"] = $entity->keterangan;
        $data_ledger["debit"] = 0;
        $data_ledger["kredit"] = ($entity->harga_item * $entity->jumlah_item);
        $data_ledger["instansi_id"] = $entity->instansi_id;
        $data_ledger["saldo"] = $saldo_akhir[0]["saldo"];
        $data_ledger["c_jurnal_detail_id"] = $entity->id;

        if ($entity->isnew() === false) {
            $data_ledger["diubah_oleh"] = $entity->dirubah_oleh;
            $data_ledger["tgl_diubah"] = date('Y-m-d');

            $ref_id = $koneksi->execute("
                SELECT id FROM c_ledger_6 WHERE kredit > 0 AND c_jurnal_detail_id = " . $entity->id
            )->fetchAll('assoc');

            $koneksi->update('c_ledger_6', $data_ledger, ['id' => $ref_id[0]['id']]);
        } else {
            $data_ledger["diubah_oleh"] = $entity->dirubah_oleh;
            $data_ledger["tgl_diubah"] = date('Y-m-d');
            $data_ledger["tgl_dibuat"] = date('Y-m-d');
            $data_ledger["dibuat_oleh"] = $entity->dibuat_oleh;
            $koneksi->insert('c_ledger_6', $data_ledger);
        }

        if ($account[0]['is_stock'] == 'masuk') {
            if (!is_null($entity->c_barang_id)) {
                $id_barang = $entity->c_barang_id;
            } else {
                //create barang sesuai keterangan
                $data_barang["nama_barang"] = $entity->keterangan;
                $data_barang["diubah_oleh"] = $entity->dirubah_oleh;
                $data_barang["instansi_id"] = $entity->instansi_id;
                $data_barang["diubah_oleh"] = $entity->dirubah_oleh;
                $data_barang["tgl_diubah"] = date('Y-m-d');
                $data_barang["tgl_dibuat"] = date('Y-m-d');
                $data_barang["dibuat_oleh"] = $entity->dibuat_oleh;
                $koneksi->insert('c_barang', $data_barang);

                //ambil id barang
                $sql_barang = "select id from c_barang where nama_barang = '" . $entity->keterangan . "'";
                $barang_baru = $koneksi->execute($sql_barang)->fetchAll('assoc');
                $id_barang = $barang_baru[0]['id'];

            }

            $cek_stok = "select id from c_barang_stok where c_barang_id =" . $id_barang . " and instansi_id = " . $entity->instansi_id;
            $stock_ada = $koneksi->execute($cek_stok)->fetchAll('assoc');

            if ($stock_ada) {
                $tambah = "update c_barang_stok set jumlah = jumlah + " . $entity->jumlah_item . ", diubah_oleh = '" . $entity->dibuat_oleh . "', tgl_diubah = '" . date('Y-m-d') . "' where c_barang_id = " . $id_barang . " and instansi_id = " . $entity->instansi_id;
                $update_stock = $koneksi->execute($tambah);
            } else {
                $data_stock["c_barang_id"] = $id_barang;
                $data_stock["jumlah"] = $entity->jumlah_item;
                $data_stock["diubah_oleh"] = $entity->dirubah_oleh;
                $data_stock["instansi_id"] = $entity->instansi_id;
                $data_stock["diubah_oleh"] = $entity->dirubah_oleh;
                $data_stock["tgl_diubah"] = date('Y-m-d');
                $data_stock["tgl_dibuat"] = date('Y-m-d');
                $data_stock["dibuat_oleh"] = $entity->dibuat_oleh;
                $koneksi->insert('c_barang_stok', $data_stock);
            }


        } else {
            if ($entity->c_barang_id != null) {
                $tambah = "update c_barang_stok set jumlah = jumlah - " . $entity->jumlah_item . ", diubah_oleh = '" . $entity->dibuat_oleh . "', tgl_diubah = '" . date('Y-m-d') . "' where c_barang_id = " . $entity->c_barang_id . " and instansi_id = " . $entity->instansi_id;
                $update_stock = $koneksi->execute($tambah);
            }
        }
        return;
    }
}
