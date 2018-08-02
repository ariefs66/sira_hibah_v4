<?php

namespace App\Http\Controllers\Budgeting\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\TTD;
use App\Model\SKPD;
use App\Model\TahunAnggaran;
use App\Model\UserBudget;
use View;
use Carbon;
use Excel;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\Input;
class ttdController extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
    }    

	public function index($tahun,$status){
		$urusan 	= '';
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
		$rekening 	= '';
        $satuan     = '';
    	return View('budgeting.referensi.ttd',['tahun'=>$tahun,'status'=>$status,'rekening'=>$rekening,'urusan'=>$urusan,'skpd'=>$skpd,'satuan'=>$satuan]);
    }

    public function getData($tahun, $status, Request $req){
        $tahapan        = TahunAnggaran::where('TAHUN',$tahun)->where('STATUS',$status)->first();
        $data 			= TTD::where('TAHUN_ANGGARAN_ID',$tahapan->ID)->get();
        $aksi           = '';
    	$view 			= array();
    	foreach ($data as $data) {
            if(Auth::user()->level == 8){
                $aksi       = '<div class="action visible pull-right"><a title="Ubah" onclick="return ubahTTD(\''.$data->TTD_ID.'\')" class="action-edit"><i class="fa fa-bookmark-o"></i></a><a title="Hapus Program" onclick="return hapusTTD(\''.$data->TTD_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            }else{
                 $aksi = '';
            }
            $tgl        = Carbon\Carbon::createFromFormat('Y-m-d', $data->VALUE)->format('d');
            $gbln       = Carbon\Carbon::createFromFormat('Y-m-d', $data->VALUE)->format('m');
            $bln        = $this->bulan($gbln*1);
            $thn        = Carbon\Carbon::createFromFormat('Y-m-d', $data->VALUE)->format('Y');
            $tgl_ttd    = $tgl . ' ' . $bln . ' ' . $thn;
    		array_push($view, array( 'id_ttd'		=>$data->TTD_ID,
                                     'OPSI'				=>$aksi,
                                     'DATA'				=>'Tanggal: '. $tgl_ttd.'<br/>Jabatan: ' . $data->JABATAN.'<br/>Nama: '.$data->NAMA_PEJABAT,
                                     'TAHUN'			=>$tahapan->TAHUN . '-' . $tahapan->STATUS,
                                     'KEY'				=>$data->KEY,
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
           }elseif(Auth::user()->level == 9 || Auth::user()->level == 1 || Auth::user()->level == 2){
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
            $status = $dc->STATUS;
            switch($status){
                case 0: $status = "Diajukan"; break;
                case 1: $status = "Disetujui"; break;
                case 2: $status = "Ditolak"; break;
                default: $status = "-";
            }
            $catatan = (strlen($dc->CATATAN) > 0 ? $dc->CATATAN : "-");
            $aksi       = '<div class="action visible pull-right"><a onclick="return editOutput(\''.$dc->OUTPUT_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapusOutput(\''.$dc->OUTPUT_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            array_push($view, array( 'INDIKATOR'        =>'Keluaran',
                                     'TOLAK_UKUR'       =>$dc->OUTPUT_TOLAK_UKUR,
                                     'TARGET'           =>$dc->OUTPUT_TARGET.' '.$dc->satuan->SATUAN_NAMA,
                                     'STATUS'           =>$status,
                                     'OUTPUT_STATUS'    =>$dc->OUTPUT_STATUS,
                                     'LOKASI'           =>$dc->OUTPUT_LOKASI,
                                     'CATATAN'          =>$catatan,
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
        
        if(Auth::user()->level == 8 or Auth::user()->level == 9){
            OutputMaster::where('OUTPUT_ID',Input::get('id'))->update([
                'OUTPUT_TOLAK_UKUR'    => Input::get('tolakukur'),
                'OUTPUT_TARGET'        => Input::get('target'),
                'SATUAN_ID'            => Input::get('satuan'),
                'STATUS'            => Input::get('status'),
                'OUTPUT_STATUS'            => Input::get('output_status'),
                'OUTPUT_LOKASI'            => Input::get('lokasi'),
                'CATATAN'            => Input::get('catatan')
                ]);
        } else {
            OutputMaster::where('OUTPUT_ID',Input::get('id'))->update([
                'OUTPUT_TOLAK_UKUR'    => Input::get('tolakukur'),
                'OUTPUT_TARGET'        => Input::get('target'),
                'OUTPUT_STATUS'        => Input::get('output_status'),
                'OUTPUT_LOKASI'        => Input::get('lokasi'),
                'SATUAN_ID'            => Input::get('satuan')
                ]);
        }
        return 'Berhasil!';
    }

    public function submitOutput($tahun,$status){
        $o  = new OutputMaster;
        $o->OUTPUT_ID           = OutputMaster::max('OUTPUT_ID')+1;
        $o->KEGIATAN_ID         = Input::get('idkegiatan');
        $o->OUTPUT_TOLAK_UKUR   = Input::get('tolakukur');
        $o->OUTPUT_TARGET       = Input::get('target');
        $o->OUTPUT_STATUS       = Input::get('output_status');
        $o->OUTPUT_LOKASI       = Input::get('lokasi');
        $o->SATUAN_ID           = Input::get('satuan');
        $o->save();            
        return 'Berhasil!';
    }

    public function hapusOutput($tahun,$status){
        OutputMaster::where('OUTPUT_ID',Input::get('id'))->delete();
        return 'Berhasil!';
    }

    public function getPrioritas($tahun,$status,$id){
        $urusan = Program::where('PROGRAM_ID',$id)->first();
        $count = Program::where('URUSAN_ID',$urusan->URUSAN_ID)->where('PROGRAM_TAHUN',$tahun)->count();
        $view = "<option value='".$urusan->PROGRAM_PRIORITAS."' selected>".$urusan->PROGRAM_PRIORITAS."</option>";
        $list = Program::where('URUSAN_ID',$urusan->URUSAN_ID)->distinct()->select('PROGRAM_PRIORITAS')->get();
        for($i = 1;$i<=($count);$i++)
        {
            foreach($list as $l){
                if($i==$l->PROGRAM_PRIORITAS){
                    continue 2;
                }
            } 
            $view .= "<option value='".$i."'>".$i."</option>";
        }
        return ['data'=>$view];
    }

    public function submitPrioritas($tahun,$status){
        Program::where('PROGRAM_ID',Input::get('id_program'))
        ->update(['PROGRAM_PRIORITAS'   =>Input::get('prioritas_program')]);        
        return '1';
    }
    
    public function rekapNomenklatur($tahun,$status,$skpd){

        if($status == 'murni'){
            $data   = DB::select('select ur."URUSAN_KODE",ur."URUSAN_NAMA","SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_PRIORITAS","PROGRAM_KODE","PROGRAM_NAMA","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET"||\' \'||( SELECT "SATUAN_NAMA" FROM "REFERENSI"."REF_SATUAN" st WHERE st."SATUAN_ID"=oc."SATUAN_ID") AS OUTCOME_TARGET, 
            CASE WHEN oc."STATUS"=1 THEN \'Disetujui\'
            WHEN oc."STATUS"=2 THEN \'Ditolak\'
            ELSE \'Diajukan\'
            END AS STATUS_OUTCOME, oc."CATATAN", "KEGIATAN_KODE", "KEGIATAN_NAMA","OUTPUT_STATUS", "OUTPUT_TOLAK_UKUR", "OUTPUT_TARGET"||\' \'||( SELECT "SATUAN_NAMA" FROM "REFERENSI"."REF_SATUAN" st WHERE st."SATUAN_ID"=op."SATUAN_ID") AS OUTPUT_TARGET,"OUTPUT_LOKASI",
            CASE WHEN op."STATUS"=1 THEN \'Disetujui\'
            WHEN op."STATUS"=2 THEN \'Ditolak\'
            WHEN op."STATUS"=0 THEN \'Diajukan\'
            ELSE \'\'
            END AS STATUS_OUTPUT
            from "BUDGETING"."DAT_BL" bl
            inner JOIN "BUDGETING"."DAT_RINCIAN" rincian
            on bl."BL_ID" = rincian."BL_ID"
            inner JOIN "REFERENSI"."REF_KEGIATAN" keg
            on bl."KEGIATAN_ID" = keg."KEGIATAN_ID"
            inner join "REFERENSI"."REF_PROGRAM" prog
            on keg."PROGRAM_ID" = prog."PROGRAM_ID"
            inner join "REFERENSI"."REF_URUSAN" ur 
            on prog."URUSAN_ID" = ur."URUSAN_ID"
            inner join "REFERENSI"."REF_SUB_UNIT" sub
            on bl."SUB_ID" = sub."SUB_ID"
            inner join "REFERENSI"."REF_SKPD" skpd
            on sub."SKPD_ID" = skpd."SKPD_ID"
            left join "REFERENSI"."REF_OUTCOME" oc
            on oc."PROGRAM_ID" = prog."PROGRAM_ID"
            left join "REFERENSI"."REF_OUTPUT" op
            on op."KEGIATAN_ID" = keg."KEGIATAN_ID"
            WHERE "BL_TAHUN" = '.$tahun.' and "BL_DELETED" = 0
            GROUP BY SKPD,ur."URUSAN_KODE",ur."URUSAN_NAMA",prog."PROGRAM_ID", "OUTCOME_ID", keg."KEGIATAN_ID", "OUTPUT_ID"
            ORDER BY SKPD, "PROGRAM_KODE", "KEGIATAN_KODE"');
        }else {
            $data   = DB::select('select ur."URUSAN_KODE",ur."URUSAN_NAMA","SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_PRIORITAS","PROGRAM_KODE", "PROGRAM_NAMA","OUTPUT_STATUS","OUTCOME_TOLAK_UKUR","OUTCOME_TARGET"||\' \'||( SELECT "SATUAN_NAMA" FROM "REFERENSI"."REF_SATUAN" st WHERE st."SATUAN_ID"=oc."SATUAN_ID") AS OUTCOME_TARGET,CASE WHEN oc."STATUS"=1 THEN \'Disetujui\'
            WHEN oc."STATUS"=2 THEN \'Ditolak\'
            ELSE \'Diajukan\'
            END AS STATUS_OUTCOME,"KEGIATAN_KODE", "KEGIATAN_NAMA", "OUTPUT_TOLAK_UKUR", "OUTPUT_TARGET"||\' \'||( SELECT "SATUAN_NAMA" FROM "REFERENSI"."REF_SATUAN" st WHERE st."SATUAN_ID"=op."SATUAN_ID") AS OUTPUT_TARGET,"OUTPUT_LOKASI", CASE WHEN op."STATUS"=1 THEN \'Disetujui\'
            WHEN op."STATUS"=2 THEN \'Ditolak\'
            WHEN op."STATUS"=0 THEN \'Diajukan\'
            ELSE \'\'
            END AS STATUS_OUTPUT
            from "BUDGETING"."DAT_BL_PERUBAHAN" bl
            inner JOIN "BUDGETING"."DAT_RINCIAN_PERUBAHAN" rincian
            on bl."BL_ID" = rincian."BL_ID"
            inner JOIN "REFERENSI"."REF_KEGIATAN" keg
            on bl."KEGIATAN_ID" = keg."KEGIATAN_ID"
            inner join "REFERENSI"."REF_PROGRAM" prog
            on keg."PROGRAM_ID" = prog."PROGRAM_ID"
            inner join "REFERENSI"."REF_URUSAN" ur 
            on prog."URUSAN_ID" = ur."URUSAN_ID"
            inner join "REFERENSI"."REF_SUB_UNIT" sub
            on bl."SUB_ID" = sub."SUB_ID"
            inner join "REFERENSI"."REF_SKPD" skpd
            on sub."SKPD_ID" = skpd."SKPD_ID"
            left join "REFERENSI"."REF_OUTCOME" oc
            on oc."PROGRAM_ID" = prog."PROGRAM_ID"
            left join "REFERENSI"."REF_OUTPUT" op
            on op."KEGIATAN_ID" = keg."KEGIATAN_ID"
            WHERE "BL_TAHUN" = '.$tahun.' and "BL_DELETED" = 0
            GROUP BY SKPD,ur."URUSAN_KODE",ur."URUSAN_NAMA",,prog."PROGRAM_ID", "OUTCOME_ID", keg."KEGIATAN_ID", "OUTPUT_ID"
            ORDER BY SKPD, "PROGRAM_KODE", "KEGIATAN_KODE"');
        }
            $data = array_map(function ($value) {
                    return (array)$value;
                }, $data);
            Excel::create('PROGRAM '.Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
                    $excel->sheet('PROGRAM', function($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
            })->download('xls');
        
    }


    public function bulan($i){
        $bulan  = [
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];
        return $bulan[$i];
    }
}





