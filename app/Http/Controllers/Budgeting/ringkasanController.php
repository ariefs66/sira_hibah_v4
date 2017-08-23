<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Excel;
Use App\Model\Pendapatan;
Use App\Model\BL;
Use App\Model\Rincian;
Use App\Model\BTL;
Use App\Model\Pembiayaan;
Use App\Model\Rekening;
class ringkasanController extends Controller
{
    public function index($tahun,$status){
    	$pendapatan 		= Pendapatan::sum('PENDAPATAN_TOTAL');
    	$pendapatan1       = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1%');
        })->sum('PENDAPATAN_TOTAL');
        $pendapatan11       = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1.1%');
        })->sum('PENDAPATAN_TOTAL');
        $pendapatan12       = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1.2%');
        })->sum('PENDAPATAN_TOTAL');
        $pendapatan13       = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1.3%');
        })->sum('PENDAPATAN_TOTAL');
        $pendapatan14       = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.1.4%');
        })->sum('PENDAPATAN_TOTAL');
        $pendapatan2        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.2%');
        })->sum('PENDAPATAN_TOTAL');
        $pendapatan21        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.2.1%');
        })->sum('PENDAPATAN_TOTAL');
        $pendapatan22        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.2.2%');
        })->sum('PENDAPATAN_TOTAL');
        $pendapatan23        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.2.3%');
        })->sum('PENDAPATAN_TOTAL');
        $pendapatan3        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.3%');
        })->sum('PENDAPATAN_TOTAL');
        $pendapatan31        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.3.1%');
        })->sum('PENDAPATAN_TOTAL');
        $pendapatan32        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.3.3%');
        })->sum('PENDAPATAN_TOTAL');
        $pendapatan33        = Pendapatan::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','4.3.4%');
        })->sum('PENDAPATAN_TOTAL');
        $pendapatan34 		= Pendapatan::wherehas('rekening',function($q){
    		$q->where('REKENING_KODE','like','4.3.5%');
    	})->sum('PENDAPATAN_TOTAL');

        $btl1      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.1%');
        })->sum('BTL_TOTAL');
        $btl3      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.3%');
        })->sum('BTL_TOTAL');
        $btl4      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.4%');
        })->sum('BTL_TOTAL');
        $btl7      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.7%');
        })->sum('BTL_TOTAL');
        $btl8      = BTL::wherehas('rekening',function($q){
            $q->where('REKENING_KODE','like','5.1.8%');
        })->sum('BTL_TOTAL');

        $penerimaan     = 0;
        $pengeluaran    = 0;
    	    $blv     = BL::where('BL_DELETED',0)->where('BL_VALIDASI',1)->sum('BL_PAGU');
			$b1v     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                        ->whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0);
                        })
                        ->sum('RINCIAN_TOTAL');
            $b2v     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                        ->whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0);
                        })
                        ->sum('RINCIAN_TOTAL');
            $b3v     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                        ->whereHas('bl',function($r){
                            $r->where('BL_VALIDASI',1)->where('BL_DELETED',0);
                        })
                        ->sum('RINCIAN_TOTAL');

            $bl      = Rincian::whereHas('bl',function($x){
                            $x->where('BL_DELETED',0);
                        })->sum('RINCIAN_TOTAL');
            $b1     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.1%');})
                        ->whereHas('bl',function($x){
                            $x->where('BL_DELETED',0);
                        })->sum('RINCIAN_TOTAL');
            $b2     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.2%');})
                        ->whereHas('bl',function($x){
                            $x->where('BL_DELETED',0);
                        })->sum('RINCIAN_TOTAL');
            $b3     = Rincian::whereHas('rekening',function($q){$q->where('REKENING_KODE','like','5.2.3%');})
                        ->whereHas('bl',function($x){
                            $x->where('BL_DELETED',0);
                        })->sum('RINCIAN_TOTAL');
    	$data 	= [ 'tahun'		=>$tahun,
    				'status'	=>$status,
                    'penerimaan'        =>$penerimaan,
                    'pengeluaran'        =>$pengeluaran,
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
    				'b3'		=>$b3];
    	return View('budgeting.ringkasan',$data);
    }
}
