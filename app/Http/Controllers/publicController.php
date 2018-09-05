<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Auth;
use DB;
use App\Model\UserBudget;
use App\Model\TahunAnggaran;
use App\Model\BL;
use App\Model\BLPerubahan;
use App\Model\BTL;
use App\Model\BTLPerubahan;
use App\Model\Pendapatan;
use App\Model\PendapatanPerubahan;
use App\Model\Pembiayaan;
use App\Model\PembiayaanPerubahan;
use App\Model\Tahapan;
use App\Model\Staff;
use App\Model\Rincian;
use App\Model\RincianPerubahan;
use App\Model\SKPD;
use App\Model\Usulan;
use App\Model\Kamus;
use App\Model\PaguRincian;
//use App\Model\BlPerubahan;
use Response;
class publicController extends Controller
{
    public function __construct(){
    }
    public function index($tahun,$status){
    	$level 	= 8;
    	$bl 	= '';
    	$btl 	= '';
    	$pdp 	= '';
    	$pby 	= '';
    	$pagu 	= 0;
    	$blv 	= 0;
    	$bln 	= 0;
    	$staff 	= '';
    	$b1=0;$b2=0;$b3=0;

        if($level == 1 or $level == 2){
            
            if($status=="murni"){
                $bl     = BL::whereHas('subunit',function($q) use ($skpd){
                                $q->where('SKPD_ID',$skpd);
                        })->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->get();
                $btl    = BTL::whereHas('subunit',function($q) use ($skpd){
                                    $q->where('SKPD_ID',$skpd);
                            })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
                $pdp    = Pendapatan::whereHas('subunit',function($q) use ($skpd){
                                    $q->where('SKPD_ID',$skpd);
                            })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
                $pby    = Pembiayaan::whereHas('subunit',function($q) use ($skpd){
                                    $q->where('SKPD_ID',$skpd);
                            })->where('PEMBIAYAAN_TAHUN',$tahun)->sum('PEMBIAYAAN_TOTAL');
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
                
                $pagu   = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                            ->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)
                            ->where('SKPD_ID',$skpd)
                            ->sum('RINCIAN_TOTAL');
            }
            elseif($status=="perubahan" || $status=="pergeseran"){
                $bl     = BLPerubahan::whereHas('subunit',function($q) use ($skpd){
                                $q->where('SKPD_ID',$skpd);
                        })->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->get();
                $btl    = BTLPerubahan::whereHas('subunit',function($q) use ($skpd){
                                    $q->where('SKPD_ID',$skpd);
                            })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
                $pdp    = PendapatanPerubahan::whereHas('subunit',function($q) use ($skpd){
                                    $q->where('SKPD_ID',$skpd);
                            })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
                $pby    = PembiayaanPerubahan::whereHas('subunit',function($q) use ($skpd){
                                    $q->where('SKPD_ID',$skpd);
                            })->where('PEMBIAYAAN_TAHUN',$tahun)->sum('PEMBIAYAAN_TOTAL');
                $blv    = BLPerubahan::whereHas('subunit',function($q) use ($skpd){
                                    $q->where('SKPD_ID',$skpd);
                            })->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->count();
                $bln    = BLPerubahan::whereHas('subunit',function($q) use ($skpd){
                                    $q->where('SKPD_ID',$skpd);
                            })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->count();
                $b1     = RincianPerubahan::whereHas('rekening',function($q){
                                $q->where('REKENING_KODE','like','5.2.1%');
                            })
                            ->whereHas('bl',function($r) use($skpd,$tahun){
                                $r->where('BL_VALIDASI',1)
                                  ->where('BL_DELETED',0)
                                  ->where('BL_TAHUN',$tahun)
                                  ->whereHas('subunit',function($s) use ($skpd){
                                        $s->where('SKPD_ID',$skpd);
                                    });
                            })->sum('RINCIAN_TOTAL');
                $b2     = RincianPerubahan::whereHas('rekening',function($q){
                            $q->where('REKENING_KODE','like','5.2.2%');
                            })->whereHas('bl',function($r) use($skpd,$tahun){
                                $r->where('BL_VALIDASI',1)
                                  ->where('BL_DELETED',0)
                                  ->where('BL_TAHUN',$tahun)                          
                                  ->whereHas('subunit',function($s) use ($skpd){
                                        $s->where('SKPD_ID',$skpd);
                                    });
                            })->sum('RINCIAN_TOTAL');
                $b3     = RincianPerubahan::whereHas('rekening',function($q){
                            $q->where('REKENING_KODE','like','5.2.3%');
                            })->whereHas('bl',function($r) use($skpd,$tahun){
                                $r->where('BL_VALIDASI',1)    
                                  ->where('BL_DELETED',0)    
                                  ->where('BL_TAHUN',$tahun)                                          
                                  ->whereHas('subunit',function($s) use ($skpd){
                                        $s->where('SKPD_ID',$skpd);
                                    });
                            })->sum('RINCIAN_TOTAL');
                $pagu   = BLPerubahan::whereHas('subunit',function($q) use ($skpd){
                                    $q->where('SKPD_ID',$skpd);
                            })->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->sum('BL_PAGU');
            }

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
            if($status=="murni"){
                $bl     = BL::where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->get();
                $pagu   = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                            ->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)
                            ->sum('RINCIAN_TOTAL');
                $blv    = BL::where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->count();
                $bln    = BL::where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->count();
                $btl    = BTL::where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
                $pdp    = Pendapatan::where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
                $pby    = Pembiayaan::where('PEMBIAYAAN_TAHUN',$tahun)->sum('PEMBIAYAAN_TOTAL');
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
            }
            elseif($status=="perubahan" || $status=="pergeseran"){
                $bl     = BLPerubahan::where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->get();
                $pagu   = BLPerubahan::where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->sum('BL_PAGU');
                $blv    = BLPerubahan::where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->count();
                $bln    = BLPerubahan::where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->count();
                $btl    = BTLPerubahan::where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
                $pdp    = PendapatanPerubahan::where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
                $pby    = PembiayaanPerubahan::where('PEMBIAYAAN_TAHUN',$tahun)->sum('PEMBIAYAAN_TOTAL');
                $b1     = RincianPerubahan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                            ->whereHas('bl',function($r){
                                $r->where('BL_VALIDASI',1)->where('BL_DELETED',0);
                            })
                            ->sum('RINCIAN_TOTAL');
                $b2     = RincianPerubahan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                            ->whereHas('bl',function($r){
                                $r->where('BL_VALIDASI',1)->where('BL_DELETED',0);
                            })
                            ->sum('RINCIAN_TOTAL');
                $b3     = RincianPerubahan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                            ->whereHas('bl',function($r){
                                $r->where('BL_VALIDASI',1)->where('BL_DELETED',0);
                            })
                            ->sum('RINCIAN_TOTAL');
            }
            
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
            if(empty($pby)) $pby = 0;                 
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
                                $q->where('BL_DELETED',0);
                            })->get();

    	}elseif($level == 2){
            if($status=="murni"){
                $staff  = BL::whereHas('subunit',function($q) use ($skpd){
                                $q->where('SKPD_ID',$skpd);
                        })->where('BL_TAHUN',$tahun)->where('BL_VALIDASI','0')->where('BL_DELETED',0)->get();
            }
            else{
                $staff  = BLPerubahan::whereHas('subunit',function($q) use ($skpd){
                                $q->where('SKPD_ID',$skpd);
                        })->where('BL_TAHUN',$tahun)->where('BL_VALIDASI','0')->where('BL_DELETED',0)->get();
            }
        }
        
        if($level == 2){
            $profile    = SKPD::where('SKPD_ID',$id)->first();
            $alamat     = $profile->SKPD_ALAMAT;
            $jabatan    = $profile->SKPD_JABATAN;           
        }else{
            $alamat     = 'ada';
            $jabatan    = 'ada';
        }

        $pengumuman = "";
        $idskpd     = 1;
        
        if($level == 2){
            $pengumuman     = PaguRincian::where('SKPD_ID',$idskpd)->get();
            if(empty($pengumuman)) $pengumuman = "";
        }
        
        $info = "Pilih Tahun Anggaran Terlebih Dahulu di Menu Header dengan Font yang Berwarna Hitam";

        $now = date('Y-m-d H:m:s');
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->orderBy('TAHAPAN_ID','desc')->first();
        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }

        $skpd = Skpd::where('SKPD_TAHUN',$tahun)->orderBy('SKPD_KODE')->get();

    	return View('public.index',['tahun'=>$tahun, 'info'=>$info, 'status'=>$status,'pagu'=>$pagu,'btl'=>$btl,'pdp'=>$pdp,'pby'=>$pby,'blv'=>$blv,'bln'=>$bln,'rkpd'=>$rkpd,'ppas'=>$ppas,'rapbd'=>$rapbd,'apbd'=>$apbd,'staff'=>$staff,'b1'=>$b1,'b2'=>$b2,'b3'=>$b3,'jabatan'=>$jabatan,'alamat'=>$alamat,'musren'=>$musren,'musrenin'=>$musrenIn,'musrentotal'=>$musrenTotal,'musrentotalin'=>$musrenTotalIn,'musrentotal'=>$musrenTotal,'rkua'=>$rkua,'pengumuman'=>$pengumuman,'thp'=>$thp, 'skpd'=>$skpd]);
    }

    public function getTahun(){
		$data  		= TahunAnggaran::orderBy('TAHUN')->get();
		$view 		= '';
		foreach($data as $data){
            $view 	.= '<li><a href="'.url('/').'/public/'.$data->TAHUN.'/'.$data->STATUS.'" id="o'.$data->TAHUN.$data->STATUS.'">APBD '.$data->TAHUN.'-'.$data->STATUS.'</a></li>';
		}
		return $view;
	}

    public function getTABudgeting($tahun,$status){
		$data  		= TahunAnggaran::orderBy('TAHUN')->get();
		$view 		= '';
$view 	.= '<option value="'.$tahun.'/'.$status.'" id="o'.$tahun.$status.'" selected>'.$tahun.'-'.$status.'</option>';
		
		foreach($data as $data){
				
			/*if($data->TAHUN == '2018' && $data->STATUS == 'pergeseran'){
				if($tahun == '2018' && $status == 'pergeseran'){
				} else {
					$view 	.= '<option value="'.$data->TAHUN.'/'.$data->STATUS.'" id="o'.$data->TAHUN.$data->STATUS.'">'.$data->TAHUN.'-'.$data->STATUS.'</option>';
				}
			}
			if($data->TAHUN == '2018' && $data->STATUS == 'murni'){
				if($tahun == '2018' && $status == 'murni'){
				} else {
					$view 	.= '<option value="'.$data->TAHUN.'/'.$data->STATUS.'" id="o'.$data->TAHUN.$data->STATUS.'">'.$data->TAHUN.'-'.$data->STATUS.'</option>';
				}
			}*/
			if($data->TAHUN == '2018' && $data->STATUS == 'perubahan'){
				if($tahun == '2018' && $status == 'perubahan'){
				} else {
					$view 	.= '<option value="'.$data->TAHUN.'/'.$data->STATUS.'" id="o'.$data->TAHUN.$data->STATUS.'">'.$data->TAHUN.'-'.$data->STATUS.'</option>';
				}
			}
			 if($data->TAHUN == '2019'){
				if($tahun == '2019' && $status == 'murni'){
				} else {
					$view 	.= '<option value="'.$data->TAHUN.'/'.$data->STATUS.'" id="o'.$data->TAHUN.$data->STATUS.'">'.$data->TAHUN.'-'.$data->STATUS.'</option>';
				}
            }
		}
		return $view;
	}
}
