<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Zend\Debug\Debug;

class Statistik extends Model
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

    public function countSuratKeluar(){

        // Debug::dump(app('db')->connection('digital_signature'));


        $params = [];
        $sql = "SELECT
                sign.alasan_revisi AS alasan_revisi,
                sign.id_surat AS id_surat,
                sign.atribut AS atribut,
                sign.eksekutor AS eksekutor,
                sign.annotation_field AS annotation_field,
                sign.level_eksekusi AS level_eksekusi,
                sign.status AS status,
                sign.created_time AS created_time,
                sign.lastmodified_time AS lastmodified_time,
                sign.filename AS filename,
                sign.judul AS judul,
                sign.perihal AS perihal,
                CASE WHEN sign.status_eksekusi IS NULL THEN 'Di Proses' ELSE sign.status_eksekusi
            END AS status_eksekusi,
            sign.status_level AS status_level,
            users.nama AS nama_eksekutor,
            users.jabatan AS jabatan,
            users.nip AS nip,
            CASE WHEN sign.status_level > sign.level_eksekusi AND sign.status_level <= 3 THEN 'Selesai' WHEN sign.status_level = sign.level_eksekusi THEN 'Di Proses' WHEN sign.status_level < sign.level_eksekusi THEN 'Menunggu Paraf' WHEN sign.status_level = 5 THEN 'Di Kembalikan'
            END AS eksekusi
            FROM document_sign sign
            LEFT JOIN users ON users.id = sign.eksekutor
            GROUP BY sign.filename";

        $results = app('db')->connection('digital_signature')->select($sql);
        // Debug::dump($results);die();

        return $results;
    }

    public function countSuratMasuk(){
        $results = app('db')->connection('digital_signature')->select("SELECT COUNT(*) AS total FROM surat_masuk");
        return $results[0]->total??0;
    }

    public function countDisposisiSelesai(){
        $results = app('db')->connection('digital_signature')->select("SELECT COUNT(*) AS total from surat_masuk WHERE status_dokumen = 5");
        return $results[0]->total??0;
    }

    public function countDisposisiProses(){
        $results = app('db')->connection('digital_signature')->select("SELECT COUNT(*) AS total from surat_masuk WHERE status_dokumen = 1");
        return $results[0]->total??0;
    }

    public function countTidakDisposisi(){
        $results = app('db')->connection('digital_signature')->select("SELECT id from surat_masuk
        LEFT JOIN disposisi ON disposisi.id_surat = surat_masuk.id
        WHERE disposisi_user is null and approve is not null");
        return $results[0]->total??0;
    }

    public function countBelumDiproses(){
        $results = app('db')->connection('digital_signature')->select("SELECT count(*) as total from surat_masuk WHERE status_dokumen = 0");
        return $results[0]->total??0;
    }

    public function countDisposisi(){
        $results = app('db')->connection('digital_signature')->select("SELECT count(*) as total from surat_masuk WHERE status_dokumen = 2");
        return $results[0]->total??0;
    }

    public function countSelesai(){
        $results = app('db')->connection('digital_signature')->select("SELECT COUNT(*) AS total from surat_masuk WHERE status_dokumen = 3");
        return $results[0]->total??0;
    }

    public function countDisposisiBelumProses(){
        $results = app('db')->connection('digital_signature')->select("SELECT count(*) as total from disposisi WHERE status = 0");
        return $results[0]->total??0;
    }

    public function countSuratMasukKemarin(){
        $results = app('db')->connection('digital_signature')->select("SELECT COUNT(*) AS total FROM surat_masuk WHERE DATE(created_time) = (SELECT DATE(DATE_SUB(CURDATE(), INTERVAL 1 DAY_HOUR)))");
        return $results[0]->total??0;
    }

    public function countSuratMasukHariini(){
        $results = app('db')->connection('digital_signature')->select("SELECT COUNT(*) AS total FROM surat_masuk WHERE DATE(created_time) = DATE(CURDATE())");
        return $results[0]->total??0;
    }

    public function countSuratKeluarHariini(){
        $results = app('db')->connection('digital_signature')->select("SELECT COUNT(*) AS total FROM document_sign WHERE DATE(created_time) = DATE(CURDATE())
        group by filename");
        return $results[0]->total??0;
    }

    public function countSuratKeluarKemarin(){
        $results = app('db')->connection('digital_signature')->select("SELECT COUNT(*) AS total FROM document_sign WHERE DATE(created_time) = (SELECT DATE(DATE_SUB(CURDATE(), INTERVAL 1 DAY_HOUR)))
        group by filename");
        return $results[0]->total??0;
    }
}
