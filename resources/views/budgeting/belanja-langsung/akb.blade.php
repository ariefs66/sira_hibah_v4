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
          <li><i class="fa fa-angle-right"></i>Anggaran Kas Bulanan </li>                                
          <li class="active"><i class="fa fa-angle-right"></i>{{ $bl->kegiatan->KEGIATAN_NAMA }}</li>                                
        </ul>
      </div>

      <div class="wrapper-lg">
        <div class="row">
          <div class="col-md-12">
            <div class="panel bg-white">
              <div class="panel-heading wrapper-lg">
                <h5 class="inline font-semibold text-orange m-n ">Belanja Langsung : {{ $bl->kegiatan->KEGIATAN_NAMA }}</h5>
                <div class="dropdown dropdown-blend pull-right m-t-n-sm">
                  <button class="btn btn-success dropdown-toggle " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Cetak DPA <i class="fa fa-chevron-down"></i>
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
                </div>
              </div>
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
                      <div class="form-group">
                        <h5 class="text-orange">Indikator Kegiatan</h5>
                        <table class="table">
                          <thead>
                            <tr>
                              <th width="20%">Indikator</th>
                              <th width="60%">Tolak Ukur</th>
                              <th width="20%">Target</th>
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
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="wrapper-lg" style="margin-top: -75px;">
        <div class="row">
          <div class="col-md-12">
            <div class="panel bg-white">
              <div class="wrapper-lg">
                <!-- <h5 class="inline font-semibold text-orange m-n ">Rincian</h5> -->
                <h5 class="inline font-semibold text-orange m-n">Pagu &nbsp;&nbsp;&nbsp;&nbsp;: <span style="color: #000" id="rincian-pagu">{{ number_format($bl->BL_PAGU,0,'.',',') }}</span><br>Rincian : <span style="color: #000" id="rincian-total">{{ number_format($rinciantotal,0,'.',',') }}</span></h5>
                @if($bl->kunci->KUNCI_RINCIAN == 0 and $mod == 1 and $thp == 1 and Auth::user()->active == 1) 
                <button class="open-rincian pull-right btn m-t-n-sm btn-success input-xl"><i class="m-r-xs fa fa-plus"></i> Tambah Komponen</button>
                @elseif($thp == 0)
                <h5 class="pull-right font-semibold text-info m-t-n-xs"><i class="fa fa-info-circle"></i> Tahapan masih ditutup!</h5>
                @elseif(Auth::user()->active == 0)
                <h5 class="pull-right font-semibold text-info m-t-n-xs"><i class="fa fa-info-circle"></i> User Tidak Aktif!</h5>
                @endif

                @if(Auth::user()->level == 8)
                <!-- <a class="pull-right btn m-t-n-sm btn-success" href="{{url('/')}}/main/{{$tahun}}/{{$status}}/belanja-langsung/akb/add/{{$BL_ID}}">Tambah AKB</a> -->
                @endif

                <a class="pull-right btn m-t-n-sm btn-success" href="{{url('/')}}/main/{{$tahun}}/{{$status}}/lampiran/akb/bl/{{$BL_ID}}" target="_blank">Print AKB</a>
                <!-- @if(($BL_ID == 5718 ) 
                    and $mod == 1 
                    and $thp == 1 
                    and $bl->kunci->KUNCI_RINCIAN == 0)
                @else
                <h5 class="pull-right font-semibold text-info m-t-n-xs"><i class="fa fa-info-circle"></i> Rincian Hanya Bisa dirubah / dihapus!</h5>
                @endif -->
                
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

             <!--  <div class="nav-tabs-alt tabs-alt-1 b-t four-row" id="tab-jurnal" >
                <ul class="nav nav-tabs" role="tablist">
                 <li class="active">
                  <a data-target="#tab-1" role="tab" data-toggle="tab">TRIWULANAN<br><span class="text-success" id="rincian-total-1">Rincian : {{ number_format($rinciantotal,0,'.',',') }}</span></a>
                </li>
                <li>
                  <a data-target="#tab-3" role="tab" data-toggle="tab">KUA/PPAS<br><span class="text-success">Rincian : {{ number_format($ppas,0,'.',',') }}</span></a>
                </li>
                <li>
                  <a data-target="#tab-4" role="tab" data-toggle="tab">RAPBD<br><span class="text-success">Rincian : {{ number_format($rapbd,0,'.',',') }}</span></a>
                </li>
                <li>
                  <a data-target="#tab-4" role="tab" data-toggle="tab">APBD<br><span class="text-success">Rincian : {{ number_format($apbd,0,'.',',') }}</span></a> 
                </li>
              </ul>
            </div> -->

            <div class="tab-content tab-content-alt-1 bg-white" id="tab-detail">
            <!-- Tab1 -->
              <div role="tabpanel" class="active tab-pane" id="tab-1">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                 sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/data/akb/{{ $BL_ID }}',
                 aoColumns: [
                 { mData: 'NO',class:'text-center' },
                 { mData: 'REKENING' },
                 { mData: 'TOTAL' },
                 { mData: 'JANUARI' },
                 { mData: 'FEBRUARI' },
                 { mData: 'MARET' },
                 { mData: 'TRIWULAN1' },
                 { mData: 'APRIL' },
                 { mData: 'MEI' },
                 { mData: 'JUNI' },                
                 { mData: 'TRIWULAN2' },
                 { mData: 'JULI' },
                 { mData: 'AGUSTUS' },
                 { mData: 'SEPTEMBER' },
                 { mData: 'TRIWULAN3' },
                 { mData: 'OKTOBER' },
                 { mData: 'NOVEMBER' },
                 { mData: 'DESEMBER' },
                 { mData: 'TRIWULAN4' }]
               }" class="table table-striped b-t b-b tabel-detail ">
               <thead>
                <tr>
                  <th style="width: 1%">#</th>
                  <th>Rekening</th>
                  <th>Jumlah</th>
                  <th style="width: 5%">Januari</th>
                  <th style="width: 5%">Februari</th>
                  <th style="width: 5%">Maret</th>
                  <th style="width: 5%">Triwulan 1</th>
                  <th style="width: 5%">April</th>
                  <th style="width: 5%">Mei</th>
                  <th style="width: 5%">Juni</th>
                  <th style="width: 5%">Triwulan 2</th>
                  <th style="width: 5%">Juli</th>
                  <th style="width: 5%">Agustus</th>
                  <th style="width: 5%">September</th>
                  <th style="width: 5%">Triwulan 3</th>
                  <th style="width: 5%">Oktober</th>
                  <th style="width: 5%">November</th>
                  <th style="width: 5%">Desember</th>
                  <th style="width: 5%">Triwulan 4</th>
                </tr>
                <tr>
                  <th colspan="19" class="th_search">
                    <i class="icon-bdg_search"></i>
                    <input type="search" class="cari-detail form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                  </th>
                </tr>
              </thead>
              <tbody>
              </tbody>                        
            </table>
          </div>
          @if(Auth::user()->email == 8)
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
                 <!-- <table ui-jq="dataTable" ui-options="{
                 sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincianrekap/rkpd/{{ $BL_ID }}',
                 aoColumns: [
                 { mData: 'NO',class:'text-center' },
                 { mData: 'REKENING' },
                 { mData: 'KOMPONEN' },
                 { mData: 'SUB' },
                 { mData: 'HARGA' },
                 { mData: 'PAJAK' },
                 { mData: 'TOTAL' }]
               }" class="table table-striped b-t b-b tabel-detail-rkpd"> -->
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
                <!--  <table ui-jq="dataTable" ui-options="{
                 sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincianrekap/ppas/{{ $BL_ID }}',
                 aoColumns: [
                 { mData: 'NO',class:'text-center' },
                 { mData: 'REKENING' },
                 { mData: 'KOMPONEN' },
                 { mData: 'SUB' },
                 { mData: 'HARGA' },
                 { mData: 'PAJAK' },
                 { mData: 'TOTAL' }]
               }" class="table table-striped b-t b-b tabel-detail-ppas"> -->
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
                 <!-- <table ui-jq="dataTable" ui-options="{
                 sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincianrekap/rapbd/{{ $BL_ID }}',
                 aoColumns: [
                 { mData: 'NO',class:'text-center' },
                 { mData: 'REKENING' },
                 { mData: 'KOMPONEN' },
                 { mData: 'SUB' },
                 { mData: 'HARGA' },
                 { mData: 'PAJAK' },
                 { mData: 'TOTAL' }]
               }" class="table table-striped b-t b-b tabel-detail-rapbd"> -->
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


<div class="overlay"></div>
<!-- <div class="bg-white wrapper-lg input-sidebar input-rincian">
  <a href="#" class="close"><i class="icon-bdg_cross"></i></a>
  <form id="simpan-komponen" class="form-horizontal">
    <div class="input-wrapper">
      <h5>Tambah Anggaran Kas Bulanan</h5>
  
      <input type="hidden" class="form-control" id="akb-id">    
      <input type="hidden" class="form-control" id="rekening-id">    

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Rekening</label>          
        <div class="col-md-6">
          <input type="text" class="form-control" placeholder="Rekening" id="nama-rekening" readonly="">        
        </div>
    </div>

    <div class="form-group">
        <label for="no_spp" class="col-md-3">Total Nominal</label>          
        <div class="col-md-6">
          <input type="text" class="form-control" placeholder="Total" id="total" readonly="">        
        </div>
    </div>

    <div class="form-group" id="koef1">
      <label for="no_spp" class="col-md-3">Januari</label>          
      <div class="col-sm-5">
        
        <input type="text" class="form-control" placeholder="Nominal Anggaran" id="januari" onkeypress="return event.charCode >= 48 && event.charCode <= 57" >        
      </div> 
      
    </div>

    <div class="form-group" id="koef2">
      <label for="no_spp" class="col-md-3">Februari</label>          
      <div class="col-sm-5">
        <input type="text" id="nominal2" class="form-control februari" onkeyup="SetNumber('nominal2')" onmouseout="SetNumber('nominal2')" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Masukan Harga">
      </div> 
      
    </div>     

    <div class="form-group" id="koef3">
      <label for="no_spp" class="col-md-3">Maret</label>          
      <div class="col-sm-5">
        <input type="text" id="nominal3" class="form-control maret" onkeyup="SetNumber('nominal3')" onmouseout="SetNumber('nominal3')" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Masukan Harga">
      </div> 
      
    </div>

    <div class="form-group" id="koef4">
      <label for="no_spp" class="col-md-3">April</label>          
      <div class="col-sm-5">
        <input type="text" id="nominal4" class="form-control april" onkeyup="SetNumber('nominal4')" onmouseout="SetNumber('nominal4')" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Masukan Harga">
      </div> 
      
    </div>     

    <div class="form-group" id="koef4">
      <label for="no_spp" class="col-md-3">Mei</label>          
      <div class="col-sm-5">
        <input type="text" id="nominal5" class="form-control mei" onkeyup="SetNumber('nominal5')" onmouseout="SetNumber('nominal5')" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Masukan Harga">
      </div> 
     
    </div>     

    <div class="form-group" id="koef4">
      <label for="no_spp" class="col-md-3">Juni</label>          
      <div class="col-sm-5">
        <input type="text" id="nominal6" class="form-control juni" onkeyup="SetNumber('nominal6')" onmouseout="SetNumber('nominal6')" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Masukan Harga">
      </div> 
      
    </div>     

    <div class="form-group" id="koef4">
      <label for="no_spp" class="col-md-3">Juli</label>          
      <div class="col-sm-5">
        <input type="text" id="nominal7" class="form-control juli" onkeyup="SetNumber('nominal7')" onmouseout="SetNumber('nominal7')" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Masukan Harga">
      </div> 
      
    </div>     

    <div class="form-group" id="koef4">
      <label for="no_spp" class="col-md-3">Agustus</label>          
      <div class="col-sm-5">
        <input type="text" id="nominal8" class="form-control agustus" onkeyup="SetNumber('nominal8')" onmouseout="SetNumber('nominal8')" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Masukan Harga">
      </div> 
     
    </div>     

    <div class="form-group" id="koef4">
      <label for="no_spp" class="col-md-3">September</label>          
      <div class="col-sm-5">
        <input type="text" id="nominal9" class="form-control september" onkeyup="SetNumber('nominal9')" onmouseout="SetNumber('nominal9')" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Masukan Harga">
      </div> 
      
    </div>    

    <div class="form-group" id="koef4">
      <label for="no_spp" class="col-md-3">Oktober</label>          
      <div class="col-sm-5">
        <input type="text" id="nominal10" class="form-control oktober" onkeyup="SetNumber('nominal10')" onmouseout="SetNumber('nominal10')" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Masukan Harga">      
      </div> 
      
    </div>      

    <div class="form-group" id="koef4">
      <label for="no_spp" class="col-md-3">November</label>          
      <div class="col-sm-5">
        <input type="text" id="nominal11" class="form-control november" onkeyup="SetNumber('nominal11')" onmouseout="SetNumber('nominal11')" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Masukan Harga">
      </div> 
     
    </div>     

    <div class="form-group" id="koef4">
      <label for="no_spp" class="col-md-3">Desember</label>          
      <div class="col-sm-5">
        <input type="text" id="nominal12" class="form-control desember" onkeyup="SetNumber('nominal12')" onmouseout="SetNumber('nominal12')" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Masukan Harga">     
      </div> 
     
    </div>     

    <hr class="m-t-xl">
   
    <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanKomponen()" ><i class="fa fa-plus m-r-xs "></i>Tambah Anggaran Kas Bulanan</a>
  </div>
</form>
</div> -->


<div class="bg-white wrapper-lg input-sidebar input-btl">
<a href="#" class="tutup-form"><i class="icon-bdg_cross"></i></a>
    <form id="form-urusan" class="form-horizontal">
      <div class="input-wrapper">
        <h5 id="judul-form">Tambah Anggaran Kas Bulanan</h5>
          <div class="form-group">
            <label for="kode_urusan" class="col-md-3">Kode Rekening</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Kode Rekening" name="kode_rek" id="kode_rek" value="" readonly="">          
              <input type="hidden" class="form-control" value="{{ csrf_token() }}" name="_token" id="token">       
              <input type="hidden" class="form-control" name="id_rek" id="id_rek">          
              <input type="hidden" class="form-control" name="total" id="total">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Nama Rekening</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Nama Rekening" name="nama_rek" id="nama_rek" value="" readonly="">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Total Nominal</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Total Nominal" name="total_view" id="total_view" value="" readonly="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Januari</label>
            <div class="col-sm-9">
               <input type="text" class="form-control" placeholder="Nominal" name="jan" id="jan" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value="" readonly="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Februari</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="feb" id="feb" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value="" readonly="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Maret</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="mar" id="mar" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value="" readonly="">            
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">April</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="apr" id="apr" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value="">        
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Mei</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="mei" id="mei" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value="">         
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Juni </label>
            <div class="col-sm-9">
             <input type="text" class="form-control" placeholder="Nominal" name="jun" id="jun" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Juli </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="jul" id="jul" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Agustus </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="agu" id="agu" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">September </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="sep" id="sep" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Oktober </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="okt" id="okt" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">November </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="nov" id="nov" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Desember </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="des" id="des" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>
         

          <hr class="m-t-xl">
         <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanAKB()"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
      </div>
    </form>
  </div>


</div>


</div>

@endsection

@section('plugin')


<script type="text/javascript">

  function simpanAKB(){
    //var akb_id    = '';
    var rek_id    = $('#id_rek').val();
    var jan       = $('#jan').val();
    var feb       = $('#feb').val();
    var mar       = $('#mar').val();
    var apr       = $('#apr').val();
    var mei       = $('#mei').val();
    var jun       = $('#jun').val();
    var jul       = $('#jul').val();
    var agu       = $('#agu').val();
    var sep       = $('#sep').val();
    var okt       = $('#okt').val();
    var nov       = $('#nov').val();
    var des       = $('#des').val();
    var total     = $('#total').val();
    var bl_id     = {{$bl->BL_ID}};
    var token     = $('#token').val();

    total_akb = parseInt(jan)+parseInt(feb)+parseInt(mar)+parseInt(apr)+parseInt(mei)+parseInt(jun)+parseInt(jul)+parseInt(agu)+parseInt(sep)+parseInt(okt)+parseInt(nov)+parseInt(des);
   
   total = parseInt(total);
   selisih = total-total_akb;

    if(jan == "" || feb == "" || mar == "" || apr == "" || mei == "" || jun == "" || jul == "" || agu == "" || sep == "" || nov == "" || des == "" ){
      $.alert('Form harap diisi atau di nol kan!');
    }
    else{
      if(total != total_akb){
        uri = "";
        $.alert('total AKB yang di input tidak sesuai!');
        $.alert("Total Rek Belanja : <b>"+total+"</b>");
        $.alert("Total Input AKB : <b>"+total_akb+"</b>");
        $.alert("selisih : <b>"+selisih+"</b>");
      } 
      else uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/akb/ubah";
     // uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/akb/ubah";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token' : token,
              //'akb_id'  : akb_id, 
              'bl_id'   : bl_id, 
              'rek_id'  : rek_id, 
              'jan'     : jan, 
              'feb'     : feb, 
              'mar'     : mar, 
              'apr'     : apr, 
              'mei'     : mei, 
              'jun'     : jun, 
              'jul'     : jul, 
              'agu'     : agu, 
              'sep'     : sep, 
              'okt'     : okt, 
              'nov'     : nov, 
              'des'     : des, 
              'total'   : total, 
              'tahun'   : '{{$tahun}}', 
            },
        success: function(msg){
            if(msg == 1){
              $('#judul-form').text('Tambah AKB');        
              $('#jan').val('');
              $('#feb').val('');
              $('#mar').val('');
              $('#apr').val('');
              $('#mei').val('');
              $('#jun').val('');
              $('#jul').val('');
              $('#agu').val('');
              $('#sep').val('');
              $('#okt').val('');
              $('#nov').val('');
              $('#des').val('');           
              $('#total').val('');           
              $.alert({
                title:'Info',
                content: 'Data berhasil disimpan',
                autoClose: 'ok|1000',
                buttons: {
                    ok: function () {
                      $('.input-spp,.input-spp-langsung,.input-sidebar').animate({'right':'-1050px'},function(){
                        $('.overlay').fadeOut('fast');
                        $('.tabel-detail').DataTable().ajax.reload();
                      });                      
                    }
                }
              });
            }
            else{
              $.alert('Data gagal di input !');
            }
          }
        });
    }
  }

  function ubah(BL_ID, REKENING_ID){
    $('#judul-form').text('Ubah Anggaran Kas Bulanan');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/akb/detail/"+BL_ID+"/"+REKENING_ID,
      success : function (data) {
        console.log(data);
       // $('#id_akb').val(data['AKB_ID']);
        $('#nama_rek').val(data['REKENING_NAMA']);
        $('#id_rek').val(data['REKENING_ID']);
        $('#kode_rek').val(data['REKENING_KODE']);
        $('#total').val(data['TOTAL']);
        $('#total_view').val(data['TOTAL_VIEW']);
        $('#jan').val(data['AKB_JAN']);
        $('#feb').val(data['AKB_FEB']);
        $('#mar').val(data['AKB_MAR']);
        $('#apr').val(data['AKB_APR']);
        $('#mei').val(data['AKB_MEI']);
        $('#jun').val(data['AKB_JUN']);
        $('#jul').val(data['AKB_JUL']);
        $('#agu').val(data['AKB_AUG']);
        $('#sep').val(data['AKB_SEP']);
        $('#okt').val(data['AKB_OKT']);
        $('#nov').val(data['AKB_NOV']);
        $('#des').val(data['AKB_DES']);
      }
    });
    $('.overlay').fadeIn('fast',function(){
      $('.input-btl').animate({'right':'0'},"linear");  
      $("html, body").animate({ scrollTop: 0 }, "slow");
    });
  }

  $('a.tutup-form').click(function(){
        $('#judul-form').text('Tambah AKB');        
        $('#jan').val('');
        $('#feb').val('');
        $('#mar').val('');
        $('#apr').val('');
        $('#mei').val('');
        $('#jun').val('');
        $('#jul').val('');
        $('#agu').val('');
        $('#sep').val('');
        $('#okt').val('');
        $('#nov').val('');
        $('#des').val('');
        $('#total').val('');
  }); 


  function hapus(bl,rek){
    var token        = $('#token').val();    
    $.confirm({
      title: 'Hapus Data!',
      content: 'Yakin hapus data?',
      buttons: {
        Ya: {
          btnClass: 'btn-danger',
          action: function(){
            $.ajax({
              url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/akb/hapus",
              type: "POST",
              data: {'_token'         : token,
              'BL_ID'           : bl,
              'REKENING_ID'     : rek
              },
              success: function(msg){
                $('.tabel-detail').DataTable().ajax.reload();                          
                $.alert('Hapus Berhasil!');
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


</script>



@endsection