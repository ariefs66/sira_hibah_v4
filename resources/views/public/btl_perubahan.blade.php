@extends('public.layout')

@section('content')
<div id="content" role="main">
  <div class="hbox hbox-auto-xs hbox-auto-sm ng-scope">
    <div class="col">
      <div class="app-content-body ">
        <div class="bg-light lter">    
          <ul class="breadcrumb bg-white m-b-none">
          <li>
              <a href="{{ url('/') }}/public/{{$tahun}}/{{$status}}" class="btn no-shadow">
                <i class="icon-bdg_expand1 text"></i>
              </a>   
            </li>
            <li><a href= "{{ url('/') }}/public/{{$tahun}}/{{$status}}">Dashboard</a></li>
            <li><i class="fa fa-angle-right"></i>Belanja</li>                               
            <li class="active"><i class="fa fa-angle-right"></i>Belanja Tidak Langsung</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Belanja Tidak Langsung</h5>
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
                    sAjaxSource: '{{ url('/') }}/public/{{$tahun}}/{{$status}}/belanja-tidak-langsung/pegawai',
                    aoColumns: [
                    { mData: 'ID',class:'hide'},
                    { mData: 'REK',class:'hide'},
                    { mData: 'KODE'},
                    { mData: 'NAMA'},
                    { mData: 'TOTAL_MURNI'},
                    { mData: 'TOTAL'}
                  ]}" class="table table-btl table-striped b-t b-b table-pegawai " id="table-pegawai">
                  <thead>
                    <tr>
                      <th class="hide" rowspan="2">ID</th>                    
                      <th class="hide" rowspan="2">REK</th>                    
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
                      <th class="hide"></th>
                      <th colspan="4" class="th_search">
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
                    sAjaxSource: '{{ url('/') }}/public/{{$tahun}}/{{$status}}/belanja-tidak-langsung/subsidi',
                    aoColumns: [
                    { mData: 'ID',class:'hide'},
                    { mData: 'REK',class:'hide'},                                        
                    { mData: 'KODE'},
                    { mData: 'NAMA'},
                    { mData: 'TOTAL_MURNI'},
                    { mData: 'TOTAL'}
                  ]}" class="table table-btl table-striped b-t b-b table-subsidi" id="table-subsidi">
                  <thead>
                    <tr>
                      <th class="hide" rowspan="2">ID</th>                    
                      <th class="hide" rowspan="2">REK</th>                    
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
                      <th class="hide"></th>
                      <th colspan="4" class="th_search">
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
                    sAjaxSource: '{{ url('/') }}/public/{{$tahun}}/{{$status}}/belanja-tidak-langsung/hibah',
                    aoColumns: [
                    { mData: 'ID',class:'hide'}, 
                    { mData: 'REK',class:'hide'},                                       
                    { mData: 'KODE'},
                    { mData: 'NAMA'},
                    { mData: 'TOTAL_MURNI'},
                    { mData: 'TOTAL'}
                  ]}" class="table table-btl table-striped b-t b-b table-hibah" id="table-hibah">
                  <thead>
                    <tr>
                      <th class="hide" rowspan="2">ID</th>                    
                      <th class="hide" rowspan="2">REK</th>                    
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
                      <th class="hide"></th>
                      <th colspan="4" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-hibah form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-4">  
                <div class="table-responsive dataTables_wrapper table-btl">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/public/{{$tahun}}/{{$status}}/belanja-tidak-langsung/bantuan',
                    aoColumns: [
                    { mData: 'ID',class:'hide'},  
                    { mData: 'REK',class:'hide'},                                      
                    { mData: 'KODE'},
                    { mData: 'NAMA'},
                    { mData: 'TOTAL_MURNI'},
                    { mData: 'TOTAL'}
                  ]}" class="table table-btl table-striped b-t b-b table-bantuan" id="table-bantuan">
                  <thead>
                    <tr>
                      <th class="hide" rowspan="2">ID</th>                    
                      <th class="hide" rowspan="2">REK</th>                    
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
                      <th class="hide"></th>
                      <th colspan="4" class="th_search">
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
                    sAjaxSource: '{{ url('/') }}/public/{{$tahun}}/{{$status}}/belanja-tidak-langsung/btt',
                    aoColumns: [
                    { mData: 'ID',class:'hide'}, 
                    { mData: 'REK',class:'hide'},                                       
                    { mData: 'KODE'},
                    { mData: 'NAMA'},
                    { mData: 'TOTAL_MURNI'},
                    { mData: 'TOTAL'}
                  ]}" class="table table-btl table-striped b-t b-b table-btt" id="table-btt">
                  <thead>
                    <tr>
                      <th class="hide" rowspan="2">ID</th>                    
                      <th class="hide" rowspan="2">REK</th>                    
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
                      <th class="hide"></th>
                      <th colspan="4" class="th_search">
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
<div id="table-detail-btl" class="hide bg-white">
  <table ui-jq="dataTable" class="table table-detail-btl-isi table-striped b-t b-b">
    <thead>
      <tr>
        <th>No</th>                                    
        <th>Rekening</th>                          
        <th>Rincian</th>                       
        <th>Anggaran Sebelum</th>                                       
        <th>Anggaran Sesudah</th>                                  
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
        btl_detail = '<tr class="btl-rincian"><td style="padding:0!important;" colspan="4">'+$('#table-detail-btl').html()+'</td></tr>';
        $(btl_detail).insertAfter('.table-btl .table tbody tr.shown');
        $('.table-detail-btl-isi').DataTable({
          sAjaxSource: "/public/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/getDetail/"+skpd+"/"+rek,
          aoColumns: [
          { mData: 'NO' },
          { mData: 'REKENING' },
          { mData: 'RINCIAN' },
          { mData: 'TOTAL_MURNI' },
          { mData: 'TOTAL' }
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
      url   : "{{ url('/') }}/public/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/getRekening/"+id,
      success : function (data) {
        $('#rekening-btl').append(data).trigger('chosen:updated');
      }
    });
  });

  $("#skpd-btl").change(function(e, params){
    var id  = $('#skpd-btl').val();
    $('#subunit-btl').find('option').remove().end().append('<option>Pilih Subunit</option>');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/public/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/subunit/"+id,
      success : function (data) {
        $('#subunit-btl').append(data).trigger('chosen:updated');
      }
    });
  });
</script>
@endsection
