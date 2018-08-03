<?php

namespace App\Http\Controllers\Integrasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\SyncData\RealisasiSimda;
use App\Model\SKPD;
use App\Model\Tahapan;

class RealisasiBelanjaController extends Controller
{
    public $status;
    public function __construct()
    {
        $this->status = 'pergeseran';
    }
    public function syncRealisasi($tahun, Request $req)
    {
        $realisasi = new RealisasiSimda($tahun);
        $data = $realisasi->sync();
    
        return response()->json($data);
    }

    public function index($tahun, Request $req)
    {
        $status = $this->status;
        $skpd = SKPD::where("SKPD_TAHUN", $tahun)->get();
        return view('integrasi.realisasi.index', compact('tahun', 'status', 'skpd'));
    }

    public function getData($tahun, Request $req)
    {
        $realisasi = new RealisasiSimda($tahun);
        $view = $realisasi->getFromSira($req->skpd_kode, true);
        $count = count($view);
        //$response = array("iTotalRecords"=>10, "iTotalDisplayRecords"  => intval($count), "aaData"=>$view);
        $response = array("aaData"=>$view);
        return response()->json($response);
    }
}
