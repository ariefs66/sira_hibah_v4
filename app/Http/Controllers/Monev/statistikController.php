<?php

namespace App\Http\Controllers\Monev;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Excel;
use App\Model\BLPerubahan;
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
        $skpd = Monev_Program::select('SKPD_ID')->distinct()->orderBy('SKPD_ID')->get();
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
                                   'NAMA'     =>$monev->value('SKPD_NAMA'),
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
