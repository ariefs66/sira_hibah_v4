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

        if($status=='perubahan'){
            $bl         = BLPerubahan::where('BL_ID',$id)->first();
            $total      = RincianPerubahan::where('BL_ID',$id)->sum('RINCIAN_TOTAL');
            $rekening   = RincianPerubahan::where('BL_ID',$id)->orderBy('REKENING_ID')
                          ->groupBy('REKENING_ID')
                          ->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL, "REKENING_ID"')
                          ->get();
        }else{
            $bl         = BL::where('BL_ID',$id)->first();
            $total      = Rincian::where('BL_ID',$id)->sum('RINCIAN_TOTAL');
            $rekening   = Rincian::where('BL_ID',$id)->orderBy('REKENING_ID')
                          ->groupBy('REKENING_ID')
                          ->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL, "REKENING_ID"')
                          ->get();
        }               

        $indikator  = Indikator::where('BL_ID',$id)->orderBy('INDIKATOR')->get();
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');

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

            if($status=='perubahan'){
                $totalrek[$q]= RincianPerubahan::whereHas('rekening', function($x) use ($rek4){
                    $x->where('REKENING_KODE','like',$rek4->REKENING_KODE.'%');
                })->where('BL_ID',$id)->sum('RINCIAN_TOTAL');
                $totalreke[$s]= RincianPerubahan::whereHas('rekening', function($x) use ($rek3){
                    $x->where('REKENING_KODE','like',$rek3->REKENING_KODE.'%');
                })->where('BL_ID',$id)->sum('RINCIAN_TOTAL');
                $paket[$i]   = RincianPerubahan::where('BL_ID',$id)
                                            ->where('REKENING_ID',$r->REKENING_ID)
                                            ->groupBy('SUBRINCIAN_ID')
                                            ->groupBy('REKENING_ID')
                                            ->groupBy('RINCIAN_PAJAK')
                                            ->orderBy('SUBRINCIAN_ID')
                                            ->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL, "SUBRINCIAN_ID","REKENING_ID", "RINCIAN_PAJAK"')
                                            ->get();

                $k = 0;
                foreach($paket[$i] as $p){
                    $komponen[$i][$k++]    = RincianPerubahan::where('SUBRINCIAN_ID',$p->SUBRINCIAN_ID)
                                                    ->where('REKENING_ID',$p->REKENING_ID)
                                                    // ->whereHas('komponen',function($q){
                                                    //     $q->orderBy('KOMPONEN_NAMA');
                                                    // })
                                                    ->orderBy('KOMPONEN_ID')
                                                    ->get();
                }
            }
            else{    
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
                                            ->groupBy('RINCIAN_PAJAK')
                                            ->orderBy('SUBRINCIAN_ID')
                                            ->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL, "SUBRINCIAN_ID","REKENING_ID", "RINCIAN_PAJAK"')
                                            ->get();

                $k = 0;
                foreach($paket[$i] as $p){
                    $komponen[$i][$k++]    = Rincian::where('SUBRINCIAN_ID',$p->SUBRINCIAN_ID)
                                                    ->where('REKENING_ID',$p->REKENING_ID)
                                                    // ->whereHas('komponen',function($q){
                                                    //     $q->orderBy('KOMPONEN_NAMA');
                                                    // })
                                                    ->orderBy('KOMPONEN_ID')
                                                    ->get();
                } 
            }
                                                       
            $i++; 
            $q++; 
            $s++; 
        }
       
        if($status=='perubahan'){
            $totalBL    = RincianPerubahan::where('BL_ID',$id)->sum('RINCIAN_TOTAL');
        }else{
            $totalBL    = Rincian::where('BL_ID',$id)->sum('RINCIAN_TOTAL');
        }

        return View('budgeting.lampiran.rka',['tahun'=>$tahun,'status'=>$status,'bl'=>$bl,'indikator'=>$indikator,'rekening'=>$rekening,'tgl'=>$tgl,'bln'=>$bln,'thn'=>$thn,'total'=>$total,'paket'=>$paket,'m'=>0,'komponen'=>$komponen,'totalbl'=>$totalBL,'rek'=>$rek,'q'=>0,'s'=>0,'reke'=>$reke,'totalrek'=>$totalrek,'totalreke'=>$totalreke]);
    }

    public function dpa(){

    }

    public function rkpd($tahun,$status,$id){
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
        return View('budgeting.lampiran.rkpd',['tahun'=>$tahun,'status'=>$status,'skpd'=>$idSKPD,'pagu'=>$pagu,'program'=>$program,'i'=>0,'paguprogram'=>$paguprogram,'urusankode'=>'xxx','bidangkode'=>'xxx']);
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
        /*$blpagu = BL::join('REFERENSI.REF_SUB_UNIT', 'REF_SUB_UNIT.SUB_ID', '=', 'DAT_BL.SUB_ID') 
                    ->where('REF_SUB_UNIT.SKPD_ID',$id)->where('BL_VALIDASI',1)
                    ->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)
                    ->sum('BL_PAGU');
                    dd($blpagu);*/

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
                                ->where('BL_DELETED',0)
                                ->where('BL_PAGU','!=',0)                                
                                ->groupBy('KEGIATAN_ID')
                                ->selectRaw('SUM("BL_PAGU") AS pagu, "KEGIATAN_ID"')
                                ->get();
            $i++;
        }
        return View('budgeting.lampiran.ppas',['tahun'=>$tahun,'status'=>$status,'skpd'=>$idSKPD,'pagu'=>$pagu,'program'=>$program,'i'=>0,'paguprogram'=>$paguprogram,'urusankode'=>'xxx','bidangkode'=>'xxx']);
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
        $i              = 0;
        $j              = 0;
        $ppp    = array();
        $pppp    = array();
        foreach($program as $pr){
            if($status == 'murni') $stat    = BL::where('BL_TAHUN',$tahun);
            else $stat  = BLPerubahan::where('BL_TAHUN',$tahun);        
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
                $pppp[$i]           = Rincian::whereHas('bl',function($bl) use($tahun,$id,$keg,$idprog){
                                    $bl->where('BL_TAHUN',$tahun)
                                       ->whereHas('subunit',function($sub) use($id){
                                        $sub->where('SKPD_ID',$id);
                                    })->whereHas('kegiatan',function($prog) use($idprog){
                                            $prog->where('PROGRAM_ID',$idprog);
                                    });
                                })->sum('RINCIAN_TOTAL'); 
                $j++;
            }
            $i++;
        }

        return View('budgeting.lampiran.ppas_rincian',['tahun'=>$tahun,'status'=>$status,'skpd'=>$idSKPD,'pagu'=>$pagu,'program'=>$program,'i'=>0,'paguprogram'=>$paguprogram,'urusankode'=>'xxx','bidangkode'=>'xxx','ppp'=>$ppp,'pppp'=>$pppp,'j'=>0]);

    }

    public function ppasDownload($tahun,$status,$id){
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
        return View('budgeting.lampiran.ppas_download',['tahun'=>$tahun,'status'=>$status,'skpd'=>$idSKPD,'pagu'=>$pagu,'program'=>$program,'i'=>0,'paguprogram'=>$paguprogram,'urusankode'=>'xxx','bidangkode'=>'xxx']);
    }

    public function lampiran1($tahun,$status){
        $tahapan        = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD')->value('TAHAPAN_ID');
        $tgl        = Carbon\Carbon::now()->format('d');
        $gbln       = Carbon\Carbon::now()->format('m');
        $bln        = $this->bulan($gbln*1);
        $thn        = Carbon\Carbon::now()->format('Y');
        $pendapatan     = Pendapatan::whereHas('rekening',function($q){
                                $q->where('REKENING_KODE','like','4%');
                            })->sum('PENDAPATAN_TOTAL');
        $pad            = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.1%');})->sum('PENDAPATAN_TOTAL');
        $pad1           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.1.1%');})->sum('PENDAPATAN_TOTAL');
        $pad2           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.1.2%');})->sum('PENDAPATAN_TOTAL');
        $pad3           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.1.3%');})->sum('PENDAPATAN_TOTAL');
        $pad4           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.1.4%');})->sum('PENDAPATAN_TOTAL');
        
        $ibg            = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.2%');})->sum('PENDAPATAN_TOTAL');
        $ibg1           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.2.1%');})->sum('PENDAPATAN_TOTAL');
        $ibg2           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.2.2%');})->sum('PENDAPATAN_TOTAL');
        $ibg3           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.2.3%');})->sum('PENDAPATAN_TOTAL');
        
        $pdl            = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.3%');})->sum('PENDAPATAN_TOTAL');
        $pdl1           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.3.1%');})->sum('PENDAPATAN_TOTAL');
        $pdl2           = Pendapatan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','4.3.3%');})->sum('PENDAPATAN_TOTAL');

        $btl            = BTL::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1%');})->sum('BTL_TOTAL');
        $btl1           = BTL::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.1%');})->sum('BTL_TOTAL');
        $btl2           = BTL::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.3%');})->sum('BTL_TOTAL');
        $btl3           = BTL::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.4%');})->sum('BTL_TOTAL');
        $btl4           = BTL::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.7%');})->sum('BTL_TOTAL');
        $btl5           = BTL::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.1.8%');})->sum('BTL_TOTAL');
        
        $bl             = RekapRincian::whereHas('rekening',function($q){
                                $q->where('REKENING_KODE','like','5.2%');
                            })->where('TAHAPAN_ID',$tahapan)->sum('RINCIAN_TOTAL');       
        $bl1            = RekapRincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                                ->where('TAHAPAN_ID',$tahapan)->sum('RINCIAN_TOTAL');       
        $bl2            = RekapRincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                                ->where('TAHAPAN_ID',$tahapan)->sum('RINCIAN_TOTAL');       
        $bl3            = RekapRincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                                ->where('TAHAPAN_ID',$tahapan)->sum('RINCIAN_TOTAL');      

        $pmb1           = Pembiayaan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','6.1%');})->sum('PEMBIAYAAN_TOTAL');         
        $pmb2           = Pembiayaan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','6.2%');})->sum('PEMBIAYAAN_TOTAL');         
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

    public function lampiran2(){

    }

    public function lampiran3(){

    }

    public function lampiran4(){

    }

    public function lampiran5(){
    	
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
        if($tipe == 'pagu')
        $data   = DB::select('select "SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", SUM("BL_PAGU")
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
                            GROUP BY SKPD, "PROGRAM_KODE", "PROGRAM_NAMA"
                            ORDER BY SKPD, "PROGRAM_KODE"');
        elseif($tipe == 'rincian')
        $data   = DB::select('select "SKPD_KODE"||\'-\'||"SKPD_NAMA" AS SKPD, "PROGRAM_KODE", "PROGRAM_NAMA", SUM("RINCIAN_TOTAL")
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
                            GROUP BY SKPD, "PROGRAM_KODE", "PROGRAM_NAMA"
                            ORDER BY SKPD, "PROGRAM_KODE"');
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
