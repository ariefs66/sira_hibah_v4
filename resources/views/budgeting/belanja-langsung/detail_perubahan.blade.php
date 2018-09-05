@extends('budgeting.layout')

@section('content')
<div id="content" class="app-content" role="main">
  <div class="hbox hbox-auto-xs hbox-auto-sm ng-scope">
    <div class="col">
      <div class="app-content-body ">

        <div class="bg-light lter">    
          <ul class="breadcrumb bg-white m-b-none">
            <li><a href="#" class="btn no-shadow" ui-toggle-class="app-aside-folded" target=".app" id="app">
              <i class="icon-bdg_expand1 text"></i>
              <i class="icon-bdg_expand2 text-active"></i>
            </a>   
          </li>
          <li><a href= "{{ url('/') }}/main">Dashboard</a></li>
          <li><i class="fa fa-angle-right"></i>Belanja</li>                               
          <li><i class="fa fa-angle-right"></i>Belanja Langsung</li>                                
          <li class="active"><i class="fa fa-angle-right"></i>{{ $bl->kegiatan->KEGIATAN_NAMA }}</li>                                
        </ul>
      </div>

      <div class="wrapper-lg">
        <div class="row">
          <div class="col-md-12">
            <div class="panel bg-white">
              <div class="panel-heading wrapper-lg">
                <h5 class="inline font-semibold text-orange m-n ">Belanja Langsung : {{ $bl->kegiatan->KEGIATAN_NAMA }}</h5>
                <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rka/{{ $bl->BL_ID }}" class="btn btn-success pull-right m-t-n-sm" target="_blank"><i class="fa fa-print"></i> Cetak RKA</a>
                <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/dpa/skpd221/{{$bl->subunit->SKPD_ID}}/{{$bl->BL_ID}}" class="btn btn-info pull-right m-t-n-sm" target="_blank"><i class="fa fa-print"></i> Cetak DPA</a>
              </div>
               <!-- <div class="dropdown dropdown-blend pull-right m-t-n-sm">
                  <button class="btn btn-success dropdown-toggle " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Cetak RKA <i class="fa fa-chevron-down"></i>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">

                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/rka/skpd/{{ $bl->subunit->SKPD_ID }}" target="_blank">RKA-SKPD </a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/rka/skpd1/{{ $bl->subunit->SKPD_ID }}" target="_blank">RKA-SKPD 1</a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/rka/skpd21/{{ $bl->subunit->SKPD_ID }}" target="_blank">RKA-SKPD 2.1</a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/rka/skpd22/{{ $bl->subunit->SKPD_ID }}" target="_blank">RKA-SKPD 2.2</a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rka/{{ $bl->BL_ID }}" target="_blank">RKA-SKPD 2.2.1</a></li>
                   
                   @if(Auth::user()->level == 8 || Auth::user()->level == 9)
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/rka/skpd31/{{ $bl->subunit->SKPD_ID }}" target="_blank">RKA-SKPD 3.1</a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/rka/skpd32/{{ $bl->subunit->SKPD_ID }}" target="_blank">RKA-SKPD 3.2</a></li>
                    @endif
                  </ul>
                </div> -->
                <!-- <div class="dropdown dropdown-blend pull-right m-t-n-sm">
                  <button class="btn btn-info dropdown-toggle " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Cetak DPA <i class="fa fa-chevron-down"></i>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/dpa/skpd/{{ $bl->subunit->SKPD_ID }}" target="_blank">DPA-SKPD </a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/dpa/skpd1/{{ $bl->subunit->SKPD_ID }}" target="_blank">DPA-SKPD 1</a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/dpa/skpd21/{{ $bl->subunit->SKPD_ID }}" target="_blank">DPA-SKPD 2.1</a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/dpa/skpd22/{{ $bl->subunit->SKPD_ID }}" target="_blank">DPA-SKPD 2.2</a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/dpa/skpd221/{{ $bl->subunit->SKPD_ID}}/{{ $bl->BL_ID}}" target="_blank">DPA-SKPD 2.2.1</a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/dpa/skpd31/{{ $bl->subunit->SKPD_ID }}" target="_blank">DPA-SKPD 3.1</a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/dpa/skpd32/{{ $bl->subunit->SKPD_ID }}" target="_blank">DPA-SKPD 3.2</a></li>
                  </ul>
                </div> -->
              <div class="tab-content tab-content-alt-1 bg-white">
                <div class="bg-white wrapper-lg">
                  <div class="input-wrapper">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label class="col-sm-2 ">Urusan</label>
                        <label class="col-sm-10 font-semibold">: {{ $bl->kegiatan->program->urusan->URUSAN_KODE }} - {{ $bl->kegiatan->program->urusan->URUSAN_NAMA }}</label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Perangkat Daerah</label>
                        <label class="col-sm-9 font-semibold">: {{ $bl->subunit->skpd->SKPD_KODE }} - {{ $bl->subunit->skpd->SKPD_NAMA }}</label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Sub Perangkat Daerah</label>
                        <label class="col-sm-9 font-semibold">: {{ $bl->subunit->skpd->SKPD_KODE }}.{{ $bl->subunit->SUB_KODE }} - {{ $bl->subunit->SUB_NAMA }}</label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Program</label>
                        <label class="col-sm-9 font-semibold">: {{ $bl->kegiatan->program->urusan->URUSAN_KODE }}.{{ $bl->subunit->skpd->SKPD_KODE }}.{{ $bl->subunit->SUB_KODE }}.{{ $bl->kegiatan->program->PROGRAM_KODE }} - {{ $bl->kegiatan->program->PROGRAM_NAMA }}</label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Kegiatan</label>
                        <label class="col-sm-9 font-semibold">: {{ $bl->kegiatan->program->urusan->URUSAN_KODE }}.{{ $bl->subunit->skpd->SKPD_KODE }}.{{ $bl->subunit->SUB_KODE }}.{{ $bl->kegiatan->program->PROGRAM_KODE }}.{{ $bl->kegiatan->KEGIATAN_KODE }} - {{ $bl->kegiatan->KEGIATAN_NAMA }}</label>
                      </div>
                      <hr class="m-t-xl">
                      <div class="form-group">
                        <h5 class="text-orange">Detail Kegiatan</h5>
                        <label class="col-sm-2">Jenis Kegiatan</label>
                        <label class="col-sm-4 font-semibold">: {{ $bl->jenis->JENIS_KEGIATAN_NAMA }}</label>
                        <label class="col-sm-2">Sumber Dana</label>
                        <label class="col-sm-4 font-semibold">: {{ $bl->sumber->DANA_NAMA }}</label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Kategori Pagu</label>
                        <label class="col-sm-4 font-semibold">: {{ $bl->pagu->PAGU_NAMA }}</label>
                        <label class="col-sm-2">Waktu Kegiatan</label>
                        <label class="col-sm-4 font-semibold">: {{ $bl->BL_AWAL }} s.d {{ $bl->BL_AKHIR }}</label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Sasaran Kegiatan</label>
                        <label class="col-sm-4 font-semibold">: {{ $bl->sasaran->SASARAN_NAMA }}</label>
                        <label class="col-sm-2">Tagging Kegiatan</label>
                        <label class="col-sm-4 font-semibold">: 
                        @foreach($tag as $t)
                          #{{ $t }}
                        @endforeach
                        </label>
                      </div>                                  
                      <div class="form-group">
                        <label class="col-sm-2">Lokasi Kegiatan</label>
                        <label class="col-sm-10 font-semibold">: {{ $bl->lokasi->LOKASI_NAMA }}</label>
                      </div>                                  
                      <hr class="m-t-xl">
                      @if(substr(Auth::user()->mod,7,1) == 1 or substr(Auth::user()->mod,7,1) == 3 or substr(Auth::user()->mod,7,1) == 5)
                      <div class="form-group">
                        <h5 class="text-orange">Indikator Kegiatan</h5>
                        <table class="table">
                          <thead>
                            <tr>
                              <th width="20%">Indikator</th>
                              <th width="40%">Tolak Ukur</th>
                              <th width="15%">Target</th>
                              <th width="5%">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($outcome)
                            @foreach($outcome as $outcome)
                            <tr>
                              <td>Capaian Program / Sasaran</td>
                              <td>{{ $outcome->OUTCOME_TOLAK_UKUR }}</td>
                              <td>{{ $outcome->OUTCOME_TARGET }} {{ $outcome->satuan->SATUAN_NAMA }}</td>
                              <td><span class="text-success"><i class="fa fa-check"></i></span>&nbsp;<a title="Masuk Keterangan" onclick="return inputRiview('{{ $outcome->OUTCOME_ID }}','outcome');" class="action-edit"><i class="fa fa-bookmark-o"></i></a>&nbsp;<a onclick="" title="Daftar Komponen" class="action-edit"><i class="mi-eye"></i></a></td>
                            </tr>
                            @endforeach
                            @endif
                            <tr>
                              <td>Masukan / Input</td>
                              <td>Dana Yang Dibutuhan</td>
                              <td id="masukan">Rp. {{ number_format($rinciantotal,0,'.',',') }}</td>
                              <td id="masukan"></td>
                            </tr>
                            @if($output)
                            @foreach($output as $output)
                            <tr>
                              <td>Keluaran / Output</td>
                              <td>{{ $output->OUTPUT_TOLAK_UKUR }}</td>
                              <td>{{ $output->OUTPUT_TARGET }} {{ $output->satuan->SATUAN_NAMA }}</td>
                              <td><span class="text-success"><i class="fa fa-check"></i></span>&nbsp;<a title="Masuk Keterangan" onclick="return inputRiview('{{ $output->OUTPUT_ID }}','output');" class="action-edit" class="action-edit"><i class="fa fa-bookmark-o"></i></a>&nbsp;<a onclick="" title="Daftar Komponen" class="action-edit"><i class="mi-eye"></i></a></td>
                            </tr>
                            @endforeach
                            @endif
                            @if($impact)                            
                            @foreach($impact as $impact)
                            <tr>
                              <td>Hasil / Outcome</td>
                              <td>{{ $impact->IMPACT_TOLAK_UKUR }}</td>
                              <td>{{ $impact->IMPACT_TARGET }} {{ $impact->satuan->SATUAN_NAMA }}</td>
                              <td><span class="text-success"><i class="fa fa-check"></i></span>&nbsp;<a title="Masuk Keterangan" onclick="return inputRiview('{{ $impact->IMPACT_ID }}','impact');" class="action-edit" class="action-edit"><i class="fa fa-bookmark-o"></i></a>&nbsp;<a onclick="" title="Daftar Komponen" class="action-edit"><i class="mi-eye"></i></a></td>
                            </tr>
                            @endforeach
                            @endif                            
                          </tbody>
                        </table>
                      </div>
                      @else
                      <div class="form-group">
                        <h5 class="text-orange">Indikator Kegiatan</h5>
                        <table class="table">
                          <thead>
                            <tr>
                              <th width="20%">Indikator</th>
                              <th width="40%">Tolak Ukur</th>
                              <th width="15%">Target</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($outcome)
                            @foreach($outcome as $outcome)
                            <tr>
                              <td>Capaian Program / Sasaran</td>
                              <td>{{ $outcome->OUTCOME_TOLAK_UKUR }}</td>
                              <td>{{ $outcome->OUTCOME_TARGET }} {{ $outcome->satuan->SATUAN_NAMA }}</td>
                            </tr>
                            @endforeach
                            @endif
                            <tr>
                              <td>Masukan / Input</td>
                              <td>Dana Yang Dibutuhan</td>
                              <td id="masukan">Rp. {{ number_format($rinciantotal,0,'.',',') }}</td>
                            </tr>
                            @if($output)
                            @foreach($output as $output)
                            <tr>
                              <td>Keluaran / Output</td>
                              <td>{{ $output->OUTPUT_TOLAK_UKUR }}</td>
                              <td>{{ $output->OUTPUT_TARGET }} {{ $output->satuan->SATUAN_NAMA }}</td>
                            </tr>
                            @endforeach
                            @endif
                            @if($impact)                            
                            @foreach($impact as $impact)
                            <tr>
                              <td>Hasil / Outcome</td>
                              <td>{{ $impact->IMPACT_TOLAK_UKUR }}</td>
                              <td>{{ $impact->IMPACT_TARGET }} {{ $impact->satuan->SATUAN_NAMA }}</td>
                            </tr>
                            @endforeach
                            @endif                            
                          </tbody>
                        </table>
                      </div>
                      @endif
                      
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

@if(substr(Auth::user()->mod,7,1) == 0)
      <div class="wrapper-lg m-t-n-xxl">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Ringkasan Rekening</h5>
                </div>
                <div class="tab-content bg-white">
                  <div role="tabpanel" class="active tab-pane" id="tab-1">
                    <div class="table-responsive dataTables_wrapper">
                     <table ui-jq="dataTable" ui-options="{
                           sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/ringkasanrekening/{{ $BL_ID }}',
                           aoColumns: [
                           { mData: 'KODE' },
                           { mData: 'URAIAN' },
                           { mData: 'SEBELUM',class:'text text-right' },
                           { mData: 'SESUDAH',class:'text text-right' },
                           { mData: 'REALISASI',class:'text text-right' },
                           { mData: 'SELISIH',class:'text text-right' },
                           { mData: 'RIVIEW',class:'text text-right' },
                           { mData: 'CLASS',class:'hide' }],
                           aoColumnDefs: [ {
                              aTargets: [6],
                              fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                                if ( sData == '1' ) {
                                  $(nTd).siblings('td').css( 'background-color', 'yellow' )
                                }
                              }
                            } ],
                          initComplete:function(setting,json){
                              $('#sebelum').html(json.sebelum);
                              $('#sesudah').html(json.sesudah);
                              $('#realisasi').html(json.realisasi);
                              $('#selisih').html(json.selisih);
                          }
                         }" class="table table-jurnal table-striped b-t b-b" id="table-rekening">
                         <thead>
                          <tr>
                            <th style="width: 1%" class="text text-center">Kode<br>(1)</th>
                            <th class="text text-center">Uraian<br>(2)</th>
                            <th class="text text-center">Murni<br>(3)</th>                                      
                            <th class="text text-center">@if($status=='pergeseran') Pergeseran @else Perubahan @endif<br>(4)</th>                                      
                            <th class="text text-center">REALISASI<br>(5)</th>                                      
                            <th class="text text-center">Selisih<br>(6 = 4 - 5)</th>    
                            <th class="text text-center">Aksi<br></th>    
                            <th class="hide">Warna<br>(0)</th>                                  
                          </tr>
                          <tr>
                            <th colspan="8" class="th_search">
                              <i class="icon-bdg_search"></i>
                              <input type="search" class="table-search form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>  
                    <tfoot>
                      <tr>
                        <td class="text text-right" colspan="2"> <b>TOTAL</b></td>
                        <td class="text text-right"><b>Rp. <text id="sebelum"></text></b></td>                                      
                        <td class="text text-right"><b>Rp. <text id="sesudah"></text></b></td>                                      
                        <td class="text text-right"><b>Rp. <text id="realisasi"></text></b></td>                                      
                        <td class="text text-right"><b>Rp. <text id="selisih"></text></b></td>
                      </tr>  
                    </tfoot>                                    
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
@elseif(substr(Auth::user()->mod,7,1) == 2 or substr(Auth::user()->mod,7,1) == 4)
<div class="wrapper-lg m-t-n-xxl">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Ringkasan Paket Pekerjaan Diatas 200 Juta</h5>
                </div>
                <div class="tab-content bg-white">
                  <div role="tabpanel" class="active tab-pane" id="tab-1">
                    <div class="table-responsive dataTables_wrapper">
                     <table ui-jq="dataTable" ui-options="{
                           sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/ringkasanrekening200/{{ $BL_ID }}',
                           aoColumns: [
                           { mData: 'URAIAN' },
                           { mData: 'SEBELUM',class:'text text-right' },
                           { mData: 'SESUDAH',class:'text text-right' },
                           { mData: 'RIVIEW',class:'text text-right' },
                           { mData: 'CLASS',class:'hide' }],
                          initComplete:function(setting,json){
                              $('#sebelum').html(json.sebelum);
                              $('#sesudah').html(json.sesudah);
                              $('#selisih').html(json.selisih);
                          }
                         }" class="table table-jurnal table-striped b-t b-b" id="table-rekening">
                         <thead>
                          <tr>
                            <th class="text text-center">Uraian Paket Pekerjaan / Subrincian <br>(1)</th>
                            <th class="text text-center">Murni<br>(2)</th>                                      
                            <th class="text text-center">@if($status=='pergeseran') Pergeseran @else Perubahan @endif<br>(3)</th>
                            <th class="text text-center">Aksi<br></th>    
                            <th class="hide">Warna<br>(0)</th>                                  
                          </tr>
                          <tr>
                            <th colspan="6" class="th_search">
                              <i class="icon-bdg_search"></i>
                              <input type="search" class="table-search form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>  
                    <tfoot>
                      <tr>
                        <td class="text text-right" colspan="2"> <b>TOTAL</b></td>
                        <td class="text text-right"><b>Rp. <text id="sebelum"></text></b></td>                                      
                        <td class="text text-right"><b>Rp. <text id="sesudah"></text></b></td>                                                                          
                        <td class="text text-right"><b>Rp. <text id="selisih"></text></b></td>
                      </tr>  
                    </tfoot>                                    
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
@endif

      <div class="wrapper-lg" style="margin-top: -75px;">
        <div class="row">
          <div class="col-md-12">
            <div class="panel bg-white">
              <div class="wrapper-lg">
                <!-- <h5 class="inline font-semibold text-orange m-n ">Rincian</h5> -->
                <h5 class="inline font-semibold text-orange m-n">Pagu &nbsp;&nbsp;&nbsp;&nbsp;: <span style="color: #000" id="rincian-pagu">{{ number_format($bl->BL_PAGU,0,'.',',') }}</span><br>Rincian : <span style="color: #000" id="rincian-total">{{ number_format($rinciantotal,0,'.',',') }}<br>
                  Selisih Total: {{ number_format($bl->BL_PAGU-$rinciantotal,0,'.',',') }} <br>
                  <!-- Selisih Belanja Pegawai : {{ number_format($JB_521,0,'.',',') }}<br>
                  Selisih Belanja Barang & Jasa : {{ number_format($JB_522,0,'.',',') }}<br>
                  Selisih Belanja Modal : {{ number_format($JB_523,0,'.',',') }}<br> -->
                </span></h5>

                @if(($bl->kunci->KUNCI_RINCIAN == 0  and $mod == 1 and $thp == 1 and Auth::user()->active == 1) or Auth::user()->level == 8)
                <button class="open-rincian pull-right btn m-t-n-sm btn-success input-xl"><i class="m-r-xs fa fa-plus"></i> Tambah Komponen</button>
                @elseif($thp == 0)
                <h5 class="pull-right font-semibold text-info m-t-n-xs"><i class="fa fa-info-circle"></i> Tahapan masih ditutup!</h5>
                @elseif(Auth::user()->active == 0)
                <h5 class="pull-right font-semibold text-info m-t-n-xs"><i class="fa fa-info-circle"></i> User Tidak Aktif!</h5>
                @endif
                @if(Auth::user()->level == 8)
                <a class="pull-right btn m-t-n-sm btn-warning" href="{{url('/')}}/main/{{$tahun}}/{{$status}}/belanja-langsung/detail/arsip/{{$BL_ID}}" target="_blank"><i class="fa fa-archive"></i></a>

                <!-- <button class="open-rincian pull-right btn m-t-n-sm btn-success input-xl"><i class="m-r-xs fa fa-plus"></i> Tambah Komponen</button> -->
                @endif
                <!-- @if(($BL_ID == 5718 ) 
                    and $mod == 1 
                    and $thp == 1 
                    and $bl->kunci->KUNCI_RINCIAN == 0)
                @else
                <h5 class="pull-right font-semibold text-info m-t-n-xs"><i class="fa fa-info-circle"></i> Rincian Hanya Bisa dirubah / dihapus!</h5>
                @endif -->
                <a class="pull-right btn btn-info m-t-n-sm m-r-sm" href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/download/rekaprincian/{{$bl->BL_ID}}"><i class="m-r-xs fa fa-download"></i> Download</a>
                <div class="col-sm-1 pull-right m-t-n-sm">
                  <select class="form-control selectrincian" id="selectrincian">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                  </select>
                </div>  
              </div>

              <div class="nav-tabs-alt tabs-alt-1 b-t four-row" id="tab-jurnal" >
                <ul class="nav nav-tabs" role="tablist">
                 <li class="{{(($mode=='RKPD') ? 'active' : '')}}">
                  <a data-target="#tab-1" role="tab" data-toggle="tab">RKPD/RKUA<br><span class="text-success" id="rincian-total-1">Rincian : {{ number_format((($mode=="RKPD") ? $rinciantotal : $rkpd),0,'.',',') }}</span></a>
                </li>
                <li class="{{(($mode=='KUA/PPAS') ? 'active' : '')}}">
                  <a data-target="#tab-3" role="tab" data-toggle="tab">KUA/PPAS<br><span class="text-success">Rincian : {{ number_format((($mode=="KUA/PPAS") ? $rinciantotal : $ppas),0,'.',',') }}</span></a>
                </li>
                <li class="{{(($mode=='RAPBD') ? 'active' : '')}}"> 
                  <a data-target="#tab-4" role="tab" data-toggle="tab">RAPBD<br><span class="text-success">Rincian : {{ number_format((($mode=="RAPBD") ? $rinciantotal : $rapbd),0,'.',',') }}</span></a>
                </li>
                <li class="{{(($mode=='APBD') ? 'active' : '')}}">
                  <a data-target="#tab-4" role="tab" data-toggle="tab">APBD<br><span class="text-success">Rincian : {{ number_format((($mode=="APBD") ? $rinciantotal : $apbd),0,'.',',') }}</span></a> 
                </li>
              </ul>
            </div>

            <div class="tab-content tab-content-alt-1 bg-white" id="tab-detail">
            <!-- Tab1 -->
              <div role="tabpanel" class="active tab-pane" id="tab-1">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                 sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincian/{{ $BL_ID }}',
                 aoColumns: [
                 { mData: 'NO',class:'text-center' },
                 { mData: 'REKENING' },
                 { mData: 'KOMPONEN' },
                 { mData: 'SUB' },
                 { mData: 'HARGA_SEBELUM' },
                 { mData: 'PAJAK_SEBELUM' },
                 { mData: 'TOTAL_SEBELUM',class:'text text-right' },
                 { mData: 'HARGA_SESUDAH' },
                 { mData: 'PAJAK_SESUDAH' },
                 { mData: 'TOTAL_SESUDAH',class:'text text-right' }]
               }" class="table table-striped b-t b-b tabel-detail">
               <thead>
                <tr>
                  <th style="width: 1%" rowspan="2">#</th>
                  <th rowspan="2">Rekening</th>
                  <th rowspan="2">Komponen</th>
                  <th rowspan="2">Paket Pekerjaan<br>Keterangan</th>
                  <th colspan="3" class="text text-center">Murni</th>
                  <th colspan="3" class="text text-center">@if($status=='pergeseran') Pergeseran @else Perubahan @endif</th>
                </tr>
                <tr>
                  <th style="width: 10%">Harga / Koefisien</th>
                  <th style="width: 1%">Pjk</th>
                  <th style="width: 5%">Total</th>
                  <th style="width: 10%">Harga / Koefisien</th>
                  <th style="width: 1%">Pjk</th>
                  <th style="width: 5%">Total</th>                  
                </tr>
                <tr>
                  <th colspan="10" class="th_search">
                    <i class="icon-bdg_search"></i>
                    <input type="search" class="cari-detail form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                  </th>
                </tr>
              </thead>
              <tbody>
              </tbody>                        
            </table>
          </div>
          @if(Auth::user()->level == 8)
          <!-- <a class="btn input-xl m-t-md btn-danger pull-right m-r-md" onclick="return hapuscb()"><i class="fa fa-trash m-r-xs"></i>Hapus</a> -->
          @endif
          <div class="tab-content" style="min-height: 75px;">                    
            <div class="form-group">
              <div class="col-md-12">
               {{-- @if(Auth::user()->level == 2)
               @if($bl->BL_VALIDASI == 0)
               <a class="btn input-xl m-t-md btn-success pull-left" type="button" id="btn-validasi"><i class="fa fa-check m-r-xs"></i>Validasi</a>
               @else
               <a class="btn input-xl m-t-md btn-success pull-left disabled" type="button" id="btn-validasi"><i class="fa fa-check m-r-xs"></i>Validasi</a>
               @endif
               @endif
               @if(Auth::user()->level == 5 or Auth::user()->level == 6)
               <button class="btn input-xl m-t-md btn-warning pull-left"><i class="fa fa-send m-r-xs"></i>Rekomendasi</button>
               @endif --}}
             </div>
           </div>
         </div>
       </div>
       <!-- Tab2 -->
       <div role="tabpanel" class="tab-pane" id="tab-2">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                 sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincianrekap/rkpd/{{ $BL_ID }}',
                 aoColumns: [
                 { mData: 'NO',class:'text-center' },
                 { mData: 'REKENING' },
                 { mData: 'KOMPONEN' },
                 { mData: 'SUB' },
                 { mData: 'HARGA' },
                 { mData: 'PAJAK' },
                 { mData: 'TOTAL' }]
               }" class="table table-striped b-t b-b tabel-detail-rkpd">
               <table class="table table-striped b-t b-b tabel-detail-rkpd">
               <thead>
                <tr>
                  <th style="width: 1%">#</th>
                  <th>Rekening</th>
                  <th>Komponen</th>
                  <th>Subtitle</th>
                  <th style="width: 10%">Harga / Koefisien</th>
                  <th style="width: 5%">Pajak</th>
                  <th style="width: 5%">Total</th>
                </tr>
                <tr>
                  <th colspan="7" class="th_search">
                    <i class="icon-bdg_search"></i>
                    <input type="search" class="cari-detail-rkpd form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                  </th>
                </tr>
              </thead>
              <tbody>
              </tbody>                        
            </table>
          </div>
       </div>
       <!-- Tab3 -->
       <div role="tabpanel" class="tab-pane" id="tab-3">  
                <div class="table-responsive dataTables_wrapper">
                <table ui-jq="dataTable" ui-options="{
                 sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincianrekap/ppas/{{ $BL_ID }}',
                 aoColumns: [
                 { mData: 'NO',class:'text-center' },
                 { mData: 'REKENING' },
                 { mData: 'KOMPONEN' },
                 { mData: 'SUB' },
                 { mData: 'HARGA' },
                 { mData: 'PAJAK' },
                 { mData: 'TOTAL' }]
               }" class="table table-striped b-t b-b tabel-detail-ppas">
               <table class="table table-striped b-t b-b tabel-detail-rkpd">               
               <thead>
                <tr>
                  <th style="width: 1%">#</th>
                  <th>Rekening</th>
                  <th>Komponen</th>
                  <th>Subtitle</th>
                  <th style="width: 10%">Harga / Koefisien</th>
                  <th style="width: 5%">Pajak</th>
                  <th style="width: 5%">Total</th>
                </tr>
                <tr>
                  <th colspan="7" class="th_search">
                    <i class="icon-bdg_search"></i>
                    <input type="search" class="cari-detail-ppas form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                  </th>
                </tr>
              </thead>
              <tbody>
              </tbody>                        
            </table>
          </div>
       </div>
       <!-- Tab4 -->
       <div role="tabpanel" class="tab-pane" id="tab-4">  
                <div class="table-responsive dataTables_wrapper">
                <table ui-jq="dataTable" ui-options="{
                 sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincianrekap/rapbd/{{ $BL_ID }}',
                 aoColumns: [
                 { mData: 'NO',class:'text-center' },
                 { mData: 'REKENING' },
                 { mData: 'KOMPONEN' },
                 { mData: 'SUB' },
                 { mData: 'HARGA' },
                 { mData: 'PAJAK' },
                 { mData: 'TOTAL' }]
               }" class="table table-striped b-t b-b tabel-detail-rapbd">
               <table class="table table-striped b-t b-b tabel-detail-rkpd">               
               <thead>
                <tr>
                  <th style="width: 1%">#</th>
                  <th>Rekening</th>
                  <th>Komponen</th>
                  <th>Subtitle</th>
                  <th style="width: 10%">Harga / Koefisien</th>
                  <th style="width: 5%">Pajak</th>
                  <th style="width: 5%">Total</th>
                </tr>
                <tr>
                  <th colspan="7" class="th_search">
                    <i class="icon-bdg_search"></i>
                    <input type="search" class="cari-detail-rapbd form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                  </th>
                </tr>
              </thead>
              <tbody>
              </tbody>                        
            </table>
          </div>
       </div>
       <!-- Tab5 -->
       <div role="tabpanel" class="tab-pane" id="tab-5">  
                <div class="table-responsive dataTables_wrapper">
                 <!-- <table ui-jq="dataTable" ui-options="{
                 sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincianrekap/apbd/{{ $BL_ID }}',
                 aoColumns: [
                 { mData: 'NO',class:'text-center' },
                 { mData: 'REKENING' },
                 { mData: 'KOMPONEN' },
                 { mData: 'SUB' },
                 { mData: 'HARGA' },
                 { mData: 'PAJAK' },
                 { mData: 'TOTAL' }]
               }" class="table table-striped b-t b-b tabel-detail-apbd"> -->
               <table class="table table-striped b-t b-b tabel-detail-rkpd">               
               <thead>
                <tr>
                  <th style="width: 1%">#</th>
                  <th>Rekening</th>
                  <th>Komponen</th>
                  <th>Subtitle</th>
                  <th style="width: 10%">Harga / Koefisien</th>
                  <th style="width: 5%">Pajak</th>
                  <th style="width: 5%">Total</th>
                </tr>
                <tr>
                  <th colspan="7" class="th_search">
                    <i class="icon-bdg_search"></i>
                    <input type="search" class="cari-detail-apbd form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                  </th>
                </tr>
              </thead>
              <tbody>
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

<!--SIMPAN RINCIAN-->
<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-rincian">
  <a href="#" class="close"><i class="icon-bdg_cross"></i></a>
  <form id="simpan-komponen" class="form-horizontal">
    <div class="input-wrapper">
      <h5>Tambah / Edit Rincian</h5>
      <div class="form-group edit">
        <label class="col-sm-3">Sub Rincian / Paket Pekerjaan / Subtitle</label>
        <div class="col-sm-7">
          <select ui-jq="chosen" class="w-full" id="paket-pekerjaan" required="" >
            <option value="">Silahkan Pilih</option>
            @foreach($subrincian as $sr)
            <option value="{{ $sr->SUBRINCIAN_ID }}">{{ $sr->SUBRINCIAN_NAMA }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-sm-2">
          <button class="btn btn-warning col-md-1 w-full" data-toggle="modal" type="button" data-target="#pilih-paket-modal" id="pilih-paket"><i class="fa fa-plus"></i> Tambah</button>
        </div>
      </div>
      <div class="form-group edit">
        <label class="col-sm-3">Jenis</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="jenis-pekerjaan" required="">
            <option value="">Silahkan Pilih Jenis</option>
            @foreach($pekerjaan as $pkj)
            <option value="{{ $pkj->PEKERJAAN_ID }}">{{ $pkj->PEKERJAAN_NAMA }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group edit">
        <label class="col-sm-3">Kategori</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori-belanja" required="">
            <option value="">Silahkan Pilih Kategori</option>
            <option value="1">SSH</option>
            <option value="2">HSPK</option>
            <option value="3">ASB</option>
          </select>
        </div>
      </div>
      <div class="form-group edit">
        <label class="col-sm-3">Rekening</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="rekening-belanja" required="">
            <option value="">Silahkan Pilih Rekening</option>
          </select>
        </div>
      </div>


      <div class="form-group edit">
        <label for="no_spp" class="col-md-3">Komponen</label>          
        <div class="col-md-6">
          <input type="text" class="form-control" placeholder="Komponen" id="nama-komponen" readonly="">          
          <input type="hidden" class="form-control" readonly="" id="id-komponen">          
          <input type="hidden" class="form-control" readonly="" id="id-rincian">          
          <input type="hidden" class="form-control" readonly="" id="harga-komponen">          
        </div>
        <div class="col-md-1 m-l-n-md">
        <a class="btn btn-warning" data-toggle="modal" data-target="#kode-komponen" id="pilih-komponen" disabled="true">Pilih</a>
        </div>
    @if($status=="perubahan")
    @else
    <div class="col-md-2">
        <label for="no_spp" class="m-l-xl">Pajak</label>          
        <div class="checkbox-remember pull-right m-t-n-xs">
          <div class="checkbox">
          <label class="checkbox-inline i-checks">
            <input type="checkbox" id="pajak">
            <i></i>  
          </label>
          </div>
        </div>
      </div>
    @endif
    </div>

    <div class="form-group hide" id="harga-free">
      <label for="no_spp" class="col-md-3">Harga</label>          
      <div class="col-sm-9">
        <input type="text" id="harga-free-input" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Masukan Harga">
      </div> 
    </div>

    <div class="form-group">
      <label for="no_spp" class="col-md-3">Keterangan</label>          
      @if($status=="perubahan")
      <div class="col-sm-7">
        <input type="text" id="ket-belanja" class="form-control" placeholder="Masukan Keterangan" >          
      </div> 
      <div class="col-md-2">
          <label for="no_spp" class="m-l-xl">Pajak</label>          
          <div class="checkbox-remember pull-right m-t-n-xs">
           <div class="checkbox">
            <label class="checkbox-inline i-checks">
              <input type="checkbox" id="pajak">
              <i></i>  
            </label>
           </div>
          </div>
      </div>
      @else
      <div class="col-sm-9">
        <input type="text" id="ket-belanja" class="form-control" placeholder="Masukan Keterangan" >          
      </div> 
      @endif
    </div>

    <div class="form-group" id="koef1">
      <label for="no_spp" class="col-md-3">Koefisien</label>          
      <div class="col-sm-5">
        <input type="text" onkeypress="return event.charCode >= 45 && event.charCode <= 57" id="vol1" class="form-control" placeholder="Masukan Jumlah" required="">      
      </div> 
      <div class="col-sm-4">
        <select ui-jq="chosen" class="w-full" id="satuan-1">
          @foreach($satuan as $sat)
          <option value="{{ $sat->SATUAN_NAMA }}">{{ $sat->SATUAN_NAMA }}</option>
          @endforeach        
        </select>    
      </div>
    </div>
    <div class="form-group" id="koef2">
      <label for="no_spp" class="col-md-3"></label>          
      <div class="col-sm-5">
        <input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="vol2" class="form-control" placeholder="Masukan Jumlah" required="">           
      </div> 
      <div class="col-sm-4">
        <select ui-jq="chosen" class="w-full" id="satuan-2">
          <option>Pilih Satuan</option>
          @foreach($satuan as $sat)
          <option value="{{ $sat->SATUAN_NAMA }}">{{ $sat->SATUAN_NAMA }}</option>
          @endforeach
        </select>    
      </div>
    </div>          
    <div class="form-group" id="koef3">
      <label for="no_spp" class="col-md-3"></label>          
      <div class="col-sm-5">
        <input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="vol3" class="form-control" placeholder="Masukan Jumlah" required="">      
      </div> 
      <div class="col-sm-4">
        <select ui-jq="chosen" class="w-full" id="satuan-3">
          <option>Pilih Satuan</option>
          @foreach($satuan as $sat)
          <option value="{{ $sat->SATUAN_NAMA }}">{{ $sat->SATUAN_NAMA }}</option>
          @endforeach
        </select>    
      </div>
    </div>
    <div class="form-group" id="koef4">
      <label for="no_spp" class="col-md-3"></label>          
      <div class="col-sm-5">
        <input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="vol4" class="form-control" placeholder="Masukan Jumlah" required="">      
      </div> 
      <div class="col-sm-4">
        <select ui-jq="chosen" class="w-full" id="satuan-4">
          <option>Pilih Satuan</option>
          @foreach($satuan as $sat)
          <option value="{{ $sat->SATUAN_NAMA }}">{{ $sat->SATUAN_NAMA }}</option>
          @endforeach
        </select>    
      </div>
    </div>          
    <hr class="m-t-xl">
    <div class="form-group" id="koef4">
      <label for="no_spp" class="col-md-1">Pagu SKPD</label>          
      <div class="col-sm-3">
        <input type="text" id="pagu-skpd" class="form-control" readonly="">      
        <input type="hidden" id="pagu-skpd_" class="form-control" readonly="">      
      </div>
      <label for="no_spp" class="col-md-1 m-t-n-xxl">Rincian SKPD</label>          
      <div class="col-sm-3">
        <input type="text" id="rincian-skpd" class="form-control" readonly="">      
        <input type="hidden" id="rincian-skpd_" class="form-control" readonly="">      
      </div> 
      <label for="no_spp" class="col-md-1">Selisih</label>          
      <div class="col-sm-3">
        <input type="text" id="sisa-skpd" class="form-control" readonly="">      
        <input type="hidden" id="sisa-skpd_" class="form-control" readonly="">      
      </div> 
    </div>
    <hr class="m-t-xl">
    <div class="form-group" id="koef5">
      <label for="no_spp" class="col-md-1">Pagu Kegiatan</label>          
      <div class="col-sm-3">
        <input type="text" id="pagu-kegiatan" class="form-control" readonly="">      
        <input type="hidden" id="pagu-kegiatan_" class="form-control" readonly="">      
      </div>
      <label for="no_spp" class="col-md-1 m-t-n-xxl">Rincian Kegiatan</label>          
      <div class="col-sm-3">
        <input type="text" id="rincian-kegiatan" class="form-control" readonly="">      
        <input type="hidden" id="rincian-kegiatan_" class="form-control" readonly="">      
      </div> 
      <label for="no_spp" class="col-md-1">Selisih</label>          
      <div class="col-sm-3">
        <input type="text" id="sisa-kegiatan" class="form-control" readonly="">      
        <input type="hidden" id="sisa-kegiatan_" class="form-control" readonly="">      
      </div> 
    </div>
    <hr class="m-t-xl">
    <div class="form-group" id="koef6">
      <label for="no_spp" class="col-md-1">Total Rekening</label>          
      <div class="col-sm-3">
        <input type="text" id="pagu-realisasi" class="form-control" readonly="">      
        <input type="hidden" id="pagu-realisasi_" class="form-control" readonly="">      
      </div>
      <label for="no_spp" class="col-md-1 m-t-n-xxl">Total Realisasi</label>          
      <div class="col-sm-3">
        <input type="text" id="rincian-realisasi" class="form-control" readonly="">      
        <input type="hidden" id="rincian-realisasi_" class="form-control" readonly="">      
      </div> 
      <label for="no_spp" class="col-md-1">Selisih</label>          
      <div class="col-sm-3">
        <input type="text" id="sisa-realisasi" class="form-control" readonly="">      
        <input type="hidden" id="sisa-realisasi_" class="form-control" readonly="">      
      </div> 
    </div>
    <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanKomponen()" ><i class="fa fa-plus m-r-xs "></i>Simpan Rincian</a>
  </div>
</form>
</div>
</div>
<!--SIMPAN RINCIAN-->



<!--PILIH KOMPONEN-->
<div class="plih-komponen modal fade " id="kode-komponen" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white modal-lg">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Pilih Komponen</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-popup table-striped b-t b-b table-komponen" id="table-komponen">
          <thead>
            <tr>
              <th class="hide">ID</th>
              <th class="hide">Satuan</th>
              <th class="hide">Nama</th>
              <th class="hide">Harga</th>
              <th>Kode</th>
              <th>Komponen</th>
              <th>Harga</th>                          
            </tr>
            <tr>
              <th class="hide"></th>
              <th class="hide"></th>
              <th class="hide"></th>
              <th class="hide"></th>
              <th colspan="3" class="th_search">
                <i class="icon-bdg_search"></i>
                <input type="search" id="cari-komponen" class="cari-komponen form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
              </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!--END PILIH KOMPONEN-->

<!--PAKET PEKERJAAN-->
<div class="pilih-paket modal fade " id="pilih-paket-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <div class="form-group m-b-xl">
          <div class="col-sm-9">
            <input type="text" id="paket-nama" class="form-control" placeholder="Masukan Paket Pekerjaan" >          
          </div>
          <div class="col-sm-3">
            <button class="btn btn-success" data-dismiss="modal" onclick="return simpanPaket()">Simpan</button>
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>
<!--END PAKET PEKERJAAN-->


<!--EDIT MUSRENBANG-->
<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-musrenbang">
  <a href="#" class="close"><i class="icon-bdg_cross"></i></a>
  <form id="simpan-musrenbang" class="form-horizontal">
    <div class="input-wrapper">
      <h5>Ubah Komponen Musrenbang</h5>
      <div class="form-group">
        <label class="col-sm-3">Jenis</label>
        <div class="col-sm-9">
          <input type="hidden" id="idmusren">
          <select ui-jq="chosen" class="w-full" id="jenis-pekerjaan-musrenbang" required="">
            <option value="">Silahkan Pilih Jenis</option>
            @foreach($pekerjaan as $pkj)
            <option value="{{ $pkj->PEKERJAAN_ID }}">{{ $pkj->PEKERJAAN_NAMA }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3">Rekening</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="rekening-belanja-musrenbang" required="">
            <option value="">Silahkan Pilih Rekening</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3">Sub Rincian / Paket Pekerjaan / Subtitle</label>
        <div class="col-sm-7">
          <select ui-jq="chosen" class="w-full paket-pekerjaan" id="paket-pekerjaan-musrenbang" required="">
            <option value="">Silahkan Pilih</option>
            @foreach($subrincian as $sr)
            <option value="{{ $sr->SUBRINCIAN_ID }}">{{ $sr->SUBRINCIAN_NAMA }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-sm-2">
          <button class="btn btn-warning col-md-1 w-full pilih-paket" data-toggle="modal" type="button" data-target="#pilih-paket-modal"><i class="fa fa-plus"></i> Tambah</button>
        </div>
      </div>
      <hr class="m-t-xl">
      <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanMusrenbang()" ><i class="fa fa-plus m-r-xs "></i>Simpan Komponen</a>
    </div>
  </form>
</div>
<!--END EDIT MUSRENBANG-->


<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-riview">
<a href="#" class="tutup-form"><i class="icon-bdg_cross"></i></a>
    <form id="form-prioritas" class="form-horizontal">
      <div class="input-wrapper">
        <h5 id="judul-prioritas">Masukan Asistensi</h5>
          
         
          <div class="form-group">
            <label for="asistensi_rekening" id="asistensi_rekening_text" class="col-md-3">Rekening</label>          
            <div class="col-sm-9">        
              <input type="hidden" class="form-control" name="asistensi_id" id="asistensi_id">        
              <input type="hidden" class="form-control" name="asistensi_blid" id="asistensi_blid" value="{{$BL_ID}}">        
              <input type="hidden" class="form-control" name="asistensi_rekening_id" id="asistensi_rekening_id">         
              <input type="hidden" class="form-control" name="asistensi_tipe" id="asistensi_tipe">          
              <input type="text" class="form-control" readonly placeholder="Masukan Tahun Program" name="asistensi_rekening" id="asistensi_rekening" value="" disabled> 
            </div> 
          </div>

          <div class="form-group">
            <label for="asistensi_anggaran" id="asistensi_anggaran_text" class="col-md-3">Anggaran</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" readonly placeholder="Anngaran Rekening" name="asistensi_anggaran" id="asistensi_anggaran" value="" disabled> 
            </div> 
          </div>

           <div class="form-group">
            <label for="nama_program" class="col-md-3">Komentar</label>          
            <div class="col-sm-9">
              <select ui-jq="chosen" class="w-full" id="asistensi_komentar" name="asistensi_komentar">
                    <option value="1">Tinjau ulang / rasionalisasi</option>
                    <option value="2">Formulasikan kembali</option>
                    <option value="3">lainya</option>
              </select>
            </div> 
          </div>

          <div id="asistensi_catatan" class="form-group hide">
            <label for="nama_program_prioritas" class="col-md-3">Pesan</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Catatan" name="catatan" id="catatan" value="Tinjau ulang / rasionalisasi"> 
            </div> 
          </div>

          <hr class="m-t-xl">
         <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanPrioritas()"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
      </div>
    </form>
  </div>
 </div>

 <div class="set-output modal fade " id="set-output-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white modal-lg">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Daftar Komponen</h5>
      </div>        
      <div class="table-responsive">
        <table class="table table-popup table-striped b-t b-b table-output" id="table-output">
        <thead>
            <tr>
              <th class="hide">ID</th>
              <th class="">Volume</th>
              <th class="">Paket Pekerjaan</th>
              <th class="">Satuan</th>
              <th>Rekening</th>
              <th>Komponen</th>
              <th>Harga</th>                          
            </tr>
            <tr>
              <th class="hide"></th>
              <th colspan="6" class="th_search">
                <i class="icon-bdg_search"></i>
                <input type="search" id="cari-komponen" class="cari-komponen form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
              </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</div>
<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
@endsection

@section('plugin')
<script type="text/javascript">
  $(document).ready(function(){
    $("#app").trigger('click');
    interval = null;
  });
</script>

<script type="text/javascript">
  $("select.selectrincian").on('click',function() {
    $('.table').DataTable().page.len($('.selectrincian').val()).draw();
  });

  $('input.cari-komponen').keyup( function () {
    $('.table-komponen').DataTable().search($('.cari-komponen').val()).draw();
  });
  $('input.cari-detail').keyup( function () {
    $('.tabel-detail').DataTable().search($('.cari-detail').val()).draw();
  });
  $('input.cari-detail-rkpd').keyup( function () {
    $('.tabel-detail-rkpd').DataTable().search($('.cari-detail-rkpd').val()).draw();
  });
  $('input.cari-detail-ppas').keyup( function () {
    $('.tabel-detail-ppas').DataTable().search($('.cari-detail-ppas').val()).draw();
  });
  $('input.cari-detail-rapbd').keyup( function () {
    $('.tabel-detail-rapbd').DataTable().search($('.cari-detail-rapbd').val()).draw();
  });
  $('input.cari-detail-apbd').keyup( function () {
    $('.tabel-detail-apbd').DataTable().search($('.cari-detail-apbd').val()).draw();
  });

  $("#kategori-belanja").change(function(e, params){
    var id  = $('#kategori-belanja').val();
    $('#rekening-belanja').find('option').remove().end().append('<option>Pilih Rekening</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rekening/"+id,
      success : function (data) {
        $('#rekening-belanja').append(data).trigger('chosen:updated');
      }
    });
  });

  $("#asistensi_komentar").change(function(e, params){
    var id  = $('#asistensi_komentar option:selected').text();
    if($('#asistensi_komentar').val()<3){
      $('#catatan').val(id);
      $('#asistensi_catatan').addClass('hide');
    }else{
      $('#catatan').val('');
      $('#asistensi_catatan').removeClass('hide');
    }
  });

  $("#rekening-belanja").change(function(e, params){
    var id  = $('#rekening-belanja').val();
    $('#pilih-komponen').attr('disabled',false);
    $('.table-komponen').DataTable().destroy();
    $('.table-komponen').DataTable({
      sAjaxSource: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/komponen/"+id+"/{{ $BL_ID }}",
      aoColumns: [
      { mData: 'KOMPONEN_ID',class:'hide' },
      { mData: 'KOMPONEN_SHOW',class:'hide' },
      { mData: 'KOMPONEN_SATUAN',class:'hide' },
      { mData: 'KOMPONEN_HARGA_',class:'hide' },
      { mData: 'KOMPONEN_KODE',class:'text-center' },
      { mData: 'KOMPONEN_NAMA' },
      { mData: 'KOMPONEN_HARGA' }]
    });
  });

  $("#jenis-pekerjaan").change(function(e, params){
    var id  = $('#jenis-pekerjaan').val();
    if(id == '4' || id == '5' || id == '6' || id == '7' || id == '8'){
      $('#nama-komponen').attr('readonly',false);
      $('#ket-belanja').attr('readonly',true);
      $('#harga-free').removeClass('hide');
      $('#pilih-komponen').addClass('hide');
    }else{
      $('#nama-komponen').attr('readonly',true);
      $('#ket-belanja').attr('readonly',false);
      $('#harga-free').addClass('hide');
      $('#pilih-komponen').removeClass('hide');
    };
  });

  $('.table-komponen').on('click','tbody > tr', function(){
    id    = $(this).children('td').eq(0).html();
    nama  = $(this).children('td').eq(1).html();
    sat   = $(this).children('td').eq(2).html();
    harga   = $(this).children('td').eq(3).html();
    $('#id-komponen').val(id);
    $('#harga-komponen').val(harga);
    $('#nama-komponen').val(nama);
    $('#satuan-1').find('option').remove().end().append('<option value="'+sat+'">'+sat+'</option>').trigger('chosen:updated');      
    $('#kode-komponen').modal('hide');
  });

  $('#btn-tambah-komponen').on('click', function(){
    if(!$('#koef3').hasClass('hide')){
      $('#koef4').removeClass('hide');
    }
    if(!$('#koef2').hasClass('hide')){
      $('#koef3').removeClass('hide');
    }
    if($('#koef2').hasClass('hide')){
      $('#koef2').removeClass('hide');
    }
  })


  $('#btn-validasi').on('click',function(){
    id    = '{{ $BL_ID }}';
    token = $('#token').val();
    $.confirm({
      title: 'Validasi!',
      content: 'Yakin validasi belanja langsung?',
      buttons: {
        Ya: {
          btnClass: 'btn-success',
          action: function(){
            $.ajax({
              url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/validasi",
              type: "POST",
              data: {'_token':token, 'BL_ID' : id},
              success: function(msg){
                $('#rincian-pagu').text(msg);
                $.alert('Validasi Berhasil!');
                $('#btn-validasi').addClass('disabled');
                $('#btn-rka-hide').removeClass('hide');
              }
            });
          }
        },
        Tidak: function () {
        }
      }
    });
    
  })

  function simpanKomponen(){
    var token           = $('#token').val();    
    var RINCIAN_ID      = $('#id-rincian').val();
    var REKENING_ID     = $('#rekening-belanja').val();
    var KOMPONEN_ID     = $('#id-komponen').val();
    var KOMPONEN_NAMA   = $('#nama-komponen').val();
    var VOL1            = $('#vol1').val();
    
    /*if($('#id-rincian').val() == ""){
      var VOL1            = $('#vol1').val();
    }else {
      var VOL1            = $('#vol1-u').val();
    }*/

    var SAT1            = $('#satuan-1').val();
    var VOL2            = $('#vol2').val();
    var SAT2            = $('#satuan-2').val();
    var VOL3            = $('#vol3').val();
    var SAT3            = $('#satuan-3').val();
    var VOL4            = $('#vol4').val();
    var SAT4            = $('#satuan-4').val();
    var RINCIAN_SUB     = $('#sub-belanja').val();
    var RINCIAN_SUB     = $('#sub-belanja').val();
    var RINCIAN_KET     = $('#ket-belanja').val();
    var PEKERJAAN_ID    = $('#jenis-pekerjaan').val();
    var SUBRINCIAN_ID   = $('#paket-pekerjaan').val();
    var HARGA           = $('#harga-free-input').val();
    if($('#pajak').is(':checked')) RINCIAN_PAJAK = 10;
    else RINCIAN_PAJAK = 0;
    if(PEKERJAAN_ID == '4' || PEKERJAAN_ID == '5' || PEKERJAAN_ID == '6' || PEKERJAAN_ID == '7' || PEKERJAAN_ID == '8' ){
      KOMPONEN_ID   = '0';
      RINCIAN_KET = KOMPONEN_NAMA + '#'+ HARGA;
    }
    console.log(KOMPONEN_ID);
    //if(REKENING_ID == "" || KOMPONEN_ID == "" || VOL1 == "" || SAT1 == "" || PEKERJAAN_ID == "" || SUBRINCIAN_ID == ""){
    //if(REKENING_ID == "" || KOMPONEN_ID == "" || VOL1 == "" || PEKERJAAN_ID == "" || SUBRINCIAN_ID == ""){
    if(REKENING_ID == "" || KOMPONEN_ID == "" || VOL1 == "" || SUBRINCIAN_ID == ""){
      //if(REKENING_ID == "" || VOL1 == "" || SUBRINCIAN_ID == ""){
      $.alert('Form harap diisi!');
    }else{
      if((PEKERJAAN_ID == '4' || PEKERJAAN_ID == '5' || PEKERJAAN_ID == '6' || PEKERJAAN_ID == '7' || PEKERJAAN_ID == '8') && (HARGA == "" || KOMPONEN_NAMA == "")){
      //if(HARGA == "" || KOMPONEN_NAMA == "") {
        $.alert('Form harap diisi-!');
      }else{
        if($('#id-rincian').val() == "") url = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincian/simpan";
        else url = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincian/ubah";
        $.ajax({
          url: url,
          type: "POST",
          data: {'_token'         : token,
          'BL_ID'           : '{{ $BL_ID }}', 
          'RINCIAN_ID'      : RINCIAN_ID, 
          'REKENING_ID'     : REKENING_ID, 
          'KOMPONEN_ID'     : KOMPONEN_ID,
          'RINCIAN_PAJAK'   : RINCIAN_PAJAK, 
          'VOL1'            : VOL1, 
          'SAT1'            : SAT1, 
          'VOL2'            : VOL2, 
          'SAT2'            : SAT2, 
          'VOL3'            : VOL3, 
          'SAT3'            : SAT3, 
          'VOL4'            : VOL4, 
          'SAT4'            : SAT4, 
          'HARGA'           : HARGA, 
          'KOMPONEN_NAMA'   : KOMPONEN_NAMA, 
          'SUBRINCIAN_ID'   : SUBRINCIAN_ID, 
          'RINCIAN_KET'     : RINCIAN_KET, 
          'PEKERJAAN_ID'    : PEKERJAAN_ID},
          success: function(msg){
            if(msg == 0){
              $.alert('Lebih Dari Pagu');
            }else if(msg == 99){
              $.alert('Volume Tidak Bisa Ditambahkan');
            }else if(msg == 98){
              $total = 0;
              $.alert('Anggaran Kurang dr realisasi Rp.'+total);
            }else if(msg == 101){
              $.alert('Total Rincian Melebihi Total Per Jenis Belanja');
            }else if(msg != 0){
              if(PEKERJAAN_ID == '4' || PEKERJAAN_ID == '5' || PEKERJAAN_ID == '7' || PEKERJAAN_ID == '8') location.reload();
              $('.input-rincian,.input-sidebar').animate({'right':'-1050px'},function(){
                $('.overlay').fadeOut('fast');
              });
              clearInterval(interval);              
              $('#id-rincian').val('');
              $('#jenis-pekerjaan').val('').trigger('chosen:updated');
              $('#kategori-belanja').val('').trigger('chosen:updated');
              $('#rekening-belanja').val('').trigger('chosen:updated');
              $('#paket-pekerjaan').val('').trigger('chosen:updated');
              $('#nama-komponen').val('');
              $('#sub-belanja').val('');
              $('#ket-belanja').val('');
              $('#vol1').val('');
              $('#satuan-1').val('').trigger('chosen:updated');
              $('#vol2').val('');
              $('#satuan-2').val('').trigger('chosen:updated');
              $('#vol3').val('');
              $('#satuan-3').val('').trigger('chosen:updated');
              $('#vol4').val('');
              $('#satuan-4').val('').trigger('chosen:updated');
              $('#pilih-komponen').attr('disabled',true);
              $.alert('Input Berhasil!');
              $('#rincian-total').text(msg);
              $('#rincian-total-1').text(msg);
              $('#masukan').text(msg);
              $('.tabel-detail').DataTable().ajax.reload();
              $('#table-rekening').DataTable().ajax.reload();              
              $('#btn-validasi').removeClass('disabled');
            }
          }
        });
      }
    }
  }

  

  function ubah(id){
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincian/detail/"+id,
      success : function (data) {
        console.log(data);
        $('#nama-komponen').val(data['KOMPONEN_NAMA']);
        $('#paket-pekerjaan').val(data['DATA']['SUBRINCIAN_ID']).trigger('chosen:updated');
        $('#ket-belanja').val(data['DATA']['RINCIAN_KETERANGAN']);
        $('#jenis-pekerjaan').val(data['DATA']['PEKERJAAN_ID']).trigger('chosen:updated');
        id_komponen   = data['KOMPONEN_KODE'].substring(0,1);
        if(id_komponen < 1){
          id_komponen = 1;
        }
        var id  = $('#jenis-pekerjaan').val();
    if(id == '4' || id == '5' || id == '6' || id == '7' || id == '8'){
      $('#nama-komponen').attr('readonly',false);
      $('#ket-belanja').attr('readonly',true);
      $('#harga-free').removeClass('hide');
      result = $('#ket-belanja').val().split('#');
      $('#harga-free-input').val(result[1]);
      $('#pilih-komponen').addClass('hide');
    }else{
      $('#nama-komponen').attr('readonly',true);
      $('#ket-belanja').attr('readonly',false);
      $('#harga-free').addClass('hide');
      $('#pilih-komponen').removeClass('hide');
    };
    @if($status=="perubahan")
      $('.edit').hide();
    @else
    $('.edit').show();
    @endif
	$('#id-komponen').val(data['DATA']['KOMPONEN_ID']);
        $('#id-rincian').val(data['DATA']['RINCIAN_ID']);
        $('#kategori-belanja').val(id_komponen).trigger('chosen:updated');
        $('#rekening-belanja').find('option').remove().end().append('<option value="'+data['DATA']['REKENING_ID']+'">'+data['REKENING_NAMA']+'</option>').trigger('chosen:updated');
        if(data['DATA']['RINCIAN_PAJAK'] == 10){
          $('#pajak').prop('checked',true);
        }
        $('#vol1').val(data['VOL1']);
        $('#satuan-1').append('<option value="'+data['SATUAN1']+'" selected>'+data['SATUAN1']+'</option>').trigger('chosen:updated');
        $('#vol2').val(data['VOL2']);
        $('#satuan-2').append('<option value="'+data['SATUAN2']+'" selected>'+data['SATUAN2']+'</option>').trigger('chosen:updated');
        $('#vol3').val(data['VOL3']);
        $('#satuan-3').append('<option value="'+data['SATUAN3']+'" selected>'+data['SATUAN3']+'</option>').trigger('chosen:updated');
        $('#vol4').val(data['VOL4']);
        $('#satuan-4').append('<option value="'+data['SATUAN4']+'" selected>'+data['SATUAN4']+'</option>').trigger('chosen:updated');
      }
    });
    $('.overlay').fadeIn('fast',function(){
      $('.input-rincian').animate({'right':'0'},"linear");  
      getpagu();
      //interval = setInterval(function(){getpagu();}, 1000);
      $("html, body").animate({ scrollTop: 0 }, "slow");
    });

  }

  function hapus(id){
    var token        = $('#token').val();    
    $.confirm({
      title: 'Hapus Data!',
      content: 'Yakin hapus data?',
      buttons: {
        Ya: {
          btnClass: 'btn-danger',
          action: function(){
            $.ajax({
              url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincian/hapus",
              type: "POST",
              data: {'_token'         : token,
              'BL_ID'           : '{{ $BL_ID }}',
              'RINCIAN_ID'      : id},
              success: function(msg){
                $('.tabel-detail').DataTable().ajax.reload();                          
                $('#table-rekening').DataTable().ajax.reload(); 
                if(msg == 'FAIL') $.alert('Hapus Gagal, Rekening Kurang Dari Realisasi!');
                else $.alert('Hapus Berhasil');
                $('#rincian-total').text(msg);
              }
            });
          }
        },
        Tidak: function () {
        }
      }
    });
  }

  function rinci(id){
    $('#rekening-belanja-musrenbang').find('option').remove().end().append('<option>Pilih Rekening</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rekening-musrenbang/"+id,
      success : function (data) {
        $('#rekening-belanja-musrenbang').append(data).trigger('chosen:updated');
        $('#idmusren').val(id);
        $('.overlay').fadeIn('fast',function(){
          $('.input-musrenbang').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        });
      }
    });
  }

  function simpanMusrenbang(){
    var token           = $('#token').val();    
    var REKENING_ID     = $('#rekening-belanja-musrenbang').val();
    var PEKERJAAN_ID    = $('#jenis-pekerjaan-musrenbang').val();
    var PAKET_ID        = $('#paket-pekerjaan-musrenbang').val();
    var RINCIAN_ID      = $('#idmusren').val();
    if(REKENING_ID == "" ||  PEKERJAAN_ID == "" || PAKET_ID == ""){
      $.alert('Form harap diisi!');
    }else{
      console.log(PEKERJAAN_ID);
      $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincian-musrenbang/simpan",
        type: "POST",
        data: {'_token'         : token,
              'RINCIAN_ID'      : RINCIAN_ID, 
              'REKENING_ID'     : REKENING_ID,
              'PAKET_ID'        : PAKET_ID,
              'BL_ID'           : '{{ $BL_ID }}',               
              'PEKERJAAN_ID'    : PEKERJAAN_ID},
        success: function(msg){
          $('.input-rincian,.input-sidebar').animate({'right':'-1050px'},function(){
            $('.overlay').fadeOut('fast');
          });
          $('#paket-pekerjaan-musrenbang').val('').trigger('chosen:updated');
          $('#jenis-pekerjaan-musrenbang').val('').trigger('chosen:updated');
          $('#rekening-belanja-musrenbang').val('').trigger('chosen:updated');
          $('#idmusren').val('');
          $.alert('Input Berhasil!');
          $('#rincian-total').text(msg);
          $('.tabel-detail').DataTable().ajax.reload();
          $('#btn-validasi').removeClass('disabled');
        }
      });
    }
  }

  function simpanPaket(){
    token  = $('#token').val();
    paket  = $('#paket-nama').val();
    
    $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/simpanpaket",
        type: "POST",
        data: {'_token'         : token,
              'SUBRINCIAN_NAMA' : paket, 
              'BL_ID'           : '{{ $BL_ID }}'},
        success: function(msg){
          $('#paket-nama').val('');
          $.alert('Input Berhasil!');
          $('#paket-pekerjaan').find('option').remove().end().append('<option>Pilih Paket</option>');
          $('.paket-pekerjaan').find('option').remove().end().append('<option>Pilih Paket</option>');
          $('#paket-pekerjaan').append(msg).trigger('chosen:updated');
          $('.paket-pekerjaan').append(msg).trigger('chosen:updated');
        }          
      });
  }

  function simpanPrioritas(){
    token  = $('#token').val();
    id  = $('#asistensi_id').val();
    rekid  = $('#asistensi_rekening_id').val();
    tipe  = $('#asistensi_tipe').val();
    catatan  = $('#catatan').val();
    $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/simpanasistensi",
        type: "POST",
        data: {'_token'         : token,
              'ID'              : id, 
              'BL_ID'           : '{{ $BL_ID }}',
              'REKENING_ID'     : rekid,
              'CATATAN'         : catatan,
              'TIPE'  : tipe,
        },
        success: function(msg){
          $('#asistensi_id').val('');
          $('#asistensi_rekening_id').val('');
          $('#catatan').val('Tinjau ulang / rasionalisasi');
          $.alert('Input Berhasil!');
          $('.input-riview').animate({'right':'-1050px'},function(){
                $('.overlay').fadeOut('fast');
          });
        }          
      });
  }

  function setAsistensi(id){
      var token        = $('#token').val();
      @if(Auth::user()->level == 0)
      var status       = 2;
      var back       = 1;
      @else
      var status       = 1;
      var back         = 0;
      @endif
      
      $.confirm({
          title: 'Validasi Asistensi!',
          content: 'Yakin validasi data?',
          buttons: {
              Ya: {
                  btnClass: 'btn-success',
                  action: function(){
                    $.ajax({
                        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/statusasistensi",
                        type: "POST",
                        data: {'_token'         : token,
                              'ASISTENSI_ID'           : id,
                              'STATUS'           : status},
                        success: function(msg){
                            $('#table-rekening').DataTable().ajax.reload();                          
                            $.alert(msg);
                          }
                    });
                  }
              },
              Tidak: {
                btnClass: 'btn-warning',
                  action: function(){
                    $.ajax({
                        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/statusasistensi",
                        type: "POST",
                        data: {'_token'         : token,
                              'ASISTENSI_ID'      : id,
                              'STATUS'           : back},
                        success: function(msg){
                            $('#table-rekening').DataTable().ajax.reload();                          
                            $.alert(msg);
                          }
                    });
                  }
              }
          }
      });
  }

  function hapuscb(){
    var token        = $('#token').val();    
    $.confirm({
      title: 'Hapus Data!',
      content: 'Yakin hapus data?',
      buttons: {
        Ya: {
          btnClass: 'btn-danger',
          action: function(){
            var val = [];
            $(':checkbox.cb:checked').each(function(i){
              val[i] = $(this).val();
            });
            if(val.length == 0){
              $.alert('Pilih Komponen!');
            }else{
              $.ajax({
                url: "{{ url('/') }}/main/{{$tahun}}/{{$status}}/belanja-langsung/rincian/hapus-cb",
                type: "POST",
                data: {'_token'            : token,
                      'BL_ID'              : '{{$BL_ID}}',
                      'RINCIAN_ID'         : val},
                success: function(msg){
                  $('.tabel-detail').DataTable().ajax.reload();                          
                  $.alert('Hapus Berhasil!');
                  $('#rincian-total').text(msg);
                }
              });
            }
          }
        },
        Tidak: function () {
        }
      }
    }); 
  }

  $('.open-rincian').on('click',function(){
    $(document).ready(function(){
        getpagu();
        //clearInterval(interval);
        $('.edit').show();              
        $('#id-rincian').val('');
        $('#jenis-pekerjaan').val('').trigger('chosen:updated');
        $('#kategori-belanja').val('').trigger('chosen:updated');
        $('#rekening-belanja').val('').trigger('chosen:updated');
        $('#paket-pekerjaan').val('').trigger('chosen:updated');
        $('#nama-komponen').val('');
        $('#sub-belanja').val('');
        $('#ket-belanja').val('');
        $('#vol1').val('');
        $('#satuan-1').val('').trigger('chosen:updated');
        $('#vol2').val('');
        $('#satuan-2').val('').trigger('chosen:updated');
        $('#vol3').val('');
        $('#satuan-3').val('').trigger('chosen:updated');
        $('#vol4').val('');
        $('#satuan-4').val('').trigger('chosen:updated');
        $('#pilih-komponen').attr('disabled',true);
    });
  });



    $('.overlay').on('click',function(){      
        //clearInterval(interval);
    }); 

    $('.close').click(function(){
        //clearInterval(interval);
        $('.input-sidebar').animate({'right':'-1050px'},"linear",function(){
          $('.overlay').fadeOut('fast');
        });
    }); 
  function getpagu(){
    $.ajax({
        url: "{{ url('/') }}/main/{{$tahun}}/{{$status}}/belanja-langsung/rincian/pagu/getpagu/{{$BL_ID}}",
        type: "GET",
        success: function(msg){
          harga     = $('#harga-komponen').val();
          vol1      = $('#vol1').val();
          vol2      = $('#vol2').val();
          vol3      = $('#vol3').val();
          vol4      = $('#vol4').val();
          if(vol1 == "") vol1 = 0;
          if(vol2 == "") vol2 = 0;
          if(vol3 == "") vol3 = 0;
          if(vol4 == "") vol4 = 0;
          if(harga == "") harga = 0;
          rincian   = msg['skpdtotal'];
          pagu      = msg['skpdpagu'];
          kegpagu   = msg['kegpagu'];
          kegtotal      = msg['kegtotal'];
          kegsisa      = msg['kegsisa'];
          rekpagu   = msg['rekpagu'];
          rekrl      = msg['rektotal'];
          reksisa      = msg['reksisa'];
          total1     = parseInt(vol1) * parseInt(harga);
          total2     = parseInt(vol2) * parseInt(harga);
          total3     = parseInt(vol3) * parseInt(harga);
          total4     = parseInt(vol4) * parseInt(harga);
          total      = total1 + total2 + total3 + total4;
          anggaran  = parseInt(rincian)+ parseInt(total);
          sisa      = parseInt(pagu) - parseInt(anggaran);
          //console.log(rincian);
          //console.log(total);
          //console.log(pagu);
          $('#rincian-skpd').val(anggaran.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          $('#pagu-skpd').val(pagu.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          $('#sisa-skpd').val(sisa.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          $('#rincian-skpd_').val(anggaran);
          $('#pagu-skpd_').val(pagu);
          $('#sisa-skpd_').val(sisa); 
          $('#rincian-kegiatan').val(kegtotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          $('#pagu-kegiatan').val(kegpagu.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          $('#sisa-kegiatan').val(kegsisa.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          $('#rincian-kegiatan_').val(kegtotal);
          $('#pagu-kegiatan_').val(kegpagu);
          $('#sisa-kegiatan_').val(kegsisa);
          $('#rincian-realisasi').val(rekrl.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          $('#pagu-realisasi').val(rekpagu.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          $('#sisa-realisasi').val(reksisa.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          $('#rincian-realisasi_').val(rekrl);
          $('#pagu-realisasi_').val(rekpagu);
          $('#sisa-realisasi_').val(reksisa); 
        }     
      });
  }

  $('#vol1').keyup(function(){
    vol();
  });
  $('#vol2').keyup(function(){
    vol();
  });
  $('#vol3').keyup(function(){
    vol();
  });
  $('#vol4').keyup(function(){
    vol();
  });

  function vol(){
    harga     = $('#harga-komponen').val();
    vol1      = $('#vol1').val();
    vol2      = $('#vol2').val();
    vol3      = $('#vol3').val();
    vol4      = $('#vol4').val();
    rincian   = $('#rincian-skpd_').val();
    pagu      = $('#pagu-skpd_').val();
          if(vol1 == "") vol1 = 0;
          if(vol2 == "") vol2 = 0;
          if(vol3 == "") vol3 = 0;
          if(vol4 == "") vol4 = 0;
          if(harga == "") harga = 0;    
    total1     = parseInt(vol1) * parseInt(harga);
    total2     = parseInt(vol2) * parseInt(harga);
    total3     = parseInt(vol3) * parseInt(harga);
    total4     = parseInt(vol4) * parseInt(harga);
    total      = total1 + total2 + total3 + total4;
    anggaran  = parseInt(rincian)+ parseInt(total);
    sisa      = parseInt(pagu) - parseInt(anggaran);
    $('#rincian-skpd_').val(anggaran);
    $('#sisa-skpd_').val(sisa);
    $('#rincian-skpd').val(anggaran.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    $('#sisa-skpd').val(sisa.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")); 
  }

  function inputRiview(id, tipe) {
    $('#judul-prioritas').text('Form Keterangan Asistensi');
    $('#asistensi_rekening_id').val(id);
    if(tipe=="rekening"){
      $.ajax({
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rekening/{{ $BL_ID }}/"+id,
      type: "GET",
      success: function(msg){
        $('#asistensi_rekening_text').text('Rekening');
        $('#asistensi_anggaran_text').text('Anggaran');
        $('#asistensi_rekening').val(msg['aaData'][0]['KODE']+' '+msg['aaData'][0]['URAIAN']); 
        $('#asistensi_anggaran').val(msg['aaData'][0]['SESUDAH']);
        $('#asistensi_tipe').val('rekening'); 
        $('.overlay').fadeIn('fast',function(){
          $('.input-riview').animate({'right':'0'},"linear"); 
          $("html, body").animate({ scrollTop: 0 }, "slow");
        });
      }
      });    
    }else if(tipe=="impact"){
      $.ajax({
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/program/"+tipe+"/"+id,
      type: "GET",
      success: function(msg){
        $('#asistensi_rekening_text').text('Tolak Ukur');
        $('#asistensi_anggaran_text').text('Target');
        $('#asistensi_rekening').val(msg['IMPACT_TOLAK_UKUR']);
        $('#asistensi_anggaran').val(msg['IMPACT_TARGET']+' '); 
        $('#asistensi_tipe').val('impact');
        $('.overlay').fadeIn('fast',function(){
          $('.input-riview').animate({'right':'0'},"linear"); 
          $("html, body").animate({ scrollTop: 0 }, "slow");
        });
      }
      }); 
    }else if(tipe=="output"){
      $.ajax({
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/kegiatan/"+tipe+"/"+id,
      type: "GET",
      success: function(msg){
        $('#asistensi_rekening_text').text('Tolak Ukur');
        $('#asistensi_anggaran_text').text('Target');
        $('#asistensi_rekening').val(msg['OUTPUT_TOLAK_UKUR']); 
        $('#asistensi_anggaran').val(msg['OUTPUT_TARGET']+' '); 
        $('#asistensi_tipe').val('output');
        $('.overlay').fadeIn('fast',function(){
          $('.input-riview').animate({'right':'0'},"linear"); 
          $("html, body").animate({ scrollTop: 0 }, "slow");
        });
      }
      }); 
    }
  }

  function showKomponen(id){
    $('#id-kegiatan').val(id);
    $('#table-output').DataTable().destroy();
    $('#table-output').DataTable({
      sAjaxSource: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/komponenterpakai/"+id+"/{{ $BL_ID }}",
      aoColumns: [
      { mData: 'KOMPONEN_ID',class:'hide' },
      { mData: 'KOMPONEN_SATUAN',class:'' },
      { mData: 'KOMPONEN_SHOW',class:'' },
      { mData: 'KOMPONEN_HARGA_',class:'' },
      { mData: 'KOMPONEN_KODE',class:'text-center' },
      { mData: 'KOMPONEN_NAMA' },
      { mData: 'KOMPONEN_HARGA' }]
    });
    $('#set-output-modal').modal('show');
  }

</script>
@endsection
