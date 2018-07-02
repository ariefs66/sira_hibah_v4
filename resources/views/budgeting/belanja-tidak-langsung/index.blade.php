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
            <li class="active"><i class="fa fa-angle-right"></i>Belanja Tidak Langsung</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1 or Auth::user()->level == 2)
                  <button class="pull-right btn m-t-n-sm btn-success open-form-btl"><i class="m-r-xs fa fa-plus"></i> Tambah Belanja Tidak Langsung</button>
                  @endif
                  @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1 or Auth::user()->level == 2)
                  <button class="pull-right btn m-t-n-sm btn-warning" onclick="$('#modal-pagu').modal('show');"><i class="m-r-xs fa fa-plus"></i> Set Pagu</button>
                  @endif
<!--                  <a class="pull-right btn btn-info m-t-n-sm m-r-sm" href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/download/rekapbtl"><i class="m-r-xs fa fa-download"></i> Download</a> -->
                  <h5 class="inline font-semibold text-orange m-n ">Belanja Tidak Langsung</h5>
                  @if((Auth::user()->level == 2 or Auth::user()->level == 1) and substr(Auth::user()->mod,0,1) == 0)
                  <h5 class="inline font-semibold text-info m-n ">
                 Pagu OPD : {{ number_format(0,0,'.',',') }}
                  </h5>
                  @endif  
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
               <div class="nav-tabs-alt tabs-alt-1 b-t five-row" id="tab-jurnal" >
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

            </div>
            <!-- / main tab -->                  
            <div class="tab-content tab-content-alt-1 bg-white">
              <div role="tabpanel" class="active tab-pane " id="tab-1">  
                <div class="table-responsive dataTables_wrapper table-btl">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/belanja-tidak-langsung/pegawai',
                    aoColumns: [
                    { mData: 'ID',class:'hide'},
                    { mData: 'REK',class:'hide'},
                    { mData: 'KODE'},
                    { mData: 'NAMA'},
                    { mData: 'TOTAL'}
                    @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                    ,{ mData: 'AKSI'}
                    @endif
                  ]}" class="table table-btl table-striped b-t b-b table-pegawai " id="table-pegawai">
                  <thead>
                    <tr>
                      <th class="hide">ID</th>                    
                      <th class="hide">REK</th>                    
                      <th>Kode Perangkat Daerah</th>
                      <th>Nama Perangkat Daerah</th>
                      <th>Anggaran</th>
                      @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                      <th>#</th>
                      @endif
                    </tr>
                    <tr>
                      <th class="hide"></th>
                      <th class="hide"></th>
                      @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                      <th colspan="4" class="th_search">
                      @else
                      <th colspan="3" class="th_search">
                      @endif
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
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/belanja-tidak-langsung/subsidi',
                    aoColumns: [
                    { mData: 'ID',class:'hide'},
                    { mData: 'REK',class:'hide'},                                        
                    { mData: 'KODE'},
                    { mData: 'NAMA'},
                    { mData: 'TOTAL'}
                    @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                    ,{ mData: 'AKSI'}
                    @endif
                  ]}" class="table table-btl table-striped b-t b-b table-subsidi" id="table-subsidi">
                  <thead>
                    <tr>
                      <th class="hide">ID</th>
                      <th class="hide">REK</th>                      
                      <th>Kode Perangkat Daerah</th>
                      <th>Nama Perangkat Daerah</th>
                      <th>Anggaran</th>
                      @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                      <th>#</th>
                      @endif
                    </tr>
                    <tr>
                      <th class="hide"></th>                    
                      <th class="hide"></th>              
                      @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                      <th colspan="4" class="th_search">
                      @else
                      <th colspan="3" class="th_search">
                      @endif
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-subsidi form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
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
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/belanja-tidak-langsung/hibah',
                    aoColumns: [
                    { mData: 'ID',class:'hide'}, 
                    { mData: 'REK',class:'hide'},                                       
                    { mData: 'KODE'},
                    { mData: 'NAMA'},
                    { mData: 'TOTAL'}
                    @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                    ,{ mData: 'AKSI'}
                    @endif
                  ]}" class="table table-btl table-striped b-t b-b table-hibah" id="table-hibah">
                  <thead>
                    <tr>
                      <th class="hide">ID</th> 
                      <th class="hide">REK</th>                                         
                      <th>Kode Perangkat Daerah</th>
                      <th>Nama Perangkat Daerah</th>
                      <th>Anggaran</th>
                      @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                      <th>#</th>
                      @endif
                    </tr>
                    <tr>
                      <th class="hide"></th>                    
                      <th class="hide"></th>              
                      @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                      <th colspan="4" class="th_search">
                      @else
                      <th colspan="3" class="th_search">
                      @endif
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-hibah form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
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
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/belanja-tidak-langsung/bantuan',
                    aoColumns: [
                    { mData: 'ID',class:'hide'},  
                    { mData: 'REK',class:'hide'},                                      
                    { mData: 'KODE'},
                    { mData: 'NAMA'},
                    { mData: 'TOTAL'}
                    @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                    ,{ mData: 'AKSI'}
                    @endif
                  ]}" class="table table-btl table-striped b-t b-b table-bantuan" id="table-bantuan">
                  <thead>
                    <tr>
                      <th class="hide">ID</th> 
                      <th class="hide">REK</th>                                         
                      <th>Kode Perangkat Daerah</th>
                      <th>Nama Perangkat Daerah</th>
                      <th>Anggaran</th>
                      @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                      <th>#</th>
                      @endif
                    </tr>
                    <tr>
                      <th class="hide"></th>                    
                      <th class="hide"></th>              
                      @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                      <th colspan="4" class="th_search">
                      @else
                      <th colspan="3" class="th_search">
                      @endif
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-bantuan form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-5">  
                <div class="table-responsive dataTables_wrapper table-btl">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/belanja-tidak-langsung/btt',
                    aoColumns: [
                    { mData: 'ID',class:'hide'}, 
                    { mData: 'REK',class:'hide'},                                       
                    { mData: 'KODE'},
                    { mData: 'NAMA'},
                    { mData: 'TOTAL'}
                    @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                    ,{ mData: 'AKSI'}
                    @endif
                  ]}" class="table table-btl table-striped b-t b-b table-btt" id="table-btt">
                  <thead>
                    <tr>
                      <th class="hide">ID</th> 
                      <th class="hide">REK</th>                                         
                      <th>Kode Perangkat Daerah</th>
                      <th>Nama Perangkat Daerah</th>
                      <th>Anggaran</th>
                      @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                      <th>#</th>
                      @endif
                    </tr>
                    <tr>
                      <th class="hide"></th>                    
                      <th class="hide"></th>                  
                      @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
                      <th colspan="4" class="th_search">
                      @else
                      <th colspan="3" class="th_search">
                      @endif
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-btt form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
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

<div class="modal fade" id="modal-pagu" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Set Pagu</h5>
        <hr>
        <div class="col-sm-12">
          <select ui-jq="chosen" class="w-full" id="id-bl">
            <option value="">Silahkan Pilih SKPD</option>
            @foreach($skpd as $s)
            <option value="{{ $s->SKPD_ID }}">{{ $s->SKPD_NAMA }}</option>
            @endforeach
          </select>
        </div>
        
        <input type="text" id="pagu" class="form-control" placeholder="Besar Pagu">
        <textarea class="form-control m-t-sm" placeholder="Catatan" id="pagu_catatan"></textarea>
        <button class="btn btn-warning m-t-md" onclick="return simpanpagu()">Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-btl">
  <a class="close"><i class="icon-bdg_cross"></i></a>
  <form class="form-horizontal">
    <div class="input-wrapper">
      <h5>Tambah Belanja Tidak Langsung</h5>
      <div class="form-group">
        <label class="col-sm-3">Jenis BTL</label>
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
        <label class="col-sm-3">Perangkat Daerah</label>
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
        <label class="col-sm-3">Sub Unit</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="subunit-btl">
            <option value="">Silahkan Pilih Subunit</option>
          </select>
        </div>
      </div>   
      <div class="form-group">
        <label class="col-sm-3">Rekening</label>
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
          <input type="number" value="1" min="0" class="form-control" placeholder="Masukan Jumlah" id="volume-btl">      
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
          <input type="number" min="0" class="form-control" placeholder="Masukan Anggaran" id="total-btl" >          
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
        <th>Rekening</th>                          
        <th>Rincian</th>                       
        <th>Anggaran</th>                                       
        <th>AKB</th>                                       
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
        
        @if(Auth::user()->level == 9 or substr(Auth::user()->mod,0,1) == 1)
        btl_detail = '<tr class="btl-rincian"><td style="padding:0!important;" colspan="4">'+$('#table-detail-btl').html()+'</td></tr>';
        @else
        btl_detail = '<tr class="btl-rincian"><td style="padding:0!important;" colspan="3">'+$('#table-detail-btl').html()+'</td></tr>';
        @endif
        $(btl_detail).insertAfter('.table-btl .table tbody tr.shown');
        $('.table-detail-btl-isi').DataTable({
          sAjaxSource: "/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/getDetail/"+skpd+"/"+rek,
          aoColumns: [
          { mData: 'NO' },
          { mData: 'REKENING' },
          { mData: 'RINCIAN' },
          { mData: 'TOTAL' },
          { mData: 'AKB' },
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

  function simpanBTL(){
    var id              = $('#jenis-btl').val();
    var token           = $('#token').val();    
    var SKPD          = $('#skpd-btl').val();
    var SUB_ID          = $('#subunit-btl').val();
    var REKENING_ID     = $('#rekening-btl').val();
    var BTL_NAMA        = $('#keterangan-btl').val();
    var BTL_VOL         = $('#volume-btl').val();
    var BTL_SATUAN      = $('#satuan-btl').val();
    var BTL_TOTAL       = $('#total-btl').val();
    var BTL_DASHUK       = $('#dashuk').val();
    var BTL_ID          = $('#id-btl').val();
    if(SKPD == "" || SUB_ID == "" || BTL_NAMA == "" || BTL_VOL == "" || BTL_SATUAN == "" || BTL_TOTAL == ""){
      $.alert('Form harap diisi!');
    }else{
      if(BTL_ID == ""){
        uri   = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/simpan";
      }else{
        uri   = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/ubah";
      }
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'BTL_ID'          : BTL_ID, 
              'SKPD'          : SKPD, 
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
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/subunit/"+id,
      success : function (data) {
        $('#subunit-btl').append(data).trigger('chosen:updated');
      }
    });
  }); 

   $("#id-bl").change(function(e, params){
    var id  = $('#id-bl').val();
    $('#pagu').val("");
    $('#pagu_catatan').val("");
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/getpagu/"+id,
      success : function (data) {
          $('#pagu').val(data['BTL_PAGU']);
          $('#pagu_catatan').val(data['BTL_CATATAN']);
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
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/hapus",
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

function setPagu(id){
    $('#id-bl').val(id).trigger('chosen:updated');
    $('#pagu').val("");
    $('#pagu_catatan').val("");
    $.ajax({
        type  : "get",
        url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/getpagu/"+id,
        success : function (data) {
          $('#pagu').val(data['BTL_PAGU']);
          $('#pagu_catatan').val(data['BTL_CATATAN']);
        }
    });
    $('#modal-pagu').modal('show');    
  }
  
  function simpanpagu(){
    var token        = $('#token').val();    
    var id           = $('#id-bl').val();    
    var pagu         = $('#pagu').val();    
    var catatan      = $('#pagu_catatan').val();    
    if(id == "" || pagu == ""){
      $.alert('Form harap diisi!');
    }else{
      $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/setpagu",
        type: "POST",
        data: {'_token' : token,
              'pagu'   : pagu,
              'kunci'  : 0,
              'catatan': catatan,
              'skpd'   : id},
        success: function(msg){
          $('#modal-pagu').modal('hide');
          $('#id-bl').val(0).trigger('chosen:updated');
          $('#pagu').val("");
          $('#pagu_catatan').val("");
          $('.table-pegawai').DataTable().ajax.reload();
          $('.table-subsidi').DataTable().ajax.reload();
          $('.table-hibah').DataTable().ajax.reload();
          $('.table-bantuan').DataTable().ajax.reload();
          $('.table-btt').DataTable().ajax.reload();                       
          $.alert(msg);
        }
      });  
    }
   
  }

  function ubah(id) {
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/edit/"+id,
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
        $('#satuan-btl').val('Tahun').trigger("chosen:updated");
        $('.overlay').fadeIn('fast',function(){
          $('.input-btl').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        }); 
      }
    });   
  } 
</script>
@endsection



