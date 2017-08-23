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
            <li><i class="fa fa-angle-right"></i>Belanja</li>                               
            <li class="active"><i class="fa fa-angle-right"></i>Arsip Belanja Langsung</li>                                
          </ul>
        </div>

        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Arsip Belanja Langsung</h5>
                </div>
                <div class="tab-content tab-content-alt-1 bg-white">
                  <div role="tabpanel" class="active tab-pane" id="tab-1">
                    <div class="table-responsive dataTables_wrapper">
                     <table ui-jq="dataTable" ui-options="{
                     sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/arsip/belanja-langsung/getData',
                     aoColumns: [
                     { mData: 'NO',class:'text-center' },
                     { mData: 'KEGIATAN' },
                     { mData: 'PAGU' },
                     { mData: 'RINCIAN' },
                     { mData: 'STATUS' }]
                   }" class="table table-jurnal table-striped b-t b-b" id="table-index">
                   <thead>
                    <tr>
                      <th rowspan="2" style="width: 1%">#</th>
                      <th rowspan="2">Program/Kegiatan/Sub Unit</th>
                      <th colspan="2" style="text-align: center;">Anggaran</th>                                      
                      <th rowspan="2" width="16%">Status</th>                                      
                    </tr>
                    <tr>
                      <th style="width: 15%">Pagu</th>                                      
                      <th style="width: 15%">Rincian</th>                                     
                    </tr>
                    <tr>
                      <th colspan="5" class="th_search">
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

<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
@endsection

@section('plugin')
<script type="text/javascript">
  function hapus(id){
    var token        = $('#token').val();    
    $.confirm({
      title: 'Hapus Data!',
      content: 'Yakin hapus data?',
      buttons: {
        Ya: {
          btnClass: 'btn-danger',
          action: function(){
            $.ajax({
              url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/arsip/belanja-langsung/delete",
              type: "POST",
              data: {'_token'         : token,
              'id'              : id},
              success: function(msg){
                $('#table-index').DataTable().ajax.reload();                          
                $.alert(msg);
              }
            });
          }
        },
        Tidak: function () {
        }
      }
    });
  }

  function restore(id){
    var token        = $('#token').val();    
    $.confirm({
      title: 'Kembalikan Data!',
      content: 'Yakin kembalikan data?',
      buttons: {
        Ya: {
          btnClass: 'btn-success',
          action: function(){
            $.ajax({
              url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/arsip/belanja-langsung/restore",
              type: "POST",
              data: {'_token'         : token,
              'id'              : id},
              success: function(msg){
                $('#table-index').DataTable().ajax.reload();                          
                $.alert(msg);
              }
            });
          }
        },
        Tidak: function () {
        }
      }
    });
  }
</script>
@endsection