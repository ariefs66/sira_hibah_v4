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
    	$monev = Monev_Kegiatan::where('KEGIATAN_ID',1);
    	Excel::create('Export Data', function($excel) use($monev){
    		$excel->sheet('Sheet 1', function($sheet) use($monev){
    			$sheet->fromArray($monev);
    		});
    	})->export('xls');
    }
}
