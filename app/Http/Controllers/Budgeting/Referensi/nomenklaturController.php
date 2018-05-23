<?php

namespace App\Http\Controllers\Budgeting\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Program;
use App\Model\Progunit;
use App\Model\Urusan;
use App\Model\Kegiatan;
use App\Model\SKPD;
use App\Model\Satuan;
use App\Model\Outcome;
use App\Model\OutputMaster;
use App\Model\Impact;
use View;
use Carbon;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\Input;
class nomenklaturController extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
    }    

	public function index($tahun,$status){
		$urusan 	= Urusan::where('URUSAN_TAHUN',$tahun)->get();
		$skpd 		= SKPD::where('SKPD_TAHUN',$tahun)->get();
        $satuan     = Satuan::orderBy('SATUAN_NAMA')->get();
    	return View('budgeting.referensi.nomenklatur',['tahun'=>$tahun,'status'=>$status,'urusan'=>$urusan,'skpd'=>$skpd,'satuan'=>$satuan]);
    }

    public function getData($tahun){
    	$data 			= Program::where('PROGRAM_TAHUN',$tahun)
    							->orderBy('URUSAN_ID','PROGRAM_KODE')
    							->get();
        $aksi           = '';
    	$view 			= array();
    	foreach ($data as $data) {
            $aksi       = '<div class="action visible pull-right"><a title="Ubah Capaian" onclick="return showCapaian(\''.$data->PROGRAM_ID.'\')" class="action-edit"><i class="mi-eye"></i></a><a title="Ubah Program" onclick="return ubahProgram(\''.$data->PROGRAM_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a title="Hapus Program" onclick="return hapusProgram(\''.$data->PROGRAM_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
    		array_push($view, array( 'id_program'		=>$data->PROGRAM_ID,
                                     'OPSI'				=>$aksi,
                                     'URUSAN'  			=>$data->urusan->URUSAN_KODE." - ".$data->urusan->URUSAN_NAMA,
                                     'PROGRAM'			=>"<i class='mi-caret-down m-r-sm'></i>".$data->PROGRAM_KODE." - ".$data->PROGRAM_NAMA));
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
    }

	public function getDataDetail(Request $req, $tahun,$status,$id){
        $start = ($req->iDisplayStart == "")? 0 : $req->iDisplayStart;
        $length = ($req->iDisplayLength == "")? 10 : $req->iDisplayLength;
    	$data 			= Kegiatan::where('PROGRAM_ID',$id)
                                    ->limit($length)
                                    ->offset($start)->get();
        $count = Kegiatan::where('PROGRAM_ID',$id)->get()->count();
    	$no 			= 1;
    	$aksi 			= '';
    	$view 			= array();
    	foreach ($data as $data) {
            $aksi 		= '<div class="action visible pull-right"><a title="Cek Rekening" class="action-edit"><i class="icon-bdg_form"></i></a><a onclick="return showIndikatorGiat(\''.$data->KEGIATAN_ID.'\')" title="Ubah Output" class="action-edit"><i class="mi-eye"></i></a><a title="Ubah Kegiatan" onclick="return ubahGiat(\''.$data->KEGIATAN_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a title="Hapus Kegiatan" onclick="return hapusGiat(\''.$data->KEGIATAN_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
    		array_push($view, array( 'KEGIATAN_ID' 		=>$data->KEGIATAN_ID,
    								 'KEGIATAN_KODE'  	=>$data->KEGIATAN_KODE,
                                     'KEGIATAN_NAMA'	=>$data->KEGIATAN_NAMA,
                                     'AKSI'				=>$aksi));
    		$no++;
        }
        $display = $data->count();
		$out = array("iTotalRecords" => intval($display), "iTotalDisplayRecords"  => intval($count),"aaData"=>$view);    	
    	return Response::JSON($out);
    }

    public function getDetail($tahun,$status,$id){
    	$data 			= Program::where('PROGRAM_ID',$id)->get();
        $skpd           = Progunit::where('PROGRAM_ID',$id)->get();
        $view           = "";
        foreach($skpd as $s){
            $view .= "<option value='".$s->SKPD_ID."' selected>".$s->skpd->SKPD_NAMA."</option>";
        }
    	return ['data'=>$data,'skpd'=>$view];
    }

    public function getCapaianKegiatan($tahun,$status,$id){
        $dataCapaian       = OutputMaster::where('KEGIATAN_ID',$id)->get();
        $view               = array();
        foreach ($dataCapaian as $dc) {
            $aksi       = '<div class="action visible pull-right"><a onclick="return editOutput(\''.$dc->OUTPUT_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapusOutput(\''.$dc->OUTPUT_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            array_push($view, array( 'INDIKATOR'        =>'Keluaran',
                                     'TOLAK_UKUR'       =>$dc->OUTPUT_TOLAK_UKUR,
                                     'TARGET'           =>$dc->OUTPUT_TARGET.' '.$dc->satuan->SATUAN_NAMA,
                                     'AKSI'             =>$aksi));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

}
