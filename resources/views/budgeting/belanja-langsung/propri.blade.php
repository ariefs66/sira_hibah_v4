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
            <li class="active"><i class="fa fa-angle-right"></i>Program Prioritas Belanja Langsung</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                  <button class="pull-right btn m-t-n-sm btn-success open-form-btl"><i class="m-r-xs fa fa-plus"></i> Tambah Belanja Tidak Langsung</button>
                  @endif
                  <a class="pull-right btn btn-info m-t-n-sm m-r-sm" href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/download/rekapbtl"><i class="m-r-xs fa fa-download"></i> Download</a>
                  <h5 class="inline font-semibold text-orange m-n ">Program Prioritas Belanja Langsung</h5>
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
              <!--  <div class="nav-tabs-alt tabs-alt-1 b-t five-row" id="tab-jurnal" >
                <ul class="nav nav-tabs" role="tablist">
                 <li class="active">
                  <a data-target="#tab-1" role="tab" data-toggle="tab">Pegawai</a>
                </li>
                <li>
                  <a data-target="#tab-2" role="tab" data-toggle="tab">Subsidi</a>
                </li>
                <li>
                  <a data-target="#tab-3" role="tab" data-toggle="tab">Hibah</a>
                </li>
                <li>
                  <a data-target="#tab-4" role="tab" data-toggle="tab">Bantuan Keuangan</a> 
                </li>
                <li>
                  <a data-target="#tab-5" role="tab" data-toggle="tab">BTT</a> 
                </li>

              </ul>

            </div> -->
            <!-- / main tab -->                  
            <div class="tab-content tab-content-alt-1 bg-white">
              <div role="tabpanel" class="active tab-pane " id="tab-1">  
                <div class="table-responsive dataTables_wrapper table-btl">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/belanja-langsung/propri/skpd',
                    aoColumns: [
                    { mData: 'ID',class:'hide'},
                    { mData: 'KODE'},
                    { mData: 'NAMA'}
                  ]}" class="table table-btl table-striped b-t b-b table-pegawai " id="table-pegawai">
                  <thead>
                    <tr>
                      <th class="hide">ID</th>                  
                      <th>Kode Perangkat Daerah</th>
                      <th>Nama Perangkat Daerah</th>
                    </tr>
                    <tr>
                      <th class="hide"></th>
                      <th class="hide"></th>
                      <th colspan="3" class="th_search">
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
<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-btl">
  <a class="close"><i class="icon-bdg_cross"></i></a>
  <form class="form-horizontal">
    <div class="input-wrapper">
      <h5>Atur Pagu</h5>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Perangkat Daerah</label>          
        <div class="col-sm-9">
          <input type="hidden" class="form-control" placeholder="ID" id="propri_id" disabled>  
          <input type="hidden" class="form-control" placeholder="Tahun" id="tahun" disabled>
          <input type="text" class="form-control" placeholder="Nama Perangkat Daerah" id="namaskpd" disabled>          
          <input type="hidden" class="form-control" placeholder="Perangkat Daerah" id="skpd" disabled>          
        </div> 
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Nama Program</label>          
        <div class="col-sm-5">
          <input type="text" class="form-control" placeholder="Nama Program" id="namaprogram" disabled>      
          <input type="hidden" class="form-control" placeholder="Program" id="program" disabled>      
        </div> 
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Pagu</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Masukan Pagu" id="pagu" >          
        </div> 
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Kunci</label>          
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="kunci">
            <option value="">Pilih Kunci</option>
            <option value="1">Ya</option>
            <option value="0">Tidak</option>
          </select>        
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
        <th>Kode Program</th>                          
        <th>Nama Program</th>                       
        <th>Pagu</th>                                       
        <th>KUNCI</th>                                       
        <th>#</th>                                       
      </tr>                                       
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
      skpd = $(this).children("td").eq(0).html();
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
          sAjaxSource: "/main/{{ $tahun }}/{{ $status }}/belanja-langsung/propri/getDetail/"+skpd,
          aoColumns: [
          { mData: 'NO' },
          { mData: 'KODE' },
          { mData: 'NAMA' },
          { mData: 'PAGU' },
          { mData: 'KUNCI' },
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

  function simpanBTL(){
    var token           = $('#token').val();    
    var id        = $('#propri_id').val();
    var SKPD_ID     = $('#skpd').val();
    var PROGRAM_ID        = $('#program').val();
    var TAHUN         = $('#tahun').val();
    var PAGU      = $('#pagu').val();
    var KUNCI       = $('#kunci').val();
    if(PAGU == "" || PROGRAM_ID == "" || SKPD_ID == "" || KUNCI == ""){
      $.alert('Form harap diisi!');
    }else{
      if(id == ""){
        uri   = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/propri/simpan";
      }else{
        uri   = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/propri/ubah";
      }
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'              : token,
              'PROPRI_ID'         : id, 
              'PROPRI_TAHUN'         : TAHUN, 
              'SKPD_ID'              : SKPD_ID, 
              'PROGRAM_ID'           : PROGRAM_ID,
              'PROPRI_PAGU'          : PAGU, 
              'PROPRI_KUNCI'         : KUNCI},
        success: function(msg){
          $('.table-pegawai').DataTable().ajax.reload();
          $(".shown").trigger('click');
          $.alert(msg);
          $('.input-btl,.input-sidebar').animate({'right':'-1050px'},function(){
              $('.overlay').fadeOut('fast');
          });
        $('#namaskpd').val("");
        $('#namaprogram').val("");
        $('#program').val("");
        $('#skpd').val("");
        $('#tahun').val("");
        $('#pagu').val("");
        $('#kunci').val("");
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
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/propri/hapus",
                      type: "POST",
                      data: {'_token'      : token,
                            'PROPRI_ID'       : id},
                      success: function(msg){
                          $.alert(msg);
                          $('.table-pegawai').DataTable().ajax.reload();              
                        }
                  });
                }
            },
            Tidak: function () {
            }
        }
    });
  }

  function ubah(skpd, id) {
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/propri/edit/"+skpd+"/"+id,
      success : function (data) {
        data = data.aaData[0];
        $('#namaskpd').val(data['SKPD_NAMA']);
        $('#namaprogram').val(data['PROGRAM_NAMA']);
        $('#program').val(data['PROGRAM_ID']);
        $('#skpd').val(data['SKPD_ID']);
        $('#tahun').val(data['PROPRI_TAHUN']);
        $('#pagu').val(data['PROPRI_PAGU']);
        $('#kunci').val(data['PROPRI_KUNCI']).trigger('chosen:updated');
        $('#propri_id').val(data['PROPRI_ID']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-btl').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        }); 
      }
    });   
  } 
</script>
@endsection