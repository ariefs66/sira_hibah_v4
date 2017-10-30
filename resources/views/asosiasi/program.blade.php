@extends('asosiasi.layout')

@section('content')
<div id="content" class="app-content" role="main">
  <div class="hbox hbox-auto-xs hbox-auto-sm ng-scope">
    <div class="col">
      <div class="app-content-body ">
        <div class="bg-light lter">    
          <ul class="breadcrumb bg-white m-b-none">
            <li>
              <a href="#" class="btn no-shadow" ui-toggle-class="app-aside-folded" target=".app">
                <i class="icon-bdg_expand1 text"></i>
                <i class="icon-bdg_expand2 text-active"></i>
              </a>   
            </li>
            <li>
              <a href= "{{ url('/') }}/asosiasi/{{$tahun}}">Dashboard</a>
            </li>
            <li><i class="fa fa-angle-right"></i>Visi Misi</li>
            <li><i class="fa fa-angle-right"></i>Tujuan</li>
            <li><i class="fa fa-angle-right"></i>Strategi</li>
            <li><i class="fa fa-angle-right"></i>Arah Kebijakan</li>
            <li class="active"><i class="fa fa-angle-right"></i>Program</li>
          </ul>
        </div>
        
        <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                     
                    
                    <h5 class="inline font-semibold text-orange m-n ">Program Urusan Tahun {{ $tahun }}</h5>
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
                                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/murni/pengaturan/program/getData',
                                    aoColumns: [
                                    { mData: 'id_porgram',class:'hide' },
                                    { mData: 'URUSAN' },
                                    { mData: 'PROGRAM' },
                                    { mData: 'OPSI' }
                                    ]}" class="table table-program-head table-striped b-t b-b">
                                    <thead>
                                      <tr>
                                        <th class="hide">No</th>
                                        <th>Urusan</th>
                                        <th>Program</th>
                                        <th width="20%">#</th>
                                      </tr>
                                      <tr>
                                        <th class="hide"></th>
                                        <th colspan="4" class="th_search">
                                            <i class="icon-bdg_search"></i>
                                            <input type="search" class="table-search form-control b-none w-full" placeholder="Cari Program" aria-controls="DataTables_Table_0">
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
</div>
@endsection

@section('plugin')
@endsection


