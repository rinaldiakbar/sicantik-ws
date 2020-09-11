<?php

use Phinx\Migration\AbstractMigration;

class AlterAddIndexes extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('alur_pengajuan');
        $table->addIndex('jenis_izin_id');
        $table->addIndex('jenis_pengajuan_id');
        $table->addIndex('jenis_proses_id');
        $table->addIndex('nama_proses');
        $table->addIndex('form_id');
        $table->update();

        $table = $this->table('alur_proses');
        $table->addIndex('keterangan');
        $table->update();

        $table = $this->table('bidang_usaha');
        $table->addIndex('kode');
        $table->addIndex('keterangan');
        $table->update();

        $table = $this->table('bidang_usaha_perusahaan');
        $table->addIndex('bidang_usaha_id');
        $table->addIndex('perusahaan_id');
        $table->update();

        $table = $this->table('canvas');
        $table->addIndex('form_id');
        $table->addIndex('form_type');
        $table->addIndex('datatabel_id');
        $table->update();

        $table = $this->table('canvas_tab');
        $table->addIndex('canvas_id');
        $table->update();

        $table = $this->table('daftar_proses');
        $table->addIndex('alur_proses_id');
        $table->addIndex('jenis_proses_id');
        $table->addIndex('no');
        $table->addIndex('nama_proses');
        $table->addIndex('form_id');
        $table->update();

        $table = $this->table('data_kolom');
        $table->addIndex('datatabel_id');
        $table->addIndex('data_kolom');
        $table->addIndex('label');
        $table->update();

        $table = $this->table('datatabel');
        $table->addIndex('nama_datatabel');
        $table->addIndex('label');
        $table->addIndex('unit_id');
        $table->update();

        $table = $this->table('desa');
        $table->addIndex('kode_daerah');
        $table->addIndex('nama_daerah');
        $table->addIndex('kecamatan_id');
        $table->update();

        $table = $this->table('dokumen_pendukung');
        $table->addIndex('jenis_izin_id');
        $table->addIndex('nama_dokumen');
        $table->update();

        $table = $this->table('element');
        $table->addIndex('canvas_id');
        $table->addIndex('label');
        $table->addIndex('data_kolom_id');
        $table->addIndex('kelompok_data_id');
        $table->update();

        $table = $this->table('element_option');
        $table->addIndex('element_id');
        $table->update();

        $table = $this->table('form');
        $table->addIndex('nama_form');
        $table->addIndex('unit_id');
        $table->update();

        $table = $this->table('izin');
        $table->addIndex('no_izin');
        $table->addIndex('no_izin_sebelumnya');
        $table->addIndex('jenis_izin_id');
        $table->addIndex('unit_id');
        $table->addIndex('keterangan');
        $table->addIndex('pemohon_id');
        $table->addIndex('perusahaan_id');
        $table->update();

        $table = $this->table('izin_paralel');
        $table->addIndex('jenis_izin_id');
        $table->addIndex('izin_paralel_id');
        $table->update();

        $table = $this->table('jenis_izin');
        $table->addIndex('jenis_izin');
        $table->addIndex('unit_id');
        $table->update();

        $table = $this->table('jenis_izin_pengguna');
        $table->addIndex('pengguna_id');
        $table->addIndex('jenis_izin_id');
        $table->update();

        $table = $this->table('jenis_pengajuan');
        $table->addIndex('jenis_izin_id');
        $table->addIndex('jenis_pengajuan');
        $table->addIndex('alur_proses_id');
        $table->update();

        $table = $this->table('jenis_proses');
        $table->addIndex('kode');
        $table->addIndex('nama_proses');
        $table->update();

        $table = $this->table('jenis_usaha');
        $table->addIndex('kode');
        $table->addIndex('keterangan');
        $table->addIndex('bidang_usaha_id');
        $table->update();

        $table = $this->table('jenis_usaha_perusahaan');
        $table->addIndex('jenis_usaha_id');
        $table->addIndex('perusahaan_id');
        $table->update();

        $table = $this->table('kabupaten');
        $table->addIndex('kode_daerah');
        $table->addIndex('nama_daerah');
        $table->addIndex('provinsi_id');
        $table->update();

        $table = $this->table('kecamatan');
        $table->addIndex('kode_daerah');
        $table->addIndex('nama_daerah');
        $table->addIndex('kabupaten_id');
        $table->update();

        $table = $this->table('kelompok_data');
        $table->addIndex('template_data_id');
        $table->addIndex('label_kelompok');
        $table->update();

        $table = $this->table('kelompok_kolom');
        $table->addIndex('kelompok_data_id');
        $table->update();

        $table = $this->table('kelompok_kondisi');
        $table->addIndex('kelompok_data_id');
        $table->addIndex('nama_tabel_utama');
        $table->addIndex('tipe_relasi');
        $table->update();

        $table = $this->table('kelompok_tabel');
        $table->addIndex('kelompok_data_id');
        $table->addIndex('nama_tabel');
        $table->addIndex('tipe_join');
        $table->update();

        $table = $this->table('mapper');
        $table->addIndex('key_id');
        $table->addIndex('datatabel_record_id');
        $table->update();

        $table = $this->table('menu');
        $table->addIndex('label_menu');
        $table->addIndex('parent_id');
        $table->addIndex('no_urut');
        $table->update();

        $table = $this->table('pegawai');
        $table->addIndex('nama');
        $table->addIndex('unit_id');
        $table->addIndex('posisi');
        $table->addIndex('jabatan');
        $table->addIndex('no_hp');
        $table->addIndex('email');
        $table->addIndex('instansi_id');
        $table->update();

        $table = $this->table('pemohon');
        $table->addIndex('tipe_identitas');
        $table->addIndex('no_identitas');
        $table->addIndex('nama');
        $table->addIndex('tgl_lahir');
        $table->addIndex('perusahaan_id');
        $table->addIndex('no_tlp');
        $table->addIndex('no_hp');
        $table->addIndex('email');
        $table->addIndex('desa_id');
        $table->addIndex('kecamatan_id');
        $table->addIndex('kabupaten_id');
        $table->addIndex('provinsi_id');
        $table->addIndex('kode_pos');
        $table->update();

        $table = $this->table('penanggung_jawab');
        $table->addIndex('alur_pengajuan_id');
        $table->addIndex('unit_id');
        $table->addIndex('jabatan_id');
        $table->addIndex('pegawai_id');
        $table->update();

        $table = $this->table('pengguna');
        $table->addIndex('username');
        $table->addIndex('peran_id');
        $table->addIndex('pegawai_id');
        $table->update();

        $table = $this->table('peran');
        $table->addIndex('label_peran');
        $table->addIndex('instansi_id');
        $table->update();

        $table = $this->table('peran_menu');
        $table->addIndex('peran_id');
        $table->addIndex('menu_id');
        $table->update();

        $table = $this->table('permohonan_izin');
        $table->addIndex('no_permohonan');
        $table->addIndex('pemohon_id');
        $table->addIndex('perusahaan_id');
        $table->addIndex('jenis_permohonan');
        $table->addIndex('jenis_izin_id');
        $table->addIndex('no_izin_lama');
        $table->addIndex('tgl_pengajuan');
        $table->addIndex('proses_permohonan_id');
        $table->addIndex('status');
        $table->update();

        $table = $this->table('persyaratan');
        $table->addIndex('permohonan_izin_id');
        $table->addIndex('persyaratan_id');
        $table->update();

        $table = $this->table('perusahaan');
        $table->addIndex('nama_perusahaan');
        $table->addIndex('npwp');
        $table->addIndex('no_register');
        $table->addIndex('jenis_perusahaan');
        $table->addIndex('no_tlp');
        $table->addIndex('fax');
        $table->addIndex('email');
        $table->addIndex('desa_id');
        $table->addIndex('kecamatan_id');
        $table->addIndex('kabupaten_id');
        $table->addIndex('provinsi_id');
        $table->addIndex('kode_pos');
        $table->update();

        $table = $this->table('proses_permohonan');
        $table->addIndex('permohonan_izin_id');
        $table->addIndex('jenis_izin_id');
        $table->addIndex('jenis_proses_id');
        $table->addIndex('form_id');
        $table->update();

        $table = $this->table('provinsi');
        $table->addIndex('kode_daerah');
        $table->addIndex('nama_daerah');
        $table->update();

        $table = $this->table('template_data');
        $table->addIndex('instansi_id');
        $table->addIndex('keterangan');
        $table->update();

        $table = $this->table('unit');
        $table->addIndex('nama');
        $table->addIndex('tipe');
        $table->addIndex('parent_id');
        $table->addIndex('kode_daerah');
        $table->update();

        $table = $this->table('unit_pengguna');
        $table->addIndex('pengguna_id');
        $table->addIndex('unit_id');
        $table->update();

        $table = $this->table('unit_terkait');
        $table->addIndex('jenis_izin_id');
        $table->addIndex('unit_id');
        $table->update();
    }
}
