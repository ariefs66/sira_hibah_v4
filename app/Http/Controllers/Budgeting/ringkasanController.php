<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Excel;
Use App\Model\Pendapatan;
Use App\Model\PendapatanPerubahan;
Use App\Model\PendapatanHistory;
Use App\Model\BL;
Use App\Model\Rincian;
Use App\Model\RincianPerubahan;
Use App\Model\RincianHistory;
Use App\Model\BTL;
Use App\Model\Pembiayaan;
Use App\Model\PembiayaanPerubahan;
Use App\Model\PembiayaanHistory;
Use App\Model\Rekening;
class ringkasanController extends Controller
{
    public function index($tahun,$status){
    	$pendapatan 		= Pendapatan::where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
    	//dd($pendapatan);
    	$pendapatan1       = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan11       = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1.1%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan12       = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1.2%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan13       = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1.3%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan14       = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1.4%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan2        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.2%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan21        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.2.1%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan22        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.2.2%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan23        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.2.3%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan3        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.3%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan31        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.3.1%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan32        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.3.3%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan33        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.3.4%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan34 		= Pendapatan::wherehas('rekening',function($q){
    		$q->where('REKENING_KODE','like','4.3.5%');
    	})->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');

        $btl1      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.1%');
        })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        $btl3      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.3%');
        })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        $btl4      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.4%');
        })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        $btl7      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.7%');
        })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        $btl8      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.8%');
        })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');

        $penerimaan     = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1%')
              ->sum('PEMBIAYAAN_TOTAL');

        $pengeluaran    = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.2%')
              ->sum('PEMBIAYAAN_TOTAL');

    	    $blv     = Rincian::whereHas('bl',function($r) use ($tahun) {
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->sum('RINCIAN_TOTAL');

			$b1v     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                        ->whereHas('bl',function($r) use ($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->sum('RINCIAN_TOTAL');
            $b2v     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                        ->whereHas('bl',function($r) use ($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->sum('RINCIAN_TOTAL');
            $b3v     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                        ->whereHas('bl',function($r) use ($tahun){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })
                        ->sum('RINCIAN_TOTAL');

            $bl      = Rincian::whereHas('bl',function($x) use ($tahun){
                            $x->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })->sum('RINCIAN_TOTAL');
            $b1     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                        ->whereHas('bl',function($x) use ($tahun){
                            $x->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })->sum('RINCIAN_TOTAL');
            $b2     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                        ->whereHas('bl',function($x) use ($tahun){
                            $x->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })->sum('RINCIAN_TOTAL');
            $b3     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                        ->whereHas('bl',function($x) use ($tahun){
                            $x->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                        })->sum('RINCIAN_TOTAL');

            $pen611     = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1.1')
              ->sum('PEMBIAYAAN_TOTAL');

            $pen612     = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1.2')
              ->sum('PEMBIAYAAN_TOTAL');

            $pen613     = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1.3')
              ->sum('PEMBIAYAAN_TOTAL');
              
            $pen614     = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1.4')
              ->sum('PEMBIAYAAN_TOTAL');
              
            $pen615     = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1.5')
              ->sum('PEMBIAYAAN_TOTAL');
                      

            $peng621    = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.2.1')
              ->sum('PEMBIAYAAN_TOTAL'); 

            $peng622    = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.2.2')
              ->sum('PEMBIAYAAN_TOTAL'); 
              
            $peng623    = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.2.3')
              ->sum('PEMBIAYAAN_TOTAL');               

              $pendapatanp 		= PendapatanPerubahan::where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              //dd($pendapatan);
              $pendapatan1p       = PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.1%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              $pendapatan11p       = PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.1.1%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              $pendapatan12p       = PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.1.2%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              $pendapatan13p       = PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.1.3%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              $pendapatan14p       = PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.1.4%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              $pendapatan2p        = PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.2%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              $pendapatan21p        = PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.2.1%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              $pendapatan22p        = PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.2.2%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              $pendapatan23p        = PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.2.3%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              $pendapatan3p        = PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.3%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              $pendapatan31p        = PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.3.1%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              $pendapatan32p        = PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.3.3%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              $pendapatan33p        = PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.3.4%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
              $pendapatan34p 		= PendapatanPerubahan::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','4.3.5%');
              })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
      
              $btl1p      = BTL::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','5.1.1%');
              })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
              $btl3p      = BTL::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','5.1.3%');
              })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
              $btl4p      = BTL::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','5.1.4%');
              })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
              $btl7p      = BTL::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','5.1.7%');
              })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
              $btl8p      = BTL::wherehas('rekening',function($q){
                  $q->where('REKENING_KODE','like','5.1.8%');
              })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
      
              $penerimaanp     = PembiayaanPerubahan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_PERUBAHAN.REKENING_ID')
                    ->where('PEMBIAYAAN_TAHUN',$tahun)
                    ->where('REKENING_KODE','LIKE', '6.1%')
                    ->sum('PEMBIAYAAN_TOTAL');
      
              $pengeluaranp    = PembiayaanPerubahan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_PERUBAHAN.REKENING_ID')
                    ->where('PEMBIAYAAN_TAHUN',$tahun)
                    ->where('REKENING_KODE','LIKE', '6.2%')
                    ->sum('PEMBIAYAAN_TOTAL');
      
                  $blvp     = RincianPerubahan::whereHas('bl',function($r) use ($tahun){
                                  $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                              })
                              ->sum('RINCIAN_TOTAL');
      
                  $b1vp     = RincianPerubahan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                              ->whereHas('bl',function($r) use ($tahun){
                                  $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                              })
                              ->sum('RINCIAN_TOTAL');
                  $b2vp     = RincianPerubahan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                              ->whereHas('bl',function($r) use ($tahun){
                                  $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                              })
                              ->sum('RINCIAN_TOTAL');
                  $b3vp     = RincianPerubahan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                              ->whereHas('bl',function($r) use ($tahun){
                                  $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                              })
                              ->sum('RINCIAN_TOTAL');
      
                  $blp      = RincianPerubahan::whereHas('bl',function($x) use ($tahun){
                                  $x->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                              })->sum('RINCIAN_TOTAL');
                  $b1p     = RincianPerubahan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                              ->whereHas('bl',function($x) use ($tahun){
                                  $x->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                              })->sum('RINCIAN_TOTAL');
                  $b2p     = RincianPerubahan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                              ->whereHas('bl',function($x) use ($tahun){
                                  $x->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                              })->sum('RINCIAN_TOTAL');
                  $b3p     = RincianPerubahan::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                              ->whereHas('bl',function($x) use ($tahun){
                                  $x->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                              })->sum('RINCIAN_TOTAL');
      
                  $pen611p     = PembiayaanPerubahan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_PERUBAHAN.REKENING_ID')
                    ->where('PEMBIAYAAN_TAHUN',$tahun)
                    ->where('REKENING_KODE','LIKE', '6.1.1')
                    ->sum('PEMBIAYAAN_TOTAL');
      
                  $pen612p     = PembiayaanPerubahan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_PERUBAHAN.REKENING_ID')
                    ->where('PEMBIAYAAN_TAHUN',$tahun)
                    ->where('REKENING_KODE','LIKE', '6.1.2')
                    ->sum('PEMBIAYAAN_TOTAL');
      
                  $pen613p     = PembiayaanPerubahan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_PERUBAHAN.REKENING_ID')
                    ->where('PEMBIAYAAN_TAHUN',$tahun)
                    ->where('REKENING_KODE','LIKE', '6.1.3')
                    ->sum('PEMBIAYAAN_TOTAL');
                    
                  $pen614p     = PembiayaanPerubahan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_PERUBAHAN.REKENING_ID')
                    ->where('PEMBIAYAAN_TAHUN',$tahun)
                    ->where('REKENING_KODE','LIKE', '6.1.4')
                    ->sum('PEMBIAYAAN_TOTAL');
                    
                  $pen615p     = PembiayaanPerubahan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_PERUBAHAN.REKENING_ID')
                    ->where('PEMBIAYAAN_TAHUN',$tahun)
                    ->where('REKENING_KODE','LIKE', '6.1.5')
                    ->sum('PEMBIAYAAN_TOTAL');
                            
      
                  $peng621p    = PembiayaanPerubahan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_PERUBAHAN.REKENING_ID')
                    ->where('PEMBIAYAAN_TAHUN',$tahun)
                    ->where('REKENING_KODE','LIKE', '6.2.1')
                    ->sum('PEMBIAYAAN_TOTAL'); 
      
                  $peng622p    = PembiayaanPerubahan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_PERUBAHAN.REKENING_ID')
                    ->where('PEMBIAYAAN_TAHUN',$tahun)
                    ->where('REKENING_KODE','LIKE', '6.2.2')
                    ->sum('PEMBIAYAAN_TOTAL'); 
                    
                  $peng623p    = PembiayaanPerubahan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_PERUBAHAN.REKENING_ID')
                    ->where('PEMBIAYAAN_TAHUN',$tahun)
                    ->where('REKENING_KODE','LIKE', '6.2.3')
                    ->sum('PEMBIAYAAN_TOTAL');

              $info = 'Sesudah';

    	$data 	= [ 'tahun'		=>$tahun,
                    'status'    =>$status,
    				'info'	    =>$info,
                    'penerimaan'         =>$penerimaan,
                    'pengeluaran'        =>$pengeluaran,
                    'pen611'        =>$pen611,
                    'pen612'        =>$pen612,
                    'pen613'        =>$pen613,
                    'pen614'        =>$pen614,
                    'pen615'        =>$pen615,
                    'peng621'        =>$peng621,
                    'peng622'        =>$peng622,
                    'peng623'        =>$peng623,
                    'pd'        =>$pendapatan,
                    'pd1'        =>$pendapatan1,
                    'pd11'       =>$pendapatan11,
                    'pd12'       =>$pendapatan12,
                    'pd13'       =>$pendapatan13,
                    'pd14'       =>$pendapatan14,
                    'pd2'       =>$pendapatan2,
                    'pd21'       =>$pendapatan21,
                    'pd22'       =>$pendapatan22,
                    'pd23'       =>$pendapatan23,
                    'pd3'       =>$pendapatan3,
                    'pd31'       =>$pendapatan31,
                    'pd32'       =>$pendapatan32,
                    'pd33'       =>$pendapatan33,
                    'pd34'       =>$pendapatan34,
                    'btl1'       =>$btl1,
                    'btl3'       =>$btl3,
                    'btl4'       =>$btl4,
                    'btl7'       =>$btl7,
                    'btl8'       =>$btl8,
    				'blv'       =>$blv,
                    'b1v'       =>$b1v,
                    'b2v'       =>$b2v,
                    'b3v'       =>$b3v,
                    'bl'		=>$bl,
    				'b1'		=>$b1,
    				'b2'		=>$b2,
                    'b3'		=>$b3,
                    'penerimaanp'         =>$penerimaanp,
                    'pengeluaranp'        =>$pengeluaranp,
                    'pen611p'        =>$pen611p,
                    'pen612p'        =>$pen612p,
                    'pen613p'        =>$pen613p,
                    'pen614p'        =>$pen614p,
                    'pen615p'        =>$pen615p,
                    'peng621p'        =>$peng621p,
                    'peng622p'        =>$peng622p,
                    'peng623p'        =>$peng623p,
                    'pdp'        =>$pendapatanp,
                    'pd1p'        =>$pendapatan1p,
                    'pd11p'       =>$pendapatan11p,
                    'pd12p'       =>$pendapatan12p,
                    'pd13p'       =>$pendapatan13p,
                    'pd14p'       =>$pendapatan14p,
                    'pd2p'       =>$pendapatan2p,
                    'pd21p'       =>$pendapatan21p,
                    'pd22p'       =>$pendapatan22p,
                    'pd23p'       =>$pendapatan23p,
                    'pd3p'       =>$pendapatan3p,
                    'pd31p'       =>$pendapatan31p,
                    'pd32p'       =>$pendapatan32p,
                    'pd33p'       =>$pendapatan33p,
                    'pd34p'       =>$pendapatan34p,
                    'btl1p'       =>$btl1p,
                    'btl3p'       =>$btl3p,
                    'btl4p'       =>$btl4p,
                    'btl7p'       =>$btl7p,
                    'btl8p'       =>$btl8p,
    				'blvp'       =>$blvp,
                    'b1vp'       =>$b1vp,
                    'b2vp'       =>$b2vp,
                    'b3vp'       =>$b3vp,
                    'blp'		=>$blp,
    				'b1p'		=>$b1p,
    				'b2p'		=>$b2p,
    				'b3p'		=>$b3p];
                    
    	return View('budgeting.ringkasan',$data);
    }

    public function ringkasanSebelum($tahun,$status){
        $pendapatan         = PendapatanHistory::where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        //dd($pendapatan);
        $pendapatan1       = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan11       = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1.1%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan12       = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1.2%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan13       = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1.3%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan14       = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1.4%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan2        = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.2%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan21        = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.2.1%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan22        = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.2.2%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan23        = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.2.3%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan3        = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.3%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan31        = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.3.1%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan32        = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.3.3%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan33        = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.3.4%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
        $pendapatan34       = PendapatanHistory::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.3.5%');
        })->where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');

        $btl1      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.1%');
        })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        $btl3      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.3%');
        })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        $btl4      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.4%');
        })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        $btl7      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.7%');
        })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
        $btl8      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.8%');
        })->where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');

        $penerimaan     = PembiayaanHistory::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_HISTORY.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1%')
              ->sum('PEMBIAYAAN_TOTAL');

        $pengeluaran    = PembiayaanHistory::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_HISTORY.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.2%')
              ->sum('PEMBIAYAAN_TOTAL');

            $blv     = RincianHistory::whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN','2018');
                        })
                        ->sum('RINCIAN_TOTAL');

            $b1v     = RincianHistory::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                        ->whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN','2018');
                        })
                        ->sum('RINCIAN_TOTAL');
            $b2v     = RincianHistory::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                        ->whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN','2018');
                        })
                        ->sum('RINCIAN_TOTAL');
            $b3v     = RincianHistory::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                        ->whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0)->where('BL_TAHUN','2018');
                        })
                        ->sum('RINCIAN_TOTAL');

            $bl      = RincianHistory::whereHas('bl',function($x){
                            $x->where('BL_DELETED',0)->where('BL_TAHUN','2018');
                        })->sum('RINCIAN_TOTAL');
            $b1     = RincianHistory::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                        ->whereHas('bl',function($x){
                            $x->where('BL_DELETED',0)->where('BL_TAHUN','2018');
                        })->sum('RINCIAN_TOTAL');
            $b2     = RincianHistory::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                        ->whereHas('bl',function($x){
                            $x->where('BL_DELETED',0)->where('BL_TAHUN','2018');
                        })->sum('RINCIAN_TOTAL');
            $b3     = RincianHistory::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                        ->whereHas('bl',function($x){
                            $x->where('BL_DELETED',0)->where('BL_TAHUN','2018');
                        })->sum('RINCIAN_TOTAL');

            $pen611     = PembiayaanHistory::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_HISTORY.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1.1')
              ->sum('PEMBIAYAAN_TOTAL');

            $pen612     = PembiayaanHistory::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_HISTORY.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1.2')
              ->sum('PEMBIAYAAN_TOTAL');

            $pen613     = PembiayaanHistory::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_HISTORY.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1.3')
              ->sum('PEMBIAYAAN_TOTAL');
              
            $pen614     = PembiayaanHistory::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_HISTORY.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1.4')
              ->sum('PEMBIAYAAN_TOTAL');
              
            $pen615     = PembiayaanHistory::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_HISTORY.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1.5')
              ->sum('PEMBIAYAAN_TOTAL');
                      

            $peng621    = PembiayaanHistory::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_HISTORY.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.2.1')
              ->sum('PEMBIAYAAN_TOTAL'); 

            $peng622    = PembiayaanHistory::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_HISTORY.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.2.2')
              ->sum('PEMBIAYAAN_TOTAL'); 
              
            $peng623    = PembiayaanHistory::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN_HISTORY.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.2.3')
              ->sum('PEMBIAYAAN_TOTAL');               

            $info = 'Sebelum';  

        $data   = [ 'tahun'     =>$tahun,
                    'status'    =>$status,
                    'info'      =>$info,
                    'penerimaan'         =>$penerimaan,
                    'pengeluaran'        =>$pengeluaran,
                    'pen611'        =>$pen611,
                    'pen612'        =>$pen612,
                    'pen613'        =>$pen613,
                    'pen614'        =>$pen614,
                    'pen615'        =>$pen615,
                    'peng621'        =>$peng621,
                    'peng622'        =>$peng622,
                    'peng623'        =>$peng623,
                    'pd'        =>$pendapatan,
                    'pd1'        =>$pendapatan1,
                    'pd11'       =>$pendapatan11,
                    'pd12'       =>$pendapatan12,
                    'pd13'       =>$pendapatan13,
                    'pd14'       =>$pendapatan14,
                    'pd2'       =>$pendapatan2,
                    'pd21'       =>$pendapatan21,
                    'pd22'       =>$pendapatan22,
                    'pd23'       =>$pendapatan23,
                    'pd3'       =>$pendapatan3,
                    'pd31'       =>$pendapatan31,
                    'pd32'       =>$pendapatan32,
                    'pd33'       =>$pendapatan33,
                    'pd34'       =>$pendapatan34,
                    'btl1'       =>$btl1,
                    'btl3'       =>$btl3,
                    'btl4'       =>$btl4,
                    'btl7'       =>$btl7,
                    'btl8'       =>$btl8,
                    'blv'       =>$blv,
                    'b1v'       =>$b1v,
                    'b2v'       =>$b2v,
                    'b3v'       =>$b3v,
                    'bl'        =>$bl,
                    'b1'        =>$b1,
                    'b2'        =>$b2,
                    'b3'        =>$b3];
                    
        return View('budgeting.ringkasan',$data);
    }
}
