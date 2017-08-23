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
            <li><a href= "{{ url('/') }}">Dashboard</a></li>
            <li class="active"><i class="fa fa-angle-right"></i>Download</li>                                
          </ul>
        </div>

        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Download</h5>
                </div>               
                <div class="tab-content tab-content-alt-1 bg-white">
                  <div role="tabpanel" class="active tab-pane" id="tab-1">
                    <div class="table-responsive dataTables_wrapper table-pendapatan">
                         <table ui-jq="dataTable" class="table table-btl table-striped b-t b-b table-download" id="table-download">
                         <thead>
                          <tr>
                            <th width="1%">No</th>
                            <th>Keterangan</th>
                          </tr>
                          <tr>
                            <th colspan="2" class="th_search">
                              <i class="icon-bdg_search"></i>
                              <input type="search" class="table-search form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>1</td>
                            <td>
                              Rekap Rincian
                              <a href="" class="btn btn-success pull-right m-t-n-sm"><i class="glyphicon glyphicon-download-alt"></i></a>
                            </td>
                          </tr>
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