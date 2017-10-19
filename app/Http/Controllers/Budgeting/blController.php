<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use View;
use Response;
use Auth;
use Carbon;
use App\Model\SKPD;
use App\Model\Program;
use App\Model\Kegiatan;
use App\Model\JenisGiat;
use App\Model\SumberDana;
use App\Model\Subunit;
use App\Model\Pagu;
use App\Model\Sasaran;
use App\Model\Tag;
use App\Model\Lokasi;
use App\Model\Satuan;
use App\Model\BL;
use App\Model\BLPerubahan;
use App\Model\Indikator;
use App\Model\Kunci;
use App\Model\Kunciperubahan;
use App\Model\Pekerjaan;
use App\Model\Rekening;
use App\Model\Komponen;
use App\Model\Rekom;
use App\Model\Rincian;
use App\Model\RincianPerubahan;
use App\Model\User;
use App\Model\Staff;
use App\Model\UserBudget;
use App\Model\Tahapan;
use App\Model\Log;
use App\Model\Subrincian;
use App\Model\SubrincianPerubahan;
use App\Model\RekapRincian;
use App\Model\Progunit;
use App\Model\Output;
use App\Model\Outcome;
use App\Model\Impact;
use App\Model\RincianArsip;
use App\Model\RincianArsipPerubahan;
use App\Model\Urgensi;
use App\Model\Realisasi;
class blController extends Controller
{
	public function __construct(){
        $this->middleware('auth');
    }
    //SHOW
    public function index($tahun,$status){

        if($status == 'murni') return $this->showMurni($tahun,$status);
        else return $this->showPerubahan($tahun,$status);
    }

    public function showMurni($tahun,$status){
        //$now        = Carbon\Carbon::now()->format('Y-m-d h:m:s');
        $now = date('Y-m-d H:m:s');
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)
                            ->where('TAHAPAN_STATUS','murni')
                            ->orderBy('TAHAPAN_ID','desc')->first();
        if($tahapan){
            if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
                $thp    = 1;
            }else{
                $thp    = 0;
            }
        }else{
            $thp    = 0;
        }
        $bl         = BL::where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->get();
        $skpd_      = $this->getSKPD($tahun);
        $user       = User::whereHas('userbudget', function($q) use ($skpd_){
                            $q->where('SKPD_ID',$skpd_);
                        })->where('app',3)->where('level',1)->get();

        $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
        $skpd_      = array(); 
        $i = 0;
        foreach($skpd as $s){
            $skpd_[$i]   = $s->SKPD_ID;
            $i++;
        }
        if(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->level == 0){
            $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->get();
        }else{
            $skpd       = SKPD::whereIn('SKPD_ID',$skpd_)->where('SKPD_TAHUN',$tahun)->get();
        }

        $id        = $this->getSKPD($tahun);
        if(Auth::user()->level == 1 or Auth::user()->level == 2){
            $blpagu    = BL::whereHas('subunit',function($q) use ($id){
                                    $q->where('SKPD_ID',$id);
                            })->where('BL_TAHUN',$tahun)->sum('BL_PAGU');
            $pagu      = SKPD::where('SKPD_ID',$id)->value('SKPD_PAGU');
            $rincian   = Rincian::whereHas('bl',function($r) use($id){
                            $r->whereHas('subunit',function($s) use ($id){
                                    $s->where('SKPD_ID',$id);
                            });
                        })->sum('RINCIAN_TOTAL');
        }else{
            $blpagu     = 0;
            $pagu       = 0;
            $rincian    = 0;
        }

        return View('budgeting.belanja-langsung.index',['tahun'=>$tahun,'status'=>$status,'bl'=>$bl,'skpd'=>$skpd,'user'=>$user,'thp'=>$thp,'blpagu'=>$blpagu,'rincian'=>$rincian,'pagu'=>$pagu]);
    }

    public function showPerubahan($tahun,$status){
        //$now        = Carbon\Carbon::now()->format('Y-m-d h:m:s');
        $now = date('Y-m-d H:m:s');
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)
                            ->where('TAHAPAN_STATUS','perubahan')
                            ->orderBy('TAHAPAN_ID','desc')->first();
        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }
        $bl         = BL::where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->get();
        $skpd_      = $this->getSKPD($tahun);
        $user       = User::whereHas('userbudget', function($q) use ($skpd_){
                            $q->where('SKPD_ID',$skpd_);
                        })->where('app',3)->where('level',1)->get();

        $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
        $skpd_      = array(); 
        $i = 0;
        foreach($skpd as $s){
            $skpd_[$i]   = $s->SKPD_ID;
            $i++;
        }
        if(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->level == 0){
            $skpd       = SKPD::where('SKPD_TAHUN',$tahun)->get();
        }else{
            $skpd       = SKPD::whereIn('SKPD_ID',$skpd_)->where('SKPD_TAHUN',$tahun)->get();
        }

        $id        = $this->getSKPD($tahun);

        if(Auth::user()->level == 1 or Auth::user()->level == 2){
            $blpagu    = BLPerubahan::whereHas('subunit',function($q) use ($id){
                                    $q->where('SKPD_ID',$id);
                            })->where('BL_TAHUN',$tahun)->sum('BL_PAGU');

            $pagu      = SKPD::where('SKPD_ID',$id)->value('SKPD_PAGU');

            $rincian   = RincianPerubahan::whereHas('bl',function($r) use($id){
                            $r->whereHas('subunit',function($s) use ($id){
                                    $s->where('SKPD_ID',$id);
                            });
                        })->sum('RINCIAN_TOTAL');

        }else{
            $blpagu     = 0;
            $pagu       = 0;
            $rincian    = 0;
        }

       // $kunci = Kunciperubahan::

        return View('budgeting.belanja-langsung.index_perubahan',['tahun'=>$tahun,'status'=>$status,'bl'=>$bl,'skpd'=>$skpd,'user'=>$user,'thp'=>$thp,'blpagu'=>$blpagu,'rincian'=>$rincian,'pagu'=>$pagu]);
    }

    public function showDetail($tahun,$status,$id){
        //$now        = Carbon\Carbon::now()->format('Y-m-d h:m:s');
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
        if($status == 'murni') $bl         = BL::where('BL_TAHUN',$tahun)->where('BL_ID',$id)->first();
        else $bl         = BLPerubahan::where('BL_TAHUN',$tahun)->where('BL_ID',$id)->first();

        $pekerjaan  = Pekerjaan::all();
        $satuan     = Satuan::all();
        if($status == 'murni')
        $rincian    = Rincian::where('BL_ID',$id)->sum('RINCIAN_TOTAL');
        else
        $rincian    = RincianPerubahan::where('BL_ID',$id)->sum('RINCIAN_TOTAL');
        $staff      = Staff::where('BL_ID',$id)->get();
        $mod        = 0;
        foreach($staff as $s){
            if($s->USER_ID == Auth::user()->id) $mod = 1;
        }

        if($status == 'murni') $tag         = BL::where('BL_TAHUN',$tahun)->where('BL_ID',$id)->value('BL_TAG');
        else $tag         = BLPerubahan::where('BL_TAHUN',$tahun)->where('BL_ID',$id)->value('BL_TAG');
        $tag         = str_replace('{', '', $tag);
        $tag         = str_replace('}', '', $tag);
        $tag         = explode(',', $tag);            
        $tagView     = array();
        $i           = 0;
        if($status == 'murni') $subrincian  = Subrincian::where('BL_ID',$id)->orderBy('SUBRINCIAN_NAMA')->get();
        else $subrincian  = SubrincianPerubahan::where('BL_ID',$id)->orderBy('SUBRINCIAN_NAMA')->get();
        if($tag){
            foreach($tag as $t){
                $tagView[$i]    = Tag::where('TAG_ID',$t)->value('TAG_NAMA');
                $i++;
            }
        }


        // $totalRKPD  = RekapRincian::where('BL_ID',$id)->whereHas('tahapan',function($q) use ($tahun){
        //                     $q->where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RKPD');
        //                 })->sum('RINCIAN_TOTAL');
        $totalRKPD  = 0;
        // $totalPPAS  = RekapRincian::where('BL_ID',$id)->whereHas('tahapan',function($q) use ($tahun){
        //                     $q->where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','KUA/PPAS');
        //                 })->sum('RINCIAN_TOTAL');
        $totalPPAS  = 0;
        // $totalRAPBD = RekapRincian::where('BL_ID',$id)->whereHas('tahapan',function($q) use ($tahun){
        //                     $q->where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','RAPBD');
        //                 })->sum('RINCIAN_TOTAL');
        $totalRAPBD = 0;
        // $totalAPBD = RekapRincian::where('BL_ID',$id)->whereHas('tahapan',function($q) use ($tahun){
        //                     $q->where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA','APBD');
        //                 })->sum('RINCIAN_TOTAL');
        $totalAPBD  = 0;
        $program    = Kegiatan::where('KEGIATAN_ID',$bl->KEGIATAN_ID)->value('PROGRAM_ID');
        $outcome    = Outcome::where('PROGRAM_ID',$program)->get();
        $impact     = Impact::where('PROGRAM_ID',$program)->get();
        $output     = Output::where('BL_ID',$id)->get();
        if($status == 'murni')
        return View('budgeting.belanja-langsung.detail',['tahun'=>$tahun,'status'=>$status,'bl'=>$bl,'pekerjaan'=>$pekerjaan,'BL_ID'=>$id,'rinciantotal'=>$rincian,'satuan'=>$satuan,'mod'=>$mod,'thp'=>$thp,'rkpd'=>$totalRKPD,'ppas'=>$totalPPAS,'rapbd'=>$totalRAPBD,'apbd'=>$totalAPBD,'tag'=>$tagView,'subrincian'=>$subrincian,'outcome'=>$outcome,'output'=>$output,'impact'=>$impact]);
        else
        return View('budgeting.belanja-langsung.detail_perubahan',['tahun'=>$tahun,'status'=>$status,'bl'=>$bl,'pekerjaan'=>$pekerjaan,'BL_ID'=>$id,'rinciantotal'=>$rincian,'satuan'=>$satuan,'mod'=>$mod,'thp'=>$thp,'rkpd'=>$totalRKPD,'ppas'=>$totalPPAS,'rapbd'=>$totalRAPBD,'apbd'=>$totalAPBD,'tag'=>$tagView,'subrincian'=>$subrincian,'outcome'=>$outcome,'output'=>$output,'impact'=>$impact]);
    }

    public function showDetailArsip($tahun,$status,$id){
        return View('budgeting.belanja-langsung.detailarsip',['tahun'=>$tahun,'status'=>$status,'bl'=>$id]);
    }

    public function showRincian($tahun,$status,$id){
        if($status == 'murni') return $this->showRincianMurni($tahun,$status,$id);
        else return $this->showRincianPerubahan($tahun,$status,$id);
    }

    public function showRincianMurni($tahun,$status,$id){
        //$now        = Carbon\Carbon::now()->format('Y-m-d H:m:s');
        $now = date('Y-m-d H:m:s');
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->orderBy('TAHAPAN_ID','desc')->first();
        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }

        $data       = Rincian::where('BL_ID',$id)->get();
        $staff      = Staff::where('BL_ID',$id)->get();
        $mod        = 0;
        foreach($staff as $s){
            if($s->USER_ID == Auth::user()->id) $mod = 1;
        }
        $view       = array();
        $i         = 1;
        $pajak      = '';
        foreach ($data as $data) {
            if(( $data->bl->kunci->KUNCI_RINCIAN == 0 and $mod == 1 and $thp == 1 ) or Auth::user()->level == 8){
                if($data->REKENING_ID == 0 or empty($data->subrincian)){
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return rinci(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a></li><li><a onclick="return hapus(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-close"></i>Hapus</a></li><li class="divider"></li><li><a onclick="return info(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                }else{
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return ubah(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a></li><li><a onclick="return hapus(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-close"></i>Hapus</a></li><li class="divider"></li><li><a onclick="return info(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                }
            }else{
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return info(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';                
            }

            if($data->RINCIAN_PAJAK == 10) $pajak = '<span class="text-success"><i class="fa fa-check"></i></span>';
            else $pajak = '<span class="text-danger"><i class="fa fa-close"></i></span>';
            if($data->subrincian) $sub = $data->subrincian->SUBRINCIAN_NAMA;
            else $sub = '-';
            if($data->PEKERJAAN_ID == '4' || $data->PEKERJAAN_ID == '5'){
                $namakomponen   = $data->komponen->KOMPONEN_KODE.'<br><p class="text-orange">'.explode("#", $data->RINCIAN_KETERANGAN)[0].'</p>';
                $hargakomponen  = number_format(explode("#", $data->RINCIAN_KETERANGAN)[1],0,'.',',').'<br><p class="text-orange">'.$data->RINCIAN_KOEFISIEN.'</p>';
            }else{
                $namakomponen   = $data->komponen->KOMPONEN_KODE.'<br><p class="text-orange">'.$data->RINCIAN_KOMPONEN.'</p>';
                $hargakomponen  = number_format($data->RINCIAN_HARGA,0,'.',',').'<br><p class="text-orange">'.$data->RINCIAN_KOEFISIEN.'</p>';
            }
            if(Auth::user()->level == 8){
                 $checkbox = '<div class="m-t-n-lg">
                                  <label class="i-checks">
                                    <input type="checkbox" value="'.$data->RINCIAN_ID.'" class="cb" id="cb-'.$data->RINCIAN_ID.'"/><i></i>
                                  </label>
                            </div>';
                $no        = $checkbox.$no;

            }
            array_push($view, array( 'NO'             =>$no,
                                     'REKENING'       =>$data->rekening->REKENING_KODE.'<br><p class="text-orange">'.$data->rekening->REKENING_NAMA.'</p>',
                                     'KOMPONEN'       =>$namakomponen,
                                     'SUB'            =>$sub."<br><span class='text-orange'>".$data->RINCIAN_KETERANGAN."</span>",
                                     'PAJAK'          =>$pajak,
                                     'HARGA'          =>$hargakomponen,
                                     'TOTAL' =>number_format($data->RINCIAN_TOTAL,0,'.',',')));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
        return $view;
    }

    public function showRincianPerubahan($tahun,$status,$id){
        //$now        = Carbon\Carbon::now()->format('Y-m-d H:m:s');
        $now = date('Y-m-d H:m:s');
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->orderBy('TAHAPAN_ID','desc')->first();
        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }
        $this->tahun=$tahun;
        $data       = RincianPerubahan::where('BL_ID',$id)->whereHas('bl',function($query){$query->where('BL_TAHUN','=',$this->tahun);})->get();
        $data1       = RincianPerubahan::where('BL_ID',$id)->whereHas('bl',function($query){$query->where('BL_TAHUN','=',$this->tahun);})->select('RINCIAN_ID')->get()->toArray();
        $data_      = Rincian::where('BL_ID',$id)
                            ->whereNotIn('RINCIAN_ID',$data1)
                            ->whereHas('bl',function($query){$query->where('BL_TAHUN','=',$this->tahun);})
                            ->get();
        $staff      = Staff::where('BL_ID',$id)->get();
        $mod        = 0;
        foreach($staff as $s){
            if($s->USER_ID == Auth::user()->id) $mod = 1;
        }
        $view       = array();
        $i         = 1;
        $pajak      = '';
        //print_r($data);exit;
        foreach ($data as $data) {
            if(( $data->bl->kunci->KUNCI_RINCIAN == 0 and $mod == 1 and $thp == 1 and Auth::user()->active == 1) or Auth::user()->level == 8 ){
                if($data->REKENING_ID == 0 or empty($data->SUBRINCIAN_ID)){
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return rinci(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a></li><li><a onclick="return hapus(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-close"></i>Hapus</a></li><li class="divider"></li><li><a onclick="return info(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                }else{
                   /* if($data->KOMPONEN_ID == 0){
                        $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return hapus(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-close"></i>Hapus</a></li><li class="divider"></li><li><a onclick="return info(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                    }else{*/
                        $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return ubah(\''.$data->RINCIAN_ID.'\')">
                        <i class="fa fa-pencil-square"></i>Ubah</a></li>
                        <li><a onclick="return hapus(\''.$data->RINCIAN_ID.'\')">
                        <i class="fa fa-close"></i>Hapus</a></li><li class="divider"></li><li><a onclick="return info(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                   // }
                }
            }else{
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return info(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';                
            }

            if($data->RINCIAN_PAJAK == 10) $pajak = '<span class="text-success"><i class="fa fa-check"></i></span>';
            else $pajak = '<span class="text-danger"><i class="fa fa-close"></i></span>';
            if($data->subrincian) $sub = $data->subrincian->SUBRINCIAN_NAMA;
            else $sub = '-';
            if($data->PEKERJAAN_ID == '4' || $data->PEKERJAAN_ID == '5'){
                $namakomponen   = $data->komponen->KOMPONEN_KODE.'<br><p class="text-orange">'.explode("#", $data->RINCIAN_KETERANGAN)[0].'</p>';
                $hargakomponen  = number_format(explode("#", $data->RINCIAN_KETERANGAN)[1],0,'.',',').'<br><p class="text-orange">'.$data->RINCIAN_KOEFISIEN.'</p>';
            }else{
                $namakomponen   = $data->komponen->KOMPONEN_KODE.'<br><p class="text-orange">'.$data->RINCIAN_KOMPONEN.'</p>';
                $hargakomponen  = number_format($data->RINCIAN_HARGA,0,'.',',').'<br><p class="text-orange">'.$data->RINCIAN_KOEFISIEN.'</p>';
            }
            if(Auth::user()->level == 8){
                 $checkbox = '<div class="m-t-n-lg">
                                  <label class="i-checks">
                                    <input type="checkbox" value="'.$data->RINCIAN_ID.'" class="cb" id="cb-'.$data->RINCIAN_ID.'"/><i></i>
                                  </label>
                            </div>';
                $no        = $checkbox.$no;

            }
            $rinciansebelum     = Rincian::where('RINCIAN_ID',$data->RINCIAN_ID)->whereHas('bl',function($query){$query->where('BL_TAHUN','=',$this->tahun);})->first();
            if($rinciansebelum){
                if($rinciansebelum->RINCIAN_PAJAK == 10) $pajaskebelum = '<span class="text-success"><i class="fa fa-check"></i></span>';
                else $pajaskebelum = '<span class="text-danger"><i class="fa fa-close"></i></span>';
                if($rinciansebelum->PEKERJAAN_ID == '4' || $rinciansebelum->PEKERJAAN_ID == '5'){
                    $namakomponensebelum   = $rinciansebelum->komponen->KOMPONEN_KODE.'<br><p class="text-orange">'.explode("#", $rinciansebelum->RINCIAN_KETERANGAN)[0].'</p>';
                    $hargakomponensebelum  = number_format(explode("#", $rinciansebelum->RINCIAN_KETERANGAN)[1],0,'.',',').'<br><p class="text-orange">'.$rinciansebelum->RINCIAN_KOEFISIEN.'</p>';
                }else{
                    $namakomponensebelum   = $rinciansebelum->komponen->KOMPONEN_KODE.'<br><p class="text-orange">'.$rinciansebelum->RINCIAN_KOMPONEN.'</p>';
                    $hargakomponensebelum  = number_format($rinciansebelum->RINCIAN_HARGA,0,'.',',').'<br><p class="text-orange">'.$rinciansebelum->RINCIAN_KOEFISIEN.'</p>';
                }
                if($data->RINCIAN_TOTAL != $rinciansebelum->RINCIAN_TOTAL) $tanda = "<span class='text text-danger'><i class='fa fa-asterisk'></i></span>";
                else $tanda = "";
                array_push($view, array( 'NO'             =>$tanda."<br>".$no,
                                         'REKENING'       =>$data->rekening->REKENING_KODE.'<br><p class="text-orange">'.$data->rekening->REKENING_NAMA.'</p>',
                                         'KOMPONEN'       =>$namakomponen,
                                         'SUB'            =>$sub."<br><span class='text-orange'>".$data->RINCIAN_KETERANGAN."</span>",
                                         'PAJAK_SESUDAH'  =>$pajak,
                                         'HARGA_SESUDAH'  =>$hargakomponen,
                                         'TOTAL_SESUDAH'  =>number_format($data->RINCIAN_TOTAL,0,'.',','),
                                         'PAJAK_SEBELUM'  =>$pajaskebelum,
                                         'HARGA_SEBELUM'  =>$hargakomponensebelum,
                                         'TOTAL_SEBELUM'  =>number_format($rinciansebelum->RINCIAN_TOTAL,0,'.',',')));
            }else{
                $tanda = "<span class='text text-danger'><i class='fa fa-asterisk'></i></span>";
                array_push($view, array( 'NO'             =>$tanda."<br>".$no,
                                         'REKENING'       =>$data->rekening->REKENING_KODE.'<br><p class="text-orange">'.$data->rekening->REKENING_NAMA.'</p>',
                                         'KOMPONEN'       =>$namakomponen,
                                         'SUB'            =>$sub."<br><span class='text-orange'>".$data->RINCIAN_KETERANGAN."</span>",
                                         'PAJAK_SESUDAH'  =>$pajak,
                                         'HARGA_SESUDAH'  =>$hargakomponen,
                                         'TOTAL_SESUDAH'  =>number_format($data->RINCIAN_TOTAL,0,'.',','),
                                         'PAJAK_SEBELUM'  =>'-',
                                         'HARGA_SEBELUM'  =>'-',
                                         'TOTAL_SEBELUM'  =>'-'));
            }
        }

        foreach ($data_ as $data_) {
            if(( $data_->bl->kunci->KUNCI_RINCIAN == 0 and $mod == 1 and $thp == 1 and Auth::user()->active == 1) or Auth::user()->level == 8){
                if($data_->REKENING_ID == 0 or empty($data_->SUBRINCIAN_ID)){
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return rinci(\''.$data_->RINCIAN_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a></li><li><a onclick="return hapus(\''.$data_->RINCIAN_ID.'\')"><i class="fa fa-close"></i>Hapus</a></li><li class="divider"></li><li><a onclick="return info(\''.$data_->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                }else{
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return ubah(\''.$data_->RINCIAN_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a></li><li><a onclick="return hapus(\''.$data_->RINCIAN_ID.'\')"><i class="fa fa-close"></i>Hapus</a></li><li class="divider"></li><li><a onclick="return info(\''.$data_->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                }
            }else{
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a><i class="fa fa-pencil-square"></i>Ubah <i class="fa fa-lock"></i></a></li><li><a><i class="fa fa-close"></i>Hapus <i class="fa fa-lock"></i> </a></li><li class="divider"></li><li><a onclick="return info(\''.$data_->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';                
            }

            if($data_->RINCIAN_PAJAK == 10) $pajak = '<span class="text-success"><i class="fa fa-check"></i></span>';
            else $pajak = '<span class="text-danger"><i class="fa fa-close"></i></span>';
            if($data_->subrincian) $sub = $data_->subrincian->SUBRINCIAN_NAMA;
            else $sub = '-';
            if($data_->PEKERJAAN_ID == '4' || $data_->PEKERJAAN_ID == '5'){
                $namakomponen   = $data_->komponen->KOMPONEN_KODE.'<br><p class="text-orange">'.explode("#", $data_->RINCIAN_KETERANGAN)[0].'</p>';
                $hargakomponen  = number_format(explode("#", $data_->RINCIAN_KETERANGAN)[1],0,'.',',').'<br><p class="text-orange">'.$data_->RINCIAN_KOEFISIEN.'</p>';
            }else{
                $namakomponen   = $data_->komponen->KOMPONEN_KODE.'<br><p class="text-orange">'.$data_->RINCIAN_KOMPONEN.'</p>';
                $hargakomponen  = number_format($data_->RINCIAN_HARGA,0,'.',',').'<br><p class="text-orange">'.$data_->RINCIAN_KOEFISIEN.'</p>';
            }
            if(Auth::user()->level == 8){
                 $checkbox = '<div class="m-t-n-lg">
                                  <label class="i-checks">
                                    <input type="checkbox" value="'.$data_->RINCIAN_ID.'" class="cb" id="cb-'.$data_->RINCIAN_ID.'"/><i></i>
                                  </label>
                            </div>';
                $no        = $checkbox.$no;

            }
                $tanda = "<span class='text text-danger'><i class='fa fa-asterisk'></i></span>";
                array_push($view, array( 'NO'             =>$tanda."<br>".$no,
                                         'REKENING'       =>$data_->rekening->REKENING_KODE.'<br><p class="text-orange">'.$data_->rekening->REKENING_NAMA.'</p>',
                                         'KOMPONEN'       =>$namakomponen,
                                         'SUB'            =>$sub."<br><span class='text-orange'>".$data_->RINCIAN_KETERANGAN."</span>",
                                         'PAJAK_SEBELUM'  =>$pajak,
                                         'HARGA_SEBELUM'  =>$hargakomponen,
                                         'TOTAL_SEBELUM'  =>number_format($data_->RINCIAN_TOTAL,0,'.',','),
                                         'PAJAK_SESUDAH'  =>'-',
                                         'HARGA_SESUDAH'  =>'-',
                                         'TOTAL_SESUDAH'  =>'-'));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
        return $view;
    }

    public function showRincianArsip($tahun,$status,$id){
        //$now        = Carbon\Carbon::now()->format('Y-m-d H:m:s');
        $now = date('Y-m-d H:m:s');
        // print_r($now);exit();
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->orderBy('TAHAPAN_ID','desc')->first();
        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }

        $data       = RincianArsip::where('BL_ID',$id)->get();
        $staff      = Staff::where('BL_ID',$id)->get();
        $mod        = 0;
        foreach($staff as $s){
            if($s->USER_ID == Auth::user()->id) $mod = 1;
        }
        $view       = array();
        $i         = 1;
        $pajak      = '';
        foreach ($data as $data) {
            if(( $data->bl->kunci->KUNCI_RINCIAN == 0 and $mod == 1 and $thp == 1 ) or Auth::user()->level == 8){
                if($data->REKENING_ID == 0 or empty($data->subrincian)){
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return rinci(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a></li><li><a onclick="return hapus(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-close"></i>Hapus</a></li><li class="divider"></li><li><a onclick="return info(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                }else{
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return ubah(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a></li><li><a onclick="return hapus(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-close"></i>Hapus</a></li><li class="divider"></li><li><a onclick="return info(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';
                }
            }else{
                $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a><i class="fa fa-pencil-square"></i>Ubah <i class="fa fa-lock"></i></a></li><li><a><i class="fa fa-close"></i>Hapus <i class="fa fa-lock"></i> </a></li><li class="divider"></li><li><a onclick="return info(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';                
            }

            if($data->RINCIAN_PAJAK == 10) $pajak = '<span class="text-success"><i class="fa fa-check"></i></span>';
            else $pajak = '<span class="text-danger"><i class="fa fa-close"></i></span>';
            if($data->subrincian) $sub = $data->subrincian->SUBRINCIAN_NAMA;
            else $sub = '-';
            if($data->PEKERJAAN_ID == '4' || $data->PEKERJAAN_ID == '5'){
                $namakomponen   = $data->komponen->KOMPONEN_KODE.'<br><p class="text-orange">'.explode("#", $data->RINCIAN_KETERANGAN)[0].'</p>';
                $hargakomponen  = number_format(explode("#", $data->RINCIAN_KETERANGAN)[1],0,'.',',').'<br><p class="text-orange">'.$data->RINCIAN_KOEFISIEN.'</p>';
            }else{
                $namakomponen   = $data->komponen->KOMPONEN_KODE.'<br><p class="text-orange">'.$data->komponen->KOMPONEN_NAMA.'</p>';
                $hargakomponen  = number_format($data->komponen->KOMPONEN_HARGA,0,'.',',').'<br><p class="text-orange">'.$data->RINCIAN_KOEFISIEN.'</p>';
            }
            if(Auth::user()->level == 8){
                 $checkbox = '<div class="m-t-n-lg">
                                  <label class="i-checks">
                                    <input type="checkbox" value="'.$data->RINCIAN_ID.'" class="cb" id="cb-'.$data->RINCIAN_ID.'"/><i></i>
                                  </label>
                            </div>';
                $no        = $checkbox.$no;

            }
            array_push($view, array( 'NO'             =>$no,
                                     'REKENING'       =>$data->rekening->REKENING_KODE.'<br><p class="text-orange">'.$data->rekening->REKENING_NAMA.'</p>',
                                     'KOMPONEN'       => $namakomponen,
                                     'SUB'            =>$sub."<br><span class='text-orange'>".$data->RINCIAN_KETERANGAN."</span>",
                                     'PAJAK'          =>$pajak,
                                     'HARGA'          =>$hargakomponen,
                                     'TOTAL'          =>number_format($data->RINCIAN_TOTAL,0,'.',',')));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
        return $view;
    }

    public function detailRincian($tahun,$status,$id){
        if($status == 'murni')
        $data   = Rincian::where('RINCIAN_ID',$id)->first();
        else
        $data   = RincianPerubahan::where('RINCIAN_ID',$id)->first();
        $koef   = explode(' x ',$data->RINCIAN_KOEFISIEN);
        if(!empty($koef[1])) {
            $v1     = explode(' ',$koef[1])[0];
            $k1     = explode(' ',$koef[1])[1];
        }else{
            $v1     = "";
            $k1     = "";
        }
        if(!empty($koef[2])) {
            $v2     = explode(' ',$koef[2])[0];
            $k2     = explode(' ',$koef[2])[1];
        }else{
            $v2     = "";
            $k2     = "";
        }
        if(!empty($koef[3])) {
            $v3     = explode(' ',$koef[3])[0];
            $k3     = explode(' ',$koef[3])[1];
        }else{
            $v3     = "";
            $k3     = "";
        }
        $out    = [ 'DATA'          => $data,
                    'REKENING_KODE' => $data->rekening->REKENING_KODE,
                    'REKENING_NAMA' => $data->rekening->REKENING_NAMA,
                    'KOMPONEN_KODE' => $data->komponen->KOMPONEN_KODE,
                    'KOMPONEN_NAMA' => $data->komponen->KOMPONEN_NAMA,
                    'VOL1'          => explode(' ',$koef[0])[0],
                    'SATUAN1'       => explode(' ',$koef[0])[1],
                    'VOL2'          => $v1,
                    'SATUAN2'       => $k1,
                    'VOL3'          => $v2,
                    'SATUAN3'       => $k2,
                    'VOL4'          => $v3,
                    'SATUAN4'       => $k3,
                    'PEKERJAAN_NAMA'=> $data->pekerjaan->PEKERJAAN_NAMA];
        return $out;
    }
    //ADD
    public function add($tahun,$status){
        $skpd           = $this->getSKPD($tahun);
        $subunit        = Subunit::where('SKPD_ID',$skpd)->orderBy('SUB_ID')->get();
        $program        = Progunit::where('SKPD_ID',$skpd)->orderBy('PROGRAM_ID')->get();
        $jenis          = JenisGiat::all();
        $sumber         = SumberDana::all();
        $pagu           = Pagu::all();
        $sasaran        = Sasaran::all();
        $lokasi         = Lokasi::all();
        $tag            = Tag::all();
        $satuan         = Satuan::all();

        $bulan          = array('',
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
                         'Desember');
        
        $data           = [
                            'subunit'   => $subunit,
                            'program'   => $program,
                            'tahun'     => $tahun,
                            'status'    => $status,
                            'jenis'     => $jenis,
                            'sumber'    => $sumber,
                            'pagu'      => $pagu,
                            'sasaran'   => $sasaran,
                            'bulan'     => $bulan,
                            'lokasi'    => $lokasi,
                            'tag'       => $tag,
                            'satuan'    => $satuan
                         ];
       // dd($status);                 
        return View('budgeting.belanja-langsung.add',$data);

    }

    public function submitDetail($tahun, $status){
        $tag        = Input::get('tag');
        settype($tag, 'array'); // can be called with a scalar or array
        $result = array();
        foreach ($tag as $t) {
            if (is_array($t)) {
                $result[] = to_pg_array($t);
            } else {
                $t = str_replace('"', '\\"', $t); // escape double quote
                if (! is_numeric($t)) // quote only non-numeric values
                    $t = '"' . $t . '"';
                $result[] = $t;
            }
        }
        $tag_   = '{' . implode(",", $result) . '}';
        if($status == 'murni') $s = 1;
        else $s = 2;

        if($status == 'murni'){
            $bl                     = new BL;
            $bl->BL_TAHUN           = $tahun;
            $bl->KEGIATAN_ID        = Input::get('kegiatan');
            $bl->JENIS_ID           = Input::get('jenis-kegiatan');
            $bl->SUMBER_ID          = Input::get('sumber-dana');
            $bl->PAGU_ID            = Input::get('kategori-pagu');
            $bl->BL_AWAL            = Input::get('waktu-awal');
            $bl->BL_AKHIR           = Input::get('waktu-akhir');
            $bl->SASARAN_ID         = Input::get('sasaran');
            $bl->LOKASI_ID          = Input::get('lokasi');
            $bl->SUB_ID             = Input::get('sub_id');
            $bl->BL_TAG             = $tag_;
            $bl->BL_STATUS          = $s;
            $bl->BL_VALIDASI        = '0';
            $bl->BL_DELETED         = '0';
            $bl->USER_CREATED       = Auth::user()->id;
            $bl->TIME_CREATED       = Carbon\Carbon::now();
            $bl->IP_CREATED         = $_SERVER['REMOTE_ADDR'];
            $bl->save();

            $id         = BL::where('BL_TAHUN',$tahun)->where('KEGIATAN_ID',Input::get('kegiatan'))->where('SUB_ID',Input::get('sub_id'))->value('BL_ID');

            $kunci      = new Kunci;
            $kunci->BL_ID                           = $id;
            $kunci->KUNCI_GIAT                      = 0;
            $kunci->KUNCI_RINCIAN                   = 0;
            $kunci->KUNCI_AKB                       = 0;
            $kunci->save();

        }else{
            $get_id      = BLPerubahan::where('BL_TAHUN',$tahun)->max('BL_ID');

            $bl         = new BLPerubahan;
            $bl->BL_TAHUN           = $tahun;
            $bl->BL_ID              = ($get_id+1);
            $bl->KEGIATAN_ID        = Input::get('kegiatan');
            $bl->JENIS_ID           = Input::get('jenis-kegiatan');
            $bl->SUMBER_ID          = Input::get('sumber-dana');
            $bl->PAGU_ID            = Input::get('kategori-pagu');
            $bl->BL_AWAL            = Input::get('waktu-awal');
            $bl->BL_AKHIR           = Input::get('waktu-akhir');
            $bl->SASARAN_ID         = Input::get('sasaran');
            $bl->LOKASI_ID          = Input::get('lokasi');
            $bl->SUB_ID             = Input::get('sub_id');
            $bl->BL_TAG             = $tag_;
            $bl->BL_STATUS          = $s;
            $bl->BL_VALIDASI        = '0';
            $bl->BL_DELETED         = '0';
            $bl->USER_CREATED       = Auth::user()->id;
            $bl->TIME_CREATED       = Carbon\Carbon::now();
            $bl->IP_CREATED         = $_SERVER['REMOTE_ADDR'];
            $bl->save();

            $id         = BLPerubahan::where('BL_TAHUN',$tahun)->where('KEGIATAN_ID',Input::get('kegiatan'))->where('SUB_ID',Input::get('sub_id'))->value('BL_ID');

            $kunci      = new Kunciperubahan;
            $get_id      = Kunciperubahan::max('KUNCI_ID')->first();
            $kunci->KUNCI_ID                        = ($get_id+1);
            $kunci->BL_ID                           = $id;
            $kunci->KUNCI_GIAT                      = 0;
            $kunci->KUNCI_RINCIAN                   = 0;
            $kunci->KUNCI_AKB                       = 0;
            $kunci->save();
        }

        $log        = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Menambahkan Belanja Langsung';
        $log->LOG_DETAIL                        = 'BL#'.$id;
        $log->save();

        return Redirect('main/'.$tahun.'/'.$status.'/belanja-langsung')->with('message_title','Success')->with('message','Sukses menambahkan data');
    }

    public function submitRincian($tahun,$status){
        if($status == 'murni') return $this->submitRincianMurni($tahun,$status);
        else return $this->submitRincianPerubahan($tahun,$status);
    }

    public function submitRincianMurni($tahun,$status){
        $koef       = Input::get('VOL1').' '.Input::get('SAT1');
        $vol        = Input::get('VOL1');
        if(!empty(Input::get('VOL2'))){
            $koef   = $koef.' x '.Input::get('VOL2').' '.Input::get('SAT2');
            $vol    = $vol * Input::get('VOL2');
        }
        if(!empty(Input::get('VOL3'))){
            $koef   = $koef.' x '.Input::get('VOL3').' '.Input::get('SAT3');
            $vol    = $vol * Input::get('VOL3');
        }
        if(!empty(Input::get('VOL4'))){
            $koef   = $koef.' x '.Input::get('VOL4').' '.Input::get('SAT4');
            $vol    = $vol * Input::get('VOL4');
        }
        // $total      = ( Komponen::where('KOMPONEN_ID',
        //     Input::get('KOMPONEN_ID'))->value('KOMPONEN_HARGA') * $vol ) + 
        // (( Input::get('RINCIAN_PAJAK')*(Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->value('KOMPONEN_HARGA')*$vol))/100);
        $total      = ( Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->value('KOMPONEN_HARGA') * $vol ) + (( Input::get('RINCIAN_PAJAK')*(Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->value('KOMPONEN_HARGA')*$vol))/100);
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->orderBy('TAHAPAN_ID','desc')->first();
        $totalBL    = BL::where('BL_ID',Input::get('BL_ID'))->value('BL_PAGU');
        $skpd       = $this->getSKPD($tahun);
        $totalOPD   = SKPD::where('SKPD_ID',$skpd)->where('SKPD_TAHUN',$tahun)->value('SKPD_PAGU');
        $now        = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');
        $nowOPD     = Rincian::whereHas('bl',function($q) use($skpd){
                            $q->whereHas('subunit',function($r) use($skpd){
                                $r->where('SKPD_ID',$skpd);
                            });
                        })->sum('RINCIAN_TOTAL');
        
        $hargakomponen  = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->value('KOMPONEN_HARGA');                          
        if($tahapan->TAHAPAN_KUNCI_GIAT == 1){
            if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                $total  = (Input::get('HARGA') * $vol)+((Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                if($total+$now <= $totalBL){
                    $rincian    = new Rincian;
                    $rincian->BL_ID                         = Input::get('BL_ID');
                    $rincian->REKENING_ID                   = Input::get('REKENING_ID');
                    $rincian->KOMPONEN_ID                   = Input::get('KOMPONEN_ID');
                    $rincian->RINCIAN_PAJAK                 = Input::get('RINCIAN_PAJAK');
                    $rincian->RINCIAN_VOLUME                = $vol;
                    $rincian->RINCIAN_KOEFISIEN             = $koef;
                    $rincian->SUBRINCIAN_ID                 = Input::get('SUBRINCIAN_ID');
                    if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                        $rincian->RINCIAN_KETERANGAN        = Input::get('KOMPONEN_NAMA')."#".Input::get('HARGA');
                        $rincian->RINCIAN_TOTAL             = ( Input::get('HARGA') * $vol ) + (( Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                    }else{
                        $rincian->RINCIAN_KETERANGAN        = Input::get('RINCIAN_KET');
                        $rincian->RINCIAN_TOTAL             = round($total);
                    }
                    $rincian->RINCIAN_HARGA                 = Input::get('HARGA');
                    $rincian->RINCIAN_KOMPONEN                  = Input::get('KOMPONEN_NAMA');
                    $rincian->PEKERJAAN_ID                  = Input::get('PEKERJAAN_ID');
                    $rincian->save();

                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log        = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }else{
                if($total+$now <= $totalBL){
                    $rincian    = new Rincian;
                    $rincian->BL_ID                         = Input::get('BL_ID');
                    $rincian->REKENING_ID                   = Input::get('REKENING_ID');
                    $rincian->KOMPONEN_ID                   = Input::get('KOMPONEN_ID');
                    $rincian->RINCIAN_PAJAK                 = Input::get('RINCIAN_PAJAK');
                    $rincian->RINCIAN_VOLUME                = $vol;
                    $rincian->RINCIAN_KOEFISIEN             = $koef;
                    $rincian->SUBRINCIAN_ID                 = Input::get('SUBRINCIAN_ID');
                    if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                        $rincian->RINCIAN_KETERANGAN        = Input::get('KOMPONEN_NAMA')."#".Input::get('HARGA');
                        $rincian->RINCIAN_TOTAL             = ( Input::get('HARGA') * $vol ) + (( Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                    }else{
                        $rincian->RINCIAN_KETERANGAN        = Input::get('RINCIAN_KET');
                        $rincian->RINCIAN_TOTAL             = round($total);
                    }
                    $rincian->RINCIAN_HARGA                 = $hargakomponen;
                    $rincian->RINCIAN_KOMPONEN                  = Input::get('KOMPONEN_NAMA');                    
                    $rincian->PEKERJAAN_ID                  = Input::get('PEKERJAAN_ID');
                    $rincian->save();

                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log        = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }
        }elseif($tahapan->TAHAPAN_KUNCI_OPD == 1){
            if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                $total  = (Input::get('HARGA') * $vol)+((Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                if($total+$nowOPD <= $totalOPD){
               // if($total <= $totalOPD){
                    $rincian    = new Rincian;
                    $rincian->BL_ID                         = Input::get('BL_ID');
                    $rincian->REKENING_ID                   = Input::get('REKENING_ID');
                    $rincian->KOMPONEN_ID                   = Input::get('KOMPONEN_ID');
                    $rincian->RINCIAN_PAJAK                 = Input::get('RINCIAN_PAJAK');
                    $rincian->RINCIAN_VOLUME                = $vol;
                    $rincian->RINCIAN_KOEFISIEN             = $koef;
                    $rincian->SUBRINCIAN_ID                 = Input::get('SUBRINCIAN_ID');
                    if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                        $rincian->RINCIAN_KETERANGAN        = Input::get('KOMPONEN_NAMA')."#".Input::get('HARGA');
                        $rincian->RINCIAN_TOTAL             = ( Input::get('HARGA') * $vol ) + (( Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                    }else{
                        $rincian->RINCIAN_KETERANGAN        = Input::get('RINCIAN_KET');
                        $rincian->RINCIAN_TOTAL             = round($total);
                    }
                    $rincian->RINCIAN_HARGA                 = Input::get('HARGA');
                    $rincian->RINCIAN_KOMPONEN                  = Input::get('KOMPONEN_NAMA');                    
                    $rincian->PEKERJAAN_ID                  = Input::get('PEKERJAAN_ID');
                    $rincian->save();

                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log        = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }else{
                 if($total+$nowOPD <= $totalOPD){
                //if($total <= $totalOPD){
                    $rincian    = new Rincian;
                    $rincian->BL_ID                         = Input::get('BL_ID');
                    $rincian->REKENING_ID                   = Input::get('REKENING_ID');
                    $rincian->KOMPONEN_ID                   = Input::get('KOMPONEN_ID');
                    $rincian->RINCIAN_PAJAK                 = Input::get('RINCIAN_PAJAK');
                    $rincian->RINCIAN_VOLUME                = $vol;
                    $rincian->RINCIAN_KOEFISIEN             = $koef;
                    $rincian->SUBRINCIAN_ID                 = Input::get('SUBRINCIAN_ID');
                    if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                        $rincian->RINCIAN_KETERANGAN        = Input::get('KOMPONEN_NAMA')."#".Input::get('HARGA');
                        $rincian->RINCIAN_TOTAL             = ( Input::get('HARGA') * $vol ) + (( Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                    }else{
                        $rincian->RINCIAN_KETERANGAN        = Input::get('RINCIAN_KET');
                        $rincian->RINCIAN_TOTAL             = round($total);
                    }
                    $rincian->RINCIAN_HARGA                 = $hargakomponen;
                    $rincian->RINCIAN_KOMPONEN                  = Input::get('KOMPONEN_NAMA');                    
                    $rincian->PEKERJAAN_ID                  = Input::get('PEKERJAAN_ID');
                    $rincian->save();

                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log        = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }
        }else{
            $rincian    = new Rincian;
            $rincian->BL_ID                         = Input::get('BL_ID');
            $rincian->REKENING_ID                   = Input::get('REKENING_ID');
            $rincian->KOMPONEN_ID                   = Input::get('KOMPONEN_ID');
            $rincian->RINCIAN_PAJAK                 = Input::get('RINCIAN_PAJAK');
            $rincian->RINCIAN_VOLUME                = $vol;
            $rincian->RINCIAN_KOEFISIEN             = $koef;
            $rincian->SUBRINCIAN_ID                 = Input::get('SUBRINCIAN_ID');
            if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                $rincian->RINCIAN_KETERANGAN        = Input::get('KOMPONEN_NAMA')."#".Input::get('HARGA');
                $rincian->RINCIAN_TOTAL             = ( Input::get('HARGA') * $vol ) + (( Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
            }else{
                $rincian->RINCIAN_KETERANGAN        = Input::get('RINCIAN_KET');
                $rincian->RINCIAN_TOTAL             = round($total);
            }
            $rincian->RINCIAN_HARGA                 = $hargakomponen;
            $rincian->RINCIAN_KOMPONEN                  = Input::get('KOMPONEN_NAMA');            
            $rincian->PEKERJAAN_ID                  = Input::get('PEKERJAAN_ID');
            $rincian->save();

            // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
            $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

            $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
            $log        = new Log;
            $log->LOG_TIME                          = Carbon\Carbon::now();
            $log->USER_ID                           = Auth::user()->id;
            $log->LOG_ACTIVITY                      = 'Menambahkan Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
            $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
            $log->save();        
            return number_format($totalrincian,0,'.',',');
        }
    }

    public function submitRincianPerubahan($tahun,$status){
        $koef       = Input::get('VOL1').' '.Input::get('SAT1');
        $vol        = Input::get('VOL1');
       // dd($vol);
        if(!empty(Input::get('VOL2'))){
            $koef   = $koef.' x '.Input::get('VOL2').' '.Input::get('SAT2');
            $vol    = $vol * Input::get('VOL2');
        }
        if(!empty(Input::get('VOL3'))){
            $koef   = $koef.' x '.Input::get('VOL3').' '.Input::get('SAT3');
            $vol    = $vol * Input::get('VOL3');
        }
        if(!empty(Input::get('VOL4'))){
            $koef   = $koef.' x '.Input::get('VOL4').' '.Input::get('SAT4');
            $vol    = $vol * Input::get('VOL4');
        }
        $total      = ( Komponen::where('KOMPONEN_ID', Input::get('KOMPONEN_ID'))
                      ->value('KOMPONEN_HARGA') * $vol ) 
                        + (( Input::get('RINCIAN_PAJAK') 
                        * (Komponen::where('KOMPONEN_ID', Input::get('KOMPONEN_ID'))
                            ->value('KOMPONEN_HARGA') * $vol) ) / 100);

        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->orderBy('TAHAPAN_ID','desc')->first();
        $totalBL    = BLPerubahan::where('BL_ID',Input::get('BL_ID'))->value('BL_PAGU');
        $skpd       = $this->getSKPD($tahun);
        $totalOPD   = SKPD::where('SKPD_ID',$skpd)->where('SKPD_TAHUN',$tahun)->value('SKPD_PAGU');
        $now        = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');
        $nowOPD     = RincianPerubahan::whereHas('bl',function($q) use($skpd){
                            $q->whereHas('subunit',function($r) use($skpd){
                                $r->where('SKPD_ID',$skpd);
                            });
                        })->sum('RINCIAN_TOTAL');
        $hargakomponen  = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->value('KOMPONEN_HARGA');
        if($tahapan->TAHAPAN_KUNCI_GIAT == 1){
            if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                $total  = (Input::get('HARGA') * $vol)+((Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);

                if($total+$now <= $totalBL){
                // if($total < $totalBL){
                    $rincian    = new RincianPerubahan;
                    $rincian->BL_ID                         = Input::get('BL_ID');
                    $rincian->REKENING_ID                   = Input::get('REKENING_ID');
                    $rincian->KOMPONEN_ID                   = Input::get('KOMPONEN_ID');
                    $rincian->RINCIAN_PAJAK                 = Input::get('RINCIAN_PAJAK');
                    $rincian->RINCIAN_VOLUME                = $vol;
                    $rincian->RINCIAN_KOEFISIEN             = $koef;
                    $rincian->SUBRINCIAN_ID                 = Input::get('SUBRINCIAN_ID');
                    if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                        $rincian->RINCIAN_KETERANGAN        = Input::get('KOMPONEN_NAMA')."#".Input::get('HARGA');
                        $rincian->RINCIAN_TOTAL             = ( Input::get('HARGA') * $vol ) + (( Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                    }else{
                        $rincian->RINCIAN_KETERANGAN        = Input::get('RINCIAN_KET');
                        $rincian->RINCIAN_TOTAL             = round($total);
                    }
                    $rincian->RINCIAN_HARGA                 = Input::get('HARGA');
                    $rincian->RINCIAN_KOMPONEN                  = Input::get('KOMPONEN_NAMA');                    
                    $rincian->PEKERJAAN_ID                  = Input::get('PEKERJAAN_ID');
                    $rincian->save();

                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log        = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BLPERUBAHAN#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }else{

                if($total+$now <= $totalBL){
                // if($total < $totalBL){
                    $rincian    = new RincianPerubahan;
                    $rincian->BL_ID                         = Input::get('BL_ID');
                    $rincian->REKENING_ID                   = Input::get('REKENING_ID');
                    $rincian->KOMPONEN_ID                   = Input::get('KOMPONEN_ID');
                    $rincian->RINCIAN_PAJAK                 = Input::get('RINCIAN_PAJAK');
                    $rincian->RINCIAN_VOLUME                = $vol;
                    $rincian->RINCIAN_KOEFISIEN             = $koef;
                    $rincian->SUBRINCIAN_ID                 = Input::get('SUBRINCIAN_ID');
                    if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                        $rincian->RINCIAN_KETERANGAN        = Input::get('KOMPONEN_NAMA')."#".Input::get('HARGA');
                        $rincian->RINCIAN_TOTAL             = ( Input::get('HARGA') * $vol ) + (( Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                    }else{
                        $rincian->RINCIAN_KETERANGAN        = Input::get('RINCIAN_KET');
                        $rincian->RINCIAN_TOTAL             = round($total);
                    }
                    $rincian->RINCIAN_HARGA                 = $hargakomponen;
                    $rincian->RINCIAN_KOMPONEN                  = Input::get('KOMPONEN_NAMA');                    
                    $rincian->PEKERJAAN_ID                  = Input::get('PEKERJAAN_ID');
                    $rincian->save();

                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log        = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BLPERUBAHAN#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }
        }elseif($tahapan->TAHAPAN_KUNCI_OPD == 1){
            if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                $total  = (Input::get('HARGA') * $vol)+((Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);

               if($total+$nowOPD <= $totalOPD){
                // if($total < $totalOPD){

                    $rincian    = new RincianPerubahan;
                    $rincian->BL_ID                         = Input::get('BL_ID');
                    $rincian->REKENING_ID                   = Input::get('REKENING_ID');
                    $rincian->KOMPONEN_ID                   = Input::get('KOMPONEN_ID');
                    $rincian->RINCIAN_PAJAK                 = Input::get('RINCIAN_PAJAK');
                    $rincian->RINCIAN_VOLUME                = $vol;
                    $rincian->RINCIAN_KOEFISIEN             = $koef;
                    $rincian->SUBRINCIAN_ID                 = Input::get('SUBRINCIAN_ID');
                    if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                        $rincian->RINCIAN_KETERANGAN        = Input::get('KOMPONEN_NAMA')."#".Input::get('HARGA');
                        $rincian->RINCIAN_TOTAL             = ( Input::get('HARGA') * $vol ) + (( Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                    }else{
                        $rincian->RINCIAN_KETERANGAN        = Input::get('RINCIAN_KET');
                        $rincian->RINCIAN_TOTAL             = round($total);
                    }
                    $rincian->RINCIAN_HARGA                 = Input::get('HARGA');
                    $rincian->RINCIAN_KOMPONEN                  = Input::get('KOMPONEN_NAMA');                    
                    $rincian->PEKERJAAN_ID                  = Input::get('PEKERJAAN_ID');
                    $rincian->save();

                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log        = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BLPERUBAHAN#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }else{
                if($total+$nowOPD <= $totalOPD){
                // if($total < $totalOPD){
                    $rincian    = new RincianPerubahan;
                    $rincian->BL_ID                         = Input::get('BL_ID');
                    $rincian->REKENING_ID                   = Input::get('REKENING_ID');
                    $rincian->KOMPONEN_ID                   = Input::get('KOMPONEN_ID');
                    $rincian->RINCIAN_PAJAK                 = Input::get('RINCIAN_PAJAK');
                    $rincian->RINCIAN_VOLUME                = $vol;
                    $rincian->RINCIAN_KOEFISIEN             = $koef;
                    $rincian->SUBRINCIAN_ID                 = Input::get('SUBRINCIAN_ID');
                    if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                        $rincian->RINCIAN_KETERANGAN        = Input::get('KOMPONEN_NAMA')."#".Input::get('HARGA');
                        $rincian->RINCIAN_TOTAL             = ( Input::get('HARGA') * $vol ) + (( Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                    }else{
                        $rincian->RINCIAN_KETERANGAN        = Input::get('RINCIAN_KET');
                        $rincian->RINCIAN_TOTAL             = round($total);
                    }
                    $rincian->RINCIAN_HARGA                 = $hargakomponen;
                    $rincian->RINCIAN_KOMPONEN                  = Input::get('KOMPONEN_NAMA');                    
                    $rincian->PEKERJAAN_ID                  = Input::get('PEKERJAAN_ID');
                    $rincian->save();

                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log        = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Menambahkan Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BLPERUBAHAN#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }
        }else{
            $rincian    = new RincianPerubahan;
            $rincian->BL_ID                         = Input::get('BL_ID');
            $rincian->REKENING_ID                   = Input::get('REKENING_ID');
            $rincian->KOMPONEN_ID                   = Input::get('KOMPONEN_ID');
            $rincian->RINCIAN_PAJAK                 = Input::get('RINCIAN_PAJAK');
            $rincian->RINCIAN_VOLUME                = $vol;
            $rincian->RINCIAN_KOEFISIEN             = $koef;
            $rincian->SUBRINCIAN_ID                 = Input::get('SUBRINCIAN_ID');
            if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                $rincian->RINCIAN_KETERANGAN        = Input::get('KOMPONEN_NAMA')."#".Input::get('HARGA');
                $rincian->RINCIAN_TOTAL             = ( Input::get('HARGA') * $vol ) + (( Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
            }else{
                $rincian->RINCIAN_KETERANGAN        = Input::get('RINCIAN_KET');
                $rincian->RINCIAN_TOTAL             = round($total);
            }
            $rincian->RINCIAN_HARGA                 = $hargakomponen;
            $rincian->RINCIAN_KOMPONEN                  = Input::get('KOMPONEN_NAMA');            
            $rincian->PEKERJAAN_ID                  = Input::get('PEKERJAAN_ID');
            $rincian->save();

            // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
            $totalrincian   = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

            $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
            $log        = new Log;
            $log->LOG_TIME                          = Carbon\Carbon::now();
            $log->USER_ID                           = Auth::user()->id;
            $log->LOG_ACTIVITY                      = 'Menambahkan Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
            $log->LOG_DETAIL                        = 'BLPERUBAHAN#'.Input::get('BL_ID');
            $log->save();        
            return number_format($totalrincian,0,'.',',');
        }
    }

    public function setPaket($tahun,$status){
        if($status == 'murni'){
            $subrincian     = new Subrincian;
            $subrincian->BL_ID              = Input::get('BL_ID');
            $subrincian->SUBRINCIAN_NAMA    = Input::get('SUBRINCIAN_NAMA');
            $subrincian->USER_CREATED       = Auth::user()->id;
            $subrincian->TIME_CREATED       = Carbon\Carbon::now();
            $subrincian->IP_CREATED         = $_SERVER['REMOTE_ADDR'];
            $subrincian->save();

            $data       = Subrincian::where('BL_ID',Input::get('BL_ID'))->orderBy('SUBRINCIAN_NAMA')->get();
            $view       = "";
            foreach($data as $d){
                $view .= "<option value='".$d->SUBRINCIAN_ID."'>".$d->SUBRINCIAN_NAMA."</option>";
            }
            return $view;
        }
        else{
            $subrincian     = new SubrincianPerubahan;
            $subrincian->BL_ID              = Input::get('BL_ID');
            $subrincian->SUBRINCIAN_ID      = SubrincianPerubahan::max('SUBRINCIAN_ID')+1;
            $subrincian->SUBRINCIAN_NAMA    = Input::get('SUBRINCIAN_NAMA');
            $subrincian->USER_CREATED       = Auth::user()->id;
            $subrincian->TIME_CREATED       = Carbon\Carbon::now();
            $subrincian->IP_CREATED         = $_SERVER['REMOTE_ADDR'];
            $subrincian->save();

            $data       = SubrincianPerubahan::where('BL_ID',Input::get('BL_ID'))->orderBy('SUBRINCIAN_NAMA')->get();
            $view       = "";
            foreach($data as $d){
                $view .= "<option value='".$d->SUBRINCIAN_ID."'>".$d->SUBRINCIAN_NAMA."</option>";
            }
            return $view;
        }
    }

    public function kuncigiat($tahun,$status){
        if($status=='murni'){
            Kunci::where('BL_ID',Input::get('BL_ID'))->update(['KUNCI_GIAT'=>Input::get('STATUS')]);
            return 'Berhasil!';
        }else{
            Kunciperubahan::where('BL_ID',Input::get('BL_ID'))->update(['KUNCI_GIAT'=>Input::get('STATUS')]);
            return 'Berhasil!';
        }
        
    }

    public function kuncigiatskpd($tahun,$status){
        $skpd   = Input::get('SKPD_ID');
        if($status=='murni'){
            $bl     = BL::whereHas('subunit',function($q) use ($skpd){
                        $q->where('SKPD_ID',$skpd);
                    })->where('BL_TAHUN',$tahun)->select('BL_ID')->get()->toArray();
            Kunci::whereIn('BL_ID',$bl)->update(['KUNCI_GIAT'=>Input::get('STATUS')]);
            return 'Berhasil!';
        }else{
            $bl     = BLPERUBAHAN::whereHas('subunit',function($q) use ($skpd){
                        $q->where('SKPD_ID',$skpd);
                    })->where('BL_TAHUN',$tahun)->select('BL_ID')->get()->toArray();
            Kunciperubahan::whereIn('BL_ID',$bl)->update(['KUNCI_GIAT'=>Input::get('STATUS')]);
            return 'Berhasil!';
        }
        
    }
    public function kuncirincian($tahun,$status){
        if($status == 'murni'){
            Kunci::where('BL_ID',Input::get('BL_ID'))->update(['KUNCI_RINCIAN'=>Input::get('STATUS')]);
            if(Input::get('STATUS') == 0) $act = "Membuka";
            else $act = "Mengunci";
            $log                = new Log;
            $log->LOG_TIME                     = Carbon\Carbon::now();
            $log->USER_ID                      = Auth::user()->id;
            $log->LOG_ACTIVITY                 = $act." Rincian Belanja";
            $log->LOG_DETAIL                   = 'BL#'.Input::get('BL_ID');
            $log->save();
            return 'Berhasil!';
        }else{
            Kunciperubahan::where('BL_ID',Input::get('BL_ID'))->update(['KUNCI_RINCIAN'=>Input::get('STATUS')]);
            if(Input::get('STATUS') == 0) $act = "Membuka";
            else $act = "Mengunci";
            $log                = new Log;
            $log->LOG_TIME                     = Carbon\Carbon::now();
            $log->USER_ID                      = Auth::user()->id;
            $log->LOG_ACTIVITY                 = $act." Rincian Belanja";
            $log->LOG_DETAIL                   = 'BLPERUBAHAN#'.Input::get('BL_ID');
            $log->save();
            return 'Berhasil!';
        }
    }

    public function kuncirincianskpd($tahun,$status){
        $skpd   = Input::get('SKPD_ID');
        if($status=='murni'){
            $bl     = BL::whereHas('subunit',function($q) use ($skpd){
                        $q->where('SKPD_ID',$skpd);
                    })->where('BL_TAHUN',$tahun)->select('BL_ID')->get()->toArray();
            Kunci::whereIn('BL_ID',$bl)->update(['KUNCI_RINCIAN'=>Input::get('STATUS')]);
        }else{
            $bl     = BLPERUBAHAN::whereHas('subunit',function($q) use ($skpd){
                        $q->where('SKPD_ID',$skpd);
                    })->where('BL_TAHUN',$tahun)->select('BL_ID')->get()->toArray();
            Kunciperubahan::whereIn('BL_ID',$bl)->update(['KUNCI_RINCIAN'=>Input::get('STATUS')]);
        }
        
        return 'Berhasil!';
    }

    public function kunciall($tahun,$status){
        if($status=='murni'){
            Kunci::where('KUNCI_RINCIAN','!=',Input::get('STATUS'))->update(['KUNCI_RINCIAN'=>Input::get('STATUS')]);
        }else{
            Kunciperubahan::where('KUNCI_RINCIAN','!=',Input::get('STATUS'))->update(['KUNCI_RINCIAN'=>Input::get('STATUS')]);
        }
        
        return 'Berhasil!';
    }

    //EDIT
    public function edit($tahun,$status,$id){
        $skpd           = $this->getSKPD($tahun);
        $subunit        = Subunit::where('SKPD_ID',$skpd)->orderBy('SUB_ID')->get();         
        $program        = Progunit::where('SKPD_ID',$skpd)->orderBy('PROGRAM_ID')->get();
        $jenis          = JenisGiat::all();
        $sumber         = SumberDana::all();
        $pagu           = Pagu::all();
        $sasaran        = Sasaran::all();
        $lokasi         = Lokasi::all();
        $tag            = Tag::all();
        $satuan         = Satuan::all();
        $bulan          = array('',
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
                         'Desember');
        $bl             = BL::where('BL_ID',$id)->first();
        $tagU           = BL::where('BL_ID',$id)->value('BL_TAG');
        $tagU           = str_replace('{', '', $tagU);
        $tagU           = str_replace('}', '', $tagU);
        $tagU           = explode(',', $tagU);
        $tagView        = array();
        $i              = 0;
        foreach($tagU as $t){
            $tagView[$i]    = Tag::where('TAG_ID',$t)->value('TAG_NAMA');
            $i++;
        }        
        // print_r($tagView);exit();
        $data           = [
                            'id'        => $id,
                            'bl'        => $bl,
                            'subunit'   => $subunit,
                            'program'   => $program,
                            'tahun'     => $tahun,
                            'status'    => $status,
                            'jenis'     => $jenis,
                            'sumber'    => $sumber,
                            'pagu'      => $pagu,
                            'sasaran'   => $sasaran,
                            'bulan'     => $bulan,
                            'lokasi'    => $lokasi,
                            'tag'       => $tag,
                            'tagused'   => $tagU,
                            'tagname'   => $tagView,
                            'satuan'    => $satuan,
                            'x'         => 0
                         ];
        return View('budgeting.belanja-langsung.edit',$data);        
    }

    public function indikator($tahun,$status,$id){
        $skpd           = $this->getSKPD($tahun);
        $subunit        = Subunit::where('SKPD_ID',$skpd)->orderBy('SUB_ID')->get();         
        $program        = Progunit::where('SKPD_ID',$skpd)->orderBy('PROGRAM_ID')->get();
        $jenis          = JenisGiat::all();
        $sumber         = SumberDana::all();
        $pagu           = Pagu::all();
        $sasaran        = Sasaran::all();
        $lokasi         = Lokasi::all();
        $tag            = Tag::all();
        $satuan         = Satuan::all();
        $bulan          = array('',
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
                         'Desember');
        $bl             = BL::where('BL_ID',$id)->first();
        $tagU           = BL::where('BL_ID',$id)->value('BL_TAG');
        $tagU           = str_replace('{', '', $tagU);
        $tagU           = str_replace('}', '', $tagU);
        $tagU           = explode(',', $tagU);
        $tagView        = array();
        $i              = 0;
        foreach($tagU as $t){
            $tagView[$i]    = Tag::where('TAG_ID',$t)->value('TAG_NAMA');
            $i++;
        }        
        // print_r($tagView);exit();
        $data           = [
                            'id'        => $id,
                            'bl'        => $bl,
                            'subunit'   => $subunit,
                            'program'   => $program,
                            'tahun'     => $tahun,
                            'status'    => $status,
                            'jenis'     => $jenis,
                            'sumber'    => $sumber,
                            'pagu'      => $pagu,
                            'sasaran'   => $sasaran,
                            'bulan'     => $bulan,
                            'lokasi'    => $lokasi,
                            'tag'       => $tag,
                            'tagused'   => $tagU,
                            'tagname'   => $tagView,
                            'satuan'    => $satuan,
                            'x'         => 0
                         ];
        return View('budgeting.belanja-langsung.indikator',$data);        
    }

    public function submitDetailEdit($tahun,$status){
        $tag        = Input::get('tag');
        settype($tag, 'array'); // can be called with a scalar or array
        $result = array();
        foreach ($tag as $t) {
            if (is_array($t)) {
                $result[] = to_pg_array($t);
            } else {
                $t = str_replace('"', '\\"', $t); // escape double quote
                if (! is_numeric($t)) // quote only non-numeric values
                    $t = '"' . $t . '"';
                $result[] = $t;
            }
        }
        $tag_   = '{' . implode(",", $result) . '}';
        if($status == 'murni') $s = 1;
        else $s = 2;
        BL::where('BL_ID',Input::get('bl_id'))->update([
        'KEGIATAN_ID'   => Input::get('kegiatan'),
        'JENIS_ID'      => Input::get('jenis-kegiatan'),
        'SUMBER_ID'     => Input::get('sumber-dana'),
        'PAGU_ID'       => Input::get('kategori-pagu'),
        'BL_AWAL'       => Input::get('waktu-awal'),
        'BL_AKHIR'      => Input::get('waktu-akhir'),
        'SASARAN_ID'    => Input::get('sasaran'),
        'LOKASI_ID'     => Input::get('lokasi'),
        'SUB_ID'        => Input::get('sub_id'),
        'BL_TAG'        => $tag_,
        'BL_STATUS'     => $s,
        'BL_VALIDASI'   => '0',
        'BL_DELETED'    => '0',
        'USER_UPDATED'  => Auth::user()->id,
        'TIME_UPDATED'  => Carbon\Carbon::now(),
        'IP_UPDATED'    => $_SERVER['REMOTE_ADDR']]);
        
        $log        = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Mengubah Belanja Langsung';
        $log->LOG_DETAIL                        = 'BL#'.Input::get('bl_id');
        $log->save();
        return Redirect('main/'.$tahun.'/'.$status.'/belanja-langsung');        

    }

    public function submitRincianEdit($tahun,$status){
        if($status == 'murni') return $this->submitRincianEditMurni($tahun,$status);
        else return $this->submitRincianEditPerubahan($tahun,$status);
    }

    public function submitRincianEditMurni($tahun,$status){
        $koef       = Input::get('VOL1').' '.Input::get('SAT1');
        $vol        = Input::get('VOL1');
        if(!empty(Input::get('VOL2'))){
            $koef   = $koef.' x '.Input::get('VOL2').' '.Input::get('SAT2');
            $vol    = $vol * Input::get('VOL2');
        }
        if(!empty(Input::get('VOL3'))){
            $koef   = $koef.' x '.Input::get('VOL3').' '.Input::get('SAT3');
            $vol    = $vol * Input::get('VOL3');
        }
        if(!empty(Input::get('VOL4'))){
            $koef   = $koef.' x '.Input::get('VOL4').' '.Input::get('SAT4');
            $vol    = $vol * Input::get('VOL4');
        }

        $rinciannow     = Rincian::where('RINCIAN_ID',Input::get('RINCIAN_ID'))->value('RINCIAN_VOLUME');
        // if($rinciannow < $vol and Input::get('BL_ID') != 5718){
        //     return 99;
        // }

        $total      = ( Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->value('KOMPONEN_HARGA') * $vol ) + (( Input::get('RINCIAN_PAJAK')*(Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->value('KOMPONEN_HARGA')*$vol))/100);
        
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','murni')->orderBy('TAHAPAN_ID','desc')->first();
        $totalBL    = BL::where('BL_ID',Input::get('BL_ID'))->value('BL_PAGU');
        // $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        $skpd       = BL::where('BL_ID',Input::get('BL_ID'))->first();
        $skpd       = $skpd->subunit->SKPD_ID;
        $totalOPD   = SKPD::where('SKPD_ID',$skpd)->where('SKPD_TAHUN',$tahun)->value('SKPD_PAGU');
        $now        = Rincian::where('BL_ID',Input::get('BL_ID'))->where('RINCIAN_ID','!=',Input::get('RINCIAN_ID'))->sum('RINCIAN_TOTAL');        
        $nowOPD     = Rincian::whereHas('bl',function($q) use($skpd){
                            $q->where('BL_DELETED',0)->whereHas('subunit',function($r) use($skpd){
                                $r->where('SKPD_ID',$skpd);
                            });
                        })->where('BL_ID','!=',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');
        // print_r($total);exit();
        if($tahapan->TAHAPAN_KUNCI_GIAT == 1){
            if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                $total  = (Input::get('HARGA') * $vol)+((Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                if($total+$now <= $totalBL or $rinciannow >= $vol){
                    Rincian::where('RINCIAN_ID',Input::get('RINCIAN_ID'))
                        ->update([
                            'BL_ID'                         => Input::get('BL_ID'),
                            'REKENING_ID'                   => Input::get('REKENING_ID'),
                            'KOMPONEN_ID'                   => Input::get('KOMPONEN_ID'),
                            'RINCIAN_PAJAK'                 => Input::get('RINCIAN_PAJAK'),
                            'RINCIAN_VOLUME'                => $vol,
                            'RINCIAN_KOEFISIEN'             => $koef,
                            'RINCIAN_TOTAL'                 => round($total),
                            'SUBRINCIAN_ID'                 => Input::get('SUBRINCIAN_ID'),
                            'RINCIAN_KETERANGAN'            => Input::get('RINCIAN_KET'),
                            'PEKERJAAN_ID'                  => Input::get('PEKERJAAN_ID')
                            ]);
                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log                = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Mengubah Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');                    
                }else{
                    return 0;
                }
            }else{
                if($total+$now <= $totalBL or $rinciannow >= $vol){
                    Rincian::where('RINCIAN_ID',Input::get('RINCIAN_ID'))
                        ->update([
                            'BL_ID'                         => Input::get('BL_ID'),
                            'REKENING_ID'                   => Input::get('REKENING_ID'),
                            'KOMPONEN_ID'                   => Input::get('KOMPONEN_ID'),
                            'RINCIAN_PAJAK'                 => Input::get('RINCIAN_PAJAK'),
                            'RINCIAN_VOLUME'                => $vol,
                            'RINCIAN_KOEFISIEN'             => $koef,
                            'RINCIAN_TOTAL'                 => round($total),
                            'SUBRINCIAN_ID'                 => Input::get('SUBRINCIAN_ID'),
                            'RINCIAN_KETERANGAN'            => Input::get('RINCIAN_KET'),
                            'PEKERJAAN_ID'                  => Input::get('PEKERJAAN_ID')
                            ]);
                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log                = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Mengubah Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }
        }elseif($tahapan->TAHAPAN_KUNCI_OPD == 1){
            if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                $total  = (Input::get('HARGA') * $vol)+((Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                if($total+$nowOPD <= $totalOPD){
                    Rincian::where('RINCIAN_ID',Input::get('RINCIAN_ID'))
                        ->update([
                            'BL_ID'                         => Input::get('BL_ID'),
                            'REKENING_ID'                   => Input::get('REKENING_ID'),
                            'KOMPONEN_ID'                   => Input::get('KOMPONEN_ID'),
                            'RINCIAN_PAJAK'                 => Input::get('RINCIAN_PAJAK'),
                            'RINCIAN_VOLUME'                => $vol,
                            'RINCIAN_KOEFISIEN'             => $koef,
                            'RINCIAN_TOTAL'                 => round($total),
                            'SUBRINCIAN_ID'                 => Input::get('SUBRINCIAN_ID'),
                            'RINCIAN_KETERANGAN'            => Input::get('RINCIAN_KET'),
                            'PEKERJAAN_ID'                  => Input::get('PEKERJAAN_ID')
                            ]);
                    BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log                = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Mengubah Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }else{
               if($total+$nowOPD <= $totalOPD){
                // if($total <= $totalOPD){
                    Rincian::where('RINCIAN_ID',Input::get('RINCIAN_ID'))
                        ->update([
                            'BL_ID'                         => Input::get('BL_ID'),
                            'REKENING_ID'                   => Input::get('REKENING_ID'),
                            'KOMPONEN_ID'                   => Input::get('KOMPONEN_ID'),
                            'RINCIAN_PAJAK'                 => Input::get('RINCIAN_PAJAK'),
                            'RINCIAN_VOLUME'                => $vol,
                            'RINCIAN_KOEFISIEN'             => $koef,
                            'RINCIAN_TOTAL'                 => round($total),
                            'SUBRINCIAN_ID'                 => Input::get('SUBRINCIAN_ID'),
                            'RINCIAN_KETERANGAN'            => Input::get('RINCIAN_KET'),
                            'PEKERJAAN_ID'                  => Input::get('PEKERJAAN_ID')
                            ]);
                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log                = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Mengubah Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }
        }else{
            Rincian::where('RINCIAN_ID',Input::get('RINCIAN_ID'))
                ->update([
                    'BL_ID'                         => Input::get('BL_ID'),
                    'REKENING_ID'                   => Input::get('REKENING_ID'),
                    'KOMPONEN_ID'                   => Input::get('KOMPONEN_ID'),
                    'RINCIAN_PAJAK'                 => Input::get('RINCIAN_PAJAK'),
                    'RINCIAN_VOLUME'                => $vol,
                    'RINCIAN_KOEFISIEN'             => $koef,
                    'RINCIAN_TOTAL'                 => round($total),
                    'SUBRINCIAN_ID'                 => Input::get('SUBRINCIAN_ID'),
                    'RINCIAN_KETERANGAN'            => Input::get('RINCIAN_KET'),
                    'PEKERJAAN_ID'                  => Input::get('PEKERJAAN_ID')
                    ]);
            // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
            $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

            $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
            $log                = new Log;
            $log->LOG_TIME                          = Carbon\Carbon::now();
            $log->USER_ID                           = Auth::user()->id;
            $log->LOG_ACTIVITY                      = 'Mengubah Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
            $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
            $log->save();        
            return number_format($totalrincian,0,'.',',');
            // return $this->setpaguBL(Input::get('BL_ID'));
        }
    }

    public function submitRincianEditPerubahan($tahun,$status){
        $koef       = Input::get('VOL1').' '.Input::get('SAT1');
        $vol        = Input::get('VOL1');
        if(!empty(Input::get('VOL2'))){
            $koef   = $koef.' x '.Input::get('VOL2').' '.Input::get('SAT2');
            $vol    = $vol * Input::get('VOL2');
        }
        if(!empty(Input::get('VOL3'))){
            $koef   = $koef.' x '.Input::get('VOL3').' '.Input::get('SAT3');
            $vol    = $vol * Input::get('VOL3');
        }
        if(!empty(Input::get('VOL4'))){
            $koef   = $koef.' x '.Input::get('VOL4').' '.Input::get('SAT4');
            $vol    = $vol * Input::get('VOL4');
        }

        $rinciannow     = RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))->value('RINCIAN_VOLUME');
        // if($rinciannow < $vol and Input::get('BL_ID') != 5718){
        //     return 99;
        // }
        $harga = 0;
        if(Input::get('KOMPONEN_ID') == 0) $harga = RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))->value('RINCIAN_HARGA');
        else $harga      = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->value('KOMPONEN_HARGA');
        $total      = ( $harga * $vol ) + (( Input::get('RINCIAN_PAJAK')*($harga*$vol))/100);
        
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->orderBy('TAHAPAN_ID','desc')->first();
        
        $totalBL    = BLPerubahan::where('BL_ID',Input::get('BL_ID'))->value('BL_PAGU');
        // $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        $skpd       = BLPerubahan::where('BL_ID',Input::get('BL_ID'))->first();
        $skpd       = $skpd->subunit->SKPD_ID;
        $totalOPD   = SKPD::where('SKPD_ID',$skpd)->where('SKPD_TAHUN',$tahun)->value('SKPD_PAGU');
        $now        = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->where('RINCIAN_ID','!=',Input::get('RINCIAN_ID'))->sum('RINCIAN_TOTAL');        
        $nowOPD     = RincianPerubahan::whereHas('bl',function($q) use($skpd){
                            $q->where('BL_DELETED',0)->whereHas('subunit',function($r) use($skpd){
                                $r->where('SKPD_ID',$skpd);
                            });
                        })->where('BL_ID','!=',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');
        // print_r($total);exit();
        $cekrealisasi   = Realisasi::where('BL_ID',Input::get('BL_ID'))
                                    ->where('REKENING_ID',Input::get('REKENING_ID'))
                                    ->first();
        $rekeningnow    = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))
                                    ->where('REKENING_ID',Input::get('REKENING_ID'))
                                    ->where('RINCIAN_ID','!=',Input::get('RINCIAN_ID'))
                                    ->sum('RINCIAN_TOTAL');
                                    // print_r($cekrealisasi->REALISASI_TOTAL);exit();
        $totalrekening  = $rekeningnow + $total;

        /*if($cekrealisasi){
            if($totalrekening < $cekrealisasi->REALISASI_TOTAL){
                return 98;
            }                        
        }*/

        if($tahapan->TAHAPAN_KUNCI_GIAT == 1){
            if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                $total  = (Input::get('HARGA') * $vol)+((Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                if($total+$now <= $totalBL){
                    RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))
                        ->update([
                            'BL_ID'                         => Input::get('BL_ID'),
                            'REKENING_ID'                   => Input::get('REKENING_ID'),
                            'KOMPONEN_ID'                   => Input::get('KOMPONEN_ID'),
                            'RINCIAN_PAJAK'                 => Input::get('RINCIAN_PAJAK'),
                            'RINCIAN_VOLUME'                => $vol,
                            'RINCIAN_KOEFISIEN'             => $koef,
                            'RINCIAN_TOTAL'                 => round($total),
                            'SUBRINCIAN_ID'                 => Input::get('SUBRINCIAN_ID'),
                            'RINCIAN_KETERANGAN'            => Input::get('RINCIAN_KET'),
                            'PEKERJAAN_ID'                  => Input::get('PEKERJAAN_ID')
                            ]);
                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log                = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Mengubah Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BLPERUBAHAN#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');                    
                }else{
                    return 0;
                }
            }else{
                if($total+$now <= $totalBL){
                    RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))
                        ->update([
                            'BL_ID'                         => Input::get('BL_ID'),
                            'REKENING_ID'                   => Input::get('REKENING_ID'),
                            'KOMPONEN_ID'                   => Input::get('KOMPONEN_ID'),
                            'RINCIAN_PAJAK'                 => Input::get('RINCIAN_PAJAK'),
                            'RINCIAN_VOLUME'                => $vol,
                            'RINCIAN_KOEFISIEN'             => $koef,
                            'RINCIAN_TOTAL'                 => round($total),
                            'SUBRINCIAN_ID'                 => Input::get('SUBRINCIAN_ID'),
                            'RINCIAN_KETERANGAN'            => Input::get('RINCIAN_KET'),
                            'PEKERJAAN_ID'                  => Input::get('PEKERJAAN_ID')
                            ]);
                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log                = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Mengubah Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BLPERUBAHAN#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }
        }elseif($tahapan->TAHAPAN_KUNCI_OPD == 1){
            if(Input::get('PEKERJAAN_ID') == '4' || Input::get('PEKERJAAN_ID') == '5'){
                $total  = (Input::get('HARGA') * $vol)+((Input::get('RINCIAN_PAJAK')*(Input::get('HARGA')*$vol))/100);
                if($total+$nowOPD <= $totalOPD){
                    RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))
                        ->update([
                            'BL_ID'                         => Input::get('BL_ID'),
                            'REKENING_ID'                   => Input::get('REKENING_ID'),
                            'KOMPONEN_ID'                   => Input::get('KOMPONEN_ID'),
                            'RINCIAN_PAJAK'                 => Input::get('RINCIAN_PAJAK'),
                            'RINCIAN_VOLUME'                => $vol,
                            'RINCIAN_KOEFISIEN'             => $koef,
                            'RINCIAN_TOTAL'                 => round($total),
                            'SUBRINCIAN_ID'                 => Input::get('SUBRINCIAN_ID'),
                            'RINCIAN_KETERANGAN'            => Input::get('RINCIAN_KET'),
                            'PEKERJAAN_ID'                  => Input::get('PEKERJAAN_ID')
                            ]);
                    BLPerubahan::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log                = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Mengubah Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BLPERUBAHAN#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }else{
                if($total+$nowOPD <= $totalOPD){
                    RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))
                        ->update([
                            'BL_ID'                         => Input::get('BL_ID'),
                            'REKENING_ID'                   => Input::get('REKENING_ID'),
                            'KOMPONEN_ID'                   => Input::get('KOMPONEN_ID'),
                            'RINCIAN_PAJAK'                 => Input::get('RINCIAN_PAJAK'),
                            'RINCIAN_VOLUME'                => $vol,
                            'RINCIAN_KOEFISIEN'             => $koef,
                            'RINCIAN_TOTAL'                 => round($total),
                            'SUBRINCIAN_ID'                 => Input::get('SUBRINCIAN_ID'),
                            'RINCIAN_KETERANGAN'            => Input::get('RINCIAN_KET'),
                            'PEKERJAAN_ID'                  => Input::get('PEKERJAAN_ID')
                            ]);
                    // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                    $totalrincian   = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

                    $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
                    $log                = new Log;
                    $log->LOG_TIME                          = Carbon\Carbon::now();
                    $log->USER_ID                           = Auth::user()->id;
                    $log->LOG_ACTIVITY                      = 'Mengubah Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
                    $log->LOG_DETAIL                        = 'BLPERUBAHAN#'.Input::get('BL_ID');
                    $log->save();        
                    return number_format($totalrincian,0,'.',',');
                }else{
                    return 0;
                }
            }
        }else{
            RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))
                ->update([
                    'BL_ID'                         => Input::get('BL_ID'),
                    'REKENING_ID'                   => Input::get('REKENING_ID'),
                    'KOMPONEN_ID'                   => Input::get('KOMPONEN_ID'),
                    'RINCIAN_PAJAK'                 => Input::get('RINCIAN_PAJAK'),
                    'RINCIAN_VOLUME'                => $vol,
                    'RINCIAN_KOEFISIEN'             => $koef,
                    'RINCIAN_TOTAL'                 => round($total),
                    'SUBRINCIAN_ID'                 => Input::get('SUBRINCIAN_ID'),
                    'RINCIAN_KETERANGAN'            => Input::get('RINCIAN_KET'),
                    'PEKERJAAN_ID'                  => Input::get('PEKERJAAN_ID')
                    ]);
            // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
            $totalrincian   = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');

            $dataKomponen   = Komponen::where('KOMPONEN_ID',Input::get('KOMPONEN_ID'))->first();
            $log                = new Log;
            $log->LOG_TIME                          = Carbon\Carbon::now();
            $log->USER_ID                           = Auth::user()->id;
            $log->LOG_ACTIVITY                      = 'Mengubah Komponen '.$dataKomponen->KOMPONEN_NAMA.' '.$koef.' Total Rp. '.number_format(round($total),0,',','.');
            $log->LOG_DETAIL                        = 'BLPERUBAHAN#'.Input::get('BL_ID');
            $log->save();        
            return number_format($totalrincian,0,'.',',');
            // return $this->setpaguBL(Input::get('BL_ID'));
        }
    }

    //DELETE
    public function deleteBL(){
        BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_DELETED'=>1]);
        $log                = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Mengarsipkan Belanja';
        $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
        $log->save();
        return "Hapus Berhasil!";
    }

    public function deleteRincian($tahun,$status){
        if($status == 'murni') return $this->delRincianMurni($tahun,$status);
        else return $this->delRincianPerubahan($tahun,$status);
        // return $this->setpaguBL(Input::get('BL_ID'));
    }

    public function delRincianMurni($tahun,$status){
        $rincian       = Rincian::where('RINCIAN_ID',Input::get('RINCIAN_ID'))->first();
        $arcRincian     = new RincianArsip;
        $arcRincian->RINCIAN_ID             = $rincian->RINCIAN_ID;
        $arcRincian->SUBRINCIAN_ID          = $rincian->SUBRINCIAN_ID;
        $arcRincian->REKENING_ID            = $rincian->REKENING_ID;
        $arcRincian->KOMPONEN_ID            = $rincian->KOMPONEN_ID;
        $arcRincian->RINCIAN_PAJAK          = $rincian->RINCIAN_PAJAK;
        $arcRincian->RINCIAN_VOLUME         = $rincian->RINCIAN_VOLUME;
        $arcRincian->RINCIAN_KOEFISIEN      = $rincian->RINCIAN_KOEFISIEN;
        $arcRincian->RINCIAN_TOTAL          = $rincian->RINCIAN_TOTAL;
        $arcRincian->RINCIAN_KETERANGAN     = $rincian->RINCIAN_KETERANGAN;
        $arcRincian->PEKERJAAN_ID           = $rincian->PEKERJAAN_ID;
        $arcRincian->BL_ID                  = $rincian->BL_ID;
        $arcRincian->save();
        $komponen      = Komponen::where('KOMPONEN_ID',$rincian->KOMPONEN_ID)->first();
        Rincian::where('RINCIAN_ID',Input::get('RINCIAN_ID'))->delete();
        //MATIKAN VALIDASI
        // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
        $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');        

        $log        = new Log;
        $log->LOG_TIME                          = Carbon\Carbon::now();
        $log->USER_ID                           = Auth::user()->id;
        $log->LOG_ACTIVITY                      = 'Menghapus komponen '.$komponen->KOMPONEN_NAMA.' '.$rincian->RINCIAN_KOEFISIEN.' Total Rp. '.number_format($rincian->RINCIAN_TOTAL,0,',','.');
        $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
        $log->save();        
        return number_format($totalrincian,0,'.',',');
    }

    public function delRincianPerubahan($tahun,$status){
        $bl             = RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))->first();
        $cekrealisasi   = Realisasi::where('BL_ID',$bl->BL_ID)
                                    ->where('REKENING_ID',$bl->REKENING_ID)
                                    ->first();
        $rekeningnow    = RincianPerubahan::where('BL_ID',$bl->BL_ID)
                                    ->where('REKENING_ID',$bl->REKENING_ID)
                                    ->where('RINCIAN_ID','!=',Input::get('RINCIAN_ID'))
                                    ->sum('RINCIAN_TOTAL');
                                    // print_r($cekrealisasi->REALISASI_TOTAL);exit();
        $totalrekening  = $rekeningnow;
        if($cekrealisasi){
            if($totalrekening < $cekrealisasi->REALISASI_TOTAL){
                return 'FAIL';
            }else{
                $rincian       = RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))->first();
                $arcRincian     = new RincianArsipPerubahan;
                $arcRincian->RINCIAN_ID             = $rincian->RINCIAN_ID;
                $arcRincian->SUBRINCIAN_ID          = $rincian->SUBRINCIAN_ID;
                $arcRincian->REKENING_ID            = $rincian->REKENING_ID;
                $arcRincian->KOMPONEN_ID            = $rincian->KOMPONEN_ID;
                $arcRincian->RINCIAN_PAJAK          = $rincian->RINCIAN_PAJAK;
                $arcRincian->RINCIAN_VOLUME         = $rincian->RINCIAN_VOLUME;
                $arcRincian->RINCIAN_KOEFISIEN      = $rincian->RINCIAN_KOEFISIEN;
                $arcRincian->RINCIAN_TOTAL          = $rincian->RINCIAN_TOTAL;
                $arcRincian->RINCIAN_KETERANGAN     = $rincian->RINCIAN_KETERANGAN;
                $arcRincian->PEKERJAAN_ID           = $rincian->PEKERJAAN_ID;
                $arcRincian->BL_ID                  = $rincian->BL_ID;
                $arcRincian->save();
                $komponen      = Komponen::where('KOMPONEN_ID',$rincian->KOMPONEN_ID)->first();
                //RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))->delete();
                RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))->update(["RINCIAN_VOLUME"=>0,"RINCIAN_KOEFISIEN"=>NULL,"RINCIAN_TOTAL"=>0]);
                //MATIKAN VALIDASI
                // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
                $totalrincian   = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');        

                $log        = new Log;
                $log->LOG_TIME                          = Carbon\Carbon::now();
                $log->USER_ID                           = Auth::user()->id;
                $log->LOG_ACTIVITY                      = 'Menghapus komponen '.$komponen->KOMPONEN_NAMA.' '.$rincian->RINCIAN_KOEFISIEN.' Total Rp. '.number_format($rincian->RINCIAN_TOTAL,0,',','.');
                $log->LOG_DETAIL                        = 'BLPERUBAHAN#'.Input::get('BL_ID');
                $log->save();        
                return number_format($totalrincian,0,'.',',');
            }                        
        }else{
            $rincian       = RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))->first();
            $arcRincian     = new RincianArsipPerubahan;
            $arcRincian->RINCIAN_ID             = $rincian->RINCIAN_ID;
            $arcRincian->SUBRINCIAN_ID          = $rincian->SUBRINCIAN_ID;
            $arcRincian->REKENING_ID            = $rincian->REKENING_ID;
            $arcRincian->KOMPONEN_ID            = $rincian->KOMPONEN_ID;
            $arcRincian->RINCIAN_PAJAK          = $rincian->RINCIAN_PAJAK;
            $arcRincian->RINCIAN_VOLUME         = $rincian->RINCIAN_VOLUME;
            $arcRincian->RINCIAN_KOEFISIEN      = $rincian->RINCIAN_KOEFISIEN;
            $arcRincian->RINCIAN_TOTAL          = $rincian->RINCIAN_TOTAL;
            $arcRincian->RINCIAN_KETERANGAN     = $rincian->RINCIAN_KETERANGAN;
            $arcRincian->PEKERJAAN_ID           = $rincian->PEKERJAAN_ID;
            $arcRincian->BL_ID                  = $rincian->BL_ID;
            $arcRincian->save();
            $komponen      = Komponen::where('KOMPONEN_ID',$rincian->KOMPONEN_ID)->first();
            //RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))->delete();
            RincianPerubahan::where('RINCIAN_ID',Input::get('RINCIAN_ID'))->update(["RINCIAN_VOLUME"=>0,"RINCIAN_KOEFISIEN"=>NULL,"RINCIAN_TOTAL"=>0]);
            //MATIKAN VALIDASI
            // BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
            $totalrincian   = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');        

            $log        = new Log;
            $log->LOG_TIME                          = Carbon\Carbon::now();
            $log->USER_ID                           = Auth::user()->id;
            $log->LOG_ACTIVITY                      = 'Menghapus komponen '.$komponen->KOMPONEN_NAMA.' '.$rincian->RINCIAN_KOEFISIEN.' Total Rp. '.number_format($rincian->RINCIAN_TOTAL,0,',','.');
            $log->LOG_DETAIL                        = 'BLPERUBAHAN#'.Input::get('BL_ID');
            $log->save();        
            return number_format($totalrincian,0,'.',',');
        }
    }

    public function deleteRincianCB($tahun,$status){
        $id             = Input::get('RINCIAN_ID');
        foreach ($id as $id) {
            $rincian       = Rincian::where('RINCIAN_ID',$id)->first();
            $arcRincian     = new RincianArsip;
            $arcRincian->RINCIAN_ID             = $rincian->RINCIAN_ID;
            $arcRincian->SUBRINCIAN_ID          = $rincian->SUBRINCIAN_ID;
            $arcRincian->REKENING_ID            = $rincian->REKENING_ID;
            $arcRincian->KOMPONEN_ID            = $rincian->KOMPONEN_ID;
            $arcRincian->RINCIAN_PAJAK          = $rincian->RINCIAN_PAJAK;
            $arcRincian->RINCIAN_VOLUME         = $rincian->RINCIAN_VOLUME;
            $arcRincian->RINCIAN_KOEFISIEN      = $rincian->RINCIAN_KOEFISIEN;
            $arcRincian->RINCIAN_TOTAL          = $rincian->RINCIAN_TOTAL;
            $arcRincian->RINCIAN_KETERANGAN     = $rincian->RINCIAN_KETERANGAN;
            $arcRincian->PEKERJAAN_ID           = $rincian->PEKERJAAN_ID;
            $arcRincian->BL_ID                  = $rincian->BL_ID;
            $arcRincian->save();
            $komponen      = Komponen::where('KOMPONEN_ID',$rincian->KOMPONEN_ID)->first();
            Rincian::where('RINCIAN_ID',$id)->delete();

            $log        = new Log;
            $log->LOG_TIME                          = Carbon\Carbon::now();
            $log->USER_ID                           = Auth::user()->id;
            $log->LOG_ACTIVITY                      = 'Menghapus komponen '.$komponen->KOMPONEN_NAMA.' '.$rincian->RINCIAN_KOEFISIEN.' Total Rp. '.number_format($rincian->RINCIAN_TOTAL,0,',','.');
            $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
            $log->save();
        }
        BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
        $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');        
        return number_format($totalrincian,0,'.',',');
    }

    public function restoreRincianCB($tahun,$status){
        $id             = Input::get('RINCIAN_ID');
        foreach ($id as $id) {
            $rincian       = RincianArsip::where('RINCIAN_ID',$id)->first();
            $arcRincian     = new Rincian;
            $arcRincian->RINCIAN_ID             = $rincian->RINCIAN_ID;
            $arcRincian->SUBRINCIAN_ID          = $rincian->SUBRINCIAN_ID;
            $arcRincian->REKENING_ID            = $rincian->REKENING_ID;
            $arcRincian->KOMPONEN_ID            = $rincian->KOMPONEN_ID;
            $arcRincian->RINCIAN_PAJAK          = $rincian->RINCIAN_PAJAK;
            $arcRincian->RINCIAN_VOLUME         = $rincian->RINCIAN_VOLUME;
            $arcRincian->RINCIAN_KOEFISIEN      = $rincian->RINCIAN_KOEFISIEN;
            $arcRincian->RINCIAN_TOTAL          = $rincian->RINCIAN_TOTAL;
            $arcRincian->RINCIAN_KETERANGAN     = $rincian->RINCIAN_KETERANGAN;
            $arcRincian->PEKERJAAN_ID           = $rincian->PEKERJAAN_ID;
            $arcRincian->BL_ID                  = $rincian->BL_ID;
            $arcRincian->save();
            $komponen      = Komponen::where('KOMPONEN_ID',$rincian->KOMPONEN_ID)->first();
            RincianArsip::where('RINCIAN_ID',$id)->delete();

            $log        = new Log;
            $log->LOG_TIME                          = Carbon\Carbon::now();
            $log->USER_ID                           = Auth::user()->id;
            $log->LOG_ACTIVITY                      = 'Mengembalikan komponen '.$komponen->KOMPONEN_NAMA.' '.$rincian->RINCIAN_KOEFISIEN.' Total Rp. '.number_format($rincian->RINCIAN_TOTAL,0,',','.');
            $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
            $log->save();
        }
        BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>0]);
        $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');        
        return number_format($totalrincian,0,'.',',');
    }

    //VALIDASI
    public function validasi($tahun,$status){
        if($status=='murni'){
            $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');
            BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>'1','BL_PAGU'=>$totalrincian]);
            $skpd           = $this->getSKPD($tahun);
            $totalPagu      = BL::whereHas('subunit',function($q) use($skpd){
                                    $q->where('SKPD_ID',$skpd);
                                })->where('BL_VALIDASI',1)->sum('BL_PAGU');
            // SKPD::where('SKPD_ID',$skpd)->update(['SKPD_PAGU'=>$totalPagu]);
            $log        = new Log;
            $log->LOG_TIME                          = Carbon\Carbon::now();
            $log->USER_ID                           = Auth::user()->id;
            $log->LOG_ACTIVITY                      = 'Validasi Belanja Langsung';
            $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
            $log->save();
            return number_format($totalrincian,0,'.',',');
        }else{
            $totalrincian   = RincianPerubahan::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');
            BLPerubahan::where('BL_ID',Input::get('BL_ID'))->update(['BL_VALIDASI'=>'1','BL_PAGU'=>$totalrincian]);
            $skpd           = $this->getSKPD($tahun);
            $totalPagu      = BLPerubahan::whereHas('subunit',function($q) use($skpd){
                                    $q->where('SKPD_ID',$skpd);
                                })->where('BL_VALIDASI',1)->sum('BL_PAGU');
            // SKPD::where('SKPD_ID',$skpd)->update(['SKPD_PAGU'=>$totalPagu]);
            $log        = new Log;
            $log->LOG_TIME                          = Carbon\Carbon::now();
            $log->USER_ID                           = Auth::user()->id;
            $log->LOG_ACTIVITY                      = 'Validasi Belanja Langsung';
            $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
            $log->save();
            return number_format($totalrincian,0,'.',',');
        }
        
    }

    public function validasiAll($tahun,$status){
        // $data   = BL::all();
        if($status=='murni'){
            $data   = BL::where('BL_VALIDASI',1)->get();
            $i      = 1;
            foreach ($data as $data) {
                $totalrincian   = Rincian::where('BL_ID',$data->BL_ID)->sum('RINCIAN_TOTAL');
                BL::where('BL_ID',$data->BL_ID)->update(['BL_VALIDASI'=>'1','BL_PAGU'=>$totalrincian]);
                $log        = new Log;
                $log->LOG_TIME                          = Carbon\Carbon::now();
                $log->USER_ID                           = Auth::user()->id;
                $log->LOG_ACTIVITY                      = 'Validasi Belanja Langsung';
                $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
                $log->save();
                $i++;      
            }
            return $i;
        }else{
             $data   = BLPerubahan::where('BL_VALIDASI',1)->get();
            $i      = 1;
            foreach ($data as $data) {
                $totalrincian   = RincianPerubahan::where('BL_ID',$data->BL_ID)->sum('RINCIAN_TOTAL');
                BLPerubahan::where('BL_ID',$data->BL_ID)->update(['BL_VALIDASI'=>'1','BL_PAGU'=>$totalrincian]);
                $log        = new Log;
                $log->LOG_TIME                          = Carbon\Carbon::now();
                $log->USER_ID                           = Auth::user()->id;
                $log->LOG_ACTIVITY                      = 'Validasi Belanja Langsung';
                $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
                $log->save();
                $i++;      
            }
            return $i;
        }
        
    }    

    //API

    public function getKegiatan($tahun, $status, $id,$sub){
        $skpd   = $this->getSKPD($tahun);
        $program = Program::where('PROGRAM_ID',$id)->first();
        if( $program->PROGRAM_KODE == '01' || 
            $program->PROGRAM_KODE == '02' || 
            $program->PROGRAM_KODE == '03' || 
            $program->PROGRAM_KODE == '04' || 
            $program->PROGRAM_KODE == '05' || 
            $program->PROGRAM_KODE == '06' ||
            ($program->urusan->URUSAN_KODE == '4.05' && $program->PROGRAM_KODE == '28') ||
            ($program->urusan->URUSAN_KODE == '2.07' && $program->PROGRAM_KODE == '22')) {
            $data =  Kegiatan::where('PROGRAM_ID',$id)->get();
        }else{
            $data =  Kegiatan::whereHas('kegunit',function($q) use ($skpd){
                $q->where('SKPD_ID',$skpd);
            })->where('PROGRAM_ID',$id)->get();            
        }
        $view = "";
        foreach ($data as $data) {
            $cekGiat        = BL::where('KEGIATAN_ID',$data->KEGIATAN_ID)
                                    ->where('SUB_ID',$sub)
                                    ->value('BL_ID');
            if(empty($cekGiat)) 
            $view .= "<option value='".$data->KEGIATAN_ID."'>".$data->KEGIATAN_KODE." - ".$data->KEGIATAN_NAMA."</option>";
        }
        return $view;
    }

    public function getCapaian($tahun, $status, $id){
        $program    = Kegiatan::where('KEGIATAN_ID',$id)->value('PROGRAM_ID');
        $outcome    = Outcome::where('PROGRAM_ID',$program)->get();
        $impact     = Impact::where('PROGRAM_ID',$program)->get();
        $view           = array();
        foreach ($outcome as $data) {
            array_push($view, array( 'INDIKATOR'        =>'Capaian Program / Sasaran',
                                     'TOLAK_UKUR'       =>$data->OUTCOME_TOLAK_UKUR,
                                     'TARGET'           =>$data->OUTCOME_TARGET." ".$data->satuan->SATUAN_NAMA));
        }
        // foreach ($output as $data) {
        //     array_push($view, array( 'INDIKATOR'        =>'Output / Keluaran',
        //                              'TOLAK_UKUR'       =>$data->OUTPUT_TOLAK_UKUR,
        //                              'TARGET'           =>$data->OUTPUT_TARGET." ".$data->satuan->SATUAN_NAMA));
        // }
        foreach ($impact as $data) {
            array_push($view, array( 'INDIKATOR'        =>'Hasil / Outcome',
                                     'TOLAK_UKUR'       =>$data->IMPACT_TOLAK_UKUR,
                                     'TARGET'           =>$data->IMPACT_TARGET." ".$data->satuan->SATUAN_NAMA));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);

    }

    public function getEditCapaian($tahun, $status, $id){
        $program    = Kegiatan::whereHas('bl',function($x) use($id){
                                            $x->where('BL_ID',$id);
                                        })->value('PROGRAM_ID');
        $outcome    = Outcome::where('PROGRAM_ID',$program)->get();
        $impact     = Impact::where('PROGRAM_ID',$program)->get();
        $output     = Output::where('BL_ID',$id)->get();
        $view           = array();
        foreach ($outcome as $data) {
            array_push($view, array( 'INDIKATOR'        =>'Capaian Program / Sasaran',
                                     'TOLAK_UKUR'       =>$data->OUTCOME_TOLAK_UKUR,
                                     'OPSI'             =>'-',
                                     'TARGET'           =>$data->OUTCOME_TARGET." ".$data->satuan->SATUAN_NAMA));
        }
        foreach ($output as $data) {
            $aksi       = '<div class="action visible pull-right"><a onclick="return editOutput(\''.$data->OUTPUT_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapusOutput(\''.$data->OUTPUT_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            array_push($view, array( 'INDIKATOR'        =>'Output / Keluaran',
                                     'TOLAK_UKUR'       =>$data->OUTPUT_TOLAK_UKUR,
                                     'OPSI'             =>$aksi,
                                     'TARGET'           =>$data->OUTPUT_TARGET." ".$data->satuan->SATUAN_NAMA));
        }
        foreach ($impact as $data) {
            array_push($view, array( 'INDIKATOR'        =>'Hasil / Outcome',
                                     'TOLAK_UKUR'       =>$data->IMPACT_TOLAK_UKUR,
                                     'OPSI'             =>'-',
                                     'TARGET'           =>$data->IMPACT_TARGET." ".$data->satuan->SATUAN_NAMA));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);

    }

    public function getRekening($tahun, $status, $id){
        // $data       = Rekening::whereHas('rekom', function($q) use ($id){
        //     $q->whereHas('komponen',function($x) use ($id){
        //         $x->where('KOMPONEN_KODE','like',$id.'%');
        //     }); 
        // })->where('REKENING_TAHUN',$tahun)->where('REKENING_KUNCI',0)->orderBy('REKENING_KODE')->get();  
        // ->where('REKENING_TAHUN',$tahun)      
        $data       = Rekening::where('REKENING_KODE','like','5%')->where('REKENING_TAHUN',$tahun)->whereRaw('LENGTH("REKENING_KODE") = 11')->where('REKENING_KUNCI',0)->get();
        $view       = "";
        foreach($data as $d){
            $view .= "<option value='".$d->REKENING_ID."'>".$d->REKENING_KODE.'-'.$d->REKENING_NAMA."</option>";
        }
        return $view;
    }    
    public function getKomponen($tahun, $status, $id,$blid){
        $kompbl     = Rincian::where('BL_ID',$blid)->select('KOMPONEN_ID')->get();
        $komp       = array();
        $i          = 0;
        foreach($kompbl as $k){
            $komp[$i]   = $k->KOMPONEN_ID;
            $i++;
        }
        $data       = Komponen::whereHas('rekom', function($q) use ($id){
            $q->whereHas('rekening',function($x) use ($id){
                $x->where('REKENING_ID',$id);
            }); 
        // })->where('KOMPONEN_TAHUN',$tahun)->whereNotIn('KOMPONEN_ID',$komp)->orderBy('KOMPONEN_KODE')->get();
        })->where('KOMPONEN_KUNCI',0)->orderBy('KOMPONEN_KODE')->get();
        // ->where('KOMPONEN_TAHUN',$tahun)
        $view           = array();
        foreach ($data as $data) {
            $nama       = $data->KOMPONEN_NAMA."<br><p class='text-orange'>Spesifikasi : ".$data->KOMPONEN_SPESIFIKASI."</p>";
            array_push($view, array( 'KOMPONEN_ID'      =>$data->KOMPONEN_ID,
                                     'KOMPONEN_KODE'    =>$data->KOMPONEN_KODE,
                                     'KOMPONEN_SHOW'    =>$data->KOMPONEN_NAMA,
                                     'KOMPONEN_HARGA_'  =>$data->KOMPONEN_HARGA,
                                     'KOMPONEN_NAMA'    =>$nama,
                                     'KOMPONEN_SATUAN'  =>$data->KOMPONEN_SATUAN,
                                     'KOMPONEN_HARGA'   =>number_format($data->KOMPONEN_HARGA,0,'.',',').' / '.$data->KOMPONEN_SATUAN));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }    

    public function getMurni($tahun,$status,$filter){        
        if($status == 'murni') return $this->getDataMurni($tahun,$status,$filter);
        else return $this->getDataPerubahan($tahun,$status,$filter);
    }

    public function getDataMurni($tahun,$status,$filter){
        $pagu_foot    = 0;
        $rincian_foot = 0;
        //$now        = Carbon\Carbon::now()->format('Y-m-d h:m:s');
        $now = date('Y-m-d H:m:s');
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS',$status)->orderBy('TAHAPAN_ID','desc')->first();
        if($tahapan){
            if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
                $thp    = 1;
            }else{
                $thp    = 0;
            }
        }else{
            $thp = 0;
        }

        //print_r(substr(Auth::user()->mod,1,1));exit;
        //print_r(Auth::user()->level);exit;

        if((Auth::user()->level == 1 and substr(Auth::user()->mod,1,1) == 0) or Auth::user()->level == 2){
            $skpd       = $this->getSKPD($tahun);
            $data       = BL::whereHas('subunit',function($q) use ($skpd){
                                $q->where('SKPD_ID',$skpd);
                        })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->get();

            $pagu_foot       = BL::whereHas('subunit',function($q) use ($skpd){
                                        $q->where('SKPD_ID',$skpd);
                                })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->sum('BL_PAGU');

            $rincian_foot       = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                                    ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                                    ->where('DAT_BL.BL_TAHUN',$tahun)->where('DAT_BL.BL_DELETED',0)
                                    ->WHERE('REF_SUB_UNIT.SKPD_ID',$skpd)->sum('DAT_RINCIAN.RINCIAN_TOTAL');



        }elseif(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->level == 0){
            if($filter == 0){
                //$data       = BL::where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->get();
                $data       = BL::where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->take(1000)->get();
                $pagu_foot    = 0;
                $rincian_foot = 0;
            }else{
                $data       = BL::whereHas('subunit',function($q) use ($filter){
                                        $q->where('SKPD_ID',$filter);
                                })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->get();

                $pagu_foot       = BL::whereHas('subunit',function($q) use ($filter){
                                        $q->where('SKPD_ID',$filter);
                                })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->sum('BL_PAGU');

                $rincian_foot       = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                                        ->where('DAT_BL.BL_TAHUN',$tahun)->where('DAT_BL.BL_DELETED',0)
                                        ->WHERE('REF_SUB_UNIT.SKPD_ID',$filter)->sum('DAT_RINCIAN.RINCIAN_TOTAL');

            }
        }else{
            $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->get();
            $skpd_      = array(); 
            $i = 0;
            foreach($skpd as $s){
                $skpd_[$i]   = $s->SKPD_ID;
                $i++;
            }
            // print_r($skpd_);exit();
            if($filter == 0){
                $data       = BL::whereHas('subunit',function($q) use ($skpd_){
                                        $q->whereIn('SKPD_ID',$skpd_);
                                })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->get();
                $pagu_foot    = 0;
                $rincian_foot = 0;
            }else{
                $data       = BL::whereHas('subunit',function($q) use ($filter){
                                        $q->where('SKPD_ID',$filter);
                                })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->get();   

                $pagu_foot       = BL::whereHas('subunit',function($q) use ($filter){
                                        $q->where('SKPD_ID',$filter);
                                })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->sum('BL_PAGU');

                $rincian_foot       = Rincian::join('BUDGETING.DAT_BL','DAT_BL.BL_ID','=','DAT_RINCIAN.BL_ID')
                                        ->join('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                                        ->where('DAT_BL.BL_TAHUN',$tahun)->where('DAT_BL.BL_DELETED',0)
                                        ->WHERE('REF_SUB_UNIT.SKPD_ID',$filter)->sum('DAT_RINCIAN.RINCIAN_TOTAL');                             
            }

        }


        $view       = array();
        $i          = 1;
        $kunci      = '';
        $rincian    = '';
        $validasi   = '';
        foreach ($data as $data) {
            $urgensi    = Urgensi::where('BL_ID',$data->BL_ID)->first();
            if(((Auth::user()->level == 1 and substr(Auth::user()->mod,1,1) == 0)or Auth::user()->level == 2) and (
                empty($urgensi->URGENSI_LATAR_BELAKANG) or
                empty($urgensi->URGENSI_DESKRIPSI) or
                empty($urgensi->URGENSI_TUJUAN) or
                empty($urgensi->URGENSI_PENERIMA_1) or
                empty($urgensi->URGENSI_PELAKSANAAN)) and $urgensi and ($skpd == 24 or $skpd == 15 or $skpd == 22 or $skpd == 14))
            $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return seturgensi(\''.$data->BL_ID.'\')"><i class="fa fa-search"></i> Detail</a></li>';
            else
            $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a href="/main/'.$tahun.'/'.$status.'/belanja-langsung/detail/'.$data->BL_ID.'"><i class="fa fa-search"></i> Detail</a></li>';                

            if($data->kunci->KUNCI_GIAT == 0 and $thp == 1){
                if(Auth::user()->level == 8){
                    $kunci     = '<label class="i-switch bg-danger m-t-xs m-r buka-giat"><input type="checkbox" onchange="return kuncigiat(\''.$data->BL_ID.'\')" id="kuncigiat-'.$data->BL_ID.'"><i></i></label>';
                }else{
                    $kunci     = '<span class="text-success"><i class="fa fa-unlock kunci-giat"></i></span>';
                }
                //if(Auth::user()->level == 4){
                //$no        .='<li><a href="/main/'.$tahun.'/'.$status.'/belanja-langsung/ubah/'.$data->BL_ID.'"><i class="fa fa-pencil-square"></i> Ubah</a></li>';
                //}
            }else{
                if(Auth::user()->level == 8){
                    $kunci      = '<label class="i-switch bg-danger m-t-xs m-r kunci-giat"><input type="checkbox" onchange="return kuncigiat(\''.$data->BL_ID.'\')" id="kuncigiat-'.$data->BL_ID.'" checked><i></i></label>';
                }else{
                    $kunci      = '<span class="text-danger"><i class="fa fa-lock kunci-giat"></i></span>';
                }             
                //if(Auth::user()->level == 4){
                //    $no        .='<li><a><i class="fa fa-pencil-square"></i> Ubah <i class="fa fa-lock"></i></a></li>';   
                //}
            }

            if($data->kunci->KUNCI_RINCIAN == 0 and $thp == 1){
                //if(substr(Auth::user()->mod,1,1) == 1 or Auth::user()->level == 8){
                if(substr(Auth::user()->mod,1,1) == 1 or Auth::user()->level == 9){
                    $rincian    = '<label class="i-switch bg-danger m-t-xs m-r"><input type="checkbox" onchange="return kuncirincian(\''.$data->BL_ID.'\')" id="kuncirincian-'.$data->BL_ID.'"><i></i></label>';
                }else{
                    $rincian    = '<span class="text-success"><i class="fa fa-unlock kunci-rincian"></i></span>';
                }                
            }else{
                if(substr(Auth::user()->mod,1,1) == 1 or Auth::user()->level == 9){
                    $rincian    = '<label class="i-switch bg-danger m-t-xs m-r"><input type="checkbox" onchange="return kuncirincian(\''.$data->BL_ID.'\')" id="kuncirincian-'.$data->BL_ID.'" checked="checked"><i></i></label>';
                }else{
                    $rincian    = '<span class="text-danger"><i class="fa fa-lock kunci-rincian"></i></span>';
                }             
            }

            if((Auth::user()->level == 2 and $data->kunci->KUNCI_GIAT == 0) or Auth::user()->level == 8 or substr(Auth::user()->mod,1,1) == 1){
                //$no            .= '<li><a href="belanja-langsung/ubah/'.$data->BL_ID.'" target="_blank"><i class="mi-edit m-r-xs"></i> Ubah</button></li><li><a href="belanja-langsung/indikator/'.$data->BL_ID.'" target="_blank"><i class="fa fa-info-circle m-r-xs"></i> Indikator</button></li><li><a onclick="return staff(\''.$data->BL_ID.'\')"><i class="icon-bdg_people m-r-xs"></i> Atur Staff</a></li>';
                $no            .= '<li><a href="belanja-langsung/ubah/'.$data->BL_ID.'" target="_blank"><i class="mi-edit m-r-xs"></i> Ubah</button></li><li><a href="belanja-langsung/indikator/'.$data->BL_ID.'" target="_blank"><i class="fa fa-info-circle m-r-xs"></i> Indikator</button></li>';
            }

            if(Auth::user()->level == 2 or Auth::user()->level == 8 or substr(Auth::user()->mod,1,1) == 1 and $thp == 1){
                $no            .= '<li><a onclick="return staff(\''.$data->BL_ID.'\')"><i class="icon-bdg_people m-r-xs"></i> Atur Staff</a></li>';
            }

            //HAPUS BL
            //<li><a onclick="return hapus(\''.$data->BL_ID.'\')"><i class="mi-trash m-r-xs"></i> Hapus</button></li>
            if(substr(Auth::user()->mod,1,1) == 1 or Auth::user()->level == 8){
                $no            .= '<li><a onclick="return setpagu(\''.$data->BL_ID.'\')"><i class="fa fa-money m-r-xs"></i> Set Pagu</button></li>';
            }

            $tahunnow   = Carbon\Carbon::now()->format('Y');
            if($tahun < $tahunnow+1 and Auth::user()->level == 2){
                $no   .= '<li><a onclick="return trftoperubahan(\''.$data->BL_ID.'\')"><i class="fa fa-repeat"></i> Perubahan</a></li>';
            }
            
            if($data->BL_VALIDASI == 0){
                $validasi  = '<span class="text-danger"><i class="fa fa-close"></i></span>';
                $no        .= '<li><a href="/main/'.$tahun.'/'.$status.'/belanja-langsung/rka/'.$data->BL_ID.'" target="_blank"><i class="fa fa-print"></i> Cetak RKA</a></li>
                <li><a onclick="return validasi(\''.$data->BL_ID.'\')"><i class="fa fa-key"></i> Validasi </a></li>
                <li class="divider"></li>
                <li><a onclick="return log(\''.$data->BL_ID.'\')"><i class="fa fa-info-circle"></i> Info</a></li>';
            }else{
                $validasi  = '<span class="text-success"><i class="fa fa-check"></i></span>';
                $no        .= '<li><a href="/main/'.$tahun.'/'.$status.'/belanja-langsung/rka/'.$data->BL_ID.'" target="_blank"><i class="fa fa-print"></i> Cetak RKA</a></li>
                <li><a onclick="return validasi(\''.$data->BL_ID.'\')"><i class="fa fa-key"></i> Validasi </a></li>
                <li class="divider"></li>
                <li><a onclick="return log(\''.$data->BL_ID.'\')"><i class="fa fa-info-circle"></i> Info</a></li>';
            }
            $no     .= '</ul></div>';
            if(empty($data->rincian)) $totalRincian = 0;
            else $totalRincian = number_format($data->rincian->sum('RINCIAN_TOTAL'),0,'.',',');
            array_push($view, array( 'NO'             =>$no,
                                     'KEGIATAN'       =>$data->kegiatan->program->urusan->URUSAN_KODE.'.'.$data->subunit->skpd->SKPD_KODE.'.'.$data->kegiatan->program->PROGRAM_KODE.' - '.$data->kegiatan->program->PROGRAM_NAMA.'<br><p class="text-orange">'.$data->kegiatan->program->urusan->URUSAN_KODE.'.'.$data->subunit->skpd->SKPD_KODE.'.'.$data->kegiatan->program->PROGRAM_KODE.'.'.$data->kegiatan->KEGIATAN_KODE.' - '.$data->kegiatan->KEGIATAN_NAMA.'</p><span class="text-success">'.$data->subunit->skpd->SKPD_KODE.'.'.$data->subunit->SUB_KODE.' - '.$data->subunit->SUB_NAMA.'</span>',
                                     'PAGU'           =>number_format($data->BL_PAGU,0,'.',','),
                                     'RINCIAN'        =>$totalRincian,
                                     'STATUS'         =>$kunci.' Kegiatan<br>'.$rincian.' Rincian<br>'.$validasi.' Validasi'));
            //$pagu_foot    =+ $data->BL_PAGU;
            //$rincian_foot =+ $data->rincian->sum('RINCIAN_TOTAL');
            $i++;
        }
        $out = array("aaData"=>$view,
                    "pagu_foot"=>number_format($pagu_foot,0,'.',','),
                    "rincian_foot"=>number_format($rincian_foot,0,'.',','),
            );      
        return Response::JSON($out);
        return $view;
    }

    public function getDataPerubahan($tahun,$status,$filter){
        //$now        = Carbon\Carbon::now()->format('Y-m-d h:m:s');
        $now = date('Y-m-d H:m:s');
        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_STATUS','perubahan')->orderBy('TAHAPAN_ID','desc')->first();
        if($now > $tahapan->TAHAPAN_AWAL && $now < $tahapan->TAHAPAN_AKHIR){
            $thp    = 1;
        }else{
            $thp    = 0;
        }

        if((Auth::user()->level == 1 and substr(Auth::user()->mod,1,1) == 0)or Auth::user()->level == 2){
            $skpd       = $this->getSKPD($tahun);
            $data       = BLPerubahan::whereHas('subunit',function($q) use ($skpd){
                                $q->where('SKPD_ID',$skpd);
                        })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->get();
        }elseif(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->level == 0){
            if($filter == 0){
                //$data       = BLPerubahan::where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->get();
                $data       = BLPerubahan::where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->take(1000)->get();
            }else{
                $data       = BLPerubahan::whereHas('subunit',function($q) use ($filter){
                                        $q->where('SKPD_ID',$filter);
                                })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->get();                
            }
        }else{
            $skpd       = UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->get();
            $skpd_      = array(); 
            $i = 0;
            foreach($skpd as $s){
                $skpd_[$i]   = $s->SKPD_ID;
                $i++;
            }
            // print_r($skpd_);exit();
            if($filter == 0){
                $data       = BLPerubahan::whereHas('subunit',function($q) use ($skpd_){
                                        $q->whereIn('SKPD_ID',$skpd_);
                                })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->get();
            }else{
                $data       = BLPerubahan::whereHas('subunit',function($q) use ($filter){
                                        $q->where('SKPD_ID',$filter);
                                })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->get();                
            }

        }

        $pagu_murni     = BL::whereHas('subunit',function($q) use ($filter){
                                        $q->where('SKPD_ID',$filter);
                                })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->sum('BL_PAGU');

        $pagu_perubahan = BLPerubahan::whereHas('subunit',function($q) use ($filter){
                                        $q->where('SKPD_ID',$filter);
                                })->where('BL_TAHUN',$tahun)->where('BL_DELETED',0)->sum('BL_PAGU');

       // $pagu_skpd      = SKPD::select('SKPD_PAGU')->where('SKPD_ID',$filter)->get();

        $view       = array();
        $i          = 1;
        $kunci      = '';
        $rincian    = '';
        $validasi   = '';
        foreach ($data as $data) {
            $urgensi    = Urgensi::where('BL_ID',$data->BL_ID)->first();
            if((Auth::user()->level == 1 or Auth::user()->level == 2) and (
                empty($urgensi->URGENSI_LATAR_BELAKANG) or
                empty($urgensi->URGENSI_DESKRIPSI) or
                empty($urgensi->URGENSI_TUJUAN) or
                empty($urgensi->URGENSI_PENERIMA_1) or
                empty($urgensi->URGENSI_PELAKSANAAN)) and $urgensi and ($skpd == 24 or $skpd == 15 or $skpd == 22 or $skpd == 14))
            $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a onclick="return seturgensi(\''.$data->BL_ID.'\')"><i class="fa fa-search"></i> Detail</a></li>';
            else
            $no = '<div class="dropdown dropdown-blend" style="float:right;"><a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="text text-success"><i class="fa fa-chevron-down"></i></span></a><ul class="dropdown-menu" aria-labelledby="dropdownMenu2"><li><a href="/main/'.$tahun.'/'.$status.'/belanja-langsung/detail/'.$data->BL_ID.'"><i class="fa fa-search"></i> Detail</a></li>';                

            if($data->Kunci->KUNCI_GIAT == 0 and $thp == 1){
                if(Auth::user()->level == 8){
                    $kunci     = '<label class="i-switch bg-danger m-t-xs m-r buka-giat"><input type="checkbox" onchange="return kuncigiat(\''.$data->BL_ID.'\')" id="kuncigiat-'.$data->BL_ID.'"><i></i></label>';
                }else{
                    $kunci     = '<span class="text-success"><i class="fa fa-unlock kunci-giat"></i></span>';
                }
                //if(Auth::user()->level == 4){
                //$no        .='<li><a href="/main/'.$tahun.'/'.$status.'/belanja-langsung/ubah/'.$data->BL_ID.'"><i class="fa fa-pencil-square"></i> Ubah</a></li>';
                //}
            }else{
                if(Auth::user()->level == 8){
                    $kunci      = '<label class="i-switch bg-danger m-t-xs m-r kunci-giat"><input type="checkbox" onchange="return kuncigiat(\''.$data->BL_ID.'\')" id="kuncigiat-'.$data->BL_ID.'" checked><i></i></label>';
                }else{
                    $kunci      = '<span class="text-danger"><i class="fa fa-lock kunci-giat"></i></span>';
                }             
                //if(Auth::user()->level == 4){
                //    $no        .='<li><a><i class="fa fa-pencil-square"></i> Ubah <i class="fa fa-lock"></i></a></li>';   
                //}
            }

            if($data->kunci->KUNCI_RINCIAN == 0 and $thp == 1){
                if(substr(Auth::user()->mod,1,1) == 1 or Auth::user()->level == 9){
                    $rincian    = '<label class="i-switch bg-danger m-t-xs m-r"><input type="checkbox" onchange="return kuncirincian(\''.$data->BL_ID.'\')" id="kuncirincian-'.$data->BL_ID.'"><i></i></label>';
                }else{
                    $rincian    = '<span class="text-success"><i class="fa fa-unlock kunci-rincian"></i></span>';
                }                
            }else{
                if(substr(Auth::user()->mod,1,1) == 1 or Auth::user()->level == 9){
                    $rincian    = '<label class="i-switch bg-danger m-t-xs m-r"><input type="checkbox" onchange="return kuncirincian(\''.$data->BL_ID.'\')" id="kuncirincian-'.$data->BL_ID.'" checked><i></i></label>';
                }else{
                    $rincian    = '<span class="text-danger"><i class="fa fa-lock kunci-rincian"></i></span>';
                }             
            }

            if((Auth::user()->level == 2 and $data->kunci->KUNCI_GIAT == 0) or Auth::user()->level == 8 or substr(Auth::user()->mod,1,1) == 1){
                //$no            .= '<li><a href="belanja-langsung/ubah/'.$data->BL_ID.'" target="_blank"><i class="mi-edit m-r-xs"></i> Ubah</button></li><li><a href="belanja-langsung/indikator/'.$data->BL_ID.'" target="_blank"><i class="fa fa-info-circle m-r-xs"></i> Indikator</button></li><li><a onclick="return staff(\''.$data->BL_ID.'\')"><i class="icon-bdg_people m-r-xs"></i> Atur Staff</a></li>';
                $no            .= '<li><a href="belanja-langsung/ubah/'.$data->BL_ID.'" target="_blank"><i class="mi-edit m-r-xs"></i> Ubah</button></li><li><a href="belanja-langsung/indikator/'.$data->BL_ID.'" target="_blank"><i class="fa fa-info-circle m-r-xs"></i> Indikator</button></li>';
            }

            if(Auth::user()->level == 2 or Auth::user()->level == 8 or substr(Auth::user()->mod,1,1) == 1 and $thp == 1){
                $no            .= '<li><a onclick="return staff(\''.$data->BL_ID.'\')"><i class="icon-bdg_people m-r-xs"></i> Atur Staff</a></li>';
            }

            //HAPUS BL
            //<li><a onclick="return hapus(\''.$data->BL_ID.'\')"><i class="mi-trash m-r-xs"></i> Hapus</button></li>
            if(substr(Auth::user()->mod,1,1) == 1 or Auth::user()->level == 8 or Auth::user()->level == 2){
                $no            .= '<li><a onclick="return setpagu(\''.$data->BL_ID.'\')"><i class="fa fa-money m-r-xs"></i> Set Pagu</button></li>';
            }

            if(($data->BL_VALIDASI == 0 && Auth::user()->active==1 ) || Auth::user()->level==8){
                $validasi  = '<span class="text-danger"><i class="fa fa-close"></i></span>';
                $no        .= '<li><a href="/main/'.$tahun.'/'.$status.'/belanja-langsung/rka/'.$data->BL_ID.'" target="_blank"><i class="fa fa-print"></i> Cetak RKA</a></li><li><a onclick="return validasi(\''.$data->BL_ID.'\')"><i class="fa fa-key"></i> Validasi </a></li><li class="divider"></li>
                <li><a onclick="return log(\''.$data->BL_ID.'\')"><i class="fa fa-info-circle"></i> Info</a></li></ul></div>';
            }else{
                $validasi  = '<span class="text-success"><i class="fa fa-check"></i></span>';
                $no        .= '<li><a href="/main/'.$tahun.'/'.$status.'/belanja-langsung/rka/'.$data->BL_ID.'" target="_blank"><i class="fa fa-print"></i> Cetak RKA</a></li>
                <li class="divider"></li>
                <li><a onclick="return log(\''.$data->BL_ID.'\')"><i class="fa fa-info-circle"></i> Info</a></li></ul></div>';

            }
            if(empty($data->rincian)) $totalRincian = 0;
            else $totalRincian = number_format($data->rincian->sum('RINCIAN_TOTAL'),0,'.',',');
            $datasebelum        = BL::where('BL_ID',$data->BL_ID)->first();
            $rinciansebelum     = Rincian::where('BL_ID',$data->BL_ID)->sum('RINCIAN_TOTAL');
            // if(empty($rinciansebelum)) $rinciansebelum = 0;
            // else $rinciansebelum = number_format($rinciansebelum,0,'.',',');
            $realisasi  = Realisasi::where('BL_ID',$data->BL_ID)->sum('REALISASI_TOTAL');

            (empty($datasebelum->BL_PAGU))?$blpagu_sebelum = 0:$blpagu_sebelum=$datasebelum->BL_PAGU;


            if(empty($realisasi)) $realisasi = 0;
            array_push($view, array( 'NO'             =>$no,
                                     'KEGIATAN'       =>$data->kegiatan->program->urusan->URUSAN_KODE.'.'.$data->subunit->skpd->SKPD_KODE.'.'.$data->kegiatan->program->PROGRAM_KODE.' - '.$data->kegiatan->program->PROGRAM_NAMA.'<br><p class="text-orange">'.$data->kegiatan->program->urusan->URUSAN_KODE.'.'.$data->subunit->skpd->SKPD_KODE.'.'.$data->kegiatan->program->PROGRAM_KODE.'.'.$data->kegiatan->KEGIATAN_KODE.' - '.$data->kegiatan->KEGIATAN_NAMA.'</p><span class="text-success">'.$data->subunit->skpd->SKPD_KODE.'.'.$data->subunit->SUB_KODE.' - '.$data->subunit->SUB_NAMA.'</span>',
                                     'PAGU_SEBELUM'           =>number_format($blpagu_sebelum,0,'.',','),
                                     'RINCIAN_SEBELUM'        =>number_format($realisasi,0,'.',','),
                                     'PAGU_SESUDAH'           =>number_format($data->BL_PAGU,0,'.',','),
                                     'RINCIAN_SESUDAH'        =>$totalRincian,
                                     'STATUS'         =>$kunci.' Kegiatan<br>'.$rincian.' Rincian<br>'.$validasi.' Validasi'));
            $i++;
        }
        $out = array("aaData"=>$view,
                    "pagu_murni"=>number_format($pagu_murni,0,'.',','),
                    "pagu_perubahan"=>number_format($pagu_perubahan,0,'.',','),
                    );      
        return Response::JSON($out);
        return $view;
    }

    public function getStaff($tahun, $status, $id){
        $staff      = Staff::where('BL_ID',$id)->get();
        if(empty($staff)) return 'KOSONG';
        else return Response::JSON($staff);        
    }

    public function setStaff($tahun, $status){
        $data       = Staff::where('BL_ID',Input::get('BL_ID'))->value('STAFF_ID');
        if(empty($data)){
            $staff = new Staff;
            $staff->BL_ID       = Input::get('BL_ID');
            $staff->USER_ID     = Input::get('staff1');
            $staff->save();
            if(!empty(Input::get('staff2'))){
                $staff = new Staff;
                $staff->BL_ID       = Input::get('BL_ID');
                $staff->USER_ID     = Input::get('staff2');
                $staff->save();                
            }
        }else{
            Staff::where('STAFF_ID',Input::get('idstaff1'))->update(['USER_ID'=>Input::get('staff1')]);
            if(!empty(Input::get('idstaff2'))){
                Staff::where('STAFF_ID',Input::get('idstaff2'))->update(['USER_ID'=>Input::get('staff2')]);
            }
            if(!empty(Input::get('staff2'))){
                $staff = new Staff;
                $staff->BL_ID       = Input::get('BL_ID');
                $staff->USER_ID     = Input::get('staff2');
                $staff->save();                
            }            
        }
        return 'Berhasil!';
    }

    public function getLog($tahun,$status,$id){
        if($status == 'murni') $bl         = BL::where('BL_ID',$id)->first();
        else $bl         = BLPerubahan::where('BL_ID',$id)->first();

        //$bl         = BL::where('BL_ID',$id)->first();    
       // $creator    = User::where('id',$bl->USER_CREATED)->first();
        $staff      = Staff::whereHas('user', function($q){
                        $q->where('level','1');
                    })->where('BL_ID',$id)->get();
        $staff1     = "-";
        $staff2     = "-";
        if(!empty($staff[0])) $staff1 = $staff[0]->user->email.' - '.$staff[0]->user->name;
        if(!empty($staff[1])) $staff2 = $staff[1]->user->email.' - '.$staff[1]->user->name;
        if($status == 'murni') $log        = Log::where('LOG_DETAIL','BL#'.$id)->orWhere('LOG_DETAIL','like','BL#'.$id.'#%')->orderBy('LOG_ID')->get();
        else $log        = Log::where('LOG_DETAIL','BLPERUBAHAN#'.$id)->orWhere('LOG_DETAIL','like','BL#'.$id.'#%')->orderBy('LOG_ID')->get();
        $timeline   = '';
        foreach ($log as $l) {
            // $filename= 'http://simpeg.bandung.go.id/uploads/'.$l->user->email.'.jpg';
            // $file_headers = @get_headers($filename);
            // // print_r($file_headers[0]);exit();
            // if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
            //     $img = '/assets/img/01.jpg';
            // } else if ($file_headers[0] == 'HTTP/1.0 302 Found' && $file_headers[7] == 'HTTP/1.0 404 Not Found'){
                $img = '/assets/img/01.jpg';
            // } else {
            //     $img = 'http://simpeg.bandung.go.id/uploads/'.$l->user->email.'.jpg';
            // }
            $timeline .= '<div>
                          <a class="pull-left thumb-sm avatar m-l-n-md">
                            <img src="'.$img.'" class="b-2x b-white img-circle" alt="...">
                          </a>
                          <div class="m-l-xxl">
                            <div class="m-b-sm">
                              <a class="text-orange">'.$l->user->email.'</a> <a class="h4 font-semibold">'.$l->user->name.'</a>
                              <span class="text-muted m-l-sm pull-right">
                                '.$l->LOG_TIME.'
                              </span>
                            </div>
                            <div class="m-b">
                              <div>'.$l->LOG_ACTIVITY.'</div>
                            </div>
                          </div>
                        </div>
                        <hr>';
        }
       // dd($creator);
        $view             = array(//'creator'      =>$creator->email.' - '.$creator->name,
                                 'staff1'        =>$staff1,
                                 'staff2'        =>$staff2,
                                 'created'       =>$bl->TIME_CREATED,
                                 'updated'       =>$bl->TIME_UPDATED,
                                 'log'           =>$timeline);        
        $out = array("header"=>$view);      
        return $out;
    }

    public function getRekMusren($tahun,$status,$id){
        $rincian    = Rincian::where('RINCIAN_ID',$id)->value('KOMPONEN_ID');
        $data       = Rekening::whereHas('rekom',function($q) use ($rincian){
                        $q->where('KOMPONEN_ID',$rincian);
                    })->where('REKENING_TAHUN',$tahun)->get();
        $view       = "";
        foreach($data as $d){
            $view .= "<option value='".$d->REKENING_ID."'>".$d->REKENING_NAMA."</option>";
        }
        return $view;
    }

    public function setMusren($tahun,$status){
        Rincian::where('RINCIAN_ID',Input::get('RINCIAN_ID'))->update([
            'REKENING_ID'=>Input::get('REKENING_ID'),
            'PEKERJAAN_ID'=>Input::get('PEKERJAAN_ID'),
            'SUBRINCIAN_ID'=>Input::get('PAKET_ID')]);

        $totalrincian   = Rincian::where('BL_ID',Input::get('BL_ID'))->sum('RINCIAN_TOTAL');
        return number_format($totalrincian,0,'.',',');
    }

    public function showRekap($tahun,$status,$tipe,$id){
        //$now        = Carbon\Carbon::now()->format('Y-m-d h:m:s');
        $now = date('Y-m-d H:m:s');
        $param      = '';
        if($tipe == 'rkpd') $param = 'RKPD';
        elseif($tipe == 'ppas') $param = 'KUA/PPAS';
        elseif($tipe == 'rapbd') $param = 'RAPBD';
        elseif($tipe == 'apbd') $param = 'APBD';

        $tahapan    = Tahapan::where('TAHAPAN_TAHUN',$tahun)->where('TAHAPAN_NAMA',$param)->first();
        if(empty($tahapan)) $data = RekapRincian::where('BL_ID',$id)->where('TAHAPAN_ID',0)->get();
        else $data       = RekapRincian::where('BL_ID',$id)->where('TAHAPAN_ID',$tahapan->TAHAPAN_ID)->get();
        $view       = array();
        $i         = 1;
        $pajak      = '';
            foreach ($data as $data) {
                $no = '<div class="btn-group dropdown"><button class="btn m-b-sm m-r-sm btn-default btn-sm" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button><ul class="dropdown-menu"><li class="divider"></li><li><a onclick="return info(\''.$data->RINCIAN_ID.'\')"><i class="fa fa-info-circle"></i>Info</a></li></ul></div>';

                if($data->RINCIAN_PAJAK == 10) $pajak = '<span class="text-success"><i class="fa fa-check"></i></span>';
                else $pajak = '<span class="text-danger"><i class="fa fa-close"></i></span>';
                array_push($view, array( 'NO'             =>$no,
                                         'REKENING'       =>$data->rekening->REKENING_KODE.'<br><p class="text-orange">'.$data->rekening->REKENING_NAMA.'</p>',
                                         'KOMPONEN'       =>$data->komponen->KOMPONEN_KODE.'<br><p class="text-orange">'.$data->komponen->KOMPONEN_NAMA.'</p>',
                                         'SUB'            =>$data->RINCIAN_SUBTITLE,
                                         'PAJAK'          =>$pajak,
                                         'HARGA'          =>number_format($data->komponen->KOMPONEN_HARGA,0,'.',',').'<br><p class="text-orange">'.$data->RINCIAN_KOEFISIEN.'</p>',
                                         'TOTAL' =>number_format($data->RINCIAN_TOTAL,0,'.',',')));
            }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
        return $view;
    }

    public function getpagu($tahun, $status, $id){
        if($status == 'murni') return BL::where('BL_ID',$id)->first();
        else return BLPerubahan::where('BL_ID',$id)->first();
    }

    public function setPagu($tahun, $status){
        if($status == 'murni'){
            BL::where('BL_ID',Input::get('BL_ID'))->update(['BL_PAGU'=>Input::get('BL_PAGU'),'BL_VALIDASI'=>0,'BL_PAGU_CATATAN'=>Input::get('BL_PAGU_CATATAN')]);
            $log                = new Log;
            $log->LOG_TIME                          = Carbon\Carbon::now();
            $log->USER_ID                           = Auth::user()->id;
            $log->LOG_ACTIVITY                      = 'Set Pagu Total Rp. '.number_format(round(Input::get('BL_PAGU')),0,',','.');
            $log->LOG_DETAIL                        = 'BL#'.Input::get('BL_ID');
            $log->save();
        }else{
            if(Auth::user()->level == 2){
               BLPerubahan::where('BL_ID',Input::get('BL_ID'))
                        ->update(['BL_PAGU_USULAN'  => Input::get('BL_PAGU_USULAN'),
                                  'BL_VALIDASI'     => 0,
                                  'BL_PAGU_SURAT'   => Input::get('BL_PAGU_SURAT'),
                                  'BL_PAGU_CATATAN' => Input::get('BL_PAGU_CATATAN')]);                                
            }else{
               BLPerubahan::where('BL_ID',Input::get('BL_ID'))
                        ->update(['BL_PAGU'         => Input::get('BL_PAGU'),
                                  'BL_VALIDASI'     => 0,
                                  'BL_PAGU_SURAT'   => Input::get('BL_PAGU_SURAT'),
                                  'BL_PAGU_USULAN'  => 0,
                                  'BL_PAGU_CATATAN' => Input::get('BL_PAGU_CATATAN')]);                
                $log                = new Log;
                $log->LOG_TIME                          = Carbon\Carbon::now();
                $log->USER_ID                           = Auth::user()->id;
                $log->LOG_ACTIVITY                      = 'Set Pagu Total Rp. '.number_format(round(Input::get('BL_PAGU')),0,',','.');
                $log->LOG_DETAIL                        = 'BLPERUBAHAN#'.Input::get('BL_ID');
                $log->save();
            }            
        }
        return 'Berhasil!';
    }

    public function getpagurincian($tahun,$status,$id){
        $blpagu         = BL::where('BL_ID',$id)->value('BL_PAGU');
        $rincian_total   = Rincian::where('BL_ID',$id)->sum('RINCIAN_TOTAL');

        $id        = $this->getSKPD($tahun);
        $pagu      = SKPD::where('SKPD_ID',$id)->value('SKPD_PAGU');

        $data      = array('pagu'=>$blpagu,
                           'rincian'=>$rincian_total,
                           'sisa'=>$blpagu - $rincian_total);

        return Response::JSON($data);
    }

    public function setpaguBL($blid){
        $pagu       = BL::where('BL_ID',$blid)->value('BL_PAGU');
        $rincian    = Rincian::where('BL_ID',$blid)->sum('RINCIAN_TOTAL');
        if($rincian < $pagu) $bl_pagu   = $rincian;
        else $bl_pagu = $pagu;
        BL::where('BL_ID',$blid)->update(['BL_PAGU'=>$bl_pagu]);
        return number_format($bl_pagu,0,'.',','); 
    }

    public function setUrgensi(){
        Urgensi::where('BL_ID',Input::get('BL_ID'))
            ->update(['URGENSI_LATAR_BELAKANG'      => Input::get('URGENSI_LATAR_BELAKANG'),
                      'URGENSI_DESKRIPSI'           => Input::get('URGENSI_DESKRIPSI'),
                      'URGENSI_LOKASI'              => Input::get('URGENSI_LOKASI'),
                      'URGENSI_TUJUAN'              => Input::get('URGENSI_TUJUAN'),
                      'URGENSI_PENERIMA_1'          => Input::get('URGENSI_PENERIMA'),
                      'URGENSI_PENERIMA_2'          => Input::get('URGENSI_PENERIMA_2'),
                      'URGENSI_PELAKSANAAN'         => Input::get('URGENSI_PELAKSANAAN')]);
        return 'Berhasil!';
    }

    public function getUrgensi($tahun,$status,$id){
        return Response::JSON(Urgensi::where('BL_ID',$id)->first());
    }

    public function getringkasanrekening($tahun,$status,$id){
        $data   = RincianPerubahan::where('BL_ID',$id)
                        ->groupBy('REKENING_ID')
                        ->select('REKENING_ID')
                        ->selectRaw('SUM("RINCIAN_TOTAL") as total')
                        ->get();
        $data1  = RincianPerubahan::where('BL_ID',$id)->selectRaw('DISTINCT("REKENING_ID")')->get()->toArray();
        $data_  = Rincian::where('BL_ID',$id)
                        ->whereNotIn('REKENING_ID',$data1)
                        ->groupBy('REKENING_ID')
                        ->select('REKENING_ID')
                        ->selectRaw('SUM("RINCIAN_TOTAL") as total')
                        ->get();
        $view   = array();
        foreach($data as $data){
            $sebelum    = Rincian::where('BL_ID',$id)->where('REKENING_ID',$data->REKENING_ID)->sum('RINCIAN_TOTAL');
            $realisasi  = Realisasi::where('BL_ID',$id)->where('REKENING_ID',$data->REKENING_ID)->sum('REALISASI_TOTAL');
            array_push($view, array('KODE'      => $data->rekening->REKENING_KODE,
                                    'URAIAN'    => $data->rekening->REKENING_NAMA,
                                    'SEBELUM'   => number_format($sebelum,0,'.',','),
                                    'REALISASI' => number_format($realisasi,0,'.',','),
                                    'SELISIH'   => number_format($data->total - $realisasi,0,'.',','),
                                    'SESUDAH'   => number_format($data->total,0,'.',',')));
        }
        foreach($data_ as $data_){
            $realisasi  = Realisasi::where('BL_ID',$id)->where('REKENING_ID',$data_->REKENING_ID)->sum('REALISASI_TOTAL');            
            array_push($view, array('KODE'      => $data_->rekening->REKENING_KODE,
                                    'URAIAN'    => $data_->rekening->REKENING_NAMA,
                                    'SEBELUM'   => number_format($data_->total,0,'.',','),
                                    'REALISASI' => number_format($realisasi,0,'.',','),
                                    'SELISIH'   => number_format(0 - $realisasi,0,'.',','),                                    
                                    'SESUDAH'   => number_format(0,0,'.',',')));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function usulanpagu($tahun,$status){
        $bl     = BLPerubahan::where('BL_TAHUN',$tahun)->where('BL_PAGU_USULAN','>',0)->get();
        $data   = array('tahun'=>$tahun,'status'=>$status,'bl'=>$bl,'no'=>1);
        return View('budgeting.belanja-langsung.usulan_pagu',$data); 
    }

    public function usulanpaguterima($tahun,$status){
        $id     = Input::get('BL_ID');
        $bl     = BLPerubahan::where('BL_ID',$id)->first();
        BLPerubahan::where('BL_ID',$id)->update(['BL_PAGU'=>$bl->BL_PAGU_USULAN]);
        BLPerubahan::where('BL_ID',$id)->update(['BL_PAGU_USULAN'=>0]);
        return 'Berhasil!';
    }
    public function usulanpagutolak($tahun,$status){
        $id     = Input::get('BL_ID');
        BLPerubahan::where('BL_ID',$id)->update(['BL_PAGU_USULAN'=>0]);
        return 'Berhasil!';
    }
}
