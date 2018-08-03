<?php

namespace App\Http\Controllers\Budgeting\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\TTD;
use App\Model\SKPD;
use App\Model\TahunAnggaran;
use App\Model\UserBudget;
use View;
use Carbon;
use Excel;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\Input;
class ttdController extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
    }    

	public function index($tahun,$status){
		$urusan 	= '';
        $i = 0;
        $tahunanggaran = TahunAnggaran::orderBy('TAHUN','asc')->orderBy('ID','asc')->get();
		$rekening 	= '';
        $satuan     = '';
    	return View('budgeting.referensi.ttd',['tahun'=>$tahun,'status'=>$status,'rekening'=>$rekening,'urusan'=>$urusan,'tahunanggaran'=>$tahunanggaran,'satuan'=>$satuan]);
    }

    public function getData($tahun, $status, Request $req){
        $start = ($req->iDisplayStart == "")? 0 : $req->iDisplayStart;
        $length = ($req->iDisplayLength == "")? 0 : $req->iDisplayLength;
        $tahapan        = TahunAnggaran::where('TAHUN',$tahun)->where('STATUS',$status)->first();
        if($length>0){
            $data 			= TTD::where('TAHUN_ANGGARAN_ID',$tahapan->ID)->limit($length)->offset($start)->get();
        }else{
            $data 			= TTD::where('TAHUN_ANGGARAN_ID',$tahapan->ID)->get();
        }
        $aksi           = '';
    	$view 			= array();
    	foreach ($data as $data) {
            if(Auth::user()->level == 8){
                $aksi       = '<div class="action visible pull-right"><a title="Ubah" onclick="return ubahTTD(\''.$data->TTD_ID.'\')" class="action-edit"><i class="fa fa-bookmark-o"></i></a><a title="Hapus Program" onclick="return hapusTTD(\''.$data->TTD_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            }else{
                 $aksi = '';
            }
            $tgl        = Carbon\Carbon::createFromFormat('Y-m-d', $data->VALUE)->format('d');
            $gbln       = Carbon\Carbon::createFromFormat('Y-m-d', $data->VALUE)->format('m');
            $bln        = $this->bulan($gbln*1);
            $thn        = Carbon\Carbon::createFromFormat('Y-m-d', $data->VALUE)->format('Y');
            $tgl_ttd    = $tgl . ' ' . $bln . ' ' . $thn;
    		array_push($view, array( 'id_ttd'		=>$data->TTD_ID,
                                     'OPSI'				=>$aksi,
                                     'DATA'				=>'Tanggal: '. $tgl_ttd.'<br/>Jabatan: ' . $data->JABATAN.'<br/>Nama: '.$data->NAMA_PEJABAT.'<br/>NIP: '.$data->NIP_PEJABAT,
                                     'TAHUN'			=>$tahapan->TAHUN . '-' . $tahapan->STATUS,
                                     'KEY'				=>$data->KEY,
                                     'PROGRAM'			=>"<i class='mi-caret-down m-r-sm'></i>".$data->PROGRAM_KODE." - ".$data->PROGRAM_NAMA));
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
    }

    public function getDetail($tahun,$status,$id){
        $data 			= TTD::where('TTD_ID',$id)->get();
    	return ['data'=>$data];
    }

    

    public function submitRekGiat($tahun,$status){
        $o  = new Rekgiat;
        $o->REKGIAT_ID           = Rekgiat::max('REKGIAT_ID')+1;
        $o->KEGIATAN_ID         = Input::get('idkegiatan');
        $o->REKENING_ID   = Input::get('idrekening');
        $o->TAHUN       = $tahun;
        $o->save();            
        return 'Berhasil!';
    }

    public function hapusRekGiat($tahun,$status){
        Rekgiat::where('REKGIAT_ID',Input::get('id'))->delete();
        return 'Berhasil!';
    }

    public function bulan($i){
        $bulan  = [
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];
        return $bulan[$i];
    }
}





