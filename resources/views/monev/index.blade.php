@extends('monev.layout')

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
            <li><a href= "#">Dashboard</a></li>
            <li class="active"><i class="fa fa-angle-right"></i>Monev</li>                               
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  
                  <button class="pull-right btn m-t-n-sm btn-success open-form-btl"><i class="m-r-xs fa fa-plus"></i> Tambah Monev</button>
                  
                  <a class="pull-right btn btn-info m-t-n-sm m-r-sm" href="{{ url('/') }}/main/{{$tahun}}/download/rekapbtl"><i class="m-r-xs fa fa-download"></i> Download</a>
                  <a class="pull-right btn btn-danger m-t-n-sm m-r-sm" href="{{ url('/') }}/monev/{{$tahun}}/cetak"><i class="m-r-xs fa fa-file"></i> Print</a>
                  <h5 class="inline font-semibold text-orange m-n ">Monev</h5>
                  <div class="col-sm-1 pull-right m-t-n-sm">
                   <select class="form-control dtSelect" id="dtSelect">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                 </div>                    
               </div>
               <!-- Main tab -->
               <div class="nav-tabs-alt tabs-alt-1 b-t four-row" id="tab-jurnal" >
                <ul class="nav nav-tabs" role="tablist">
                 <li class="active">
                  <a data-target="#tab-1" role="tab" data-toggle="tab">Triwulan 1</a>
                </li>
                <li>
                  <a data-target="#tab-2" role="tab" data-toggle="tab">Triwulan 2</a>
                </li>
                <li>
                  <a data-target="#tab-3" role="tab" data-toggle="tab">Triwulan 3</a>
                </li>
                <li>
                  <a data-target="#tab-4" role="tab" data-toggle="tab">Triwulan 4</a> 
                </li>

              </ul>

            </div>
            <!-- / main tab -->                  
            <div class="tab-content tab-content-alt-1 bg-white">
              <div role="tabpanel" class="active tab-pane " id="tab-1">  
                <div class="table-responsive dataTables_wrapper table-btl">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/monev/{{$tahun}}/getTriwulan1',
                    aoColumns: [
                    { mData: 'ID'},
                    { mData: 'PROGRAM_ID', sClass:'hide'},
                    { mData: 'PROGRAM'},
                    { mData: 'OUTCOME'},
                    { mData: 'TARGET'},
                    { mData: 'KINERJA'},
                    { mData: 'TOTAL'}
                  ]}" class="table table-btl table-striped b-t b-b table-pegawai " id="table-pegawai">
                  <thead>
                    <tr>                   
                      <th>No </th>
                      <th class="hide">ID </th>
                      <th>PROGRAM </th>
                      <th>OUTCOME </th>
                      <th>TARGET </th>
                      <th>KINERJA </th>
                      <th>TOTAL </th>
                    </tr>
                    <tr>
                      <th class="hide"></th>                    
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-pegawai form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-2">  
                <div class="table-responsive dataTables_wrapper table-btl">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/monev/{{$tahun}}/getTriwulan2',
                    aoColumns: [
                    { mData: 'ID'},
                    { mData: 'PROGRAM_ID', sClass:'hide'},
                    { mData: 'PROGRAM'},
                    { mData: 'OUTCOME'},
                    { mData: 'TARGET'},
                    { mData: 'KINERJA'},
                    { mData: 'TOTAL'}
                  ]}" class="table table-btl table-striped b-t b-b table-subsidi" id="table-subsidi">
                 <thead>
                    <tr>                   
                      <th>No </th>
                      <th class="hide">ID </th>
                      <th>PROGRAM </th>
                      <th>OUTCOME </th>
                      <th>TARGET </th>
                      <th>KINERJA </th>
                      <th>TOTAL </th>
                    </tr>
                    <tr>
                      <th class="hide"></th>                    
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-pegawai form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-3">  
                <div class="table-responsive dataTables_wrapper table-btl">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/monev/{{$tahun}}/getTriwulan3',
                    aoColumns: [
                    { mData: 'ID'},
                    { mData: 'PROGRAM_ID', sClass:'hide'},
                    { mData: 'PROGRAM'},
                    { mData: 'OUTCOME'},
                    { mData: 'TARGET'},
                    { mData: 'KINERJA'},
                    { mData: 'TOTAL'}
                  ]}" class="table table-btl table-striped b-t b-b table-hibah" id="table-hibah">
                  <thead>
                    <tr>                   
                      <th>No </th>
                      <th class="hide">ID </th>
                      <th>PROGRAM </th>
                      <th>OUTCOME </th>
                      <th>TARGET </th>
                      <th>KINERJA </th>
                      <th>TOTAL </th>
                    </tr>
                    <tr>
                      <th class="hide"></th>                    
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-pegawai form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                      <tr>
                        <td><b>Total</b></td>
                        <td><b><text id="foot"></text></b></td>
                      </tr>
                    </tfoot>  
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-4">  
                <div class="table-responsive dataTables_wrapper table-btl">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/monev/{{$tahun}}/getTriwulan4',
                    aoColumns: [
                    { mData: 'ID'},
                    { mData: 'PROGRAM_ID', sClass:'hide'},
                    { mData: 'PROGRAM'},
                    { mData: 'OUTCOME'},
                    { mData: 'TARGET'},
                    { mData: 'KINERJA'},
                    { mData: 'TOTAL'}
                  ]}" class="table table-btl table-striped b-t b-b table-bantuan" id="table-bantuan">
                 <thead>
                    <tr>                   
                      <th>No </th>
                      <th class='hide'>ID </th>
                      <th>PROGRAM </th>
                      <th>OUTCOME </th>
                      <th>TARGET </th>
                      <th>KINERJA </th>
                      <th>TOTAL </th>
                    </tr>
                    <tr>
                      <th class="hide"></th>                    
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-pegawai form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
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
<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-btl">
  <a class="close"><i class="icon-bdg_cross"></i></a>
  <form class="form-horizontal">
    <div class="input-wrapper">
      <h5>Edit Data </h5>
      <div class="form-group">
        <label class="col-sm-3">Program</label>
        <div class="col-sm-9">
          <input type="hidden" id="id-btl">
          <select ui-jq="chosen" class="w-full" id="jenis-btl">
            <option value="">Silahkan Pilih Jenis</option>
            <option value="5.1.1">Pegawai</option>
            <option value="5.1.3">Subsidi</option>
            <option value="5.1.4">Hibah</option>
            <option value="5.1.7">Bantuan Keuangan</option>
            <option value="5.1.8">Belanja Tidak Terduga</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3">Kegiatan</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="skpd-btl">
            <option value="">Silahkan Pilih SKPD</option>
            @foreach($skpd as $s)
            <option value="{{ $s->SKPD_ID }}">{{ $s->SKPD_NAMA }}</option>
            @endforeach
          </select>
        </div>
      </div>   
      <div class="form-group">
        <label class="col-sm-3">Output</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="subunit-btl">
            <option value="">Silahkan Pilih Subunit</option>
          </select>
        </div>
      </div>   
      <div class="form-group">
        <label class="col-sm-3">output</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="rekening-btl">
            <option value="">Silahkan Pilih Rekening</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Peruntukan</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Masukan Keterangan" id="keterangan-btl">          
        </div> 
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Koefisien</label>          
        <div class="col-sm-5">
          <input type="text" class="form-control" placeholder="Masukan Jumlah" id="volume-btl">      
        </div> 
        <div class="col-sm-4">
          <select ui-jq="chosen" class="w-full" id="satuan-btl">
            <option value="">Satuan</option>
            @foreach($satuan as $sat)
            <option value="{{ $sat->SATUAN_NAMA }}">{{ $sat->SATUAN_NAMA }}</option>
            @endforeach
          </select>    
        </div>
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Anggaran</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Masukan Anggaran" id="total-btl" >          
        </div> 
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Dasar Hukum</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Dasar Hukum" id="dashuk" >          
        </div> 
      </div>

      <hr class="m-t-xl">
      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">      
      <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanBTL()"><i class="fa fa-check m-r-xs "></i>Simpan</a>
    </div>
  </form>
</div>
</div>

<div id="table-detail-btl" class="hide bg-white">
  <table ui-jq="dataTable" class="table table-detail-btl-isi table-striped b-t b-b">
    <thead>
      <tr>
        <th>No</th>                                    
        <th>KEGIATAN</th>                          
        <th>KINERJA</th>                                       
        <th>TOTAL</th>                                       
        <th>#</th>                                       
      </tr> 
      <!-- <tr>
        <th class="hide"></th>                    
        <th colspan="5" class="th_search">
          <i class="icon-bdg_search"></i>
          <input type="search" class="cari-detail form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
        </th>
      </tr> -->                                       
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
@endsection

@section('plugin')
<script>
  $('.table-btl').on('click', '.table-btl > tbody > tr ', function () {
    if($("tr").hasClass('btl-rincian') == false){
      skpd      = $(this).children("td").eq(0).html();
      kegiatan  = $(this).children("td").eq(1).html();
    }
    if(!$(this).hasClass('btl-rincian')){
      if($(this).hasClass('shown')){      
        $('.btl-rincian').slideUp('fast').remove(); 
        $(this).removeClass('shown'); 
      }else{
        $('.btl-rincian').slideUp('fast').remove(); 
        $(this).addClass('shown');
        btl_detail = '<tr class="btl-rincian"><td style="padding:0!important;" colspan="6">'+$('#table-detail-btl').html()+'</td></tr>';
        $(btl_detail).insertAfter('.table-btl .table tbody tr.shown');
        $('.table-detail-btl-isi').DataTable({
          sAjaxSource: "/monev/{{ $tahun }}/getDetail/"+skpd+"/"+kegiatan,
          aoColumns: [
          { mData: 'NO' },
          { mData: 'KEGIATAN' },
          { mData: 'KINERJA' },
          { mData: 'TOTAL' },
          { mData: 'AKSI' }
          ]
        });
      }
    }
  });
</script>
<script type="text/javascript">
  $('input.cari-pegawai').keyup( function () {
    $('.table-pegawai').DataTable().search($('.cari-pegawai').val()).draw();
  });
  $('input.cari-subsidi').keyup( function () {
    $('.table-subsidi').DataTable().search($('.cari-subsidi').val()).draw();
  });
  $('input.cari-hibah').keyup( function () {
    $('.table-hibah').DataTable().search($('.cari-hibah').val()).draw();
  });
  $('input.cari-bantuan').keyup( function () {
    $('.table-bantuan').DataTable().search($('.cari-bantuan').val()).draw();
  });
  $('input.cari-btt').keyup( function () {
    $('.table-btt').DataTable().search($('.cari-btt').val()).draw();
  });

  $("#jenis-btl").change(function(e, params){
    var id  = $('#jenis-btl').val();
    $('#rekening-btl').find('option').remove().end().append('<option>Pilih Rekening</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/belanja-tidak-langsung/getRekening/"+id,
      success : function (data) {
        $('#rekening-btl').append(data).trigger('chosen:updated');
      }
    });
  });

  function simpanBTL(){
    var id              = $('#jenis-btl').val();
    var token           = $('#token').val();    
    var SUB_ID          = $('#subunit-btl').val();
    var REKENING_ID     = $('#rekening-btl').val();
    var BTL_NAMA        = $('#keterangan-btl').val();
    var BTL_VOL         = $('#volume-btl').val();
    var BTL_SATUAN      = $('#satuan-btl').val();
    var BTL_TOTAL       = $('#total-btl').val();
    var BTL_DASHUK       = $('#dashuk').val();
    var BTL_ID          = $('#id-btl').val();
    if(SUB_ID == "" || BTL_NAMA == "" || BTL_VOL == "" || BTL_SATUAN == "" || BTL_TOTAL == ""){
      $.alert('Form harap diisi!');
    }else{
      if(BTL_ID == ""){
        uri   = "{{ url('/') }}/main/{{ $tahun }}/belanja-tidak-langsung/simpan";
      }else{
        uri   = "{{ url('/') }}/main/{{ $tahun }}/belanja-tidak-langsung/ubah";
      }
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'BTL_ID'          : BTL_ID, 
              'SUB_ID'          : SUB_ID, 
              'REKENING_ID'     : REKENING_ID,
              'BTL_NAMA'        : BTL_NAMA, 
              'BTL_VOL'         : BTL_VOL, 
              'BTL_SATUAN'      : BTL_SATUAN, 
              'BTL_DASHUK'      : BTL_DASHUK, 
              'BTL_TOTAL'       : BTL_TOTAL},
        success: function(msg){
          $('.table-pegawai').DataTable().ajax.reload();
          $('.table-subsidi').DataTable().ajax.reload();
          $('.table-hibah').DataTable().ajax.reload();
          $('.table-bantuan').DataTable().ajax.reload();
          $('.table-btt').DataTable().ajax.reload();
          $(".shown").trigger('click');
          $.alert(msg);
          $('.input-btl,.input-sidebar').animate({'right':'-1050px'},function(){
              $('.overlay').fadeOut('fast');
          });
          $('#jenis-btl').val("").trigger("chosen:updated");
          $('#skpd-btl').val("").trigger("chosen:updated");
          $('#subunit-btl').val("").trigger("chosen:updated");
          $('#rekening-btl').val("").trigger("chosen:updated");
          $('#keterangan-btl').val("");
          $('#volume-btl').val("");
          $('#satuan-btl').val("").trigger("chosen:updated");
          $('#total-btl').val("");
          $('#dashuk').val("");
          $('#id-btl').val("");
        }
      });
    }
  }  

  $("#skpd-btl").change(function(e, params){
    var id  = $('#skpd-btl').val();
    $('#subunit-btl').find('option').remove().end().append('<option>Pilih Subunit</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/belanja-tidak-langsung/subunit/"+id,
      success : function (data) {
        $('#subunit-btl').append(data).trigger('chosen:updated');
      }
    });
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
                      url: "{{ url('/') }}/main/{{ $tahun }}/belanja-tidak-langsung/hapus",
                      type: "POST",
                      data: {'_token'      : token,
                            'BTL_ID'       : id},
                      success: function(msg){
                          $.alert(msg);
                          $('.table-pegawai').DataTable().ajax.reload();
                          $('.table-subsidi').DataTable().ajax.reload();
                          $('.table-hibah').DataTable().ajax.reload();
                          $('.table-bantuan').DataTable().ajax.reload();
                          $('.table-btt').DataTable().ajax.reload();                   
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
      url   : "{{ url('/') }}/main/{{ $tahun }}/belanja-tidak-langsung/edit/"+id,
      success : function (data) {
        $('#id-btl').val(data['BTL_ID']);
        $('#jenis-btl').val(data['JENIS_BTL']).trigger("chosen:updated");
        $('#skpd-btl').val(data['SKPD']).trigger("chosen:updated");
        $('#subunit-btl').append('<option value="'+data['SUB_ID']+'" selected>'+data['SUB_NAMA']+'</option>').trigger("chosen:updated");
        $('#rekening-btl').append('<option value="'+data['REKENING_ID']+'" selected>'+data['REKENING_KODE']+'-'+data['REKENING_NAMA']+'</option>').trigger("chosen:updated");
        $('#keterangan-btl').val(data['BTL_KETERANGAN']);
        $('#volume-btl').val(data['BTL_VOLUME']);
        $('#total-btl').val(data['BTL_TOTAL']);
        $('#dashuk').val(data['BTL_DASHUK']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-btl').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        }); 
      }
    });   
  } 
</script>
@endsection