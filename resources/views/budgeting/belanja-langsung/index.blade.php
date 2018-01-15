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
            <li><i class="fa fa-angle-right"></i>Belanja</li>                               
            <li class="active"><i class="fa fa-angle-right"></i>Belanja Langsung</li>                                
          </ul>
        </div>

        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Belanja Langsung</h5> 
                  
                  @if(Auth::user()->level == 2)
                    @if($thp == 1 and Auth::user()->active == 1)
                      <a class="pull-right btn m-t-n-sm btn-success" href="{{ url('/') }}/main/{{$tahun}}/murni/belanja-langsung/tambah"><i class="m-r-xs fa fa-plus"></i> Tambah Belanja Langsung</a>
                    @else 
                      <p class="text-orange"> Pagu OPD : {{number_format($pagu,0,'.',',')}} | Pagu BL  : {{number_format($blpagu,0,'.',',')}} | RIncian  : {{number_format($rincian,0,'.',',')}}</p>
                    @endif
                  @elseif(Auth::user()->active == 0)
                  <h5 class="pull-right font-semibold text-info m-t-n-xs"><i class="fa fa-info-circle"></i> Akun tidak aktif!</h5>
                  @elseif($thp == 0)
                  <h5 class="pull-right font-semibold text-info m-t-n-xs"><i class="fa fa-info-circle"></i> Tahapan masih ditutup!</h5>
                  @endif
                  @if(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->level == 0 or substr(Auth::user()->mod,1,1) == 1)
                  <div class="col-sm-4 pull-right m-t-n-sm">
                   <select ui-jq="chosen" class="form-control" id="filter-skpd">
                     <option value="">- Pilih OPD -</option>
                     @foreach($skpd as $pd)
                     <option value="{{ $pd->SKPD_ID }}">{{ $pd->SKPD_NAMA }}</option>
                     @endforeach
                   </select>
                   
                 </div>
                 @endif
               </div>   



               

                <div role="tabpanel" class="active tab-pane" id="tab-1">
                 
                  <div class="tab-content tab-content-alt-1 bg-white">
                

                  <div class="table-responsive dataTables_wrapper">
                   <table ui-jq="dataTable" ui-options="{
                        sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/getMurni/0',
                        aoColumns: [
                          { mData: 'NO',class:'text-center' },
                          { mData: 'KEGIATAN' },
                          { mData: 'PAGU' },
                          { mData: 'RINCIAN' },
                          { mData: 'STATUS' }]
                      }" class="table table-jurnal table-striped b-t b-b" id="table-index">
                    <thead>
                      <tr>
                        <th rowspan="2" style="width: 1%">#</th>
                        <th rowspan="2">Program/Kegiatan/Sub Unit</th>
                        <th colspan="2" style="text-align: center;">Anggaran</th>                                      
                        <th rowspan="2" width="16%">Status
                          @if(substr(Auth::user()->mod,1,1) == 1 or Auth::user()->level == 8)
                            <label class="i-switch bg-danger m-t-xs m-r buka-giat"><input type="checkbox" onchange="return kunciGiatSKPD()" id="kuncigiatskpd"><i></i></label>
                          @endif
                          @if(substr(Auth::user()->mod,1,1) == 1 or Auth::user()->level == 9)
                            <label class="i-switch bg-danger m-t-xs m-r buka-giat"><input type="checkbox" onchange="return kunciRincianSKPD()" id="kuncirincianskpd"><i></i></label>
                          @endif
                        </th>                                      
                      </tr>
                      <tr>
                        <th style="width: 15%">Pagu</th>                                      
                        <th style="width: 15%">Rincian</th>                                     
                      </tr>
                      <tr>
                        <th colspan="7" class="th_search">
                          <i class="icon-bdg_search"></i>
                          <input type="search" class="table-search form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="2"><b>Total</b></td>
                        <td><b><text id="pagu_foot"></text></b></td>
                        <td><b><text id="rincian_foot"></text></b></td>
                        <td></td>
                      </tr>
                    </tfoot>                                   
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

<!-- Staf -->
<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-staff">
  <a href="#" class="close"><i class="icon-bdg_cross"></i></a>
  <form action="#" class="form-horizontal">
    <div class="input-wrapper">
      <h5>Tambah Staff</h5>
      <div class="form-group">
        <label class="col-sm-3">Staff 1</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="staff-1">
            <option value="">Pilih Staff</option>
            @foreach($user as $u)
            <option value="{{ $u->id }}">{{ $u->email }} - {{ $u->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3">Staff 2</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="staff-2">
            <option value="">Pilih Staff</option>
            @foreach($user as $u)
            <option value="{{ $u->id }}">{{ $u->email }} - {{ $u->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <hr class="m-t-xl">
      <input type="hidden" id="id-belanja">
      <input type="hidden" id="id-staff1">
      <input type="hidden" id="id-staff2">
      <a class="btn input-xl m-t-md btn-success pull-right" onclick="return setStaff()"><i class="fa fa-plus m-r-xs "></i>Simpan Staff</a>
    </div>
  </form>
</div>
</div>


<!-- log -->
<div class="info modal fade" id="info" tabindex="-1" role="dialog">
  <div class="modal-dialog" style="width: 80%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="inline font-semibold text-orange m-n text16 ">Info Kegiatan</h5>
      </div>
      <div class="modal-body">
       <div class="row">
        <div class="col-sm-2">Dibuat Oleh</div>
        <div class="col-sm-4">: <span id="creator"></span></div>
        <div class="col-sm-2">Staff 1</div>
        <div class="col-sm-4">: <span id="staff1"></span></div>
        <div class="col-sm-2">Waktu di buat</div>
        <div class="col-sm-4">: <span id="created"></span></div>
        <div class="col-sm-2">Staff 2</div>
        <div class="col-sm-4">: <span id="staff2"></span></div>
        <div class="col-sm-2">Update terkahir</div>
        <div class="col-sm-4">: <span id="updated"></span></div>
      </div>
      <div class="wrapper-lg">
       <div class="streamline b-l b-grey m-l-lg m-b padder-v" id="timeline-log">
      </div>           			
    </div>
  </div>
</div>
</div>
</div>


<div class="modal fade" id="modal-pagu" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Set Pagu</h5>
        <hr>
        
        <input type="text" id="pagu" class="form-control">
        <textarea class="form-control m-t-sm" placeholder="Catatan" id="pagu_catatan"></textarea>
        <input type="hidden" id="id-bl" class="form-control">
        <button class="btn btn-warning m-t-md" onclick="return simpanpagu()">Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-urgensi" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Urgensi Kegiatan</h5>
        <p>Sebelum merinci kegiatan harap isi terlebih dahulu form urgensi kegiatan di bawah ini.</p>
        <hr>
        <input type="hidden" id="id_bl" class="form-control">
        <p class="m-t-n-sm">Latar Belakang (dasar) Dari Pembuatan Kegiatan Ini</p>
        <textarea class="form-control m-t-sm" placeholder="Tulis Latar Belakang (dasar) Dari Pembuatan Kegiatan Ini" id="urgensi_latar_belakang"></textarea>
        <p class="m-t-sm">Deskripsi Kegiatan</p>
        <textarea class="form-control m-t-sm" placeholder="Deskripsi Kegiatan Ini Dalam 1 Paragraf (Min 250 Karakter)" id="urgensi_deskripsi"></textarea>
        <p class="m-t-sm">Tujuan & Manfaat Kegiatan</p>
        <textarea class="form-control m-t-sm" placeholder="Deskripsikan Tujuan dan Manfaat Kegiatan Ini" id="urgensi_tujuan"></textarea>
        <p class="m-t-sm">Lokasi Kegiatan (Fisik Konstruksi)</p>
        <textarea class="form-control m-t-sm" placeholder="Dimanakah dilaksanakannya kegiatan ini (Tuliskan alamat lokasi kegiatan yang dimaksud)? *Hanya Untuk Kegiatan Konstruksi" id="urgensi_lokasi"></textarea>
        <div class="col-md-3 no-padder">
        <p class="m-t-sm">Penerima Manfaat</p>
        </div>
        <div class="col-md-9 no-padder">
        <select class="form-control m-t-sm" id="urgensi_penerima">
          <option value="masyarakat">Masyarakat</option>
          <option value="pns">PNS</option>
        </select>
        </div>
        <div class="col-md-3 no-padder">
        </div>
        <div class="col-sm-9 no-padder">
        <select class="form-control m-t-sm col-md-6" ui-jq="chosen" id="urgensi_penerima_rincian" multiple="">
          <option value="-">---</option>
        </select>
        </div>
        <p class="m-t-lg">Pelaksanaan Kegiatan</p>
        <textarea class="form-control m-t-sm" placeholder="Bagaimana Cara Pelaksanaan/Pengadaan Kegiatan Ini?" id="urgensi_pelaksanaan"></textarea>
        <hr>
        <button class="btn btn-success" onclick="return simpanurgensi()">Simpan</button>
      </div>
    </div>
  </div>
</div>
<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
@endsection

@section('plugin')
<script type="text/javascript">
  $("select.selectrincian").on('click',function() {
    $('.table').DataTable().page.len($('.selectrincian').val()).draw();
  });


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
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/hapus",
                      type: "POST",
                      data: {'_token'         : token,
                            'BL_ID'           : id},
                      success: function(msg){
                          $('#table-index').DataTable().ajax.reload();                          
                          $.alert(msg);
                        }
                  });
                }
            },
            Tidak: function () {
            }
        }
    });
}

function kuncigiat(id){
  var token        = $('#token').val();  
  if($('#kuncigiat-'+id).is(':checked')){
    //kunci
    $.ajax({
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/kuncigiat",
      type: "POST",
      data: {'_token'         : token,
             'STATUS'         : 1,
             'BL_ID'          : id},
      success: function(msg){
        $('#table-index').DataTable().ajax.reload();                          
        $.alert(msg);
      }
    });
  }else{
    //buka
    $.ajax({
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/kuncigiat",
      type: "POST",
      data: {'_token'         : token,
             'STATUS'         : 0,
             'BL_ID'          : id},
      success: function(msg){
        $('#table-index').DataTable().ajax.reload();                          
        $.alert(msg);
      }
    });
  }
}

  function kuncirincian(id){
    var token        = $('#token').val();  
    if($('#kuncirincian-'+id).is(':checked')){
      //kunci
      $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/kuncirincian",
        type: "POST",
        data: {'_token'         : token,
               'STATUS'         : 1,
               'BL_ID'          : id},
        success: function(msg){
          $('#table-index').DataTable().ajax.reload();                          
          $.alert(msg);
        }
      });
    }else{
      //buka
      $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/kuncirincian",
        type: "POST",
        data: {'_token'         : token,
               'STATUS'         : 0,
               'BL_ID'          : id},
        success: function(msg){
          $('#table-index').DataTable().ajax.reload();                          
          $.alert(msg);
        }
      });
  }
}

function validasi(id){
    var token        = $('#token').val();    
    $.confirm({
        title: 'Validasi!',
        content: 'Yakin Validasi Kegiatan ?',
        buttons: {
            Ya: {
                btnClass: 'btn-danger',
                action: function(){
                  $.ajax({
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/validasi",
                      type: "POST",
                      data: {'_token'         : token,
                            'BL_ID'           : id},
                      success: function(msg){
                          $('#table-index').DataTable().ajax.reload();                          
                          $.alert(msg);
                        }
                  });
                }
            },
            Tidak: function () {
            }
        }
    });
}


function staff(id){
  $('.overlay').fadeIn('fast',function(){
    $('.input-staff').animate({'right':'0'},"linear");  
    $("html, body").animate({ scrollTop: 0 }, "slow");
  });
  $('#id-belanja').val(id);
  $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/staff/"+id,
      success : function (data) {
        if(data != 'KOSONG'){
          console.log(data[0]['USER_ID']);
          $('#staff-1').val(data[0]['USER_ID']).trigger('chosen:updated');
          $('#id-staff1').val(data[0]['STAFF_ID']);
          if(data[1] != null){
            $('#staff-2').val(data[1]['USER_ID']).trigger('chosen:updated');
            $('#id-staff2').val(data[1]['STAFF_ID']);
          }
        }
      }
  });
}

function setStaff(){
  token       = $('#token').val();  
  id          = $('#id-belanja').val();
  staff1      = $('#staff-1').val(); 
  staff2      = $('#staff-2').val(); 
  idstaff1    = $('#id-staff1').val(); 
  idstaff2    = $('#id-staff2').val(); 
  if(staff1 == '') $.alert('Harap isi staff miniman satu staff!');
  if(staff1 == staff2){
     $.alert('Staf tidak boleh sama!'); 
  }else{
    $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/setStaff",
        type: "POST",
        data: {'_token'         : token,
               'staff1'         : staff1,
               'staff2'         : staff2,
               'idstaff1'       : idstaff1,
               'idstaff2'       : idstaff2,
               'BL_ID'          : id},
        success: function(msg){
          $('#table-index').DataTable().ajax.reload();                          
          $.alert(msg);
          $('.input-staff').animate({'right':'-1050px'},function(){
              $('.overlay').fadeOut('fast');
          });
        }
      });
  }
}

  $('#filter-skpd').change(function(e, params){
      var id  = $('#filter-skpd').val();
      $('#table-index').DataTable().destroy();
      $('#table-index').DataTable({
        sAjaxSource: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/getMurni/"+id,
        aoColumns: [
          { mData: 'NO',class:'text-center' },
          { mData: 'KEGIATAN' },
          { mData: 'PAGU' },
          { mData: 'RINCIAN' },
          { mData: 'STATUS' }],
          initComplete:function(setting,json){
            $("#pagu_foot").html(json.pagu_foot);
            $("#rincian_foot").html(json.rincian_foot);
        }
      });  
  });

  function log(id){
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/log/"+id,
      success : function (data) {
          $('#creator').text(data['header']['creator']);
          $('#staff1').text(data['header']['staff1']);
          $('#staff2').text(data['header']['staff2']);
          $('#created').text(data['header']['created']);
          $('#updated').text(data['header']['updated']);
          $('#timeline-log').html(data['header']['log']);
          $('#info').modal('show');
      }
    });
  }

  function kunciGiatSKPD(){
    var id           = $('#filter-skpd').val();
    var token        = $('#token').val();  
      if($('#kuncigiatskpd').is(':checked')){
        //kunci
        $.ajax({
          url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/kuncigiatskpd",
          type: "POST",
          data: {'_token'         : token,
                 'STATUS'         : 1,
                 'SKPD_ID'        : id},
          success: function(msg){
            $('#table-index').DataTable().ajax.reload();                          
            $.alert(msg);
          }
        });
      }else{
        //buka
        $.ajax({
          url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/kuncigiatskpd",
          type: "POST",
          data: {'_token'         : token,
                 'STATUS'         : 0,
                 'SKPD_ID'        : id},
          success: function(msg){
            $('#table-index').DataTable().ajax.reload();                          
            $.alert(msg);
          }
        });
      }
  }

  function kunciRincianSKPD(){
    var id           = $('#filter-skpd').val();
    var token        = $('#token').val();  
      if($('#kuncirincianskpd').is(':checked')){
        //kunci
        $.ajax({
          url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/kuncirincianskpd",
          type: "POST",
          data: {'_token'         : token,
                 'STATUS'         : 1,
                 'SKPD_ID'        : id},
          success: function(msg){
            $('#table-index').DataTable().ajax.reload();                          
            $.alert(msg);
          }
        });
      }else{
        //buka
        $.ajax({
          url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/kuncirincianskpd",
          type: "POST",
          data: {'_token'         : token,
                 'STATUS'         : 0,
                 'SKPD_ID'        : id},
          success: function(msg){
            $('#table-index').DataTable().ajax.reload();                          
            $.alert(msg);
          }
        });
      }
  }

  function setpagu(id){
    $('#id-bl').val(id);
    $.ajax({
        type  : "get",
        url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/getpagu/"+id,
        success : function (data) {
          $('#pagu').val(data['BL_PAGU']);
          $('#pagu_catatan').val(data['BL_PAGU_CATATAN']);
        }
    });
    $('#modal-pagu').modal('show');    
  }
  function simpanpagu(){
    var token        = $('#token').val();    
    var id           = $('#id-bl').val();    
    var pagu         = $('#pagu').val();    
    var catatan       = $('#pagu_catatan').val();    
    $.ajax({
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/setpagu",
      type: "POST",
      data: {'_token'         : token,
             'BL_PAGU'        : pagu,
             'BL_PAGU_CATATAN': catatan,
             'BL_ID'          : id},
      success: function(msg){
        $('#table-index').DataTable().ajax.reload();
        $('#modal-pagu').modal('hide');                          
        $.alert(msg);
      }
    });  
  }

  function seturgensi(id){
    @if(Auth::user()->level == 2){
      $('#id_bl').val(id);
      $.ajax({
        type  : "get",
        url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/geturgensi/"+id,
        success : function (data) {
          $('#urgensi_latar_belakang').val(data['URGENSI_LATAR_BELAKANG']);
          $('#urgensi_deskripsi').val(data['URGENSI_DESKRIPSI']);
          $('#urgensi_tujuan').val(data['URGENSI_TUJUAN']);
          $('#urgensi_lokasi').val(data['URGENSI_LOKASI']);
          $('#urgensi_penerima').val(data['URGENSI_PENERIMA_1']);
          $('#urgensi_pelaksanaan').val(data['URGENSI_PELAKSANAAN']);
        }
    });
      $('#modal-urgensi').modal('show');
    }@elseif(Auth::user()->level == 1){
      $.alert('Harap Isi Urgensi Di Akun Kepala OPD');
    }@endif
  }

  function simpanurgensi(){
    var token           = $('#token').val();
    var id              = $('#id_bl').val();
    var latarbelakang   = $('#urgensi_latar_belakang').val();
    var deskripsi       = $('#urgensi_deskripsi').val();
    var tujuan          = $('#urgensi_tujuan').val();
    var lokasi          = $('#urgensi_lokasi').val();
    var penerima        = $('#urgensi_penerima_rincian').val();
    var pelaksanaan     = $('#urgensi_pelaksanaan').val();   
    $.ajax({
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/urgensi/simpan",
      type: "POST",
      data: {'_token'                     : token,
             'BL_ID'                      : id,
             'URGENSI_LATAR_BELAKANG'     : latarbelakang,
             'URGENSI_DESKRIPSI'          : deskripsi,
             'URGENSI_TUJUAN'             : tujuan,
             'URGENSI_PENERIMA'           : penerima,
             'URGENSI_LOKASI'             : lokasi,
             'URGENSI_PELAKSANAAN'        : pelaksanaan},
      success: function(msg){ 
        $('#table-index').DataTable().ajax.reload();                               
        $.alert(msg);
      }
    });  
  }

  $('#urgensi_penerima').change(function(){
    penerima  = $('#urgensi_penerima').val();
    if(penerima == 'masyarakat'){
      $("#urgensi_penerima_rincian").find('option').remove();
      $('#urgensi_penerima_rincian').append('<option>Seluruh Masyarakat</option><option>Masyarakat Miskin</option><option>Masyarakat Berkebutuhan Khusus</option><option>Lansia</option><option>Anak-Anak (di bawah 12 tahun)</option><option>Pemuda</option><option>Remaja</option><option>Ibu-Ibu</option><option>Pelajar</option><option>Pengusaha</option><option>Pengangguran</option><option>Pengajar</option>');
    }else if(penerima == 'pns'){
      $("#urgensi_penerima_rincian").find('option').remove();
      $('#urgensi_penerima_rincian').append('<option>Seluruh SKPD</option><option>SKPD Tertentu</option>');
    }
  });
</script>
@endsection