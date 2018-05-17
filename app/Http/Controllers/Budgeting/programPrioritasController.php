<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use View;
use Response;
use DB;
use Carbon;
use Auth;
use App\Model\Program;
use App\Model\Kegiatan;
use App\Model\SKPD;
use App\Model\UserBudget;
use App\Model\Tahapan;
use App\Model\Propri;

class programPrioritasController extends Controller
{
    public function index($tahun, $status){
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->get();
        if($status=="murni"){
          return View('budgeting.belanja-langsung.propri',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd]);
        }
        else{
          return View('budgeting.belanja-langsung.propri',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd]);
        }
    }

    public function submitAdd($tahun,$status){
      if($status=="murni"){
        $p    = new Propri;
        $p->PROPRI_TAHUN     = Input::get('PROPRI_TAHUN');
        $p->SKPD_ID        = Input::get('SKPD_ID');
        $p->PROGRAM_ID     = Input::get('PROGRAM_ID');
        $p->PROPRI_PAGU      = Input::get('PROPRI_PAGU');
        $p->PROPRI_KUNCI  = Input::get('PROPRI_KUNCI');
        $p->save();
      }
      /*
        $log        = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Setting Pagu Program '.$datarek->REKENING_KODE.'-'.$datarek->REKENING_NAMA.' Jumlah '.Input::get('BTL_TOTAL');
        $log->LOG_DETAIL                        = 'BTL#'.$databtl->BTL_ID;
        $log->save();   */   
    	return 'Input Berhasil!';
    }

    public function submitEdit($tahun,$status){
      if($status=="murni"){
        Propri::where('PROPRI_ID',Input::get('PROPRI_ID'))->update([
          'PROPRI_TAHUN'     => Input::get('PROPRI_TAHUN'),
          'SKPD_ID'        => Input::get('SKPD_ID'),
          'PROGRAM_ID'     => Input::get('PROGRAM_ID'),
          'PROPRI_PAGU'      => Input::get('PROPRI_PAGU'),
          'PROPRI_KUNCI'  => Input::get('PROPRI_KUNCI')
          ]);
      }
      /*
        $log              = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Merubah BTL Rekening '.$datarek->REKENING_KODE.'-'.$datarek->REKENING_NAMA.' Jumlah '.Input::get('BTL_TOTAL');
        $log->LOG_DETAIL                        = 'BTL#'.$databtl->BTL_ID;
        $log->save();*/
      return "Berhasil Ubah";
    }

    public function delete($tahun,$status){
      if($status=="murni"){
        Propri::where('PROPRI_ID',Input::get('PROPRI_ID'))->delete();
      }
        
      /*$log        = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Menghapus BTL '.$databtl->rekening->REKENING_KODE.'-'.$databtl->rekening->REKENING_NAMA.' Jumlah '.$databtl->BTL_TOTAL;
        $log->LOG_DETAIL                        = 'BTL#'.$databtl->BTL_ID;
        $log->save();
        */
        return 'Berhasil!';      

    }

   	public function getProgramSKPD($tahun,$status){
      $data      = DB::table('BUDGETING.DAT_BL')
                  ->where('BUDGETING.DAT_BL.BL_TAHUN',$tahun)
                  ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','BUDGETING.DAT_BL.SKPD_ID')
                  ->select('REF_SKPD.SKPD_KODE', 'REF_SKPD.SKPD_NAMA', 'REF_SKPD.SKPD_ID');
        if(Auth::user()->mod== '01000000000'){
          $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
          $skpd_      = array(); 
          $i = 0;
          foreach($skpd as $s){
            $skpd_[$i]   = $s->SKPD_ID;
            $i++;
          }
          $data       = $data->whereIn('REFERENSI.REF_SKPD.SKPD_ID',$skpd_);
        }
        $data = $data->distinct()->get();
        $view       = array();
        $totPeg      = 0;
        foreach ($data as $data) {
          array_push($view, array('ID'      =>$data->SKPD_ID,
                                  'KODE'    =>$data->SKPD_KODE,
                                  'NAMA'    =>$data->SKPD_NAMA)
                                  );
          $totPeg += 1;
        }
		  $out = array("aaData"=>$view, "totPeg"=>$totPeg);    	
    	return Response::JSON($out);
   	}

   	public function getDetail($tahun,$status,$skpd){
      $view       = array();
      if($status=="murni"){
        $data      = DB::table('BUDGETING.DAT_BL')
                      ->where('BUDGETING.DAT_BL.BL_TAHUN',$tahun)->where('BUDGETING.DAT_BL.SKPD_ID',$skpd)
                      ->leftJoin('REFERENSI.REF_KEGIATAN','REFERENSI.REF_KEGIATAN.KEGIATAN_ID','=','BUDGETING.DAT_BL.KEGIATAN_ID')
                      ->leftJoin('REFERENSI.REF_PROGRAM','REFERENSI.REF_PROGRAM.PROGRAM_ID','=','REFERENSI.REF_KEGIATAN.PROGRAM_ID')
                      ->select('REF_PROGRAM.PROGRAM_KODE', 'REF_PROGRAM.PROGRAM_NAMA', 'REF_PROGRAM.PROGRAM_ID')->distinct()->get();
        $no         = 1;
        $opsi       = '';
        $akb       = '';
        foreach ($data as $data) {
          $pagu        = Propri::where('PROGRAM_ID',$data->PROGRAM_ID)->where('SKPD_ID',$skpd)->where('PROPRI_TAHUN',$tahun);
          if($pagu->count()){
            $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$skpd.'\',\''.$data->PROGRAM_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapus(\''.$pagu->value('PROPRI_ID').'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
          }else{
            $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$skpd.'\',\''.$data->PROGRAM_ID.'\')" class="action-edit"><i class="mi-edit"></i></a></div>';
          }
          array_push($view, array( 'NO'       => $no++,
                                   'AKSI'     => $opsi,
                                   'KODE'     => $data->PROGRAM_KODE,
                                   'NAMA'   => $data->PROGRAM_NAMA,
                                   'ID'    => $data->PROGRAM_ID,
                                   'PAGU'    => $pagu->value('PROPRI_PAGU'),
                                   'KUNCI'    => $pagu->value('PROPRI_KUNCI')));
        }
      }
   		
		  $out = array("aaData"=>$view);    	
    	return Response::JSON($out);
     }
     
     public function getEdit($tahun,$status,$skpd,$id){
      $view       = array();
      if($status=="murni"){
        $data      = DB::table('BUDGETING.DAT_BL')
                      ->where('BUDGETING.DAT_BL.BL_TAHUN',$tahun)->where('BUDGETING.DAT_BL.SKPD_ID',$skpd)->where('REFERENSI.REF_PROGRAM.PROGRAM_ID',$id)
                      ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','BUDGETING.DAT_BL.SKPD_ID')
                      ->leftJoin('REFERENSI.REF_KEGIATAN','REFERENSI.REF_KEGIATAN.KEGIATAN_ID','=','BUDGETING.DAT_BL.KEGIATAN_ID')
                      ->leftJoin('REFERENSI.REF_PROGRAM','REFERENSI.REF_PROGRAM.PROGRAM_ID','=','REFERENSI.REF_KEGIATAN.PROGRAM_ID')
                      ->select('REF_SKPD.SKPD_NAMA', 'REF_PROGRAM.PROGRAM_KODE', 'REF_PROGRAM.PROGRAM_NAMA', 'REF_PROGRAM.PROGRAM_ID')->distinct()->get();
        $no         = 1;
        $opsi       = '';
        $akb       = '';
        foreach ($data as $data) {
          $pagu        = Propri::where('PROGRAM_ID',$data->PROGRAM_ID)->where('SKPD_ID',$skpd)->where('PROPRI_TAHUN',$tahun);
          if($pagu->count()){
            $opsi = "EDIT";
          }else{
            $opsi = "CREATE";
           }
          array_push($view, array( 'NO'       => $no++,
                                   'AKSI'     => $opsi,
                                   'PROPRI_TAHUN'     => $tahun,
                                   'SKPD_ID'   => $skpd,
                                   'SKPD_NAMA'   => $data->SKPD_NAMA,
                                   'PROGRAM_ID'    => $data->PROGRAM_ID,
                                   'PROGRAM_NAMA'    => $data->PROGRAM_NAMA,
                                   'PROGRAM_KODE'    => $data->PROGRAM_KODE,
                                   'PROPRI_ID'    => $pagu->value('PROPRI_ID'),
                                   'PROPRI_PAGU'    => $pagu->value('PROPRI_PAGU'),
                                   'PROPRI_KUNCI'    => $pagu->value('PROPRI_KUNCI')));
        }
      }
   		
		  $out = array("aaData"=>$view);    	
    	return Response::JSON($out);
   	}

}
