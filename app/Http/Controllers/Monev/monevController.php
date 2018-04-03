<?php

namespace App\Http\Controllers\Monev;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use View;
use Response;
use Auth;
use DB;
use Carbon;
use App\Model\SKPD;
use App\Model\Program;
use App\Model\Kegiatan;
use App\Model\BL;
use App\Model\BLPerubahan;
use App\Model\Rekening;
use App\Model\Output;
use App\Model\OutputPerubahan;
use App\Model\Outcome;
use App\Model\Realisasi;
use App\Model\Satuan;
use App\Model\UserBudget;


class monevController extends Controller
{
   public function index($tahun){
      $skpd    = SKPD::where('SKPD_TAHUN',$tahun)->get();
      $satuan  = Satuan::All();
	  return View('monev.index',[
      'tahun'     =>$tahun,
      'skpd'      =>$skpd,
      'satuan'    =>$satuan
      ]);

   }

   public function getTriwulan1($tahun){
           $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
          $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->Join('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->Join('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',1)
                        ->groupBy('PROGRAM_NAMA',"REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA")
                        ->selectRaw('SUM("BL_PAGU") AS TOTAL, "PROGRAM_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA"')
                        ->get();

        $view       = array();
        $totPeg      = 0;
        $no=1;
        foreach ($data as $data) {
          array_push($view, array('ID'               =>$no,
                                   'PROGRAM_ID'      =>$data->PROGRAM_ID, 
                                   'PROGRAM'         =>$data->PROGRAM_KODE.'-'.$data->PROGRAM_NAMA, 
                                   'OUTCOME'         =>$data->OUTCOME_TOLAK_UKUR, 
                                   'TARGET'          =>$data->OUTCOME_TARGET.' '.$data->SATUAN_NAMA, 
                                   'KINERJA'         =>'', 
                                   'TOTAL'           =>number_format($data->total,0,'.',',')));
          $no++;
        }

         $out = array("aaData"=>$view, "totPeg"=>$totPeg);      
         return Response::JSON($out);
      }

   public function getTriwulan2($tahun){
           $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
          $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->Join('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->Join('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',1)
                        ->groupBy('PROGRAM_NAMA',"REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA")
                        ->selectRaw('SUM("BL_PAGU") AS TOTAL, "PROGRAM_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA"')
                        ->get();

        $view       = array();
        $totPeg      = 0;
        $no=1;
        foreach ($data as $data) {
          array_push($view, array('ID'               =>$no,
                                   'PROGRAM_ID'      =>$data->PROGRAM_ID, 
                                   'PROGRAM'         =>$data->PROGRAM_KODE.'-'.$data->PROGRAM_NAMA, 
                                   'OUTCOME'         =>$data->OUTCOME_TOLAK_UKUR, 
                                   'TARGET'          =>$data->OUTCOME_TARGET.' '.$data->SATUAN_NAMA, 
                                   'KINERJA'         =>'', 
                                   'TOTAL'           =>number_format($data->total,0,'.',',')));
          $no++;
        }

         $out = array("aaData"=>$view, "totPeg"=>$totPeg);      
         return Response::JSON($out);
      }
      
   public function getTriwulan3($tahun){
           $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
          $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->Join('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->Join('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',1)
                        ->groupBy('PROGRAM_NAMA',"REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA")
                        ->selectRaw('SUM("BL_PAGU") AS TOTAL, "PROGRAM_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA"')
                        ->get();

        $view       = array();
        $totPeg      = 0;
        $no=1;
        foreach ($data as $data) {
          array_push($view, array('ID'               =>$no,
                                   'PROGRAM_ID'      =>$data->PROGRAM_ID, 
                                   'PROGRAM'         =>$data->PROGRAM_KODE.'-'.$data->PROGRAM_NAMA, 
                                   'OUTCOME'         =>$data->OUTCOME_TOLAK_UKUR, 
                                   'TARGET'          =>$data->OUTCOME_TARGET.' '.$data->SATUAN_NAMA, 
                                   'KINERJA'         =>'', 
                                   'TOTAL'           =>number_format($data->total,0,'.',',')));
          $no++;
        }

         $out = array("aaData"=>$view, "totPeg"=>$totPeg);      
         return Response::JSON($out);
      }
      

      public function getTriwulan4($tahun){
           $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
          $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->Join('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->Join('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',1)
                        ->groupBy('PROGRAM_NAMA',"REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA")
                        ->selectRaw('SUM("BL_PAGU") AS TOTAL, "PROGRAM_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA"')
                        ->get();

        $view       = array();
        $totPeg      = 0;
        $no=1;
        foreach ($data as $data) {
          array_push($view, array('ID'               =>$no,
                                   'PROGRAM_ID'      =>$data->PROGRAM_ID, 
                                   'PROGRAM'         =>$data->PROGRAM_KODE.'-'.$data->PROGRAM_NAMA, 
                                   'OUTCOME'         =>$data->OUTCOME_TOLAK_UKUR, 
                                   'TARGET'          =>$data->OUTCOME_TARGET.' '.$data->SATUAN_NAMA, 
                                   'KINERJA'         =>'', 
                                   'TOTAL'           =>number_format($data->total,0,'.',',')));
          $no++;
        }

         $out = array("aaData"=>$view, "totPeg"=>$totPeg);      
         return Response::JSON($out);
      }      

   public function getDetail($tahun){
        $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('BUDGETING.DAT_OUTPUT','DAT_OUTPUT.BL_ID','=','DAT_BL.BL_ID')
                        ->Join('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_OUTPUT.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',1)
                        //->groupBy('KEGIATAN_NAMA',"REF_KEGIATAN.KEGIATAN_ID","KEGIATAN_KODE","OUTPUT_TOLAK_UKUR","OUTPUT_TARGET","SATUAN_NAMA","DAT_BL.BL_ID","BL_PAGU" )
                        ->selectRaw(' "KEGIATAN_NAMA","REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","OUTPUT_TOLAK_UKUR","OUTPUT_TARGET","SATUAN_NAMA","DAT_BL"."BL_ID","BL_PAGU" ')
                        ->get();

        $view       = array();
        $no         = 1;
        $opsi       = '';
        $akb       = '';
        foreach ($data as $data) {
          if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1){
            $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->BL_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapus(\''.$data->BL_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
             $akb = '<div class="action visible pull-right"><a href="/main/'.$tahun.'/belanja-tidak-langsung/akb/" class="action-edit" target="_blank"><i class="mi-edit"></i></a></div>';
          }elseif(Auth::user()->level == 2){
            $opsi = '-';
            $akb = '';
          }
          array_push($view, array( 'NO'       => $no++,
                                   'KEGIATAN'     => $data->KEGIATAN_KODE.'-'.$data->KEGIATAN_NAMA,
                                   'OUTPUT'     => $data->OUTPUT_TOLAK_UKUR,
                                   'TARGET'   => $data->OUTPUT_TARGET.' '.$data->SATUAN_NAMA,
                                   'KINERJA'    => '',
                                   'TOTAL'    => number_format($data->BL_PAGU,0,'.',',')));
        }
         
        $out = array("aaData"=>$view);       
      return Response::JSON($out);
      }   

      public function cetak($tahun){

         return View('monev.pdf',
            [   'tahun'             =>$tahun
            ]);
              
      }
}
