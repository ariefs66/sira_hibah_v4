@extends('asosiasi.layout')

@section('content')
<div id="content" class="app-content" role="main">
  <div class="hbox hbox-auto-xs hbox-auto-sm ng-scope">
    <div class="col">
      <div class="app-content-body ">
        <div class="bg-light lter">    
          <ul class="breadcrumb bg-white m-b-none">
            <li>
              <a href="#" class="btn no-shadow" ui-toggle-class="app-aside-folded" target=".app">
                <i class="icon-bdg_expand1 text"></i>
                <i class="icon-bdg_expand2 text-active"></i>
              </a>   
            </li>
            <li>
              <a href= "{{ url('/') }}/asosiasi/{{$tahun}}">Dashboard</a>
            </li>
            <li><i class="fa fa-angle-right"></i>Visi Misi</li>
            <li class="active"><i class="fa fa-angle-right"></i>Tujuan</li>
          </ul>
        </div>
        
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Tujuan</h5>
                </div>               
                <div class="tab-content tab-content-alt-1 bg-white">
                  <div role="tabpanel" class="active tab-pane" id="tab-1">
                    <div id="table-ringkasan">
                      <table class="table">
                        <thead>
                          <tr>
                            <th width="1%">No</th>
                            <th>Uraian Tujuan</th>
                            <th width="20%" class="text-right">2017</th>
                            <th width="20%" class="text-right">Anggaran Divalidasi (2018)</th>
                            <th width="20%" class="text-right">Anggaran Ajuan (2018)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><b>1</b></td>
                            <td><b>PENDAPATAN</b></td>
                            <td class="text-right"><b>Rp. 6.345.835.314.502</b></td>
                            <td class="text-right"><b>Rp. {{ number_format(0,0,',','.') }}</b></td>
                            <td class="text-right"><b>Rp. {{ number_format(0,0,',','.') }}</b></td>
                          </tr>
                          <tr>  
                            <td><b>1.1</b></td>
                            <td><b>PENDAPATAN ASLI DAERAH</b></td>
                            <td class="text-right"><b>Rp. 3.066.682.007.234</b></td>
                            <td class="text-right"><b>Rp. {{ number_format(0,0,',','.') }}</b></td>
                            <td class="text-right"><b>Rp. {{ number_format(0,0,',','.') }}</b></td>
                          </tr>
                                                                                                           
                          <tr>
                            <td><b>3.1</b></td>
                            <td><b>PENERIMAAN PEMBIAYAAN DAERAH</b></td>
                            <td class="text-right"><b>Rp. 455.147.609.954</b></td>
                            <td class="text-right"><b>Rp. {{ number_format(0,0,',','.') }}</b></td>
                            <td class="text-right"><b>Rp. {{ number_format(0,0,',','.') }}</b></td>
                          </tr>                                                                                                
                          <tr>
                            <td>3.1.1.</td>
                            <td>Sisa Lebih Perhitungan Anggaran Tahun Anggaran Sebelumnya </td>
                            <td class="text-right">Rp. 455.147.609.954</td>
                            <td class="text-right">Rp. {{ number_format(0,0,',','.') }}</td>
                            <td class="text-right">Rp. {{ number_format(0,0,',','.') }}</td>
                          </tr>
                          <tr>
                            <td colspan="2" class="text-right"><b>JUMLAH PENERIMAAN PEMBIAYAAN </b></td>
                            <td class="text-right"><b>Rp. 455,147,609,954</b></td>
                            <td class="text-right"><b>Rp. {{ number_format(0,0,',','.') }}</b></td>
                            <td class="text-right"><b>Rp. {{ number_format(0,0,',','.') }}</b></td>
                          </tr>                              
                          <tr>
                            <td><b>3.2</b></td>
                            <td><b>PENGELUARAN PEMBIAYAAN DAERAH</b></td>
                            <td class="text-right"><b>Rp. 103.000.000.000</b></td>
                            <td class="text-right"><b>Rp. {{ number_format(0,0,',','.') }}</b></td>
                            <td class="text-right"><b>Rp. {{ number_format(0,0,',','.') }}</b></td>
                          </tr>                                                                     
                         
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
</div>
@endsection

@section('plugin')
@endsection


