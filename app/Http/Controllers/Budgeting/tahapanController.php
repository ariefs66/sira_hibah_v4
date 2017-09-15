<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use View;
use Response;
use Auth;
use DB;
use App\Model\SKPD;
use App\Model\Program;
use App\Model\Kegiatan;
use App\Model\JenisGiat;
use App\Model\SumberDana;
use App\Model\Pagu;
use App\Model\Sasaran;
use App\Model\Tag;
use App\Model\Lokasi;
use App\Model\Satuan;
use App\Model\BL;
use App\Model\Subrincian;
use App\Model\Indikator;
use App\Model\Kunci;
use App\Model\Pekerjaan;
use App\Model\Rekening;
use App\Model\Komponen;
use App\Model\Rekom;
use App\Model\Rincian;
use App\Model\User;
use App\Model\Staff;
use App\Model\Tahapan;
use App\Model\Subtahapan;
use App\Model\RekapRincian;
use App\Model\RekapBL;
use App\Model\Output;
use App\Model\UserBudget;
class tahapanController extends Controller
{
	public function __construct(){
        $this->middleware('auth');
    }

    public function index($tahun,$status){
        if($status == 'murni') return $this->indexmurni($tahun,$status);
        else return $this->indexperubahan($tahun,$status);
    }

    public function indexmurni($tahun,$status){
        $tutup        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->orderBy('TAHAPAN_ID','desc')->value('TAHAPAN_SELESAI');
        if($tutup != '0') $tutup = 1;
        $tahapan     = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->orderBy('TAHAPAN_ID','desc')->value('TAHAPAN_NAMA');
        if(empty($tahapan)){
            $tahapan    = 'RKPD';
        }elseif ($tahapan == 'RKPD') {
            $tahapan    = 'RKUA/PPAS';
        }elseif ($tahapan == 'RKUA/PPAS') {
            $tahapan    = 'KUA/PPAS';
        }elseif ($tahapan == 'KUA/PPAS') {
            $tahapan    = 'RAPBD';
        }elseif ($tahapan == 'RAPBD') {
            $tahapan    = 'APBD';
        }
        return View('budgeting.referensi.tahapan',['tahun'=>$tahun,'status'=>$status,'tutup'=>$tutup,'tahapan'=>$tahapan]);        
    }
    
    public function indexperubahan($tahun,$status){
        $tutup        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->orderBy('TAHAPAN_ID','desc')->value('TAHAPAN_SELESAI');
        if($tutup != '0') $tutup = 1;
        $tahapan     = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->orderBy('TAHAPAN_ID','desc')->value('TAHAPAN_NAMA');
        if(empty($tahapan)){
            $tahapan    = 'RKPD';
        }elseif ($tahapan == 'RKPD') {
            $tahapan    = 'RKUA/PPAS';
        }elseif ($tahapan == 'RKUA/PPAS') {
            $tahapan    = 'KUA/PPAS';
        }elseif ($tahapan == 'KUA/PPAS') {
            $tahapan    = 'RAPBD';
        }elseif ($tahapan == 'RAPBD') {
            $tahapan    = 'APBD';
        }
        return View('budgeting.referensi.tahapan',['tahun'=>$tahun,'status'=>$status,'tutup'=>$tutup,'tahapan'=>$tahapan]);        
    }

    public function getData($tahun,$status){
        if($status == 'murni')
        $data           = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->orderBy('TAHAPAN_ID')->get();
    	else
        $data 			= Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->orderBy('TAHAPAN_ID')->get();
    	$no 			= 1;
    	$aksi 			= '';
    	$view 			= array();
    	foreach ($data as $data) {
    		if($data->TAHAPAN_KUNCI_RINCIAN == 0){
                $kunci     = '<label class="i-switch bg-danger m-t-xs m-r buka-giat"><input type="checkbox" onchange="return kuncigiat(\''.$data->TAHAPAN_ID.'\')" id="kuncigiat-'.$data->TAHAPAN_ID.'"><i></i></label>';
            }else{
                $kunci      = '<label class="i-switch bg-danger m-t-xs m-r kunci-giat"><input type="checkbox" onchange="return kuncigiat(\''.$data->TAHAPAN_ID.'\')" id="kuncigiat-'.$data->TAHAPAN_ID.'" checked><i></i></label>';
            }
    		array_push($view, array( 'no'				=>$no,
                                     'TAHAPAN_NAMA'  	=>$data->TAHAPAN_NAMA.' - '.$data->TAHAPAN_STATUS,
                                     'TAHAPAN_ID'       =>$data->TAHAPAN_ID,
                                     'STATUS'		    =>$data->TAHAPAN_SELESAI,
                                     'TAHAPAN_AWAL'		=>'<span class="text-orange">'.date('H:i',strtotime($data->TAHAPAN_AWAL)).'</span><br>'.date('d/M/Y',strtotime($data->TAHAPAN_AWAL)),
                                     'TAHAPAN_KUNCI'    =>$kunci,
                                     'TAHAPAN_AKHIR'	=>'<span class="text-orange">'.date('H:i',strtotime($data->TAHAPAN_AKHIR)).'</span><br>'.date('d/M/Y',strtotime($data->TAHAPAN_AKHIR))));
    		$no++;
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
    }

    public function getDetail($tahun,$status,$id){
    	$data 			= Tahapan::where('TAHAPAN_ID',$id)->get();
    	return $data;
    }

    public function submitAdd(){
    	$tahapan = new Tahapan;
	    $tahapan->TAHAPAN_TAHUN		  = Input::get('tahun');
        $tahapan->TAHAPAN_STATUS      = Input::get('status');
        $tahapan->TAHAPAN_NAMA        = Input::get('tahapan');
        $tahapan->TAHAPAN_AWAL        = Input::get('awal');
        $tahapan->TAHAPAN_KUNCI_GIAT  = Input::get('giat');
        $tahapan->TAHAPAN_KUNCI_OPD   = Input::get('opd');
	    $tahapan->TAHAPAN_SELESAI	  = 0;
	    $tahapan->save();

        return 1;
    }

    public function submitEdit(){
    	Tahapan::where('TAHAPAN_ID',Input::get('id_tahapan'))
    			->update(['TAHAPAN_KUNCI_GIAT' => Input::get('giat'),
                          'TAHAPAN_KUNCI_OPD' => Input::get('opd')]);
        return 1;
    }

    public function delete(){
    	Urusan::where('URUSAN_ID',Input::get('id_urusan'))->delete();
    	return 'Berhasil dihapus!';
    }

    public function tutupTahapan($tahun,$status){
        if($status == 'murni') $this->tutupTahapanMurni($tahun,$status);
        else $this->tutupTahapanPerubahan($tahun,$status);
    }

    public function tutupTahapanMurni($tahun,$status){
        $tahapan            = Input::get('TAHAPAN_ID');
        $getDetail          = BL::where('BL_TAHUN',$tahun)
                                ->where('BL_DELETED',0)
                                ->where('BL_VALIDASI',1)
                                ->selectRaw('* , \''.$tahapan.'\' AS "TAHAPAN_ID"');
        $getRincian         = Rincian::whereHas('bl',function($q) use($tahun){
                                    $q->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0);
                                })->selectRaw('* , \''.$tahapan.'\' AS "TAHAPAN_ID"');
        $getSubRincian      = SubRincian::whereHas('bl',function($q) use($tahun){
                                    $q->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0);
                                })->selectRaw('* , \''.$tahapan.'\' AS "TAHAPAN_ID"');
        $getOutput          = Output::whereHas('bl',function($q) use($tahun){
                                    $q->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0);
                                })->selectRaw('* , \''.$tahapan.'\' AS "TAHAPAN_ID"');
        $b_detail = $getDetail->getBindings();
        $i_detail = 'INSERT INTO "BUDGETING"."RKP_BL" '. $getDetail->toSql();
        DB::insert($i_detail, $b_detail);
        $b_rincian = $getRincian->getBindings();
        $i_rincian = 'INSERT INTO "BUDGETING"."RKP_RINCIAN" '. $getRincian->toSql();
        DB::insert($i_rincian, $b_rincian);
        $b_subrincian = $getSubRincian->getBindings();
        $i_subrincian = 'INSERT INTO "BUDGETING"."RKP_SUBRINCIAN" '. $getSubRincian->toSql();
        DB::insert($i_subrincian, $b_subrincian);
        $b_output = $getOutput->getBindings();
        $i_output = 'INSERT INTO "BUDGETING"."RKP_OUTPUT" '. $getOutput->toSql();
        DB::insert($i_output, $b_output);
        Tahapan::where('TAHAPAN_ID',Input::get('TAHAPAN_ID'))->update(['TAHAPAN_SELESAI'=>1]);
        return 'Berhasil!';
    }

    public function tutupTahapanPerubahan($tahun,$status){
        $blvalidasinol      = BL::where('BL_VALIDASI',0)->where('BL_TAHUN',$tahun)->get();
        foreach($blvalidasinol as $bnol){
            $rincianBL      = Rincian::where('BL_ID',$bnol->BL_ID)->sum('RINCIAN_TOTAL');
            BL::where('BL_ID',$bnol->BL_ID)->update(['BL_VALIDASI'=>1, 'BL_PAGU'=>$rincianBL]);
        }

        $getRincian         = Rincian::whereHas('bl',function($q) use($tahun){
                                    $q->where('BL_TAHUN',$tahun);
                                })->get();
        $getDetail          = BL::where('BL_TAHUN',$tahun)->selectRaw('* , \''.Input::get('TAHAPAN_ID').'\' AS "TAHAPAN_ID"')->get()->toArray();
        foreach ($getDetail as $gd) {
            $detail         = new RekapBL;
            $detail->insert($gd);
        }
        foreach($getRincian as $gr){
            $rekap          = new RekapRincian;
            $rekap->RINCIAN_ID          = $gr->RINCIAN_ID;
            $rekap->BL_ID               = $gr->BL_ID;
            $rekap->REKENING_ID         = $gr->REKENING_ID;
            $rekap->KOMPONEN_ID         = $gr->KOMPONEN_ID;
            $rekap->RINCIAN_PAJAK       = $gr->RINCIAN_PAJAK;
            $rekap->RINCIAN_VOLUME      = $gr->RINCIAN_VOLUME;
            $rekap->RINCIAN_KOEFISIEN   = $gr->RINCIAN_KOEFISIEN;
            $rekap->RINCIAN_TOTAL       = $gr->RINCIAN_TOTAL;
            $rekap->RINCIAN_SUBTITLE    = $gr->RINCIAN_SUBTITLE;
            $rekap->RINCIAN_KETERANGAN  = $gr->RINCIAN_KETERANGAN;
            $rekap->PEKERJAAN_ID        = $gr->PEKERJAAN_ID;
            $rekap->TAHAPAN_ID          = Input::get('TAHAPAN_ID');
            $rekap->save();
        }

        Tahapan::where('TAHAPAN_ID',Input::get('TAHAPAN_ID'))->update(['TAHAPAN_SELESAI'=>1]);
        return 'Berhasil!';
    }

    public function getSubTahapan($tahun,$status,$tahapan){
        $data       = Subtahapan::where('TAHAPAN_ID',$tahapan)->get();
        $view       = array();
        foreach($data as $data){
            array_push($view, array('NAMA'  => $data->TAHAPAN_NAMA,'TGL'=> $data->TAHAPAN_AKHIR));
        }
        $out        = array('aaData'=>$view);
        return Response::JSON($out);
    }

    public function submitSubTahapan($tahun,$status){
        $sub    = new Subtahapan;
        $sub->TAHAPAN_ID        = Input::get('TAHAPAN_ID');
        $sub->TAHAPAN_NAMA      = Input::get('TAHAPAN_NAMA');
        $sub->TAHAPAN_AKHIR     = Input::get('TAHAPAN_AKHIR');
        $sub->save();

        Tahapan::where('TAHAPAN_ID',Input::get('TAHAPAN_ID'))->update(['TAHAPAN_AKHIR'=>Input::get('TAHAPAN_AKHIR')]);
    }
}
