<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Carbon;
use Response;
use Auth;
use App\Model\Usulan;
use App\Model\UsulanReses;
use App\Model\Kamus;
use App\Model\UserBudget;
use App\Model\Komponen;
use App\Model\UserReses;
use App\Model\BL;
use App\Model\Rincian;
use App\Model\Log;
use App\Model\Subrincian;
use App\Model\Kegiatan;
use App\Model\Subunit;
use App\Model\SKPD;
use Illuminate\Support\Facades\Input;
class usulanController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index($tahun,$status){
        $skpd           = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');
        $usulan         = Usulan::whereHas('kamus',function($q) use($skpd){
                                $q->where('KAMUS_SKPD',$skpd);
                            })->where('USULAN_TUJUAN',1)->groupBy('KAMUS_ID')->select('KAMUS_ID')->get()->toArray();
        $kamus          = Kamus::where('KAMUS_SKPD',$skpd)->whereIn('KAMUS_ID',$usulan)->get();
        $idgiat         = Kamus::where('KAMUS_SKPD',$skpd)->select('KAMUS_KEGIATAN')->get()->toArray();
        $kegiatan       = Kegiatan::whereIn('KEGIATAN_ID',$idgiat)->get(); 
    	return View('budgeting.usulan.index',compact('tahun','status','kamus','kegiatan','skpd'));
    }

    public function getMusrenbang($tahun,$status){
        $skpd           = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');
        $dataKamus      = Kamus::where('KAMUS_SKPD',$skpd)->select('KAMUS_ID')->get()->toArray();
        $data           = Usulan::whereIn('KAMUS_ID',$dataKamus)->get();
        $i =  1;
        $view   = array();
        foreach ($data as $data) {
            if(Auth::user()->level == 2 and $data->USULAN_STATUS == 0) $aksi = '<div class="action visible"><a onclick="return submitMusren(\''.$data->USULAN_ID.'\')"><i class="fa fa-check"></i></a></div>';
            else $aksi      = '-';
            $keg = Kegiatan::where('KEGIATAN_ID',$data->kamus->KAMUS_KEGIATAN)->first();
            array_push($view, array( 'NO'               =>$i++,
                                     'PENGUSUL'         =>"RW ".$data->rw->RW_NAMA." Kel ".$data->rw->kelurahan->KEL_NAMA." Kec ".$data->rw->kelurahan->kecamatan->KEC_NAMA,
                                     'KAMUS'            =>$data->kamus->KAMUS_NAMA."<br>".$data->USULAN_VOLUME." ".$data->kamus->KAMUS_SATUAN,
                                     'KEGIATAN'         =>$keg->KEGIATAN_NAMA,
                                     'AKSI'             =>$aksi));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function getMusrenbangFilter($tahun,$status,$kamus,$giat){
        $skpd           = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');
        if($kamus != 'x' and $giat != 'x') 
            $dataKamus      = Kamus::where('KAMUS_SKPD',$skpd)->where('KAMUS_ID',$kamus)->where('KAMUS_KEGIATAN',$giat)->select('KAMUS_ID')->get()->toArray();
        elseif($kamus != 'x')
            $dataKamus      = Kamus::where('KAMUS_SKPD',$skpd)->where('KAMUS_ID',$kamus)->select('KAMUS_ID')->get()->toArray();
        elseif($giat != 'x')
            $dataKamus      = Kamus::where('KAMUS_SKPD',$skpd)->where('KAMUS_KEGIATAN',$giat)->select('KAMUS_ID')->get()->toArray();
        else $dataKamus      = Kamus::where('KAMUS_SKPD',$skpd)->select('KAMUS_ID')->get()->toArray();
        
        $data = Usulan::whereIn('KAMUS_ID',$dataKamus)->get();
        $i =  1;
        $view   = array();
        foreach ($data as $data) {
            if(Auth::user()->level == 2 and $data->USULAN_STATUS == 0) $aksi = '<div class="action visible"><a onclick="return submitMusren(\''.$data->USULAN_ID.'\')"><i class="fa fa-check"></i></a></div>';
            else $aksi      = '-';
            $keg = Kegiatan::where('KEGIATAN_ID',$data->kamus->KAMUS_KEGIATAN)->first();
            array_push($view, array( 'NO'               =>$i++,
                                     'PENGUSUL'         =>"RW ".$data->rw->RW_NAMA." Kel ".$data->rw->kelurahan->KEL_NAMA." Kec ".$data->rw->kelurahan->kecamatan->KEC_NAMA,
                                     'KAMUS'            =>$data->kamus->KAMUS_NAMA."<br>".$data->USULAN_VOLUME." ".$data->kamus->KAMUS_SATUAN,
                                     'KEGIATAN'         =>$keg->KEGIATAN_NAMA,
                                     'AKSI'             =>$aksi));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function getReses($tahun,$status){
        $skpd           = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID');
        $dataKamus      = Kamus::where('KAMUS_SKPD',$skpd)->select('KAMUS_ID')->get()->toArray();
        $data           = UsulanReses::whereIn('KAMUS_ID',$dataKamus)->where('USULAN_STATUS',0)->where('USULAN_DELETED',0)->get();
        $i =  1;
        $view   = array();
       // dd($data);
        foreach ($data as $data) {
            $anggaran   = $data->USULAN_VOLUME * Kamus::where('KAMUS_ID',$data->KAMUS_ID)->value('KAMUS_HARGA');
            $keg = Kegiatan::where('KEGIATAN_ID',$data->kamus->KAMUS_KEGIATAN)->first();
            array_push($view, array( 'ID'               =>$data->USULAN_ID,
                                     'NO'               =>$i++,
                                     'PENGUSUL'         =>$data->dewan->DEWAN_NAMA,
                                     'KAMUS'            =>$data->kamus->KAMUS_NAMA."<br>".$data->USULAN_VOLUME." ".$data->kamus->KAMUS_SATUAN,
                                     'KEGIATAN'         =>$keg->KEGIATAN_NAMA,
                                     'ANGGARAN'         =>number_format($anggaran,0,'.',',')));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function getRW($tahun,$status){
        $skpd           = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        $data           = Usulan::whereHas('rw',function($q) use($skpd){
                            $q->whereHas('kelurahan',function($r) use($skpd){
                                $r->whereHas('subunit',function($s) use($skpd){
                                    $s->where('SKPD_ID',$skpd);
                                });
                            });
                        })->whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','PIPPK');
                        })->groupBy('RW_ID')->select('RW_ID')->get();
        $i =  1;
        $kegiatan = '';
        $view   = array();
        foreach ($data as $data) {
            $total  = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','PIPPK');
                        })->where('USULAN_STATUS',0)->where('RW_ID',$data->RW_ID)->get();
            $sum    = 0;
            foreach($total as $t){
                $sum    += $t->USULAN_VOLUME * Kamus::where('KAMUS_ID',$t->KAMUS_ID)->value('KAMUS_HARGA');
            }
            if(Auth::user()->level == 2 and $data->USULAN_STATUS == 0) $aksi = '<div class="action visible"><a onclick="return submitRW(\''.$data->RW_ID.'\')"><i class="fa fa-check"></i></a></div>';
            else $aksi = '-';
            array_push($view, array('NO'=>$i++,
                                    'KELURAHAN'=>$data->rw->kelurahan->KEL_NAMA."<br>RW ".$data->rw->RW_NAMA,
                                    'TOTAL'=>number_format($sum,0,'.',','),
                                    'AKSI'=>$aksi));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function getLPM($tahun,$status){
        $skpd           = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        $data           = Usulan::whereHas('rw',function($q) use($skpd){
                            $q->whereHas('kelurahan',function($r) use($skpd){
                                $r->whereHas('subunit',function($s) use($skpd){
                                    $s->where('SKPD_ID',$skpd);
                                });
                            });
                        })->whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','LPM');
                        })->groupBy('RW_ID')->select('RW_ID')->get();
        $i =  1;
        $kegiatan = '';
        $view   = array();
        foreach ($data as $data) {
            $total  = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','LPM');
                        })->where('USULAN_STATUS',0)->where('RW_ID',$data->RW_ID)->get();
            $sum    = 0;
            foreach($total as $t){
                $sum    += $t->USULAN_VOLUME * Kamus::where('KAMUS_ID',$t->KAMUS_ID)->value('KAMUS_HARGA');
            }
            if(Auth::user()->level == 2 and $data->USULAN_STATUS == 0) $aksi = '<div class="action visible"><a onclick="return submitLPM(\''.$data->RW_ID.'\')"><i class="fa fa-check"></i></a></div>';
            else $aksi = '-';
            array_push($view, array('NO'=>$i++,
                                    'KELURAHAN'=>$data->rw->kelurahan->KEL_NAMA,
                                    'TOTAL'=>number_format($sum,0,'.',','),
                                    'AKSI'=>$aksi));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function getKarta($tahun,$status){
        $skpd           = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        $data           = Usulan::whereHas('rw',function($q) use($skpd){
                            $q->whereHas('kelurahan',function($r) use($skpd){
                                $r->whereHas('subunit',function($s) use($skpd){
                                    $s->where('SKPD_ID',$skpd);
                                });
                            });
                        })->whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','KARTA');
                        })->groupBy('RW_ID')->select('RW_ID')->get();
        $i =  1;
        $kegiatan = '';
        $view   = array();
        foreach ($data as $data) {
            $total  = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','KARTA');
                        })->where('USULAN_STATUS',0)->where('RW_ID',$data->RW_ID)->get();
            $sum    = 0;
            foreach($total as $t){
                $sum    += $t->USULAN_VOLUME * Kamus::where('KAMUS_ID',$t->KAMUS_ID)->value('KAMUS_HARGA');
            }
            if(Auth::user()->level == 2 and $data->USULAN_STATUS == 0) $aksi = '<div class="action visible"><a onclick="return submitKarta(\''.$data->RW_ID.'\')"><i class="fa fa-check"></i></a></div>';
            else $aksi = '-';
            array_push($view, array('NO'=>$i++,
                                    'KELURAHAN'=>$data->rw->kelurahan->KEL_NAMA,
                                    'TOTAL'=>number_format($sum,0,'.',','),
                                    'AKSI'=>$aksi));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function getPKK($tahun,$status){
        $skpd           = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        $data           = Usulan::whereHas('rw',function($q) use($skpd){
                            $q->whereHas('kelurahan',function($r) use($skpd){
                                $r->whereHas('subunit',function($s) use($skpd){
                                    $s->where('SKPD_ID',$skpd);
                                });
                            });
                        })->whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','PKK');
                        })->groupBy('RW_ID')->select('RW_ID')->get();
        $i =  1;
        $kegiatan = '';
        $view   = array();
        foreach ($data as $data) {
            $total  = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','PKK');
                        })->where('USULAN_STATUS',0)->where('RW_ID',$data->RW_ID)->get();
            $sum    = 0;
            foreach($total as $t){
                $sum    += $t->USULAN_VOLUME * Kamus::where('KAMUS_ID',$t->KAMUS_ID)->value('KAMUS_HARGA');
            }
            if(Auth::user()->level == 2 and $data->USULAN_STATUS == 0) $aksi = '<div class="action visible"><a onclick="return submitPKK(\''.$data->RW_ID.'\')"><i class="fa fa-check"></i></a></div>';
            else $aksi = '-';
            array_push($view, array('NO'=>$i++,
                                    'KELURAHAN'=>$data->rw->kelurahan->KEL_NAMA,
                                    'TOTAL'=>number_format($sum,0,'.',','),
                                    'AKSI'=>$aksi));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function setMusrenbang($tahun,$status){
        $idUsulan   = Input::get('USULAN_ID');
        $usulan     = Usulan::where('USULAN_ID',$idUsulan)->first();
        $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        $sub        = Subunit::where('SKPD_ID',$skpd)->select('SUB_ID')->get()->toArray();
        $belanja    = BL::where('KEGIATAN_ID',$usulan->kamus->KAMUS_KEGIATAN)->whereIn('SUB_ID',$sub)->value('BL_ID');
        $komponen   = Komponen::where('KOMPONEN_KODE',$usulan->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_ID');
            if($komponen){
                if($belanja){
                    $rincian    = new Rincian;
                    $rincian->BL_ID             = $belanja;
                    $rincian->KOMPONEN_ID       = $komponen;
                    $rincian->REKENING_ID       = 0;
                    $rincian->RINCIAN_PAJAK     = 0;
                    $rincian->RINCIAN_KETERANGAN= 'Musrenbang RW '.$usulan->rw->RW_NAMA.' KEL '.$usulan->rw->kelurahan->KEL_NAMA.' KEC '.$usulan->rw->kelurahan->kecamatan->KECAMATAN_NAMA.'#'.$usulan->USULAN_ID;
                    $rincian->RINCIAN_VOLUME    = $usulan->USULAN_VOLUME;
                    $rincian->RINCIAN_KOEFISIEN = $usulan->USULAN_VOLUME." ".$usulan->kamus->KAMUS_SATUAN;
                    $rincian->RINCIAN_TOTAL     = $usulan->USULAN_VOLUME * $usulan->kamus->KAMUS_HARGA;
                    $rincian->save();

                    Usulan::where('USULAN_ID',$usulan->USULAN_ID)->update(['USULAN_STATUS'=>1]);
                    BL::where('BL_ID',$belanja)->update(['BL_VALIDASI'=>0]);

                    $log            = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen Musrenbang';
                    $log->LOG_DETAIL                        = 'BL#'.$belanja;
                    $log->save();

                    $alert  = 1;
                }else{
                    $alert  = 2;
                }
            }else{
                $alert  = 0;
            }
        return $alert;
    }

    public function setReses($tahun,$status){
        $idUsulan   = Input::get('USULAN_ID');
        $usulan     = UsulanReses::where('USULAN_ID',$idUsulan)->first();
        $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        $sub        = Subunit::where('SKPD_ID',$skpd)->select('SUB_ID')->get()->toArray();
        $belanja    = BL::where('KEGIATAN_ID',$usulan->kamus->KAMUS_KEGIATAN)->whereIn('SUB_ID',$sub)->value('BL_ID');
        $komponen   = Komponen::where('KOMPONEN_KODE',$usulan->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_ID');
            if($komponen){
                if($belanja){
                    $rincian    = new Rincian;
                    $rincian->BL_ID             = $belanja;
                    $rincian->KOMPONEN_ID       = $komponen;
                    $rincian->REKENING_ID       = 0;
                    $rincian->RINCIAN_PAJAK     = 0;
                    $rincian->RINCIAN_KETERANGAN= 'Reses Dewan '.$usulan->dewan->DEWAN_NAMA.' Kec '.$usulan->kecamatan->KEC_NAMA.'#'.$usulan->USULAN_ID;
                    $rincian->RINCIAN_VOLUME    = $usulan->USULAN_VOLUME;
                    $rincian->RINCIAN_KOEFISIEN = $usulan->USULAN_VOLUME." ".$usulan->kamus->KAMUS_SATUAN;
                    $rincian->RINCIAN_TOTAL     = $usulan->USULAN_VOLUME * $usulan->kamus->KAMUS_HARGA;
                    $rincian->save();

                    UsulanReses::where('USULAN_ID',$usulan->USULAN_ID)->update(['USULAN_STATUS'=>2]);
                    BL::where('BL_ID',$belanja)->update(['BL_VALIDASI'=>0]);

                    $log            = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen Reses';
                    $log->LOG_DETAIL                        = 'BL#'.$belanja;
                    $log->save();

                    $alert  = 1;
                }else{
                    $alert  = 2;
                }
            }else{
                $alert  = 0;
            }
        return $alert;
    }

    public function setRW($tahun,$status){
        $rw             = Input::get('RW_ID');
        $data           = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','PIPPK');
                        })->where('USULAN_STATUS',0)->where('RW_ID',$rw)->get();
        $alert          = 0;
        foreach ($data as $data) {
            $belanja    = BL::where('KEGIATAN_ID',$data->kamus->KAMUS_KEGIATAN)->where('SUB_ID',$data->rw->kelurahan->SUB_ID)->value('BL_ID');
            $komponen   = Komponen::where('KOMPONEN_KODE',$data->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_ID');
            if($komponen){
                if($belanja){
                    $rincian    = new Rincian;
                    $rincian->BL_ID             = $belanja;
                    $rincian->KOMPONEN_ID       = $komponen;
                    $rincian->REKENING_ID       = 0;
                    $rincian->RINCIAN_PAJAK     = 0;
                    $rincian->RINCIAN_KETERANGAN= 'PIPPK RW '.$data->rw->RW_NAMA.'#'.$data->USULAN_ID;
                    $rincian->RINCIAN_VOLUME    = $data->USULAN_VOLUME;
                    $rincian->RINCIAN_KOEFISIEN = $data->USULAN_VOLUME." ".$data->kamus->KAMUS_SATUAN;
                    $rincian->RINCIAN_TOTAL     = $data->USULAN_VOLUME * $data->kamus->KAMUS_HARGA;
                    $rincian->save();

                    Usulan::where('USULAN_ID',$data->USULAN_ID)->update(['USULAN_STATUS'=>1]);
                    BL::where('BL_ID',$belanja)->update(['BL_VALIDASI'=>0]);

                    $log            = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen PIPPK RW';
                    $log->LOG_DETAIL                        = 'BL#'.$belanja;
                    $log->save();

                    $alert  = 1;
                }else{
                    $alert  = 2;
                }
            }
        }
        return $alert;
    }

    public function setPKK($tahun,$status){
        $rw             = Input::get('RW_ID');
        $data           = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','PKK');
                        })->where('USULAN_STATUS',0)->where('RW_ID',$rw)->get();
        $alert          = 0;
        foreach ($data as $data) {
            $belanja    = BL::where('KEGIATAN_ID',$data->kamus->KAMUS_KEGIATAN)->where('SUB_ID',$data->rw->kelurahan->SUB_ID)->value('BL_ID');
            $komponen   = Komponen::where('KOMPONEN_KODE',$data->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_ID');
            if($komponen){
                if($belanja){
                    $rincian    = new Rincian;
                    $rincian->BL_ID             = $belanja;
                    $rincian->KOMPONEN_ID       = $komponen;
                    $rincian->REKENING_ID       = 0;
                    $rincian->RINCIAN_PAJAK     = 0;
                    $rincian->RINCIAN_KETERANGAN= 'PIPPK PKK#'.$data->USULAN_ID;
                    $rincian->RINCIAN_VOLUME    = $data->USULAN_VOLUME;
                    $rincian->RINCIAN_KOEFISIEN = $data->USULAN_VOLUME." ".$data->kamus->KAMUS_SATUAN;
                    $rincian->RINCIAN_TOTAL     = $data->USULAN_VOLUME * $data->kamus->KAMUS_HARGA;
                    $rincian->save();

                    Usulan::where('USULAN_ID',$data->USULAN_ID)->update(['USULAN_STATUS'=>1]);
                    BL::where('BL_ID',$belanja)->update(['BL_VALIDASI'=>0]);

                    $log            = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen PIPPK RW';
                    $log->LOG_DETAIL                        = 'BL#'.$belanja;
                    $log->save();

                    $alert  = 1;
                }else{
                    $alert  = 2;
                }
            }
        }
        return $alert;
    }

    public function setLPM($tahun,$status){
        $rw             = Input::get('RW_ID');
        $data           = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','LPM');
                        })->where('USULAN_STATUS',0)->where('RW_ID',$rw)->get();
        $alert          = 0;
        foreach ($data as $data) {
            $belanja    = BL::where('KEGIATAN_ID',$data->kamus->KAMUS_KEGIATAN)->where('SUB_ID',$data->rw->kelurahan->SUB_ID)->value('BL_ID');
            $komponen   = Komponen::where('KOMPONEN_KODE',$data->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_ID');
            if($komponen){
                if($belanja){
                    $rincian    = new Rincian;
                    $rincian->BL_ID             = $belanja;
                    $rincian->KOMPONEN_ID       = $komponen;
                    $rincian->REKENING_ID       = 0;
                    $rincian->RINCIAN_PAJAK     = 0;
                    $rincian->RINCIAN_KETERANGAN= 'PIPPK LPM#'.$data->USULAN_ID;
                    $rincian->RINCIAN_VOLUME    = $data->USULAN_VOLUME;
                    $rincian->RINCIAN_KOEFISIEN = $data->USULAN_VOLUME." ".$data->kamus->KAMUS_SATUAN;
                    $rincian->RINCIAN_TOTAL     = $data->USULAN_VOLUME * $data->kamus->KAMUS_HARGA;
                    $rincian->save();

                    Usulan::where('USULAN_ID',$data->USULAN_ID)->update(['USULAN_STATUS'=>1]);
                    BL::where('BL_ID',$belanja)->update(['BL_VALIDASI'=>0]);

                    $log            = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen PIPPK RW';
                    $log->LOG_DETAIL                        = 'BL#'.$belanja;
                    $log->save();

                    $alert      = 1;
                }else{
                    $alert      = 2;
                }
            }
        }
        return $alert;
    }

    public function setKarta($tahun,$status){
        $rw             = Input::get('RW_ID');
        $data           = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','KARTA');
                        })->where('USULAN_STATUS',0)->where('RW_ID',$rw)->get();
        $alert          = 0;
        foreach ($data as $data) {
            $belanja    = BL::where('KEGIATAN_ID',$data->kamus->KAMUS_KEGIATAN)->where('SUB_ID',$data->rw->kelurahan->SUB_ID)->value('BL_ID');
            $komponen   = Komponen::where('KOMPONEN_KODE',$data->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_ID');
            if($komponen){
                if($belanja){
                    $rincian    = new Rincian;
                    $rincian->BL_ID             = $belanja;
                    $rincian->KOMPONEN_ID       = $komponen;
                    $rincian->REKENING_ID       = 0;
                    $rincian->RINCIAN_PAJAK     = 0;
                    $rincian->RINCIAN_KETERANGAN= 'PIPPK KARANG TARUNA#'.$data->USULAN_ID;
                    $rincian->RINCIAN_VOLUME    = $data->USULAN_VOLUME;
                    $rincian->RINCIAN_KOEFISIEN = $data->USULAN_VOLUME." ".$data->kamus->KAMUS_SATUAN;
                    $rincian->RINCIAN_TOTAL     = $data->USULAN_VOLUME * $data->kamus->KAMUS_HARGA;
                    $rincian->save();

                    Usulan::where('USULAN_ID',$data->USULAN_ID)->update(['USULAN_STATUS'=>1]);
                    BL::where('BL_ID',$belanja)->update(['BL_VALIDASI'=>0]);

                    $log            = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen PIPPK RW';
                    $log->LOG_DETAIL                        = 'BL#'.$belanja;
                    $log->save();

                    $alert  = 1;
                }else{
                    $alert  = 2;
                }
            }
        }
        return $alert;
    }

    public function getResesDetail($tahun,$status,$id){
        $data   = UsulanReses::where('USULAN_ID',$id)->first();
        $view   = ['dewan'      => $data->dewan->DEWAN_NAMA,
                   'fraksi'     => $data->dewan->fraksi->FRAKSI_NAMA,
                   'dapil'      => $data->dewan->dapil->DAPIL_NAMA,
                   'kamus'      => $data->kamus->KAMUS_NAMA,
                   'lokasi'     => $data->USULAN_ALAMAT,
                   'urgensi'    => $data->USULAN_URGENSI,
                   'volume'     => $data->USULAN_VOLUME." ".$data->kamus->KAMUS_SATUAN,
                   'anggaran'   => "Rp. ".number_format($data->USULAN_VOLUME * $data->kamus->KAMUS_HARGA,0,'.',',')];
        return Response::JSON($view);
    }

    public function tolakReses($tahun,$status){
        UsulanReses::where('USULAN_ID',Input::get('USULAN_ID'))->update(['USULAN_STATUS'=>1,'USULAN_ALASAN'=>Input::get('USULAN_ALASAN')]);
        return 'Berhasil Tolak!';
    }

    public function trfRW($tahun,$status,$kec){
        $kec            = SKPD::where('SKPD_KODE',$kec)->value('SKPD_ID');
        // $data           = Usulan::whereHas('kamus',function($q){
        //                     $q->where('KAMUS_JENIS','PIPPK');
        //                 })->whereHas('rw',function($x) use($kec){
        //                     $x->whereHas('kelurahan',function($y) use($kec){
        //                         $y->whereHas('subunit', function($z) use($kec){
        //                             $z->where('SKPD_ID',$kec);
        //                         });
        //                     });
        //                 })->where('USULAN_STATUS',0)->get();
        $data           = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','PIPPK');
                        })->where('USULAN_STATUS',0)->get();

        $masuk                  = 0;
        $nonkomponen            = 0;
        $nonbelanja             = 0;
        foreach ($data as $data) {
            $belanja    = BL::where('KEGIATAN_ID',$data->kamus->KAMUS_KEGIATAN)->where('SUB_ID',$data->rw->kelurahan->SUB_ID)->value('BL_ID');
            $komponen   = Komponen::where('KOMPONEN_KODE',$data->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_ID');
            if($komponen){
                if($belanja){
                    $subrincian     = Subrincian::where('BL_ID',$belanja)->where('SUBRINCIAN_NAMA',$data->kamus->KAMUS_NAMA.'- RW'.$data->rw->RW_NAMA)->value('SUBRINCIAN_ID');
                    if(empty($subrincian)){
                        $sub = new Subrincian;
                        $sub->SUBRINCIAN_NAMA    = $data->kamus->KAMUS_NAMA.'- RW'.$data->rw->RW_NAMA;
                        $sub->USER_CREATED       = Auth::user()->id;
                        $sub->BL_ID              = $belanja;
                        $sub->save();
                        $subrincian   = Subrincian::where('BL_ID',$belanja)->where('SUBRINCIAN_NAMA',$data->kamus->KAMUS_NAMA.'- RW'.$data->rw->RW_NAMA)->value('SUBRINCIAN_ID');
                    }
                    $harga  = Komponen::where('KOMPONEN_KODE',$data->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_HARGA');
                    $rincian    = new Rincian;
                    $rincian->BL_ID             = $belanja;
                    $rincian->KOMPONEN_ID       = $komponen;
                    $rincian->SUBRINCIAN_ID     = $subrincian;
                    $rincian->REKENING_ID       = $data->kamus->REKENING_ID;
                    $rincian->RINCIAN_PAJAK     = 0;
                    $rincian->RINCIAN_KETERANGAN= 'PIPPK RW '.$data->rw->RW_NAMA.'#'.$data->USULAN_ID;
                    $rincian->RINCIAN_VOLUME    = $data->USULAN_VOLUME;
                    $rincian->RINCIAN_KOEFISIEN = $data->USULAN_VOLUME." ".$data->kamus->KAMUS_SATUAN;
                    $rincian->RINCIAN_TOTAL     = $data->USULAN_VOLUME * $harga;
                    $rincian->save();

                    Usulan::where('USULAN_ID',$data->USULAN_ID)->update(['USULAN_STATUS'=>1]);
                    BL::where('BL_ID',$belanja)->update(['BL_VALIDASI'=>0]);

                    $log            = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen PIPPK RW';
                    $log->LOG_DETAIL                        = 'BL#'.$belanja;
                    $log->save();

                    $masuk++;
                }else{
                    $nonbelanja++;
                }
            }else{
                $nonkomponen++;
            }
        }
        $data   = array('masuk'=>$masuk,'nonbelanja'=>$nonbelanja,'nonkomponen'=>$nonkomponen);
        return Response::JSON($data);
    }
    public function trfPKK($tahun,$status,$kec){
        $kec            = SKPD::where('SKPD_KODE',$kec)->value('SKPD_ID');
        // $data           = Usulan::whereHas('kamus',function($q){
        //                     $q->where('KAMUS_JENIS','PIPPK');
        //                 })->whereHas('rw',function($x) use($kec){
        //                     $x->whereHas('kelurahan',function($y) use($kec){
        //                         $y->whereHas('subunit', function($z) use($kec){
        //                             $z->where('SKPD_ID',$kec);
        //                         });
        //                     });
        //                 })->where('USULAN_STATUS',0)->get();
        $data           = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','PKK');
                        })->where('USULAN_STATUS',0)->get();

        $masuk                  = 0;
        $nonkomponen            = 0;
        $nonbelanja             = 0;
        foreach ($data as $data) {
            $belanja    = BL::where('KEGIATAN_ID',$data->kamus->KAMUS_KEGIATAN)->where('SUB_ID',$data->rw->kelurahan->SUB_ID)->value('BL_ID');
            $komponen   = Komponen::where('KOMPONEN_KODE',$data->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_ID');
            if($komponen){
                if($belanja){
                    $subrincian     = Subrincian::where('BL_ID',$belanja)->where('SUBRINCIAN_NAMA',$data->kamus->KAMUS_NAMA)->value('SUBRINCIAN_ID');
                    if(empty($subrincian)){
                        $sub = new Subrincian;
                        $sub->SUBRINCIAN_NAMA    = $data->kamus->KAMUS_NAMA;
                        $sub->USER_CREATED       = Auth::user()->id;
                        $sub->BL_ID              = $belanja;
                        $sub->save();
                        $subrincian   = Subrincian::where('BL_ID',$belanja)->where('SUBRINCIAN_NAMA',$data->kamus->KAMUS_NAMA)->value('SUBRINCIAN_ID');
                    }
                    $harga  = Komponen::where('KOMPONEN_KODE',$data->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_HARGA');                    
                    $rincian    = new Rincian;
                    $rincian->BL_ID             = $belanja;
                    $rincian->KOMPONEN_ID       = $komponen;
                    $rincian->SUBRINCIAN_ID     = $subrincian;
                    $rincian->REKENING_ID       = $data->kamus->REKENING_ID;
                    $rincian->RINCIAN_PAJAK     = 0;
                    $rincian->RINCIAN_KETERANGAN= 'PIPPK PKK#'.$data->USULAN_ID;
                    $rincian->RINCIAN_VOLUME    = $data->USULAN_VOLUME;
                    $rincian->RINCIAN_KOEFISIEN = $data->USULAN_VOLUME." ".$data->kamus->KAMUS_SATUAN;
                    $rincian->RINCIAN_TOTAL     = $data->USULAN_VOLUME * $harga;
                    $rincian->save();

                    Usulan::where('USULAN_ID',$data->USULAN_ID)->update(['USULAN_STATUS'=>1]);
                    BL::where('BL_ID',$belanja)->update(['BL_VALIDASI'=>0]);

                    $log            = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen PIPPK PKK';
                    $log->LOG_DETAIL                        = 'BL#'.$belanja;
                    $log->save();

                    $masuk++;
                }else{
                    $nonbelanja++;
                }
            }else{
                $nonkomponen++;
            }
        }
        $data   = array('masuk'=>$masuk,'nonbelanja'=>$nonbelanja,'nonkomponen'=>$nonkomponen);
        return Response::JSON($data);

    }    
    public function trfLPM($tahun,$status,$kec){
        $kec            = SKPD::where('SKPD_KODE',$kec)->value('SKPD_ID');
        // $data           = Usulan::whereHas('kamus',function($q){
        //                     $q->where('KAMUS_JENIS','PIPPK');
        //                 })->whereHas('rw',function($x) use($kec){
        //                     $x->whereHas('kelurahan',function($y) use($kec){
        //                         $y->whereHas('subunit', function($z) use($kec){
        //                             $z->where('SKPD_ID',$kec);
        //                         });
        //                     });
        //                 })->where('USULAN_STATUS',0)->get();
        $data           = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','LPM');
                        })->where('USULAN_STATUS',0)->get();

        $masuk                  = 0;
        $nonkomponen            = 0;
        $nonbelanja             = 0;
        foreach ($data as $data) {
            $belanja    = BL::where('KEGIATAN_ID',$data->kamus->KAMUS_KEGIATAN)->where('SUB_ID',$data->rw->kelurahan->SUB_ID)->value('BL_ID');
            $komponen   = Komponen::where('KOMPONEN_KODE',$data->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_ID');
            if($komponen){
                if($belanja){
                    $subrincian     = Subrincian::where('BL_ID',$belanja)->where('SUBRINCIAN_NAMA',$data->kamus->KAMUS_NAMA)->value('SUBRINCIAN_ID');
                    if(empty($subrincian)){
                        $sub = new Subrincian;
                        $sub->SUBRINCIAN_NAMA    = $data->kamus->KAMUS_NAMA;
                        $sub->USER_CREATED       = Auth::user()->id;
                        $sub->BL_ID              = $belanja;
                        $sub->save();
                        $subrincian   = Subrincian::where('BL_ID',$belanja)->where('SUBRINCIAN_NAMA',$data->kamus->KAMUS_NAMA)->value('SUBRINCIAN_ID');
                    }
                    $harga  = Komponen::where('KOMPONEN_KODE',$data->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_HARGA');                    
                    $rincian    = new Rincian;
                    $rincian->BL_ID             = $belanja;
                    $rincian->KOMPONEN_ID       = $komponen;
                    $rincian->SUBRINCIAN_ID     = $subrincian;
                    $rincian->REKENING_ID       = $data->kamus->REKENING_ID;
                    $rincian->RINCIAN_PAJAK     = 0;
                    $rincian->RINCIAN_KETERANGAN= 'PIPPK LPM#'.$data->USULAN_ID;
                    $rincian->RINCIAN_VOLUME    = $data->USULAN_VOLUME;
                    $rincian->RINCIAN_KOEFISIEN = $data->USULAN_VOLUME." ".$data->kamus->KAMUS_SATUAN;
                    $rincian->RINCIAN_TOTAL     = $data->USULAN_VOLUME * $harga;
                    $rincian->save();

                    Usulan::where('USULAN_ID',$data->USULAN_ID)->update(['USULAN_STATUS'=>1]);
                    BL::where('BL_ID',$belanja)->update(['BL_VALIDASI'=>0]);

                    $log            = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen PIPPK LPM';
                    $log->LOG_DETAIL                        = 'BL#'.$belanja;
                    $log->save();

                    $masuk++;
                }else{
                    $nonbelanja++;
                }
            }else{
                $nonkomponen++;
            }
        }
        $data   = array('masuk'=>$masuk,'nonbelanja'=>$nonbelanja,'nonkomponen'=>$nonkomponen);
        return Response::JSON($data);

    }    
    public function trfKARTA($tahun,$status,$kec){
        $kec            = SKPD::where('SKPD_KODE',$kec)->value('SKPD_ID');
        // $data           = Usulan::whereHas('kamus',function($q){
        //                     $q->where('KAMUS_JENIS','PIPPK');
        //                 })->whereHas('rw',function($x) use($kec){
        //                     $x->whereHas('kelurahan',function($y) use($kec){
        //                         $y->whereHas('subunit', function($z) use($kec){
        //                             $z->where('SKPD_ID',$kec);
        //                         });
        //                     });
        //                 })->where('USULAN_STATUS',0)->get();
        $data           = Usulan::whereHas('kamus',function($q){
                            $q->where('KAMUS_JENIS','KARTA');
                        })->where('USULAN_STATUS',0)->get();

        $masuk                  = 0;
        $nonkomponen            = 0;
        $nonbelanja             = 0;
        foreach ($data as $data) {
            $belanja    = BL::where('KEGIATAN_ID',$data->kamus->KAMUS_KEGIATAN)->where('SUB_ID',$data->rw->kelurahan->SUB_ID)->value('BL_ID');
            $komponen   = Komponen::where('KOMPONEN_KODE',$data->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_ID');
            if($komponen){
                if($belanja){
                    $subrincian     = Subrincian::where('BL_ID',$belanja)->where('SUBRINCIAN_NAMA',$data->kamus->KAMUS_NAMA)->value('SUBRINCIAN_ID');
                    if(empty($subrincian)){
                        $sub = new Subrincian;
                        $sub->SUBRINCIAN_NAMA    = $data->kamus->KAMUS_NAMA;
                        $sub->USER_CREATED       = Auth::user()->id;
                        $sub->BL_ID              = $belanja;
                        $sub->save();
                        $subrincian   = Subrincian::where('BL_ID',$belanja)->where('SUBRINCIAN_NAMA',$data->kamus->KAMUS_NAMA)->value('SUBRINCIAN_ID');
                    }
                    $harga  = Komponen::where('KOMPONEN_KODE',$data->kamus->KAMUS_KODE_KOMPONEN)->value('KOMPONEN_HARGA');                    
                    $rincian    = new Rincian;
                    $rincian->BL_ID             = $belanja;
                    $rincian->KOMPONEN_ID       = $komponen;
                    $rincian->SUBRINCIAN_ID     = $subrincian;
                    $rincian->REKENING_ID       = $data->kamus->REKENING_ID;
                    $rincian->RINCIAN_PAJAK     = 0;
                    $rincian->RINCIAN_KETERANGAN= 'PIPPK KARTA#'.$data->USULAN_ID;
                    $rincian->RINCIAN_VOLUME    = $data->USULAN_VOLUME;
                    $rincian->RINCIAN_KOEFISIEN = $data->USULAN_VOLUME." ".$data->kamus->KAMUS_SATUAN;
                    $rincian->RINCIAN_TOTAL     = $data->USULAN_VOLUME * $harga;
                    $rincian->save();

                    Usulan::where('USULAN_ID',$data->USULAN_ID)->update(['USULAN_STATUS'=>1]);
                    BL::where('BL_ID',$belanja)->update(['BL_VALIDASI'=>0]);

                    $log            = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen PIPPK KARTA';
                    $log->LOG_DETAIL                        = 'BL#'.$belanja;
                    $log->save();

                    $masuk++;
                }else{
                    $nonbelanja++;
                }
            }else{
                $nonkomponen++;
            }
        }
        $data   = array('masuk'=>$masuk,'nonbelanja'=>$nonbelanja,'nonkomponen'=>$nonkomponen);
        return Response::JSON($data);

    }    
}
