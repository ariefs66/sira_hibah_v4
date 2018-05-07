<?php

namespace App\Http\Controllers\Monev;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use View;
use Excel;
use App\Model\BLPerubahan;
use App\Model\SKPD;
use App\Model\UserBudget;
Use App\Model\Monev\Monev_Faktor;
Use App\Model\Monev\Monev_Kegiatan;
Use App\Model\Monev\Monev_Log;
Use App\Model\Monev\Monev_Outcome;
Use App\Model\Monev\Monev_Output;
Use App\Model\Monev\Monev_Program;
Use App\Model\Monev\Monev_Realisasi;
Use App\Model\Monev\Monev_Tahapan;
class statistikController extends Controller
{
    public function index($tahun){
    	$program 		= 0;
        $monev_program 	= 0;
        $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
        $skpd_      = array(); 
        $i = 0;
        foreach($skpd as $s){
            $skpd_[$i]   = $s->SKPD_ID;
            $i++;
        }
        if(Auth::user()->level == 8 or Auth::user()->level == 9){
            $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_ID')->get();
        }else{
            $skpd       = SKPD::whereIn('SKPD_ID',$skpd_)->where('SKPD_TAHUN',$tahun)->orderBy('SKPD_ID')->get();
        }
        $view       = array();
        foreach ($skpd as $skpd) {
            $total       = BLPerubahan::Join('REFERENSI.REF_KEGIATAN','DAT_BL_PERUBAHAN.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',$skpd->SKPD_ID)
                        ->groupBy('PROGRAM_NAMA',"REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA")
                        ->selectRaw('SUM("BL_PAGU") AS TOTAL, "PROGRAM_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA"')
                        ->get()->count();
          $monev        = Monev_Program::where('DAT_PROGRAM.SKPD_ID',$skpd->SKPD_ID)->where('PROGRAM_TAHUN',$tahun)
          ->leftJoin('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','DAT_PROGRAM.SKPD_ID');
          array_push($view, array( 'KODE'       =>$skpd->SKPD_ID,
                                   'NAMA'     =>$skpd->SKPD_NAMA,
                                   'TOTAL'    =>$total,
                                   'ISI'      =>$monev->count()));
            $program+=$total;
            $monev_program+=$monev->count();
        }

    	$data 	= [ 'tahun'		=>$tahun,
                    'skpd'        =>$view,
                    'program'        =>$program,
                    'isi'        =>$monev_program,
                    'selisih'        =>$program-$monev_program
                ];
                    
    	return View('monev.ringkasan',$data);
    }
}
