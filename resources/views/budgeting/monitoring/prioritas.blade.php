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
            <li class="active"><i class="fa fa-angle-right"></i>Prioritas</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
                  <div role="tabpanel" class="active tab-pane " id="tab-1"> 
                    <div class="row">
                      <div class="col-md-12">
                        <button class="btn btn-default btn-lg btn-block">
                            <i class="pull-left">1.01 Pendidikan</i>
                            <i class="fa fa-bars pull-right"></i><i class="pull-right m-r-sm">Rp. 300,000,000</i>
                        </button>
                        <button class="btn btn-default btn-lg btn-block">
                            <i class="pull-left">1.01 Pendidikan</i>
                            <i class="fa fa-bars pull-right"></i><i class="pull-right m-r-sm">Rp. 300,000,000</i>
                        </button>
                        <button class="btn btn-default btn-lg btn-block">
                            <i class="pull-left">1.01 Pendidikan</i>
                            <i class="fa fa-bars pull-right"></i><i class="pull-right m-r-sm">Rp. 300,000,000</i>
                        </button>
                      </div>
                    </div> 
                  </div>
            </div>
          </div>
        </div>

<div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Data Program Kegiatan Prioritas</h5>
               </div>
               <!-- Main tab -->
               <div class="nav-tabs-alt tabs-alt-1 b-t half-row" id="tab-jurnal" >
                <ul class="nav nav-tabs" role="tablist">
                 <li class="active">
                  <a data-target="#tab-1" role="tab" data-toggle="tab">{{$tahun}}</a>
                </li>
              </ul>

            </div>
            <!-- / main tab -->                  
            <div class="tab-content tab-content-alt-1 bg-white">
              <div role="tabpanel" class="active tab-pane " id="tab-1">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/prioritas/program',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'PRIORITAS'},
                    { mData: 'URUSAN'},
                    { mData: 'SKPD'},
                    { mData: 'PROGRAM'},
                    { mData: 'ANGGARAN'},
                    { mData: 'OPSI',class:'text-right'}
                  ]}" class="table table-striped b-t b-b table-program " id="table-program">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th>Prioritas</th>
                      <th>Urusan</th>
                      <th>SKPD</th>
                      <th>Program</th>
                      <th>Anggaran</th>
                      <th>OPSI</th>
                    </tr>
                    <tr>
                      <th colspan="7" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-program form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
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
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/prioritas/kegiatan',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'SKPD'},
                    { mData: 'KEGIATAN'},
                    { mData: 'ANGGARAN',class:'text text-right'}
                  ]}" class="table table-striped b-t b-b table-kegiatan" id="table-kegiatan">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th>Perangkat Daerah</th>
                      <th>Kegiatan</th>
                      <th>Anggaran</th>
                    </tr>
                    <tr>
                      <th colspan="4" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-kegiatan form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
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
<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

@endsection

@section('plugin')
<script type="text/javascript">
  $(document).ready(function(){
    $("#app").trigger('click');
  });
  $('.cari-program').keyup( function () {
    $('#table-program').DataTable().search($('.cari-program').val()).draw();
  });
  $('.cari-kegiatan').keyup( function () {
    $('#table-kegiatan').DataTable().search($('.cari-kegiatan').val()).draw();
  });
</script>
@endsection