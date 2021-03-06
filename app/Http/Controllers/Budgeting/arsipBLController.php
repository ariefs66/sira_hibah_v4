<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use View;
use Response;
use Auth;
use Carbon;
use App\Model\SKPD;
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
use App\Model\Pekerjaan;
use App\Model\Rekening;
use App\Model\Komponen;
use App\Model\Rekom;
use App\Model\Rincian;
use App\Model\RincianPerubahan;
use App\Model\RincianArsip;
use App\Model\RincianArsipPerubahan;
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
use App\Model\Outcome;
use App\Model\Impact;
class arsipBLController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    //SHOW
    public function index($tahun,$status){
        $pagu_foot       = BL::where('BL_TAHUN',$tahun)->where('BL_DELETED',1)->sum('BL_PAGU');
        $rincian_foot    = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                                ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                                ->where('DAT_BL.BL_TAHUN',$tahun)->where('DAT_BL.BL_DELETED',1)
                                ->sum('DAT_RINCIAN.RINCIAN_TOTAL');
		return View('budgeting.belanja-langsung.arsip',['tahun'=>$tahun,'status'=>$status,'pagu_foot'=>number_format($pagu_foot,0,'.',','),'rincian_foot'=>number_format($rincian_foot,0,'.',',')]);
    }

    public function getData($tahun,$status){
        $skpd       = $this->getSKPD($tahun);
        if($status=="murni"){
            $edit       = RincianArsip::orderBy('BL_ID','desc')->pluck('BL_ID')->toArray();
            $data       = BL::where('BL_TAHUN',$tahun)->where('BL_DELETED',1)->orWhereIn('DAT_BL.BL_ID',$edit);
        }else{
            $edit       = RincianArsipPerubahan::orderBy('BL_ID','desc')->pluck('BL_ID')->toArray();
            $data       = BLPerubahan::where('BL_TAHUN',$tahun)->where('BL_DELETED',1)->orWhereIn('DAT_BL_PERUBAHAN.BL_ID',$edit);
        }
        if(Auth::user()->level != 8){
            $data     = $data->where('SKPD_ID',$skpd);
        }
        $data = $data->get();

        $view       = array();
        $i          = 1;
        $kunci      = '';
        $rincian    = '';
        $validasi   = '';
        foreach ($data as $data) {
              /*$aksi 	= '<div class="action visible pull-right">
        				<a onclick="return restore(\''.$data->BL_ID.'\')" title="Kembalikan Belanja?"><i class="fa fa-retweet" ></i></a>
        				<a onclick="return hapus(\''.$data->BL_ID.'\')" title="Hapus Belanja Secara Permanen?"><i class="mi-trash"></i></a>
        			   </div>';*/
            $aksi   = '<div class="action visible pull-right">
                        <a onclick="return restore(\''.$data->BL_ID.'\')" title="Kembalikan Belanja?"><i class="fa fa-retweet" ></i></a>
                       </div>';  
            if(empty($data->rincian)) $totalRincian = 0;
            else $totalRincian = number_format($data->rincian->sum('RINCIAN_TOTAL'),0,',','.');
            if(!empty($data->kegiatan->program)){
                if(is_array($data->kegiatan->program)){
                    array_push($view, array( 'NO'             =>$i,
                    'KEGIATAN'       =>'.'.$data->subunit->skpd->SKPD_KODE.'.'.' - '.'<br><p class="text-orange">'.$data->kegiatan->program->urusan->URUSAN_KODE.'.'.$data->subunit->skpd->SKPD_KODE.'.'.'.'.$data->kegiatan->KEGIATAN_KODE.' - '.$data->kegiatan->KEGIATAN_NAMA.'</p><span class="text-success">'.$data->subunit->skpd->SKPD_KODE.'.'.$data->subunit->SUB_KODE.' - '.$data->subunit->SUB_NAMA.'</span>',
                    'PAGU'           =>number_format($data->BL_PAGU,0,',','.'),
                    'RINCIAN'        =>$totalRincian,
                    'STATUS'         =>$aksi));
                }else{
                    array_push($view, array( 'NO'             =>$i,
                    'KEGIATAN'       =>$data->kegiatan->program->urusan->URUSAN_KODE.'.'.$data->subunit->skpd->SKPD_KODE.'.'.$data->kegiatan->program->PROGRAM_KODE.' - '.$data->kegiatan->program->PROGRAM_NAMA.'<br><p class="text-orange">'.$data->kegiatan->program->urusan->URUSAN_KODE.'.'.$data->subunit->skpd->SKPD_KODE.'.'.$data->kegiatan->program->PROGRAM_KODE.'.'.$data->kegiatan->KEGIATAN_KODE.' - '.$data->kegiatan->KEGIATAN_NAMA.'</p><span class="text-success">'.$data->subunit->skpd->SKPD_KODE.'.'.$data->subunit->SUB_KODE.' - '.$data->subunit->SUB_NAMA.'</span>',
                    'PAGU'           =>number_format($data->BL_PAGU,0,',','.'),
                    'RINCIAN'        =>$totalRincian,
                    'STATUS'         =>$aksi));
                }    
            }else{
                array_push($view, array( 'NO'             =>$i,
                'KEGIATAN'       =>$data->subunit->skpd->SKPD_KODE.'<br><p class="text-orange">Kegiatan</p><span class="text-success">'.$data->subunit->skpd->SKPD_KODE.'.'.$data->subunit->SUB_KODE.' - '.$data->subunit->SUB_NAMA.'</span>',
                'PAGU'           =>number_format($data->BL_PAGU,0,',','.'),
                'RINCIAN'        =>$totalRincian,
                'STATUS'         =>$aksi));
            }

            $i++;
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
        return $view;
    }

    public function restore($tahun,$status){
        if($status=="murni"){
            BL::where('BL_ID',Input::get('id'))->update(['BL_DELETED'=>0]);
        }else{
            BLPerubahan::where('BL_ID',Input::get('id'))->update(['BL_DELETED'=>0]);
        }
        $log                = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Mengembalikan Belanja Langsung';
        $log->LOG_DETAIL                        = 'BL#'.Input::get('id');
        $log->save();
    	return 'Berhasil Mengembalikan Belanja!';
    }

    public function delete($tahun,$status){
        if($status=="murni"){
            BL::where('BL_ID',Input::get('id'))->delete();
            Rincian::where('BL_ID',Input::get('id'))->delete();
            Kunci::where('BL_ID',Input::get('id'))->delete();
            Subrincian::where('BL_ID',Input::get('id'))->delete();
        }else{
            BLPerubahan::where('BL_ID',Input::get('id'))->delete();
            RincianPerubahan::where('BL_ID',Input::get('id'))->delete();
            KunciPerubahan::where('BL_ID',Input::get('id'))->delete();
            SubrincianPerubahan::where('BL_ID',Input::get('id'))->delete();
        }
    	Staff::where('BL_ID',Input::get('id'))->delete();

        $log                = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Menghapus Belanja Langsung';
        $log->LOG_DETAIL                        = 'BL#'.Input::get('id');
        $log->save();
    	return 'Berhasil Menghapus Belanja!';
    }
}
