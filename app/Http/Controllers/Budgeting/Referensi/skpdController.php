<?php

namespace App\Http\Controllers\Budgeting\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\SKPD;
use App\Model\Tahapan;
use App\Model\BL;
use App\Model\Kunci;
use App\Model\Kunciperubahan;
use Auth;
use View;
use Carbon;
use Response;
use Illuminate\Support\Facades\Input;
class skpdController extends Controller
{
    public function index($tahun,$status){
    	return View('budgeting.referensi.skpd',['tahun'=>$tahun,'status'=>$status]);
    }

    public function getData($tahun,$status){
    	$now        = Carbon\Carbon::now()->format('Y-m-d h:m:s');
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->orderBy('TAHAPAN_ID','desc')->first();
        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }

        $data 			= SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
    	$no 			= 1;
    	$aksi 			= '';
    	$view 			= array();


        foreach ($data as $data) {
            $id         = $data->SKPD_ID;

            if($status=='murni'){
                $BL         = Kunci::whereHas('bl',function($q) use($id){
                $q->whereHas('subunit',function($r) use($id){
                    $r->where('SKPD_ID',$id);
                });
                })->selectRaw('DISTINCT("KUNCI_RINCIAN")')->groupBy('KUNCI_RINCIAN')->get();
            }else{
                $BL         = Kunciperubahan::whereHas('bl',function($q) use($id){
                $q->whereHas('subunit',function($r) use($id){
                    $r->where('SKPD_ID',$id);
                });
                })->selectRaw('DISTINCT("KUNCI_RINCIAN")')->groupBy('KUNCI_RINCIAN')->get();
            }
            
            // print_r(count($BL));exit();
            if(count($BL) == 1) $kunci = $BL[0]['KUNCI_RINCIAN'];
            else $kunci = 0;

    		$aksi 		= '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->SKPD_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapus(\''.$data->SKPD_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            
            if($thp == 1 and $kunci == 0){
                if(Auth::user()->level == 8){
                    $aksi    .= '<br><label class="i-switch bg-success m-l-n-xxl m-r-xl m-t-sm pull-right"><input type="checkbox" onchange="return kunciRincianSKPD(\''.$data->SKPD_ID.'\')" id="kuncirincian-'.$data->SKPD_ID.'"><i></i></label>';
                }                
            }else{
                if(Auth::user()->level == 8){
                    $aksi    .= '<br><label class="i-switch bg-danger m-l-n-xxl m-r-xl m-t-sm pull-right"><input type="checkbox" onchange="return kunciRincianSKPD(\''.$data->SKPD_ID.'\')" id="kuncirincian-'.$data->SKPD_ID.'" checked><i></i></label>';
                }             
            }
    		array_push($view, array( 'no'				=>$no,
                                     'SKPD_TAHUN'       =>$data->SKPD_TAHUN,
                                     'SKPD_KODE'  		=>$data->SKPD_KODE,
                                     'SKPD_NAMA'		=>$data->SKPD_NAMA,
                                     'SKPD_KEPALA'		=>$data->SKPD_KEPALA_NIP."<br><p class='text-orange'>".$data->SKPD_KEPALA."</p>",
                                     'SKPD_BENDAHARA'   =>$data->SKPD_BENDAHARA_NIP."<br><p class='text-orange'>".$data->SKPD_BENDAHARA."</p>",
                                     'SKPD_PAGU'	=>"Rp.".number_format($data->SKPD_PAGU,0,'.',','),
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

    public function submitAdd(){
    	$skpd 		= new SKPD;
    	$cekKode 	= SKPD::where('SKPD_KODE',Input::get('kode_skpd'))
    						->where('SKPD_TAHUN',Input::get('tahun'))
    						->value('SKPD_KODE');
    	if(empty($cekKode)){
	    	$skpd->SKPD_KODE			= Input::get('kode_skpd');
	    	$skpd->SKPD_NAMA			= Input::get('nama_skpd');
	    	$skpd->SKPD_KEPALA_NIP		= Input::get('kepala_nip');
	    	$skpd->SKPD_KEPALA 			= Input::get('kepala_skpd');
	    	$skpd->SKPD_BENDAHARA_NIP 	= Input::get('bendahara_nip');
	    	$skpd->SKPD_BENDAHARA 	 	= Input::get('bendahara_skpd');
            $skpd->SKPD_TAHUN           = Input::get('tahun');
            $skpd->SKPD_PANGKAT         = Input::get('pangkat');
            $skpd->SKPD_ALAMAT          = Input::get('alamat');
	    	$skpd->SKPD_PAGU 		 	= Input::get('pagu');
	    	$skpd->save();
	    	return '1';
    	}else{
	    	return '0';
    	}
    }

    public function submitEdit(){
    	$cekKode 	= SKPD::where('SKPD_KODE',Input::get('kode_skpd'))
    						->where('SKPD_TAHUN',Input::get('tahun'))
    						->value('SKPD_KODE');
    	if(empty($cekKode) || $cekKode == Input::get('kode_skpd')){    						
    	SKPD::where('SKPD_ID',Input::get('id_skpd'))
    			->update(['SKPD_KODE'			=>Input::get('kode_skpd'),
    					  'SKPD_NAMA'  			=>Input::get('nama_skpd'),
    					  'SKPD_KEPALA_NIP' 	=>Input::get('kepala_nip'),
                          'SKPD_KEPALA'         =>Input::get('kepala_skpd'),
                          'SKPD_JABATAN'        =>Input::get('pangkat'),
    					  'SKPD_ALAMAT' 		=>Input::get('alamat'),
    					  'SKPD_BENDAHARA_NIP' 	=>Input::get('bendahara_nip'),
                          'SKPD_BENDAHARA'      =>Input::get('bendahara_skpd'),
    					  'SKPD_PAGU' 		    =>Input::get('pagu'),
    					  'SKPD_TAHUN'			=>Input::get('tahun')]);
    		return '1';
    	}else{
	    	return '0';
    	}
    }

    public function delete(){
    	SKPD::where('SKPD_ID',Input::get('id_skpd'))->delete();
    	return 'Berhasil dihapus!';
    }
}
