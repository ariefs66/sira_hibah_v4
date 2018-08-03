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
                <li><a>Pengaturan</a></li>
                <li class="active"><i class="fa fa-angle-right"></i>Setting TTD</li>                                
              </ul>
          </div>
          <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                    @if(Auth::user()->level == 8 && Auth::user()->active == 1) 
                    <div class="dropdown dropdown-blend pull-right m-t-n-sm">
                      <button class="btn btn-success dropdown-toggle " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Tambah <i class="fa fa-chevron-down"></i>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <li><a class="open-form-ttd">Tambah Data TTD</a></li>
                      </ul>
                    </div>
                    @endif
  
                    @if(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->level == 0 or substr(Auth::user()->mod,1,1) == 1)
                    <div class="col-sm-4 pull-right m-t-n-sm">
                   <select ui-jq="chosen" class="form-control" id="filter_tahun">
                     <option value="">- Pilih Tahun Anggaran-</option>
                       @if(!empty($tahunanggaran))
                       @foreach($tahunanggaran as $th)
                       <option value="{{ $th->ID }}">{{ $th->TAHUN . '-' . $th->STATUS }}</option>
                       @endforeach
                       @endif
                   </select>
                 </div>
                 @endif


                    <h5 class="inline font-semibold text-orange m-n ">Setting Data TTD</h5>
          					<div class="col-sm-1 pull-right m-t-n-sm">
                    	<select class="form-control dtSelect" id="dtSelect">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>                      
                  </div>           
                  <div class="tab-content tab-content-alt-1 bg-white">
                        <div role="tabpanel" class="active tab-pane" id="tab-1">  
                            <div class="table-responsive dataTables_wrapper table-ttd">
                             <table ui-jq="dataTable" ui-options="{
                                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/pengaturan/ttd/getData',
                                    aoColumns: [
                                    { mData: 'id_ttd',class:'hide' },
                                    { mData: 'TAHUN' },
                                    { mData: 'KEY' },
                                    { mData: 'DATA' },
                                    { mData: 'OPSI' }
                                    ]}" class="table table-ttd-head table-striped b-t b-b">
                                    <thead>
                                      <tr>
                                        <th class="hide">No</th>
                                        <th>Tahapan</th>
                                        <th>Lampiran</th>
                                        <th>Pengaturan</th>
                                        <th width="20%">#</th>
                                      </tr>
                                      <tr>
                                        <th class="hide"></th>
                                        <th colspan="4" class="th_search">
                                            <i class="icon-bdg_search"></i>
                                            <input type="search" class="table-search form-control b-none w-full" placeholder="Cari Lampiran" aria-controls="DataTables_Table_0">
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
      <!-- App-content-body -->  
    </div>
    <!-- .col -->
</div>

<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-ttd">
<a href="#" class="tutup-form"><i class="icon-bdg_cross"></i></a>
    <form id="form-prioritas" class="form-horizontal">
      <div class="input-wrapper">
        <h5 id="judul-prioritas">Setting Data TTD</h5>

          <div class="form-group">
            <label for="tahun_anggaran" class="col-md-3">Tahun Anggaran</label>          
            <div class="col-sm-9">
              <input type="hidden" placeholder="ID TTD" class="hide" id="id_ttd">
              <input type="hidden" class="form-control" value="{{ csrf_token() }}" name="_token" id="token"> 
              <select ui-jq="chosen" class="w-full" id="tahun_anggaran" name="tahun_anggaran">
                @if(!empty($tahunanggaran))
                @foreach($tahunanggaran as $th)
                <option value="{{ $th->ID }}">{{ $th->TAHUN . '-' . $th->STATUS }}</option>
                @endforeach
                @endif
              </select>
            </div> 
          </div>

          <div class="form-group">
            <label for="tahun_anggaran" class="col-md-3">Lampiran</label>          
            <div class="col-sm-9">
              <select ui-jq="chosen" class="w-full" id="lampiran" name="lampiran">
                <option value="PERDA1">Peraturan Daerah 1</option>
                <option value="PERDA2">Peraturan Daerah 2</option>
                <option value="PERDA3">Peraturan Daerah 3</option>
                <option value="PERDA4">Peraturan Daerah 4</option>
                <option value="PERDA5">Peraturan Daerah 5</option>
                <option value="PERWAL1">Peraturan Walikota 1</option>
                <option value="PERWAL2">Peraturan Walikota 2</option>
                <option value="PERWAL3">Peraturan Walikota 3</option>
                <option value="PERWAL4">Peraturan Walikota 4</option>
                <option value="PERWAL5">Peraturan Walikota 5</option>
                <option value="RKA">Rencana Kerja dan Anggaran</option>
                <option value="RKA1">Rencana Kerja dan Anggaran 1</option>
                <option value="RKA2.1">Rencana Kerja dan Anggaran 2.1</option>
                <option value="RKA2.2">Rencana Kerja dan Anggaran 2.2</option>
                <option value="RKA2.2.1">Rencana Kerja dan Anggaran 2.2.1</option>
                <option value="RKA3.1">Rencana Kerja dan Anggaran 3.1</option>
                <option value="RKA3.2">Rencana Kerja dan Anggaran 3.2</option>
                <option value="DPA">Dokumen Pelaksanaan Anggaran</option>
                <option value="DPA1">Dokumen Pelaksanaan Anggaran 1</option>
                <option value="DPA2.1">Dokumen Pelaksanaan Anggaran 2.1</option>
                <option value="DPA2.2">Dokumen Pelaksanaan Anggaran 2.2</option>
                <option value="DPA2.2.1">Dokumen Pelaksanaan Anggaran 2.2.1</option>
                <option value="DPA3.1">Dokumen Pelaksanaan Anggaran 3.1</option>
                <option value="DPA3.2">Dokumen Pelaksanaan Anggaran 3.2</option>
              </select>
            </div> 
          </div>

          <div class="form-group">
            <label for="nomor" class="col-md-3">Nomor</label>          
            <div class="col-sm-9">
            <input type="text" placeholder="Setting Nomor" class="form-control" id="nomor">
            </div> 
          </div>

          <div class="form-group">
            <label for="tanggal" class="col-md-3">Tanggal</label>          
            <div class="col-sm-9">
            <input type="text" ui-jq="daterangepicker" ui-options="{singleDatePicker:true,timePicker24Hour:false,format:'YYYY-MM-DD',timePicker: false}" placeholder="Setting Tanggal" class="form-control" id="tanggal">
            </div> 
          </div>
          
          <div class="form-group">
            <label for="nama" class="col-md-3">Nama Penanda Tangan</label>          
            <div class="col-sm-9">
            <input type="text" placeholder="Setting Nama" class="form-control" id="nama">
            </div> 
          </div>

          <div class="form-group">
            <label for="jabatan" class="col-md-3">Jabatan Penanda Tangan</label>          
            <div class="col-sm-9">
            <input type="text" placeholder="Setting Jabatan" class="form-control" id="jabatan">
            </div> 
          </div>
          
          <div class="form-group">
            <label for="nip" class="col-md-3">NIP Penanda Tangan</label>          
            <div class="col-sm-9">
            <input type="text" placeholder="Setting NIP" class="form-control" id="nip">
            </div> 
          </div>

          <hr class="m-t-xl">
         <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanTTD()"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
      </div>
    </form>
  </div>
 </div>
@endsection

@section('plugin')
<script type="text/javascript">
  $('.open-form-ttd').on('click',function(){
      $('.overlay').fadeIn('fast',function(){
        $('.input-ttd').animate({'right':'0'},"linear"); 
        $("html, body").animate({ scrollTop: 0 }, "slow");
      }); 
  });

  function simpanTTD(){
    var id            = $('#id_ttd').val();
    var tahun_anggaran= $('#tahun_anggaran').val();
    var lampiran      = $('#lampiran').val();
    var nomor         = $('#nomor').val();
    var tanggal       = $('#tanggal').val();
    var nama          = $('#nama').val();
    var jabatan       = $('#jabatan').val();
    var nip           = $('#nip').val();
    var token         = $('#token').val();
    if(tahun_anggaran == "0" || tahun_anggaran == "" || lampiran == "0" || lampiran == "" ){
      $.alert('Form harap dilengkapi!');
    }else{
      uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/ttd/submitTTD";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'id'              : id, 
              'tahun_anggaran'  : tahun_anggaran,
              'lampiran'        : lampiran, 
              'nomor'           : nomor, 
              'tanggal'         : tanggal, 
              'nama'            : nama, 
              'jabatan'         : jabatan, 
              'nip'             : nip},
        success: function(msg){
            if(msg == 1){
              $('#id_ttd').val('');
              $('select#tahun_anggaran').val('').trigger("chosen:updated");
              $('select#lampiran').val('').trigger("chosen:updated");
              $('#nomor').val('');
              $('#tanggal').val('');
              $('#nama').val('');
              $('#jabatan').val('');
              $('#nip').val('');
              $('.table-ttd-head').DataTable().ajax.reload();              
              $.alert({
                title:'Info',
                content: 'Data berhasil disimpan',
                autoClose: 'ok|1000',
                buttons: {
                    ok: function () {
                      $('.input-ttd,.input-sidebar').animate({'right':'-1050px'},function(){
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
  function hapusTTD(id){
    var token        = $('#token').val();    
    $.confirm({
        title: 'Hapus Data!',
        content: 'Yakin hapus data?',
        buttons: {
            Ya: {
                btnClass: 'btn-danger',
                action: function(){
                  $.ajax({
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/ttd/hapusTTD",
                      type: "POST",
                      data: {'_token'         : token,
                            'id'      : id},
                      success: function(msg){
                          $.alert(msg);
                          $('.table-ttd-head').DataTable().ajax.reload();                          
                        }
                  });
                }
            },
            Tidak: function () {
            }
        }
    });
  }

  function ubahTTD(id) {
    $('#judul-form').text('Setting Data TTD');        
    $.ajax({
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/ttd/getData/"+id,
      type: "GET",
      success: function(msg){
        $('#id_ttd').val(msg['data'][0]['TTD_ID']);
        $('select#tahun_anggaran').val(msg['data'][0]['TAHUN_ANGGARAN_ID']).trigger("chosen:updated");
        $('select#lampiran').val(msg['data'][0]['KEY']).trigger("chosen:updated");
        $('#nomor').val(msg['data'][0]['NOMOR']);
        $('#tanggal').val(msg['data'][0]['VALUE']);
        $('#nama').val(msg['data'][0]['NAMA_PEJABAT']);
        $('#jabatan').val(msg['data'][0]['JABATAN']);
        $('#nip').val(msg['data'][0]['NIP_PEJABAT']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-ttd').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        });
      }
    });    
  }
  $('a.tutup-form').click(function(){
    $('#id_ttd').val(msg['data'][0]['TTD_ID']);
    $('select#tahun_anggaran').val('').trigger("chosen:updated");
    $('select#lampiran').val('').trigger("chosen:updated");
    $('#nomor').val('');
    $('#tanggal').val('');
    $('#nama').val('');
    $('#jabatan').val('');
    $('#nip').val('');
  }); 
  $('.overlay').click(function(){
    $('#id_ttd').val(msg['data'][0]['TTD_ID']);
    $('select#tahun_anggaran').val('').trigger("chosen:updated");
    $('select#lampiran').val('').trigger("chosen:updated");
    $('#nomor').val('');
    $('#tanggal').val('');
    $('#nama').val('');
    $('#jabatan').val('');
    $('#nip').val('');
  }); 
</script>
@endsection