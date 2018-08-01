<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use View;
use Response;
use App\Model\UserBudget;
use App\Model\User;
use App\Model\SKPD;
use App\Model\Kunci;
class staffController extends Controller
{
	public function index($tahun,$status){
        $skpd   = $this->getSKPD($tahun);
        $data   = UserBudget::where('SKPD_ID',$skpd)
                        ->whereHas('user',function($q){
                            $q->where('level',1);
                        })->orderBy('USER_ID')->get();
        $userHarga  = "";
        $userMonev  = "";
        foreach($data as $d){
            $mod        = substr($d->user->mod, 3,1);
            $mod_monev  = substr($d->user->mod, 9,1);
            if($mod == "1"){
               $userHarga = User::where('id',$d->USER_ID)->first();
            }
            if($mod_monev == "1"){
               $userMonev = User::where('id',$d->USER_ID)->first();
            }
        }
		return view('budgeting.referensi.staff',['tahun'=>$tahun,'status'=>$status,'userharga'=>$userHarga,'usermonev'=>$userMonev,'skpd'=>$skpd ]);
	}

    public function getData($tahun){
    	$skpd 	= $this->getSKPD($tahun);
        if(Auth::user()->level == 2){
            $data   = UserBudget::where('SKPD_ID',$skpd)
                            ->where('TAHUN',$tahun)
                            ->whereHas('user',function($q){
                                $q->where('level',1);
                                //$q->where('mod','00000000000');
                            })->orderBy('USER_ID')->get();            
        }elseif(Auth::user()->level == 8){
            $data   = UserBudget::whereHas('user',function($q){
                                $q->where('level',2);
                        })->where('TAHUN',$tahun)->orderBy('USER_ID')->get();
        }

    	$view 	= array();
    	$i 		= 1;
    	foreach ($data as $data) {
    		$aksi 	= '<div class="action visible pull-right">
    						<a onclick="return ubah(\''.$data->USER_ID.'\')" class="action-edit" title="edit skpd"><i class="mi-edit"></i></a>
    						<a onclick="return reset(\''.$data->USER_ID.'\')" class="action-edit" title="riset password"><i class="fa fa-retweet"></i></a>
    						<a onclick="return hapus(\''.$data->USER_ID.'\')" class="action-delete" title="delete skpd"><i class="mi-trash"></i></a>
    					</div>';

            if(Auth::user()->level == 2 || Auth::user()->level == 8){
                if($data->user->active==1){
                    $aksi    .= '<br><label title="Status user aktif" class="i-switch bg-success m-l-n-xxl m-r-xl m-t-sm pull-right"><input type="checkbox" checked onchange="return nonAktivasiUser(\''.$data->USER_ID.'\')"><i></i></label>';
                }else{
                    $aksi    .= '<br><label title="Status user non aktif" class="i-switch bg-danger m-l-n-xxl m-r-xl m-t-sm pull-right"><input type="checkbox" onchange="return aktivasiUser(\''.$data->USER_ID.'\')"><i></i></label>';
                }
            } 

        //<a onclick="return mod(\''.$data->USER_ID.'\')" class="action-edit"><i class="icon-bdg_setting3"></i></a>                      
    		$tapd 	= substr($data->user->mod, 0,1);
    		$ppkd 	= substr($data->user->mod, 1,1);
    		$spva 	= substr($data->user->mod, 2,1);
    		$spvb 	= substr($data->user->mod, 3,1);
    		$staffharga 	= substr($data->user->mod, 4,1);
    		$adminharga 	= substr($data->user->mod, 5,1);
    		$kabid 		= substr($data->user->mod, 6,1);
    		$kasubid 	= substr($data->user->mod, 7,1);
    		$adminsisdur 	= substr($data->user->mod, 8,1);
    		$adminsimda 	= substr($data->user->mod, 9,1);
            array_push($view, array( 'USER_ID'		=>$data->USER_ID,
                                     'USER_NIP'     =>$data->user->email,
            						 'TAHUN'		=>$data->skpd->SKPD_TAHUN,
            						 'NO'			=>$i,
            						 'tapd'			=>$tapd,
            						 'ppkd'			=>$ppkd,
            						 'spva'			=>$spva,
            						 'spvb'			=>$spvb,
            						 'staffharga'	=>$staffharga,
            						 'adminharga'	=>$adminharga,
            						 'kabid'		=>$kabid,
            						 'kasubid'		=>$kasubid,
            						 'adminsisdur'	=>$adminsisdur,
            						 'adminsimda'	=>$adminsimda,
                                     'aksi'         =>$aksi,
            						 'skpd'			=>$data->skpd->SKPD_NAMA,
                                     'USER_NAMA'    =>$data->user->name));
            $i++;
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function getStaff($tahun,$status){
        $skpd   = $this->getSKPD($tahun);
        $data   = UserBudget::where('SKPD_ID',$skpd)
                        ->where('TAHUN',$tahun)
                        ->whereHas('user',function($q){
                            $q->where('level',1);
                        })->orderBy('USER_ID')->get();
        $view   = "";
        $i      = 1;
        foreach ($data as $data) {
            $view .= "<option value='".$data->user->id."'>".$data->user->email." - ".$data->user->name."</option>";
        }
        return $view;
    }

    public function submitAdd($tahun,$status){
        $cek    = User::where('email',Input::get('NIP'))->value('id');
        if(empty($cek)){
            $user   = new User;
            $user->email        = Input::get('NIP');
            $user->name         = Input::get('NAMA');
            $user->password     = '$2y$10$oDOpQp8JIQkStQxRKP/uPuLOg8qYYBRWyblH95odj0.ngqlF93ysS';
            $user->login        = 0;
            $user->active       = 0;
            $user->app          = 3;
            $user->level        = 1;
            $user->mod          = '00000000000';
            $user->save();

            $id    = User::where('email',Input::get('NIP'))->value('id');

            $skpd   = $this->getSKPD($tahun);

            $get_id      = User::max('id');

            $ub     = new UserBudget;
            $ub->id             = $get_id;
            $ub->SKPD_ID        = $skpd;
            $ub->USER_ID        = $id;
            $ub->TAHUN          = $tahun;
            $ub->save();

            return 'Berhasil ditambahkan!';

        }else{
            return 'NIP telah terdaftar!';
        }
    }

    public function submitEdit(){
        User::where('id',Input::get('ID'))->update(['email'=>Input::get('NIP'),'name'=>Input::get('NAMA')]);
        return 'Berhasil diubah!';
    }

    public function submitEharga($tahun,$status){
        $skpd   = $this->getSKPD($tahun);
        $data   = UserBudget::where('SKPD_ID',$skpd)
                        ->where('TAHUN',$tahun)
                        ->whereHas('user',function($q){
                            $q->where('level',1);
                        })->orderBy('USER_ID')->get();
        foreach($data as $d){
            $mod    = substr($d->user->mod, 3,1);
            if($mod == '1'){
                $modAwal    = substr($d->user->mod, 0,3);
                $modAkhir   = substr($d->user->mod, 4,7);
                $modFix     = $modAwal."0".$modAkhir;
                User::where('id',$d->USER_ID)->update(['mod'=>$modFix]);
            }
        }
        $user   = User::where('id',Input::get('ID'))->first();
        $modAwal    = substr($user->mod, 0,3);
        $modAkhir   = substr($user->mod, 4,7);
        $modFix     = $modAwal."1".$modAkhir;
        User::where('id',Input::get('ID'))->update(['mod'=>$modFix]);
        return 'Set Berhasil!';
    }

    public function submitEmonev($tahun,$status){
        $skpd   = $this->getSKPD($tahun);
        $data   = UserBudget::where('SKPD_ID',$skpd)
                        ->where('TAHUN',$tahun)
                        ->whereHas('user',function($q){
                            $q->where('level',1);
                        })->orderBy('USER_ID')->get();
        foreach($data as $d){
            $mod    = substr($d->user->mod, 9,1);
            if($mod == '1'){
                $modAwal    = substr($d->user->mod, 0,9);
                $modAkhir   = substr($d->user->mod, 10,1);
                $modFix     = $modAwal."0".$modAkhir;
                User::where('id',$d->USER_ID)->update(['mod'=>$modFix]);
            }
        }
        $user   = User::where('id',Input::get('ID'))->first();
        $modAwal    = substr($user->mod, 0,9);
        $modAkhir   = substr($user->mod, 10,1);
        $modFix     = $modAwal."1".$modAkhir;
        User::where('id',Input::get('ID'))->update(['mod'=>$modFix]);
        return 'Set Berhasil!';
    }

    public function hapus(){
        User::where('id',Input::get('id'))->delete();
        return 'Berhasil!';
    }
    public function reset(){
        User::where('id',Input::get('id'))->update(['password'=>'$2y$10$oDOpQp8JIQkStQxRKP/uPuLOg8qYYBRWyblH95odj0.ngqlF93ysS']);
        return 'Berhasil Reset Password Menjadi "<b>bandungjuara</b>"';
    }

    public function getStaffDetail($tahun,$status,$id){
        return Response::JSON(User::where('id',$id)->first());
    }

    public function penyelia($tahun,$status){
        $skpd   = SKPD::where('SKPD_TAHUN',$tahun)->get();
        return view('budgeting.referensi.penyelia',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd]);
    }

    public function penyeliaGetData($tahun){
        if(Auth::user()->level==8){
            $data   = User::where('users.mod','01000000000')
                              ->get();
        }else                      
            if(Auth::user()->level==9){
            $data   = User::where('users.mod','10001000000')
                              ->get();
        } 

        $view   = array();
        $i      = 1;
        foreach ($data as $data) {
            $aksi   = '<div class="action visible pull-right">
                            <a onclick="return ubah(\''.$data->id.'\')" class="action-edit"><i class="mi-edit"></i></a>
                            <a onclick="return reset(\''.$data->id.'\')" class="action-edit"><i class="fa fa-retweet"></i></a>
                            <a onclick="return hapus(\''.$data->id.'\')" class="action-delete"><i class="mi-trash"></i></a>

                        </div>';

            if(Auth::user()->level == 8){
                if($data->active==1){
                    $aksi    .= '<br><label title="Status user aktif" class="i-switch bg-success m-l-n-xxl m-r-xl m-t-sm pull-right"><input type="checkbox" checked onchange="return nonAktivasiUser(\''.$data->id.'\')"><i></i></label>';
                }else{
                    $aksi    .= '<br><label title="Status user non aktif" class="i-switch bg-danger m-l-n-xxl m-r-xl m-t-sm pull-right"><input type="checkbox" onchange="return aktivasiUser(\''.$data->id.'\')"><i></i></label>';
                }
            }             


            array_push($view, array( 'USER_ID'      =>$data->id,
                                     'USER_NIP'     =>$data->email,
                                     'NO'           =>$i,
                                     'aksi'         =>$aksi,
                                     'USER_NAMA'    =>$data->name));
            $i++;
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }


    public function penyeliaGetDataSkpd($tahun, $status, $id){
        $data   = UserBudget::join('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','USER_BUDGET.SKPD_ID')
                          ->where('USER_ID',$id)
                          ->where('TAHUN',$tahun)  
                          ->get();  
         $view   = array();                             
        foreach ($data as $d) {
            
            $tahun = '<input type="text" class="form-control col-lg-4" value="'.$d->TAHUN.'">';
            $skpd  = '<input type="text" class="form-control col-lg-12" value="'.$d->SKPD_NAMA.'">';  

            array_push($view, array( 'tahun'  =>$tahun,
                                     'skpd'   =>$skpd,     
                                     ));     
        }        
        $out = array("header"=>$view);      
        return $out;                             
    }


    public function submitAddPenyelia($tahun,$status){
        $cek    = User::where('email',Input::get('NIP'))->value('id');
        if(empty($cek)){
            $user   = new User;
            $get_id  = User::max('id');
            $user->id           = $get_id+1;
            $user->email        = Input::get('NIP');
            $user->name         = Input::get('NAMA');
            $user->password     = '$2y$10$oDOpQp8JIQkStQxRKP/uPuLOg8qYYBRWyblH95odj0.ngqlF93ysS';
            $user->login        = 0;
            $user->active       = 0;
            $user->app          = 3;
            $user->level        = 1;
            if(Auth::user()->level==8){
                $user->mod          = '01000000000';
            }else if(Auth::user()->level==9){
                $user->mod          = '10001000000';
            }
            $user->save();

            $id     = User::where('email',Input::get('NIP'))->value('id');

            $skpd       = Input::get('SKPD');
            foreach($skpd as $s){
                $ub     = new UserBudget;
                $_id       = UserBudget::max('id');
                $ub->id             = $_id+1;
                $ub->SKPD_ID        = $s;
                $ub->USER_ID        = $id;
                $ub->TAHUN          = $tahun;
                $ub->save();
            }

            return 'Berhasil ditambahkan!';

        }else{
            return 'NIP telah terdaftar!';
        }
    }

    public function getPenyeliaDetail($tahun,$status,$id){
        $data = User::where('users.id',$id)->first();
        $skpd = UserBudget::join('REFERENSI.REF_SKPD', 'REF_SKPD.SKPD_ID', '=', 'USER_BUDGET.SKPD_ID')
                        ->where('USER_BUDGET.USER_ID',$id)
                        ->where('USER_BUDGET.TAHUN',$tahun)
                        ->get();
        $view           = "";
        foreach($skpd as $s){
            $view .= "<option value='".$s->SKPD_ID."' selected>".$s->SKPD_NAMA."</option>";
        }
        return ['data'=>$data,'skpd'=>$view];
    }

    public function submitEditPenyelia(){
        User::where('id',Input::get('ID'))->update(['email'=>Input::get('NIP'),'name'=>Input::get('NAMA')]);
        return 'Berhasil diubah!';
    }

     public function hapusPenyelia($tahun){
        $user_id = Input::get('id');
        foreach ($user_id as $user_id) {
            UserBudget::where('id',Input::get('id'))->where('TAHUN',$tahun)->delete();
        }
       // User::where('id',Input::get('id'))->delete();
        return 'Berhasil!';
    }

    public function aktivasiUser($tahun){
        $user_id = UserBudget::where('USER_ID',Input::get('id'))->where('TAHUN',$tahun)->pluck('SKPD_ID');
        $user_id = UserBudget::where('SKPD_ID',$user_id)->pluck('USER_ID');
        foreach ($user_id as $id) {
            User::where('id',$id)->update(['active'=>1]);
        }
        return 'User telah di aktifkan';
    }

    public function nonAktivasiUser($tahun){
        $user_id = UserBudget::where('USER_ID',Input::get('id'))->where('TAHUN',$tahun)->pluck('SKPD_ID');
        $user_id = UserBudget::where('SKPD_ID',$user_id)->pluck('USER_ID');
        foreach ($user_id as $id) {
            User::where('id',$id)->update(['active'=>0]);
        }
        return 'User di non aktifkan';
    }

    public function aktivasiUserAll($tahun,$status){
        User::where('active','!=',null)->whereNotIn('email', ['TAPD','pansus9dprd'])->update(['active'=>1]);
        Kunci::where('BL_ID','!=',null)->update(['KUNCI_GIAT'=>1]);
        return 'Semua user telah di aktifkan!';
    }
    public function nonAktivasiUserAll($tahun,$status){
        User::where('active','!=',null)->whereNotIn('email', ['TAPD','pansus9dprd'])->update(['active'=>0]);
        Kunci::where('BL_ID','!=',null)->update(['KUNCI_GIAT'=>0]);

        return 'Semua user di non aktifkan!';
    }
}
