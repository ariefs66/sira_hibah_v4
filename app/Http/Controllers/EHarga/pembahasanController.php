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
use Redirect;
use Session;
use QrCode;
use PDF;
use Excel;
use App\Model\Rekening;
use App\Model\Rincian;
use App\Model\Komponen;
use App\Model\Satuan;
use App\Model\KatKom;
use App\Model\UsulanKomponen;
use App\Model\UsulanKomponenMember;
use App\Model\SKPD;
use App\Model\User;
use App\Model\UserBudget;
use App\Model\Rekom;
use App\Model\DataDukung;
use App\Model\UsulanSurat;
class pembahasanController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index($tahun){
        $data   = ['tahun'  =>$tahun];
        return View('eharga.pembahasan',$data);
    }

    public function detail($tahun,$id){
        $usulan   = UsulanKomponen::where('USULAN_ID',$id)->first();
        $member     = UsulanKomponenMember::where('USULAN_ID',$id)->get();
        $data   = ['tahun'  =>$tahun,'usulan'=>$usulan,'member'=>$member];
        return View('eharga.pembahasandetail',$data);        
    }

    public function getdata($tahun){
        $data   = UsulanKomponen::where('USULAN_TAHUN',$tahun)
                                ->where('USULAN_POSISI',9)
                                ->whereHas('katkom',function($q){
                                    $q->where('KATEGORI_KODE','like','2%')->orwhere('KATEGORI_KODE','like','3%');
                                })->orderBy('USULAN_ID')->get();
        
        $i      = 1;
        $view   = array();

        foreach ($data as $data) {
            $post = '';$stat = '';
            if(empty($data->DD_ID)){
                $dd     = '';
            }else{
                $dd         = '<a href="/uploads/komponen/'.$tahun.'/'.$data->datadukung->DD_PATH.'/dd.pdf" target="_blank"><i class="fa fa-file-pdf-o"></i></a>';
            }
            if(empty($data->REKENING_ID)) $rekening = '-';
            else $rekening = $data->rekening->REKENING_KODE.'<br>'.$data->rekening->REKENING_NAMA;
            
            $tipe = '';
            if($data->USULAN_TYPE == 1) $tipe           = 'Komponen Baru';
            elseif($data->USULAN_TYPE == 2) $tipe       = 'Ubah Komponen';
            elseif($data->USULAN_TYPE == 3) $tipe       = 'Tambah Rekening';
            
            if($data->USULAN_POSISI == 0) $posisi       = 'Rencana';
            elseif($data->USULAN_POSISI == 1)$posisi    = 'Pengajuan';
            elseif($data->USULAN_POSISI == 2)$posisi    = 'Verifikasi';
            elseif($data->USULAN_POSISI == 3)$posisi    = 'Validasi';
            elseif($data->USULAN_POSISI == 4 and $data->SURAT_ID == "")$posisi    = 'Surat';
            elseif($data->USULAN_POSISI == 4 and $data->SURAT_ID != "")$posisi    = 'Disposisi 1';
            elseif($data->USULAN_POSISI == 5)$posisi    = 'Disposisi 2';
            elseif($data->USULAN_POSISI == 6)$posisi    = 'Disposisi 3';
            elseif($data->USULAN_POSISI == 7)$posisi    = 'Posting';
            elseif($data->USULAN_POSISI == 8)$posisi    = 'Ebudgeting';
            elseif($data->USULAN_POSISI == 9)$posisi    = 'Pembahasan';
            $pd     = UserBudget::where('USER_ID',$data->USER_CREATED)->first();

            if(Auth::user()->level == 0){
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a href="javascript:;"><i class="fa fa-check"></i> Terima</a></li><li><a href="javascript:;"><i class="fa fa-close"></i> Tolak</a></li>';
                $no     .= '</ul></div>';
            }
            
            array_push($view, array( 'NO'       =>$i."<br/>".$no,
                                     'PD'       =>$pd->skpd->SKPD_NAMA,
                                     'ID'       =>$data->USULAN_ID,
                                     'NAMA'     =>'<span class="text-success">'.$data->USULAN_NAMA." ".$dd."</span><br>".
                                                  $data->katkom->KATEGORI_KODE.' - '.$data->katkom->KATEGORI_NAMA.
                                                  "<br><p class='text-orange'>Spesifikasi : ".$data->USULAN_SPESIFIKASI.'</p>',
                                     'REKENING' =>$rekening,
                                     'TIPE'     =>$tipe,
                                     'POSISI'   =>$posisi,
                                     'HARGA'    =>number_format($data->USULAN_HARGA,2,'.',',')));
            $i++;
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function uploadHSPK($tahun){
        $btn    = Input::get('btn-simpan');
        $fileupload     = Input::file('file');
        $id             = Input::get('id');
        $catatan        = Input::get('catatan');
        $data       = Excel::selectSheetsByIndex(0)->load($fileupload,function($reader){
                        $reader->limit(10000);
                        $reader->select(array('nomor','uraian','koef','satuan','harga','jumlah','rekening'));                        
                    })->get();
        foreach ($data as $data) {
            if(substr($data->nomor,0,1)=="2"){
                if($btn == 1){
                    UsulanKomponen::where('USULAN_ID',$id)->update(['USULAN_HARGA'      => $data->jumlah,
                                                                    'USULAN_POSISI'     =>3,                    
                                                                    'KOMPONEN_KODE'     => $data->nomor,
                                                                    'USULAN_CATATAN'    => $data->catatan]);                    
                }else{
                    UsulanKomponen::where('USULAN_ID',$id)->update(['USULAN_HARGA'      => $data->jumlah,
                                                                    'KOMPONEN_KODE'     => $data->nomor,
                                                                    'USULAN_CATATAN'    => $data->catatan]);                    
                }

            }
            if(substr($data->nomor,0,1)=="1"){
                $komponen   = Komponen::where('KOMPONEN_KODE',$data->nomor)->value('KOMPONEN_ID');
                $member     = new UsulanKomponenMember;
                $member->USULAN_ID          = $id;
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

        return Redirect('harga/'.$tahun.'/usulan/pembahasan/detail/'.$id);
    }

    public function uploadASB($tahun){
        $btn    = Input::get('btn-simpan');
        $fileupload     = Input::file('file');
        $id             = Input::get('id');
        $catatan        = Input::get('catatan');        
        $data       = Excel::selectSheetsByIndex(0)->load($fileupload,function($reader){
                        $reader->limit(10000);
                        $reader->select(array('kode','uraian','koef','satuan','harga','jumlah','rekening'));                        
                    })->get();
        foreach ($data as $data) {
            $len    = strlen($data->kode);
            if(substr($data->kode, $len-1,1)*1 != 0){
                if($btn == 1){
                    UsulanKomponen::where('USULAN_ID',$id)->update(['USULAN_HARGA'      =>$data->jumlah,  
                                                                    'USULAN_POSISI'     =>3,
                                                                    'KOMPONEN_KODE'     => $data->kode,
                                                                    'USULAN_CATATAN'    => $catatan]);                    
                }else{
                    UsulanKomponen::where('USULAN_ID',$id)->update(['USULAN_HARGA'      =>$data->jumlah,  
                                                                    'KOMPONEN_KODE'     => $data->kode,
                                                                    'USULAN_CATATAN'    => $catatan]);                    
                }

            }else{
                // $komponen   = Komponen::where('KOMPONEN_KODE',$data->nomor)->value('KOMPONEN_ID');
                $member     = new UsulanKomponenMember;
                $member->USULAN_ID          = $id;
                $member->KOMPONEN_KODE      = $data->kode;
                $member->KOMPONEN_URAIAN    = $data->uraian;
                $member->MEMBER_KOEF        = $data->koef;
                $member->MEMBER_SATUAN      = $data->satuan;
                $member->MEMBER_HARGA       = $data->harga;
                $member->MEMBER_JUMLAH      = $data->jumlah;
                $member->save();
            }
        }
        return Redirect('harga/'.$tahun.'/usulan/pembahasan/detail/'.$id);
    }
}
