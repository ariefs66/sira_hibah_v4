<?php

namespace App\Http\Controllers\Budgeting\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Rekening;
use View;
use Carbon;
use Auth;
use Response;
use Illuminate\Support\Facades\Input;
class rekeningController extends Controller
{
    public function index($tahun){
    	return View('budgeting.referensi.rekening',['tahun'=>$tahun]);
    }

    public function getData($tahun){
    	$data 			= Rekening::where('REKENING_TAHUN',$tahun)->orderBy('REKENING_KODE')->get();
    	$no 			= 1;
    	$aksi 			= '';
    	$view 			= array();
    	foreach ($data as $data) {
            if(substr(Auth::user()->mod,4,1) == 1){            
    		    $aksi 		= '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->REKENING_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapus(\''.$data->REKENING_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            }else{
                $aksi       = '-';
            }

            if(substr(Auth::user()->mod,4,1) == 1){
                if($data->REKENING_KUNCI == 1) 
                    $kunci = '<label class="i-switch bg-success m-t-xs m-r"><input type="checkbox" onchange="return buka(\''.$data->REKENING_ID.'\')" id="buka-'.$data->BL_ID.'"><i></i></label>';
                else
                    $kunci = '<label class="i-switch bg-success m-t-xs m-r"><input type="checkbox" onchange="return kunci(\''.$data->REKENING_ID.'\')" id="kunci-'.$data->BL_ID.'" checked><i></i></label>';
            }else{
                if($data->REKENING_KUNCI == 1) $kunci = '<span class="text-danger"><i class="fa fa-lock"></i></span>';
                else $kunci = '<span class="text-success"><i class="fa fa-unlock"></i></span>';
            } 
    		array_push($view, array( 'no'			    =>$no,
                                     'REKENING_KODE'    =>$data->REKENING_KODE,
                                     'REKENING_NAMA'    =>$data->REKENING_NAMA,
                                     'KUNCI'            =>$kunci,
                                     'aksi'			    =>$aksi));
    		$no++;
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
    }

    public function getDetail($tahun,$id){
    	$data 			= Rekening::where('REKENING_ID',$id)->get();
    	return $data;
    }

    public function submitAdd(){
    	$rekening = new Rekening;
    	$cekKode 	= Rekening::where('REKENING_KODE',Input::get('kode_rekening'))
    						->where('REKENING_TAHUN',Input::get('tahun'))
    						->value('REKENING_NAMA');
    	if(empty($cekKode)){
	    	$rekening->REKENING_KODE		= Input::get('kode_rekening');
	    	$rekening->REKENING_TAHUN		= Input::get('tahun');
	    	$rekening->REKENING_NAMA		= Input::get('nama_rekening');
	    	$rekening->save();
	    	return '1';
    	}else{
	    	return '0';
    	}
    }

    public function submitEdit(){
    	$cekKode 	= Rekening::where('REKENING_KODE',Input::get('kode_rekening'))
    						->where('REKENING_TAHUN',Input::get('tahun'))
    						->value('REKENING_KODE');
    	if(empty($cekKode) || $cekKode == Input::get('kode_rekening')){    						
    	Rekening::where('REKENING_ID',Input::get('id_rekening'))
    			->update(['REKENING_KODE'=>Input::get('kode_rekening'),
    					  'REKENING_TAHUN'=>Input::get('tahun'),
    					  'REKENING_NAMA'=>Input::get('nama_rekening')]);
    		return '1';
    	}else{
	    	return '0';
    	}
    }

    public function delete(){
    	Rekening::where('REKENING_ID',Input::get('id_rekening'))->delete();
    	return 'Berhasil dihapus!';
    }

    public function kunci($tahun,$kunci){
        Rekening::where('REKENING_ID',Input::get('REKENING_ID'))->update(['REKENING_KUNCI'=>$kunci]);
        return 'Berhasil !';
    }
}
