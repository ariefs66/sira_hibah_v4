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

	// Untuk Menggunakan API ini, pastikan pada web server anda telah terinstall ekstensi PDO_SQLSERVER
    // Yang dimana tersedia dalam paketan Microsoft Drivers 4.3 for PHP for SQL Server
	// (https://www.microsoft.com/en-us/download/details.aspx?id=55642)
	/* Cara install PHP 7.0 di Ubuntu 16.04
sudo su
apt-get update
apt-get -y install php7.0 mcrypt php7.0-mcrypt php-mbstring php-pear php7.0-dev
php7.0-xml 
	*/
	/* Cara install file yang dibutuhkan ekstensi sql server
sudo su
curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
curl https://packages.microsoft.com/config/ubuntu/16.04/prod.list >
/etc/apt/sources.list.d/mssql-release.list
exit
sudo apt-get update
sudo ACCEPT_EULA=Y apt-get install msodbcsql mssql-tools
sudo apt-get install unixodbc-dev
echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bash_profile
echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc
source ~/.bashrc
	*/
	/* Cara menambahkan konfigurasi ekstensi Sql Server
sudo pear config-set php_ini `php --ini | grep "Loaded Configuration" | sed -e
"s|.*:\s*||"` system
sudo pecl install sqlsrv
sudo pecl install pdo_sqlsrv
	*/
	/* Cara menginstall Apache
sudo su
apt-get install libapache2-mod-php7.0 apache2
a2dismod mpm_event
a2enmod mpm_prefork
a2enmod php7.0
echo "extension=sqlsrv.so" >> /etc/php/7.0/apache2/php.ini
echo "extension=pdo_sqlsrv.so" >> /etc/php/7.0/apache2/php.ini
	*/
	/* Restart Apache
sudo service apache2 restart
	*/
    // Setelah itu, installkan Microsoft® ODBC Driver 13 for SQL Server® - Windows
	// (https://www.microsoft.com/en-us/download/details.aspx?id=50420)
	/* Cara install Microsoft® ODBC Driver 13 for SQL Server® - Linux
sudo su
curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
curl https://packages.microsoft.com/config/ubuntu/16.04/prod.list > /etc/apt/sources.list.d/mssqlrelease.list
exit
sudo apt-get update
sudo ACCEPT_EULA=Y apt-get install msodbcsql=13.0.1.0-1 mssql-tools
sudo apt-get install unixodbc-dev-utf16 #this step is optional but recommended*
	*/

	public function index($tahun){ // cek total belanja simda yang kode tidak sama dgn nol
		$total 	= DB::connection('sqlsrv')->select('SELECT SUM(Total) as Total FROM Ta_Belanja_Rinc_Sub WHERE Kd_Prog != 0');
		//print_r($total);exit();
		$data  	= array('tahun'=>$tahun);
		return view('tosimda',$data);
	}

	public function trfSubUnit($tahun){ // transfer sub unit 
		$data 	= Subunit::whereHas('skpd',function($skpd) use ($tahun){
					$skpd->where('SKPD_TAHUN',$tahun);
				})->get();
		foreach($data as $sub){
			$Kd_Urusan 	= substr($sub->SKPD->SKPD_KODE, 0,1);
			$Kd_Bidang 	= substr($sub->SKPD->SKPD_KODE, 2,2)*1;
			$Kd_Unit 	= substr($sub->SKPD->SKPD_KODE, 5,2)*1;
			$cek 		= DB::connection('sqlsrv')->table('dbo.Ta_Sub_Unit')
									->where('Tahun',2017)
									->where('Kd_Urusan',$Kd_Urusan)
									->where('Kd_Bidang',$Kd_Bidang)
									->where('Kd_Unit',$Kd_Unit)
									->where('Kd_Sub',$sub->SUB_KODE *1)
									->count();
			if($cek == 0){
				$value 		= array('Tahun' 		=> 2017,
									'Kd_Urusan' 	=> $Kd_Urusan,
									'Kd_Bidang' 	=> $Kd_Bidang,
									'Kd_Unit'		=> $Kd_Unit,
									'Kd_Sub' 		=> $sub->SUB_KODE *1);
				DB::connection('sqlsrv')->table('dbo.Ta_Sub_Unit')
							->insert($value); //input kode sub unit 
			}
		}
		$count_sira 		= count($data);
		$count_simda 		= DB::connection('sqlsrv')->table('dbo.Ta_Sub_Unit')
									->where('Tahun',2017)
									->count();
		return 'SIRA : '.$count_sira.'<br>SIMDA : '.$count_simda;
	}

	public function trfProgram($tahun){ /// kirim program 
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
			$Kd_Bidang1 = substr($program->urusan->URUSAN_KODE, 2,2)*1;		//ngecek null
			$Kd_Unit 	= substr($skpd->SKPD->SKPD_KODE, 5,2)*1;
			$Kd_Sub 	= $skpd->SUB_KODE *1;
			$Ket_Prog 	= $program->PROGRAM_NAMA;
			$cek 		= DB::connection('sqlsrv')->table('dbo.Ta_Program')
									->where('Tahun',2017)
									->where('Kd_Urusan',$Kd_Urusan)
									->where('Kd_Bidang',$Kd_Bidang)
									->where('Kd_Unit',$Kd_Unit)
									->where('Kd_Sub',$Kd_Sub)
									->where('Kd_Prog',$Kd_Prog)
									->where('ID_Prog',$ID_Prog)
									->count();
			if($cek == 0){
				$value 	= array('Tahun' 		=> 2017,
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

	public function trfKegiatan($tahun){ // transfer. kegiatan 
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
									->where('Tahun',2017)
									->where('Kd_Urusan',$Kd_Urusan)
									->where('Kd_Bidang',$Kd_Bidang)
									->where('Kd_Unit',$Kd_Unit)
									->where('Kd_Sub',$Kd_Sub)
									->where('Kd_Prog',$Kd_Prog)
									->where('ID_Prog',$ID_Prog)
									->where('Kd_Keg',$Kd_Keg)
									->count();
			if($cek == 0){
				$value 	= array('Tahun' 			=> 2017,
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
									->where('Tahun',2017)
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
				$value 	= array('Tahun' 			=> 2017,
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
		$pd 	= array();
		$ii 	= 1;
		for($ii=61;$ii<=70;$ii++){
			array_push($pd, $ii);
		}
		$data 	= Rincian::whereHas('bl',function($bl)use($tahun,$pd){
			$bl->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->where('BL_VALIDASI',1)->whereHas('subunit',function($sub)use($pd){
				$sub->whereIn('SKPD_ID',$pd);
			});
		})->where('REKENING_ID','!=',0)->orderBy('BL_ID','REKENING_ID')->groupBy('REKENING_ID','BL_ID')->select('REKENING_ID','BL_ID')->get();
		$i 			= 1;		
		foreach($data as $rincian){
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
			foreach($subrincian as $sr){
				if($sr->subrincian){
					$value 	= array('Tahun' 			=> 2017,
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
						if($k->RINCIAN_HARGA == 0) $harga = $k->komponen->KOMPONEN_HARGA;
						else $harga = $k->RINCIAN_HARGA;
						
						if($k->RINCIAN_PAJAK == 10){
							$insert 	= "INSERT INTO [dbo].[Ta_Belanja_Rinc_Sub] VALUES 
										('2017', 
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
										 CAST('".$harga."' AS MONEY), 
										 CAST('".$k->RINCIAN_TOTAL/1.1."' AS MONEY), 
										 '".str_replace("'", '', str_replace(';',',',$k->RINCIAN_KOMPONEN))."');";
							DB::connection('sqlsrv')->insert(DB::raw($insert));
							$j++;			
							if($j == 400) $j = 1;

							$total 		= $k->RINCIAN_TOTAL/1.1/10;
							$insert 	= "INSERT INTO [dbo].[Ta_Belanja_Rinc_Sub] VALUES 
										('2017', 
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
										 'persen', 
										 CAST('0.1' AS MONEY), 
										 '', CAST('0' AS MONEY), 
										 '', CAST('0' AS MONEY), 
										 'persen', 
										 CAST('0.1' AS MONEY), 
										 CAST('".$k->RINCIAN_TOTAL/1.1."' AS MONEY), 
										 CAST('".$total."' AS MONEY), 
										 'PPN ".str_replace("'", '', str_replace(';',',',$k->RINCIAN_KOMPONEN))."');";
							DB::connection('sqlsrv')->insert(DB::raw($insert));
							$j++;
							if($j == 400) $j = 1;							
						}else{
							$insert 	= "INSERT INTO [dbo].[Ta_Belanja_Rinc_Sub] VALUES 
										('2017', 
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
										 CAST('".$k->RINCIAN_TOTAL."' AS MONEY), 
										 '".str_replace("'", '', str_replace(';',',',$k->RINCIAN_KOMPONEN))."');";
							DB::connection('sqlsrv')->insert(DB::raw($insert));
							$j++;			
							if($j == 400) $j = 1;
						}
					}
					$i++;
					if($i == 400) $i = 1;
				}
			}
		}
	}

	public function trfBTL($tahun){
		$data 	 	= BTL::where('BTL_TAHUN',$tahun)
						->groupBy('SUB_ID')
						->groupBy('REKENING_ID')
						->select('SUB_ID','REKENING_ID')
						->get();
		$i 			= 1;
		foreach($data as $btl){
			$skpd 		= $btl->subunit;
			$Kd_Urusan 	= substr($skpd->SKPD->SKPD_KODE, 0,1);
			$Kd_Bidang 	= substr($skpd->SKPD->SKPD_KODE, 2,2)*1;
			$Kd_Unit 	= substr($skpd->SKPD->SKPD_KODE, 5,2)*1;
			$Kd_Sub 	= $skpd->SUB_KODE *1;
			$Kd_Prog 	= 0;
			$ID_Prog 	= 0;
			$Kd_Keg 	= 0;
			$rekening 	= $btl->rekening;
			$Kd_Rek_1	= substr($rekening->REKENING_KODE,0,1);
			$Kd_Rek_2	= substr($rekening->REKENING_KODE,2,1);
			$Kd_Rek_3	= substr($rekening->REKENING_KODE,4,1);
			$Kd_Rek_4	= substr($rekening->REKENING_KODE,6,2)*1;
			$Kd_Rek_5	= substr($rekening->REKENING_KODE,9,2)*1;
			$cek 		= DB::connection('sqlsrv')->table('dbo.Ta_Belanja')
									->where('Tahun',2017)
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
				$value 	= array('Tahun' 			=> 2017,
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
				DB::connection('sqlsrv')->table('dbo.Ta_Belanja')->insert($value);
				
				$value 	= array('Tahun' 			=> 2017,
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
									'No_Rinc'			=> $i,
									'Keterangan' 		=> $rekening->REKENING_NAMA);
				DB::connection('sqlsrv')->table('dbo.Ta_Belanja_Rinc')->insert($value);
				$subrincian 	= BTL::where('BTL_TAHUN',$tahun)
									->where('REKENING_ID',$btl->REKENING_ID)
									->where('SUB_ID',$btl->SUB_ID)
									->get();
				$j = 1;
				foreach($subrincian as $sr){
					$rekening 	= $sr->rekening;
					$insert 	= "INSERT INTO [dbo].[Ta_Belanja_Rinc_Sub] VALUES 
										('2017', 
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
										 'Tahun', 
										 CAST('1' AS MONEY), 
										 '', CAST('0' AS MONEY), 
										 '', CAST('0' AS MONEY), 
										 'Tahun', 
										 CAST('1' AS MONEY), 
										 CAST('".$sr->BTL_TOTAL."' AS MONEY), 
										 CAST('".$sr->BTL_TOTAL."' AS MONEY), 
										 '".$rekening->REKENING_NAMA."');";
					DB::connection('sqlsrv')->insert(DB::raw($insert));
					$j++;			
				}
			}
		}
	}

	public function trfPendapatan($tahun){
		$data 	 	= Pendapatan::where('PENDAPATAN_TAHUN',$tahun)
						->groupBy('SUB_ID')
						->groupBy('REKENING_ID')
						->select('SUB_ID','REKENING_ID')
						->get();
		$i 			= 1;
		foreach($data as $btl){
			$skpd 		= $btl->subunit;
			$Kd_Urusan 	= substr($skpd->SKPD->SKPD_KODE, 0,1);
			$Kd_Bidang 	= substr($skpd->SKPD->SKPD_KODE, 2,2)*1;
			$Kd_Unit 	= substr($skpd->SKPD->SKPD_KODE, 5,2)*1;
			$Kd_Sub 	= $skpd->SUB_KODE *1;
			$Kd_Prog 	= 0;
			$ID_Prog 	= 0;
			$Kd_Keg 	= 0;
			$rekening 	= $btl->rekening;
			$Kd_Rek_1	= substr($rekening->REKENING_KODE,0,1);
			$Kd_Rek_2	= substr($rekening->REKENING_KODE,2,1);
			$Kd_Rek_3	= substr($rekening->REKENING_KODE,4,1);
			$Kd_Rek_4	= substr($rekening->REKENING_KODE,6,2)*1;
			$Kd_Rek_5	= substr($rekening->REKENING_KODE,9,2)*1;
			$cek 		= DB::connection('sqlsrv')->table('dbo.Ta_Pendapatan')
									->where('Tahun',2017)
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
									->where('Kd_Pendapatan',$Kd_Rek_3)
									->count();
			if($cek == 0){
				$value 	= array('Tahun' 			=> 2017,
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
								'Kd_Pendapatan'		=> $Kd_Rek_3);
				DB::connection('sqlsrv')->table('dbo.Ta_Pendapatan')->insert($value);
				
				$subrincian 	= Pendapatan::where('PENDAPATAN_TAHUN',$tahun)
									->where('REKENING_ID',$btl->REKENING_ID)
									->where('SUB_ID',$btl->SUB_ID)
									->get();
				$j = 1;
				foreach($subrincian as $sr){
					$rekening 	= $sr->rekening;
					$insert 	= "INSERT INTO [dbo].[Ta_Pendapatan_Rinc] VALUES 
										('2017', 
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
										 '".$j."', 
										 'Tahun', 
										 CAST('1' AS MONEY), 
										 '', CAST('0' AS MONEY), 
										 '', CAST('0' AS MONEY), 
										 'Tahun', 
										 CAST('1' AS MONEY), 
										 CAST('".$sr->PENDAPATAN_TOTAL."' AS MONEY), 
										 CAST('".$sr->PENDAPATAN_TOTAL."' AS MONEY), 
										 '".$rekening->REKENING_NAMA."');";
					DB::connection('sqlsrv')->insert(DB::raw($insert));
					$j++;			
				}
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
