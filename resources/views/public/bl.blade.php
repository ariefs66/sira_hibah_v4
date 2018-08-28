@extends('budgeting.layout')

@section('content')
<div id="content" class="app-content" role="main">
  <div class="hbox hbox-auto-xs hbox-auto-sm ng-scope">
    <div class="col">
      <div class="app-content-body ">

        <div class="bg-light lter">    
          <ul class="breadcrumb bg-white m-b-none">
          <li>
              <a href="{{ url('/') }}/public/{{$tahun}}/{{$status}}" class="btn no-shadow">
                <i class="icon-bdg_expand1 text"></i>
              </a>   
            </li>
            <li><a href= "{{ url('/') }}/public/{{$tahun}}/{{$status}}">Dashboard</a></li>
            <li><i class="fa fa-angle-right"></i>Belanja</li>                               
            <li class="active"><i class="fa fa-angle-right"></i>Belanja Langsung</li>                                
          </ul>
        </div>

        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Belanja Langsung</h5> 
                  <div class="col-sm-4 pull-right m-t-n-sm">
                   <select ui-jq="chosen" class="form-control" id="filter-skpd">
                     <option value="">- Pilih OPD -</option>
                     @foreach($skpd as $pd)
                     <option value="{{ $pd->SKPD_ID }}">{{ $pd->SKPD_NAMA }}</option>
                     @endforeach
                   </select><br>
                 </div>
               </div>   
               

                <div role="tabpanel" class="active tab-pane" id="tab-1">
                 
                  <div class="tab-content tab-content-alt-1 bg-white">
                

                  <div class="table-responsive dataTables_wrapper">
                   <table ui-jq="dataTable" ui-options="{
                        sAjaxSource: '{{ url('/') }}/public//{{ $tahun }}/{{ $status }}/belanja-langsung/getMurni/0',
                        aoColumns: [
                          { mData: 'NO',class:'text-center' },
                          { mData: 'KEGIATAN' },
                          { mData: 'PAGU' },
                          { mData: 'RINCIAN' }]
                      }" class="table table-jurnal table-striped b-t b-b" id="table-index">
                    <thead>
                      <tr>
                        <th rowspan="2" style="width: 1%">#</th>
                        <th rowspan="2">Program/Kegiatan/Sub Unit</th>
                        <th colspan="2" style="text-align: center;">Anggaran</th>                                     
                      </tr>
                      <tr>
                        <th style="width: 15%">Pagu</th>                                      
                        <th style="width: 15%">Rincian</th>                                     
                      </tr>
                      <tr>
                        <th colspan="7" class="th_search">
                          <i class="icon-bdg_search"></i>
                          <input type="search" class="table-search form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="2"><b>Total</b></td>
                        <td><b><text id="pagu_foot"></text></b></td>
                        <td><b><text id="rincian_foot"></text></b></td>
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
</div>
</div>
<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
@endsection

@section('plugin')
<script type="text/javascript">
  $("select.selectrincian").on('click',function() {
    $('.table').DataTable().page.len($('.selectrincian').val()).draw();
  });

  $('#filter-skpd').change(function(e, params){
      var id  = $('#filter-skpd').val();
      $('#table-index').DataTable().destroy();
      $('#table-index').DataTable({
        sAjaxSource: "{{ url('/') }}/public//{{ $tahun }}/{{ $status }}/belanja-langsung/getMurni/"+id,
        aoColumns: [
          { mData: 'NO',class:'text-center' },
          { mData: 'KEGIATAN' },
          { mData: 'PAGU' },
          { mData: 'RINCIAN' },
          { mData: 'STATUS' }],
          initComplete:function(setting,json){
            $("#pagu_foot").html(json.pagu_foot);
            $("#rincian_foot").html(json.rincian_foot);
        }
      });  
  });
</script>
@endsection
