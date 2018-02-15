<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use View;
use Response;
use Auth;
use Carbon;
use DB;
use Excel;
use App\Model\SKPD;
use App\Model\Urusan;
use App\Model\Program;
use App\Model\Kegiatan;
use App\Model\JenisGiat;
use App\Model\Kelurahan;
use App\Model\SumberDana;
use App\Model\Subunit;
use App\Model\Pagu;
use App\Model\Sasaran;
use App\Model\Tag;
use App\Model\Lokasi;
use App\Model\Satuan;
use App\Model\BL;
use App\Model\BLPerubahan;
use App\Model\Kamus;
use App\Model\Usulan;
use App\Model\Indikator;
use App\Model\Kunci;
use App\Model\Pekerjaan;
use App\Model\Rekening;
use App\Model\Komponen;
use App\Model\Rekom;
use App\Model\Rincian;
use App\Model\User;
use App\Model\Staff;
use App\Model\UserBudget;
use App\Model\Tahapan;
use App\Model\Log;
use App\Model\Subrincian;
use App\Model\RekapRincian;
use App\Model\Progunit;
use App\Model\Output;
use App\Model\Outcome;
use App\Model\Kegunit;
use App\Model\Impact;
class downloadController extends Controller
{
    // public function __construct(){
    //     $this->middleware('auth');
    // }

    public function index($tahun,$status){
    	$data	=['tahun'	=> $tahun,
    			  'status' 	=> $status];
    	return View('budgeting.download',$data);
    }

    public function rekapAll($tahun,$status){
    	$data 	= DB::select('select "SKPD_KODE", "SKPD_NAMA", "SUB_NAMA",
							"URUSAN_KODE", "URUSAN_NAMA", "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_KODE", "KEGIATAN_NAMA", 
							"REKENING_KODE", "REKENING_NAMA", "KOMPONEN_NAMA", "RINCIAN_KOEFISIEN", "KOMPONEN_HARGA", "RINCIAN_VOLUME","RINCIAN_PAJAK" ,"RINCIAN_TOTAL"
							from "BUDGETING"."DAT_BL" bl
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
							inner join "BUDGETING"."DAT_RINCIAN" rinci
							on bl."BL_ID" = rinci."BL_ID"
							inner JOIN "EHARGA"."DAT_KOMPONEN" komp
							on rinci."KOMPONEN_ID" = komp."KOMPONEN_ID"
							inner join "REFERENSI"."REF_REKENING" rek
							on rinci."REKENING_ID" = rek."REKENING_ID"
							ORDER BY "SKPD_KODE", "URUSAN_KODE", "PROGRAM_KODE", "KEGIATAN_KODE"');
    	$data = array_map(function ($value) {
		    return (array)$value;
		}, $data);
    	Excel::create('Test', function($excel) use($data){
	        $excel->sheet('rekap', function($sheet) use ($data) {
	            $sheet->fromArray($data);
	        });
	    })->download('xls');
    }

    public function rekapBTL($tahun,$status){
    	$data 	= DB::select('SELECT "SKPD_KODE","SKPD_NAMA","REKENING_KODE","REKENING_NAMA","BTL_TOTAL" 
							FROM "BUDGETING"."DAT_BTL" BTL
							JOIN "REFERENSI"."REF_REKENING" REK
							ON BTL."REKENING_ID" = REK."REKENING_ID"
							JOIN "REFERENSI"."REF_SUB_UNIT" SUB
							ON BTL."SUB_ID" = SUB."SUB_ID"
							JOIN "REFERENSI"."REF_SKPD" SKPD
							ON SUB."SKPD_ID" = SKPD."SKPD_ID"
							WHERE "BTL_TOTAL" != 0
							ORDER BY "SKPD_KODE", "REKENING_KODE"');
    	$data = array_map(function ($value) {
		    return (array)$value;
		}, $data);
    	Excel::create('REKAP BTL '.Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
	        $excel->sheet('REKAP BTL', function($sheet) use ($data) {
	            $sheet->fromArray($data);
	        });
	    })->download('xls');
    }

    public function rekapPendapatan($tahun,$status){
    	$data 	= DB::select('SELECT "SKPD_KODE","SKPD_NAMA","REKENING_KODE","REKENING_NAMA","PENDAPATAN_TOTAL" 
								FROM "BUDGETING"."DAT_PENDAPATAN" PEN
								JOIN "REFERENSI"."REF_REKENING" REK
								ON PEN."REKENING_ID" = REK."REKENING_ID"
								JOIN "REFERENSI"."REF_SUB_UNIT" SUB
								ON PEN."SUB_ID" = SUB."SUB_ID"
								JOIN "REFERENSI"."REF_SKPD" SKPD
								ON SUB."SKPD_ID" = SKPD."SKPD_ID"
								WHERE "PENDAPATAN_TOTAL" != 0
								ORDER BY "SKPD_KODE", "REKENING_KODE"');
    	$data = array_map(function ($value) {
		    return (array)$value;
		}, $data);
    	Excel::create('REKAP PENDAPATAN '.Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
	        $excel->sheet('REKAP PENDAPATAN', function($sheet) use ($data) {
	            $sheet->fromArray($data);
	        });
	    })->download('xls');
    }

    public function rekapReses($tahun,$status){
    	$data 	= DB::select('SELECT "DEWAN_NAMA" AS "NAMA DEWAN",
								"FRAKSI_AKRONIM" AS "NAMA FRAKSI", 
								"KAMUS_NAMA" AS "KAMUS USULAN", 
								"USULAN_VOLUME"||\' \'||"KAMUS_SATUAN" AS "VOLUME USULAN",
								"USULAN_VOLUME"*"KAMUS_HARGA" AS "ANGGARAN",
								"USULAN_ALAMAT" AS "ALAMAT USULAN", 
								"SKPD_NAMA" AS "PERANGKAT DAERAH",
								"KEGIATAN_NAMA" AS "KEGIATAN",
								CASE 
								WHEN "USULAN_STATUS" = 0 THEN \'Verifikasi Perangkat Daerah\'
								WHEN "USULAN_STATUS" = 1 THEN \'Ditolak Perangkat Daerah\'
								WHEN "USULAN_STATUS" = 2 THEN \'Diakomodir Perangkat Daerah\'
								END AS "STATUS",
								"USULAN_ALASAN" AS "ALASAN"								
								FROM "RESES"."DAT_USULAN" USULAN
								JOIN "REFERENSI"."REF_KAMUS" KAMUS
								ON USULAN."KAMUS_ID" = KAMUS."KAMUS_ID"
								JOIN "RESES"."DAT_DEWAN" DEWAN
								ON USULAN."DEWAN_ID" = DEWAN."DEWAN_ID"
								JOIN "RESES"."DAT_FRAKSI" FRAKSI
								ON DEWAN."FRAKSI_ID" = FRAKSI."FRAKSI_ID"
								JOIN "RESES"."DAT_DAPIL" DAPIL
								ON DEWAN."DAPIL_ID" = DAPIL."DAPIL_ID"
								JOIN "REFERENSI"."REF_KEGIATAN" KEG
								ON CAST(KAMUS."KAMUS_KEGIATAN" AS INT) = KEG."KEGIATAN_ID"
								JOIN "REFERENSI"."REF_SKPD" SKPD
								ON KAMUS."KAMUS_SKPD" = SKPD."SKPD_ID"
								ORDER BY DEWAN."FRAKSI_ID", DEWAN."DEWAN_ID"');
    	$data = array_map(function ($value) {
		    return (array)$value;
		}, $data);
    	Excel::create('REKAP RESES '.Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
	        $excel->sheet('REKAP RESES', function($sheet) use ($data) {
	            $sheet->fromArray($data);
	        });
	    })->download('xls');
    }

    public function rekapRincian($tahun,$status,$id){
    	if($status=='murni'){
    		$bl 	= BL::where('BL_ID',$id)->first();
	    	$data 	= DB::select('SELECT "SUBRINCIAN_NAMA" AS "SUBRINCIAN",
									"REKENING_KODE" AS "KODE REKENING", 
									"REKENING_NAMA" AS "NAMA REKENING",
									"KOMPONEN_KODE" AS "KODE KOMPONEN", 
									"KOMPONEN_NAMA" AS "NAMA KOMPONEN",
									"RINCIAN_VOLUME" AS "VOLUME", 
									"RINCIAN_KOEFISIEN" AS "KOEFISIEN",
									"KOMPONEN_HARGA" AS "HARGA SATUAN",
									"RINCIAN_PAJAK"||\' %\' AS "PAJAK",
									"RINCIAN_TOTAL" AS "TOTAL"
									FROM "BUDGETING"."DAT_RINCIAN" RINCIAN
									JOIN "REFERENSI"."REF_REKENING" REK
									ON RINCIAN."REKENING_ID" = REK."REKENING_ID"
									JOIN "EHARGA"."DAT_KOMPONEN" KOMP
									ON RINCIAN."KOMPONEN_ID" = KOMP."KOMPONEN_ID"
									JOIN "BUDGETING"."DAT_SUBRINCIAN" SUB
									ON RINCIAN."SUBRINCIAN_ID" = SUB."SUBRINCIAN_ID"
									WHERE RINCIAN."BL_ID" = '.$id.'
									ORDER BY RINCIAN."SUBRINCIAN_ID","REKENING_KODE","KOMPONEN_NAMA"');
	    	$data = array_map(function ($value) {
			    return (array)$value;
			}, $data);
	    	Excel::create('REKAP BELANJA '.$bl->kegiatan->KEGIATAN_NAMA." ".Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
		        $excel->sheet('REKAP BELANJA', function($sheet) use ($data) {
		            $sheet->fromArray($data);
		        });
		    })->download('xls');
    	}else{
    		$bl 	= BLPerubahan::where('BL_ID',$id)->first();
	    	$data 	= DB::select('SELECT "SUBRINCIAN_NAMA" AS "SUBRINCIAN",
									"REKENING_KODE" AS "KODE REKENING", 
									"REKENING_NAMA" AS "NAMA REKENING",
									"KOMPONEN_KODE" AS "KODE KOMPONEN", 
									"KOMPONEN_NAMA" AS "NAMA KOMPONEN",
									"RINCIAN_VOLUME" AS "VOLUME", 
									"RINCIAN_KOEFISIEN" AS "KOEFISIEN",
									"KOMPONEN_HARGA" AS "HARGA SATUAN",
									"RINCIAN_PAJAK"||\' %\' AS "PAJAK",
									"RINCIAN_TOTAL" AS "TOTAL"
									FROM "BUDGETING"."DAT_RINCIAN_PERUBAHAN" RINCIAN
									JOIN "REFERENSI"."REF_REKENING" REK
									ON RINCIAN."REKENING_ID" = REK."REKENING_ID"
									JOIN "EHARGA"."DAT_KOMPONEN" KOMP
									ON RINCIAN."KOMPONEN_ID" = KOMP."KOMPONEN_ID"
									JOIN "BUDGETING"."DAT_SUBRINCIAN_PERUBAHAN" SUB
									ON RINCIAN."SUBRINCIAN_ID" = SUB."SUBRINCIAN_ID"
									WHERE RINCIAN."BL_ID" = '.$id.'
									ORDER BY RINCIAN."SUBRINCIAN_ID","REKENING_KODE","KOMPONEN_NAMA"');
	    	$data = array_map(function ($value) {
			    return (array)$value;
			}, $data);
	    	Excel::create('REKAP BELANJA '.$bl->kegiatan->KEGIATAN_NAMA." ".Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
		        $excel->sheet('REKAP BELANJA', function($sheet) use ($data) {
		            $sheet->fromArray($data);
		        });
		    })->download('xls');
    	}
    	
    }

    public function rekapMusrenbangSKPD($tahun,$status,$id){
    	$data 	= DB::select('SELECT "KEC_NAMA" AS "KECAMATAN",
								"KEL_NAMA"  AS "KEL_NAMA",
								"RW_NAMA" AS "RW",
								"KAMUS_NAMA" AS "KAMUS",
								"USULAN_VOLUME" AS "VOLUME",
								"KAMUS_SATUAN" AS "SATUAN"
								FROM "MUSRENBANG"."DAT_USULAN" USULAN
								JOIN "REFERENSI"."REF_KAMUS" KAMUS
								ON USULAN."KAMUS_ID" = KAMUS."KAMUS_ID"
								JOIN "REFERENSI"."REF_RW" RW
								ON USULAN."RW_ID" = RW."RW_ID"
								JOIN "REFERENSI"."REF_KELURAHAN" KEL
								ON RW."KEL_ID" = KEL."KEL_ID"
								JOIN "REFERENSI"."REF_KECAMATAN" KEC
								ON KEL."KEC_ID" = KEC."KEC_ID"
								WHERE "USULAN_STATUS" = 1
								AND "KAMUS_SKPD" = '.$id);
    	$data = array_map(function ($value) {
		    return (array)$value;
		}, $data);
    	Excel::create('REKAP MUSRENBANG '.Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
	        $excel->sheet('REKAP MUSRENBANG', function($sheet) use ($data) {
	            $sheet->fromArray($data);
	        });
	    })->download('xls');
    }

    public function rekapResesSKPD($tahun,$status,$id){
    	$data 	= DB::select('SELECT "DEWAN_NAMA" AS "NAMA DEWAN",
								"FRAKSI_AKRONIM" AS "NAMA FRAKSI", 
								"KAMUS_NAMA" AS "KAMUS USULAN", 
								"USULAN_VOLUME" AS "VOLUME",
								"KAMUS_SATUAN" AS "SATUAN",
								"USULAN_ALAMAT" AS "ALAMAT USULAN",
								"KEGIATAN_NAMA" AS "KEGIATAN"
								FROM "RESES"."DAT_USULAN" USULAN
								JOIN "REFERENSI"."REF_KAMUS" KAMUS
								ON USULAN."KAMUS_ID" = KAMUS."KAMUS_ID"
								JOIN "RESES"."DAT_DEWAN" DEWAN
								ON USULAN."DEWAN_ID" = DEWAN."DEWAN_ID"
								JOIN "RESES"."DAT_FRAKSI" FRAKSI
								ON DEWAN."FRAKSI_ID" = FRAKSI."FRAKSI_ID"
								JOIN "RESES"."DAT_DAPIL" DAPIL
								ON DEWAN."DAPIL_ID" = DAPIL."DAPIL_ID"
								JOIN "REFERENSI"."REF_KEGIATAN" KEG
								ON CAST(KAMUS."KAMUS_KEGIATAN" AS INT) = KEG."KEGIATAN_ID"
								JOIN "REFERENSI"."REF_SKPD" SKPD
								ON KAMUS."KAMUS_SKPD" = SKPD."SKPD_ID"
								WHERE "USULAN_STATUS" = 2
								AND "KAMUS_SKPD" = '.$id.'
								ORDER BY DEWAN."FRAKSI_ID", DEWAN."DEWAN_ID"');
    	$data = array_map(function ($value) {
		    return (array)$value;
		}, $data);
    	Excel::create('REKAP RESES '.Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
	        $excel->sheet('REKAP RESES', function($sheet) use ($data) {
	            $sheet->fromArray($data);
	        });
	    })->download('xls');
    }
}
