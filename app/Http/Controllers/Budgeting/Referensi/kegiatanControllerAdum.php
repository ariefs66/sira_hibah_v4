<?php

namespace App\Http\Controllers\Budgeting\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Program;
use App\Model\Progunit;
use App\Model\Kegunit;
use App\Model\Urusan;
use App\Model\Kegiatan;
use App\Model\SKPD;
use View;
use Carbon;
use Response;
use DB;
use Illuminate\Support\Facades\Input;
class kegiatanControllerAdum extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function indexReferensi($tahun,$status){
        return View('budgeting.referensi.kegiatan',['tahun'=>$tahun,'status'=>$status]);
    }

    public function getProgram($tahun,$status,$id){
    	$data 	= Program::where('URUSAN_ID',$id)->get();
    	$view   = "";
        foreach($data as $d){
            $view .= "<option value='".$d->PROGRAM_ID."' selected>".$d->PROGRAM_KODE." - ".$d->PROGRAM_NAMA."</option>";
        }
        return $view;
    }

    public function getDetail($tahun,$status,$id){
    	$data 			= Kegiatan::where('KEGIATAN_ID',$id)->get();
        $skpd           = Kegunit::where('KEGIATAN_ID',$id)->get();
        $view           = "";
        foreach($skpd as $s){
            $view .= "<option value='".$s->SKPD_ID."' selected>".$s->skpd->SKPD_NAMA."</option>";
        }
    	return ['data'=>$data,'skpd'=>$view];
    }

    public function submitAdd(){
    	$kode 		= Input::get('program');
    	$no 		= Kegiatan::where('KEGIATAN_TAHUN',Input::get('tahun'))
    						->whereHas('program',function($q) use($kode){
    							$q->where('PROGRAM_KODE',Input::get('program'));
    						})->orderBy('KEGIATAN_KODE','DESC')->value('KEGIATAN_KODE');
    	$kode 		= "";
    	if(empty($no)){
    		$kode 	= '001';
    	}else{
    		$no = $no+1;
    		if($no < 10){
    			$kode 	= '00'.$no;
    		}elseif($no < 100){
    			$kode 	= '0'.$no;
    		}else{
    			$kode 	= $no;
    		}
    	}

    	$program 	= Program::where('PROGRAM_KODE',Input::get('program'))->get();
    	// print_r($program);exit();
    	foreach($program as $p){
    		$kegiatan 						= new Kegiatan;
    		$kegiatan->KEGIATAN_TAHUN       = Input::get('tahun');
            $kegiatan->PROGRAM_ID           = $p->PROGRAM_ID;
            $kegiatan->KEGIATAN_KODE        = $kode;
	    	$kegiatan->KEGIATAN_NAMA        = Input::get('kegiatan');
            $kegiatan->save();
    	}
    	return '1';
    }

    public function submitEdit(){
    	$kegiatan   = new Kegiatan;
        $cek        = Kegiatan::where('KEGIATAN_ID',Input::get('id_giat'))->first();
        $no         = Kegiatan::where('KEGIATAN_TAHUN',Input::get('tahun'))
                            ->where('PROGRAM_ID',Input::get('program')) 
                            ->orderBy('KEGIATAN_KODE','DESC')
                            ->value('KEGIATAN_KODE');
        $kode       = "";
        if($cek['PROGRAM_ID'] == Input::get('program')){
            $kode   = $no;
        }elseif(empty($no)){
            $kode   = '001';
        }else{
            $no = $no+1;
            if($no < 10){
                $kode   = '00'.$no;
            }elseif($no < 100){
                $kode   = '0'.$no;
            }else{
                $kode   = $no;
            }
        }

        Kegiatan::where('KEGIATAN_ID',Input::get('id_giat'))
                ->update(['KEGIATAN_TAHUN'       =>Input::get('tahun'),
                          'KEGIATAN_KODE'        =>$kode,
                          'PROGRAM_ID'           =>Input::get('program'),
                          'KEGIATAN_NAMA'        =>Input::get('kegiatan')]);
        Kegunit::where('KEGIATAN_ID',Input::get('id_giat'))->delete();
            $skpd       = Input::get('skpd');
            if($skpd){
                foreach($skpd as $s){
                    $pd     = new Kegunit;
                    $pd->KEGIATAN_ID     = Input::get('id_giat');
                    $pd->SKPD_ID        = $s;
                    $pd->save();
                }
            }
    		return '1';
    }

    public function delete(){
        Kegiatan::where('KEGIATAN_ID',Input::get('id_giat'))->delete();
        return 'Berhasil dihapus!';
    }
}
