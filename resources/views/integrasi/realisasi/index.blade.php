@extends('budgeting.layout')

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
              <a href= "#">Integrasi</a>
              <li class="active"><i class="fa fa-angle-right"></i>Realisasi</li>
            </li>
          </ul>
        </div>

        <div class="wrapper-lg"> <!-- Start Wrapper -->
          
          <div class="row">
            <div class="col-md-12" id="alert-sync-realisasi">
            </div>
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Realisasi dari Simda</h5>
                  <div class="row">
                    <div class="col-md-12">
                      <a class="col-sm-2 pull-right btn btn-success m-t-n-sm" data-toggle="modal" id="btn-modal-sinkron" data-target="#modal-sinkron-realisasi"><i class="m-r-xs fa fa-refresh"></i>Sinkron</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <div class="row">
                    <div class="col-md-8">
                    </div>
                    <div class="col-md-4">
                      <select ui-jq="chosen" class="form-control" id="filter-skpd" style="color: black">
                        <option value="">- Pilih OPD -</option>
                        @foreach($skpd as $pd)
                        <option value="{{ $pd->SKPD_KODE }}">{{ $pd->SKPD_NAMA }}</option>
                        @endforeach
                      </select>    
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="tab-content tab-content-alt-1 bg-white">
                        <div role="tabpanel" class="active tab-pane" id="tab-1">  
                          <div class="table-responsive dataTables_wrapper">
                            <table id="table-realisasi" ui-jq="dataTable" ui-options="" class="table table-striped b-t b-b">
                                  <thead>
                                    <tr>
                                      <th>PROGRAM NAMA</th>
                                      <th>KEGIATAN NAMA</th>
                                      <th>KODE REK BL</th>
                                      <th>REKENING_NAMA</th>
                                      <th>PAGU</th>
                                      <th>REALISASI</th>
                                      <th>SELISIH</th>
                                    </tr>
                                    <tr>
                                      <th colspan="7" class="th_search">
                                          <i class="icon-bdg_search"></i>
                                          <input type="search" class="table-search form-control b-none w-full" placeholder="Cari Kode Rekening Belanja" aria-controls="DataTables_Table_0">
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

        </div> <!-- End Wrapper -->

      </div>
    </div>
  </div>
</div>

<!-- Start List Modal -->

<!-- Start 1. Modal Sinkronisasi -->
<div class="modal fade" id="modal-sinkron-realisasi" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Sinkroninsasi realisasi simda</h5>
        <hr>
        <p>
          Proses sinkronisasi akan memakan waktu yang relatif lama. Dimohon untuk tetap berada pada halaman ini
          sampai proses selesai dilakukan.
        </p>
        <div class="row">
          <div class="col-sm-3">
            <br>
            <br>
            <i class="load-sync">Loading ...</i>
          </div>
          <div class="col-sm-3 pull-right">
            <button class="btn btn-success m-t-md" id="btn_sync_process"><i class="m-r-xs fa fa-refresh"></i>Sinkron</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End 1. Modal Sinkronisasi -->

<!-- End List Modal -->
@endsection
@section('plugin')
<script type="text/javascript">
  $("#btn-modal-sinkron").click(function(){
    $('.load-sync').hide();
  });
  $("#btn_sync_process").click(function(){
    $('.preloader-wrapper').fadeIn();
    $('#modal-sinkron-realisasi').modal('toggle');
    $.ajax({
      url: "{{route('realisasi-sync',[$tahun])}}",
      type: "POST",
      dataType: 'json',
      data : {
        "_token": "{{ csrf_token() }}",
      },
      error: function(xhr) {
      },
      success: function(data) {
      },
      complete: function(data) {

        $('.preloader-wrapper').fadeOut();
        var alertval='';
        if (data.responseJSON.http_code == 500) {
          alertval = 'alert alert-danger alert-dismissible';
        } else {
          alertval = 'alert alert-success alert-dismissible';
        }

        $("#alert-sync-realisasi").append('<div class="'+alertval+'" role="alert">'
          +'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
          +'<strong> Message : </strong>'+data.responseJSON.message+'</div>');
      }
    });
  });
  $("#filter-skpd").change(function(){
    var skpdKode = $(this).val();
    $('#table-realisasi').DataTable().destroy();
    $('#table-realisasi').DataTable({
      ajax : {
        url : "{{route('realisasi-get',[$tahun])}}",
        data : {skpd_kode : skpdKode}
      },
      aoColumns: [
        { mData: 'PROGRAM_NAMA' },
        { mData: 'KEGIATAN_NAMA' },
        { mData: 'KODE_REK_BL' },
        { mData: 'REKENING_NAMA' },
        { mData: 'PAGU' },
        { mData: 'REALISASI_TOTAL' },
        { mData: 'SELISIH' }
      ]
    });
    $.fn.dataTable.ext.errMode = 'none';

    /*{
    sAjaxSource: '{{route('realisasi-get',[$tahun])}}',
    aoColumns: [
    
    ]}*/
  });
</script>
@endsection