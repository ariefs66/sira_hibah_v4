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
                <li><a>Pengaturan</a></li>
                <li class="active"><i class="fa fa-angle-right"></i>Urusan</li>                                
              </ul>
          </div>
          <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                    @if(Auth::user()->active==15)
                    <button class="pull-right btn m-t-n-sm btn-success open-form-btl"><i class="m-r-xs fa fa-plus"></i> Tambah Urusan</button>   
                    @endif
                    <h5 class="inline font-semibold text-orange m-n ">Urusan Tahun {{ $tahun }}</h5>
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
                             <table ui-jq="dataTable" ui-options="{
                                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/pengaturan/urusan/getData',
                                    aoColumns: [
                                    { mData: 'no',class:'text-center' },
                                    { mData: 'URUSAN_TAHUN' },
                                    { mData: 'URUSAN_KODE' },
                                    { mData: 'URUSAN_NAMA' },
                                    { mData: 'aksi' }
                                    ]}" class="table table-striped b-t b-b">
                                    <thead>
                                      <tr>
                                        <th>No</th>
                                        <th>Tahun Urusan</th>
                                        <th>Kode Urusan</th>
                                        <th>Nama Urusan</th>
                                        <th>Aksi</th>
                                      </tr>
                                      <tr>
                                        <th colspan="5" class="th_search">
                                            <i class="icon-bdg_search"></i>
                                            <input type="search" class="table-search form-control b-none w-full" placeholder="Cari Urusan" aria-controls="DataTables_Table_0">
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
      <!-- App-content-body -->  
    </div>
    <!-- .col -->
</div>

<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-btl">
<a href="#" class="tutup-form"><i class="icon-bdg_cross"></i></a>
    <form id="form-urusan" class="form-horizontal">
      <div class="input-wrapper">
        <h5 id="judul-form">Tambah Urusan</h5>
          <div class="form-group">
            <label for="kode_urusan" class="col-md-3">Kode Urusan</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Kode Urusan" name="kode_urusan" id="kode_urusan" value="">          
              <input type="hidden" class="form-control" value="{{ csrf_token() }}" name="_token" id="token">          
              <input type="hidden" class="form-control" name="id_urusan" id="id_urusan">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Nama Urusan</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Nama Urusan" name="nama_urusan" id="nama_urusan" value="">          
            </div> 
          </div>

          <hr class="m-t-xl">
         <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanUrusan()"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
      </div>
    </form>
  </div>
 </div>
@endsection

@section('plugin')
<script type="text/javascript">
  function simpanUrusan(){
    var kode_urusan  = $('#kode_urusan').val();
    var nama_urusan  = $('#nama_urusan').val();
    var id_urusan    = $('#id_urusan').val();
    var token        = $('#token').val();
    if(kode_urusan == "" || nama_urusan == ""){
      $.alert('Form harap diisi!');
    }else{
      if(id_urusan == '') uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/urusan/add/submit";
      else uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/urusan/edit/submit";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'kode_urusan'     : kode_urusan, 
              'tahun'           : '{{$tahun}}', 
              'id_urusan'       : id_urusan, 
              'nama_urusan'     : nama_urusan},
        success: function(msg){
            if(msg == 1){
              $('#kode_urusan').val('');
              $('#nama_urusan').val('');
              $('.table').DataTable().ajax.reload();              
              $.alert({
                title:'Info',
                content: 'Data berhasil disimpan',
                autoClose: 'ok|1000',
                buttons: {
                    ok: function () {
                      $('.input-spp,.input-spp-langsung,.input-sidebar').animate({'right':'-1050px'},function(){
                        $('.overlay').fadeOut('fast');
                      });                      
                    }
                }
              });
            }else{
              $.alert('Data telah tersedia!');
            }
          }
        });
    }
  }

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
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/urusan/delete",
                      type: "POST",
                      data: {'_token'         : token,
                            'id_urusan'       : id},
                      success: function(msg){
                          $.alert(msg);
                          $('.table').DataTable().ajax.reload();                          
                        }
                  });
                }
            },
            Tidak: function () {
            }
        }
    });
  }

  function ubah(id) {
    $('#judul-form').text('Ubah Urusan');        
    $.ajax({
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/urusan/getData/"+id,
      type: "GET",
      success: function(msg){
        $('#kode_urusan').val(msg[0]['URUSAN_KODE']);
        $('#nama_urusan').val(msg[0]['URUSAN_NAMA']);
        $('#id_urusan').val(msg[0]['URUSAN_ID']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-btl').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        });
      }
    });    
  }

  $('a.tutup-form').click(function(){
        $('#judul-form').text('Tambah Urusan');        
        $('#kode_urusan').val('');
        $('#nama_urusan').val('');
        $('#id_urusan').val('');
  }); 
</script>
@endsection


