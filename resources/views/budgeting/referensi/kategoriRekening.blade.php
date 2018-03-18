@extends('eharga.layout')

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
                <li><a href= "{{ url('/') }}/harga/{{$tahun}}">Dashboard</a></li>
                <li class="active"><i class="fa fa-angle-right"></i>Kategori Rekening</li>                                
              </ul>
          </div>
          <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                    @if(substr(Auth::user()->mod,4,1)==1)
                     <div class="dropdown dropdown-blend pull-right m-t-n-sm">
                        <button class="btn btn-success dropdown-toggle open-form-btl"><i class="fa fa-plus"></i> Tambah</button>
                      </div>
                    @endif                 
                    <h5 class="inline font-semibold text-orange m-n ">Kategori Rekening Tahun {{ $tahun }}</h5>
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
                                    sAjaxSource: '{{ url('/') }}/harga/{{$tahun}}/kategori/rekening/getData',
                                    aoColumns: [
                                    { mData: 'no',class:'text-center' },
                                    { mData: 'KATEGORI_TAHUN' },
                                    { mData: 'KATEGORI_KODE' },
                                    { mData: 'KATEGORI_NAMA' },
                                    { mData: 'KUNCI' },
                                    { mData: 'aksi' }
                                    ]}" class="table table-striped b-t b-b" id="table-rekening">
                                    <thead>
                                      <tr>
                                        <th>No</th>
                                        <th>TAHUN</th>
                                        <th>KODE KATEGORI </th>
                                        <th>NAMA KATEGORI</th>
                                        <th width="1%">KUNCI</th>
                                        <th width="15%">AKSI</th>
                                      </tr>
                                      <tr>
                                        <th colspan="6" class="th_search">
                                            <i class="icon-bdg_search"></i>
                                            <input type="search" class="table-search form-control b-none w-full" placeholder="Cari Rekening" aria-controls="DataTables_Table_0">
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
    <form id="form-rekening" class="form-horizontal">
      <div class="input-wrapper">
        <h5 id="judul-form">Tambah Kategori Rekening</h5>
          <div class="form-group">
            <label for="kode_rekening" class="col-md-3">Kode Kategori Rekening</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Kode Kategori" name="kode_kategori" id="kode_kategori" value="">          
              <input type="hidden" class="form-control" value="{{ csrf_token() }}" name="_token" id="token">          
              <input type="hidden" class="form-control" name="id_kategori" id="id_kategori">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_rekening" class="col-md-3">Nama Kategori Rekening</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Nama Kategori" name="nama_kategori" id="nama_kategori" value="">          
            </div> 
          </div>

          <hr class="m-t-xl">
         <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanKategori()"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
      </div>
    </form>
  </div>
 </div>

@endsection

@section('plugin')
<script type="text/javascript">
  function simpanKategori(){
    var kode_kategori  = $('#kode_kategori').val();
    var nama_kategori  = $('#nama_kategori').val();
    var id_kategori    = $('#id_kategori').val();
    var token        = $('#token').val();
    if(kode_kategori == "" || nama_kategori == ""){
      $.alert('Form harap diisi!');
    }else{
      if(id_kategori == '') uri = "{{ url('/') }}/harga/{{ $tahun }}/kategori/rekening/add/submit";
      else uri = "{{ url('/') }}/harga/{{ $tahun }}/kategori/rekening/edit/submit";
      //uri = "{{ url('/') }}/harga/{{ $tahun }}/kategori/rekening/add/submit";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'kode_kategori'     : kode_kategori, 
              'tahun'           : '{{$tahun}}', 
              'id_kategori'       : id_kategori, 
              'nama_kategori'     : nama_kategori},
        success: function(msg){
            if(msg == 1){
              $('#kode_kategori').val('');
              $('#nama_kategori').val('');
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
                      url: "{{ url('/') }}/harga/{{ $tahun }}/kategori/rekening/delete",
                      type: "POST",
                      data: {'_token'         : token,
                            'id_kategori'       : id},
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
    $('#judul-form').text('Ubah Kategori');        
    $.ajax({
      url: "{{ url('/') }}/harga/{{ $tahun }}/kategori/rekening/getData/"+id,
      type: "GET",
      success: function(msg){
        $('#kode_kategori').val(msg[0]['KATEGORI_KODE']);
        $('#nama_kategori').val(msg[0]['KATEGORI_NAMA']);
        $('#id_kategori').val(msg[0]['KATEGORI_ID']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-btl').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        });
      }
    });    
  }

  $('a.tutup-form').click(function(){
        $('#judul-form').text('Tambah Kategori');        
        $('#kode_kategori').val('');
        $('#nama_kategori').val('');
        $('#id_kategori').val('');
  });

  function buka(id){
    var token        = $('#token').val();
    $.ajax({
      url: "{{ url('/') }}/harga/{{$tahun}}/kategori/rekening/0",              
      type: "POST",
      data: {'_token'         : token,
              'KATEGORI_ID'   : id},
      success: function(msg){
        $('#table-rekening').DataTable().ajax.reload();
        $.alert(msg);
      }
    });
  } 

  function kunci(id){
    var token        = $('#token').val();
    $.ajax({
      url: "{{ url('/') }}/harga/{{$tahun}}/kategori/rekening/1",              
      type: "POST",
      data: {'_token'         : token,
              'KATEGORI_ID'   : id},
      success: function(msg){
        $('#table-rekening').DataTable().ajax.reload();
        $.alert(msg);
      }
    });
  }
</script>
@endsection


