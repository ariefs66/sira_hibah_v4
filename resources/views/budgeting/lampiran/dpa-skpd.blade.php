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
                <li><i class="fa fa-angle-right"></i><a>Lampiran</a></li>
                <li class="active"><i class="fa fa-angle-right"></i>{{ $tipe }}</li>                                
              </ul>
          </div>
          <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                    <h5 class="inline font-semibold text-orange m-n ">{{$tipe}} - {{ $tahun }}</h5>
                    
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
                            <div class="table-responsive dataTables_wrapper">
                             <table ui-jq="dataTable" class="table table-striped b-t b-b">
                                    <thead>
                                      <tr>
                                        <th>No</th>
                                        <th>Kode Perangkat Daerah</th>
                                        <th>Nama</th>
                                      </tr>
                                      <tr>
                                        <th colspan="3" class="th_search">
                                            <i class="icon-bdg_search"></i>
                                            <input type="search" class="table-search form-control b-none w-full" placeholder="Cari Perangkat Daerah" aria-controls="DataTables_Table_0">
                                        </th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($skpd as $s)
                                    <tr>
                                      <td>{{ $i++ }}</td>
                                      <td>{{ $s->SKPD_KODE }}</td>
                                      <td>
                                        {{ $s->SKPD_NAMA }}
                                        <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/dpa/skpd/{{ $s->SKPD_ID }}" class="btn btn-success pull-right m-t-n-sm m-r-xs" target="_blank"><i class="fa fa-print"></i> DPA-SKPD </a>
                                      </td>
                                    </tr>
                                    @endforeach
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
      <!-- App-content-body -->  
    </div>
    <!-- .col -->
</div>


@endsection

@section('plugin')
@endsection


