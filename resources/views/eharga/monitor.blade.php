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
            <li class="active"><i class="fa fa-angle-right"></i>Monitoring Usulan Komponen</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
              <div class="panel bg-white">
                <div class="wrapper-lg">
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
                </div>
                <!-- / main tab --> 
                <hr class="m-t-n-sm">
                  <div class="wrapper-lg m-t-n-xl">
                    <div class="row">
                      <div class="col-sm-3">
                        <select ui-jq="chosen" class="w-full" id="tipe">
                          <option value="x">Pilih Tipe</option>
                          <option value="1">Tambah Komponen</option>
                          <option value="2">Ubah Komponen</option>
                          <option value="3">Tambah Rekening</option>
                        </select>                        
                      </div>
                      <div class="col-sm-3">
                        <select ui-jq="chosen" class="w-full" id="jenis">
                          <option value="x">Pilih Jenis Komponen</option>
                          <option value="1">SSH</option>
                          <option value="2">HSPK</option>
                          <option value="3">ASB</option>
                        </select>                        
                      </div>
                      <div class="col-sm-3">
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
                      <div class="col-sm-3">
                        <select ui-jq="chosen" class="w-full" id="posisi">
                          <option value="x">Pilih Posisi</option>
                          <option value="0">Rencana</option>
                          <option value="1">Pengajuan</option>
                          <option value="2">Verifikasi</option>
                          <option value="3">Validasi</option>
                          <option value="4a">Surat</option>
                          <option value="4b">Disposisi 1</option>
                          <option value="5">Disposisi 2</option>
                          <option value="6">Disposisi 3</option>
                          <option value="7">Posting</option>
                          <option value="8">Ebudgeting</option>
                        </select>                        
                      </div>
                    </div>
                  </div>   
                <div class="tab-content tab-content-alt-1 bg-white m-t-n-md">
                  <div role="tabpanel" class="active tab-pane" id="tab-1">  
                    <div class="table-responsive dataTables_wrapper table-usulan">
                      <table ui-jq="dataTable" ui-options="{
                      sAjaxSource: '{{ url('/') }}/harga/{{$tahun}}/monitor/getData',
		      pagination: true,
		      serverside: true,
                      aoColumns: [
                      { mData: 'NO',class:'text-center'},
                      { mData: 'PD'},
                      { mData: 'TIPE'},
                      { mData: 'NAMA'},
                      { mData: 'REKENING'},
                      { mData: 'HARGA',class:'text-right'},
                      { mData: 'POSISI'},
                      ]}" class="table table-striped b-t b-b table-usulan" id="table-usulan">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th width="1%">Perangkat Daerah</th>
                          <th width="1%">Tipe</th>
                          <th>Nama Barang / Kategori / Spesifikasi</th>
                          <th>Rekening</th>
                          <th>Harga Usulan</th>
                          <th width="10%">Posisi</th>
                        </tr>
                        <tr>
                          <th colspan="7" class="th_search">
                            <i class="icon-bdg_search"></i>
                            <input type="search" class="table-search cari-usulan form-control b-none w-full" placeholder="Cari Usulan" aria-controls="DataTables_Table_0">
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
</div>
@endsection

@section('plugin')
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $("#app").trigger('click');
  });
</script>
<script type="text/javascript">
  $("#tipe").change(function(e, params){filter();});
  $("#jenis").change(function(e, params){filter();});
  $("#opd").change(function(e, params){filter();});
  $("#posisi").change(function(e, params){filter();});

  function filter(){
    tipe    = $('#tipe').val();
    jenis   = $('#jenis').val();
    opd     = $('#opd').val();
    posisi  = $('#posisi').val();
    $('#table-usulan').DataTable().destroy();
    $('#table-usulan').DataTable({
      pagination: true,
      serverside: true,
      sAjaxSource: "{{ url('/') }}/harga/{{$tahun}}/monitor/getData/"+tipe+"/"+jenis+"/"+opd+"/"+posisi,
      aoColumns: [
        { mData: 'NO',class:'text-center'},
        { mData: 'PD'},
        { mData: 'TIPE'},
        { mData: 'NAMA'},
        { mData: 'REKENING'},
        { mData: 'HARGA',class:'text-right'},
        { mData: 'POSISI'}
      ]
    });
  }
</script>
@endsection
