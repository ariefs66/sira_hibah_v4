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
use App\Model\Monev\Monev_Kegiatan;
use App\Model\Monev\Monev_Program;
use App\Model\Monev\Monev_Realisasi;
use App\Model\Monev\Monev_Faktor;
use App\Model\Monev\Monev_Outcome;
use App\Model\Monev\Monev_Output;
use App\Model\Monev\Monev_Tahapan;
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
      $skpd    = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_ID')->get();
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
      if(Auth::user()->level == 8 || Auth::user()->level == 9){
        $cek = $cek->first();
      }else{
        $id = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');
        $cek = $cek->where('SKPD_ID',$id)->first();
      }
      if($cek){
        $cek = TRUE;
      }else {
        $cek = FALSE;
      }
	  return View('monev.index',[
      'tahun'     =>$tahun,
      'skpd'      =>$skpd,
      'satuan'    =>$satuan,
      'cek'       =>$cek,
      'triwulan1' =>($tahapan->TAHAPAN_T1==1?'active':''),
      'triwulan2' =>($tahapan->TAHAPAN_T2==1?'active':''),
      'triwulan3' =>($tahapan->TAHAPAN_T3==1?'active':''),
      'triwulan4' =>($tahapan->TAHAPAN_T4==1?'active':''),
      ]);

   }

   public function getTriwulan1($tahun,$filter){
          if(Auth::user()->level == 8){
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
                                   'TOTAL'           =>number_format($data->total,0,'.',',')));
          $no++;
        }

         $out = array("aaData"=>$view, "totPeg"=>$totPeg);      
         return Response::JSON($out);
      }

   public function getTriwulan2($tahun, $filter){
          if(Auth::user()->level == 8){  
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
                                   'TOTAL'           =>number_format($data->total,0,'.',',')));
          $no++;
        }

         $out = array("aaData"=>$view, "totPeg"=>$totPeg);      
         return Response::JSON($out);
      }
      
   public function getTriwulan3($tahun, $filter){
        if(Auth::user()->level == 8){
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
                                   'TOTAL'           =>number_format($data->total,0,'.',',')));
          $no++;
        }

         $out = array("aaData"=>$view, "totPeg"=>$totPeg);      
         return Response::JSON($out);
      }
      

      public function getTriwulan4($tahun, $filter){
        if(Auth::user()->level == 8){
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
        $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                        ->leftJoin('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->LeftJoin('BUDGETING.DAT_BL_REALISASI','DAT_BL_REALISASI.BL_ID','=','DAT_BL.BL_ID')
                        ->groupBy('KEGIATAN_NAMA','REF_KEGIATAN.KEGIATAN_ID','REF_SUB_UNIT.SUB_KODE','KEGIATAN_KODE','REF_PROGRAM.PROGRAM_ID','DAT_BL.BL_ID','BL_PAGU')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('DAT_BL.SKPD_ID',$skpd)
                        ->where('DAT_BL.BL_ID',$id)
                        ->select(DB::raw('SUM("DAT_BL_REALISASI"."REALISASI_TOTAL"), "DAT_BL".*, "REF_SUB_UNIT"."SUB_KODE", "REF_KEGIATAN".*, "REF_PROGRAM".*'))
                        ->get();

        $view       = array();
        $no         = 1;
        $opsi       = '';
        $akb       = '';
        foreach ($data as $data) {
            
          $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->BL_ID.'\')" class="action-edit open-form-btl"><i class="mi-edit"></i></a></div>';
          $akb = '<div class="action visible pull-right"><a href="/main/'.$tahun.'/belanja-tidak-langsung/akb/" class="action-edit" target="_blank"><i class="mi-edit"></i></a></div>';
          $monev_keg  = Monev_Kegiatan::where('REF_KEGIATAN_ID',$data->KEGIATAN_ID)->where('KEGIATAN_KODE',$data->SUB_KODE)->first();
        
          if($monev_keg){
            $kegiatanid = $monev_keg->KEGIATAN_ID;
            $kinerja = 'KEGIATAN_T'.$mode;
            $penghambat = 'KEGIATAN_PENGHAMBAT_T'.$mode;
            $pendukung = 'KEGIATAN_PENDUKUNG_T'.$mode;
            $kinerja = $monev_keg->$kinerja;
            $penghambat = $monev_keg->$penghambat;
            $pendukung = $monev_keg->$pendukung;
            $satuan = $monev_keg->SATUAN;
          }else{
            $kegiatanid = "";
            $kinerja = "";
            $penghambat = "";
            $pendukung = "";
            $satuan = "";
          }
          $sasaran="";
          $monev_output  = Output::where('BL_ID',$id)->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_OUTPUT.SATUAN_ID')->get();
          if($monev_output){
          }else{
            $monev_output  = OutputPerubahan::where('BL_ID',$id)->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_OUTPUT_PERUBAHAN.SATUAN_ID')->get();
          }
          $total = 0;
          foreach ($monev_output as $monev_output) {
            $sasaran = $monev_output->OUTPUT_TOLAK_UKUR ." : ". $monev_output->OUTPUT_TARGET . " ". $monev_output->SATUAN_NAMA . "\r\n". $sasaran;
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
                                   'PROGRAM_NAMA'       => $data->PROGRAM_NAMA,
                                   'PROGRAM_KODE'       => $data->PROGRAM_KODE,
                                   'SUB_KODE'       => $data->SUB_KODE,
                                   'KEGIATAN_KODE'       => $data->KEGIATAN_KODE,
                                   'KEGIATAN_NAMA'       => $data->KEGIATAN_NAMA,
                                   'KEGIATAN_ANGGARAN'       => $data->BL_PAGU,
                                   'REALISASI'       => $realisasi,
                                   'TARGET'       => $sasaran,
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

    public function simpanKegiatan($tahun,$mode=1){
      $mode = Input::get('MODE');
      $kinerja = 'KEGIATAN_T'.$mode;
      $pendukung = 'KEGIATAN_PENDUKUNG_T'.$mode;
      $penghambat = 'KEGIATAN_PENGHAMBAT_T'.$mode;
      $kinerjap = 'PROGRAM_T'.$mode;
      $pendukungp = 'PROGRAM_PENDUKUNG_T'.$mode;
      $penghambatp = 'PROGRAM_PENGHAMBAT_T'.$mode;
      $before = 0;
      $id = Input::get('KEGIATAN_ID');
      $skpd = Input::get('SKPD_ID');
      if(empty($skpd)){
        $skpd = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
      }
     
      $program = Input::get('PROGRAM_ID');
      $prog = Monev_Program::where('REF_PROGRAM_ID',$program)->where('PROGRAM_TAHUN',$tahun)->first();
      
      if($prog){
        $prog = Monev_Program::find($prog->PROGRAM_ID);
        $prog->USER_UPDATED       = Auth::user()->id;
        $prog->TIME_UPDATED       = Carbon\Carbon::now();
        $prog->$kinerjap        = intval(Input::get('KINERJA'));
        $prog->$pendukungp        = $prog->$pendukungp . ' ' . Input::get('PENDUKUNG');
        $prog->$penghambatp        = $prog->$penghambatp. ' ' . Input::get('PENGHAMBAT');
        $prog->PROGRAM_ANGGARAN       += intval(Input::get('KEGIATAN_ANGGARAN'));
      }else{
        $prog = new Monev_Program;
        $prog->REF_PROGRAM_ID = Input::get('PROGRAM_ID');
        $prog->USER_CREATED       = Auth::user()->id;
        $prog->TIME_CREATED       = Carbon\Carbon::now();
        $prog->$kinerjap        = intval(Input::get('KINERJA'));
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
      $prog->SATUAN        = 1;
      $prog->save(); 
      $program_id = Monev_Program::where('REF_PROGRAM_ID',Input::get('PROGRAM_ID'))->where('PROGRAM_TAHUN',$tahun)->value('PROGRAM_ID');
      $total = intval(Input::get('TOTAL'));
      $k_input = Input::get('KINERJA');
      $s_input = Input::get('SATUAN');
      $kinerjas = explode(",", $k_input);
      $satuans = explode(",", $s_input);
      $edit = Monev_Kegiatan::where('REF_KEGIATAN_ID',$id)->where('SKPD_ID',$skpd)->first();
      for ($i = 0; $i < $total; $i++) {
        if($edit){
          if($i>0){
            $edit = Monev_Kegiatan::where('REF_KEGIATAN_ID',$id)->where('SKPD_ID',$skpd)->first();
          }
          echo($edit->KEGIATAN_ID);
          $keg = Monev_Kegiatan::find($edit->KEGIATAN_ID);
          $keg->USER_UPDATED       = Auth::user()->id;
          $keg->TIME_UPDATED       = Carbon\Carbon::now();
        }else{
          $keg = new Monev_Kegiatan;
          $keg->REF_KEGIATAN_ID = Input::get('KEGIATAN_ID');
          $keg->USER_CREATED       = Auth::user()->id;
          $keg->TIME_CREATED       = Carbon\Carbon::now();
          $monev_output  = new Monev_Output;
          $monev_output->KEGIATAN_ID = Input::get('KEGIATAN_ID');
          $monev_output->OUTPUT_TOLAK_UKUR = Input::get('TARGET');
          $monev_output->save();
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
        $keg->$pendukung        = Input::get('PENDUKUNG');
        $keg->$penghambat        = Input::get('PENGHAMBAT');  
        $keg->save();
      }
      $kegiatan_id = Monev_Kegiatan::where('REF_KEGIATAN_ID',Input::get('KEGIATAN_ID'))->where('PROGRAM_ID',$program_id)->value('KEGIATAN_ID');
      $nilai = Input::get('REALISASI');
      $realisasi = Monev_Realisasi::where('KEGIATAN_ID',$kegiatan_id)->where('PROGRAM_ID',$program_id)->where('SKPD_ID',$skpd)->first();
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
      return 'Berhasil!';
    }

      public function getDetail($tahun, $skpd, $mode=1, $id){

        $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->Join('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->groupBy('KEGIATAN_NAMA','REF_KEGIATAN.KEGIATAN_ID','REF_SUB_UNIT.SUB_KODE','KEGIATAN_KODE','DAT_BL.BL_ID','BL_PAGU')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('DAT_BL.SKPD_ID',$skpd)
                        ->where('PROGRAM_ID',$id)
                        ->selectRaw(' "KEGIATAN_NAMA","REF_KEGIATAN"."KEGIATAN_ID","REF_SUB_UNIT"."SUB_KODE","KEGIATAN_KODE","DAT_BL"."BL_ID","BL_PAGU" ')
                        ->get();

        $view       = array();
        $no         = 1;
        $opsi       = '';
        $akb       = '';
        foreach ($data as $data) {

          $monev_keg  = Monev_Kegiatan::leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_KEGIATAN.SATUAN')->where('REF_KEGIATAN_ID',$data->KEGIATAN_ID)->where('KEGIATAN_KODE',$data->SUB_KODE)->first();
        
          if($monev_keg){
            $kegiatanid = $monev_keg->KEGIATAN_ID;
            $kinerja = 'KEGIATAN_T'.$mode;
            $penghambat = 'KEGIATAN_PENGHAMBAT_T'.$mode;
            $pendukung = 'KEGIATAN_PENDUKUNG_T'.$mode;
            $kinerja = $monev_keg->$kinerja;
            $penghambat = $monev_keg->$penghambat;
            $pendukung = $monev_keg->$pendukung;
            $satuan = $monev_keg->SATUAN_NAMA;
          }else{
            $kegiatanid = "";
            $kinerja = "";
            $penghambat = "";
            $pendukung = "";
            $satuan = "";
          }
          
            $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$mode.'\',\''.$data->BL_ID.'\')" class="action-edit open-form-btl"><i class="mi-edit"></i></a></div>';
          array_push($view, array( 'NO'       => $no++,
                                   'KEGIATAN'     => $data->SUB_KODE.'-'.$data->KEGIATAN_NAMA,
                                   'KEGIATAN_ID'     => $data->KEGIATAN_ID,
                                   'KINERJA'    => $kinerja.' '.$satuan,
                                   'TOTAL'    => number_format($data->BL_PAGU,0,'.',','),
                                    'AKSI' => $opsi ));
        }
         
        $out = array("aaData"=>$view);       
      return Response::JSON($out);
      }   

      public function getFaktor($tahun, $skpd, $mode=1){
        if(empty($skpd)){
          $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');  
        }
          $data       = Monev_Faktor::where('TAHUN',$tahun)
                        ->where('T',$mode)
                        ->where('SKPD_ID',$skpd)
                        ->first();
          $id = "";
          if($data){
            $save       = "edit";
            $id = $data->FAKTOR_ID;
            $pendukung = $data->PENDUKUNG;
            $penghambat =  $data->PENGHAMBAT;
            $triwulan =  $data->TRIWULAN;
            $renja = $data->RENJA;
          }else{
            $data       = Monev_Program::where('PROGRAM_TAHUN',$tahun)
            ->where('SKPD_ID',$skpd)
            ->get();
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
            $triwulan = '';
            $renja = '';
            $save       = "save";
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
                                    'TAHUN'       => $tahun));
             
            $out = array("aaData"=>$view);       
          return Response::JSON($out);
      }   

      public function simpanFaktor($tahun,$mode=1){
        $mode = Input::get('T');
        $pendukung = Input::get('PENDUKUNG');
        $penghambat = Input::get('PENGHAMBAT');
        $triwulan = Input::get('TRIWULAN');
        $renja = Input::get('RENJA');
        
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
