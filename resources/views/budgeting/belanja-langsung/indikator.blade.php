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
            <li><i class="fa fa-angle-right"></i>Belanja Langsung</li>                                
            <li class="active"><i class="fa fa-angle-right"></i>Ubah Belanja Langsung</li>                                
          </ul>
        </div>

        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">

              <div class="panel bg-white">

                <div class="panel-heading wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Indikator Belanja Langsung</h5>
                </div>

                <div class="tab-content tab-content-alt-1 bg-white">
                  <div class="bg-white wrapper-lg input-jurnal">
                    <form action="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/detail/ubah/simpan/" method="POST" class="form-horizontal">
                      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">                            
                      <div class="input-wrapper">                                  
                        <div id="out1">
                        <div class="form-group">
                          <label class="col-sm-1">Output</label>
                          <div class="col-sm-6">
                            <input type="text" class="form-control" name="keluaran" placeholder="Keluaran" id="tolak-ukur">
                            <input type="hidden" class="form-control" name="id" placeholder="Keluaran" id="id-indikator">
                            <input type="hidden" class="form-control" name="id-bl" placeholder="Keluaran" id="id-bl" value="{{$id}}">
                          </div>
                          <div class="col-sm-2">
                            <input type="text" class="form-control" name="target-keluaran" placeholder="Target" id="target-capaian">
                          </div>
                          <div class="col-sm-2">
                            <select class="w-full" name="satuan-capaian" id="satuan-capaian">
                              <option value="">Satuan</option>
                              @foreach($satuan as $st)
                              <option value="{{ $st->SATUAN_ID }}">{{ $st->SATUAN_NAMA }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-sm-1">
                            <a class="btn btn-success" id="tambah-output" onclick="return simpanCapaian()"><i class="fa fa-plus"></i></a>
                          </div>
                        </div>
                        </div>
                        <hr class="m-t-xl">
                        <div class="table-responsive dataTables_wrapper">
                          <table ui-jq="dataTable" ui-options="{
                                sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/capaianedit/{{ $id }}',
                                aoColumns: [
                                  { mData: 'INDIKATOR' },
                                  { mData: 'TOLAK_UKUR' },
                                  { mData: 'TARGET',class:'text-right' },
                                  { mData: 'OPSI',class:'text-right' }]
                              }" class="table table-striped b-t b-b"  id="tabel-indikator">
                            <thead>
                              <tr>
                                <th width="20%">Indikator</th>
                                <th>Tolak Ukur</th>
                                <th width="15%">Target</th>
                                <th width="12%">OPSI</th>
                              </tr>
                              <tr>
                                <th colspan="4" class="th_search">
                                  <i class="icon-bdg_search"></i>
                                  <input type="search" class="cari-detail form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>                        
                          </table>
                        </div>
                     </div>
                   </form>
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
  });

  $("#program").change(function(e, params){
    var id  = $('#program').val();
    var sub = $('#sub_id').val();
    $('#kegiatan').find('option').remove().end().append('<option>Pilih Kegiatan</option>');
    $('#capaian').val();
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/kegiatan/"+id+"/"+sub,
      success : function (data) {
        $('#kegiatan').append(data).trigger('chosen:updated');
      }
    });
  });

  $("#kegiatan").change(function(e, params){
    var id  = $('#kegiatan').val();
    $('#tabel-indikator').DataTable().destroy();
    $('#tabel-indikator').DataTable({
      sAjaxSource: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/capaian/"+id,
      aoColumns: [
        { mData: 'INDIKATOR' },
        { mData: 'TOLAK_UKUR' },
        { mData: 'TARGET',class:'text-right' }]
    });
  }); 
  function hapusOutput(id){
      token       = $('#token').val();
      $.ajax({
          url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/kegiatan/hapusOutput",
          type: "POST",
          data: {'_token'         : token,
                'id'              : id},
          success: function(msg){
            $.alert(msg);
            $('#table-capaian').DataTable().ajax.reload();
          }
      }); 
    }   
    function editOutput(id){
      $.ajax({
          url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/kegiatan/output/"+id,
          type: "GET",
          success: function(msg){
            if($('#tolak-ukur').is(':visible')){
              $('#tolak-ukur').val(msg['OUTPUT_TOLAK_UKUR']);
              $('#target-capaian').val(msg['OUTPUT_TARGET']);
              $('#satuan-capaian').val(msg['SATUAN_ID']);
              $('#id-indikator').val(msg['OUTPUT_ID']);
              $('#tolak-ukur').hide();
              $('#satuan-capaian').hide();
              $('#tambah-output').removeClass('btn-success').addClass('btn-warning').html('<i class="mi-edit"></i>');
            }else{
              $('#id-indikator').val(null);
              $('#tolak-ukur').val(null);
              $('#target-capaian').val(null);
              $('#satuan-capaian').val(0);
              $('#tolak-ukur').show();
              $('#satuan-capaian').show();
              $('#tambah-output').removeClass('btn-warning').addClass('btn-success').html('<i class="fa fa-plus"></i>');
            };
          }
      }); 
    }

    function simpanCapaian(){
    id          = $('#id-bl').val();
    tipe        = $('#indikator-capaian').val();
    tolakukur   = $('#tolak-ukur').val();
    target      = $('#target-capaian').val();
    satuan      = $('#satuan-capaian').val();
    token       = $('#token').val();
    type        = $('#tipe-capaian').val();
    idindikator = $('#id-indikator').val();
    if(idindikator){
        uri = "{{ url('/') }}/main/{{$tahun}}/{{$status}}/pengaturan/kegiatan/editCapaian"; 
    }else{
        uri = "{{ url('/') }}/main/{{$tahun}}/{{$status}}/pengaturan/kegiatan/submitCapaian"; 
    }
    $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'id'              : id, 
              'idindikator'     : idindikator, 
              'tipe'            : tipe,
              'tolakukur'       : tolakukur, 
              'target'          : target, 
              'satuan'          : satuan},
        success: function(msg){
          $.alert(msg);
          $('#tambah-output').removeClass('btn-warning').addClass('btn-success').html('<i class="fa fa-plus"></i>');
          $('#tolak-ukur').show();
          $('#satuan-capaian').show();
          $('#id-indikator').val(null);
          $('#tolak-ukur').val(null);
          $('#target-capaian').val(null);
          $('#satuan-capaian').val(0);
          $('#tabel-indikator').DataTable().ajax.reload();
        }
    });
  }

  function hapusOutput(id){
      token       = $('#token').val();
      $.ajax({
          url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/kegiatan/hapusOutput",
          type: "POST",
          data: {'_token'         : token,
                'id'              : id},
          success: function(msg){
            $.alert(msg);
            $('#tabel-indikator').DataTable().ajax.reload();
            $('#tambah-output').removeClass('btn-warning').addClass('btn-success').html('<i class="fa fa-plus"></i>');
          }
      }); 
    }
</script>
@endsection