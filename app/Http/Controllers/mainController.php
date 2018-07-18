<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon;
use App\Model\User;
use App\Model\TahunAnggaran;
use App\Model\UserBudget;
use Redirect;
use Auth;
use View;
class mainController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

	public function index(){
		$tahun 	= Carbon\Carbon::now()->format('Y')+1;
		if(Auth::user()->app == 3) {
			if(substr(Auth::user()->mod, 4,1) == 1 || substr(Auth::user()->mod, 5,1) == 1 || substr(Auth::user()->mod, 6,1) == 1){
				return Redirect('harga/'.$tahun);		
			}else{
				// return View('maintenence');
				return Redirect('main/'.$tahun.'/murni');		
			}
		}
		else if(Auth::user()->app == 4) return Redirect('harga/'.$tahun);
	}

	public function chpass(){
		User::where('id',Auth::user()->id)->update(['password'=>bcrypt(Input::get('password'))]);
		return 'Berhasil!';
	}

	public function chprofile(){
		$id 	= UserBudget::where('USER_ID',Auth::user()->id)->value('SKPD_ID');	
		SKPD::where('SKPD_ID',$id)->update(['SKPD_ALAMAT'=>Input::get('ALAMAT'),'SKPD_JABATAN'=>Input::get('PANGKAT')]);
		return 'Berhasil';
	}

	public function aktifuser($id){
		User::where('id',$id)->update(['login'=>1]);
	}

	public function offuser($id){
		User::where('id',$id)->update(['login'=>0]);
	}

	public function keluar($id){
		// return 'haha';
		User::where('id',$id)->update(['login'=>0]);		
		User::where('id',$id)->update(['login'=>0]);
		$this->offuser($id);		
		// return print_r(Auth::user()->id);
		return Redirect('/logout');
	}

	public function getTABudgeting($tahun,$status){
		$data  		= TahunAnggaran::all()->orderBy('TAHUN');
		$view 		= '';
$view 	.= '<option value="'.$tahun.'/'.$status.'" id="o'.$tahun.$status.'" selected>'.$tahun.'-'.$status.'</option>';
		
		foreach($data as $data){
				
			if($data->TAHUN == '2018' && $data->STATUS == 'pergeseran'){
				if($tahun == '2018' && $status == 'pergeseran'){
				} else {
					$view 	.= '<option value="'.$data->TAHUN.'/'.$data->STATUS.'" id="o'.$data->TAHUN.$data->STATUS.'">'.$data->TAHUN.'-'.$data->STATUS.'</option>';
				}
			}
			if($data->TAHUN == '2018' && $data->STATUS == 'perubahan'){
				if($tahun == '2018' && $status == 'perubahan'){
				} else {
					$view 	.= '<option value="'.$data->TAHUN.'/'.$data->STATUS.'" id="o'.$data->TAHUN.$data->STATUS.'">'.$data->TAHUN.'-'.$data->STATUS.'</option>';
				}
			}
			 if($data->TAHUN == '2019'){
				if($tahun == '2019' && $status == 'murni'){
				} else {
					$view 	.= '<option value="'.$data->TAHUN.'/'.$data->STATUS.'" id="o'.$data->TAHUN.$data->STATUS.'">'.$data->TAHUN.'-'.$data->STATUS.'</option>';
				}
            }
		}
		return $view;
	}



}
