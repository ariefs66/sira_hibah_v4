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
use App\Model\Kamus;
use App\Model\Usulan;
use App\Model\UsulanReses;
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
use App\Model\Urgensi;
use App\Model\Priositas;
use App\Model\Detail_prioritas;

class statistikController extends Controller{

	  public function __construct(){
        $this->middleware('auth');
    }

    public function pd($tahun,$status){
		  $template 	= Kegunit::count();
    	$used 		= BL::whereHas('kegiatan',function($r){
			    			$r->whereHas('program',function($s){
								$s->whereNotIn('PROGRAM_KODE',['01','02','03','04','05','06']);
			    			});
			    		})->count();
		  $adum 		= BL::whereHas('kegiatan',function($r){
			    			$r->whereHas('program',function($s){
								$s->whereIn('PROGRAM_KODE',['01','02','03','04','05','06']);
			    			});
			    		})->count();			    		
    	$rincian 	= Rincian::whereHas('bl',function($q){
                    $q->where('BL_DELETED',0);
                  })->sum('RINCIAN_TOTAL');
    	$pagu 		= BL::where('BL_VALIDASI','1')->where('BL_DELETED',0)->sum('BL_PAGU');
    	
    		if($template == 0 ) $p1 = 0;
    		else $p1 = ($used/$template)*100;
    		if($rincian == 0) $p2 = 0;
    		else $p2 = ($pagu/$rincian)*100;    

      $total2017  = 4269765389901;
        if($pagu < $total2017*1){
          $sel  = ($total2017*1) - $pagu;
          $ket  = number_format(($sel/($total2017*1) * 100),0,'.',',');
        }elseif($total2017*1 < $pagu){
          $sel  = $pagu-($total2017*1);
          $ket  = number_format(($sel/($total2017*1) * 100),0,'.',',');
        }else{
          $ket  = "100 <span class='text-info'>=</span>";          
        }

    	$data =	array('tahun'	=> $tahun,
    				  'status'	=> $status,
    				  'adum'	=> $adum,
    				  'template'=> $template,
    				  'used'	=> $used,
    				  'p1'		=> $p1,
    				  'pagu'	=> $pagu,
    				  'rincian'	=> $rincian,
    				  'p2'		=> $p2,
              'total2017'=>$total2017,
              'ket'=>$ket." %",
              'selisih'   => $rincian - $pagu
    				  );
    	return View('budgeting.monitoring.pd',$data);
    }

    public function pdInput($tahun,$status){

      $data = array('tahun' => $tahun,
              'status'  => $status);
      return View('budgeting.monitoring.InputSKPD',$data);
    }

    public function pdDetail($tahun,$status,$id){
        $detail       = SKPD::where('SKPD_ID',$id)->first();
        $data = array('tahun'   => $tahun,
                      'status'  => $status,
                      'skpd'    => $detail,
                      'id'      => $id
                      );
        return View('budgeting.monitoring.pddetail',$data);
    }

    public function prioritas($tahun,$status){
        $data = array('tahun'   => $tahun,
                      'status'  => $status,
                      );
        return View('budgeting.monitoring.prioritas',$data);
    }

    public function rekeningDetail($tahun,$status,$id){
        $rekening       = Rekening::where('REKENING_ID',$id)->first();
        $data = array('tahun'   => $tahun,
                      'status'  => $status,
                      'rekening'=> $rekening,
                      'id'      => $id
                      );
        return View('budgeting.monitoring.rekeningdetail',$data);
    }

    public function komponenDetail($tahun,$status,$id){
        $komponen       = Komponen::where('KOMPONEN_ID',$id)->first();
        $data = array('tahun'   => $tahun,
                      'status'  => $status,
                      'komponen'=> $komponen,
                      'id'      => $id
                      );
        return View('budgeting.monitoring.komponendetail',$data);
    }

    public function paketDetail($tahun,$status,$id){
        $paket       = Subrincian::where('SUBRINCIAN_ID',$id)->first();
        $data = array('tahun'   => $tahun,
                      'status'  => $status,
                      'paket'   => $paket,
                      'id'      => $id
                      );
        return View('budgeting.monitoring.paketdetail',$data);
    }

    public function urusan($tahun,$status){
      $rincian  = Rincian::whereHas('bl',function($q) use($tahun){
                    $q->where('BL_DELETED',0)->where('BL_TAHUN',$tahun);
                  })->sum('RINCIAN_TOTAL');
      $pagu     = BL::where('BL_VALIDASI','1')->where('BL_DELETED',0)->sum('BL_PAGU');
      $data = array('tahun'   => $tahun,
                    'status'  => $status,
                    'pagu'    => $pagu,
                    'rincian' => $rincian);
      return View('budgeting.monitoring.urusan',$data);
    }

    public function paguDetail($tahun,$status,$id){
      $pagu   = Pagu::where('PAGU_ID',$id)->first();
      $data = array('tahun'   => $tahun,
                    'pagu'    => $pagu,
                    'status'  => $status);
      return View('budgeting.monitoring.pagudetail',$data);
    }

    public function pagu($tahun,$status){
      $data = array('tahun'   => $tahun,
                    'status'  => $status);
      return View('budgeting.monitoring.pagu',$data);
    }

    public function kerangkaLogis($tahun,$status){
      $data = array('tahun'   => $tahun,
                    'status'  => $status);
      return View('budgeting.monitoring.kerangkalogis',$data);
    }

    public function program($tahun,$status){
      $rincian  = Rincian::whereHas('bl',function($q){
                    $q->where('BL_DELETED',0);
                  })->sum('RINCIAN_TOTAL');
      $pagu     = BL::where('BL_VALIDASI','1')->where('BL_DELETED',0)->sum('BL_PAGU');
      $data = array('tahun'   => $tahun,
                    'status'  => $status,
                    'pagu'    => $pagu,
                    'rincian' => $rincian);
      return View('budgeting.monitoring.program',$data);
    }

    public function kegiatan($tahun,$status){
      $rincian  = Rincian::whereHas('bl',function($q){
                    $q->where('BL_DELETED',0);
                  })->sum('RINCIAN_TOTAL');
      $pagu     = BL::where('BL_VALIDASI','1')->where('BL_DELETED',0)->sum('BL_PAGU');
      $data = array('tahun'   => $tahun,
                    'status'  => $status,
                    'pagu'    => $pagu,
                    'rincian' => $rincian);
      return View('budgeting.monitoring.kegiatan',$data);
    }

    public function kegiatanAdum($tahun,$status){
      $rincian  = Rincian::whereHas('bl',function($q){
                    $q->where('BL_DELETED',0);
                  })->sum('RINCIAN_TOTAL');
      $pagu     = BL::where('BL_VALIDASI','1')->where('BL_DELETED',0)->sum('BL_PAGU');
      $data = array('tahun'   => $tahun,
                    'status'  => $status,
                    'pagu'    => $pagu,
                    'rincian' => $rincian);
      return View('budgeting.monitoring.kegiatanadum',$data);
    }

    public function kegiatanAdumDetail($tahun,$status,$id){
        $kegiatan       = Kegiatan::where('KEGIATAN_NAMA',$id)->first();
        $data = array('tahun'   => $tahun,
                      'status'  => $status,
                      'kegiatan'=> $kegiatan,
                      'id'      => $id
                      );
        return View('budgeting.monitoring.kegiatanadumdetail',$data);
    }

    public function paket($tahun,$status){
      $data = array('tahun'   => $tahun,
                    'status'  => $status);
      return View('budgeting.monitoring.paket',$data);
    }

    public function tagging($tahun,$status){
      $data = array('tahun'   => $tahun,
                    'status'  => $status);
      return View('budgeting.monitoring.tagging',$data);
    }

    public function rekening($tahun,$status){
      $rincian  = Rincian::whereHas('bl',function($q){
                    $q->where('BL_DELETED',0);
                  })->sum('RINCIAN_TOTAL');
      $pagu     = BL::where('BL_VALIDASI','1')->where('BL_DELETED',0)->sum('BL_PAGU');
      $data = array('tahun'   => $tahun,
                    'status'  => $status,
                    'pagu'    => $pagu,
                    'rincian' => $rincian);
      return View('budgeting.monitoring.rekening',$data);
    }

    public function komponen($tahun,$status){
      $data = array('tahun'   => $tahun,
                    'status'  => $status);
      return View('budgeting.monitoring.komponen',$data);
    }

    public function indikator($tahun,$status){
      $data = array('tahun'   => $tahun,
                    'status'  => $status);
      return View('budgeting.monitoring.indikator',$data);
    }

    public function musrenbang($tahun,$status){
      $data = array('tahun'   => $tahun,
                    'status'  => $status);
      return View('budgeting.monitoring.musrenbang',$data);
    }

    public function porsiAPBD($tahun,$status){
        $musrenbang   =  Rincian::whereHas('bl',function($x){
                              $x->where('BL_DELETED',0);
                          })->whereRaw('"RINCIAN_KETERANGAN" SIMILAR TO \'(Musrenbang RW|Emusrenbang)%\'')
                            // ->where('RINCIAN_KETERANGAN','LIKE','Musrenbang RW%')
                            // ->orWhere('RINCIAN_KETERANGAN','LIKE','EMusrenbang%')
                            ->sum('RINCIAN_TOTAL');
        $etc          =  Rincian::whereHas('bl',function($x){
                            $x->where('BL_DELETED',0)
                              ->whereNotIn('KEGIATAN_ID',[3394,3395,3396,3397])
                              ->where('PAGU_ID','!=',15)
                              ->whereHas('kegiatan',function($y){
                              $y->whereHas('program',function($z){
                                $z->whereNotIn('PROGRAM_KODE',['01','02','03','04','05','06','07','08']);
                              });
                            });
                          })->where('RINCIAN_KETERANGAN','NOT LIKE','Musrenbang RW%')
                            ->Where('RINCIAN_KETERANGAN','NOT LIKE','Emusrenbang%')
                            ->where('RINCIAN_KETERANGAN','NOT LIKE','Reses Dewan%')
                            ->sum('RINCIAN_TOTAL');                            
        $reses        =  Rincian::whereHas('bl',function($x){
                              $x->where('BL_DELETED',0);
                          })->where('RINCIAN_KETERANGAN','LIKE','Reses Dewan %')->sum('RINCIAN_TOTAL');
        $arahan       =  Rincian::whereHas('bl',function($x){
                              $x->where('BL_DELETED',0)->where('PAGU_ID',15);
                          })->where('RINCIAN_KETERANGAN','NOT ILIKE','Reses Dewan %')
                            ->whereRaw('"RINCIAN_KETERANGAN" NOT SIMILAR TO \'(Musrenbang RW|Emusrenbang)%\'')
                            ->sum('RINCIAN_TOTAL');
        $pippkrw      = Rincian::whereHas('bl',function($x){
                            $x->where('BL_DELETED',0)
                              ->where('KEGIATAN_ID',3394);                            
                          })->sum('RINCIAN_TOTAL');
        $pippkpkk     = Rincian::whereHas('bl',function($x){
                            $x->where('BL_DELETED',0)
                              ->where('KEGIATAN_ID',3395);
                          })->sum('RINCIAN_TOTAL');
        $pippklpm     = Rincian::whereHas('bl',function($x){
                            $x->where('BL_DELETED',0)
                              ->where('KEGIATAN_ID',3397);
                          })->sum('RINCIAN_TOTAL');
        $pippkkarta   = Rincian::whereHas('bl',function($x){
                            $x->where('BL_DELETED',0)
                              ->where('KEGIATAN_ID',3396);
                          })->sum('RINCIAN_TOTAL');
        $pippk        = $pippkrw+$pippkkarta+$pippklpm+$pippkpkk;
        $nonurusan    =  Rincian::whereHas('bl',function($x){
                            $x->where('BL_DELETED',0)->whereHas('kegiatan',function($y){
                              $y->whereHas('program',function($z){
                                $z->whereIn('PROGRAM_KODE',['01','02','03','04','05','06','07','08']);
                              });
                            });
                          })->sum('RINCIAN_TOTAL');
      $data = array('tahun'       => $tahun,
                    'status'      => $status,
                    'etc'         => $etc,
                    'musrenbang'  => $musrenbang,
                    'pippk'       => $pippk,
                    'reses'       => $reses,
                    'arahan'      => $arahan,
                    'nonurusan'   => $nonurusan);
      return View('budgeting.monitoring.porsiapbd',$data);
    }

    //API
    public function pdApi($tahun,$status){
    	$skpd 	= SKPD::whereNotIn('SKPD_KODE',['4.02.02','4.05.01','4.05.03'])->where('SKPD_TAHUN',$tahun)->get();
    	$view           = array();
    	foreach ($skpd as $skpd) {
    		$id 		= $skpd->SKPD_ID;
    		$template 	= Kegunit::where('SKPD_ID',$id)->count();
    		$used 		= BL::whereHas('subunit',function($q) use ($id){
			    			$q->where('SKPD_ID',$id);
			    		})->whereHas('kegiatan',function($r){
			    			$r->whereHas('program',function($s){
								$s->whereNotIn('PROGRAM_KODE',['01','02','03','04','05','06','07']);
			    			});
			    		})->where('BL_TAHUN',$tahun)->selectRaw('COUNT(DISTINCT("KEGIATAN_ID")) AS total')->first();
              $used = $used->total;
			$adum 		= BL::whereHas('subunit',function($q) use ($id){
			    			$q->where('SKPD_ID',$id);
			    		})->whereHas('kegiatan',function($r){
			    			$r->whereHas('program',function($s){
								$s->whereIn('PROGRAM_KODE',['01','02','03','04','05','06','07']);
			    			});
			    		})->where('BL_TAHUN',$tahun)->count();			    		
    		$rincian 	= Rincian::whereHas('bl',function($q) use ($id,$tahun){
			    			$q->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->whereHas('subunit',function($r) use ($id){
			    				$r->where('SKPD_ID',$id);
			    			});
			    		})->sum('RINCIAN_TOTAL');
    		$pagu 		= BL::whereHas('subunit',function($q) use ($id){
			    			$q->where('SKPD_ID',$id);
			    		})->where('BL_VALIDASI','1')->where('BL_DELETED',0)->where('BL_TAHUN',$tahun)->sum('BL_PAGU');

    		if($template == 0 ) $p1 = 0;
    		else $p1 = ($used/$template)*100;
    		if($rincian == 0) $p2 = 0;
    		else $p2 = ($pagu/$rincian)*100;

        $Kd_urusan    = substr($skpd->SKPD_KODE, 0,1)*1;
        $Kd_bidang    = substr($skpd->SKPD_KODE, 2,2)*1;
        $Kd_unit      = substr($skpd->SKPD_KODE, 5,2)*1;
        $tahunmin     = $tahun-1;
        $t2017        = DB::connection('sqlsrv')
                        ->select("SELECT sum(Total) as Total 
                                  FROM dbo.Ta_RASK_Arsip rincian
                                  where rincian.Kd_Urusan = ".$Kd_urusan." 
                                  and rincian.Kd_Bidang = ".$Kd_bidang." 
                                  and rincian.Kd_unit = ".$Kd_unit." 
                                  and rincian.Kd_Prog != 0
                                  and rincian.Kd_Rek_1 = 5
                                  and rincian.Kd_Rek_2 = 2
                                  and Tahun = ".$tahunmin);
        $total2017  = $t2017[0]->Total;

        if($pagu < $total2017*1){
          $sel  = ($total2017*1) - $pagu;
          if($total2017*1 != 0){
          $ket  = number_format(($sel/($total2017*1) * 100),2,'.',',')."<span class='text-danger'><i class='fa fa-arrow-circle-down'></i></span>";
          }else{
          $ket = "100";
          }
        }elseif($total2017*1 < $pagu){
          $sel  = $pagu-($total2017*1);
          if($total2017*1 != 0){
          $ket  = number_format(($sel/($total2017*1) * 100),2,'.',',')."<span class='text-success'><i class='fa fa-arrow-circle-up'></i></span>";
          }else{
          $ket = "100";
          }
        }else{
          $ket  = "100";          
        }
        
    		array_push($view, array( 'ID'        		=>$skpd->SKPD_ID,
                                     'KODE'       		=>$skpd->SKPD_KODE,
                                     'NAMA'       		=>$skpd->SKPD_NAMA,
                                     'TEMPLATE'       	=>$template,
                                     'KEGIATAN'       	=>$used,
                                     'ADUM'           =>$adum,
                                     'KET'       		=>$ket,
                                     'P1'       		=>number_format($p1,'0','.',','),
                                     'P2'       		=>number_format($p2,'0','.',','),
                                     'total2017'           =>number_format($total2017,'0','.',','),
                                     'PAGU'           =>number_format($pagu,'0','.',','),
                                     'SELISIH'       		=>number_format($rincian - $pagu,'0','.',','),
                                     'RINCIAN'          =>number_format($rincian,'0','.',',')));
    	}
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function pdApiInput($tahun,$status){
      $skpd   = SKPD::whereNotIn('SKPD_KODE',['4.02.02','4.05.01','4.05.03'])->where('SKPD_TAHUN',$tahun)->get();
      $view           = array();      
      foreach ($skpd as $skpd) {
        $id     = $skpd->SKPD_ID;            
        $rincian  = Rincian::whereHas('bl',function($q) use ($id){
                      $q->where('BL_DELETED',0)->where('BL_TAHUN',$tahun)->whereHas('subunit',function($r) use ($id){
                        $r->where('SKPD_ID',$id);
                      });
                    })->sum('RINCIAN_TOTAL');
        $pagu       = BL::whereHas('subunit',function($r) use($id){
                          $r->whereHas('skpd',function($s) use($id){
                            $s->where('SKPD_ID',$id);
                          });
                      })->where('BL_VALIDASI',1)
                      ->where('BL_DELETED',0)
                      ->where('BL_TAHUN',$tahun)
                      ->sum('BL_PAGU');
        array_push($view, array( 'ID'              => $skpd->SKPD_ID,
                                  'KODE'           => $skpd->SKPD_KODE,
                                  'NAMA'           => $skpd->SKPD_NAMA,
                                  'PAGU'           => number_format($pagu,'0','.',','),
                                  'SELISIH'        => number_format($pagu - $rincian,'0','.',','),
                                  'RINCIAN'        => number_format($rincian,'0','.',',')));
      }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function pdApiDetail($tahun,$status,$id){
        $skpd   = BL::whereHas('subunit',function($q) use($id){
                        $q->where('SKPD_ID',$id);
                      })->where('BL_DELETED',0)
                      ->get();
        $view   = array();
        foreach ($skpd as $skpd) {
            $rincian  = Rincian::where('BL_ID',$skpd->BL_ID)->sum('RINCIAN_TOTAL');
            array_push($view, array( 'ID'               =>$skpd->BL_ID,
                                     'KODE'             =>$skpd->kegiatan->program->urusan->URUSAN_KODE.".".$skpd->subunit->skpd->SKPD_KODE.".".$skpd->kegiatan->program->PROGRAM_KODE.".".$skpd->kegiatan->KEGIATAN_KODE,
                                     'NAMA'             =>$skpd->kegiatan->KEGIATAN_NAMA,
                                     'PAGU'             =>number_format($skpd->BL_PAGU,'0','.',','),
                                     'SELISIH'          =>number_format($rincian - $skpd->BL_PAGU,'0','.',','),
                                     'RINCIAN'          =>number_format($rincian,'0','.',',')));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }  

    public function urusanApi($tahun,$status){
      $urusan   = Urusan::where('URUSAN_TAHUN',$tahun)->get();
      $view     = array();      
      foreach ($urusan as $u) {
        $id         = $u->URUSAN_ID;
        $totalgiat  = BL::whereHas('kegiatan',function($r) use($id){
                          $r->whereHas('program',function($s) use($id){
                            $s->where('URUSAN_ID',$id);
                          });
                        })->selectRaw('COUNT(DISTINCT("KEGIATAN_ID")) AS total')->first();
        $totalgiat = $totalgiat->total;
        $totalgiatvalidasi  = BL::whereHas('kegiatan',function($r) use($id){
                          $r->whereHas('program',function($s) use($id){
                            $s->where('URUSAN_ID',$id);
                          });
                        })->selectRaw('COUNT(DISTINCT("KEGIATAN_ID")) AS total')->where('BL_VALIDASI',1)->first();
        $totalgiatvalidasi = $totalgiatvalidasi->total;
        $rincian    = Rincian::whereHas('bl',function($q) use($id,$tahun){
                        $q->where('BL_DELETED',0)->where('BL_TAHUN',$tahun)->whereHas('kegiatan',function($r) use($id){
                          $r->whereHas('program',function($s) use($id){
                            $s->where('URUSAN_ID',$id);
                          });
                        });
                      })->sum('RINCIAN_TOTAL');
        $pagu       = BL::whereHas('kegiatan',function($r) use($id){
                          $r->whereHas('program',function($s) use($id){
                            $s->where('URUSAN_ID',$id);
                          });
                      })->where('BL_VALIDASI',1)
                      ->where('BL_DELETED',0)
                      ->where('BL_TAHUN',$tahun)
                      ->sum('BL_PAGU');
        $idprog     = str_replace(".","",$u->URUSAN_KODE);
        $tahunmin   = $tahun-1;
        $t2017        = DB::connection('sqlsrv')
                        ->select("SELECT sum(Total) as Total 
                                  FROM dbo.Ta_RASK_Arsip rincian
                                  where rincian.Id_Prog = ".$idprog."
                                  and Kd_Perubahan = 4
                                  and rincian.Kd_Prog != 0
                                  and rincian.Kd_Rek_1 = 5
                                  and rincian.Kd_Rek_2 = 2
                                  and rincian.Tahun = ".$tahunmin);
        $total2017  = $t2017[0]->Total;

        if($pagu < $total2017*1){
          $sel  = ($total2017*1) - $pagu;
          if($total2017*1 != 0){
          $ket  = number_format(($sel/($total2017*1) * 100),2,'.',',')."<span class='text-danger'><i class='fa fa-arrow-circle-down'></i></span>";
          }else{
          $ket  = "100<span class='text-danger'><i class='fa fa-arrow-circle-down'></i></span>";
          }
        }elseif($total2017*1 < $pagu){
          $sel  = $pagu-($total2017*1);
          if($total2017*1 != 0){
          $ket  = number_format(($sel/($total2017*1) * 100),2,'.',',')."<span class='text-success'><i class='fa fa-arrow-circle-up'></i></span>";
          }else{
          $ket  = "100<span class='text-success'><i class='fa fa-arrow-circle-up'></i></span>";
          }
        }else{
          $ket  = "100";          
        }

        array_push($view, array('ID'        =>$u->URUSAN_ID,
                                'KODE'      =>$u->URUSAN_KODE,
                                'NAMA'      =>$u->URUSAN_NAMA,
                                'TGIAT'     =>$totalgiat,
                                'TGIATV'    =>$totalgiatvalidasi,
                                'KET'       =>$ket,
                                't2017'     =>number_format($total2017,'0','.',','),
                                'PAGU'      =>number_format($pagu,'0','.',','),
                                'RINCIAN'   =>number_format($rincian,'0','.',',')));
      }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function taggingApi($tahun,$status){
      $tag   = BL::where('BL_VALIDASI',1)->where('BL_DELETED')->where('BL_PAGU','!=',0)->get();
      $tag   = Rincian::whereHas('bl',function($x){
        $x->where('BL_VALIDASI',1)->where('BL_DELETED',0);
      })->selectRaw('SUM("RINCIAN_TOTAL") AS total, "BL_ID"')->groupBy('BL_ID')->get();
      $view     = array();
      $data     = array(); 
        foreach($tag as $t){
          $tagging         = BL::where('BL_ID',$t->BL_ID)->value('BL_TAG');
          $tagging         = str_replace('{', '', $tagging);
          $tagging         = str_replace('}', '', $tagging);
          $tagging         = explode(',', $tagging);
          foreach($tagging as $tagging){
            if(empty($data[$tagging])){
              $data[$tagging] = $t->total;
            }else{
              $data[$tagging] += $t->total;
            }
          }
        }
      $i = 1;
      $view = array();
      foreach($data as $r => $val) {
        $nama     = Tag::where('TAG_ID',$r)->value('TAG_NAMA');
        array_push($view, array('NO'        =>$i++,
                                'NAMA'      =>$nama,
                                'RINCIAN'   =>number_format($val,'0','.',',')));
      }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function paguApi($tahun,$status){
      $bl       = Pagu::all();
      $view     = array();
      $i        = 1;      
      foreach ($bl as $bl) {
        $id       = $bl->PAGU_ID;
        $rincian  = Rincian::whereHas('bl',function($q) use($id){
                      $q->where('PAGU_ID',$id)->where('BL_DELETED',0);
                    })->sum('RINCIAN_TOTAL');
        array_push($view, array('ID'        =>$id,
                                'NO'        =>$i++,
                                'NAMA'      =>$bl->PAGU_NAMA,
                                'RINCIAN'   =>number_format($rincian,'0','.',',')));
      }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function paguApiDetail($tahun,$status,$id){
      $data     = Rincian::whereHas('bl',function($x) use($id){
                          $x->where('BL_DELETED',0)->where('PAGU_ID',$id);
                        })->select('BL_ID')
                          ->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL')
                          ->groupBy('BL_ID')->get();
      $i = 1;
      $view   = array();
      foreach ($data as $data) {
        $kegiatan   = BL::where('BL_ID',$data->BL_ID)->first();
        array_push($view, array('NO'        => $i++,
                                'ID'        => $kegiatan->BL_ID,
                                'NAMA'      => $kegiatan->kegiatan->KEGIATAN_NAMA,
                                'RINCIAN'   => number_format($data->total,0,'.',','),
                                'OPD'       => $kegiatan->subunit->skpd->SKPD_NAMA));
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }
    
    public function rekeningApi($tahun,$status){
      $rekening   = Rincian::whereHas('bl',function($q){
                    $q->where('BL_DELETED',0);
                  })->groupBy('REKENING_ID')->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL , "REKENING_ID"')->get();
      $view     = array();      
      foreach ($rekening as $r) {
        $id         = $r->REKENING_ID;
        $kode       = Rekening::where('REKENING_ID',$id)->value('REKENING_KODE');
        
        // $json       = file_get_contents('http://128.199.82.1:8000/api/global/rekening/'.$kode);
        // $obj        = json_decode($json);
        // $total2017  = $obj[0]->Total;
        
        $Kd_Rek_1       = substr($kode, 0,1)*1;
        $Kd_Rek_2       = substr($kode, 2,1)*1;
        $Kd_Rek_3       = substr($kode, 4,1)*1;
        $Kd_Rek_4       = substr($kode, 6,2)*1;
        $Kd_Rek_5       = substr($kode, 9,2)*1;
        $t2017   = DB::connection('sqlsrv')
                    ->select("SELECT sum(Total) as Total 
                              FROM dbo.Ta_Belanja_Rinc_Sub rincian
                              LEFT JOIN dbo.Ta_Kegiatan kegiatan
                              ON rincian.Kd_Urusan = kegiatan.Kd_Urusan
                              and rincian.Kd_Unit = kegiatan.Kd_Unit
                              and rincian.Kd_Bidang = kegiatan.Kd_Bidang
                              and rincian.Kd_Sub = kegiatan.Kd_Sub 
                              and rincian.Kd_Prog = kegiatan.Kd_Prog 
                              and rincian.ID_Prog = kegiatan.ID_Prog 
                              and rincian.Kd_Keg = kegiatan.Kd_Keg                               
                              where Kd_Rek_1 = ".$Kd_Rek_1." 
                              and Kd_Rek_2 = ".$Kd_Rek_2." 
                              and Kd_Rek_3 = ".$Kd_Rek_3." 
                              and Kd_Rek_4 = ".$Kd_Rek_4." 
                              and Kd_Rek_5 = ".$Kd_Rek_5." 
                              and rincian.Kd_Prog != 0
                              and kegiatan.Ket_Kegiatan not like '%(Banprov)'
                              and kegiatan.Ket_Kegiatan not like '%(DAK)'");
        $total2017  = $t2017[0]->Total;

        if($total2017 == null){
          $total2017 = 0;
        }
        // $total2017 = 0;

        if($r->total < $total2017*1 and $total2017 != 0){
          $sel  = ($total2017*1) - $r->total;
          $ket  = number_format(($sel/($total2017*1) * 100),2,'.',',')."<span class='text-danger'><i class='fa fa-arrow-circle-down'></i></span>";
        }elseif($total2017*1 < $r->total and $total2017 != 0){
          $sel  = $r->total-($total2017*1);
          $ket  = number_format(($sel/($total2017*1) * 100),2,'.',',')."<span class='text-success'><i class='fa fa-arrow-circle-up'></i></span>";
        }else{
          $ket  = "100";          
        }
        $nama       = Rekening::where('REKENING_ID',$id)->value('REKENING_NAMA');
        array_push($view, array('ID'        =>$id,
                                'KODE'      =>$kode,
                                'NAMA'      =>$nama,
                                'PERSENTASE'=>$ket,
                                'RINCIAN'   =>number_format($r->total,'0','.',','),
                                'RINCIAN_'  =>number_format($total2017,'0','.',',')));
      }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function rekeningApiFilter($tahun,$status,$id,$jenis,$persentase){
      $rekening   = Rincian::whereHas('bl',function($q){
                    $q->where('BL_DELETED',0);
                  })->whereHas('rekening',function($x) use($jenis){
                    $x->where('REKENING_KODE','LIKE',$jenis.'%');
                  })->groupBy('REKENING_ID')
                    ->havingRaw('SUM("RINCIAN_TOTAL") > '.$id*1000000000)
                    ->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL , "REKENING_ID"')->get();
      $view     = array();      
      foreach ($rekening as $r) {
        $id         = $r->REKENING_ID;
        $kode       = Rekening::where('REKENING_ID',$id)->value('REKENING_KODE');
        
        // $json       = file_get_contents('http://128.199.82.1:8000/api/global/rekening/'.$kode);
        // $obj        = json_decode($json);
        // $total2017  = $obj[0]->Total;
        
        $Kd_Rek_1       = substr($kode, 0,1)*1;
        $Kd_Rek_2       = substr($kode, 2,1)*1;
        $Kd_Rek_3       = substr($kode, 4,1)*1;
        $Kd_Rek_4       = substr($kode, 6,2)*1;
        $Kd_Rek_5       = substr($kode, 9,2)*1;
        $t2017   = DB::connection('sqlsrv')
                    ->select("SELECT sum(Total) as Total 
                              FROM dbo.Ta_Belanja_Rinc_Sub rincian
                              LEFT JOIN dbo.Ta_Kegiatan kegiatan
                              ON rincian.Kd_Urusan = kegiatan.Kd_Urusan
                              and rincian.Kd_Unit = kegiatan.Kd_Unit
                              and rincian.Kd_Bidang = kegiatan.Kd_Bidang
                              and rincian.Kd_Sub = kegiatan.Kd_Sub 
                              and rincian.Kd_Prog = kegiatan.Kd_Prog 
                              and rincian.ID_Prog = kegiatan.ID_Prog 
                              and rincian.Kd_Keg = kegiatan.Kd_Keg                               
                              where Kd_Rek_1 = ".$Kd_Rek_1." 
                              and Kd_Rek_2 = ".$Kd_Rek_2." 
                              and Kd_Rek_3 = ".$Kd_Rek_3." 
                              and Kd_Rek_4 = ".$Kd_Rek_4." 
                              and Kd_Rek_5 = ".$Kd_Rek_5." 
                              and rincian.Kd_Prog != 0
                              and kegiatan.Ket_Kegiatan not like '%(Banprov)'
                              and kegiatan.Ket_Kegiatan not like '%(DAK)'");
        $total2017  = $t2017[0]->Total;

        if($total2017 == null){
          $total2017 = 0;
        }
        // $total2017 = 0;
        if($r->total < $total2017*1 and $total2017 != 0){
          $sel  = ($total2017*1) - $r->total;
          $ket  = number_format(($sel/($total2017*1) * -100),2,'.',',')." <span class='text-danger'><i class='fa fa-arrow-circle-down'></i></span>";
        }elseif($total2017*1 < $r->total and $total2017 != 0){
          $sel  = $r->total-($total2017*1);
          $ket  = number_format(($sel/($total2017*1) * 100),2,'.',',')." <span class='text-success'><i class='fa fa-arrow-circle-up'></i></span>";
        }else{
          $ket  = "100 <span class='text-success'><i class='fa fa-arrow-circle-up'></i></span>";          
        }
        $nama       = Rekening::where('REKENING_ID',$id)->value('REKENING_NAMA');
        if($persentase == 2 and $total2017*1 < $r->total)
        array_push($view, array('ID'        =>$id,
                                'KODE'      =>$kode,
                                'NAMA'      =>$nama,
                                'PERSENTASE'=>$ket,
                                'RINCIAN'   =>number_format($r->total,'0','.',','),
                                'RINCIAN_'  =>number_format($total2017,'0','.',',')));
        elseif($persentase == 1 and $total2017*1 > $r->total)
        array_push($view, array('ID'        =>$id,
                                'KODE'      =>$kode,
                                'NAMA'      =>$nama,
                                'PERSENTASE'=>$ket,
                                'RINCIAN'   =>number_format($r->total,'0','.',','),
                                'RINCIAN_'  =>number_format($total2017,'0','.',',')));
        elseif($persentase == 0)
        array_push($view, array('ID'        =>$id,
                                'KODE'      =>$kode,
                                'NAMA'      =>$nama,
                                'PERSENTASE'=>$ket,
                                'RINCIAN'   =>number_format($r->total,'0','.',','),
                                'RINCIAN_'  =>number_format($total2017,'0','.',',')));  
      }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function programApi($tahun,$status){
      $bl     = BL::whereHas('kegiatan',function($q){
                  $q->whereHas('program',function($r){
                    $r->whereNotIn('PROGRAM_KODE',['01','02','03','04','05','06']);
                  });
                })->where('BL_DELETED',0)
                ->select('KEGIATAN_ID')
                ->groupBy('KEGIATAN_ID')
                ->get()->toArray();
      $program        = Program::whereHas('kegiatan',function($q) use($bl){
                                $q->whereIn('KEGIATAN_ID',$bl);
                                })
                                ->orderBy('URUSAN_ID')
                                ->orderBy('PROGRAM_KODE')
                                ->get();
      $view     = array();
      $i        = 1;
      $pagu     = 0;
      $rincian  = 0;
      foreach($program as $program){
        $id       = $program->PROGRAM_ID;
        $opd      = Progunit::where('PROGRAM_ID',$id)->groupBy('SKPD_ID','PROGRAM_ID')->select('SKPD_ID','PROGRAM_ID')->get();
        $pd       = "";
        if($opd){
          if($opd[0]->SKPD_ID > 36){
            $pd     = 'Kecamatan';
          }else{
            foreach($opd as $opd){
              $pd     = $pd.$opd->skpd->SKPD_NAMA."<br>";
            }                      
          }

        }else{
            $pd     = 'Kecamatan';
        }

        $rincian  = Rincian::whereHas('bl',function($x) use($id){
                      $x->where('BL_DELETED',0)
                        ->whereHas('kegiatan',function($y) use($id){
                          $y->where('PROGRAM_ID',$id);
                        });
                    })->sum('RINCIAN_TOTAL');
        $pagu     = BL::whereHas('kegiatan',function($x) use($id){
                      $x->where('PROGRAM_ID',$id);
                    })->where('BL_DELETED',0)->where('BL_VALIDASI',1)->sum('BL_PAGU');
        array_push($view, array('NO'        => $i++,
                                'ID'        => $id,
                                'NAMA'      => $program->PROGRAM_NAMA,
                                'PAGU'      => number_format($pagu,0,'.',','),
                                'RINCIAN'   => number_format($rincian,0,'.',','),
                                'OPD'       => $pd));
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }

    public function indikatorApi($tahun,$status,$tipe){
      $i      = 1;
      $view   = array();
      if($tipe == 1){
        $data     = Outcome::all();
        foreach($data as $d){
        array_push($view, array('NO'          =>$i++,
                                'TOLOK_UKUR'  =>$d->OUTCOME_TOLAK_UKUR,
                                'TARGET'      =>$d->OUTCOME_TARGET,
                                'SATUAN'      =>$d->satuan->SATUAN_NAMA,
                                'PROGRAM'     =>$d->program->PROGRAM_NAMA,
                                'OPSI'        =>"-"));  
        }
      }elseif($tipe == 2){
        $data     = Impact::all();
        foreach($data as $d){
        array_push($view, array('NO'          =>$i++,
                                'TOLOK_UKUR'  =>$d->IMPACT_TOLAK_UKUR,
                                'TARGET'     =>$d->IMPACT_TARGET,
                                'SATUAN'      =>$d->satuan->SATUAN_NAMA,
                                'PROGRAM'     =>$d->program->PROGRAM_NAMA,
                                'OPSI'        =>"-"));  
        }
      }elseif($tipe == 3){
        $data     = Output::all();
        foreach($data as $d){
        array_push($view, array('NO'          =>$i++,
                                'TOLOK_UKUR'  =>$d->OUTPUT_TOLAK_UKUR,
                                'TARGET'      =>$d->OUTPUT_TARGET,
                                'SATUAN'      =>$d->satuan->SATUAN_NAMA,
                                'KEGIATAN'    =>$d->bl->kegiatan->KEGIATAN_NAMA,
                                'OPSI'        =>"-"));  
        }
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }   

    public function renjaApi($tahun,$status){
        $dataKamus      = Kamus::whereNotIn('KAMUS_JENIS',['RW','PKK','KARTA','LPM','PIPPK'])->groupBy('KAMUS_SKPD')
                            ->orderBy('KAMUS_SKPD')
                            ->select('KAMUS_SKPD')->get();
        $i =  1;
        $view   = array();
        foreach ($dataKamus as $data) {
            $pd     = SKPD::where('SKPD_ID',$data->KAMUS_SKPD)->first();
            if($pd)  $pd = $pd->SKPD_NAMA;
            else  $pd = '-';
            $total  = DB::table('MUSRENBANG.DAT_USULAN as usulan')
                          ->selectRAW('SUM("KAMUS_HARGA" * "USULAN_VOLUME") as total')
                          ->join('REFERENSI.REF_KAMUS as kamus',function($join){
                                $join->on("usulan.KAMUS_ID","=",'kamus.KAMUS_ID');
                            })
                          ->where('USULAN_TUJUAN',1)
                          ->where('KAMUS_SKPD',$data->KAMUS_SKPD)
                          ->get();
            $in      = DB::table('MUSRENBANG.DAT_USULAN as usulan')
                          ->selectRAW('SUM("KAMUS_HARGA" * "USULAN_VOLUME") as total')
                          ->join('REFERENSI.REF_KAMUS as kamus',function($join){
                                $join->on("usulan.KAMUS_ID","=",'kamus.KAMUS_ID');
                            })
                          ->where('USULAN_TUJUAN',1)
                          ->where('USULAN_STATUS',1)
                          ->where('KAMUS_SKPD',$data->KAMUS_SKPD)
                          ->get();
            $id   = $data->KAMUS_SKPD;
            $musren      = Usulan::wherehas('kamus',function($q) use($id){$q->where('KAMUS_SKPD',$id);})->where('USULAN_TUJUAN',1)->count();
            $musrenIn    = Usulan::wherehas('kamus',function($q) use($id){$q->where('KAMUS_SKPD',$id);})->where('USULAN_TUJUAN',1)->where('USULAN_STATUS',1)->count();                          
            array_push($view, array( 'NO'               =>$i++,
                                     'PD'               =>$pd,
                                     'JUMLAH'           =>$musrenIn.' / '.$musren,
                                     'TOTAL'            =>number_format($total[0]->total,0,'.',','),
                                     'IN'               =>number_format($in[0]->total,0,'.',',')));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function resesApi($tahun,$status){
        $dataKamus      = Kamus::whereNotIn('KAMUS_JENIS',['RW','PKK','KARTA','LPM','PIPPK'])->groupBy('KAMUS_SKPD')
                            ->orderBy('KAMUS_SKPD')
                            ->select('KAMUS_SKPD')->get();
        $i =  1;
        $view   = array();
        foreach ($dataKamus as $data) {
            $pd     = SKPD::where('SKPD_ID',$data->KAMUS_SKPD)->first();
            if($pd)  $pd = $pd->SKPD_NAMA;
            else  $pd = '-';
            $total  = DB::table('RESES.DAT_USULAN as usulan')
                          ->selectRAW('SUM("KAMUS_HARGA" * "USULAN_VOLUME") as total')
                          ->join('REFERENSI.REF_KAMUS as kamus',function($join){
                                $join->on("usulan.KAMUS_ID","=",'kamus.KAMUS_ID');
                            })
                          ->where('KAMUS_SKPD',$data->KAMUS_SKPD)
                          ->get();
            $in      = DB::table('RESES.DAT_USULAN as usulan')
                          ->selectRAW('SUM("KAMUS_HARGA" * "USULAN_VOLUME") as total')
                          ->join('REFERENSI.REF_KAMUS as kamus',function($join){
                                $join->on("usulan.KAMUS_ID","=",'kamus.KAMUS_ID');
                            })
                          ->where('USULAN_STATUS',2)
                          ->where('KAMUS_SKPD',$data->KAMUS_SKPD)
                          ->get();
            $id   = $data->KAMUS_SKPD;
            $musren      = UsulanReses::wherehas('kamus',function($q) use($id){$q->where('KAMUS_SKPD',$id);})->count();
            $musrenIn    = UsulanReses::wherehas('kamus',function($q) use($id){$q->where('KAMUS_SKPD',$id);})->where('USULAN_STATUS',2)->count();                          
            array_push($view, array( 'NO'               =>$i++,
                                     'PD'               =>$pd,
                                     'JUMLAH'           =>$musrenIn.' / '.$musren,
                                     'TOTAL'            =>number_format($total[0]->total,0,'.',','),
                                     'IN'               =>number_format($in[0]->total,0,'.',',')));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function rwApi($tahun,$status){
        $data           = kelurahan::whereNotIn('KEL_ID',['9999','9900','0','999','9000'])->get();
        $i =  1;
        $kegiatan = '';
        $view   = array();
        foreach ($data as $data) {
            $id     = $data->KEL_ID;
            $total  = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','PIPPK');
                        })
                        ->wherehas('rw',function($r) use($id){
                            $r->where('KEL_ID',$id);
                        })->get();
            $sum    = 0;
            $in    = 0;
            foreach($total as $t){
                $sum    += $t->USULAN_VOLUME * Kamus::where('KAMUS_ID',$t->KAMUS_ID)->value('KAMUS_HARGA');
                if($t->USULAN_STATUS == 1) $in += $t->USULAN_VOLUME * Kamus::where('KAMUS_ID',$t->KAMUS_ID)->value('KAMUS_HARGA');
            }
            if($in == 0 and $sum == 0) $present = 0;
            else $present  = $in/$sum*100;            
            array_push($view, array('NO'=>$i++,
                                    'KECAMATAN'=>$data->kecamatan->KEC_NAMA,
                                    'KELURAHAN'=>$data->KEL_NAMA,
                                    'TOTAL'=>number_format($sum,0,'.',','),
                                    'IN'=>number_format($in,0,'.',','),
                                    'PERSENTASE'=>number_format($present,0,'.',',')));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function lpmApi($tahun,$status){
        $data           = kelurahan::whereNotIn('KEL_ID',['9999','9900','0','999','9000'])->get();
        $i =  1;
        $kegiatan = '';
        $view   = array();
        foreach ($data as $data) {
            $id     = $data->KEL_ID;
            $total  = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','LPM');
                        })
                        ->wherehas('rw',function($r) use($id){
                            $r->where('KEL_ID',$id);
                        })->get();
            $sum    = 0;
            $in    = 0;
            foreach($total as $t){
                $sum    += $t->USULAN_VOLUME * Kamus::where('KAMUS_ID',$t->KAMUS_ID)->value('KAMUS_HARGA');
                if($t->USULAN_STATUS == 1) $in += $t->USULAN_VOLUME * Kamus::where('KAMUS_ID',$t->KAMUS_ID)->value('KAMUS_HARGA');
            }
            if($in == 0 and $sum == 0) $present = 0;
            else $present  = $in/$sum*100;            
            array_push($view, array('NO'=>$i++,
                                    'KECAMATAN'=>$data->kecamatan->KEC_NAMA,
                                    'KELURAHAN'=>$data->KEL_NAMA,
                                    'TOTAL'=>number_format($sum,0,'.',','),
                                    'IN'=>number_format($in,0,'.',','),
                                    'PERSENTASE'=>number_format($present,0,'.',',')));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function kartaApi($tahun,$status){
        $data           = kelurahan::whereNotIn('KEL_ID',['9999','9900','0','999','9000'])->get();
        $i =  1;
        $kegiatan = '';
        $view   = array();
        foreach ($data as $data) {
            $id     = $data->KEL_ID;
            $total  = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','KARTA');
                        })->wherehas('rw',function($r) use($id){
                            $r->where('KEL_ID',$id);
                        })->get();
            $sum    = 0;
            $in     = 0;
            foreach($total as $t){
              $sum    += $t->USULAN_VOLUME * Kamus::where('KAMUS_ID',$t->KAMUS_ID)->value('KAMUS_HARGA');
              if($t->USULAN_STATUS == 1) $in += $t->USULAN_VOLUME * Kamus::where('KAMUS_ID',$t->KAMUS_ID)->value('KAMUS_HARGA');
            }
            if($in == 0 and $sum == 0) $present = 0;
            else $present  = $in/$sum*100;
            array_push($view, array('NO'=>$i++,
                                    'KECAMATAN'=>$data->kecamatan->KEC_NAMA,
                                    'KELURAHAN'=>$data->KEL_NAMA,
                                    'TOTAL'=>number_format($sum,0,'.',','),
                                    'IN'=>number_format($in,0,'.',','),
                                    'PERSENTASE'=>number_format($present,0,'.',',')));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function pkkApi($tahun,$status){
        $data           = kelurahan::whereNotIn('KEL_ID',['9999','9900','0','999','9000'])->get();
        $i =  1;
        $kegiatan = '';
        $view   = array();
        foreach ($data as $data) {
            $id     = $data->KEL_ID;
            $total  = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','PKK');
                        })
                        ->wherehas('rw',function($r) use($id){
                            $r->where('KEL_ID',$id);
                        })->get();
            $sum    = 0;
            $in    = 0;
            foreach($total as $t){
                $sum    += $t->USULAN_VOLUME * Kamus::where('KAMUS_ID',$t->KAMUS_ID)->value('KAMUS_HARGA');
                if($t->USULAN_STATUS == 1) $in += $t->USULAN_VOLUME * Kamus::where('KAMUS_ID',$t->KAMUS_ID)->value('KAMUS_HARGA');
            }
            if($in == 0 and $sum == 0) $present = 0;
            else $present  = $in/$sum*100;                        
            array_push($view, array('NO'=>$i++,
                                    'KECAMATAN'=>$data->kecamatan->KEC_NAMA,
                                    'KELURAHAN'=>$data->KEL_NAMA,
                                    'TOTAL'=>number_format($sum,0,'.',','),
                                    'IN'=>number_format($in,0,'.',','),
                                    'PERSENTASE'=>number_format($present,0,'.',',')));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    } 

    public function kegiatanApi($tahun,$status){
      $bl     = BL::whereHas('kegiatan',function($q){
                  $q->whereHas('program',function($r){
                    $r->whereNotIn('PROGRAM_KODE',['01','02','03','04','05','06']);
                  });
                })->where('BL_DELETED',0)
                ->select('KEGIATAN_ID')
                ->groupBy('KEGIATAN_ID')
                ->get();
      $view     = array();
      $i        = 1;
      $pagu     = 0;
      $rincian  = 0;
      foreach($bl as $bl){
        $id       = $bl->KEGIATAN_ID;
        $opd      = Kegunit::where('KEGIATAN_ID',$id)->groupBy('SKPD_ID','KEGIATAN_ID')->select('SKPD_ID','KEGIATAN_ID')->get();
        $pd       = "";
        if($opd){
          foreach($opd as $opd){
            $pd     = $pd.$opd->skpd->SKPD_NAMA."<br>";
          } 
        }else{
            $pd     = "Kecamatan";
        }
        $rincian  = Rincian::whereHas('bl',function($x) use($id){
                      $x->where('KEGIATAN_ID',$id)->where('BL_DELETED',0);
                    })->sum('RINCIAN_TOTAL');
        $pagu     = BL::where('KEGIATAN_ID',$id)->where('BL_DELETED',0)->where('BL_VALIDASI',1)->sum('BL_PAGU');
        array_push($view, array('NO'        => $i++,
                                'ID'        => $id,
                                'NAMA'      => $bl->kegiatan->KEGIATAN_NAMA,
                                'PAGU'      => number_format($pagu,0,'.',','),
                                'RINCIAN'   => number_format($rincian,0,'.',','),
                                'OPD'       => $pd));
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }

    public function kegiatanApiAdum($tahun,$status){
      $bl     = Kegiatan::whereHas('program',function($r){
                    $r->whereIn('PROGRAM_KODE',['01','02','03','04','05','06']);
                })->whereHas('bl',function($q){
                  $q->where('BL_DELETED',0);
                })->select('KEGIATAN_NAMA')
                  ->groupBy('KEGIATAN_NAMA')                
                  ->get();
      $view     = array();
      $i        = 1;
      $pagu     = 0;
      $rincian  = 0;
      foreach($bl as $bl){
        $id       = $bl->KEGIATAN_NAMA;
        $id       = Kegiatan::where('KEGIATAN_NAMA',$id)->select('KEGIATAN_ID')->get()->toArray();
        $rincian  = Rincian::whereHas('bl',function($x) use($id){
                      $x->whereIn('KEGIATAN_ID',$id)->where('BL_DELETED',0);
                    })->sum('RINCIAN_TOTAL');
        $pagu     = BL::whereIn('KEGIATAN_ID',$id)->where('BL_DELETED',0)->where('BL_VALIDASI',1)->sum('BL_PAGU');
        array_push($view, array('NO'        => $i++,
                                'ID'        => $bl->KEGIATAN_NAMA,
                                'NAMA'      => $bl->KEGIATAN_NAMA,
                                'PAGU'      => number_format($pagu,0,'.',','),
                                'RINCIAN'   => number_format($rincian,0,'.',',')));
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }

    public function kegiatanApiAdumDetail($tahun,$status,$id){
      $data     = Rincian::whereHas('bl',function($x) use($id){
                      $x->where('BL_DELETED',0)
                        ->whereHas('kegiatan',function($y) use($id){
                          $y->where('KEGIATAN_NAMA',$id);
                        });
                    })->select('BL_ID')->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL')
                    ->groupBy('BL_ID')->get();
      $i = 1;
      $view   = array();
      foreach ($data as $data) {
        $kegiatan   = BL::where('BL_ID',$data->BL_ID)->first();
        array_push($view, array('NO'        => $i++,
                                'ID'        => $kegiatan->BL_ID,
                                'NAMA'      => $kegiatan->kegiatan->KEGIATAN_NAMA,
                                'RINCIAN'   => number_format($data->total,0,'.',','),
                                'OPD'       => $kegiatan->subunit->skpd->SKPD_NAMA));
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }

    public function rekeningApiDetail($tahun,$status,$id){
      $data     = Rincian::where('REKENING_ID',$id)
                    ->whereHas('bl',function($x){
                      $x->where('BL_DELETED',0);
                    })->select('BL_ID')->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL')
                    ->groupBy('BL_ID')->get();
      $i = 1;
      $view   = array();
      foreach ($data as $data) {
        $kegiatan   = BL::where('BL_ID',$data->BL_ID)->first();
        array_push($view, array('NO'        => $i++,
                                'ID'        => $kegiatan->BL_ID,
                                'NAMA'      => $kegiatan->kegiatan->KEGIATAN_NAMA,
                                'RINCIAN'   => number_format($data->total,0,'.',','),
                                'OPD'       => $kegiatan->subunit->skpd->SKPD_NAMA));
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }

    public function komponenApi($tahun,$status){
      $data     = Rincian::whereHas('bl',function($x){
                    $x->where('BL_DELETED',0);
                  })->groupBy('KOMPONEN_ID')
                  ->select('KOMPONEN_ID')
                  ->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL')
                  ->selectRaw('SUM("RINCIAN_VOLUME") AS VOLUME')
                  ->get();
      $i  = 1;
      $view   = array();
      foreach ($data as $data) {
        $nama   = Komponen::where('KOMPONEN_ID',$data->KOMPONEN_ID)->first();
        array_push($view, array('NO'        => $i++,
                                'ID'        => $data->KOMPONEN_ID,
                                'NAMA'      => $nama->KOMPONEN_NAMA,
                                'SATUAN'      => $nama->KOMPONEN_SATUAN,
                                'VOL'       => number_format($data->volume,0,'.',','),
                                'RINCIAN'   => number_format($data->total,0,'.',',')));
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }

    public function komponenApiDetail($tahun,$status,$id){
      $data     = Rincian::where('KOMPONEN_ID',$id)
                    ->whereHas('bl',function($x){
                      $x->where('BL_DELETED',0);
                    })->select('BL_ID')->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL')
                    ->groupBy('BL_ID')->get();
      $i = 1;
      $view   = array();
      foreach ($data as $data) {
        $kegiatan   = BL::where('BL_ID',$data->BL_ID)->first();
        array_push($view, array('NO'        => $i++,
                                'ID'        => $kegiatan->BL_ID,
                                'NAMA'      => $kegiatan->kegiatan->KEGIATAN_NAMA,
                                'RINCIAN'   => number_format($data->total,0,'.',','),
                                'OPD'       => $kegiatan->subunit->skpd->SKPD_NAMA));
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }

    public function paketApi($tahun,$status){
      $data     = Rincian::whereHas('bl',function($x){
                    $x->where('BL_DELETED',0);
                  })->groupBy('SUBRINCIAN_ID')
                  ->select('SUBRINCIAN_ID')
                  ->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL')
                  ->whereRaw('"SUBRINCIAN_ID" IS NOT NULL')
                  ->get();
      $i  = 1;
      $view   = array();
      foreach ($data as $data) {
        $nama   = Subrincian::where('SUBRINCIAN_ID',$data->SUBRINCIAN_ID)->first();
        array_push($view, array('NO'        => $i++,
                                'ID'        => $data->SUBRINCIAN_ID,
                                'NAMA'      => $nama->SUBRINCIAN_NAMA,
                                'RINCIAN'   => number_format($data->total,0,'.',',')));
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }

    public function paketApiDetail($tahun,$status,$id){
      $data     = Rincian::where('SUBRINCIAN_ID',$id)
                    ->whereHas('bl',function($x){
                      $x->where('BL_DELETED',0);
                    })->select('BL_ID')->selectRaw('SUM("RINCIAN_TOTAL") AS TOTAL')
                    ->groupBy('BL_ID')->get();
      $i = 1;
      $view   = array();
      foreach ($data as $data) {
        $kegiatan   = BL::where('BL_ID',$data->BL_ID)->first();
        array_push($view, array('NO'        => $i++,
                                'ID'        => $kegiatan->BL_ID,
                                'NAMA'      => $kegiatan->kegiatan->KEGIATAN_NAMA,
                                'RINCIAN'   => number_format($data->total,0,'.',','),
                                'OPD'       => $kegiatan->subunit->skpd->SKPD_NAMA));
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }

    public function porsiAPDBApi($tahun,$status){
      $data     = SKPD::orderBy('SKPD_KODE')->get();
      $view     = array();
      foreach($data as $data){
        $id           =  $data->SKPD_ID;
        $Kd_urusan    = substr($data->SKPD_KODE, 0,1)*1;
        $Kd_bidang    = substr($data->SKPD_KODE, 2,2)*1;
        $Kd_unit      = substr($data->SKPD_KODE, 5,2)*1;
        $t2017        = DB::connection('sqlsrv')
                        ->select("SELECT sum(Total) as Total 
                                  FROM dbo.Ta_Belanja_Rinc_Sub rincian
                                  LEFT JOIN dbo.Ta_Kegiatan kegiatan
                                  ON rincian.Kd_Urusan = kegiatan.Kd_Urusan
                                  and rincian.Kd_Unit = kegiatan.Kd_Unit
                                  and rincian.Kd_Bidang = kegiatan.Kd_Bidang 
                                  and rincian.Kd_Sub = kegiatan.Kd_Sub 
                                  and rincian.Kd_Prog = kegiatan.Kd_Prog 
                                  and rincian.ID_Prog = kegiatan.ID_Prog 
                                  and rincian.Kd_Keg = kegiatan.Kd_Keg                                  
                                  where rincian.Kd_Urusan = ".$Kd_urusan." 
                                  and rincian.Kd_Bidang = ".$Kd_bidang." 
                                  and rincian.Kd_unit = ".$Kd_unit." 
                                  and rincian.Kd_Prog != 0
                                  and kegiatan.Ket_Kegiatan not like '%(Banprov)'
                                  and kegiatan.Ket_Kegiatan not like '%(DAK)'");
        $total2017  = $t2017[0]->Total;
        $etc          =  Rincian::whereHas('bl',function($x) use($id){
                            $x->where('BL_DELETED',0)
                              ->whereNotIn('KEGIATAN_ID',[3394,3395,3396,3397])
                              ->where('PAGU_ID','!=',15)
                              ->whereHas('kegiatan',function($y) use($id){
                              $y->whereHas('program',function($z) use($id){
                                $z->whereNotIn('PROGRAM_KODE',['01','02','03','04','05','06','07','08']);
                              });
                              })->whereHas('subunit',function($y) use($id){
                                $y->where('SKPD_ID',$id);
                              });
                            })->where('RINCIAN_KETERANGAN','NOT LIKE','Musrenbang RW%')
                              ->Where('RINCIAN_KETERANGAN','NOT LIKE','Emusrenbang%')
                              ->where('RINCIAN_KETERANGAN','NOT LIKE','Reses Dewan%')
                              ->sum('RINCIAN_TOTAL');
        $musrenbang   =  Rincian::whereHas('bl',function($x) use($id){
                            $x->where('BL_DELETED',0)
                              ->whereHas('subunit',function($y) use($id){
                              $y->where('SKPD_ID',$id);
                            });
                          })->whereRaw('"RINCIAN_KETERANGAN" SIMILAR TO \'(Musrenbang RW|Emusrenbang)%\'')
                            // ->whereRaw('"RINCIAN_KETERANGAN" LIKE \'Musrenbang RW%\' OR "RINCIAN_KETERANGAN" LIKE \'EMusrenbang%\'')
                            ->sum('RINCIAN_TOTAL');
        $reses        =  Rincian::whereHas('bl',function($x) use($id){
                            $x->where('BL_DELETED',0)
                              ->whereHas('subunit',function($y) use($id){
                              $y->where('SKPD_ID',$id);
                            });
                          })->where('RINCIAN_KETERANGAN','LIKE','Reses Dewan%')->sum('RINCIAN_TOTAL');
        $arahan       =  Rincian::whereHas('bl',function($x) use($id){
                            $x->where('BL_DELETED',0)
                              ->WHERE('PAGU_ID',15)
                              ->whereHas('subunit',function($y) use($id){
                              $y->where('SKPD_ID',$id);
                            });
                          })->where('RINCIAN_KETERANGAN','NOT ILIKE','Reses Dewan%')
                            ->whereRaw('"RINCIAN_KETERANGAN" NOT SIMILAR TO \'(Musrenbang RW|Emusrenbang)%\'')
                            ->sum('RINCIAN_TOTAL');
        $pippkrw      = Rincian::whereHas('bl',function($x) use($id){
                            $x->where('BL_DELETED',0)
                              ->where('KEGIATAN_ID',3394)                            
                              ->whereHas('subunit',function($y) use($id){
                              $y->where('SKPD_ID',$id);
                            });
                          })->sum('RINCIAN_TOTAL');
        $pippkpkk     = Rincian::whereHas('bl',function($x) use($id){
                            $x->where('BL_DELETED',0)
                              ->where('KEGIATAN_ID',3395)                            
                              ->whereHas('subunit',function($y) use($id){
                              $y->where('SKPD_ID',$id);
                            });
                          })->sum('RINCIAN_TOTAL');
        $pippklpm     = Rincian::whereHas('bl',function($x) use($id){
                            $x->where('BL_DELETED',0)
                              ->where('KEGIATAN_ID',3397)                            
                              ->whereHas('subunit',function($y) use($id){
                              $y->where('SKPD_ID',$id);
                            });
                          })->sum('RINCIAN_TOTAL');
        $pippkkarta   = Rincian::whereHas('bl',function($x) use($id){
                            $x->where('BL_DELETED',0)
                              ->where('KEGIATAN_ID',3396)
                              ->whereHas('subunit',function($y) use($id){
                              $y->where('SKPD_ID',$id);
                            });
                          })->sum('RINCIAN_TOTAL');
        $pippk        = $pippkrw+$pippkkarta+$pippklpm+$pippkpkk;
        $nonurusan    =  Rincian::whereHas('bl',function($x) use($id){
                            $x->where('BL_DELETED',0)
                              ->whereHas('subunit',function($y) use($id){
                                $y->where('SKPD_ID',$id);
                              })->whereHas('kegiatan',function($y) use($id){
                              $y->whereHas('program',function($z) use($id){
                                $z->whereIn('PROGRAM_KODE',['01','02','03','04','05','06','07','08']);
                              });
                            });
                          })->sum('RINCIAN_TOTAL');
        array_push($view, array('KODE'          => $data->SKPD_KODE,
                                'SKPD'          => $data->SKPD_NAMA,
                                'ETC'           => number_format($etc,0,'.',','),
                                'ARAHAN'        => number_format($arahan,0,'.',','),
                                'RESES'         => number_format($reses,0,'.',','),
                                'MUSRENBANG'    => number_format($musrenbang,0,'.',','),
                                'PIPPK'         => number_format($pippk,0,'.',','),
                                'NONURUSAN'     => number_format($nonurusan,0,'.',','),
                                'T2017'         => number_format($total2017,0,'.',',')));

      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }


    public function kerangkaLogisApi($tahun,$status){
      $data   = Urgensi::where('URGENSI_LATAR_BELAKANG','!=',"")->get();
      $view   = array();
      foreach($data as $data){
        array_push($view, array('ID'          => $data->BL_ID,
                                'SKPD'        => $data->bl->subunit->skpd->SKPD_NAMA,
                                'KEGIATAN'    => $data->bl->kegiatan->KEGIATAN_NAMA));
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }


    public function programPrioritasApi($tahun,$status){
      $data   = DB::table('BUDGETING.DAT_BL')
                    ->join('REFERENSI.REF_SUB_UNIT','REFERENSI.REF_SUB_UNIT.SUB_ID','=','BUDGETING.DAT_BL.SUB_ID')
                    ->join('REFERENSI.REF_SKPD','REFERENSI.REF_SUB_UNIT.SKPD_ID','=','REFERENSI.REF_SKPD.SKPD_ID')
                    ->join('REFERENSI.REF_KEGIATAN','REFERENSI.REF_KEGIATAN.KEGIATAN_ID','=','BUDGETING.DAT_BL.KEGIATAN_ID')
                    ->join('REFERENSI.REF_PROGRAM','REFERENSI.REF_PROGRAM.PROGRAM_ID','=','REFERENSI.REF_KEGIATAN.PROGRAM_ID')
                    ->join('REFERENSI.REF_DETAIL_PRIORITAS','REFERENSI.REF_DETAIL_PRIORITAS.PROGRAM_ID','=','REFERENSI.REF_PROGRAM.PROGRAM_ID')
                    ->join('REFERENSI.REF_PRIORITAS','REFERENSI.REF_PRIORITAS.PRIORITAS_ID','=','REFERENSI.REF_DETAIL_PRIORITAS.PRIORITAS_ID')
                    ->select('SKPD_NAMA','PROGRAM_NAMA','PRIORITAS_NAMA','URUSAN_NAMA')
                    ->selectRaw('SUM("BL_PAGU") AS pagu')
                    ->groupBy('SKPD_NAMA')
                    ->groupBy('PROGRAM_NAMA')
                    ->groupBy('PRIORITAS_NAMA')
                    ->groupBy('URUSAN_NAMA')
                    ->groupBy('SKPD_KODE')
                    ->orderBy('PRIORITAS_NAMA')
                    ->get();


      //$data =               
                    
     // dd($data);
      $no     = 1;
      $view   = array();
      foreach($data as $data){
        array_push($view, array('NO'        => $no,
                                'PRIORITAS' => $data->PRIORITAS_NAMA,
                                'URUSAN'    => $data->URUSAN_NAMA,
                                'SKPD'      => $data->SKPD_NAMA,
                                'PROGRAM'   => $data->PROGRAM_NAMA,
                                'ANGGARAN'  => number_format($data->pagu,0,'.',','),
                                'OPSI'      => '<a href="'.url('/main/'.$tahun.'/'.$status.'/statistik/prioritas/kegiatan/'.$data->PRIORITAS_NAMA).'" target="_blank"><i class="mi-eye"></i></a>'              
                                ));
        $no++;
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
    }

    public function kegiatanPrioritasApi($tahun,$status){
      $data   = BL::whereHas('kegiatan',function($q){
                  $q->where('KEGIATAN_PRIORITAS',1);
                })->get();
      $no     = 1;
      $view   = array();
      foreach($data as $data){
        array_push($view, array('NO'        => $no,
                                'SKPD'      => $data->subunit->skpd->SKPD_NAMA,
                                'KEGIATAN'  => $data->kegiatan->KEGIATAN_NAMA,
                                'ANGGARAN'  => number_format($data->BL_PAGU,0,'.',',')));
        $no++;
      }
      $out = array("aaData"=>$view);      
      return Response::JSON($out);
}
}