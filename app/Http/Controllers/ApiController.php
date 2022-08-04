<?php

namespace App\Http\Controllers;

use Zend\Debug\Debug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Statistik;
use App\Models\Suratmasuk;

class ApiController extends Controller
{
    public function statistik(){

        $data = [
            'suratmasuk' => 0
        ];

        $statistikModel = new Statistik();
        $suratkeluar = $statistikModel->countSuratKeluar();
        $suratkeluar_ttd = 0;
        $suratkeluar_dikembalikan = 0;
        foreach ($suratkeluar as $v) {
            if ($v->status_eksekusi=='Di Tandatangani') { $suratkeluar_ttd +=1; }
            if ($v->status_eksekusi=='Di Kembalikan') { $suratkeluar_dikembalikan +=1; }
        }
        $data['suratmasuk'] = $statistikModel->countSuratMasuk();
        $data['suratkeluar'] = count($suratkeluar);
        $data['disposisi_selesai'] = $statistikModel->countDisposisiSelesai();
        $data['disposisi_proses'] = $statistikModel->countDisposisiProses();
        $data['tidak_disposisi'] = $statistikModel->countTidakDisposisi();
        $data['belum_diproses'] = $statistikModel->countBelumDiproses();
        $data['disposisi'] = $statistikModel->countDisposisi();
        $data['selesai'] = $statistikModel->countSelesai();
        $data['disposisi_belum_proses'] = $statistikModel->countDisposisiBelumProses();
        $data['suratkeluar_ttd'] = $suratkeluar_ttd;
        $data['suratkeluar_dikembalikan'] = $suratkeluar_dikembalikan;
        $data['suratmasuk_kemarin'] = $statistikModel->countSuratMasukKemarin();
        $data['suratmasuk_hariini'] = $statistikModel->countSuratMasukHariini();
        $data['suratkeluar_hariini'] = $statistikModel->countSuratKeluarHariini();
        $data['suratkeluar_kemarin'] = $statistikModel->countSuratKeluarKemarin();
        // Debug::dump($data);die;

        return response()->json($data);





    }
    
    public function smdisposisi(){

        $suratmasukModel = new Suratmasuk();
        $data = $suratmasukModel->disposisi();

        return response()->json($data);
    }
}
