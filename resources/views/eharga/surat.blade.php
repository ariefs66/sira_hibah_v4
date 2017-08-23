@extends('eharga.layout')

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
            <li><a href= "{{ url('/') }}/harga/{{$tahun}}">Eharga</a></li>
            <li class="active"><i class="fa fa-angle-right"></i>Cetak Surat</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n">Surat Usulan</h5>
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
              <!-- / main tab -->                  
              <div class="tab-content tab-content-alt-1 bg-white">
                <div role="tabpanel" class="active tab-pane" id="tab-1">  
                  <div class="table-responsive dataTables_wrapper table-usulan">
                   <table ui-jq="dataTable" ui-options="{
                   sAjaxSource: '{{ url('/') }}/harga/{{$tahun}}/usulan/getSurat',
                   aoColumns: [
                   { mData: 'NO',class:'text-center'},
                   { mData: 'ID'},
                   { mData: 'AKSI'},
                   ]}" class="table table-striped b-t b-b table-surat" id="table-surat">
                   <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th>ID</th>
                      <th width="1%">Aksi</th>
                    </tr>
                    <tr>
                      <th colspan="3" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="table-search cari-usulan form-control b-none w-full" placeholder="Cari Usulan" aria-controls="DataTables_Table_0">
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
@endsection

@section('plugin')
<script type="text/javascript">

</script>
@endsection