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
            <li class="active"><i class="fa fa-angle-right"></i>Rekening</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Statistik Rekening</h5>
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
               <hr class="m-t-n-sm">
                  <div class="wrapper-lg m-t-n-xl">
                    <div class="row">
                      <div class="col-sm-3" class="w-full">
                        <p class="pull-right m-t-sm">Filter : </p>
                      </div>
                      <div class="col-sm-3" class="w-full">
                        <select ui-jq="chosen" class="w-full" id="filterpersentase">
                          <option value="0">Seluruh Persentase</option>
                          <option value="2">Diatas 2017</option>
                          <option value="1">Dibawah 2017</option>
                        </select>
                      </div>
                      <div class="col-sm-3" class="w-full">
                        <select ui-jq="chosen" class="w-full" id="filterjenis">
                          <option value="5.2">Seluruh Belanja</option>
                          <option value="5.2.1">Belanja Pegawai</option>
                          <option value="5.2.2">Belanja Barang & jasa</option>
                          <option value="5.2.3">Belanja Modal</option>
                        </select>
                      </div>
                      <div class="col-sm-3">
                        <select ui-jq="chosen" class="w-full" id="filter2018">
                          <option value="0">Seluruh Angka 2018</option>
                          <option value="10">Lebih Dari 10 M</option>
                          <option value="50">Lebih Dari 50 M</option>
                          <option value="100">Lebih Dari 100 M</option>
                        </select>                        
                      </div>
                      
                    </div>  
                  </div>           
               <div class="tab-content tab-content-alt-1 bg-white m-t-n-md">
                <div role="tabpanel" class="active tab-pane" id="tab-1">  
                  <div class="table-responsive dataTables_wrapper table-statistik">
                   <table ui-jq="dataTable" ui-options="{
                   sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/rekening/api',
                   aoColumns: [
                   { mData: 'ID',class:'hide'},
                   { mData: 'KODE'},
                   { mData: 'NAMA'},
                   { mData: 'RINCIAN',class:'text-right'},
                   { mData: 'RINCIAN_',class:'text-right'},
                   { mData: 'PERSENTASE'}
                   ]}" class="table table-btl table-striped b-t b-b table-statistik" id="table-statistik">
                   <thead>
                    <tr>
                      <th class="hide">ID</th>
                      <th class="text-center" width="1%">Kode</th>
                      <th class="text-center">Nama</th>
                      <th class="text-center">Anggaran 2018</th>
                      <th class="text-center">Anggaran 2017</th>
                      <th class="text-center">%</th>
                    </tr>
                    <tr>
                      <th class="hide"></th>
                      <th colspan="5" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="table-search form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td class="hide"></td>
                      <th></th>
                      <th></th>
                      <th>{{ number_format($rincian,0,'.',',') }}</th>
                      <th>-</th>
                    </tr>
                  </tfoot>
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
      window.open("{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/rekening/detail/"+kode);
   })

  $("#filter2018").change(function(e, params){
    loadTable();
  });

  $("#filterjenis").change(function(e, params){
    loadTable();
  });

  $("#filterpersentase").change(function(e, params){
    loadTable();
  });

  function loadTable(filter,id){
    var id2018      = $('#filter2018').val();
    var jenis       = $('#filterjenis').val();
    var persentase  = $('#filterpersentase').val();
    $('#table-statistik').DataTable().destroy();
    $('#table-statistik').DataTable({
      sAjaxSource: "{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/rekening/api/filter/"+id2018+"/"+jenis+"/"+persentase,
      aoColumns: [
        { mData: 'ID',class:'hide'},
        { mData: 'KODE'},
        { mData: 'NAMA'},
        { mData: 'RINCIAN',class:'text-right'},
        { mData: 'RINCIAN_',class:'text-right'},
        { mData: 'PERSENTASE'}]
    });
  }
</script>
@endsection