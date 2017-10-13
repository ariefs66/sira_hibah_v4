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
}
