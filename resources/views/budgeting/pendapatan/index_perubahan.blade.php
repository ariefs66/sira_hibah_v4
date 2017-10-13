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
            <li class="active"><i class="fa fa-angle-right"></i>Pendapatan</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  @if(Auth::user()->level == 9 
                      or substr(Auth::user()->mod,10,1) == 1
                      or substr(Auth::user()->mod,0,1) == 1)
                  <button class="pull-right btn m-t-n-sm btn-success open-form-pendapatan"><i class="m-r-xs fa fa-plus"></i> Tambah Pendapatan</button>
                  @endif
                  <a class="pull-right btn btn-info m-t-n-sm m-r-sm" href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/download/rekappendapatan"><i class="m-r-xs fa fa-download"></i> Download</a>
                  <h5 class="inline font-semibold text-orange m-n ">Pendapatan</h5>
                  <div class="col-sm-1 pull-right m-t-n-sm">
                   <select class="form-control">
                     <option value="">Baris</option>
                     <option value="kegiatanA">10</option>
                     <option value="kegiatanA">25</option>
                     <option value="kegiatanA">50</option>
                     <option value="kegiatanA">100</option>
                   </select>
                 </div>                    
               </div>           
               <div class="tab-content tab-content-alt-1 bg-white">
                <div role="tabpanel" class="active tab-pane" id="tab-1">  
                  <div class="table-responsive dataTables_wrapper table-pendapatan">
                   <table ui-jq="dataTable" ui-options="{
                   sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/pendapatan/getData',
                   aoColumns: [
                   { mData: 'ID',class:'hide'},
                   { mData: 'KODE'},
                   { mData: 'NAMA'},
                   { mData: 'TOTAL_MURNI'},
                   { mData: 'TOTAL'}
                   ]}" class="table table-pendapatan table-striped b-t b-b table-pendapatan" id="table-pendapatan">
                   <thead>
                    <tr>
                      <th class="hide" rowspan="2">ID</th>
                      <th rowspan="2" style="text-align: center;">Kode Perangkat Daerah</th>
                      <th rowspan="2" style="text-align: center;">Nama Perangkat Daerah</th>
                      <th colspan="2" style="text-align: center;">Anggaran</th>
                    </tr>
                    <tr>
                      <th style="text-align: center;">Sebelum</th>
                      <th style="text-align: center;">Sesudah</th>
                    </tr>  
                    <tr>
                      <th class="hide"></th>
                      <th colspan="4" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="table-search form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                      <tr>
                        <td><b>Total</b></td>
                        <td><td>
                        <td><b>Rp. {{$anggaran}}</b></td>
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
<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-pendapatan">
  <a href="#" class="close"><i class="icon-bdg_cross"></i></a>
  <form class="form-horizontal">
    <div class="input-wrapper">
      <h5>Tambah Pendapatan</h5>
      <div class="form-group">
        <label class="col-sm-3">Unit</label>
        <div class="col-sm-9">
          <input type="hidden" id="id-pendapatan">
          <select ui-jq="chosen" class="w-full" id="skpd-pendapatan" required="">
            <option value="">Silahkan Pilih SKPD</option>
            @foreach($skpd as $s)
            <option value="{{ $s->SKPD_ID }}">{{ $s->SKPD_NAMA }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3">Sub Unit</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="subunit-pendapatan" required="">
            <option value="">Silahkan Pilih Sub Unit</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3">Rekening</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="rekening-pendapatan" required="">
            <option value="">Silahkan Pilih Rekening</option>
            @foreach($rekening as $rek)
            <option value="{{ $rek->REKENING_ID }}">{{ $rek->REKENING_KODE }} - {{ $rek->REKENING_NAMA }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Peruntukan</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Masukan Keterangan" id="nama-pendapatan" required="">          
        </div> 
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Anggaran</label>          
        <div class="col-sm-9">
          <input type="number" class="form-control" placeholder="Masukan Anggaran" id="total-pendapatan" required="">          
        </div> 
      </div>

      <hr class="m-t-xl">
      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">      
      <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanPendapatan()"><i class="fa fa-save m-r-xs "></i>Simpan Pendapatan</a>
    </div>
  </form>
</div>
</div>

<div id="table-detail-pendapatan" class="hide bg-white">
  <table ui-jq="dataTable" class="table table-detail-pendapatan-isi table-striped b-t b-b">
    <thead>
      <tr>
        <th>No</th>                                    
        <th>Rekening</th>                          
        <th>Rincian</th>                       
        <th>Anggaran</th>                                       
        <th>#</th>                                       
      </tr> 
      <tr>
        <th class="hide"></th>                    
        <th colspan="5" class="th_search">
          <i class="icon-bdg_search"></i>
          <input type="search" class="cari-detail form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
        </th>
      </tr>                                       
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
@endsection

@section('plugin')
<script>
  $('.table-pendapatan').on('click', '.table-pendapatan > tbody > tr ', function () {
    if($("tr").hasClass('pendapatan-rincian') == false){
      skpd = $(this).children("td").eq(0).html();
    }
    if(!$(this).hasClass('pendapatan-rincian')){
      if($(this).hasClass('shown')){      
        $('.pendapatan-rincian').slideUp('fast').remove();  
        $(this).removeClass('shown'); 
      }else{
        $('.pendapatan-rincian').slideUp('fast').remove();  
        $(this).addClass('shown');
        btl_detail = '<tr class="pendapatan-rincian"><td style="padding:0!important;" colspan="3">'+$('#table-detail-pendapatan').html()+'</td></tr>';
        $(btl_detail).insertAfter('.table-pendapatan .table tbody tr.shown');
        $('.table-detail-pendapatan-isi').DataTable({
          sAjaxSource: "/main/{{ $tahun }}/{{ $status }}/pendapatan/getDetail/"+skpd,
          aoColumns: [
          { mData: 'NO' },
          { mData: 'REKENING' },
          { mData: 'RINCIAN' },
          { mData: 'TOTAL' },
          { mData: 'AKSI' }
          ]
        });
      }
    }
  });
</script>
<script type="text/javascript">
  $("#skpd-pendapatan").change(function(e, params){
    var id  = $('#skpd-pendapatan').val();
    $('#subunit-pendapatan').find('option').remove().end().append('<option>Pilih Subunit</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pendapatan/subunit/"+id,
      success : function (data) {
        $('#subunit-pendapatan').append(data).trigger('chosen:updated');
      }
    });
  });

  function simpanPendapatan(){
    var token           = $('#token').val();    
    var SUB_ID          = $('#subunit-pendapatan').val();
    var REKENING_ID     = $('#rekening-pendapatan').val();
    var PENDAPATAN_NAMA = $('#nama-pendapatan').val();
    var PENDAPATAN_TOTAL= $('#total-pendapatan').val();
    var PENDAPATAN_ID   = $('#id-pendapatan').val();    
    if(SUB_ID == "" || REKENING_ID == "" || PENDAPATAN_NAMA == "" || PENDAPATAN_TOTAL == ""){
      $.alert('Form harap diisi!');
    }else{
      if(PENDAPATAN_ID == ""){
        uri    = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pendapatan/simpan";
      }else{
        uri    = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pendapatan/ubah";
      }
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'SUB_ID'          : SUB_ID, 
              'REKENING_ID'     : REKENING_ID,
              'PENDAPATAN_ID'   : PENDAPATAN_ID, 
              'PENDAPATAN_NAMA' : PENDAPATAN_NAMA, 
              'PENDAPATAN_TOTAL': PENDAPATAN_TOTAL},
        success: function(msg){
          $('#table-pendapatan').DataTable().ajax.reload();
          $.alert(msg);
          $('#id-pendapatan').val('');
          $('#skpd-pendapatan').val('').trigger('chosen:updated');
          $('#subunit-pendapatan').val('').trigger('chosen:updated');
          $('#rekening-pendapatan').val('').trigger('chosen:updated');
          $('#nama-pendapatan').val('').trigger('chosen:updated');
          $('#total-pendapatan').val('').trigger('chosen:updated');
          $('.input-pendapatan,.input-sidebar').animate({'right':'-1050px'},function(){
              $('.overlay').fadeOut('fast');
          });
        }
      });
    }
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
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pendapatan/hapus",
                      type: "POST",
                      data: {'_token'             : token,
                            'PENDAPATAN_ID'       : id},
                      success: function(msg){
                          $.alert(msg);
                          $('#table-pendapatan').DataTable().ajax.reload();                          
                        }
                  });
                }
            },
            Tidak: function () {
            }
        }
    });
  } 

   function ubah(id) {
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pendapatan/edit/"+id,
      success : function (data) {
        $('#id-pendapatan').val(data['PENDAPATAN_ID']);
        $('#skpd-pendapatan').val(data['SKPD']).trigger("chosen:updated");
        $('#subunit-pendapatan').append('<option value="'+data['SUB_ID']+'" selected>'+data['SUB_NAMA']+'</option>').trigger("chosen:updated");
        $('#rekening-pendapatan').append('<option value="'+data['REKENING_ID']+'" selected>'+data['REKENING_KODE']+'-'+data['REKENING_NAMA']+'</option>').trigger("chosen:updated");
        $('#nama-pendapatan').val(data['PENDAPATAN_NAMA']);
        $('#total-pendapatan').val(data['PENDAPATAN_TOTAL']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-pendapatan').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        }); 
      }
    });   
  } 
</script>
@endsection