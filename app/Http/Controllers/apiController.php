<?php

namespace app\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use View;
use Response;
use Auth;
use Carbon;
use App\Model\SKPD;
use App\Model\Propri;
use App\Model\Program;
use App\Model\Kegiatan;
use App\Model\JenisGiat;
use App\Model\SumberDana;
use App\Model\Subunit;
use App\Model\Pagu;
use App\Model\Sasaran;
use App\Model\Tag;
use App\Model\Lokasi;
use App\Model\Satuan;
use App\Model\BL;
use App\Model\BLPerubahan;
use App\Model\Indikator;
use App\Model\Kunci;
use App\Model\Kunciperubahan;
use App\Model\Pekerjaan;
use App\Model\Rekening;
use App\Model\Komponen;
use App\Model\Rekom;
use App\Model\Rincian;
use App\Model\RincianPerubahan;
use App\Model\User;
use App\Model\Staff;
use App\Model\UserBudget;
use App\Model\Tahapan;
use App\Model\Log;
use App\Model\Subrincian;
use App\Model\SubrincianPerubahan;
use App\Model\RekapRincian;
use App\Model\Progunit;
use App\Model\Output;
use App\Model\OutputPerubahan;
use App\Model\Outcome;
use App\Model\Impact;
use App\Model\RincianArsip;
use App\Model\RincianArsipPerubahan;
use App\Model\Urgensi;
use App\Model\Realisasi;
use App\Model\RincianLog;
use App\Model\AKB_BL;
use App\Model\AKB_BL_Perubahan;
use App\Model\Rekgiat;
use App\Model\OutputMaster;
use App\Model\Usulan;
use App\Model\UsulanReses;
use App\Model\Kamus;
use App\Model\UserReses;
use App\Model\BTL;
use App\Model\BTLPerubahan;
use App\Model\AKB_BTL;
use App\Model\AKB_BTL_Perubahan;
use App\Model\Pendapatan;
use App\Model\PendapatanPerubahan;
use App\Model\RkpPendapatan;
use App\Model\AKB_Pendapatan;
use App\Model\AKB_Pendapatan_Perubahan;
use App\Model\Pembiayaan;
use App\Model\PembiayaanPerubahan;
use App\Model\AKB_Pembiayaan;
use App\Model\AKB_Pembiayaan_Perubahan;

class apiController extends Controller
{
    public function api($tahun,$status){
        $data   = Usulan::where('USULAN_STATUS',1)->select('USULAN_ID')->get();
        return Response::JSON($data);
    }

	public function apiSiraBL($tahun,$status, Request $req){
	$skpd = ($req->SKPD == "")? 0 : $req->SKPD;
        if($skpd>0){
            $skpd = SKPD::where('SKPD_KODE', $req->SKPD)->where('SKPD_TAHUN', $tahun)->value('SKPD_ID');
            if(!$skpd){
                return "SKPD Tidak Valid!";
            }
        }
        $filter = "";
        $pagu_foot    = 0;
        $rincian_foot = 0;
        $now = date('Y-m-d H:m:s');
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS',$status)->orderBy('TAHAPAN_ID','desc')->first();
        if($tahapan){
            if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
                $thp    = 1;
            }else{
                $thp    = 0;
            }
        }else{
            $thp = 0;
        }
        
        if($skpd>0){
            $data       = BL::whereHas('subunit',function($q) use ($skpd){
                            $q->where('SKPD_ID',$skpd);
                        })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->get();

            $pagu_foot       = BL::whereHas('subunit',function($q) use ($skpd){
                                    $q->where('SKPD_ID',$skpd);
                            })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->sum('BL_PAGU');

            $rincian_foot       = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                                ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                                ->where('DAT_BL.BL_TAHUN',$tahun)->where('DAT_BL.BL_DELETED',0)
                                ->WHERE('REF_SUB_UNIT.SKPD_ID',$skpd)->sum('DAT_RINCIAN.RINCIAN_TOTAL');
        } else {
            $data       = BL::where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->where('BL_VALIDASI',1)->get();

            $pagu_foot       = BL::where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->where('BL_VALIDASI',1)->sum('BL_PAGU');

            $rincian_foot       = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                                ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                                ->where('DAT_BL.BL_TAHUN',$tahun)->where('DAT_BL.BL_DELETED',0)->where('BL_VALIDASI',1)->sum('DAT_RINCIAN.RINCIAN_TOTAL');
        }

        $view       = array();
        $i          = 1;
        $kunci      = '';
        $rincian    = '';
        $validasi   = '';
        foreach ($data as $data) {
            $urgensi    = Urgensi::where('BL_ID',$data->BL_ID)->first();
            if( (
                empty($urgensi->URGENSI_LATAR_BELAKANG) or
                empty($urgensi->URGENSI_DESKRIPSI) or
                empty($urgensi->URGENSI_TUJUAN) or
                empty($urgensi->URGENSI_PENERIMA_1) or
                empty($urgensi->URGENSI_PELAKSANAAN)) and $urgensi and ($skpd == 24 or $skpd == 15 or $skpd == 22 or $skpd == 14))

            $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a href="/main/'.$tahun.'/'.$status.'/belanja-langsung/detail/'.$data->BL_ID.'"><i class="fa fa-search"></i> Detail</a></li>';

            else
            $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a href="/main/'.$tahun.'/'.$status.'/belanja-langsung/detail/'.$data->BL_ID.'"><i class="fa fa-search"></i> Detail</a></li>';                
			
            if(!isset($data->kunci)){
				$app = app();
                $obj = $app->make('stdClass');
                $obj->KUNCI_GIAT = 0;
                $obj->KUNCI_RINCIAN = 0;
                $data->setAttribute('kunci',$obj);
            }
			
            if($data->kunci->KUNCI_GIAT == 0 and $thp == 1){
                    $kunci     = '<span class="text-success"><i class="fa fa-unlock kunci-giat"></i></span>';
            }else{
                    $kunci      = '<span class="text-danger"><i class="fa fa-lock kunci-giat"></i></span>';
            }

            //kunci rincian
            if ($data->kunci->KUNCI_RINCIAN == 0 and $thp == 1 ) {
                    $rincian    = '<span class="text-success"><i class="fa fa-unlock kunci-rincian"></i></span>';
            }else{
                    $rincian    = '<span class="text-danger"><i class="fa fa-lock kunci-rincian"></i></span>';
            }

            if(empty($data->rincian)) $totalRincian = 0;
            else $totalRincian = number_format($data->rincian->sum('RINCIAN_TOTAL'),0,'.',',');
            array_push($view, array( 'KEGIATAN'       =>$data->kegiatan->program->urusan->URUSAN_KODE.'.'.$data->subunit->skpd->SKPD_KODE.'.'.$data->kegiatan->program->PROGRAM_KODE.' - '.$data->kegiatan->program->PROGRAM_NAMA.'<br><p class="text-orange">'.$data->kegiatan->program->urusan->URUSAN_KODE.'.'.$data->subunit->skpd->SKPD_KODE.'.'.$data->kegiatan->program->PROGRAM_KODE.'.'.$data->kegiatan->KEGIATAN_KODE.' - '.$data->kegiatan->KEGIATAN_NAMA.'</p><span class="text-success">'.$data->subunit->skpd->SKPD_KODE.'.'.$data->subunit->SUB_KODE.' - '.$data->subunit->SUB_NAMA.'</span>',
                                     'PAGU'           =>number_format($data->BL_PAGU,0,'.',','),
                                     'RINCIAN'        =>$totalRincian,
                                     'STATUS'         =>$kunci.' Kegiatan<br>'.$rincian.' Rincian<br>'.$validasi.' Validasi'));
            //$pagu_foot    =+ $data->BL_PAGU;
            //$rincian_foot =+ $data->rincian->sum('RINCIAN_TOTAL');
            $i++;
        }
        $out = array("aaData"=>$view,
                    "pagu_foot"=>number_format($pagu_foot,0,'.',','),
                    "rincian_foot"=>number_format($rincian_foot,0,'.',','),
            );      
        return Response::JSON($out);
        return $view;
    }

	public function apiSiraBTL($tahun,$status, Request $req){
	$skpd = ($req->SKPD == "")? 0 : $req->SKPD;
        if($skpd>0){
            $skpd = SKPD::where('SKPD_KODE', $req->SKPD)->where('SKPD_TAHUN', $tahun)->value('SKPD_ID');
            if(!$skpd){
                return "SKPD Tidak Valid!";
            }
        }
        $id = ($req->ID == "")? "" : $req->ID;
        if($status=="murni"){
            if($skpd>0){
                $data   = BTL::whereHas('rekening', function($q) use ($id){
                    $q->where('REKENING_KODE','like',$id.'%');  
                  })->where('BTL_TAHUN',$tahun)->get();
            }else{
                $data   = BTL::whereHas('rekening', function($q) use ($id){
                    $q->where('REKENING_KODE','like',$id.'%');  
                  })->where('BTL_TAHUN',$tahun)->get();
            }
          $view       = array();
          $no         = 1;
          $opsi       = '';
          $akb       = '';
          foreach ($data as $data) {
               $akb = '-';
            array_push($view, array( 'NO'       => $no++,
                                     'AKB'     => $akb,
                                     'REKENING'   => $data->rekening->REKENING_KODE,
                                     'RINCIAN'    => $data->BTL_NAMA,
                                     'TOTAL'    => number_format($data->BTL_TOTAL,0,'.',',')));
          }
        }
        else{
            if($skpd>0){
                $data   = BTLPerubahan::leftJoin('BUDGETING.DAT_BTL','BUDGETING.DAT_BTL.BTL_ID','=','BUDGETING.DAT_BTL_PERUBAHAN.BTL_ID')
                ->leftJoin('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL_PERUBAHAN.REKENING_ID')
                ->where('REKENING_KODE','like',$id.'%')
                ->where('DAT_BTL_PERUBAHAN.BTL_TAHUN',$tahun);
            }else{
                $data   = BTLPerubahan::leftJoin('BUDGETING.DAT_BTL','BUDGETING.DAT_BTL.BTL_ID','=','BUDGETING.DAT_BTL_PERUBAHAN.BTL_ID')
                ->leftJoin('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL_PERUBAHAN.REKENING_ID')
                ->where('REKENING_KODE','like',$id.'%')
                ->where('DAT_BTL_PERUBAHAN.BTL_TAHUN',$tahun);
            }

          $data       = $data->groupBy('REF_REKENING.REKENING_KODE','DAT_BTL.BTL_ID','DAT_BTL.BTL_NAMA')
                        ->select('REF_REKENING.REKENING_KODE','DAT_BTL.BTL_ID',
                        'DAT_BTL.BTL_NAMA', DB::raw('SUM("BUDGETING"."DAT_BTL_PERUBAHAN"."BTL_TOTAL") AS TOTAL'),DB::raw('SUM("BUDGETING"."DAT_BTL"."BTL_TOTAL") AS TOTAL_MURNI'))
                        ->get();   
              
          $view       = array();
          $no         = 1;
          $opsi       = '';
          foreach ($data as $data) {
  
              $opsi = '-';
            array_push($view, array( 'NO'             => $no++,
                                     'REKENING'       => $data->REKENING_KODE,
                                     'RINCIAN'        => $data->BTL_NAMA,
                                     'TOTAL'          => number_format($data->total,0,'.',','),
                                     'TOTAL_MURNI'    => number_format($data->total_murni,0,'.',',')
                                   ));
          }
        }
          $out = array("aaData"=>$view);    	
          return Response::JSON($out);
         }

    public function apiSiraPembiayaan($tahun,$status){
        $data = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
                ->JOIN('DATA.users','users.id','=','DAT_PEMBIAYAAN.USER_UPDATED')
                ->where('PEMBIAYAAN_TAHUN',$tahun)->orderBy('REKENING_KODE')->get();
            $view 			= array();
        $no=1;
            foreach ($data as $data) {
            
            if(Auth::user()->level == 8){
                $opsi = '<a onclick="return ubah(\''.$data->PEMBIAYAAN_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a> <a onclick="return hapus(\''.$data->PEMBIAYAAN_ID.'\')"><i class="fa fa-close"></i>Hapus</a>';
                $akb = '<div class="action visible pull-right"><a href="/main/'.$tahun.'/'.$status.'/pembiayaan/akb/'.$data->SKPD_ID.'" class="action-edit" target="_blank"><i class="mi-edit"></i></a></div>';
                }else {
                $opsi ='-';
                $akb ='-';
                }
            
            
                array_push($view, array( 
                                    'ID'			               =>$data->PEMBIAYAAN_ID,
                                    'NO'                   =>$no,
                                    'SKPD'                =>$data->name,
                                                    'REKENING_KODE'	       =>$data->REKENING_KODE,
                                    'REKENING_NAMA'         =>$data->REKENING_NAMA,
                                    'PEMBIAYAAN_DASHUK'     =>$data->PEMBIAYAAN_DASHUK,
                                                    'PEMBIAYAAN_TOTAL'      =>number_format($data->PEMBIAYAAN_TOTAL,0,'.',','),
                                    'PEMBIAYAAN_KETERANGAN' =>$data->PEMBIAYAAN_KETERANGAN,
                                    'OPSI'                  =>$opsi,
                                    'AKB'                   =>$akb,
                                    
                                    ));
            $no++;
            }
            $out = array("aaData"=>$view);    	
            return Response::JSON($out);
            }


    public function apiSirupKegiatan($tahun,$status){
      if($status == 'murni'){
    	$data 	= Rincian::JOIN('BUDGETING.DAT_SUBRINCIAN','DAT_RINCIAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN.SUBRINCIAN_ID')
    				->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
    				->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
    				->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
    				->JOIN('BUDGETING.DAT_BL','DAT_RINCIAN.BL_ID','=','DAT_BL.BL_ID')
    				->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
    				->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
    				->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
    				->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
    				->WHERE('DAT_BL.BL_TAHUN',$tahun)
    				->WHERE('DAT_BL.BL_DELETED',0)
    				->WHERE('DAT_BL.BL_VALIDASI',1)
    				->take('1000')
    				->orderBy('REF_SKPD.SKPD_ID','asc')
    				->get();		
    	}else{
    		$data 	= RincianPerubahan::JOIN('BUDGETING.DAT_SUBRINCIAN_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN_PERUBAHAN.SUBRINCIAN_ID')
    				->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN_PERUBAHAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
    				->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN_PERUBAHAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
    				->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN_PERUBAHAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
    				->JOIN('BUDGETING.DAT_BL_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.BL_ID','=','DAT_BL_PERUBAHAN.BL_ID')
    				->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL_PERUBAHAN.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
    				->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
    				->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL_PERUBAHAN.SUB_ID')
    				->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
    				->WHERE('DAT_BL_PERUBAHAN.BL_TAHUN',$tahun)
    				->WHERE('DAT_BL_PERUBAHAN.BL_DELETED',0)
    				->WHERE('DAT_BL_PERUBAHAN.BL_VALIDASI',1)
    				->take('1000')
    				->orderBy('REF_SKPD.SKPD_ID','asc')
    				->get();
    	}			

    	$view 			= array();
    	foreach ($data as $data) {
            if($data->BL_PAGU == 0){
                $deleted = 'false';
            }else $deleted = 'true';

    		array_push($view, array( 
                                     'PROGRAM_ID'           =>$data->PROGRAM_ID,
                                     'KEGIATAN_ID'          =>$data->KEGIATAN_ID,
                                     'SKPD_ID'              =>$data->SKPD_ID,
                                     'KEGIATAN_NAMA'        =>$data->KEGIATAN_NAMA,
                                     'BL_PAGU'              =>$data->BL_PAGU,
                                     'BL_DELETED'           =>$deleted,
    		));
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
    }

    public function apiSirupProgram($tahun,$status){
      if($status == 'murni'){
        $data   = Rincian::JOIN('BUDGETING.DAT_SUBRINCIAN','DAT_RINCIAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN.SUBRINCIAN_ID')
                    ->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
                    ->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
                    ->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
                    ->JOIN('BUDGETING.DAT_BL','DAT_RINCIAN.BL_ID','=','DAT_BL.BL_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->WHERE('DAT_BL.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_DELETED',0)
                    ->WHERE('DAT_BL.BL_VALIDASI',1)
                    ->take('1000')
                    ->orderBy('REF_SKPD.SKPD_ID','asc')
                    ->get();        
        }else{
            $data   = RincianPerubahan::JOIN('BUDGETING.DAT_SUBRINCIAN_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN_PERUBAHAN.SUBRINCIAN_ID')
                    ->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN_PERUBAHAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
                    ->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN_PERUBAHAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
                    ->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN_PERUBAHAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
                    ->JOIN('BUDGETING.DAT_BL_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.BL_ID','=','DAT_BL_PERUBAHAN.BL_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL_PERUBAHAN.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL_PERUBAHAN.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->WHERE('DAT_BL_PERUBAHAN.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL_PERUBAHAN.BL_DELETED',0)
                    ->WHERE('DAT_BL_PERUBAHAN.BL_VALIDASI',1)
                    ->take('1000')
                    ->orderBy('REF_SKPD.SKPD_ID','asc')
                    ->get();
        }           

        $view           = array();
        foreach ($data as $data) {
            if($data->BL_PAGU == 0){
                $deleted = 'false';
            }else $deleted = 'true';
            array_push($view, array( 
                                     'PROGRAM_ID'           =>$data->PROGRAM_ID,
                                     'SKPD_ID'              =>$data->SKPD_ID,
                                     'PROGRAM_NAMA'         =>$data->PROGRAM_NAMA,
                                     'BL_PAGU'              =>$data->BL_PAGU,
                                     'BL_DELETED'           =>$deleted,
            ));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function apiSirupPenyedia($tahun,$status){
      if($status == 'murni'){
        $data   = Rincian::JOIN('BUDGETING.DAT_SUBRINCIAN','DAT_RINCIAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN.SUBRINCIAN_ID')
                    ->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
                    ->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
                    ->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
                    ->JOIN('BUDGETING.DAT_BL','DAT_RINCIAN.BL_ID','=','DAT_BL.BL_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->JOIN('REFERENSI.REF_LOKASI','REF_LOKASI.LOKASI_ID','=','DAT_BL.LOKASI_ID')
                    ->JOIN('REFERENSI.REF_SUMBER_DANA','REF_SUMBER_DANA.DANA_ID','=','DAT_BL.SUMBER_ID')
                    ->WHERE('DAT_BL.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_DELETED',0)
                    ->WHERE('DAT_BL.BL_VALIDASI',1)
                    ->take('1000')
                    ->orderBy('REF_SKPD.SKPD_ID','asc')
                    ->get();        
        }else{
            $data   = RincianPerubahan::JOIN('BUDGETING.DAT_SUBRINCIAN_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN_PERUBAHAN.SUBRINCIAN_ID')
                    ->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN_PERUBAHAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
                    ->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN_PERUBAHAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
                    ->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN_PERUBAHAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
                    ->JOIN('BUDGETING.DAT_BL_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.BL_ID','=','DAT_BL_PERUBAHAN.BL_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL_PERUBAHAN.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL_PERUBAHAN.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->JOIN('REFERENSI.REF_LOKASI','REF_LOKASI.LOKASI_ID','=','DAT_BL_PERUBAHAN.LOKASI_ID')
                    ->JOIN('REFERENSI.REF_SUMBER_DANA','REF_SUMBER_DANA.DANA_ID','=','DAT_BL_PERUBAHAN.SUMBER_ID')
                    ->WHERE('DAT_BL_PERUBAHAN.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL_PERUBAHAN.BL_DELETED',0)
                    ->WHERE('DAT_BL_PERUBAHAN.BL_VALIDASI',1)
                    ->take('1000')
                    ->orderBy('REF_SKPD.SKPD_ID','asc')
                    ->get();
        }           

        $view           = array();
        foreach ($data as $data) {
            array_push($view, array( 
                                     'RUP_ID'               =>0,
                                     'SKPD_ID'              =>$data->SKPD_ID,
                                     'PROGRAM_ID'           =>$data->PROGRAM_ID,
                                     'KEGIATAN_ID'          =>$data->KEGIATAN_ID,
                                     'SUBRINCIAN_NAMA'      =>$data->SUBRINCIAN_NAMA,
                                     'LOKASI_NAMA'          =>$data->LOKASI_NAMA,
                                     'DETAIL_LOKASI_NAMA'   =>"-",
                                     'RINCIAN_VOLUME'       =>$data->RINCIAN_VOLUME,
                                     'PEKERJAAN_NAMA'       =>$data->PEKERJAAN_NAMA,
                                     'BL_PAGU'              =>$data->BL_PAGU,
                                     'DANA_ID'              =>$data->DANA_ID,
                                     'DANA_NAMA'            =>$data->DANA_NAMA,
                                     'DANA_SKPD'            =>'-',
                                     'MAK'                  =>$data->SKPD_KODE.'.'.$data->PROGRAM_KODE.'.'.$data->KEGIATAN_KODE.'.'.$data->REKENING_KODE,
                                     'RINCIAN_TOTAL'        =>$data->RINCIAN_TOTAL,
                                     'BL_AWAL'              =>$data->BL_AWAL,
                                     'BL_AKHIR'             =>$data->BL_AKHIR,
                                     'TIME_CREATED'         =>$data->TIME_CREATED,
                                     'TIME_UPDATED'         =>$data->TIME_UPDATED,
                                     'AKTIF'                =>'TRUE',
                                     
            ));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function apiSirupSwakelola($tahun,$status){
      if($status == 'murni'){
        $data   = Rincian::JOIN('BUDGETING.DAT_SUBRINCIAN','DAT_RINCIAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN.SUBRINCIAN_ID')
                    ->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
                    ->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
                    ->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
                    ->JOIN('BUDGETING.DAT_BL','DAT_RINCIAN.BL_ID','=','DAT_BL.BL_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->JOIN('REFERENSI.REF_LOKASI','REF_LOKASI.LOKASI_ID','=','DAT_BL.LOKASI_ID')
                    ->JOIN('REFERENSI.REF_SUMBER_DANA','REF_SUMBER_DANA.DANA_ID','=','DAT_BL.SUMBER_ID')
                    ->WHERE('DAT_BL.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_DELETED',0)
                    ->WHERE('DAT_BL.BL_VALIDASI',1)
                    ->take('1000')
                    ->orderBy('REF_SKPD.SKPD_ID','asc')
                    ->get();        
        }else{
            $data   = RincianPerubahan::JOIN('BUDGETING.DAT_SUBRINCIAN_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN_PERUBAHAN.SUBRINCIAN_ID')
                    ->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN_PERUBAHAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
                    ->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN_PERUBAHAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
                    ->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN_PERUBAHAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
                    ->JOIN('BUDGETING.DAT_BL_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.BL_ID','=','DAT_BL_PERUBAHAN.BL_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL_PERUBAHAN.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL_PERUBAHAN.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->JOIN('REFERENSI.REF_LOKASI','REF_LOKASI.LOKASI_ID','=','DAT_BL_PERUBAHAN.LOKASI_ID')
                    ->JOIN('REFERENSI.REF_SUMBER_DANA','REF_SUMBER_DANA.DANA_ID','=','DAT_BL_PERUBAHAN.SUMBER_ID')
                    ->WHERE('DAT_BL_PERUBAHAN.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL_PERUBAHAN.BL_DELETED',0)
                    ->WHERE('DAT_BL_PERUBAHAN.BL_VALIDASI',1)
                    ->take('1000')
                    ->orderBy('REF_SKPD.SKPD_ID','asc')
                    ->get();
        }           

        $view           = array();
        foreach ($data as $data) {
            array_push($view, array( 
                                     'RUP_ID'               =>0,
                                     'SKPD_ID'              =>$data->SKPD_ID,
                                     'PROGRAM_ID'           =>$data->PROGRAM_ID,
                                     'KEGIATAN_ID'          =>$data->KEGIATAN_ID,
                                     'SUBRINCIAN_NAMA'      =>$data->SUBRINCIAN_NAMA,
                                     'LOKASI_NAMA'          =>$data->LOKASI_NAMA,
                                     'DETAIL_LOKASI_NAMA'   =>"-",
                                     'RINCIAN_VOLUME'       =>$data->RINCIAN_VOLUME,
                                     'PEKERJAAN_NAMA'       =>$data->PEKERJAAN_NAMA,
                                     'BL_PAGU'              =>$data->BL_PAGU,
                                     'DANA_ID'              =>$data->DANA_ID,
                                     'DANA_NAMA'            =>$data->DANA_NAMA,
                                     'DANA_SKPD'            =>'-',
                                     'MAK'                  =>$data->SKPD_KODE.'.'.$data->PROGRAM_KODE.'.'.$data->KEGIATAN_KODE.'.'.$data->REKENING_KODE,
                                     'RINCIAN_TOTAL'        =>$data->RINCIAN_TOTAL,
                                     'BL_AWAL'              =>$data->BL_AWAL,
                                     'BL_AKHIR'             =>$data->BL_AKHIR,
                                     'TIME_CREATED'         =>$data->TIME_CREATED,
                                     'TIME_UPDATED'         =>$data->TIME_UPDATED,
                                     'AKTIF'                =>'TRUE',
                                     
            ));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }


    public function apiMonevProgram($tahun,$kode){
      //if($status == 'murni'){
        $data   = BL::JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                    ->JOIN('BUDGETING.DAT_OUTPUT','DAT_OUTPUT.BL_ID','=','DAT_BL.BL_ID','LEFT')
                    ->JOIN('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_OUTPUT.SATUAN_ID','LEFT')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->where('REF_SKPD.SKPD_KODE',$kode)
                    ->where('REF_SKPD.SKPD_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_DELETED',0)
                    ->WHERE('DAT_BL.BL_VALIDASI',1)
                     ->groupBy('PROGRAM_KODE', "PROGRAM_NAMA")
                    ->select('PROGRAM_KODE', "PROGRAM_NAMA")
                    ->get();        
       // }         

        $view           = array();
        foreach ($data as $data) {
            array_push($view, array( 
                                     'PROGRAM_KODE'           =>$data->PROGRAM_KODE,
                                     'PROGRAM_NAMA'           =>$data->PROGRAM_NAMA,
                                     
            ));
        }
        //$view = array("aaData"=>$view);      
        return Response::JSON($view);
    }

    public function apiAnggaran($tahun, $status, Request $req){
        $kode = ($req->kode == "")? "" : $req->kode;
        $view           = array();
        if($tahun="2018" && $status="pergeseran" && $kode=="4.05.36.01.002"){
            array_push($view, array( 
                'KODE_REKENING'           =>'4.05.36.01.002',
                'URAIAN'           => 'Kegiatan Penyediaan Jasa Komunikasi, Sumber Daya Air dan Listrik',
                'ANGGARAN'          => '63.212.400'                                 
            ));
        }else{
            array_push($view, array( 
                'KODE_REKENING'   => '-',
                'URAIAN'          => '-',
                'ANGGARAN'        => '-'                                     
            )); 
        }
        return Response::JSON($view);
    }

    public function apiMonevKegiatan($tahun,$kode, $kode_p){
      //if($status == 'murni'){
        $data   = BL::JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                    ->JOIN('BUDGETING.DAT_OUTPUT','DAT_OUTPUT.BL_ID','=','DAT_BL.BL_ID','LEFT')
                    ->JOIN('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_OUTPUT.SATUAN_ID','LEFT')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->where('REF_SKPD.SKPD_KODE',$kode)
                    ->where('REF_PROGRAM.PROGRAM_KODE',$kode_p)
                    ->where('REF_SKPD.SKPD_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_DELETED',0)
                    ->WHERE('DAT_BL.BL_VALIDASI',1)
                    ->get();        
       // }         

        $view           = array();
        foreach ($data as $data) {
            array_push($view, array( 
                                     'PROGRAM_KODE'           =>$data->PROGRAM_KODE,
                                     'PROGRAM_NAMA'           =>$data->PROGRAM_NAMA,
                                     'KEGIATAN_KODE'          =>$data->KEGIATAN_KODE,
                                     'KEGIATAN_NAMA'          =>$data->KEGIATAN_NAMA,
                                     'OUTPUT_TOLAK_UKUR'      =>$data->OUTPUT_TOLAK_UKUR,
                                     'OUTPUT_TARGET'      =>$data->OUTPUT_TARGET,
                                     'SATUAN_NAMA'      =>$data->SATUAN_NAMA,
                                     
            ));
        }
       // $view = array("aaData"=>$view);      
        return Response::JSON($view);
    }


}
