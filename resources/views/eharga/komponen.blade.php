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
                <li><a href= "{{ url('/') }}/main">Dashboard</a></li>
                <li class="active"><i class="fa fa-angle-right"></i>Komponen</li>                                
              </ul>
          </div>

          <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                    @if(substr(Auth::user()->mod,4,1)==1)
                      <div class="dropdown dropdown-blend pull-right m-t-n-sm">
                        <button class="btn btn-success dropdown-toggle open-form-btl"><i class="fa fa-plus"></i> Tambah</button>
                      </div>
                    @endif
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
                                        @if(substr(Auth::user()->mod,4,1)==1)
                                        <th width="1%">Kunci</th>
                                        @endif
                                        <th width="20%">Aksi</th>
                                      </tr>
                                      <tr>
                                        @if(substr(Auth::user()->mod,4,1)==1)                                        
                                        <th colspan="7" class="th_search">
                                        @else
                                        <th colspan="6" class="th_search">
                                        @endif
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
      <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kategori" required="">
            <option value="">Silahkan Pilih Kategori</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3">Rekening</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="rekening" required="" multiple="">
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

      <hr class="m-t-xl">
      <input type="hidden" id="id-usulan">      
      <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanKomponen()" id="simpanUsulan"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
    </div>
  </form>
</div>

<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
@endsection

@section('plugin')
<script type="text/javascript">
  $(document).ready(function(){
    $("#app").trigger('click');
  });
</script>
<script type="text/javascript">
  $("#jeniskomponen").change(function(e, params){
      var id  = $('#jeniskomponen').val();
      $('#kategori').find('option').remove().end().append('<option>Silahkan Pilih Kategori</option>');
      $.ajax({
        type  : "get",
        url   : "{{ url('/') }}/harga/{{$tahun}}/komponen/getkategori/"+id,
        success : function (data) {
          $('#kategori').append(data).trigger('chosen:updated');
        }
      });    
  });

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
      sAjaxSource: "{{ url('/') }}/main/{{$tahun}}/_/referensi/komponen/getData/"+kategori,
      aoColumns: [
        { mData: 'NO'},
        { mData: 'KOMPONEN_NAMA' },
        { mData: 'KOMPONEN_SPESIFIKASI' },
        { mData: 'KOMPONEN_SATUAN' },
        @if(substr(Auth::user()->mod,4,1)==1)      
        { mData: 'KUNCI' },
        @endif
        { mData: 'KOMPONEN_HARGA',class:'text-right' },
        { mData: 'AKSI' }]
    });
  }

  function getrekening(komponen){
    $('#table-modal').DataTable().destroy();
    $('#table-modal').DataTable({
      sAjaxSource: "{{ url('/') }}/main/{{$tahun}}/_/referensi/komponen/getrekening/"+komponen,
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
      sAjaxSource: "{{ url('/') }}/main/{{$tahun}}/_/referensi/komponen/getuser/"+komponen,
      aoColumns: [
        { mData: 'NO'},
        { mData: 'SKPD_KODE' },
        { mData: 'SKPD_NAMA' }]
    });
    $('#judul-modal').text('Daftar OPD Pengguna');    
    $('#modal').modal('show');
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
              url: "{{ url('/') }}/harga/{{$tahun}}/komponen/hapus",              
              type: "POST",
              data: {'_token'         : token,
                    'KOMPONEN_ID'     : id},
              success: function(msg){
                $('#table-komponen').DataTable().ajax.reload();
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

  function simpanKomponen(){
    kategori    = $('#kategori').val();
    komponen    = $('#komponen-nama').val();
    satuan      = $('#satuan').val();
    harga       = $('#harga').val();
    spesifikasi = $('#spesifikasi').val();
    token       = $('#token').val();
    rekening    = $('#rekening').val();
    idkomponen  = $('#idkomponen').val();
    if(kategori == "" || komponen == "" || satuan == "" || harga == "" || rekening == ""){
      $.alert('Form harap diisi!');
    }else{
      $.ajax({
        url: "{{ url('/') }}/harga/{{$tahun}}/komponen/submit",
        type: "POST",
        data: {'_token'               : token,
                'REKENING_ID'         : rekening, 
                'KOMPONEN_KODE'       : kategori,
                'KOMPONEN_NAMA'       : komponen, 
                'KOMPONEN_SPESIFIKASI': spesifikasi, 
                'KOMPONEN_SATUAN'     : satuan,
                'KOMPONEN_ID'         : idkomponen,
                'KOMPONEN_HARGA'      : harga},
        success: function(msg){
          $('#table-komponen').DataTable().ajax.reload();
          $.alert(msg);
          $('#kategori').val('');
          $('#komponen-nama').val('');
          $('#satuan').val('');
          $('#spesifikasi').val('');
          $('#jeniskomponen').val('');
          $('.input-btl,.input-sidebar').animate({'right':'-1050px'},function(){
            $('.overlay').fadeOut('fast');
          });
        }
      });
    }
  }

  function buka(id){
    var token        = $('#token').val();
    $.ajax({
      url: "{{ url('/') }}/harga/{{$tahun}}/komponen/kunci/0",              
      type: "POST",
      data: {'_token'         : token,
              'KOMPONEN_ID'   : id},
      success: function(msg){
        $('#table-komponen').DataTable().ajax.reload();
        $.alert(msg);
      }
    });
  } 

  function kunci(id){
    var token        = $('#token').val();
    $.ajax({
      url: "{{ url('/') }}/harga/{{$tahun}}/komponen/kunci/1",              
      type: "POST",
      data: {'_token'         : token,
              'KOMPONEN_ID'   : id},
      success: function(msg){
        $('#table-komponen').DataTable().ajax.reload();
        $.alert(msg);
      }
    });
  }
</script>
@endsection


