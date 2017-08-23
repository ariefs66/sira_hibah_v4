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
            <li class="active"><i class="fa fa-angle-right"></i>Kerangka Logis</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Statistik Kerangka Logis</h5>
                  <div class="col-sm-1 pull-right m-t-n-sm">
                    <select class="form-control dtSelect" id="dtSelect">
                     <option value="">Baris</option>
                     <option value="10">10</option>
                     <option value="25">25</option>
                     <option value="50">50</option>
                     <option value="100">100</option>
                   </select>
                 </div>                    
               </div>           
               <div class="tab-content tab-content-alt-1 bg-white">
                <div role="tabpanel" class="active tab-pane" id="tab-1">  
                  <div class="table-responsive dataTables_wrapper table-statistik">
                   <table ui-jq="dataTable" ui-options="{
                   sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/kerangka-logis/api',
                   aoColumns: [
                   { mData: 'ID',class:'hide'},
                   { mData: 'SKPD'},
                   { mData: 'KEGIATAN'}
                   ]}" class="table table-btl table-striped b-t b-b table-statistik" id="table-statistik">
                   <thead>
                    <tr>
                      <th class="hide">ID</th>
                      <th class="text-center">SKPD</th>
                      <th class="text-center">KEGIATAN</th>
                    </tr>
                    <tr>
                      <th class="hide"></th>
                      <th colspan="10" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="table-search form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
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
@endsection

@section('plugin')
<script type="text/javascript">
  $(document).ready(function(){
    $("#app").trigger('click');
  });

  $('#table-statistik').on('click','tbody > tr', function(){
      kode = $(this).children('td').eq(0).html();
      window.open("{{ url('/') }}/main/{{$tahun}}/{{$status}}/belanja-langsung/detail/"+kode);
   })
</script>
@endsection