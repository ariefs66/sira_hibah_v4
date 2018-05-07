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
                  @if(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->mod == '01000000000'  or Auth::user()->level == 2   )
                  <button class="pull-right btn m-t-n-sm btn-success open-form-faktor"><i class="m-r-xs fa fa-plus"></i> <span>Parameter Cetak</span></button>
                  @endif
                  @if(Auth::user()->level == 8 or Auth::user()->level == 9 and $cek)
                  <a class="pull-right btn btn-info m-t-n-sm m-r-sm" id="excel"  target="_blank" href="{{ url('/') }}/monev/{{$tahun}}/excel/1"><i class="m-r-xs fa fa-download"></i> Download</a>
                  @elseif(Auth::user()->level == 2 and $cek)
                  <a class="pull-right btn btn-info m-t-n-sm m-r-sm" id="excel" target="_blank" href="{{ url('/') }}/monev/{{$tahun}}/excel/{{ \App\Model\UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID')}}"><i class="m-r-xs fa fa-download"></i> Download</a>
                  @endif
@if(Auth::user()->level == 8 or Auth::user()->level == 9 )
                  <a id="print" class="pull-right btn btn-danger m-t-n-sm m-r-sm" target="_blank"  href="{{ url('/') }}/monev/{{$tahun}}/cetak/1"><i class="m-r-xs fa fa-file"></i> Print</a>
@else
  @if($cek)
                  <a id="print" class="pull-right btn btn-danger m-t-n-sm m-r-sm" target="_blank" href="{{ url('/') }}/monev/{{$tahun}}/cetak/{{ \App\Model\UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID')}}"><i class="m-r-xs fa fa-file"></i> Print</a>
  @endif
@endif
                  <h5 class="inline font-semibold text-orange m-n ">Monev</h5>
                  @if(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->mod == '01000000000' )
                  <div class="col-sm-4 pull-right m-t-n-sm">
                   <select ui-jq="chosen" class="form-control" id="filter-skpd">
                     <option value="">- Pilih OPD -</option>
                     @foreach($skpd as $pd)
                     <option value="{{ $pd->SKPD_ID }}">{{ $pd->SKPD_NAMA }}</option>
                     @endforeach
                   </select>
                   
                 </div>
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
               <div class="nav-tabs-alt tabs-alt-1 b-t four-row" id="tab-jurnal" >
                <ul class="nav nav-tabs" role="tablist">
                @if(Auth::user()->level == 8 or Auth::user()->level == 9 )
                 <li class="{{$triwulan1}}">
                  <a data-target="#tab-1" role="tab" data-toggle="tab">Triwulan 1</a>
                </li>
                <li class="{{$triwulan2}}">
                  <a data-target="#tab-2" role="tab" data-toggle="tab">Triwulan 2</a>
                </li>
                <li class="{{$triwulan3}}">
                  <a data-target="#tab-3" role="tab" data-toggle="tab">Triwulan 3</a>
                </li>
                <li class="{{$triwulan4}}">
                  <a data-target="#tab-4" role="tab" data-toggle="tab">Triwulan 4</a> 
                </li>
              @else
              <li class="{{$triwulan1}}">
                  <a>Triwulan 1</a>
                </li>
                <li class="{{$triwulan2}}">
                  <a>Triwulan 2</a>
                </li>
                <li class="{{$triwulan3}}">
                  <a>Triwulan 3</a>
                </li>
                <li class="{{$triwulan4}}">
                  <a>Triwulan 4</a> 
                </li>
              </ul>
            @endif
            </div>
            <!-- / main tab -->                  
            <div class="tab-content tab-content-alt-1 bg-white">
              <div role="tabpanel" class="{{$triwulan1}} tab-pane " id="tab-1">  
                <div class="table-responsive dataTables_wrapper table-btl">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/monev/{{$tahun}}/getTriwulan1/0',
                    aoColumns: [
                    { mData: 'ID'},
                    { mData: 'PROGRAM_ID', sClass:'hide'},
                    { mData: 'MODE', sClass:'hide'},
                    { mData: 'PROGRAM'},
                    { mData: 'OUTCOME'},
                    { mData: 'TARGET'},
                    { mData: 'KINERJA'},
                    { mData: 'TOTAL'},
                    { mData: 'STATUS' },
                  ]}" class="table table-btl table-striped b-t b-b table-pegawai " id="table-pegawai">
                  <thead>
                    <tr>                   
                      <th>No </th>
                      <th class="hide">ID </th>
                      <th class="hide">MODE </th>
                      <th>PROGRAM </th>
                      <th>OUTCOME </th>
                      <th>TARGET </th>
                      <th>KINERJA </th>
                      <th>TOTAL </th>
                      <th>STATUS </th>
                    </tr>
                    <tr>
                      <th class="hide"></th>                    
                      <th colspan="8" class="th_search">
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
            <div role="tabpanel" class="{{$triwulan2}} tab-pane" id="tab-2">  
                <div class="table-responsive dataTables_wrapper table-btl">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/monev/{{$tahun}}/getTriwulan2/0',
                    aoColumns: [
                    { mData: 'ID'},
                    { mData: 'PROGRAM_ID', sClass:'hide'},
                    { mData: 'MODE', sClass:'hide'},
                    { mData: 'PROGRAM'},
                    { mData: 'OUTCOME'},
                    { mData: 'TARGET'},
                    { mData: 'KINERJA'},
                    { mData: 'TOTAL'},
                    { mData: 'STATUS' },
                  ]}" class="table table-btl table-striped b-t b-b table-subsidi" id="table-subsidi">
                 <thead>
                    <tr>                   
                      <th>No </th>
                      <th class="hide">ID </th>
                      <th class="hide">MODE </th>
                      <th>PROGRAM </th>
                      <th>OUTCOME </th>
                      <th>TARGET </th>
                      <th>KINERJA </th>
                      <th>TOTAL </th>
                      <th>STATUS </th>
                    </tr>
                    <tr>
                      <th class="hide"></th>                    
                      <th colspan="8" class="th_search">
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
            <div role="tabpanel" class="{{$triwulan3}} tab-pane" id="tab-3">  
                <div class="table-responsive dataTables_wrapper table-btl">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/monev/{{$tahun}}/getTriwulan3/0',
                    aoColumns: [
                    { mData: 'ID'},
                    { mData: 'PROGRAM_ID', sClass:'hide'},
                    { mData: 'MODE', sClass:'hide'},
                    { mData: 'PROGRAM'},
                    { mData: 'OUTCOME'},
                    { mData: 'TARGET'},
                    { mData: 'KINERJA'},
                    { mData: 'TOTAL'},
                    { mData: 'STATUS' },
                  ]}" class="table table-btl table-striped b-t b-b table-hibah" id="table-hibah">
                  <thead>
                    <tr>                   
                      <th>No </th>
                      <th class="hide">ID </th>
                      <th class="hide">MODE </th>
                      <th>PROGRAM </th>
                      <th>OUTCOME </th>
                      <th>TARGET </th>
                      <th>KINERJA </th>
                      <th>TOTAL </th>
                      <th>STATUS </th>
                    </tr>
                    <tr>
                      <th class="hide"></th>                    
                      <th colspan="8" class="th_search">
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
            <div role="tabpanel" class="{{$triwulan4}} tab-pane" id="tab-4">  
                <div class="table-responsive dataTables_wrapper table-btl">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/monev/{{$tahun}}/getTriwulan4/0',
                    aoColumns: [
                    { mData: 'ID'},
                    { mData: 'PROGRAM_ID', sClass:'hide'},
                    { mData: 'MODE', sClass:'hide'},
                    { mData: 'PROGRAM'},
                    { mData: 'OUTCOME'},
                    { mData: 'TARGET'},
                    { mData: 'KINERJA'},
                    { mData: 'TOTAL'},
                    { mData: 'STATUS' },
                  ]}" class="table table-btl table-striped b-t b-b table-bantuan" id="table-bantuan">
                 <thead>
                    <tr>                   
                      <th>No </th>
                      <th class='hide'>ID </th>
                      <th class='hide'>MODE </th>
                      <th>PROGRAM </th>
                      <th>OUTCOME </th>
                      <th>TARGET </th>
                      <th>KINERJA </th>
                      <th>TOTAL </th>
                      <th>STATUS </th>
                    </tr>
                    <tr>
                      <th class="hide"></th>                    
                      <th colspan="8" class="th_search">
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
  <form class="form-horizontal">
    <div class="input-wrapper">
      <h5>Edit Data </h5>
          <input type="hidden" id="id">
          <input type="hidden" id="sub-id">
          <input type="hidden" id="skpd-id">
          <input type="hidden" id="keg-id">
          <input type="hidden" id="keg-kode">
          <input type="hidden" id="keg-nama">
          <input type="hidden" id="prog-id">
          <input type="hidden" id="prog-kode">
          <input type="hidden" id="prog-nama">
          <input type="hidden" id="mode">
          <input type="hidden" id="total">
      <div class="form-group">
        <label for="kegiatan" class="col-md-3">Program</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Nama Program" id="program_nama" disabled>          
          <input type="hidden" class="form-control" placeholder="Nama Program" id="program" disabled>          
        </div> 
      </div>
      <div class="form-group">
        <label for="kegiatan" class="col-md-3">Kegiatan</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Nama Kegiatan" id="kegiatan_nama" disabled>          
          <input type="hidden" class="form-control" placeholder="Nama Kegiatan" id="kegiatan" disabled>          
        </div> 
      </div> 
      <div class="form-group">
        <label for="no_spp" class="col-md-3">Anggaran</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Total Anggaran" id="anggaran" disabled>          
        </div> 
      </div>
      <div class="form-group">
        <label class="col-sm-3">Output</label>
        <div class="col-sm-9">
          <textarea class="w-full" id="target" placeholder="Tidak ada Outcome Tolak Ukur" disabled>
          </textarea>
        </div>
      </div>

      <div class="form-group" id="dupe">
        <label for="no_spp" class="col-md-3">Realisasi Kinerja</label>          
        <div class="col-sm-5">
          <input type="number" required="true" min="0" step="0.01" class="form-control" placeholder="Masukan Realisasi Kinerja" id="kinerja" value="0"></input>       
        </div> 
        <div class="col-sm-4">
          <input type="text" class="form-control" placeholder="Satuan" id="satuan_nama" disabled></input> 
          <input type="hidden" class="form-control" id="satuan"></input>
          <input type="hidden" class="form-control" id="output_target"></input>
          <input type="hidden" class="form-control" id="output"></input>
        </div>
      </div>

      <div id="extra">
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Realisasi Anggaran</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Masukan Realisasi Anggaran" id="realisasi" disabled >          
        </div>
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Faktor Pendukung</label>          
        <div class="col-sm-9"> 
          <input type="text" class="form-control" placeholder="Faktor Pendukung" id="pendukung">        
        </div> 
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Faktor Penghambat</label>          
        <div class="col-sm-9">      
          <input type="text" class="form-control" placeholder="Faktor Penghambat" id="penghambat"> 
        </div> 
      </div>    

      <hr class="m-t-xl">
      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">      
      <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanBTL()"><i class="fa fa-check m-r-xs "></i>Simpan</a>
      </div>
  </form>
</div>
</div>
<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-faktor">
  <form class="form-horizontal">
    <div class="input-wrapper">
      <h5><span class="judul">Parameter Cetak</span></h5>
          <input type="hidden" id="faktorid">
          <input type="hidden" id="faktorskpd">
          <input type="hidden" id="faktortahun">
          <input type="hidden" id="faktormode" value="{{$mode}}">
      
      <div class="form-group">
        <label for="faktorsasaran" class="col-md-3">Sasaran</label>          
        <div class="col-sm-9"> 
          <textarea class="form-control" placeholder="Sasaran" id="faktorsasaran" {{$input}} ></textarea>        
        </div> 
      </div>

      <div class="form-group">
        <label for="faktorpendukung" class="col-md-3">Faktor pendorong</label>          
        <div class="col-sm-9"> 
          <textarea class="form-control" placeholder="Faktor pendorong keberhasilan kinerja" id="faktorpendukung" {{$input}} ></textarea>        
        </div> 
      </div>

      <div class="form-group">
        <label for="faktorpenghambat" class="col-md-3">Faktor penghambat</label>          
        <div class="col-sm-9">      
          <textarea class="form-control" placeholder="Faktor penghambat keberhasilan kinerja" id="faktorpenghambat" {{$input}} ></textarea> 
        </div> 
      </div>  

<div class="form-group">
  <label for="faktortriwulan" class="col-md-3">Tindak Lanjut Triwulan</label>          
  <div class="col-sm-9">      
    <textarea class="form-control" placeholder="Tindak Lanjut yang diperlukan dalam triwulan berikutnya" id="faktortriwulan" {{$validasi}}></textarea> 
  </div> 
</div> 

<div class="form-group">
  <label for="faktorrenja" class="col-md-3">Tindak Lanjut Renja Perangkat Daerah</label>          
  <div class="col-sm-9">      
    <textarea class="form-control" placeholder="Tindak Lanjut yang diperlukan dalam Renja Perangkat Daerah" id="faktorrenja" {{$validasi}} ></textarea> 
  </div> 
</div>   

      <hr class="m-t-xl">
      <input type="hidden" name="_token" id="faktortoken" value="{{ csrf_token() }}">
      @if($validasi)
      <span>*Jika Belum sesuai, jangan divalidasi</span>
      @endif      
      <a id="savefaktor" class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanFaktor()"><i class="fa fa-check m-r-xs "></i>Simpan</a>
    </div>
  </form>
</div>
</div>

<div id="table-detail-btl" class="hide bg-white">
  <table ui-jq="dataTable" class="table table-detail-btl-isi table-striped b-t b-b">
    <thead>
      <tr>                                    
        <th>#</th>                                     
        <th>KEGIATAN</th>                          
        <th>KINERJA</th>                                       
        <th>TOTAL</th>    
        <th>STATUS</th>     
        <th class="hide">No</th>                                    
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
                    <div class="col-sm-2">Update terakhir</div>
                    <div class="col-sm-4">: <span id="updated"></span></div>
                </div>
                @if(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->level == 2  )
                <div class="wrapper-lg">
                    <div class="streamline b-l b-grey m-l-lg m-b padder-v" id="timeline-log"></div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('plugin')
<script>
  $('.table-btl').on('click', '.table-btl > tbody > tr ', function () {
    if($("tr").hasClass('btl-rincian') == false){
      @if(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->mod == '01000000000' )
      skpd     = $('#filter-skpd').val();
    @else
      skpd     = @php echo \App\Model\UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID'); @endphp;
    @endif
      kegiatan  = $(this).children("td").eq(1).html();
      mode      = $(this).children("td").eq(2).html();
    }
    if(!$(this).hasClass('btl-rincian')){
      if($(this).hasClass('shown')){      
        $('.btl-rincian').slideUp('fast').remove(); 
        $(this).removeClass('shown'); 
      }else{
        $('.btl-rincian').slideUp('fast').remove(); 
        $(this).addClass('shown');
        btl_detail = '<tr class="btl-rincian"><td style="padding:0!important;" colspan="7">'+$('#table-detail-btl').html()+'</td></tr>';
        $(btl_detail).insertAfter('.table-btl .table tbody tr.shown');
        $('.table-detail-btl-isi').DataTable({
          sAjaxSource: "/monev/{{ $tahun }}/getDetail/"+skpd+"/"+mode+"/"+kegiatan,
          aoColumns: [
          { mData: 'AKSI' },
          { mData: 'KEGIATAN' },
          { mData: 'KINERJA' },
          { mData: 'TOTAL' },
          { mData: 'STATUS' },
          { mData: 'NO', sClass:'hide' }]
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

  $('.open-form-faktor').on('click',function(){
			$('.overlay').fadeIn('fast',function(){
				$('.input-faktor').animate({'right':'0'},"linear");	
				$("html, body").animate({ scrollTop: 0 }, "slow");
        cekFaktor();
			});	
		});

  function simpanBTL(){
    var id              = $('#id').val();
    var token           = $('#token').val();    
    var mode            = $('#mode').val();
    var KEGIATAN_ID     = $('#keg-id').val();
    var SUB_ID     = $('#sub-id').val();
    var KEGIATAN_KODE     = $('#keg-kode').val();
    var KEGIATAN_NAMA     = $('#keg-nama').val();
    var PROGRAM_ID     = $('#prog-id').val();
    var PROGRAM_NAMA     = $('#prog-nama').val();
    var PROGRAM_KODE     = $('#prog-kode').val();
    @if(Auth::user()->level == 8 or Auth::user()->level == 9 )
    var SKPD_ID     = $('#filter-skpd').val();
    @else
    var SKPD_ID     = $('#skpd-id').val();
    @endif
    var SATUAN     = $('#satuan').val();
    var KINERJA        = $('#kinerja').val();
    if(isNaN(KINERJA)){
      KINERJA = 0;
    }
    var TOTAL        = $('#total').val();
    var OUTPUT        = $('#output').val();
    var TARGET        = parseFloat($('#output_target').val().replace('.',''));
    if(isNaN(TARGET)){
      TARGET = 0;
    }
    for (var i = 1; i < TOTAL; i++) { 
      SATUAN = SATUAN + ',' +$('#satuan_'+i).val();
      if(isNaN($('#kinerja_'+i).val())){
        KINERJA = KINERJA + ',' + 0;
      }else{
        KINERJA = KINERJA + ',' + $('#kinerja_'+i).val();
      }
      OUTPUT = OUTPUT + ',' +$('#output_'+i).val();
      if(isNaN($('#output_target_'+i).val())){
        TARGET = TARGET + ',' +0;
      }else{
        TARGET = TARGET + ',' +parseFloat($('#output_target_'+i).val().replace('.',''));
      }
    }
    var KEGIATAN_ANGGARAN     = $('#anggaran').val();
    var PENDUKUNG         = $('#pendukung').val();
    var PENGHAMBAT      = $('#penghambat').val();
    var REALISASI      = $('#realisasi').val();
    if(KINERJA == "" || PENDUKUNG == "" || PENGHAMBAT == ""){
      $.alert('Form harap diisi!');
    }else{
        uri   = "{{ url('/') }}/monev/{{ $tahun }}/kegiatan/simpan/"+mode;
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'             : token,
              'SKPD_ID'             : SKPD_ID, 
              'SUB_ID'             : SUB_ID, 
              'SATUAN'             : SATUAN, 
              'KEGIATAN_ID'         : KEGIATAN_ID, 
              'KEGIATAN_KODE'       : KEGIATAN_KODE, 
              'KEGIATAN_NAMA'       : KEGIATAN_NAMA, 
              'PROGRAM_ID'          : PROGRAM_ID, 
              'PROGRAM_KODE'        : PROGRAM_KODE, 
              'PROGRAM_NAMA'        : PROGRAM_NAMA, 
              'KEGIATAN_ANGGARAN'   : KEGIATAN_ANGGARAN, 
              'REALISASI'   : REALISASI, 
              'TARGET'              : TARGET, 
              'KINERJA'             : KINERJA, 
              'OUTPUT'             : OUTPUT, 
              'PENDUKUNG'           : PENDUKUNG, 
              'PENGHAMBAT'          : PENGHAMBAT, 
              'TOTAL'          : TOTAL, 
              'MODE'          : mode},
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
          $('#kegiatan').val("").trigger("chosen:updated");
          $('#program').val("").trigger("chosen:updated");
          $('#anggaran').val("");
          $('#kinerja').val("");
          $('#pendukung').val("");
          $('#penghambat').val("");
          $('#target').val("");
          $('#realisasi').val(0);
          $("#extra").empty();
        }
      });
    }
  }  

    function simpanFaktor(){
    var token           = $('#faktortoken').val();  
    var ID     = $('#faktorid').val();
    var SKPD_ID     = $('#faktorskpd').val();
    var PENDUKUNG         = $('#faktorpendukung').val();
    var PENGHAMBAT           = $('#faktorpenghambat').val();
    var TRIWULAN          = $('#faktortriwulan').val();
    var RENJA             = $('#faktorrenja').val();
    var SASARAN             = $('#faktorsasaran').val();
    var T             = $('#faktormode').val();
    if(SKPD_ID == ""){
      $.alert('Terjadi Kesalahan!');
    }else{
        uri   = "{{ url('/') }}/monev/{{ $tahun }}/faktor/simpan";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'             : token,
              'SKPD_ID'             : SKPD_ID, 
              'PENDUKUNG'           : PENDUKUNG, 
              'PENGHAMBAT'          : PENGHAMBAT, 
              'TRIWULAN'          : TRIWULAN, 
              'RENJA'          : RENJA, 
              'SASARAN'          : SASARAN, 
              'T'          : T},
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
          $('#faktorid').val("");
          $('#faktorskpd').val("");
          $('#faktorpendukung').val("");
          $('#faktorpenghambat').val("");
          $('#faktortriwulan').val("");
          $('#faktorrenja').val("");
          $('#faktorsasaran').val("");
        }
      });
    }
  }  

  function cekFaktor(){
    var id  = $('#faktormode').val();
    @if(Auth::user()->level == 8 or Auth::user()->level == 9 or Auth::user()->mod == '01000000000' )
      skpd     = $('#filter-skpd').val();
    @else
      skpd     = @php echo \App\Model\UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID'); @endphp;
    @endif
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/monev/{{ $tahun }}/faktor/"+skpd+"/"+id,
      success : function (data) {
        data = data.aaData[0];
        $('#faktorid').val(data['FAKTOR_ID']);
        $('#faktorskpd').val(data['SKPD_ID']);
        $('#faktortahun').val(data['TAHUN']);
        $('#faktorpendukung').val(data['PENDUKUNG']);
        $('#faktorpenghambat').val(data['PENGHAMBAT']);
        $('#faktortriwulan').val(data['TRIWULAN']);
        $('#faktorrenja').val(data['RENJA']);
        $('#faktorsasaran').val(data['SASARAN']);
        $("#faktorpendukung").prop('disabled', data['INPUT']);
        $("#faktorpenghambat").prop('disabled', data['INPUT']);
        $("#faktortriwulan").prop('disabled', data['VALIDASI']);
        $("#faktorrenja").prop('disabled', data['VALIDASI']);
        $("#faktorsasaran").prop('disabled', data['INPUT']);
        $(".judul").html(data['JUDUL']+' ' +data['SKPD_NAMA']);
        if(data['INPUT'] || data['VALIDASI']){
          $('#savefaktor').show();
        }else{
          $('#savefaktor').hide();
        }
      }
    });
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


  function hapus(skpd,sub,id){
    var token        = $('#token').val();    
    $.confirm({
        title: 'Hapus Data!',
        content: 'Yakin hapus data?',
        buttons: {
            Ya: {
                btnClass: 'btn-danger',
                action: function(){
                  $.ajax({
                      url: "{{ url('/') }}/monev/{{ $tahun }}/hapus/"+sub,
                      type: "POST",
                      data: {'_token'      : token,
                            'KEGIATAN_ID'       : id,
                            'SKPD_ID'       : skpd},
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
  
  function info(mode=1,id){
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/pergeseran/belanja-langsung/log/"+id,
      success : function (data) {
         // $('#creator').text(data['header']['creator']);
          $('#staff1').text(data['header']['staff1']);
          $('#staff2').text(data['header']['staff2']);
          $('#created').text(data['header']['created']);
          $('#updated').text(data['header']['updated']);
          $('#info').modal('show');
      }
    });
  }

  function ubah(mode=1,id) {
    @if(Auth::user()->level == 8 or Auth::user()->level == 9 )
    skpd     = $('#filter-skpd').val();
    @else
    skpd     = @php echo \App\Model\UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID'); @endphp;
    @endif
    $("#extra").empty();
    $("#kinerja").val(0);
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/monev/{{ $tahun }}/getData/"+skpd+"/"+mode+"/"+id,
      success : function (data) {
        var counter = 0;
    function duplicateNode(/*DOMNode*/sourceNode, /*Array*/attributesToBump) {
        counter++;
        var out = sourceNode.cloneNode(true);
        if (out.hasAttribute("id")) { out["id"] = bump(out["id"]); }
        var nodes = out.getElementsByTagName("*");
        
        for (var i = 0, len1 = nodes.length; i < len1; i++) {
            var node = nodes[i];
            for (var j = 0, len2 = attributesToBump.length; j < len2; j++) {
                var attribute = attributesToBump[j];
                if (node.hasAttribute(attribute)) {
                    node[attribute] = bump(node[attribute]);
                }
            }
        }
        
        function bump(/*String*/str) {
            return str + "_" + counter;
        }
      
        return out;
    }
        data = data.aaData[0];
        $('#id').val(data['ID']);
        $('#keg-id').val(data['KEGIATAN_ID']);
        $('#sub-id').val(data['SUB_ID']);
        $('#keg-kode').val(data['KEGIATAN_KODE']);
        $('#keg-nama').val(data['KEGIATAN_NAMA']);
        $('#prog-id').val(data['PROGRAM_ID']);
        $('#prog-nama').val(data['PROGRAM_NAMA']);
        $('#prog-kode').val(data['PROGRAM_KODE']);
        $('#skpd-id').val(data['SKPD_ID']);
        $('#mode').val(data['MODE']);
        $('#program_nama').val(data['PROGRAM_NAMA']);
        $('#program').val(data['PROGRAM_ID']);
        $('#kegiatan_nama').val(data['KEGIATAN_NAMA']);
        $('#kegiatan').val(data['KEGIATAN_ID']);
        $('#anggaran').val(data['KEGIATAN_ANGGARAN']);
        $('#target').val(data['OUTPUT']);
        $('#total').val(data['TOTAL']);
        var kinerja = data['KINERJA'].split(",");
        var target = data['TARGET'].split(",");
        var satuan = data['SATUAN_ID'].split(",");
        var satuan_nama = data['SATUAN'].split(",");
        var output_nama = data['TOLAK_UKUR'].split(",");
        if(kinerja[0]){
        $('#kinerja').val(kinerja[0]);}
        $('#satuan').val(satuan[0]);
        $('#satuan_nama').val(satuan_nama[0]);
        $('#output').val(output_nama[0]);
        $('#output_target').val(target[0]);
        var total = data['TOTAL'];
        for (i = 1; i < total; i++) { 
        var sourceNode = document.getElementById("dupe");
        var node = duplicateNode(sourceNode, ["id", "name"]);
        var extra = document.getElementById("extra");
        extra.appendChild(node);
        if(kinerja[i]){
        $('#kinerja_'+i).val(kinerja[i]);}
        $('#satuan_'+i).val(satuan[i]);
        $('#satuan_nama_'+i).val(satuan_nama[i]);
        $('#output_'+i).val(output_nama[i]);
        $('#output_target_'+i).val(target[i]);
        }
        $('#pendukung').val(data['KEGIATAN_PENDUKUNG']);
        $('#penghambat').val(data['KEGIATAN_PENGHAMBAT']);
        $('#realisasi').val(data['REALISASI']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-btl').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        }); 
        $('.btn-success').show();
      }
    });   
  } 

  function view(mode=1,id) {
    @if(Auth::user()->level == 8 or Auth::user()->level == 9 )
    skpd     = $('#filter-skpd').val();
    @else
    skpd     = @php echo \App\Model\UserBudget::where('USER_ID',Auth::user()->id)->where('TAHUN',$tahun)->value('SKPD_ID'); @endphp;
    @endif
    $("#extra").empty();
    $("#kinerja").val(0);
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/monev/{{ $tahun }}/getData/"+skpd+"/"+mode+"/"+id,
      success : function (data) {
        var counter = 0;
    function duplicateNode(/*DOMNode*/sourceNode, /*Array*/attributesToBump) {
        counter++;
        var out = sourceNode.cloneNode(true);
        if (out.hasAttribute("id")) { out["id"] = bump(out["id"]); }
        var nodes = out.getElementsByTagName("*");
        
        for (var i = 0, len1 = nodes.length; i < len1; i++) {
            var node = nodes[i];
            for (var j = 0, len2 = attributesToBump.length; j < len2; j++) {
                var attribute = attributesToBump[j];
                if (node.hasAttribute(attribute)) {
                    node[attribute] = bump(node[attribute]);
                }
            }
        }
        
        function bump(/*String*/str) {
            return str + "_" + counter;
        }
      
        return out;
    }
        data = data.aaData[0];
        $('#id').val(data['ID']);
        $('#keg-id').val(data['KEGIATAN_ID']);
        $('#sub-id').val(data['SUB_ID']);
        $('#keg-kode').val(data['KEGIATAN_KODE']);
        $('#keg-nama').val(data['KEGIATAN_NAMA']);
        $('#prog-id').val(data['PROGRAM_ID']);
        $('#prog-nama').val(data['PROGRAM_NAMA']);
        $('#prog-kode').val(data['PROGRAM_KODE']);
        $('#skpd-id').val(data['SKPD_ID']);
        $('#mode').val(data['MODE']);
        $('#program_nama').val(data['PROGRAM_NAMA']);
        $('#program').val(data['PROGRAM_ID']);
        $('#kegiatan_nama').val(data['KEGIATAN_NAMA']);
        $('#kegiatan').val(data['KEGIATAN_ID']);
        $('#anggaran').val(data['KEGIATAN_ANGGARAN']);
        $('#target').val(data['OUTPUT']);
        $('#total').val(data['TOTAL']);
        var kinerja = data['KINERJA'].split(",");
        var target = data['TARGET'].split(",");
        var satuan = data['SATUAN_ID'].split(",");
        var satuan_nama = data['SATUAN'].split(",");
        var output_nama = data['TOLAK_UKUR'].split(",");
        $('#kinerja').val(kinerja[0]);
        $('#satuan').val(satuan[0]);
        $('#satuan_nama').val(satuan_nama[0]);
        $('#output').val(output_nama[0]);
        $('#output_target').val(target[0]);
        var total = data['TOTAL'];
        for (i = 1; i < total; i++) { 
        var sourceNode = document.getElementById("dupe");
        var node = duplicateNode(sourceNode, ["id", "name"]);
        var extra = document.getElementById("extra");
        extra.appendChild(node);
        $('#kinerja_'+i).val(kinerja[i]);
        $('#satuan_'+i).val(satuan[i]);
        $('#satuan_nama_'+i).val(satuan_nama[i]);
        $('#output_'+i).val(output_nama[i]);
        $('#output_target_'+i).val(target[i]);
        }
        $('#pendukung').val(data['KEGIATAN_PENDUKUNG']);
        $('#penghambat').val(data['KEGIATAN_PENGHAMBAT']);
        $('#realisasi').val(data['REALISASI']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-btl').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        }); 
        $('.btn-success').show();
      }
    });   
  } 

  $('#filter-skpd').change(function(e, params){
      var id  = $('#filter-skpd').val();
      $("#print").attr("href", "{{ url('/') }}/monev/{{$tahun}}/cetak/"+id);
      $("#excel").attr("href", "{{ url('/') }}/monev/{{$tahun}}/excel/"+id);
      $.fn.dataTable.ext.errMode = 'none';
      $('#table-pegawai').DataTable().destroy();
      $('#table-pegawai').DataTable({
        sAjaxSource: "{{ url('/') }}/monev/{{$tahun}}/getTriwulan1/"+id,
        aoColumns: [
          { mData: 'ID'},
          { mData: 'PROGRAM_ID', sClass:'hide'},
          { mData: 'MODE', sClass:'hide'},
          { mData: 'PROGRAM'},
          { mData: 'OUTCOME'},
          { mData: 'TARGET'},
          { mData: 'KINERJA'},
          { mData: 'TOTAL'},
          { mData: 'STATUS'}],
          initComplete:function(setting,json){
            $("#pagu_foot").html(json.pagu_foot);
            $("#rincian_foot").html(json.rincian_foot);
        }
      });  
      $('#table-subsidi').DataTable().destroy();
      $('#table-subsidi').DataTable({
        sAjaxSource: "{{ url('/') }}/monev/{{$tahun}}/getTriwulan2/"+id,
        aoColumns: [
          { mData: 'ID'},
          { mData: 'PROGRAM_ID', sClass:'hide'},
          { mData: 'MODE', sClass:'hide'},
          { mData: 'PROGRAM'},
          { mData: 'OUTCOME'},
          { mData: 'TARGET'},
          { mData: 'KINERJA'},
          { mData: 'TOTAL'},
          { mData: 'STATUS'}],
          initComplete:function(setting,json){
            $("#pagu_foot").html(json.pagu_foot);
            $("#rincian_foot").html(json.rincian_foot);
        }
      }); 
      $('#table-hibah').DataTable().destroy();
      $('#table-hibah').DataTable({
        sAjaxSource: "{{ url('/') }}/monev/{{$tahun}}/getTriwulan3/"+id,
        aoColumns: [
          { mData: 'ID'},
          { mData: 'PROGRAM_ID', sClass:'hide'},
          { mData: 'MODE', sClass:'hide'},
          { mData: 'PROGRAM'},
          { mData: 'OUTCOME'},
          { mData: 'TARGET'},
          { mData: 'KINERJA'},
          { mData: 'TOTAL'},
          { mData: 'STATUS'}],
          initComplete:function(setting,json){
            $("#pagu_foot").html(json.pagu_foot);
            $("#rincian_foot").html(json.rincian_foot);
        }
      }); 
      $('#table-bantuan').DataTable().destroy();
      $('#table-bantuan').DataTable({
        sAjaxSource: "{{ url('/') }}/monev/{{$tahun}}/getTriwulan4/"+id,
        aoColumns: [
          { mData: 'ID'},
          { mData: 'PROGRAM_ID', sClass:'hide'},
          { mData: 'MODE', sClass:'hide'},
          { mData: 'PROGRAM'},
          { mData: 'OUTCOME'},
          { mData: 'TARGET'},
          { mData: 'KINERJA'},
          { mData: 'TOTAL'},
          { mData: 'STATUS'}],
          initComplete:function(setting,json){
            $("#pagu_foot").html(json.pagu_foot);
            $("#rincian_foot").html(json.rincian_foot);
        }
      }); 
  });
</script>
@endsection