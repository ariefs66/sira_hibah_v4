@extends('budgeting.layout')

@section('content')
<div id="content" class="app-content" role="main">
  <div class="hbox hbox-auto-xs hbox-auto-sm ng-scope">
    <div class="col">
      <div class="app-content-body ">
        <div class="bg-light lter">    
          <ul class="breadcrumb bg-white m-b-none">
            <li><a href="#" class="btn no-shadow" ui-toggle-class="app-aside-folded" target=".app" id="app">
              <i class="icon-bdg_expand1 text"></i>
              <i class="icon-bdg_expand2 text-active"></i>
            </a>   </li>
            <li><a href= "{{ url('/') }}/main">Dashboard</a></li>
            <li><i class="fa fa-angle-right"></i>Statistik</li> 
            <li class="active"><i class="fa fa-angle-right"></i>Indikator</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Data Indikator</h5>
               </div>
               <!-- Main tab -->
               <div class="nav-tabs-alt tabs-alt-1 b-t three-row" id="tab-jurnal" >
                <ul class="nav nav-tabs" role="tablist">
                 <li class="active">
                  <a data-target="#tab-1" role="tab" data-toggle="tab">Capaian</a>
                </li>
                <li>
                  <a data-target="#tab-2" role="tab" data-toggle="tab">Hasil</a>
                </li>
                <li>
                  <a data-target="#tab-3" role="tab" data-toggle="tab">Keluaran</a>
                </li>
              </ul>

            </div>
            <!-- / main tab -->                  
            <div class="tab-content tab-content-alt-1 bg-white">
              <div role="tabpanel" class="active tab-pane " id="tab-1">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/indikator/1',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'TOLOK_UKUR'},
                    { mData: 'TARGET'},
                    { mData: 'SATUAN'},
                    { mData: 'PROGRAM'},
                    { mData: 'OPSI',class:'text-center'}
                  ]}" class="table table-striped b-t b-b table-capaian " id="table-capaian">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th>Tolok Ukur</th>
                      <th>Target</th>
                      <th>Satuan</th>
                      <th>Program</th>
                      <th>Opsi</th>
                    </tr>
                    <tr>
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-capaian form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-2">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/indikator/2',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'TOLOK_UKUR'},
                    { mData: 'TARGET'},
                    { mData: 'SATUAN'},
                    { mData: 'PROGRAM'},
                    { mData: 'OPSI',class:'text-center'}
                  ]}" class="table table-striped b-t b-b table-hasil" id="table-hasil">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th>Tolok Ukur</th>
                      <th>Target</th>
                      <th>Satuan</th>
                      <th>Program</th>
                      <th>Opsi</th>
                    </tr>
                    <tr>
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-hasil form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-3">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/indikator/3',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'TOLOK_UKUR'},
                    { mData: 'TARGET'},
                    { mData: 'SATUAN'},
                    { mData: 'KEGIATAN'},
                    { mData: 'OPSI',class:'text-center'}
                  ]}" class="table table-striped b-t b-b table-keluaran " id="table-keluaran">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th>Tolok Ukur</th>
                      <th>Target</th>
                      <th>Satuan</th>
                      <th>Kegiatan</th>
                      <th>Opsi</th>
                    </tr>
                    <tr>
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-keluaran form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
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
<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

@endsection

@section('plugin')
<script type="text/javascript">
  $(document).ready(function(){
    $("#app").trigger('click');
  });
  $('.cari-capaian').keyup( function () {
    $('#table-capaian').DataTable().search($('.cari-capaian').val()).draw();
  });
  $('.cari-hasil').keyup( function () {
    $('#table-hasil').DataTable().search($('.cari-hasil').val()).draw();
  });
  $('.cari-keluaran').keyup( function () {
    $('#table-keluaran').DataTable().search($('.cari-keluaran').val()).draw();
  });
</script>
@endsection