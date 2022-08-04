<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Zend\Debug\Debug;

class Suratmasuk extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function disposisi(){

        $sql = "select 
            sm.status_dokumen AS status_dokumen2, 
            sm.id AS id_surat, 
            sm.tanggal_terimasurat AS tanggal_terimasurat, 
            sm.perihal_surat AS perihal_surat, 
            sm.filename AS filename, 
            sm.jenis_surat AS jenis_surat, 
            sm.created_time AS tanggal_eksekusi, 
            sm.modified_by AS modified_by, 
            ds.disposisi_user AS disposisi_user, 
            ds.disposisi_id AS disposisi_id, 
            ds.status AS status, 
            ds.created_time AS disposisi_time, 
            ds.disposisi_by AS disposisi_by, 
            role_ud.role_name AS jabatan_diposisi, 
            role_udb.role_name AS jabatan_disposisi_by, 
            role_ud.disposision_level AS disposision_level_disposisi, 
            role_udb.disposision_level AS disposision_level_pendisposisi, 
            ud.nama AS nama_disposisi, 
            udb.nama AS nama_pendisposisi, 
            ud.nip AS nip_disposisi, 
            udb.nip AS nip_pendisposisi, 
            ds.modified_time,
            case 
            when sm.status_dokumen = 0 then 'Outstanding'
            when sm.status_dokumen = 1 then 'Di Proses' 
            when sm.status_dokumen = 2 then 'Diposisi' 
            when sm.status_dokumen = 3 then 'Selesai Di Proses' 
            when sm.status_dokumen = 4 then 'Disposisi Proses' 
            when sm.status_dokumen = 5 then 'Disposisi Selesai' 
            end AS status_dokumen 
                from surat_masuk sm 
                left join disposisi ds on ds.id_surat = sm.id
                left join users ud on ud.id = ds.disposisi_user
                left join users udb on udb.id = ds.disposisi_by
                left join role role_ud on role_ud.id = ud.jabatan
                left join role role_udb on role_udb.id = udb.jabatan
                GROUP BY sm.id
                ORDER BY sm.id DESC";

        $results = app('db')->connection('digital_signature')->select($sql);
        // Debug::dump($results);die();

        $data = [];
        foreach ($results as $k => $v) {

            // Debug::dump($k);die;

            $sql = "select 
            sm.status_dokumen AS status_dokumen2, 
            sm.id AS id_surat, 
            sm.tanggal_terimasurat AS tanggal_terimasurat, 
            sm.perihal_surat AS perihal_surat, 
            sm.filename AS filename, 
            sm.jenis_surat AS jenis_surat, 
            sm.created_time AS tanggal_eksekusi, 
            sm.modified_by AS modified_by, 
            ds.disposisi_user AS disposisi_user, 
            ds.disposisi_id AS disposisi_id, 
            ds.status AS status, 
            ds.created_time AS disposisi_time, 
            ds.disposisi_by AS disposisi_by, 
            role_ud.role_name AS jabatan_diposisi, 
            role_udb.role_name AS jabatan_disposisi_by, 
            role_ud.disposision_level AS disposision_level_disposisi, 
            role_udb.disposision_level AS disposision_level_pendisposisi, 
            CONCAT(
             (SELECT role_name FROM role LEFT JOIN users ON users.role = role.id WHERE users.nip = udb.nip),' - ',
             (udb.nama)
            ) AS nama_pendisposisi,
             CONCAT(
             (SELECT role_name FROM role LEFT JOIN users ON users.role = role.id WHERE users.nip = ud.nip),' - ',
             (ud.nama)
            ) AS nama_disposisi,
            ud.nip AS nip_disposisi, 
            udb.nip AS nip_pendisposisi,
            CONCAT(
              (SELECT role_name FROM role LEFT JOIN users ON users.role = role.id WHERE users.id = sm.created_by),' - ', 
              (SELECT nama FROM users WHERE id = sm.created_by)
            ) AS diperoleh_dari,
            CONCAT(
              (SELECT role_name FROM role ORDER BY disposision_level DESC LIMIT 1),' - ', 
              (SELECT nama FROM users LEFT JOIN role ON role.id = users.role ORDER BY role.disposision_level DESC LIMIT 1)
            ) AS baru_masuk,
            ds.dokumen_status,
            ds.modified_time,
            case 
              when sm.status_dokumen = 0 then 'Outstanding'
              when sm.status_dokumen = 1 then 'Di Proses' 
              when sm.status_dokumen = 2 then 'Diposisi' 
              when sm.status_dokumen = 3 then 'Selesai Di Proses' 
              when sm.status_dokumen = 4 then 'Disposisi Proses' 
              when sm.status_dokumen = 5 then 'Disposisi Selesai' 
            end AS status_dokumen 
                from surat_masuk sm 
                left join disposisi ds on ds.id_surat = sm.id
                left join users ud on ud.id = ds.disposisi_user
                left join users udb on udb.id = ds.disposisi_by
                left join role role_ud on role_ud.id = ud.jabatan
                left join role role_udb on role_udb.id = udb.jabatan
            WHERE sm.id=:id_surat";

            $detail = app('db')->connection('digital_signature')->select($sql, ['id_surat'=>$v->id_surat]);
            // Debug::dump($detail);die;

            $results[$k]->detail = $detail;

        }

        // Debug::dump($results);die;

        return $results;
    }

}
