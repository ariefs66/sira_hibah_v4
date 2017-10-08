<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Carbon;
use Response;
use Auth;
use App\Model\Usulan;
use App\Model\UsulanReses;
use App\Model\Kamus;
use App\Model\UserBudget;
use App\Model\Komponen;
use App\Model\UserReses;
use App\Model\BL;
use App\Model\BLPerubahan;
use App\Model\Rincian;
use App\Model\RincianPerubahan;
use App\Model\Rekening;
use App\Model\Log;
use App\Model\Kegiatan;
use App\Model\Program;
use App\Model\Pekerjaan;
use App\Model\Subunit;
use App\Model\Skpd;
use Illuminate\Support\Facades\Input;
class apiController extends Controller
{
    public function api($tahun,$status){
        $data   = Usulan::where('USULAN_STATUS',1)->select('USULAN_ID')->get();
        return Response::JSON($data);
    }


    public function apiSirup($tahun,$status){
      if($status == 'murni'){
    	$data 	= Rincian::JOIN('BUDGETING.DAT_SUBRINCIAN','DAT_RINCIAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN.SUBRINCIAN_ID')
    				->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
    				->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
    				->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
    				->JOIN('BUDGETING.DAT_BL','DAT_RINCIAN.BL_ID','=','DAT_BL.BL_ID')
    				->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
    				->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
    				->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
    				->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
    				->WHERE('DAT_BL.BL_TAHUN',$tahun)
    				->WHERE('DAT_BL.BL_DELETED',0)
    				->WHERE('DAT_BL.BL_VALIDASI',1)
    				->take('1000')
    				->orderBy('REF_SKPD.SKPD_ID','asc')
    				->get();		
    	}else{
    		$data 	= RincianPerubahan::JOIN('BUDGETING.DAT_SUBRINCIAN_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN_PERUBAHAN.SUBRINCIAN_ID')
    				->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN_PERUBAHAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
    				->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN_PERUBAHAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
    				->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN_PERUBAHAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
    				->JOIN('BUDGETING.DAT_BL_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.BL_ID','=','DAT_BL_PERUBAHAN.BL_ID')
    				->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL_PERUBAHAN.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
    				->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
    				->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL_PERUBAHAN.SUB_ID')
    				->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
    				->WHERE('DAT_BL_PERUBAHAN.BL_TAHUN',$tahun)
    				->WHERE('DAT_BL_PERUBAHAN.BL_DELETED',0)
    				->WHERE('DAT_BL_PERUBAHAN.BL_VALIDASI',1)
    				//->take('1000')
    				->orderBy('REF_SKPD.SKPD_ID','asc')
    				->get();
    	}			

    	$view 			= array();
    	foreach ($data as $data) {
    		array_push($view, array( 'SKPD_NAMA'	 		=>$data->SKPD_NAMA,
    								 'BL_PAGU' 				=>$data->BL_PAGU,
    								 'BL_TAHUN' 			=>$data->BL_TAHUN,
    								 'PROGRAM_NAMA' 		=>$data->PROGRAM_NAMA,
    								 'KEGIATAN_NAMA' 		=>$data->KEGIATAN_NAMA,
    								 'RINCIAN_ID'           =>$data->RINCIAN_ID,
    								 'RINCIAN_VOLUME'       =>$data->RINCIAN_VOLUME,
    								 'RINCIAN_KOEFISIEN'    =>$data->RINCIAN_KOEFISIEN,
    								 'RINCIAN_TOTAL'   	    =>$data->RINCIAN_TOTAL,
    								 'RINCIAN_KETERANGAN'   =>$data->RINCIAN_KETERANGAN,
    								 'SUBRINCIAN_NAMA'      =>$data->SUBRINCIAN_NAMA,
    								 'REKENING_NAMA'        =>$data->REKENING_NAMA,
    								 'KOMPONEN_NAMA'        =>$data->KOMPONEN_NAMA,
    								 'KOMPONEN_HARGA' 		=>$data->KOMPONEN_HARGA,
    								 'KOMPONEN_SATUAN' 		=>$data->KOMPONEN_SATUAN,
    								 'PEKERJAAN_NAMA' 		=>$data->PEKERJAAN_NAMA,
    								 
    		));
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
    }

}
