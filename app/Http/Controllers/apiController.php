<?php

namespace App\Http\Controllers;

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
use App\Model\Kegiatan;
use App\Model\Subunit;
use Illuminate\Support\Facades\Input;
class apiController extends Controller
{
    public function api($tahun,$status){
        $data   = Usulan::where('USULAN_STATUS',1)->select('USULAN_ID')->get();
        return Response::JSON($data);
    }
}
