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
use App\Model\Staff;
use App\Model\Subunit;
use App\Model\Program;
use App\Model\Kegiatan;
use App\Model\Monev\Monev_Kegiatan;
use App\Model\Monev\Monev_Program;
use App\Model\Monev\Monev_Realisasi;
use App\Model\Monev\Monev_Faktor;
use App\Model\Monev\Monev_Outcome;
use App\Model\Monev\Monev_Output;
use App\Model\Monev\Monev_Tahapan;
use App\Model\Monev\Monev_Log;
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
   public function __construct(){
    $this->middleware('auth');
  }
   public function index($tahun){
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
      $satuan  = Satuan::All();
      $tahapan    = Monev_Tahapan::where('TAHAPAN_TAHUN',$tahun)->first();
      if($tahapan){
        if($tahapan->TAHAPAN_T1==1){
          $triwulan = 1;
        }elseif($tahapan->TAHAPAN_T2==1){
          $triwulan = 2;
        }elseif($tahapan->TAHAPAN_T3==1){
          $triwulan = 3;
        }else{
          $triwulan = 4;
        }
      }else{
        $triwulan = 0;
      }
      $cek    = Monev_Faktor::where('TAHUN',$tahun)
      ->where('T',$triwulan);
      if(Auth::user()->level == 8 || Auth::user()->level == 9 || Auth::user()->mod == '01000000000'){
        $cek = $cek->first();
      }else{
        $id = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');
        $cek = $cek->where('SKPD_ID',$id)->first();
      }
      if($cek){
        if($cek->STATUS==2) {
            if(Auth::user()->level == 8 || Auth::user()->level == 9 || Auth::user()->mod == '01000000000'){
              $input ='disabled';
              $validasi ='disabled';
            }else{
              $input ='disabled';
              $validasi ='disabled';
            }
        }else{
          if(Auth::user()->level == 8 || Auth::user()->level == 9 || Auth::user()->mod == '01000000000'){
            $input ='';
            $validasi ='disabled';
          }else{
            $input ='disabled';
            $validasi ='';
          }
        }
        $cek = TRUE;
        $input ='disabled';
        $validasi ='disabled';
      }else {
        $cek = FALSE;
        $input     ='';
        $validasi ='';
      }
	  return View('monev.index',[
      'tahun'     =>$tahun,
      'skpd'      =>$skpd,
      'satuan'    =>$satuan,
      'input'     =>$input,
      'validasi'     =>$validasi,
      'cek'       =>$cek,
      'mode'      =>$triwulan,
      'triwulan1' =>($tahapan->TAHAPAN_T1==1?'active':''),
      'triwulan2' =>($tahapan->TAHAPAN_T2==1?'active':''),
      'triwulan3' =>($tahapan->TAHAPAN_T3==1?'active':''),
      'triwulan4' =>($tahapan->TAHAPAN_T4==1?'active':''),
      ]);
   }

   public function getTriwulan1($tahun,$filter){
          if(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->mod == '01000000000'){
            $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',$filter)
                        ->groupBy('PROGRAM_NAMA',"REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA")
                        ->selectRaw('SUM("BL_PAGU") AS TOTAL, "PROGRAM_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA"')
                        ->get();
          }else{
            $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
            $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',$skpd)
                        ->groupBy('PROGRAM_NAMA',"REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA")
                        ->selectRaw('SUM("BL_PAGU") AS TOTAL, "PROGRAM_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA"')
                        ->get();
          }
          

        $view       = array();
        $totPeg      = 0;
        $no=1;
        foreach ($data as $data) {
          $monev        = Monev_Program::where('REF_PROGRAM_ID',$data->PROGRAM_ID)->where('PROGRAM_TAHUN',$tahun)
                          ->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_PROGRAM.SATUAN');
          array_push($view, array('ID'               =>$no,
                                   'PROGRAM_ID'      =>$data->PROGRAM_ID, 
                                   'MODE'            =>1, 
                                   'PROGRAM'         =>$data->PROGRAM_KODE.'-'.$data->PROGRAM_NAMA, 
                                   'PROGRAM_KODE'    =>$data->PROGRAM_KODE, 
                                   'OUTCOME'         =>$data->OUTCOME_TOLAK_UKUR, 
                                   'TARGET'          =>$data->OUTCOME_TARGET.' '.$data->SATUAN_NAMA, 
                                   'KINERJA'         =>$monev->value('PROGRAM_T1').' '.$monev->value('SATUAN_NAMA'), 
                                   'STATUS'         =>'', 
                                   'TOTAL'           =>number_format($data->total,0,'.',',')));
          $no++;
        }

         $out = array("aaData"=>$view, "totPeg"=>$totPeg);      
         return Response::JSON($out);
      }

   public function getTriwulan2($tahun, $filter){
          if(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->mod == '01000000000'){  
            $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',$filter)
                        ->groupBy('PROGRAM_NAMA',"REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA")
                        ->selectRaw('SUM("BL_PAGU") AS TOTAL, "PROGRAM_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA"')
                        ->get();
          }else{
            $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
            $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',$skpd)
                        ->groupBy('PROGRAM_NAMA',"REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA")
                        ->selectRaw('SUM("BL_PAGU") AS TOTAL, "PROGRAM_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA"')
                        ->get();
          }

        $view       = array();
        $totPeg      = 0;
        $no=1;
        foreach ($data as $data) {
          $monev        = Monev_Program::where('REF_PROGRAM_ID',$data->PROGRAM_ID)->where('PROGRAM_TAHUN',$tahun)
                          ->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_PROGRAM.SATUAN');
         array_push($view, array('ID'               =>$no,
                                   'PROGRAM_ID'      =>$data->PROGRAM_ID, 
                                   'MODE'      =>2, 
                                   'PROGRAM'         =>$data->PROGRAM_KODE.'-'.$data->PROGRAM_NAMA, 
                                   'PROGRAM_KODE'    =>$data->PROGRAM_KODE, 
                                   'OUTCOME'         =>$data->OUTCOME_TOLAK_UKUR, 
                                   'TARGET'          =>$data->OUTCOME_TARGET.' '.$data->SATUAN_NAMA, 
                                   'KINERJA'         =>$monev->value('PROGRAM_T2').' '.$monev->value('SATUAN_NAMA'), 
                                   'STATUS'         =>'', 
                                   'TOTAL'           =>number_format($data->total,0,'.',',')));
          $no++;
        }

         $out = array("aaData"=>$view, "totPeg"=>$totPeg);      
         return Response::JSON($out);
      }
      
   public function getTriwulan3($tahun, $filter){
        if(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->mod == '01000000000'){
            $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',$filter)
                        ->groupBy('PROGRAM_NAMA',"REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA")
                        ->selectRaw('SUM("BL_PAGU") AS TOTAL, "PROGRAM_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA"')
                        ->get();
          }else{
            $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
            $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',$skpd)
                        ->groupBy('PROGRAM_NAMA',"REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA")
                        ->selectRaw('SUM("BL_PAGU") AS TOTAL, "PROGRAM_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA"')
                        ->get();
          }

        $view       = array();
        $totPeg      = 0;
        $no=1;
        foreach ($data as $data) {
          $monev        = Monev_Program::where('REF_PROGRAM_ID',$data->PROGRAM_ID)->where('PROGRAM_TAHUN',$tahun)
                          ->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_PROGRAM.SATUAN');
          array_push($view, array('ID'               =>$no,
                                   'PROGRAM_ID'      =>$data->PROGRAM_ID, 
                                   'MODE'            =>3, 
                                   'PROGRAM'         =>$data->PROGRAM_KODE.'-'.$data->PROGRAM_NAMA, 
                                   'PROGRAM_KODE'    =>$data->PROGRAM_KODE, 
                                   'OUTCOME'         =>$data->OUTCOME_TOLAK_UKUR, 
                                   'TARGET'          =>$data->OUTCOME_TARGET.' '.$data->SATUAN_NAMA, 
                                   'KINERJA'         =>$monev->value('PROGRAM_T2').' '.$monev->value('SATUAN_NAMA'), 
                                   'STATUS'         =>'', 
                                   'TOTAL'           =>number_format($data->total,0,'.',',')));
          $no++;
        }

         $out = array("aaData"=>$view, "totPeg"=>$totPeg);      
         return Response::JSON($out);
      }
      

      public function getTriwulan4($tahun, $filter){
        if(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->mod == '01000000000'){
            $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',$filter)
                        ->groupBy('PROGRAM_NAMA',"REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA")
                        ->selectRaw('SUM("BL_PAGU") AS TOTAL, "PROGRAM_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA"')
                        ->get();
          }else{
            $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
            $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',$skpd)
                        ->groupBy('PROGRAM_NAMA',"REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA")
                        ->selectRaw('SUM("BL_PAGU") AS TOTAL, "PROGRAM_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET","SATUAN_NAMA"')
                        ->get();
          }

        $view       = array();
        $totPeg      = 0;
        $no=1;
        foreach ($data as $data) {
          $monev        = Monev_Program::where('REF_PROGRAM_ID',$data->PROGRAM_ID)->where('PROGRAM_TAHUN',$tahun)
          ->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_PROGRAM.SATUAN');
          array_push($view, array('ID'               =>$no,
                                   'PROGRAM_ID'      =>$data->PROGRAM_ID, 
                                   'MODE'      =>4, 
                                   'PROGRAM'         =>$data->PROGRAM_KODE.'-'.$data->PROGRAM_NAMA, 
                                   'PROGRAM_KODE'    =>$data->PROGRAM_KODE, 
                                   'OUTCOME'         =>$data->OUTCOME_TOLAK_UKUR, 
                                   'TARGET'          =>$data->OUTCOME_TARGET.' '.$data->SATUAN_NAMA, 
                                   'KINERJA'         =>$monev->value('PROGRAM_T2').' '.$monev->value('SATUAN_NAMA'), 
                                   'STATUS'         =>'',   
                                   'TOTAL'           =>number_format($data->total,0,'.',',')));
          $no++;
        }

         $out = array("aaData"=>$view, "totPeg"=>$totPeg);      
         return Response::JSON($out);
      }      

   public function getData($tahun, $skpd, $mode=1, $id){
    if(empty($skpd)){
      $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
    }
        $data       = BLPerubahan::Join('REFERENSI.REF_KEGIATAN','DAT_BL_PERUBAHAN.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_SKPD','DAT_BL_PERUBAHAN.SKPD_ID','=','REF_SKPD.SKPD_ID')
                        ->leftJoin('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL_PERUBAHAN.SUB_ID')
                        ->leftJoin('REFERENSI.REF_URUSAN_SKPD','DAT_BL_PERUBAHAN.SKPD_ID','=','REF_URUSAN_SKPD.SKPD_ID')
                        ->leftJoin('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_URUSAN_SKPD.URUSAN_ID')
                        ->LeftJoin('BUDGETING.DAT_BL_REALISASI','DAT_BL_REALISASI.BL_ID','=','DAT_BL_PERUBAHAN.BL_ID')
                        ->groupBy('KEGIATAN_NAMA','REF_KEGIATAN.KEGIATAN_ID','REF_SKPD.SKPD_ID','REF_SUB_UNIT.SUB_ID','REF_URUSAN.URUSAN_KODE','KEGIATAN_KODE','REF_PROGRAM.PROGRAM_ID','DAT_BL_PERUBAHAN.BL_ID','BL_PAGU')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('REF_SUB_UNIT.SKPD_ID',$skpd)
                        ->where('DAT_BL_PERUBAHAN.BL_ID',$id)
                        ->select(DB::raw('SUM("DAT_BL_REALISASI"."REALISASI_TOTAL"), "DAT_BL_PERUBAHAN".*, "REF_URUSAN"."URUSAN_KODE", "REF_SUB_UNIT"."SUB_ID","REF_SUB_UNIT"."SUB_KODE", "REF_SUB_UNIT"."SUB_NAMA", "REF_SKPD"."SKPD_ID","REF_SKPD"."SKPD_KODE", "REF_KEGIATAN".*, "REF_PROGRAM".*'))
                        ->get();

        $view       = array();
        $no         = 1;
        $opsi       = '';
        $akb       = '';
        foreach ($data as $data) {
            
          $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->BL_ID.'\')" class="action-edit open-form-btl"><i class="mi-edit"></i></a></div>';
          $akb = '<div class="action visible pull-right"><a href="/main/'.$tahun.'/belanja-tidak-langsung/akb/" class="action-edit" target="_blank"><i class="mi-edit"></i></a></div>';
          $monev_keg  = Monev_Kegiatan::where('REF_KEGIATAN_ID',$data->KEGIATAN_ID)->where('SUB_ID',$data->SUB_ID)->where('SKPD_ID',$data->SKPD_ID)->first();
        
          if($monev_keg){
            $kegiatanid = $monev_keg->KEGIATAN_ID;
            $kinerja = 'KEGIATAN_T'.$mode;
            $penghambat = 'KEGIATAN_PENGHAMBAT_T'.$mode;
            $pendukung = 'KEGIATAN_PENDUKUNG_T'.$mode;
            $kinerja = $monev_keg->$kinerja;
            $penghambat = $monev_keg->$penghambat;
            $pendukung = $monev_keg->$pendukung;
            $monev_keg2  = Monev_Kegiatan::where('REF_KEGIATAN_ID',$data->KEGIATAN_ID)->where('SUB_ID',$data->SUB_ID)->where('SKPD_ID',$data->SKPD_ID)->orderBy('KEGIATAN_ID','DESC')->get();
            if($monev_keg2){
              $kinerja = '';
              $kinerjap='KEGIATAN_T'.$mode;
              foreach ($monev_keg2 as $monev_keg2) {
                $kinerja = $monev_keg2->$kinerjap."," . $kinerja;
              }
            }
          }else{
            $kegiatanid = "";
            $kinerja = "";
            $penghambat = "";
            $pendukung = "";
            $satuan = "";
          }
          $sasaran="";
          $target="";
          $tolak_ukur="";
          $satuan="";
          $satuan_nama="";
          //$monev_output  = Output::where('BL_ID',$id)->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_OUTPUT.SATUAN_ID')->get();
          //if($monev_output){
          //}else{
            $monev_output  = OutputPerubahan::where('BL_ID',$id)->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_OUTPUT_PERUBAHAN.SATUAN_ID')->get();
          //}
          $total = 0;
          foreach ($monev_output as $monev_output) {
            $sasaran = $sasaran . $monev_output->OUTPUT_TOLAK_UKUR ." : ". $monev_output->OUTPUT_TARGET . " ". $monev_output->SATUAN_NAMA . "\r\n" ;
            $target =  $target . $monev_output->OUTPUT_TARGET.",";
            $tolak_ukur =  $tolak_ukur . $monev_output->OUTPUT_TOLAK_UKUR.",";
            $satuan =  $satuan . $monev_output->SATUAN_ID.",";
            $satuan_nama =  $satuan_nama . $monev_output->SATUAN_NAMA.",";
            $total = $total + 1;
          }
          $monev_realisasi  = Monev_Realisasi::where('KEGIATAN_ID',$data->KEGIATAN_ID)->where('PROGRAM_ID',$data->PROGRAM_ID)->first();
          if($monev_realisasi){
            $realisasi = 'REALISASI_T'.$mode;
            $realisasi = $monev_realisasi->$realisasi;
          }else{
            $realisasi = ($data->sum>0?$data->sum:0);
          }

          array_push($view, array( 'KEGIATAN_ID'       => $data->KEGIATAN_ID,
                                   'PROGRAM_ID'       => $data->PROGRAM_ID,
                                   'PROGRAM_NAMA'       => $data->URUSAN_KODE.'.'.$data->SKPD_KODE.'.'.$data->SUB_KODE.'.'.$data->PROGRAM_KODE.' - '.$data->PROGRAM_NAMA,
                                   'PROGRAM_KODE'       => $data->PROGRAM_KODE,
                                   'SUB_ID'       => $data->SUB_ID,
                                   'KEGIATAN_KODE'       => $data->KEGIATAN_KODE,
                                   'KEGIATAN_NAMA'       => $data->URUSAN_KODE.'.'.$data->SKPD_KODE.'.'.$data->SUB_KODE.'.'.$data->PROGRAM_KODE.'.'.$data->KEGIATAN_KODE.' - '.$data->KEGIATAN_NAMA.' - '.$data->SUB_NAMA,
                                   'KEGIATAN_ANGGARAN'       => $data->BL_PAGU,
                                   'REALISASI'       => $realisasi,
                                   'TARGET'       => $target,
                                   'TOLAK_UKUR'       => $tolak_ukur,
                                   'SATUAN'       => $satuan_nama,
                                   'OUTPUT'       => $sasaran,
                                   'TOTAL'             => $total,
                                   'MODE'       => $mode,
                                   'ID'       => $kegiatanid,
                                   'SKPD_ID'       => $skpd,
                                   'KINERJA'       => $kinerja,
                                   'KEGIATAN_PENDUKUNG'       => $pendukung,
                                   'SATUAN_ID'       => $satuan,
                                   'KEGIATAN_PENGHAMBAT'       => $penghambat));
        }
         
        $out = array("aaData"=>$view);       
      return Response::JSON($out);
      }   

    public function hapusKegiatan($tahun, $id){
      $prog = 0;
      $keg = Monev_Kegiatan::where('REF_KEGIATAN_ID',Input::get('KEGIATAN_ID'))->where('SUB_ID',$id)->where('SKPD_ID',Input::get('SKPD_ID'))->get();
      foreach ($keg as $keg) {
        $prog = $keg->PROGRAM_ID;
        $log                = new Monev_Log;
        $log->LOG_TIME      = Carbon\Carbon::now();
        $log->USER_ID       = Auth::user()->id;
        $log->LOG_ACTIVITY  = 'Menghapus Kegiatan';
        $log->LOG_DETAIL    = 'ARSIP#'.$keg->KEGIATAN_ID.'#'.$keg->PROGRAM_ID.'#'.$keg->KEGIATAN_KODE.'#'.$keg->KEGIATAN_NAMA.'#'.$keg->KEGIATAN_ANGGARAN.'#'.$keg->KEGIATAN_T1.'#'.$keg->KEGIATAN_T2.'#'.$keg->KEGIATAN_T3.'#'.$keg->KEGIATAN_T4.'#'.$keg->USER_CREATED.'#'.$keg->TIME_CREATED.'#'.$keg->USER_UPDATED.'#'.$keg->TIME_UPDATED.'#'.$keg->KEGIATAN_VALIDASI.'#'.$keg->KEGIATAN_INPUT.'#'.$keg->KEGIATAN_PENDUKUNG_T1.'#'.$keg->KEGIATAN_PENGHAMBAT_T1.'#'.$keg->KEGIATAN_PENDUKUNG_T2.'#'.$keg->KEGIATAN_PENGHAMBAT_T2.'#'.$keg->KEGIATAN_PENDUKUNG_T3.'#'.$keg->KEGIATAN_PENGHAMBAT_T3.'#'.$keg->KEGIATAN_PENDUKUNG_T4.'#'.$keg->KEGIATAN_PENGHAMBAT_T4.'#'.$keg->SATUAN.'#'.$keg->SKPD_ID.'#'.$keg->REF_KEGIATAN_ID.'#'.$keg->SUB_ID.'#';
        $log->save();
        Monev_Kegiatan::where('REF_KEGIATAN_ID',$keg->REF_KEGIATAN_ID)->where('SKPD_ID',$keg->SKPD_ID)->where('SUB_ID',$keg->SUB_ID)->delete();
        Monev_Output::where('KEGIATAN_ID',$keg->KEGIATAN_ID)->delete();
        Monev_Realisasi::where('KEGIATAN_ID',$keg->KEGIATAN_ID)->delete();
      }
      $prog = Monev_Program::where('PROGRAM_ID',$prog)->where('PROGRAM_TAHUN',$tahun)->first();
      if($prog){
        //Monev_Outcome::where('PROGRAM_ID',$prog->REF_PROGRAM_ID)->delete();
        Monev_Program::where('PROGRAM_ID',$prog->PROGRAM_ID)->update(['PROGRAM_T1'=>0,'PROGRAM_T2'=>0,'PROGRAM_T3'=>0,'PROGRAM_T4'=>0]);
      }else{
        return "Hapus Gagal!";
      }
      return "Hapus Berhasil!";
  }

    public function simpanKegiatan($tahun,$mode=1){
      $mode = Input::get('MODE');
      $acara = "Aksi Tidak Dikenal";
      $kinerja = 'KEGIATAN_T'.$mode;
      $pendukung = 'KEGIATAN_PENDUKUNG_T'.$mode;
      $penghambat = 'KEGIATAN_PENGHAMBAT_T'.$mode;
      $kinerjap = 'PROGRAM_T'.$mode;
      $pendukungp = 'PROGRAM_PENDUKUNG_T'.$mode;
      $penghambatp = 'PROGRAM_PENGHAMBAT_T'.$mode;
      $before = 0;
      $counter = 0;
      $id = Input::get('KEGIATAN_ID');
      $skpd = Input::get('SKPD_ID');
      if(empty($skpd)){
        $skpd = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
      }
     
      $program = Input::get('PROGRAM_ID');
      $prog = Monev_Program::where('REF_PROGRAM_ID',$program)->where('PROGRAM_TAHUN',$tahun)->where('SUB_ID',Input::get('SUB_ID'))->first();

      if($prog){
        $prog = Monev_Program::find($prog->PROGRAM_ID);
        $prog->USER_UPDATED       = Auth::user()->id;
        $prog->TIME_UPDATED       = Carbon\Carbon::now();
        $prog->$kinerjap        = intval(Input::get('KINERJA'));
        $prog->$pendukungp        = $prog->$pendukungp . ' ' . Input::get('PENDUKUNG');
        $prog->$penghambatp        = $prog->$penghambatp. ' ' . Input::get('PENGHAMBAT');
        $prog->PROGRAM_ANGGARAN       = intval(Input::get('KEGIATAN_ANGGARAN'));
      }else{
        $prog = new Monev_Program;
        $prog->REF_PROGRAM_ID = Input::get('PROGRAM_ID');
        $prog->USER_CREATED       = Auth::user()->id;
        $prog->TIME_CREATED       = Carbon\Carbon::now();
        $prog->$kinerjap        = intval($before);
        $prog->$pendukungp        = Input::get('PENDUKUNG');
        $prog->$penghambatp        = Input::get('PENGHAMBAT');
        $prog->PROGRAM_TAHUN        = $tahun;
        $prog->PROGRAM_ANGGARAN        = Input::get('KEGIATAN_ANGGARAN');
        $outcome  = Outcome::where('PROGRAM_ID',Input::get('PROGRAM_ID'))->get();
        foreach ($outcome as $outcome) {
          $monev_outcome  = new Monev_Outcome;
          $monev_outcome->PROGRAM_ID = Input::get('PROGRAM_ID');
          $monev_outcome->OUTCOME_TOLAK_UKUR = $outcome->OUTCOME_TOLAK_UKUR;
          $monev_outcome->OUTCOME_TARGET = $outcome->OUTCOME_TARGET;
          $monev_outcome->save();
        }
      }
      $prog->SKPD_ID        = $skpd;
      $prog->PROGRAM_KODE        = Input::get('PROGRAM_KODE');
      $prog->PROGRAM_NAMA        = Input::get('PROGRAM_NAMA');
      $prog->PROGRAM_VALIDASI        = 0;
      $prog->PROGRAM_INPUT        = 0;
      $prog->SATUAN        = 126;
      $prog->SUB_ID        = Input::get('SUB_ID');
      $prog->save(); 
      $program_id = Monev_Program::where('REF_PROGRAM_ID',Input::get('PROGRAM_ID'))->where('PROGRAM_TAHUN',$tahun)->value('PROGRAM_ID');
      $total = intval(Input::get('TOTAL'));
      $k_input = Input::get('KINERJA');
      $s_input = Input::get('SATUAN');
      $t_input = Input::get('TARGET');
      $o_input = Input::get('OUTPUT');
      $kinerjas = explode(",", $k_input);
      $satuans = explode(",", $s_input);
      $targets = explode(",", $t_input);
      $outputs = explode(",", $o_input);
      $edit = Monev_Kegiatan::where('REF_KEGIATAN_ID',$id)->where('SKPD_ID',$skpd)->where('SUB_ID',Input::get('SUB_ID'))->first();
      for ($i = 0; $i < $total; $i++) {
        if($edit){
          if($i>0){
            $edit = Monev_Kegiatan::where('REF_KEGIATAN_ID',$id)->where('SKPD_ID',$skpd)->where('SUB_ID',Input::get('SUB_ID'))->first();
          }
          //echo($edit->KEGIATAN_ID);
          $acara = 'Mengubah Kinerja Kegiatan';
          $keg = Monev_Kegiatan::find($edit->KEGIATAN_ID);
          $keg->USER_UPDATED       = Auth::user()->id;
          $keg->TIME_UPDATED       = Carbon\Carbon::now();
        }else{
          $keg = new Monev_Kegiatan;
          $acara = 'Menambahkan Kinerja Kegiatan';
          $keg->REF_KEGIATAN_ID = Input::get('KEGIATAN_ID');
          $keg->USER_CREATED       = Auth::user()->id;
          $keg->TIME_CREATED       = Carbon\Carbon::now();
        }
          if(is_array($kinerjas)){
              if($i < count($kinerjas)){
                $keg->$kinerja = $kinerjas[$i];
              }else{
                $keg->$kinerja = 0;
              }
          }
          if(is_array($satuans)){
              if( $i <count($satuans)){
                $keg->SATUAN = $satuans[$i];
              }else{
                $keg->SATUAN = 0;
              }
          }
        $keg->PROGRAM_ID        = $program_id;
        $keg->KEGIATAN_KODE        = Input::get('KEGIATAN_KODE');
        $keg->KEGIATAN_NAMA        = Input::get('KEGIATAN_NAMA');
        $keg->KEGIATAN_ANGGARAN        = Input::get('KEGIATAN_ANGGARAN');
        $keg->KEGIATAN_VALIDASI        = 0;
        $keg->KEGIATAN_INPUT        = 0;
        $keg->SKPD_ID        = $skpd;
        $keg->SUB_ID        = Input::get('SUB_ID');
        $keg->$pendukung        = Input::get('PENDUKUNG');
        $keg->$penghambat        = Input::get('PENGHAMBAT');
        $keg->save();
        $kegiatan_id = Monev_Kegiatan::where('REF_KEGIATAN_ID',Input::get('KEGIATAN_ID'))->where('PROGRAM_ID',$program_id)->skip($i)->first()->KEGIATAN_ID;
        $monev_log  = new Monev_Log;
        $monev_log->LOG_TIME           = Carbon\Carbon::now();
        $monev_log->KEGIATAN_ID  = Input::get('KEGIATAN_ID');
        $monev_log->USER_ID      =Auth::user()->id;
        $monev_log->LOG_ACTIVITY = $acara;
        $monev_log->LOG_DETAIL         = 'KEG#'.$kegiatan_id;
        $monev_log->save();
        $monev_output  = Monev_Output::where('KEGIATAN_ID',$kegiatan_id)->where('OUTPUT_SATUAN',$keg->SATUAN)->skip($i)->first();
        if($monev_output){
          $monev_output = Monev_Output::find($monev_output->OUTPUT_ID);
        }else{
          $monev_output  = new Monev_Output;
          $monev_output->REF_KEGIATAN_ID = Input::get('KEGIATAN_ID');
          $monev_output->OUTPUT_SATUAN = $keg->SATUAN;
        }
        if(is_array($outputs)){
          if( $i <count($outputs)){
            $monev_output->OUTPUT_TOLAK_UKUR = $outputs[$i];
          }else{
            $monev_output->OUTPUT_TOLAK_UKUR = '';
          }
      }
      if(is_array($targets)){
        if( $i <count($targets)){
          $monev_output->OUTPUT_TARGET = $targets[$i];
          if($targets[$i]>0){
            $before = $before + (($kinerjas[$i] / $targets[$i]) * 100);
          }else{
            $before = $before;
          }
          $counter++;
        }else{
          $monev_output->OUTPUT_TARGET = 0;
        }
    }
        $monev_output->KEGIATAN_ID = $kegiatan_id;
        $monev_output->save(); 
        $nilai = Input::get('REALISASI');
        $realisasi = Monev_Realisasi::where('KEGIATAN_ID',$kegiatan_id)->where('PROGRAM_ID',$program_id)->where('SKPD_ID',$skpd)->skip($i)->first();
        if($realisasi){
          $realisasi = Monev_Realisasi::find($realisasi->REALISASI_ID);
          switch ($mode) {
            case 2:
                $nilai = $nilai - intval($realisasi->REALISASI_T1);
                break;
            case 3:
                $nilai = $nilai - intval($realisasi->REALISASI_T1)- intval($realisasi->REALISASI_T2);
                break;
            case 4:
                $nilai = $nilai - intval($realisasi->REALISASI_T1)- intval($realisasi->REALISASI_T2)- intval($realisasi->REALISASI_T3);
                break;
            default:
                $nilai = intval(Input::get('REALISASI'));
        }
        }else{
          $realisasi = new Monev_Realisasi;
        }
        $rtriwulan = 'REALISASI_T'.$mode;
        $realisasi->PROGRAM_ID        = $program_id;
        $realisasi->KEGIATAN_ID        = $kegiatan_id;
        $realisasi->SKPD_ID        = $skpd;
        $realisasi->$rtriwulan        = Input::get('REALISASI');
        $realisasi->save();  
      }
      $prog = Monev_Program::find($program_id);
      
      if($counter>0){
        $jumlah = (floatval($before) / $counter);
        if(floatval($prog->$kinerjap)>0){
          $prog->$kinerjap        = (floatval($prog->$kinerjap) + $jumlah) / 2;
        }else{
          $prog->$kinerjap        = floatval($prog->$kinerjap) + $jumlah;
        }
      }else{
        $prog->$kinerjap        = floatval($before);
      }
      $prog->save();  
      return 'Berhasil!';
    }

      public function getDetail($tahun, $skpd, $mode=1, $id){

        $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->leftJoin('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_SKPD','DAT_BL.SKPD_ID','=','REF_SKPD.SKPD_ID')
                        ->Join('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->leftJoin('REFERENSI.REF_URUSAN_SKPD','DAT_BL.SKPD_ID','=','REF_URUSAN_SKPD.SKPD_ID')
                        ->leftJoin('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_URUSAN_SKPD.URUSAN_ID')
                        ->groupBy('REF_URUSAN.URUSAN_KODE','REF_SKPD.SKPD_KODE','REF_PROGRAM.PROGRAM_KODE','KEGIATAN_NAMA','REF_KEGIATAN.KEGIATAN_ID','REF_SUB_UNIT.SUB_KODE','REF_SUB_UNIT.SUB_NAMA','REF_SUB_UNIT.SUB_ID','KEGIATAN_KODE','DAT_BL.BL_ID','BL_PAGU')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('DAT_BL.SKPD_ID',$skpd)
                        ->where('REF_KEGIATAN.PROGRAM_ID',$id)
                        ->selectRaw(' "REF_URUSAN"."URUSAN_KODE","REF_SKPD"."SKPD_KODE","REF_PROGRAM"."PROGRAM_KODE","KEGIATAN_NAMA","REF_KEGIATAN"."KEGIATAN_ID","REF_SUB_UNIT"."SUB_KODE","REF_SUB_UNIT"."SUB_NAMA","REF_SUB_UNIT"."SUB_ID","KEGIATAN_KODE","DAT_BL"."BL_ID","BL_PAGU" ')
                        ->get();

        $view       = array();
        $no         = 1;
        $opsi       = '';
        $akb       = '';
        foreach ($data as $data) {

          $monev_keg  = Monev_Kegiatan::leftJoin('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_KEGIATAN.SUB_ID')->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_KEGIATAN.SATUAN')->where('REF_KEGIATAN_ID',$data->KEGIATAN_ID)->where('REF_SUB_UNIT.SUB_ID',$data->SUB_ID)->where('DAT_KEGIATAN.SKPD_ID',$skpd)->first();
        
          if($monev_keg){
            $status  = '<span class="text-success"><i class="fa fa-check"> Sudah Diisi</i></span>';
            $kegiatanid = $monev_keg->KEGIATAN_ID;
            $kinerja = 'KEGIATAN_T'.$mode;
            $penghambat = 'KEGIATAN_PENGHAMBAT_T'.$mode;
            $pendukung = 'KEGIATAN_PENDUKUNG_T'.$mode;
            $kinerja = $monev_keg->$kinerja;
            $penghambat = $monev_keg->$penghambat;
            $pendukung = $monev_keg->$pendukung;
            $satuan = $monev_keg->SATUAN_NAMA;
          }else{
            $status  = '<span class="text-danger"><i class="fa fa-close"> Belum Diisi</i></span>';
            $kegiatanid = "";
            $kinerja = "";
            $penghambat = "";
            $pendukung = "";
            $satuan = "";
          }
            $staff      = Staff::where('BL_ID',$data->BL_ID)->get();
            $mod        = 0;
            $opsi = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
            <li><a onclick="return view(\''.$mode.'\',\''.$data->BL_ID.'\')"><i class="fa fa-eye"></i>Lihat</a></li>
            <li><a onclick="return info(\''.$mode.'\',\''.$data->BL_ID.'\')"><i class="fa fa-pencil-square"></i>Info</a></li></ul></div>';
            
            foreach($staff as $s){
                if($s->USER_ID == Auth::user()->id) $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$mode.'\',\''.$data->BL_ID.'\')" class="action-edit open-form-btl"><i class="mi-edit"></i></a></div>';
            }
            if(Auth::user()->level == 8 || Auth::user()->level == 9){
              $opsi = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
              <li><a onclick="return ubah(\''.$mode.'\',\''.$data->BL_ID.'\')"><i class="fa mi-edit"></i>Edit</a></li>
              <li><a onclick="return view(\''.$mode.'\',\''.$data->BL_ID.'\')"><i class="fa fa-eye"></i>Lihat</a></li>
              <li><a onclick="return hapus(\''.$skpd.'\',\''.$data->SUB_ID.'\',\''.$data->KEGIATAN_ID.'\')"><i class="fa fa-close"></i>Hapus</a></li>
              <li><a onclick="return info(\''.$mode.'\',\''.$data->BL_ID.'\')"><i class="fa fa-pencil-square"></i>Info</a></li></ul></div>';
            }
           array_push($view, array( 'NO'       => $no++,
                                   'KEGIATAN'     => $data->URUSAN_KODE.'.'.$data->SKPD_KODE.'.'.$data->SUB_KODE.'.'.$data->PROGRAM_KODE.'.'.$data->KEGIATAN_KODE.'-'.$data->KEGIATAN_NAMA.'-'.$data->SUB_NAMA,
                                   'KEGIATAN_ID'     => $data->KEGIATAN_ID,
                                   'KINERJA'    => $kinerja.' '.$satuan,
                                   'TOTAL'    => number_format($data->BL_PAGU,0,'.',','),
                                    'AKSI' => $opsi,
                                    'STATUS' => $status ));
        }
         
        $out = array("aaData"=>$view);       
      return Response::JSON($out);
      }   

      public function getFaktor($tahun, $skpd, $mode=1){
        
        if(empty($skpd)){
          $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
          $skpdnama   = SKPD::where('SKPD_ID',Auth::user()->id)->where('SKPD_TAHUN',$tahun)->value('SKPD_NAMA'); 
          
        }else{
          $skpdnama   = SKPD::where('SKPD_ID',$skpd)->where('SKPD_TAHUN',$tahun)->value('SKPD_NAMA'); 
        }
        $sub_id = Subunit::where('SKPD_ID',$skpd)->count();
        if($sub_id==1){
          $sub_id = Subunit::where('SKPD_ID',$skpd)->value('SUB_ID');
        }else{
          $sub_id = FALSE;
        }

          $data       = Monev_Faktor::where('TAHUN',$tahun)
                        ->where('T',$mode)
                        ->where('SKPD_ID',$skpd)
                        ->first();
          $id = "";
          $input =FALSE;
          $validasi =TRUE;
          if($data){
            $save       = "edit";
            $id = $data->FAKTOR_ID;
            $pendukung = $data->PENDUKUNG;
            $penghambat =  $data->PENGHAMBAT;
            $triwulan =  $data->TRIWULAN;
            $renja = $data->RENJA;
            $sasaran = $data->SASARAN;
            if($data->STATUS==2) {
              $input =TRUE;
              $validasi =TRUE;
              $judul = "Validasi";
          }else{
            if(Auth::user()->level == 8 || Auth::user()->level == 9 || Auth::user()->mod == '01000000000'){
              $input =TRUE;
              $validasi =FALSE;
              $judul = "Verifikasi";
            }else{
              $input =FALSE;
              $validasi =TRUE;
              $judul = "Parameter Cetak";
            }
          }
          }else{
            $data       = Monev_Program::where('PROGRAM_TAHUN',$tahun)
            ->where('SKPD_ID',$skpd);
            if($sub_id){
              $data       = $data->where('SUB_ID',$sub_id);}
            $data       = $data->get();
            if($data){
              $cpendukung =  "PROGRAM_PENDUKUNG_T".$mode;
              $cpenghambat = "PROGRAM_PENGHAMBAT_T".$mode;
              $pendukung =  "";
              $penghambat = "";
              foreach ($data as $data) {
                $pendukung = $data->$cpendukung . "\r\n". $pendukung;
                $penghambat = $data->$cpenghambat . "\r\n". $penghambat;
              }
            }else{
              $pendukung = "";
              $penghambat = "";
            }
            $sasaran = "";
            $triwulan = '';
            $renja = '';
            $save       = "save";
            $judul = "Parameter Cetak";
          }
          $view       = array();
          array_push($view, array(  'PENGHAMBAT'       => $penghambat,
                                    'PENDUKUNG'       => $pendukung,
                                    'TRIWULAN'       => $triwulan,
                                    'RENJA'       => $renja,
                                    'MODE'       => $mode,
                                    'SAVE'       => $save,
                                    'ID'       => $id,
                                    'SKPD_ID'       => $skpd,
                                    'SKPD_NAMA'       => $skpdnama,
                                    'TAHUN'       => $tahun,
                                    'INPUT'     => $input,
                                    'VALIDASI' => $validasi,
                                    'SASARAN' => $sasaran,
                                    'JUDUL'    => $judul));
            $out = array("aaData"=>$view);       
          return Response::JSON($out);
      }   

      public function simpanFaktor($tahun,$mode=1){
        $mode = Input::get('T');
        $pendukung = Input::get('PENDUKUNG');
        $penghambat = Input::get('PENGHAMBAT');
        $triwulan = Input::get('TRIWULAN');
        $renja = Input::get('RENJA');
        $sasaran = Input::get('SASARAN');
        
        $id = Input::get('FAKTOR_ID');
        $skpd = Input::get('SKPD_ID');
        if(empty($skpd)){
          $skpd = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
        }
         if($id){
            $keg = Monev_Faktor::find($id);
            }else{
            $keg = new Monev_Faktor;
          }
        
        $keg->TAHUN        = $tahun;
        $keg->T        = $mode;
        $keg->SKPD_ID        = $skpd;
        $keg->PENDUKUNG        = $pendukung;
        $keg->PENGHAMBAT        = $penghambat;
        $keg->TRIWULAN        = $triwulan;
        $keg->RENJA        = $renja;
        $keg->SASARAN        = $sasaran;
        $keg->save(); 
        return 'Berhasil!';
      }

      public function cetak($tahun, $skpd){
        $prog = Monev_Program::where('DAT_PROGRAM.SKPD_ID',$skpd)->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_PROGRAM.SATUAN')
        ->leftJoin('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','DAT_PROGRAM.SKPD_ID')
                ->where('PROGRAM_TAHUN',$tahun)->get();
        $program = array();
        $skpdnama = "Tidak Ada SKPD";
        $ringkasoutcome = "";
        $tahapan = 0;
        $tahapan    = Monev_Tahapan::where('TAHAPAN_TAHUN',$tahun)->first();
        if($tahapan){
          if($tahapan->TAHAPAN_T1==1){
            $tahapan = 1;
          }elseif($tahapan->TAHAPAN_T2==1){
            $tahapan = 2;
          }elseif($tahapan->TAHAPAN_T3==1){
            $tahapan = 3;
          }else{
            $tahapan = 4;
          }
        }else{
          $tahapan = 0;
        }
        $faktor = Monev_Faktor::where('TAHUN',$tahun)->where('SKPD_ID',$skpd)
                ->where('T',$tahapan)->first();
        if($faktor){
          $penghambat=$faktor->PENGHAMBAT;
          $pendukung=$faktor->PENDUKUNG;
          $triwulan=$faktor->TRIWULAN;
          $renja=$faktor->RENJA;
        }else{
          $penghambat="";
          $pendukung="";
          $triwulan="";
          $renja="";
        }
        foreach ($prog as $prog) {
          $outcome = Monev_Outcome::where('PROGRAM_ID',$prog->REF_PROGRAM_ID)->get();
          foreach ($outcome as $outcome) {
            $ringkasoutcome = $outcome->OUTCOME_TOLAK_UKUR ." : ". $outcome->OUTCOME_TARGET . "%\r\n". $ringkasoutcome;
          }
        array_push($program, array( 'KEGIATAN'     => Monev_Kegiatan::where('DAT_KEGIATAN.PROGRAM_ID',$prog->PROGRAM_ID)->leftJoin('MONEV.DAT_REALISASI','DAT_REALISASI.KEGIATAN_ID','=','DAT_KEGIATAN.KEGIATAN_ID')->leftJoin('MONEV.DAT_OUTPUT','DAT_OUTPUT.KEGIATAN_ID','=','DAT_KEGIATAN.REF_KEGIATAN_ID')->get(),
                                 'PROGRAM_ID'     => $prog->PROGRAM_ID,
                                 'SKPD_ID'     => $prog->SKPD_ID,
                                 'SKPD'     => $prog->SKPD_NAMA,
                                 'PROGRAM_KODE'     => $prog->PROGRAM_KODE,
                                 'PROGRAM_NAMA'     => $prog->PROGRAM_NAMA,
                                 'PROGRAM_ANGGARAN'     => $prog->PROGRAM_ANGGARAN,
                                 'PROGRAM_T1'     => $prog->PROGRAM_T1,
                                 'PROGRAM_T2'     => $prog->PROGRAM_T2,
                                 'PROGRAM_T3'     => $prog->PROGRAM_T3,
                                 'PROGRAM_T4'     => $prog->PROGRAM_T4,
                                 'PROGRAM_PENDUKUNG_T1'     => $prog->PROGRAM_PENDUKUNG_T1,
                                 'PROGRAM_PENDUKUNG_T2'     => $prog->PROGRAM_PENDUKUNG_T2,
                                 'PROGRAM_PENDUKUNG_T3'     => $prog->PROGRAM_PENDUKUNG_T3,
                                 'PROGRAM_PENDUKUNG_T4'     => $prog->PROGRAM_PENDUKUNG_T4,
                                 'PROGRAM_PENGHAMBAT_T1'     => $prog->PROGRAM_PENGHAMBAT_T1,
                                 'PROGRAM_PENGHAMBAT_T2'     => $prog->PROGRAM_PENGHAMBAT_T2,
                                 'PROGRAM_PENGHAMBAT_T3'     => $prog->PROGRAM_PENGHAMBAT_T3,
                                 'PROGRAM_PENGHAMBAT_T4'     => $prog->PROGRAM_PENGHAMBAT_T4,
                                 'PROGRAM_TAHUN'     => $prog->PROGRAM_TAHUN,
                                 'SASARAN_NAMA'     => $prog->SASARAN_NAMA,
                                 'REF_PROGRAM_ID'     => $prog->REF_PROGRAM_ID,
                                 'SATUAN'    => $prog->SATUAN_NAMA,
                                 'OUTCOME'   => $ringkasoutcome ));
                                 $skpdnama     = $prog->SKPD_NAMA;
      }
      $i=1;

         return View('monev.pdf',
            [   'tahun'             =>$tahun,
                'program'           =>$program,
                'i'                 =>$i,
                'skpd'              =>$skpdnama,
                'penghambat'  =>$penghambat,
                'pendukung'  =>$pendukung,
                'triwulan'  =>$triwulan,
                'renja'  =>$renja,
            ]);
              
      }

}
