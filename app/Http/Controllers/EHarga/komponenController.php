<?php

namespace App\Http\Controllers\EHarga;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Carbon;
use App;
use Auth;
use View;
use Response;
use Session;
use QrCode;
use PDF;
use Excel;
use App\Model\Rekening;
use App\Model\Rincian;
use App\Model\Komponen;
use App\Model\KomponenMember;
use App\Model\Satuan;
use App\Model\KatKom;
use App\Model\UsulanKomponen;
use App\Model\SKPD;
use App\Model\User;
use App\Model\UserBudget;
use App\Model\Rekom;
use App\Model\DataDukung;
use App\Model\UsulanSurat;
use App\Model\Log;
class komponenController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function referensi($tahun,$status){
        $data   = ['tahun'  =>$tahun,
                   'status' =>$status];
        return View('budgeting.referensi.komponen',$data);
    }

    public function index($tahun){
        $rekening   = Rekening::where('REKENING_KODE','like','5%')
                            ->whereRaw('LENGTH("REKENING_KODE") = 11')
                            ->get();
        $satuan     = Satuan::all();
    	$data 	= ['tahun'	=>$tahun,'rekening'=>$rekening,'satuan'=>$satuan];
    	return View('eharga.komponen',$data);
    }

    public function getReferensi(Request $req, $tahun,$status,$kategori){
        //dd($req);
        $start = ($req->iDisplayStart == "")? 0 : $req->iDisplayStart;
        $length = ($req->iDisplayLength == "")? 10 : $req->iDisplayLength;
        $kategori = ($req->sSearch == "")? $kategori : urldecode($req->sSearch);
        $kondisi = 'KOMPONEN_NAMA';
        if (preg_match('/[.].*[0-9]|[0-9].*[.]/', $kategori))
        {
            $kondisi = 'KOMPONEN_KODE';
        }
    	$data 	= Komponen::where($kondisi,'like',$kategori.'%')
                            ->where('KOMPONEN_TAHUN',$tahun)
                            ->limit($length)
							->offset($start)
					    	->orderBy('KOMPONEN_KODE')
                            ->get();
        $display = $data->count();
        $count = Komponen::where($kondisi,'like',$kategori.'%')->where('KOMPONEN_TAHUN',$tahun)->get()->count();
        
    	$i = $start+1;
        $view = array();
        foreach ($data as $data) {
            if(substr(Auth::user()->mod,4,1) == 1 or substr(Auth::user()->mod,6,1) == 1){
            /*
            $aksi         = '<div class="action visible">
                                <a onclick="return getrekening(\''.$data->KOMPONEN_ID.'\')" data-toggle="tooltip" title="Rekening"><i class="mi-eye"></i></a>
                                <a onclick="return getuser(\''.$data->KOMPONEN_ID.'\')" data-toggle="tooltip" title="Pengguna"><i class="icon-bdg_people"></i></a>
                                <a onclick="return ubah(\''.$data->KOMPONEN_ID.'\')" data-toggle="tooltip" title="Ubah"><i class="mi-edit"></i></a>
                                <a onclick="return hapus(\''.$data->KOMPONEN_ID.'\')" data-toggle="tooltip" title="Hapus"><i class="mi-trash"></i></a>
                            </div>';
            */
            $aksi         = '<div class="action visible">
                                <a onclick="return getrekening(\''.$data->KOMPONEN_ID.'\')" data-toggle="tooltip" title="Rekening"><i class="mi-eye"></i></a>
                                <a onclick="return getuser(\''.$data->KOMPONEN_ID.'\')" data-toggle="tooltip" title="Pengguna"><i class="icon-bdg_people"></i></a>
                                <a onclick="return ubah(\''.$data->KOMPONEN_ID.'\')" data-toggle="tooltip" title="Ubah"><i class="mi-edit"></i></a>
                                <a onclick="return tambahrekening(\''.$data->KOMPONEN_ID.'\')" data-toggle="tooltip" title="Tambah Rekening"><i class="icon-bdg_setting2"></i></a>
                            </div>';
            if($data->KOMPONEN_KUNCI == 1) 
                    $kunci = '<label class="i-switch bg-success m-t-xs m-r"><input type="checkbox" onchange="return buka(\''.$data->KOMPONEN_ID.'\')" id="buka-'.$data->BL_ID.'"><i></i></label>';
                else
                    $kunci = '<label class="i-switch bg-success m-t-xs m-r"><input type="checkbox" onchange="return kunci(\''.$data->KOMPONEN_ID.'\')" id="kunci-'.$data->BL_ID.'" checked><i></i></label>';                            
            }else{
            $aksi         = '<div class="action visible">
                                <a onclick="return getrekening(\''.$data->KOMPONEN_ID.'\')" data-toggle="tooltip" title="Rekening"><i class="mi-eye"></i></a>
                                <a onclick="return getuser(\''.$data->KOMPONEN_ID.'\')" data-toggle="tooltip" title="Pengguna"><i class="icon-bdg_people"></i></a>
                            </div>';   
            if($data->KOMPONEN_KUNCI == 1) 
                    $kunci = '<span class="text text-danger"><i class="fa fa-lock"></i></span>';
                else
                    $kunci = '<span class="text text-success"><i class="fa fa-unlock"></i></span>';
            }

            array_push($view, array( 'NO'       			=>$i,
                                     'KOMPONEN_NAMA'       	=>$data->KOMPONEN_KODE.'<br><span class="text text-orange">'.$data->KOMPONEN_NAMA."</span>",
                                     'KOMPONEN_SPESIFIKASI' =>$data->KOMPONEN_SPESIFIKASI,
                                     'KOMPONEN_SATUAN' 		=>$data->KOMPONEN_SATUAN,
                                     'KUNCI'                =>$kunci,
                                     'AKSI'   				=>$aksi,
                                     'KOMPONEN_HARGA'    	=>number_format($data->KOMPONEN_HARGA,0,'.',',')));
            $i++;
        }
        $out = array("iTotalRecords" 		=> intval($display),
        "iTotalDisplayRecords"  => intval($count),"aaData"=>$view);      
        return Response::JSON($out);
    }

    public function getrekening($tahun,$status,$komponen){
    	$data 	= Rekom::where('KOMPONEN_ID',$komponen)->get();
    	$i = 1;
        $view = array();
        foreach ($data as $data) {
            array_push($view, array( 'NO'       			=>$i,
                                     'REKENING_KODE'       	=>$data->rekening->REKENING_KODE,
                                     'REKENING_NAMA' 		=>$data->rekening->REKENING_NAMA));
            $i++;
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function getuser($tahun,$status,$komponen){
    	$data 	= Rincian::where('KOMPONEN_ID',$komponen)->groupBy('BL_ID','KOMPONEN_ID')->select('KOMPONEN_ID','BL_ID')->get();
    	$i = 1;
        $view = array();
        foreach ($data as $data) {
        	if(!empty($data->BL_ID)){
	            array_push($view, array( 'NO'       			=>$i,
	                                     'SKPD_KODE'       		=>$data->bl->subunit->skpd->SKPD_KODE,
	                                     'SKPD_NAMA' 			=>$data->bl->subunit->skpd->SKPD_NAMA));
        	}
            $i++;
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function delete($tahun){
        Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->delete();
        return 'Berhasil dihapus!';
    }

    public function getKategori($tahun,$jenis){
        $Komponen   = KatKom::where('KATEGORI_TAHUN',$tahun)
                            ->where('KATEGORI_KODE','like',$jenis.'%')
                            ->whereRaw('LENGTH("KATEGORI_KODE") = 17')
                            ->get();
        $view       = "";
        foreach($Komponen as $d){
                $view .= "<option value='".$d->KATEGORI_KODE."'>".$d->KATEGORI_KODE.' - '.$d->KATEGORI_NAMA."</option>";
        }
        return $view;        
    }

    public function detail($tahun,$id){

        $data = Komponen::where('KOMPONEN_TAHUN',$tahun)->where('KOMPONEN_ID',$id)->first();
        
        $out    = [ 'DATA'          => $data,
                    'KOMPONEN_TAHUN' => $data->KOMPONEN_TAHUN,
                    'KOMPONEN_KODE' => $data->KOMPONEN_KODE,
                    'KOMPONEN_NAMA' => $data->KOMPONEN_NAMA,
                    'KOMPONEN_SPEK' => $data->KOMPONEN_SPESIFIKASI,
                    'KOMPONEN_HARGA'=> $data->KOMPONEN_HARGA,
                    'KOMPONEN_SATUAN'=> $data->KOMPONEN_SATUAN,
                    'KOMPONEN_JENIS'=> substr($data->KOMPONEN_KODE,0,1)
                  ];
        return $out;
    }

    public function detailrekom($tahun,$id){

        $data = Komponen::where('KOMPONEN_TAHUN',$tahun)->where('KOMPONEN_ID',$id)->first();
        
        $out    = [ 'DATA'          => $data,
                    'KOMPONEN_TAHUN' => $data->KOMPONEN_TAHUN,
                    'KOMPONEN_KODE' => $data->KOMPONEN_KODE,
                    'KOMPONEN_NAMA' => $data->KOMPONEN_NAMA,
                    'KOMPONEN_SPEK' => $data->KOMPONEN_SPESIFIKASI,
                    'KOMPONEN_HARGA'=> $data->KOMPONEN_HARGA,
                    'KOMPONEN_SATUAN'=> $data->KOMPONEN_SATUAN,
                    'KOMPONEN_JENIS'=> substr($data->KOMPONEN_KODE,0,1)
                  ];
        return $out;
    }

    public function submit($tahun){
        $kode       = Komponen::where('KOMPONEN_KODE','like',Input::get('KOMPONEN_KODE').'%')
                                ->where('KOMPONEN_KODE','not like','%999')
                                ->orderBy('KOMPONEN_KODE','desc')
                                ->value('KOMPONEN_KODE');
        if($kode){
            $last   = substr($kode,18,3)+1;
            if($last < 10) $kode = Input::get('KOMPONEN_KODE').'.00'.$last;
            elseif($last < 100) $kode = Input::get('KOMPONEN_KODE').'.0'.$last;
            else $kode = Input::get('KOMPONEN_KODE').'.'.$last;
        }else{
            $kode   = Input::get('KOMPONEN_KODE').'.001';
        }


        $k  = new Komponen;
        $k->KOMPONEN_KODE           = $kode;
        $k->KOMPONEN_NAMA           = Input::get('KOMPONEN_NAMA');
        $k->KOMPONEN_SPESIFIKASI    = Input::get('KOMPONEN_SPESIFIKASI');
        $k->KOMPONEN_HARGA          = Input::get('KOMPONEN_HARGA');
        $k->KOMPONEN_SATUAN         = Input::get('KOMPONEN_SATUAN');
        $k->KOMPONEN_TAHUN          = $tahun;
        $k->TIME_CREATED            = Carbon\Carbon::now();
        $k->save();

        $id         = Komponen::where('KOMPONEN_TAHUN',$tahun)->where('KOMPONEN_KODE',$kode)->value('KOMPONEN_ID');
        $rekening    = Input::get('REKENING_ID');

        foreach($rekening as $r){
            $rekom  = new Rekom;
            $rekom->KOMPONEN_ID     = $id;
            $rekom->REKENING_ID     = $r;
            $rekom->REKOM_TAHUN     = $tahun;
            $rekom->save();
        }
        return 'Simpan Berhasil!';
    }

    public function submitubah($tahun){

        Komponen::where('KOMPONEN_TAHUN',Input::get('KOMPONEN_TAHUN'))->where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->where('KOMPONEN_KODE',Input::get('KOMPONEN_KODE'))->update([
        'KOMPONEN_NAMA'       => Input::get('KOMPONEN_NAMA'),
        'KOMPONEN_SPESIFIKASI'=> Input::get('KOMPONEN_SPESIFIKASI'),
        'KOMPONEN_SATUAN'     => Input::get('KOMPONEN_SATUAN'),
        'TIME_UPDATED'        => Carbon\Carbon::now(),
        'KOMPONEN_HARGA'      => Input::get('KOMPONEN_HARGA')]);
        
        $log        = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Mengubah Komponen';
        $log->LOG_DETAIL                        = 'KOMPONEN#'.Input::get('KOMPONEN_ID');
        $log->save();

        return 'Simpan Berhasil!';
    }

    public function submitrekom($tahun){
        $rekening    = Input::get('REKENING_ID');
        foreach($rekening as $r){
            $getrekom = Rekom::where('REKOM_TAHUN',$tahun)->where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->where('REKENING_ID',$r)->value('REKOM_ID');
            
            if(empty($getrekom)){
                $rekom  = new Rekom;
                $rekom->KOMPONEN_ID     = Input::get('KOMPONEN_ID');
                $rekom->REKENING_ID     = $r;
                $rekom->REKOM_TAHUN     = $tahun;
                $rekom->save();

                $log        = new Log;
                $log->LOG_TIME                          = Carbon\Carbon::now();
                $log->USER_ID                           = Auth::user()->id;
                $log->LOG_ACTIVITY                      = 'Menambah Rekening Komponen';
                $log->LOG_DETAIL                        = 'KOMPONEN#'.Input::get('KOMPONEN_ID').'#REKENING#'.$r;
                $log->save();
            }
        }
        return 'Simpan Berhasil!';
    }

    public function kunci($tahun,$kunci){
        Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->update(['KOMPONEN_KUNCI'=>$kunci]);
        return 'Berhasil !';
    }

    public function uploadHSPK($tahun){
        $fileupload     = Input::file('file_hspk');
        $data       = Excel::selectSheetsByIndex(0)->load($fileupload,function($reader){
                        $reader->limit(10000);
                        $reader->select(array('nomor','uraian','koef','satuan','harga','jumlah','rekening'));                        
                    })->get();
        $komponen   = "";
        foreach ($data as $data) {
            if(substr($data->nomor,0,1)=="2"){
                $komp       = new Komponen;
                $komp->KOMPONEN_TAHUN       = $tahun;
                $komp->KOMPONEN_KODE        = $data->nomor;
                $komp->KOMPONEN_NAMA        = $data->uraian;
                $komp->KOMPONEN_HARGA       = $data->jumlah;
                $komp->KOMPONEN_SATUAN      = $data->satuan;
                $komp->KOMPONEN_KUNCI       = 0;
                $komp->TIME_CREATED         = Carbon\Carbon::now();
                $komp->save();

                $komponen   = Komponen::where('KOMPONEN_TAHUN',$tahun)
                                        ->where('KOMPONEN_KODE',$data->nomor)
                                        ->value('KOMPONEN_ID');

                $rekening   = Rekening::where('REKENING_TAHUN',$tahun)
                                        ->where('REKENING_KODE',$data->rekening)
                                        ->value('REKENING_ID');
                if($komponen){
                    $rekom  = new Rekom;
                    $rekom->KOMPONEN_ID     = $komponen;
                    $rekom->REKENING_ID     = $rekening;
                    $rekom->REKOM_TAHUN     = $tahun;
                    $rekom->save();
                }
            }
            if(substr($data->nomor,0,1)=="1"){
                $member     = new KomponenMember;
                $member->KOMPONEN_ID        = $komponen;
                $member->MEMBER_KOEF        = $data->koef;
                $member->MEMBER_SATUAN      = $data->satuan;
                $member->MEMBER_HARGA       = $data->harga;
                $member->MEMBER_JUMLAH      = $data->jumlah;
                $member->KOMPONEN_URAIAN    = $data->uraian;
                $member->KOMPONEN_KODE      = $data->nomor;
                $member->save();
            }
        }

        return Redirect('harga/'.$tahun.'/komponen');
    }

    public function uploadASB($tahun){
        $fileupload     = Input::file('file_asb');
        $data       = Excel::selectSheetsByIndex(0)->load($fileupload,function($reader){
                        $reader->limit(10000);
                        $reader->select(array('kode','uraian','koef','satuan','harga','jumlah','rekening'));                        
                    })->get();
        $komponen = NULL;
        foreach ($data as $data) {
            $len    = strlen($data->kode);
            //if(substr($data->kode, $len-1,1)*1 != 0){
            if(substr($data->kode, $len-1,1) != 'A'){
                $komp       = new Komponen;
                $komp->KOMPONEN_TAHUN       = $tahun;
                $komp->KOMPONEN_KODE        = $data->kode;
                $komp->KOMPONEN_NAMA        = $data->uraian;
                $komp->KOMPONEN_HARGA       = $data->jumlah;
                $komp->KOMPONEN_SATUAN      = $data->satuan;
                $komp->KOMPONEN_KUNCI       = 0;
                $komp->TIME_CREATED         = Carbon\Carbon::now();
                $komp->save();

                $komponen   = Komponen::where('KOMPONEN_TAHUN',$tahun)
                                        ->where('KOMPONEN_KODE',$data->kode)
                                        ->value('KOMPONEN_ID');
                
                $rekening   = Rekening::where('REKENING_TAHUN',$tahun)
                                        ->where('REKENING_KODE',$data->rekening)
                                        ->value('REKENING_ID');
                if($komponen){
                    $rekom  = new Rekom;
                    $rekom->KOMPONEN_ID     = $komponen;
                    $rekom->REKENING_ID     = $rekening;
                    $rekom->REKOM_TAHUN     = $tahun;
                    $rekom->save();
                }     
            }elseif($data->kode){
                $member     = new KomponenMember;
                $member->KOMPONEN_ID        = $komponen;
                $member->MEMBER_KOEF        = $data->koef;
                $member->MEMBER_SATUAN      = $data->satuan;
                $member->MEMBER_HARGA       = $data->harga;
                $member->MEMBER_JUMLAH      = $data->jumlah;
                $member->KOMPONEN_URAIAN    = $data->uraian;
                $member->KOMPONEN_KODE      = $data->kode;
                $member->save();
            }
        }
        return Redirect('harga/'.$tahun.'/komponen');
    }
}
