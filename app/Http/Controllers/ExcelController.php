<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Excel;
use PHPExcel_Helper_HTML;
use Carbon;
use App\Model\SKPD;
use App\Model\Program;
use App\Model\Kegiatan;
use App\Model\Monev\Monev_Kegiatan;
use App\Model\Monev\Monev_Program;
use App\Model\Monev\Monev_Realisasi;
use App\Model\Monev\Monev_Faktor;
use App\Model\Monev\Monev_Outcome;
use App\Model\Monev\Monev_Output;
use App\Model\Monev\Monev_Tahapan;
use App\Model\BL;
use App\Model\BLPerubahan;
use App\Model\Rekening;
use App\Model\Output;
use App\Model\OutputPerubahan;
use App\Model\Outcome;
use App\Model\Realisasi;
use App\Model\Satuan;
use App\Model\UserBudget;



class ExcelController extends Controller
{
    public function getExport($tahun, $skpd){
		$prog = Monev_Program::where('DAT_PROGRAM.SKPD_ID',$skpd)->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_PROGRAM.SATUAN')
        ->leftJoin('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','DAT_PROGRAM.SKPD_ID')
                ->where('PROGRAM_TAHUN',$tahun)->get();
    if($skpd==0){
      return $this->tapdAll($tahun);
    }else{
      $idskpd         = SKPD::where('SKPD_ID',$skpd)->first();
    }
        $program = array();
        $skpdnama = "Tidak Ada SKPD";
        $ringkasoutcome = "";
        $tahapan = 0;
        $tahapan    = Monev_Tahapan::where('TAHAPAN_TAHUN',$tahun)->first();
        if($tahapan){
          if($tahapan->TAHAPAN_T1==1){
            $tahapan = 1;
          }elseif($tahapan->TAHAPAN_T2==1){
            $tahapan = 2;
          }elseif($tahapan->TAHAPAN_T3==1){
            $tahapan = 3;
          }else{
            $tahapan = 4;
          }
        }else{
          $tahapan = 0;
        }
        $faktor = Monev_Faktor::where('TAHUN',$tahun)->where('SKPD_ID',$skpd)
                ->where('T',$tahapan)->first();
        if($faktor){
          $penghambat=$faktor->PENGHAMBAT;
          $pendukung=$faktor->PENDUKUNG;
          $triwulan=$faktor->TRIWULAN;
          $renja=$faktor->RENJA;
          $sasaran=$faktor->SASARAN;
        }else{
          $penghambat="";
          $pendukung="";
          $triwulan="";
          $renja="";
          $sasaran="";
        }
        foreach ($prog as $prog) {
          $outcome = Monev_Outcome::where('PROGRAM_ID',$prog->REF_PROGRAM_ID)->get();
          foreach ($outcome as $outcome) {
            $ringkasoutcome = $outcome->OUTCOME_TOLAK_UKUR ." : ". $outcome->OUTCOME_TARGET . "%\r\n". $ringkasoutcome;
          }
        array_push($program, array( 'KEGIATAN'     => Monev_Kegiatan::where('DAT_PROGRAM.REF_PROGRAM_ID',$prog->REF_PROGRAM_ID)->where('DAT_KEGIATAN.SKPD_ID',$prog->SKPD_ID)->leftJoin('MONEV.DAT_PROGRAM','DAT_PROGRAM.PROGRAM_ID','=','DAT_KEGIATAN.PROGRAM_ID')->leftJoin('MONEV.DAT_REALISASI','DAT_REALISASI.KEGIATAN_ID','=','DAT_KEGIATAN.KEGIATAN_ID')->leftJoin('MONEV.DAT_OUTPUT','DAT_OUTPUT.KEGIATAN_ID','=','DAT_KEGIATAN.KEGIATAN_ID')->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_KEGIATAN.SATUAN')->get(),
                                 'PROGRAM_ID'     => $prog->PROGRAM_ID,
                                 'SKPD_ID'     => $prog->SKPD_ID,
                                 'SKPD'     => $prog->SKPD_NAMA,
                                 'PROGRAM_KODE'     => $prog->PROGRAM_KODE,
                                 'PROGRAM_NAMA'     => $prog->PROGRAM_NAMA,
                                 'PROGRAM_ANGGARAN'     => $prog->PROGRAM_ANGGARAN,
                                 'PROGRAM_T1'     => $prog->PROGRAM_T1,
                                 'PROGRAM_T2'     => $prog->PROGRAM_T2,
                                 'PROGRAM_T3'     => $prog->PROGRAM_T3,
                                 'PROGRAM_T4'     => $prog->PROGRAM_T4,
                                 'PROGRAM_PENDUKUNG_T1'     => $prog->PROGRAM_PENDUKUNG_T1,
                                 'PROGRAM_PENDUKUNG_T2'     => $prog->PROGRAM_PENDUKUNG_T2,
                                 'PROGRAM_PENDUKUNG_T3'     => $prog->PROGRAM_PENDUKUNG_T3,
                                 'PROGRAM_PENDUKUNG_T4'     => $prog->PROGRAM_PENDUKUNG_T4,
                                 'PROGRAM_PENGHAMBAT_T1'     => $prog->PROGRAM_PENGHAMBAT_T1,
                                 'PROGRAM_PENGHAMBAT_T2'     => $prog->PROGRAM_PENGHAMBAT_T2,
                                 'PROGRAM_PENGHAMBAT_T3'     => $prog->PROGRAM_PENGHAMBAT_T3,
                                 'PROGRAM_PENGHAMBAT_T4'     => $prog->PROGRAM_PENGHAMBAT_T4,
                                 'PROGRAM_TAHUN'     => $prog->PROGRAM_TAHUN,
                                 'SASARAN_NAMA'     => $prog->SASARAN_NAMA,
                                 'REF_PROGRAM_ID'     => $prog->REF_PROGRAM_ID,
                                 'SATUAN'    => $prog->SATUAN_NAMA,
                                 'OUTCOME'   => $ringkasoutcome ));
                                 $skpdnama     = $prog->SKPD_NAMA;
      }
      $i=1;
		
		Excel::load('public/uploads/e81.xls', function($excel) use($program, $idskpd, $tahun, $skpdnama, $sasaran, $pendukung, $penghambat, $triwulan, $renja) {
    		$excel->sheet('Formulir E.81', function($sheet) use($program, $tahun, $idskpd,  $skpdnama, $sasaran, $pendukung, $penghambat, $triwulan, $renja){
				$sheet->setCellValue('A7', 'Renja Perangkat Daerah '. $skpdnama .' Kabupaten/kota Bandung');
				$sheet->setCellValue('A8', 'Periode Pelaksanaan '.$tahun);
				$sheet->setCellValue('B16', strtoupper($skpdnama));
				$row = 17;
        $t1 = 0;
        $jumlah = 0;
        $t2 = 0;
        $t3 = 0;
        $t4 = 0;
				foreach($program as $p){
					$sheet->setHeight(($row-1), 50);
					if($row==17){
						$sheet->appendRow($row, array(
							'',$sasaran, ' '.$p['PROGRAM_NAMA'], $p['OUTCOME'],'','','','','','',$p['PROGRAM_T1'] . ' ' . ($p['PROGRAM_T1']?$p['SATUAN']:''),'',$p['PROGRAM_T2'] . ' ' . ($p['PROGRAM_T2']?$p['SATUAN']:''),'',$p['PROGRAM_T3'] . ' ' . ($p['PROGRAM_T3']?$p['SATUAN']:''),'',$p['PROGRAM_T4'] . ' ' . ($p['PROGRAM_T4']?$p['SATUAN']:''),'','','','','','','',$p['SKPD']
            ));
            $t1 = $t1 + $p['PROGRAM_T1'];
            $t2 = $t1 + $p['PROGRAM_T2'];
            $t3 = $t1 + $p['PROGRAM_T3'];
            $t4 = $t1 + $p['PROGRAM_T4'];
            $jumlah=$jumlah+1;
					}else{
						$sheet->prependRow($row, array(
							'',$p['SASARAN_NAMA'], ' '.$p['PROGRAM_NAMA'], $p['OUTCOME'],'','','','','','',$p['PROGRAM_T1'] . ' ' . ($p['PROGRAM_T1']?$p['SATUAN']:''),'',$p['PROGRAM_T2'] . ' ' . ($p['PROGRAM_T2']?$p['SATUAN']:''),'',$p['PROGRAM_T3'] . ' ' . ($p['PROGRAM_T3']?$p['SATUAN']:''),'',$p['PROGRAM_T4'] . ' ' . ($p['PROGRAM_T4']?$p['SATUAN']:''),'','','','','','','',$p['SKPD']
						));
            $t1 = $t1 + $p['PROGRAM_T1'];
            $t2 = $t1 + $p['PROGRAM_T2'];
            $t3 = $t1 + $p['PROGRAM_T3'];
            $t4 = $t1 + $p['PROGRAM_T4'];
            $jumlah=$jumlah+1;
					}
					$sheet->row(($row), function($cells) { $cells->setFont(array(
						'family'     => 'Times',
						'size'       => '9',
						'bold'       =>  true
					)); });
					$range = 'A'.($row-1).':Y'.($row-1);
					$sheet->cells($range, function($cells) {
						$cells->setBorder('thin', 'thin', 'thin', 'thin');
          });
          $cek = "";
					$row++;
					foreach($p['KEGIATAN'] as $k){
            $sheet->setHeight(($row-1), 50);
            if($cek == $k['KEGIATAN_NAMA']){
              $sheet->prependRow($row, array(
                '',$k['SASARAN_NAMA'], '', $k['OUTPUT_TOLAK_UKUR'],'','','','',$k['OUTPUT_TARGET'].' '.$k['SATUAN_NAMA'],'',$k['KEGIATAN_T1'] . ' ' . ($k['KEGIATAN_T1']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T1'],$k['KEGIATAN_T2'] . ' ' . ($k['KEGIATAN_T2']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T2'],$k['KEGIATAN_T3'] . ' ' . ($k['KEGIATAN_T3']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T3'],$k['KEGIATAN_T4'] . ' ' . ($k['KEGIATAN_T4']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T4'],'',($k['REALISASI_T1']+$k['REALISASI_T2']+$k['REALISASI_T3']+$k['REALISASI_T4']),'',($k['REALISASI_T1']+$k['REALISASI_T2']+$k['REALISASI_T3']+$k['REALISASI_T4']),'',($k['REALISASI_T1']+$k['REALISASI_T2']+$k['REALISASI_T3']+$k['REALISASI_T4']),$p['SKPD']
              ));
            }else{
              $sheet->prependRow($row, array(
                '',$k['SASARAN_NAMA'], '  '.$k['KEGIATAN_NAMA'], $k['OUTPUT_TOLAK_UKUR'],'','','','',$k['OUTPUT_TARGET'].' '.$k['SATUAN_NAMA'],$k['KEGIATAN_ANGGARAN'],$k['KEGIATAN_T1'] . ' ' . ($k['KEGIATAN_T1']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T1'],$k['KEGIATAN_T2'] . ' ' . ($k['KEGIATAN_T2']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T2'],$k['KEGIATAN_T3'] . ' ' . ($k['KEGIATAN_T3']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T3'],$k['KEGIATAN_T4'] . ' ' . ($k['KEGIATAN_T4']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T4'],'',($k['REALISASI_T1']+$k['REALISASI_T2']+$k['REALISASI_T3']+$k['REALISASI_T4']),'',($k['REALISASI_T1']+$k['REALISASI_T2']+$k['REALISASI_T3']+$k['REALISASI_T4']),'',($k['REALISASI_T1']+$k['REALISASI_T2']+$k['REALISASI_T3']+$k['REALISASI_T4']),$p['SKPD']
              ));
              $cek = $k['KEGIATAN_NAMA'];
            }
						$sheet->row(($row), function($cells) { $cells->setFont(array(
							'family'     => 'Times',
							'size'       => '9',
							'bold'       =>  false
						)); });
						$range = 'A'.($row-1).':Y'.($row-1);
						$sheet->cells($range, function($cells) {
							$cells->setBorder('thin', 'thin', 'thin', 'thin');
						});
						$row++;
					}
        }
        if($row==17){
          $row=19;
        }
        if(intval($jumlah)>0){
          $hitungt1 = intval($t1) / intval($jumlah);
        }else{
          $hitungt1 = intval($t1);
        }
        $sheet->setCellValue('K'.($row), number_format($hitungt1,0,'.',',').'%');
				$helper = new PHPExcel_Helper_HTML;
				$html = "<b>Faktor pendorong keberhasilan kinerja:<br>".nl2br(str_limit($pendukung, 300))."</b>";
				$richText = $helper->toRichTextObject($html);
				$sheet->setCellValue('A'.($row+2), $richText);
				$html = "<b>Faktor penghambat pencapain kinerja:<br>".nl2br(str_limit($penghambat,300))."</b>";
				$richText = $helper->toRichTextObject($html);
				$sheet->setCellValue('A'.($row+3), $richText);
				$html = "<b>Tindak lanjut yang diperlukan dalam triwulan berikutnya:<br>".nl2br($triwulan)."</b>";
				$richText = $helper->toRichTextObject($html);
				$sheet->setCellValue('A'.($row+4), $richText);
				$html = "<b>Tindak lanjut yang diperlukan dalam Renja Perangkat Daerah Kabupaten/Kota berikutnya:<br>".nl2br($renja)."</b>";
				$richText = $helper->toRichTextObject($html);
				$sheet->setCellValue('A'.($row+5), $richText);
				$sheet->setCellValue('W3', ': April  2018');
				$sheet->setCellValue('T'.($row+9), '30 April 2018');
				$sheet->setCellValue('Y'.($row+9), '30 April 2018');
				$sheet->setCellValue('Q'.($row+10), '');
				$sheet->setCellValue('Q'.($row+16), ''.$idskpd->SKPD_KEPALA);
				$sheet->setCellValue('Q'.($row+17), 'NIP. '.$idskpd->SKPD_KEPALA_NIP);
    		});
    	})->export('xls');
    }

    public function tapdAll($tahun){
      $skpd       = Monev_Faktor::select('SKPD_ID')->where('TAHUN',$tahun)->groupBy('SKPD_ID')->get();
      Excel::load('public/uploads/e81.xls', function($excel) use($tahun, $skpd) {
        $excel->sheet('Formulir E.81', function($sheet) use($tahun, $skpd){
        $sheet->setCellValue('A7', 'Renja Perangkat Daerah '.' Kabupaten/kota Bandung');
        $sheet->setCellValue('A8', 'Periode Pelaksanaan '.$tahun);
        $sheet->setCellValue('B16', strtoupper('A'));
        $row = 17;
        $t1 = 0;
        $t2 = 0;
        $t3 = 0;
        $t4 = 0;
        foreach($skpd as $s){
          $prog = Monev_Program::where('DAT_PROGRAM.SKPD_ID',$s->SKPD_ID)->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_PROGRAM.SATUAN')
          ->leftJoin('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','DAT_PROGRAM.SKPD_ID')
                  ->where('PROGRAM_TAHUN',$tahun)->get();
          $program = array();
          $skpdnama = "Tidak Ada SKPD";
          $ringkasoutcome = "";
          $tahapan = 0;
          $tahapan    = Monev_Tahapan::where('TAHAPAN_TAHUN',$tahun)->first();
          if($tahapan){
            if($tahapan->TAHAPAN_T1==1){
              $tahapan = 1;
            }elseif($tahapan->TAHAPAN_T2==1){
              $tahapan = 2;
            }elseif($tahapan->TAHAPAN_T3==1){
              $tahapan = 3;
            }else{
              $tahapan = 4;
            }
          }else{
            $tahapan = 0;
          }
          $faktor = Monev_Faktor::where('TAHUN',$tahun)->where('SKPD_ID',$s->SKPD_ID)
                  ->where('T',$tahapan)->first();
          if($faktor){
            $penghambat=$faktor->PENGHAMBAT;
            $pendukung=$faktor->PENDUKUNG;
            $triwulan=$faktor->TRIWULAN;
            $renja=$faktor->RENJA;
            $sasaran=$faktor->SASARAN;
          }else{
            $penghambat="";
            $pendukung="";
            $triwulan="";
            $renja="";
            $sasaran="";
          }
          foreach ($prog as $prog) {
            $outcome = Monev_Outcome::where('PROGRAM_ID',$prog->REF_PROGRAM_ID)->get();
            foreach ($outcome as $outcome) {
              $ringkasoutcome = $outcome->OUTCOME_TOLAK_UKUR ." : ". $outcome->OUTCOME_TARGET . "%\r\n". $ringkasoutcome;
            }
          array_push($program, array( 'KEGIATAN'     => Monev_Kegiatan::where('DAT_KEGIATAN.PROGRAM_ID',$prog->PROGRAM_ID)->leftJoin('MONEV.DAT_REALISASI','DAT_REALISASI.KEGIATAN_ID','=','DAT_KEGIATAN.KEGIATAN_ID')->leftJoin('MONEV.DAT_OUTPUT','DAT_OUTPUT.KEGIATAN_ID','=','DAT_KEGIATAN.KEGIATAN_ID')->leftJoin('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_OUTPUT.OUTPUT_SATUAN')->get(),
                                   'PROGRAM_ID'     => $prog->PROGRAM_ID,
                                   'SKPD_ID'     => $prog->SKPD_ID,
                                   'SKPD'     => $prog->SKPD_NAMA,
                                   'PROGRAM_KODE'     => $prog->PROGRAM_KODE,
                                   'PROGRAM_NAMA'     => $prog->PROGRAM_NAMA,
                                   'PROGRAM_ANGGARAN'     => $prog->PROGRAM_ANGGARAN,
                                   'PROGRAM_T1'     => $prog->PROGRAM_T1,
                                   'PROGRAM_T2'     => $prog->PROGRAM_T2,
                                   'PROGRAM_T3'     => $prog->PROGRAM_T3,
                                   'PROGRAM_T4'     => $prog->PROGRAM_T4,
                                   'PROGRAM_PENDUKUNG_T1'     => $prog->PROGRAM_PENDUKUNG_T1,
                                   'PROGRAM_PENDUKUNG_T2'     => $prog->PROGRAM_PENDUKUNG_T2,
                                   'PROGRAM_PENDUKUNG_T3'     => $prog->PROGRAM_PENDUKUNG_T3,
                                   'PROGRAM_PENDUKUNG_T4'     => $prog->PROGRAM_PENDUKUNG_T4,
                                   'PROGRAM_PENGHAMBAT_T1'     => $prog->PROGRAM_PENGHAMBAT_T1,
                                   'PROGRAM_PENGHAMBAT_T2'     => $prog->PROGRAM_PENGHAMBAT_T2,
                                   'PROGRAM_PENGHAMBAT_T3'     => $prog->PROGRAM_PENGHAMBAT_T3,
                                   'PROGRAM_PENGHAMBAT_T4'     => $prog->PROGRAM_PENGHAMBAT_T4,
                                   'PROGRAM_TAHUN'     => $prog->PROGRAM_TAHUN,
                                   'SASARAN_NAMA'     => $prog->SASARAN_NAMA,
                                   'REF_PROGRAM_ID'     => $prog->REF_PROGRAM_ID,
                                   'SATUAN'    => $prog->SATUAN_NAMA,
                                   'OUTCOME'   => $ringkasoutcome ));
                                   $skpdnama     = $prog->SKPD_NAMA;
        }
        $i=1;
        
          
        foreach($program as $p){
          $sheet->setHeight(($row-1), 50);
          if($row==17){
            $sheet->appendRow($row, array(
              '',$sasaran, ' '.$p['PROGRAM_NAMA'], $p['OUTCOME'],'','','','','',$p['PROGRAM_ANGGARAN'],$p['PROGRAM_T1'] . ' ' . ($p['PROGRAM_T1']?$p['SATUAN']:''),'',$p['PROGRAM_T2'] . ' ' . ($p['PROGRAM_T2']?$p['SATUAN']:''),'',$p['PROGRAM_T3'] . ' ' . ($p['PROGRAM_T3']?$p['SATUAN']:''),'',$p['PROGRAM_T4'] . ' ' . ($p['PROGRAM_T4']?$p['SATUAN']:''),'','','','','','','',$p['SKPD']
            ));
            $t1 = $t1 + $p['PROGRAM_T1'];
            $t2 = $t1 + $p['PROGRAM_T2'];
            $t3 = $t1 + $p['PROGRAM_T3'];
            $t4 = $t1 + $p['PROGRAM_T4'];
          }else{
            $sheet->prependRow($row, array(
              '',$p['SASARAN_NAMA'], ' '.$p['PROGRAM_NAMA'], $p['OUTCOME'],'','','','','',$p['PROGRAM_ANGGARAN'],$p['PROGRAM_T1'] . ' ' . ($p['PROGRAM_T1']?$p['SATUAN']:''),'',$p['PROGRAM_T2'] . ' ' . ($p['PROGRAM_T2']?$p['SATUAN']:''),'',$p['PROGRAM_T3'] . ' ' . ($p['PROGRAM_T3']?$p['SATUAN']:''),'',$p['PROGRAM_T4'] . ' ' . ($p['PROGRAM_T4']?$p['SATUAN']:''),'','','','','','','',$p['SKPD']
            ));
            $t1 = $t1 + $p['PROGRAM_T1'];
            $t2 = $t1 + $p['PROGRAM_T2'];
            $t3 = $t1 + $p['PROGRAM_T3'];
            $t4 = $t1 + $p['PROGRAM_T4'];
          }
          $sheet->row(($row), function($cells) { $cells->setFont(array(
            'family'     => 'Times',
            'size'       => '9',
            'bold'       =>  true
          )); });
          $range = 'A'.($row-1).':Y'.($row-1);
          $sheet->cells($range, function($cells) {
            $cells->setBorder('thin', 'thin', 'thin', 'thin');
          });
          $cek = "";
          $row++;
          foreach($p['KEGIATAN'] as $k){
            $sheet->setHeight(($row-1), 50);
            if($cek == $k['KEGIATAN_NAMA']){
              $sheet->prependRow($row, array(
                '',$k['SASARAN_NAMA'], '', $k['OUTPUT_TOLAK_UKUR'],'','','','',$k['OUTPUT_TARGET'].' '.$k['SATUAN_NAMA'],'',$k['KEGIATAN_T1'] . ' ' . ($k['KEGIATAN_T1']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T1'],$k['KEGIATAN_T2'] . ' ' . ($k['KEGIATAN_T2']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T2'],$k['KEGIATAN_T3'] . ' ' . ($k['KEGIATAN_T3']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T3'],$k['KEGIATAN_T4'] . ' ' . ($k['KEGIATAN_T4']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T4'],'',($k['REALISASI_T1']+$k['REALISASI_T2']+$k['REALISASI_T3']+$k['REALISASI_T4']),'',($k['REALISASI_T1']+$k['REALISASI_T2']+$k['REALISASI_T3']+$k['REALISASI_T4']),'',($k['REALISASI_T1']+$k['REALISASI_T2']+$k['REALISASI_T3']+$k['REALISASI_T4']),$p['SKPD']
              ));
            }else{
              $sheet->prependRow($row, array(
                '',$k['SASARAN_NAMA'], '  '.$k['KEGIATAN_NAMA'], $k['OUTPUT_TOLAK_UKUR'],'','','','',$k['OUTPUT_TARGET'].' '.$k['SATUAN_NAMA'],$k['KEGIATAN_ANGGARAN'],$k['KEGIATAN_T1'] . ' ' . ($k['KEGIATAN_T1']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T1'],$k['KEGIATAN_T2'] . ' ' . ($k['KEGIATAN_T2']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T2'],$k['KEGIATAN_T3'] . ' ' . ($k['KEGIATAN_T3']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T3'],$k['KEGIATAN_T4'] . ' ' . ($k['KEGIATAN_T4']?' '.$k['SATUAN_NAMA']:''),$k['REALISASI_T4'],'',($k['REALISASI_T1']+$k['REALISASI_T2']+$k['REALISASI_T3']+$k['REALISASI_T4']),'',($k['REALISASI_T1']+$k['REALISASI_T2']+$k['REALISASI_T3']+$k['REALISASI_T4']),'',($k['REALISASI_T1']+$k['REALISASI_T2']+$k['REALISASI_T3']+$k['REALISASI_T4']),$p['SKPD']
              ));
              $cek = $k['KEGIATAN_NAMA'];
            }
            $sheet->row(($row), function($cells) { $cells->setFont(array(
              'family'     => 'Times',
              'size'       => '9',
              'bold'       =>  false
            )); });
            $range = 'A'.($row-1).':Y'.($row-1);
            $sheet->cells($range, function($cells) {
              $cells->setBorder('thin', 'thin', 'thin', 'thin');
            });
            $row++;
          }
        }
        $sheet->setCellValue('K'.($row), $t1.'%');
        $helper = new PHPExcel_Helper_HTML;
        $html = "<b>Faktor pendorong keberhasilan kinerja:<br>".nl2br($pendukung)."</b>";
        $richText = $helper->toRichTextObject($html);
        $sheet->setCellValue('A'.($row+2), $richText);
        $html = "<b>Faktor penghambat pencapain kinerja:<br>".nl2br($penghambat)."</b>";
        $richText = $helper->toRichTextObject($html);
        $sheet->setCellValue('A'.($row+3), $richText);
        $html = "<b>Tindak lanjut yang diperlukan dalam triwulan berikutnya:<br>".nl2br($triwulan)."</b>";
        $richText = $helper->toRichTextObject($html);
        $sheet->setCellValue('A'.($row+4), $richText);
        $html = "<b>Tindak lanjut yang diperlukan dalam Renja Perangkat Daerah Kabupaten/Kota berikutnya:<br>".nl2br($renja)."</b>";
        $richText = $helper->toRichTextObject($html);
        $sheet->setCellValue('A'.($row+5), $richText);
        $sheet->setCellValue('W3', ': April  2018');
        $sheet->setCellValue('T'.($row+9), '20 Maret 2018');
        $sheet->setCellValue('Y'.($row+9), '20 Maret 2018');
        $sheet->setCellValue('Q'.($row+10), '');
        $sheet->setCellValue('Q'.($row+16), '');
        $sheet->setCellValue('Q'.($row+17), '');
          }

        });
      })->export('xls');
      
      }

}
