<?php

namespace App\Http\Controllers\Budgeting\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Satuan;
class satuanController extends Controller
{
    public function getData($tahun, $status){
        $data =  Satuan::all();
        $view = "";
        foreach ($data as $data) {
            $view .= "<option value='".$data->SATUAN_ID."'>".$data->SATUAN_NAMA."</option>";
        }
        return $view;
    }
}
