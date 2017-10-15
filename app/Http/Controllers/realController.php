<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Model\Pendapatan;
Use App\Model\Pembiayaan;
Use App\Model\BL;
Use App\Model\BLPerubahan;
Use App\Model\Rincian;
Use App\Model\RincianPerubahan;
Use App\Model\BTL;
Use App\Model\Rekening;
Use App\Model\Output;
Use App\Model\Urusan;
Use App\Model\Program;
Use App\Model\Kegiatan;
Use App\Model\Subunit;
Use App\Model\Kunci;
Use App\Model\Subrincian;
Use App\Model\UserBudget;
Use App\Model\SKPD;
Use App\Model\Realisasi;
Use App\Model\Komponen;
Use App\Model\Progunit;
Use App\Model\Kegunit;
Use Response;
Use DB;
class realController extends Controller
{
	public function index($tahun){
		return View('real',['tahun'=>$tahun]);
	}

	public function trfnamakomponen(){
		$data 			= Rincian::where('RINCIAN_KOMPONEN',null)->get();
		$i = 1;
		foreach($data as $data){
			$komponen 		= Komponen::where('KOMPONEN_ID',$data->KOMPONEN_ID)->first();
			Rincian::where('RINCIAN_ID',$data->RINCIAN_ID)->update(['RINCIAN_KOMPONEN'=>$komponen->KOMPONEN_NAMA,'RINCIAN_HARGA'=>$komponen->KOMPONEN_HARGA]);	
			$i++;
		}
		print_r(count($data));
		print_r('<br>');
		print_r($i);
	}

	public function trfprogram($tahun,$kode){
        $idprogram  = Program::where('PROGRAM_KODE',$kode)->where('PROGRAM_TAHUN')->select('PROGRAM_ID')->get()->toArray();
        Progunit::whereIn('PROGRAM_ID',$idprogram)->delete();
        $skpd   = SKPD::where('SKPD_TAHUN',$tahun)->get();
        foreach($skpd as $skpd){
            $urusan     = substr($skpd->SKPD_KODE, 0,4);
            $urusan     = Urusan::where('URUSAN_KODE',$urusan)->where('URUSAN_TAHUN',$tahun)->value('URUSAN_ID');
            $program    = Program::where('PROGRAM_KODE',$kode)->where('PROGRAM_TAHUN',$tahun)->where('URUSAN_ID',$urusan)->value('PROGRAM_ID');
            if($program){
	            $progunit   = new Progunit;
	            $progunit->PROGRAM_ID   = $program;
	            $progunit->SKPD_ID      = $skpd->SKPD_ID;
	            $progunit->save();
				
				Kegunit::whereIn('KEGIATAN_ID',Kegiatan::where('PROGRAM_ID',$program)->select('KEGIATAN_ID')->get()->toArray())->delete();
	            $kegiatan 		= Kegiatan::where('PROGRAM_ID',$program)->get();
	            foreach($kegiatan as $keg){
	            	$kegunit 	= new Kegunit;
	            	$kegunit->KEGIATAN_ID 		= $keg->KEGIATAN_ID;
	            	$kegunit->SKPD_ID 			= $skpd->SKPD_ID;
	            	$kegunit->save();
	            }
            }
        }
	}

	public function trfnamakomponenperubahan(){
		$data 			= RincianPerubahan::where('RINCIAN_KOMPONEN',null)->get();
		$i = 1;
		foreach($data as $data){
			$komponen 		= Komponen::where('KOMPONEN_ID',$data->KOMPONEN_ID)->first();
			RincianPerubahan::where('RINCIAN_ID',$data->RINCIAN_ID)->update(['RINCIAN_KOMPONEN'=>$komponen->KOMPONEN_NAMA,'RINCIAN_HARGA'=>$komponen->KOMPONEN_HARGA]);	
			$i++;
		}
		print_r(count($data));
		print_r('<br>');
		print_r($i);
	}


	public function getdata($tahun){
		$bl 				= Rincian::whereHas('bl',function($x){$x->where('BL_DELETED',0);})->sum('RINCIAN_TOTAL');
    	$pendapatan 		= Pendapatan::sum('PENDAPATAN_TOTAL');
    	$pagu 				= BL::where('BL_DELETED',0)->sum('BL_PAGU');
    	$btl 				= BTL::sum('BTL_TOTAL');
    	// $penerimaan     	= 268977102031;
    	$penerimaan     	= 0;
        $pengeluaran    	= 0;
        $defisit 			= ($bl+$btl+$pengeluaran) - ($pendapatan+$penerimaan);
        $defisit_ 			= ($pagu+$btl+$pengeluaran) - ($pendapatan+$penerimaan);
		$data 				= [ 'PENDAPATAN'	=> number_format($pendapatan,0,'.',','),
								'BL'			=> number_format($bl,0,'.',','),
								'PAGU'			=> number_format($pagu,0,'.',','),
								'BTL'			=> number_format($btl,0,'.',','),
								'DEFISIT'	 	=> number_format($defisit,0,'.',','),
								'DEFISIT_'	 	=> number_format($defisit_,0,'.',',')];
		return Response::JSON($data);
	}
	public function transferoutput($tahun){
		$data 	= DB::table('REFERENSI.REF_OUTPUT')->get();
		$i 	= 1;
		foreach($data as $data){
			$bl 	= BL::where('KEGIATAN_ID',$data->KEGIATAN_ID)->get();
			foreach ($bl as $bl) {
				$o 	= new Output;
				$o->BL_ID 				= $bl->BL_ID;
				$o->OUTPUT_TOLAK_UKUR 	= $data->OUTPUT_TOLAK_UKUR;
				$o->OUTPUT_TARGET		= $data->OUTPUT_TARGET;
				$o->SATUAN_ID 			= $data->SATUAN_ID;
				$o->save();
				echo "<a id='id'>".$i++."</a><br>";
			}
		}
		print_r('done');
	}

	public function getfromsimda($tahun){
		$data  	= array('tahun'=>$tahun);
		return view('getsimda',$data);
	}

	public function getStatus($tahun,$param,$kodeperubahan){
		$view 	= array();
		if($param == 'urusan'){
			$data 	= DB::connection('sqlsrv')->table('dbo.Ref_Bidang')->get();
			foreach($data as $data){
				$kode 	= $data->Kd_Urusan;
				if($data->Kd_Bidang < 10) $kode = $kode.'.0'.$data->Kd_Bidang;
				else $kode = $kode.'.'.$data->Kd_Bidang;

				$budgeting 	= Urusan::where('URUSAN_KODE',$kode)->value('URUSAN_ID');
				if($budgeting) $status = '<span class="text text-success"><i class="fa fa-check"></i></span>';
				else $status = '<span class="text text-danger"><i class="fa fa-close"></i></span>';
				$aksi 		= '-';				
				array_push($view,array('KODE'=>$kode,'URAIAN'=>$data->Nm_Bidang,'STATUS'=>$status,'SIMDA'=>"-",'BUDGETING'=>"-",'AKSI'=>$aksi));
			}
		}elseif($param == 'belanja'){
			$data 	= DB::connection('sqlsrv')->table('dbo.Ref_Unit')->get();
			foreach($data as $data){
				$simda 	= DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Tahun',$tahun)
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Kd_Urusan',$data->Kd_Urusan)
							->where('Kd_Bidang',$data->Kd_Bidang)
							->where('Kd_Unit',$data->Kd_Unit)
							->where('Kd_Prog','!=',0)
							->groupBy('Tahun','Kd_Perubahan','Kd_Urusan','Kd_Bidang','Kd_Unit','Kd_Sub','Kd_Prog','ID_Prog','Kd_Keg')
							->select('Tahun','Kd_Perubahan','Kd_Urusan','Kd_Bidang','Kd_Unit','Kd_Sub','Kd_Prog','ID_Prog','Kd_Keg')
							->get();
				$simda 	= count($simda);
				$kode 	= $data->Kd_Urusan;
				if($data->Kd_Bidang < 10) $kode = $kode.'.0'.$data->Kd_Bidang;
				else $kode = $kode.'.'.$data->Kd_Bidang;
				if($data->Kd_Unit < 10) $kode = $kode.'.0'.$data->Kd_Unit;
				else $kode = $kode.'.'.$data->Kd_Unit;

				$budgeting 	= BL::whereHas('subunit',function($sub) use($tahun,$kode){
					$sub->whereHas('skpd',function($skpd) use($tahun,$kode){
						$skpd->where('SKPD_TAHUN',$tahun)->where('SKPD_KODE',$kode);
					});
				})->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0)->count();
				if($simda == 0) $status = "100 %";
				else $status 	= number_format($budgeting/$simda*100,0,'.',',').' %';
				$aksi 		= '<button class="btn btn-success" onclick="return transferbelanja(\''.$kode.'\')"><i class="fa fa-retweet"></i></button>';				
				array_push($view,array('KODE'=>$kode,'URAIAN'=>$data->Nm_Unit,'STATUS'=>$status,'SIMDA'=>$simda,'BUDGETING'=>$budgeting,'AKSI'=>$aksi));
			}
		}elseif($param == 'subrincian'){
			$data 	= DB::connection('sqlsrv')->table('dbo.Ref_Unit')->get();
			foreach($data as $data){
				$simda 	= DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->groupBy('Tahun','Kd_Perubahan','Kd_Urusan','Kd_Bidang','Kd_Unit','Kd_Sub','Kd_Prog','ID_Prog','Kd_Keg','Kd_Rek_1','Kd_Rek_2','Kd_Rek_3','Kd_Rek_4','Kd_Rek_5','No_Rinc','Keterangan_Rinc')
							->select('Tahun','Kd_Perubahan','Kd_Urusan','Kd_Bidang','Kd_Unit','Kd_Sub','Kd_Prog','ID_Prog','Kd_Keg','Kd_Rek_1','Kd_Rek_2','Kd_Rek_3','Kd_Rek_4','Kd_Rek_5','No_Rinc','Keterangan_Rinc')
							->where('Kd_Prog','!=',0)
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Kd_Urusan',$data->Kd_Urusan)
							->where('Kd_Bidang',$data->Kd_Bidang)
							->where('Kd_Unit',$data->Kd_Unit)							
							->where('Tahun',$tahun)
							->get();
				$simda 	= count($simda);
				$kode 	= $data->Kd_Urusan;
				if($data->Kd_Bidang < 10) $kode = $kode.'.0'.$data->Kd_Bidang;
				else $kode = $kode.'.'.$data->Kd_Bidang;
				if($data->Kd_Unit < 10) $kode = $kode.'.0'.$data->Kd_Unit;
				else $kode = $kode.'.'.$data->Kd_Unit;

				$budgeting 	= Subrincian::whereHas('bl',function($bl) use($tahun,$kode){
								$bl->whereHas('subunit',function($sub) use($tahun,$kode){
									$sub->whereHas('skpd',function($skpd) use($tahun,$kode){
										$skpd->where('SKPD_TAHUN',$tahun)->where('SKPD_KODE',$kode);
									});
								})->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0);
							})->count();
				if($simda == 0) $status = "100 %";
				else $status 	= number_format($budgeting/$simda*100,0,'.',',').' %';
				$aksi 		= '<button class="btn btn-success" onclick="return transfersubrincian(\''.$kode.'\')"><i class="fa fa-retweet"></i></button>';				
				array_push($view,array('KODE'=>$kode,'URAIAN'=>$data->Nm_Unit,'STATUS'=>$status,'SIMDA'=>$simda,'BUDGETING'=>$budgeting,'AKSI'=>$aksi));
			}
		}elseif($param == 'rincian'){
			$data 	= DB::connection('sqlsrv')->table('dbo.Ref_Unit')->get();
			foreach($data as $data){
				$simda 	= DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Tahun',$tahun)
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Kd_Urusan',$data->Kd_Urusan)
							->where('Kd_Bidang',$data->Kd_Bidang)
							->where('Kd_Unit',$data->Kd_Unit)
							->where('Kd_Prog','!=',0)
							->sum('Total');
				$kode 	= $data->Kd_Urusan;
				if($data->Kd_Bidang < 10) $kode = $kode.'.0'.$data->Kd_Bidang;
				else $kode = $kode.'.'.$data->Kd_Bidang;
				if($data->Kd_Unit < 10) $kode = $kode.'.0'.$data->Kd_Unit;
				else $kode = $kode.'.'.$data->Kd_Unit;

				$budgeting 	= Rincian::whereHas('bl',function($bl) use($tahun,$kode){
					$bl->whereHas('subunit',function($sub) use($tahun,$kode){
						$sub->whereHas('skpd',function($skpd) use($tahun,$kode){
							$skpd->where('SKPD_TAHUN',$tahun)->where('SKPD_KODE',$kode);
						});
					})->where('BL_TAHUN',$tahun)->where('BL_VALIDASI',1)->where('BL_DELETED',0);
				})->sum('RINCIAN_TOTAL');
				if($simda == 0) $status = "100 %";
				else $status 	= number_format($budgeting/$simda*100,0,'.',',').' %';
				$simda 		= number_format($simda,0,'.',',');
				$budgeting  = number_format($budgeting,0,'.',',');
				$aksi 		= '<button class="btn btn-success" onclick="return transferrincian(\''.$kode.'\')"><i class="fa fa-retweet"></i></button>';
				array_push($view,array('KODE'=>$kode,'URAIAN'=>$data->Nm_Unit,'STATUS'=>$status,'SIMDA'=>$simda,'BUDGETING'=>$budgeting,'AKSI'=>$aksi));
			}
		}elseif($param == 'btl'){
			$data 	= DB::connection('sqlsrv')->table('dbo.Ref_Unit')->get();
			foreach($data as $data){
				$simda 	= DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Tahun',$tahun)
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Kd_Urusan',$data->Kd_Urusan)
							->where('Kd_Bidang',$data->Kd_Bidang)
							->where('Kd_Unit',$data->Kd_Unit)
							->where('Kd_Prog',0)
							->sum('Total');
				$kode 	= $data->Kd_Urusan;
				if($data->Kd_Bidang < 10) $kode = $kode.'.0'.$data->Kd_Bidang;
				else $kode = $kode.'.'.$data->Kd_Bidang;
				if($data->Kd_Unit < 10) $kode = $kode.'.0'.$data->Kd_Unit;
				else $kode = $kode.'.'.$data->Kd_Unit;

				$budgeting 	= BTL::whereHas('subunit',function($sub)use($tahun,$kode){
									$sub->whereHas('skpd',function($skpd) use($tahun,$kode){
										$skpd->where('SKPD_TAHUN',$tahun)->where('SKPD_KODE',$kode);
									});
								})->sum('BTL_TOTAL');
				if($simda == 0) $status = "100 %";
				else $status 	= number_format($budgeting/$simda*100,0,'.',',').' %';
				$simda 		= number_format($simda,0,'.',',');
				$budgeting  = number_format($budgeting,0,'.',',');
				$aksi 		= '<button class="btn btn-success" onclick="return transferbtl(\''.$kode.'\')"><i class="fa fa-retweet"></i></button>';
				array_push($view,array('KODE'=>$kode,'URAIAN'=>$data->Nm_Unit,'STATUS'=>$status,'SIMDA'=>$simda,'BUDGETING'=>$budgeting,'AKSI'=>$aksi));
			}
		}elseif($param == 'pendapatan'){
			$data 	= DB::connection('sqlsrv')->table('dbo.Ref_Unit')->get();
			foreach($data as $data){
				$simda 	= DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Tahun',$tahun)
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Kd_Urusan',$data->Kd_Urusan)
							->where('Kd_Bidang',$data->Kd_Bidang)
							->where('Kd_Unit',$data->Kd_Unit)
							->where('Kd_Rek_1',4)
							->sum('Total');
				$kode 	= $data->Kd_Urusan;
				if($data->Kd_Bidang < 10) $kode = $kode.'.0'.$data->Kd_Bidang;
				else $kode = $kode.'.'.$data->Kd_Bidang;
				if($data->Kd_Unit < 10) $kode = $kode.'.0'.$data->Kd_Unit;
				else $kode = $kode.'.'.$data->Kd_Unit;

				$budgeting 	= Pendapatan::whereHas('subunit',function($sub)use($tahun,$kode){
									$sub->whereHas('skpd',function($skpd) use($tahun,$kode){
										$skpd->where('SKPD_TAHUN',$tahun)->where('SKPD_KODE',$kode);
									});
								})->sum('PENDAPATAN_TOTAL');
				if($simda == 0) $status = "100 %";
				else $status 	= number_format($budgeting/$simda*100,0,'.',',').' %';
				$simda 		= number_format($simda,0,'.',',');
				$budgeting  = number_format($budgeting,0,'.',',');
				$aksi 		= '<button class="btn btn-success" onclick="return transferpendapatan(\''.$kode.'\')"><i class="fa fa-retweet"></i></button>';
				array_push($view,array('KODE'=>$kode,'URAIAN'=>$data->Nm_Unit,'STATUS'=>$status,'SIMDA'=>$simda,'BUDGETING'=>$budgeting,'AKSI'=>$aksi));
			}
		}elseif($param == 'pembiayaan'){
			$data 	= DB::connection('sqlsrv')->table('dbo.Ref_Unit')->get();
			foreach($data as $data){
				$simda 	= DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Tahun',$tahun)
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Kd_Urusan',$data->Kd_Urusan)
							->where('Kd_Bidang',$data->Kd_Bidang)
							->where('Kd_Unit',$data->Kd_Unit)
							->where('Kd_Rek_1',6)
							->sum('Total');
				$kode 	= $data->Kd_Urusan;
				if($data->Kd_Bidang < 10) $kode = $kode.'.0'.$data->Kd_Bidang;
				else $kode = $kode.'.'.$data->Kd_Bidang;
				if($data->Kd_Unit < 10) $kode = $kode.'.0'.$data->Kd_Unit;
				else $kode = $kode.'.'.$data->Kd_Unit;

				$budgeting 	= Pembiayaan::whereHas('subunit',function($sub)use($tahun,$kode){
									$sub->whereHas('skpd',function($skpd) use($tahun,$kode){
										$skpd->where('SKPD_TAHUN',$tahun)->where('SKPD_KODE',$kode);
									});
								})->sum('PEMBIAYAAN_TOTAL');
				if($simda == 0) $status = "100 %";
				else $status 	= number_format($budgeting/$simda*100,0,'.',',').' %';
				$simda 		= number_format($simda,0,'.',',');
				$budgeting  = number_format($budgeting,0,'.',',');
				$aksi 		= '<button class="btn btn-success" onclick="return transferpembiayaan(\''.$kode.'\')"><i class="fa fa-retweet"></i></button>';
				array_push($view,array('KODE'=>$kode,'URAIAN'=>$data->Nm_Unit,'STATUS'=>$status,'SIMDA'=>$simda,'BUDGETING'=>$budgeting,'AKSI'=>$aksi));
			}
		}elseif($param == 'program'){
			$simda = DB::connection('sqlsrv')
								->table('dbo.Ta_Program')
								->where('Kd_Prog','!=',0)
								->where('Tahun',$tahun)
								->count();
			$budgeting  = Program::where('PROGRAM_TAHUN',$tahun)->count();	
			$aksi 		= '<button class="btn btn-success" onclick="return transferprogram()"><i class="fa fa-retweet"></i></button>';	
			$status 	= number_format($budgeting/$simda*100,0,'.',',')." %";
			array_push($view,array('KODE'=>'-','URAIAN'=>'TOTAL PROGRAM','STATUS'=>$status,'SIMDA'=>$simda,'BUDGETING'=>$budgeting,'AKSI'=>$aksi));			
		}elseif($param == 'kegiatan'){
			$simda = DB::connection('sqlsrv')
								->table('dbo.Ta_Kegiatan')
								->where('Kd_Prog','!=',0)
								->where('Tahun',$tahun)
								->count();
			$budgeting = Kegiatan::where('KEGIATAN_TAHUN',$tahun)->count();			
			$aksi 		= '<button class="btn btn-success" onclick="return transferkegiatan()"><i class="fa fa-retweet"></i></button>';	
			$status 	= number_format($budgeting/$simda*100,0,'.',',')." %";
			array_push($view,array('KODE'=>'-','URAIAN'=>'TOTAL KEGIATAN','STATUS'=>$status,'SIMDA'=>$simda,'BUDGETING'=>$budgeting,'AKSI'=>$aksi));			
		}
		$out = array("aaData"=>$view);      
        return Response::JSON($out);
	}

	public function transferUrusanFromSimda($tahun){
		$urusan 	= DB::connection('sqlsrv')
							->table('dbo.Ref_Bidang')
							->get();
		foreach($urusan as $urusan){
			$kode 	= $urusan->Kd_Urusan;
			if($urusan->Kd_Bidang < 10) $kode = $kode.'.0'.$urusan->Kd_Bidang;
			else $kode = $kode.'.'.$urusan->Kd_Bidang;
			$ur = new Urusan;
			$ur->URUSAN_TAHUN 	= $tahun;
			$ur->URUSAN_KODE	= $kode;
			$ur->URUSAN_NAMA 	= $urusan->Nm_Bidang;
			$ur->save();
		}
		$count_lama = DB::connection('sqlsrv')
							->table('dbo.Ref_Bidang')
							->count();
		$count_baru = Urusan::where('URUSAN_TAHUN',$tahun)->count();
		return 'lama = '.$count_lama.'<br>baru = '.$count_baru;
	}

	public function transferProgramFromSimda($tahun){
		$program 	= DB::connection('sqlsrv')
							->table('dbo.Ta_Program')
							->where('Kd_Prog','!=',0)
							->where('Tahun',$tahun)
							->get();
		foreach($program as $p){
			if($p->Kd_Prog < 10) $kode = '0'.$p->Kd_Prog;
			else $kode = $p->Kd_Prog;
			$kdurusan 	= substr($p->ID_Prog, 0,1).'.'.substr($p->ID_Prog,1,2);
			$urusan 	= Urusan::where('URUSAN_TAHUN',$tahun)
								->where('URUSAN_KODE',$kdurusan)
								->value('URUSAN_ID');
			$prog 	= new Program;
			$prog->PROGRAM_TAHUN 	= $tahun;
			$prog->URUSAN_ID 		= $urusan;
			$prog->PROGRAM_KODE 	= $kode;
			$prog->PROGRAM_NAMA 	= $p->Ket_Program;
			$prog->save();
		}
		$count_lama = DB::connection('sqlsrv')
							->table('dbo.Ta_Program')
							->where('Kd_Prog','!=',0)
							->where('Tahun',$tahun)
							->count();
		$count_baru = Program::where('PROGRAM_TAHUN',$tahun)->count();
		return 'lama = '.$count_lama.'<br>baru = '.$count_baru;		
	}

	public function transferKegiatanFromSimda($tahun){
		$program 	= DB::connection('sqlsrv')
							->table('dbo.Ta_Kegiatan')
							->where('Kd_Prog','!=',0)
							->where('Tahun',$tahun)
							->get();
		foreach($program as $p){
			if($p->Kd_Keg < 10) $kode = '00'.$p->Kd_Keg;
			elseif($p->Kd_Keg < 100) $kode = '0'.$p->Kd_Keg;
			else $kode = $p->Kd_Keg;
			$kdurusan 	= substr($p->ID_Prog, 0,1).'.'.substr($p->ID_Prog,1,2);
			if($p->Kd_Prog < 10) $kdprogram = '0'.$p->Kd_Prog;
			else $kdprogram = $p->Kd_Prog;
			$program 	= Program::where('PROGRAM_TAHUN',$tahun)
								->where('PROGRAM_KODE',$kdprogram)
								->whereHas('urusan',function($q) use($tahun,$kdurusan){
									$q->where('URUSAN_TAHUN',$tahun)->where('URUSAN_KODE',$kdurusan);
							  })->value('PROGRAM_ID');
			$nama 		= $this->utf8ize($p->Ket_Kegiatan);
			$keg 	= new Kegiatan;
			$keg->KEGIATAN_TAHUN 	= $tahun;
			$keg->PROGRAM_ID 		= $program;
			$keg->KEGIATAN_KODE 	= $kode;
			$keg->KEGIATAN_NAMA 	= $nama;
			$keg->KEGIATAN_KUNCI 	= 0;
			$keg->save();
		}
		$count_lama = DB::connection('sqlsrv')
							->table('dbo.Ta_Kegiatan')
							->where('Kd_Prog','!=',0)
							->where('Tahun',$tahun)
							->count();
		$count_baru = Kegiatan::where('KEGIATAN_TAHUN',$tahun)->count();
		return 'lama = '.$count_lama.'<br>baru = '.$count_baru;		
	}

	public function transferBelanjaFromSimda($tahun,$kodeperubahan,$skpd){
		$Kd_Urusan  = substr($skpd, 0,1);
		$Kd_Bidang  = substr($skpd, 2,2);
		$Kd_Unit 	= substr($skpd, 5,2);		
		$belanja 	= DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->groupBy('Tahun','Kd_Perubahan','Kd_Urusan','Kd_Bidang','Kd_Unit','Kd_Sub','Kd_Prog','ID_Prog','Kd_Keg')
							->select('Tahun','Kd_Perubahan','Kd_Urusan','Kd_Bidang','Kd_Unit','Kd_Sub','Kd_Prog','ID_Prog','Kd_Keg')
							->where('Kd_Prog','!=',0)
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Tahun',$tahun);
		if($skpd == 0) $belanja = $belanja->get();
		else $belanja = $belanja->where('Kd_Urusan',$Kd_Urusan)->where('Kd_Bidang',$Kd_Bidang)->where('Kd_Unit',$Kd_Unit)->get();							
		
		foreach($belanja as $p){
			if($p->Kd_Keg < 10) $kode = '00'.$p->Kd_Keg;
			elseif($p->Kd_Keg < 100) $kode = '0'.$p->Kd_Keg;
			else $kode = $p->Kd_Keg;
			$kdurusan 	= substr($p->ID_Prog, 0,1).'.'.substr($p->ID_Prog,1,2);
			if($p->Kd_Prog < 10) $kdprogram = '0'.$p->Kd_Prog;
			else $kdprogram = $p->Kd_Prog;
			$kegiatan 	= Kegiatan::where('KEGIATAN_TAHUN',$tahun)
								->where('KEGIATAN_KODE',$kode)
								->whereHas('program',function($p) use($tahun,$kdprogram,$kdurusan){
									$p->whereHas('urusan',function($u) use($tahun,$kdurusan){
										$u->where('URUSAN_TAHUN',$tahun)
										->where('URUSAN_KODE',$kdurusan);
									})
									->where('PROGRAM_TAHUN',$tahun)
									->where('PROGRAM_KODE',$kdprogram);
								})->value('KEGIATAN_ID');

			if($p->Kd_Sub < 10) $kodesub = '0'.$p->Kd_Sub;
			else $kodesub = $p->Kd_Sub;
			$skpd 	= $p->Kd_Urusan;
			if($p->Kd_Bidang < 10) $skpd = $skpd.'.0'.$p->Kd_Bidang;
			else $skpd = $skpd.'.'.$p->Kd_Bidang;
			if($p->Kd_Unit < 10) $skpd = $skpd.'.0'.$p->Kd_Unit;
			else $skpd = $skpd.'.'.$p->Kd_Unit;
			$subunit 	= Subunit::where('SUB_KODE',$kodesub)
								->whereHas('skpd',function($s) use($tahun,$skpd){
									$s->where('SKPD_TAHUN',$tahun)->where('SKPD_KODE',$skpd);
								})->value('SUB_ID');

			$pagu 	= DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Tahun',$tahun)
							->where('Kd_Urusan',$p->Kd_Urusan)
							->where('Kd_Bidang',$p->Kd_Bidang)
							->where('Kd_Unit',$p->Kd_Unit)
							->where('Kd_Sub',$p->Kd_Sub)
							->where('Kd_Prog',$p->Kd_Prog)
							->where('ID_Prog',$p->ID_Prog)
							->where('Kd_Keg',$p->Kd_Keg)
							->where('Kd_Rek_1',5)
							->where('Kd_Rek_2',2)
							->sum('Total');
			$bl = new BL;
			$bl->BL_TAHUN 			= $tahun;
			$bl->KEGIATAN_ID 		= $kegiatan;
			$bl->JENIS_ID 			= 0;
			$bl->SUMBER_ID 			= 0;
			$bl->PAGU_ID 			= 0;
			$bl->SASARAN_ID 		= 0;
			$bl->LOKASI_ID 			= 0;
			$bl->BL_STATUS 			= 1;
			$bl->BL_VALIDASI 		= 1;
			$bl->BL_AWAL 			= 'Januari';
			$bl->BL_AKHIR 			= 'Desember';
			$bl->BL_PAGU 			= $pagu;
			$bl->BL_DELETED 		= 0;
			$bl->SUB_ID 			= $subunit;
			$bl->BL_TAG 			= '{0}';
			$bl->save();
		}
		$bl 		= BL::where('BL_TAHUN',$tahun)->select('BL_ID')->get();
		foreach($bl as $bl){
			$kunci = new Kunci;
			$kunci->BL_ID 			= $bl->BL_ID;
			$kunci->KUNCI_GIAT 		= 0;
			$kunci->KUNCI_RINCIAN 	= 0;
			$kunci->KUNCI_AKB 		= 0;
			$kunci->save();
 		}
		$count_lama = DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Kd_Perubahan',$kodeperubahan)						
							->where('Kd_Prog','!=',0)
							->where('Kd_Rek_1',5)
							->where('Kd_Rek_2',2)
							->where('Tahun',$tahun)
							->sum('Total');
		$count_baru = BL::where('BL_TAHUN',$tahun)->sum('BL_PAGU');
		return 'lama = '.$count_lama.'<br>baru = '.$count_baru;		
	}

	public function transferSubrincianFromSimda($tahun,$kodeperubahan,$skpd){
		$Kd_Urusan  = substr($skpd, 0,1);
		$Kd_Bidang  = substr($skpd, 2,2);
		$Kd_Unit 	= substr($skpd, 5,2);		
		$belanja 	= DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->groupBy('Tahun','Kd_Perubahan','Kd_Urusan','Kd_Bidang','Kd_Unit','Kd_Sub','Kd_Prog','ID_Prog','Kd_Keg','Kd_Rek_1','Kd_Rek_2','Kd_Rek_3','Kd_Rek_4','Kd_Rek_5','No_Rinc','Keterangan_Rinc')
							->select('Tahun','Kd_Perubahan','Kd_Urusan','Kd_Bidang','Kd_Unit','Kd_Sub','Kd_Prog','ID_Prog','Kd_Keg','Kd_Rek_1','Kd_Rek_2','Kd_Rek_3','Kd_Rek_4','Kd_Rek_5','No_Rinc','Keterangan_Rinc')
							->where('Kd_Prog','!=',0)
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Tahun',$tahun);
		if($skpd == 0) $belanja = $belanja->get();
		else $belanja = $belanja->where('Kd_Urusan',$Kd_Urusan)->where('Kd_Bidang',$Kd_Bidang)->where('Kd_Unit',$Kd_Unit)->get();

		foreach($belanja as $p){
			if($p->Kd_Keg < 10) $kode = '00'.$p->Kd_Keg;
			elseif($p->Kd_Keg < 100) $kode = '0'.$p->Kd_Keg;
			else $kode = $p->Kd_Keg;
			$kdurusan 	= substr($p->ID_Prog, 0,1).'.'.substr($p->ID_Prog,1,2);
			if($p->Kd_Prog < 10) $kdprogram = '0'.$p->Kd_Prog;
			else $kdprogram = $p->Kd_Prog;
			$kegiatan 	= Kegiatan::where('KEGIATAN_TAHUN',$tahun)
								->where('KEGIATAN_KODE',$kode)
								->whereHas('program',function($p) use($tahun,$kdprogram,$kdurusan){
									$p->whereHas('urusan',function($u) use($tahun,$kdurusan){
										$u->where('URUSAN_TAHUN',$tahun)
										->where('URUSAN_KODE',$kdurusan);
									})
									->where('PROGRAM_TAHUN',$tahun)
									->where('PROGRAM_KODE',$kdprogram);
								})->value('KEGIATAN_ID');
			if($p->Kd_Sub < 10) $kodesub = '0'.$p->Kd_Sub;
			else $kodesub = $p->Kd_Sub;
			$skpd 	= $p->Kd_Urusan;
			if($p->Kd_Bidang < 10) $skpd = $skpd.'.0'.$p->Kd_Bidang;
			else $skpd = $skpd.'.'.$p->Kd_Bidang;
			if($p->Kd_Unit < 10) $skpd = $skpd.'.0'.$p->Kd_Unit;
			else $skpd = $skpd.'.'.$p->Kd_Unit;
			$subunit 	= Subunit::where('SUB_KODE',$kodesub)
								->whereHas('skpd',function($s) use($tahun,$skpd){
									$s->where('SKPD_TAHUN',$tahun)->where('SKPD_KODE',$skpd);
								})->value('SUB_ID');
			$bl 	= BL::where('BL_TAHUN',$tahun)
						->where('KEGIATAN_ID',$kegiatan)
						->where('SUB_ID',$subunit)
						->value('BL_ID');
			$sub 	= new Subrincian;
			$sub->SUBRINCIAN_NAMA 		= $p->Keterangan_Rinc;
			$sub->BL_ID 				= $bl;
			$sub->save();
		}
		$count_lama = count($belanja);
		$count_baru = Subrincian::whereHas('bl',function($bl) use($tahun){
						$bl->where('BL_TAHUN',$tahun);
					})->count();
		return 'lama = '.$count_lama.'<br>baru = '.$count_baru;		
	}

	public function transferRincianFromSimda($tahun,$kodeperubahan,$skpd){
		$Kd_Urusan  = substr($skpd, 0,1);
		$Kd_Bidang  = substr($skpd, 2,2);
		$Kd_Unit 	= substr($skpd, 5,2);
		$belanja 	= DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Kd_Prog','!=',0)
							->where('Kd_Rek_1',5)
							->where('Kd_Rek_2',2)
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Tahun',$tahun);
		if($skpd == 0) $belanja = $belanja->get();
		else $belanja = $belanja->where('Kd_Urusan',$Kd_Urusan)->where('Kd_Bidang',$Kd_Bidang)->where('Kd_Unit',$Kd_Unit)->get();
		$count 		= count($belanja);
		$i 			= 0;							
		foreach($belanja as $p){
			if($p->Kd_Keg < 10) $kode = '00'.$p->Kd_Keg;
			elseif($p->Kd_Keg < 100) $kode = '0'.$p->Kd_Keg;
			else $kode = $p->Kd_Keg;
			$kdurusan 	= substr($p->ID_Prog, 0,1).'.'.substr($p->ID_Prog,1,2);
			if($p->Kd_Prog < 10) $kdprogram = '0'.$p->Kd_Prog;
			else $kdprogram = $p->Kd_Prog;
			$kegiatan 	= Kegiatan::where('KEGIATAN_TAHUN',$tahun)
								->where('KEGIATAN_KODE',$kode)
								->whereHas('program',function($p) use($tahun,$kdprogram,$kdurusan){
									$p->whereHas('urusan',function($u) use($tahun,$kdurusan){
										$u->where('URUSAN_TAHUN',$tahun)
										->where('URUSAN_KODE',$kdurusan);
									})
									->where('PROGRAM_TAHUN',$tahun)
									->where('PROGRAM_KODE',$kdprogram);
								})->value('KEGIATAN_ID');
			if($p->Kd_Sub < 10) $kodesub = '0'.$p->Kd_Sub;
			else $kodesub = $p->Kd_Sub;
			$skpd 	= $p->Kd_Urusan;
			if($p->Kd_Bidang < 10) $skpd = $skpd.'.0'.$p->Kd_Bidang;
			else $skpd = $skpd.'.'.$p->Kd_Bidang;
			if($p->Kd_Unit < 10) $skpd = $skpd.'.0'.$p->Kd_Unit;
			else $skpd = $skpd.'.'.$p->Kd_Unit;
			$subunit 	= Subunit::where('SUB_KODE',$kodesub)
								->whereHas('skpd',function($s) use($tahun,$skpd){
									$s->where('SKPD_TAHUN',$tahun)->where('SKPD_KODE',$skpd);
								})->value('SUB_ID');
			$rekening 		= $p->Kd_Rek_1.'.'.$p->Kd_Rek_2.'.'.$p->Kd_Rek_3;
			if($p->Kd_Rek_4 < 10) $rekening = $rekening.'.0'.$p->Kd_Rek_4;
			else $rekening = $rekening.'.'.$p->Kd_Rek_4;
			if($p->Kd_Rek_5 < 10) $rekening = $rekening.'.0'.$p->Kd_Rek_5;
			else $rekening = $rekening.'.'.$p->Kd_Rek_5;
			$idrek 	= Rekening::where('REKENING_TAHUN',$tahun)->where('REKENING_KODE',$rekening)->value('REKENING_ID');
			$bl 	= BL::where('BL_TAHUN',$tahun)
						->where('KEGIATAN_ID',$kegiatan)
						->where('SUB_ID',$subunit)
						->value('BL_ID');
			$subrincian 	= Subrincian::where('BL_ID',$bl)->where('SUBRINCIAN_NAMA',$p->Keterangan_Rinc)->value('SUBRINCIAN_ID');
			$nama 			= $this->utf8ize($p->Keterangan);
			$rincian 		= new Rincian;
			$rincian->SUBRINCIAN_ID 		= $subrincian;
			$rincian->REKENING_ID 			= $idrek;
			$rincian->KOMPONEN_ID 			= 0;
			$rincian->RINCIAN_PAJAK 		= 0;
			$rincian->RINCIAN_VOLUME		= $p->Jml_Satuan;
			$rincian->RINCIAN_KOEFISIEN 	= $p->Jml_Satuan.' '.$p->Satuan123;
			$rincian->RINCIAN_TOTAL 		= $p->Total;
			$rincian->PEKERJAAN_ID 			= 0;
			$rincian->BL_ID 				= $bl;
			$rincian->RINCIAN_KOMPONEN		= $nama;
			$rincian->RINCIAN_HARGA			= $p->Nilai_Rp;
			$rincian->save();			
		}
		$count_lama = DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Kd_Perubahan',$kodeperubahan)						
							->where('Kd_Prog','!=',0)
							->where('Kd_Rek_1',5)
							->where('Kd_Rek_2',2)
							->where('Tahun',$tahun)
							->sum('Total');
		$count_baru = Rincian::whereHas('bl',function($bl) use($tahun){
								$bl->where('BL_TAHUN',$tahun);
							})->sum('RINCIAN_TOTAL');
		return 'lama = '.$count_lama.'<br>baru = '.$count_baru;		
	}

	public function transferBTLFromSimda($tahun,$kodeperubahan,$skpd){
		$Kd_Urusan  = substr($skpd, 0,1);
		$Kd_Bidang  = substr($skpd, 2,2);
		$Kd_Unit 	= substr($skpd, 5,2);		
		$belanja 	= DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Kd_Rek_1',5)
							->where('Kd_Rek_2',1)
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Tahun',$tahun);
		if($skpd == 0) $belanja = $belanja->get();
		else $belanja = $belanja->where('Kd_Urusan',$Kd_Urusan)->where('Kd_Bidang',$Kd_Bidang)->where('Kd_Unit',$Kd_Unit)->get();							
		foreach($belanja as $p){
			if($p->Kd_Sub < 10) $kodesub = '0'.$p->Kd_Sub;
			else $kodesub = $p->Kd_Sub;
			$skpd 	= $p->Kd_Urusan;
			if($p->Kd_Bidang < 10) $skpd = $skpd.'.0'.$p->Kd_Bidang;
			else $skpd = $skpd.'.'.$p->Kd_Bidang;
			if($p->Kd_Unit < 10) $skpd = $skpd.'.0'.$p->Kd_Unit;
			else $skpd = $skpd.'.'.$p->Kd_Unit;
			$subunit 	= Subunit::where('SUB_KODE',$kodesub)
								->whereHas('skpd',function($s) use($tahun,$skpd){
									$s->where('SKPD_TAHUN',$tahun)->where('SKPD_KODE',$skpd);
								})->value('SUB_ID');
			$rekening 		= $p->Kd_Rek_1.'.'.$p->Kd_Rek_2.'.'.$p->Kd_Rek_3;
			if($p->Kd_Rek_4 < 10) $rekening = $rekening.'.0'.$p->Kd_Rek_4;
			else $rekening = $rekening.'.'.$p->Kd_Rek_4;
			if($p->Kd_Rek_5 < 10) $rekening = $rekening.'.0'.$p->Kd_Rek_5;
			else $rekening = $rekening.'.'.$p->Kd_Rek_5;
			$idrek 	= Rekening::where('REKENING_TAHUN',$tahun)->where('REKENING_KODE',$rekening)->value('REKENING_ID');
			$rincian 		= new BTL;
			$rincian->BTL_TAHUN 			= $tahun;
			$rincian->SUB_ID 				= $subunit;
			$rincian->REKENING_ID 			= $idrek;
			$rincian->BTL_NAMA 				= $p->Keterangan;
			$rincian->BTL_KETERANGAN		= $p->Keterangan;
			$rincian->BTL_TOTAL				= $p->Total;
			$rincian->BTL_VOLUME			= 1;
			$rincian->BTL_KOEFISIEN			= '1 Tahun';
			$rincian->BTL_PAJAK				= 0;
			$rincian->save();
		}
		$count_lama = DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Kd_Rek_1',5)
							->where('Kd_Rek_2',1)
							->where('Tahun',$tahun)
							->sum('Total');
		$count_baru = BTL::where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
		return number_format($count_baru/$count_lama*100,2,'.',',');				
	}

	public function transferPendapatanFromSimda($tahun,$kodeperubahan,$skpd){
		$Kd_Urusan  = substr($skpd, 0,1);
		$Kd_Bidang  = substr($skpd, 2,2);
		$Kd_Unit 	= substr($skpd, 5,2);		
		$belanja 	= DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Kd_Rek_1',4)
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Tahun',$tahun);
		if($skpd == 0) $belanja = $belanja->get();
		else $belanja = $belanja->where('Kd_Urusan',$Kd_Urusan)->where('Kd_Bidang',$Kd_Bidang)->where('Kd_Unit',$Kd_Unit)->get();							
		foreach($belanja as $p){
			if($p->Kd_Sub < 10) $kodesub = '0'.$p->Kd_Sub;
			else $kodesub = $p->Kd_Sub;
			$skpd 	= $p->Kd_Urusan;
			if($p->Kd_Bidang < 10) $skpd = $skpd.'.0'.$p->Kd_Bidang;
			else $skpd = $skpd.'.'.$p->Kd_Bidang;
			if($p->Kd_Unit < 10) $skpd = $skpd.'.0'.$p->Kd_Unit;
			else $skpd = $skpd.'.'.$p->Kd_Unit;
			$subunit 	= Subunit::where('SUB_KODE',$kodesub)
								->whereHas('skpd',function($s) use($tahun,$skpd){
									$s->where('SKPD_TAHUN',$tahun)->where('SKPD_KODE',$skpd);
								})->value('SUB_ID');
			$rekening 		= $p->Kd_Rek_1.'.'.$p->Kd_Rek_2.'.'.$p->Kd_Rek_3;
			if($p->Kd_Rek_4 < 10) $rekening = $rekening.'.0'.$p->Kd_Rek_4;
			else $rekening = $rekening.'.'.$p->Kd_Rek_4;
			if($p->Kd_Rek_5 < 10) $rekening = $rekening.'.0'.$p->Kd_Rek_5;
			else $rekening = $rekening.'.'.$p->Kd_Rek_5;
			$idrek 	= Rekening::where('REKENING_TAHUN',$tahun)->where('REKENING_KODE',$rekening)->value('REKENING_ID');
			$rincian 		= new Pendapatan;
			$rincian->PENDAPATAN_TAHUN 			= $tahun;
			$rincian->SUB_ID 				= $subunit;
			$rincian->REKENING_ID 			= $idrek;
			$rincian->PENDAPATAN_NAMA 				= $p->Keterangan;
			$rincian->PENDAPATAN_KETERANGAN		= $p->Keterangan;
			$rincian->PENDAPATAN_TOTAL				= $p->Total;
			$rincian->save();
		}
		$count_lama = DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Kd_Rek_1',4)
							->where('Tahun',$tahun)
							->sum('Total');
		$count_baru = Pendapatan::where('PENDAPATAN_TAHUN',$tahun)->sum('PENDAPATAN_TOTAL');
		return number_format($count_baru/$count_lama*100,2,'.',',');				
	}

	public function transferPembiayaanFromSimda($tahun,$kodeperubahan,$skpd){
		$Kd_Urusan  = substr($skpd, 0,1);
		$Kd_Bidang  = substr($skpd, 2,2);
		$Kd_Unit 	= substr($skpd, 5,2);		
		$belanja 	= DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Kd_Rek_1',6)
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Tahun',$tahun);
		if($skpd == 0) $belanja = $belanja->get();
		else $belanja = $belanja->where('Kd_Urusan',$Kd_Urusan)->where('Kd_Bidang',$Kd_Bidang)->where('Kd_Unit',$Kd_Unit)->get();							
		foreach($belanja as $p){
			if($p->Kd_Sub < 10) $kodesub = '0'.$p->Kd_Sub;
			else $kodesub = $p->Kd_Sub;
			$skpd 	= $p->Kd_Urusan;
			if($p->Kd_Bidang < 10) $skpd = $skpd.'.0'.$p->Kd_Bidang;
			else $skpd = $skpd.'.'.$p->Kd_Bidang;
			if($p->Kd_Unit < 10) $skpd = $skpd.'.0'.$p->Kd_Unit;
			else $skpd = $skpd.'.'.$p->Kd_Unit;
			$subunit 	= Subunit::where('SUB_KODE',$kodesub)
								->whereHas('skpd',function($s) use($tahun,$skpd){
									$s->where('SKPD_TAHUN',$tahun)->where('SKPD_KODE',$skpd);
								})->value('SUB_ID');
			$rekening 		= $p->Kd_Rek_1.'.'.$p->Kd_Rek_2.'.'.$p->Kd_Rek_3;
			if($p->Kd_Rek_4 < 10) $rekening = $rekening.'.0'.$p->Kd_Rek_4;
			else $rekening = $rekening.'.'.$p->Kd_Rek_4;
			if($p->Kd_Rek_5 < 10) $rekening = $rekening.'.0'.$p->Kd_Rek_5;
			else $rekening = $rekening.'.'.$p->Kd_Rek_5;
			$idrek 	= Rekening::where('REKENING_TAHUN',$tahun)->where('REKENING_KODE',$rekening)->value('REKENING_ID');
			$rincian 		= new Pembiayaan;
			$rincian->PEMBIAYAAN_TAHUN 			= $tahun;
			$rincian->SUB_ID 					= $subunit;
			$rincian->REKENING_ID 				= $idrek;
			$rincian->PEMBIAYAAN_NAMA 			= $p->Keterangan;
			$rincian->PEMBIAYAAN_KETERANGAN		= $p->Keterangan;
			$rincian->PEMBIAYAAN_TOTAL			= $p->Total;
			$rincian->save();
		}
		$count_lama = DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Kd_Rek_1',6)
							->where('Tahun',$tahun)
							->sum('Total');
		$count_baru = Pembiayaan::where('PEMBIAYAAN_TAHUN',$tahun)->sum('PEMBIAYAAN_TOTAL');
		return number_format($count_baru/$count_lama*100,2,'.',',');				
	}

	public function progress($tahun,$param,$kodeperubahan){
		$count_baru = 0;
		$count_lama = 0;
		if($param == 'getbtl'){
				$count_lama = DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Kd_Perubahan',$kodeperubahan)
							->where('Kd_Rek_1',5)
							->where('Kd_Rek_2',1)
							->where('Tahun',$tahun)
							->sum('Total');
				$count_baru = BTL::where('BTL_TAHUN',$tahun)->sum('BTL_TOTAL');
		}elseif($param == 'getrincian'){
				$count_lama = DB::connection('sqlsrv')
							->table('dbo.Ta_RASK_Arsip')
							->where('Kd_Perubahan',$kodeperubahan)						
							->where('Kd_Prog','!=',0)
							->where('Kd_Rek_1',5)
							->where('Kd_Rek_2',2)
							->where('Tahun',$tahun)
							->sum('Total');
				$count_baru = Rincian::whereHas('bl',function($bl) use($tahun){
								$bl->where('BL_TAHUN',$tahun);
							})->sum('RINCIAN_TOTAL');
		}

		return number_format($count_baru/$count_lama*100,2,'.',',');
	}

	public function transferuser($tahunawal,$tahunakhir){
		$data 	= UserBudget::where('TAHUN',$tahunawal)->get();
		foreach($data as $data){
			$skpdlama 	= SKPD::where('SKPD_ID',$data->SKPD_ID)->value('SKPD_KODE');
			$skpdbaru 	= SKPD::where('SKPD_TAHUN',$tahunakhir)->where('SKPD_KODE',$skpdlama)->value('SKPD_ID');
			$userbudget 	= new UserBudget;
			$userbudget->USER_ID 		= $data->USER_ID;
			$userbudget->SKPD_ID 		= $skpdbaru;
			$userbudget->TAHUN 			= $tahunakhir;
			$userbudget->save();
		}
		$lama 	= UserBudget::where('TAHUN',$tahunawal)->count();
		$baru 	= UserBudget::where('TAHUN',$tahunakhir)->count();
		return 'lama = '.$lama.'<br>baru = '.$baru;
	}

	public function getRealisasi($tahun){
		$data 	= DB::connection('sqlsrv')
    						->table('dbo.Ta_SPM_Rinc as spm')
                            ->join('dbo.Ta_SP2D as sp2d',function($join){
                                    $join->on("spm.No_SPM","=",'sp2d.No_SPM');
                            })->where('spm.Tahun',$tahun)
                            ->where('Kd_Rek_1',5)                                                  
                            ->where('Kd_Rek_2',2)
                            ->where('Kd_Prog','!=',0)  
		    				->groupBy('spm.Tahun','spm.Kd_Urusan','spm.Kd_Bidang','spm.Kd_Unit','spm.Kd_Sub','spm.Kd_Prog','spm.ID_Prog','spm.Kd_Keg','Kd_Rek_1','Kd_Rek_2','Kd_Rek_3','Kd_Rek_4','Kd_Rek_5')
		    				->select('spm.Tahun','spm.Kd_Urusan','spm.Kd_Bidang','spm.Kd_Unit','spm.Kd_Sub','spm.Kd_Prog','spm.ID_Prog','spm.Kd_Keg','Kd_Rek_1','Kd_Rek_2','Kd_Rek_3','Kd_Rek_4','Kd_Rek_5')
                            ->selectRaw("SUM(spm.Nilai) AS TOTAL")
                            ->get();
        foreach($data as $p){
			$urusan 	= substr($p->ID_Prog, 0,1).'.'.substr($p->ID_Prog,1,2);
			$urusan 	= Urusan::where('URUSAN_KODE',$urusan)->where('URUSAN_TAHUN',$tahun)->value('URUSAN_ID');

			$skpd 		= $p->Kd_Urusan;
			if($p->Kd_Bidang < 10) $skpd 		= $skpd.'.0'.$p->Kd_Bidang;
			else $skpd 							= $skpd.'.'.$p->Kd_Bidang;
			if($p->Kd_Unit < 10) $skpd 			= $skpd.'.0'.$p->Kd_Unit;
			else $skpd 							= $skpd.'.'.$p->Kd_Unit;
			$skpd 		= SKPD::where('SKPD_TAHUN',$tahun)->where('SKPD_KODE',$skpd)->value('SKPD_ID');

			if($p->Kd_Sub < 10) $sub 			= '0'.$p->Kd_Sub;
			else $sub 							= $p->Kd_Sub;
			$sub 		= Subunit::where('SKPD_ID',$skpd)->where('SUB_KODE',$sub)->value('SUB_ID');

			if($p->Kd_Prog < 10) $prog 			= '0'.$p->Kd_Prog;
			else $prog 							= $p->Kd_Prog;
			$prog 		= Program::where('URUSAN_ID',$urusan)->where('PROGRAM_KODE',$prog)->where('PROGRAM_TAHUN',$tahun)->value('PROGRAM_ID');

			if($p->Kd_Keg < 10) $keg 			= '00'.$p->Kd_Keg;
			elseif($p->Kd_Keg < 100) $keg 		= '0'.$p->Kd_Keg;
			else $keg 							= $p->Kd_Keg;
			$keg 		= Kegiatan::where('PROGRAM_ID',$prog)->where('KEGIATAN_TAHUN',$tahun)->where('KEGIATAN_KODE',$keg)->value('KEGIATAN_ID');


			$bl 		= BLPerubahan::where('KEGIATAN_ID',$keg)->where('SUB_ID',$sub)->value('BL_ID');
			$penyesuaiand		= DB::connection('sqlsrv')
										->table('dbo.Ta_Penyesuaian_Rinc')
										->where('Tahun',$p->Tahun)
										->where('Kd_Urusan',$p->Kd_Urusan)
										->where('Kd_Bidang',$p->Kd_Bidang)
										->where('Kd_Unit',$p->Kd_Unit)
										->where('Kd_Prog',$p->Kd_Prog)
										->where('ID_Prog',$p->ID_Prog)
										->where('Kd_Keg',$p->Kd_Keg)
										->where('D_K','D')
										->sum('Nilai');
			$penyesuaiand 		= 0;
			$penyesuaiank 		= DB::connection('sqlsrv')
										->table('dbo.Ta_Penyesuaian_Rinc')
										->where('Tahun',$p->Tahun)
										->where('Kd_Urusan',$p->Kd_Urusan)
										->where('Kd_Bidang',$p->Kd_Bidang)
										->where('Kd_Unit',$p->Kd_Unit)
										->where('Kd_Prog',$p->Kd_Prog)
										->where('ID_Prog',$p->ID_Prog)
										->where('Kd_Keg',$p->Kd_Keg)
										->where('D_K','K')
										->sum('Nilai');
			$penyesuaiank 		= 0;
			$realisasi 			= $p->TOTAL+$penyesuaiank-$penyesuaiand;
			
			$rekening 			= '5.2.'.$p->Kd_Rek_3;
			if($p->Kd_Rek_4 < 10) $rekening 		= $rekening.'.0'.$p->Kd_Rek_4;
			else $rekening 							= $rekening.'.'.$p->Kd_Rek_4; 
			if($p->Kd_Rek_5 < 10) $rekening 		= $rekening.'.0'.$p->Kd_Rek_5;
			else $rekening 							= $rekening.'.'.$p->Kd_Rek_5;
			$rekening 		= Rekening::where('REKENING_TAHUN',$tahun)->where('REKENING_KODE',$rekening)->value('REKENING_ID');
			$cek  			= Realisasi::where('BL_ID',$bl)->where('REKENING_ID',$rekening)->value('REALISASI_ID');
			if($cek){
				Realisasi::where('REALISASI_ID',$cek)->update(['REALISASI_TOTAL'=>$realisasi]);
			}else{
				$real 		= new Realisasi;
				$real->BL_ID 			= $bl;
				$real->REKENING_ID 		= $rekening;
				$real->REALISASI_TOTAL 	= $realisasi;	
				$real->save();			
			}
        }
        return 'OK';
	}

	public function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = $this->utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
    }
}
