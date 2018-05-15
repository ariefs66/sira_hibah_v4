<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers\Budgeting\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\SKPD;
use App\Model\Subunit;
use App\Model\Tahapan;
use App\Model\BL;
use App\Model\Kunci;
use App\Model\Kunciperubahan;
use App\Model\User;
use App\Model\UserBudget;
use Auth;
use View;
use Carbon;
use Response;
use Illuminate\Support\Facades\Input;

class subunitController extends Controller
{
    public function index($tahun,$status){
    	$skpd = SKPD::where('SKPD_TAHUN',$tahun)->get();
    	return View('budgeting.referensi.subunit',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd]);
    }

    public function getData($tahun,$status){
    	$now        = Carbon\Carbon::now()->format('Y-m-d h:m:s');
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->orderBy('TAHAPAN_ID','desc')->first();
		if(!empty($tahapan)){
			if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
				$thp    = 1;
			}else{
				$thp    = 0;
			}
		}else {
			$thp    = 0;
		}

        $data 			= Subunit::join('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')->where('SUB_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
    	$no 			= 1;
    	$aksi 			= '';
    	$view 			= array();


        foreach ($data as $data) {
            $id         = $data->SUB_ID;

            /*

    		$aksi 		= '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->SKPD_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapus(\''.$data->SKPD_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            
            if($thp == 1 and $kunci == 0){
                if(Auth::user()->level == 8){
                    $aksi    .= '<br><label class="i-switch bg-success m-l-n-xxl m-r-xl m-t-sm pull-right"><input type="checkbox" onchange="return kunciRincianSKPD(\''.$data->SKPD_ID.'\')" id="kuncirincian-'.$data->SKPD_ID.'"><i></i></label>';
                }                
            }else{
                if(Auth::user()->level == 8){
                    $aksi    .= '<br><label class="i-switch bg-danger m-l-n-xxl m-r-xl m-t-sm pull-right"><input type="checkbox" onchange="return kunciRincianSKPD(\''.$data->SKPD_ID.'\')" id="kuncirincian-'.$data->SKPD_ID.'" checked><i></i></label>';
                }             
            }*/
    		array_push($view, array( 'no'				=>$no,
                                     'SUB_TAHUN'        =>$data->SUB_TAHUN,
                                     'SUB_KODE'  		=>$data->SUB_KODE,
                                     'SUB_NAMA'		    =>$data->SUB_NAMA,
                                     'SKPD_KODE'		=>$data->SKPD_KODE,
                                     'SKPD_NAMA'		=>$data->SKPD_NAMA,
                                     'aksi'				=>$aksi));
    		$no++;
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
    }

    public function getDetail($tahun,$status,$id){
    	$data 			= SKPD::where('SKPD_ID',$id)->get();
    	return $data;
    }

    public function submitAdd($tahun,$status){
    	$subunit 		= new Subunit;

		if(!empty(Input::get('tahun'))){
			$tahun = Input::get('tahun');
		}
    	$cekKode 	= Subunit::where('SUB_KODE',Input::get('kode_sub'))
    						->where('SUB_TAHUN',$tahun)
    						->where('SKPD_ID',Input::get('skpd'))
							->value('SUB_KODE');
    	if(empty($cekKode)){
	    	$subunit->SUB_KODE			= Input::get('kode_sub');
	    	$subunit->SUB_NAMA			= Input::get('nama_sub');
	    	$subunit->SUB_TAHUN			= Input::get('tahun');
	    	$subunit->SKPD_ID 			= Input::get('skpd');
			$subunit->save();
	    	return '1';
    	}else{
	    	return '0';
    	}

    }

    public function submitEdit(){
		$kode = SKPD::where('SKPD_KODE',Input::get('kode_skpd'))->where('SKPD_TAHUN',Input::get('tahun'));
    	$cekKode = $kode->value('SKPD_KODE');
		$tahun = $kode->value('SKPD_TAHUN');
		$cekID = $kode->value('SKPD_ID');
    	if(empty($cekKode) || $cekID == Input::get('id_skpd') ){ 
			$nip_lama = SKPD::where('SKPD_ID',Input::get('id_skpd'))->value('SKPD_KEPALA_NIP');
			$tahun_lama = SKPD::where('SKPD_ID',Input::get('id_skpd'))->value('SKPD_TAHUN');
			$nip_bendahara = SKPD::where('SKPD_ID',Input::get('id_skpd'))->value('SKPD_BENDAHARA_NIP');
			$kepala_baru = Input::get('kepala_skpd');
			$bendahara_baru = Input::get('bendahara_skpd');
			if(!empty($nip_lama)&&Input::get('kepala_nip') !== "-"){
				$kepala_asal = User::where('email',$nip_lama);
				if(!empty($kepala_asal)){
					$kepala_baru = User::where('email',Input::get('kepala_nip'));
					if(!empty($kepala_baru)){
						UserBudget::where('USER_ID',$kepala_baru->value('id'))->where('TAHUN',$tahun_lama)->delete();
						UserBudget::where('USER_ID',$kepala_asal->value('id'))->where('SKPD_ID',Input::get('id_skpd'))->where('TAHUN',$tahun_lama)->update(['USER_ID'=>$kepala_baru->value('id'),'TAHUN'=>Input::get('tahun')]);
						User::where('email',Input::get('kepala_nip'))->update(['level'=>2,'mod'=>'00000000000']);
						$kepala_baru = $kepala_baru->value('name');
					}
				}
			}
			if(!empty($nip_bendahara)&&Input::get('bendahara_nip') !== "-"){
				$bendahara_asal = User::where('email',$nip_bendahara);
				if(!empty($bendahara_asal)){
					$bendahara_baru = User::where('email',Input::get('bendahara_nip'));
					if(!empty($bendahara_baru)){
						UserBudget::where('USER_ID',$bendahara_asal->value('id'))->where('SKPD_ID',Input::get('id_skpd'))->where('TAHUN',$tahun_lama)->update(['USER_ID'=>$bendahara_baru->value('id'),'TAHUN'=>Input::get('tahun')]);
						$bendahara_baru = $bendahara_baru->value('name');
					}
				}
			}
			if(empty($kepala_baru)){
				$kepala_baru = Input::get('kepala_skpd');
			}
			if(empty($bendahara_baru)){
				$bendahara_baru = Input::get('bendahara_skpd');
			}
			SKPD::where('SKPD_ID',Input::get('id_skpd'))
			->update(['SKPD_KODE'			=>Input::get('kode_skpd'),
					'SKPD_NAMA'  			=>Input::get('nama_skpd'),
					'SKPD_KEPALA_NIP' 	=>Input::get('kepala_nip'),
					'SKPD_KEPALA'         =>$kepala_baru,
					'SKPD_JABATAN'        =>Input::get('pangkat'),
					'SKPD_ALAMAT' 		=>Input::get('alamat'),
					'SKPD_BENDAHARA_NIP' 	=>Input::get('bendahara_nip'),
					'SKPD_BENDAHARA'      =>$bendahara_baru,
					'SKPD_PAGU' 		    =>Input::get('pagu'),
					'SKPD_TAHUN'			=>Input::get('tahun')]);
			return '1';
    	}else{
	    	return '0';
    	}
    }

    public function delete(){
    	Subunit::where('SUB_ID',Input::get('id_sub'))->delete();
    	return 'Berhasil dihapus!';
    }
}
