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
	  return View('monev.index',[
      'tahun'     =>$tahun,
      'skpd'      =>$skpd,
      'satuan'    =>$satuan
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
                        ->Join('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->Join('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
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
                        ->Join('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->Join('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
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
                        ->Join('REFERENSI.REF_OUTCOME','REF_PROGRAM.PROGRAM_ID','=','REF_OUTCOME.PROGRAM_ID')
                        ->Join('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','REF_OUTCOME.SATUAN_ID')
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
                        ->groupBy('KEGIATAN_NAMA','REF_KEGIATAN.KEGIATAN_ID','KEGIATAN_KODE','REF_PROGRAM.PROGRAM_ID','DAT_BL.BL_ID','BL_PAGU')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',$skpd)
                        ->where('BL_ID',$id)
                        ->get();


        $view       = array();
        $no         = 1;
        $opsi       = '';
        $akb       = '';
        foreach ($data as $data) {
            
          $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->BL_ID.'\')" class="action-edit open-form-btl"><i class="mi-edit"></i></a></div>';
          $akb = '<div class="action visible pull-right"><a href="/main/'.$tahun.'/belanja-tidak-langsung/akb/" class="action-edit" target="_blank"><i class="mi-edit"></i></a></div>';
          $monev_keg  = Monev_Kegiatan::where('REF_KEGIATAN_ID',$data->KEGIATAN_ID)->first();
        
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

          array_push($view, array( 'KEGIATAN_ID'       => $data->KEGIATAN_ID,
                                   'PROGRAM_ID'       => $data->PROGRAM_ID,
                                   'PROGRAM_NAMA'       => $data->PROGRAM_NAMA,
                                   'PROGRAM_KODE'       => $data->PROGRAM_KODE,
                                   'KEGIATAN_KODE'       => $data->KEGIATAN_KODE,
                                   'KEGIATAN_NAMA'       => $data->KEGIATAN_NAMA,
                                   'KEGIATAN_ANGGARAN'       => $data->BL_PAGU,
                                   'TARGET'       => '',
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
      $keg = Monev_Kegiatan::where('REF_KEGIATAN_ID',$id)->where('SKPD_ID',$skpd)->first();
        if($keg){
          $keg = Monev_Kegiatan::find($keg->KEGIATAN_ID);
          $keg->USER_UPDATED       = Auth::user()->id;
          $keg->TIME_UPDATED       = Carbon\Carbon::now();
        }else{
          $keg = new Monev_Kegiatan;
          $keg->REF_KEGIATAN_ID = Input::get('KEGIATAN_ID');
          $keg->USER_CREATED       = Auth::user()->id;
          $keg->TIME_CREATED       = Carbon\Carbon::now();
        }
      
      $keg->KEGIATAN_KODE        = Input::get('KEGIATAN_KODE');
      $keg->KEGIATAN_NAMA        = Input::get('KEGIATAN_NAMA');
      $keg->KEGIATAN_ANGGARAN        = Input::get('KEGIATAN_ANGGARAN');
      $before = intval($keg->$kinerja);
      $keg->$kinerja        = Input::get('KINERJA');
      $keg->KEGIATAN_VALIDASI        = 0;
      $keg->KEGIATAN_INPUT        = 0;
      $keg->SKPD_ID        = $skpd;
      $keg->$pendukung        = Input::get('PENDUKUNG');
      $keg->$penghambat        = Input::get('PENGHAMBAT');
      $keg->SATUAN        = Input::get('SATUAN');
    
      $program = Input::get('PROGRAM_ID');
      $prog = Monev_Program::where('REF_PROGRAM_ID',$program)->where('PROGRAM_TAHUN',$tahun)->first();
      
      if($prog){
        $prog = Monev_Program::find($prog->PROGRAM_ID);
        $prog->USER_UPDATED       = Auth::user()->id;
        $prog->TIME_UPDATED       = Carbon\Carbon::now();
        $prog->$kinerjap        = intval($prog->$kinerjap) - $before + intval(Input::get('KINERJA'));
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
      }
      $prog->SKPD_ID        = $skpd;
      $prog->PROGRAM_KODE        = Input::get('KEGIATAN_KODE');
      $prog->PROGRAM_NAMA        = Input::get('PROGRAM_NAMA');
      $prog->PROGRAM_VALIDASI        = 0;
      $prog->PROGRAM_INPUT        = 0;
      $prog->SATUAN        = Input::get('SATUAN');
      $prog->save(); 
      
      $keg->PROGRAM_ID        = Monev_Program::where('REF_PROGRAM_ID',Input::get('PROGRAM_ID'))->where('PROGRAM_TAHUN',$tahun)->value('PROGRAM_ID');
      $keg->save(); 
        return 'Berhasil!';
    }

      public function getDetail($tahun, $skpd, $mode=1, $id){

        $data       = BL::Join('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->groupBy('KEGIATAN_NAMA','REF_KEGIATAN.KEGIATAN_ID','KEGIATAN_KODE','DAT_BL.BL_ID','BL_PAGU')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('SKPD_ID',$skpd)
                        ->where('PROGRAM_ID',$id)
                        ->selectRaw(' "KEGIATAN_NAMA","REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","DAT_BL"."BL_ID","BL_PAGU" ')
                        ->get();

        $view       = array();
        $no         = 1;
        $opsi       = '';
        $akb       = '';
        foreach ($data as $data) {
          
            $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$mode.'\',\''.$data->BL_ID.'\')" class="action-edit open-form-btl"><i class="mi-edit"></i></a></div>';
            $akb = '<div class="action visible pull-right"><a href="/main/'.$tahun.'/belanja-tidak-langsung/akb/" class="action-edit" target="_blank"><i class="mi-edit"></i></a></div>';
          
          array_push($view, array( 'NO'       => $no++,
                                   'KEGIATAN'     => $data->KEGIATAN_KODE.'-'.$data->KEGIATAN_NAMA,
                                   'KEGIATAN_ID'     => $data->KEGIATAN_ID,
                                   'KINERJA'    => '',
                                   'TOTAL'    => number_format($data->BL_PAGU,0,'.',','),
                                    'AKSI' => $opsi ));
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
