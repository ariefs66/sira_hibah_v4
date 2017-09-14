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
            <li class="active"><i class="fa fa-angle-right"></i>Usulan</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Monitorung Usulan</h5>
                  <a class="pull-right btn btn-info m-t-n-sm m-r-sm" href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/download/rekapreses"><i class="m-r-xs fa fa-download"></i> Download Reses</a>                  
               </div>
               <!-- Main tab -->
               <div class="nav-tabs-alt tabs-alt-1 b-t six-row" id="tab-jurnal" >
                <ul class="nav nav-tabs" role="tablist">
                 <li class="active">
                  <a data-target="#tab-1" role="tab" data-toggle="tab" class="menu" data-status="1">Musrenbang</a>
                </li>
                <li>
                  <a data-target="#tab-2" role="tab" data-toggle="tab" class="menu" data-status="2">Reses</a>
                </li>
                <li>
                  <a data-target="#tab-3" role="tab" data-toggle="tab">PIPPK RW</a>
                </li>
                <li>
                  <a data-target="#tab-4" role="tab" data-toggle="tab">PIPPK TARKA</a>
                </li>
                <li>
                  <a data-target="#tab-5" role="tab" data-toggle="tab">PIPPK LPM</a>
                </li>
                <li>
                  <a data-target="#tab-6" role="tab" data-toggle="tab">PIPPK PKK  </a>
                </li>

              </ul>

            </div>
            <!-- / main tab -->                  
            <div class="tab-content tab-content-alt-1 bg-white">
              <div role="tabpanel" class="active tab-pane " id="tab-1">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                 sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/musrenbang/renja',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'OPSI'},
                    { mData: 'PD'},
                    { mData: 'JUMLAH'},
                    { mData: 'TOTAL'},
                    { mData: 'IN'}
                  ],iDisplayLength: 100}" class="table table-striped b-t b-b table-renja " id="table-renja">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th>Opsi</th>
                      <th>Perangkat Deaerah</th>
                      <th>Renja</th>
                      <th>Total Renja</th>
                      <th>Total Masuk</th>
                    </tr>
                    <tr>
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-renja form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                      <tr>
                        <td colspan="3"> </td>
                        <td><b>Renja : <text id="renja"></text> </b></td>
                        <td><b>Total Renja : Rp. <text id="total_renja"></text> </b></td>
                        <td><b>Total Masuk : Rp. <text id="total_masuk"></text> </b></td>
                      </tr>  
                    </tfoot>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-2">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/musrenbang/reses',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'OPSI'},
                    { mData: 'PD'},
                    { mData: 'JUMLAH'},
                    { mData: 'TOTAL'},
                    { mData: 'IN'}
                  ],iDisplayLength: 100}" class="table table-striped b-t b-b table-reses " id="table-reses">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th>Opsi</th>
                      <th>Perangkat Deaerah</th>
                      <th>Reses</th>
                      <th>Total Reses</th>
                      <th>Total Masuk</th>
                    </tr>
                    <tr>
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-musrenbang form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                      <tr>
                        <td colspan="2"> </td>
                        <td><b>Reses : <text id="reses"></text> </b></td>
                        <td><b>Total Reses : Rp. <text id="total_reses"></text> </b></td>
                        <td><b>Total Masuk : Rp. <text id="total_masuk"></text> </b></td>
                      </tr>  
                    </tfoot>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-3">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/musrenbang/rw',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'KECAMATAN'},
                    { mData: 'KELURAHAN'},
                    { mData: 'TOTAL'},
                    { mData: 'IN'},
                    { mData: 'PERSENTASE'}
                  ],iDisplayLength: 100}" class="table table-striped b-t b-b table-rw " id="table-rw">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th width="1%">Kecamatan</th>
                      <th width="1%">Kelurahan</th>
                      <th>Total</th>
                      <th>Masuk</th>
                      <th width="1%">%</th>
                    </tr>
                    <tr>
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-rw form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-4">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/musrenbang/karta',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'KECAMATAN'},
                    { mData: 'KELURAHAN'},
                    { mData: 'TOTAL'},
                    { mData: 'IN'},
                    { mData: 'PERSENTASE'}
                  ],iDisplayLength: 100}" class="table table-striped b-t b-b table-karta " id="table-karta">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th width="1%">Kecamatan</th>
                      <th width="1%">Kelurahan</th>
                      <th>Total</th>
                      <th>Masuk</th>
                      <th width="1%">%</th>
                    </tr>
                    <tr>
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-karta form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-5">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/musrenbang/pkk',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'KECAMATAN'},
                    { mData: 'KELURAHAN'},
                    { mData: 'TOTAL'},
                    { mData: 'IN'},
                    { mData: 'PERSENTASE'}
                  ],iDisplayLength: 100}" class="table table-striped b-t b-b table-pkk " id="table-pkk">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th width="1%">Kecamatan</th>
                      <th width="1%">Kelurahan</th>
                      <th>Total</th>
                      <th>Masuk</th>
                      <th width="1%">%</th>
                    </tr>
                    <tr>
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-pkk form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-6">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/musrenbang/lpm',
                    aoColumns: [
                    { mData: 'NO'},
                    { mData: 'KECAMATAN'},
                    { mData: 'KELURAHAN'},
                    { mData: 'TOTAL'},
                    { mData: 'IN'},
                    { mData: 'PERSENTASE'}
                  ],iDisplayLength: 100}" class="table table-striped b-t b-b table-lpm " id="table-lpm">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th width="1%">Kecamatan</th>
                      <th width="1%">Kelurahan</th>
                      <th>Total</th>
                      <th>Masuk</th>
                      <th width="1%">%</th>
                    </tr>
                    <tr>
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="cari-lpm form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
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

    $(".menu").on("click",function(event) {
      status = $(this).data('status'); 
      if(status == 1)   resetTableRenja(); else resetTableReses();

    });

    function resetTableRenja(){
     // alert("renja");
      $('#table-renja').DataTable().destroy();
      $('#table-renja').DataTable({
            sAjaxSource: "{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/musrenbang/renja",
            aoColumns: [
            { mData: 'NO' },
            { mData: 'PD' },
            { mData: 'JUMLAH' },
            { mData: 'TOTAL' },
            { mData: 'ISUKAMUS' },
          ],
          "order": [[100, "asc"]], 
          initComplete:function(setting,json){
              $("#renja").html(json.renja);
              $("#total_renja").html(json.total_renja);
              $("#total_masuk").html(json.total_masuk);
          }
      });

  }

  function resetTableReses(){
     // alert("reses");
     $('#table-renja').DataTable().destroy();
        $('#table-renja').DataTable({
              sAjaxSource: "{{ url('/') }}/main/{{$tahun}}/{{$status}}/statistik/musrenbang/renja",
              aoColumns: [
              { mData: 'NO' },
              { mData: 'PD' },
              { mData: 'JUMLAH' },
              { mData: 'TOTAL' },
              { mData: 'ISUKAMUS' },
            ],
            "order": [[100, "asc"]], 
            initComplete:function(setting,json){
                $("#renja").html(json.renja);
                $("#total_renja").html(json.total_renja);
                $("#total_masuk").html(json.total_masuk);
            }
        });

  }

  });

  $('.cari-renja').keyup( function () {
    $('#table-renja').DataTable().search($('.cari-renja').val()).draw();
  });
  
  $('.cari-rw').keyup( function () {
    $('#table-rw').DataTable().search($('.cari-rw').val()).draw();
  });

  $('.cari-karta').keyup( function () {
    $('#table-karta').DataTable().search($('.cari-karta').val()).draw();
  });

  $('.cari-pkk').keyup( function () {
    $('#table-pkk').DataTable().search($('.cari-pkk').val()).draw();
  });

  $('.cari-lpm').keyup( function () {
    $('#table-lpm').DataTable().search($('.cari-lpm').val()).draw();
  });

</script>
@endsection