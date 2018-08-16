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
        $kode = ($req->kode == "")? "" : $req->kode;
        $start = ($req->iDisplayStart == "")? 0 : $req->iDisplayStart;
        $length = ($req->iDisplayLength == "")? 0 : $req->iDisplayLength;
        $tabel = '';
        if($status=="murni"){
            $data = BL::where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->where('BL_VALIDASI',1);
        }else{
            $tabel = '_PERUBAHAN';
            $data = BLPerubahan::where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->where('BL_VALIDASI',1);
        }
        $view           = array();
        if(strlen($kode)>0){
            $data = $data->JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL'.$tabel.'.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
            ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
            ->JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL'.$tabel.'.KEGIATAN_ID')
            ->JOIN('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
            ->JOIN('BUDGETING.DAT_RINCIAN'.$tabel,'DAT_RINCIAN'.$tabel.'.BL_ID','=','DAT_BL'.$tabel.'.BL_ID')
            ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN'.$tabel.'.REKENING_ID')
            ->JOIN('REFERENSI.REF_URUSAN','REF_PROGRAM.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
            ->JOIN('REFERENSI.REF_URUSAN_KATEGORI1','REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID','=','REF_URUSAN.URUSAN_KAT1_ID')
            ->whereRaw("\"SKPD_KODE\"||'.'||\"PROGRAM_KODE\"||'.'||\"KEGIATAN_KODE\" LIKE ?", [$kode])
            ->groupBy("REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID","REF_URUSAN_KATEGORI1.URUSAN_KAT1_KODE","REF_URUSAN_KATEGORI1.URUSAN_KAT1_NAMA","REF_URUSAN.URUSAN_ID","REF_URUSAN.URUSAN_KODE","REF_URUSAN.URUSAN_NAMA","REF_SUB_UNIT.SUB_ID","REF_SUB_UNIT.SUB_NAMA","REF_SKPD.SKPD_ID","REF_SKPD.SKPD_KODE","REF_SKPD.SKPD_NAMA","REF_PROGRAM.PROGRAM_ID","REF_PROGRAM.PROGRAM_NAMA","REF_KEGIATAN.KEGIATAN_ID","REF_KEGIATAN.KEGIATAN_KODE","REF_KEGIATAN.KEGIATAN_NAMA","DAT_RINCIAN$tabel.RINCIAN_ID","REF_REKENING.REKENING_ID","REF_REKENING.REKENING_KODE","REF_REKENING.REKENING_NAMA")
            ->orderBy('URUSAN_KAT1_KODE','KEGIATAN_KODE')
            ->selectRaw('"REF_URUSAN_KATEGORI1"."URUSAN_KAT1_ID","URUSAN_KAT1_KODE","URUSAN_KAT1_NAMA","REF_URUSAN"."URUSAN_ID","URUSAN_KODE","URUSAN_NAMA","REF_SUB_UNIT"."SUB_ID","SUB_NAMA","REF_SKPD"."SKPD_ID","SKPD_KODE","SKPD_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","PROGRAM_NAMA","REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA","REF_REKENING"."REKENING_ID","REKENING_KODE","REKENING_NAMA",SUM("RINCIAN_TOTAL") AS pagu');
        } else {
            $data = $data->JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL'.$tabel.'.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
            ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
            ->JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL'.$tabel.'.KEGIATAN_ID')
            ->JOIN('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
            ->JOIN('BUDGETING.DAT_RINCIAN'.$tabel,'DAT_RINCIAN'.$tabel.'.BL_ID','=','DAT_BL'.$tabel.'.BL_ID')
            ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN'.$tabel.'.REKENING_ID')
            ->JOIN('REFERENSI.REF_URUSAN','REF_PROGRAM.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
            ->JOIN('REFERENSI.REF_URUSAN_KATEGORI1','REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID','=','REF_URUSAN.URUSAN_KAT1_ID')
            ->groupBy("REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID","REF_URUSAN_KATEGORI1.URUSAN_KAT1_KODE","REF_URUSAN_KATEGORI1.URUSAN_KAT1_NAMA","REF_URUSAN.URUSAN_ID","REF_URUSAN.URUSAN_KODE","REF_URUSAN.URUSAN_NAMA","REF_SUB_UNIT.SUB_ID","REF_SUB_UNIT.SUB_KODE","REF_SUB_UNIT.SUB_NAMA","REF_SKPD.SKPD_ID","REF_SKPD.SKPD_KODE","REF_SKPD.SKPD_NAMA","REF_PROGRAM.PROGRAM_ID","REF_PROGRAM.PROGRAM_NAMA","REF_KEGIATAN.KEGIATAN_ID","REF_KEGIATAN.KEGIATAN_KODE","REF_KEGIATAN.KEGIATAN_NAMA","DAT_RINCIAN$tabel.RINCIAN_ID","REF_REKENING.REKENING_ID","REF_REKENING.REKENING_KODE","REF_REKENING.REKENING_NAMA")
            ->orderBy('URUSAN_KAT1_KODE','KEGIATAN_KODE')
            ->selectRaw('"REF_URUSAN_KATEGORI1"."URUSAN_KAT1_ID","URUSAN_KAT1_KODE","URUSAN_KAT1_NAMA","REF_URUSAN"."URUSAN_ID","URUSAN_KODE","URUSAN_NAMA","REF_SUB_UNIT"."SUB_ID","SUB_KODE","SUB_NAMA","REF_SKPD"."SKPD_ID","SKPD_KODE","SKPD_NAMA","REF_PROGRAM"."PROGRAM_ID","PROGRAM_KODE","PROGRAM_NAMA","REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA","REF_REKENING"."REKENING_ID","REKENING_KODE","REKENING_NAMA",SUM("RINCIAN_TOTAL") AS pagu');
        }

        if($length>0){
            $data = $data->limit($length)->offset($start)->get();
        } else {
            $data = $data->get();
        }

        foreach ($data as $data) {
            array_push($view, array( 
                'KODE_URUSAN'         => $data->URUSAN_KAT1_KODE,
                'NAMA_URUSAN'         => $data->URUSAN_KAT1_NAMA,
                'KODE_BIDANG'         => $data->URUSAN_KODE,
                'NAMA_BIDANG'         => $data->URUSAN_NAMA,
                'KODE_SKPD'           => $data->SKPD_KODE,
                'NAMA_SKPD'           => $data->SKPD_NAMA,
                'KODE_SUB_UNIT'       => $data->SUB_KODE,
                'NAMA_SUB_UNIT'       => $data->SUB_NAMA,
                'KODE_PROGRAM'        => $data->PROGRAM_KODE,
                'NAMA_PROGRAM'        => $data->PROGRAM_NAMA,
                'KODE_KEGIATAN'       => $data->KEGIATAN_KODE,
                'NAMA_KEGIATAN'       => $data->KEGIATAN_NAMA,
                'KODE_REKENING'       => $data->REKENING_KODE,
                'JENIS_BELANJA'       => 'Belanja Langsung',
                'NAMA_REKENING'       => $data->REKENING_NAMA,
                'ANGGARAN_KEGIATAN'   => $data->pagu                                 
            ));
        
        }
        return Response::JSON($view);
    }

	public function apiSiraBTL($tahun,$status, Request $req){
        $kode = ($req->kode == "")? "" : $req->kode;
        $start = ($req->iDisplayStart == "")? 0 : $req->iDisplayStart;
        $length = ($req->iDisplayLength == "")? 0 : $req->iDisplayLength;
        $view           = array();
        if(strlen($kode)>0){
            $data = BTL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BTL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
            ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
            ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
            ->JOIN('REFERENSI.REF_URUSAN_SKPD','REF_SKPD.SKPD_ID','=','REF_URUSAN_SKPD.SKPD_ID')
            ->JOIN('REFERENSI.REF_URUSAN','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
            ->JOIN('REFERENSI.REF_URUSAN_KATEGORI1','REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID','=','REF_URUSAN.URUSAN_KAT1_ID')
            ->where('BTL_TAHUN',$tahun)
            ->whereRaw("\"REKENING_KODE\" LIKE ?", [$kode])
            ->groupBy("REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID","REF_URUSAN_KATEGORI1.URUSAN_KAT1_KODE","REF_URUSAN_KATEGORI1.URUSAN_KAT1_NAMA","REF_URUSAN.URUSAN_ID","REF_URUSAN.URUSAN_KODE","REF_URUSAN.URUSAN_NAMA","REF_SKPD.SKPD_ID","REF_SKPD.SKPD_KODE","REF_SKPD.SKPD_NAMA",
                "REF_SUB_UNIT.SUB_ID","REF_SUB_UNIT.SUB_KODE","REF_SUB_UNIT.SUB_NAMA",
                "REF_REKENING.REKENING_ID","REF_REKENING.REKENING_KODE","REF_REKENING.REKENING_NAMA","BTL_NAMA")
            ->orderBy('URUSAN_KAT1_KODE','KEGIATAN_KODE')
            ->selectRaw('"REF_URUSAN_KATEGORI1"."URUSAN_KAT1_ID","URUSAN_KAT1_KODE","URUSAN_KAT1_NAMA","REF_URUSAN"."URUSAN_ID","URUSAN_KODE","URUSAN_NAMA","REF_SKPD"."SKPD_ID","SKPD_KODE","SKPD_NAMA","REF_SUB_UNIT.SUB_ID","SUB_KODE","SUB_NAMA","REF_REKENING"."REKENING_ID","REKENING_KODE","REKENING_NAMA","BTL_NAMA",SUM("BTL_TOTAL") AS pagu');
        } else {
            $data = BTL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BTL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
            ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
            ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
            ->JOIN('REFERENSI.REF_URUSAN_SKPD','REF_SKPD.SKPD_ID','=','REF_URUSAN_SKPD.SKPD_ID')
            ->JOIN('REFERENSI.REF_URUSAN','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
            ->JOIN('REFERENSI.REF_URUSAN_KATEGORI1','REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID','=','REF_URUSAN.URUSAN_KAT1_ID')
            ->where('BTL_TAHUN',$tahun)
            ->groupBy("REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID","REF_URUSAN_KATEGORI1.URUSAN_KAT1_KODE","REF_URUSAN_KATEGORI1.URUSAN_KAT1_NAMA","REF_URUSAN.URUSAN_ID","REF_URUSAN.URUSAN_KODE","REF_URUSAN.URUSAN_NAMA","REF_SKPD.SKPD_ID","REF_SKPD.SKPD_KODE","REF_SKPD.SKPD_NAMA",
                "REF_SUB_UNIT.SUB_ID","REF_SUB_UNIT.SUB_KODE","REF_SUB_UNIT.SUB_NAMA",
                "REF_REKENING.REKENING_ID","REF_REKENING.REKENING_KODE","REF_REKENING.REKENING_NAMA","BTL_NAMA")
            ->orderBy('URUSAN_KAT1_KODE','KEGIATAN_KODE')
            ->selectRaw('"REF_URUSAN_KATEGORI1"."URUSAN_KAT1_ID","URUSAN_KAT1_KODE","URUSAN_KAT1_NAMA","REF_URUSAN"."URUSAN_ID","URUSAN_KODE","URUSAN_NAMA","REF_SKPD"."SKPD_ID","SKPD_KODE","SKPD_NAMA","REF_SUB_UNIT.SUB_ID","SUB_KODE","SUB_NAMA","REF_REKENING"."REKENING_ID","REKENING_KODE","REKENING_NAMA","BTL_NAMA",SUM("BTL_TOTAL") AS pagu');
        }

        if($length>0){
            $data = $data->limit($length)->offset($start)->get();
        } else {
            $data = $data->get();
        }

        foreach ($data as $data) {
            array_push($view, array( 
                'KODE_URUSAN'         => $data->URUSAN_KAT1_KODE,
                'NAMA_URUSAN'         => $data->URUSAN_KAT1_NAMA,
                'KODE_BIDANG'         => $data->URUSAN_KODE,
                'NAMA_BIDANG'         => $data->URUSAN_NAMA,
                'KODE_SKPD'           => $data->SKPD_KODE,
                'NAMA_SKPD'           => $data->SKPD_NAMA,
                'KODE_SUB_UNIT'       => $data->SUB_KODE,
                'NAMA_SUB_UNIT'       => $data->SUB_NAMA,
                'NAMA_URAIAN'         => $data->BTL_NAMA,
                'KODE_REKENING'       => $data->REKENING_KODE,
                'JENIS_BELANJA'       => 'Belanja Tidak Langsung',
                'NAMA_REKENING'       => $data->REKENING_NAMA,
                'ANGGARAN_KEGIATAN'   => $data->pagu                                 
            ));
        
        }
        return Response::JSON($view);
    }

    public function apiSiraPembiayaan($tahun,$status, Request $req){
        $kode = ($req->kode == "")? "" : $req->kode;
        $start = ($req->iDisplayStart == "")? 0 : $req->iDisplayStart;
        $length = ($req->iDisplayLength == "")? 0 : $req->iDisplayLength;
        $view           = array();
        if(strlen($kode)>0){
            $data = Pembiayaan::JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','DAT_PEMBIAYAAN.SKPD_ID')
            ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
            ->JOIN('REFERENSI.REF_URUSAN_SKPD','REF_SKPD.SKPD_ID','=','REF_URUSAN_SKPD.SKPD_ID')
            ->JOIN('REFERENSI.REF_URUSAN','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
            ->JOIN('REFERENSI.REF_URUSAN_KATEGORI1','REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID','=','REF_URUSAN.URUSAN_KAT1_ID')
            ->where('PEMBIAYAAN_TAHUN',$tahun)
            ->whereRaw("\"REKENING_KODE\" LIKE ?", [$kode])
            ->groupBy("REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID","REF_URUSAN_KATEGORI1.URUSAN_KAT1_KODE","REF_URUSAN_KATEGORI1.URUSAN_KAT1_NAMA","REF_URUSAN.URUSAN_ID","REF_URUSAN.URUSAN_KODE","REF_URUSAN.URUSAN_NAMA","REF_SKPD.SKPD_ID","REF_SKPD.SKPD_KODE","REF_SKPD.SKPD_NAMA","REF_REKENING.REKENING_ID","REF_REKENING.REKENING_KODE","REF_REKENING.REKENING_NAMA","PEMBIAYAAN_KETERANGAN")
            ->orderBy('URUSAN_KAT1_KODE','KEGIATAN_KODE')
            ->selectRaw('"REF_URUSAN_KATEGORI1"."URUSAN_KAT1_ID","URUSAN_KAT1_KODE","URUSAN_KAT1_NAMA","REF_URUSAN"."URUSAN_ID","URUSAN_KODE","URUSAN_NAMA","REF_SKPD"."SKPD_ID","SKPD_KODE","SKPD_NAMA","REF_REKENING"."REKENING_ID","REKENING_KODE","REKENING_NAMA","PEMBIAYAAN_KETERANGAN",SUM("PEMBIAYAAN_TOTAL") AS pagu');
        } else {
            $data = Pembiayaan::JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','DAT_PEMBIAYAAN.SKPD_ID')
            ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
            ->JOIN('REFERENSI.REF_URUSAN_SKPD','REF_SKPD.SKPD_ID','=','REF_URUSAN_SKPD.SKPD_ID')
            ->JOIN('REFERENSI.REF_URUSAN','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
            ->JOIN('REFERENSI.REF_URUSAN_KATEGORI1','REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID','=','REF_URUSAN.URUSAN_KAT1_ID')
            ->where('PEMBIAYAAN_TAHUN',$tahun)
            ->groupBy("REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID","REF_URUSAN_KATEGORI1.URUSAN_KAT1_KODE","REF_URUSAN_KATEGORI1.URUSAN_KAT1_NAMA","REF_URUSAN.URUSAN_ID","REF_URUSAN.URUSAN_KODE","REF_URUSAN.URUSAN_NAMA","REF_SKPD.SKPD_ID","REF_SKPD.SKPD_KODE","REF_SKPD.SKPD_NAMA","REF_REKENING.REKENING_ID","REF_REKENING.REKENING_KODE","REF_REKENING.REKENING_NAMA","PEMBIAYAAN_KETERANGAN")
            ->orderBy('URUSAN_KAT1_KODE','KEGIATAN_KODE')
            ->selectRaw('"REF_URUSAN_KATEGORI1"."URUSAN_KAT1_ID","URUSAN_KAT1_KODE","URUSAN_KAT1_NAMA","REF_URUSAN"."URUSAN_ID","URUSAN_KODE","URUSAN_NAMA","REF_SKPD"."SKPD_ID","SKPD_KODE","SKPD_NAMA","REF_REKENING"."REKENING_ID","REKENING_KODE","REKENING_NAMA","PEMBIAYAAN_KETERANGAN",SUM("PEMBIAYAAN_TOTAL") AS pagu');
        }

        if($length>0){
            $data = $data->limit($length)->offset($start)->get();
        } else {
            $data = $data->get();
        }

        foreach ($data as $data) {
            array_push($view, array( 
                'KODE_URUSAN'         => $data->URUSAN_KAT1_KODE,
                'NAMA_URUSAN'         => $data->URUSAN_KAT1_NAMA,
                'KODE_BIDANG'         => $data->URUSAN_KODE,
                'NAMA_BIDANG'         => $data->URUSAN_NAMA,
                'KODE_SKPD'           => $data->SKPD_KODE,
                'NAMA_SKPD'           => $data->SKPD_NAMA,
                'NAMA_PEMBIAYAAN'     => $data->PEMBIAYAAN_NAMA,
                'KODE_REKENING'       => $data->REKENING_KODE,
                'JENIS_BELANJA'       => 'Pembiayaan',
                'NAMA_REKENING'       => $data->REKENING_NAMA,
                'ANGGARAN_KEGIATAN'   => $data->pagu                                 
            ));
        }
        return Response::JSON($view);
    }

    public function apiSiraPendapatan($tahun,$status, Request $req){
        $kode = ($req->kode == "")? "" : $req->kode;
        $start = ($req->iDisplayStart == "")? 0 : $req->iDisplayStart;
        $length = ($req->iDisplayLength == "")? 0 : $req->iDisplayLength;
        $view           = array();
        if(strlen($kode)>0){
            $data = Pendapatan::JOIN('REFERENSI.REF_SUB_UNIT','DAT_PENDAPATAN.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
            ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
            ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
            ->JOIN('REFERENSI.REF_URUSAN_SKPD','REF_SKPD.SKPD_ID','=','REF_URUSAN_SKPD.SKPD_ID')
            ->JOIN('REFERENSI.REF_URUSAN','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
            ->JOIN('REFERENSI.REF_URUSAN_KATEGORI1','REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID','=','REF_URUSAN.URUSAN_KAT1_ID')
            ->where('PENDAPATAN_TAHUN',$tahun)
            ->whereRaw("\"REKENING_KODE\" LIKE ?", [$kode])
            ->groupBy("REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID","REF_URUSAN_KATEGORI1.URUSAN_KAT1_KODE","REF_URUSAN_KATEGORI1.URUSAN_KAT1_NAMA","REF_URUSAN.URUSAN_ID","REF_URUSAN.URUSAN_KODE","REF_URUSAN.URUSAN_NAMA","REF_SKPD.SKPD_ID","REF_SKPD.SKPD_KODE","REF_SKPD.SKPD_NAMA",
                "REF_SUB_UNIT.SUB_ID","REF_SUB_UNIT.SUB_KODE","REF_SUB_UNIT.SUB_NAMA",
                "REF_REKENING.REKENING_ID","REF_REKENING.REKENING_KODE","REF_REKENING.REKENING_NAMA","PENDAPATAN_NAMA")
            ->orderBy('URUSAN_KAT1_KODE','KEGIATAN_KODE')
            ->selectRaw('"REF_URUSAN_KATEGORI1"."URUSAN_KAT1_ID","URUSAN_KAT1_KODE","URUSAN_KAT1_NAMA","REF_URUSAN"."URUSAN_ID","URUSAN_KODE","URUSAN_NAMA","REF_SKPD"."SKPD_ID","SKPD_KODE","SKPD_NAMA","REF_SUB_UNIT.SUB_ID","SUB_KODE","SUB_NAMA","REF_REKENING"."REKENING_ID","REKENING_KODE","REKENING_NAMA","PENDAPATAN_NAMA",SUM("PENDAPATAN_TOTAL") AS pagu');
        } else {
            $data = Pendapatan::JOIN('REFERENSI.REF_SUB_UNIT','DAT_PENDAPATAN.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
            ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
            ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
            ->JOIN('REFERENSI.REF_URUSAN_SKPD','REF_SKPD.SKPD_ID','=','REF_URUSAN_SKPD.SKPD_ID')
            ->JOIN('REFERENSI.REF_URUSAN','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
            ->JOIN('REFERENSI.REF_URUSAN_KATEGORI1','REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID','=','REF_URUSAN.URUSAN_KAT1_ID')
            ->where('PENDAPATAN_TAHUN',$tahun)
            ->groupBy("REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID","REF_URUSAN_KATEGORI1.URUSAN_KAT1_KODE","REF_URUSAN_KATEGORI1.URUSAN_KAT1_NAMA","REF_URUSAN.URUSAN_ID","REF_URUSAN.URUSAN_KODE","REF_URUSAN.URUSAN_NAMA","REF_SKPD.SKPD_ID","REF_SKPD.SKPD_KODE","REF_SKPD.SKPD_NAMA",
                "REF_SUB_UNIT.SUB_ID","REF_SUB_UNIT.SUB_KODE","REF_SUB_UNIT.SUB_NAMA",
                "REF_REKENING.REKENING_ID","REF_REKENING.REKENING_KODE","REF_REKENING.REKENING_NAMA","PENDAPATAN_NAMA")
            ->orderBy('URUSAN_KAT1_KODE','KEGIATAN_KODE')
            ->selectRaw('"REF_URUSAN_KATEGORI1"."URUSAN_KAT1_ID","URUSAN_KAT1_KODE","URUSAN_KAT1_NAMA","REF_URUSAN"."URUSAN_ID","URUSAN_KODE","URUSAN_NAMA","REF_SKPD"."SKPD_ID","SKPD_KODE","SKPD_NAMA","REF_SUB_UNIT.SUB_ID","SUB_KODE","SUB_NAMA","REF_REKENING"."REKENING_ID","REKENING_KODE","REKENING_NAMA","PENDAPATAN_NAMA",SUM("PENDAPATAN_TOTAL") AS pagu');
        }

        if($length>0){
            $data = $data->limit($length)->offset($start)->get();
        } else {
            $data = $data->get();
        }

        foreach ($data as $data) {
            array_push($view, array( 
                'KODE_URUSAN'         => $data->URUSAN_KAT1_KODE,
                'NAMA_URUSAN'         => $data->URUSAN_KAT1_NAMA,
                'KODE_BIDANG'         => $data->URUSAN_KODE,
                'NAMA_BIDANG'         => $data->URUSAN_NAMA,
                'KODE_SKPD'           => $data->SKPD_KODE,
                'NAMA_SKPD'           => $data->SKPD_NAMA,
                'KODE_SUB_UNIT'       => $data->SUB_KODE,
                'NAMA_SUB_UNIT'       => $data->SUB_NAMA,
                'NAMA_PENDAPATAN'       => $data->PENDAPATAN_NAMA,
                'KODE_REKENING'       => $data->REKENING_KODE,
                'JENIS_BELANJA'       => 'Pendapatan',
                'NAMA_REKENING'       => $data->REKENING_NAMA,
                'ANGGARAN_KEGIATAN'   => $data->pagu                                 
            ));
        }
        return Response::JSON($view);
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
        if(strlen($kode)>0){
            $data = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
            ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
            ->JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
            ->JOIN('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
            ->JOIN('REFERENSI.REF_URUSAN','REF_PROGRAM.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
            ->where('BL_TAHUN',$tahun)
            ->where('BL_DELETED',0)
            ->where('BL_VALIDASI',1)
            ->whereRaw("\"SKPD_KODE\"||'.'||\"PROGRAM_KODE\"||'.'||\"KEGIATAN_KODE\" LIKE ?", [$kode])
            ->groupBy("REF_URUSAN_KATEGORI1.URUSAN_KAT1_KODE","REF_URUSAN_KATEGORI1.URUSAN_KAT1_NAMA","REF_URUSAN.URUSAN_KODE","REF_URUSAN.URUSAN_NAMA","SKPD_KODE","SKPD_NAMA","REF_PROGRAM.PROGRAM_ID","REF_PROGRAM"."PROGRAM_NAMA","KEGIATAN_KODE","KEGIATAN_NAMA","REF_KEGIATAN.KEGIATAN_ID")
            ->orderBy('URUSAN_KAT1_KODE','KEGIATAN_KODE')
            ->selectRaw('"REF_URUSAN_KATEGORI1"."URUSAN_KAT1_KODE","REF_URUSAN_KATEGORI1"."URUSAN_KAT1_NAMA","REF_URUSAN"."URUSAN_KODE","REF_URUSAN"."URUSAN_NAMA","SKPD_KODE","SKPD_NAMA","PROGRAM_KODE","PROGRAM_NAMA","REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA",SUM ( "BL_PAGU" ) AS pagu')
            ->get();
        } else {
            $data = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
            ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
            ->JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
            ->JOIN('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
            ->JOIN('REFERENSI.REF_URUSAN','REF_PROGRAM.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
            ->JOIN('REFERENSI.REF_URUSAN_KATEGORI1','REF_URUSAN.URUSAN_KAT1_ID','=','REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID')
            ->where('BL_TAHUN',$tahun)
            ->where('BL_DELETED',0)
            ->where('BL_VALIDASI',1)
            ->groupBy("REF_URUSAN_KATEGORI1.URUSAN_KAT1_KODE","REF_URUSAN_KATEGORI1.URUSAN_KAT1_NAMA","REF_URUSAN.URUSAN_KODE","REF_URUSAN.URUSAN_NAMA","SKPD_KODE","SKPD_NAMA","REF_PROGRAM.PROGRAM_ID","PROGRAM_NAMA","KEGIATAN_KODE","KEGIATAN_NAMA","REF_KEGIATAN.KEGIATAN_ID")
            ->orderBy('URUSAN_KAT1_KODE','KEGIATAN_KODE')
            ->selectRaw('"REF_URUSAN_KATEGORI1"."URUSAN_KAT1_KODE","REF_URUSAN_KATEGORI1"."URUSAN_KAT1_NAMA","REF_URUSAN"."URUSAN_KODE","REF_URUSAN"."URUSAN_NAMA","SKPD_KODE","SKPD_NAMA","PROGRAM_KODE","PROGRAM_NAMA","REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA",SUM ( "BL_PAGU" ) AS pagu')
            ->get();
        }

        foreach ($data as $data) {
            array_push($view, array( 
                'KODE_URUSAN'       => $data->URUSAN_KAT1_KODE,
                'NAMA_URUSAN'       => $data->URUSAN_KAT1_NAMA,
                'KODE_BIDANG'       => $data->URUSAN_KODE,
                'NAMA_BIDANG'       => $data->URUSAN_NAMA,
                'KODE_SKPD'       => $data->SKPD_KODE,
                'NAMA_SKPD'       => $data->SKPD_NAMA,
                'KODE_PROGRAM'       => $data->PROGRAM_KODE,
                'NAMA_PROGRAM'       => $data->PROGRAM_NAMA,
                'KODE_KEGIATAN'       => $data->KEGIATAN_KODE,
                'NAMA_KEGIATAN'       => $data->KEGIATAN_NAMA,
                'JENIS_BELANJA'       => 'Belanja Langsung',
                'ANGGARAN'          => $data->pagu                                 
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

