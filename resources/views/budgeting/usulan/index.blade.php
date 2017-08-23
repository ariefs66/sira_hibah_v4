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
            <li class="active"><i class="fa fa-angle-right"></i>Usulan</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Daftar Usulan</h5>
                  <div class="dropdown dropdown-blend pull-right m-t-n-sm">
                    <button class="btn btn-info dropdown-toggle " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Download <i class="fa fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                      <li><a href="{{url('/')}}/main/{{$tahun}}/{{$status}}/download/musrenbang/{{$skpd}}">Musrenbang</a></li>
                      <li><a href="{{url('/')}}/main/{{$tahun}}/{{$status}}/download/reses/{{$skpd}}">Reses</a></li>
                      <!-- <li><a>PIPPK RW</a></li>
                      <li><a>PIPPK PKK</a></li>
                      <li><a>PIPPK LPM</a></li>
                      <li><a>PIPPK Karang Taruna</a></li> -->
                    </ul>
                  </div>
               </div>
               <!-- Main tab -->
               <div class="nav-tabs-alt tabs-alt-1 b-t six-row" id="tab-jurnal" >
                <ul class="nav nav-tabs" role="tablist">
                 <li class="active">
                  <a data-target="#tab-1" role="tab" data-toggle="tab">Musrenbang</a>
                </li>
                <li>
                  <a data-target="#tab-2" role="tab" data-toggle="tab">Reses</a>
                </li>
                <li>
                  <a data-target="#tab-3" role="tab" data-toggle="tab">PIPPK RW</a>
                </li>
                <li>
                  <a data-target="#tab-4" role="tab" data-toggle="tab">PIPPK Karta</a>
                </li>
                <li>
                  <a data-target="#tab-5" role="tab" data-toggle="tab">PIPPK LPM</a>
                </li>
                <li>
                  <a data-target="#tab-6" role="tab" data-toggle="tab">PIPPK PKK  </a>
                </li>

              </ul>

            </div>
            <!-- / main tab -->                  
            <div class="tab-content tab-content-alt-1 bg-white">
              <div role="tabpanel" class="active tab-pane " id="tab-1">
                    <div class="row m-t-sm m-b-sm m-l-sm">
                      <div class="col-sm-6">
                        <select ui-jq="chosen" class="w-full" id="filterkamus">
                          <option value="x">Pilih Kamus</option>
                          @foreach($kamus as $kamus)
                          <option value="{{$kamus->KAMUS_ID}}">{{ $kamus->KAMUS_NAMA }}</option>
                          @endforeach
                        </select>                        
                      </div>
                      <div class="col-sm-6 m-l-n-md">
                        <select ui-jq="chosen" class="w-full" id="filterkegiatan">
                          <option value="x">Pilih Kegiatan</option>
                          @foreach($kegiatan as $kegiatan)
                          <option value="{{$kegiatan->KEGIATAN_ID}}">{{ $kegiatan->KEGIATAN_NAMA }}</option>
                          @endforeach
                        </select>                        
                      </div>
                    </div>
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/usulan/getMusrenbang',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'PENGUSUL'},
                    { mData: 'KAMUS'},
                    { mData: 'KEGIATAN'},
                    { mData: 'AKSI'}
                  ],iDisplayLength: 100}" class="table table-striped b-t b-b table-musrenbang " id="table-musrenbang">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th>Pengusul</th>
                      <th>Kamus / Volume</th>
                      <th>Kegiatan</th>
                      <th width="1%">Opsi</th>
                    </tr>
                    <tr>
                      <th colspan="5" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-musrenbang form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-2">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/usulan/getReses',
                    aoColumns: [
                    { mData: 'ID',class:'hide'},
                    { mData: 'NO'},
                    { mData: 'PENGUSUL'},
                    { mData: 'KAMUS'},
                    { mData: 'KEGIATAN'},
                    { mData: 'ANGGARAN'}
                  ],iDisplayLength: 100}" class="table table-striped b-t b-b table-reses " id="table-reses">
                  <thead>
                    <tr>
                      <th class="hide"></th>
                      <th width="1%">No</th>
                      <th>Pengusul</th>
                      <th>Kamus / Volume</th>
                      <th>Kegiatan</th>
                      <th>Anggaran</th>
                    </tr>
                    <tr>
                      <th class="hide"></th>
                      <th colspan="5" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-reses form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-3">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/usulan/getRW',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'KELURAHAN'},
                    { mData: 'TOTAL'},
                    { mData: 'AKSI'}
                  ],iDisplayLength: 1000}" class="table table-striped b-t b-b table-reses " id="table-rw">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th>Kelurahan / RW</th>
                      <th>Total</th>
                      <th width="1%">Opsi</th>
                    </tr>
                    <tr>
                      <th colspan="4" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-reses form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-4">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/usulan/getKarta',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'KELURAHAN'},
                    { mData: 'TOTAL'},
                    { mData: 'AKSI'}
                  ],iDisplayLength: 100}" class="table table-striped b-t b-b table-reses " id="table-karta">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th>Kelurahan / RW</th>
                      <th>Total</th>
                      <th width="1%">Opsi</th>
                    </tr>
                    <tr>
                      <th colspan="4" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-reses form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-5">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/usulan/getLPM',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'KELURAHAN'},
                    { mData: 'TOTAL'},
                    { mData: 'AKSI'}
                  ],iDisplayLength: 100}" class="table table-striped b-t b-b table-reses " id="table-lpm">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th>Kelurahan / RW</th>
                      <th>Total</th>
                      <th width="1%">Opsi</th>
                    </tr>
                    <tr>
                      <th colspan="4" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-reses form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-6">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/usulan/getPKK',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'KELURAHAN'},
                    { mData: 'TOTAL'},
                    { mData: 'AKSI'}
                  ],iDisplayLength: 100}" class="table table-striped b-t b-b table-reses " id="table-pkk">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th>Kelurahan / RW</th>
                      <th>Total</th>
                      <th width="1%">Opsi</th>
                    </tr>
                    <tr>
                      <th colspan="4" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-reses form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
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
<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

<div class="modal fade" id="modal-reses" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Detail Reses</h5>
        <hr>
        <div class="row">
          <div class="col-md-12">
            <input type="hidden" id="idusulanreses">
            <div class="col-md-4"><p class="font-semibold">Dewan</p></div>
            <div class="col-md-8"><p class="font-semibold" id="dewan">: </p></div>
          </div>
          <div class="col-md-12">
            <div class="col-md-4"><p class="font-semibold">Fraksi</p></div>
            <div class="col-md-8"><p class="font-semibold" id="fraksi">: </p></div>
          </div>
          <div class="col-md-12">
            <div class="col-md-4"><p class="font-semibold">Dapil</p></div>
            <div class="col-md-8"><p class="font-semibold" id="dapil">: </p></div>
          </div>
          <div class="col-md-12">
            <div class="col-md-4"><p class="font-semibold">Kamus Usulan</p></div>
            <div class="col-md-8"><p class="font-semibold" id="kamus">: </p></div>
          </div>
          <div class="col-md-12">
            <div class="col-md-4"><p class="font-semibold">Lokasi Usulan</p></div>
            <div class="col-md-8"><p class="font-semibold" id="lokasi">: </p></div>
          </div>
          <div class="col-md-12">
            <div class="col-md-4"><p class="font-semibold">Urgensi Usulan</p></div>
            <div class="col-md-8"><p class="font-semibold" id="urgensi">: </p></div>
          </div>
          <div class="col-md-12">
            <div class="col-md-4"><p class="font-semibold">Volume</p></div>
            <div class="col-md-8"><p class="font-semibold" id="volume">: </p></div>
          </div>
          <div class="col-md-12">
            <div class="col-md-4"><p class="font-semibold">Anggaran</p></div>
            <div class="col-md-8"><p class="font-semibold" id="anggaran">: </p></div>
          </div>
          <div class="col-md-12">
            <div class="col-md-4"><p class="font-semibold">Alasan</p></div>
            <div class="col-md-8">
              <textarea class="form-control" placeholder="Isi Alasan Jika Usulan Ditolak!" id="alasan"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="terimaReses()"><i class="fa fa-check"></i> Terima</button>
        <button type="button" class="btn btn-danger" onclick="tolakReses()"><i class="fa fa-close"></i> Tolak</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('plugin')
<script type="text/javascript">
  $('.cari-musrenbang').keyup( function () {
    $('#table-musrenbang').DataTable().search($('.cari-musrenbang').val()).draw();
  });

  function submitRW(id){
    var token   = $('#token').val();
    $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/usulan/pippk/setrw",
        type: "POST",
        data: {'_token'         : token,
              'RW_ID'           : id},
        success: function(msg){
          if(msg == 0) $.alert('Komponen Belum Tersedia!');
          else if(msg == 1) $.alert('Berhasil!');
          else if(msg == 2) $.alert('Belanja Belum Tersedia');
          $('#table-rw').DataTable().ajax.reload();
        }
    });
  }
  function submitKarta(id){
    var token   = $('#token').val();
    $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/usulan/pippk/setkarta",
        type: "POST",
        data: {'_token'         : token,
              'RW_ID'           : id},
        success: function(msg){
          if(msg == 0) $.alert('Komponen Belum Tersedia!');
          else if(msg == 1) $.alert('Berhasil!');
          else if(msg == 2) $.alert('Belanja Belum Tersedia');
          $('#table-karta').DataTable().ajax.reload();
        }
    });
  }
  function submitPKK(id){
    var token   = $('#token').val();
    $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/usulan/pippk/setpkk",
        type: "POST",
        data: {'_token'         : token,
              'RW_ID'           : id},
        success: function(msg){
          if(msg == 0) $.alert('Komponen Belum Tersedia!');
          else if(msg == 1) $.alert('Berhasil!');
          else if(msg == 2) $.alert('Belanja Belum Tersedia');
          $('#table-pkk').DataTable().ajax.reload();
        }
    });
  }
  function submitLPM(id){
    var token   = $('#token').val();
    $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/usulan/pippk/setlpm",
        type: "POST",
        data: {'_token'         : token,
              'RW_ID'           : id},
        success: function(msg){
          if(msg == 0) $.alert('Komponen Belum Tersedia!');
          else if(msg == 1) $.alert('Berhasil!');
          else if(msg == 2) $.alert('Belanja Belum Tersedia');
          $('#table-lpm').DataTable().ajax.reload();
        }
    });
  }
  function submitMusren(id){
    var token   = $('#token').val();
    $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/usulan/musrenbang/set",
        type: "POST",
        data: {'_token'         : token,
              'USULAN_ID'       : id},
        success: function(msg){
          if(msg == 0) $.alert('Komponen Belum Tersedia!');
          else if(msg == 1) {
            $.alert('Berhasil!');
            $('#table-musrenbang').DataTable().ajax.reload();
        }else if(msg == 2) $.alert('Belanja Belum Tersedia');
        }
    });
  }

  $("#filterkamus").change(function(e, params){
    loadTable();
  });
  $("#filterkegiatan").change(function(e, params){
    loadTable();
  });

  function loadTable(filter,id){
    var idkamus  = $('#filterkamus').val();
    var idgiat   = $('#filterkegiatan').val();
    $('#table-musrenbang').DataTable().destroy();
    $('#table-musrenbang').DataTable({
      sAjaxSource: "{{ url('/') }}/main/{{$tahun}}/{{$status}}/usulan/getMusrenbang/"+idkamus+"/"+idgiat,
      aoColumns: [
        { mData: 'NO'},
        { mData: 'PENGUSUL'},
        { mData: 'KAMUS'},
        { mData: 'KEGIATAN'},
        { mData: 'AKSI'}]
    });
  }

  $('#table-reses').on('click','tbody > tr', function(){
      id = $(this).children('td').eq(0).html();
      $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/usulan/getReses/"+id,
        type: "GET",
        success: function(data){
          $('#dewan').text(': '+data['dewan']);
          $('#fraksi').text(': '+data['fraksi']);
          $('#dapil').text(': '+data['dapil']);
          $('#kamus').text(': '+data['kamus']);
          $('#lokasi').text(': '+data['lokasi']);
          $('#urgensi').text(': '+data['urgensi']);
          $('#volume').text(': '+data['volume']);
          $('#anggaran').text(': '+data['anggaran']);
          $('#idusulanreses').val(id);
          $('#modal-reses').modal('show');
        }
      });
   })

  function tolakReses(){
    var id      = $('#idusulanreses').val();
    var alasan  = $('#alasan').val();
    var token   = $('#token').val();
    if(alasan == ""){
      $.alert('Input Alasan Terlebih Dahulu!');
    }else{
      $.confirm({
        title: 'Tokak Usulan!',
        content: 'Yakin Totak Usulan?',
        buttons: {
          Ya: {
            btnClass: 'btn-danger',
            action: function(){
              $.ajax({
                  url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/usulan/reses/tolak",
                  type: "POST",
                  data: {'_token'             : token,
                        'USULAN_ALASAN'       : alasan,
                        'USULAN_ID'           : id},
                  success: function(msg){
                    $('#modal-reses').modal('hide');
                    $.alert(msg);
                    $('#table-reses').DataTable().ajax.reload();
                  }
              });
            }
          },
          Tidak: function () {
          }
        }
      });
    }
  }

  function terimaReses(){
    var id      = $('#idusulanreses').val();
    var token   = $('#token').val();
      $.confirm({
        title: 'Terima Usulan!',
        content: 'Yakin Terima Usulan?',
        buttons: {
          Ya: {
            btnClass: 'btn-success',
            action: function(){
              $.ajax({
                  url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/usulan/reses/set",
                  type: "POST",
                  data: {'_token'             : token,
                        'USULAN_ID'           : id},
                  success: function(msg){
                    if(msg == 0){
                      $.alert('Komponen Belum Tersedia!');
                    }else if(msg == 1) {
                      $.alert('Berhasil!');
                      $('#table-reses').DataTable().ajax.reload();
                    }else if(msg == 2){
                      $.alert('Belanja Belum Tersedia');
                    }
                    $('#modal-reses').modal('hide');
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