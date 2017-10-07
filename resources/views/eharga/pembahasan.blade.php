@extends('eharga.layout')

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
            <li><a href= "{{ url('/') }}/harga/{{$tahun}}">Eharga</a></li>
            <li class="active"><i class="fa fa-angle-right"></i>Pembahasan</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12" id="btl">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n">Data Pembahasan</h5>
                  <div class="col-sm-1 pull-right m-t-n-sm">
                    <select class="form-control dtSelect" id="dtSelect">
                      <option value="10">10</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                    </select>
                  </div>                    
                </div>
                <!-- Main tab -->
              <!-- / main tab -->                  
              <div class="tab-content tab-content-alt-1 bg-white">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <div role="tabpanel" class="active tab-pane" id="tab-1">  
                  <div class="table-responsive dataTables_wrapper table-usulan">
                   <table ui-jq="dataTable" ui-options="{
                   sAjaxSource: '{{ url('/') }}/harga/{{$tahun}}/usulan/pembahasan/getdata',
                   aoColumns: [
                      { mData: 'ID',class:'hide'},
                      { mData: 'NO',class:'text-center'},
                      { mData: 'PD'},
                      { mData: 'TIPE'},
                      { mData: 'NAMA'},
                      { mData: 'REKENING'},
                      { mData: 'HARGA',class:'text-right'},
                      { mData: 'POSISI'},
                   ]}" class="table table-striped b-t b-b table-pembahasan" id="table-pembahasan">
                   <thead>
                    <tr>
                          <th class="hide">No</th>
                          <th>No</th>
                          <th width="1%">Perangkat Daerah</th>
                          <th width="1%">Tipe</th>
                          <th>Nama Barang / Kategori / Spesifikasi</th>
                          <th>Rekening</th>
                          <th>Harga Usulan</th>
                          <th width="10%">Posisi</th>
                        </tr>
                        <tr>
                          <th class="hide"></th>
                          <th colspan="7" class="th_search">
                            <i class="icon-bdg_search"></i>
                            <input type="search" class="table-search cari-usulan form-control b-none w-full" placeholder="Cari Usulan" aria-controls="DataTables_Table_0">
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
@endsection

@section('plugin')

<script type="text/javascript">
  $(document).ready(function(){
    $("#app").trigger('click');
  });

  $('#table-pembahasan').on('click','tbody > tr', function(){
      kode = $(this).children('td').eq(0).html();
      //window.open("{{ url('/') }}/harga/{{$tahun}}/usulan/pembahasan/detail/"+kode);
   });
</script>

<script type="text/javascript">
  function acceptpembahasan(id){
    //alert('Accept : '+$id);
    token       = $('#token').val();
    //alert(token);exit;
    $.ajax({
      url: "{{ url('/') }}/harga/{{$tahun}}/usulan/pembahasan/accept",
      type: "POST",
      data: {'_token' : token,'USULAN_ID' : id},
      success: function(msg){
        $('#table-pembahasan').DataTable().ajax.reload();
        $.alert(msg);
      }
    });
  }

  function rejectpembahasan(id){
    //alert('Reject : '+$id);
    var token        = $('#token').val();    
    $.confirm({
      title: 'Tolak Data!',
      content: 'Yakin usulan ditolak?',
      buttons: {
        Ya: {
          btnClass: 'btn-danger',
          action: function(){
            $.ajax({
              url: "{{ url('/') }}/harga/{{$tahun}}/usulan/pembahasan/decline",              
              type: "POST",
              data: {'_token'         : token,
                    'USULAN_ID'       : id},
              success: function(msg){
                $('#table-pembahasan').DataTable().ajax.reload();
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