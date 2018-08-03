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
                   <select ui-jq="chosen" class="form-control" id="filter-skpd">
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
              <select ui-jq="chosen" class="w-full" id="tahun_anggaran" name="tahun_anggaran">
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
                <option value="RKAP">Rencana Kerja dan Anggaran Perubahan</option>
                <option value="RKAP1">Rencana Kerja dan Anggaran Perubahan 1</option>
                <option value="RKAP2.1">Rencana Kerja dan Anggaran Perubahan 2.1</option>
                <option value="RKAP2.2">Rencana Kerja dan Anggaran Perubahan 2.2</option>
                <option value="RKAP2.2.1">Rencana Kerja dan Anggaran Perubahan 2.2.1</option>
                <option value="RKAP3.1">Rencana Kerja dan Anggaran Perubahan 3.1</option>
                <option value="RKAP3.2">Rencana Kerja dan Anggaran Perubahan 3.2</option>
                <option value="DPPA">Dokumen Pelaksanaan Perubahan Anggaran</option>
                <option value="DPPA1">Dokumen Pelaksanaan Perubahan Anggaran 1</option>
                <option value="DPPA2.1">Dokumen Pelaksanaan Perubahan Anggaran 2.1</option>
                <option value="DPPA2.2">Dokumen Pelaksanaan Perubahan Anggaran 2.2</option>
                <option value="DPPA2.2.1">Dokumen Pelaksanaan Perubahan Anggaran 2.2.1</option>
                <option value="DPPA3.1">Dokumen Pelaksanaan Perubahan Anggaran 3.1</option>
                <option value="DPPA3.2">Dokumen Pelaksanaan Perubahan Anggaran 3.2</option>
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
    var urusan        = $('#urusan').val();
    var nama_program  = $('#nama_program').val();
    var skpd          = $('#skpd').val();
    var id_program    = $('#id_program').val();
    var token         = $('#token').val();
    if(urusan == "0" || nama_program == "" ){
      $.alert('Form harap dilengkapi!');
    }else{
      if(id_program == '') uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/program/add/submit";
      else uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/program/edit/submit";
      //console.log(uri);
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'urusan'          : urusan, 
              'skpd'            : skpd,
              'tahun'           : '{{$tahun}}', 
              'id_program'      : id_program, 
              'nama_program'    : nama_program},
        success: function(msg){
            if(msg == 1){
              $('#urusan select').val('0').trigger("chosen:updated");
              $('#skpd').val('').trigger("chosen:updated");
              $('#nama_program').val('');
              $('#id_program').val('');
              $('.table-program-head').DataTable().ajax.reload();              
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
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/program/delete",
                      type: "POST",
                      data: {'_token'         : token,
                            'id_program'      : id},
                      success: function(msg){
                          $.alert(msg);
                          $('.table-program-head').DataTable().ajax.reload();                          
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
        $('select#lampiran').val(msg['data'][0]['KEY']).trigger("chosen:updated");
        $('#id_ttd').val(msg['data'][0]['TTD_ID']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-ttd').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        });
      }
    });    
  }
  $('a.tutup-form').click(function(){
      $('select#urusan').val('0').trigger("chosen:updated");
      $('select#skpd').val('').trigger("chosen:updated");
      $('select#skpd_').val('').trigger("chosen:updated");
      $('#nama_program').val('');
      $('#kode_program').val('');
      $('#prioritas_program').val('');
      $('#id_program').val('');
      $('#nama_kegiatan').val('');
      $('#kode_kegiatan').val('');
      $('#kunci_kegiatan').val('');
      $('#prioritas_kegiatan').val('');
      $('#id_giat').val('');
  }); 
  $('.overlay').click(function(){
      $('select#urusan').val('0').trigger("chosen:updated");
      $('select#urusan_').val('0').trigger("chosen:updated");
      $('select#skpd').val('').trigger("chosen:updated");
      $('select#skpd_').val('').trigger("chosen:updated");
      $('select#program_').val('').trigger("chosen:updated");
      $('#nama_program').val('');
      $('#kode_program').val('');
      $('#prioritas_program').val('');
      $('#id_program').val('');
      $('#nama_kegiatan').val('');
      $('#kode_kegiatan').val('');
      $('#kunci_kegiatan').val('');
      $('#prioritas_kegiatan').val('');
      $('#id_giat').val('');
  }); 
</script>
@endsection