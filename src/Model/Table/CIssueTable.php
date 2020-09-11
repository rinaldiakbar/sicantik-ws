<?php

namespace App\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventManager;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\Datasource\EntityInterface;
use Cake\ORM\ArrayObject;
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
 * @method \App\Modefl\Entity\Tesw findOrCreate($search, callable $callback = null)
 */
class CIssueTable extends AppTable
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

        $this->setTable('c_issue');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

    /*public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options){
        debug($data);

        exit();
    }*/

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */

    public function afterSave(Event $event, Entity $entity, $options)
    {
        if ($entity->isNew() === false) {
            $koneksi = ConnectionManager::get('default');

            $user = $this->getUser();
            $aktif_user = $user->username;
            /*
            @Perubahan Kategori
                - Perubahan pertama
                    - Tgl awal kategori & Status samakan
                - Penerima tugas clearkan
                - Tutup log assignment aktif
                - Perubahan kategori berikutnya
                    - set status ke new
            @Kategori tidak berubah
                - Status berubah
                    - Status = 'close'
                        - tutup semua log
                - Status tidak berubah
                    - Sebelumnya 'new'?
                        - Set status 'dilayani'
                - Tidak ada assignment
                        - Set assignment ke user aktif
                - Set log assignment

            */
            //debug($entity);exit();
            if ($entity->isDirty('kategori') === true or $entity->isDirty('projek') === true) {

                $koneksi->execute("update c_log_kategori set tgl_akhir = '" . date("Y-m-d H:i") . "', diubah_oleh = '" . $aktif_user . "' where tgl_akhir is null and c_issue_id = " . $entity->id);
                //clearkan penerima tugasnya
                $data_issue['status'] = 'new';
                $data_issue['penerima_tugas'] = null;
                $koneksi->update('c_issue', $data_issue, ['id' => $entity->id]);
                //End clearkan penerima tugasnya

                //close assignment aktif
                $koneksi->execute("update c_log_assignment_issue set tgl_akhir_tugas = '" . date("Y-m-d H:i") . "', diubah_oleh = '" . $aktif_user . "' where tgl_akhir_tugas is null and c_issue_id = " . $entity->id);

                //Kategori pertama
                $cek_kategori = $koneksi->execute("select kategori from c_log_kategori where c_issue_id = " . $entity->id)->fetchAll("assoc");
                if (empty($cek_kategori)) { //jika pertama kali samakan tgl_awalnya dg status
                    $awal_status = $koneksi->execute("select tgl_awal from c_log_status_isu where c_issue_id = " . $entity->id)->fetchAll("assoc");
                    $log_kategori["tgl_mulai"] = $awal_status[0]['tgl_awal'];
                } else {
                    $log_kategori["tgl_mulai"] = date('Y-m-d H:i');
                }

                //Set Log Kategori
                $log_kategori["del"] = 0;
                $log_kategori["instansi_id"] = $entity->instansi_id;
                $log_kategori["c_issue_id"] = $entity->id;
                $log_kategori["projek"] = $entity->projek;
                $log_kategori["kategori"] = $entity->kategori;
                $log_kategori["catatan"] = $entity->catatan;
                $log_kategori["diset_oleh"] = $aktif_user;
                $log_kategori["diubah_oleh"] = $aktif_user;
                $log_kategori["tgl_diubah"] = date('Y-m-d');
                $log_kategori["tgl_dibuat"] = date('Y-m-d');
                $log_kategori["dibuat_oleh"] = $aktif_user;
                $koneksi->insert('c_log_kategori', $log_kategori);

                //Close end date status sebelumnya
                $koneksi->execute("update c_log_status_isu set tgl_akhir = '" . date("Y-m-d H:i") . "' , diubah_oleh = '" . $aktif_user . "' where tgl_akhir is null and c_issue_id = " . $entity->id);
                //End Close end date status sebelumnya

                $log_status["del"] = 0;
                $log_status["instansi_id"] = $entity->instansi_id;
                $log_status["c_issue_id"] = $entity->id;
                $log_status["status"] = "new";
                $log_status["pic"] = $aktif_user;
                $log_status["catatan"] = $entity->catatan;
                $log_status["tgl_awal"] = date('Y-m-d H:i');
                $log_status["diubah_oleh"] = $entity->diubah_oleh;
                $log_status["tgl_diubah"] = date('Y-m-d');
                $log_status["tgl_dibuat"] = date('Y-m-d');
                $log_status["dibuat_oleh"] = $entity->dibuat_oleh;
                $koneksi->insert('c_log_status_isu', $log_status);


            } else { // jika tidak ada perubahan kategori atau projek maka status berubah
                //Log Status jika baru
                //Status dirubah manual oleh user
                $last_status = $koneksi->execute("select status from c_log_status_isu where tgl_akhir is null and c_issue_id = " . $entity->id)->fetchAll('assoc');

                if ($entity->isDirty('status') === true) {

                    if ($last_status[0]['status'] === 'new') { //Dari new harus dilayani dulu
                        if ($entity->status === 'close') {
                            throw new \Exception('Hanya bisa di close dalam status in progress/dilayani');
                            exit();
                        }

                    }
                    $koneksi->execute("update c_log_status_isu set tgl_akhir = '" . date("Y-m-d H:i") . "' , diubah_oleh = '" . $aktif_user . "' where tgl_akhir is null and c_issue_id = " . $entity->id);

                    $log_status["del"] = 0;
                    $log_status["instansi_id"] = $entity->instansi_id;
                    $log_status["c_issue_id"] = $entity->id;
                    $log_status["status"] = $entity->status;
                    $log_status["pic"] = $aktif_user;
                    $log_status["catatan"] = $entity->catatan;
                    $log_status["tgl_awal"] = date('Y-m-d H:i');
                    $log_status["diubah_oleh"] = $entity->diubah_oleh;
                    $log_status["tgl_diubah"] = date('Y-m-d');
                    $log_status["tgl_dibuat"] = date('Y-m-d');
                    $log_status["dibuat_oleh"] = $entity->dibuat_oleh;
                    $koneksi->insert('c_log_status_isu', $log_status);

                    if ($entity->status == "close") {
                        $koneksi->execute("update c_log_status_isu set tgl_akhir = '" . date("Y-m-d H:i") . "' , diubah_oleh = '" . $aktif_user . "' where tgl_akhir is null and c_issue_id = " . $entity->id);

                        $koneksi->execute("update c_log_assignment_issue set tgl_akhir_tugas = '" . date("Y-m-d H:i") . "', diubah_oleh = '" . $aktif_user . "' where tgl_akhir_tugas is null and c_issue_id = " . $entity->id);

                        $koneksi->execute("update c_log_kategori set tgl_akhir = '" . date("Y-m-d H:i") . "', diubah_oleh = '" . $aktif_user . "' where tgl_akhir is null and c_issue_id = " . $entity->id);

                        $koneksi->execute("update c_issue set tgl_resolve = '" . date("Y-m-d H:i") . "', diubah_oleh = '" . $aktif_user . "' where id = " . $entity->id);
                    } else {
                        $koneksi->execute("update c_issue set tgl_resolve = null, diubah_oleh = '" . $aktif_user . "' where id = " . $entity->id);
                    }
                } else {

                    if ($last_status[0]['status'] === 'new') { // otomatis di set 'dilayani'
                        $koneksi->execute("update c_log_status_isu set tgl_akhir = '" . date("Y-m-d H:i") . "' , diubah_oleh = '" . $aktif_user . "' where tgl_akhir is null and c_issue_id = " . $entity->id);

                        $log_status["del"] = 0;
                        $log_status["instansi_id"] = $entity->instansi_id;
                        $log_status["c_issue_id"] = $entity->id;
                        $log_status["status"] = 'dilayani';
                        $log_status["pic"] = $aktif_user;
                        $log_status["catatan"] = $entity->catatan;
                        $log_status["tgl_awal"] = date('Y-m-d H:i');
                        $log_status["diubah_oleh"] = $entity->diubah_oleh;
                        $log_status["tgl_diubah"] = date('Y-m-d');
                        $log_status["tgl_dibuat"] = date('Y-m-d');
                        $log_status["dibuat_oleh"] = $entity->dibuat_oleh;
                        $koneksi->insert('c_log_status_isu', $log_status);

                        $data_issue['status'] = 'dilayani';
                        $koneksi->update('c_issue', $data_issue, ['id' => $entity->id]);
                    }

                }
                //End Log Status

                //Log assignment
                $petugas = $koneksi->execute("select penerima_tugas from c_issue where id = " . $entity->id)->fetchAll('assoc');

                if ($petugas[0]['penerima_tugas'] === null or $entity->isDirty('penerima_tugas') === true) {
                    if ($entity->isDirty('penerima_tugas') === true) {
                        $log_assign["penerima_tugas"] = $entity->penerima_tugas;
                    } else {
                        $data_issue["penerima_tugas"] = $aktif_user;
                        $koneksi->update('c_issue', $data_issue, ['id' => $entity->id]);

                        $log_assign["penerima_tugas"] = $aktif_user;
                    }


                    $koneksi->execute("update c_log_assignment_issue set tgl_akhir_tugas = '" . date("Y-m-d H:i") . "', diubah_oleh = '" . $aktif_user . "' where tgl_akhir_tugas is null and c_issue_id = " . $entity->id);

                    $log_assign["del"] = 0;
                    $log_assign["instansi_id"] = $entity->instansi_id;
                    $log_assign["c_issue_id"] = $entity->id;
                    $log_assign["pemberi_tugas"] = $aktif_user;

                    $log_assign["catatan"] = $entity->catatan;
                    $log_assign["tgl_penugasan"] = date('Y-m-d H:i');
                    $log_assign["diubah_oleh"] = $aktif_user;
                    $log_assign["tgl_diubah"] = date('Y-m-d');
                    $log_assign["tgl_dibuat"] = date('Y-m-d');
                    $log_assign["dibuat_oleh"] = $aktif_user;
                    $koneksi->insert('c_log_assignment_issue', $log_assign);
                }

                //End Log assignment
            }

            if ($entity->isDirty('catatan') === true) {
                $log["del"] = 0;
                $log["instansi_id"] = $entity->instansi_id;
                $log["c_issue_id"] = $entity->id;
                $log["pengguna"] = $aktif_user;
                $log["tanggapan"] = $entity->catatan;
                $log["diubah_oleh"] = $aktif_user;
                $log["tgl_diubah"] = date('Y-m-d');
                $log["tgl_dibuat"] = date('Y-m-d');
                $log["dibuat_oleh"] = $aktif_user;
                $koneksi->insert('c_log_tanggapan', $log);
            }

        }


        /*if($entity->isNew()===false&&!$entity->unit_pelapor){
            $koneksi->execute("update c_issue set unit_pelapor = (select unit.nama as unit_pelapor  from unit, pegawai, pengguna where unit_id = unit.id and pegawai_id = pegawai.id and pengguna.username = '".$entity->dibuat_oleh."') where id = ".$entity->id);
        }*/

        return;
    }

    public function afterSaveCommit(Event $event, EntityInterface $entity, $options)
    {

        $koneksi = ConnectionManager::get('default');

        $user = $this->getUser();
        $aktif_user = $user->username;

        if ($entity->isNew() === true && !$entity->unit_pelapor) {
            /*
            @Pertama di laporkan
                - Default status = new
                - Set unit pelapornya
            */
            $unit = $koneksi->execute("select unit.nama as unit_pelapor  from unit, pegawai, pengguna where unit_id = unit.id and pegawai_id = pegawai.id and pengguna.username = '" . $entity->dibuat_oleh . "'")->fetchAll('assoc');

            $data_issue['data_labels'] = '{"status" : "new"}';
            $data_issue['status'] = 'new';
            $data_issue['unit_pelapor'] = $unit[0]['unit_pelapor'];
            $koneksi->update('c_issue', $data_issue, ['id' => $entity->id]);

            $log_status["del"] = 0;
            $log_status["instansi_id"] = $entity->instansi_id;
            $log_status["c_issue_id"] = $entity->id;
            $log_status["status"] = "new";
            $log_status["pic"] = $aktif_user;
            //$log_status["catatan"] = $entity->catatan;<-- pertama gak bisa masukkan catatan
            $log_status["tgl_awal"] = date('Y-m-d H:i');
            $log_status["diubah_oleh"] = $entity->diubah_oleh;
            $log_status["tgl_diubah"] = date('Y-m-d');
            $log_status["tgl_dibuat"] = date('Y-m-d');
            $log_status["dibuat_oleh"] = $entity->dibuat_oleh;
            $koneksi->insert('c_log_status_isu', $log_status);

            //set status new
        }

        return;
    }
}
