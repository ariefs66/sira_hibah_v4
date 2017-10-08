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
class simdaController extends Controller{

	public function index($tahun){
		$data  	= array('tahun'=>$tahun);
		return view('tosimda',$data);
	}

	public function trfSubUnit($tahun){
		$data 	= Subunit::whereHas('skpd',function($skpd) use ($tahun){
					$skpd->where('SKPD_TAHUN',$tahun);
				})->get();
		foreach($data as $sub){
			$Kd_Urusan 	= substr($sub->SKPD->SKPD_KODE, 0,1);
			$Kd_Bidang 	= substr($sub->SKPD->SKPD_KODE, 2,2)*1;
			$Kd_Unit 	= substr($sub->SKPD->SKPD_KODE, 5,2)*1;
			$cek 		= DB::connection('sqlsrv')->table('dbo.Ta_Sub_Unit')
									->where('Tahun',$tahun)
									->where('Kd_Urusan',$Kd_Urusan)
									->where('Kd_Bidang',$Kd_Bidang)
									->where('Kd_Unit',$Kd_Unit)
									->where('Kd_Sub',$sub->SUB_KODE *1)
									->count();
			if($cek == 0){
				$value 		= array('Tahun' 		=> $tahun,
									'Kd_Urusan' 	=> $Kd_Urusan,
									'Kd_Bidang' 	=> $Kd_Bidang,
									'Kd_Unit'		=> $Kd_Unit,
									'Kd_Sub' 		=> $sub->SUB_KODE *1);
				DB::connection('sqlsrv')->table('dbo.Ta_Sub_Unit')
							->insert($value);
			}
		}
		$count_sira 		= count($data);
		$count_simda 		= DB::connection('sqlsrv')->table('dbo.Ta_Sub_Unit')
									->where('Tahun',$tahun)
									->count();
		return 'SIRA : '.$count_sira.'<br>SIMDA : '.$count_simda;
	}

	public function trfProgram($tahun){
		$data 	= BL::where('BL_TAHUN',$tahun)
					->where('BL_DELETED',0)
					->where('BL_VALIDASI',1)
					->get();
		foreach($data as $bl){
			$skpd 		= $bl->subunit;
			$program 	= $bl->kegiatan->program;
			$Kd_Prog 	= $program->PROGRAM_KODE * 1;
			$ID_Prog 	= str_replace('.','',$program->urusan->URUSAN_KODE);
			$Kd_Urusan 	= substr($skpd->SKPD->SKPD_KODE, 0,1);
			$Kd_Urusan1 = substr($program->urusan->URUSAN_KODE, 0,1);
			$Kd_Bidang 	= substr($skpd->SKPD->SKPD_KODE, 2,2)*1;
			$Kd_Bidang1 = substr($program->urusan->URUSAN_KODE, 2,2)*1;
			$Kd_Unit 	= substr($skpd->SKPD->SKPD_KODE, 5,2)*1;
			$Kd_Sub 	= $skpd->SUB_KODE *1;
			$Ket_Prog 	= $program->PROGRAM_NAMA;
			$cek 		= DB::connection('sqlsrv')->table('dbo.Ta_Program')
									->where('Tahun',$tahun)
									->where('Kd_Urusan',$Kd_Urusan)
									->where('Kd_Bidang',$Kd_Bidang)
									->where('Kd_Unit',$Kd_Unit)
									->where('Kd_Sub',$Kd_Sub)
									->where('Kd_Prog',$Kd_Prog)
									->where('ID_Prog',$ID_Prog)
									->count();
			if($cek == 0){
				$value 	= array('Tahun' 		=> $tahun,
								'Kd_Urusan' 	=> $Kd_Urusan,
								'Kd_Urusan1' 	=> $Kd_Urusan1,
								'Kd_Bidang' 	=> $Kd_Bidang,
								'Kd_Bidang1' 	=> $Kd_Bidang1,
								'Kd_Unit'		=> $Kd_Unit,
								'Kd_Sub'		=> $Kd_Sub,
								'Kd_Prog'		=> $Kd_Prog,
								'ID_Prog'		=> $ID_Prog,
								'Ket_Program'	=> "'".$Ket_Prog."'");
				DB::connection('sqlsrv')->table('dbo.Ta_Program')
							->insert($value);
			}
		}
	}

	public function trfKegiatan($tahun){
		$data 	= BL::where('BL_TAHUN',$tahun)
					->where('BL_DELETED',0)
					->where('BL_VALIDASI',1)
					->get();
		foreach($data as $bl){
			$skpd 		= $bl->subunit;
			$program 	= $bl->kegiatan->program;
			$kegiatan 	= $bl->kegiatan;
			$Kd_Prog 	= $program->PROGRAM_KODE * 1;
			$ID_Prog 	= str_replace('.','',$program->urusan->URUSAN_KODE);
			$Kd_Urusan 	= substr($skpd->SKPD->SKPD_KODE, 0,1);
			$Kd_Bidang 	= substr($skpd->SKPD->SKPD_KODE, 2,2)*1;
			$Kd_Unit 	= substr($skpd->SKPD->SKPD_KODE, 5,2)*1;
			$Kd_Sub 	= $skpd->SUB_KODE *1;
			$Kd_Keg 	= $kegiatan->KEGIATAN_KODE * 1;
			$Ket_Kegiatan 	= $kegiatan->KEGIATAN_NAMA;
			$cek 		= DB::connection('sqlsrv')->table('dbo.Ta_Kegiatan')
									->where('Tahun',$tahun)
									->where('Kd_Urusan',$Kd_Urusan)
									->where('Kd_Bidang',$Kd_Bidang)
									->where('Kd_Unit',$Kd_Unit)
									->where('Kd_Sub',$Kd_Sub)
									->where('Kd_Prog',$Kd_Prog)
									->where('ID_Prog',$ID_Prog)
									->where('Kd_Keg',$Kd_Keg)
									->count();
			if($cek == 0){
				$value 	= array('Tahun' 			=> $tahun,
								'Kd_Urusan' 		=> $Kd_Urusan,
								'Kd_Bidang' 		=> $Kd_Bidang,
								'Kd_Unit'			=> $Kd_Unit,
								'Kd_Sub'			=> $Kd_Sub,
								'Kd_Prog'			=> $Kd_Prog,
								'ID_Prog'			=> $ID_Prog,
								'Kd_Keg'			=> $Kd_Keg,
								'Ket_Kegiatan'		=> "'".$Ket_Kegiatan."'",
								'Status_Kegiatan'	=> 1);
				DB::connection('sqlsrv')->table('dbo.Ta_Kegiatan')
							->insert($value);
			}
		}
	}

	public function trfBelanja($tahun){
		$data 	= Rincian::whereHas('bl',function($bl)use($tahun){
			$bl->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->where('BL_VALIDASI',1);
		})->where('REKENING_ID','!=',0)->groupBy('REKENING_ID','BL_ID')->select('REKENING_ID','BL_ID')->get();
		foreach($data as $rincian){
			$skpd 		= $rincian->bl->subunit;
			$program 	= $rincian->bl->kegiatan->program;
			$kegiatan 	= $rincian->bl->kegiatan;
			$Kd_Prog 	= $program->PROGRAM_KODE * 1;
			$ID_Prog 	= str_replace('.','',$program->urusan->URUSAN_KODE);
			$Kd_Urusan 	= substr($skpd->SKPD->SKPD_KODE, 0,1);
			$Kd_Bidang 	= substr($skpd->SKPD->SKPD_KODE, 2,2)*1;
			$Kd_Unit 	= substr($skpd->SKPD->SKPD_KODE, 5,2)*1;
			$Kd_Sub 	= $skpd->SUB_KODE *1;
			$Kd_Keg 	= $kegiatan->KEGIATAN_KODE * 1;
			$rekening 	= $rincian->rekening;
			$Kd_Rek_1	= substr($rekening->REKENING_KODE,0,1);
			$Kd_Rek_2	= substr($rekening->REKENING_KODE,2,1);
			$Kd_Rek_3	= substr($rekening->REKENING_KODE,4,1);
			$Kd_Rek_4	= substr($rekening->REKENING_KODE,6,2)*1;
			$Kd_Rek_5	= substr($rekening->REKENING_KODE,9,2)*1;
			$cek 		= DB::connection('sqlsrv')->table('dbo.Ta_Belanja')
									->where('Tahun',$tahun)
									->where('Kd_Urusan',$Kd_Urusan)
									->where('Kd_Bidang',$Kd_Bidang)
									->where('Kd_Unit',$Kd_Unit)
									->where('Kd_Sub',$Kd_Sub)
									->where('Kd_Prog',$Kd_Prog)
									->where('ID_Prog',$ID_Prog)
									->where('Kd_Keg',$Kd_Keg)
									->where('Kd_Rek_1',$Kd_Rek_1)
									->where('Kd_Rek_2',$Kd_Rek_2)
									->where('Kd_Rek_3',$Kd_Rek_3)
									->where('Kd_Rek_4',$Kd_Rek_4)
									->where('Kd_Rek_5',$Kd_Rek_5)
									->count();
			if($cek == 0){
				$value 	= array('Tahun' 			=> $tahun,
								'Kd_Urusan' 		=> $Kd_Urusan,
								'Kd_Bidang' 		=> $Kd_Bidang,
								'Kd_Unit'			=> $Kd_Unit,
								'Kd_Sub'			=> $Kd_Sub,
								'Kd_Prog'			=> $Kd_Prog,
								'ID_Prog'			=> $ID_Prog,
								'Kd_Keg'			=> $Kd_Keg,
								'Kd_Rek_1'			=> $Kd_Rek_1,
								'Kd_Rek_2'			=> $Kd_Rek_2,
								'Kd_Rek_3'			=> $Kd_Rek_3,
								'Kd_Rek_4'			=> $Kd_Rek_4,
								'Kd_Rek_5'			=> $Kd_Rek_5,);
				DB::connection('sqlsrv')->table('dbo.Ta_Belanja')
							->insert($value);
			}
		}
	}

	public function trfBelanjaSub($tahun){
		$data 	= Rincian::whereHas('bl',function($bl)use($tahun){
			$bl->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->where('BL_VALIDASI',1);
		})->where('REKENING_ID','!=',0)->orderBy('BL_ID','REKENING_ID')->groupBy('REKENING_ID','BL_ID')->select('REKENING_ID','BL_ID')->get();
		$i 			= 1;		
		foreach($data as $rincian){
			if($i == 32000) $i = 1;
			$subrincian = Rincian::whereHas('bl',function($bl)use($tahun){
							$bl->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->where('BL_VALIDASI',1);
						})->where('REKENING_ID',$rincian->REKENING_ID)
						  ->where('BL_ID',$rincian->BL_ID)
						  ->orderBy('BL_ID','REKENING_ID')
						  ->groupBy('SUBRINCIAN_ID','BL_ID','REKENING_ID')->select('SUBRINCIAN_ID','BL_ID','REKENING_ID')->get();
			$skpd 		= $rincian->bl->subunit;
			$program 	= $rincian->bl->kegiatan->program;
			$kegiatan 	= $rincian->bl->kegiatan;
			$Kd_Prog 	= $program->PROGRAM_KODE * 1;
			$ID_Prog 	= str_replace('.','',$program->urusan->URUSAN_KODE);
			$Kd_Urusan 	= substr($skpd->SKPD->SKPD_KODE, 0,1);
			$Kd_Bidang 	= substr($skpd->SKPD->SKPD_KODE, 2,2)*1;
			$Kd_Unit 	= substr($skpd->SKPD->SKPD_KODE, 5,2)*1;
			$Kd_Sub 	= $skpd->SUB_KODE *1;
			$Kd_Keg 	= $kegiatan->KEGIATAN_KODE * 1;
			$rekening 	= $rincian->rekening;
			$Kd_Rek_1	= substr($rekening->REKENING_KODE,0,1);
			$Kd_Rek_2	= substr($rekening->REKENING_KODE,2,1);
			$Kd_Rek_3	= substr($rekening->REKENING_KODE,4,1);
			$Kd_Rek_4	= substr($rekening->REKENING_KODE,6,2)*1;
			$Kd_Rek_5	= substr($rekening->REKENING_KODE,9,2)*1;
			$j 			= 1;
			if($j == 32000) $j = 1;
			foreach($subrincian as $sr){
				$value 	= array('Tahun' 			=> $tahun,
								'Kd_Urusan' 		=> $Kd_Urusan,
								'Kd_Bidang' 		=> $Kd_Bidang,
								'Kd_Unit'			=> $Kd_Unit,
								'Kd_Sub'			=> $Kd_Sub,
								'Kd_Prog'			=> $Kd_Prog,
								'ID_Prog'			=> $ID_Prog,
								'Kd_Keg'			=> $Kd_Keg,
								'Kd_Rek_1'			=> $Kd_Rek_1,
								'Kd_Rek_2'			=> $Kd_Rek_2,
								'Kd_Rek_3'			=> $Kd_Rek_3,
								'Kd_Rek_4'			=> $Kd_Rek_4,
								'Kd_Rek_5'			=> $Kd_Rek_5,
								'No_Rinc' 			=> $i,
								'Keterangan' 		=> "'".str_replace(';',',',$sr->subrincian->SUBRINCIAN_NAMA)."'");
				DB::connection('sqlsrv')->table('dbo.Ta_Belanja_Rinc')->insert($value);
				$komponen 	= Rincian::whereHas('bl',function($bl)use($tahun){
							$bl->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->where('BL_VALIDASI',1);
						})->where('REKENING_ID',$rincian->REKENING_ID)
						  ->where('BL_ID',$rincian->BL_ID)
						  ->where('SUBRINCIAN_ID',$sr->SUBRINCIAN_ID)
						  ->get();
				foreach($komponen as $k){
					$insert 	= "INSERT INTO [dbo].[Ta_Belanja_Rinc_Sub] VALUES 
									('".$tahun."', 
									 '".$Kd_Urusan."', 
									 '".$Kd_Bidang."', 
									 '".$Kd_Unit."', 
									 '".$Kd_Sub."', 
									 '".$Kd_Prog."', 
									 '".$ID_Prog."', 
									 '".$Kd_Keg."', 
									 '".$Kd_Rek_1."', 
									 '".$Kd_Rek_2."', 
									 '".$Kd_Rek_3."', 
									 '".$Kd_Rek_4."', 
									 '".$Kd_Rek_5."', 
									 '".$i."', 
									 '".$j."', 
									 '".str_replace("'", '', $k->komponen->KOMPONEN_SATUAN)."', 
									 CAST('".$k->RINCIAN_VOLUME."' AS MONEY), 
									 '', CAST('0' AS MONEY), 
									 '', CAST('0' AS MONEY), 
									 '".str_replace("'", '', $k->komponen->KOMPONEN_SATUAN)."', 
									 CAST('".$k->RINCIAN_VOLUME."' AS MONEY), 
									 CAST('".$k->RINCIAN_HARGA."' AS MONEY), 
									 CAST('".$k->RINCIAN_VOLUME*$k->RINCIAN_HARGA."' AS MONEY), 
									 '".str_replace("'", '', str_replace(';',',',$k->komponen->KOMPONEN_NAMA))."');";
					DB::connection('sqlsrv')->insert(DB::raw($insert));
					$j++;								
					if($k->RINCIAN_PAJAK == 10){
						$vol 	= $k->RINCIAN_VOLUME/10;
						$total 	= $vol*$k->RINCIAN_HARGA;
						$insert 	= "INSERT INTO [dbo].[Ta_Belanja_Rinc_Sub] VALUES 
									('".$tahun."', 
									 '".$Kd_Urusan."', 
									 '".$Kd_Bidang."', 
									 '".$Kd_Unit."', 
									 '".$Kd_Sub."', 
									 '".$Kd_Prog."', 
									 '".$ID_Prog."', 
									 '".$Kd_Keg."', 
									 '".$Kd_Rek_1."', 
									 '".$Kd_Rek_2."', 
									 '".$Kd_Rek_3."', 
									 '".$Kd_Rek_4."', 
									 '".$Kd_Rek_5."', 
									 '".$i."', 
									 '".$j."', 
									 '".str_replace("'", '', $k->komponen->KOMPONEN_SATUAN)."', 
									 CAST('".$vol."' AS MONEY), 
									 '', CAST('0' AS MONEY), 
									 '', CAST('0' AS MONEY), 
									 '".str_replace("'", '', $k->komponen->KOMPONEN_SATUAN)."', 
									 CAST('".$vol."' AS MONEY), 
									 CAST('".$k->RINCIAN_HARGA."' AS MONEY), 
									 CAST('".$total."' AS MONEY), 
									 'PPN ".str_replace("'", '', str_replace(';',',',$k->komponen->KOMPONEN_NAMA))."');";
						DB::connection('sqlsrv')->insert(DB::raw($insert));
						$j++;
					}
				}
				$i++;
			}
		}
	}

	// FAILED INSERT
	// $vals 	= array('Tahun' 			=> $tahun,
					// 				'Kd_Urusan' 		=> $Kd_Urusan,
					// 				'Kd_Bidang' 		=> $Kd_Bidang,
					// 				'Kd_Unit'			=> $Kd_Unit,
					// 				'Kd_Sub'			=> $Kd_Sub,
					// 				'Kd_Prog'			=> $Kd_Prog,
					// 				'ID_Prog'			=> $ID_Prog,
					// 				'Kd_Keg'			=> $Kd_Keg,
					// 				'Kd_Rek_1'			=> $Kd_Rek_1,
					// 				'Kd_Rek_2'			=> $Kd_Rek_2,
					// 				'Kd_Rek_3'			=> $Kd_Rek_3,
					// 				'Kd_Rek_4'			=> $Kd_Rek_4,
					// 				'Kd_Rek_5'			=> $Kd_Rek_5,
					// 				'No_Rinc' 			=> $i,
					// 				'No_ID' 			=> $j,
					// 				'Nilai_1'			=> $k->RINCIAN_VOLUME*1,
					// 				'Sat_1'				=> "'".$k->komponen->KOMPONEN_SATUAN."'",
					// 				'Satuan123' 		=> "'".$k->komponen->KOMPONEN_SATUAN."'",
					// 				'Jml_Satuan'		=> $k->RINCIAN_VOLUME*1,
					// 				'Nilai_Rp' 			=> $k->RINCIAN_HARGA*1,
					// 				'Total' 			=> $k->RINCIAN_VOLUME*$k->RINCIAN_HARGA*1,
					// 				'Keterangan' 		=> "'".str_replace(';',',',$k->komponen->KOMPONEN_NAMA)."'");
					// DB::connection('sqlsrv')->table('dbo.Ta_Belanja_Rinc_Sub')->insert($vals);
}
