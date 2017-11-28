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
                <li><i class="fa fa-angle-right"></i><a>Monitoring</a></li>
                <li><i class="fa fa-angle-right"></i><a>Musrenbang</a></li>                                
                <li class="active"><i class="fa fa-angle-right"></i>Renja</li>                                
              </ul>
          </div>
          <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                    <h5 class="inline font-semibold text-orange m-n ">Musrenbang Renja - {{ $skpd->SKPD_NAMA }}</h5>
                    <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/musrenbang" class="pull-right"><li class="fa fa-reply"></li> Kembali</a>
          					<div class="col-sm-1 pull-right m-t-n-sm">
                    	<select class="form-control dtSelect" id="dtSelect">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>                    
                  </div>           
                  <div class="tab-content tab-content-alt-1 bg-white">
                        <div role="tabpanel" class="active tab-pane" id="tab-1">  
                            <div class="table-responsive dataTables_wrapper table-program">
                             <table ui-jq="dataTable" ui-options="{
                                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/musrenbang/renja/getData/{{$skpd->SKPD_ID}}',
                                    aoColumns: [
                                    { mData: 'ID' },
                                    { mData: 'PENGUSUL' },
                                    { mData: 'KEGIATAN' },
                                    { mData: 'ANGGARAN' },
                                    { mData: 'STATUS' },
                                    { mData: 'SKPD' }
                                    ]}" class="table table-program-head table-striped b-t b-b">
                                    <thead>
                                      <tr>
                                        <th>ID</th>
                                        <th>Pengusul</th>
                                        <th>Kegiatan / Program</th>
                                        <th>Anggaran</th>
                                        <th>Status</th>
                                        <th>SKPD</th>
                                      </tr>
                                      <tr>
                                        <th colspan="6" class="th_search">
                                            <i class="icon-bdg_search"></i>
                                            <input type="search" class="table-search form-control b-none w-full" placeholder="Cari Program" aria-controls="DataTables_Table_0">
                                        </th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                      <tr>
                                        <td colspan="2"><b>Total</b></td>
                                        <td><b>Rp.{{$foot_anggaran}}</b></td>
                                        <td></td>
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
      <!-- App-content-body -->  
    </div>
    <!-- .col -->
</div>

@endsection

@section('plugin')
<script type="text/javascript">
</script>
@endsection


