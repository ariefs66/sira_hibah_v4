<?php

namespace App\Http\Controllers\Publik;

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
use App\Model\RkpPendapatan;
use App\Model\Tahapan;
use App\Model\AKB_Pendapatan;
use App\Model\AKB_Pendapatan_Perubahan;

class pendapatanController extends Controller
{
    public function index($tahun,$status){
      $skpd     = SKPD::where('SKPD_TAHUN',$tahun)->get();
      $rekening   = Rekening::where('REKENING_KODE','like','4%')->whereRaw('length("REKENING_KODE") = 11')
                            ->where('REKENING_TAHUN',$tahun)->get();

      if($status=='murni'){
        $anggaran       = DB::table('BUDGETING.DAT_PENDAPATAN')->where('DAT_PENDAPATAN.PENDAPATAN_TAHUN',$tahun)
                ->sum('PENDAPATAN_TOTAL');
          return View('budgeting.pendapatan.index',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd,'rekening'=>$rekening,'anggaran'=>number_format($anggaran,0,'.',',')]);
      }else{
        $anggaran       = DB::table('BUDGETING.DAT_PENDAPATAN')->where('DAT_PENDAPATAN.PENDAPATAN_TAHUN',$tahun)
                ->sum('PENDAPATAN_TOTAL');
        $anggaranp       = DB::table('BUDGETING.DAT_PENDAPATAN_PERUBAHAN')->where('DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_TAHUN',$tahun)
                ->sum('PENDAPATAN_TOTAL');
                //dd($anggaran);
        $selisih = ($anggaran-$anggaranp > 0 ? 'Rp. '.number_format(abs($anggaran-$anggaranp),0,'.',',') : '(Rp. '.number_format(abs($anggaran-$anggaranp),0,'.',',').')');
        $selisih = ($anggaran-$anggaranp == 0 ? 'Rp. 0' : $selisih);
         return View('budgeting.pendapatan.index_perubahan',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd,'rekening'=>$rekening,'anggaran'=>number_format($anggaran,0,'.',','),'anggaranp'=>number_format($anggaranp,0,'.',','),'selisih'=>$selisih]);
      }
    }

    public function submitAdd($tahun,$status){
        if($status=="murni"){
          $pendapatan   = new Pendapatan;
          $pendapatan->PENDAPATAN_TAHUN   = $tahun;
          $pendapatan->SUB_ID           = Input::get('SUB_ID');
          $pendapatan->REKENING_ID      = Input::get('REKENING_ID');
          $pendapatan->PENDAPATAN_NAMA    = Input::get('PENDAPATAN_NAMA');
          $pendapatan->PENDAPATAN_DASHUK    = Input::get('PENDAPATAN_DASHUK');
          $pendapatan->PENDAPATAN_KETERANGAN  = Input::get('PENDAPATAN_NAMA');
          $pendapatan->PENDAPATAN_TOTAL   = Input::get('PENDAPATAN_TOTAL');
          $pendapatan->save();

            $datarek    = Rekening::where('REKENING_ID',Input::get('REKENING_ID'))->first();
            $datapen    = Pendapatan::where('SUB_ID',Input::get('SUB_ID'))
                                    ->where('REKENING_ID',Input::get('REKENING_ID'))
                                    ->where('PENDAPATAN_NAMA',Input::get('PENDAPATAN_NAMA'))
                                    ->first();
        }
        else{
          $pendapatan   = new PendapatanPerubahan;
          $pendapatan->PENDAPATAN_TAHUN   = $tahun;
          $pendapatan->SUB_ID           = Input::get('SUB_ID');
          $pendapatan->REKENING_ID      = Input::get('REKENING_ID');
          $pendapatan->PENDAPATAN_NAMA    = Input::get('PENDAPATAN_NAMA');
          $pendapatan->PENDAPATAN_KETERANGAN  = Input::get('PENDAPATAN_NAMA');
          $pendapatan->PENDAPATAN_TOTAL   = Input::get('PENDAPATAN_TOTAL');
          $pendapatan->save();

            $datarek    = Rekening::where('REKENING_ID',Input::get('REKENING_ID'))->first();
            $datapen    = PendapatanPerubahan::where('SUB_ID',Input::get('SUB_ID'))
                                    ->where('REKENING_ID',Input::get('REKENING_ID'))
                                    ->where('PENDAPATAN_NAMA',Input::get('PENDAPATAN_NAMA'))
                                    ->first();
        }
          $log        = new Log;
          $log->LOG_TIME                          = Carbon\Carbon::now();
          $log->USER_ID                           = Auth::user()->id;
          $log->LOG_ACTIVITY                      = 'Menambahkan Pendapatan Rekening '.$datarek->REKENING_KODE.'-'.$datarek->REKENING_NAMA.' Jumlah '.Input::get('PENDAPATAN_TOTAL');
          $log->LOG_DETAIL                        = 'PD#'.$datapen->PENDAPATAN_ID;
          $log->save();
        return "Input Berhasil!";
    	
    }

    public function submitEdit($tahun,$status){
      if($status=="murni"){
        Pendapatan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->update([
            'SUB_ID'                  => Input::get('SUB_ID'),
            'PENDAPATAN_NAMA'         => Input::get('PENDAPATAN_NAMA'),
            'PENDAPATAN_KETERANGAN'   => Input::get('PENDAPATAN_NAMA'),
            'REKENING_ID'             => Input::get('REKENING_ID'),
            'PENDAPATAN_DASHUK'        => Input::get('PENDAPATAN_DASHUK'),
            'PENDAPATAN_TOTAL'        => Input::get('PENDAPATAN_TOTAL'),
            'TIME_UPDATED'            => Carbon\Carbon::now(),
            'USER_UPDATED'            => Auth::user()->id
          ]);

        $datarek    = Rekening::where('REKENING_ID',Input::get('REKENING_ID'))->first();
        $datapd    = Pendapatan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->first();
      }
      else{
        PendapatanPerubahan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->update([
            'SUB_ID'                  => Input::get('SUB_ID'),
            'PENDAPATAN_NAMA'         => Input::get('PENDAPATAN_NAMA'),
            'PENDAPATAN_KETERANGAN'   => Input::get('PENDAPATAN_NAMA'),
            'REKENING_ID'             => Input::get('REKENING_ID'),
            'PENDAPATAN_DASHUK'        => Input::get('PENDAPATAN_DASHUK'),
            'PENDAPATAN_TOTAL'        => Input::get('PENDAPATAN_TOTAL'),
            'TIME_UPDATED'            => Carbon\Carbon::now(),
            'USER_UPDATED'            => Auth::user()->id
          ]);

        $datarek    = Rekening::where('REKENING_ID',Input::get('REKENING_ID'))->first();
        $datapd    = PendapatanPerubahan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->first();
      }
        $log              = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Merubah Pendapatan Rekening '.$datarek->REKENING_KODE.'-'.$datarek->REKENING_NAMA.' Jumlah '.Input::get('PENDAPATAN_TOTAL');
        $log->LOG_DETAIL                        = 'PD#'.$datapd->PENDAPATAN_ID;
        $log->save();
      return "Berhasil Ubah";

    }

    public function delete($tahun,$status){
        if($status=="murni"){
          $datapendapatan     = Pendapatan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->first();
          Pendapatan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->delete();
        }
        else{
          $datapendapatan     = PendapatanPerubahan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->first();
          PendapatanPerubahan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->update(array('PENDAPATAN_TOTAL'=>0));
        }
        
        $log        = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Menghapus Pendapatan '.$datapendapatan->rekening->REKENING_KODE.'-'.$datapendapatan->rekening->REKENING_NAMA.' Jumlah '.$datapendapatan->PENDAPATAN_TOTAL;
        $log->LOG_DETAIL                        = 'PD#'.$datapendapatan->PENDAPATAN_ID;
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
    public function getPendapatan($tahun,$status){
      if($status=='murni'){
        $data       = DB::table('BUDGETING.DAT_PENDAPATAN')
              ->where('BUDGETING.DAT_PENDAPATAN.PENDAPATAN_TAHUN',$tahun)
              ->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_PENDAPATAN.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
              ->leftJoin('REFERENSI.REF_SUB_UNIT','BUDGETING.DAT_PENDAPATAN.SUB_ID','=','REFERENSI.REF_SUB_UNIT.SUB_ID')
              ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','REFERENSI.REF_SUB_UNIT.SKPD_ID')
              ->groupBy('REFERENSI.REF_SKPD.SKPD_ID','SKPD_KODE','SKPD_NAMA')
              ->select('REFERENSI.REF_SKPD.SKPD_ID','SKPD_KODE','SKPD_NAMA',DB::raw('SUM("PENDAPATAN_TOTAL") AS TOTAL'))
              ->get();
        //dd($data);      
        $view       = array();
        foreach ($data as $data) {

          array_push($view, array( 'ID'     =>$data->SKPD_ID,
                       'KODE'     =>$data->SKPD_KODE,
                       'NAMA'     =>$data->SKPD_NAMA,
                       'TOTAL'    =>number_format($data->total,0,'.',','),
                       'OPSI'    => '-'
                ));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
      }else{
        $data = DB::table('BUDGETING.DAT_PENDAPATAN_PERUBAHAN')
              ->where('BUDGETING.DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_TAHUN',$tahun)
              ->leftJoin('BUDGETING.DAT_PENDAPATAN','BUDGETING.DAT_PENDAPATAN.PENDAPATAN_ID','=','BUDGETING.DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_ID')
              ->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_PENDAPATAN_PERUBAHAN.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
              ->leftJoin('REFERENSI.REF_SUB_UNIT','BUDGETING.DAT_PENDAPATAN_PERUBAHAN.SUB_ID','=','REFERENSI.REF_SUB_UNIT.SUB_ID')
              ->leftJoin('REFERENSI.REF_SKPD','REFERENSI.REF_SKPD.SKPD_ID','=','REFERENSI.REF_SUB_UNIT.SKPD_ID')
              ->groupBy('REFERENSI.REF_SKPD.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA')
              ->select('REFERENSI.REF_SKPD.SKPD_ID','REFERENSI.REF_SKPD.SKPD_KODE','REFERENSI.REF_SKPD.SKPD_NAMA',DB::raw('SUM("BUDGETING"."DAT_PENDAPATAN_PERUBAHAN"."PENDAPATAN_TOTAL") AS TOTAL'),DB::raw('SUM("BUDGETING"."DAT_PENDAPATAN"."PENDAPATAN_TOTAL") AS TOTAL_MURNI'))
              ->get();
        //print_r($data);exit;
        $view       = array();
        foreach ($data as $data) {
            $selisih = ($data->total_murni-$data->total > 0 ? 'Rp. '.number_format(abs($data->total_murni-$data->total),0,'.',',') : '(Rp. '.number_format(abs($data->total_murni-$data->total),0,'.',',').')');
            $selisih = ($data->total_murni-$data->total == 0 ? 'Rp. 0' : $selisih);    
            array_push($view, array( 'ID'     =>$data->SKPD_ID,
                       'KODE'     =>$data->SKPD_KODE,
                       'NAMA'     =>$data->SKPD_NAMA,
                       'TOTAL_MURNI'   =>number_format($data->total_murni,0,'.',','),
                       'TOTAL'    =>number_format($data->total,0,'.',','),
                       'SELISIH'    =>$selisih,
                       'OPSI'    => '-'
                     ));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
      }
   		
   	}

   	public function getDetail($tahun,$status,$skpd){
      if($status=="murni"){
        $data   = Pendapatan::whereHas('subunit',function($q) use($skpd){
              $q->where('SKPD_ID',$skpd);
          })->where('PENDAPATAN_TAHUN',$tahun)->get();
        $view       = array();
        $no       = 1;
        $opsi       = '';
        foreach ($data as $data) {
          if(Auth::user()->level == 9 or Auth::user()->level == 8){
              $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->PENDAPATAN_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapus(\''.$data->PENDAPATAN_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            
              $akb = '<div class="action visible pull-right"><a href="/main/'.$tahun.'/'.$status.'/pendapatan/akb/'.$skpd.'" class="action-edit" target="_blank"><i class="mi-edit"></i></a></div>';
          }
	  elseif (substr(Auth::user()->mod,10,1) == 1 or Auth::user()->level == 2) {
              $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->PENDAPATAN_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapus(\''.$data->PENDAPATAN_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
              $akb = '-';
              /*$akb = '<div class="action visible pull-right"><a href="/main/'.$tahun.'/'.$status.'/pendapatan/akb/'.$skpd.'" class="action-edit" target="_blank"><i class="mi-edit"></i></a></div>';*/
          }else{
            $opsi = '-';
            $akb = '-';
          }
          array_push($view, array( 'NO'       => $no++,
                       'AKSI'     => $opsi,
                       'AKB'     => $akb,
                       'REKENING'   => $data->rekening->REKENING_KODE.' - '.$data->rekening->REKENING_NAMA,
                       'RINCIAN'    => $data->PENDAPATAN_NAMA,
                       'DASHUK'    => $data->PENDAPATAN_DASHUK,
                       'TOTAL'    => number_format($data->PENDAPATAN_TOTAL,0,'.',',')));
        }
      }
      else{

        $data   = PendapatanPerubahan::leftJoin('BUDGETING.DAT_PENDAPATAN','BUDGETING.DAT_PENDAPATAN.PENDAPATAN_ID','=','BUDGETING.DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_ID')
              ->Join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN_PERUBAHAN.REKENING_ID')
              ->where('DAT_PENDAPATAN_PERUBAHAN.SKPD_ID',$skpd)
              ->where('DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_TAHUN',$tahun);

        $data       = $data->groupBy('REF_REKENING.REKENING_KODE','REF_REKENING.REKENING_NAMA','DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_ID','DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_NAMA')
                      ->select('REF_REKENING.REKENING_KODE','REF_REKENING.REKENING_NAMA','DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_ID',
                      'DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_NAMA', DB::raw('SUM("BUDGETING"."DAT_PENDAPATAN_PERUBAHAN"."PENDAPATAN_TOTAL") AS TOTAL'),DB::raw('SUM("BUDGETING"."DAT_PENDAPATAN"."PENDAPATAN_TOTAL") AS TOTAL_MURNI'))
                      ->get();  


        $view       = array();
        $no       = 1;
        $opsi       = '';
        foreach ($data as $data) {
              if(Auth::user()->level == 8 or substr(Auth::user()->mod,10,1) == 1 or Auth::user()->level == 9){
                $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->PENDAPATAN_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapus(\''.$data->PENDAPATAN_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
                $akb = '<div class="action visible pull-right"><a href="/main/'.$tahun.'/'.$status.'/pendapatan/akb/'.$skpd.'" class="action-edit" target="_blank"><i class="mi-edit"></i></a></div>';
              }else{
              $opsi = '-';
              $akb = '-';
              }
        $selisih = ($data->total_murni-$data->total > 0 ? 'Rp. '.number_format(abs($data->total_murni-$data->total),0,'.',',') : '(Rp. '.number_format(abs($data->total_murni-$data->total),0,'.',',').')');
        $selisih = ($data->total_murni-$data->total == 0 ? 'Rp. 0' : $selisih);
          array_push($view, array( 'NO'       => $no++,
                       'AKSI'           => $opsi,
                       'AKB'            => $akb,
                       'REKENING'       => $data->REKENING_KODE.' - '.$data->REKENING_NAMA,
                       'RINCIAN'        => $data->PENDAPATAN_NAMA,
                       'DASHUK'         => $data->DASHUK,
                       'TOTAL_MURNI'    => number_format($data->total_murni,0,'.',','),
                        'TOTAL'         => number_format($data->total,0,'.',','),
                        'SELISIH'    => $selisih));
        }
      }
   		
		  $out = array("aaData"=>$view);    	
    	return Response::JSON($out);
   	}

     public function getId($tahun,$status,$id){
      if($status=="murni"){
        $data   = Pendapatan::where('PENDAPATAN_ID',$id)->first();
        $data->REKENING_KODE   = $data->rekening->REKENING_KODE;
        $data->REKENING_NAMA   = $data->rekening->REKENING_NAMA;
        $data->SUB_NAMA        = $data->subunit->SUB_NAMA;
        $data->SKPD            = $data->subunit->SKPD_ID;
      }
      else{
        $data   = PendapatanPerubahan::where('PENDAPATAN_ID',$id)->first();
        $data->REKENING_KODE   = $data->rekening->REKENING_KODE;
        $data->REKENING_NAMA   = $data->rekening->REKENING_NAMA;
        $data->SUB_NAMA        = $data->subunit->SUB_NAMA;
        $data->SKPD            = $data->subunit->SKPD_ID;
      }
      
      return Response::JSON($data);
    }


     public function showAKB($tahun,$status,$id){
        $now = date('Y-m-d H:m:s');
        if($status == 'murni')
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->orderBy('TAHAPAN_ID','desc')->first();
        else
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)
                       ->where(function($q) {
                                  $q->where('TAHAPAN_STATUS', 'perubahan')
                                    ->orWhere('TAHAPAN_STATUS', 'pergeseran');
                              })->orderBy('TAHAPAN_ID','desc')->first();
        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }
        if($status == 'murni') {
          $pen  = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                    ->leftjoin('BUDGETING.DAT_AKB_PENDAPATAN',function($join){
                        $join->on('DAT_AKB_PENDAPATAN.PENDAPATAN_ID','=','DAT_PENDAPATAN.PENDAPATAN_ID')->on('DAT_AKB_PENDAPATAN.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID');
                    })
                    ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_PENDAPATAN.SUB_ID')
                    ->where('DAT_PENDAPATAN.SKPD_ID',$id)
                    ->orderBy("REKENING_NAMA")
                    ->selectRaw(' "DAT_PENDAPATAN"."PENDAPATAN_ID", "DAT_PENDAPATAN"."REKENING_ID", "REKENING_NAMA", "PENDAPATAN_TOTAL" AS total, "AKB_JAN", "AKB_FEB", "AKB_MAR", "AKB_APR", "AKB_MEI", "AKB_JUN", "AKB_JUL", "AKB_AUG", "AKB_SEP", "AKB_OKT", "AKB_NOV", "AKB_DES" ')
                    ->get();                              
        }
        else 
          $pen  = PendapatanPerubahan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN_PERUBAHAN.REKENING_ID')
                    ->leftjoin('BUDGETING.DAT_AKB_PENDAPATAN_PERUBAHAN',function($join){
                        $join->on('DAT_AKB_PENDAPATAN_PERUBAHAN.PENDAPATAN_ID','=','DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_ID')->on('DAT_AKB_PENDAPATAN_PERUBAHAN.REKENING_ID','=','DAT_PENDAPATAN_PERUBAHAN.REKENING_ID');
                    })
                    ->where('DAT_PENDAPATAN_PERUBAHAN.SKPD_ID',$id)
                    ->orderBy("REKENING_NAMA")
                    ->selectRaw(' "DAT_PENDAPATAN_PERUBAHAN"."PENDAPATAN_ID", "DAT_PENDAPATAN_PERUBAHAN"."REKENING_ID", "REKENING_NAMA", "PENDAPATAN_TOTAL" AS total, "AKB_JAN", "AKB_FEB", "AKB_MAR", "AKB_APR", "AKB_MEI", "AKB_JUN", "AKB_JUL", "AKB_AUG", "AKB_SEP", "AKB_OKT", "AKB_NOV", "AKB_DES" ')
                    ->get();

          $skpd         = SKPD::where('SKPD_ID',$id)->first();


        if($status == 'murni')
         return View('budgeting.pendapatan.akb-pendapatan',['tahun'=>$tahun,'status'=>$status,'pen'=>$pen,'PENDAPATAN_ID'=>$id, 'thp'=>$thp, 'skpd'=>$skpd ]);
        else
         return View('budgeting.pendapatan.akb-pendapatan',['tahun'=>$tahun,'status'=>$status,'pen'=>$pen,'PENDAPATAN_ID'=>$id, 'thp'=>$thp, 'skpd'=>$skpd ]);
    }

    public function showDataAKB($tahun,$status,$id){
        if($status == 'murni') return $this->showDataAKBMurni($tahun,$status,$id);
        else return $this->showDataAKBPerubahan($tahun,$status,$id);
    }


     public function showDataAKBMurni($tahun,$status,$id){
        $now = date('Y-m-d H:m:s');
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->orderBy('TAHAPAN_ID','desc')->first();
        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }

        $data = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                    ->leftjoin('BUDGETING.DAT_AKB_PENDAPATAN',function($join){
                        $join->on('DAT_AKB_PENDAPATAN.PENDAPATAN_ID','=','DAT_PENDAPATAN.PENDAPATAN_ID')->on('DAT_AKB_PENDAPATAN.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID');
                    })
                    ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_PENDAPATAN.SUB_ID')
                    ->where('DAT_PENDAPATAN.SKPD_ID',$id)
                    ->orderBy("REKENING_NAMA")
                    ->selectRaw(' "DAT_PENDAPATAN"."PENDAPATAN_ID", "DAT_PENDAPATAN"."REKENING_ID", "REKENING_NAMA", "PENDAPATAN_TOTAL" AS total, "AKB_JAN", "AKB_FEB", "AKB_MAR", "AKB_APR", "AKB_MEI", "AKB_JUN", "AKB_JUL", "AKB_AUG", "AKB_SEP", "AKB_OKT", "AKB_NOV", "AKB_DES" ')
                    ->get(); 
       
                 //   dd($data);

        $view       = array();
        $i         = 1;
        
        foreach ($data as $data) {

            $getAkb = AKB_Pendapatan::where('PENDAPATAN_ID',$data->PENDAPATAN_ID)->where('REKENING_ID',$data->REKENING_ID)->value('AKB_PENDAPATAN_ID');            

            if(($thp == 1 or Auth::user()->level == 8 ) and Auth::user()->active == 5){
                if(empty($getAkb) ){
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return ubah(\''.$data->PENDAPATAN_ID.'\',\''.$data->REKENING_ID.'\')"><i class="fa fa-pencil-square"></i>Tambah</a></li>
                    <li class="divider"></li><li><a onclick="return info(\''.$data->REKENING_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                }else{
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li><a onclick="return ubah(\''.$data->PENDAPATAN_ID.'\',\''.$data->REKENING_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a></li>
                <li><a onclick="return hapus(\''.$data->PENDAPATAN_ID.'\',\''.$data->REKENING_ID.'\')"><i class="fa fa-pencil-square"></i>Hapus</a></li>
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
                                     //'TOTAL'          =>$data->total,
                                     'TOTAL'     =>number_format($data->total,0,'.',','),
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


    public function showDataAKBPerubahan($tahun,$status,$id){
        $now = date('Y-m-d H:m:s');

        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)
                       ->where(function($q) {
                                  $q->where('TAHAPAN_STATUS', 'perubahan')
                                    ->orWhere('TAHAPAN_STATUS', 'pergeseran');
                              })->orderBy('TAHAPAN_ID','desc')->first();

        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }

        $data = PendapatanPerubahan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN_PERUBAHAN.REKENING_ID')
                    ->leftjoin('BUDGETING.DAT_AKB_PENDAPATAN_PERUBAHAN',function($join){
                        $join->on('DAT_AKB_PENDAPATAN_PERUBAHAN.PENDAPATAN_ID','=','DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_ID')->on('DAT_AKB_PENDAPATAN_PERUBAHAN.REKENING_ID','=','DAT_PENDAPATAN_PERUBAHAN.REKENING_ID');
                    })
                    ->where('DAT_PENDAPATAN_PERUBAHAN.SKPD_ID',$id)
                    ->orderBy("REKENING_NAMA")
                    ->selectRaw(' "DAT_PENDAPATAN_PERUBAHAN"."PENDAPATAN_ID", "DAT_PENDAPATAN_PERUBAHAN"."REKENING_ID", "REKENING_NAMA", "PENDAPATAN_TOTAL" AS total, "AKB_JAN", "AKB_FEB", "AKB_MAR", "AKB_APR", "AKB_MEI", "AKB_JUN", "AKB_JUL", "AKB_AUG", "AKB_SEP", "AKB_OKT", "AKB_NOV", "AKB_DES" ')
                    ->get(); 
       
                 //   dd($data);

        $view       = array();
        $i         = 1;
        
        foreach ($data as $data) {

            $getAkb = AKB_Pendapatan_Perubahan::where('PENDAPATAN_ID',$data->PENDAPATAN_ID)->where('REKENING_ID',$data->REKENING_ID)->value('AKB_PENDAPATAN_ID');            

            if(($thp == 1 or Auth::user()->level == 8 ) and Auth::user()->active == 5){
                if(empty($getAkb) ){
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return ubah(\''.$data->PENDAPATAN_ID.'\',\''.$data->REKENING_ID.'\')"><i class="fa fa-pencil-square"></i>Tambah</a></li>
                    <li class="divider"></li><li><a onclick="return info(\''.$data->REKENING_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                }else{
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li><a onclick="return ubah(\''.$data->PENDAPATAN_ID.'\',\''.$data->REKENING_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a></li>
                <li class="divider"></li><li><a onclick="return info(\''.$data->REKENING_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                /*<li><a onclick="return hapus(\''.$data->PENDAPATAN_ID.'\',\''.$data->REKENING_ID.'\')"><i class="fa fa-pencil-square"></i>Hapus</a></li>*/
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
                                     //'TOTAL'          =>$data->total,
                                     'TOTAL'     =>number_format($data->total,0,'.',','),
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


     public function detailAKB($tahun,$status,$pendapatan_id,$rek_id){
        if($status == 'murni'){

             $data = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                    ->leftjoin('BUDGETING.DAT_AKB_PENDAPATAN',function($join){
                        $join->on('DAT_AKB_PENDAPATAN.PENDAPATAN_ID','=','DAT_PENDAPATAN.PENDAPATAN_ID')->on('DAT_AKB_PENDAPATAN.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID');
                    })
                    ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_PENDAPATAN.SUB_ID')
                    ->where('DAT_PENDAPATAN.PENDAPATAN_ID',$pendapatan_id)
                    ->where('DAT_PENDAPATAN.REKENING_ID',$rek_id)
                    ->orderBy("REKENING_NAMA")
                    ->selectRaw(' "DAT_PENDAPATAN"."PENDAPATAN_ID", "DAT_PENDAPATAN"."REKENING_ID", "REKENING_KODE", "REKENING_NAMA", "PENDAPATAN_TOTAL" AS total, "AKB_JAN", "AKB_FEB", "AKB_MAR", "AKB_APR", "AKB_MEI", "AKB_JUN", "AKB_JUL", "AKB_AUG", "AKB_SEP", "AKB_OKT", "AKB_NOV", "AKB_DES" ')
                    ->first();

        }else{
            $data = PendapatanPerubahan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN_PERUBAHAN.REKENING_ID')
                    ->leftjoin('BUDGETING.DAT_AKB_PENDAPATAN_PERUBAHAN',function($join){
                        $join->on('DAT_AKB_PENDAPATAN_PERUBAHAN.PENDAPATAN_ID','=','DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_ID')->on('DAT_AKB_PENDAPATAN_PERUBAHAN.REKENING_ID','=','DAT_PENDAPATAN_PERUBAHAN.REKENING_ID');
                    })
                    ->where('DAT_PENDAPATAN_PERUBAHAN.PENDAPATAN_ID',$pendapatan_id)
                    ->where('DAT_PENDAPATAN_PERUBAHAN.REKENING_ID',$rek_id)
                    ->orderBy("REKENING_NAMA")
                    ->selectRaw(' "DAT_PENDAPATAN_PERUBAHAN"."PENDAPATAN_ID", "DAT_PENDAPATAN_PERUBAHAN"."REKENING_ID", "REKENING_KODE", "REKENING_NAMA", "PENDAPATAN_TOTAL" AS total, "AKB_JAN", "AKB_FEB", "AKB_MAR", "AKB_APR", "AKB_MEI", "AKB_JUN", "AKB_JUL", "AKB_AUG", "AKB_SEP", "AKB_OKT", "AKB_NOV", "AKB_DES" ')
                    ->first();
        }

        $bagi    = $data->total/12; 

        $out    = [ //'DATA'          => $data,
                    'REKENING_KODE' => $data->REKENING_KODE,
                    'REKENING_NAMA' => $data->REKENING_NAMA,
                    'TOTAL'         => $data->total,
                    'TOTAL_VIEW'    => number_format($data->total,0,'.',','),
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
                    /*
                    (empty($data->AKB_JAN))?$jan=$bagi:$jan=$data->AKB_JAN,
                    (empty($data->AKB_FEB))?$feb=$bagi:$feb=$data->AKB_FEB,
                    (empty($data->AKB_MAR))?$mar=$bagi:$mar=$data->AKB_MAR,
                    (empty($data->AKB_APR))?$apr=$bagi:$apr=$data->AKB_APR,
                    (empty($data->AKB_MEI))?$mei=$bagi:$mei=$data->AKB_MEI,
                    (empty($data->AKB_JUN))?$jun=$bagi:$jun=$data->AKB_JUN,
                    (empty($data->AKB_JUL))?$jul=$bagi:$jul=$data->AKB_JUL,
                    (empty($data->AKB_AUG))?$agu=$bagi:$agu=$data->AKB_AUG,
                    (empty($data->AKB_SEP))?$sep=$bagi:$sep=$data->AKB_SEP,
                    (empty($data->AKB_OKT))?$okt=$bagi:$okt=$data->AKB_OKT,
                    (empty($data->AKB_NOV))?$nov=$bagi:$nov=$data->AKB_NOV,
                    (empty($data->AKB_DES))?$des=$bagi:$des=$data->AKB_DES,
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
                    'AKB_DES'       => $des,*/
                    'REKENING_ID'   => $data->REKENING_ID,
                    'PENDAPATAN_ID' => $data->PENDAPATAN_ID,
                    ];
        return $out;
    }


     public function submitAKBEdit($tahun,$status){
        if($status == 'murni') {
            $akb_pendapatan = AKB_Pendapatan::where('PENDAPATAN_ID',Input::get('id_pendapatan'))
                         ->where('REKENING_ID',Input::get('rek_id'))->value('AKB_PENDAPATAN_ID');

            if(empty($akb_pendapatan)){
                $akb = new AKB_Pendapatan;
                $akb->PENDAPATAN_ID      = Input::get('id_pendapatan');
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
                AKB_Pendapatan::where('PENDAPATAN_ID',Input::get('id_pendapatan'))
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
        else{
          $akb_pendapatan = AKB_Pendapatan_Perubahan::where('PENDAPATAN_ID',Input::get('id_pendapatan'))
                         ->where('REKENING_ID',Input::get('rek_id'))->value('AKB_PENDAPATAN_ID');

            if(empty($akb_pendapatan)){
                $akb = new AKB_Pendapatan_Perubahan;
                $akb->PENDAPATAN_ID      = Input::get('id_pendapatan');
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
                AKB_Pendapatan_Perubahan::where('PENDAPATAN_ID',Input::get('id_pendapatan'))
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
    }

    public function deleteAKB($tahun, $status){
        if($status=='murni'){
          AKB_Pendapatan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->where('REKENING_ID',Input::get('REKENING_ID'))->delete();
          return "Hapus Berhasil!";
        }else{
          AKB_Pendapatan_Perubahan::where('PENDAPATAN_ID',Input::get('PENDAPATAN_ID'))->where('REKENING_ID',Input::get('REKENING_ID'))->delete();
          return "Hapus Berhasil!";
        }
    }

}
