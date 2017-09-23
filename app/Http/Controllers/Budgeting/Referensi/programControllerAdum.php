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
use App\Model\Impact;
use View;
use Carbon;
use Response;
use DB;
use Illuminate\Support\Facades\Input;
class programControllerAdum extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
    }    

	public function index($tahun,$status){
		$program 	= Program::whereIn('PROGRAM_KODE',['01','02','03','04','05','06','07','08','09'])
                            ->where('PROGRAM_TAHUN',$tahun)
							->groupBy('PROGRAM_KODE','PROGRAM_NAMA')
							->select('PROGRAM_KODE','PROGRAM_NAMA')
							->orderBy('PROGRAM_KODE')
							->get();
		$skpd 		= SKPD::where('SKPD_TAHUN',$tahun)->get();
        $satuan     = Satuan::orderBy('SATUAN_NAMA')->get();
    	return View('budgeting.referensi.programadum',['tahun'=>$tahun,'status'=>$status,'program'=>$program,'skpd'=>$skpd,'satuan'=>$satuan]);
    }

    public function getData($tahun){
    	$data 			= Program::where('PROGRAM_TAHUN',$tahun)
                                ->whereIn('PROGRAM_KODE',['01','02','03','04','05','06','07','08','09'])
    							->orderBy('PROGRAM_KODE')
    							->groupBy('PROGRAM_KODE','PROGRAM_NAMA')
    							->select('PROGRAM_KODE','PROGRAM_NAMA')
    							->get();
        $aksi           = '';
    	$view 			= array();
    	foreach ($data as $data) {
    		$aksi 		= '<div class="action visible pull-right"><a onclick="return showCapaian(\''.$data->PROGRAM_KODE.'\')" class="action-edit"><i class="mi-eye"></i></a><a onclick="return ubahProgram(\''.$data->PROGRAM_KODE.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapusProgram(\''.$data->PROGRAM_KODE.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
    		array_push($view, array( 'id_porgram'		=>$data->PROGRAM_KODE,
                                     'OPSI'				=>$aksi,
                                     'PROGRAM'			=>"<i class='mi-caret-down m-r-sm'></i>".$data->PROGRAM_KODE." - ".$data->PROGRAM_NAMA));
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
    }

	public function getDataDetail($tahun,$status,$id){
    	$data 			= Kegiatan::whereHas('program',function($q) use($id){
    		$q->where('PROGRAM_KODE',$id);
    	})->groupBy('KEGIATAN_KODE','KEGIATAN_NAMA')->select('KEGIATAN_KODE','KEGIATAN_NAMA')->get();
    	$no 			= 1;
    	$aksi 			= '';
    	$view 			= array();
    	foreach ($data as $data) {
            // <a onclick="return showIndikatorGiat(\''.$data->KEGIATAN_KODE.'\')" class="action-edit"><i class="mi-eye"></i></a>
    		$aksi 		= '<div class="action visible pull-right"><a onclick="return ubahGiat(\''.$data->KEGIATAN_KODE.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapusGiat(\''.$data->KEGIATAN_KODE.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
    		array_push($view, array( 'KEGIATAN_KODE'  	=>$data->KEGIATAN_KODE,
                                     'KEGIATAN_NAMA'	=>$data->KEGIATAN_NAMA,
                                     'AKSI'				=>$aksi));
    		$no++;
    	}
		$out = array("aaData"=>$view);    	
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

    public function submitAdd(){
    	$program 	= new program;
    	$urusan 	= Urusan::where('URUSAN_TAHUN',Input::get('tahun'))
    						->where('URUSAN_ID',Input::get('urusan'))
    						->value('URUSAN_KODE');
    	$no 		= Program::where('PROGRAM_TAHUN',Input::get('tahun'))
    						->where('URUSAN_ID',Input::get('urusan')) 
    						->orderBy('PROGRAM_KODE','DESC')
    						->value('PROGRAM_KODE');
    	$kode 		= "";
    	if(empty($no)){
    		$kode 	= '01';
    	}else{
    		$no = $no+1;
    		if($no < 10){
    			$kode 	= '0'.$no;
    		}else{
    			$kode 	= $no;
    		}
    	}
    	// print_r($kode);exit();
        
        $cekKode    = Program::where('PROGRAM_NAMA',Input::get('nama_program'))
                            ->where('PROGRAM_TAHUN',Input::get('tahun'))
                            ->where('URUSAN_ID',Input::get('urusan'))
                            ->value('PROGRAM_NAMA');

    	if(empty($cekKode)){
            $program->PROGRAM_TAHUN       = Input::get('tahun');
            $program->URUSAN_ID           = Input::get('urusan');
            $program->PROGRAM_KODE        = $kode;
	    	$program->PROGRAM_NAMA        = Input::get('nama_program');
            $program->save();

            $idprogram  = Program::where('PROGRAM_TAHUN',Input::get('tahun'))
                                    ->where('URUSAN_ID',Input::get('urusan'))
                                    ->where('PROGRAM_KODE',$kode)
                                    ->value('PROGRAM_ID');
            $skpd       = Input::get('skpd');
            foreach($skpd as $s){
                $pd     = new Progunit;
                $pd->PROGRAM_ID     = $idprogram;
                $pd->SKPD_ID        = $s;
                $pd->save();
            }
	    	return '1';
    	}else{
	    	return '0';
    	}
    }

    public function submitEdit($tahun,$status){
        $kode   = Input::get('kode_program');
        Program::where('PROGRAM_KODE',$kode)
                        ->update(['PROGRAM_NAMA'=>Input::get('nama_program')]);
        $idprogram  = Program::where('PROGRAM_KODE',$kode)->select('PROGRAM_ID')->get()->toArray();
        Progunit::whereIn('PROGRAM_ID',$idprogram)->delete();
        $skpd   = SKPD::where('SKPD_TAHUN',$tahun)->get();
        foreach($skpd as $skpd){
            $urusan     = substr($skpd->SKPD_KODE, 0,4);
            $urusan     = Urusan::where('URUSAN_KODE',$urusan)->where('URUSAN_TAHUN',$tahun)->value('URUSAN_ID');
            $program    = Program::where('PROGRAM_KODE',Input::get('kode_program'))->where('PROGRAM_TAHUN',$tahun)->where('URUSAN_ID',$urusan)->value('PROGRAM_ID');
            $progunit   = new Progunit;
            $progunit->PROGRAM_ID   = $program;
            $progunit->SKPD_ID      = $skpd->SKPD_ID;
            $progunit->save();
        }
        return 1;
    }

    public function delete(){
    	Program::where('PROGRAM_ID',Input::get('id_program'))->delete();
    	return 'Berhasil dihapus!';
    }

    public function getCapaian($tahun,$status,$id){
        $dataCapaian       = Outcome::where('PROGRAM_ID',$id)->get();
        $dataHasil         = Impact::where('PROGRAM_ID',$id)->get();
        $view               = array();
        foreach ($dataCapaian as $dc) {
            $aksi       = '<div class="action visible pull-right"><a onclick="return hapusOutcome(\''.$dc->OUTCOME_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            array_push($view, array( 'INDIKATOR'        =>'Capaian',
                                     'TOLAK_UKUR'       =>$dc->OUTCOME_TOLAK_UKUR,
                                     'TARGET'           =>$dc->OUTCOME_TARGET.' '.$dc->satuan->SATUAN_NAMA,
                                     'AKSI'             =>$aksi));
        }
        foreach ($dataHasil as $dh) {
            $aksi       = '<div class="action visible pull-right"><a onclick="return hapusImpact(\''.$dh->IMPACT_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            array_push($view, array( 'INDIKATOR'        =>'Hasil',
                                     'TOLAK_UKUR'       =>$dh->IMPACT_TOLAK_UKUR,
                                     'TARGET'           =>$dh->IMPACT_TARGET.' '.$dh->satuan->SATUAN_NAMA,
                                     'AKSI'             =>$aksi));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function submitCapaian($tahun,$status){
        if(Input::get('tipe') == 'CAPAIAN'){
            $o  = new Outcome;
            $o->PROGRAM_ID  = Input::get('id');
            $o->OUTCOME_TOLAK_UKUR  = Input::get('tolakukur');
            $o->OUTCOME_TARGET      = Input::get('target');
            $o->SATUAN_ID           = Input::get('satuan');
            $o->save();
        }else{
            $o  = new Impact;
            $o->PROGRAM_ID  = Input::get('id');
            $o->IMPACT_TOLAK_UKUR  = Input::get('tolakukur');
            $o->IMPACT_TARGET       = Input::get('target');
            $o->SATUAN_ID           = Input::get('satuan');
            $o->save();            
        }
        return 'Berhasil!';
    }
}
