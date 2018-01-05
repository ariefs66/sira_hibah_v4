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
                @if($log_r == 1)
                <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rka/log/{{ $bl->BL_ID }}" class="btn btn-danger pull-right m-t-n-sm" target="_blank"><i class="fa fa-download"></i> Log RKA</a> &nbsp;
                <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rka/sebelum/{{ $bl->BL_ID }}" class="btn btn-success pull-right m-t-n-sm" target="_blank"><i class="fa fa-print"></i> RKA Sebelum</a>
                @endif
                <div class="dropdown dropdown-blend pull-right m-t-n-sm">
                  <button class="btn btn-success dropdown-toggle " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Cetak DPA <i class="fa fa-chevron-down"></i>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/dpa/skpd/{{ $bl->subunit->SKPD_ID }}" target="_blank">DPA-SKPD </a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/dpa/skpd1/{{ $bl->subunit->SKPD_ID }}" target="_blank">DPA-SKPD 1</a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/dpa/skpd21/{{ $bl->subunit->SKPD_ID }}" target="_blank">DPA-SKPD 2.1</a></li>
                    <li><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/dpa/skpd22/{{ $bl->subunit->SKPD_ID }}" target="_blank">DPA-SKPD 2.2</a></li>
                    <li><a>DPA-SKPD 2.2.1</a></li>
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
                <a class="pull-right btn m-t-n-sm btn-warning" href="{{url('/')}}/main/{{$tahun}}/{{$status}}/belanja-langsung/detail/arsip/{{$BL_ID}}" target="_blank"><i class="fa fa-archive"></i></a>
                 <button class="pull-right btn m-t-n-sm btn-success open-form-btl"><i class="m-r-xs fa fa-plus"></i> Tambah AKB</button>
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
               }" class="table table-striped b-t b-b tabel-detail">
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
              <input type="hidden" class="form-control" name="id_akb" id="id_akb">          
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
              <input type="text" class="form-control" placeholder="Total Nominal" name="total" id="total" value="" readonly="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Januari</label>
            <div class="col-sm-9">
               <input type="text" class="form-control" placeholder="Nominal" name="jan" id="jan" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Februari</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="feb" id="feb" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Maret</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="mar" id="mar" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value="">            
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
              <input type="hidden" class="form-control" name="id_akb" id="id_akb">          
            </div> 
          </div>

          <div class="form-group">
            <label class="col-sm-3">Rekening</label>
            <div class="col-sm-9">
              <select ui-jq="chosen" class="w-full" id="jenis-pekerjaan" required="">
                <option value="">Silahkan Pilih Rek</option>
                @foreach($rincian_rek as $rrek)
                <option value="{{ $rrek->REKENING_ID }}">{{ $rrek->REKENING_KODE }}-{{ $rrek->REKENING_NAMA }}</option>
                @endforeach
              </select>
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
              <input type="text" class="form-control" placeholder="Total Nominal" name="total" id="total" value="" readonly="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Januari</label>
            <div class="col-sm-9">
               <input type="text" class="form-control" placeholder="Nominal" name="jan" id="jan" value="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Februari</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="feb" id="feb" value="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Maret</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="mar" id="mar" value="">            
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">April</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="apr" id="apr"   value="">        
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Mei</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="mei" id="mei"  value="">         
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Juni </label>
            <div class="col-sm-9">
             <input type="text" class="form-control" placeholder="Nominal" name="jun" id="jun"   value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Juli </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="jul" id="jul"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Agustus </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="agu" id="agu"   value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">September </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="sep" id="sep"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Oktober </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="okt" id="okt"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">November </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="nov" id="nov"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Desember </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="des" id="des"   value=""> 
            </div> 
          </div>
         

          <hr class="m-t-xl">
         <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanAKB()"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
      </div>
    </form>
  </div>

</div>
<!-- 
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
</div> -->

</div>

@endsection

@section('plugin')


<script type="text/javascript">
  function simpanAKB(){
    var akb_id    = $('#id_akb').val();
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
    var bl_id     = {{$bl->BL_PAGU}};
    var token     = $('#token').val();
    //alert(pagu);
    if(rek_id == "" ){
      $.alert('Form harap diisi!');
    }else{
      if(akb_id == '') uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/akb/simpan";
      else uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/akb/ubah";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token' : token,
              'akb_id'  : akb_id, 
              'bl_id'   : bl_id, 
              'rek_id'  : rek_id, 
              'jan'     : jan, 
              'feb'     : feb, 
              'mar'     : mar, 
              'mei'     : mei, 
              'apr'     : apr, 
              'jun'     : jun, 
              'jul'     : jul, 
              'agu'     : agu, 
              'sep'     : sep, 
              'okt'     : okt, 
              'nov'     : nov, 
              'des'     : des, 
              'tahun'   : '{{$tahun}}' 
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
              $('.table').DataTable().ajax.reload();              
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
            }else{
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
        $('#id_akb').val(data['AKB_ID']);
        $('#nama_rek').val(data['REKENING_NAMA']);
        $('#id_rek').val(data['REKENING_ID']);
        $('#kode_rek').val(data['REKENING_KODE']);
        $('#total').val(data['TOTAL']);
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
  }); 


</script>


<!-- <script type="text/javascript">
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

  /*$("#kategori-belanja").change(function(e, params){
    var id  = $('#kategori-belanja').val();
    $('#rekening-belanja').find('option').remove().end().append('<option>Pilih Rekening</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rekening/"+id,
      success : function (data) {
        $('#rekening-belanja').append(data).trigger('chosen:updated');
      }
    });
  });*/

 /* $("#rekening-belanja").change(function(e, params){
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
  });*/

  /*$("#jenis-pekerjaan").change(function(e, params){
    var id  = $('#jenis-pekerjaan').val();
    if(id == '4' || id == '5'){
      $('#nama-komponen').attr('readonly',false);
      $('#ket-belanja').attr('readonly',true);
      $('#harga-free').removeClass('hide');
      $('#pilih-komponen').addClass('hide');
    };
  });*/

 /* $('.table-komponen').on('click','tbody > tr', function(){
    id    = $(this).children('td').eq(0).html();
    nama  = $(this).children('td').eq(1).html();
    sat   = $(this).children('td').eq(2).html();
    harga   = $(this).children('td').eq(3).html();
    $('#id-komponen').val(id);
    $('#harga-komponen').val(harga);
    $('#nama-komponen').val(nama);
    $('#satuan-1').find('option').remove().end().append('<option value="'+sat+'">'+sat+'</option>').trigger('chosen:updated');      
    $('#kode-komponen').modal('hide');
  });*/

  /*$('#btn-tambah-komponen').on('click', function(){
    if(!$('#koef3').hasClass('hide')){
      $('#koef4').removeClass('hide');
    }
    if(!$('#koef2').hasClass('hide')){
      $('#koef3').removeClass('hide');
    }
    if($('#koef2').hasClass('hide')){
      $('#koef2').removeClass('hide');
    }
  })*/


 /* $('#btn-validasi').on('click',function(){
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
    
  })*/
   function simpanSKPD(){
    var kode_skpd       = $('#kode_skpd').val();
    var nama_skpd       = $('#nama_skpd').val();
    var kepala_nip      = $('#kepala_nip').val();
    var kepala_skpd     = $('#kepala_skpd').val();
    var pangkat         = $('#pangkat').val();
    var bendahara_nip   = $('#bendahara_nip').val();
    var bendahara_skpd  = $('#bendahara_skpd').val();
    var alamat          = $('#alamat').val();
    var pagu            = $('#pagu').val();
    var id_skpd         = $('#id_skpd').val();
    //var pagu            = $('.pagu').val();
    var token           = $('#token').val();
    //alert(pagu);
    if(kode_skpd == "" || nama_skpd == "" || kepala_nip == "" || pangkat == "" || kepala_skpd == "" || bendahara_nip == "" || pagu == ""){
      $.alert('Form harap diisi!');
    }else{
      if(id_skpd == '') uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/skpd/add/submit";
      else uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/skpd/edit/submit";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'kode_skpd'       : kode_skpd, 
              'nama_skpd'       : nama_skpd, 
              'kepala_nip'      : kepala_nip, 
              'kepala_skpd'     : kepala_skpd, 
              'pangkat'         : pangkat, 
              'alamat'          : alamat, 
              'bendahara_nip'   : bendahara_nip, 
              'bendahara_skpd'  : bendahara_skpd, 
              'pagu'            : pagu,
              'tahun'           : '{{$tahun}}', 
              'id_skpd'         : id_skpd},
        success: function(msg){
            if(msg == 1){
              $('#judul-form').text('Tambah SKPD');        
              $('#kode_skpd').val('');
              $('#nama_skpd').val('');
              $('#kepala_nip').val('');
              $('#kepala_skpd').val('');
              $('#pangkat').val('');
              $('#alamat').val('');
              $('#bendahara_nip').val('');
              $('#bendahara_skpd').val('');
              $('#pagu').val('');
              $('.table').DataTable().ajax.reload();              
              $.alert({
                title:'Info',
                content: 'Data berhasil disimpan',
                autoClose: 'ok|1000',
                buttons: {
                    ok: function () {
                      $('.input-spp,.input-spp-langsung,.input-sidebar').animate({'right':'-1050px'},function(){
                        $('.overlay').fadeOut('fast');
                      });                      
                    }
                }
              });
            }else{
              $.alert('Data telah tersedia!');
            }
          }
        });
    }
  }


  function simpanKomponen(){
    var token           = $('#token').val();    
    var AKB_ID          = $('#akb-id').val();
    var REKENING_ID     = $('#rekening-id').val();
    var TOTAL           = $('#total').val();
    var JANUARI         = $('#januari').val();
    var FEBRUARI        = $('.februari').val();
    var MARET           = $('.maret').val();
    var APRIL           = $('.april').val();
    var MEI             = $('.mei').val();
    var JUNI            = $('.juni').val();
    var JULI            = $('.juli').val();
    var AGUSTUS         = $('.agustus').val();
    var SEPTEMBER       = $('.september').val();
    var OKTOBER         = $('.oktober').val();
    var NOVEMBER        = $('.november').val();
    var DESEMBER        = $('.desember').val();

    alert(JANUARI);
    alert(FEBRUARI);
    alert(MARET);
    alert(APRIL);


    if(REKENING_ID == "" || AKB_ID == "" ){
      $.alert('Form harap diisi!');
    }else{

          url = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsungz/akb/ubah";
        $.ajax({
          url: url,
          type: "POST",
          data: {'_token'         : token,
          'BL_ID'           : '{{ $BL_ID }}', 
          'AKB_ID'          : AKB_ID, 
          'REKENING_ID'     : REKENING_ID, 
          'JANUARI'         : JANUARI, 
          'FEBRUARI'        : FEBRUARI, 
          'MARET'           : MARET, 
          'APRIL'           : APRIL, 
          'MEI'             : MEI, 
          'JUNI'            : JUNI, 
          'JULI'            : JULI, 
          'AGUSTUS'         : AGUSTUS, 
          'SEPTEMBER'       : SEPTEMBER, 
          'OKTOBER'         : OKTOBER, 
          'NOVEMBER'        : NOVEMBER, 
          'DESEMBER'        : DESEMBER },
          
          success: function(msg){
            if(msg == 0){
              $.alert('Iput AKB Gagal');
            }else if(msg != 0){
              $('.input-rincian,.input-sidebar').animate({'right':'-1050px'},function(){
                $('.overlay').fadeOut('fast');
              });
              clearInterval(interval);
              $('.tabel-detail').DataTable().ajax.reload();
            }
            /*if(msg == 0){
              $.alert('Lebih Dari Pagu');
            }else if(msg == 99){
              $.alert('Volume Tidak Bisa Ditambahkan');
            }else if(msg == 98){
              $.alert('Rekening Kurang Dari Realisasi');
            }else if(msg != 0){
              if(PEKERJAAN_ID == '4' || PEKERJAAN_ID == '5') location.reload();
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
              $('#btn-validasi').removeClass('disabled');
            }*/
            
          }
        });
    /*  }
    }*/
    }
  }

  function ubah(BL_ID, REKENING_ID){
        $('#nama-rekening').val('');
        $('#akb-id').val('');
        $('#rekening-id').val('');
        $('#total').val('');
        $('#januari').val('');
        $('.februari').val('');
        $('.maret').val('');
        $('.april').val('');
        $('.mei').val('');
        $('.juni').val('');
        $('.juli').val('');
        $('.agustus').val('');
        $('.september').val('');
        $('.oktober').val('');
        $('.november').val('');
        $('.desember').val('');

    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/akb/detail/"+BL_ID+"/"+REKENING_ID,
      success : function (data) {
        console.log(data);
        $('#nama-rekening').val(data['REKENING_NAMA']);
        $('#akb-id').val(data['AKB_ID']);
        $('#rekening-id').val(data['REKENING_ID']);
        $('#total').val(data['TOTAL']);
        $('#januari').val(data['AKB_JAN']);
        $('.februari').val(data['AKB_FEB']);
        $('.maret').val(data['AKB_MAR']);
        $('.april').val(data['AKB_APR']);
        $('.mei').val(data['AKB_MEI']);
        $('.juni').val(data['AKB_JUN']);
        $('.juli').val(data['AKB_JUL']);
        $('.agustus').val(data['AKB_AUG']);
        $('.september').val(data['AKB_SEP']);
        $('.oktober').val(data['AKB_OKT']);
        $('.november').val(data['AKB_NOV']);
        $('.desember').val(data['AKB_DES']);
      }
    });
    $('.overlay').fadeIn('fast',function(){
      $('.input-rincian').animate({'right':'0'},"linear");  
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
        interval = setInterval(function(){getpagu();}, 1000);
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
        clearInterval(interval);
    }); 

    $('.close').click(function(){
        clearInterval(interval);
        $('.input-sidebar').animate({'right':'-1050px'},"linear",function(){
          $('.overlay').fadeOut('fast');
        });
    }); 

  function getpagu(){
    $.ajax({
        url: "{{ url('/') }}/main/{{$tahun}}/{{$status}}/belanja-langsung/rincian/pagu/getpagu/{{$bl->BL_ID}}",
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
          rincian   = msg['rincian'];
          pagu      = msg['pagu'];
          total1     = parseInt(vol1) * parseInt(harga);
          total2     = parseInt(vol2) * parseInt(harga);
          total3     = parseInt(vol3) * parseInt(harga);
          total4     = parseInt(vol4) * parseInt(harga);
          total      = total1 + total2 + total3 + total4;
          anggaran  = parseInt(rincian)+ parseInt(total);
          sisa      = parseInt(pagu) - parseInt(anggaran);
          console.log(rincian);
          console.log(total);
          console.log(pagu);
          $('#rincian-skpd').val(anggaran.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          $('#pagu-skpd').val(pagu.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          $('#sisa-skpd').val(sisa.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          $('#rincian-skpd_').val(anggaran);
          $('#pagu-skpd_').val(pagu);
          $('#sisa-skpd_').val(sisa);          
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
</script> -->

@endsection