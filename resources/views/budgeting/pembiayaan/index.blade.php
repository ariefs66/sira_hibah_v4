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
            <li class="active"><i class="fa fa-angle-right"></i>Pembiayaan</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
              <div class="panel bg-white">

                <div class="wrapper-lg">
                  
                  <h5 class="inline font-semibold text-orange m-n ">Pembiayaan</h5>
                  <div class="col-sm-1 pull-right m-t-n-sm">
                   <select class="form-control dtSelect" id="dtSelect">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                 </div>                    
               </div>
               



            <!-- / main tab -->                  
            <div class="tab-content tab-content-alt-1 bg-white">
              <div role="tabpanel" class="active tab-pane " id="tab-1">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/pembiayaan/getData',
                    aoColumns: [
                    { mData: 'ID',class:'hide'},
                    { mData: 'REKENING_KODE'},
                    { mData: 'REKENING_NAMA'},
                    { mData: 'PEMBIAYAAN_DASHUK'},
                    { mData: 'PEMBIAYAAN_KETERANGAN'},
                    { mData: 'PEMBIAYAAN_TOTAL'},
                    { mData: 'OPSI'}
                  ]}" class="table table-striped b-t b-b table-pembiayaan" id="table-pembiayaan">
                  <thead>
                    <tr>
                      <th class="hide">ID</th>                    
                      <th>REKENING</th>                    
                      <th>URAIAN</th>
                      <th>DASAR HUKUM</th>
                      <th>KETERANGAN</th>
                      <th>TOTAL</th>
                      <th>OPSI</th>
                    </tr>
                    <tr>
                      <th class="hide"></th>
                      <th colspan="7" class="th_search">
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
<div class="bg-white wrapper-lg input-sidebar input-pembiayaan">
  <a class="close"><i class="icon-bdg_cross"></i></a>
  <form class="form-horizontal">
    <div class="input-wrapper">
      <h5>Ubah Pembiayaan</h5>
      

      <div class="form-group">
        <label for="no_spp" class="col-md-3">KODE REKENING</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="KODE REKENING" id="rek_kode" readonly="">          
        </div> 
      </div>
      <div class="form-group">
        <label for="no_spp" class="col-md-3">URAIAN</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="URAIAN" id="rek_nama" readonly="">          
        </div> 
      </div>
      <div class="form-group">
        <label for="no_spp" class="col-md-3">DASAR HUKUM</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="DASAR HUKUM" id="dashuk" name="dashuk">          
        </div> 
      </div>
      <div class="form-group">
        <label for="no_spp" class="col-md-3">KETERANGAN</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="KETERANGAN" id="keterangan" name="keterangan">          
        </div> 
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">TOTAL PEMBIAYAAN</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="TOTAL PEMBIAYAAN" id="totalpembiayaan" name="total-pembiayaan" required="">          
        </div> 
      </div>

      <hr class="m-t-xl">
      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">      
      <input type="hidden" name="idpembiayaan" id="idpembiayaan">      
      <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanPembiayaan()"><i class="fa fa-check m-r-xs "></i>Simpan</a>
    </div>
  </form>
</div>
</div>


@endsection

@section('plugin')
<script>
  $('.table-btl').on('click', '.table-btl > tbody > tr ', function () {
    if($("tr").hasClass('btl-rincian') == false){
      skpd = $(this).children("td").eq(0).html();
      rek  = $(this).children("td").eq(1).html();
    }
    if(!$(this).hasClass('btl-rincian')){
      if($(this).hasClass('shown')){      
        $('.btl-rincian').slideUp('fast').remove(); 
        $(this).removeClass('shown'); 
      }else{
        $('.btl-rincian').slideUp('fast').remove(); 
        $(this).addClass('shown');
        btl_detail = '<tr class="btl-rincian"><td style="padding:0!important;" colspan="3">'+$('#table-detail-btl').html()+'</td></tr>';
        $(btl_detail).insertAfter('.table-btl .table tbody tr.shown');
        $('.table-detail-btl-isi').DataTable({
          sAjaxSource: "/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/getDetail/"+skpd+"/"+rek,
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
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/getRekening/"+id,
      success : function (data) {
        $('#rekening-btl').append(data).trigger('chosen:updated');
      }
    });
  });

  function simpanPembiayaan(){
    var token           = $('#token').val();  
    var id              = $('#idpembiayaan').val();
    var dashuk          = $('#dashuk').val();
    var keterangan      = $('#keterangan').val();
    var total           = $('#totalpembiayaan').val();

    if(id == "" || total == "" ){
      $.alert('Form harap diisi!');
    }else{
      if(id != ""){
        uri   = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pembiayaan/update";
      }
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'                 : token,
              'PEMBIAYAAN_ID'           : id, 
              'PEMBIAYAAN_DASHUK'       : dashuk, 
              'PEMBIAYAAN_KETERANGAN'   : keterangan,
              'PEMBIAYAAN_TOTAL'        : total },
        success: function(msg){
          $('.table-pembiayaan').DataTable().ajax.reload();
          $(".shown").trigger('click');
          $.alert(msg);
          $('.input-btl,.input-sidebar').animate({'right':'-1050px'},function(){
              $('.overlay').fadeOut('fast');
          });
          $('#rek_kode').val("");
          $('#rek_nama').val("");
          $('#dashuk').val("");
          $('#keterangan').val("");
          $('#total-pembiayaan').val("");
        }
      });
    }
  }  

  $("#skpd-btl").change(function(e, params){
    var id  = $('#skpd-btl').val();
    $('#subunit-btl').find('option').remove().end().append('<option>Pilih Subunit</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/subunit/"+id,
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
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pembiayaan/hapus",
                      type: "POST",
                      data: {'_token'      : token,
                            'PEMBIAYAAN_ID': id},
                      success: function(msg){
                          $.alert(msg);
                          $('.table-pembiayaan').DataTable().ajax.reload();                  
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
    //alert(id);
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pembiayaan/edit/"+id,
      success : function (data) {
        $('#idpembiayaan').val(data['PEMBIAYAAN_ID']);
        $('#rek_kode').val(data['REKENING_KODE']);
        $('#rek_nama').val(data['REKENING_NAMA']);
        $('#dashuk').val(data['PEMBIAYAAN_DASHUK']);
        $('#keterangan').val(data['PEMBIAYAAN_KETERANGAN']);
        $('#totalpembiayaan').val(data['PEMBIAYAAN_TOTAL']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-pembiayaan').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        }); 
      }
    });   
  } 
</script>
@endsection