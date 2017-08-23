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
            <li class="active"><i class="fa fa-angle-right"></i>Porsi APBD</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Porsi APBD</h5>   
               </div>           
               <div class="tab-content tab-content-alt-1 bg-white">
                <div role="tabpanel" class="active tab-pane" id="tab-1">  
                  <div class="table-responsive dataTables_wrapper table-statistik">
                   <table ui-jq="dataTable" ui-options="{
                   sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/porsi-apbd/api',
                   aoColumns: [
                   { mData: 'KODE'},
                   { mData: 'SKPD'},
                   { mData: 'MUSRENBANG',class:'text-right'},
                   { mData: 'PIPPK',class:'text-right'},
                   { mData: 'RESES',class:'text-right'},
                   { mData: 'ARAHAN',class:'text-right'},
                   { mData: 'NONURUSAN',class:'text-right'},
                   { mData: 'ETC',class:'text-right'},
                   { mData: 'T2017',class:'text-right'}
                   ],iDisplayLength: 100}" class="table table-btl table-striped b-t b-b table-statistik" id="table-statistik">
                   <thead>
                    <tr>
                      <th class="text-center" width="1%">Kode</th>
                      <th class="text-center">SKPD</th>
                      <th class="text-center">MUSRENBANG</th>
                      <th class="text-center">PIPPK</th>
                      <th class="text-center">RESES</th>
                      <th class="text-center">ARAHAN WALIKOTA</th>
                      <th class="text-center">NON URUSAN</th>
                      <th class="text-center">LAINNYA</th>
                      <th class="text-center">2017</th>
                    </tr>
                    <tr>
                      <th colspan="9" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="table-search form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                   <tr>
                      <th class="text-center" colspan="2">TOTAL</th>
                      <th class="text-right">{{ number_format($musrenbang,0,'.',',') }}</th>
                      <th class="text-right">{{ number_format($pippk,0,'.',',') }}</th>
                      <th class="text-right">{{ number_format($reses,0,'.',',') }}</th>
                      <th class="text-right">{{ number_format($arahan,0,'.',',') }}</th>
                      <th class="text-right">{{ number_format($nonurusan,0,'.',',') }}</th>
                      <th class="text-right">{{ number_format($etc,0,'.',',') }}</th>
                      <th class="text-right"></th>
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
    // $('#table-statistik').dataTable({
    //     dom: 'Bfrtip',
    //     buttons: [{ "extend": 'pdf', "text":'Export',"className": 'btn btn-default btn-xs' }],
        
    // });
  });
</script>
@endsection