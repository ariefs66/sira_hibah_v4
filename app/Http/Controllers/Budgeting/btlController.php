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
use App\Model\JenisGiat;
use App\Model\SumberDana;
use App\Model\Log;
use App\Model\Pagu;
use App\Model\Sasaran;
use App\Model\Tag;
use App\Model\Lokasi;
use App\Model\Satuan;
use App\Model\BTL;
use App\Model\BTLPerubahan;
use App\Model\Indikator;
use App\Model\Kunci;
use App\Model\Pekerjaan;
use App\Model\Rekening;
use App\Model\Komponen;
use App\Model\Rekom;
use App\Model\Rincian;
use App\Model\SKPD;
use App\Model\UserBudget;
use App\Model\Tahapan;
use App\Model\AKB_BTL;

class btlController extends Controller
{
    public function index($tahun, $status){
        $skpd       = SKPD::all();
      	$satuan 	= Satuan::all();
        if($status=="murni"){
          return View('budgeting.belanja-tidak-langsung.index',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd,'satuan'=>$satuan]);
        }
        else{
          return View('budgeting.belanja-tidak-langsung.index_perubahan',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd,'satuan'=>$satuan]);
        }
    }

    public function submitAdd($tahun,$status){
      if($status=="murni"){
        $btl    = new BTL;
        $btl->BTL_TAHUN     = $tahun;
        $btl->SUB_ID        = Input::get('SUB_ID');
        $btl->REKENING_ID     = Input::get('REKENING_ID');
        $btl->BTL_NAMA      = Input::get('BTL_NAMA');
        $btl->BTL_KETERANGAN  = Input::get('BTL_NAMA');
        $btl->BTL_TOTAL     = Input::get('BTL_TOTAL');
        $btl->BTL_VOLUME    = Input::get('BTL_VOL');
        $btl->BTL_KOEFISIEN   = Input::get('BTL_VOLUME').' '.Input::get('BTL_SATUAN');
        $btl->save();

          $datarek    = Rekening::where('REKENING_ID',Input::get('REKENING_ID'))->first();
          $databtl    = BTL::where('SUB_ID',Input::get('SUB_ID'))
                                  ->where('REKENING_ID',Input::get('REKENING_ID'))
                                  ->where('BTL_NAMA',Input::get('BTL_NAMA'))
                                  ->first();
      }
      else{
        $btl    = new BTLPerubahan;
        $btl->BTL_TAHUN     = $tahun;
        $btl->SUB_ID        = Input::get('SUB_ID');
        $btl->REKENING_ID     = Input::get('REKENING_ID');
        $btl->BTL_NAMA      = Input::get('BTL_NAMA');
        $btl->BTL_KETERANGAN  = Input::get('BTL_NAMA');
        $btl->BTL_TOTAL     = Input::get('BTL_TOTAL');
        $btl->BTL_VOLUME    = Input::get('BTL_VOL');
        $btl->BTL_KOEFISIEN   = Input::get('BTL_VOLUME').' '.Input::get('BTL_SATUAN');
        $btl->save();

          $datarek    = Rekening::where('REKENING_ID',Input::get('REKENING_ID'))->first();
          $databtl    = BTLPerubahan::where('SUB_ID',Input::get('SUB_ID'))
                                  ->where('REKENING_ID',Input::get('REKENING_ID'))
                                  ->where('BTL_NAMA',Input::get('BTL_NAMA'))
                                  ->first();
      }
        $log        = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Menambahkan BTL Rekening '.$datarek->REKENING_KODE.'-'.$datarek->REKENING_NAMA.' Jumlah '.Input::get('BTL_TOTAL');
        $log->LOG_DETAIL                        = 'BTL#'.$databtl->BTL_ID;
        $log->save();      
    	return 'Input Berhasil!';
    }

    public function submitEdit($tahun,$status){
      if($status=="murni"){
        BTL::where('BTL_ID',Input::get('BTL_ID'))->update([
            'SUB_ID'          => Input::get('SUB_ID'),
            'BTL_NAMA'        => Input::get('BTL_NAMA'),
            'BTL_KETERANGAN'  => Input::get('BTL_NAMA'),
            'REKENING_ID'     => Input::get('REKENING_ID'),
            'BTL_VOLUME'      => Input::get('BTL_VOL'),
            'BTL_KOEFISIEN'   => Input::get('BTL_VOLUME').' '.Input::get('BTL_SATUAN'),
            'BTL_TOTAL'       => Input::get('BTL_TOTAL'),
            'TIME_UPDATED'    => Carbon\Carbon::now(),
            'USER_UPDATED'    => Auth::user()->id
          ]);

        $datarek    = Rekening::where('REKENING_ID',Input::get('REKENING_ID'))->first();
        $databtl    = BTL::where('BTL_ID',Input::get('BTL_ID'))->first();
      }
      else{
        BTLPerubahan::where('BTL_ID',Input::get('BTL_ID'))->update([
            'SUB_ID'          => Input::get('SUB_ID'),
            'BTL_NAMA'        => Input::get('BTL_NAMA'),
            'BTL_KETERANGAN'  => Input::get('BTL_NAMA'),
            'REKENING_ID'     => Input::get('REKENING_ID'),
            'BTL_VOLUME'      => Input::get('BTL_VOL'),
            'BTL_KOEFISIEN'   => Input::get('BTL_VOLUME').' '.Input::get('BTL_SATUAN'),
            'BTL_TOTAL'       => Input::get('BTL_TOTAL'),
            'TIME_UPDATED'    => Carbon\Carbon::now(),
            'USER_UPDATED'    => Auth::user()->id
          ]);

        $datarek    = Rekening::where('REKENING_ID',Input::get('REKENING_ID'))->first();
        $databtl    = BTLPerubahan::where('BTL_ID',Input::get('BTL_ID'))->first();
      }
      
        $log              = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Merubah BTL Rekening '.$datarek->REKENING_KODE.'-'.$datarek->REKENING_NAMA.' Jumlah '.Input::get('BTL_TOTAL');
        $log->LOG_DETAIL                        = 'BTL#'.$databtl->BTL_ID;
        $log->save();
      return "Berhasil Ubah";
    }

    public function delete($tahun,$status){
      if($status=="murni"){
        $databtl     = BTL::where('BTL_ID',Input::get('BTL_ID'))->first();
        BTL::where('BTL_ID',Input::get('BTL_ID'))->delete();
      }
      else{
        $databtl     = BTLPerubahan::where('BTL_ID',Input::get('BTL_ID'))->first();
        BTLPerubahan::where('BTL_ID',Input::get('BTL_ID'))->update(array('BTL_TOTAL'=>0,'BTL_VOLUME'=>0,'BTL_KOEFISIEN'=>NULL,'BTL_PAJAK'=>0));
      }
        $log        = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Menghapus BTL '.$databtl->rekening->REKENING_KODE.'-'.$databtl->rekening->REKENING_NAMA.' Jumlah '.$databtl->BTL_TOTAL;
        $log->LOG_DETAIL                        = 'BTL#'.$databtl->BTL_ID;
        $log->save();
        
        return 'Berhasil!';      

    }

   	//API

    public function getsubunit($tahun,$status,$id){
        $data       = Subunit::where('SKPD_ID',$id)->get();;
        $view       = "";
        foreach($data as $d){
            $view .= "<option value='".$d->SUB_ID."'>".$d->SUB_NAMA."</option>";
        }
        return $view;
    }

   	public function getPegawai($tahun,$status){
      if($status=="murni"){
        $data       = DB::table('BUDGETING.DAT_BTL')
                      ->where('BUDGETING.DAT_BTL.BTL_TAHUN',$tahun)
                      ->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_BTL.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
                      ->leftJoin('REFERENSI.REF_SUB_UNIT','BUDGETING.DAT_BTL.SUB_ID','=','REFERENSI.REF_SUB_UNIT.SUB_ID')
                      ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','REFERENSI.REF_SUB_UNIT.SKPD_ID')
                      ->where('REKENING_KODE','like','5.1.1%');
        // if(Auth::user()->level == 1 or Auth::user()->level == 2 or Auth::user()->level == 3 or Auth::user()->level == 4){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        //     $data       = $data->where('DAT_BTL.SKPD_ID',$skpd); 
        // }elseif(Auth::user()->level == 5 or Auth::user()->level == 6){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
        //     $skpd_      = array(); 
        //     $i = 0;
        //     foreach($skpd as $s){
        //         $skpd_[$i]   = $s->SKPD_ID;
        //         $i++;
        //     } 
        //     $data       = $data->whereIn('DAT_BTL.SKPD_ID',$skpd_);         
        // }
        $data       = $data->groupBy('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA')
                      ->select('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA',DB::raw('SUM("BUDGETING"."DAT_BTL"."BTL_TOTAL") AS TOTAL'))
                      ->get();
        $view       = array();
        foreach ($data as $data) {
          array_push($view, array('ID'      =>$data->SKPD_ID,
                                  'KODE'    =>$data->SKPD_KODE,
                                  'NAMA'    =>$data->SKPD_NAMA,
                                  'REK'     =>'5.1.1',
                                  'TOTAL'   =>number_format($data->total,0,'.',',')));
        }
      }
      else{
        $data       = DB::table('BUDGETING.DAT_BTL_PERUBAHAN')
                      ->where('BUDGETING.DAT_BTL_PERUBAHAN.BTL_TAHUN',$tahun)
                      ->leftJoin('BUDGETING.DAT_BTL','BUDGETING.DAT_BTL.BTL_ID','=','BUDGETING.DAT_BTL_PERUBAHAN.BTL_ID')
                      ->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_BTL_PERUBAHAN.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
                      ->leftJoin('REFERENSI.REF_SUB_UNIT','BUDGETING.DAT_BTL_PERUBAHAN.SUB_ID','=','REFERENSI.REF_SUB_UNIT.SUB_ID')
                      ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','REFERENSI.REF_SUB_UNIT.SKPD_ID')
                      ->where('REKENING_KODE','like','5.1.1%');
        // if(Auth::user()->level == 1 or Auth::user()->level == 2 or Auth::user()->level == 3 or Auth::user()->level == 4){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        //     $data       = $data->where('DAT_BTL.SKPD_ID',$skpd); 
        // }elseif(Auth::user()->level == 5 or Auth::user()->level == 6){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
        //     $skpd_      = array(); 
        //     $i = 0;
        //     foreach($skpd as $s){
        //         $skpd_[$i]   = $s->SKPD_ID;
        //         $i++;
        //     } 
        //     $data       = $data->whereIn('DAT_BTL.SKPD_ID',$skpd_);         
        // }
        $data       = $data->groupBy('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA')
                      ->select('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA',DB::raw('SUM("BUDGETING"."DAT_BTL_PERUBAHAN"."BTL_TOTAL") AS TOTAL'),DB::raw('SUM("BUDGETING"."DAT_BTL"."BTL_TOTAL") AS TOTAL_MURNI'))
                      ->get();
        $view       = array();
        foreach ($data as $data) {
          array_push($view, array('ID'      =>$data->SKPD_ID,
                                  'KODE'    =>$data->SKPD_KODE,
                                  'NAMA'    =>$data->SKPD_NAMA,
                                  'REK'     =>'5.1.1',
                                  'TOTAL'   =>number_format($data->total,0,'.',','),
                                  'TOTAL_MURNI'   =>number_format($data->total_murni,0,'.',',')));
        }
      }
		  $out = array("aaData"=>$view);    	
    	return Response::JSON($out);
   	}

   	public function getSubsidi($tahun,$status){
      if($status=="murni"){
        $data       = DB::table('BUDGETING.DAT_BTL')
                      ->where('BUDGETING.DAT_BTL.BTL_TAHUN',$tahun)
                      ->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_BTL.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
                      ->leftJoin('REFERENSI.REF_SUB_UNIT','BUDGETING.DAT_BTL.SUB_ID','=','REFERENSI.REF_SUB_UNIT.SUB_ID')
                      ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','REFERENSI.REF_SUB_UNIT.SKPD_ID')
                      ->where('REKENING_KODE','like','5.1.3%');
        // if(Auth::user()->level == 1 or Auth::user()->level == 2 or Auth::user()->level == 3 or Auth::user()->level == 4){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        //     $data       = $data->where('DAT_BTL.SKPD_ID',$skpd); 
        // }elseif(Auth::user()->level == 5 or Auth::user()->level == 6){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
        //     $skpd_      = array(); 
        //     $i = 0;
        //     foreach($skpd as $s){
        //         $skpd_[$i]   = $s->SKPD_ID;
        //         $i++;
        //     } 
        //     $data       = $data->whereIn('DAT_BTL.SKPD_ID',$skpd_);         
        // }
        $data       = $data->groupBy('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA')
                      ->select('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA',DB::raw('SUM("BUDGETING"."DAT_BTL"."BTL_TOTAL") AS TOTAL'))
                      ->get();
        $view       = array();
        foreach ($data as $data) {
          array_push($view, array('ID'      =>$data->SKPD_ID,
                                  'KODE'    =>$data->SKPD_KODE,
                                  'NAMA'    =>$data->SKPD_NAMA,
                                  'REK'     =>'5.1.3',
                                  'TOTAL'   =>number_format($data->total,0,'.',',')));
        }
      }
      else{
        $data       = DB::table('BUDGETING.DAT_BTL_PERUBAHAN')
                      ->where('BUDGETING.DAT_BTL_PERUBAHAN.BTL_TAHUN',$tahun)
                      ->leftJoin('BUDGETING.DAT_BTL','BUDGETING.DAT_BTL.BTL_ID','=','BUDGETING.DAT_BTL_PERUBAHAN.BTL_ID')
                      ->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_BTL_PERUBAHAN.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
                      ->leftJoin('REFERENSI.REF_SUB_UNIT','BUDGETING.DAT_BTL_PERUBAHAN.SUB_ID','=','REFERENSI.REF_SUB_UNIT.SUB_ID')
                      ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','REFERENSI.REF_SUB_UNIT.SKPD_ID')
                      ->where('REKENING_KODE','like','5.1.3%');
        // if(Auth::user()->level == 1 or Auth::user()->level == 2 or Auth::user()->level == 3 or Auth::user()->level == 4){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        //     $data       = $data->where('DAT_BTL.SKPD_ID',$skpd); 
        // }elseif(Auth::user()->level == 5 or Auth::user()->level == 6){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
        //     $skpd_      = array(); 
        //     $i = 0;
        //     foreach($skpd as $s){
        //         $skpd_[$i]   = $s->SKPD_ID;
        //         $i++;
        //     } 
        //     $data       = $data->whereIn('DAT_BTL.SKPD_ID',$skpd_);         
        // }
        $data       = $data->groupBy('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA')
                      ->select('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA',DB::raw('SUM("BUDGETING"."DAT_BTL_PERUBAHAN"."BTL_TOTAL") AS TOTAL'),DB::raw('SUM("BUDGETING"."DAT_BTL"."BTL_TOTAL") AS TOTAL_MURNI'))
                      ->get();
        $view       = array();
        foreach ($data as $data) {
          array_push($view, array('ID'      =>$data->SKPD_ID,
                                  'KODE'    =>$data->SKPD_KODE,
                                  'NAMA'    =>$data->SKPD_NAMA,
                                  'REK'     =>'5.1.3',
                                  'TOTAL'   =>number_format($data->total,0,'.',','),
                                  'TOTAL_MURNI'   =>number_format($data->total_murni,0,'.',',')));
        }
      }
   		
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
   	}

   	public function getHibah($tahun,$status){
      if($status=="murni"){
        $data       = DB::table('BUDGETING.DAT_BTL')
                      ->where('BUDGETING.DAT_BTL.BTL_TAHUN',$tahun)
                      ->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_BTL.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
                      ->leftJoin('REFERENSI.REF_SUB_UNIT','BUDGETING.DAT_BTL.SUB_ID','=','REFERENSI.REF_SUB_UNIT.SUB_ID')
                      ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','REFERENSI.REF_SUB_UNIT.SKPD_ID')
                      ->where('REKENING_KODE','like','5.1.4%');
        // if(Auth::user()->level == 1 or Auth::user()->level == 2 or Auth::user()->level == 3 or Auth::user()->level == 4){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        //     $data       = $data->where('DAT_BTL.SKPD_ID',$skpd); 
        // }elseif(Auth::user()->level == 5 or Auth::user()->level == 6){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
        //     $skpd_      = array(); 
        //     $i = 0;
        //     foreach($skpd as $s){
        //         $skpd_[$i]   = $s->SKPD_ID;
        //         $i++;
        //     } 
        //     $data       = $data->whereIn('DAT_BTL.SKPD_ID',$skpd_);         
        // }
        $data       = $data->groupBy('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA')
                      ->select('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA',DB::raw('SUM("BUDGETING"."DAT_BTL"."BTL_TOTAL") AS TOTAL'))
                      ->get();
        $view       = array();
        foreach ($data as $data) {
          array_push($view, array('ID'      =>$data->SKPD_ID,
                                  'KODE'    =>$data->SKPD_KODE,
                                  'NAMA'    =>$data->SKPD_NAMA,
                                  'REK'     =>'5.1.4',
                                  'TOTAL'   =>number_format($data->total,0,'.',',')));
        }
      }
      else{
        $data       = DB::table('BUDGETING.DAT_BTL_PERUBAHAN')
                      ->where('BUDGETING.DAT_BTL_PERUBAHAN.BTL_TAHUN',$tahun)
                      ->leftJoin('BUDGETING.DAT_BTL','BUDGETING.DAT_BTL.BTL_ID','=','BUDGETING.DAT_BTL_PERUBAHAN.BTL_ID')
                      ->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_BTL_PERUBAHAN.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
                      ->leftJoin('REFERENSI.REF_SUB_UNIT','BUDGETING.DAT_BTL_PERUBAHAN.SUB_ID','=','REFERENSI.REF_SUB_UNIT.SUB_ID')
                      ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','REFERENSI.REF_SUB_UNIT.SKPD_ID')
                      ->where('REKENING_KODE','like','5.1.4%');
        // if(Auth::user()->level == 1 or Auth::user()->level == 2 or Auth::user()->level == 3 or Auth::user()->level == 4){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        //     $data       = $data->where('DAT_BTL.SKPD_ID',$skpd); 
        // }elseif(Auth::user()->level == 5 or Auth::user()->level == 6){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
        //     $skpd_      = array(); 
        //     $i = 0;
        //     foreach($skpd as $s){
        //         $skpd_[$i]   = $s->SKPD_ID;
        //         $i++;
        //     } 
        //     $data       = $data->whereIn('DAT_BTL.SKPD_ID',$skpd_);         
        // }
        $data       = $data->groupBy('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA')
                      ->select('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA',DB::raw('SUM("BUDGETING"."DAT_BTL_PERUBAHAN"."BTL_TOTAL") AS TOTAL'),DB::raw('SUM("BUDGETING"."DAT_BTL"."BTL_TOTAL") AS TOTAL_MURNI'))
                      ->get();
        $view       = array();
        foreach ($data as $data) {
          array_push($view, array('ID'      =>$data->SKPD_ID,
                                  'KODE'    =>$data->SKPD_KODE,
                                  'NAMA'    =>$data->SKPD_NAMA,
                                  'REK'     =>'5.1.4',
                                  'TOTAL'   =>number_format($data->total,0,'.',','),
                                  'TOTAL_MURNI'   =>number_format($data->total_murni,0,'.',',')));
        }
      }
   		
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
   	}

   	public function getBantuan($tahun,$status){
      if($status=="murni"){
        $data       = DB::table('BUDGETING.DAT_BTL')
                      ->where('BUDGETING.DAT_BTL.BTL_TAHUN',$tahun)
                      ->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_BTL.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
                      ->leftJoin('REFERENSI.REF_SUB_UNIT','BUDGETING.DAT_BTL.SUB_ID','=','REFERENSI.REF_SUB_UNIT.SUB_ID')
                      ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','REFERENSI.REF_SUB_UNIT.SKPD_ID')
                      ->where('REKENING_KODE','like','5.1.7%');
        // if(Auth::user()->level == 1 or Auth::user()->level == 2 or Auth::user()->level == 3 or Auth::user()->level == 4){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        //     $data       = $data->where('DAT_BTL.SKPD_ID',$skpd); 
        // }elseif(Auth::user()->level == 5 or Auth::user()->level == 6){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
        //     $skpd_      = array(); 
        //     $i = 0;
        //     foreach($skpd as $s){
        //         $skpd_[$i]   = $s->SKPD_ID;
        //         $i++;
        //     } 
        //     $data       = $data->whereIn('DAT_BTL.SKPD_ID',$skpd_);         
        // }
        $data       = $data->groupBy('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA')
                      ->select('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA',DB::raw('SUM("BUDGETING"."DAT_BTL"."BTL_TOTAL") AS TOTAL'))
                      ->get();
        $view       = array();
        foreach ($data as $data) {
          array_push($view, array('ID'      =>$data->SKPD_ID,
                                  'KODE'    =>$data->SKPD_KODE,
                                  'NAMA'    =>$data->SKPD_NAMA,
                                  'REK'     =>'5.1.7',
                                  'TOTAL'   =>number_format($data->total,0,'.',',')));
        }
      }
      else{
        $data       = DB::table('BUDGETING.DAT_BTL_PERUBAHAN')
                      ->where('BUDGETING.DAT_BTL_PERUBAHAN.BTL_TAHUN',$tahun)
                      ->leftJoin('BUDGETING.DAT_BTL','BUDGETING.DAT_BTL.BTL_ID','=','BUDGETING.DAT_BTL_PERUBAHAN.BTL_ID')
                      ->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_BTL_PERUBAHAN.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
                      ->leftJoin('REFERENSI.REF_SUB_UNIT','BUDGETING.DAT_BTL_PERUBAHAN.SUB_ID','=','REFERENSI.REF_SUB_UNIT.SUB_ID')
                      ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','REFERENSI.REF_SUB_UNIT.SKPD_ID')
                      ->where('REKENING_KODE','like','5.1.7%');
        // if(Auth::user()->level == 1 or Auth::user()->level == 2 or Auth::user()->level == 3 or Auth::user()->level == 4){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        //     $data       = $data->where('DAT_BTL.SKPD_ID',$skpd); 
        // }elseif(Auth::user()->level == 5 or Auth::user()->level == 6){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
        //     $skpd_      = array(); 
        //     $i = 0;
        //     foreach($skpd as $s){
        //         $skpd_[$i]   = $s->SKPD_ID;
        //         $i++;
        //     } 
        //     $data       = $data->whereIn('DAT_BTL.SKPD_ID',$skpd_);         
        // }
        $data       = $data->groupBy('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA')
                      ->select('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA',DB::raw('SUM("BUDGETING"."DAT_BTL_PERUBAHAN"."BTL_TOTAL") AS TOTAL'),DB::raw('SUM("BUDGETING"."DAT_BTL"."BTL_TOTAL") AS TOTAL_MURNI'))
                      ->get();
        $view       = array();
        foreach ($data as $data) {
          array_push($view, array('ID'      =>$data->SKPD_ID,
                                  'KODE'    =>$data->SKPD_KODE,
                                  'NAMA'    =>$data->SKPD_NAMA,
                                  'REK'     =>'5.1.7',
                                  'TOTAL'   =>number_format($data->total,0,'.',','),
                                  'TOTAL_MURNI'   =>number_format($data->total_murni,0,'.',',')));
        }
      }
   		
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
   	}
   	public function getBTT($tahun,$status){
      if($status=="murni"){
        $data       = DB::table('BUDGETING.DAT_BTL')
                      ->where('BUDGETING.DAT_BTL.BTL_TAHUN',$tahun)
                      ->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_BTL.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
                      ->leftJoin('REFERENSI.REF_SUB_UNIT','BUDGETING.DAT_BTL.SUB_ID','=','REFERENSI.REF_SUB_UNIT.SUB_ID')
                      ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','REFERENSI.REF_SUB_UNIT.SKPD_ID')
                      ->where('REKENING_KODE','like','5.1.8%');
        // if(Auth::user()->level == 1 or Auth::user()->level == 2 or Auth::user()->level == 3 or Auth::user()->level == 4){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        //     $data       = $data->where('DAT_BTL.SKPD_ID',$skpd); 
        // }elseif(Auth::user()->level == 5 or Auth::user()->level == 6){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
        //     $skpd_      = array(); 
        //     $i = 0;
        //     foreach($skpd as $s){
        //         $skpd_[$i]   = $s->SKPD_ID;
        //         $i++;
        //     } 
        //     $data       = $data->whereIn('DAT_BTL.SKPD_ID',$skpd_);         
        // }
        $data       = $data->groupBy('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA')
                      ->select('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA',DB::raw('SUM("BUDGETING"."DAT_BTL"."BTL_TOTAL") AS TOTAL'))
                      ->get();
        $view       = array();
        foreach ($data as $data) {
          array_push($view, array('ID'      =>$data->SKPD_ID,
                                  'KODE'    =>$data->SKPD_KODE,
                                  'NAMA'    =>$data->SKPD_NAMA,
                                  'REK'     =>'5.1.8',
                                  'TOTAL'   =>number_format($data->total,0,'.',',')));
        }
      }
      else{
        $data       = DB::table('BUDGETING.DAT_BTL_PERUBAHAN')
                      ->where('BUDGETING.DAT_BTL_PERUBAHAN.BTL_TAHUN',$tahun)
                      ->leftJoin('BUDGETING.DAT_BTL','BUDGETING.DAT_BTL.BTL_ID','=','BUDGETING.DAT_BTL_PERUBAHAN.BTL_ID')
                      ->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_BTL_PERUBAHAN.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
                      ->leftJoin('REFERENSI.REF_SUB_UNIT','BUDGETING.DAT_BTL_PERUBAHAN.SUB_ID','=','REFERENSI.REF_SUB_UNIT.SUB_ID')
                      ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','REFERENSI.REF_SUB_UNIT.SKPD_ID')
                      ->where('REKENING_KODE','like','5.1.8%');
        // if(Auth::user()->level == 1 or Auth::user()->level == 2 or Auth::user()->level == 3 or Auth::user()->level == 4){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        //     $data       = $data->where('DAT_BTL.SKPD_ID',$skpd); 
        // }elseif(Auth::user()->level == 5 or Auth::user()->level == 6){
        //     $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
        //     $skpd_      = array(); 
        //     $i = 0;
        //     foreach($skpd as $s){
        //         $skpd_[$i]   = $s->SKPD_ID;
        //         $i++;
        //     } 
        //     $data       = $data->whereIn('DAT_BTL.SKPD_ID',$skpd_);         
        // }
        $data       = $data->groupBy('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA')
                      ->select('REF_SUB_UNIT.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA',DB::raw('SUM("BUDGETING"."DAT_BTL_PERUBAHAN"."BTL_TOTAL") AS TOTAL'),DB::raw('SUM("BUDGETING"."DAT_BTL"."BTL_TOTAL") AS TOTAL_MURNI'))
                      ->get();
        $view       = array();
        foreach ($data as $data) {
          array_push($view, array('ID'      =>$data->SKPD_ID,
                                  'KODE'    =>$data->SKPD_KODE,
                                  'NAMA'    =>$data->SKPD_NAMA,
                                  'REK'     =>'5.1.8',
                                  'TOTAL'   =>number_format($data->total,0,'.',','),
                                  'TOTAL_MURNI'   =>number_format($data->total_murni,0,'.',',')));
        }
      }
   		
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
   	}

   	public function getRekening($tahun,$status,$id){
   		$data       = Rekening::where('REKENING_KODE','like',$id.'%')
   						->where('REKENING_TAHUN',$tahun)
   						->orderBy('REKENING_ID')->get();        
        $view       = "";
        foreach($data as $d){
        	if(strlen($d->REKENING_KODE) == 11){
	            $view .= "<option value='".$d->REKENING_ID."'>".$d->REKENING_KODE.' - '.$d->REKENING_NAMA."</option>";
        	}
        }
        return $view;
   	}

   	public function getDetail($tahun,$status,$skpd,$id){
      if($status=="murni"){
        $data   = BTL::whereHas('rekening', function($q) use ($id){
              $q->where('REKENING_KODE','like',$id.'%');  
            })->whereHas('subunit',function($x) use($skpd){
              $x->where('SKPD_ID',$skpd);
            })->where('BTL_TAHUN',$tahun)->get();
        $view       = array();
        $no         = 1;
        $opsi       = '';
        $akb       = '';
        foreach ($data as $data) {
          if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1){
            $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->BTL_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapus(\''.$data->BTL_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
             $akb = '<div class="action visible pull-right"><a href="/main/'.$tahun.'/'.$status.'/belanja-tidak-langsung/akb/'.$skpd.'" class="action-edit" target="_blank"><i class="mi-edit"></i></a></div>';
          }else{
            $opsi = '-';
            $akb = '-';
          }
          array_push($view, array( 'NO'       => $no++,
                                   'AKSI'     => $opsi,
                                   'AKB'     => $akb,
                                   'REKENING'   => $data->rekening->REKENING_KODE,
                                   'RINCIAN'    => $data->BTL_NAMA,
                                   'TOTAL'    => number_format($data->BTL_TOTAL,0,'.',',')));
        }
      }
      else{
        $data   = BTLPerubahan::whereHas('rekening', function($q) use ($id){
              $q->where('REKENING_KODE','like',$id.'%');  
            })->whereHas('subunit',function($x) use($skpd){
              $x->where('SKPD_ID',$skpd);
            })->where('BTL_TAHUN',$tahun)->get();
        $view       = array();
        $no         = 1;
        $opsi       = '';
        foreach ($data as $data) {
          if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1){
            $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->BTL_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapus(\''.$data->BTL_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
          }else{
            $opsi = '-';
          }
          array_push($view, array( 'NO'       => $no++,
                                   'AKSI'     => $opsi,
                                   'REKENING'   => $data->rekening->REKENING_KODE,
                                   'RINCIAN'    => $data->BTL_NAMA,
                                   'TOTAL'    => number_format($data->BTL_TOTAL,0,'.',',')));
        }
      }
   		
		  $out = array("aaData"=>$view);    	
    	return Response::JSON($out);
   	}

    public function getId($tahun,$status,$id){
      if($status=="murni"){
        $data   = BTL::where('BTL_ID',$id)->first();
        $data->REKENING_KODE   = $data->rekening->REKENING_KODE;
        $data->REKENING_NAMA   = $data->rekening->REKENING_NAMA;
        $data->SUB_NAMA        = $data->subunit->SUB_NAMA;
        $data->JENIS_BTL       = substr($data->rekening->REKENING_KODE, 0,5);
        $data->SKPD            = $data->subunit->SKPD_ID;
      }
      else{
        $data   = BTLPerubahan::where('BTL_ID',$id)->first();
        $data->REKENING_KODE   = $data->rekening->REKENING_KODE;
        $data->REKENING_NAMA   = $data->rekening->REKENING_NAMA;
        $data->SUB_NAMA        = $data->subunit->SUB_NAMA;
        $data->JENIS_BTL       = substr($data->rekening->REKENING_KODE, 0,5);
        $data->SKPD            = $data->subunit->SKPD_ID;
      }
      
      return Response::JSON($data);
    }

     public function showAKB($tahun,$status,$id){
        $now = date('Y-m-d H:m:s');
        if($status == 'murni')
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->orderBy('TAHAPAN_ID','desc')->first();
        else
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->orderBy('TAHAPAN_ID','desc')->first();
        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }
        if($status == 'murni') {
          /*$btl         = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                              ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                              ->where('BTL_TAHUN',$tahun)->where('SKPD_ID',$id)->get();*/
          $btl = BTL::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                    ->leftjoin('BUDGETING.DAT_AKB_BTL',function($join){
                        $join->on('DAT_AKB_BTL.BTL_ID','=','DAT_BTL.BTL_ID')->on('DAT_AKB_BTL.REKENING_ID','=','DAT_BTL.REKENING_ID');
                    })
                    ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$id)
                    ->orderBy("REKENING_NAMA")
                    ->selectRaw(' "DAT_BTL"."BTL_ID", "DAT_BTL"."REKENING_ID", "REKENING_NAMA", "BTL_TOTAL" AS total, "AKB_JAN", "AKB_FEB", "AKB_MAR", "AKB_APR", "AKB_MEI", "AKB_JUN", "AKB_JUL", "AKB_AUG", "AKB_SEP", "AKB_OKT", "AKB_NOV", "AKB_DES" ')
                    ->get();                              
        }
        else 
          $btl         = BTLPerubahan::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                              ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                              ->where('BTL_TAHUN',$tahun)->where('SKPD_ID',$id)->get();

          $skpd         = SKPD::where('SKPD_ID',$id)->first();


        if($status == 'murni')
         return View('budgeting.belanja-tidak-langsung.akb-btl',['tahun'=>$tahun,'status'=>$status,'btl'=>$btl,'BTL_ID'=>$id, 'thp'=>$thp, 'skpd'=>$skpd ]);
        else
         return View('budgeting.belanja-tidak-langsung.akb-btl',['tahun'=>$tahun,'status'=>$status,'btl'=>$btl,'BTL_ID'=>$id, 'thp'=>$thp, 'skpd'=>$skpd]);
    }

     public function showDataAKB($tahun,$status,$id){
        $now = date('Y-m-d H:m:s');
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->orderBy('TAHAPAN_ID','desc')->first();
        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }

        $data = BTL::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                    ->leftjoin('BUDGETING.DAT_AKB_BTL',function($join){
                        $join->on('DAT_AKB_BTL.BTL_ID','=','DAT_BTL.BTL_ID')->on('DAT_AKB_BTL.REKENING_ID','=','DAT_BTL.REKENING_ID');
                    })
                    ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$id)
                    ->orderBy("REKENING_NAMA")
                    ->selectRaw(' "DAT_BTL"."BTL_ID", "DAT_BTL"."REKENING_ID", "REKENING_NAMA", "BTL_TOTAL" AS total, "AKB_JAN", "AKB_FEB", "AKB_MAR", "AKB_APR", "AKB_MEI", "AKB_JUN", "AKB_JUL", "AKB_AUG", "AKB_SEP", "AKB_OKT", "AKB_NOV", "AKB_DES" ')
                    ->get(); 
       
                 //   dd($data);

        $view       = array();
        $i         = 1;
        
        foreach ($data as $data) {

            $getAkb = AKB_BTL::where('BTL_ID',$data->BTL_ID)->where('REKENING_ID',$data->REKENING_ID)->value('AKB_BTL_ID');            

            if(($thp == 1 or Auth::user()->level == 8 ) and Auth::user()->active == 1){
                if(empty($getAkb) ){
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return ubah(\''.$data->BTL_ID.'\',\''.$data->REKENING_ID.'\')"><i class="fa fa-pencil-square"></i>Tambah</a></li>
                    <li class="divider"></li><li><a onclick="return info(\''.$data->REKENING_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                }else{
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return ubah(\''.$data->BTL_ID.'\',\''.$data->REKENING_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a></li>
                <li class="divider"></li><li><a onclick="return info(\''.$data->REKENING_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                }
            }else{
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return info(\''.$data->REKENING_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';                
            }

            if(Auth::user()->level == 8){
                 $checkbox = '<div class="m-t-n-lg">
                                  <label class="i-checks">
                                    <input type="checkbox" value="'.$data->REKENING_ID.'" class="cb" id="cb-'.$data->REKENING_ID.'"/><i></i>
                                  </label>
                            </div>';
                $no        = $checkbox.$no;

            }

            $tri1 = $data->AKB_JAN + $data->AKB_FEB + $data->AKB_MAR;
            $tri2 = $data->AKB_APR + $data->AKB_MEI + $data->AKB_JUN;
            $tri3 = $data->AKB_JUL + $data->AKB_AUG + $data->AKB_SEP;
            $tri4 = $data->AKB_OKT + $data->AKB_NOV + $data->AKB_DES;

            array_push($view, array( 'NO'             =>$no,
                                     'REKENING'       =>$data->REKENING_KODE.'<br><p class="text-orange">'.$data->REKENING_NAMA.'</p>',
                                     'TOTAL'          =>number_format($data->total,0,'.',','),
                                     'JANUARI'        =>number_format($data->AKB_JAN,0,'.',','),
                                     'FEBRUARI'       =>number_format($data->AKB_FEB,0,'.',','),
                                     'MARET'          =>number_format($data->AKB_MAR,0,'.',','),
                                     'APRIL'          =>number_format($data->AKB_APR,0,'.',','),
                                     'MEI'            =>number_format($data->AKB_MEI,0,'.',','),
                                     'JUNI'           =>number_format($data->AKB_JUN,0,'.',','),
                                     'JULI'           =>number_format($data->AKB_JUL,0,'.',','),
                                     'AGUSTUS'        =>number_format($data->AKB_AUG,0,'.',','),
                                     'SEPTEMBER'      =>number_format($data->AKB_SEP,0,'.',','),
                                     'OKTOBER'        =>number_format($data->AKB_OKT,0,'.',','),
                                     'NOVEMBER'       =>number_format($data->AKB_NOV,0,'.',','),
                                     'DESEMBER'       =>number_format($data->AKB_DES,0,'.',','),
                                     'TRIWULAN1'      =>number_format($tri1,0,'.',','),
                                     'TRIWULAN2'      =>number_format($tri2,0,'.',','),
                                     'TRIWULAN3'      =>number_format($tri3,0,'.',','),
                                     'TRIWULAN4'      =>number_format($tri4,0,'.',','),
                                 ));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
        return $view;
    }

     public function detailAKB($tahun,$status,$btl_id,$rek_id){
        if($status == 'murni'){

             $data = BTL::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                    ->leftjoin('BUDGETING.DAT_AKB_BTL',function($join){
                        $join->on('DAT_AKB_BTL.BTL_ID','=','DAT_BTL.BTL_ID')->on('DAT_AKB_BTL.REKENING_ID','=','DAT_BTL.REKENING_ID');
                    })
                    ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('DAT_BTL.BTL_ID',$btl_id)
                    ->where('DAT_BTL.REKENING_ID',$rek_id)
                    ->orderBy("REKENING_NAMA")
                    ->selectRaw(' "DAT_BTL"."BTL_ID", "DAT_BTL"."REKENING_ID", "REKENING_KODE", "REKENING_NAMA", "BTL_TOTAL" AS total, "AKB_JAN", "AKB_FEB", "AKB_MAR", "AKB_APR", "AKB_MEI", "AKB_JUN", "AKB_JUL", "AKB_AUG", "AKB_SEP", "AKB_OKT", "AKB_NOV", "AKB_DES" ')
                    ->first();

        }

        $out    = [ //'DATA'          => $data,
                    'REKENING_KODE' => $data->REKENING_KODE,
                    'REKENING_NAMA' => $data->REKENING_NAMA,
                    'TOTAL'         => number_format($data->total,0,'.',','),
                    (empty($data->AKB_JAN))?$jan=0:$jan=$data->AKB_JAN,
                    (empty($data->AKB_FEB))?$feb=0:$feb=$data->AKB_FEB,
                    (empty($data->AKB_MAR))?$mar=0:$mar=$data->AKB_MAR,
                    (empty($data->AKB_APR))?$apr=0:$apr=$data->AKB_APR,
                    (empty($data->AKB_MEI))?$mei=0:$mei=$data->AKB_MEI,
                    (empty($data->AKB_JUN))?$jun=0:$jun=$data->AKB_JUN,
                    (empty($data->AKB_JUL))?$jul=0:$jul=$data->AKB_JUL,
                    (empty($data->AKB_AUG))?$agu=0:$agu=$data->AKB_AUG,
                    (empty($data->AKB_SEP))?$sep=0:$sep=$data->AKB_SEP,
                    (empty($data->AKB_OKT))?$okt=0:$okt=$data->AKB_OKT,
                    (empty($data->AKB_NOV))?$nov=0:$nov=$data->AKB_NOV,
                    (empty($data->AKB_DES))?$des=0:$des=$data->AKB_DES,
                    'AKB_JAN'       => $jan,
                    'AKB_FEB'       => $feb,
                    'AKB_MAR'       => $mar,
                    'AKB_APR'       => $apr,
                    'AKB_MEI'       => $mei,
                    'AKB_JUN'       => $jun,
                    'AKB_JUL'       => $jul,
                    'AKB_AUG'       => $agu,
                    'AKB_SEP'       => $sep,
                    'AKB_OKT'       => $okt,
                    'AKB_NOV'       => $nov,
                    'AKB_DES'       => $des,
                    'REKENING_ID'   => $data->REKENING_ID,
                    'BTL_ID'        => $data->BTL_ID,
                    ];
        return $out;
    }


     public function submitAKBEdit($tahun,$status){
        if($status == 'murni') {
            $akb_btl = AKB_BTL::where('BTL_ID',Input::get('btl_id'))
                         ->where('REKENING_ID',Input::get('rek_id'))->value('AKB_BTL_ID');

            if(empty($akb_btl)){
                $akb = new AKB_BTL;
                $akb->BTL_ID             = Input::get('btl_id');
                $akb->REKENING_ID        = Input::get('rek_id');
                $akb->AKB_JAN            = Input::get('jan');
                $akb->AKB_FEB            = Input::get('feb');
                $akb->AKB_MAR            = Input::get('mar');
                $akb->AKB_APR            = Input::get('apr');
                $akb->AKB_MEI            = Input::get('mei');
                $akb->AKB_JUN            = Input::get('jun');
                $akb->AKB_JUL            = Input::get('jul');
                $akb->AKB_AUG            = Input::get('agu');
                $akb->AKB_SEP            = Input::get('sep');
                $akb->AKB_OKT            = Input::get('okt');
                $akb->AKB_NOV            = Input::get('nov');
                $akb->AKB_DES            = Input::get('des');
                $akb->USER_CREATED       = Auth::user()->id;
                $akb->TIME_CREATED       = Carbon\Carbon::now();
                $akb->IP_CREATED         = $_SERVER['REMOTE_ADDR'];
                $akb->save(); 

                return 1; 

            }else{
                AKB_BTL::where('BTL_ID',Input::get('btl_id'))
                         ->where('REKENING_ID',Input::get('rek_id'))
                 ->update([
                        'AKB_JAN'        => Input::get('jan'),
                        'AKB_FEB'        => Input::get('feb'),
                        'AKB_MAR'        => Input::get('mar'),
                        'AKB_APR'        => Input::get('apr'),
                        'AKB_MEI'        => Input::get('mei'),
                        'AKB_JUN'        => Input::get('jun'),
                        'AKB_JUL'        => Input::get('jul'),
                        'AKB_AUG'        => Input::get('agu'),
                        'AKB_SEP'        => Input::get('sep'),
                        'AKB_OKT'        => Input::get('okt'),
                        'AKB_NOV'        => Input::get('nov'),
                        'AKB_DES'        => Input::get('des')
                        ]); 

                 return 1; 
            }                

             return 0; 
               
        }
        else return $this->submitAKBEditPerubahan($tahun,$status);
    }

}
