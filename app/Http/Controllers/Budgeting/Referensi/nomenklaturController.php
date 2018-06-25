<?php

namespace App\Http\Controllers\Budgeting\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Program;
use App\Model\Progunit;
use App\Model\Urusan;
use App\Model\Kegiatan;
use App\Model\SKPD;
use App\Model\Satuan;
use App\Model\Outcome;
use App\Model\OutputMaster;
use App\Model\Rekgiat;
use App\Model\Rekening;
use App\Model\Impact;
use App\Model\UserBudget;
use View;
use Carbon;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\Input;
class nomenklaturController extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
    }    

	public function index($tahun,$status){
		$urusan 	= Urusan::where('URUSAN_TAHUN',$tahun)->get();
        $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->get();
        $skpd_      = array(); 
        $i = 0;
        foreach($skpd as $s){
        $skpd_[$i]   = $s->SKPD_ID;
        $i++;
        }

        if(Auth::user()->level == 8){
            $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->get();    
        }elseif(Auth::user()->mod == '01000000000'){
            $skpd       = SKPD::whereIn('SKPD_ID',$skpd_)->where('SKPD_TAHUN',$tahun)->get();
        }else{            
            $skpdz       = $this->getSKPD($tahun);   
            $skpd       = SKPD::where('SKPD_ID',$skpdz)->first(); 
        }
		$rekening 	= Rekening::where('REKENING_TAHUN',$tahun)->where('REKENING_KODE','like','5.2%')->get();
        $satuan     = Satuan::orderBy('SATUAN_NAMA')->get();
    	return View('budgeting.referensi.nomenklatur',['tahun'=>$tahun,'status'=>$status,'rekening'=>$rekening,'urusan'=>$urusan,'skpd'=>$skpd,'satuan'=>$satuan]);
    }

    public function getData($tahun, Request $req){
        if($req->skpd){
            $skpd           = Progunit::where('SKPD_ID',$req->skpd)->pluck('PROGRAM_ID');
            $data 			= Program::where('PROGRAM_TAHUN',$tahun)->wherein('PROGRAM_ID',$skpd)
            ->orderBy('URUSAN_ID','PROGRAM_KODE')
            ->get();
        } else {
            $data 			= Program::where('PROGRAM_TAHUN',$tahun)
    							->orderBy('URUSAN_ID','PROGRAM_KODE')
    							->get();
        }
        $aksi           = '';
    	$view 			= array();
    	foreach ($data as $data) {
            if(Auth::user()->level == 8){
                $aksi       = '<div class="action visible pull-right"><a title="Ubah Capaian" onclick="return showCapaian(\''.$data->PROGRAM_ID.'\')" class="action-edit"><i class="mi-eye"></i></a><a title="Ubah Program" onclick="return ubahProgram(\''.$data->PROGRAM_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a title="Hapus Program" onclick="return hapusProgram(\''.$data->PROGRAM_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            }else{
                 $aksi       = '<div class="action visible pull-right"><a title="Ubah Capaian" onclick="return showCapaian(\''.$data->PROGRAM_ID.'\')" class="action-edit"><i class="mi-eye"></i></a></div>';
            }
    		array_push($view, array( 'id_program'		=>$data->PROGRAM_ID,
                                     'OPSI'				=>$aksi,
                                     'URUSAN'  			=>$data->urusan->URUSAN_KODE." - ".$data->urusan->URUSAN_NAMA,
                                     'PROGRAM'			=>"<i class='mi-caret-down m-r-sm'></i>".$data->PROGRAM_KODE." - ".$data->PROGRAM_NAMA));
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
    }

	public function getDataDetail(Request $req, $tahun,$status,$id){
        $start = ($req->iDisplayStart == "")? 0 : $req->iDisplayStart;
        $length = ($req->iDisplayLength == "")? 10 : $req->iDisplayLength;
    	$data 			= Kegiatan::where('PROGRAM_ID',$id)
                                    ->limit($length)
                                    ->offset($start)->get();
        $count = Kegiatan::where('PROGRAM_ID',$id)->get()->count();
    	$no 			= 1;
    	$aksi 			= '';
    	$view 			= array();
    	foreach ($data as $data) {
                if(Auth::user()->level == 8){
                $aksi       = '<div class="action visible pull-right"><a onclick="return showRekeningGiat(\''.$data->KEGIATAN_ID.'\')" title="Cek Rekening" class="action-edit"><i class="icon-bdg_form"></i></a><a onclick="return showIndikatorGiat(\''.$data->KEGIATAN_ID.'\')" title="Ubah Output" class="action-edit"><i class="mi-eye"></i></a><a title="Ubah Kegiatan" onclick="return ubahGiat(\''.$data->KEGIATAN_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a title="Hapus Kegiatan" onclick="return hapusGiat(\''.$data->KEGIATAN_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
           }elseif(Auth::user()->level == 9){
                $aksi       = '<div class="action visible pull-right"><a onclick="return showIndikatorGiat(\''.$data->KEGIATAN_ID.'\')" title="Ubah Output" class="action-edit"><i class="mi-eye"></i></a></div>';
            }else{
                $aksi       = '-';
            }
    		array_push($view, array( 'KEGIATAN_ID' 		=>$data->KEGIATAN_ID,
    								 'KEGIATAN_KODE'  	=>$data->KEGIATAN_KODE,
                                     'KEGIATAN_NAMA'	=>$data->KEGIATAN_NAMA,
                                     'AKSI'				=>$aksi));
    		$no++;
        }
        $display = $data->count();
		$out = array("iTotalRecords" => intval($display), "iTotalDisplayRecords"  => intval($count),"aaData"=>$view);    	
    	return Response::JSON($out);
    }

    public function getDetail($tahun,$status,$id){
    	$data 			= Program::where('PROGRAM_ID',$id)->get();
        $skpd           = Progunit::where('PROGRAM_ID',$id)->get();
        $view           = "";
        foreach($skpd as $s){
            $view .= "<option value='".$s->SKPD_ID."' selected>".$s->skpd->SKPD_NAMA."</option>";
        }
    	return ['data'=>$data,'skpd'=>$view];
    }

    public function getRekGiat($tahun,$status,$id){
        $dataRekGiat      = Rekgiat::where('KEGIATAN_ID',$id)->where('TAHUN',$tahun)
                            ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','REF_REKGIAT.REKENING_ID')->get();
        $view               = array();
        foreach ($dataRekGiat as $dc) {
            $aksi       = '<div class="action visible pull-right"><a onclick="return editRekGiat(\''.$dc->REKGIAT_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapusRekGiat(\''.$dc->REKGIAT_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            array_push($view, array( 'REKENING_KODE'        =>$dc->REKENING_KODE,
                                     'REKENING_NAMA'       =>$dc->REKENING_NAMA,
                                     'REKENING_KUNCI'           =>($dc->REKENING_KUNCI == 1 ? "ditutup" : "dibuka"),
                                     'AKSI'             =>$aksi));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function detailRekGiat($tahun,$status,$id){
        $data   = Rekgiat::where('REKGIAT_ID',$id)->first();
        return Response::JSON($data);
    }

    public function editRekGiat($tahun,$status){
        Rekgiat::where('REKGIAT_ID',Input::get('id'))->update([
                'REKENING_ID'    => Input::get('idrekening')
                ]);
        return 'Berhasil!';
    }

    public function submitRekGiat($tahun,$status){
        $o  = new Rekgiat;
        $o->REKGIAT_ID           = Rekgiat::max('REKGIAT_ID')+1;
        $o->KEGIATAN_ID         = Input::get('idkegiatan');
        $o->REKENING_ID   = Input::get('idrekening');
        $o->TAHUN       = $tahun;
        $o->save();            
        return 'Berhasil!';
    }

    public function hapusRekGiat($tahun,$status){
        Rekgiat::where('REKGIAT_ID',Input::get('id'))->delete();
        return 'Berhasil!';
    }

    public function getOutput($tahun,$status,$id){
        $dataCapaian       = OutputMaster::where('KEGIATAN_ID',$id)->get();
        $view               = array();
        foreach ($dataCapaian as $dc) {
            $aksi       = '<div class="action visible pull-right"><a onclick="return editOutput(\''.$dc->OUTPUT_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapusOutput(\''.$dc->OUTPUT_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            array_push($view, array( 'INDIKATOR'        =>'Keluaran',
                                     'TOLAK_UKUR'       =>$dc->OUTPUT_TOLAK_UKUR,
                                     'TARGET'           =>$dc->OUTPUT_TARGET.' '.$dc->satuan->SATUAN_NAMA,
                                     'AKSI'             =>$aksi));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function detailOutput($tahun,$status,$id){
        $data   = OutputMaster::where('OUTPUT_ID',$id)->first();
        return Response::JSON($data);
    }

    public function editOutput($tahun,$status){
        OutputMaster::where('OUTPUT_ID',Input::get('id'))->update([
                'OUTPUT_TOLAK_UKUR'    => Input::get('tolakukur'),
                'OUTPUT_TARGET'        => Input::get('target'),
                'SATUAN_ID'            => Input::get('satuan')
                ]);
        return 'Berhasil!';
    }

    public function submitOutput($tahun,$status){
        $o  = new OutputMaster;
        $o->OUTPUT_ID           = OutputMaster::max('OUTPUT_ID')+1;
        $o->KEGIATAN_ID         = Input::get('idkegiatan');
        $o->OUTPUT_TOLAK_UKUR   = Input::get('tolakukur');
        $o->OUTPUT_TARGET       = Input::get('target');
        $o->SATUAN_ID           = Input::get('satuan');
        $o->save();            
        return 'Berhasil!';
    }

    public function hapusOutput($tahun,$status){
        OutputMaster::where('OUTPUT_ID',Input::get('id'))->delete();
        return 'Berhasil!';
    }

}
