@extends('eharga.layout')

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
            </a>   </li>
            <li><a href= "{{ url('/') }}/harga/{{$tahun}}">Eharga</a></li>
            <li class="active"><i class="fa fa-angle-right"></i>Usulan Komponen</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <!-- if((substr(Auth::user()->mod,3,1)==1) and Auth::user()->active == 20) -->
                  <div class="dropdown dropdown-blend pull-right m-t-n-sm">
                  <!--@if($tahun>=2020)-->
                    <button class="btn btn-default dropdown-toggle " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Tambah Usulan <i class="fa fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                      <li><a class="open-form-btl">Tambah Komponen</a></li>
                      <li><a class="open-ubah-komponen">Ubah Komponen</a></li>
                      <li><a class="open-tambah-rekening">Tambah Rekening</a></li>
                    </ul>
                    <!--@endif-->
                  </div>
                  <!-- endif -->
                  <h5 class="inline font-semibold text-orange m-n">Usulan Komponen</h5>
                  <div class="col-sm-1 pull-right m-t-n-sm">
                    <select class="form-control dtSelect" id="dtSelect">
                      <option value="10">10</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                      <option value="250">250</option>
                      <option value="500">500</option>
                      <option value="1000">1000</option>
                    </select>
                  </div>
                  <div class="col-sm-3 pull-right">
                    @if(substr(Auth::user()->mod,3,1) == 1 or Auth::user()->level == 2)
                    <select ui-jq="chosen" class="w-full" id="opd" disabled="">
                    @else
                    <select ui-jq="chosen" class="w-full" id="opd">
                    @endif                    
                      <option value="x" selected="">Pilih Perangkat Daerah</option>
                      @foreach($skpd as $opd)
                      <option value="{{ $opd->SKPD_ID }}">{{ $opd->SKPD_KODE }} - {{ $opd->SKPD_NAMA }}</option>
                      @endforeach
                    </select>
                  </div>                    
                </div>
                <!-- Main tab -->
                @if(substr(Auth::user()->mod,3,1)==1)
                <div class="nav-tabs-alt tabs-alt-1 b-t three-row" id="tab-jurnal" >
                @elseif(substr(Auth::user()->mod,6,1)==1 or substr(Auth::user()->mod,4,1)==1)
                <div class="nav-tabs-alt tabs-alt-1 b-t half-row" id="tab-jurnal" >
                @else
                <div>
                @endif
                      <ul class="nav nav-tabs" role="tablist">
                       <li class="active">
                        @if(substr(Auth::user()->mod,3,1)==1)
                        <a data-target="#tab-1" role="tab" data-toggle="tab"><i class="fa fa-envelope"></i>Rencana Usulan</a>
                        @elseif(substr(Auth::user()->mod,6,1)==1)
                        <a data-target="#tab-1" role="tab" data-toggle="tab"><i class="fa fa-envelope"></i>Deliver Usulan</a>
                        @elseif(substr(Auth::user()->mod,4,1)==1)
                        <a data-target="#tab-1" role="tab" data-toggle="tab"><i class="fa fa-envelope"></i>Verifikasi Usulan</a>
                        @endif
                      </li>
                      @if(Auth::user()->level != 2 and $jenis == 0)
                      <li>
                        @if(substr(Auth::user()->mod,3,1)==1)                  
                        <a data-target="#tab-2" role="tab" data-toggle="tab"><i class="fa fa-check"></i>Pengajuan Usulan</a>
                        @elseif(substr(Auth::user()->mod,6,1)==1)
                        <a data-target="#tab-2" role="tab" data-toggle="tab"><i class="fa fa-check"></i>Disposisi Usulan</a>
                        @elseif(substr(Auth::user()->mod,4,1)==1)
                        <a data-target="#tab-2" role="tab" data-toggle="tab"><i class="fa fa-check"></i>Posting Usulan</a>
                        @elseif(Auth::user()->level == 2)
                        <a data-target="#tab-2" role="tab" data-toggle="tab"><i class="fa fa-check"></i>Grouping Usulan</a>
                        @endif                    
                      </li>
                      @endif
                      @if(substr(Auth::user()->mod,3,1)==1 and $jenis == 0)
                      <li>
                        <a data-target="#tab-4" role="tab" data-toggle="tab"><i class="fa fa-print"></i>Cetak Usulan</a>
                      </li>                  
                      @endif
                    </ul>
                  </div>
                  <!-- / main tab -->    
                  <div class="tab-content tab-content-alt-1 bg-white">
                    <div role="tabpanel" class="active tab-pane" id="tab-1">  
                      <div class="table-responsive dataTables_wrapper table-usulan">
                      @if(Auth::user()->app != 4)              
                       <table ui-jq="dataTable" ui-options="{
                       processing: true,
                       serverSide: true,
                       sAjaxSource: '{{ url('/') }}/harga/{{$tahun}}/usulan/getData',
                       aoColumns: [
                       { mData: 'ID',class:'hide'}, 
                       { mData: 'CB',class:'text-center', bSortable: false},
                       { mData: 'PD'},
                       { mData: 'TIPE'},
                       { mData: 'KATEGORI'},
                       { mData: 'NAMA'},
                       { mData: 'REKENING'},
                       { mData: 'HARGAAWAL',class:'text-right'},
                       { mData: 'HARGA',class:'text-right'},
                       { mData: 'DD'},
                       { mData: 'OPSI', bSortable: false}
                       ]}" class="table table-striped b-t b-b table-usulan" id="table-usulan">
                       <thead>
                        <tr>
                          <th class="hide">ID</th> 

                          @if(substr(Auth::user()->mod, 3,1) == 1 or 
                          substr(Auth::user()->mod, 6,1) == 1)                   
                          <th>
                            <div class="form-group checkbox-remember">
                              <div class="checkbox m-b-lg m-t-none">
                                <label class="checkbox-inline i-checks">
                                  <input type="checkbox" id="cb-all"><i></i>
                                </label>
                              </div>
                            </div>
                          </th>
                          @else
                          <th>No</th>
                          @endif

                          <th width="1%">Perangkat Daerah</th>
                          <th width="1%">Tipe</th>
                          <th width="1%">Kategori</th>
                          <th>Nama Barang / Spesifikasi</th>
                          <th>Rekening</th>
                          <th>Harga Awal</th>
                          <th>Harga Usulan</th>
                          <th>Data Pendukung</th>
                          <th width="15%">Opsi</th>
                        </tr>
                        <tr>
                          <th class="hide"></th>
                          <th colspan="11" class="th_search">
                            <i class="icon-bdg_search"></i>
                            <input type="search" class="table-search cari-usulan form-control b-none w-full" placeholder="Cari Usulan" aria-controls="DataTables_Table_0">
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                    @endif
                  </div>
                  <div class="pull-right m-t-n-sm m-r-xs">

                    @if(substr(Auth::user()->mod, 6,1) == 1)
                    <button class="btn btn-success m-t-xl m-l-sm" onclick="return disposisi()"><i class="fa fa-check"></i> Proses</button>
                    @endif


                    @if(substr(Auth::user()->mod, 3,1) == 1)
                    <button class="btn btn-success m-t-xl" onclick="return datadukung()"><i class="fa fa-gear"></i> Set Data Dukung</button>
                    @endif
                  </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tab-2">  
                  <div class="table-responsive dataTables_wrapper table-usulan-terima">
                   @if(Auth::user()->app != 4)
                   <table ui-jq="dataTable" ui-options="{
                   sAjaxSource: '{{ url('/') }}/harga/{{$tahun}}/usulan/getData/valid',
                   aoColumns: [
                   { mData: 'ID',class:'hide'}, 
                   { mData: 'NO',class:'text-center'},
                   { mData: 'PD'},
                   { mData: 'TIPE'},
                   { mData: 'NAMA'},
                   { mData: 'REKENING'},
                   { mData: 'HARGAAWAL',class:'text-right'},
                   { mData: 'HARGA',class:'text-right'},
                   { mData: 'OPSI'}
                   ]}" class="table table-striped b-t b-b table-usulan-terima" id="table-usulan-terima">
                   <thead>
                    <tr>
                      <th class="hide">ID</th>
                      @if(Auth::user()->level == 2 or substr(Auth::user()->mod,4,1)==1)                   
                      <th>
                        <div class="form-group checkbox-remember">
                          <div class="checkbox m-b-lg m-t-none">
                            <label class="checkbox-inline i-checks">
                              <input type="checkbox" id="cb-all"><i></i>
                            </label>
                          </div>
                        </div>
                      </th>
                      @else
                      <th>No</th>
                      @endif                  
                      <th width="1%">Perangkat Daerah</th>
                      <th>Tipe</th>
                      <th>Nama Barang / Spesifikasi</th>
                      <th>Rekening</th>
                      <th>Harga Awal</th>
                      <th>Harga Usulan</th>
                      <th>Opsi</th>
                    </tr>
                    <tr>
                      <th class="hide"></th>
                      <th colspan="9" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="table-search cari-usulan form-control b-none w-full" placeholder="Cari Usulan" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                @endif
              </div>
              <div class="pull-right m-t-n-sm m-r-xs">
                @if(substr(Auth::user()->mod, 3,1) == 1)
                <button class="btn btn-success m-t-xl m-l-sm" onclick="return ajukan()"><i class="fa fa-check"></i> Ajukan</button>
                @endif
                @if(Auth::user()->level == 2)
                <button class="btn btn-success m-t-xl m-l-sm" onclick="return grouping()"><i class="fa fa-check"></i> Group</button>
                @endif
                @if(substr(Auth::user()->mod,4,1) == 1)
                <button class="btn btn-success m-t-xl m-l-sm" onclick="return posting()"><i class="fa fa-check"></i> Posting</button>
                @endif
              </div>
            </div>


            <div role="tabpanel" class="tab-pane" id="tab-4">  
              <div class="table-responsive dataTables_wrapper table-usulan-surat">
              @if(substr(Auth::user()->mod, 3,1) == 1)
               <table ui-jq="dataTable" ui-options="{
               sAjaxSource: '{{ url('/') }}/harga/{{$tahun}}/usulan/getData/surat',
               aoColumns: [
               { mData: 'ID',class:'hide'}, 
               { mData: 'NO',class:'text-center'},
               { mData: 'PD'},
               { mData: 'TIPE'},
               { mData: 'NAMA'},
               { mData: 'REKENING'},
               { mData: 'HARGA',class:'text-right'}
               ]}" class="table table-striped b-t b-b table-usulan-surat" id="table-usulan-surat">
               <thead>
                <tr>
                  <th class="hide">ID</th>
                  <th>
                    <div class="form-group checkbox-remember">
                      <div class="checkbox m-b-lg m-t-none">
                        <label class="checkbox-inline i-checks">
                          <input type="checkbox" id="cb-all_"><i></i>
                        </label>
                      </div>
                    </div>
                  </th>                
                  <th width="1%">Perangkat Daerah</th>
                  <th>Tipe</th>
                  <th>Nama Barang / Spesifikasi</th>
                  <th>Rekening</th>
                  <th>Harga</th>
                </tr>
                <tr>
                  <th class="hide"></th>
                  <th colspan="8" class="th_search">
                    <i class="icon-bdg_search"></i>
                    <input type="search" class="table-search cari-usulan form-control b-none w-full" placeholder="Cari Usulan" aria-controls="DataTables_Table_0">
                  </th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            @endif
          </div>
          <div class="pull-right m-t-n-sm m-r-xs">
            <button class="btn btn-success m-t-xl m-l-sm" onclick="return grouping()"><i class="fa fa-check"></i> Atur Lampiran</button>
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
<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-btl">
  <a class="close"><i class="icon-bdg_cross"></i></a>
  <form class="form-horizontal">
    <div class="input-wrapper">
      <h5>Tambah Usulan</h5>
      <div class="form-group">
        <label class="col-sm-3">Jenis Komponen</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="jeniskomponen" required="">
            <option value="">Silahkan Pilih Jenis</option>
            <option value="1">SSH</option>
            <option value="2">HSPK</option>
            <option value="3">ASB</option>
          </select>
        </div>
      </div>
      {{-- <div class="form-group">
        <label class="col-sm-3">Kategori Usulan</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori1" required="">
            <option value="">Silahkan Pilih Kategori 1</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori2" required="">
            <option value="">Silahkan Pilih Kategori 2</option>
          </select>
        </div>
      </div> --}}
      {{-- <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori3" required="">
            <option value="">Silahkan Pilih Kategori 3</option>
          </select>
        </div>
      </div> --}}
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori4" required="">
            <option value="">Silahkan Pilih Kategori 4</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori5" required="">
            <option value="">Silahkan Pilih Kategori 5</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3">Rekening</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="rekening" required="">
            <option value="">Silahkan Pilih Rekening</option>
            @foreach($rekening as $rek)
            <option value="{{ $rek->REKENING_ID }}">{{ $rek->REKENING_KODE }} - {{ $rek->REKENING_NAMA }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Komponen</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Masukan Nama Komponen" id="komponen-nama" required="">          
        </div>
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Satuan</label>          
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="satuan" required="">
            <option value="">Silahkan Pilih Satuan</option>
            @foreach($satuan as $s)
            <option value="{{ $s->SATUAN_NAMA }}">{{ $s->SATUAN_NAMA }}</option>
            @endforeach
          </select>
        </div> 
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Spesifikasi</label>          
        <div class="col-sm-9">
          <textarea class="form-control" placeholder="Masukan Spesifikasi" id="spesifikasi"></textarea>
        </div> 
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Harga</label>          
        <div class="col-sm-9">
          <input type="number" class="form-control" placeholder="Masukan Harga" id="harga" required="">  
        </div> 
      </div>
      
      @if(substr(Auth::user()->mod,4,1) == 1)
      <div class="form-group" id="tgl">
        <label for="no_spp" class="col-md-3">Tanggal</label>          
        <div class="col-sm-9">
          <input type="date" class="form-control" placeholder="Masukan Tanggal" id="tanggal">          
        </div> 
      </div>
      @endif

      <hr class="m-t-xl">
      <input type="hidden" id="id-usulan">      
      <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanUsulan()" id="simpanUsulan"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
    </div>
  </form>
</div>
</div>

<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-ubah-komponen">
  <a class="close"><i class="icon-bdg_cross"></i></a>
  <form class="form-horizontal">
    <div class="input-wrapper">
      <h5>Ubah Komponen</h5>
      <div class="form-group">
        <label class="col-sm-3">Jenis Komponen</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="jeniskomponen_" required="">
            <option value="">Silahkan Pilih Jenis</option>
            <option value="1">SSH</option>
            <option value="2">HSPK</option>
            <option value="3">ASB</option>
          </select>
        </div>
      </div>
      {{-- <div class="form-group">
        <label class="col-sm-3">Kategori Usulan</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori1_" required="">
            <option value="">Silahkan Pilih Kategori 1</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori2_" required="">
            <option value="">Silahkan Pilih Kategori 2</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori3_" required="">
            <option value="">Silahkan Pilih Kategori 3</option>
          </select>
        </div>
      </div> --}}
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori4_" required="">
            <option value="">Silahkan Pilih Kategori 4</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori5_" required="">
            <option value="">Silahkan Pilih Kategori 5</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori6_" required="">
            <option value="">Silahkan Pilih Komponen</option>
          </select>
        </div>
      </div>


      <div class="form-group">
        <label for="no_spp" class="col-md-3">Komponen</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Masukan Nama Komponen" id="komponen-nama_" required="" readonly="">
          <input type="hidden" class="form-control" placeholder="Masukan Nama Komponen" id="idkomponen_" required="" readonly="">
          <input type="hidden" class="form-control" placeholder="Masukan Nama Komponen" id="idusulan_" required="" readonly="">
        </div>
      </div>


      <div class="form-group">
        <label for="no_spp" class="col-md-3">Spesifikasi</label>          
        <div class="col-sm-9">
          <textarea class="form-control" placeholder="Masukan Spesifikasi" id="spesifikasi_" readonly=""></textarea>
        </div> 
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Harga Awal</label>          
        <div class="col-sm-9">
          <input type="number" class="form-control" placeholder="Masukan Harga" id="hargaawal_" required="" readonly="">          
        </div> 
      </div>
      <div class="form-group">
        <label for="no_spp" class="col-md-3">Harga Usulan</label>          
        <div class="col-sm-9">
          <input type="number" class="form-control" placeholder="Masukan Harga" id="harga_" required="">          
        </div> 
      </div>
      <hr class="m-t-xl">
      <input type="hidden" id="id-usulan">      
      <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanUbahUsulan()" id="simpanUbahUsulan"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
    </div>
  </form>
</div>
</div>

<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-tambah-rekening">
  <a class="close"><i class="icon-bdg_cross"></i></a>
  <form class="form-horizontal">
    <div class="input-wrapper">
      <h5>Tambah Rekening</h5>
      <div class="form-group">
        <label class="col-sm-3">Jenis Komponen</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="jeniskomponen__" required="">
            <option value="">Silahkan Pilih Jenis</option>
            <option value="1">SSH</option>
            <option value="2">HSPK</option>
            <option value="3">ASB</option>
          </select>
        </div>
      </div>
     {{--  <div class="form-group">
        <label class="col-sm-3">Kategori Usulan</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori1__" required="">
            <option value="">Silahkan Pilih Kategori 1</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori2__" required="">
            <option value="">Silahkan Pilih Kategori 2</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori3__" required="">
            <option value="">Silahkan Pilih Kategori 3</option>
          </select>
        </div>
      </div> --}}
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori4__" required="">
            <option value="">Silahkan Pilih Kategori 4</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori5__" required="">
            <option value="">Silahkan Pilih Kategori 5</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori6__" required="">
            <option value="">Silahkan Pilih Komponen</option>
          </select>
        </div>
      </div>


      <div class="form-group">
        <label for="no_spp" class="col-md-3">Komponen</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Masukan Nama Komponen" id="komponen-nama__" required="" readonly="">
          <input type="hidden" class="form-control" placeholder="Masukan Nama Komponen" id="idkomponen__" required="" readonly="">
          <input type="hidden" class="form-control" placeholder="Masukan Nama Komponen" id="idusulan__" required="" readonly="">
        </div>
      </div>


      <div class="form-group">
        <label for="no_spp" class="col-md-3">Spesifikasi</label>          
        <div class="col-sm-9">
          <textarea class="form-control" placeholder="Masukan Spesifikasi" id="spesifikasi__" readonly=""></textarea>
        </div> 
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Rekening</label>          
        <div class="col-sm-9">
          <textarea class="form-control" placeholder="Masukan Rekening" id="rekeningawal__" required="" readonly=""></textarea>
        </div> 
      </div>

      <div class="form-group">
        <label class="col-sm-3">Rekening</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="rekening__" required="">
            <option value="">Silahkan Pilih Rekening</option>
            @foreach($rekening as $rek)
            <option value="{{ $rek->REKENING_ID }}">{{ $rek->REKENING_KODE }} - {{ $rek->REKENING_NAMA }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <hr class="m-t-xl">
      <input type="hidden" id="id-usulan">      
      <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanTambahRekening()" id="simpanTambahRekening"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
    </div>
  </form>
</div>
</div>


<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-dd">
  <a class="close"><i class="icon-bdg_cross"></i></a>
  <form action="{{url('/')}}/harga/{{$tahun}}/usulan/submitDD" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="input-wrapper">
      <h5>Tambah Data Dukung</h5>
      <div class="form-group">
        <label for="no_spp" class="col-md-3">Nama</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Masukan Nama Data Dukung" id="dd-nama" name="dd-nama">          
        </div> 
      </div>
      <div class="form-group">
        <label for="no_spp" class="col-md-3">Upload</label>          
        <div class="col-sm-9">
          <input type="file" class="form-control" id="dd-file" name="dd-file" required="">          
        </div> 
      </div>
      <hr class="m-t-xl">
      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">      
      <button type="submit" class="btn input-xl m-t-md btn-success pull-right"><i class="fa fa-plus m-r-xs "></i>Simpan</button>
    </div>
  </form>
</div>

<div class="modal fade" id="bdg-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h5 class="modal-title font-semibold text text-orange text17"><i class="fa fa-check"></i> Disposisi</h5>
      </div>
      <div class="modal-body">
        <div class="row text14">
          <div class="col-sm-2">Staff</div>
          <div class="col-sm-10 m-t-n-sm">
            <select ui-jq="chosen" class="w-full" id="disposisi-staff" required="">
              <option value="0" selected="">Silahkan Pilih Staff</option>
              @foreach($user as $u)
              <option value="{{ $u->id }}">{{ $u->email }} - {{ $u->name }}</option>
              @endforeach
            </select>
          </div> 
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="return accMultiple()"><i class="fa fa-check"></i> Deliver</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="bdg-modal-tolak" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h5 class="modal-title font-semibold text text-orange text17"><i class="fa fa-envelope"></i> Keterangan</h5>
      </div>
      <div class="modal-body">
        <div class="row text14">
          <div class="col-sm-2">Keterangan</div>
          <div class="col-sm-10">
            <input type="hidden" id="id-usulan-tolak">
            <!-- <select class="form-control" id="alasan">
              <option value="Harga Terlalu Tinggi">Harga Terlalu Tinggi</option>
              <option value="Harga Terlalu Tinggi">Harga Terlalu Rendah</option>
            </select> -->
            <input type="text" id="alasan" class="form-control" placeholder="Alasan">
          </div> 
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="return submitAlasan()"><i class="fa fa-close"></i> Tolak</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="bdg-modal-surat" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h5 class="modal-title font-semibold text text-orange text17"><i class="fa fa-envelope"></i> No Surat</h5>
      </div>
      <div class="modal-body">
        <div class="row text14">
          <div class="col-sm-2">No Surat</div>
          <div class="col-sm-10">
            <input type="text" id="no-surat" class="form-control w-full">
          </div> 
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="return submitGroup()"><i class="fa fa-check"></i> Group</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="bdg-modal-alasan" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h5 class="modal-title font-semibold text text-orange text17"><i class="fa fa-envelope"></i> Alasan</h5>
      </div>
      <div class="modal-body">
        <div class="row text14">
          <div class="col-sm-2">Alasan</div>
          <div class="col-sm-10">
            <input type="hidden" id="id-usulan-tolak">
            <textarea class="form-control" id="alasanshow" readonly></textarea>
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="bdg-modal-dd" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h5 class="modal-title font-semibold text text-orange text17"><i class="fa fa-plus"></i> Data Dukung</h5>
      </div>
      <div class="modal-body">
        <div class="row text14">
          <div class="col-sm-3">Data Dukung</div>
          <div class="col-sm-9 m-t-n-sm">
            <select ui-jq="chosen" class="w-full" id="dd-id" required="">
              <option value="0" selected="">Silahkan Pilih Data Dukung</option>
              @foreach($datadukung as $dd)
              <option value="{{ $dd->DD_ID }}">{{ $dd->DD_NAMA }}</option>
              @endforeach
            </select>
          </div> 
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning open-dd pull-left" data-dismiss="modal"><i class="fa fa-plus"></i> Tambah Data Pendukung</button>
        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="return submitDD()"><i class="fa fa-check"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="bdg-modal-suggest" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white modal-lg">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Suggest</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-popup table-striped b-t b-b table-suggest" id="table-suggest">
          <thead>
            <tr>
              <th>No</th>
              <th>Komponen</th>
              <th>Spesifikai</th>
              <th>Harga</th>                          
            </tr>
            <tr>
              <th colspan="4" class="th_search">
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
@endsection

@section('plugin')
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="{{ url('/') }}/assets/js/jquery.ui.autocomplete.scroll.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.11.0/js/bootstrap-tour.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $("#app").trigger('click');
    $( function() {
    $( "#komponen-nama" ).autocomplete({
      source: function( request, response ) {
        $.ajax( {
          url: "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKomponen/",
          data: {
            term: request.term
          },
          success: function( data ) {
            response( data );
          }
        } );
      },
      minLength: 1,
      maxShowItems: 5,
      select: function( event, ui ) {
        $( "#spesifikasi" ).val(ui.item.spek);
        $( "#satuan" ).val(ui.item.satuan).trigger('chosen:updated');
        $( "#harga" ).val(ui.item.harga);
      }
    });
  } );
  });
  $(function(){
    var tour = new Tour({
      steps: [
      {
        element: "#tahun-anggaran",
        title: "Tahun Anggaran",
        content: "Pilih Tahun Untuk Merubah Tahun Anggaran",
        backdrop : true
      }
    ]});
    tour.init();
    tour.start();
  })
</script>
<script type="text/javascript">
  $("#opd").change(function(e, params){filter();});
  $("#jeniskomponen").change(function(e, params){
    getKategori4();
  });

  $("#kategori1").change(function(e, params){
    getKategori2();
  });

  $("#kategori2").change(function(e, params){
    getKategori3();
  });
  
  $("#kategori3").change(function(e, params){
    getKategori4();
  });
  $("#kategori4").change(function(e, params){
    getKategori5();
  });

  $("#kategori5").change(function(e, params){
    var id  = $('#kategori5').val();
    /*
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getSuggest/"+id,
      success : function (data) {
        $("#komponen-nama").autocomplete({source: data});
      }
    });
    */
  });

  $("#jeniskomponen_").change(function(e, params){
    getKategori4_();
  });

  $("#kategori1_").change(function(e, params){
    getKategori2_();
  });

  $("#kategori2_").change(function(e, params){
    getKategori3_();
  });
  
  $("#kategori3_").change(function(e, params){
    getKategori4_();
  });
  $("#kategori4_").change(function(e, params){
    getKategori5_();
  });
  $("#kategori5_").change(function(e, params){
    var id  = $('#kategori5_').val();
    $('#kategori6_').find('option').remove().end().append('<option>Silahkan Pilih Komponen</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori6_').append(data).trigger('chosen:updated');
      }
    });
  });

  $('#kategori6_').change(function(e,params){
    var id  = $('#kategori6_').val();
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getUbah/"+id,
      success : function (data) {
        $('#komponen-nama_').val(data['KOMPONEN_NAMA']);
        $('#spesifikasi_').val(data['KOMPONEN_SPESIFIKASI']);
        $('#hargaawal_').val(data['KOMPONEN_HARGA']);
        $('#idkomponen_').val(data['KOMPONEN_ID']);
      }
    });
  });

  $("#jeniskomponen__").change(function(e, params){
    getKategori4__();
  });

  $("#kategori1__").change(function(e, params){
    getKategori2__();
  });

  $("#kategori2__").change(function(e, params){
    getKategori3__();
  });
  
  $("#kategori3__").change(function(e, params){
    getKategori4__();
  });
  $("#kategori4__").change(function(e, params){
    getKategori5__();
  });
  $("#kategori5__").change(function(e, params){
    var id  = $('#kategori5__').val();
    $('#kategori6__').find('option').remove().end().append('<option>Silahkan Pilih Komponen</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori6__').append(data).trigger('chosen:updated');
      }
    });
  });

  $('#kategori6__').change(function(e,params){
    var id  = $('#kategori6__').val();
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getUbah/"+id,
      success : function (data) {
        $('#komponen-nama__').val(data['KOMPONEN_NAMA']);
        $('#spesifikasi__').val(data['KOMPONEN_SPESIFIKASI']);
        $('#rekeningawal__').val(data['REKENING'])
        $('#idkomponen__').val(data['KOMPONEN_ID']);
      }
    });
  });

  $("#cb-all").change(function(e, params){
    if($('#cb-all').is(':checked')) $('.cb').prop('checked', true);
    else $('.cb').prop('checked', false);
  });

  $("#cb-all_").change(function(e, params){
    if($('#cb-all_').is(':checked')) $('.cb_').prop('checked', true);
    else $('.cb_').prop('checked', false);
  });


  function disposisi(){
    $('#bdg-modal').modal('show');
  }

  function tolak(id){
    $('#id-usulan-tolak').val(id);
    $('#bdg-modal-tolak').modal('show');
  }

  function showAlasan(id){
    $('#bdg-modal-alasan').modal('show');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getAlasan/"+id,
      success : function (data) {
        $('#alasanshow').val(data['USULAN_ALASAN']);
      }
    }); 
  }

  function datadukung(){
    $('#bdg-modal-dd').modal('show');
  }

  function submitAlasan(){
    $.ajax({
      url: "{{ url('/') }}//harga/{{$tahun}}/usulan/submitAlasan",
      type: "POST",
      data: {'_token'           : $('#token').val(),
      'ALASAN'            : $('#alasan').val(),
      'USULAN_ID'         : $('#id-usulan-tolak').val()},
      success: function(msg){
        $('#table-usulan').DataTable().ajax.reload();
        $.alert(msg);
      }
    });
  }

  function getKategori1(){
    var id  = $('#jeniskomponen').val();
    $('#kategori1').find('option').remove().end().append('<option>Silahkan Pilih Kategori 1</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori/"+id,
      success : function (data) {
        $('#kategori1').append(data).trigger('chosen:updated');
      }
    });    
  }

  function getKategori2(){
    var id  = $('#kategori1').val();
    $('#kategori2').find('option').remove().end().append('<option>Silahkan Pilih Kategori 2</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori/"+id,
      success : function (data) {
        $('#kategori2').append(data).trigger('chosen:updated');
      }
    });    
  }

  function getKategori3(){
    var id  = $('#kategori2').val();
    $('#kategori3').find('option').remove().end().append('<option>Silahkan Pilih Kategori 3</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori/"+id,
      success : function (data) {
        $('#kategori3').append(data).trigger('chosen:updated');
      }
    });
  }

  function getKategori4(){
    var id  = $('#jeniskomponen').val();
    // var id  = $('#kategori3').val();
    $('#kategori4').find('option').remove().end().append('<option>Silahkan Pilih Kategori 4</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori/"+id,
      success : function (data) {
        $('#kategori4').append(data).trigger('chosen:updated');
      }
    });
  }

  function getKategori5(){
    var id  = $('#kategori4').val();
    $('#kategori5').find('option').remove().end().append('<option>Silahkan Pilih Kategori 5</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori/"+id,
      success : function (data) {
        $('#kategori5').append(data).trigger('chosen:updated');
      }
    });
  }

  function getKategori1_(){
    var id  = $('#jeniskomponen_').val();
    $('#kategori1_').find('option').remove().end().append('<option>Silahkan Pilih Kategori 1</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori1_').append(data).trigger('chosen:updated');
      }
    });    
  }

  function getKategori2_(){
    var id  = $('#kategori1_').val();
    $('#kategori2_').find('option').remove().end().append('<option>Silahkan Pilih Kategori 2</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori2_').append(data).trigger('chosen:updated');
      }
    });    
  }

  function getKategori3_(){
    var id  = $('#kategori2_').val();
    $('#kategori3_').find('option').remove().end().append('<option>Silahkan Pilih Kategori 3</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori3_').append(data).trigger('chosen:updated');
      }
    });
  }

  function getKategori4_(){
    // var id  = $('#kategori3_').val();
    var id  = $('#jeniskomponen_').val();
    $('#kategori4_').find('option').remove().end().append('<option>Silahkan Pilih Kategori 4</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori4_').append(data).trigger('chosen:updated');
      }
    });
  }

  function getKategori5_(){
    var id  = $('#kategori4_').val();
    $('#kategori5_').find('option').remove().end().append('<option>Silahkan Pilih Kategori 5</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori5_').append(data).trigger('chosen:updated');
      }
    });
  }
  function getKategori1__(){
    var id  = $('#jeniskomponen__').val();
    $('#kategori1__').find('option').remove().end().append('<option>Silahkan Pilih Kategori 1</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori1__').append(data).trigger('chosen:updated');
      }
    });    
  }

  function getKategori2__(){
    var id  = $('#kategori1__').val();
    $('#kategori2__').find('option').remove().end().append('<option>Silahkan Pilih Kategori 2</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori2__').append(data).trigger('chosen:updated');
      }
    });    
  }

  function getKategori3__(){
    var id  = $('#kategori2__').val();
    $('#kategori3__').find('option').remove().end().append('<option>Silahkan Pilih Kategori 3</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori3__').append(data).trigger('chosen:updated');
      }
    });
  }

  function getKategori4__(){
    // var id  = $('#kategori3__').val();
    var id  = $('#jeniskomponen__').val();
    $('#kategori4__').find('option').remove().end().append('<option>Silahkan Pilih Kategori 4</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori4__').append(data).trigger('chosen:updated');
      }
    });
  }

  function getKategori5__(){
    var id  = $('#kategori4__').val();
    $('#kategori5__').find('option').remove().end().append('<option>Silahkan Pilih Kategori 5</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori5__').append(data).trigger('chosen:updated');
      }
    });
  }

  function simpanUsulan(){
    kategori5   = $('#kategori5').val();
    komponen    = $('#komponen-nama').val();
    satuan      = $('#satuan').val();
    harga       = $('#harga').val();
    spesifikasi = $('#spesifikasi').val();
    token       = $('#token').val();
    rekening    = $('#rekening').val();
    idusulan    = $('#id-usulan').val();
    tanggal     = $('#tanggal').val();
    if(kategori5 == "" || komponen == "" || satuan == "" || harga == "" || rekening == ""){
      $.alert('Form harap diisi!');
    }else{
      $.ajax({
        url: "{{ url('/') }}//harga/{{$tahun}}/usulan/submitUsulan",
        type: "POST",
        data: {'_token'           : token,
        'REKENING_ID'       : rekening, 
        'KATEGORI_ID'       : kategori5,
        'USULAN_NAMA'       : komponen, 
        'USULAN_SPESIFIKASI': spesifikasi, 
        'USULAN_SATUAN'     : satuan,
        'USULAN_ID'         : idusulan,
        'USULAN_TANGGAL'    : tanggal,
        'USULAN_HARGA'      : harga},
        success: function(msg){
          $('#table-usulan').DataTable().ajax.reload();
          $('#table-usulan-terima').DataTable().ajax.reload();
          $.alert(msg);
          $('.input-btl,.input-sidebar').animate({'right':'-1050px'},function(){
            $('.overlay').fadeOut('fast');
          });
        }
      });
    }
  }

  function simpanUbahUsulan(){
    idkomponen  = $('#idkomponen_').val();
    idusulan    = $('#idusulan_').val();
    harga_      = $('#harga_').val();
    token       = $('#token').val();
    tanggal     = $('#tanggal').val();
    if(harga_ == ""){
      $.alert('Form harap diisi!');
    }else{
      $.ajax({
        url: "{{ url('/') }}//harga/{{$tahun}}/usulan/submitUsulanUbah",
        type: "POST",
        data: {'_token'             : token,
        'USULAN_ID'         : idusulan,
        'KOMPONEN_ID'       : idkomponen,
        'USULAN_TANGGAL'    : tanggal,
        'USULAN_HARGA'      : harga_},
        success: function(msg){
          $('#table-usulan').DataTable().ajax.reload();
          $('#table-usulan-terima').DataTable().ajax.reload();
          $.alert(msg);
          $('.input-btl,.input-sidebar').animate({'right':'-1050px'},function(){
            $('.overlay').fadeOut('fast');
          });
        }
      });
    }
  }

  function simpanTambahRekening(){
    idkomponen  = $('#idkomponen__').val();
    idusulan    = $('#idusulan__').val();
    token       = $('#token').val();
    rekening    = $('#rekening__').val();
    if(harga_ == ""){
      $.alert('Form harap diisi!');
    }else{
      $.ajax({
        url: "{{ url('/') }}//harga/{{$tahun}}/usulan/submitTambahRekening",
        type: "POST",
        data: {'_token'             : token,
        'USULAN_ID'         : idusulan,
        'KOMPONEN_ID'       : idkomponen,
        'REKENING_ID'       : rekening},
        success: function(msg){
          $('#table-usulan').DataTable().ajax.reload();
          $('#table-usulan-terima').DataTable().ajax.reload();
          $.alert(msg);
          $('.input-btl,.input-sidebar').animate({'right':'-1050px'},function(){
            $('.overlay').fadeOut('fast');
          });
        }
      });
    }
  }

  function actUsulan(status){
    token       = $('#token').val();
    id          = $('#id-usulan').val();
    if(id == ""){
      $.alert('Gagal Teirma Usulan!');
    }else{
      $.ajax({
        url: "{{ url('/') }}//harga/{{$tahun}}/usulan/actUsulan",
        type: "POST",
        data: {'_token'           : token,
        'USULAN_ID'         : id,
        'STATUS'            : status},
        success: function(msg){
          $('#table-usulan').DataTable().ajax.reload();
          $.alert(msg);
        }
      });

    }
  }


  function detail(id){
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{$tahun}}/usulan/getData/detail/"+id,
      success : function (data) {
        $('#simpanUsulan').text('Proses');
        $('#rekening').val(data['REKENING_ID']).trigger("chosen:updated");
        $('#satuan').val(data['SATUAN']).trigger("chosen:updated");
        $('#komponen-nama').val(data['KOMPONEN']);
        $('#spesifikasi').val(data['SPESIFIKASI']);
        $('#harga').val(data['HARGA']);
        $('#harga').attr('readonly');
        $('#id-usulan').val(data['ID']);
        $('#jeniskomponen').val(data['JENIS']).trigger("chosen:updated");
        getKategori1();
        $('#kategori1').append('<option value="'+data['K1']+'" selected>'+data['K1']+' - '+data['K1_NAMA']+'</option>').trigger('chosen:updated');
        getKategori2();
        $('#kategori2').append('<option value="'+data['K2']+'" selected>'+data['K2']+' - '+data['K2_NAMA']+'</option>').trigger('chosen:updated');
        getKategori3();
        $('#kategori3').append('<option value="'+data['K3']+'" selected>'+data['K3']+' - '+data['K3_NAMA']+'</option>').trigger('chosen:updated');
        getKategori4();
        $('#kategori4').append('<option value="'+data['K4']+'" selected>'+data['K4']+' - '+data['K4_NAMA']+'</option>').trigger('chosen:updated');
        getKategori5();
        $('#kategori5').append('<option value="'+data['K5']+'" selected>'+data['K5_KODE']+' - '+data['K5_NAMA']+'</option>').trigger('chosen:updated');
        if(data['K5_KODE'].substring(0,1) == 1) $('#tgl').addClass('hide');
        else $('#tgl').removeClass('hide')
        $('.overlay').fadeIn('fast',function(){
          $('.input-btl').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        }); 
      }
    });
  }

  function accMultiple(){
    token       = $('#token').val();
    staff       = $('#disposisi-staff').val();
    var val = [];
    $(':checkbox.cb:checked').each(function(i){
      val[i] = $(this).val();
    });
    $.ajax({
      url: "{{ url('/') }}//harga/{{$tahun}}/usulan/submitUsulanMultiple",
      type: "POST",
      data: {'_token'           : token,
      'STAFF'             : staff,
      'USULAN_ID'         : val},
      success: function(msg){
        refresh();
        $.alert(msg);
      }
    });
  }

  function decMultiple(){
    token       = $('#token').val();
    var val = [];
    $(':checkbox.cb:checked').each(function(i){
      val[i] = $(this).val();
    });
    $.ajax({
      url: "{{ url('/') }}//harga/{{$tahun}}/usulan/submitUsulanMultiple",
      type: "POST",
      data: {'_token'           : token,
      'STATUS'            : 2,
      'USULAN_ID'         : val},
      success: function(msg){
        refresh();
        $.alert(msg);
      }
    });
  }

  function posting(){
    token       = $('#token').val();
    var val = [];
    $(':checkbox.cb:checked').each(function(i){
      val[i] = $(this).val();
    });
    $.ajax({
      url: "{{ url('/') }}//harga/{{$tahun}}/usulan/posting",
      type: "POST",
      data: {'_token'           : token,
      'STATUS'            : 0,
      'USULAN_ID'         : val},
      success: function(msg){
        refresh();
        $.alert(msg);
      }
    });
  }

  function submitDD(){
    token       = $('#token').val();
    dd          = $('#dd-id').val();
    var val = [];
    $(':checkbox.cb:checked').each(function(i){
      val[i] = $(this).val();
    });
    if(dd == 0){
      $.alert('Pilih Data Dukung!');
    }else{
      $.ajax({
        url: "{{ url('/') }}/harga/{{$tahun}}/usulan/updateDD",
        type: "POST",
        data: {'_token'           : token,
        'STATUS'            : 0,
        'DD'                : dd,
        'USULAN_ID'         : val},
        success: function(msg){
          refresh();
          $.alert(msg);
        }
      });
    }
  }

  function ajukan(){
    $.ajax({
      url: "{{ url('/') }}/harga/{{$tahun}}/usulan/ajukan",
      type: "POST",
      data: {'_token'           : $('#token').val()},
      success: function(msg){
        refresh();
        $.alert(msg);
      }
    });
  }

  function refresh(){
    $('#table-usulan').DataTable().ajax.reload();
    $('#table-usulan-terima').DataTable().ajax.reload();
    $('#table-usulan-verifikasi').DataTable().ajax.reload();
    $('#table-usulan-validasi').DataTable().ajax.reload();    
    $('#table-usulan-disposisi1').DataTable().ajax.reload();    
    $('#table-usulan-disposisi2').DataTable().ajax.reload();    
    $('#table-usulan-disposisi3').DataTable().ajax.reload();    
    $('#table-usulan-posting').DataTable().ajax.reload();    
    $('#table-usulan-surat').DataTable().ajax.reload();    
  }

  function acc(id){
    token       = $('#token').val();
    $.ajax({
      url: "{{ url('/') }}/harga/{{$tahun}}/usulan/acc",
      type: "POST",
      data: {'_token'           : token,
      'USULAN_ID'         : id},
      success: function(msg){
        refresh();
        $.confirm({
          title     : msg,
          content     : msg,
          autoClose: 'ok|1000',
          buttons: {
              ok: function () {
              }
          }
        });
      }
    });
  }

  function grouping(){
    submitGroup();
  }

  function submitGroup(){
    token       = $('#token').val();
    var val = [];
    $(':checkbox.cb_:checked').each(function(i){
      val[i] = $(this).val();
    });
    $.ajax({
      url: "{{ url('/') }}/harga/{{$tahun}}/usulan/grouping",
      type: "POST",
      data: {'_token'           : token,
      'USULAN_ID'         : val},
      success: function(msg){
        // refresh();
        window.open('{{ url("/") }}/harga/{{$tahun}}/usulan/surat/'+msg);
        $.alert('Berhasil!');
      }
    });    
  }

  function showSuggest(id){
    $('#table-suggest').DataTable().destroy();
    $('#table-suggest').DataTable({
      sAjaxSource: "{{ url('/') }}/harga/{{ $tahun }}/usulan/getSuggest_/"+id,
      aoColumns: [
      { mData: 'NO' },
      { mData: 'NAMA', },
      { mData: 'SPESIFIKASI' },
      { mData: 'HARGA' }]
    });
    $('#bdg-modal-suggest').modal('show');
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
              url: "{{ url('/') }}/harga/{{$tahun}}/usulan/hapus",              
              type: "POST",
              data: {'_token'         : token,
                    'USULAN_ID'       : id},
              success: function(msg){
                refresh();
                $.alert('Hapus Berhasil!');
              }
            });
          }
        },
        Tidak: function () {
        }
      }
    });
  }

  function cancel(id){
    var token        = $('#token').val();    
    $.confirm({
      title: 'Hapus Data!',
      content: 'Yakin Kembalikan Data?',
      buttons: {
        Ya: {
          btnClass: 'btn-danger',
          action: function(){
            $.ajax({
              url: "{{ url('/') }}/harga/{{$tahun}}/usulan/cancel",              
              type: "POST",
              data: {'_token'         : token,
                    'USULAN_ID'       : id},
              success: function(msg){
                refresh();
                $.alert('Data dapat dikembalikan!');
              }
            });
          }
        },
        Tidak: function () {
        }
      }
    });
  }

  function filter(){
    opd     = $('#opd').val();
    $('#table-usulan').DataTable().destroy();
    $('#table-usulan').DataTable({
      processing: true,
      serverSide: true,
      sAjaxSource: '{{ url('/') }}/harga/{{$tahun}}/usulan/getData?skpd='+opd,
      aoColumns: [
      { mData: 'ID',class:'hide'}, 
      { mData: 'CB',class:'text-center', bSortable: false},
      { mData: 'PD'},
      { mData: 'TIPE'},
      { mData: 'KATEGORI'},
      { mData: 'NAMA'},
      { mData: 'REKENING'},
      { mData: 'HARGAAWAL',class:'text-right'},
      { mData: 'HARGA',class:'text-right'},
      { mData: 'DD'},
      { mData: 'OPSI', bSortable: false}
      ]
    });
  }
</script>
@endsection
