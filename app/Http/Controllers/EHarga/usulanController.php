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
use App\Model\Rekening;
use App\Model\Komponen;
use App\Model\Satuan;
use App\Model\KatKom;
use App\Model\UsulanKomponen;
use App\Model\SKPD;
use App\Model\User;
use App\Model\UserBudget;
use App\Model\Rekom;
use App\Model\DataDukung;
use App\Model\UsulanSurat;

class usulanController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index($tahun){
        $id         = Auth::user()->id;
        $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');        
    	$Rekening	= Rekening::where('REKENING_KODE','like','5%')
                            ->where('REKENING_TAHUN',$tahun)
    						->whereRaw('LENGTH("REKENING_KODE") = 11')
    						->get();
    	$Satuan 	= Satuan::all();
    	$komponen 	= Komponen::all();
        $dataDukung = DataDukung::where('SKPD_ID',$skpd)->get();
        $user       = User::whereRaw("substring(mod from 5 for 1) = '1'")->get();
        $jenis      = SKPD::whereHas('userbudget',function($q) use ($id){
                                $q->where('USER_ID',$id);
                            })->value('SKPD_JENIS');
        if($jenis == 2 or $jenis == 4) $jenis = 1;
        else $jenis = 0;
        // print_r($jenis);exit();
    	return View('eharga.usulan',['tahun'=>$tahun,'rekening'=>$Rekening,'satuan'=>$Satuan,'komponen'=>$komponen,'user'=>$user,'datadukung'=>$dataDukung,'status'=>'murni','jenis'=>$jenis]);
    }

    public function showSurat($tahun){
        return View('eharga.surat',['tahun'=>$tahun]);
    }

    public function getKategori($tahun,$length){
        if(strlen($length) < 2) $pjg   = strlen($length)+12;
        elseif(strlen($length) < 13) $pjg   = strlen($length)+3;
    	else $pjg = strlen($length)+4;
    	$Komponen 	= KatKom::where('KATEGORI_TAHUN',$tahun)
    						->where('KATEGORI_KODE','like',$length.'%')
    						->whereRaw('LENGTH("KATEGORI_KODE") = '.$pjg)
    						->get();
    	$view       = "";
        if(strlen($length)< 13){
	        foreach($Komponen as $d){
	            $view .= "<option value='".$d->KATEGORI_KODE."'>".$d->KATEGORI_KODE.' - '.$d->KATEGORI_NAMA."</option>";
	        }
        }else{
	        foreach($Komponen as $d){
	            $view .= "<option value='".$d->KATEGORI_ID."'>".$d->KATEGORI_KODE.' - '.$d->KATEGORI_NAMA."</option>";
	        }        	
        }
        return $view;
    }

    public function getKategori_($tahun,$length){
        // if(strlen($length) < 13) $pjg   = strlen($length)+3;
        if(strlen($length) < 2) $pjg   = strlen($length)+12;
        elseif(strlen($length) < 13) $pjg   = strlen($length)+3;
        else $pjg = strlen($length)+4;
        $Komponen   = KatKom::where('KATEGORI_TAHUN',$tahun)
                            ->where('KATEGORI_KODE','like',$length.'%')
                            ->whereRaw('LENGTH("KATEGORI_KODE") = '.$pjg)
                            ->get();
        if($pjg == 21){
        $Komponen   = Komponen::where('KOMPONEN_TAHUN',$tahun)
                            ->where('KOMPONEN_KODE','like',$length.'%')
                            ->get();
        }
        $view       = "";
        if($pjg < 21){
            foreach($Komponen as $d){
                $view .= "<option value='".$d->KATEGORI_KODE."'>".$d->KATEGORI_KODE.' - '.$d->KATEGORI_NAMA."</option>";
            }
        }else{
            foreach($Komponen as $d){
                $view .= "<option value='".$d->KOMPONEN_ID."'>".$d->KOMPONEN_KODE.' - '.$d->KOMPONEN_NAMA."</option>";
            }
        }
        
        return $view;
    }

    public function getUsulan(Request $req, $tahun){
        $start = ($req->iDisplayStart == "")? 0 : $req->iDisplayStart;
        $length = ($req->iDisplayLength == "")? 10 : $req->iDisplayLength;
        $kategori = ($req->sSearch == "")? FALSE : urldecode($req->sSearch);
        //GET USULAN STAFF
        $data='';
        if(substr(Auth::user()->mod,3,1) == 1){
        	$data 	= UsulanKomponen::where('USER_CREATED',Auth::user()->id)
                                    ->where('USULAN_TAHUN',$tahun)
                                    ->where('USULAN_POSISI',0)
                                    ->where('USULAN_STATUS',0)
                                    ->orderBy('USULAN_ID')
                                    ->limit($length)
                                    ->offset($start);
        }
        elseif(substr(Auth::user()->mod,6,1) == 1){
            $data   = UsulanKomponen::where('USULAN_POSISI',2)
                                    ->where('USULAN_TAHUN',$tahun)            
                                    ->where('USULAN_STATUS',0)
                                    ->where('USER_POST',null)
                                    ->orderBy('USULAN_ID')
                                    ->limit($length)
                                    ->offset($start);
        }
        elseif(substr(Auth::user()->mod,4,1) == 1){
            $data   = UsulanKomponen::where('USULAN_POSISI',2)
                                    ->where('USULAN_TAHUN',$tahun)            
                                    ->where('USULAN_STATUS',0)
                                    ->where('USER_POST',Auth::user()->id)                                    
                                    ->orderBy('USULAN_ID')
                                    ->limit($length)
                                    ->offset($start);
        }elseif(substr(Auth::user()->mod,5,1) == 1){
            $data   = UsulanKomponen::where('USULAN_POSISI',5)
                                    ->where('USULAN_TAHUN',$tahun)            
                                    ->where('USULAN_STATUS',0)
                                    ->orderBy('USULAN_ID')
                                    ->limit($length)
                                    ->offset($start);
        }elseif(substr(Auth::user()->mod,0,1) == 1){
            $data   = UsulanKomponen::where('USULAN_POSISI',4)
                                    ->where('USULAN_TAHUN',$tahun)            
                                    ->where('USULAN_STATUS',0)
                                    ->whereRaw('"SURAT_ID" IS NOT NULL')                                    
                                    ->orderBy('USULAN_ID')
                                    ->limit($length)
                                    ->offset($start);
        }elseif(Auth::user()->level == 2){
            $skpd      = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->first();
            $datauser  = UserBudget::whereHas('user',function($q){
                            $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                        })->where('SKPD_ID',$skpd->SKPD_ID)->value('USER_ID');
            if($skpd->skpd->SKPD_JENIS == 1){
                $pd     = SKPD::where('SKPD_JENIS', 2)->get();
                $i = 0;
                foreach($pd as $pd){
                    $id     = $pd->SKPD_ID;
                    $user[$i]   = UserBudget::where('SKPD_ID',$id)->where('TAHUN',$tahun)
                                            ->whereHas('user',function($q){
                                                $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                                            })->value('USER_ID');
                    $i++;
                }
                $user[$i] = $datauser;
            }elseif($skpd->skpd->SKPD_JENIS == 3){
                $pd     = SKPD::where('SKPD_JENIS', 4)->get();
                $i = 0;
                foreach($pd as $pd){
                    $id     = $pd->SKPD_ID;
                    $user[$i]   = UserBudget::where('SKPD_ID',$id)->where('TAHUN',$tahun)
                                            ->whereHas('user',function($q){
                                                $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                                            })->value('USER_ID');
                    $i++;
                }
                $user[$i] = $datauser;
            }elseif($skpd->skpd->SKPD_JENIS == 2){
                $user[0]    = null; 
            }else{
                $user[0]    = $datauser;
            }
                $data   = UsulanKomponen::whereIn('USER_CREATED',$user)
                                        ->where('USULAN_POSISI',3)
                                        ->where('USULAN_STATUS',0)
                                        ->where('USULAN_TAHUN',$tahun)
                                        ->orderBy('USULAN_ID')
                                        ->limit($length)
                                        ->offset($start);
        }else if(Auth::user()->level == 8){
            $data   = UsulanKomponen::where('USULAN_STATUS',0)
                                        ->where('USULAN_TAHUN',$tahun)
                                        ->orderBy('USULAN_ID')
                                        ->limit($length)
                                        ->offset($start);
        }
        //dd($data);

	if(Auth::user()->level==8 || Auth::user()->level==9){
            $count = UsulanKomponen::where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->get()->count();
        }else{ 
            if(substr(Auth::user()->mod,3,1) == 1){
                $count = UsulanKomponen::where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->where('USULAN_POSISI',0)->get()->count();
            }
            elseif(substr(Auth::user()->mod,6,1) == 1){
                $count = UsulanKomponen::where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->where('USULAN_POSISI',2)->where('USER_POST',null)->get()->count();
            }
            elseif(substr(Auth::user()->mod,4,1) == 1){
                $count = UsulanKomponen::where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->where('USULAN_POSISI',2)->where('USER_POST',Auth::user()->id)->get()->count();
            }elseif(substr(Auth::user()->mod,5,1) == 1){
                $count = UsulanKomponen::where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->where('USULAN_POSISI',5)->get()->count();
            }elseif(substr(Auth::user()->mod,0,1) == 1){
                $count = UsulanKomponen::where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->where('USULAN_POSISI',4)->get()->count();
            }else{
                $count = UsulanKomponen::where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->where('USER_CREATED',Auth::user()->id)->get()->count();    
            }
        }

        if($kategori){
            $data   = $data->where('USULAN_NAMA','ilike','%'.$kategori.'%');
	    if(Auth::user()->level==8 || Auth::user()->level==9){
                $count = UsulanKomponen::where('USULAN_NAMA','ilike','%'.$kategori.'%')->where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->get()->count();
            }else{
                if(substr(Auth::user()->mod,3,1) == 1){
                    $count = UsulanKomponen::where('USULAN_NAMA','ilike','%'.$kategori.'%')->where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->where('USULAN_POSISI',0)->get()->count();
                }
                elseif(substr(Auth::user()->mod,6,1) == 1){
                    $count = UsulanKomponen::where('USULAN_NAMA','ilike','%'.$kategori.'%')->where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->where('USULAN_POSISI',2)->where('USER_POST',null)->get()->count();
                }
                elseif(substr(Auth::user()->mod,4,1) == 1){
                    $count = UsulanKomponen::where('USULAN_NAMA','ilike','%'.$kategori.'%')->where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->where('USULAN_POSISI',2)->where('USER_POST',Auth::user()->id)->get()->count();
                }elseif(substr(Auth::user()->mod,5,1) == 1){
                    $count = UsulanKomponen::where('USULAN_NAMA','ilike','%'.$kategori.'%')->where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->where('USULAN_POSISI',5)->get()->count();
                }elseif(substr(Auth::user()->mod,0,1) == 1){
                    $count = UsulanKomponen::where('USULAN_NAMA','ilike','%'.$kategori.'%')->where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->where('USULAN_POSISI',4)->get()->count();
                }else{
                    $count = UsulanKomponen::where('USULAN_NAMA','ilike','%'.$kategori.'%')->where('USULAN_TAHUN',$tahun)->where('USULAN_STATUS',0)->where('USER_CREATED',Auth::user()->id)->get()->count();    
                }
            }
        }
	$display = $data->get()->count();
        $data = $data->get();
    	$i 		= $start+1;
    	$view 	= array();

    	foreach ($data as $data) {
    		$post = '';$stat = '';


            if(substr(Auth::user()->mod, 3,1) == 1 ){
            $act = '<div class="dropdown dropdown-blend" style="float:right;">
				        <a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				          <span class="text text-success"><i class="fa fa-chevron-down"></i></span>
				        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
				          <li><a onclick="return detail(\''.$data->USULAN_ID.'\')"><i class="fa fa-edit"></i> Ubah</a></li>
				          <li><a href="#"><i class="fa fa-trash"></i> Hapus</a></li>
				          <li role="separator" class="divider"></li>
				          <li><a href="#"><i class="fa fa-info"></i> Info</a></li>
				        </ul>
				      </div>';
            }elseif(substr(Auth::user()->mod, 4,1) == 1){
            $act = '<div class="dropdown dropdown-blend" style="float:right;">
                        <a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <span class="text text-success"><i class="fa fa-chevron-down"></i></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                          <li><a onclick="return detail(\''.$data->USULAN_ID.'\')"><i class="fa fa-edit"></i> Ubah</a></li>                        
                          <li><a onclick="return tolak(\''.$data->USULAN_ID.'\')"><i class="fa fa-close"></i> Tolak</a></li>
                        </ul>
                      </div>';
            }elseif(Auth::user()->level == 2){
            $act = '<div class="dropdown dropdown-blend" style="float:right;">
                        <a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <span class="text text-success"><i class="fa fa-chevron-down"></i></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                          <li><a onclick="return tolak(\''.$data->USULAN_ID.'\')"><i class="fa fa-close"></i> Tolak</a></li>
                        </ul>
                      </div>';
            }else{
                $act    = "";
            }
            $opsi   = "<a class='btn btn-danger'><i class='glyphicon glyphicon-remove-sign'></i></a>
                       <a class='btn btn-success'><i class='glyphicon glyphicon-ok-sign'></i></a>";
            
            
            if(substr(Auth::user()->mod, 3,1) == 1 or 
               substr(Auth::user()->mod, 6,1) == 1){
                $checkbox = '<div class="form-group checkbox-remember m-t-n-xl">
                                <div class="checkbox">
                                  <label class="checkbox-inline i-checks">
                                    <input type="checkbox" value="'.$data->USULAN_ID.'" class="cb" id="cb-'.$data->USULAN_ID.'"/><i></i>
                                  </label>
                               </div>
                            </div>';
            }else{
                $checkbox = $i;
            }

            $pd     = UserBudget::where('USER_ID',$data->USER_CREATED)->first();

            if(substr(Auth::user()->mod, 3,1) == 1){
                $opsi   = '<div class="action visible">
                                <a onclick="return detail(\''.$data->USULAN_ID.'\')"><i class="mi-edit"></i></a>
                                <a onclick="return hapus(\''.$data->USULAN_ID.'\')"><i class="mi-trash"></i></a>';
                if($data->USULAN_ALASAN != NULL) $opsi .= '<a onclick="return showAlasan(\''.$data->USULAN_ID.'\')"><i class="icon-bdg_announce"><span class="badge badge-sm up bg-danger pull-right-xs">!</span></i></a></div>';
            }elseif(substr(Auth::user()->mod,4,1) == 1){
                $opsi   = '<div class="action visible">
                                <a onclick="return tolak(\''.$data->USULAN_ID.'\')"><i class="glyphicon glyphicon-remove"></i></a>';
                if(substr($data->katkom->KATEGORI_KODE, 0,1) == 1){
                    $opsi .= '<a onclick="return acc(\''.$data->USULAN_ID.'\')"><i class="glyphicon glyphicon-ok"></i></a>';
                }else{
                    $opsi .= '<a onclick="return detail(\''.$data->USULAN_ID.'\')"><i class="glyphicon glyphicon-ok"></i></a>';
                }
                $opsi   .= '<a onclick="return detail(\''.$data->USULAN_ID.'\')"><i class="mi-edit"></i></a>
                                <a onclick="return showSuggest(\''.$data->USULAN_ID.'\')"><i class="icon-bdg_announce"></i></a>
                            </div>';
            }elseif(Auth::user()->level == 2 or substr(Auth::user()->mod,0,1) == 1 or substr(Auth::user()->mod,5,1) == 1){
                $opsi   = '<div class="action visible">
                                <a onclick="return tolak(\''.$data->USULAN_ID.'\')"><i class="glyphicon glyphicon-remove"></i></a>
                                <a onclick="return acc(\''.$data->USULAN_ID.'\')"><i class="glyphicon glyphicon-ok"></i></a>
                            </div>';
            }elseif(substr(Auth::user()->mod,6,1) == 1){
                $opsi   = '-';
            }
            if(empty($data->DD_ID)){
                $dd     = '-';
            }else{
                $dd         = '<div class="action visible">
                                <a href="/uploads/komponen/'.$tahun.'/'.$data->datadukung->DD_PATH.'/dd.pdf" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                            </div>';
            }
            if(empty($data->REKENING_ID)) $rekening = '-';
            else $rekening = $data->rekening->REKENING_KODE.'<br>'.$data->rekening->REKENING_NAMA;
            $tipe = '';
            if($data->USULAN_TYPE == 1) $tipe = 'Komponen Baru';
            elseif($data->USULAN_TYPE == 2) $tipe = 'Ubah Komponen';
            elseif($data->USULAN_TYPE == 3) $tipe = 'Tambah Rekening';
            array_push($view, array( 'ID'       =>$data->USULAN_ID,
                                     'CB'       =>$checkbox,
                                     'NO'    	=>$i,
                                     'DD'       =>$dd,
                                     'PD'       =>$pd->skpd->SKPD_NAMA,
                                     'KATEGORI' =>$data->katkom->KATEGORI_KODE.'<br>'.$data->katkom->KATEGORI_NAMA,
                                     'NAMA'    	=>$data->USULAN_NAMA."<br><p class='text-orange'>".$data->USULAN_SPESIFIKASI.'</p>',
                                     'REKENING' =>$rekening,
                                     'OPSI'     =>$opsi,
                                     'TIPE'     =>$tipe,
                                     'HARGAAWAL'=>number_format($data->USULAN_HARGA_AWAL,2,'.',',').' / '.$data->USULAN_SATUAN,
                                     'HARGA'   	=>number_format($data->USULAN_HARGA,2,'.',',').' / '.$data->USULAN_SATUAN));
            $i++;
        }
        
        $out = array("iTotalRecords" => intval($display), "iTotalDisplayRecords"  => intval($display),"aaData"=>$view); 
        return Response::JSON($out);
    }

    public function getValid($tahun){
        //GET USULAN STAFF
        $data='';
        $user=array();
        if(substr(Auth::user()->mod,3,1) == 1){
            $skpd   = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->first();
            if($skpd){
                if($skpd->skpd->SKPD_JENIS == 1){
                    $pd     = SKPD::where('SKPD_JENIS', 2)->get();
                    $i = 0;
                    foreach($pd as $pd){
                        $id     = $pd->SKPD_ID;
                        $user[$i]   = UserBudget::where('SKPD_ID',$id)->where('TAHUN',$tahun)
                                                ->whereHas('user',function($q){
                                                    $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                                                })->value('USER_ID');
                        $i++;
                    }
                    $user[$i] = Auth::user()->id;
                }elseif($skpd->skpd->SKPD_JENIS == 3){
                    $pd     = SKPD::where('SKPD_JENIS', 4)->get();
                    $i = 0;
                    foreach($pd as $pd){
                        $id     = $pd->SKPD_ID;
                        $user[$i]   = UserBudget::where('SKPD_ID',$id)->where('TAHUN',$tahun)
                                                ->whereHas('user',function($q){
                                                    $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                                                })->value('USER_ID');
                        $i++;
                    }
                    $user[$i] = Auth::user()->id;
                }
                else{
                    
                }
            }else{
                $user[0]    = Auth::user()->id;
            }
            
            $data   = UsulanKomponen::whereIn('USER_CREATED',$user)
                                    ->where('USULAN_POSISI',1)
                                    ->where('USULAN_STATUS',0)
                                    ->where('USULAN_TAHUN',$tahun)
                                    ->orderBy('USULAN_ID')                                
                                    ->get();
        }
        elseif(substr(Auth::user()->mod,4,1) == 1){
            $data   = UsulanKomponen::where('USULAN_POSISI',7)
                                    ->where('USULAN_STATUS',0)
                                    ->where('USER_POST',Auth::user()->id)
                                    ->where('USULAN_TAHUN',$tahun)                                    
                                    ->orderBy('USULAN_ID')
                                    ->get();
        }elseif(substr(Auth::user()->mod,5,1) == 1){
            $data   = UsulanKomponen::where('USULAN_POSISI','>',3)
                                    ->where('USULAN_STATUS',0)
                                    ->where('USULAN_TAHUN',$tahun)                                    
                                    ->orderBy('USULAN_ID')
                                    ->get();
        }elseif(substr(Auth::user()->mod,6,1) == 1){
            $data   = UsulanKomponen::where('USULAN_POSISI',6)
                                    ->where('USULAN_STATUS',0)
                                    ->where('USULAN_TAHUN',$tahun)                                    
                                    ->orderBy('USULAN_ID')
                                    ->get();
        }elseif(substr(Auth::user()->mod,0,1) == 1){
            $data   = UsulanKomponen::where('USULAN_POSISI','>',2)
                                    ->where('USULAN_STATUS',0)
                                    ->where('USULAN_TAHUN',$tahun)                                    
                                    ->orderBy('USULAN_ID')
                                    ->get();
        }elseif(Auth::user()->level == 2){
            $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->first();
            $dataUser   = UserBudget::where('SKPD_ID',$skpd->SKPD_ID)->select('USER_ID')->get();
            $data       = UsulanKomponen::where('USULAN_POSISI',4)
                                        ->where('SURAT_ID',null)
                                        ->where('USULAN_TAHUN',$tahun)                                        
                                        ->whereIn('USER_CREATED',$dataUser)
                                        ->orderBy('USULAN_ID')
                                        ->get();
        }elseif(Auth::user()->level == 8){
            /*$skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->first();
            $dataUser   = UserBudget::where('SKPD_ID',$skpd->SKPD_ID)->select('USER_ID')->get();*/
            $data       = UsulanKomponen::where('SURAT_ID',null)
                                        ->where('USULAN_TAHUN',$tahun)      
                                        ->orderBy('USULAN_ID')
                                        ->get();
        }
       // dd($data);
        $i      = 1;
        $view   = array();
        foreach ($data as $data) {
            if(substr(Auth::user()->mod, 3,1) == 1){
                $opsi   = '<div class="action visible">
                                <a onclick="return cancel(\''.$data->USULAN_ID.'\')"><i class="mi-trash"></i></a>
                            </div>';
            }elseif(substr(Auth::user()->mod, 6,1) == 1){
                $opsi   = '<div class="action visible">
                                <a onclick="return tolak(\''.$data->USULAN_ID.'\')"><i class="glyphicon glyphicon-remove"></i></a>
                                <a onclick="return acc(\''.$data->USULAN_ID.'\')"><i class="glyphicon glyphicon-ok"></i></a>
                            </div>';
            }elseif(substr(Auth::user()->mod, 4,1) == 1){
                $opsi   = '<div class="action visible">
                                <a onclick="return acc(\''.$data->USULAN_ID.'\')"><i class="glyphicon glyphicon-ok"></i></a>
                            </div>';
            }
            else{
                $opsi   = '-';
            }

            if(Auth::user()->level == 2 or substr(Auth::user()->mod, 4,1)==1){
                $i = '<div class="form-group checkbox-remember m-t-n-xl">
                                <div class="checkbox">
                                  <label class="checkbox-inline i-checks">
                                    <input type="checkbox" value="'.$data->USULAN_ID.'" class="cb" id="cb-'.$data->USULAN_ID.'"/><i></i>
                                  </label>
                               </div>
                            </div>';
            }
            $pd     = UserBudget::where('USER_ID',$data->USER_CREATED)->first();
            if(empty($data->REKENING_ID)) $rekening = '-';
            else $rekening = $data->rekening->REKENING_KODE.'<br>'.$data->rekening->REKENING_NAMA; 
            $tipe = ''; 
            if($data->USULAN_TYPE == 1) $tipe = 'Komponen Baru';
            elseif($data->USULAN_TYPE == 2) $tipe = 'Ubah Komponen';
            elseif($data->USULAN_TYPE == 3) $tipe = 'Tambah Rekening';
            array_push($view, array( 'ID'       =>$data->USULAN_ID,
                                     'NO'       =>$i,
                                     'PD'       =>$pd->skpd->SKPD_NAMA,
                                     'KATEGORI' =>$data->katkom->KATEGORI_NAMA,
                                     'NAMA'     =>$data->USULAN_NAMA."<br><p class='text-orange'>".$data->USULAN_SPESIFIKASI.'</p>',
                                     'REKENING' =>$rekening,
                                     'OPSI'     =>$opsi,
                                     'TIPE'     =>$tipe,
                                     'HARGAAWAL'=>number_format($data->USULAN_HARGA_AWAL,2,'.',',').' / '.$data->USULAN_SATUAN,                                     
                                     'HARGA'    =>number_format($data->USULAN_HARGA,0,'.',',').' / '.$data->USULAN_SATUAN));
            $i++;
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function getDetail($tahun,$id){
    	$data 	= UsulanKomponen::where('USULAN_ID',$id)->where('USULAN_TAHUN',$tahun)->first();
        $K1     = KatKom::where('KATEGORI_KODE',substr($data->katkom->KATEGORI_KODE, 0,4))->first();
        $K2     = KatKom::where('KATEGORI_KODE',substr($data->katkom->KATEGORI_KODE, 0,7))->first();
        $K3     = KatKom::where('KATEGORI_KODE',substr($data->katkom->KATEGORI_KODE, 0,10))->first();
        $K4     = KatKom::where('KATEGORI_KODE',substr($data->katkom->KATEGORI_KODE, 0,13))->first();
        $K5     = KatKom::where('KATEGORI_KODE',$data->katkom->KATEGORI_KODE)->first();
        if(empty($data->REKENING_ID)) $rekening = '-';
        else $rekening = $data->rekening->REKENING_KODE.'<br>'.$data->rekening->REKENING_NAMA;
    	return ['ID'            =>$data->USULAN_ID,
                'KATEGORI'      =>$data->katkom->KATEGORI_NAMA,
                'REKENING'      =>$rekening,
                'REKENING_ID'   =>$data->REKENING_ID,
                'KOMPONEN'      =>$data->USULAN_NAMA,
                'SPESIFIKASI'   =>$data->USULAN_SPESIFIKASI,
                'SATUAN'        =>$data->USULAN_SATUAN,
                'HARGA'         =>$data->USULAN_HARGA,
                'JENIS'         =>substr($data->katkom->KATEGORI_KODE, 0,1),
                'K1'            =>$K1->KATEGORI_KODE,
                'K1_NAMA'       =>$K1->KATEGORI_NAMA,
                'K2'            =>$K2->KATEGORI_KODE,
                'K2_NAMA'       =>$K2->KATEGORI_NAMA,
                'K3'            =>$K3->KATEGORI_KODE,
                'K3_NAMA'       =>$K3->KATEGORI_NAMA,
                'K4'            =>$K4->KATEGORI_KODE,
                'K4_NAMA'       =>$K4->KATEGORI_NAMA,
                'K5'            =>$data->KATEGORI_ID,
                'K5_KODE'       =>$data->katkom->KATEGORI_KODE,
                'K5_NAMA'       =>$K5->KATEGORI_NAMA,];

    }

    public function submitUsulan($tahun){
        $idUsulan   = Input::get('USULAN_ID');
        $cek = UsulanKomponen::where('USULAN_NAMA','ilike',Input::get('USULAN_NAMA'))
        ->where('USULAN_SPESIFIKASI','ilike',Input::get('USULAN_SPESIFIKASI'))->first();
        if($cek){
            return "Anda tidak bisa mengusulkan komponen yang sudah tersedia!";
        }
        $cek = Komponen::where('KOMPONEN_NAMA','ilike',Input::get('USULAN_NAMA'))
        ->where('KOMPONEN_SPESIFIKASI','ilike',Input::get('USULAN_SPESIFIKASI'))->first();
        if($cek){
            return "Anda tidak bisa mengusulkan komponen yang sudah didaftarkan!";
        }
        if(empty($idUsulan)){
    	   $usulan 	= new UsulanKomponen;
            $usulan->REKENING_ID        = Input::get('REKENING_ID');
            $usulan->KATEGORI_ID        = Input::get('KATEGORI_ID');
            $usulan->USULAN_NAMA        = Input::get('USULAN_NAMA');
            $usulan->USULAN_SPESIFIKASI = Input::get('USULAN_SPESIFIKASI');
            $usulan->USULAN_HARGA       = Input::get('USULAN_HARGA');
            $usulan->USULAN_SATUAN      = Input::get('USULAN_SATUAN');
            $usulan->USULAN_STATUS      = 0;
            $usulan->USULAN_POSISI      = 0;
            $usulan->USULAN_TAHUN       = $tahun;
            $usulan->USULAN_TYPE        = 1;
            $usulan->TIME_CREATED       = Carbon\Carbon::now();
            $usulan->USER_CREATED       = Auth::user()->id;
            $usulan->IP_CREATED         = $_SERVER['REMOTE_ADDR'];
            $usulan->save();
        }else{
            if(substr(Auth::user()->mod,4,1)==1){
                $usulan     = UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->first();
                // print_r(substr($usulan->katkom->KATEGORI_KODE,0,1));exit();
                if(substr($usulan->katkom->KATEGORI_KODE,0,1) == '1' or substr($usulan->katkom->KATEGORI_KODE,0,1) == '2') $posisi = 9;
                else $posisi = 3;
            }
            else $posisi = 0;
            UsulanKomponen::where('USULAN_ID',$idUsulan)->update([
                    'REKENING_ID'           => Input::get('REKENING_ID'),
                    'KATEGORI_ID'           => Input::get('KATEGORI_ID'),
                    'USULAN_NAMA'           => Input::get('USULAN_NAMA'),
                    'USULAN_SPESIFIKASI'    => Input::get('USULAN_SPESIFIKASI'),
                    'USULAN_SATUAN'         => Input::get('USULAN_SATUAN'),
                    // 'USULAN_TANGGAL'        => Input::get('USULAN_TANGGAL'),
                    'USULAN_STATUS'         => 0,
                    'USULAN_POSISI'         => $posisi,
                    'TIME_UPDATED'          => Carbon\Carbon::now(),
                    'USER_UPDATED'          => Auth::user()->id,
                    'IP_UPDATED'            => $_SERVER['REMOTE_ADDR']
                ]);
        }
    	return "Berhasil disimpan!";
    }

    public function submitUsulanMultiple(){
        $id = Input::get('USULAN_ID');
        foreach($id as $id){
            UsulanKomponen::where('USULAN_ID',$id)->update(['USER_POST'=>Input::get('STAFF')]);
        }
        return 'Berhasil!';
    }

    public function actUsulan($tahun){
    	$status    = Input::get('STATUS');
        $id        = Input::get('USULAN_ID');
        
        if($status == 'acc'){
            UsulanKomponen::where('USULAN_ID',$id)->update(['USULAN_STATUS'=>'1']);
            return 'Berhasil Diterima';
        }elseif($status = 'dec'){
            UsulanKomponen::where('USULAN_ID',$id)->update(['USULAN_STATUS'=>'2']);
            return 'Berhasil Ditolak!';
        }
    }

    public function posting($tahun){
        $id = Input::get('USULAN_ID');
        foreach($id as $id){
            $getKode    = UsulanKomponen::where('USULAN_ID',$id)->first();
            if($getKode->USULAN_TYPE == 1){
                $getkode    = Komponen::whereRaw('"KOMPONEN_KODE" LIKE \''.$getKode->katkom->KATEGORI_KODE.'%\'')
                                        ->orderBy('KOMPONEN_KODE','DESC')
                                        ->value('KOMPONEN_KODE');
                $kode_      = substr($getkode, 18,3)+1;
                if($kode_ < 10) $kode = "00".$kode_;
                elseif($kode_ <100) $kode = "0".$kode_;
                else $kode = $kode_;
                UsulanKomponen::where('USULAN_ID',$id)->update(['USULAN_POSISI'=>8,'USULAN_STATUS'=>1]);
                $komponen = new Komponen;
                $komponen->KOMPONEN_TAHUN       = $tahun;
                $komponen->KOMPONEN_KODE        = $getKode->katkom->KATEGORI_KODE.".".$kode;
                $komponen->KOMPONEN_NAMA        = $getKode->USULAN_NAMA;
                $komponen->KOMPONEN_SPESIFIKASI = $getKode->USULAN_SPESIFIKASI;
                $komponen->KOMPONEN_HARGA       = $getKode->USULAN_HARGA;
                $komponen->KOMPONEN_SATUAN      = $getKode->USULAN_SATUAN;
                $komponen->TIME_CREATED         = Carbon\Carbon::now();
                $komponen->save();

                $idKomponen     = Komponen::where('KOMPONEN_KODE',$getKode->katkom->KATEGORI_KODE.".".$kode)->value('KOMPONEN_ID');
                $rekom          = new Rekom;
                $rekom->REKOM_TAHUN         = $tahun;
                $rekom->REKENING_ID         = $getKode->REKENING_ID;
                $rekom->KOMPONEN_ID         = $idKomponen;
                $rekom->save();
                UsulanKomponen::where('USULAN_ID',$id)->update(['KOMPONEN_ID'=>$idKomponen,'KOMPONEN_KODE'=>$getKode->katkom->KATEGORI_KODE.".".$kode]);
            }elseif($getKode->USULAN_TYPE == 2){
                Komponen::where('KOMPONEN_ID',$getKode->KOMPONEN_ID)->update(['KOMPONEN_HARGA'=>$getKode->USULAN_HARGA]);
                UsulanKomponen::where('USULAN_ID',$id)->update(['USULAN_POSISI'=>8,'USULAN_STATUS'=>1]);
            }elseif($getKode->USULAN_TYPE == 3){
                $rekom  = new Rekom;
                $rekom->REKOM_TAHUN     = $tahun;
                $rekom->KOMPONEN_ID     = $getKode->KOMPONEN_ID;
                $rekom->REKENING_ID     = $getKode->REKENING_ID;
                $rekom->save();
                UsulanKomponen::where('USULAN_ID',$id)->update(['USULAN_POSISI'=>8,'USULAN_STATUS'=>1]);
            }
        }
        return 'Berhasil!';
    }

    public function submitAlasan(){
        if(substr(Auth::user()->mod, 4,1) == 1) UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->update(['USULAN_STATUS'=>0,'USULAN_ALASAN'=>Input::get('ALASAN'),'USULAN_POSISI'=>0]);
        else UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->update(['USULAN_STATUS'=>2,'USULAN_ALASAN'=>Input::get('ALASAN')]);
        return 'Berhasil!';
    }

    public function getAlasan($tahun,$id){
        $data = UsulanKomponen::where('USULAN_ID',$id)->first();
        return Response::JSON($data);
    }

    public function submitDD($tahun){
        $img1               = Input::file('dd-file');
        $destinationPath    = $this->generateRandomString(10);
        $image1             = "";
        $image              = "";
        if(!empty($img1)){
            $extension = $img1->getClientOriginalExtension();
            if($extension == 'pdf' or $extension == 'PDF'){
                $fileimg1   = 'dd.' . $extension;
                $upload_success = $img1->move('uploads/komponen/'.$tahun.'/'.$destinationPath, $fileimg1);
                $image1     = $destinationPath.'/'.$fileimg1;
            }else{
                Session::flash('success','Dokumen tidak sesuai');
                return Redirect('harga/'.$tahun.'/usulan');
            }
        }
        $skpd   = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        $dd     = new DataDukung;
        $dd->DD_PATH    = $destinationPath;
        $dd->DD_NAMA    = Input::get('dd-nama');
        $dd->SKPD_ID    = $skpd;
        $dd->save();

        Session::flash('success','Upload Berhasil');
        return Redirect('harga/'.$tahun.'/usulan');
    }

    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function updateDD($tahun){
        $id = Input::get('USULAN_ID');
        foreach($id as $id){
            UsulanKomponen::where('USULAN_ID',$id)->update(['DD_ID'=>Input::get('DD'),'USULAN_POSISI'=>1]);
        }
        return 'Berhasil!';
    }

    function ajukan(){
        // UsulanKomponen::where('USER_CREATED',Auth::user()->id)
        UsulanKomponen::where('USULAN_POSISI',1)
                        ->where('USULAN_STATUS',0)
                        ->update(['USULAN_POSISI'=>2]);
        return 'Berhasil!';
    }

    function getStatus($tahun,$post){
        if($post == 7) $post = ['7','8'];
        else $post = [$post];
        if(substr(Auth::user()->mod, 3,1)==1){
            $data   = UsulanKomponen::where('USER_CREATED',Auth::user()->id)
                                    ->whereIn('USULAN_POSISI',$post)
                                    ->orderBy('USULAN_ID')                                
                                    ->get();
        }elseif(substr(Auth::user()->mod, 4,1)){
            $data   = UsulanKomponen::where('USER_POST',Auth::user()->id)
                                    ->whereIn('USULAN_POSISI',$post)
                                    ->orderBy('USULAN_ID')                                
                                    ->get();
        }elseif(substr(Auth::user()->mod, 5,1)){
            $data   = UsulanKomponen::whereIn('USULAN_POSISI',$post)
                                    ->orderBy('USULAN_ID')                                
                                    ->get();
        }elseif(substr(Auth::user()->mod, 6,1)){
            $data   = UsulanKomponen::whereIn('USULAN_POSISI',$post)
                                    ->orderBy('USULAN_ID')                                
                                    ->get();
        }elseif(substr(Auth::user()->mod, 0,1)){
            $data   = UsulanKomponen::whereIn('USULAN_POSISI',$post)
                                    ->orderBy('USULAN_ID')                                
                                    ->get();
        }elseif(Auth::user()->app == 4){
            $data   = UsulanKomponen::whereIn('USULAN_POSISI',$post)
                                    ->orderBy('USULAN_ID')                                
                                    ->get();
        }elseif(Auth::user()->level == 2){
            $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->first();
            $dataUser   = UserBudget::where('SKPD_ID',$skpd->SKPD_ID)->select('USER_ID')->get();
            $data       = UsulanKomponen::whereIn('USULAN_POSISI',$post)
                                        ->whereIn('USER_CREATED',$dataUser)
                                        ->orderBy('USULAN_ID')
                                        ->get();
        }
        $i = 1;
        $view = array();
        
        foreach ($data as $data) {
            $dd         = '<div class="action visible">
                                <a href="/uploads/komponen/'.$tahun.'/'.$data->datadukung->DD_PATH.'/dd.pdf" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                            </div>';
            if($data->USULAN_POSISI == 8) $status = '<span class="text text-success">Posted</span>';
            else $status = '<span class="text text-info">Waiting</span>';

            $pd     = UserBudget::where('USER_ID',$data->USER_CREATED)->first();
            if(empty($data->REKENING_ID)) $rekening = '-';
            else $rekening = $data->rekening->REKENING_KODE.'<br>'.$data->rekening->REKENING_NAMA;
            $tipe = ''; 
            if($data->USULAN_TYPE == 1) $tipe = 'Komponen Baru';
            elseif($data->USULAN_TYPE == 2) $tipe = 'Ubah Komponen';
            elseif($data->USULAN_TYPE == 3) $tipe = 'Tambah Rekening';
            if($data->USULAN_ASALAN != NULL)
            $opsi   = '<div class="action visible">
                                <a onclick="return showAlasan(\''.$data->USULAN_ID.'\')"><i class="icon-bdg_announce"></i></a>
                            </div>';
            else $opsi = '-'; 
            if(substr($data->katkom->KATEGORI_KODE, 0,1) == 1) $jenis = 'SSH';   
            elseif(substr($data->katkom->KATEGORI_KODE, 0,1) == 2) $jenis = 'HSPK';   
            elseif(substr($data->katkom->KATEGORI_KODE, 0,1) == 3) $jenis = 'ASB';   
            array_push($view, array( 'ID'       =>$data->USULAN_ID,
                                     'NO'       =>$i,
                                     'DD'       =>$dd,
                                     'PD'       =>$pd->skpd->SKPD_NAMA,
                                     'KATEGORI' =>$data->katkom->KATEGORI_NAMA,
                                     'STATUS'   =>$status,
                                     'JENIS'    =>$jenis,
                                     'NAMA'     =>$data->USULAN_NAMA."<br><p class='text-orange'>".$data->USULAN_SPESIFIKASI.'</p>',
                                     'REKENING' =>$rekening,
                                     'TIPE'     =>$tipe,
                                     'OPSI'     =>$opsi,
                                     'HARGA'    =>number_format($data->USULAN_HARGA,0,'.',',')));
            $i++;
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function acc(){
        if(substr(Auth::user()->mod,4,1)==1){
            $getKode    = UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->first();
            if($getKode->USULAN_POSISI!=7){
                $posisi = 3;
                UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->update(['USULAN_POSISI'=>$posisi]);
            }
            elseif($getKode->USULAN_POSISI==7){
                if($getKode->USULAN_TYPE == 1){
                    $getkode    = Komponen::whereRaw('"KOMPONEN_KODE" LIKE \''.$getKode->katkom->KATEGORI_KODE.'%\'')
                                            ->orderBy('KOMPONEN_KODE','DESC')
                                            ->value('KOMPONEN_KODE');
                    $kode_      = substr($getkode, 18,3)+1;
                    if($kode_ < 10) $kode = "00".$kode_;
                    elseif($kode_ <100) $kode = "0".$kode_;
                    else $kode = $kode_;
                    //print_r($getKode->katkom->KATEGORI_KODE.".".$kode);exit;
                    UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->update(['USULAN_POSISI'=>8,'USULAN_STATUS'=>1]);
                    //$posisi = 8;
                    $komponen = new Komponen;
                    $komponen->KOMPONEN_TAHUN       = $getKode->USULAN_TAHUN;
                    $komponen->KOMPONEN_KODE        = $getKode->katkom->KATEGORI_KODE.".".$kode;
                    $komponen->KOMPONEN_NAMA        = $getKode->USULAN_NAMA;
                    $komponen->KOMPONEN_SPESIFIKASI = $getKode->USULAN_SPESIFIKASI;
                    $komponen->KOMPONEN_HARGA       = $getKode->USULAN_HARGA;
                    $komponen->KOMPONEN_SATUAN      = $getKode->USULAN_SATUAN;
                    $komponen->TIME_CREATED         = Carbon\Carbon::now();
                    $komponen->save();

                    $idKomponen     = Komponen::where('KOMPONEN_KODE',$getKode->katkom->KATEGORI_KODE.".".$kode)->value('KOMPONEN_ID');
                    $rekom          = new Rekom;
                    $rekom->REKOM_TAHUN         = $getKode->USULAN_TAHUN;
                    $rekom->REKENING_ID         = $getKode->REKENING_ID;
                    $rekom->KOMPONEN_ID         = $idKomponen;
                    $rekom->save();
                    UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->update(['KOMPONEN_ID'=>$idKomponen,'KOMPONEN_KODE'=>$getKode->katkom->KATEGORI_KODE.".".$kode]);
                }elseif($getKode->USULAN_TYPE == 2){
                    Komponen::where('KOMPONEN_ID',$getKode->KOMPONEN_ID)->update(['KOMPONEN_HARGA'=>$getKode->USULAN_HARGA]);
                }elseif($getKode->USULAN_TYPE == 3){
                    $rekom  = new Rekom;
                    $rekom->REKOM_TAHUN     = $getKode->USULAN_TAHUN;
                    $rekom->KOMPONEN_ID     = $getKode->KOMPONEN_ID;
                    $rekom->REKENING_ID     = $getKode->REKENING_ID;
                    $rekom->save();
                }
            }
        }
        elseif(substr(Auth::user()->mod, 5,1)==1){
            $posisi = 6;
            UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->update(['USULAN_POSISI'=>$posisi]);
        }
        elseif(substr(Auth::user()->mod, 6,1)==1){
            $posisi = 7;
            UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->update(['USULAN_POSISI'=>$posisi]);
        }
        elseif(substr(Auth::user()->mod, 0,1)==1){
            $posisi = 5;
            UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->update(['USULAN_POSISI'=>$posisi]);
        }
        elseif(Auth::user()->level == 2){
            $posisi = 4;
            UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->update(['USULAN_POSISI'=>$posisi]);
        }
        //UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->update(['USULAN_POSISI'=>$posisi]);
        return 'Berhasil!';
    }
    public function grouping(){
        $skpd   = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');   
        $surat  = $this->generateRandomString(6);     
        $us     = new UsulanSurat;
        $us->SURAT_NO   = $surat;
        $us->SKPD_ID    = $skpd;
        $us->save();

        $sid    = UsulanSurat::where('SURAT_NO',$surat)->value('SURAT_ID');
        $id = Input::get('USULAN_ID');
        foreach($id as $id){
            UsulanKomponen::where('USULAN_ID',$id)->update(['SURAT_ID'=>$sid]);
        }
        return $surat;
    }

    public function getSurat($tahun){
            $skpd   = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->first();
            if($skpd){
                if($skpd->skpd->SKPD_JENIS == 1){
                    $pd     = SKPD::where('SKPD_JENIS', 2)->get();
                    $i = 0;
                    foreach($pd as $pd){
                        $id     = $pd->SKPD_ID;
                        $user[$i]   = UserBudget::where('SKPD_ID',$id)->where('TAHUN',$tahun)
                                                ->whereHas('user',function($q){
                                                    $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                                                })->value('USER_ID');
                        $i++;
                    }
                    $user[$i] = Auth::user()->id;
                }elseif($skpd->skpd->SKPD_JENIS == 3){
                    $pd     = SKPD::where('SKPD_JENIS', 4)->get();
                    $i = 0;
                    foreach($pd as $pd){
                        $id     = $pd->SKPD_ID;
                        $user[$i]   = UserBudget::where('SKPD_ID',$id)->where('TAHUN',$tahun)
                                                ->whereHas('user',function($q){
                                                    $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                                                })->value('USER_ID');
                        $i++;
                    }
                    $user[$i] = Auth::user()->id;
                }
                else{
                    $user[0]    = Auth::user()->id;
                }
            }else{
                $user[0]    = Auth::user()->id;
            }
            
        $data       = UsulanKomponen::where('USULAN_POSISI',4)
                                        ->where('SURAT_ID',null)
                                        ->whereIn('USER_CREATED',$user)
                                        ->orderBy('USULAN_ID')
                                        ->get();
        $i      = 1;
        $view   = array();
        foreach ($data as $data) {
                $i = '<div class="form-group checkbox-remember m-t-n-xl">
                                <div class="checkbox">
                                  <label class="checkbox-inline i-checks">
                                    <input type="checkbox" value="'.$data->USULAN_ID.'" class="cb_" id="cb1-'.$data->USULAN_ID.'"/><i></i>
                                  </label>
                               </div>
                            </div>';
                        $pd     = UserBudget::where('USER_ID',$data->USER_CREATED)->first();
            if(empty($data->REKENING_ID)) $rekening = '-';
            else $rekening = $data->rekening->REKENING_KODE.'<br>'.$data->rekening->REKENING_NAMA;
            $tipe = ''; 
            if($data->USULAN_TYPE == 1) $tipe = 'Komponen Baru';
            elseif($data->USULAN_TYPE == 2) $tipe = 'Ubah Komponen';
            elseif($data->USULAN_TYPE == 3) $tipe = 'Tambah Rekening';            
            array_push($view, array( 'ID'       =>$data->USULAN_ID,
                                     'NO'       =>$i,
                                     'PD'       =>$pd->skpd->SKPD_NAMA,
                                     'KATEGORI' =>$data->katkom->KATEGORI_NAMA,
                                     'NAMA'     =>$data->USULAN_NAMA."<br><p class='text-orange'>".$data->USULAN_SPESIFIKASI.'</p>',
                                     'REKENING' =>$rekening,
                                     'TIPE'     =>$tipe,
                                     'HARGA'    =>number_format($data->USULAN_HARGA,0,'.',',')));
            $i++;
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);        
    }

    public function getKomponen(Request $req, $tahun){
        if($req->term){
            $komponen   = Komponen::where('KOMPONEN_NAMA','ilike','%'.$req->term.'%')->get();
        } else {
            $komponen   = Komponen::all();
        }
        $view   = array();$i=0;
        foreach($komponen as $k){
            $view[$i]['id']   = $k->KOMPONEN_ID;
            $view[$i]['label']   = $k->KOMPONEN_NAMA;
            $view[$i]['value']   = $k->KOMPONEN_NAMA;
            $view[$i]['spek']   = $k->KOMPONEN_SPESIFIKASI;
            $view[$i]['satuan']   = $k->KOMPONEN_SATUAN;
            $view[$i]['harga']   = $k->KOMPONEN_HARGA;
            //number_format($k->KOMPONEN_HARGA,0,',','.');
            $i++;
        }     
        return Response::JSON($view);  

    }

    public function getSugest($tahun,$id){
        $kategori   = KatKom::where('KATEGORI_ID',$id)->value('KATEGORI_KODE');
        $komponen   = Komponen::where('KOMPONEN_KODE','like',$kategori.'%')->get();
        $view   = "";$i=0;
        foreach($komponen as $k){
            $view[$i]   = $k->KOMPONEN_NAMA.' : '.number_format($k->KOMPONEN_HARGA,0,',','.');
            $i++;
        }
        return $view;

    }

    public function getSugest_($tahun,$id){
        $usulan     = UsulanKomponen::where('USULAN_ID',$id)->first();
        $kategori   = KatKom::where('KATEGORI_ID',$usulan->KATEGORI_ID)->value('KATEGORI_KODE');
        $explode    = explode(' ', $usulan->USULAN_NAMA);
        $komponen   = Komponen::where('KOMPONEN_KODE','like',$kategori.'%');
        // foreach($explode as $e){
        //     $komponen = $komponen->whereRaw('LOWER("KOMPONEN_NAMA") like LOWER(\'%'.$e.'%\')');
        // }
        $komponen = $komponen->get();
        $view   = array();$i=1;
        foreach($komponen as $k){
            array_push($view, array( 'NO'               =>$i,
                                     'NAMA'             =>$k->KOMPONEN_NAMA,
                                     'SPESIFIKASI'      =>$k->KOMPONEN_SPESIFIKASI,
                                     'HARGA'            =>number_format($k->KOMPONEN_HARGA,2,'.',',')));
            $i++;
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function getUbah($tahun,$id){
        $data = Komponen::where('KOMPONEN_TAHUN',$tahun)
                            ->where('KOMPONEN_ID',$id)
                            ->first();
        $rekom  = Rekom::where('KOMPONEN_ID',$id)->get();
        $rekening = "";
        foreach($rekom as $r){
            $rekening .= $r->rekening->REKENING_KODE." , ";
        }
        $view   = array("KOMPONEN_ID"=>$data->KOMPONEN_ID,
                        "KOMPONEN_TAHUN"=>$data->KOMPONEN_TAHUN,
                        "KOMPONEN_KODE"=>$data->KOMPONEN_KODE,
                        "KOMPONEN_NAMA"=>$data->KOMPONEN_NAMA,
                        "KOMPONEN_SPESIFIKASI"=>$data->KOMPONEN_SPESIFIKASI,
                        "KOMPONEN_HARGA"=>$data->KOMPONEN_HARGA,
                        "KOMPONEN_SATUAN"=>$data->KOMPONEN_SATUAN,
                        "REKENING"=>$rekening
            );                            
        return Response::JSON($view);
    }

    public function submitUsulanUbah($tahun){
        $idUsulan   = Input::get('USULAN_ID');
        $komponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
        $kategori   = KatKom::where('KATEGORI_KODE',substr($komponen->KOMPONEN_KODE, 0,17))->value('KATEGORI_ID');
        if(empty($idUsulan)){
           $usulan  = new UsulanKomponen;
            $usulan->KATEGORI_ID        = $kategori;
            $usulan->USULAN_NAMA        = $komponen->KOMPONEN_NAMA;
            $usulan->USULAN_SPESIFIKASI = $komponen->KOMPONEN_SPESIFIKASI;
            $usulan->USULAN_HARGA_AWAL  = $komponen->KOMPONEN_HARGA;
            $usulan->USULAN_HARGA       = Input::get('USULAN_HARGA');
            $usulan->USULAN_SATUAN      = $komponen->KOMPONEN_SATUAN;
            $usulan->KOMPONEN_ID        = $komponen->KOMPONEN_ID;
            $usulan->USULAN_STATUS      = 0;
            $usulan->USULAN_POSISI      = 0;
            $usulan->USULAN_TAHUN       = $tahun;
            $usulan->USULAN_TYPE        = 2;
            $usulan->TIME_CREATED       = Carbon\Carbon::now();
            $usulan->USER_CREATED       = Auth::user()->id;
            $usulan->IP_CREATED         = $_SERVER['REMOTE_ADDR'];
            $usulan->save();
        }else{
            if(substr(Auth::user()->mod,4,1)==1) $posisi = 3;
            else $posisi = 0;
            UsulanKomponen::where('USULAN_ID',$idUsulan)->update([
                    'REKENING_ID'           => Input::get('REKENING_ID'),
                    'KATEGORI_ID'           => Input::get('KATEGORI_ID'),
                    'USULAN_NAMA'           => Input::get('USULAN_NAMA'),
                    'USULAN_SPESIFIKASI'    => Input::get('USULAN_SPESIFIKASI'),
                    'USULAN_SATUAN'         => Input::get('USULAN_SATUAN'),
                    // 'USULAN_TANGGAL'        => Input::get('USULAN_TANGGAL'),
                    'USULAN_STATUS'         => 0,
                    'USULAN_POSISI'         => $posisi,
                    'TIME_UPDATED'          => Carbon\Carbon::now(),
                    'USER_UPDATED'          => Auth::user()->id,
                    'IP_UPDATED'            => $_SERVER['REMOTE_ADDR']
                ]);
        }
        return "Berhasil disimpan!";
    }

    public function submitTambahRekening($tahun){
        $idUsulan   = Input::get('USULAN_ID');
        $komponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
        $kategori   = KatKom::where('KATEGORI_KODE',substr($komponen->KOMPONEN_KODE, 0,17))->value('KATEGORI_ID');
        if(empty($idUsulan)){
           $usulan  = new UsulanKomponen;
            $usulan->KATEGORI_ID        = $kategori;
            $usulan->REKENING_ID        = Input::get('REKENING_ID');
            $usulan->USULAN_NAMA        = $komponen->KOMPONEN_NAMA;
            $usulan->USULAN_SPESIFIKASI = $komponen->KOMPONEN_SPESIFIKASI;
            $usulan->USULAN_HARGA_AWAL  = $komponen->KOMPONEN_HARGA;
            $usulan->USULAN_HARGA       = $komponen->KOMPONEN_HARGA;
            $usulan->USULAN_SATUAN      = $komponen->KOMPONEN_SATUAN;
            $usulan->KOMPONEN_ID        = $komponen->KOMPONEN_ID;
            $usulan->USULAN_STATUS      = 0;
            $usulan->USULAN_POSISI      = 0;
            $usulan->USULAN_TAHUN       = $tahun;
            $usulan->USULAN_TYPE        = 3;
            $usulan->TIME_CREATED       = Carbon\Carbon::now();
            $usulan->USER_CREATED       = Auth::user()->id;
            $usulan->IP_CREATED         = $_SERVER['REMOTE_ADDR'];
            $usulan->save();
        }else{
            if(substr(Auth::user()->mod,4,1)==1) $posisi = 3;
            else $posisi = 1;
            UsulanKomponen::where('USULAN_ID',$idUsulan)->update([
                    'REKENING_ID'           => Input::get('REKENING_ID'),
                    'KATEGORI_ID'           => Input::get('KATEGORI_ID'),
                    'USULAN_NAMA'           => Input::get('USULAN_NAMA'),
                    'USULAN_SPESIFIKASI'    => Input::get('USULAN_SPESIFIKASI'),
                    'USULAN_SATUAN'         => Input::get('USULAN_SATUAN'),
                    // 'USULAN_TANGGAL'        => Input::get('USULAN_TANGGAL'),
                    'USULAN_STATUS'         => 0,
                    'USULAN_POSISI'         => $posisi,
                    'TIME_UPDATED'          => Carbon\Carbon::now(),
                    'USER_UPDATED'          => Auth::user()->id,
                    'IP_UPDATED'            => $_SERVER['REMOTE_ADDR']
                ]);
        }
        return "Berhasil disimpan!";
    }

    public function cetak($tahun,$id){
        $thn    = $tahun;
        $surat  = UsulanSurat::where('SURAT_NO',$id)->first();

        $test = new QrCode;
        $test::format('png');
        $test::merge('/public/assets/img/bandung.png');
        $test::size('130');
        $test::generate($id,'../public/qr/'.$id.'.png');

        $data   = UsulanKomponen::where('SURAT_ID',$surat->SURAT_ID)->get();
        $skpd   = UsulanSurat::where('SURAT_NO',$id)->first();

        $tgl    = Carbon\Carbon::now()->format('d');
        $bln    = Carbon\Carbon::now()->format('m');
        $tahun  = Carbon\Carbon::now()->format('Y');
        $bulan  = ['',
                        'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember'
                        ];
        $date   = $tgl.' '.$bulan[$bln*1].' '.$tahun;
        return View('eharga.cetak',['tahun'=>$tahun,'data'=>$data,'skpd'=>$skpd,'x'=>1,'y'=>1,'z'=>1,'date'=>$date,'surat'=>$surat,'thn'=>$thn]);  
    }

    public function getNotif($tahun){
        if(substr(Auth::user()->mod,0,1 == 1)){
            return UsulanKomponen::where('USULAN_POSISI',4)->whereRaw('"SURAT_ID" IS NOT NULL')->count();
        }elseif(Auth::user()->level == 2){
            $skpd      = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->first();
            $datauser  = UserBudget::whereHas('user',function($q){
                            $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                        })->where('SKPD_ID',$skpd->SKPD_ID)->value('USER_ID');
            if($skpd->skpd->SKPD_JENIS == 1){
                $pd     = SKPD::where('SKPD_JENIS', 2)->get();
                $i = 0;
                foreach($pd as $pd){
                    $id     = $pd->SKPD_ID;
                    $user[$i]   = UserBudget::where('SKPD_ID',$id)->where('TAHUN',$tahun)
                                            ->whereHas('user',function($q){
                                                $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                                            })->value('USER_ID');
                    $i++;
                }
                $user[$i] = $datauser;
            }elseif($skpd->skpd->SKPD_JENIS == 3){
                $pd     = SKPD::where('SKPD_JENIS', 4)->get();
                $i = 0;
                foreach($pd as $pd){
                    $id     = $pd->SKPD_ID;
                    $user[$i]   = UserBudget::where('SKPD_ID',$id)->where('TAHUN',$tahun)
                                            ->whereHas('user',function($q){
                                                $q->whereRaw('substring("mod" from 4 for 1) = \'1\'');
                                            })->value('USER_ID');
                    $i++;
                }
                $user[$i] = $datauser;
            }elseif($skpd->skpd->SKPD_JENIS == 2){
                $user[0]    = null; 
            }else{
                $user[0]    = $datauser;
            }
                $data   = UsulanKomponen::whereIn('USER_CREATED',$user)
                                        ->where('USULAN_POSISI',3)
                                        ->where('USULAN_STATUS',0)
                                        ->count();
            return $data;
        }elseif(substr(Auth::user()->mod,3,1 == 1)){
            return UsulanKomponen::where('USULAN_POSISI',4)->where('SURAT_ID',null)->where('USER_CREATED',Auth::user()->id)->count();
        }
    }

    public function delete($tahun){
        UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->delete();
        return 'Berhasil!';
    }

    public function cancel($tahun){
        UsulanKomponen::where('USULAN_ID',Input::get('USULAN_ID'))->update(['USULAN_POSISI'=>0]);
        return 'Berhasil';
    }


    public function getDataSurat($tahun){
        $skpd   = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        $data   = UsulanSurat::where('SKPD_ID',$skpd)
                            ->whereHas('usulan',function($q) use ($tahun){
                                $q->where('USULAN_TAHUN',$tahun);
                            })->get();
        $view   = array();
        $i  = 1;
        foreach ($data as $data) {
            array_push($view, array( 'NO'       =>$i++,
                                     'ID'       =>$data->SURAT_NO,
                                     'AKSI'     =>"<a class='btn btn-success' href='/harga/".$tahun."/usulan/surat/".$data->SURAT_NO."' target='_blank'><i class='fa fa-print'></i> Cetak</a>"));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out); 
    }

}
