<?php

namespace App\Http\Controllers\EHarga;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Carbon;
use App;
use Auth;
use View;
use Response;
use Session;
use QrCode;
use PDF;
use App\Model\Rekening;
use App\Model\Komponen;
use App\Model\Satuan;
use App\Model\KatKom;
use App\Model\UsulanKomponen;
use App\Model\SKPD;
use App\Model\User;
use App\Model\UserBudget;
use App\Model\Rekom;
use App\Model\DataDukung;
use App\Model\UsulanSurat;

class monitorUsulanController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index($tahun){
    	$skpd 	= SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
    	return View('eharga.monitor',['tahun'=>$tahun,'skpd'=>$skpd]);
    }

    public function getData($tahun){
    	if(substr(Auth::user()->mod,3,1) == 1){
        	$data 	= UsulanKomponen::where('USER_CREATED',Auth::user()->id)
                                    ->where('USULAN_TAHUN',$tahun)
                                    ->orderBy('USULAN_ID')->get();
        }elseif(Auth::user()->level == 2){
            $skpd      = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->first();
            $datauser  = UserBudget::whereHas('user',function($q){
                            $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                        })->where('SKPD_ID',$skpd->SKPD_ID)->value('USER_ID');
            if($skpd->skpd->SKPD_JENIS == 1){
                $pd     = SKPD::where('SKPD_JENIS', 2)->get();
                $i = 0;
                foreach($pd as $pd){
                    $id     = $pd->SKPD_ID;
                    $user[$i]   = UserBudget::where('SKPD_ID',$id)
                                            ->whereHas('user',function($q){
                                                $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                                            })->value('USER_ID');
                    $i++;
                }
                $user[$i] = $datauser;
            }elseif($skpd->skpd->SKPD_JENIS == 3){
                $pd     = SKPD::where('SKPD_JENIS', 4)->get();
                $i = 0;
                foreach($pd as $pd){
                    $id     = $pd->SKPD_ID;
                    $user[$i]   = UserBudget::where('SKPD_ID',$id)
                                            ->whereHas('user',function($q){
                                                $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                                            })->value('USER_ID');
                    $i++;
                }
                $user[$i] = $datauser;
            }elseif($skpd->skpd->SKPD_JENIS == 2){
                $user[0]    = null; 
            }else{
                $user[0]    = $datauser;
            }
            $data   = UsulanKomponen::whereIn('USER_CREATED',$user)
                                    ->where('USULAN_TAHUN',$tahun)
                                    ->orderBy('USULAN_ID')->get();
        }elseif(substr(Auth::user()->mod,4,1) == 1){
            /*$data   = UsulanKomponen::where('USER_POST',Auth::user()->id) 
                                    ->where('USULAN_TAHUN',$tahun)                                               
                                    ->orderBy('USULAN_ID')
                                    ->get();*/
            if(Auth::user()->level == 1){
                $data   = UsulanKomponen::where('USER_POST',Auth::user()->id) 
                                    ->where('USULAN_TAHUN',$tahun)                                               
                                    ->orderBy('USULAN_ID')
                                    ->get();
            }
            elseif(Auth::user()->level == 0){
                $data   = UsulanKomponen::where('USULAN_TAHUN',$tahun)->orderBy('USULAN_ID')->get();
            }
        }else{
            $data   = UsulanKomponen::where('USULAN_TAHUN',$tahun)->orderBy('USULAN_ID')->get();
        }
		
    	$i 		= 1;
    	$view 	= array();

    	foreach ($data as $data) {
    		$post = '';$stat = '';
            if(empty($data->DD_ID)){
                $dd     = '';
            }else{
                $dd         = '<a href="/uploads/komponen/'.$tahun.'/'.$data->datadukung->DD_PATH.'/dd.pdf" target="_blank"><i class="fa fa-file-pdf-o"></i></a>';
            }
            if(empty($data->REKENING_ID)) $rekening = '-';
            else $rekening = $data->rekening->REKENING_KODE.'<br>'.$data->rekening->REKENING_NAMA;
            
            $tipe = '';
            if($data->USULAN_TYPE == 1) $tipe 			= 'Komponen Baru';
            elseif($data->USULAN_TYPE == 2) $tipe 		= 'Ubah Komponen';
            elseif($data->USULAN_TYPE == 3) $tipe 		= 'Tambah Rekening';
            
            if($data->USULAN_POSISI == 0) $posisi 		= 'Rencana';
            elseif($data->USULAN_POSISI == 1)$posisi 	= 'Pengajuan';
            elseif($data->USULAN_POSISI == 2)$posisi 	= 'Verifikasi';
            elseif($data->USULAN_POSISI == 3)$posisi 	= 'Validasi';
            elseif($data->USULAN_POSISI == 4 and $data->SURAT_ID == "")$posisi    = 'Surat';
            elseif($data->USULAN_POSISI == 4 and $data->SURAT_ID != "")$posisi 	  = 'Disposisi 1';
            elseif($data->USULAN_POSISI == 5)$posisi 	= 'Disposisi 2';
            elseif($data->USULAN_POSISI == 6)$posisi 	= 'Disposisi 3';
            elseif($data->USULAN_POSISI == 7)$posisi 	= 'Posting';
            elseif($data->USULAN_POSISI == 8)$posisi 	= 'Ebudgeting';
            elseif($data->USULAN_POSISI == 9)$posisi    = 'Pembahasan';            
            $pd     = UserBudget::where('USER_ID',$data->USER_CREATED)->first();            
            array_push($view, array( 'NO'    	=>$i,
                                     'PD'       =>$pd->skpd->SKPD_NAMA,
                                     'NAMA'    	=>'<span class="text-success">'.$data->USULAN_NAMA." ".$dd."</span><br>".
                                     			  $data->katkom->KATEGORI_KODE.' - '.$data->katkom->KATEGORI_NAMA.
                                     			  "<br><p class='text-orange'>Spesifikasi : ".$data->USULAN_SPESIFIKASI.'</p>',
                                     'REKENING' =>$rekening,
                                     'TIPE'     =>$tipe,
                                     'POSISI'   =>$posisi,
                                     'HARGA'   	=>number_format($data->USULAN_HARGA,2,'.',',')));
            $i++;
        }
        
        $display = $data->count(); 
        $out = array("iTotalRecords" => intval($display), "iTotalDisplayRecords"  => 10,"aaData"=>$view); 
        return Response::JSON($out);
    }

    public function getFilter($tahun,$tipe,$jenis,$opd,$posisi){
        
        if(substr(Auth::user()->mod,3,1) == 1){
        	$data 	= UsulanKomponen::where('USER_CREATED',Auth::user()->id)
                                    ->where('USULAN_TAHUN',$tahun)
                                    ->orderBy('USULAN_ID');
        	if($tipe != 'x') $data 		= $data->where('USULAN_TYPE',$tipe);
        	if($jenis != 'x') $data 	= $data->whereHas('katkom',function($q) use($jenis){
								        		$q->where('KATEGORI_KODE','like',$jenis.'%');
								        	});
        	if($posisi != 'x'){
                if($posisi == '4a') $data   = $data->where('USULAN_POSISI',4)->whereRaw('"SURAT_ID" IS NULL');
                elseif($posisi == '4b') $data   = $data->where('USULAN_POSISI',4)->whereRaw('"SURAT_ID" IS NOT NULL');
                else $data   = $data->where('USULAN_POSISI',$posisi);  
            } 
        	$data = $data->get();
        }elseif(Auth::user()->level == 2){
            
            $skpd      = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->first();
            $user  = UserBudget::where('SKPD_ID',$skpd->SKPD_ID)->pluck('USER_ID')->toArray();
            /*if($skpd->skpd->SKPD_JENIS == 1){
                $pd     = SKPD::where('SKPD_JENIS', 2)->get();
                $i = 0;
                foreach($pd as $pd){
                    $id     = $pd->SKPD_ID;
                    $user[$i]   = UserBudget::where('SKPD_ID',$id)
                                            ->whereHas('user',function($q){
                                                $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                                            })->value('USER_ID');
                    $i++;
                }
                $user[$i] = $datauser;
            }elseif($skpd->skpd->SKPD_JENIS == 3){
                $pd     = SKPD::where('SKPD_JENIS', 4)->get();
                $i = 0;
                foreach($pd as $pd){
                    $id     = $pd->SKPD_ID;
                    $user[$i]   = UserBudget::where('SKPD_ID',$id)
                                            ->whereHas('user',function($q){
                                                $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                                            })->value('USER_ID');
                    $i++;
                }
                $user[$i] = $datauser;
            }elseif($skpd->skpd->SKPD_JENIS == 2){
                $user[0]    = null; 
            }else{
                $user[0]    = $datauser;
            }*/
            $data   = UsulanKomponen::whereIn('USER_CREATED',$user)
                                    ->where('USULAN_TAHUN',$tahun)
                                    ->orderBy('USULAN_ID');
            if($tipe != 'x') $data 		= $data->where('USULAN_TYPE',$tipe);
        	if($jenis != 'x') $data 	= $data->whereHas('katkom',function($q) use($jenis){
								        		$q->where('KATEGORI_KODE','like',$jenis.'%');
								        	});
        	if($posisi != 'x'){
                if($posisi == '4a') $data   = $data->where('USULAN_POSISI',4)->whereRaw('"SURAT_ID" IS NULL');
                elseif($posisi == '4b') $data   = $data->where('USULAN_POSISI',4)->whereRaw('"SURAT_ID" IS NOT NULL');
                else $data   = $data->where('USULAN_POSISI',$posisi);  
            }
        	$data = $data->get();
        }elseif(substr(Auth::user()->mod,4,1) == 1){
            $data   = UsulanKomponen::where('USER_POST',Auth::user()->id)
                                    ->where('USULAN_TAHUN',$tahun)
                                    ->orderBy('USULAN_ID');
            if($tipe != 'x') $data 		= $data->where('USULAN_TYPE',$tipe);
        	if($jenis != 'x') $data 	= $data->whereHas('katkom',function($q) use($jenis){
								        		$q->where('KATEGORI_KODE','like',$jenis.'%');
								        	});
            if($posisi != 'x'){
                if($posisi == '4a') $data   = $data->where('USULAN_POSISI',4)->whereRaw('"SURAT_ID" IS NULL');
                elseif($posisi == '4b') $data   = $data->where('USULAN_POSISI',4)->whereRaw('"SURAT_ID" IS NOT NULL');
                else $data   = $data->where('USULAN_POSISI',$posisi);  
            }
        	if($opd != 'x'){
	        	$datauser  = UserBudget::whereHas('user',function($q){
	                            $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
	                        })->where('SKPD_ID',$opd)->value('USER_ID');
	            $data 		= $data->where('USER_CREATED',$datauser);
        	}
        	$data = $data->get();
        }else{
            $data   = UsulanKomponen::where('USULAN_TAHUN',$tahun)
                                    ->orderBy('USULAN_ID');
            if($tipe != 'x') $data 		= $data->where('USULAN_TYPE',$tipe);
        	if($jenis != 'x') $data 	= $data->whereHas('katkom',function($q) use($jenis){
								        		$q->where('KATEGORI_KODE','like',$jenis.'%');
								        	});
            if($posisi != 'x'){
                if($posisi == '4a') $data   = $data->where('USULAN_POSISI',4)->whereRaw('"SURAT_ID" IS NULL');
                elseif($posisi == '4b') $data   = $data->where('USULAN_POSISI',4)->whereRaw('"SURAT_ID" IS NOT NULL');
                else $data   = $data->where('USULAN_POSISI',$posisi);  
            }
        	if($opd != 'x'){
	        	$datauser  = UserBudget::whereHas('user',function($q){
	                            $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
	                        })->where('SKPD_ID',$opd)->value('USER_ID');
	            $data 		= $data->where('USER_CREATED',$datauser);
        	}
        	$data = $data->get();
        }
		
    	$i 		= 1;
    	$view 	= array();

    	foreach ($data as $data) {
    		$post = '';$stat = '';
            if(empty($data->DD_ID)){
                $dd     = '';
            }else{
                $dd         = '<a href="/uploads/komponen/'.$tahun.'/'.$data->datadukung->DD_PATH.'/dd.pdf" target="_blank"><i class="fa fa-file-pdf-o"></i></a>';
            }
            if(empty($data->REKENING_ID)) $rekening = '-';
            else $rekening = $data->rekening->REKENING_KODE.'<br>'.$data->rekening->REKENING_NAMA;
            
            $tipe = '';
            if($data->USULAN_TYPE == 1) $tipe 			= 'Komponen Baru';
            elseif($data->USULAN_TYPE == 2) $tipe 		= 'Ubah Komponen';
            elseif($data->USULAN_TYPE == 3) $tipe 		= 'Tambah Rekening';
            
            if($data->USULAN_POSISI == 0) $posisi 		= 'Rencana';
            elseif($data->USULAN_POSISI == 1)$posisi 	= 'Pengajuan';
            elseif($data->USULAN_POSISI == 2)$posisi 	= 'Verifikasi';
            elseif($data->USULAN_POSISI == 3)$posisi 	= 'Validasi';
            elseif($data->USULAN_POSISI == 4 and $data->SURAT_ID == "")$posisi  = 'Surat';
            elseif($data->USULAN_POSISI == 4 and $data->SURAT_ID != "")$posisi 	= 'Disposisi 1';
            elseif($data->USULAN_POSISI == 5)$posisi 	= 'Disposisi 2';
            elseif($data->USULAN_POSISI == 6)$posisi 	= 'Disposisi 3';
            elseif($data->USULAN_POSISI == 7)$posisi 	= 'Posting';
            elseif($data->USULAN_POSISI == 8)$posisi 	= 'Ebudgeting';
            elseif($data->USULAN_POSISI == 9)$posisi    = 'Pembahasan';            
            $pd     = UserBudget::where('USER_ID',$data->USER_CREATED)->first();            
            array_push($view, array( 'NO'    	=>$i,
                                     'PD'       =>$pd->skpd->SKPD_NAMA,
                                     'NAMA'    	=>'<span class="text-success">'.$data->USULAN_NAMA." ".$dd."</span><br>".
                                     			  $data->katkom->KATEGORI_KODE.' - '.$data->katkom->KATEGORI_NAMA.
                                     			  "<br><p class='text-orange'>Spesifikasi : ".$data->USULAN_SPESIFIKASI.'</p>',
                                     'REKENING' =>$rekening,
                                     'TIPE'     =>$tipe,
                                     'POSISI'   =>$posisi,
                                     'HARGA'   	=>number_format($data->USULAN_HARGA,2,'.',',')));
            $i++;
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }
}
