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
                <li class="active"><i class="fa fa-angle-right"></i>Trigger Rincian</li>                                
              </ul>
          </div>
          <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                    <button class="pull-right btn m-t-n-sm btn-success open-tahapan" id="btn-tambah"><i class="m-r-xs fa fa-plus"></i> Setting Rincian</button>
                    <h5 class="inline font-semibold text-orange m-n ">Tahapan Tahun {{ $tahun }}</h5>
                  </div>           
                  <div class="tab-content tab-content-alt-1 bg-white">
                        <div role="tabpanel" class="active tab-pane" id="tab-1">  
                            <div class="table-responsive dataTables_wrapper">
                             <table ui-jq="dataTable" ui-options="{
                                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/pengaturan/tahapan/getData',
                                    aoColumns: [
                                    { mData: 'TAHAPAN_ID',class:'hide' },
                                    { mData: 'STATUS',class:'hide' },
                                    { mData: 'no',class:'text-center' },
                                    { mData: 'TAHAPAN_NAMA' },
                                    { mData: 'TAHAPAN_AWAL' },
                                    { mData: 'TAHAPAN_AKHIR' }
                                    ]}" class="table table-striped b-t b-b" id="table-tahapan">
                                    <thead>
                                      <tr>
                                        <th class="hide"></th>
                                        <th class="hide"></th>
                                        <th>No</th>
                                        <th>Tahapan</th>
                                        <th>Awal</th>
                                        <th>Akhir</th>
                                      </tr>
                                      <tr>
                                        <th class="hide"></th>
                                        <th class="hide"></th>
                                        <th colspan="4" class="th_search">
                                            <i class="icon-bdg_search"></i>
                                            <input type="search" class="table-search form-control b-none w-full" placeholder="Cari Tahapan" aria-controls="DataTables_Table_0">
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
<div class="bg-white wrapper-lg input-sidebar input-tahapan">
<a href="#" class="tutup-form"><i class="icon-bdg_cross"></i></a>
    <form id="form-urusan" class="form-horizontal">
      <div class="input-wrapper">
        <h5 id="judul-form">Trigger Data Rincian</h5>
          <div class="form-group">
            <label for="kode_urusan" class="col-md-3">Tahapan</label>          
            <div class="col-sm-9">
              <input type="hidden" class="form-control" value="{{ csrf_token() }}" name="_token" id="token">
              <select ui-jq="chosen" class="form-control" id="id_tahapan">
                <option value="">- Pilih Tahapan-</option>
                  @if(!empty($tahap))
                  @foreach($tahap as $th)
                  <option value="{{ $th->TAHAPAN_ID }}">{{ $th->TAHAPAN_TAHUN . '-' . $th->TAHAPAN_STATUS . '-' . $th->TAHAPAN_NAMA }}</option>
                  @endforeach
                  @endif
              </select>
            </div> 
          </div>
          <hr class="m-t-xl">
         <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanTahapan()"><i class="fa fa-plus m-r-xs "></i>Trigger</a>
      </div>
    </form>
  </div>
 </div>
@endsection

@section('plugin')
<script type="text/javascript">
  function simpanTahapan(){
    var id_tahapan   = $('#id_tahapan').val();
    var token        = $('#token').val();
    if(id_tahapan == ""){
      $.alert('Form harap diisi!');
    }else{
      uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/tahapan/trigger";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'id_tahapan'      : id_tahapan,
              'tahun'           : '{{$tahun}}', 
              'status'          : '{{$status}}'},
        success: function(msg){
            if(msg == 1){
              $('#id_tahapan').val('');
              $('#table-tahapan').DataTable().ajax.reload();              
              $.alert({
                title:'Info',
                content: 'Data berhasil dieksekusi',
                autoClose: 'ok|1000',
                buttons: {
                    ok: function () {
                      $('.input-sidebar,.input-tahapan').animate({'right':'-1050px'},function(){
                        $('.overlay').fadeOut('fast');
                      });
                      $('#table-tahapan').DataTable().ajax.reload();                      
                    }
                }
              });
            }else{
              $.alert('Data berhasil diupdate!');
            }
          }
        });
    }
  }

  function tutupTahapan(){
    var id_tahapan   = $('#id_tahapan').val();
    var token        = $('#token').val();
    $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/tahapan/tutup",
        type: "POST",
        data: {'_token'         : token,
              'TAHAPAN_ID'      : id_tahapan},
        success: function(msg){
              $.alert({
                title:'Info',
                content: msg,
                autoClose: 'ok|1000',
                buttons: {
                    ok: function () {
                      $('.input-sidebar,.input-tahapan').animate({'right':'-1050px'},function(){
                        $('.overlay').fadeOut('fast');
                      });
                      $('#table-tahapan').DataTable().ajax.reload();
                      location.reload();                      
                      $('#btn-tambah').removeClass('hide');                       
                    }
                }
              });
          }
        });  
  }

  function ubah(id) {
    $('#judul-form').text('Ubah Urusan');        
    $.ajax({
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/urusan/getData/"+id,
      type: "GET",
      success: function(msg){
        $('#kode_urusan').val(msg[0]['URUSAN_KODE']);
        $('#nama_urusan').val(msg[0]['URUSAN_NAMA']);
        $('#id_urusan').val(msg[0]['URUSAN_ID']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-btl').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        });
      }
    });    
  }

  $('a.tutup-form').click(function(){
        $('#awal').val('');
        $('#akhir').val('');
        $('#id_tahapan').val('');
        $('#btn-tutup').attr('disabled',true);        
  }); 

  $('#table-tahapan').on('click','tbody > tr', function(){
      if($(this).children('td').eq(1).html() != 1){
        $('#btn-tutup').attr('disabled',false);
        kode = $(this).children('td').eq(0).html();
        
        $.ajax({
          url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/tahapan/getData/"+kode,
          type: "GET",
          success: function(msg){
            $('#tahapan').val(msg[0]['TAHAPAN_NAMA']);
            $('#id_tahapan').val(msg[0]['TAHAPAN_ID']);
            $('#awal').val(msg[0]['TAHAPAN_AWAL']);
            $('#akhir').val(msg[0]['TAHAPAN_AKHIR']);
            if(msg[0]['TAHAPAN_KUNCI_GIAT'] == 1){
              $('#kunci-giat').attr('checked',true);
            }else{
              $('#kunci-giat').attr('checked',false);
            }
            if(msg[0]['TAHAPAN_KUNCI_OPD'] == 1){
              $('#kunci-opd').attr('checked',true);
            }else{
              $('#kunci-opd').attr('checked',false);
            }
            $('#awal').attr('readonly',true);
            $('.overlay').fadeIn('fast',function(){
              $('.input-tahapan').animate({'right':'0'},"linear");  
              $("html, body").animate({ scrollTop: 0 }, "slow");
            });
          }
        });
      }
  })

  $('.overlay').on('click',function(){      
      $('.input-sidebar').animate({'right':'-1050px'},"linear",function(){
        $('.overlay').fadeOut('fast');
      }); 
        $('#awal').val('');
        $('#akhir').val('');
        $('#id_tahapan').val('');
        $('#btn-tutup').attr('disabled',true);  
  });

  function subTahapan(){
    $('#modal_subtahapan').modal('show');
    loadTableSub();
  }

  function simpanSubTahapan(){
    var id_tahapan   = $('#id_tahapan').val();
    var token        = $('#token').val();
    var tahapan      = $('#text_sub_tahapan').val();
    var akhir      = $('#text_akhir_subtahapan').val();
    if(tahapan == "" || akhir == ""){
      $.alert('Form harap diisi!');
    }else{
      $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/subtahapan/add/submit",
        type: "POST",
        data: {'_token'         : token,
              'TAHAPAN_ID'      : id_tahapan,
              'TAHAPAN_NAMA'    : tahapan, 
              'TAHAPAN_AKHIR'   : akhir},
        success: function(msg){
              $.alert('OK!');
              loadTableSub();
          }
      });
    }    
  }

  function loadTableSub(){
    id  = $('#id_tahapan').val();
    $('#tabel_subtahapan').DataTable().destroy();
    $('#tabel_subtahapan').DataTable({
      sAjaxSource     : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/subtahapan/getData/"+id,
          aoColumns       : [
                              { mData: 'NAMA' },
                              { mData: 'TGL'}
                            ]
      });    
  }
</script>
@endsection


