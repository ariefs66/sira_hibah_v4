<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Auth;
use DB;
use App\Model\UserBudget;
use App\Model\BL;
use App\Model\BTL;
use App\Model\Pendapatan;
use App\Model\Tahapan;
use App\Model\Staff;
use App\Model\Rincian;
use App\Model\SKPD;
use App\Model\Usulan;
use App\Model\Kamus;
use App\Model\PaguRincian;
use App\Model\BlPerubahan;
use Response;
class dashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index($tahun,$status){
    	$level 	= Auth::user()->level;
    	$bl 	= '';
    	$btl 	= '';
    	$pdp 	= '';
    	$pagu 	= 0;
    	$blv 	= 0;
    	$bln 	= 0;
    	$staff 	= '';
    	$b1=0;$b2=0;$b3=0;
        if($level == '99'){
            return View('budgeting.dewa',(['tahun'=>$tahun,'status'=>$status]));
        }

        if($level == 1 or $level == 2){
            $skpd   = $this->getSKPD($tahun);
    		$bl  	= BL::whereHas('subunit',function($q) use ($skpd){
                				$q->where('SKPD_ID',$skpd);
                		})->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->get();
    		$btl 	= BTL::whereHas('subunit',function($q) use ($skpd){
                                $q->where('SKPD_ID',$skpd);
                        })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
    		$pdp 	= Pendapatan::whereHas('subunit',function($q) use ($skpd){
                                $q->where('SKPD_ID',$skpd);
                        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
            $blv    = BL::whereHas('subunit',function($q) use ($skpd){
                                $q->where('SKPD_ID',$skpd);
                        })->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->count();
            $bln    = BL::whereHas('subunit',function($q) use ($skpd){
                                $q->where('SKPD_ID',$skpd);
                        })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->count();
            $b1     = Rincian::whereHas('rekening',function($q){
                        $q->where('REKENING_KODE','like','5.2.1%');
                    })->whereHas('bl',function($r) use($skpd,$tahun){
                        $r->where('BL_VALIDASI',1)
                          ->where('BL_DELETED',0)
                          ->where('BL_TAHUN',$tahun)
                          ->whereHas('subunit',function($s) use ($skpd){
                                $s->where('SKPD_ID',$skpd);
                        });
                    })->sum('RINCIAN_TOTAL');
            $b2     = Rincian::whereHas('rekening',function($q){
                        $q->where('REKENING_KODE','like','5.2.2%');
                    })->whereHas('bl',function($r) use($skpd,$tahun){
                        $r->where('BL_VALIDASI',1)
                          ->where('BL_DELETED',0)
                          ->where('BL_TAHUN',$tahun)                          
                          ->whereHas('subunit',function($s) use ($skpd){
                                $s->where('SKPD_ID',$skpd);
                        });
                    })->sum('RINCIAN_TOTAL');
            $b3     = Rincian::whereHas('rekening',function($q){
                        $q->where('REKENING_KODE','like','5.2.3%');
                    })->whereHas('bl',function($r) use($skpd,$tahun){
                        $r->where('BL_VALIDASI',1)    
                          ->where('BL_DELETED',0)    
                          ->where('BL_TAHUN',$tahun)                                          
                          ->whereHas('subunit',function($s) use ($skpd){
                                $s->where('SKPD_ID',$skpd);
                        });
                    })->sum('RINCIAN_TOTAL');  
            $pagu   = BL::whereHas('subunit',function($q) use ($skpd){
                                $q->where('SKPD_ID',$skpd);
                        })->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->sum('BL_PAGU');
            $skpdjenis  = SKPD::where('SKPD_ID',$skpd)->value('SKPD_JENIS');
            if($skpdjenis == 4){
                $musren           = Usulan::whereHas('rw',function($q) use($skpd){
                                        $q->whereHas('kelurahan',function($r) use($skpd){
                                            $r->whereHas('subunit',function($s) use($skpd){
                                                $s->where('SKPD_ID',$skpd);
                                            });
                                        });
                                    })->count('USULAN_ID');
                $musrenIn           = Usulan::whereHas('rw',function($q) use($skpd){
                                        $q->whereHas('kelurahan',function($r) use($skpd){
                                            $r->whereHas('subunit',function($s) use($skpd){
                                                $s->where('SKPD_ID',$skpd);
                                            });
                                        });
                                    })->where('USULAN_STATUS',1)->count('USULAN_ID');
                $musrenTotal        = DB::table('MUSRENBANG.DAT_USULAN as usulan')
                                        ->selectRAW('SUM("KAMUS_HARGA" * "USULAN_VOLUME") as total')
                                        ->join('REFERENSI.REF_KAMUS as kamus',function($join){
                                            $join->on("usulan.KAMUS_ID","=",'kamus.KAMUS_ID');
                                        })
                                        ->join('REFERENSI.REF_RW as rw',function($join){
                                            $join->on("usulan.RW_ID","=",'rw.RW_ID');
                                        })
                                        ->join('REFERENSI.REF_KELURAHAN as kel',function($join){
                                            $join->on("rw.KEL_ID","=",'kel.KEL_ID');
                                        })
                                        ->join('REFERENSI.REF_SUB_UNIT as sub',function($join){
                                            $join->on("kel.SUB_ID","=",'sub.SUB_ID');
                                        })
                                        ->where('SKPD_ID',$skpd)
                                        ->whereIn('KAMUS_JENIS',['PIPPK','KARTA','PKK','LPM'])
                                        ->get();
                $musrenTotal    = $musrenTotal[0]->total; 
                $musrenTotalIn      = DB::table('MUSRENBANG.DAT_USULAN as usulan')
                                        ->selectRAW('SUM("KAMUS_HARGA" * "USULAN_VOLUME") as total')
                                        ->join('REFERENSI.REF_KAMUS as kamus',function($join){
                                            $join->on("usulan.KAMUS_ID","=",'kamus.KAMUS_ID');
                                        })
                                        ->join('REFERENSI.REF_RW as rw',function($join){
                                            $join->on("usulan.RW_ID","=",'rw.RW_ID');
                                        })
                                        ->join('REFERENSI.REF_KELURAHAN as kel',function($join){
                                            $join->on("rw.KEL_ID","=",'kel.KEL_ID');
                                        })
                                        ->join('REFERENSI.REF_SUB_UNIT as sub',function($join){
                                            $join->on("kel.SUB_ID","=",'sub.SUB_ID');
                                        })
                                        ->where('SKPD_ID',$skpd)
                                        ->whereIn('KAMUS_JENIS',['PIPPK','KARTA','PKK','LPM'])                                    
                                        ->where('USULAN_STATUS',1)
                                        ->get();
                $musrenTotalIn  = $musrenTotalIn[0]->total;
            }else{
                $musren           = Usulan::whereHas('kamus',function($q) use($skpd){
                                        $q->where('KAMUS_SKPD',$skpd);
                                    })->count('USULAN_ID');
                $musrenIn           = Usulan::whereHas('kamus',function($q) use($skpd){
                                        $q->where('KAMUS_SKPD',$skpd);
                                    })->where('USULAN_STATUS',1)->count('USULAN_ID');
                $musrenTotal        = DB::table('MUSRENBANG.DAT_USULAN as usulan')
                                        ->selectRAW('SUM("KAMUS_HARGA" * "USULAN_VOLUME") as total')
                                        ->join('REFERENSI.REF_KAMUS as kamus',function($join){
                                            $join->on("usulan.KAMUS_ID","=",'kamus.KAMUS_ID');
                                        })
                                        ->where('KAMUS_SKPD',$skpd)
                                        ->whereNotIn('KAMUS_JENIS',['PIPPK','KARTA','PKK','LPM'])
                                        ->get();
                $musrenTotal    = $musrenTotal[0]->total; 
                $musrenTotalIn      = DB::table('MUSRENBANG.DAT_USULAN as usulan')
                                        ->selectRAW('SUM("KAMUS_HARGA" * "USULAN_VOLUME") as total')
                                        ->join('REFERENSI.REF_KAMUS as kamus',function($join){
                                            $join->on("usulan.KAMUS_ID","=",'kamus.KAMUS_ID');
                                        })
                                        ->where('KAMUS_SKPD',$skpd)
                                        ->whereNotIn('KAMUS_JENIS',['PIPPK','KARTA','PKK','LPM'])                                    
                                        ->where('USULAN_STATUS',1)
                                        ->get();
                $musrenTotalIn  = $musrenTotalIn[0]->total;
            }                     
    	}else{
            $bl     = BL::where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->get();
            $pagu   = BL::where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->sum('BL_PAGU');
            $blv    = BL::where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->count();
    		$bln 	= BL::where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->count();
    		$btl 	= BTL::where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
    		$pdp 	= Pendapatan::where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
            $b1     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                        ->whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0);
                        })
                        ->sum('RINCIAN_TOTAL');
            $b2     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                        ->whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0);
                        })
                        ->sum('RINCIAN_TOTAL');
            $b3     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                        ->whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0);
                        })
                        ->sum('RINCIAN_TOTAL');
            $musren      = Usulan::count();
            $musrenIn    = Usulan::where('USULAN_STATUS',1)->count();
            $datamusren  = Usulan::all();
            $musrenTotal        = DB::table('MUSRENBANG.DAT_USULAN as usulan')
                                    ->selectRAW('SUM("KAMUS_HARGA" * "USULAN_VOLUME") as total')
                                    ->join('REFERENSI.REF_KAMUS as kamus',function($join){
                                        $join->on("usulan.KAMUS_ID","=",'kamus.KAMUS_ID');
                                    })
                                    ->get();
            $musrenTotal    = $musrenTotal[0]->total; 
            $musrenTotalIn      = DB::table('MUSRENBANG.DAT_USULAN as usulan')
                                    ->selectRAW('SUM("KAMUS_HARGA" * "USULAN_VOLUME") as total')
                                    ->join('REFERENSI.REF_KAMUS as kamus',function($join){
                                        $join->on("usulan.KAMUS_ID","=",'kamus.KAMUS_ID');
                                    })
                                    ->where('USULAN_STATUS','1')
                                    ->get();
            $musrenTotalIn  = $musrenTotalIn[0]->total;
    	}
            if(empty($btl)) $btl = 0;  
            if(empty($pdp)) $pdp = 0;                 
        if($status == 'murni'){
        $rkpd   = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->where('TAHAPAN_NAMA','RKPD')->first();
        $rkua   = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->where('TAHAPAN_NAMA','RKUA/PPAS')->first();
        $ppas   = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->where('TAHAPAN_NAMA','KUA/PPAS')->first();
        $rapbd  = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->where('TAHAPAN_NAMA','RAPBD')->first();
        $apbd   = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->where('TAHAPAN_NAMA','APBD')->first();            
        }else{
        $rkpd   = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->where('TAHAPAN_NAMA','RKPD')->first();
        $rkua   = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->where('TAHAPAN_NAMA','RKUA/PPAS')->first();
        $ppas   = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->where('TAHAPAN_NAMA','KUA/PPAS')->first();
        $rapbd  = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->where('TAHAPAN_NAMA','RAPBD')->first();
        $apbd   = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->where('TAHAPAN_NAMA','APBD')->first();                        
        }

        $staff  = "";
    	if($level == 1){
    		$staff 	= Staff::where('USER_ID',Auth::user()->id)
                            ->whereHas('bl',function($q) use($tahun){
                                $q->where('BL_TAHUN',$tahun);
                            })->get();
    	}elseif($level == 2){
    		$staff 	= BL::whereHas('subunit',function($q) use ($skpd){
                                $q->where('SKPD_ID',$skpd);
                        })->where('BL_TAHUN',$tahun)->where('BL_VALIDASI','0')->get();
    	}
        // dd($staff);exit();
        if(Auth::user()->level == 2){
            $id         = $this->getSKPD($tahun);
            $profile    = SKPD::where('SKPD_ID',$id)->first();
            $alamat     = $profile->SKPD_ALAMAT;
            $jabatan    = $profile->SKPD_JABATAN;           
        }else{
            $alamat     = 'ada';
            $jabatan    = 'ada';
        }

        $pengumuman = "";
        $idskpd     = $this->getSKPD($tahun,$status);
        
        if(Auth::user()->level == 2){
            $pengumuman     = PaguRincian::where('SKPD_ID',$idskpd)->get();
            if(empty($pengumuman)) $pengumuman = "";
        }
        
        $info = "Pilih Tahun Anggaran Terlebih Dahulu di Menu Header dengan Font yang Berwarna Hitam";

        /*$bl_pagu_perubahan = BLPerubahan::join('REFERENSI.REF_SUB_UNIT', 'REF_SUB_UNIT.SUB_ID', '=', 'DAT_BL_PERUBAHAN.SUB_ID') 
                            ->join('REFERENSI.REF_SKPD', 'REF_SKPD.SKPD_ID', '=', 'REF_SUB_UNIT.SKPD_ID') 
                            ->where('DAT_BL_PERUBAHAN.BL_TAHUN',$tahun)
                            ->where('REF_SKPD.SKPD_ID',$idskpd)->sum('BL_PAGU');
        dd($bl_pagu_perubahan);*/

    	return View('budgeting.index',['tahun'=>$tahun, 'info'=>$info, 'status'=>$status,'pagu'=>$pagu,'btl'=>$btl,'pdp'=>$pdp,'blv'=>$blv,'bln'=>$bln,'rkpd'=>$rkpd,'ppas'=>$ppas,'rapbd'=>$rapbd,'apbd'=>$apbd,'staff'=>$staff,'b1'=>$b1,'b2'=>$b2,'b3'=>$b3,'jabatan'=>$jabatan,'alamat'=>$alamat,'musren'=>$musren,'musrenin'=>$musrenIn,'musrentotal'=>$musrenTotal,'musrentotalin'=>$musrenTotalIn,'musrentotal'=>$musrenTotal,'rkua'=>$rkua,'pengumuman'=>$pengumuman]);
    }

    public function dewa($tahun,$status){
        $data = Usulan::where('USULAN_POSISI',5)->get();
        $view = array();
        $no   = 1;
        foreach ($data as $d) {
            array_push($view, array('no'     =>$no,
                                    'KODE'   =>$d->kamus->kegiatan->program->skpd->SKPD_KODE,
                                    'SKPD'   =>$d->kamus->kegiatan->program->skpd->SKPD_NAMA,
                                    'TOTAL'   =>$d->count('KAMUS_ID') ));
            $no++;
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
        return $view;
    }
}
