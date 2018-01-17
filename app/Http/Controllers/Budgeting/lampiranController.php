<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use App;
use Carbon;
use PDF;
use Auth;
use Excel;
use DB;
use App\Model\BL;
use App\Model\Indikator;
use App\Model\Rincian;
use App\Model\Rekening;
use App\Model\Outcome;
use App\Model\SKPD;
use App\Model\Kegiatan;
use App\Model\Program;
use App\Model\Pendapatan;
use App\Model\BTL;
use App\Model\Pembiayaan;
use App\Model\RekapBL;
use App\Model\RekapRincian;
use App\Model\UserBudget;
use App\Model\Tahapan;
use App\Model\BLPerubahan;
use App\Model\RincianPerubahan;
use App\Model\RincianLog;
use App\Model\RincianHistory;
use App\Model\Urusan;
use App\Model\UrusanSKPD;
use App\Model\UrusanKategori1;
use App\Model\UrusanKategori2;
use App\Model\AKB_BL; 
use App\Model\AKB_BTL; 
use App\Model\Staff; 
use App\Model\User; 
class lampiranController extends Controller
{
    // public function __construct(){
    //     $this->middleware('auth');
    // }
    public function showLampiran($tahun,$status,$tipe){
        if(Auth::user()->level == 2) $skpd = SKPD::where('SKPD_ID',UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID'))->get();
        else  $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        if($tipe == 'apbd') return View('budgeting.lampiran.indexAPBD',$data);
        else return View('budgeting.lampiran.index',$data);
    }

    public function rka($tahun, $status, $id){

        $indikator  = Indikator::where('BL_ID',$id)->orderBy('INDIKATOR')->get();

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        if($status=='murni'){

            $bl         = BL::where('BL_ID',$id)->first();

            $total      = Rincian::where('BL_ID',$id)->sum('RINCIAN_TOTAL');
            $rekening   = Rincian::where('BL_ID',$id)->orderBy('REKENING_ID')
                          ->groupBy('REKENING_ID')
                          ->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL, "REKENING_ID"')
                          ->get();


            $paket      = array();
            $i          = 0;
            $q          = 0;
            $s          = 0;
            $komponen   = "";
            $rek        = "";
            $reke       = "";
            $rek4       = "";
            $rek3       = "";
            $totalrek   = "";
            $totalreke  = "";

            foreach($rekening as $r) {

                $rek[$q]     = Rekening::where('REKENING_KODE',substr($r->rekening->REKENING_KODE,0,8))->first();
                $reke[$s]    = Rekening::where('REKENING_KODE',substr($r->rekening->REKENING_KODE,0,5))->first();
                $rek4        = $rek[$q];
                $rek3        = $reke[$s];


                $totalrek[$q]= Rincian::whereHas('rekening', function($x) use ($rek4){
                   $x->where('REKENING_KODE','like',$rek4->REKENING_KODE.'%');
                })->where('BL_ID',$id)->sum('RINCIAN_TOTAL');
                $totalreke[$s]= Rincian::whereHas('rekening', function($x) use ($rek3){
                    $x->where('REKENING_KODE','like',$rek3->REKENING_KODE.'%');
                })->where('BL_ID',$id)->sum('RINCIAN_TOTAL');

                $paket[$i]   = Rincian::where('BL_ID',$id)
                                ->where('REKENING_ID',$r->REKENING_ID)
                                ->groupBy('SUBRINCIAN_ID')
                                ->groupBy('REKENING_ID')
                                //->groupBy('RINCIAN_PAJAK')
                                ->orderBy('SUBRINCIAN_ID')
                                ->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL, "SUBRINCIAN_ID","REKENING_ID" ')
                                ->get();



                $k = 0;
                foreach($paket[$i] as $p){
                    $komponen[$i][$k++]    = Rincian::where('SUBRINCIAN_ID',$p->SUBRINCIAN_ID)
                                            ->where('REKENING_ID',$p->REKENING_ID)
                                            ->orderBy('KOMPONEN_ID')
                                            ->get();
                                            
                } 
                                                           
                $i++; 
                $q++; 
                $s++; 
            }
           
            $totalBL    = Rincian::where('BL_ID',$id)->sum('RINCIAN_TOTAL');


            return View('budgeting.lampiran.rka',['tahun'=>$tahun,'status'=>$status,'bl'=>$bl,'indikator'=>$indikator,'rekening'=>$rekening,'tgl'=>$tgl,'bln'=>$bln,'thn'=>$thn,'total'=>$total,'paket'=>$paket,'m'=>0,'komponen'=>$komponen,'totalbl'=>$totalBL,'rek'=>$rek,'q'=>0,'s'=>0,'reke'=>$reke,'totalrek'=>$totalrek,'totalreke'=>$totalreke]);

        }else{
            $bl    = BLPerubahan::where('BL_ID',$id)->where('BL_TAHUN',$tahun)->first();
            $total = RincianPerubahan::where('BL_ID',$id)->sum('RINCIAN_TOTAL');
            $rekening       = RincianPerubahan::leftJoin('BUDGETING.DAT_RINCIAN',function($join){
                                $join->on('BUDGETING.DAT_RINCIAN.RINCIAN_ID','=','DAT_RINCIAN_PERUBAHAN.RINCIAN_ID')
                                     ->on('BUDGETING.DAT_RINCIAN.BL_ID','=','DAT_RINCIAN_PERUBAHAN.BL_ID');
                              })
                              ->whereHas('bl', function($x) use ($tahun){
                                $x->where('BL_TAHUN','=',$tahun);
                              })
                              ->whereHas('rekening', function($x) use ($tahun){
                                $x->where('REKENING_TAHUN','=',$tahun);
                              })
                              ->where('DAT_RINCIAN_PERUBAHAN.BL_ID',$id)
                              ->orderBy('DAT_RINCIAN_PERUBAHAN.REKENING_ID')
                              ->groupBy('DAT_RINCIAN_PERUBAHAN.REKENING_ID')
                              //->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL, "REKENING_ID"')
                              ->select(['DAT_RINCIAN_PERUBAHAN.REKENING_ID',DB::raw('sum("DAT_RINCIAN_PERUBAHAN"."RINCIAN_TOTAL") as "TOTAL"'),DB::raw('sum("BUDGETING"."DAT_RINCIAN"."RINCIAN_TOTAL") as "TOTAL_MURNI"')])
                              ->get();

            $bl_murni       = BL::where('BL_ID',$id)->where('BL_TAHUN',$tahun)->first();
            (empty($bl_murni))?$bl_murni=0:$bl_murni=$bl_murni;//print_r($bl_murni);exit;
            $total_murni    = Rincian::where('BL_ID',$id)->sum('RINCIAN_TOTAL');
            (empty($total_murni))?$total_murni=0:$total_murni=$total_murni;

            $paket      = array();
            $i          = 0;
            $q          = 0;
            $s          = 0;
            $komponen   = "";
            $rek        = "";
            $reke       = "";
            $rek4       = "";
            $rek3       = "";
            $totalrek   = "";
            $totalreke  = "";
            $totalrek_murni   = "";
            $totalreke_murni  = "";                  

            foreach($rekening as $r) {
                $rek[$q]     = Rekening::where('REKENING_KODE',substr($r->rekening->REKENING_KODE,0,8))->first();
                $reke[$s]    = Rekening::where('REKENING_KODE',substr($r->rekening->REKENING_KODE,0,5))->first();
                $rek4        = $rek[$q];
                $rek3        = $reke[$s];    
                
                $totalrek[$q]= RincianPerubahan::whereHas('rekening', function($x) use ($rek4){
                                    $x->where('REKENING_KODE','like',$rek4->REKENING_KODE.'%');
                                })
                               ->where('BL_ID',$id)
                               ->whereHas('bl', function($x) use ($tahun){
                                    $x->where('BL_TAHUN','=',$tahun);
                                })
                               ->sum('RINCIAN_TOTAL');
                $totalrek_murni[$q]= Rincian::whereHas('rekening', function($x) use ($rek4){
                                    $x->where('REKENING_KODE','like',$rek4->REKENING_KODE.'%');
                                })
                               ->where('BL_ID',$id)
                               ->whereHas('bl', function($x) use ($tahun){
                                    $x->where('BL_TAHUN','=',$tahun);
                                })
                               ->sum('RINCIAN_TOTAL');

                $totalreke[$s]= RincianPerubahan::whereHas('rekening', function($x) use ($rek3,$tahun){
                                    $x->where('REKENING_KODE','like',$rek3->REKENING_KODE.'%');
                                })
                                ->where('BL_ID',$id)
                                ->whereHas('bl', function($x) use ($tahun){
                                    $x->where('BL_TAHUN','=',$tahun);
                                })
                                ->sum('RINCIAN_TOTAL');
                $totalreke_murni[$s]= Rincian::whereHas('rekening', function($x) use ($rek3,$tahun){
                                    $x->where('REKENING_KODE','like',$rek3->REKENING_KODE.'%');
                                })
                                ->where('BL_ID',$id)
                                ->whereHas('bl', function($x) use ($tahun){
                                    $x->where('BL_TAHUN','=',$tahun);
                                })
                                ->sum('RINCIAN_TOTAL');
                
                $paket[$i]   = RincianPerubahan::leftJoin('BUDGETING.DAT_RINCIAN',function($join){
                                    $join->on('BUDGETING.DAT_RINCIAN.RINCIAN_ID','=','DAT_RINCIAN_PERUBAHAN.RINCIAN_ID')
                                         ->on('BUDGETING.DAT_RINCIAN.BL_ID','=','DAT_RINCIAN_PERUBAHAN.BL_ID');
                                })
                                ->where('DAT_RINCIAN_PERUBAHAN.BL_ID',$id)
                                ->whereHas('bl', function($x) use ($tahun){
                                    $x->where('BL_TAHUN','=',$tahun);
                                })
                                ->where('DAT_RINCIAN_PERUBAHAN.REKENING_ID',$r->REKENING_ID)
                                ->groupBy('DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID')
                                ->groupBy('DAT_RINCIAN_PERUBAHAN.REKENING_ID')
                                //->groupBy('DAT_RINCIAN_PERUBAHAN.RINCIAN_PAJAK')
                                ->orderBy('DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID')
                                //->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL, "SUBRINCIAN_ID","REKENING_ID", "RINCIAN_PAJAK"')
                                ->select(['DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID','DAT_RINCIAN_PERUBAHAN.REKENING_ID',DB::raw('sum("DAT_RINCIAN_PERUBAHAN"."RINCIAN_TOTAL") as "TOTAL"'),DB::raw('sum("BUDGETING"."DAT_RINCIAN"."RINCIAN_TOTAL") as "TOTAL_MURNI"')])
                                ->get();
                
                /*
                $paket[$i]   = RincianPerubahan::where('BL_ID',$id)
                                ->where('REKENING_ID',$r->REKENING_ID)
                                ->groupBy('SUBRINCIAN_ID')
                                ->groupBy('REKENING_ID')
                                ->groupBy('RINCIAN_PAJAK')
                                ->orderBy('SUBRINCIAN_ID')
                                ->selectRaw('SUM("RINCIAN_TOTAL") AS "TOTAL", "SUBRINCIAN_ID","REKENING_ID", "RINCIAN_PAJAK", SUM("RINCIAN_TOTAL") AS "TOTAL_MURNI"')
                                ->get();
                */

                $k = 0;
                foreach($paket[$i] as $p){
                    /*
                    $komponen[$i][$k++]    = RincianPerubahan::where('SUBRINCIAN_ID',$p->SUBRINCIAN_ID)
                                                    ->where('REKENING_ID',$p->REKENING_ID)
                                                    ->groupBy('RINCIAN_ID','SUBRINCIAN_ID','REKENING_ID','KOMPONEN_ID','RINCIAN_PAJAK','RINCIAN_VOLUME','RINCIAN_KOEFISIEN','RINCIAN_TOTAL','RINCIAN_KETERANGAN','PEKERJAAN_ID','BL_ID','RINCIAN_KOMPONEN','RINCIAN_HARGA')
                                                    ->orderBy('KOMPONEN_ID')
                                                    ->get();
                    */
                    $komponen[$i][$k++] = RincianPerubahan::leftJoin('BUDGETING.DAT_RINCIAN',function($join){
                                            $join->on('BUDGETING.DAT_RINCIAN.RINCIAN_ID','=','DAT_RINCIAN_PERUBAHAN.RINCIAN_ID')
                                                 ->on('BUDGETING.DAT_RINCIAN.BL_ID','=','DAT_RINCIAN_PERUBAHAN.BL_ID');
                                        })
                                        ->where('DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID',$p->SUBRINCIAN_ID)
                                        ->where('DAT_RINCIAN_PERUBAHAN.REKENING_ID',$p->REKENING_ID)
                                        ->whereHas('bl', function($x) use ($tahun){
                                            $x->where('BL_TAHUN','=',$tahun);
                                        })
                                        ->orderBy('DAT_RINCIAN_PERUBAHAN.RINCIAN_ID')
                                        ->orderBy('DAT_RINCIAN_PERUBAHAN.KOMPONEN_ID')
                                        ->select('DAT_RINCIAN_PERUBAHAN.*','BUDGETING.DAT_RINCIAN.RINCIAN_KETERANGAN AS RINCIAN_KETERANGAN_MURNI','BUDGETING.DAT_RINCIAN.RINCIAN_KOEFISIEN AS RINCIAN_KOEFISIEN_MURNI','BUDGETING.DAT_RINCIAN.RINCIAN_KOMPONEN AS RINCIAN_KOMPONEN_MURNI','BUDGETING.DAT_RINCIAN.RINCIAN_VOLUME AS RINCIAN_VOLUME_MURNI','BUDGETING.DAT_RINCIAN.RINCIAN_HARGA AS RINCIAN_HARGA_MURNI','BUDGETING.DAT_RINCIAN.RINCIAN_TOTAL AS RINCIAN_TOTAL_MURNI')
                                        ->get();
                    }

                $i++; 
                $q++; 
                $s++;    

            }
            //print_r($komponen);exit;
            $totalBL    = RincianPerubahan::where('BL_ID',$id)->sum('RINCIAN_TOTAL');

            $totalBLMurni = Rincian::where('BL_ID',$id)->sum('RINCIAN_TOTAL');

            $selisih = $totalBL-$totalBLMurni;

            if($totalBLMurni != 0){
                $persen = ($selisih*100)/$totalBLMurni;
            }else {
                $persen = 0;
            }
                                         
           return View('budgeting.lampiran.rka_perubahan',
                [   'tahun'             =>$tahun,
                    'status'            =>$status,
                    'bl'                =>$bl,
                    'indikator'         =>$indikator,
                    'rekening'          =>$rekening,
                    'tgl'               =>$tgl,
                    'bln'               =>$bln,
                    'thn'               =>$thn,
                    'total'             =>$total,
                    'paket'             =>$paket,
                    'm'                 =>0,
                    'komponen'          =>$komponen,
                    'totalbl'           =>$totalBL,
                    'rek'               =>$rek,
                    'q'                 =>0,
                    's'                 =>0,
                    'reke'              =>$reke,
                    'totalrek'          =>$totalrek,
                    'totalreke'         =>$totalreke,
                    'totalrek_murni'    =>$totalrek_murni,
                    'totalreke_murni'   =>$totalreke_murni,
                    'total_murni'       =>$total_murni,
                    'totalbl_murni'     =>$totalBLMurni,
                    'bl_murni'          =>$bl_murni,
                    'selisih'          =>$selisih,
                    'persen'            =>$persen
                ]);
        }      
            
    }

    public function rkaSebelum($tahun, $status, $id){

        $indikator  = Indikator::where('BL_ID',$id)->orderBy('INDIKATOR')->get();
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        if($status=='murni'){

            $bl         = BL::where('BL_ID',$id)->first();
            $total      = RincianHistory::where('BL_ID',$id)->sum('RINCIAN_TOTAL');
            $rekening   = RincianHistory::where('BL_ID',$id)->orderBy('REKENING_ID')
                          ->groupBy('REKENING_ID')
                          ->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL, "REKENING_ID"')
                          ->get();

            $paket      = array();
            $i          = 0;
            $q          = 0;
            $s          = 0;
            $komponen   = "";
            $rek        = "";
            $reke       = "";
            $rek4       = "";
            $rek3       = "";
            $totalrek   = "";
            $totalreke  = "";

            foreach($rekening as $r) {

                $rek[$q]     = Rekening::where('REKENING_KODE',substr($r->rekening->REKENING_KODE,0,8))->first();
                $reke[$s]    = Rekening::where('REKENING_KODE',substr($r->rekening->REKENING_KODE,0,5))->first();
                $rek4        = $rek[$q];
                $rek3        = $reke[$s];

                $totalrek[$q]= RincianHistory::whereHas('rekening', function($x) use ($rek4){
                    $x->where('REKENING_KODE','like',$rek4->REKENING_KODE.'%');
                })->where('BL_ID',$id)->sum('RINCIAN_TOTAL');
                $totalreke[$s]= RincianHistory::whereHas('rekening', function($x) use ($rek3){
                    $x->where('REKENING_KODE','like',$rek3->REKENING_KODE.'%');
                })->where('BL_ID',$id)->sum('RINCIAN_TOTAL');
                $paket[$i]   = RincianHistory::where('BL_ID',$id)
                                ->where('REKENING_ID',$r->REKENING_ID)
                                ->groupBy('SUBRINCIAN_ID')
                                ->groupBy('REKENING_ID')
                                ->groupBy('RINCIAN_PAJAK')
                                ->orderBy('SUBRINCIAN_ID')
                                ->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL, "SUBRINCIAN_ID","REKENING_ID", "RINCIAN_PAJAK"')
                                ->get();

                $k = 0;
                foreach($paket[$i] as $p){
                    $komponen[$i][$k++]    = RincianHistory::where('SUBRINCIAN_ID',$p->SUBRINCIAN_ID)
                                            ->where('REKENING_ID',$p->REKENING_ID)
                                            ->orderBy('KOMPONEN_ID')
                                            ->get();
                } 
                                                           
                $i++; 
                $q++; 
                $s++; 
            }
           
            $totalBL    = RincianHistory::where('BL_ID',$id)->sum('RINCIAN_TOTAL');

            return View('budgeting.lampiran.rka',['tahun'=>$tahun,'status'=>$status,'bl'=>$bl,'indikator'=>$indikator,'rekening'=>$rekening,'tgl'=>$tgl,'bln'=>$bln,'thn'=>$thn,'total'=>$total,'paket'=>$paket,'m'=>0,'komponen'=>$komponen,'totalbl'=>$totalBL,'rek'=>$rek,'q'=>0,'s'=>0,'reke'=>$reke,'totalrek'=>$totalrek,'totalreke'=>$totalreke]);

        }else{
            $bl    = BLPerubahan::where('BL_ID',$id)->where('BL_TAHUN',$tahun)->first();
            $total = RincianPerubahan::where('BL_ID',$id)->sum('RINCIAN_TOTAL');
            $rekening       = RincianPerubahan::leftJoin('BUDGETING.DAT_RINCIAN',function($join){
                                $join->on('BUDGETING.DAT_RINCIAN.RINCIAN_ID','=','DAT_RINCIAN_PERUBAHAN.RINCIAN_ID')
                                     ->on('BUDGETING.DAT_RINCIAN.BL_ID','=','DAT_RINCIAN_PERUBAHAN.BL_ID');
                              })
                              ->whereHas('bl', function($x) use ($tahun){
                                $x->where('BL_TAHUN','=',$tahun);
                              })
                              ->whereHas('rekening', function($x) use ($tahun){
                                $x->where('REKENING_TAHUN','=',$tahun);
                              })
                              ->where('DAT_RINCIAN_PERUBAHAN.BL_ID',$id)
                              ->orderBy('DAT_RINCIAN_PERUBAHAN.REKENING_ID')
                              ->groupBy('DAT_RINCIAN_PERUBAHAN.REKENING_ID')
                              //->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL, "REKENING_ID"')
                              ->select(['DAT_RINCIAN_PERUBAHAN.REKENING_ID',DB::raw('sum("DAT_RINCIAN_PERUBAHAN"."RINCIAN_TOTAL") as "TOTAL"'),DB::raw('sum("BUDGETING"."DAT_RINCIAN"."RINCIAN_TOTAL") as "TOTAL_MURNI"')])
                              ->get();

            $bl_murni       = BL::where('BL_ID',$id)->where('BL_TAHUN',$tahun)->first();
            (empty($bl_murni))?$bl_murni=0:$bl_murni=$bl_murni;//print_r($bl_murni);exit;
            $total_murni    = Rincian::where('BL_ID',$id)->sum('RINCIAN_TOTAL');
            (empty($total_murni))?$total_murni=0:$total_murni=$total_murni;

            $paket      = array();
            $i          = 0;
            $q          = 0;
            $s          = 0;
            $komponen   = "";
            $rek        = "";
            $reke       = "";
            $rek4       = "";
            $rek3       = "";
            $totalrek   = "";
            $totalreke  = "";
            $totalrek_murni   = "";
            $totalreke_murni  = "";                  

            foreach($rekening as $r) {
                $rek[$q]     = Rekening::where('REKENING_KODE',substr($r->rekening->REKENING_KODE,0,8))->first();
                $reke[$s]    = Rekening::where('REKENING_KODE',substr($r->rekening->REKENING_KODE,0,5))->first();
                $rek4        = $rek[$q];
                $rek3        = $reke[$s];    
                
                $totalrek[$q]= RincianPerubahan::whereHas('rekening', function($x) use ($rek4){
                                    $x->where('REKENING_KODE','like',$rek4->REKENING_KODE.'%');
                                })
                               ->where('BL_ID',$id)
                               ->whereHas('bl', function($x) use ($tahun){
                                    $x->where('BL_TAHUN','=',$tahun);
                                })
                               ->sum('RINCIAN_TOTAL');
                $totalrek_murni[$q]= Rincian::whereHas('rekening', function($x) use ($rek4){
                                    $x->where('REKENING_KODE','like',$rek4->REKENING_KODE.'%');
                                })
                               ->where('BL_ID',$id)
                               ->whereHas('bl', function($x) use ($tahun){
                                    $x->where('BL_TAHUN','=',$tahun);
                                })
                               ->sum('RINCIAN_TOTAL');

                $totalreke[$s]= RincianPerubahan::whereHas('rekening', function($x) use ($rek3,$tahun){
                                    $x->where('REKENING_KODE','like',$rek3->REKENING_KODE.'%');
                                })
                                ->where('BL_ID',$id)
                                ->whereHas('bl', function($x) use ($tahun){
                                    $x->where('BL_TAHUN','=',$tahun);
                                })
                                ->sum('RINCIAN_TOTAL');
                $totalreke_murni[$s]= Rincian::whereHas('rekening', function($x) use ($rek3,$tahun){
                                    $x->where('REKENING_KODE','like',$rek3->REKENING_KODE.'%');
                                })
                                ->where('BL_ID',$id)
                                ->whereHas('bl', function($x) use ($tahun){
                                    $x->where('BL_TAHUN','=',$tahun);
                                })
                                ->sum('RINCIAN_TOTAL');
                
                $paket[$i]   = RincianPerubahan::leftJoin('BUDGETING.DAT_RINCIAN',function($join){
                                    $join->on('BUDGETING.DAT_RINCIAN.RINCIAN_ID','=','DAT_RINCIAN_PERUBAHAN.RINCIAN_ID')
                                         ->on('BUDGETING.DAT_RINCIAN.BL_ID','=','DAT_RINCIAN_PERUBAHAN.BL_ID');
                                })
                                ->where('DAT_RINCIAN_PERUBAHAN.BL_ID',$id)
                                ->whereHas('bl', function($x) use ($tahun){
                                    $x->where('BL_TAHUN','=',$tahun);
                                })
                                ->where('DAT_RINCIAN_PERUBAHAN.REKENING_ID',$r->REKENING_ID)
                                ->groupBy('DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID')
                                ->groupBy('DAT_RINCIAN_PERUBAHAN.REKENING_ID')
                                //->groupBy('DAT_RINCIAN_PERUBAHAN.RINCIAN_PAJAK')
                                ->orderBy('DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID')
                                //->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL, "SUBRINCIAN_ID","REKENING_ID", "RINCIAN_PAJAK"')
                                ->select(['DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID','DAT_RINCIAN_PERUBAHAN.REKENING_ID',DB::raw('sum("DAT_RINCIAN_PERUBAHAN"."RINCIAN_TOTAL") as "TOTAL"'),DB::raw('sum("BUDGETING"."DAT_RINCIAN"."RINCIAN_TOTAL") as "TOTAL_MURNI"')])
                                ->get();
                
                /*
                $paket[$i]   = RincianPerubahan::where('BL_ID',$id)
                                ->where('REKENING_ID',$r->REKENING_ID)
                                ->groupBy('SUBRINCIAN_ID')
                                ->groupBy('REKENING_ID')
                                ->groupBy('RINCIAN_PAJAK')
                                ->orderBy('SUBRINCIAN_ID')
                                ->selectRaw('SUM("RINCIAN_TOTAL") AS "TOTAL", "SUBRINCIAN_ID","REKENING_ID", "RINCIAN_PAJAK", SUM("RINCIAN_TOTAL") AS "TOTAL_MURNI"')
                                ->get();
                */

                $k = 0;
                foreach($paket[$i] as $p){
                    /*
                    $komponen[$i][$k++]    = RincianPerubahan::where('SUBRINCIAN_ID',$p->SUBRINCIAN_ID)
                                                    ->where('REKENING_ID',$p->REKENING_ID)
                                                    ->groupBy('RINCIAN_ID','SUBRINCIAN_ID','REKENING_ID','KOMPONEN_ID','RINCIAN_PAJAK','RINCIAN_VOLUME','RINCIAN_KOEFISIEN','RINCIAN_TOTAL','RINCIAN_KETERANGAN','PEKERJAAN_ID','BL_ID','RINCIAN_KOMPONEN','RINCIAN_HARGA')
                                                    ->orderBy('KOMPONEN_ID')
                                                    ->get();
                    */
                    $komponen[$i][$k++] = RincianPerubahan::leftJoin('BUDGETING.DAT_RINCIAN',function($join){
                                            $join->on('BUDGETING.DAT_RINCIAN.RINCIAN_ID','=','DAT_RINCIAN_PERUBAHAN.RINCIAN_ID')
                                                 ->on('BUDGETING.DAT_RINCIAN.BL_ID','=','DAT_RINCIAN_PERUBAHAN.BL_ID');
                                        })
                                        ->where('DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID',$p->SUBRINCIAN_ID)
                                        ->where('DAT_RINCIAN_PERUBAHAN.REKENING_ID',$p->REKENING_ID)
                                        ->whereHas('bl', function($x) use ($tahun){
                                            $x->where('BL_TAHUN','=',$tahun);
                                        })
                                        ->orderBy('DAT_RINCIAN_PERUBAHAN.RINCIAN_ID')
                                        ->orderBy('DAT_RINCIAN_PERUBAHAN.KOMPONEN_ID')
                                        ->select('DAT_RINCIAN_PERUBAHAN.*','BUDGETING.DAT_RINCIAN.RINCIAN_KETERANGAN AS RINCIAN_KETERANGAN_MURNI','BUDGETING.DAT_RINCIAN.RINCIAN_KOEFISIEN AS RINCIAN_KOEFISIEN_MURNI','BUDGETING.DAT_RINCIAN.RINCIAN_KOMPONEN AS RINCIAN_KOMPONEN_MURNI','BUDGETING.DAT_RINCIAN.RINCIAN_VOLUME AS RINCIAN_VOLUME_MURNI','BUDGETING.DAT_RINCIAN.RINCIAN_HARGA AS RINCIAN_HARGA_MURNI','BUDGETING.DAT_RINCIAN.RINCIAN_TOTAL AS RINCIAN_TOTAL_MURNI')
                                        ->get();
                    }

                $i++; 
                $q++; 
                $s++;    

            }
            //print_r($komponen);exit;
            $totalBL    = RincianPerubahan::where('BL_ID',$id)->sum('RINCIAN_TOTAL');

            $totalBLMurni = Rincian::where('BL_ID',$id)->sum('RINCIAN_TOTAL');

            $selisih = $totalBL-$totalBLMurni;

            if($totalBLMurni != 0){
                $persen = ($selisih*100)/$totalBLMurni;
            }else {
                $persen = 0;
            }
                                         
           return View('budgeting.lampiran.rka_perubahan',
                [   'tahun'             =>$tahun,
                    'status'            =>$status,
                    'bl'                =>$bl,
                    'indikator'         =>$indikator,
                    'rekening'          =>$rekening,
                    'tgl'               =>$tgl,
                    'bln'               =>$bln,
                    'thn'               =>$thn,
                    'total'             =>$total,
                    'paket'             =>$paket,
                    'm'                 =>0,
                    'komponen'          =>$komponen,
                    'totalbl'           =>$totalBL,
                    'rek'               =>$rek,
                    'q'                 =>0,
                    's'                 =>0,
                    'reke'              =>$reke,
                    'totalrek'          =>$totalrek,
                    'totalreke'         =>$totalreke,
                    'totalrek_murni'    =>$totalrek_murni,
                    'totalreke_murni'   =>$totalreke_murni,
                    'total_murni'       =>$total_murni,
                    'totalbl_murni'     =>$totalBLMurni,
                    'bl_murni'          =>$bl_murni,
                    'selisih'          =>$selisih,
                    'persen'            =>$persen
                ]);
        }      
            
    }

    public function rkaLog($tahun, $status, $id){

        if($status == 'murni'){
            $data   = DB::select('select keg."KEGIATAN_NAMA", sub."SUBRINCIAN_NAMA", rek."REKENING_KODE", rek."REKENING_NAMA", kom."KOMPONEN_KODE", kom."KOMPONEN_NAMA", "RINCIAN_PAJAK" AS PAJAK, "RINCIAN_VOLUME" as VOLUME, "RINCIAN_KOEFISIEN" AS KOEF, "RINCIAN_TOTAL" AS TOTAL,"RINCIAN_KETERANGAN" AS KET,"RINCIAN_TAHAPAN" AS TAHAPAN ,"RINCIAN_TANGGAL" AS TANGGAL, "RINCIAN_HARGA" AS HARGA, "RINCIAN_STATUS"
                                    from "BUDGETING"."DAT_RINCIAN_LOG" RIN
                                    inner JOIN "BUDGETING"."DAT_BL" BL
                                    on BL."BL_ID" = RIN."BL_ID"
                                    inner JOIN "REFERENSI"."REF_KEGIATAN" keg
                                    on BL."KEGIATAN_ID" = keg."KEGIATAN_ID"
                                    inner join "BUDGETING"."DAT_SUBRINCIAN" sub
                                    on sub."SUBRINCIAN_ID" = RIN."SUBRINCIAN_ID"
                                    inner join "REFERENSI"."REF_REKENING" rek
                                    on RIN."REKENING_ID" = rek."REKENING_ID"
                                    inner join "EHARGA"."DAT_KOMPONEN" kom
                                    on RIN."KOMPONEN_ID" = kom."KOMPONEN_ID"
                                    WHERE RIN."BL_ID" = '.$id
                                    );
        
        }
               //dd($data);
            $data = array_map(function ($value) {
                    return (array)$value;
                }, $data);
            Excel::create('RKA SEBELUMNYA '.Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
                    $excel->sheet('RKA SEBELUMNYA', function($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
            })->download('xls');
            
    }

     
     public function rkaLogAll($tahun, $status){

        if($status == 'murni'){
            $data   = DB::select('select skpd."SKPD_NAMA", keg."KEGIATAN_NAMA", sub."SUBRINCIAN_NAMA", rek."REKENING_KODE", rek."REKENING_NAMA", kom."KOMPONEN_KODE", kom."KOMPONEN_NAMA", "RINCIAN_PAJAK" AS PAJAK, "RINCIAN_VOLUME" as VOLUME, "RINCIAN_KOEFISIEN" AS KOEF, "RINCIAN_TOTAL" AS TOTAL,"RINCIAN_KETERANGAN" AS KET,"RINCIAN_TAHAPAN" AS TAHAPAN ,"RINCIAN_TANGGAL" AS TANGGAL, "RINCIAN_HARGA" AS HARGA, "RINCIAN_STATUS"
                                    from "BUDGETING"."DAT_RINCIAN_LOG" RIN
                                    inner JOIN "BUDGETING"."DAT_BL" BL
                                    on BL."BL_ID" = RIN."BL_ID"
                                    inner JOIN "REFERENSI"."REF_KEGIATAN" keg
                                    on BL."KEGIATAN_ID" = keg."KEGIATAN_ID"
                                    inner JOIN "REFERENSI"."REF_SUB_UNIT" unit
                                    on unit."SUB_ID" = BL."SUB_ID"
                                    inner JOIN "REFERENSI"."REF_SKPD" skpd
                                    on skpd."SKPD_ID" = unit."SKPD_ID"
                                    inner join "BUDGETING"."DAT_SUBRINCIAN" sub
                                    on sub."SUBRINCIAN_ID" = RIN."SUBRINCIAN_ID"
                                    inner join "REFERENSI"."REF_REKENING" rek
                                    on RIN."REKENING_ID" = rek."REKENING_ID"
                                    inner join "EHARGA"."DAT_KOMPONEN" kom
                                    on RIN."KOMPONEN_ID" = kom."KOMPONEN_ID"'
                                    );
        
        }
               //dd($data);
            $data = array_map(function ($value) {
                    return (array)$value;
                }, $data);
            Excel::create('RKA SEBELUMNYA '.Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
                    $excel->sheet('RKA SEBELUMNYA', function($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
            })->download('xls');
            
    } 


    public function dpa(){

    }

    public function rkpd($tahun,$status,$id){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RKPD')->value('TAHAPAN_ID');
        $idSKPD         = SKPD::where('SKPD_ID',$id)->first();

        if($status == 'murni') $stat    = BL::where('BL_TAHUN',$tahun);
        else { 
            $stat  = BLPerubahan::where('BL_TAHUN',$tahun);
            $pagu_murni = BL::where('BL_TAHUN',$tahun)->whereHas('subunit',function($x) use ($id){
                                $x->where('SKPD_ID',$id);
                        })
                        ->where('BL_VALIDASI',1)
                        ->where('BL_DELETED',0)
                        ->where('BL_PAGU','!=',0)
                        ->get();
        }
            
        $pagu           = $stat->whereHas('subunit',function($x) use ($id){
                                $x->where('SKPD_ID',$id);
                        })
                        ->where('BL_VALIDASI',1)
                        ->where('BL_DELETED',0)
                        ->where('BL_PAGU','!=',0)
                        ->get();

        $prog           = $stat->whereHas('subunit',function($q) use ($id){
                                $q->where('SKPD_ID',$id);
                        })
                        ->groupBy('KEGIATAN_ID')
                        ->select('KEGIATAN_ID')
                        ->where('BL_VALIDASI',1)
                        ->where('BL_DELETED',0)
                        ->where('BL_PAGU','!=',0)                        
                        ->get()->toArray();
        
        $program        = Program::whereHas('kegiatan',function($q) use($prog){
                                $q->whereIn('KEGIATAN_ID',$prog);
                            })
                            ->orderBy('URUSAN_ID')
                            ->orderBy('PROGRAM_KODE')
                            ->get();
        $paguprogram    = array();
        $paguprogrammurni    = array();
        $i              = 0;
        foreach($program as $pr){
            if($status == 'murni') {
                $stat    = BL::where('BL_TAHUN',$tahun);
                $idprog            = $pr->PROGRAM_ID;
                $paguprogram[$i]   = $stat->whereHas('kegiatan',function($q) use ($idprog){
                                    $q->where('PROGRAM_ID',$idprog);
                                })->whereHas('subunit',function($x) use ($id){
                                    $x->where('SKPD_ID',$id);
                                })
                                ->where('BL_VALIDASI',1)
                                ->where('BL_DELETED',0)
                                ->where('BL_PAGU','!=',0)                                
                                ->groupBy('KEGIATAN_ID')
                                ->selectRaw('SUM("BL_PAGU") AS pagu, "KEGIATAN_ID"')
                                ->get();
            }
            else {
                $stat  = BLPerubahan::where('BL_TAHUN',$tahun);
                $idprog            = $pr->PROGRAM_ID;
                $paguprogram[$i]   = $stat->whereHas('kegiatan',function($q) use ($idprog){
                                    $q->where('PROGRAM_ID',$idprog);
                                })->whereHas('subunit',function($x) use ($id){
                                    $x->where('SKPD_ID',$id);
                                })
                                ->where('BL_VALIDASI',1)
                                ->where('BL_DELETED',0)
                                ->where('BL_PAGU','!=',0)                                
                                ->groupBy('KEGIATAN_ID')
                                ->selectRaw('SUM("BL_PAGU") AS pagu, "KEGIATAN_ID"')
                                ->get();
                //$stat  = BL::where('BL_TAHUN',$tahun);
                $idprog            = $pr->PROGRAM_ID;
                $paguprogrammurni[$i]   = BL::where('BL_TAHUN',$tahun)->whereHas('kegiatan',function($q) use ($idprog){
                                    $q->where('PROGRAM_ID',$idprog);
                                })->whereHas('subunit',function($x) use ($id){
                                    $x->where('SKPD_ID',$id);
                                })
                                ->where('BL_VALIDASI',1)
                                ->where('BL_DELETED',0)
                                ->where('BL_PAGU','!=',0)                                
                                ->groupBy('KEGIATAN_ID')
                                ->selectRaw('SUM("BL_PAGU") AS pagu, "KEGIATAN_ID"')
                                ->get();                                
            }
            $i++;
        }

        if($status == 'murni'){
            return View('budgeting.lampiran.rkpd',['tahun'=>$tahun,'status'=>$status,'skpd'=>$idSKPD,'pagu'=>$pagu,'program'=>$program,'i'=>0,'paguprogram'=>$paguprogram,'urusankode'=>'xxx','bidangkode'=>'xxx']);
        }else{
            return View('budgeting.lampiran.rkpd_perubahan',['tahun'=>$tahun,'status'=>$status,'skpd'=>$idSKPD,'pagu'=>$pagu,'program'=>$program,'i'=>0,'paguprogram'=>$paguprogram,'paguprogrammurni'=>$paguprogrammurni,'pagu_murni'=>$pagu_murni,'idm'=>0,'urusankode'=>'xxx','bidangkode'=>'xxx']);
        }

        


    }

    public function rkpdDownload($tahun,$status,$id){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RKPD')->value('TAHAPAN_ID');
        $idSKPD         = SKPD::where('SKPD_ID',$id)->first();
        if($status == 'murni') $stat    = BL::where('BL_TAHUN',$tahun);
        else $stat  = BLPerubahan::where('BL_TAHUN',$tahun);        
        $pagu           = $stat->whereHas('subunit',function($x) use ($id){
                                $x->where('SKPD_ID',$id);
                        })
                        ->where('BL_VALIDASI',1)
                        ->where('BL_DELETED',0)
                        ->where('BL_PAGU','!=',0)
                        ->get();
        $prog           = $stat->whereHas('subunit',function($q) use ($id){
                                $q->where('SKPD_ID',$id);
                        })
                        ->groupBy('KEGIATAN_ID')
                        ->select('KEGIATAN_ID')
                        ->where('BL_VALIDASI',1)
                        ->where('BL_DELETED',0)
                        ->where('BL_PAGU','!=',0)                        
                        ->get()->toArray();
        
        $program        = Program::whereHas('kegiatan',function($q) use($prog){
                                $q->whereIn('KEGIATAN_ID',$prog);
                            })
                            ->orderBy('URUSAN_ID')
                            ->orderBy('PROGRAM_KODE')
                            ->get();
        $paguprogram    = array();
        $i              = 0;
        foreach($program as $pr){
            if($status == 'murni') $stat    = BL::where('BL_TAHUN',$tahun);
            else $stat  = BLPerubahan::where('BL_TAHUN',$tahun);
            $idprog            = $pr->PROGRAM_ID;
            $paguprogram[$i]   = $stat->whereHas('kegiatan',function($q) use ($idprog){
                                    $q->where('PROGRAM_ID',$idprog);
                                })->whereHas('subunit',function($x) use ($id){
                                    $x->where('SKPD_ID',$id);
                                })
                                ->where('BL_VALIDASI',1)
                                ->where('BL_DELETED',0)
                                ->where('BL_PAGU','!=',0)                                
                                ->groupBy('KEGIATAN_ID')
                                ->selectRaw('SUM("BL_PAGU") AS pagu, "KEGIATAN_ID"')
                                ->get();
            $i++;
        }
        return View('budgeting.lampiran.rkpd_download',['tahun'=>$tahun,'status'=>$status,'skpd'=>$idSKPD,'pagu'=>$pagu,'program'=>$program,'i'=>0,'paguprogram'=>$paguprogram,'urusankode'=>'xxx','bidangkode'=>'xxx']);
    }

    public function ppas($tahun,$status,$id){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RKPD')->value('TAHAPAN_ID');
        $idSKPD         = SKPD::where('SKPD_ID',$id)->where('SKPD_TAHUN',$tahun)->first();

        if($status == 'murni'){ $stat    = BL::where('BL_TAHUN',$tahun);   }
        else {
            $stat        = BLPerubahan::where('BL_TAHUN',$tahun); 

            $pagu_murni  = BLPerubahan::whereHas('subunit',function($x) use ($id){
                                $x->where('SKPD_ID',$id);
                        })
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_VALIDASI',1)
                        ->where('BL_DELETED',0)
                        ->where('BL_PAGU','!=',0)                        
                        ->sum('BL_PAGU');
                    //dd($pagu_murni);    
        }          

        $pagu           = $stat->whereHas('subunit',function($x) use ($id){
                                $x->where('SKPD_ID',$id);
                        })
                        ->where('BL_VALIDASI',1)
                        ->where('BL_DELETED',0)
                        ->where('BL_PAGU','!=',0)                        
                        ->get();

        $prog           = $stat->whereHas('subunit',function($q) use ($id){
                                $q->where('SKPD_ID',$id);
                        })
                        ->groupBy('KEGIATAN_ID')
                        ->select('KEGIATAN_ID')
                        ->where('BL_VALIDASI',1)
                        ->where('BL_DELETED',0)
                        ->where('BL_PAGU','!=',0)                        
                        ->get()->toArray();
        
        $program        = Program::whereHas('kegiatan',function($q) use($prog){
                                $q->whereIn('KEGIATAN_ID',$prog);
                            })
                            ->orderBy('URUSAN_ID')
                            ->orderBy('PROGRAM_KODE')
                            ->get();

        $paguprogram    = array();
        $paguprogrammurni    = array();
        $i              = 0;

        foreach($program as $pr){
            if($status == 'murni'){ 
                $stat    = BL::where('BL_TAHUN',$tahun);
                $idprog            = $pr->PROGRAM_ID;
                $paguprogram[$i]   = $stat->whereHas('kegiatan',function($q) use ($idprog){
                                        $q->where('PROGRAM_ID',$idprog);
                                    })->whereHas('subunit',function($x) use ($id){
                                        $x->where('SKPD_ID',$id);
                                    })
                                    ->where('BL_DELETED',0)
                                    ->where('BL_PAGU','!=',0)
                                    ->groupBy('KEGIATAN_ID')
                                    ->selectRaw('SUM("BL_PAGU") AS pagu, "KEGIATAN_ID"')
                                    ->get();
            }
            else {
                $stat  = BLPerubahan::where('BL_TAHUN',$tahun);
                $idprog            = $pr->PROGRAM_ID;
                $paguprogram[$i]   = $stat->whereHas('kegiatan',function($q) use ($idprog){
                                        $q->where('PROGRAM_ID',$idprog);
                                    })->whereHas('subunit',function($x) use ($id){
                                        $x->where('SKPD_ID',$id);
                                    })
                                    ->where('BL_DELETED',0)
                                    ->where('BL_PAGU','!=',0)
                                    ->groupBy('KEGIATAN_ID')
                                    ->selectRaw('SUM("BL_PAGU") AS pagu, "KEGIATAN_ID"')
                                    ->get();
                $statmurni  = BL::where('BL_TAHUN',$tahun);
                $paguprogrammurni[$i]   = $statmurni->whereHas('kegiatan',function($q) use ($idprog){
                                        $q->where('PROGRAM_ID',$idprog);
                                    })->whereHas('subunit',function($x) use ($id){
                                        $x->where('SKPD_ID',$id);
                                    })
                                    ->where('BL_DELETED',0)
                                    ->where('BL_PAGU','!=',0)
                                    ->groupBy('KEGIATAN_ID')
                                    ->selectRaw('SUM("BL_PAGU") AS pagu, "KEGIATAN_ID"')
                                    ->get();
            }
            $i++;
        }

        if($status=='murni'){
            return View('budgeting.lampiran.ppas',['tahun'=>$tahun,'status'=>$status,'skpd'=>$idSKPD,'pagu'=>$pagu,'program'=>$program,'i'=>0,'paguprogram'=>$paguprogram,'urusankode'=>'xxx','bidangkode'=>'xxx']);
        }else{
            return View('budgeting.lampiran.ppas_perubahan',['tahun'=>$tahun,'status'=>$status,'skpd'=>$idSKPD,'pagu'=>$pagu,'program'=>$program,'i'=>0,'paguprogram'=>$paguprogram,'paguprogrammurni'=>$paguprogrammurni,'pagu_murni'=>$pagu_murni,'idm'=>0,'urusankode'=>'xxx','bidangkode'=>'xxx' ]);
        }
    }

    public function ppasRincian($tahun,$status,$id){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RKPD')->value('TAHAPAN_ID');
        $idSKPD         = SKPD::where('SKPD_ID',$id)->first();
        if($status == 'murni') $stat    = Rincian::whereHas('bl',function($bl) use($tahun){
                                                        $bl->where('BL_TAHUN',$tahun);
                                                    });
        else $stat  = RincianPerubahan::whereHas('bl',function($bl) use($tahun){
                                                        $bl->where('BL_TAHUN',$tahun);
                                                    });       

        if($status == 'murni') $stats    = BL::where('BL_TAHUN',$tahun);
        else $stats  = BLPerubahan::where('BL_TAHUN',$tahun);   
        $pagu           = $stat->whereHas('bl',function($bl) use ($id){
                                        $bl->whereHas('subunit',function($x) use ($id){
                                                $x->where('SKPD_ID',$id);
                                        })->where('BL_VALIDASI',1)
                                        ->where('BL_DELETED',0)
                                        ->where('BL_PAGU','!=',0);
                                    })->sum('RINCIAN_TOTAL');

        $prog           = $stats->whereHas('subunit',function($q) use ($id){
                                    $q->where('SKPD_ID',$id);
                                })
                                ->whereHas('rincian',function($rincian){
                                    $rincian->where('RINCIAN_TOTAL','!=',0);
                                })
                                ->groupBy('KEGIATAN_ID')
                                ->select('KEGIATAN_ID')
                                ->where('BL_DELETED',0)
                                ->where('BL_PAGU','!=',0)                        
                                ->get()->toArray();  


        $program        = Program::whereHas('kegiatan',function($q) use($prog){
                                $q->whereIn('KEGIATAN_ID',$prog);
                            })
                            ->orderBy('URUSAN_ID')
                            ->orderBy('PROGRAM_KODE')
                            ->get();


        $paguprogram    = array();
        $paguprogram_murni    = array();
        $i              = 0;
        $j              = 0;
        $k              = 0;
        $ppp_murni    = array();
        $pppp_murni    = array();
        foreach($program as $pr){
            if($status == 'murni') {
                $stat    = BL::where('BL_TAHUN',$tahun);
                $idprog            = $pr->PROGRAM_ID;

                $paguprogram[$i]   = $stat->whereHas('kegiatan',function($q) use ($idprog){
                                    $q->where('PROGRAM_ID',$idprog);
                                })->whereHas('subunit',function($x) use ($id){
                                    $x->where('SKPD_ID',$id);
                                })->whereHas('rincian',function($r){
                                    $r->where('RINCIAN_TOTAL','!=',0);
                                })
                                ->where('BL_DELETED',0)
                                ->where('BL_PAGU','!=',0)                                
                                ->groupBy('KEGIATAN_ID')
                                ->selectRaw('"KEGIATAN_ID"')
                                ->get();
            
                foreach($paguprogram[$i] as $r){
                    $idprog     = $pr->PROGRAM_ID;
                    $keg        = $r->KEGIATAN_ID;
                    $ppp[$i][$j]        = Rincian::whereHas('bl',function($bl) use($tahun,$id,$keg){
                                        $bl->where('BL_TAHUN',$tahun)->where('KEGIATAN_ID',$keg)
                                           ->whereHas('subunit',function($sub) use($id){
                                            $sub->where('SKPD_ID',$id);
                                        });
                                    })->sum('RINCIAN_TOTAL');

                    $pppp[$i] = Rincian::whereHas('bl',function($bl) use($tahun,$id,$idprog){
                                    $bl->where('BL_TAHUN',$tahun)
                                       ->where('BL_DELETED',0)
                                       ->where('BL_PAGU','!=',0)
                                       ->whereHas('kegiatan',function($keg) use($idprog){ $keg->where('PROGRAM_ID',$idprog); })
                                       ->whereHas('subunit',function($sub) use($id){ $sub->where('SKPD_ID',$id); });
                                })
                                ->sum('RINCIAN_TOTAL');

                    $j++;
                }
            }
            else {
                $stat  = BLPerubahan::where('BL_TAHUN',$tahun); 
                $stat_murni  = BL::where('BL_TAHUN',$tahun); 

                $idprog            = $pr->PROGRAM_ID;

                $paguprogram[$i]   = $stat->whereHas('kegiatan',function($q) use ($idprog){
                                    $q->where('PROGRAM_ID',$idprog);
                                })->whereHas('subunit',function($x) use ($id){
                                    $x->where('SKPD_ID',$id);
                                })->whereHas('rincian',function($r){
                                    $r->where('RINCIAN_TOTAL','!=',0);
                                })
                                ->where('BL_DELETED',0)
                                ->where('BL_PAGU','!=',0)                                
                                ->groupBy('KEGIATAN_ID')
                                ->selectRaw('"KEGIATAN_ID"')
                                ->get();
            
                foreach($paguprogram[$i] as $r){
                    $idprog     = $pr->PROGRAM_ID;
                    $keg        = $r->KEGIATAN_ID;
                    $ppp[$i][$j]        = Rincian::whereHas('bl',function($bl) use($tahun,$id,$keg){
                                        $bl->where('BL_TAHUN',$tahun)->where('KEGIATAN_ID',$keg)
                                           ->whereHas('subunit',function($sub) use($id){
                                            $sub->where('SKPD_ID',$id);
                                        });
                                    })->sum('RINCIAN_TOTAL');

                    $pppp[$i] = Rincian::whereHas('bl',function($bl) use($tahun,$id,$idprog){
                                    $bl->where('BL_TAHUN',$tahun)
                                       ->where('BL_DELETED',0)
                                       ->where('BL_PAGU','!=',0)
                                       ->whereHas('kegiatan',function($keg) use($idprog){ $keg->where('PROGRAM_ID',$idprog); })
                                       ->whereHas('subunit',function($sub) use($id){ $sub->where('SKPD_ID',$id); });
                                })
                                ->sum('RINCIAN_TOTAL');

                    $j++;
                }

                $idprog            = $pr->PROGRAM_ID;

                $paguprogram_murni[$i]   = $stat_murni->whereHas('kegiatan',function($q) use ($idprog){
                                    $q->where('PROGRAM_ID',$idprog);
                                })->whereHas('subunit',function($x) use ($id){
                                    $x->where('SKPD_ID',$id);
                                })->whereHas('rincian',function($r){
                                    $r->where('RINCIAN_TOTAL','!=',0);
                                })
                                ->where('BL_DELETED',0)
                                ->where('BL_PAGU','!=',0)                                
                                ->groupBy('KEGIATAN_ID')
                                ->selectRaw('"KEGIATAN_ID"')
                                ->get();
            
                foreach($paguprogram_murni[$i] as $r){
                    $idprog     = $pr->PROGRAM_ID;
                    $keg        = $r->KEGIATAN_ID;
                    $ppp_murni[$i][$k]        = Rincian::whereHas('bl',function($bl) use($tahun,$id,$keg){
                                        $bl->where('BL_TAHUN',$tahun)->where('KEGIATAN_ID',$keg)
                                           ->whereHas('subunit',function($sub) use($id){
                                            $sub->where('SKPD_ID',$id);
                                        });
                                    })->sum('RINCIAN_TOTAL');

                    $pppp_murni[$i] = Rincian::whereHas('bl',function($bl) use($tahun,$id,$idprog){
                                    $bl->where('BL_TAHUN',$tahun)
                                       ->where('BL_DELETED',0)
                                       ->where('BL_PAGU','!=',0)
                                       ->whereHas('kegiatan',function($keg) use($idprog){ $keg->where('PROGRAM_ID',$idprog); })
                                       ->whereHas('subunit',function($sub) use($id){ $sub->where('SKPD_ID',$id); });
                                })
                                ->sum('RINCIAN_TOTAL');

                    $k++;
                }
            } 
            
            $i++;
        }

        //dd($idprog);
        //dd($pppp);

        if($status == 'murni'){
            return View('budgeting.lampiran.ppas_rincian',['tahun'=>$tahun,'status'=>$status,'skpd'=>$idSKPD,'pagu'=>$pagu,'program'=>$program,'i'=>0,'paguprogram'=>$paguprogram,'urusankode'=>'xxx','bidangkode'=>'xxx','ppp'=>$ppp,'pppp'=>$pppp,'j'=>0]);
        }else{    
            return View('budgeting.lampiran.ppas_rincian_perubahan',['tahun'=>$tahun,'status'=>$status,'skpd'=>$idSKPD,'pagu'=>$pagu,'program'=>$program,'i'=>0,'paguprogram'=>$paguprogram,'urusankode'=>'xxx','bidangkode'=>'xxx','ppp'=>$ppp,'pppp'=>$pppp,'j'=>0 ,'paguprogrammurni'=>$paguprogram_murni, 'ppp_murni'=>$ppp_murni,'k'=>0,'pppp_murni'=>$pppp_murni ]);
        }

    }

    public function ppasDownload($tahun,$status,$id){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RKPD')->value('TAHAPAN_ID');
        $idSKPD         = SKPD::where('SKPD_ID',$id)->first();
        if($status == 'murni') $stat    = BL::where('BL_TAHUN',$tahun);
        else {
            $stat  = BLPerubahan::where('BL_TAHUN',$tahun);
            $pagu_murni  = BL::whereHas('subunit',function($x) use ($id){
                                $x->where('SKPD_ID',$id);
                        })
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_VALIDASI',1)
                        ->where('BL_DELETED',0)
                        ->where('BL_PAGU','!=',0)                        
                        ->get(); 
        }       
        $pagu           = $stat->whereHas('subunit',function($x) use ($id){
                                $x->where('SKPD_ID',$id);
                        })
                        ->where('BL_VALIDASI',1)
                        ->where('BL_DELETED',0)
                        ->where('BL_PAGU','!=',0)                        
                        ->get();
        $prog           = $stat->whereHas('subunit',function($q) use ($id){
                                $q->where('SKPD_ID',$id);
                        })
                        ->groupBy('KEGIATAN_ID')
                        ->select('KEGIATAN_ID')
                        ->where('BL_VALIDASI',1)
                        ->where('BL_DELETED',0)
                        ->where('BL_PAGU','!=',0)                        
                        ->get()->toArray();
        
        $program        = Program::whereHas('kegiatan',function($q) use($prog){
                                $q->whereIn('KEGIATAN_ID',$prog);
                            })
                            ->orderBy('URUSAN_ID')
                            ->orderBy('PROGRAM_KODE')
                            ->get();
        $paguprogram    = array();
        $paguprogrammurni    = array();
        $i              = 0;
        foreach($program as $pr){
            if($status == 'murni') $stat    = BL::where('BL_TAHUN',$tahun);
            else {
                $stat  = BLPerubahan::where('BL_TAHUN',$tahun);
                $idprog            = $pr->PROGRAM_ID;
                $statmurni  = BL::where('BL_TAHUN',$tahun);
                $paguprogrammurni[$i]   = $statmurni->whereHas('kegiatan',function($q) use ($idprog){
                                        $q->where('PROGRAM_ID',$idprog);
                                    })->whereHas('subunit',function($x) use ($id){
                                        $x->where('SKPD_ID',$id);
                                    })
                                    ->where('BL_DELETED',0)
                                    ->where('BL_PAGU','!=',0)
                                    ->groupBy('KEGIATAN_ID')
                                    ->selectRaw('SUM("BL_PAGU") AS pagu, "KEGIATAN_ID"')
                                    ->get();
            }
            $idprog            = $pr->PROGRAM_ID;
            $paguprogram[$i]   = $stat->whereHas('kegiatan',function($q) use ($idprog){
                                    $q->where('PROGRAM_ID',$idprog);
                                })->whereHas('subunit',function($x) use ($id){
                                    $x->where('SKPD_ID',$id);
                                })
                                ->where('BL_VALIDASI',1)
                                ->where('BL_DELETED',0)
                                ->where('BL_PAGU','!=',0)                                
                                ->groupBy('KEGIATAN_ID')
                                ->selectRaw('SUM("BL_PAGU") AS pagu, "KEGIATAN_ID"')
                                ->get();
            $i++;
        }

        if($status == 'murni'){
            return View('budgeting.lampiran.ppas_download',['tahun'=>$tahun,'status'=>$status,'skpd'=>$idSKPD,'pagu'=>$pagu,'program'=>$program,'i'=>0,'paguprogram'=>$paguprogram,'urusankode'=>'xxx','bidangkode'=>'xxx']);
        }else{
            return View('budgeting.lampiran.ppas_download_perubahan',['tahun'=>$tahun,'status'=>$status,'skpd'=>$idSKPD,'pagu'=>$pagu,'pagu_murni'=>$pagu_murni,'program'=>$program,'i'=>0,'paguprogram'=>$paguprogram,'paguprogrammurni'=>$paguprogrammurni,'urusankode'=>'xxx','bidangkode'=>'xxx']);
        }
    }

    public function lampiran1($tahun,$status){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD')->value('TAHAPAN_ID');
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        /*$pendapatan     = Pendapatan::whereHas('rekening',function($q){
                                $q->where('REKENING_KODE','like','4%');
                            })->sum('PENDAPATAN_TOTAL');*/
        $pendapatan     = Pendapatan::where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');                

        $pad            = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.1%');})->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pad1           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.1.1%');})->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pad2           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.1.2%');})->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pad3           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.1.3%');})->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pad4           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.1.4%');})->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        
        $ibg            = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.2%');})->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $ibg1           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.2.1%');})->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $ibg2           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.2.2%');})->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $ibg3           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.2.3%');})->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        
        $pdl            = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.3%');})->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pdl1           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.3.1%');})->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pdl2           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.3.3%');})->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');


        $btl            = BTL::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1%');})->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        //dd($btl);
        $btl1           = BTL::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.1%');})->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        $btl2           = BTL::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.3%');})->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        $btl3           = BTL::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.4%');})->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        $btl4           = BTL::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.7%');})->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        $btl5           = BTL::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.8%');})->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        
        $bl     = Rincian::whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN','2018');
                        })
                        ->sum('RINCIAN_TOTAL');

            $bl1     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                        ->whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN','2018');
                        })
                        ->sum('RINCIAN_TOTAL');
            $bl2     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                        ->whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN','2018');
                        })
                        ->sum('RINCIAN_TOTAL');
            $bl3     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                        ->whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN','2018');
                        })
                        ->sum('RINCIAN_TOTAL'); 

                        //dd($bl2);


        $pmb1           = Pembiayaan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','6.1%');})->where('PEMBIAYAAN_TAHUN',$tahun)->sum('PEMBIAYAAN_TOTAL');         
        $pmb2           = Pembiayaan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','6.2%');})->where('PEMBIAYAAN_TAHUN',$tahun)->sum('PEMBIAYAAN_TOTAL');         
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn,
                            'pendapatan'    =>$pendapatan,
                            'pad'           =>$pad,
                            'pad1'          =>$pad1,
                            'pad2'          =>$pad2,
                            'pad3'          =>$pad3,
                            'pad4'          =>$pad4,
                            'ibg'           =>$ibg,
                            'ibg1'          =>$ibg1,
                            'ibg2'          =>$ibg2,
                            'ibg3'          =>$ibg3,
                            'pdl'           =>$pdl,
                            'pdl1'          =>$pdl1,
                            'pdl2'          =>$pdl2,
                            'bl'            =>$bl,
                            'bl1'           =>$bl1,
                            'bl2'           =>$bl2,
                            'bl3'           =>$bl3,
                            'btl'           =>$btl,        
                            'btl1'          =>$btl1,        
                            'btl2'          =>$btl2,        
                            'btl3'          =>$btl3,        
                            'btl4'          =>$btl4,        
                            'btl5'          =>$btl5,        
                            'pmb1'          =>$pmb1,        
                            'pmb2'          =>$pmb2,        
                            );
        return View('budgeting.lampiran.apbd1',$data);

    }

   /* public function lampiran2($tahun,$status){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD')->value('TAHAPAN_ID');
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        $kat1       = UrusanKategori1::where('URUSAN_KAT1_TAHUN',$tahun)->orderBy('URUSAN_KAT1_KODE')->get();

        $urusan     = Urusan::where('URUSAN_TAHUN',$tahun)->orderBy('URUSAN_KODE')->get();

        $bl         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                        ->JOIN('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
                        ->JOIN('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('URUSAN_TAHUN',$tahun)
                        ->groupBy('SKPD_NAMA',"SKPD_KODE","URUSAN_KODE")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"URUSAN_KODE","SKPD_KODE", "SKPD_NAMA", SUM("BL_PAGU") AS pagu')
                        ->get();    

        $btl       = BTL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BTL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->groupBy('SKPD_NAMA',"SKPD_KODE")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", SUM("BTL_TOTAL") AS pagu ')
                        ->get();

        $pendapatan = Pendapatan::JOIN('REFERENSI.REF_SUB_UNIT','DAT_PENDAPATAN.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->groupBy('SKPD_NAMA',"SKPD_KODE")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", SUM("PENDAPATAN_TOTAL") AS pagu')
                        ->get(); 

        //dd($pendapatan);
        
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn,        
                            'kat1'          =>$kat1,        
                            'urusan'        =>$urusan,        
                            'bl'            =>$bl,        
                            'btl'           =>$btl,        
                            'pendapatan'    =>$pendapatan,        
                            );
        return View('budgeting.lampiran.apbd2',$data);

    }*/

    public function lampiran2($tahun,$status){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD')->value('TAHAPAN_ID');
        $tahapan=0;
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        $tabel=array();
        $idx=0;

        $total_pendapatan_murni=0;
        $total_btl_murni=0;
        $total_bl_murni=0;

        $detil = DB::table('BUDGETING.RKP_LAMP_2 as lampiran')
                 ->where('lampiran.TAHUN',$tahun)
                 ->where('lampiran.TAHAPAN_ID',$tahapan)
                 ->select('lampiran.TAHUN',
                          'lampiran.URUSAN_KAT1_KODE',
                          'lampiran.URUSAN_KAT1_NAMA',
                          'lampiran.URUSAN_KODE',
                          'lampiran.URUSAN_NAMA',
                          'lampiran.SKPD_KODE',
                          'lampiran.SKPD_NAMA',
                          'lampiran.PENDAPATAN_MURNI',
                          'lampiran.BTL_MURNI',
                          'lampiran.BL_MURNI',
                          DB::raw("'f' as urusan_ok"),
                          DB::raw("'f' as kode_urusan_ok"),
                          DB::raw("'f' as kode_unit_ok"),
                          DB::raw("0 as subtotal1_pendapatan_murni"),
                          DB::raw("0 as subtotal1_btl_murni"),
                          DB::raw("0 as subtotal1_bl_murni"),
                          DB::raw("0 as subtotal_pendapatan_murni"),
                          DB::raw("0 as subtotal_btl_murni"),
                          DB::raw("0 as subtotal_bl_murni"))
                 ->orderBy('lampiran.URUSAN_KODE','asc')
                 ->orderBy('lampiran.SKPD_ID','asc')
                 ->orderBy('lampiran.SKPD_KODE','asc')
                 ->get();
        //print_r($detil);exit;
        $old_urusan="";
        $old_kode_urusan="";
        $old_kode_unit="";
        for($i=0;$i<count($detil);$i++){
            $rs=$detil[$i];
            if($rs->SKPD_KODE!=$old_kode_unit){
                $rs->kode_unit_ok='t';
                $old_kode_unit=$rs->SKPD_KODE;
                $total_pendapatan_murni+=$rs->PENDAPATAN_MURNI;
                $total_btl_murni+=$rs->BTL_MURNI;
                $total_bl_murni+=$rs->BL_MURNI;
            }
            /*if(substr($rs->URUSAN_KODE,1,1)!=$old_urusan){
                $rs->urusan_ok='t';
                $old_urusan=substr($rs->URUSAN_KODE,1,1);
            }*/
            if($rs->URUSAN_KAT1_NAMA!=$old_urusan){
                $rs->urusan_ok='t';
                $old_urusan=$rs->URUSAN_KAT1_NAMA;
                $rekap = DB::table('BUDGETING.RKP_LAMP_2 as rekap')
                         ->where('rekap.TAHUN',$tahun)
                         ->where('rekap.TAHAPAN_ID',$tahapan)
                         ->where('rekap.URUSAN_KAT1_KODE',$rs->URUSAN_KAT1_KODE)
                         ->select(DB::raw('sum("PENDAPATAN_MURNI") as pendapatanmurni'),
                                  DB::raw('sum("BTL_MURNI") as btlmurni'),
                                  DB::raw('sum("BL_MURNI") as blmurni'))
                         ->get();
                //print_r($rekap);exit;
                $rs->subtotal1_pendapatan_murni=$rekap[0]->pendapatanmurni;
                $rs->subtotal1_btl_murni=$rekap[0]->btlmurni;
                $rs->subtotal1_bl_murni=$rekap[0]->blmurni;
            }
            if($rs->URUSAN_KODE!=$old_kode_urusan){
                $rs->kode_urusan_ok='t';
                $old_kode_urusan=$rs->URUSAN_KODE;
                $rekap = DB::table('BUDGETING.RKP_LAMP_2 as rekap')
                         ->where('rekap.TAHUN',$tahun)
                         ->where('rekap.TAHAPAN_ID',$tahapan)
                         ->where('rekap.URUSAN_KODE',$rs->URUSAN_KODE)
                         ->select(DB::raw('sum("PENDAPATAN_MURNI") as pendapatanmurni'),
                                  DB::raw('sum("BTL_MURNI") as btlmurni'),
                                  DB::raw('sum("BL_MURNI") as blmurni'))
                         ->get();
                //print_r($rekap);exit;
                $rs->subtotal_pendapatan_murni=$rekap[0]->pendapatanmurni;
                $rs->subtotal_btl_murni=$rekap[0]->btlmurni;
                $rs->subtotal_bl_murni=$rekap[0]->blmurni;
            }
            array_push($tabel,$rs);
        }
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn,
                            'detil'         =>$tabel,
                            'totalpendapatanmurni'=>$total_pendapatan_murni,
                            'totalbtlmurni'=>$total_btl_murni,
                            'totalblmurni'=>$total_bl_murni
                            );
        return View('budgeting.lampiran.apbd2',$data);

        /*
        *========================== KODING LAMA
        $kat1       = UrusanKategori1::where('URUSAN_KAT1_TAHUN',$tahun)->orderBy('URUSAN_KAT1_KODE')->get();

        $urusan     = Urusan::where('URUSAN_TAHUN',$tahun)->orderBy('URUSAN_KODE')->get();

        $bl         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                        ->JOIN('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
                        ->JOIN('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('URUSAN_TAHUN',$tahun)
                        ->groupBy('SKPD_NAMA',"SKPD_KODE","URUSAN_KODE")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"URUSAN_KODE","SKPD_KODE", "SKPD_NAMA", SUM("BL_PAGU") AS pagu')
                        ->get();    

        $btl       = BTL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BTL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->groupBy('SKPD_NAMA',"SKPD_KODE")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", SUM("BTL_TOTAL") AS pagu ')
                        ->get();

        $pendapatan = Pendapatan::JOIN('REFERENSI.REF_SUB_UNIT','DAT_PENDAPATAN.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->groupBy('SKPD_NAMA',"SKPD_KODE")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", SUM("PENDAPATAN_TOTAL") AS pagu')
                        ->get(); 

        //dd($pendapatan);
        
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn,        
                            'kat1'          =>$kat1,        
                            'urusan'        =>$urusan,        
                            'bl'            =>$bl,        
                            'btl'           =>$btl,        
                            'pendapatan'    =>$pendapatan,        
                            );
        return View('budgeting.lampiran.apbd2',$data);
        */

    }

   /* public function lampiran3($tahun,$status){
        $tipe = 'Lampiran'; 
        if(Auth::user()->level == 2) $skpd = SKPD::where('SKPD_ID',UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID'))->get();
        else  $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        if($tipe == 'apbd') return View('budgeting.lampiran.indexAPBD',$data);
        else return View('budgeting.lampiran.apbd3_index',$data);
    }*/

    /*public function lampiran3Detail($tahun,$status,$id){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD')->value('TAHAPAN_ID');
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        $skpd       = SKPD::where('SKPD_ID',$id)->first();

        $urusan     = Urusan::JOIN('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                            ->JOIN('REFERENSI.REF_URUSAN_KATEGORI1','REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID','=','REF_URUSAN.URUSAN_KAT1_ID')
                            ->where('REF_URUSAN_SKPD.SKPD_ID',$id)
                            ->first();
                            //dd($urusan);

       $bl_prog         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->JOIN('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->groupBy("SKPD_KODE", "SKPD_NAMA", "REF_PROGRAM.PROGRAM_ID", "PROGRAM_KODE", "PROGRAM_NAMA")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", "REF_PROGRAM"."PROGRAM_ID", "PROGRAM_KODE", "PROGRAM_NAMA" ')
                        ->get(); 


        $bl_keg         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", "PROGRAM_ID", "REF_KEGIATAN"."KEGIATAN_ID", "KEGIATAN_KODE", "KEGIATAN_NAMA", "BL_PAGU" ')
                        ->get(); 
                                        

        $bl_rek         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('BUDGETING.DAT_RINCIAN','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->groupBy("SKPD_KODE", "SKPD_NAMA", "DAT_BL.KEGIATAN_ID", "REKENING_KODE", "REKENING_NAMA")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", "DAT_BL"."KEGIATAN_ID", "REKENING_KODE", "REKENING_NAMA", SUM("RINCIAN_TOTAL") AS pagu')
                        ->get();                 



        $btl       = BTL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BTL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->where('REKENING_KODE','like','5.1.1%')
                        ->selectRaw('sum("BTL_TOTAL") as pagu ')
                        ->get(); 
                        
        
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn,        
                            'skpd'          =>$skpd,        
                            'urusan'        =>$urusan,        
                            'bl_prog'       =>$bl_prog,        
                            'bl_keg'       =>$bl_keg,        
                            'bl_rek'       =>$bl_rek,        
                            'btl'           =>$btl,        
                            );

        return View('budgeting.lampiran.apbd3',$data);
    }*/

    public function lampiran3Skpd($tahun,$status){
        $tipe = 'Lampiran'; 
        if(Auth::user()->level == 2) $skpd = SKPD::where('SKPD_ID',UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID'))->get();
        else  $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        if($tipe == 'apbd') return View('budgeting.lampiran.indexAPBD',$data);
        else return View('budgeting.lampiran.apbd3_index',$data);
    }

     public function lampiran3Detail($tahun,$status,$id){
        
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD')->value('TAHAPAN_ID');
        $tahapan=0;
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        $skpd       = SKPD::where('SKPD_ID',$id)->first();
        $urusan     = Urusan::JOIN('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->JOIN('REFERENSI.REF_URUSAN_KATEGORI1','REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID','=','REF_URUSAN.URUSAN_KAT1_ID')
                        ->where('REF_URUSAN_SKPD.SKPD_ID',$id)
                        ->first();
                        //dd($urusan);
        $tabel=array();
        $idx=0;

        $total_pendapatan=0;
        $total_belanja=0;
        $total_penerimaan=0;
        $total_pengeluaran=0;
        $surplus=0;
        $netto=0;

        $detil = DB::table('REFERENSI.REF_SKPD as skpd')
                 ->leftJoin('REFERENSI.REF_URUSAN_SKPD as us',function($join){
                    $join->on('us.SKPD_ID','=','skpd.SKPD_ID');
                 })
                 ->leftJoin('REFERENSI.REF_URUSAN as urusan',function($join){
                    $join->on('urusan.URUSAN_ID','=','us.URUSAN_ID')
                         ->on('urusan.URUSAN_TAHUN','=','skpd.SKPD_TAHUN');
                 })
                 ->where('skpd.SKPD_TAHUN',$tahun)
                 ->where('skpd.SKPD_ID',$id)
                 ->select('skpd.SKPD_KODE','skpd.SKPD_NAMA','urusan.URUSAN_KODE','urusan.URUSAN_NAMA')
                 ->get();

        if($status=="murni"){
            //return $this->ringkasmurni($tahun,$app,$id);
            $pendapatan=DB::table('REFERENSI.REF_REKENING as rk')
                        ->where('rk.REKENING_TAHUN',$tahun)
                        ->where('rk.REKENING_KUNCI','0')
                        ->where('rk.REKENING_KODE','LIKE','4%');
            $pendapatan = $pendapatan->where(DB::raw('length(rk."REKENING_KODE")'),'<=',5);
            $pendapatan=$pendapatan->leftJoin('BUDGETING.DAT_PENDAPATAN as dp',function($join) use ($tahun,$id){
                            $join->on('dp.PENDAPATAN_TAHUN','=','rk.REKENING_TAHUN')
                                 ->where('dp.SKPD_ID',$id)
                                 ->whereIn('dp.REKENING_ID',function($join) use ($tahun){
                                    $join->select(DB::raw(' "REKENING_ID" FROM "REFERENSI"."REF_REKENING" WHERE "REKENING_TAHUN"='.$tahun.' AND "REKENING_KODE" LIKE rk."REKENING_KODE" '."||'%'"));
                                 });
                        })
                        ->select(DB::raw('1||SUBSTRING(rk."REKENING_KODE",2,4) as koderekening'),
                                 'rk.REKENING_KODE as koderek',
                                 'rk.REKENING_NAMA as namarek',
                                 DB::raw('SUM(dp."PENDAPATAN_TOTAL") as nilai')
                                 )
                        ->groupBy('rk.REKENING_KODE','rk.REKENING_NAMA')
                        ->having(DB::raw('SUM(dp."PENDAPATAN_TOTAL")'),'<>','0')
                        ->orderBy('rk.REKENING_KODE')
                        ->get();
            //print_r($pendapatan);exit;
            foreach($pendapatan as $pd){
                if(strlen($pd->koderek)==1){
                    $total_pendapatan+=$pd->nilai;
                    $tabel[$idx]['tingkat']=1;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    //$getakun=explode(".",$pd->koderek);
                    $tabel[$idx]['akun1']=$pd->koderek;
                    $tabel[$idx]['akun2']="";
                    $tabel[$idx]['akun3']="";
                    $tabel[$idx]['akun4']="";
                    $tabel[$idx]['akun5']="";
                    $tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$pd->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($pd->namarek));
                    $tabel[$idx]['totalrekening']=$pd->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
                elseif(strlen($pd->koderek)==3){
                    $tabel[$idx]['tingkat']=2;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    $getakun=explode(".",$pd->koderek);
                    $tabel[$idx]['akun1']=$getakun[0];
                    $tabel[$idx]['akun2']=$getakun[1];
                    $tabel[$idx]['akun3']="";
                    $tabel[$idx]['akun4']="";
                    $tabel[$idx]['akun5']="";
                    $tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$pd->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($pd->namarek));
                    $tabel[$idx]['totalrekening']=$pd->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
                elseif(strlen($pd->koderek)>3 and strlen($pd->koderek)<=8){
                    $tabel[$idx]['tingkat']=3;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    $getakun=explode(".",$pd->koderek);
                    $tabel[$idx]['akun1']=$getakun[0];
                    $tabel[$idx]['akun2']=$getakun[1];
                    (strlen($pd->koderek)>3 and strlen($pd->koderek)<=8)?$tabel[$idx]['akun3']=$getakun[2]:$tabel[$idx]['akun3']="";
                    (strlen($pd->koderek)>5 and strlen($pd->koderek)<=8)?$tabel[$idx]['akun4']=$getakun[3]:$tabel[$idx]['akun4']="";
                    $tabel[$idx]['akun5']="";
                    $tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$pd->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($pd->namarek));
                    $tabel[$idx]['totalrekening']=$pd->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
                else{
                    $tabel[$idx]['tingkat']=4;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    $getakun=explode(".",$pd->koderek);
                    $tabel[$idx]['akun1']=$getakun[0];
                    $tabel[$idx]['akun2']=$getakun[1];
                    $tabel[$idx]['akun3']=$getakun[2];
                    $tabel[$idx]['akun4']=$getakun[3];
                    (!empty($getakun[4]))?$tabel[$idx]['akun5']=$getakun[4]:$tabel[$idx]['akun5']="";
                    (strlen($pd->koderek)>11)?$tabel[$idx]['akun6']=$getakun[5]:$tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$pd->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($pd->namarek));
                    $tabel[$idx]['totalrekening']=$pd->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
            }
            $tabel[$idx]['tingkat']=5;
            $tabel[$idx]['koderekening']=NULL;
            $tabel[$idx]['namarekening']=NULL;
            $tabel[$idx]['totalrekeningmurni']=NULL;
            $tabel[$idx]['totalrekening']=NULL;
            $tabel[$idx]['namajumlah']="Jumlah Pendapatan";
            $tabel[$idx]['totaljumlah']=$total_pendapatan;
            $idx+=1;
            //print_r($tabel);exit;//CEK PENDAPATAN
            
            $btl=DB::table('REFERENSI.REF_REKENING as rk')
                        ->where('rk.REKENING_TAHUN',$tahun)
                        ->where('rk.REKENING_KUNCI','0')
                        ->where('rk.REKENING_KODE','LIKE','5%');
            $btl = $btl->where(DB::raw('length(rk."REKENING_KODE")'),'<=',5);
            $btl=$btl->whereNotIn('rk.REKENING_KODE',['5.2','5.2.1','5.2.2','5.2.3'])
                  ->leftJoin('BUDGETING.DAT_BTL as dp',function($join) use ($tahun,$id){
                      $join->on('dp.BTL_TAHUN','=','rk.REKENING_TAHUN')
                            ->where('dp.SKPD_ID',$id)
                                 ->whereIn('dp.REKENING_ID',function($join) use ($tahun){
                                    $join->select(DB::raw(' "REKENING_ID" FROM "REFERENSI"."REF_REKENING" WHERE "REKENING_TAHUN"='.$tahun.' AND "REKENING_KODE" LIKE rk."REKENING_KODE" '."||'%'"));
                                 });
                  })
                  ->select(DB::raw('2||SUBSTRING(rk."REKENING_KODE",2,4) as koderekening'),
                           'rk.REKENING_KODE as koderek',
                           'rk.REKENING_NAMA as namarek',
                           'rk.REKENING_ID as idrek',
                           DB::raw('SUM(dp."BTL_TOTAL") as nilai'))
                  ->groupBy('rk.REKENING_ID','rk.REKENING_KODE','rk.REKENING_NAMA')
                  ->having(DB::raw('SUM(dp."BTL_TOTAL")'),'<>','0')
                  ->orderBy('rk.REKENING_KODE')
                  ->get();
            //print_r($btl);exit;
            foreach($btl as $bt){
                if(strlen($bt->koderek)==1){
                    $tabel[$idx]['tingkat']=1;
                    $total_belanja+=$bt->nilai;
                    $tabel[$idx]['tingkat']=1;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    //$getakun=explode(".",$pd->koderek);
                    $tabel[$idx]['akun1']=$bt->koderek;
                    $tabel[$idx]['akun2']="";
                    $tabel[$idx]['akun3']="";
                    $tabel[$idx]['akun4']="";
                    $tabel[$idx]['akun5']="";
                    $tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
                elseif(strlen($bt->koderek)==3){
                    $tabel[$idx]['tingkat']=2;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    $getakun=explode(".",$bt->koderek);
                    $tabel[$idx]['akun1']=$getakun[0];
                    $tabel[$idx]['akun2']=$getakun[1];
                    $tabel[$idx]['akun3']="";
                    $tabel[$idx]['akun4']="";
                    $tabel[$idx]['akun5']="";
                    $tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
                elseif(strlen($bt->koderek)>3 and strlen($bt->koderek)<=8){
                    $tabel[$idx]['tingkat']=3;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    $getakun=explode(".",$bt->koderek);
                    $tabel[$idx]['akun1']=$getakun[0];
                    $tabel[$idx]['akun2']=$getakun[1];
                    (strlen($bt->koderek)>3 and strlen($bt->koderek)<=8)?$tabel[$idx]['akun3']=$getakun[2]:$tabel[$idx]['akun3']="";
                    (strlen($bt->koderek)>5 and strlen($bt->koderek)<=8)?$tabel[$idx]['akun4']=$getakun[3]:$tabel[$idx]['akun4']="";
                    $tabel[$idx]['akun5']="";
                    $tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
                else{
                    $tabel[$idx]['tingkat']=4;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    $getakun=explode(".",$bt->koderek);
                    $tabel[$idx]['akun1']=$getakun[0];
                    $tabel[$idx]['akun2']=$getakun[1];
                    $tabel[$idx]['akun3']=$getakun[2];
                    $tabel[$idx]['akun4']=$getakun[3];
                    $tabel[$idx]['akun5']=$getakun[4];
                    (strlen($bt->koderek)>11)?$tabel[$idx]['akun6']=$getakun[5]:$tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                    /**
                    *============ INI UNTUK MENAMPILKAN NILAI BTL PPKD
                    if($id=''){
                        if(substr($bt->koderek,0,5)=="5.1.3" or substr($bt->koderek,0,5)=="5.1.4" or substr($bt->koderek,0,5)=="5.1.5" or substr($bt->koderek,0,5)=="5.1.7"){
                            $detil2 = DB::table('BUDGETING.DAT_BTL as dp')
                                    ->where('dp.BTL_TAHUN',$tahun)
                                    ->where('dp.SKPD_ID',$id)
                                    ->where('dp.REKENING_ID',$bt->idrek)
                                    ->select('dp.namakomponen','dp.subkomponen','dp.keterangankomponen','dp.hargakomponen as nilai')
                                    ->get();
                            foreach($detil2 as $res2){
                                $tabel[$idx]['tingkat']=4;
                                $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                                $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                                $tabel[$idx]['kodeprogram']="00";
                                $tabel[$idx]['nogiat']="000";
                                $getakun=explode(".",$bt->koderek);
                                $tabel[$idx]['akun1']=$getakun[0];
                                $tabel[$idx]['akun2']=$getakun[1];
                                $tabel[$idx]['akun3']=$getakun[2];
                                $tabel[$idx]['akun4']=$getakun[3];
                                $tabel[$idx]['akun5']=$getakun[4];
                                (strlen($bt->koderek)>11)?$tabel[$idx]['akun6']=$getakun[5]:$tabel[$idx]['akun6']="";
                                $tabel[$idx]['koderekening']=$bt->koderek;
                                //$tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                                $tabel[$idx]['namarekening']=$res2->namakomponen. " ( ".$res2->keterangankomponen." )";
                                $tabel[$idx]['totalrekening']=$res2->nilai;
                                $tabel[$idx]['namajumlah']=NULL;
                                $tabel[$idx]['totaljumlah']=NULL;
                                $idx+=1;
                            }
                        }
                    }
                    */
                }
                //print_r($getakun);exit;
            }

            $bl=DB::table('REFERENSI.REF_REKENING as rk')
                        ->where('rk.REKENING_TAHUN',$tahun)
                        ->where('rk.REKENING_KUNCI','0')
                        ->where('rk.REKENING_KODE','=','5.2');
            $bl = $bl->select(DB::raw('2||SUBSTRING(rk."REKENING_KODE",2,4) as koderekening'),
                            'rk.REKENING_KODE as koderek',
                            'rk.REKENING_NAMA as namarek',
                            DB::raw('(select sum(rd."RINCIAN_TOTAL") from "BUDGETING"."DAT_RINCIAN" as rd where rd."BL_ID" IN (select bl."BL_ID" from "BUDGETING"."DAT_BL" as bl where bl."BL_TAHUN"=rk."REKENING_TAHUN" and bl."BL_DELETED"=0 and bl."SKPD_ID"='.$id.') and rd."REKENING_ID" in (select r."REKENING_ID" from "REFERENSI"."REF_REKENING" as r where r."REKENING_TAHUN"=rk."REKENING_TAHUN" and r."REKENING_KODE" like rk."REKENING_KODE"'."||'%'".')) as nilai')
                        )
                    ->groupBy('rk.REKENING_TAHUN','rk.REKENING_KODE','rk.REKENING_NAMA')
                    ->orderBy('rk.REKENING_KODE')
                    ->get();
            //print_r($bl);exit;
            $tabel[$idx]['tingkat']=2;
            $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
            $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
            $tabel[$idx]['kodeprogram']="00";
            $tabel[$idx]['nogiat']="000";
            $tabel[$idx]['akun1']="5";
            $tabel[$idx]['akun2']="2";
            $tabel[$idx]['akun3']="";
            $tabel[$idx]['akun4']="";
            $tabel[$idx]['akun5']="";
            $tabel[$idx]['akun6']="";
            $tabel[$idx]['koderekening']="5.2";
            $tabel[$idx]['namarekening']="Belanja Langsung";
            $tabel[$idx]['totalrekening']=$bl[0]->nilai;
            $tabel[$idx]['namajumlah']=NULL;
            $tabel[$idx]['totaljumlah']=NULL;
            $idx+=1;
            
            $rincian = DB::table('BUDGETING.RKP_LAMP_3_REKBL as rincian')
                       ->where('rincian.TAHUN',$tahun)
                       ->where('rincian.SKPD_ID',$id)
                       ->where('rincian.TAHAPAN_ID',$tahapan);
            $rincian = $rincian->where(DB::raw('length(rincian."REKENING_KODE")'),'<=',5);
            $rincian = $rincian->leftJoin('BUDGETING.RKP_LAMP_3_REK as rekap',function($join){
                            $join->on('rekap.SKPD_ID','=','rincian.SKPD_ID')
                                 ->on('rekap.BL_ID','=','rincian.BL_ID')
                                 ->on('rekap.TAHAPAN_ID','=','rincian.TAHAPAN_ID')
                                 ->on('rekap.TAHUN','=','rincian.TAHUN');
                       })
                       ->select('rincian.SKPD_KODE',
                                'rincian.KEGIATAN_KODE',
                                'rincian.BL_ID',
                                'rincian.KEGIATAN_NAMA',
                                'rekap.PROGRAM_KODE',
                                'rekap.PROGRAM_NAMA',
                                'rincian.REKENING_KODE',
                                'rincian.REKENING_NAMA',
                                'rincian.BL_MURNI as nilai',
                                DB::raw("'f' as kode_program_ok"),
                                DB::raw("'f' as kode_giat_ok"))
                       ->groupBy('rincian.SKPD_KODE',
                                'rincian.KEGIATAN_KODE',
                                'rincian.BL_ID',
                                'rincian.KEGIATAN_NAMA',
                                'rekap.PROGRAM_KODE',
                                'rekap.PROGRAM_NAMA',
                                'rincian.REKENING_KODE',
                                'rincian.REKENING_NAMA',
                                'rincian.BL_MURNI')
                       ->orderBy('rincian.SKPD_KODE')
                       ->orderBy('rekap.PROGRAM_KODE')
                       ->orderBy('rincian.KEGIATAN_KODE')
                       ->orderBy('rincian.REKENING_KODE')
                       ->get();
            //print_r($rincian);exit;
            $old_kode_program="";
            $old_kode_giat="";
            foreach($rincian as $bt){
                if($bt->PROGRAM_KODE!=$old_kode_program){
                    $bt->kode_program_ok="t";
                    $old_kode_program=$bt->PROGRAM_KODE;
                }
                if($bt->BL_ID!=$old_kode_giat){
                    $bt->kode_giat_ok="t";
                    $old_kode_giat=$bt->BL_ID;
                }

                if($bt->kode_program_ok=="t"){
                    $tabel[$idx]['tingkat']=1;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']=$bt->PROGRAM_KODE;
                    $tabel[$idx]['nogiat']="000";
                    $tabel[$idx]['akun1']="";
                    $tabel[$idx]['akun2']="";
                    $tabel[$idx]['akun3']="";
                    $tabel[$idx]['akun4']="";
                    $tabel[$idx]['akun5']="";
                    $tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$bt->PROGRAM_KODE;
                    $tabel[$idx]['namarekening']=$bt->PROGRAM_NAMA;
                    $tabel[$idx]['totalrekening']=NULL;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }

                if(($bt->kode_giat_ok=="t") or ($bt->BL_ID==$old_kode_giat)){
                    if($bt->kode_giat_ok=="t"){
                        $tabel[$idx]['tingkat']=1;
                        $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                        $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                        $tabel[$idx]['kodeprogram']=$bt->PROGRAM_KODE;
                        $tabel[$idx]['nogiat']=$bt->KEGIATAN_KODE;
                        $tabel[$idx]['akun1']="";
                        $tabel[$idx]['akun2']="";
                        $tabel[$idx]['akun3']="";
                        $tabel[$idx]['akun4']="";
                        $tabel[$idx]['akun5']="";
                        $tabel[$idx]['akun6']="";
                        $tabel[$idx]['koderekening']=$bt->KEGIATAN_KODE;
                        $tabel[$idx]['namarekening']=$bt->KEGIATAN_NAMA;
                        $tabel[$idx]['totalrekening']=NULL;
                        $tabel[$idx]['namajumlah']=NULL;
                        $tabel[$idx]['totaljumlah']=NULL;
                        $idx+=1;
                    }

                    if(strlen($bt->REKENING_KODE)==3){
                        $total_belanja+=$bt->nilai;

                        $tabel[$idx]['tingkat']=3;
                        $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                        $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                        $tabel[$idx]['kodeprogram']=$bt->PROGRAM_KODE;
                        $tabel[$idx]['nogiat']=$bt->KEGIATAN_KODE;
                        $getakun=explode(".",$bt->REKENING_KODE);
                        $tabel[$idx]['akun1']=$getakun[0];
                        $tabel[$idx]['akun2']=$getakun[1];
                        $tabel[$idx]['akun3']="";
                        $tabel[$idx]['akun4']="";
                        $tabel[$idx]['akun5']="";
                        $tabel[$idx]['akun6']="";
                        $tabel[$idx]['koderekening']=$bt->REKENING_KODE;
                        $tabel[$idx]['namarekening']=ucwords(strtolower($bt->REKENING_NAMA));
                        $tabel[$idx]['totalrekening']=$bt->nilai;
                        $tabel[$idx]['namajumlah']=NULL;
                        $tabel[$idx]['totaljumlah']=NULL;
                        $idx+=1;
                    }
                    elseif(strlen($bt->REKENING_KODE)>3 and strlen($bt->REKENING_KODE)<=8){
                        $tabel[$idx]['tingkat']=3;
                        $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                        $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                        $tabel[$idx]['kodeprogram']=$bt->PROGRAM_KODE;
                        $tabel[$idx]['nogiat']=$bt->KEGIATAN_KODE;
                        $getakun=explode(".",$bt->REKENING_KODE);
                        $tabel[$idx]['akun1']=$getakun[0];
                        $tabel[$idx]['akun2']=$getakun[1];
                        (strlen($bt->REKENING_KODE)>3 and strlen($bt->REKENING_KODE)<=8)?$tabel[$idx]['akun3']=$getakun[2]:$tabel[$idx]['akun3']="";
                        (strlen($bt->REKENING_KODE)>5 and strlen($bt->REKENING_KODE)<=8)?$tabel[$idx]['akun4']=$getakun[3]:$tabel[$idx]['akun4']="";
                        $tabel[$idx]['akun5']="";
                        $tabel[$idx]['akun6']="";
                        $tabel[$idx]['koderekening']=$bt->REKENING_KODE;
                        $tabel[$idx]['namarekening']=ucwords(strtolower($bt->REKENING_NAMA));
                        $tabel[$idx]['totalrekening']=$bt->nilai;
                        $tabel[$idx]['namajumlah']=NULL;
                        $tabel[$idx]['totaljumlah']=NULL;
                        $idx+=1;
                    }
                    else{
                        $tabel[$idx]['tingkat']=4;
                        $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                        $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                        $tabel[$idx]['kodeprogram']=$bt->PROGRAM_KODE;
                        $tabel[$idx]['nogiat']=$bt->KEGIATAN_KODE;
                        $getakun=explode(".",$bt->REKENING_KODE);
                        $tabel[$idx]['akun1']=$getakun[0];
                        $tabel[$idx]['akun2']=$getakun[1];
                        $tabel[$idx]['akun3']=$getakun[2];
                        $tabel[$idx]['akun4']=$getakun[3];
                        $tabel[$idx]['akun5']=$getakun[4];
                        (strlen($bt->REKENING_KODE)>11)?$tabel[$idx]['akun6']=$getakun[5]:$tabel[$idx]['akun6']="";
                        $tabel[$idx]['koderekening']=$bt->REKENING_KODE;
                        $tabel[$idx]['namarekening']=ucwords(strtolower($bt->REKENING_NAMA));
                        $tabel[$idx]['totalrekening']=$bt->nilai;
                        $tabel[$idx]['namajumlah']=NULL;
                        $tabel[$idx]['totaljumlah']=NULL;
                        $idx+=1;
                    }
                }

            }
            
            $tabel[$idx]['tingkat']=5;
            $tabel[$idx]['koderekening']=NULL;
            $tabel[$idx]['namarekening']=NULL;
            $tabel[$idx]['totalrekening']=NULL;
            $tabel[$idx]['namajumlah']="Jumlah Belanja";
            $tabel[$idx]['totaljumlah']=$total_belanja;
            $idx+=1;

            $surplus=$total_pendapatan-$total_belanja;
            $tabel[$idx]['tingkat']=5;
            $tabel[$idx]['koderekening']=NULL;
            $tabel[$idx]['namarekening']=NULL;
            $tabel[$idx]['totalrekening']=NULL;
            $tabel[$idx]['namajumlah']="Total Surplus/(Defisit)";
            $tabel[$idx]['totaljumlah']=$surplus;
            $idx+=1;
            
            $penerimaan=DB::table('REFERENSI.REF_REKENING as rk')
                        ->where('rk.REKENING_TAHUN',$tahun)
                        ->where('rk.REKENING_KUNCI','0')
                        ->where('rk.REKENING_KODE','LIKE','6%')
                        ->whereIn(DB::raw('substr(rk."REKENING_KODE",1,3)'),['6.1']);
            $penerimaan = $penerimaan->where(DB::raw('length(rk."REKENING_KODE")'),'<=',5);
            $penerimaan=$penerimaan->leftJoin('BUDGETING.DAT_PEMBIAYAAN as dp',function($join) use ($tahun,$id){
                            $join->on('dp.PEMBIAYAAN_TAHUN','=','rk.REKENING_TAHUN')
                                 ->where('dp.SKPD_ID',$id)
                                 ->whereIn('dp.REKENING_ID',function($join) use ($tahun){
                                    $join->select(DB::raw(' "REKENING_ID" FROM "REFERENSI"."REF_REKENING" WHERE "REKENING_TAHUN"='.$tahun.' AND "REKENING_KODE" LIKE rk."REKENING_KODE" '."||'%'"));
                                 });
                        })
                        ->select(DB::raw('3||SUBSTRING(rk."REKENING_KODE",2,4) as koderekening'),
                               'rk.REKENING_KODE as koderek',
                               'rk.REKENING_NAMA as namarek',
                               DB::raw('SUM(dp."PEMBIAYAAN_TOTAL") as nilai'))
                        ->groupBy('rk.REKENING_KODE','rk.REKENING_NAMA')
                        ->having(DB::raw('SUM(dp."PEMBIAYAAN_TOTAL")'),'<>','0')
                        ->orderBy('rk.REKENING_KODE')
                        ->get();
            //print_r($penerimaan);exit;
            foreach($penerimaan as $bt){
                if(strlen($bt->koderek)==1){
                    $tabel[$idx]['tingkat']=1;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    //$getakun=explode(".",$pd->koderek);
                    $tabel[$idx]['akun1']=$bt->koderek;
                    $tabel[$idx]['akun2']="";
                    $tabel[$idx]['akun3']="";
                    $tabel[$idx]['akun4']="";
                    $tabel[$idx]['akun5']="";
                    $tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
                elseif(strlen($bt->koderek)==3){
                    $total_penerimaan+=$bt->nilai;
                    $tabel[$idx]['tingkat']=2;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    $getakun=explode(".",$bt->koderek);
                    $tabel[$idx]['akun1']=$getakun[0];
                    $tabel[$idx]['akun2']=$getakun[1];
                    $tabel[$idx]['akun3']="";
                    $tabel[$idx]['akun4']="";
                    $tabel[$idx]['akun5']="";
                    $tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
                elseif(strlen($bt->koderek)>3 and strlen($bt->koderek)<=8){
                    $tabel[$idx]['tingkat']=3;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    $getakun=explode(".",$bt->koderek);
                    $tabel[$idx]['akun1']=$getakun[0];
                    $tabel[$idx]['akun2']=$getakun[1];
                    (strlen($bt->koderek)>3 and strlen($bt->koderek)<=8)?$tabel[$idx]['akun3']=$getakun[2]:$tabel[$idx]['akun3']="";
                    (strlen($bt->koderek)>5 and strlen($bt->koderek)<=8)?$tabel[$idx]['akun4']=$getakun[3]:$tabel[$idx]['akun4']="";
                    $tabel[$idx]['akun5']="";
                    $tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
                else{
                    $tabel[$idx]['tingkat']=4;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    $getakun=explode(".",$bt->koderek);
                    $tabel[$idx]['akun1']=$getakun[0];
                    $tabel[$idx]['akun2']=$getakun[1];
                    $tabel[$idx]['akun3']=$getakun[2];
                    $tabel[$idx]['akun4']=$getakun[3];
                    $tabel[$idx]['akun5']=$getakun[4];
                    (strlen($bt->koderek)>11)?$tabel[$idx]['akun6']=$getakun[5]:$tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
            }
            
            $tabel[$idx]['tingkat']=5;
            $tabel[$idx]['koderekening']=NULL;
            $tabel[$idx]['namarekening']=NULL;
            $tabel[$idx]['totalrekening']=NULL;
            $tabel[$idx]['namajumlah']="Jumlah Penerimaan Pembiayaan";
            $tabel[$idx]['totaljumlah']=$total_penerimaan;
            $idx+=1;
            
            $pengeluaran=DB::table('REFERENSI.REF_REKENING as rk')
                        ->where('rk.REKENING_TAHUN',$tahun)
                        ->where('rk.REKENING_KUNCI','0')
                        ->where('rk.REKENING_KODE','LIKE','6.2%');
            $pengeluaran = $pengeluaran->where(DB::raw('length(rk."REKENING_KODE")'),'<=',5);
            $pengeluaran=$pengeluaran->leftJoin('BUDGETING.DAT_PEMBIAYAAN as dp',function($join) use ($tahun,$id){
                            $join->on('dp.PEMBIAYAAN_TAHUN','=','rk.REKENING_TAHUN')
                                 ->where('dp.SKPD_ID',$id)
                                 ->whereIn('dp.REKENING_ID',function($join) use ($tahun){
                                    $join->select(DB::raw(' "REKENING_ID" FROM "REFERENSI"."REF_REKENING" WHERE "REKENING_TAHUN"='.$tahun.' AND "REKENING_KODE" LIKE rk."REKENING_KODE" '."||'%'"));
                                 });
                        })
                        ->select(DB::raw('3||SUBSTRING(rk."REKENING_KODE",2,4) as koderekening'),
                               'rk.REKENING_KODE as koderek',
                               'rk.REKENING_NAMA as namarek',
                               DB::raw('SUM(dp."PEMBIAYAAN_TOTAL") as nilai'))
                        ->groupBy('rk.REKENING_KODE','rk.REKENING_NAMA')
                        ->having(DB::raw('SUM(dp."PEMBIAYAAN_TOTAL")'),'<>','0')
                        ->orderBy('rk.REKENING_KODE')
                        ->get();
            //print_r($pendapatan);exit;
            foreach($pengeluaran as $bt){
                if(strlen($bt->koderek)==1){
                    $tabel[$idx]['tingkat']=1;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    //$getakun=explode(".",$pd->koderek);
                    $tabel[$idx]['akun1']=$bt->koderek;
                    $tabel[$idx]['akun2']="";
                    $tabel[$idx]['akun3']="";
                    $tabel[$idx]['akun4']="";
                    $tabel[$idx]['akun5']="";
                    $tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
                elseif(strlen($bt->koderek)==3){
                    $total_pengeluaran+=$bt->nilai;
                    
                    $tabel[$idx]['tingkat']=2;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    $getakun=explode(".",$bt->koderek);
                    $tabel[$idx]['akun1']=$getakun[0];
                    $tabel[$idx]['akun2']=$getakun[1];
                    $tabel[$idx]['akun3']="";
                    $tabel[$idx]['akun4']="";
                    $tabel[$idx]['akun5']="";
                    $tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
                elseif(strlen($bt->koderek)>3 and strlen($bt->koderek)<=8){
                    $tabel[$idx]['tingkat']=3;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    $getakun=explode(".",$bt->koderek);
                    $tabel[$idx]['akun1']=$getakun[0];
                    $tabel[$idx]['akun2']=$getakun[1];
                    (strlen($bt->koderek)>3 and strlen($bt->koderek)<=8)?$tabel[$idx]['akun3']=$getakun[2]:$tabel[$idx]['akun3']="";
                    (strlen($bt->koderek)>5 and strlen($bt->koderek)<=8)?$tabel[$idx]['akun4']=$getakun[3]:$tabel[$idx]['akun4']="";
                    $tabel[$idx]['akun5']="";
                    $tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
                else{
                    $tabel[$idx]['tingkat']=4;
                    $tabel[$idx]['kodeurusan']=$detil[0]->URUSAN_KODE;
                    $tabel[$idx]['kodeskpd']=$detil[0]->SKPD_KODE;
                    $tabel[$idx]['kodeprogram']="00";
                    $tabel[$idx]['nogiat']="000";
                    $getakun=explode(".",$bt->koderek);
                    $tabel[$idx]['akun1']=$getakun[0];
                    $tabel[$idx]['akun2']=$getakun[1];
                    $tabel[$idx]['akun3']=$getakun[2];
                    $tabel[$idx]['akun4']=$getakun[3];
                    $tabel[$idx]['akun5']=$getakun[4];
                    (strlen($bt->koderek)>11)?$tabel[$idx]['akun6']=$getakun[5]:$tabel[$idx]['akun6']="";
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
            }
            
            $tabel[$idx]['tingkat']=5;
            $tabel[$idx]['koderekening']=NULL;
            $tabel[$idx]['namarekening']=NULL;
            $tabel[$idx]['totalrekeningmurni']=NULL;
            $tabel[$idx]['totalrekening']=NULL;
            $tabel[$idx]['namajumlah']="Jumlah Pengeluaran Pembiayaan";
            $tabel[$idx]['totaljumlah']=$total_pengeluaran;
            $idx+=1;
            
            $netto=$total_penerimaan-$total_pengeluaran;
            $tabel[$idx]['tingkat']=5;
            $tabel[$idx]['koderekening']=NULL;
            $tabel[$idx]['namarekening']=NULL;
            $tabel[$idx]['totalrekeningmurni']=NULL;
            $tabel[$idx]['totalrekening']=NULL;
            $tabel[$idx]['namajumlah']="Pembiayaan Netto";
            $tabel[$idx]['totaljumlah']=$netto;
            
            //print_r($tabel);exit;
            $urut=0;
        }
        elseif($status=="perubahan"){
            //return $this->ringkasperubahan($tahun,$app,$id);
        }
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn,        
                            'skpd'          =>$skpd,        
                            'urusan'        =>$urusan,
                            'rincian'       =>$tabel,    
                            );

        return View('budgeting.lampiran.apbd3',$data);

        /*
        *===================== KODING LAMA ============================
        *

       $bl_prog         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->JOIN('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->groupBy("SKPD_KODE", "SKPD_NAMA", "REF_PROGRAM.PROGRAM_ID", "PROGRAM_KODE", "PROGRAM_NAMA")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", "REF_PROGRAM"."PROGRAM_ID", "PROGRAM_KODE", "PROGRAM_NAMA" ')
                        ->get(); 


        $bl_keg         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", "PROGRAM_ID", "REF_KEGIATAN"."KEGIATAN_ID", "KEGIATAN_KODE", "KEGIATAN_NAMA", "BL_PAGU" ')
                        ->get(); 
                                        

        $bl_rek         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('BUDGETING.DAT_RINCIAN','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->groupBy("SKPD_KODE", "SKPD_NAMA", "DAT_BL.KEGIATAN_ID", "REKENING_KODE", "REKENING_NAMA")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", "DAT_BL"."KEGIATAN_ID", "REKENING_KODE", "REKENING_NAMA", SUM("RINCIAN_TOTAL") AS pagu')
                        ->get();                 



        $btl       = BTL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BTL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->where('REKENING_KODE','like','5.1.1%')
                        ->selectRaw('sum("BTL_TOTAL") as pagu ')
                        ->get(); 
                        
        
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn,        
                            'skpd'          =>$skpd,        
                            'urusan'        =>$urusan,        
                            'bl_prog'       =>$bl_prog,        
                            'bl_keg'       =>$bl_keg,        
                            'bl_rek'       =>$bl_rek,        
                            'btl'           =>$btl,        
                            );

        return View('budgeting.lampiran.apbd3',$data);
        */
    }

   /* public function lampiran4($tahun,$status){
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        $kat1       = UrusanKategori1::where('URUSAN_KAT1_TAHUN',$tahun)->orderBy('URUSAN_KAT1_KODE')->get();
        $urusan     = Urusan::where('URUSAN_TAHUN',$tahun)->orderBy('URUSAN_KODE')->get();

        $skpd         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                        ->JOIN('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
                        ->JOIN('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->groupBy("REF_URUSAN.URUSAN_ID","URUSAN_KODE","SKPD_KODE", "SKPD_NAMA")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"REF_URUSAN"."URUSAN_ID","URUSAN_KODE","SKPD_KODE", "SKPD_NAMA", SUM("BL_PAGU") AS pagu')
                        ->get(); 


        //foreach ($skpd as $s) {
        $program         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
                    ->where('BL_TAHUN',$tahun)
                    ->where('BL_DELETED',0)
                    //->where('REF_SKPD.SKPD_ID',)
                    ->groupBy("URUSAN_ID","REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","PROGRAM_NAMA")
                    ->orderBy('PROGRAM_KODE')
                    ->selectRaw('"URUSAN_ID","REF_PROGRAM"."PROGRAM_ID", "PROGRAM_KODE","PROGRAM_NAMA", SUM("BL_PAGU") AS pagu')
                    ->get();
                        

        $kegiatan         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->groupBy("PROGRAM_ID","KEGIATAN_KODE","KEGIATAN_NAMA","REF_KEGIATAN.KEGIATAN_ID")
                        ->orderBy('KEGIATAN_KODE')
                        ->selectRaw('"PROGRAM_ID","REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA", SUM("BL_PAGU") AS pagu')
                        ->get();    


        $pegawai    = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                      ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                      ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                      ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                      ->where('BL_TAHUN',$tahun)  
                      ->where('BL_DELETED',0)  
                      ->where('REKENING_KODE','like','5.2.1%')
                      ->groupBy("REF_KEGIATAN.KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA")
                      ->selectRaw('"REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA", SUM("RINCIAN_TOTAL") AS total')
                      ->get(); 

        $barangJasa    = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                      ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                      ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                      ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                      ->where('BL_TAHUN',$tahun)  
                      ->where('BL_DELETED',0)  
                      ->where('REKENING_KODE','like','5.2.2%')
                      ->groupBy("REF_KEGIATAN.KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA")
                      ->selectRaw('"REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA", SUM("RINCIAN_TOTAL") AS total')
                      ->get(); 

        $modal    = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                      ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                      ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                      ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                      ->where('BL_TAHUN',$tahun)  
                      ->where('BL_DELETED',0)  
                      ->where('REKENING_KODE','like','5.2.3%')
                      ->groupBy("REF_KEGIATAN.KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA")
                      ->selectRaw('"REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA", SUM("RINCIAN_TOTAL") AS total')
                      ->get(); 



        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            'kat1'          =>$kat1, 
                            'urusan'        =>$urusan, 
                            'skpd'          =>$skpd, 
                            'program'       =>$program, 
                            'kegiatan'      =>$kegiatan, 
                            'pegawai'       =>$pegawai, 
                            'barangJasa'    =>$barangJasa, 
                            'modal'         =>$modal, 
                            );
        return View('budgeting.lampiran.apbd4',$data);
    }

    public function lampiran5($tahun,$status){
    	$tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        $kat2       = UrusanKategori2::where('URUSAN_KAT2_TAHUN',$tahun)->where('URUSAN_KAT2_NAMA','!=','-')->get();
        $urusan     = Urusan::where('URUSAN_TAHUN',$tahun)->orderby('URUSAN_KODE')->get();

        $btl_p      = BTL::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                      ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                      ->join('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')  
                      ->join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')  
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_URUSAN_SKPD.URUSAN_ID')  
                      ->where('REKENING_KODE','like','5.1.1%')
                      ->groupBy("URUSAN_KODE","URUSAN_NAMA")
                      ->selectRaw('"URUSAN_KODE","URUSAN_NAMA", SUM("BTL_TOTAL") AS total')
                      ->get(); 

        $btl_l      = BTL::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                      ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                      ->join('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')  
                      ->join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')  
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_URUSAN_SKPD.URUSAN_ID')  
                      ->whereNotIn('REKENING_KODE',['5.1.3%','5.1.4%','5.1.7%','5.1.8%'])
                      ->groupBy("URUSAN_KODE","URUSAN_NAMA")
                      ->selectRaw('"URUSAN_KODE","URUSAN_NAMA", SUM("BTL_TOTAL") AS total')
                      ->get();               

        $pegawai    = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                      ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                      ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                      ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                      ->where('BL_TAHUN',$tahun)  
                      ->where('BL_DELETED',0)  
                      ->where('REKENING_KODE','like','5.2.1%')
                      ->groupBy("URUSAN_KODE","URUSAN_NAMA")
                      ->selectRaw('"URUSAN_KODE","URUSAN_NAMA", SUM("RINCIAN_TOTAL") AS total')
                      ->get();  
       $barangJasa    = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                      ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                      ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                      ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                      ->where('BL_TAHUN',$tahun)  
                      ->where('BL_DELETED',0)  
                      ->where('REKENING_KODE','like','5.2.2%')
                      ->groupBy("URUSAN_KODE","URUSAN_NAMA")
                      ->selectRaw('"URUSAN_KODE","URUSAN_NAMA", SUM("RINCIAN_TOTAL") AS total')
                      ->get();  
        $modal    = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                      ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                      ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                      ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                      ->where('BL_TAHUN',$tahun)  
                      ->where('BL_DELETED',0)  
                      ->where('REKENING_KODE','like','5.2.3%')
                      ->groupBy("URUSAN_KODE","URUSAN_NAMA")
                      ->selectRaw('"URUSAN_KODE","URUSAN_NAMA", SUM("RINCIAN_TOTAL") AS total')
                      ->get();                
        
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            'kat2'          =>$kat2, 
                            'urusan'        =>$urusan, 
                            'pegawai'       =>$pegawai, 
                            'barangJasa'    =>$barangJasa, 
                            'modal'         =>$modal, 
                            'btl_p'         =>$btl_p, 
                            'btl_l'         =>$btl_l, 
                            );
        return View('budgeting.lampiran.apbd5',$data);
    }*/

    public function lampiran4($tahun,$status){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD')->value('TAHAPAN_ID');
        $tahapan=0;
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        $tabel=array();
        $idx=0;

        $total_pegawai_murni=0;
        $total_jasa_murni=0;
        $total_modal_murni=0;
        $total_pegawai=0;
        $total_jasa=0;
        $total_modal=0;

        $detil = DB::table('BUDGETING.RKP_LAMP_4 as lampiran')
                 ->where('lampiran.TAHUN',$tahun)
                 ->where('lampiran.TAHAPAN_ID',$tahapan)
                 ->select('lampiran.TAHUN',
                          'lampiran.URUSAN_KAT1_KODE',
                          'lampiran.URUSAN_KAT1_NAMA',
                          'lampiran.URUSAN_KODE',
                          'lampiran.URUSAN_NAMA',
                          'lampiran.PROGRAM_KODE',
                          'lampiran.PROGRAM_NAMA',
                          'lampiran.SKPD_KODE',
                          'lampiran.SKPD_NAMA',
                          'lampiran.KEGIATAN_ID',
                          'lampiran.KEGIATAN_KODE',
                          'lampiran.KEGIATAN_NAMA',
                          DB::raw('sum(lampiran."BL_PEGAWAI_MURNI") as "BL_PEGAWAI_MURNI"'),
                          DB::raw('sum(lampiran."BL_JASA_MURNI") as "BL_JASA_MURNI"'),
                          DB::raw('sum(lampiran."BL_MODAL_MURNI") as "BL_MODAL_MURNI"'),
                          DB::raw("'f' as urusan_ok"),
                          DB::raw("'f' as kode_urusan_ok"),
                          DB::raw("'f' as kode_program_ok"),
                          DB::raw("'f' as kode_unit_ok"),
                          DB::raw("'f' as kode_giat_ok"),
                          DB::raw("0 as suburusan_pegawai_murni"),
                          DB::raw("0 as suburusan_jasa_murni"),
                          DB::raw("0 as suburusan_modal_murni"),
                          DB::raw("0 as subunit_pegawai_murni"),
                          DB::raw("0 as subunit_jasa_murni"),
                          DB::raw("0 as subunit_modal_murni"),
                          DB::raw("0 as subprogram_pegawai_murni"),
                          DB::raw("0 as subprogram_jasa_murni"),
                          DB::raw("0 as subprogram_modal_murni"))
                 ->groupBy('lampiran.TAHUN',
                          'lampiran.URUSAN_KAT1_KODE',
                          'lampiran.URUSAN_KAT1_NAMA',
                          'lampiran.URUSAN_KODE',
                          'lampiran.URUSAN_NAMA',
                          'lampiran.PROGRAM_KODE',
                          'lampiran.PROGRAM_NAMA',
                          'lampiran.SKPD_KODE',
                          'lampiran.SKPD_NAMA',
                          'lampiran.KEGIATAN_ID',
                          'lampiran.KEGIATAN_KODE',
                          'lampiran.KEGIATAN_NAMA')
                 ->orderBy('lampiran.URUSAN_KAT1_KODE','asc')
                 ->orderBy('lampiran.URUSAN_KODE','asc')
                 ->orderBy('lampiran.SKPD_KODE','asc')
                 ->orderBy('lampiran.PROGRAM_KODE','asc')
                 ->orderBy('lampiran.KEGIATAN_ID','asc')
                 ->get();
        //print_r($detil);exit;
        $old_urusan="";
        $old_kode_urusan="";
        $old_kode_program="";
        $old_kode_unit="";
        $old_kode_giat="";
        for($i=0;$i<count($detil);$i++){
            $rs=$detil[$i];
            
            if($rs->URUSAN_KAT1_KODE!=$old_urusan){
                $rs->urusan_ok='t';
                $old_urusan=$rs->URUSAN_KAT1_KODE;
            }
            if($rs->URUSAN_KODE!=$old_kode_urusan){
                $rs->kode_urusan_ok='t';
                $old_kode_urusan=$rs->URUSAN_KODE;
                $rekap = DB::table('BUDGETING.RKP_LAMP_4 as rekap')
                         ->where('rekap.TAHUN',$tahun)
                         ->where('rekap.TAHAPAN_ID',$tahapan)
                         ->where('rekap.URUSAN_KODE',$rs->URUSAN_KODE)
                         ->select(DB::raw('sum("BL_PEGAWAI_MURNI") as pegawaimurni'),
                                  DB::raw('sum("BL_JASA_MURNI") as jasamurni'),
                                  DB::raw('sum("BL_MODAL_MURNI") as modalmurni'))
                         ->get();
                //print_r($rekap);exit;
                $rs->suburusan_pegawai_murni=$rekap[0]->pegawaimurni;
                $rs->suburusan_jasa_murni=$rekap[0]->jasamurni;
                $rs->suburusan_modal_murni=$rekap[0]->modalmurni;
            }
            if($rs->SKPD_KODE!=$old_kode_unit){
                $rs->kode_unit_ok='t';
                $old_kode_unit=$rs->SKPD_KODE;
                $rekap = DB::table('BUDGETING.RKP_LAMP_4 as rekap')
                         ->where('rekap.TAHUN',$tahun)
                         ->where('rekap.TAHAPAN_ID',$tahapan)
                         ->where('rekap.URUSAN_KODE',$rs->URUSAN_KODE)
                         ->where('rekap.SKPD_KODE',$rs->SKPD_KODE)
                         ->select(DB::raw('sum("BL_PEGAWAI_MURNI") as pegawaimurni'),
                                  DB::raw('sum("BL_JASA_MURNI") as jasamurni'),
                                  DB::raw('sum("BL_MODAL_MURNI") as modalmurni'))
                         ->get();
                //print_r($rekap);exit;
                $rs->subunit_pegawai_murni=$rekap[0]->pegawaimurni;
                $rs->subunit_jasa_murni=$rekap[0]->jasamurni;
                $rs->subunit_modal_murni=$rekap[0]->modalmurni;
            }
            if($rs->PROGRAM_KODE!=$old_kode_program){
                $rs->kode_program_ok='t';
                $old_kode_program=$rs->PROGRAM_KODE;
                $rekap = DB::table('BUDGETING.RKP_LAMP_4 as rekap')
                         ->where('rekap.TAHUN',$tahun)
                         ->where('rekap.TAHAPAN_ID',$tahapan)
                         ->where('rekap.URUSAN_KODE',$rs->URUSAN_KODE)
                         ->where('rekap.SKPD_KODE',$rs->SKPD_KODE)
                         ->where('rekap.PROGRAM_KODE',$rs->PROGRAM_KODE)
                         ->select(DB::raw('sum("BL_PEGAWAI_MURNI") as pegawaimurni'),
                                  DB::raw('sum("BL_JASA_MURNI") as jasamurni'),
                                  DB::raw('sum("BL_MODAL_MURNI") as modalmurni'))
                         ->get();
                //print_r($rekap);exit;
                $rs->subprogram_pegawai_murni=$rekap[0]->pegawaimurni;
                $rs->subprogram_jasa_murni=$rekap[0]->jasamurni;
                $rs->subprogram_modal_murni=$rekap[0]->modalmurni;
            }
            if($rs->KEGIATAN_ID!=$old_kode_giat){
                $rs->kode_giat_ok='t';
                $old_kode_giat=$rs->KEGIATAN_ID;
                $total_pegawai_murni+=$rs->BL_PEGAWAI_MURNI;
                $total_jasa_murni+=$rs->BL_JASA_MURNI;
                $total_modal_murni+=$rs->BL_MODAL_MURNI;
            }
            array_push($tabel,$rs);
        }

        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            'detil'=>$tabel,
                            'totalpegawaimurni'=>$total_pegawai_murni,
                            'totaljasamurni'=>$total_jasa_murni,
                            'totalmodalmurni'=>$total_modal_murni
                            );
        return View('budgeting.lampiran.apbd4',$data);
        /*
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        $kat1       = UrusanKategori1::where('URUSAN_KAT1_TAHUN',$tahun)->orderBy('URUSAN_KAT1_KODE')->get();
        $urusan     = Urusan::where('URUSAN_TAHUN',$tahun)->orderBy('URUSAN_KODE')->get();

        $skpd         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                        ->JOIN('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
                        ->JOIN('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->groupBy("REF_URUSAN.URUSAN_ID","URUSAN_KODE","SKPD_KODE", "SKPD_NAMA")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"REF_URUSAN"."URUSAN_ID","URUSAN_KODE","SKPD_KODE", "SKPD_NAMA", SUM("BL_PAGU") AS pagu')
                        ->get(); 


        //foreach ($skpd as $s) {
        $program         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
                    ->where('BL_TAHUN',$tahun)
                    ->where('BL_DELETED',0)
                    //->where('REF_SKPD.SKPD_ID',)
                    ->groupBy("URUSAN_ID","REF_PROGRAM.PROGRAM_ID","PROGRAM_KODE","PROGRAM_NAMA")
                    ->orderBy('PROGRAM_KODE')
                    ->selectRaw('"URUSAN_ID","REF_PROGRAM"."PROGRAM_ID", "PROGRAM_KODE","PROGRAM_NAMA", SUM("BL_PAGU") AS pagu')
                    ->get();
                        

        $kegiatan         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->groupBy("PROGRAM_ID","KEGIATAN_KODE","KEGIATAN_NAMA","REF_KEGIATAN.KEGIATAN_ID")
                        ->orderBy('KEGIATAN_KODE')
                        ->selectRaw('"PROGRAM_ID","REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA", SUM("BL_PAGU") AS pagu')
                        ->get();    


        $pegawai    = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                      ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                      ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                      ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                      ->where('BL_TAHUN',$tahun)  
                      ->where('BL_DELETED',0)  
                      ->where('REKENING_KODE','like','5.2.1%')
                      ->groupBy("REF_KEGIATAN.KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA")
                      ->selectRaw('"REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA", SUM("RINCIAN_TOTAL") AS total')
                      ->get(); 

        $barangJasa    = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                      ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                      ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                      ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                      ->where('BL_TAHUN',$tahun)  
                      ->where('BL_DELETED',0)  
                      ->where('REKENING_KODE','like','5.2.2%')
                      ->groupBy("REF_KEGIATAN.KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA")
                      ->selectRaw('"REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA", SUM("RINCIAN_TOTAL") AS total')
                      ->get(); 

        $modal    = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                      ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                      ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                      ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                      ->where('BL_TAHUN',$tahun)  
                      ->where('BL_DELETED',0)  
                      ->where('REKENING_KODE','like','5.2.3%')
                      ->groupBy("REF_KEGIATAN.KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA")
                      ->selectRaw('"REF_KEGIATAN"."KEGIATAN_ID","KEGIATAN_KODE","KEGIATAN_NAMA", SUM("RINCIAN_TOTAL") AS total')
                      ->get(); 



        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            'kat1'          =>$kat1, 
                            'urusan'        =>$urusan, 
                            'skpd'          =>$skpd, 
                            'program'       =>$program, 
                            'kegiatan'      =>$kegiatan, 
                            'pegawai'       =>$pegawai, 
                            'barangJasa'    =>$barangJasa, 
                            'modal'         =>$modal, 
                            );
        return View('budgeting.lampiran.apbd4',$data);
        */
    }

    public function lampiran5($tahun,$status){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD')->value('TAHAPAN_ID');
        $tahapan=0;
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        $tabel=array();
        $idx=0;

        $total_gaji_murni=0;
        $total_nongaji_murni=0;
        $total_pegawai_murni=0;
        $total_jasa_murni=0;
        $total_modal_murni=0;
        $total_gaji=0;
        $total_nongaji=0;
        $total_pegawai=0;
        $total_jasa=0;
        $total_modal=0;

        $detil = DB::table('BUDGETING.RKP_LAMP_5 as lampiran')
                 ->where('lampiran.TAHUN',$tahun)
                 ->where('lampiran.TAHAPAN_ID',$tahapan)
                 ->select('lampiran.TAHUN',
                          'lampiran.URUSAN_KAT1_KODE',
                          'lampiran.URUSAN_KAT1_NAMA',
                          'lampiran.URUSAN_KODE',
                          'lampiran.URUSAN_NAMA',
                          'lampiran.FUNGSI_KODE',
                          'lampiran.FUNGSI_NAMA',
                          DB::raw('sum(lampiran."BTL_LAIN_MURNI") as btlnongajimurni'),
                          DB::raw('sum(lampiran."BTL_PEGAWAI_MURNI") as btlgajimurni'),
                          DB::raw('sum(lampiran."BL_PEGAWAI_MURNI") as pegawaimurni'),
                          DB::raw('sum(lampiran."BL_JASA_MURNI") as jasamurni'),
                          DB::raw('sum(lampiran."BL_MODAL_MURNI") as modalmurni'),
                          DB::raw("'f' as kode_urusan_ok"),
                          DB::raw("'f' as kode_fungsi_ok"),
                          DB::raw("0 as subfungsi_gaji_murni"),
                          DB::raw("0 as subfungsi_nongaji_murni"),
                          DB::raw("0 as subfungsi_pegawai_murni"),
                          DB::raw("0 as subfungsi_jasa_murni"),
                          DB::raw("0 as subufungsi_modal_murni"))
                 ->groupBy('lampiran.TAHUN',
                          'lampiran.URUSAN_KAT1_KODE',
                          'lampiran.URUSAN_KAT1_NAMA',
                          'lampiran.URUSAN_KODE',
                          'lampiran.URUSAN_NAMA',
                          'lampiran.FUNGSI_KODE',
                          'lampiran.FUNGSI_NAMA')
                 ->orderBy('lampiran.FUNGSI_KODE','asc')
                 ->orderBy('lampiran.URUSAN_KODE','asc')
                 ->get();
        //print_r($detil);exit;
        $old_urusan="";
        $old_kode_urusan="";
        $old_kode_fungsi="";
        for($i=0;$i<count($detil);$i++){
            $rs=$detil[$i];
            if($rs->URUSAN_KAT1_KODE!=$old_kode_fungsi){
                $rs->kode_fungsi_ok='t';
                $old_kode_fungsi=$rs->URUSAN_KAT1_KODE;
                $rekap = DB::table('BUDGETING.RKP_LAMP_5 as rekap')
                         ->where('rekap.TAHUN',$tahun)
                         ->where('rekap.TAHAPAN_ID',$tahapan)
                         ->where('rekap.URUSAN_KAT1_KODE',$rs->URUSAN_KAT1_KODE)
                         ->select(DB::raw('sum("BTL_LAIN_MURNI") as btlnongajimurni'),
                                  DB::raw('sum("BTL_PEGAWAI_MURNI") as btlgajimurni'),
                                  DB::raw('sum("BL_PEGAWAI_MURNI") as pegawaimurni'),
                                  DB::raw('sum("BL_JASA_MURNI") as jasamurni'),
                                  DB::raw('sum("BL_MODAL_MURNI") as modalmurni'))
                         ->get();
                //print_r($rekap);exit;
                $rs->subfungsi_nongaji_murni=$rekap[0]->btlnongajimurni;
                $rs->subfungsi_gaji_murni=$rekap[0]->btlgajimurni;
                $rs->subfungsi_pegawai_murni=$rekap[0]->pegawaimurni;
                $rs->subfungsi_jasa_murni=$rekap[0]->jasamurni;
                $rs->subfungsi_modal_murni=$rekap[0]->modalmurni;
            }
            
            if($rs->URUSAN_KODE!=$old_kode_urusan){
                $rs->kode_urusan_ok='t';
                $old_kode_urusan=$rs->URUSAN_KODE;
                $total_nongaji_murni+=$rs->btlnongajimurni;
                $total_gaji_murni+=$rs->btlgajimurni;
                $total_pegawai_murni+=$rs->pegawaimurni;
                $total_jasa_murni+=$rs->jasamurni;
                $total_modal_murni+=$rs->modalmurni;
            }
            
            array_push($tabel,$rs);
        }

        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            'detil'=>$tabel,
                            'totalnongajimurni'=>$total_nongaji_murni,
                            'totalgajimurni'=>$total_gaji_murni,
                            'totalpegawaimurni'=>$total_pegawai_murni,
                            'totaljasamurni'=>$total_jasa_murni,
                            'totalmodalmurni'=>$total_modal_murni 
                            );
        return View('budgeting.lampiran.apbd5',$data);

        /*
        *=================== KODING LAMA
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        $kat2       = UrusanKategori2::where('URUSAN_KAT2_TAHUN',$tahun)->where('URUSAN_KAT2_NAMA','!=','-')->get();
        $urusan     = Urusan::where('URUSAN_TAHUN',$tahun)->orderby('URUSAN_KODE')->get();

        $btl_p      = BTL::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                      ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                      ->join('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')  
                      ->join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')  
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_URUSAN_SKPD.URUSAN_ID')  
                      ->where('REKENING_KODE','like','5.1.1%')
                      ->groupBy("URUSAN_KODE","URUSAN_NAMA")
                      ->selectRaw('"URUSAN_KODE","URUSAN_NAMA", SUM("BTL_TOTAL") AS total')
                      ->get(); 

        $btl_l      = BTL::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                      ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                      ->join('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')  
                      ->join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')  
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_URUSAN_SKPD.URUSAN_ID')  
                      ->whereNotIn('REKENING_KODE',['5.1.3%','5.1.4%','5.1.7%','5.1.8%'])
                      ->groupBy("URUSAN_KODE","URUSAN_NAMA")
                      ->selectRaw('"URUSAN_KODE","URUSAN_NAMA", SUM("BTL_TOTAL") AS total')
                      ->get();               

        $pegawai    = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                      ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                      ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                      ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                      ->where('BL_TAHUN',$tahun)  
                      ->where('BL_DELETED',0)  
                      ->where('REKENING_KODE','like','5.2.1%')
                      ->groupBy("URUSAN_KODE","URUSAN_NAMA")
                      ->selectRaw('"URUSAN_KODE","URUSAN_NAMA", SUM("RINCIAN_TOTAL") AS total')
                      ->get();  
       $barangJasa    = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                      ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                      ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                      ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                      ->where('BL_TAHUN',$tahun)  
                      ->where('BL_DELETED',0)  
                      ->where('REKENING_KODE','like','5.2.2%')
                      ->groupBy("URUSAN_KODE","URUSAN_NAMA")
                      ->selectRaw('"URUSAN_KODE","URUSAN_NAMA", SUM("RINCIAN_TOTAL") AS total')
                      ->get();  
        $modal    = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                      ->join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                      ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                      ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                      ->join('REFERENSI.REF_URUSAN','REF_URUSAN.URUSAN_ID','=','REF_PROGRAM.URUSAN_ID')
                      ->where('BL_TAHUN',$tahun)  
                      ->where('BL_DELETED',0)  
                      ->where('REKENING_KODE','like','5.2.3%')
                      ->groupBy("URUSAN_KODE","URUSAN_NAMA")
                      ->selectRaw('"URUSAN_KODE","URUSAN_NAMA", SUM("RINCIAN_TOTAL") AS total')
                      ->get();                
        
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            'kat2'          =>$kat2, 
                            'urusan'        =>$urusan, 
                            'pegawai'       =>$pegawai, 
                            'barangJasa'    =>$barangJasa, 
                            'modal'         =>$modal, 
                            'btl_p'         =>$btl_p, 
                            'btl_l'         =>$btl_l, 
                            );
        return View('budgeting.lampiran.apbd5',$data);
        */
    }

    public function lampiran6($tahun,$status){
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            );
        return View('budgeting.lampiran.apbd6',$data);
    }

    public function lampiran7($tahun,$status){
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            );
        return View('budgeting.lampiran.apbd7',$data);
    }


     public function lampiran8($tahun,$status){
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            );
        return View('budgeting.lampiran.apbd8',$data);
    }

     public function lampiran9($tahun,$status){
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            );
        return View('budgeting.lampiran.apbd9',$data);
    }

    public function lampiran10($tahun,$status){
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            );
        return View('budgeting.lampiran.apbd10',$data);
    }

    public function lampiran11($tahun,$status){
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            );
        return View('budgeting.lampiran.apbd11',$data);
    }

    public function lampiran12($tahun,$status){
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            );
        return View('budgeting.lampiran.apbd12',$data);
    }

    public function lampiran13($tahun,$status){
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            );
        return View('budgeting.lampiran.apbd13',$data);
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

    public function ppasProgram($tahun,$status,$tipe){
        if($status=='murni'){
            if($tipe == 'pagu')
                $data   = DB::select('select "SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_KODE", "KEGIATAN_NAMA", SUM("BL_PAGU")
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
                                    WHERE "BL_DELETED" = 0 and "BL_TAHUN" = '.$tahun.' and "BL_VALIDASI" = 1
                                    GROUP BY SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_KODE", "KEGIATAN_NAMA",
                                    ORDER BY SKPD, "PROGRAM_KODE"');
            elseif($tipe == 'rincian')
                $data   = DB::select('select "SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_KODE", "KEGIATAN_NAMA", SUM("RINCIAN_TOTAL")
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
                                    inner join "BUDGETING"."DAT_RINCIAN" rincian
                                    on bl."BL_ID" = rincian."BL_ID"
                                    WHERE "BL_DELETED" = 0 and "BL_TAHUN" = '.$tahun.'
                                    GROUP BY SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_NAMA", "KEGIATAN_KODE"
                                    ORDER BY SKPD, "PROGRAM_KODE" ');
            else
                $data   = DB::select('select "SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_KODE", "PROGRAM_NAMA","KEGIATAN_KODE", "KEGIATAN_NAMA",SUM("BL_PAGU") AS "PAGU KEGIATAN"
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
                                    WHERE "BL_TAHUN" = '.$tahun.' and "BL_VALIDASI" = 1 and "BL_DELETED" = 0
                                    GROUP BY SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_KODE","KEGIATAN_NAMA", bl."BL_ID"
                                    ORDER BY SKPD, "PROGRAM_KODE", "KEGIATAN_KODE"');
               // dd($data);
                $data = array_map(function ($value) {
                    return (array)$value;
                }, $data);
                Excel::create('PAGU PROGRAM '.Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
                    $excel->sheet('PAGU PROGRAM', function($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
                })->download('xls');

        }else{
            if($tipe == 'pagu')
                $data   = DB::select('select "SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_KODE", "KEGIATAN_NAMA",  SUM(bl."BL_PAGU") AS PAGU_PERUBAHAN
                                    from "BUDGETING"."DAT_BL_PERUBAHAN" bl
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
                                    WHERE "BL_DELETED" = 0 and "BL_TAHUN" = '.$tahun.' and "BL_VALIDASI" = 1
                                    GROUP BY SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_KODE", "KEGIATAN_NAMA"
                                    ORDER BY SKPD, "PROGRAM_KODE"');
            elseif($tipe == 'rincian')
                $data   = DB::select('select "SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_KODE", "KEGIATAN_NAMA", SUM(rincian."RINCIAN_TOTAL") AS RINCIAN_TOTAL_PERUBAHAN
                                    from "BUDGETING"."DAT_BL_PERUBAHAN" bl
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
                                    inner join "BUDGETING"."DAT_RINCIAN_PERUBAHAN" rincian
                                    on bl."BL_ID" = rincian."BL_ID"
                                    WHERE "BL_DELETED" = 0 and "BL_TAHUN" = '.$tahun.'
                                    GROUP BY SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_NAMA", "KEGIATAN_KODE"
                                    ORDER BY SKPD, "PROGRAM_KODE" ');
            else
                $data   = DB::select('select "SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_KODE", "PROGRAM_NAMA","KEGIATAN_KODE", "KEGIATAN_NAMA", SUM(bl."BL_PAGU") AS "PAGU KEGIATAN PERUBAHAN"
                                    from "BUDGETING"."DAT_BL_PERUBAHAN" bl
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
                                    WHERE "BL_TAHUN" = '.$tahun.' and "BL_VALIDASI" = 1 and "BL_DELETED" = 0
                                    GROUP BY SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_KODE","KEGIATAN_NAMA", bl."BL_ID"
                                    ORDER BY SKPD, "PROGRAM_KODE", "KEGIATAN_KODE"');
               // dd($data);
                $data = array_map(function ($value) {
                    return (array)$value;
                }, $data);
                Excel::create('PAGU PROGRAM '.Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
                    $excel->sheet('PAGU PROGRAM', function($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
                })->download('xls');

        }

        
    }


    public function rekapAll($tahun,$status){

        if($status == 'murni'){
            $data   = DB::select('select "SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_KODE", "PROGRAM_NAMA","KEGIATAN_KODE", "KEGIATAN_NAMA", bl."BL_PAGU" AS "PAGU KEGIATAN",
            sum(rincian."RINCIAN_TOTAL") as "RINCIAN TOTAL KEGIATAN"
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
                                    WHERE "BL_TAHUN" = '.$tahun.' and "BL_DELETED" = 0
                                    GROUP BY SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_KODE","KEGIATAN_NAMA", bl."BL_PAGU"
                                    ORDER BY SKPD, "PROGRAM_KODE", "KEGIATAN_KODE"');
        }else {
            $data   = DB::select('select "SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_KODE", "PROGRAM_NAMA","KEGIATAN_KODE", "KEGIATAN_NAMA", bl."BL_PAGU" AS "PAGU KEGIATAN",
            sum(rincian."RINCIAN_TOTAL") as "RINCIAN TOTAL KEGIATAN"
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
                                    WHERE "BL_TAHUN" = '.$tahun.' and "BL_DELETED" = 0
                                    GROUP BY SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_KODE","KEGIATAN_NAMA", bl."BL_PAGU"
                                    ORDER BY SKPD, "PROGRAM_KODE", "KEGIATAN_KODE"');
        }
               //dd($data);
            $data = array_map(function ($value) {
                    return (array)$value;
                }, $data);
            Excel::create('PAGU PROGRAM '.Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
                    $excel->sheet('PAGU PROGRAM', function($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
            })->download('xls');
        
    }


    public function berbedaPaguRIncian($tahun,$status){

        if($status == 'murni'){
            $data   = DB::select('select "SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_KODE", "PROGRAM_NAMA","KEGIATAN_KODE", "KEGIATAN_NAMA", bl."BL_PAGU" AS "PAGU KEGIATAN",
            sum(rincian."RINCIAN_TOTAL") as "RINCIAN TOTAL KEGIATAN"
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
                                    WHERE "BL_TAHUN" = '.$tahun.' and "BL_DELETED" = 0
                                    GROUP BY SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_KODE","KEGIATAN_NAMA", bl."BL_PAGU"
                                    having sum(rincian."RINCIAN_TOTAL") <> bl."BL_PAGU"
                                    ORDER BY SKPD, "PROGRAM_KODE", "KEGIATAN_KODE"');
        }else {
            $data   = DB::select('select "SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_KODE", "PROGRAM_NAMA","KEGIATAN_KODE", "KEGIATAN_NAMA", bl."BL_PAGU" AS "PAGU KEGIATAN",
            sum(rincian."RINCIAN_TOTAL") as "RINCIAN TOTAL KEGIATAN"
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
                                    WHERE "BL_TAHUN" = '.$tahun.' and "BL_DELETED" = 0
                                    GROUP BY SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", "KEGIATAN_KODE","KEGIATAN_NAMA", bl."BL_PAGU"
                                    having sum(rincian."RINCIAN_TOTAL") <> bl."BL_PAGU"
                                    ORDER BY SKPD, "PROGRAM_KODE", "KEGIATAN_KODE"');
        }
               //dd($data);
            $data = array_map(function ($value) {
                    return (array)$value;
                }, $data);
            Excel::create('PAGU PROGRAM '.Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
                    $excel->sheet('PAGU PROGRAM', function($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
            })->download('xls');
        
    }


    public function rekapBelanja($tahun,$status){

        if($status == 'murni'){
            $data   = DB::select('select "REKENING_KODE" as KODE, "REKENING_NAMA" AS REKENING, sum("RINCIAN_TOTAL") AS TOTAL
                                    from "BUDGETING"."DAT_RINCIAN"
                                    join "REFERENSI"."REF_REKENING" on "REF_REKENING"."REKENING_ID"="DAT_RINCIAN"."REKENING_ID"
                                    join "BUDGETING"."DAT_BL" on "DAT_BL"."BL_ID" = "DAT_RINCIAN"."BL_ID"
                                    WHERE "BL_TAHUN" = '.$tahun.' and "BL_DELETED" = 0
                                    GROUP BY "REKENING_KODE", "REKENING_NAMA"');
        }
               //dd($data);
            $data = array_map(function ($value) {
                    return (array)$value;
                }, $data);
            Excel::create('REKAP BELANJA '.$status.' Tahun '.$tahun.' - '.Carbon\Carbon::now()->format('d M Y - H'), function($excel) use($data){
                    $excel->sheet('REKAP BELANJA ', function($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
            })->download('xls');
        
    }



    public function rkaSKPD($tahun,$status){
        $tipe = 'Lampiran RKA-SKPD'; 
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        
        return View('budgeting.lampiran.rka-skpd',$data);
    }


    public function rkaSKPDDetail($tahun, $status, $s){
        
        $urusan = Urusan::join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->where('SKPD_ID',$s)->first();

        $skpd = SKPD::where('SKPD_ID',$s)->first();

        $pendapatan = Pendapatan::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_PENDAPATAN.SUB_ID')
                        ->where('SKPD_ID',$s)->get();
                        
                        
        $btl = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');

        $btl1   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.1%');})
                    ->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');

        $btl2   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.3%');})
                    ->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');

        $btl3   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.4%');})
                    ->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');  

        $btl4   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.7%');})
                    ->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL'); 

        $btl5   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.8%');})
                    ->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');                     
        
        
        $bl     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->sum('RINCIAN_TOTAL');

            $bl1     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->sum('RINCIAN_TOTAL');

            $bl2     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->sum('RINCIAN_TOTAL');

            $bl3     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->sum('RINCIAN_TOTAL'); 
                                                         
                        
        $pembiayaan = Pembiayaan::All();  

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.rka-skpd-detail',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'urusan'=>$urusan, 'pendapatan'=>$pendapatan, 'bl'=>$bl, 'pembiayaan'=>$pembiayaan, 'btl'=>$btl, 'btl1'=>$btl1, 'btl2'=>$btl2, 'btl3'=>$btl3, 'btl4'=>$btl4, 'btl5'=>$btl5,'bl'=>$bl,'bl1'=>$bl1,'bl2'=>$bl2,'bl3'=>$bl3 ]);
    }


    public function rkaSKPD1($tahun,$status){
        $tipe = 'Lampiran RKA-SKPD 1'; 
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        
        return View('budgeting.lampiran.rka-skpd1',$data);
    }


    public function rkaSKPD1Detail($tahun, $status, $s){
        
        $urusan = Urusan::join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->where('SKPD_ID',$s)->first();

        $skpd = SKPD::where('SKPD_ID',$s)->first();

        $pendapatan = Pendapatan::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_PENDAPATAN.SUB_ID')
                        ->where('SKPD_ID',$s)->where('PENDAPATAN_TAHUN',$tahun)->get();

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.rka-skpd1-detail',['tahun'=>$tahun,'status'=>$status, 'skpd'=>$skpd, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'urusan'=>$urusan, 'pendapatan'=>$pendapatan ]);
    }

    public function rkaSKPD21($tahun,$status){
        $tipe = 'Lampiran RKA-SKPD 2.1'; 
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        
        return View('budgeting.lampiran.rka-skpd21',$data);
    }


    public function rkaSKPD21Detail($tahun, $status, $s){

        $urusan = Urusan::join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->where('SKPD_ID',$s)->first();

        $skpd = SKPD::where('SKPD_ID',$s)->first();

        $btl = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                        ->where('SKPD_ID',$s)->where('BTL_TAHUN',$tahun)->get();

        $btl1   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.1.01%');})
                    ->where('BTL_TAHUN',$tahun)->get();

        $btl2   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.1.02%');})
                    ->where('BTL_TAHUN',$tahun)->get();            
 

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.rka-skpd21-detail',['tahun'=>$tahun,'status'=>$status, 'btl'=>$btl,'skpd'=>$skpd, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'urusan'=>$urusan,'btl1'=>$btl1, 'btl2'=>$btl2 ]);
    }


    public function rkaSKPD22($tahun,$status){
        $tipe = 'Lampiran RKA-SKPD 2.2'; 
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        
        return View('budgeting.lampiran.rka-skpd22',$data);
    }


    public function rkaSKPD22Detail($tahun, $status, $s){
        $id=2241;
        $urusan = Urusan::join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->where('SKPD_ID',$s)->first();

        $skpd = SKPD::where('SKPD_ID',$s)->first();

        $bl_p = BL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                   ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                   ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID') 
                   ->where('SKPD_ID',$s)
                   ->groupBy("REF_KEGIATAN.PROGRAM_ID","PROGRAM_NAMA","PROGRAM_KODE")
                   ->orderby("PROGRAM_KODE")
                   ->selectRaw('"REF_KEGIATAN"."PROGRAM_ID","PROGRAM_NAMA", "PROGRAM_KODE", sum("BL_PAGU") as pagu')
                   ->get(); 

        $bl = BL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                   ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                   ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID') 
                   ->join('REFERENSI.REF_LOKASI','REF_LOKASI.LOKASI_ID','=','DAT_BL.LOKASI_ID') 
                   ->where('SKPD_ID',$s)
                   ->orderby("KEGIATAN_KODE")
                   ->selectRaw('"REF_KEGIATAN"."PROGRAM_ID", "DAT_BL"."KEGIATAN_ID", "PROGRAM_KODE", "KEGIATAN_KODE", "KEGIATAN_NAMA", "BL_PAGU", "LOKASI_NAMA"')
                   ->get(); 

        $bl_idk = BL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                   ->join('BUDGETING.DAT_OUTPUT','DAT_OUTPUT.BL_ID','=','DAT_BL.BL_ID') 
                   ->join('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_OUTPUT.SATUAN_ID')
                   ->where('SKPD_ID',$s)
                   ->selectRaw('"KEGIATAN_ID","OUTPUT_TARGET","SATUAN_NAMA"')
                   ->get(); 

         $bl1     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->groupBy("KEGIATAN_ID")
                        ->selectRaw(' "KEGIATAN_ID", sum("RINCIAN_TOTAL") as total')
                        ->get();

        $bl2     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->groupBy("KEGIATAN_ID")
                        ->selectRaw(' "KEGIATAN_ID", sum("RINCIAN_TOTAL") as total')
                        ->get(); 

        $bl3     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->groupBy("KEGIATAN_ID")
                        ->selectRaw(' "KEGIATAN_ID", sum("RINCIAN_TOTAL") as total')
                        ->get();                                                       

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.rka-skpd22-detail',['tahun'=>$tahun,'status'=>$status, 'bl'=>$bl,'skpd'=>$skpd, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'urusan'=>$urusan, 'bl_p'=>$bl_p, 'bl_idk'=>$bl_idk, 'bl1'=>$bl1, 'bl2'=>$bl2, 'bl3'=>$bl3 ]);
    }


    public function rkaSKPD31($tahun,$status){
        $tipe = 'Lampiran RKA-SKPD 3.1'; 
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        
        return View('budgeting.lampiran.rka-skpd31',$data);
    }


    public function rkaSKPD31Detail($tahun, $status, $s){
        
        $urusan = Urusan::join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->where('SKPD_ID',$s)->first();

        $skpd = SKPD::where('SKPD_ID',$s)->first();

        //$pembiayaan = Pembiayaan::ALL();
        $pembiayaan     = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1%')
              ->orderby('REKENING_KODE')
              ->get();

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.rka-skpd31-detail',['tahun'=>$tahun,'status'=>$status, 'pembiayaan'=>$pembiayaan,'skpd'=>$skpd, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'urusan'=>$urusan ]);
    }


    public function rkaSKPD32($tahun,$status){
        $tipe = 'Lampiran RKA-SKPD 3.2'; 
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        
        return View('budgeting.lampiran.rka-skpd32',$data);
    }


    public function rkaSKPD32Detail($tahun, $status, $s){
        
        $urusan = Urusan::join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->where('SKPD_ID',$s)->first();

        $skpd = SKPD::where('SKPD_ID',$s)->first();

        $pembiayaan     = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.2%')
              ->orderby('REKENING_KODE')
              ->get();

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.rka-skpd32-detail',['tahun'=>$tahun,'status'=>$status, 'pembiayaan'=>$pembiayaan,'skpd'=>$skpd, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'urusan'=>$urusan ]);
    }


    public function dpaSKPD($tahun,$status){
        $tipe = 'Lampiran DPA-SKPD'; 
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        
        return View('budgeting.lampiran.dpa-skpd',$data);
    }


    public function dpaSKPDDetail($tahun, $status, $s){
        
        $urusan = Urusan::join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->where('SKPD_ID',$s)->first();

        $skpd = SKPD::where('SKPD_ID',$s)->first();

        $pendapatan = Pendapatan::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_PENDAPATAN.SUB_ID')
                        ->where('SKPD_ID',$s)->get();
                        
                        
        $btl = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');

        $btl1   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.1%');})
                    ->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');

        $btl2   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.3%');})
                    ->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');

        $btl3   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.4%');})
                    ->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');  

        $btl4   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.7%');})
                    ->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL'); 

        $btl5   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.8%');})
                    ->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');                     
        
        
        $bl     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->sum('RINCIAN_TOTAL');

            $bl1     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->sum('RINCIAN_TOTAL');

            $bl2     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->sum('RINCIAN_TOTAL');

            $bl3     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->sum('RINCIAN_TOTAL'); 
                                                         
                        
        $pembiayaan = Pembiayaan::All();  

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.dpa-skpd-detail',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'urusan'=>$urusan, 'pendapatan'=>$pendapatan, 'bl'=>$bl, 'pembiayaan'=>$pembiayaan, 'btl'=>$btl, 'btl1'=>$btl1, 'btl2'=>$btl2, 'btl3'=>$btl3, 'btl4'=>$btl4, 'btl5'=>$btl5,'bl'=>$bl,'bl1'=>$bl1,'bl2'=>$bl2,'bl3'=>$bl3 ]);

    }



    public function dpaSKPD1($tahun,$status){
        $tipe = 'Lampiran DPA-SKPD 1'; 
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        
        return View('budgeting.lampiran.dpa-skpd1',$data);
    }


    public function dpaSKPD1Detail($tahun, $status, $s){
        
        $urusan = Urusan::join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->where('SKPD_ID',$s)->first();

        $skpd = SKPD::where('SKPD_ID',$s)->first();

        $pendapatan = Pendapatan::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_PENDAPATAN.SUB_ID')
                        ->where('SKPD_ID',$s)->get();

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.dpa-skpd1-detail',['tahun'=>$tahun,'status'=>$status, 'pendapatan'=>$pendapatan,'skpd'=>$skpd, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'urusan'=>$urusan ]);
    }


    public function dpaSKPD21($tahun,$status){
        $tipe = 'Lampiran DPA-SKPD 2.1'; 
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        
        return View('budgeting.lampiran.dpa-skpd21',$data);
    }


    public function dpaSKPD21Detail($tahun, $status, $s){
        $id=2241;
        $urusan = Urusan::join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->where('SKPD_ID',$s)->first();

        $skpd = SKPD::where('SKPD_ID',$s)->first();

        $btl = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                        ->where('SKPD_ID',$s)->where('BTL_TAHUN',$tahun)->get();

        $btl1   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.1.01%');})
                    ->where('BTL_TAHUN',$tahun)->get();

        $btl2   = BTL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$s)
                    ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.1.02%');})
                    ->where('BTL_TAHUN',$tahun)->get();                          

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.dpa-skpd21-detail',['tahun'=>$tahun,'status'=>$status, 'btl'=>$btl,'skpd'=>$skpd, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'urusan'=>$urusan,'btl1'=>$btl1, 'btl2'=>$btl2 ]);
    }


    public function dpaSKPD22($tahun,$status){
        $tipe = 'Lampiran DPA-SKPD 2.2'; 
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        
        return View('budgeting.lampiran.dpa-skpd22',$data);
    }


    public function dpaSKPD22Detail($tahun, $status, $s){
        $urusan = Urusan::join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->where('SKPD_ID',$s)->first();

        $skpd = SKPD::where('SKPD_ID',$s)->first();

        $bl_p = BL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                   ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                   ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID') 
                   ->where('SKPD_ID',$s)
                   ->groupBy("REF_KEGIATAN.PROGRAM_ID","PROGRAM_NAMA","PROGRAM_KODE")
                   ->orderby("PROGRAM_KODE")
                   ->selectRaw('"REF_KEGIATAN"."PROGRAM_ID","PROGRAM_NAMA", "PROGRAM_KODE", sum("BL_PAGU") as pagu')
                   ->get(); 

        $bl = BL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                   ->join('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                   ->join('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID') 
                   ->join('REFERENSI.REF_LOKASI','REF_LOKASI.LOKASI_ID','=','DAT_BL.LOKASI_ID') 
                   ->where('SKPD_ID',$s)
                   ->orderby("KEGIATAN_KODE")
                   ->selectRaw('"REF_KEGIATAN"."PROGRAM_ID", "DAT_BL"."KEGIATAN_ID", "PROGRAM_KODE", "KEGIATAN_KODE", "KEGIATAN_NAMA", "BL_PAGU", "LOKASI_NAMA"')
                   ->get(); 

        $bl_idk = BL::join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                   ->join('BUDGETING.DAT_OUTPUT','DAT_OUTPUT.BL_ID','=','DAT_BL.BL_ID') 
                   ->join('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_OUTPUT.SATUAN_ID')
                   ->where('SKPD_ID',$s)
                   ->selectRaw('"KEGIATAN_ID","OUTPUT_TARGET","SATUAN_NAMA"')
                   ->get(); 

         $bl1     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->groupBy("KEGIATAN_ID")
                        ->selectRaw(' "KEGIATAN_ID", sum("RINCIAN_TOTAL") as total')
                        ->get();

        $bl2     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->groupBy("KEGIATAN_ID")
                        ->selectRaw(' "KEGIATAN_ID", sum("RINCIAN_TOTAL") as total')
                        ->get(); 

        $bl3     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        ->where('SKPD_ID',$s)
                        ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->groupBy("KEGIATAN_ID")
                        ->selectRaw(' "KEGIATAN_ID", sum("RINCIAN_TOTAL") as total')
                        ->get();                                                       

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.dpa-skpd22-detail',['tahun'=>$tahun,'status'=>$status, 'bl'=>$bl,'skpd'=>$skpd, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'urusan'=>$urusan, 'bl_p'=>$bl_p, 'bl_idk'=>$bl_idk, 'bl1'=>$bl1, 'bl2'=>$bl2, 'bl3'=>$bl3 ]);
    }



    public function dpaSKPD221($tahun,$status){
        $tipe = 'Lampiran DPA-SKPD 2.2.1'; 
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        
        return View('budgeting.lampiran.dpa-skpd221',$data);
    }


    public function dpaSKPD221Detail($tahun, $status, $s){
        $id=2241;
        $urusan = Urusan::join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->where('SKPD_ID',$s)->first();

        $skpd = SKPD::where('SKPD_ID',$s)->first();

        $indikator  = Indikator::where('BL_ID',$id)->orderBy('INDIKATOR')->get();

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.dpa-skpd221-detail',['tahun'=>$tahun,'status'=>$status, 'indikator'=>$indikator,'skpd'=>$skpd, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'urusan'=>$urusan ]);
    }


    public function dpaSKPD31($tahun,$status){
        $tipe = 'Lampiran DPA-SKPD 3.1'; 
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        
        return View('budgeting.lampiran.dpa-skpd31',$data);
    }


    public function dpaSKPD31Detail($tahun, $status, $s){
        $id=2241;
        $urusan = Urusan::join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->where('SKPD_ID',$s)->first();

        $skpd = SKPD::where('SKPD_ID',$s)->first();

        $pembiayaan     = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1%')
              ->orderby('REKENING_KODE')
              ->get();

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.dpa-skpd31-detail',['tahun'=>$tahun,'status'=>$status, 'pembiayaan'=>$pembiayaan,'skpd'=>$skpd, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'urusan'=>$urusan ]);
    }


    public function dpaSKPD32($tahun,$status){
        $tipe = 'Lampiran DPA-SKPD 3.2'; 
        $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        
        return View('budgeting.lampiran.dpa-skpd32',$data);
    }


    public function dpaSKPD32Detail($tahun, $status, $s){
        
        $urusan = Urusan::join('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                        ->where('SKPD_ID',$s)->first();

        $skpd = SKPD::where('SKPD_ID',$s)->first();

        $pembiayaan     = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.2%')
              ->orderby('REKENING_KODE')
              ->get();  

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.dpa-skpd32-detail',['tahun'=>$tahun,'status'=>$status, 'pembiayaan'=>$pembiayaan,'skpd'=>$skpd, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'urusan'=>$urusan ]);
    }


    public function akbBL($tahun, $status, $id){
        
        $akb  = AKB_BL::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_AKB_BL.REKENING_ID')
                    ->join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_AKB_BL.BL_ID')
                    ->where('DAT_AKB_BL.BL_ID',$id)->get();

        $bl   = BL::join('BUDGETING.DAT_STAFF','DAT_STAFF.BL_ID','=','DAT_BL.BL_ID')
                        ->where('DAT_BL.BL_ID',$id)->first();           // dd($akb->SKPD_ID);         
        
         

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.akb-bl',['tahun'=>$tahun,'status'=>$status, 'akb'=>$akb, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'bl'=>$bl ]);
    }


    public function akbBTL($tahun, $status, $id){
        
        $akb  = AKB_BTL::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_AKB_BTL.REKENING_ID')
                    ->join('BUDGETING.DAT_BTL','DAT_BTL.BTL_ID','=','DAT_AKB_BTL.BTL_ID')
                    ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BTL.SUB_ID')
                    ->where('SKPD_ID',$id)->get();

        $skpd = SKPD::where('SKPD_ID',$id)->first();        
        
         

        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        
            return View('budgeting.lampiran.akb-btl',['tahun'=>$tahun,'status'=>$status, 'akb'=>$akb, 'tgl'=>$tgl, 'gbln'=>$gbln, 'bln'=>$bln, 'skpd'=>$skpd ]);
    }


     public function perwal1($tahun,$status){
        $id = 1;
        //dd($id);
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD')->value('TAHAPAN_ID');
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        $tabel=array();
        $idx=0;

        $total_pendapatan=0;
        $total_belanja=0;
        $total_penerimaan=0;
        $total_pengeluaran=0;
        $surplus=0;
        $netto=0;

        $pendapatan=DB::table('REFERENSI.REF_REKENING as rk')
                    ->where('rk.REKENING_TAHUN',$tahun)
                    ->where('rk.REKENING_KUNCI','0')
                    ->where('rk.REKENING_KODE','LIKE','4%');
        $pendapatan = $pendapatan->where(DB::raw('length(rk."REKENING_KODE")'),'<=',15);
        $pendapatan=$pendapatan->leftJoin('BUDGETING.RKP_LAMP_1 as dp',function($join) use ($tahun){
                        $join->on('dp.TAHUN','=','rk.REKENING_TAHUN')
                             ->where('dp.REKENING_KODE','like',DB::raw('rk."REKENING_KODE"'."||'%'"));
                    })
                    ->leftJoin('BUDGETING.DAT_PENDAPATAN as pp',function($join) use ($tahun){
                        $join->on('pp.PENDAPATAN_TAHUN','=','rk.REKENING_TAHUN')
                             ->on('pp.REKENING_ID','=','rk.REKENING_ID');
                    })
                    ->select(DB::raw('1||SUBSTRING(rk."REKENING_KODE",2,4) as koderekening'),
                             'rk.REKENING_KODE as koderek',
                             'rk.REKENING_NAMA as namarek',
                             'pp.PENDAPATAN_DASHUK as dashuk',
                             DB::raw('SUM(dp."NILAI_MURNI") as nilai')
                             )
                    ->groupBy('rk.REKENING_KODE','rk.REKENING_NAMA','pp.PENDAPATAN_DASHUK')
                    ->orderBy('rk.REKENING_KODE')
                    ->get();
        //print_r($pendapatan);exit;
        foreach($pendapatan as $pd){
            if(strlen($pd->koderek)==1){
                $tabel[$idx]['tingkat']=1;
                $tabel[$idx]['koderekening']=$pd->koderek;
                $tabel[$idx]['namarekening']=ucwords(strtolower($pd->namarek));
                $tabel[$idx]['totalrekening']=$pd->nilai;
                $tabel[$idx]['dashuk']=NULL;
                $tabel[$idx]['namajumlah']=NULL;
                $tabel[$idx]['totaljumlah']=NULL;
                $idx+=1;
            }
            elseif(strlen($pd->koderek)<=3){
                $total_pendapatan+=$pd->nilai;

                $tabel[$idx]['tingkat']=2;
                $tabel[$idx]['koderekening']=$pd->koderek;
                $tabel[$idx]['namarekening']=ucwords(strtolower($pd->namarek));
                $tabel[$idx]['totalrekening']=$pd->nilai;
                $tabel[$idx]['dashuk']=$pd->dashuk;
                $tabel[$idx]['namajumlah']=NULL;
                $tabel[$idx]['totaljumlah']=NULL;
                $idx+=1;
            }
            else{
                if($pd->nilai>0){
                    $tabel[$idx]['tingkat']=3;
                    $tabel[$idx]['koderekening']=$pd->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($pd->namarek));
                    $tabel[$idx]['totalrekening']=$pd->nilai;
                    $tabel[$idx]['dashuk']=$pd->dashuk;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
            }
        }
        $tabel[$idx]['tingkat']=4;
        $tabel[$idx]['koderekening']=NULL;
        $tabel[$idx]['namarekening']=NULL;
        $tabel[$idx]['totalrekening']=NULL;
        $tabel[$idx]['dashuk']=NULL;
        $tabel[$idx]['namajumlah']="Jumlah Pendapatan";
        $tabel[$idx]['totaljumlah']=$total_pendapatan;
        $idx+=1;
        //print_r($tabel);exit;//CEK PENDAPATAN
        
        $btl=DB::table('REFERENSI.REF_REKENING as rk')
                    ->where('rk.REKENING_TAHUN',$tahun)
                    ->where('rk.REKENING_KUNCI','0')
                    ->where('rk.REKENING_KODE','LIKE','5%')
                    ->whereNotIn(DB::raw('substr(rk."REKENING_KODE",1,3)'),['5.2']);
        $btl = $btl->where(DB::raw('length(rk."REKENING_KODE")'),'<=',15);
        $btl=$btl->leftJoin('BUDGETING.RKP_LAMP_1 as dp',function($join) use ($tahun){
                    $join->on('dp.TAHUN','=','rk.REKENING_TAHUN')
                         ->where('dp.REKENING_KODE','like',DB::raw('rk."REKENING_KODE"'."||'%'"));
                })
                ->leftJoin('BUDGETING.DAT_BTL as pp',function($join) use ($tahun){
                        $join->on('pp.BTL_TAHUN','=','rk.REKENING_TAHUN')
                             ->on('pp.REKENING_ID','=','rk.REKENING_ID');
                    })
              ->select(DB::raw('2||SUBSTRING(rk."REKENING_KODE",2,4) as koderekening'),
                       'rk.REKENING_KODE as koderek',
                       'rk.REKENING_NAMA as namarek',
                       'pp.BTL_DASHUK as dashuk',
                       DB::raw('SUM(dp."NILAI_MURNI") as nilai'))
              ->groupBy('rk.REKENING_KODE','rk.REKENING_NAMA','pp.BTL_DASHUK')
              ->orderBy('rk.REKENING_KODE')
              ->get();
        //print_r($btl);exit;
        foreach($btl as $bt){
            if(strlen($bt->koderek)==1){
                $tabel[$idx]['tingkat']=1;
                $tabel[$idx]['koderekening']=$bt->koderek;
                $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                $tabel[$idx]['totalrekening']=$bt->nilai;
                $tabel[$idx]['dashuk']=NULL;
                $tabel[$idx]['namajumlah']=NULL;
                $tabel[$idx]['totaljumlah']=NULL;
                $idx+=1;
            }
            elseif(strlen($bt->koderek)<=3){
                $total_belanja+=$bt->nilai;

                $tabel[$idx]['tingkat']=2;
                $tabel[$idx]['koderekening']=$bt->koderek;
                $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                $tabel[$idx]['totalrekening']=$bt->nilai;
                $tabel[$idx]['dashuk']=$bt->dashuk;
                $tabel[$idx]['namajumlah']=NULL;
                $tabel[$idx]['totaljumlah']=NULL;
                $idx+=1;
            }
            else{
                if($bt->nilai>0){
                    $tabel[$idx]['tingkat']=3;
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['dashuk']=$bt->dashuk;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
            }
        }
        
        $bl=DB::table('REFERENSI.REF_REKENING as rk')
                    ->where('rk.REKENING_TAHUN',$tahun)
                    ->where('rk.REKENING_KUNCI','0')
                    ->where('rk.REKENING_KODE','LIKE','5.2%');
        $bl = $bl->where(DB::raw('length(rk."REKENING_KODE")'),'<=',15);
        $bl = $bl->leftJoin('BUDGETING.RKP_LAMP_1 as dp',function($join) use ($tahun){
                    $join->on('dp.TAHUN','=','rk.REKENING_TAHUN')
                         ->where('dp.REKENING_KODE','like',DB::raw('rk."REKENING_KODE"'."||'%'"));
                });
        $bl = $bl->select(DB::raw('2||SUBSTRING(rk."REKENING_KODE",2,4) as koderekening'),
                        'rk.REKENING_KODE as koderek',
                        'rk.REKENING_NAMA as namarek',
                        DB::raw('SUM(dp."NILAI_MURNI") as nilai')
                    )
                ->groupBy('rk.REKENING_TAHUN','rk.REKENING_KODE','rk.REKENING_NAMA')
                ->orderBy('rk.REKENING_KODE')
                ->get();
        //print_r($bl);exit;
        foreach($bl as $bt){
            if(strlen($bt->koderek)==1){
                $tabel[$idx]['tingkat']=1;
                $tabel[$idx]['tiperekening']=2;
                $tabel[$idx]['koderekening']=$bt->koderek;
                $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                $tabel[$idx]['totalrekening']=$bt->nilai;
                $tabel[$idx]['dashuk']=NULL;
                $tabel[$idx]['namajumlah']=NULL;
                $tabel[$idx]['totaljumlah']=NULL;
                $idx+=1;
            }
            elseif(strlen($bt->koderek)<=3){
                $total_belanja+=$bt->nilai;

                $tabel[$idx]['tingkat']=2;
                $tabel[$idx]['koderekening']=$bt->koderek;
                $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                $tabel[$idx]['totalrekening']=$bt->nilai;
                $tabel[$idx]['dashuk']=NULL;
                $tabel[$idx]['namajumlah']=NULL;
                $tabel[$idx]['totaljumlah']=NULL;
                $idx+=1;
            }
            else{
                if($bt->nilai>0){
                    $tabel[$idx]['tingkat']=3;
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['dashuk']=NULL;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
            }
        }
        
        $tabel[$idx]['tingkat']=4;
        $tabel[$idx]['koderekening']=NULL;
        $tabel[$idx]['namarekening']=NULL;
        $tabel[$idx]['totalrekening']=NULL;
        $tabel[$idx]['dashuk']=NULL;
        $tabel[$idx]['namajumlah']="Jumlah Belanja";
        $tabel[$idx]['totaljumlah']=round($total_belanja);
        $idx+=1;

        $surplus=$total_pendapatan-$total_belanja;
        $tabel[$idx]['tingkat']=4;
        $tabel[$idx]['koderekening']=NULL;
        $tabel[$idx]['namarekening']=NULL;
        $tabel[$idx]['totalrekening']=NULL;
        $tabel[$idx]['dashuk']=NULL;
        $tabel[$idx]['namajumlah']="Total Surplus/(Defisit)";
        $tabel[$idx]['totaljumlah']=round($surplus);
        $idx+=1;
        
        $penerimaan=DB::table('REFERENSI.REF_REKENING as rk')
                    ->where('rk.REKENING_TAHUN',$tahun)
                    ->where('rk.REKENING_KUNCI','0')
                    ->where('rk.REKENING_KODE','LIKE','6%')
                    ->whereNotIn(DB::raw('substr(rk."REKENING_KODE",1,3)'),['6.2','6.3','6.4']);
        $penerimaan = $penerimaan->where(DB::raw('length(rk."REKENING_KODE")'),'<=',15);
        $penerimaan=$penerimaan->leftJoin('BUDGETING.RKP_LAMP_1 as dp',function($join) use ($tahun){
                        $join->on('dp.TAHUN','=','rk.REKENING_TAHUN')
                             ->where('dp.REKENING_KODE','like',DB::raw('rk."REKENING_KODE"'."||'%'"));
                    })
                    ->leftJoin('BUDGETING.DAT_PEMBIAYAAN as pp',function($join) use ($tahun){
                        $join->on('pp.PEMBIAYAAN_TAHUN','=','rk.REKENING_TAHUN')
                             ->on('pp.REKENING_ID','=','rk.REKENING_ID');
                    })
                    ->select(DB::raw('3||SUBSTRING(rk."REKENING_KODE",2,4) as koderekening'),
                           'rk.REKENING_KODE as koderek',
                           'rk.REKENING_NAMA as namarek',
                           'pp.PEMBIAYAAN_DASHUK as dashuk',
                           DB::raw('SUM(dp."NILAI_MURNI") as nilai'))
                    ->groupBy('rk.REKENING_KODE','rk.REKENING_NAMA','pp.PEMBIAYAAN_DASHUK')
                    ->orderBy('rk.REKENING_KODE')
                    ->get();
        //print_r($penerimaan);exit;
        foreach($penerimaan as $bt){
            if(strlen($bt->koderek)==1){
                $tabel[$idx]['tingkat']=1;
                $tabel[$idx]['tiperekening']=3;
                $tabel[$idx]['koderekening']=$bt->koderek;
                $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                $tabel[$idx]['totalrekening']=$bt->nilai;
                $tabel[$idx]['dashuk']=NULL;
                $tabel[$idx]['namajumlah']=NULL;
                $tabel[$idx]['totaljumlah']=NULL;
                $idx+=1;
            }
            elseif(strlen($bt->koderek)<=3){
                $total_penerimaan+=$bt->nilai;

                $tabel[$idx]['tingkat']=2;
                $tabel[$idx]['koderekening']=$bt->koderek;
                $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                $tabel[$idx]['totalrekening']=$bt->nilai;
                $tabel[$idx]['dashuk']=$bt->dashuk;
                $tabel[$idx]['namajumlah']=NULL;
                $tabel[$idx]['totaljumlah']=NULL;
                $idx+=1;
            }
            else{
                if(($bt->nilai>0) and substr($bt->koderek,0,3)=="6.1"){
                    $tabel[$idx]['tingkat']=3;
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['dashuk']=$bt->dashuk;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
            }
        }
        
        $tabel[$idx]['tingkat']=4;
        $tabel[$idx]['koderekening']=NULL;
        $tabel[$idx]['namarekening']=NULL;
        $tabel[$idx]['totalrekening']=NULL;
        $tabel[$idx]['dashuk']=NULL;
        $tabel[$idx]['namajumlah']="Jumlah Penerimaan Pembiayaan";
        $tabel[$idx]['totaljumlah']=$total_penerimaan;
        $idx+=1;
        
        $pengeluaran=DB::table('REFERENSI.REF_REKENING as rk')
                    ->where('rk.REKENING_TAHUN',$tahun)
                    ->where('rk.REKENING_KUNCI','0')
                    ->where('rk.REKENING_KODE','LIKE','6.2%');
        $pengeluaran = $pengeluaran->where(DB::raw('length(rk."REKENING_KODE")'),'<=',15);
        $pengeluaran=$pengeluaran->leftJoin('BUDGETING.RKP_LAMP_1 as dp',function($join) use ($tahun){
                        $join->on('dp.TAHUN','=','rk.REKENING_TAHUN')
                             ->where('dp.REKENING_KODE','like',DB::raw('rk."REKENING_KODE"'."||'%'"));
                    })
                    ->leftJoin('BUDGETING.DAT_PEMBIAYAAN as pp',function($join) use ($tahun){
                        $join->on('pp.PEMBIAYAAN_TAHUN','=','rk.REKENING_TAHUN')
                             ->on('pp.REKENING_ID','=','rk.REKENING_ID');
                    })
                    ->select(DB::raw('3||SUBSTRING(rk."REKENING_KODE",2,4) as koderekening'),
                           'rk.REKENING_KODE as koderek',
                           'rk.REKENING_NAMA as namarek',
                           'pp.PEMBIAYAAN_DASHUK as dashuk',
                           DB::raw('SUM(dp."NILAI_MURNI") as nilai'))
                    ->groupBy('rk.REKENING_KODE','rk.REKENING_NAMA','pp.PEMBIAYAAN_DASHUK')
                    ->orderBy('rk.REKENING_KODE')
                    ->get();
        //print_r($pendapatan);exit;
        foreach($pengeluaran as $bt){
            if(strlen($bt->koderek)==1){
                $tabel[$idx]['tingkat']=1;
                $tabel[$idx]['tiperekening']=3;
                $tabel[$idx]['koderekening']=$bt->koderek;
                $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                $tabel[$idx]['totalrekening']=$bt->nilai;
                $tabel[$idx]['dashuk']=NULL;
                $tabel[$idx]['namajumlah']=NULL;
                $tabel[$idx]['totaljumlah']=NULL;
                $idx+=1;
            }
            elseif(strlen($bt->koderek)<=3){
                $total_pengeluaran+=$bt->nilai;

                $tabel[$idx]['tingkat']=2;
                $tabel[$idx]['koderekening']=$bt->koderek;
                $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                $tabel[$idx]['totalrekening']=$bt->nilai;
                $tabel[$idx]['dashuk']=$bt->dashuk;
                $tabel[$idx]['namajumlah']=NULL;
                $tabel[$idx]['totaljumlah']=NULL;
                $idx+=1;
            }
            else{

                if($bt->nilai>0){
                    $tabel[$idx]['tingkat']=3;
                    $tabel[$idx]['koderekening']=$bt->koderek;
                    $tabel[$idx]['namarekening']=ucwords(strtolower($bt->namarek));
                    $tabel[$idx]['totalrekening']=$bt->nilai;
                    $tabel[$idx]['dashuk']=$bt->dashuk;
                    $tabel[$idx]['namajumlah']=NULL;
                    $tabel[$idx]['totaljumlah']=NULL;
                    $idx+=1;
                }
            }
        }
        
        $tabel[$idx]['tingkat']=4;
        $tabel[$idx]['koderekening']=NULL;
        $tabel[$idx]['namarekening']=NULL;
        $tabel[$idx]['totalrekening']=NULL;
        $tabel[$idx]['dashuk']=NULL;
        $tabel[$idx]['namajumlah']="Jumlah Pengeluaran Pembiayaan";
        $tabel[$idx]['totaljumlah']=$total_pengeluaran;
        $idx+=1;

        $netto=$total_penerimaan-$total_pengeluaran;
        $tabel[$idx]['tingkat']=4;
        $tabel[$idx]['koderekening']=NULL;
        $tabel[$idx]['namarekening']=NULL;
        $tabel[$idx]['totalrekening']=NULL;
        $tabel[$idx]['dashuk']=NULL;
        $tabel[$idx]['namajumlah']="Pembiayaan Netto";
        $tabel[$idx]['totaljumlah']=$netto;
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn,
                            'detil'=>$tabel,'totalpendapatan'=>$total_pendapatan,'totalbelanja'=>$total_belanja,'totalpenerimaan'=>$total_penerimaan,'totalpengeluaran'=>$total_pengeluaran               
                            );
        return View('budgeting.lampiran.perwal-1_ed',$data);

        /*
        $skpd       = SKPD::where('SKPD_ID',$id)->first();

        $bl1     = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                        //->where('SKPD_ID',$s)
                        ->whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->sum('RINCIAN_TOTAL');

        $urusan     = Urusan::JOIN('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                            ->JOIN('REFERENSI.REF_URUSAN_KATEGORI1','REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID','=','REF_URUSAN.URUSAN_KAT1_ID')
                            ->where('REF_URUSAN_SKPD.SKPD_ID',$id)
                            ->first();
                            //dd($urusan);
       $rb1 = Rekening::where('REKENING_KODE','LIKE','5.2.1')->first();                                     
       $rb2 = Rekening::where('REKENING_KODE','LIKE','5.2.2')->first();                                     
       $rb3 = Rekening::where('REKENING_KODE','LIKE','5.2.3')->first(); 

       $rb11 = Rekening::where('REKENING_KODE','LIKE','5.2.1%')->first();                                     

       $bl1     = Rincian::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                        ->where('REKENING_KODE','like',$rb1->REKENING_KODE.'%')
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->orderBy("REKENING_KODE")
                        ->groupBy("REKENING_KODE", "REKENING_NAMA")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("RINCIAN_TOTAL") as pagu ')
                        ->get();

        $bl2     = Rincian::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                        ->where('REKENING_KODE','like',$rb2->REKENING_KODE.'%')
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->orderBy("REKENING_KODE")
                        ->groupBy("REKENING_KODE", "REKENING_NAMA")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("RINCIAN_TOTAL") as pagu ')
                        ->get();                

        $bl3     = Rincian::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                        ->where('REKENING_KODE','like',$rb3->REKENING_KODE.'%')
                        ->whereHas('bl',function($r) use($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->orderBy("REKENING_KODE")
                        ->groupBy("REKENING_KODE", "REKENING_NAMA")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("RINCIAN_TOTAL") as pagu ')
                        ->get();                  
     

        $bl_rek         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('BUDGETING.DAT_RINCIAN','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->groupBy("SKPD_KODE", "SKPD_NAMA", "DAT_BL.KEGIATAN_ID", "REKENING_KODE", "REKENING_NAMA")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", "DAT_BL"."KEGIATAN_ID", "REKENING_KODE", "REKENING_NAMA", SUM("RINCIAN_TOTAL") AS pagu')
                        ->get();                 


        $rek1 = Rekening::where('REKENING_KODE','LIKE','5.1.1')->first();                
        $rek2 = Rekening::where('REKENING_KODE','LIKE','5.1.2')->first();                
        $rek3 = Rekening::where('REKENING_KODE','LIKE','5.1.3')->first();                
        $rek4 = Rekening::where('REKENING_KODE','LIKE','5.1.4')->first();                
        $rek5 = Rekening::where('REKENING_KODE','LIKE','5.1.5')->first();                
        $rek6 = Rekening::where('REKENING_KODE','LIKE','5.1.6')->first();                
        $rek7 = Rekening::where('REKENING_KODE','LIKE','5.1.7')->first();                
        $rek8 = Rekening::where('REKENING_KODE','LIKE','5.1.8')->first(); 

        $rek11 = Rekening::where('REKENING_KODE','LIKE','5.1.1.01')->first();   
        $rek12 = Rekening::where('REKENING_KODE','LIKE','5.1.2.01')->first();   
        $rek13 = Rekening::where('REKENING_KODE','LIKE','5.1.3.01')->first();   
        $rek14 = Rekening::where('REKENING_KODE','LIKE','5.1.4.01')->first();   
        $rek15 = Rekening::where('REKENING_KODE','LIKE','5.1.5.01')->first();   
        $rek16 = Rekening::where('REKENING_KODE','LIKE','5.1.6.01')->first();   
        $rek17 = Rekening::where('REKENING_KODE','LIKE','5.1.7.01')->first();   
        $rek18 = Rekening::where('REKENING_KODE','LIKE','5.1.8.01')->first(); 

        $btlrek1 = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '5.1.1.01')
                        ->get(); 
        $btlrek2 = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '5.1.1.02')
                        ->get(); 
        $btlrek3 = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '5.1.1.03')
                        ->get(); 
        $btlrek4 = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '5.1.1.04')
                        ->get(); 
         $btlrek5 = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '5.1.1.05')
                        ->get(); 
         $btlrek6 = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '5.1.1.06')
                        ->get(); 
                                                                      


        $btl1       = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like',$rek1->REKENING_KODE.'%')
                        ->groupBy("REKENING_KODE", "REKENING_NAMA")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("BTL_TOTAL") as pagu ')
                        ->get(); 
         $btl2       = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like',$rek2->REKENING_KODE.'%')
                        ->groupBy("REKENING_KODE", "REKENING_NAMA")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("BTL_TOTAL") as pagu ')
                        ->get(); 
         $btl3       = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like',$rek3->REKENING_KODE.'%')
                        ->groupBy("REKENING_KODE", "REKENING_NAMA")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("BTL_TOTAL") as pagu ')
                        ->get();                               
         $btl4       = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like',$rek4->REKENING_KODE.'%')
                        ->groupBy("REKENING_KODE", "REKENING_NAMA")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("BTL_TOTAL") as pagu ')
                        ->get(); 
          $btl5       = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like',$rek5->REKENING_KODE.'%')
                        ->groupBy("REKENING_KODE", "REKENING_NAMA")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("BTL_TOTAL") as pagu ')
                        ->get(); 
          $btl6       = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like',$rek6->REKENING_KODE.'%')
                        ->groupBy("REKENING_KODE", "REKENING_NAMA")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("BTL_TOTAL") as pagu ')
                        ->get(); 
          $btl7       = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like',$rek7->REKENING_KODE.'%')
                        ->groupBy("REKENING_KODE", "REKENING_NAMA")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("BTL_TOTAL") as pagu ')
                        ->get(); 
          $btl8       = BTL::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like',$rek8->REKENING_KODE.'%')
                        ->groupBy("REKENING_KODE", "REKENING_NAMA")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("BTL_TOTAL") as pagu ')
                        ->get(); 


        $pendapatan = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->orderby('REKENING_KODE')
                        ->get();

        $pendapatan1 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1.01%')
                        ->get(); 
        $pendapatan2 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1.02%')
                        ->get(); 
        $pendapatan3 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1.03%')
                        ->get();
        $pendapatan4 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1.04%')
                        ->get();
        $pendapatan5 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1.05%')
                        ->get();
        $pendapatan6 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1.07%')
                        ->get(); 
        $pendapatan7 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1.08%')
                        ->get(); 
        $pendapatan8 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1.11%')
                        ->get();  
        $pendapatan9 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1.12%')
                        ->get();
         //pajak retribusi               
         $pendapatan10 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.2.01%')
                        ->get();  
          $pendapatan11 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.2.02%')
                        ->get(); 
          $pendapatan12 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.2.03%')
                        ->get(); 
        //Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan
          $pendapatan13 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.3.01%')
                        ->get(); 
         // Lain-lain Pendapatan Asli Daerah yang Sah
          $pendapatan14 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.4.01%')
                        ->get(); 
           $pendapatan15 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.4.02%')
                        ->get(); 
           $pendapatan16 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.4.11%')
                        ->get(); 
           $pendapatan17 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.4.14%')
                        ->get();  
           $pendapatan18 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.4.16%')
                        ->get(); 

            $pendapatan19 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.2.1.01%')
                        ->get();    
            $pendapatan20 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.2.1.02%')
                        ->get();  
            $pendapatan21 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.2.2.01%')
                        ->get(); 
            $pendapatan22 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.2.3.01%')
                        ->get();   
            $pendapatan23 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.3.1.01%')
                        ->get(); 
            $pendapatan24 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.3.3.01%')
                        ->get();  
            $pendapatan25 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.3.5.01%')
                        ->get();     

            $totpad = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1%')
                        ->sum('PENDAPATAN_TOTAL'); 

            $totpad1 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1%')
                        ->sum('PENDAPATAN_TOTAL'); 

            $totpad2 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.2%')
                        ->sum('PENDAPATAN_TOTAL');  

              $totpad3 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.3%')
                        ->sum('PENDAPATAN_TOTAL');     

              $totpad4 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.4%')
                        ->sum('PENDAPATAN_TOTAL'); 

               $totpad5 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.2%')
                        ->sum('PENDAPATAN_TOTAL');
               $totpad6 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.2.1%')
                        ->sum('PENDAPATAN_TOTAL'); 
                $totpad7 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.2.2%')
                        ->sum('PENDAPATAN_TOTAL');
                $totpad8 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.2.3%')
                        ->sum('PENDAPATAN_TOTAL'); 
                $totpad9 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.3%')
                        ->sum('PENDAPATAN_TOTAL');
                $totpad10 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.3.1%')
                        ->sum('PENDAPATAN_TOTAL'); 
                $totpad11 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.3.3%')
                        ->sum('PENDAPATAN_TOTAL'); 


                        $pembiayaan = Pembiayaan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')->get();  

            $pmb1           = Pembiayaan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','6.1%');})->where('PEMBIAYAAN_TAHUN',$tahun)->get();         
    
            $pmb2           = Pembiayaan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','6.2%');})->where('PEMBIAYAAN_TAHUN',$tahun)->get();                                


        
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn,        
                            'skpd'          =>$skpd,        
                            'urusan'        =>$urusan,        
                            'bl_rek'        =>$bl_rek,        
                            'btl1'           =>$btl1,        
                            'btl2'           =>$btl2,        
                            'btl3'           =>$btl3,        
                            'btl4'           =>$btl4,        
                            'btl5'           =>$btl5,        
                            'btl6'           =>$btl6,        
                            'btl7'           =>$btl7,        
                            'btl8'           =>$btl8,        
                            'pendapatan'    =>$pendapatan,        
                            'pendapatan1'    =>$pendapatan1,        
                            'pendapatan2'    =>$pendapatan2,        
                            'pendapatan3'    =>$pendapatan3,        
                            'pendapatan4'    =>$pendapatan4,        
                            'pendapatan5'    =>$pendapatan5,        
                            'pendapatan6'    =>$pendapatan6,        
                            'pendapatan7'    =>$pendapatan7,        
                            'pendapatan8'    =>$pendapatan8,        
                            'pendapatan9'    =>$pendapatan9,        
                            'pendapatan10'    =>$pendapatan10,        
                            'pendapatan11'    =>$pendapatan11,        
                            'pendapatan12'    =>$pendapatan12,        
                            'pendapatan13'    =>$pendapatan13,        
                            'pendapatan14'    =>$pendapatan14,        
                            'pendapatan15'    =>$pendapatan15,        
                            'pendapatan16'    =>$pendapatan16,        
                            'pendapatan17'    =>$pendapatan17,        
                            'pendapatan18'    =>$pendapatan18,        
                            'pendapatan19'    =>$pendapatan19,        
                            'pendapatan20'    =>$pendapatan20,        
                            'pendapatan21'    =>$pendapatan21,        
                            'pendapatan22'    =>$pendapatan22,        
                            'pendapatan23'    =>$pendapatan23,        
                            'pendapatan24'    =>$pendapatan24,        
                            'pendapatan25'    =>$pendapatan25,        
                            'totpad'          =>$totpad,        
                            'totpad1'          =>$totpad1,        
                            'totpad2'          =>$totpad2,        
                            'totpad3'          =>$totpad3,        
                            'totpad4'          =>$totpad4,        
                            'totpad5'          =>$totpad5,        
                            'totpad6'          =>$totpad6,        
                            'totpad7'          =>$totpad7,        
                            'totpad8'          =>$totpad8,        
                            'totpad9'          =>$totpad9,        
                            'totpad10'         =>$totpad10,        
                            'totpad11'         =>$totpad11,        
                            'bl1'          =>$bl1,        
                            'bl2'          =>$bl2,        
                            'bl3'          =>$bl3,        
                            'rek1'          =>$rek1,        
                            'rek2'          =>$rek2,        
                            'rek3'          =>$rek3,        
                            'rek4'          =>$rek4,        
                            'rek5'          =>$rek5,        
                            'rek6'          =>$rek6,        
                            'rek7'          =>$rek7,        
                            'rek8'          =>$rek8, 
                            'rek11'          =>$rek11,               
                            'rek12'          =>$rek12,               
                            'rek13'          =>$rek13,               
                            'rek14'          =>$rek14,               
                            'rek15'          =>$rek15,               
                            'rek16'          =>$rek16,               
                            'rek17'          =>$rek17,               
                            'rek18'          =>$rek18,               
                            'rb1'          =>$rb1,               
                            'rb2'          =>$rb2,               
                            'rb3'          =>$rb3,               
                            'pembiayaan'          =>$pembiayaan,               
                            'btlrek1'          =>$btlrek1,               
                            'btlrek2'          =>$btlrek2,               
                            'btlrek3'          =>$btlrek3,               
                            'btlrek4'          =>$btlrek4,               
                            'btlrek5'          =>$btlrek5,               
                            'btlrek6'          =>$btlrek6,               
                            'pmb1'          =>$pmb1,               
                            'pmb2'          =>$pmb2,               
                            );
        return View('budgeting.lampiran.perwal-1',$data);
        */
        
    }

    public function perwal2index($tahun,$status){
        $tipe = 'Perwal'; 
        if(Auth::user()->level == 2) $skpd = SKPD::where('SKPD_ID',UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID'))->get();
        else  $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();
        $data       = ['tahun'=>$tahun,'status'=>$status,'tipe'=>$tipe,'skpd'=>$skpd,'i'=>1];
        //if($tipe == 'apbd') return View('budgeting.lampiran.indexAPBD',$data);
        return View('budgeting.lampiran.perwal2_index',$data);
    }


    public function perwal2($tahun,$status,$id){
        //dd($id);
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD')->value('TAHAPAN_ID');
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        $skpd       = SKPD::where('SKPD_ID',$id)->first();

        $urusan     = Urusan::JOIN('REFERENSI.REF_URUSAN_SKPD','REF_URUSAN_SKPD.URUSAN_ID','=','REF_URUSAN.URUSAN_ID')
                            ->JOIN('REFERENSI.REF_URUSAN_KATEGORI1','REF_URUSAN_KATEGORI1.URUSAN_KAT1_ID','=','REF_URUSAN.URUSAN_KAT1_ID')
                            ->where('REF_URUSAN_SKPD.SKPD_ID',$id)
                            ->first();
                            //dd($urusan);
        $bl         = BL::JOIN('BUDGETING.DAT_RINCIAN','DAT_RINCIAN.BL_ID','=','DAT_BL.BL_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('BL_VALIDASI',1)
                        ->sum('RINCIAN_TOTAL');                

       $bl_prog         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->JOIN('REFERENSI.REF_PROGRAM','REF_KEGIATAN.PROGRAM_ID','=','REF_PROGRAM.PROGRAM_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->groupBy("SKPD_KODE", "SKPD_NAMA", "REF_PROGRAM.PROGRAM_ID", "PROGRAM_KODE", "PROGRAM_NAMA")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", "REF_PROGRAM"."PROGRAM_ID", "PROGRAM_KODE", "PROGRAM_NAMA" ')
                        ->get(); 

        /*kegaiatn sesuai pagu                  */
        $bl_keg         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->orderBy('KEGIATAN_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", "PROGRAM_ID", "REF_KEGIATAN"."KEGIATAN_ID", "KEGIATAN_KODE", "KEGIATAN_NAMA", "BL_PAGU" ')
                        ->get(); 
                                        

        $bl_rek         = BL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('BUDGETING.DAT_RINCIAN','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                        ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('BL_DELETED',0)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->groupBy("SKPD_KODE", "SKPD_NAMA", "DAT_BL.KEGIATAN_ID", "REKENING_KODE", "REKENING_NAMA")
                        ->orderBy('SKPD_KODE')
                        ->selectRaw('"SKPD_KODE", "SKPD_NAMA", "DAT_BL"."KEGIATAN_ID", "REKENING_KODE", "REKENING_NAMA", SUM("RINCIAN_TOTAL") AS pagu')
                        ->get();                 


    $btl_rek_1   =Rekening::where('REKENING_KODE','like','5.1.1')->where('REKENING_TAHUN',$tahun)->first();
    $btl_rek_2   =Rekening::where('REKENING_KODE','like','5.1.2')->where('REKENING_TAHUN',$tahun)->first();
    $btl_rek_3   =Rekening::where('REKENING_KODE','like','5.1.3')->where('REKENING_TAHUN',$tahun)->first();
    $btl_rek_4   =Rekening::where('REKENING_KODE','like','5.1.4')->where('REKENING_TAHUN',$tahun)->first();
    $btl_rek_5   =Rekening::where('REKENING_KODE','like','5.1.5')->where('REKENING_TAHUN',$tahun)->first();
    $btl_rek_6   =Rekening::where('REKENING_KODE','like','5.1.6')->where('REKENING_TAHUN',$tahun)->first();
    $btl_rek_7   =Rekening::where('REKENING_KODE','like','5.1.7')->where('REKENING_TAHUN',$tahun)->first();
    $btl_rek_8   =Rekening::where('REKENING_KODE','like','5.1.8')->where('REKENING_TAHUN',$tahun)->first();

    $btl_rek_1_1   =Rekening::where('REKENING_KODE','like','5.1.1.01')->where('REKENING_TAHUN',$tahun)->first();
    $btl_rek_1_2   =Rekening::where('REKENING_KODE','like','5.1.1.02')->where('REKENING_TAHUN',$tahun)->first();
                       

        $btl1_1       = BTL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BTL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->where('REKENING_KODE','like','5.1.1.01%')
                        ->groupBy("REKENING_KODE", "REKENING_NAMA", "BTL_DASHUK")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("BTL_TOTAL") as pagu, "BTL_DASHUK" ')
                        ->get(); 

        $btl1_2       = BTL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BTL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REF_SKPD.SKPD_ID',$id)
                        ->where('REKENING_KODE','like','5.1.1.02%')
                        ->groupBy("REKENING_KODE", "REKENING_NAMA", "BTL_DASHUK")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("BTL_TOTAL") as pagu, "BTL_DASHUK" ')
                        ->get();                 

        $pendapatan = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->orderby('REKENING_KODE')
                        ->get();

        $pendapatan1 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1.01%')
                        ->get(); 

        $pendapatan2 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1.02%')
                        ->get(); 

        $pendapatan3 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1.03%')
                        ->get();

        $pendapatan4 = Pendapatan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PENDAPATAN.REKENING_ID')
                        ->where('PENDAPATAN_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '4.1.1.04%')
                        ->get();                                                        


                        
        
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn,        
                            'skpd'          =>$skpd,        
                            'urusan'        =>$urusan,        
                            'bl_prog'       =>$bl_prog,        
                            'bl_keg'        =>$bl_keg,        
                            'bl_rek'        =>$bl_rek,                      
                            'pendapatan'    =>$pendapatan,        
                            'pendapatan1'    =>$pendapatan1,        
                            'pendapatan2'    =>$pendapatan2,        
                            'pendapatan3'    =>$pendapatan3,        
                            'pendapatan4'    =>$pendapatan4,        
                            'btl_rek_1'      =>$btl_rek_1,        
                            'btl_rek_1_1'      =>$btl_rek_1_1,        
                            'btl_rek_1_2'      =>$btl_rek_1_2, 
                            'btl1_1'        =>$btl1_1,       
                            'btl1_2'        =>$btl1_2,      
                            'bl'        =>$bl     
                            );

        return View('budgeting.lampiran.perwal-2',$data);
    }

    public function perwal3($tahun,$status){
        //dd($id);
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD')->value('TAHAPAN_ID');
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');


        $btl_rek_1   =Rekening::where('REKENING_KODE','like','5.1.4.01')->where('REKENING_TAHUN',$tahun)->first();      
        $btl_rek_2   =Rekening::where('REKENING_KODE','like','5.1.4.02')->where('REKENING_TAHUN',$tahun)->first();      
        $btl_rek_3   =Rekening::where('REKENING_KODE','like','5.1.4.03')->where('REKENING_TAHUN',$tahun)->first();
        $btl_rek_4   =Rekening::where('REKENING_KODE','like','5.1.4.04')->where('REKENING_TAHUN',$tahun)->first();      
        $btl_rek_5   =Rekening::where('REKENING_KODE','like','5.1.4.05')->where('REKENING_TAHUN',$tahun)->first();
        $btl_rek_6   =Rekening::where('REKENING_KODE','like','5.1.4.06')->where('REKENING_TAHUN',$tahun)->first();      
        $btl_rek_7   =Rekening::where('REKENING_KODE','like','5.1.4.07')->where('REKENING_TAHUN',$tahun)->first();                            

        $btl1_1       = BTL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BTL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like','5.1.4.01%')
                        ->groupBy("REKENING_KODE", "REKENING_NAMA", "BTL_DASHUK")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("BTL_TOTAL") as pagu, "BTL_DASHUK" ')
                        ->get(); 

        $btl1_2       = BTL::JOIN('REFERENSI.REF_SUB_UNIT','DAT_BTL.SUB_ID','=','REF_SUB_UNIT.SUB_ID')
                        ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                        ->JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_BTL.REKENING_ID')
                        ->where('BTL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like','5.1.4.05%')
                        ->groupBy("REKENING_KODE", "REKENING_NAMA", "BTL_DASHUK")
                        ->selectRaw('"REKENING_KODE", "REKENING_NAMA", sum("BTL_TOTAL") as pagu, "BTL_DASHUK" ')
                        ->get();                  
                        
        
        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn,        
                            'btl_rek_1'    =>$btl_rek_1,       
                            'btl_rek_2'    =>$btl_rek_2,       
                            'btl1_1'    =>$btl1_1,       
                            'btl1_2'    =>$btl1_2,       
                            );

        return View('budgeting.lampiran.perwal-3',$data);
    }


     public function perwal4($tahun,$status){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD')->value('TAHAPAN_ID');
        $tahapan=0;
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        $rincian = Rincian::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_RINCIAN.REKENING_ID')
                        ->join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.RINCIAN_ID')
                        ->where('BL_TAHUN',$tahun)
                        ->where('REKENING_KODE','like', '5.1.5%')
                        ->get();                                                      

        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn,        
                            'rincian'       =>$rincian,        
                            );

        return View('budgeting.lampiran.perwal-4',$data);
        
    }

    public function perwal5($tahun,$status){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD')->value('TAHAPAN_ID');
        $tahapan=0;
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

        $tabel=array();
        $idx=0;

        $total_gaji_murni=0;
        $total_nongaji_murni=0;
        $total_pegawai_murni=0;
        $total_jasa_murni=0;
        $total_modal_murni=0;
        $total_gaji=0;
        $total_nongaji=0;
        $total_pegawai=0;
        $total_jasa=0;
        $total_modal=0;

        $detil = DB::table('BUDGETING.RKP_LAMP_5 as lampiran')
                 ->where('lampiran.TAHUN',$tahun)
                 ->where('lampiran.TAHAPAN_ID',$tahapan)
                 ->select('lampiran.TAHUN',
                          'lampiran.URUSAN_KAT1_KODE',
                          'lampiran.URUSAN_KAT1_NAMA',
                          'lampiran.URUSAN_KODE',
                          'lampiran.URUSAN_NAMA',
                          'lampiran.FUNGSI_KODE',
                          'lampiran.FUNGSI_NAMA',
                          DB::raw('sum(lampiran."BTL_LAIN_MURNI") as btlnongajimurni'),
                          DB::raw('sum(lampiran."BTL_PEGAWAI_MURNI") as btlgajimurni'),
                          DB::raw('sum(lampiran."BL_PEGAWAI_MURNI") as pegawaimurni'),
                          DB::raw('sum(lampiran."BL_JASA_MURNI") as jasamurni'),
                          DB::raw('sum(lampiran."BL_MODAL_MURNI") as modalmurni'),
                          DB::raw("'f' as kode_urusan_ok"),
                          DB::raw("'f' as kode_fungsi_ok"),
                          DB::raw("0 as subfungsi_gaji_murni"),
                          DB::raw("0 as subfungsi_nongaji_murni"),
                          DB::raw("0 as subfungsi_pegawai_murni"),
                          DB::raw("0 as subfungsi_jasa_murni"),
                          DB::raw("0 as subufungsi_modal_murni"))
                 ->groupBy('lampiran.TAHUN',
                          'lampiran.URUSAN_KAT1_KODE',
                          'lampiran.URUSAN_KAT1_NAMA',
                          'lampiran.URUSAN_KODE',
                          'lampiran.URUSAN_NAMA',
                          'lampiran.FUNGSI_KODE',
                          'lampiran.FUNGSI_NAMA')
                 ->orderBy('lampiran.FUNGSI_KODE','asc')
                 ->orderBy('lampiran.URUSAN_KODE','asc')
                 ->get();
        //print_r($detil);exit;
        $old_urusan="";
        $old_kode_urusan="";
        $old_kode_fungsi="";
        for($i=0;$i<count($detil);$i++){
            $rs=$detil[$i];
            if($rs->URUSAN_KAT1_KODE!=$old_kode_fungsi){
                $rs->kode_fungsi_ok='t';
                $old_kode_fungsi=$rs->URUSAN_KAT1_KODE;
                $rekap = DB::table('BUDGETING.RKP_LAMP_5 as rekap')
                         ->where('rekap.TAHUN',$tahun)
                         ->where('rekap.TAHAPAN_ID',$tahapan)
                         ->where('rekap.URUSAN_KAT1_KODE',$rs->URUSAN_KAT1_KODE)
                         ->select(DB::raw('sum("BTL_LAIN_MURNI") as btlnongajimurni'),
                                  DB::raw('sum("BTL_PEGAWAI_MURNI") as btlgajimurni'),
                                  DB::raw('sum("BL_PEGAWAI_MURNI") as pegawaimurni'),
                                  DB::raw('sum("BL_JASA_MURNI") as jasamurni'),
                                  DB::raw('sum("BL_MODAL_MURNI") as modalmurni'))
                         ->get();
                //print_r($rekap);exit;
                $rs->subfungsi_nongaji_murni=$rekap[0]->btlnongajimurni;
                $rs->subfungsi_gaji_murni=$rekap[0]->btlgajimurni;
                $rs->subfungsi_pegawai_murni=$rekap[0]->pegawaimurni;
                $rs->subfungsi_jasa_murni=$rekap[0]->jasamurni;
                $rs->subfungsi_modal_murni=$rekap[0]->modalmurni;
            }
            
            if($rs->URUSAN_KODE!=$old_kode_urusan){
                $rs->kode_urusan_ok='t';
                $old_kode_urusan=$rs->URUSAN_KODE;
                $total_nongaji_murni+=$rs->btlnongajimurni;
                $total_gaji_murni+=$rs->btlgajimurni;
                $total_pegawai_murni+=$rs->pegawaimurni;
                $total_jasa_murni+=$rs->jasamurni;
                $total_modal_murni+=$rs->modalmurni;
            }
            
            array_push($tabel,$rs);
        }

        $data       = array('tahun'         =>$tahun,
                            'status'        =>$status,
                            'tgl'           =>$tgl,
                            'bln'           =>$bln,
                            'thn'           =>$thn, 
                            'detil'=>$tabel,
                            'totalnongajimurni'=>$total_nongaji_murni,
                            'totalgajimurni'=>$total_gaji_murni,
                            'totalpegawaimurni'=>$total_pegawai_murni,
                            'totaljasamurni'=>$total_jasa_murni,
                            'totalmodalmurni'=>$total_modal_murni 
                            );
        return View('budgeting.lampiran.perwal-5',$data);
    }
    
}
