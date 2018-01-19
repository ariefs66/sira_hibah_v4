<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use View;
use Response;
use DB;
use Auth;
use Carbon;
use App\Model\Program;
use App\Model\Kegiatan;
use App\Model\JenisGiat;
use App\Model\SumberDana;
use App\Model\Pagu;
use App\Model\Sasaran;
use App\Model\Tag;
use App\Model\Lokasi;
use App\Model\Satuan;
use App\Model\BTL;
use App\Model\Indikator;
use App\Model\Kunci;
use App\Model\Pekerjaan;
use App\Model\Rekening;
use App\Model\Komponen;
use App\Model\Rekom;
use App\Model\Rincian;
use App\Model\SKPD;
use App\Model\Pembiayaan;
use App\Model\AKB_Pembiayaan;
use App\Model\Tahapan;

class pembiayaanController extends Controller
{
public function index($tahun,$status){
		$skpd 		= SKPD::all();
    	$rekening 	= Rekening::where('REKENING_KODE','like','6%')->whereRaw('length("REKENING_KODE") = 11')
                    ->where('REKENING_TAHUN',$tahun)->get();
        return View('budgeting.pembiayaan.index',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd,'rekening'=>$rekening]);
    }

    public function submitAdd($tahun,$status){
    	$pembiayaan 	= new Pembiayaan;
    	$pembiayaan->PEMBIAYAAN_TAHUN		= $tahun;
    	$pembiayaan->REKENING_ID			= Input::get('REKENING_ID');
      $pembiayaan->PEMBIAYAAN_TOTAL   = Input::get('PEMBIAYAAN_TOTAL');
      $pembiayaan->PEMBIAYAAN_KETERANGAN  = Input::get('PEMBIAYAAN_KETERANGAN');
      $pembiayaan->PEMBIAYAAN_DASHUK  = Input::get('PEMBIAYAAN_DASHUK');
      $pembiayaan->TIME_UPDATED  = Carbon\Carbon::now();
      $pembiayaan->USER_UPDATED   = Auth::user()->id;
      $pembiayaan->IP_UPDATED   = $_SERVER['REMOTE_ADDR'];
    	$pembiayaan->save();

    	return "Input Berhasil!";
    }

    public function submitEdit(){

    }

    public function delete(){

    }

    //API
    public function getPembiayaan($tahun,$status){
      $data = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->JOIN('DATA.users','users.id','=','DAT_PEMBIAYAAN.USER_UPDATED')
              ->where('PEMBIAYAAN_TAHUN',$tahun)->orderBy('REKENING_KODE')->get();
              //dd($data);  

    	$view 			= array();
      $no=1;
    	foreach ($data as $data) {
          
          if(Auth::user()->level == 8){
              $opsi = '<a onclick="return ubah(\''.$data->PEMBIAYAAN_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a> <a onclick="return hapus(\''.$data->PEMBIAYAAN_ID.'\')"><i class="fa fa-close"></i>Hapus</a>';
              $akb = '<div class="action visible pull-right"><a href="/main/'.$tahun.'/'.$status.'/pembiayaan/akb/'.$data->SKPD_ID.'" class="action-edit" target="_blank"><i class="mi-edit"></i></a></div>';
            }else {
              $opsi ='-';
              $akb ='-';
            }
          
          
    		array_push($view, array( 
                                 'ID'			               =>$data->PEMBIAYAAN_ID,
                                 'NO'                   =>$no,
                                 'SKPD'                =>$data->name,
                								 'REKENING_KODE'	       =>$data->REKENING_KODE,
                                 'REKENING_NAMA'         =>$data->REKENING_NAMA,
                                 'PEMBIAYAAN_DASHUK'     =>$data->PEMBIAYAAN_DASHUK,
                								 'PEMBIAYAAN_TOTAL'      =>number_format($data->PEMBIAYAAN_TOTAL,0,'.',','),
                                 'PEMBIAYAAN_KETERANGAN' =>$data->PEMBIAYAAN_KETERANGAN,
                                 'OPSI'                  =>$opsi,
                                 'AKB'                   =>$akb,
                                 
                               ));
        $no++;
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
   	}

    public function edit($tahun,$status,$id){
        $data   = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')->where('PEMBIAYAAN_TAHUN',$tahun)->where('PEMBIAYAAN_ID',$id)->first();

        $data->REKENING_KODE   = $data->REKENING_KODE;
        $data->REKENING_NAMA   = $data->REKENING_NAMA;
        $data->PEMBIAYAAN_KETERANGAN        = $data->PEMBIAYAAN_KETERANGAN;
        $data->PEMBIAYAAN_DASHUK        = $data->PEMBIAYAAN_DASHUK;
        $data->PEMBIAYAAN_TOTAL        = $data->PEMBIAYAAN_TOTAL;
      
      return Response::JSON($data);
    }


    public function update($tahun,$status){ 

        Pembiayaan::where('PEMBIAYAAN_ID',Input::get('PEMBIAYAAN_ID'))
                        ->update([
                            'PEMBIAYAAN_DASHUK'         => Input::get('PEMBIAYAAN_DASHUK'),
                            'PEMBIAYAAN_KETERANGAN'     => Input::get('PEMBIAYAAN_KETERANGAN'),
                            'PEMBIAYAAN_TOTAL'          => Input::get('PEMBIAYAAN_TOTAL')
                            /*'USER_UPDATED'              => Auth::user()->id,
                            'TIME_UPDATED'              => Carbon\Carbon::now()*/
                            ]); 

        return ("sukses mengubah pembiayaan");                              

    }

    public function hapus(){
        pembiayaan::where('PEMBIAYAAN_ID',Input::get('PEMBIAYAAN_ID'))->delete();
        return "Hapus Berhasil!";
    }


     public function showAKB($tahun,$status,$id){
        $now = date('Y-m-d H:m:s');
        if($status == 'murni')
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->orderBy('TAHAPAN_ID','desc')->first();
        else
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->orderBy('TAHAPAN_ID','desc')->first();
        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }
        if($status == 'murni') {
          $pen  = Pembiayaan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
                    ->leftjoin('BUDGETING.DAT_AKB_PEMBIAYAAN',function($join){
                        $join->on('DAT_AKB_PEMBIAYAAN.PEMBIAYAAN_ID','=','DAT_PEMBIAYAAN.PEMBIAYAAN_ID')->on('DAT_AKB_PEMBIAYAAN.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID');
                    })
                    ->where('DAT_PEMBIAYAAN.SKPD_ID',$id)
                    ->orderBy("REKENING_NAMA")
                    ->selectRaw(' "DAT_PEMBIAYAAN"."PEMBIAYAAN_ID", "DAT_PEMBIAYAAN"."REKENING_ID", "REKENING_NAMA", "PEMBIAYAAN_TOTAL" AS total, "AKB_JAN", "AKB_FEB", "AKB_MAR", "AKB_APR", "AKB_MEI", "AKB_JUN", "AKB_JUL", "AKB_AUG", "AKB_SEP", "AKB_OKT", "AKB_NOV", "AKB_DES" ')
                    ->get();                              
        }
        else 
          $pen='';

          $skpd         = SKPD::where('SKPD_ID',$id)->first();


        if($status == 'murni')
         return View('budgeting.pembiayaan.akb-pembiayaan',['tahun'=>$tahun,'status'=>$status,'pen'=>$pen,'PEMBIAYAAN_ID'=>$id, 'thp'=>$thp, 'skpd'=>$skpd ]);
        else
         return View('budgeting.pembiayaan.akb-pembiayaan',['tahun'=>$tahun,'status'=>$status,'pen'=>$pen,'PEMBIAYAAN_ID'=>$id, 'thp'=>$thp, 'skpd'=>$skpd ]);
    }

     public function showDataAKB($tahun,$status,$id){
        $now = date('Y-m-d H:m:s');
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->orderBy('TAHAPAN_ID','desc')->first();
        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }

        $data = Pembiayaan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
                    ->leftjoin('BUDGETING.DAT_AKB_PEMBIAYAAN',function($join){
                        $join->on('DAT_AKB_PEMBIAYAAN.PEMBIAYAAN_ID','=','DAT_PEMBIAYAAN.PEMBIAYAAN_ID')->on('DAT_AKB_PEMBIAYAAN.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID');
                    })
                    ->where('DAT_PEMBIAYAAN.SKPD_ID',$id)
                    ->orderBy("REKENING_NAMA")
                    ->selectRaw(' "DAT_PEMBIAYAAN"."PEMBIAYAAN_ID", "DAT_PEMBIAYAAN"."REKENING_ID", "REKENING_NAMA", "PEMBIAYAAN_TOTAL" AS total, "AKB_JAN", "AKB_FEB", "AKB_MAR", "AKB_APR", "AKB_MEI", "AKB_JUN", "AKB_JUL", "AKB_AUG", "AKB_SEP", "AKB_OKT", "AKB_NOV", "AKB_DES" ')
                    ->get(); 
       
                 //   dd($data);

        $view       = array();
        $i         = 1;
        
        foreach ($data as $data) {

            $getAkb = AKB_Pembiayaan::where('PEMBIAYAAN_ID',$data->PEMBIAYAAN_ID)->where('REKENING_ID',$data->REKENING_ID)->value('AKB_PEMBIAYAAN_ID');            

            if(($thp == 1 or Auth::user()->level == 8 ) and Auth::user()->active == 5){
                if(empty($getAkb) ){
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return ubah(\''.$data->PEMBIAYAAN_ID.'\',\''.$data->REKENING_ID.'\')"><i class="fa fa-pencil-square"></i>Tambah</a></li>
                    <li class="divider"></li><li><a onclick="return info(\''.$data->REKENING_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                }else{
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li><a onclick="return ubah(\''.$data->PEMBIAYAAN_ID.'\',\''.$data->REKENING_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a></li>
                <li><a onclick="return hapus(\''.$data->PEMBIAYAAN_ID.'\',\''.$data->REKENING_ID.'\')"><i class="fa fa-pencil-square"></i>Hapus</a></li>
                <li class="divider"></li><li><a onclick="return info(\''.$data->REKENING_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                }
            }else{
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return info(\''.$data->REKENING_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';                
            }

            if(Auth::user()->level == 8){
                 $checkbox = '<div class="m-t-n-lg">
                                  <label class="i-checks">
                                    <input type="checkbox" value="'.$data->REKENING_ID.'" class="cb" id="cb-'.$data->REKENING_ID.'"/><i></i>
                                  </label>
                            </div>';
                $no        = $checkbox.$no;

            }

            $tri1 = $data->AKB_JAN + $data->AKB_FEB + $data->AKB_MAR;
            $tri2 = $data->AKB_APR + $data->AKB_MEI + $data->AKB_JUN;
            $tri3 = $data->AKB_JUL + $data->AKB_AUG + $data->AKB_SEP;
            $tri4 = $data->AKB_OKT + $data->AKB_NOV + $data->AKB_DES;

            array_push($view, array( 'NO'             =>$no,
                                     'REKENING'       =>$data->REKENING_KODE.'<br><p class="text-orange">'.$data->REKENING_NAMA.'</p>',
                                     //'TOTAL'          =>$data->total,
                                     'TOTAL'     =>number_format($data->total,0,'.',','),
                                     'JANUARI'        =>number_format($data->AKB_JAN,0,'.',','),
                                     'FEBRUARI'       =>number_format($data->AKB_FEB,0,'.',','),
                                     'MARET'          =>number_format($data->AKB_MAR,0,'.',','),
                                     'APRIL'          =>number_format($data->AKB_APR,0,'.',','),
                                     'MEI'            =>number_format($data->AKB_MEI,0,'.',','),
                                     'JUNI'           =>number_format($data->AKB_JUN,0,'.',','),
                                     'JULI'           =>number_format($data->AKB_JUL,0,'.',','),
                                     'AGUSTUS'        =>number_format($data->AKB_AUG,0,'.',','),
                                     'SEPTEMBER'      =>number_format($data->AKB_SEP,0,'.',','),
                                     'OKTOBER'        =>number_format($data->AKB_OKT,0,'.',','),
                                     'NOVEMBER'       =>number_format($data->AKB_NOV,0,'.',','),
                                     'DESEMBER'       =>number_format($data->AKB_DES,0,'.',','),
                                     'TRIWULAN1'      =>number_format($tri1,0,'.',','),
                                     'TRIWULAN2'      =>number_format($tri2,0,'.',','),
                                     'TRIWULAN3'      =>number_format($tri3,0,'.',','),
                                     'TRIWULAN4'      =>number_format($tri4,0,'.',','),
                                 ));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
        return $view;
    }

     public function detailAKB($tahun,$status,$pembiayaan_id,$rek_id){
        if($status == 'murni'){

             $data = Pembiayaan::join('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
                    ->leftjoin('BUDGETING.DAT_AKB_PEMBIAYAAN',function($join){
                        $join->on('DAT_AKB_PEMBIAYAAN.PEMBIAYAAN_ID','=','DAT_PEMBIAYAAN.PEMBIAYAAN_ID')->on('DAT_AKB_PEMBIAYAAN.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID');
                    })
                    ->where('DAT_PEMBIAYAAN.PEMBIAYAAN_ID',$pembiayaan_id)
                    ->where('DAT_PEMBIAYAAN.REKENING_ID',$rek_id)
                    ->orderBy("REKENING_NAMA")
                    ->selectRaw(' "DAT_PEMBIAYAAN"."PEMBIAYAAN_ID", "DAT_PEMBIAYAAN"."REKENING_ID", "REKENING_KODE", "REKENING_NAMA", "PEMBIAYAAN_TOTAL" AS total, "AKB_JAN", "AKB_FEB", "AKB_MAR", "AKB_APR", "AKB_MEI", "AKB_JUN", "AKB_JUL", "AKB_AUG", "AKB_SEP", "AKB_OKT", "AKB_NOV", "AKB_DES" ')
                    ->first();

        }

        $out    = [ //'DATA'          => $data,
                    'REKENING_KODE' => $data->REKENING_KODE,
                    'REKENING_NAMA' => $data->REKENING_NAMA,
                    'TOTAL'         => $data->total,
                    'TOTAL_VIEW'    => number_format($data->total,0,'.',','),
                    (empty($data->AKB_JAN))?$jan=0:$jan=$data->AKB_JAN,
                    (empty($data->AKB_FEB))?$feb=0:$feb=$data->AKB_FEB,
                    (empty($data->AKB_MAR))?$mar=0:$mar=$data->AKB_MAR,
                    (empty($data->AKB_APR))?$apr=0:$apr=$data->AKB_APR,
                    (empty($data->AKB_MEI))?$mei=0:$mei=$data->AKB_MEI,
                    (empty($data->AKB_JUN))?$jun=0:$jun=$data->AKB_JUN,
                    (empty($data->AKB_JUL))?$jul=0:$jul=$data->AKB_JUL,
                    (empty($data->AKB_AUG))?$agu=0:$agu=$data->AKB_AUG,
                    (empty($data->AKB_SEP))?$sep=0:$sep=$data->AKB_SEP,
                    (empty($data->AKB_OKT))?$okt=0:$okt=$data->AKB_OKT,
                    (empty($data->AKB_NOV))?$nov=0:$nov=$data->AKB_NOV,
                    (empty($data->AKB_DES))?$des=0:$des=$data->AKB_DES,
                    'AKB_JAN'       => $jan,
                    'AKB_FEB'       => $feb,
                    'AKB_MAR'       => $mar,
                    'AKB_APR'       => $apr,
                    'AKB_MEI'       => $mei,
                    'AKB_JUN'       => $jun,
                    'AKB_JUL'       => $jul,
                    'AKB_AUG'       => $agu,
                    'AKB_SEP'       => $sep,
                    'AKB_OKT'       => $okt,
                    'AKB_NOV'       => $nov,
                    'AKB_DES'       => $des,
                    'REKENING_ID'   => $data->REKENING_ID,
                    'PEMBIAYAAN_ID' => $data->PEMBIAYAAN_ID,
                    ];
        return $out;
    }


     public function submitAKBEdit($tahun,$status){
        if($status == 'murni') {
            $akb = AKB_Pembiayaan::where('PEMBIAYAAN_ID',Input::get('id_pembiayaan'))
                         ->where('REKENING_ID',Input::get('rek_id'))->value('AKB_PEMBIAYAAN_ID');

            if(empty($akb)){
                $akb = new AKB_Pembiayaan;
                $akb->PEMBIAYAAN_ID      = Input::get('id_pembiayaan');
                $akb->REKENING_ID        = Input::get('rek_id');
                $akb->AKB_JAN            = Input::get('jan');
                $akb->AKB_FEB            = Input::get('feb');
                $akb->AKB_MAR            = Input::get('mar');
                $akb->AKB_APR            = Input::get('apr');
                $akb->AKB_MEI            = Input::get('mei');
                $akb->AKB_JUN            = Input::get('jun');
                $akb->AKB_JUL            = Input::get('jul');
                $akb->AKB_AUG            = Input::get('agu');
                $akb->AKB_SEP            = Input::get('sep');
                $akb->AKB_OKT            = Input::get('okt');
                $akb->AKB_NOV            = Input::get('nov');
                $akb->AKB_DES            = Input::get('des');
                $akb->USER_CREATED       = Auth::user()->id;
                $akb->TIME_CREATED       = Carbon\Carbon::now();
                $akb->IP_CREATED         = $_SERVER['REMOTE_ADDR'];
                $akb->save(); 

                return 1; 

            }else{
                AKB_Pembiayaan::where('PEMBIAYAAN_ID',Input::get('id_pembiayaan'))
                         ->where('REKENING_ID',Input::get('rek_id'))
                 ->update([
                        'AKB_JAN'        => Input::get('jan'),
                        'AKB_FEB'        => Input::get('feb'),
                        'AKB_MAR'        => Input::get('mar'),
                        'AKB_APR'        => Input::get('apr'),
                        'AKB_MEI'        => Input::get('mei'),
                        'AKB_JUN'        => Input::get('jun'),
                        'AKB_JUL'        => Input::get('jul'),
                        'AKB_AUG'        => Input::get('agu'),
                        'AKB_SEP'        => Input::get('sep'),
                        'AKB_OKT'        => Input::get('okt'),
                        'AKB_NOV'        => Input::get('nov'),
                        'AKB_DES'        => Input::get('des')
                        ]); 

                 return 1; 
            }                

             return 0; 
               
        }
        else return $this->submitAKBEditPerubahan($tahun,$status);
    }

    public function deleteAKB(){
        AKB_Pembiayaan::where('PEMBIAYAAN_ID',Input::get('PEMBIAYAAN_ID'))->where('REKENING_ID',Input::get('REKENING_ID'))->delete();
        return "Hapus Berhasil!";
    }

   	
}
