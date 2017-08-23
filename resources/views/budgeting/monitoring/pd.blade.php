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
            <li class="active"><i class="fa fa-angle-right"></i>Perangkat Daerah</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Statistik Perangkat Daerah</h5>
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
                   sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/perangkat-daerah/api',
                   aoColumns: [
                   { mData: 'ID',class:'hide'},
                   { mData: 'KODE'},
                   { mData: 'NAMA'},
                   { mData: 'ADUM',class:'text-center'},
                   { mData: 'KEGIATAN',class:'text-center'},
                   { mData: 'PAGU',class:'text-right'},
                   { mData: 'RINCIAN',class:'text-right'},
                   { mData: 'SELISIH',class:'text-right'},
                   { mData: 'total2017',class:'text-right'},
                   { mData: 'KET',class:'text-center'}
                   ]}" class="table table-btl table-striped b-t b-b table-statistik" id="table-statistik">
                   <thead>
                    <tr>
                      <th class="hide" rowspan="2">ID</th>
                      <th rowspan="2">Kode</th>
                      <th rowspan="2" class="text-center">Nama</th>
                      <th colspan="2" class="text-center" width="1%">Kegiatan</th>
                      <th colspan="5" class="text-center"> Anggaran</th>
                    </tr>
                    <tr>
                      <th width="1%" class="text-center">Non Urusan</th>
                      <th width="1%" class="text-center">Urusan</th>
                      <th width="1%" class="text-right"><a data-toggle="tooltip" title="Total Belanja yang Telah di Validasi">Pagu</a></th>
                      <th width="1%" class="text-right"><a data-toggle="tooltip" title="Total Keseluruhan Belanja">Rincian</a></th>
                      <th width="1%" class="text-center"><a data-toggle="tooltip" title="Rincian - Pagu">Selisih</a></th>
                      <th width="1%" class="text-center"><a data-toggle="tooltip" title="Total Belanja Tahun 2017">2017</a></th>
                      <th width="1%" class="text-center"><a data-toggle="tooltip" title="Presentase Pagu 2018 Terhadap Belanja 2017">Ket (%)</a></th>
                    </tr>                   
                    <tr>
                      <th class="hide"></th>
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
                      <td class="hide"></td>
                      <th colspan="2" class="text-center">Total</th>
                      <th>{{ $adum }}</th>
                      <th>{{ $used }}</th>
                      <th>{{ number_format($pagu,0,'.',',') }}</th>
                      <th>{{ number_format($rincian,0,'.',',') }}</th>
                      <th>{{ number_format($selisih,0,'.',',') }}</th>
                      <th>{{ number_format($total2017,0,'.',',') }}</th>
                      <th>{{ $ket }}</th>
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
      window.open("{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/perangkat-daerah/detail/"+kode);
   })
</script>
@endsection