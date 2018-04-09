<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use DB;
use Excel;
use App\Model\Monev\Monev_Kegiatan;



class ExcelController extends Controller
{
    public function getExport(){
		$monev = Monev_Kegiatan::where('KEGIATAN_ID',1)->get()->toarray();
		
    	Excel::create('Export Data', function($excel) use($monev){
    		$excel->sheet('Sheet 1', function($sheet) use($monev){

				$header = [
            		"NO",
            		"Sasaran",
            		"Program/ Kegiatan",
            		"Indikator Kinerja Program (outcome)/Kegiatan(output)",
            		"Target Renstra Perangkat Daerah Pada Tahun 2019 (Akhir Periode Renstra Perangkat Daerah)",
            		"Realisasi Capaian Kinerja Renstra PD sampai dengan PD tahun lalu (n-2)",
                    "Target Kinerja dan Anggaran Renja PD tahun berjalan (Tahun n-1) yang di evaluasi",
            		"Realisasi Kinerja Pada Triwulan (Rp)",
            		"Realisasi Capaian Kinerja dan Anggaran Renja PD yang di Evaluasi",
            		"Realisasi Kinerja dan Anggaran Renstra PD s/d tahun 2019 PD yang di Evaluasi",
            		"Tingkat Capaian Kinerja dan Realisasi Anggaran Resntra PD s/d tahun 2019(%)	Unit PD Penanggung Jawab",
				];
				$sheet->setColumnFormat(array(
                    'F' => "#,##0",
                    'H' => "#,##0",
                    'J' => "#,##0",
                    'L' => "#,##0",
                    'N' => "#,##0",
                    'P' => "#,##0",
                    'R' => "#,##0",
                    'T' => "#,##0",
                    'V' => "#,##0",
                    'X' => "#,##0",
				));
				
				$sheet->row(1,["Evaluasi Terhadap Hasil Renja Perangkat Daerah Lingkup Kota Bandung"]);
				$sheet->row(2,["Renja Perangkat Daerah SKPD A Kota Bandung"]);
				$sheet->row(3,["Periode Pelaksanaan : 2019"]);
                $sheet->row(4,["","","","Indikator dan target Kinerja Perangkat Daerah Kota yang mengacu pada sasaran RKPD : "]);
				$sheet->row(5,$header);
				$sheet->row(6,["","","","","","","","","","","1","","2","","3","","4",""]);
            	$sheet->row(7,["1","2","3","4","5","","6","","7","","8","","9","","10","","11","","12","","13=6+12","","14=13/5x100%","","15"]);
            	$sheet->row(8,["","","","","K","Rp","K","Rp","K","Rp","K","Rp","K","Rp","K","Rp","K","Rp","K","Rp","K","Rp","K","Rp",""]);
            	$sheet->fromArray($monev, null, 'A6', true,false);
				$sheet->setBorder('A5:Y'.(count($monev)+1),"thin");
				$sheet->mergeCells('A'.(count($monev)+1).':X'.(count($monev)+1));
				$sheet->mergeCells('A'.(count($monev)+2).':X'.(count($monev)+2));
				$sheet->mergeCells('A'.(count($monev)+3).':X'.(count($monev)+3));
				$sheet->mergeCells('D'.(count($monev)+4).':X'.(count($monev)+4));
                $sheet->mergeCells('E5:F6');
                $sheet->mergeCells('G5:H6');
                $sheet->mergeCells('I5:J6');
                $sheet->mergeCells('K5:R6');
                $sheet->mergeCells('S5:T6');
                $sheet->mergeCells('U5:V6');
				$sheet->mergeCells('W5:X6');
                $sheet->mergeCells('A7:A8');
                $sheet->mergeCells('B7:B8');
                $sheet->mergeCells('C7:C8');
                $sheet->mergeCells('D7:D8');
                $sheet->mergeCells('E7:F7');
                $sheet->mergeCells('G7:H7');
				$sheet->mergeCells('K7:L7');
				$sheet->mergeCells('M7:N7');
				$sheet->mergeCells('O7:P7');
				$sheet->mergeCells('Q7:R7');
				$sheet->mergeCells('S7:T7');
				$sheet->mergeCells('U7:V7');
				$sheet->mergeCells('W7:X7');

				$sheet->mergeCells('A'.(count($monev)+8).':J'.(count($monev)+8));
				$sheet->mergeCells('A'.(count($monev)+9).':J'.(count($monev)+9));
				$sheet->mergeCells('A'.(count($monev)+10).':Y'.(count($monev)+10));
				$sheet->mergeCells('A'.(count($monev)+11).':Y'.(count($monev)+11));
				$sheet->mergeCells('A'.(count($monev)+12).':Y'.(count($monev)+12));
				$sheet->mergeCells('A'.(count($monev)+13).':Y'.(count($monev)+13));
				
				$sheet->cells('A1:Y1',function($cells){
                	$cells->setAlignment('center');
                	$cells->setFontWeight("bold");
                	//$cells->setBorder('solid','solid','solid','solid');
				});
				$sheet->cells('A2:Y2',function($cells){
                	$cells->setAlignment('center');
                	$cells->setFontWeight("bold");
                	//$cells->setBorder('solid','solid','solid','solid');
				});
				$sheet->cells('A3:Y3',function($cells){
                	$cells->setAlignment('center');
                	$cells->setFontWeight("bold");
                	//$cells->setBorder('solid','solid','solid','solid');
				});
				$sheet->cells('A4:Y4',function($cells){
                	$cells->setFontWeight("bold");
                	//$cells->setBorder('solid','solid','solid','solid');
                });

            	$sheet->cells('A5:Y5',function($cells){
                	$cells->setAlignment('center');
                	$cells->setFontWeight("bold");
                	//$cells->setBorder('solid','solid','solid','solid');
                });
               	
                $sheet->setAutoFilter('A5:Y'.(count($monev)+5));
    		});
    	})->export('xls');
    }
}
