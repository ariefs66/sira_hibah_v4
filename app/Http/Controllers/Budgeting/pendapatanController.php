<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use View;
use Carbon;
use Response;
use DB;
use Auth;
use App\Model\Program;
use App\Model\Kegiatan;
use App\Model\JenisGiat;
use App\Model\SumberDana;
use App\Model\Pagu;
use App\Model\Sasaran;
use App\Model\Tag;
use App\Model\Lokasi;
use App\Model\Subunit;
use App\Model\Satuan;
use App\Model\BTL;
use App\Model\Indikator;
use App\Model\Kunci;
use App\Model\Log;
use App\Model\Pekerjaan;
use App\Model\Rekening;
use App\Model\Komponen;
use App\Model\Rekom;
use App\Model\Rincian;
use App\Model\SKPD;
use App\Model\Pendapatan;
use App\Model\PendapatanPerubahan;
class pendapatanController extends Controller
{
    public function index($tahun,$status){
      $skpd     = SKPD::all();
      $rekening   = Rekening::where('REKENING_KODE','like','4%')->whereRaw('length("REKENING_KODE") = 11')->get();

      if($status=='murni'){
        $anggaran       = DB::table('BUDGETING.DAT_PENDAPATAN')->where('DAT_PENDAPATAN.PENDAPATAN_TAHUN',$tahun)
                ->sum('PENDAPATAN_TOTAL');
          return View('budgeting.pendapatan.index',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd,'rekening'=>$rekening,'anggaran'=>number_format($anggaran,0,'.',',')]);
      }else{
        $anggaran       = DB::table('BUDGETING.DAT_PENDAPATAN_PERUBAHAN')->where('DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_TAHUN',$tahun)
                ->sum('PENDAPATAN_TOTAL');
                //dd($anggaran);
          return View('budgeting.pendapatan.index_perubahan',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd,'rekening'=>$rekening,'anggaran'=>number_format($anggaran,0,'.',',')]);
      }
    }

    public function submitAdd($tahun,$status){

      if($status=='murni'){
        $pendapatan   = new Pendapatan;
        $pendapatan->PENDAPATAN_TAHUN   = $tahun;
        $pendapatan->SUB_ID           = Input::get('SUB_ID');
        $pendapatan->REKENING_ID      = Input::get('REKENING_ID');
        $pendapatan->PENDAPATAN_NAMA    = Input::get('PENDAPATAN_NAMA');
        $pendapatan->PENDAPATAN_KETERANGAN  = Input::get('PENDAPATAN_NAMA');
        $pendapatan->PENDAPATAN_TOTAL   = Input::get('PENDAPATAN_TOTAL');
        $pendapatan->save();

          $datarek    = Rekening::where('REKENING_ID',Input::get('REKENING_ID'))->first();
          $datapen    = Pendapatan::where('SUB_ID',Input::get('SUB_ID'))
                                  ->where('REKENING_ID',Input::get('REKENING_ID'))
                                  ->where('PENDAPATAN_NAMA',Input::get('PENDAPATAN_NAMA'))
                                  ->first();
          $log        = new Log;
          $log->LOG_TIME                          = Carbon\Carbon::now();
          $log->USER_ID                           = Auth::user()->id;
          $log->LOG_ACTIVITY                      = 'Menambahkan Pendapatan Rekening '.$datarek->REKENING_KODE.'-'.$datarek->REKENING_NAMA.' Jumlah '.Input::get('PENDAPATAN_TOTAL');
          $log->LOG_DETAIL                        = 'PD#'.$datapen->PENDAPATAN_ID;
          $log->save();
        return "Input Berhasil!";

      }else{
        $pendapatan   = new Pendapatan;
        $pendapatan->PENDAPATAN_TAHUN   = $tahun;
        $pendapatan->SUB_ID           = Input::get('SUB_ID');
        $pendapatan->REKENING_ID      = Input::get('REKENING_ID');
        $pendapatan->PENDAPATAN_NAMA    = Input::get('PENDAPATAN_NAMA');
        $pendapatan->PENDAPATAN_KETERANGAN  = Input::get('PENDAPATAN_NAMA');
        $pendapatan->PENDAPATAN_TOTAL   = Input::get('PENDAPATAN_TOTAL');
        $pendapatan->save();

          $datarek    = Rekening::where('REKENING_ID',Input::get('REKENING_ID'))->first();
          $datapen    = Pendapatan::where('SUB_ID',Input::get('SUB_ID'))
                                  ->where('REKENING_ID',Input::get('REKENING_ID'))
                                  ->where('PENDAPATAN_NAMA',Input::get('PENDAPATAN_NAMA'))
                                  ->first();
          $log        = new Log;
          $log->LOG_TIME                          = Carbon\Carbon::now();
          $log->USER_ID                           = Auth::user()->id;
          $log->LOG_ACTIVITY                      = 'Menambahkan Pendapatan Rekening '.$datarek->REKENING_KODE.'-'.$datarek->REKENING_NAMA.' Jumlah '.Input::get('PENDAPATAN_TOTAL');
          $log->LOG_DETAIL                        = 'PD#'.$datapen->PENDAPATAN_ID;
          $log->save();
        return "Input Berhasil!";
      }
    	
    }

    public function submitEdit(){
        Pendapatan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->update([
          'SUB_ID'                  => Input::get('SUB_ID'),
          'PENDAPATAN_NAMA'         => Input::get('PENDAPATAN_NAMA'),
          'PENDAPATAN_KETERANGAN'   => Input::get('PENDAPATAN_NAMA'),
          'REKENING_ID'             => Input::get('REKENING_ID'),
          'PENDAPATAN_TOTAL'        => Input::get('PENDAPATAN_TOTAL'),
          'TIME_UPDATED'            => Carbon\Carbon::now(),
          'USER_UPDATED'            => Auth::user()->id
        ]);

      $datarek    = Rekening::where('REKENING_ID',Input::get('REKENING_ID'))->first();
      $datapd    = Pendapatan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->first();
        $log              = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Merubah Pendapatan Rekening '.$datarek->REKENING_KODE.'-'.$datarek->REKENING_NAMA.' Jumlah '.Input::get('PENDAPATAN_TOTAL');
        $log->LOG_DETAIL                        = 'PD#'.$datapd->PENDAPATAN_ID;
        $log->save();
      return "Berhasil Ubah";

    }

    public function delete(){
        $datapendapatan     = Pendapatan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->first();
        $log        = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Menghapus Pendapatan '.$datapendapatan->rekening->REKENING_KODE.'-'.$datapendapatan->rekening->REKENING_NAMA.' Jumlah '.$datapendapatan->PENDAPATAN_TOTAL;
        $log->LOG_DETAIL                        = 'PD#'.$datapendapatan->PENDAPATAN_ID;
        $log->save();
        Pendapatan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->delete();
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
    public function getPendapatan($tahun,$status){
   		$data       = DB::table('BUDGETING.DAT_PENDAPATAN')
   						->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_PENDAPATAN.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
   						->leftJoin('REFERENSI.REF_SUB_UNIT','BUDGETING.DAT_PENDAPATAN.SUB_ID','=','REFERENSI.REF_SUB_UNIT.SUB_ID')
                        ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','REFERENSI.REF_SUB_UNIT.SKPD_ID')
   						->groupBy('REFERENSI.REF_SKPD.SKPD_ID','SKPD_KODE','SKPD_NAMA')
   						->select('REFERENSI.REF_SKPD.SKPD_ID','SKPD_KODE','SKPD_NAMA',DB::raw('SUM("PENDAPATAN_TOTAL") AS TOTAL'))
   						->get();
    	$view 			= array();
    	foreach ($data as $data) {
    		array_push($view, array( 'ID'			=>$data->SKPD_ID,
    								 'KODE'			=>$data->SKPD_KODE,
    								 'NAMA'			=>$data->SKPD_NAMA,
                                     'TOTAL'		=>number_format($data->total,0,'.',',')));
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
   	}

   	public function getDetail($tahun,$status,$skpd){
   		$data 	= Pendapatan::whereHas('subunit',function($q) use($skpd){
            $q->where('SKPD_ID',$skpd);
        })->where('PENDAPATAN_TAHUN',$tahun)->get();
    	$view 			= array();
    	$no 			= 1;
    	$opsi 			= '';
    	foreach ($data as $data) {
            if(Auth::user()->level == 9 or substr(Auth::user()->mod,10,1) == 1){
    		$opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->PENDAPATAN_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapus(\''.$data->PENDAPATAN_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            }else{
            $opsi = '-';
            }
    		array_push($view, array( 'NO'  			=> $no++,
    								 'AKSI'			=> $opsi,
    								 'REKENING'		=> $data->rekening->REKENING_KODE.' - '.$data->rekening->REKENING_NAMA,
    								 'RINCIAN'		=> $data->PENDAPATAN_NAMA,
                                     'TOTAL'		=> number_format($data->PENDAPATAN_TOTAL,0,'.',',')));
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
   	}

     public function getId($tahun,$status,$id){
      $data   = Pendapatan::where('PENDAPATAN_ID',$id)->first();
      $data->REKENING_KODE   = $data->rekening->REKENING_KODE;
      $data->REKENING_NAMA   = $data->rekening->REKENING_NAMA;
      $data->SUB_NAMA        = $data->subunit->SUB_NAMA;
      $data->SKPD            = $data->subunit->SKPD_ID;
      return Response::JSON($data);
    }
}
