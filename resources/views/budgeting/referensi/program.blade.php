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
                <li class="active"><i class="fa fa-angle-right"></i>Program</li>                                
              </ul>
          </div>
          <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                    @if(Auth::user()->level == 8) 
                    <div class="dropdown dropdown-blend pull-right m-t-n-sm">
                      <button class="btn btn-success dropdown-toggle " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Tambah Usulan <i class="fa fa-chevron-down"></i>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <li><a class="open-form-btl">Tambah Program</a></li>
                        <li><a class="open-form-giat">Tambah Kegiatan</a></li>
                      </ul>
                    </div>
                    @endif
			<h5 class="inline font-semibold text-orange m-n ">Program Urusan Tahun {{ $tahun }}</h5>
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
                            <div class="table-responsive dataTables_wrapper table-program">
                             <table ui-jq="dataTable" ui-options="{
                                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/pengaturan/program/getData',
                                    aoColumns: [
                                    { mData: 'id_porgram',class:'hide' },
                                    { mData: 'URUSAN' },
                                    { mData: 'PROGRAM' },
                                    { mData: 'OPSI' }
                                    ]}" class="table table-program-head table-striped b-t b-b">
                                    <thead>
                                      <tr>
                                        <th class="hide">No</th>
                                        <th>Urusan</th>
                                        <th>Program</th>
                                        <th width="20%">#</th>
                                      </tr>
                                      <tr>
                                        <th class="hide"></th>
                                        <th colspan="4" class="th_search">
                                            <i class="icon-bdg_search"></i>
                                            <input type="search" class="table-search form-control b-none w-full" placeholder="Cari Program" aria-controls="DataTables_Table_0">
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
<div class="bg-white wrapper-lg input-sidebar input-btl">
<a href="#" class="tutup-form"><i class="icon-bdg_cross"></i></a>
    <form id="form-urusan" class="form-horizontal">
      <div class="input-wrapper">
        <h5 id="judul-form">Tambah Program</h5>
          <div class="form-group">
            <label for="kode_urusan" class="col-md-3">Urusan</label>          
            <div class="col-sm-9">
              <select ui-jq="chosen" class="w-full" id="urusan" name="urusan">
                  <option value="0">Silahkan Pilih Urusan</option>
                  @foreach($urusan as $u)
                  <option value="{{$u->URUSAN_ID}}">{{ $u->URUSAN_KODE }} - {{ $u->URUSAN_NAMA }}</option>
                  @endforeach
              </select>            
              <input type="hidden" class="form-control" value="{{ csrf_token() }}" name="_token" id="token">          
              <input type="hidden" class="form-control" name="id_program" id="id_program">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_program" class="col-md-3">Nama Program</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Nama Program" name="nama_program" id="nama_program" value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Perangkat Daerah</label>          
            <div class="col-sm-9">
              <select ui-jq="chosen" class="w-full" id="skpd" name="skpd" multiple="">
                @foreach($skpd as $s)
                  <option value="{{ $s->SKPD_ID }}">{{ $s->SKPD_NAMA }}</option>
                @endforeach
              </select>
            </div> 
          </div>

          <hr class="m-t-xl">
         <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanProgram()"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
      </div>
    </form>
  </div>
 </div>

<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-kegitan">
<a href="#" class="tutup-form"><i class="icon-bdg_cross"></i></a>
    <form id="form-urusan" class="form-horizontal">
      <div class="input-wrapper">
        <h5 id="judul-form_">Tambah Kegiatan</h5>
          <div class="form-group">
            <label for="kode_urusan" class="col-md-3">Urusan</label>          
            <div class="col-sm-9">
              <select ui-jq="chosen" class="w-full" id="urusan_" name="urusan">
                  <option value="0">Silahkan Pilih Urusan</option>
                  @foreach($urusan as $u)
                  <option value="{{$u->URUSAN_ID}}">{{ $u->URUSAN_KODE }} - {{ $u->URUSAN_NAMA }}</option>
                  @endforeach
              </select>            
              <input type="hidden" class="form-control" name="id_giat" id="id_giat">          
            </div> 
          </div>
          <div class="form-group">
            <label for="kode_urusan" class="col-md-3">Program</label>          
            <div class="col-sm-9">
              <select ui-jq="chosen" class="w-full" id="program_">
                  <option value="0">Silahkan Pilih Program</option>
              </select>            
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_program" class="col-md-3">Nama Kegiatan</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Nama Kegiatan" name="nama_kegiatan" id="nama_kegiatan" value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Perangkat Daerah</label>          
            <div class="col-sm-9">
              <select ui-jq="chosen" class="w-full" id="skpd_" name="skpd_" multiple="">
                @foreach($skpd as $s)
                  <option value="{{ $s->SKPD_ID }}">{{ $s->SKPD_NAMA }}</option>
                @endforeach
              </select>
            </div> 
          </div>

          <hr class="m-t-xl">
         <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanKegiatan()"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
      </div>
    </form>
  </div>
 </div>

<div id="table-detail-program" class="table-detail-program hide bg-white">
  <table class="table table-detail-program-isi table-striped b-t b-b">
    <thead class="orange">
      <tr>
        <th class="hide">#</th>                          
        <th width="1%">Kode</th>                          
        <th>Nama Kegiatan</th>                       
        <th width="20%">#</th>                                       
      </tr>                                  
    </thead>
    <tbody>
    </tbody>
  </table>
</div>


<div class="set-capaian modal fade " id="set-capaian-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white modal-lg">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Set Capaian</h5>
      </div>
      <div class="wrapper-lg m-t-n-md">
          <div class="form-group m-t-n-md">
            <div class="col-sm-2 no-padder">
              <select class="w-full" id="indikator-capaian">
                <option value="CAPAIAN" selected="">CAPAIAN</option>
                <option value="HASIL">HASIL</option>
              </select>
            </div> 
            <div class="col-sm-5 no-padder">
              <input type="text" class="form-control" placeholder="Tolak Ukur" id="tolak-ukur">
              <input type="hidden" class="form-control" id="id-capaian">
              <input type="hidden" class="form-control" id="tipe-capaian">
              <input type="hidden" class="form-control" id="id-indikator">
            </div> 
            <div class="col-sm-2 no-padder">
              <input type="text" class="form-control" placeholder="Target" id="target-capaian">
            </div> 
            <div class="col-sm-2 no-padder">
              <select id="satuan-capaian" class="w-full" id="satuan-capaian">
                <option value="0">Satuan</option>
                @foreach($satuan as $sat)
                <option value="{{ $sat->SATUAN_ID }}">{{ $sat->SATUAN_NAMA }}</option>
                @endforeach
              </select>
            </div>
            <button class="btn btn-success col-sm-1" onclick="return simpanCapaian()"><i class="fa fa-plus"></i></button>            
          </div>
      </div>      
      <div class="table-responsive">
        <table class="table table-popup table-striped b-t b-b table-capaian" id="table-capaian">
          <thead>
            <tr>
              <th width="1%">Indikator</th>
              <th>Tolak Ukur</th>
              <th width="10%">Target</th>                          
              <th width="1%">#</th>                          
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
  $('.open-form-giat').on('click',function(){
      $('.overlay').fadeIn('fast',function(){
        $('.input-kegitan').animate({'right':'0'},"linear"); 
        $("html, body").animate({ scrollTop: 0 }, "slow");
      }); 
  });
  $("#urusan_").change(function(e, params){
    var id  = $('#urusan_').val();
    $('#program_').find('option').remove().end().append('<option value="0">Silahkan Pilih Program</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/kegiatan/getProgram/"+id,
      success : function (data) {
        $('#program_').append(data).trigger('chosen:updated');
      }
    });
  });

  function simpanProgram(){
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
      console.log(uri);
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

  function simpanKegiatan(){
    var skpd          = $('#skpd_').val();
    var program       = $('#program_').val();
    var kegiatan      = $('#nama_kegiatan').val();
    var id_giat       = $('#id_giat').val();
    var token         = $('#token').val();
    console.log(skpd);
    if(skpd == "0" || kegiatan == "" || program == "0" ){
      $.alert('Form harap dilengkapi!');
    }else{
      if(id_giat == '') uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/kegiatan/add/submit";
      else uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/kegiatan/edit/submit";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'program'         : program, 
              'skpd'            : skpd,
              'tahun'           : '{{$tahun}}', 
              'id_giat'         : id_giat, 
              'kegiatan'        : kegiatan},
        success: function(msg){
            if(msg == 1){
              $('#urusan_').val('0').trigger("chosen:updated");
              $('#skpd_').val('0').trigger("chosen:updated");
              $('#nama_kegiatan').val('');
              $('#id_giat').val('');
              $('.table-program-head').DataTable().ajax.reload();              
              f$.alert({
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

  function hapusProgram(id){
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

  function hapusGiat(id){
    var token        = $('#token').val();    
    $.confirm({
        title: 'Hapus Data!',
        content: 'Yakin hapus data?',
        buttons: {
            Ya: {
                btnClass: 'btn-danger',
                action: function(){
                  $.ajax({
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/kegiatan/delete",
                      type: "POST",
                      data: {'_token'         : token,
                            'id_giat'       : id},
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

  function ubahProgram(id) {
    $('#judul-form').text('Ubah Program');        
    $.ajax({
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/program/getData/"+id,
      type: "GET",
      success: function(msg){
        $('select#skpd').append(msg['skpd']).trigger("chosen:updated");
        $('select#urusan').val(msg['data'][0]['URUSAN_ID']).trigger("chosen:updated");
        $('#id_program').val(msg['data'][0]['PROGRAM_ID']);
        $('#nama_program').val(msg['data'][0]['PROGRAM_NAMA']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-btl').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        });
      }
    });    
  }
  function ubahGiat(id) {
    $('#judul-form_').text('Ubah Kegiatan');        
    $.ajax({
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/kegiatan/getData/"+id,
      type: "GET",
      success: function(msg){
        $('select#skpd_').append(msg['skpd']).trigger("chosen:updated");
        $('select#program_').val(msg['data'][0]['PROGRAM_ID']).trigger("chosen:updated");
        $('#id_giat').val(msg['data'][0]['KEGIATAN_ID']);
        $('#nama_kegiatan').val(msg['data'][0]['KEGIATAN_NAMA']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-kegitan').animate({'right':'0'},"linear");  
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
      $('#id_program').val('');
  }); 
  $('.overlay').click(function(){
      $('select#urusan').val('0').trigger("chosen:updated");
      $('select#skpd_').val('').trigger("chosen:updated");
      $('#nama_program').val('');
      $('#id_program').val('');
  }); 

  function showCapaian(id){
    $('#id-capaian').val(id);
    $('#indikator-capaian').removeClass('hide');
    $('#tipe-capaian').val('0');
    $('#table-capaian').DataTable().destroy();
    $('#table-capaian').DataTable({
      sAjaxSource: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/program/getCapaian/"+id,
      aoColumns: [
      { mData: 'INDIKATOR' },
      { mData: 'TOLAK_UKUR' },
      { mData: 'TARGET' },
      { mData: 'AKSI' }]
    });
    $('#set-capaian-modal').modal('show');
  }
  function simpanCapaian(){
    id          = $('#id-capaian').val();
    tipe        = $('#indikator-capaian').val();
    tolakukur   = $('#tolak-ukur').val();
    target      = $('#target-capaian').val();
    satuan      = $('#satuan-capaian').val();
    token       = $('#token').val();
    type        = $('#tipe-capaian').val();
    idindikator = $('#id-indikator').val();
    if(idindikator){
        uri = "{{ url('/') }}/main/{{$tahun}}/{{$status}}/pengaturan/program/editCapaian"; 
    }else{
        uri = "{{ url('/') }}/main/{{$tahun}}/{{$status}}/pengaturan/program/submitCapaian"; 
    }
    $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'id'              : id, 
              'idindikator'     : idindikator, 
              'tipe'            : tipe,
              'tolakukur'       : tolakukur, 
              'target'          : target, 
              'satuan'          : satuan},
        success: function(msg){
          $.alert(msg);
          $('#id-indikator').val(null);
          $('#tolak-ukur').val(null);
          $('#target-capaian').val(null);
          $('#satuan-capaian').val(0);
          $('#table-capaian').DataTable().ajax.reload();
          // $('#table-capaian').DataTable({
          //   sAjaxSource: url,
          //   aoColumns: [
          //   { mData: 'INDIKATOR' },
          //   { mData: 'TOLAK_UKUR' },
          //   { mData: 'TARGET' },
          //   { mData: 'AKSI' }]
          // });
        }
    });
  }

  function showIndikatorGiat(id){
    $('#id-capaian').val(id);
    $('#indikator-capaian').addClass('hide');
    $('#tipe-capaian').val('1');    
    $('#table-capaian').DataTable().destroy();
    $('#table-capaian').DataTable({
      sAjaxSource: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/kegiatan/getCapaian/"+id,
      aoColumns: [
      { mData: 'INDIKATOR' },
      { mData: 'TOLAK_UKUR' },
      { mData: 'TARGET' },
      { mData: 'AKSI' }]
    });
    $('#set-capaian-modal').modal('show');
  }

  function hapusOutcome(id){
    token       = $('#token').val();
    $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/program/hapusOutcome",
        type: "POST",
        data: {'_token'         : token,
              'id'              : id},
        success: function(msg){
          $.alert(msg);
          $('#table-capaian').DataTable().ajax.reload();
        }
    });
  }

  function editOutcome(id){
      $.ajax({
          url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/program/outcome/"+id,
          type: "GET",
          success: function(msg){
            $('#indikator-capaian').val('CAPAIAN');
            $('#tolak-ukur').val(msg['OUTCOME_TOLAK_UKUR']);
            $('#target-capaian').val(msg['OUTCOME_TARGET']);
            $('#satuan-capaian').val(msg['SATUAN_ID']);
            $('#id-indikator').val(msg['OUTCOME_ID']);
          }
      }); 
    }

  function hapusImpact(id){
      token       = $('#token').val();
      $.ajax({
          url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/program/hapusImpact",
          type: "POST",
          data: {'_token'         : token,
                'id'              : id},
          success: function(msg){
            $.alert(msg);
            $('#table-capaian').DataTable().ajax.reload();
          }
      }); 
    }

  function editImpact(id){
      $.ajax({
          url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/program/impact/"+id,
          type: "GET",
          success: function(msg){
            $('#indikator-capaian').val('HASIL');
            $('#tolak-ukur').val(msg['IMPACT_TOLAK_UKUR']);
            $('#target-capaian').val(msg['IMPACT_TARGET']);
            $('#satuan-capaian').val(msg['SATUAN_ID']);
            $('#id-indikator').val(msg['IMPACT_ID']);
          }
      }); 
    }

    function hapusOutput(id){
      token       = $('#token').val();
      $.ajax({
          url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/kegiatan/hapusOutput",
          type: "POST",
          data: {'_token'         : token,
                'id'              : id},
          success: function(msg){
            $.alert(msg);
            $('#table-capaian').DataTable().ajax.reload();
          }
      }); 
    }   
    function editOutput(id){
      $.ajax({
          url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/kegiatan/output/"+id,
          type: "GET",
          success: function(msg){
            $('#tolak-ukur').val(msg['OUTPUT_TOLAK_UKUR']);
            $('#target-capaian').val(msg['OUTPUT_TARGET']);
            $('#satuan-capaian').val(msg['SATUAN_ID']);
            $('#id-indikator').val(msg['OUTPUT_ID']);
          }
      }); 
    }
</script>
@endsection


