@extends('budgeting.layout')

@section('content')
<div id="content" class="app-content" role="main">
  <div class="hbox hbox-auto-xs hbox-auto-sm ng-scope">
    <div class="col">
      <div class="app-content-body ">

          <div class="bg-light lter">    
              <ul class="breadcrumb bg-white m-b-none">
                <li><a href="#" class="btn no-shadow" ui-toggle-class="app-aside-folded" target=".app">
                  <i class="icon-bdg_expand1 text"></i>
                  <i class="icon-bdg_expand2 text-active"></i>
                </a>   </li>
                <li><a href= "{{ url('/') }}/main">Dashboard</a></li>
                <li><i class="fa fa-angle-right"></i><a>Referensi</a></li>
                <li class="active"><i class="fa fa-angle-right"></i>Komponen</li>                                
              </ul>
          </div>

          <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                    <h5 class="inline font-semibold text-orange m-n ">Daftar Komponen Tahun {{ $tahun }}</h5>
                    <div class="col-sm-1 pull-right m-t-n-sm">
                      <select class="form-control dtSelect" id="dtSelect">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>                    
                  </div>    
                  <hr class="m-t-n-sm">
                  <div class="wrapper-lg m-t-n-xl">
                    <div class="row">
                      <div class="col-sm-3">
                        <select ui-jq="chosen" class="w-full" id="jenis">
                          <option>Pilih Jenis</option>
                          <option value="1">SSH</option>
                          <option value="2">HSPK</option>
                          <option value="3">ASB</option>
                        </select>                        
                      </div>
                      <div class="col-sm-3">
                        <select ui-jq="chosen" class="w-full" id="kategori1">
                          <option>Pilih Kategori 1</option>
                        </select>                        
                      </div>
                      <div class="col-sm-3">
                        <select ui-jq="chosen" class="w-full" id="kategori2">
                          <option>Pilih Kategori 2</option>
                        </select>                        
                      </div>
                      <div class="col-sm-3">
                        <select ui-jq="chosen" class="w-full" id="kategori3">
                          <option>Pilih Kategori 3</option>
                        </select>                        
                      </div>
                    </div>
                  </div>                         
                  <div class="tab-content tab-content-alt-1 bg-white m-t-n-md">
                        <div role="tabpanel" class="active tab-pane" id="tab-1">  
                            <div class="table-responsive dataTables_wrapper">
                             <table ui-jq="dataTable" ui-options="" class="table table-striped b-t b-b" id="table-komponen">
                                    <thead>
                                      <tr>
                                        <th width="1%">No</th>
                                        <th>Kode / Nama</th>
                                        <th>Spesifikasi</th>
                                        <th width="1%">Satuan</th>
                                        <th width="1%">Harga</th>
                                        <th width="15%">Aksi</th>
                                      </tr>
                                      <tr>
                                        <th colspan="6" class="th_search">
                                            <i class="icon-bdg_search"></i>
                                            <input type="search" class="table-search form-control b-none w-full" placeholder="Cari Komponen" aria-controls="DataTables_Table_0">
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


<div class="modal fade " id="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 " id="judul-modal"></h5>
      </div>
      <div class="table-responsive">
        <table class="table table-popup table-striped b-t b-b" id="table-modal">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Nama</th>                          
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
<script type="text/javascript">
  $("#jenis").change(function(e, params){
    var id  = $('#jenis').val();
    loadTable(id);
    $('#kategori1').find('option').remove().end().append('<option>Pilih Kategori 1</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori1').append(data).trigger('chosen:updated');
      }
    });
  });
  $("#kategori1").change(function(e, params) {
    var id  = $('#kategori1').val();
    loadTable(id);    
    $('#kategori2').find('option').remove().end().append('<option>Pilih Kategori 2</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori2').append(data).trigger('chosen:updated');
      }
    });
  });
  $("#kategori2").change(function(e, params) {
    var id  = $('#kategori2').val();
    loadTable(id);    
    $('#kategori3').find('option').remove().end().append('<option>Pilih Kategori 3</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/harga/{{ $tahun }}/usulan/getKategori_/"+id,
      success : function (data) {
        $('#kategori3').append(data).trigger('chosen:updated');
      }
    });
  });

  $("#kategori3").change(function(e, params) {
    var id  = $('#kategori3').val();
    loadTable(id);
  });  

  function loadTable(kategori){
    $('#table-komponen').DataTable().destroy();
    $('#table-komponen').DataTable({
      sAjaxSource: "{{ url('/') }}/main/{{$tahun}}/{{$status}}/referensi/komponen/getData/"+kategori,
      aoColumns: [
        { mData: 'NO'},
        { mData: 'KOMPONEN_NAMA' },
        { mData: 'KOMPONEN_SPESIFIKASI' },
        { mData: 'KOMPONEN_SATUAN' },
        { mData: 'KOMPONEN_HARGA',class:'text-right' },
        { mData: 'AKSI' }]
    });
  }

  function getrekening(komponen){
    $('#table-modal').DataTable().destroy();
    $('#table-modal').DataTable({
      sAjaxSource: "{{ url('/') }}/main/{{$tahun}}/{{$status}}/referensi/komponen/getrekening/"+komponen,
      aoColumns: [
        { mData: 'NO'},
        { mData: 'REKENING_KODE' },
        { mData: 'REKENING_NAMA' }]
    });
    $('#judul-modal').text('Daftar Rekening');
    $('#modal').modal('show');
  }

  function getuser(komponen) {
    $('#table-modal').DataTable().destroy();
    $('#table-modal').DataTable({
      sAjaxSource: "{{ url('/') }}/main/{{$tahun}}/{{$status}}/referensi/komponen/getuser/"+komponen,
      aoColumns: [
        { mData: 'NO'},
        { mData: 'SKPD_KODE' },
        { mData: 'SKPD_NAMA' }]
    });
    $('#judul-modal').text('Daftar OPD Pengguna');    
    $('#modal').modal('show');
  }
</script>
@endsection


