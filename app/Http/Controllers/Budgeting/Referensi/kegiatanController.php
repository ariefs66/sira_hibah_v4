<?php

namespace App\Http\Controllers\Budgeting\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Program;
use App\Model\Progunit;
use App\Model\Kegunit;
use App\Model\Urusan;
use App\Model\Kegiatan;
use App\Model\SKPD;
use App\Model\Output;
use App\Model\OutputPerubahan;
use View;
use Carbon;
use Response;
use DB;
use Illuminate\Support\Facades\Input;
class kegiatanController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function indexReferensi($tahun,$status){
        return View('budgeting.referensi.kegiatan',['tahun'=>$tahun,'status'=>$status]);
    }
    public function getReferensi($tahun,$status){
    	$data 	= Kegiatan::whereHas('program',function($q){
                $q->whereNotIn('PROGRAM_KODE',['01','02','03','04','05','06']);
            })->where('KEGIATAN_TAHUN',$tahun)->get();
    	$view 			= array();
    	foreach ($data as $data) {
    		array_push($view, array( 'ID'           =>$data->KEGIATAN_ID,
                                     'URUSAN' 		=>$data->program->urusan->URUSAN_KODE.' - '.$data->program->urusan->URUSAN_NAMA,
    								 'PROGRAM' 		=>$data->program->PROGRAM_KODE.' - '.$data->program->PROGRAM_NAMA,
                                     'KEGIATAN'		=>$data->KEGIATAN_KODE.' - '.$data->KEGIATAN_NAMA));
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
    }

    public function getProgram($tahun,$status,$id){
    	$data 	= Program::where('URUSAN_ID',$id)->get();
    	$view   = "";
        foreach($data as $d){
            $view .= "<option value='".$d->PROGRAM_ID."'>".$d->PROGRAM_KODE." - ".$d->PROGRAM_NAMA."</option>";
        }
        return $view;
    }

    public function getDetail($tahun,$status,$id){
    	$data 			= Kegiatan::where('KEGIATAN_ID',$id)->get();
        $skpd           = Kegunit::where('KEGIATAN_ID',$id)->where('TAHUN',$tahun)->get();
        $view           = "";
        $urusan         = "";
        foreach($data as $d){
            $urusan = Program::where('PROGRAM_ID',$d->PROGRAM_ID)->value('URUSAN_ID');
        }
        foreach($skpd as $s){
            if($s->skpd){
                $view .= "<option value='".$s->SKPD_ID."' selected>".$s->skpd->SKPD_NAMA."</option>";
            }
        }
    	return ['data'=>$data,'skpd'=>$view,'urusan'=>$urusan];
    }

    public function submitAdd(){
    	$kegiatan 	= new Kegiatan;
    	$no 		= Kegiatan::where('KEGIATAN_TAHUN',Input::get('tahun'))
    						->where('PROGRAM_ID',Input::get('program')) 
    						->orderBy('KEGIATAN_KODE','DESC')
    						->value('KEGIATAN_KODE');
    	$kode 		= "";
        
    	if(empty($no)){
    		$kode 	= '001';
    	}else{
    		$no = $no+1;
    		if($no < 10){
    			$kode 	= '00'.$no;
    		}elseif($no < 100){
    			$kode 	= '0'.$no;
    		}else{
    			$kode 	= $no;
    		}
        }
            $idgiat                         = Kegiatan::max('KEGIATAN_ID') + 1;
            $kegiatan->KEGIATAN_ID          = $idgiat;
            $kegiatan->KEGIATAN_TAHUN       = Input::get('tahun');
            $kegiatan->PROGRAM_ID           = Input::get('program');
            $kegiatan->KEGIATAN_KODE        = $kode;
	    	$kegiatan->KEGIATAN_NAMA        = Input::get('kegiatan');
            if(Input::get('kunci_kegiatan')){
                $kegiatan->KEGIATAN_KUNCI        = Input::get('kunci_kegiatan');
            }
            if(Input::get('prioritas_kegiatan')){
                $kegiatan->KEGIATAN_PRIORITAS        = Input::get('prioritas_kegiatan');
            }
            $kegiatan->save();

            /*$idgiat  	= Kegiatan::where('KEGIATAN_TAHUN',Input::get('tahun'))
                                    ->where('PROGRAM_ID',Input::get('program'))
                                    ->where('KEGIATAN_KODE',$kode)
            
                                    ->value('KEGIATAN_ID');
            $idgiat = Kegiatan::where('KEGIATAN_TAHUN',Input::get('tahun'))
                            ->max('KEGIATAN_ID');*/

            $skpd       = Input::get('skpd');
            foreach($skpd as $s){
                $kg     = new Kegunit;
                $kg->KEGUNIT_ID     = Kegunit::max('KEGUNIT_ID') + 1;
                $kg->TAHUN          = Input::get('tahun');
                $kg->KEGIATAN_ID    = $idgiat;
                $kg->SKPD_ID        = $s;
                $kg->save();
            }
	    	return '1';
    }

    public function submitEdit(){
        $cek        = Kegiatan::where('KEGIATAN_ID',Input::get('id_giat'))->first();
        $no         = Kegiatan::where('KEGIATAN_TAHUN',Input::get('tahun'))
                            ->where('PROGRAM_ID',Input::get('program')) 
                            ->orderBy('KEGIATAN_KODE','DESC')
                            ->value('KEGIATAN_KODE');
        $kode       = "";
        if($cek['PROGRAM_ID'] == Input::get('program')){
            $kode   = $no;
        }elseif(empty($no)){
            $kode   = '001';
        }else{
            $no = $no+1;
            if($no < 10){
                $kode   = '00'.$no;
            }elseif($no < 100){
                $kode   = '0'.$no;
            }else{
                $kode   = $no;
            }
        }
        if(Input::get('kunci_kegiatan') && Input::get('prioritas_kegiatan') ){
            Kegiatan::where('KEGIATAN_ID',Input::get('id_giat'))
            ->update(['KEGIATAN_TAHUN'       =>Input::get('tahun'),
                      'KEGIATAN_KODE'        =>$kode,
                      'PROGRAM_ID'           =>Input::get('program'),
                      'KEGIATAN_NAMA'        =>Input::get('kegiatan'),
                      'KEGIATAN_KUNCI'        =>Input::get('kunci_kegiatan'),
                      'KEGIATAN_PRIORITAS'        =>Input::get('prioritas_kegiatan')]);
        } else {
            Kegiatan::where('KEGIATAN_ID',Input::get('id_giat'))
            ->update(['KEGIATAN_TAHUN'       =>Input::get('tahun'),
                        'KEGIATAN_KODE'        =>$kode,
                        'PROGRAM_ID'           =>Input::get('program'),
                        'KEGIATAN_NAMA'        =>Input::get('kegiatan')]);
        }
        Kegunit::where('KEGIATAN_ID',Input::get('id_giat'))->delete();
            $skpd       = Input::get('skpd');
            if($skpd){
                foreach($skpd as $s){
                    $pd                = new Kegunit;
                    $pd->KEGUNIT_ID    = Kegunit::max('KEGUNIT_ID') + 1;
                    $pd->KEGIATAN_ID   = Input::get('id_giat');
                    $pd->TAHUN         = Input::get('tahun');
                    $pd->SKPD_ID       = $s;
                    $pd->save();
                }
            }
    		return '1';
    }

    public function delete(){
        Kegiatan::where('KEGIATAN_ID',Input::get('id_giat'))->delete();
        return 'Berhasil dihapus!';
    }

    public function getCapaian($tahun,$status,$id){
        if($status=="murni"){
            $dataCapaian       = Output::where('BL_ID',$id)->get();
        }else{
            $dataCapaian       = OutputPerubahan::where('BL_ID',$id)->get();
        }
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

    public function submitCapaian($tahun,$status){
        if($status=="murni"){
            $o  = new Output;
            $get_id  = Output::max('OUTPUT_ID');
        }else{
            $o  = new OutputPerubahan;
            $get_id = OutputPerubahan::max('OUTPUT_ID');
        }
        $o->OUTPUT_ID           = $get_id+1;
        $o->BL_ID               = Input::get('id');
        $o->OUTPUT_TOLAK_UKUR   = Input::get('tolakukur');
        $o->OUTPUT_TARGET       = Input::get('target');
        $o->OUTPUT_TAHUN        = $tahun;
        $o->SATUAN_ID           = Input::get('satuan');
        $o->save();            
        return 'Berhasil!';
    }

    public function hapusOutput($tahun,$status){
        if($status=="murni"){
            Output::where('OUTPUT_ID',Input::get('id'))->delete();
        }else{
            OutputPerubahan::where('OUTPUT_ID',Input::get('id'))->delete();
        }
        return 'Berhasil!';
    }

    public function detailOutput($tahun,$status,$id){
        $data   = Output::where('OUTPUT_ID',$id)->first();
        return Response::JSON($data);
    }

    public function editCapaian($tahun,$status){
        if($status=="murni"){
            Output::where('OUTPUT_ID',Input::get('idindikator'))->update([
                'OUTPUT_TOLAK_UKUR'    => Input::get('tolakukur'),
                'OUTPUT_TARGET'        => Input::get('target'),
                'OUTPUT_TAHUN'        => $tahun,
                'SATUAN_ID'            => Input::get('satuan')
                ]);
        }else{
            OutputPerubahan::where('OUTPUT_ID',Input::get('idindikator'))->update([
                'OUTPUT_TOLAK_UKUR'    => Input::get('tolakukur'),
                'OUTPUT_TARGET'        => Input::get('target'),
                'OUTPUT_TAHUN'        => $tahun,
                'SATUAN_ID'            => Input::get('satuan')
                ]);
        }
        return 'Berhasil!';
    }
}
