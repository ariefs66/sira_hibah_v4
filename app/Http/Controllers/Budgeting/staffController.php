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
class staffController extends Controller
{
	public function index($tahun,$status){
        $skpd   = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        $data   = UserBudget::where('SKPD_ID',$skpd)
                        ->whereHas('user',function($q){
                            $q->where('level',1);
                        })->orderBy('USER_ID')->get();
        $userHarga  = "";
        foreach($data as $d){
            $mod    = substr($d->user->mod, 3,1);
            if($mod == "1"){
               $userHarga = User::where('id',$d->USER_ID)->first();
            }
        }
		return view('budgeting.referensi.staff',['tahun'=>$tahun,'status'=>$status,'userharga'=>$userHarga]);
	}

    public function getData($tahun){
    	$skpd 	= UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        if(Auth::user()->level == 2){
            $data   = UserBudget::where('SKPD_ID',$skpd)
                            ->whereHas('user',function($q){
                                $q->where('level',1);
                            })->orderBy('USER_ID')->get();            
        }elseif(Auth::user()->level == 8){
            $data   = UserBudget::whereHas('user',function($q){
                                $q->where('level',2);
                        })->orderBy('USER_ID')->get();
        }

    	$view 	= array();
    	$i 		= 1;
    	foreach ($data as $data) {
    		$aksi 	= '<div class="action visible pull-right">
    						<a onclick="return ubah(\''.$data->USER_ID.'\')" class="action-edit"><i class="mi-edit"></i></a>
    						<a onclick="return reset(\''.$data->USER_ID.'\')" class="action-edit"><i class="fa fa-retweet"></i></a>
    						<a onclick="return hapus(\''.$data->USER_ID.'\')" class="action-delete"><i class="mi-trash"></i></a>
    					</div>';
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
            						 'USER_NIP'		=>$data->user->email,
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
        $skpd   = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        $data   = UserBudget::where('SKPD_ID',$skpd)
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
            $id     = User::where('email',Input::get('NIP'))->value('id');
            $skpd   = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');

            $ub     = new UserBudget;
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
        $skpd   = UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');
        $data   = UserBudget::where('SKPD_ID',$skpd)
                        ->whereHas('user',function($q){
                            $q->where('level',1);
                        })->orderBy('USER_ID')->get();
        foreach($data as $d){
            $mod    = substr($d->user->mod, 3,1);
            if($mod == 1){
                $modAwal    = substr($d->user->mod, 0,3);
                $modAkhir   = substr($d->user->mod, 4,7);
                $modFix     = $modAwal."0".$modAkhir;
                User::where('id',$d->USER_ID)->update(['mod'=>$modFix]);
            }
        }
        $user   = User::where('id',Input::get('ID'))->first();
        $modAwal    = substr($d->user->mod, 0,3);
        $modAkhir   = substr($d->user->mod, 4,7);
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
}
