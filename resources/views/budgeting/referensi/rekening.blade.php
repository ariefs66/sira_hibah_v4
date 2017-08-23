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
                <li class="active"><i class="fa fa-angle-right"></i>Rekening</li>                                
              </ul>
          </div>
          <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                    @if(substr(Auth::user()->mod,4,1)==1)
                    <button class="pull-right btn m-t-n-sm btn-success open-form-btl"><i class="m-r-xs fa fa-plus"></i> Tambah Rekening</button> 
                    @endif                 
                    <h5 class="inline font-semibold text-orange m-n ">Rekening Tahun {{ $tahun }}</h5>
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
                                    sAjaxSource: '{{ url('/') }}/harga/{{$tahun}}/rekening/getData',
                                    aoColumns: [
                                    { mData: 'no',class:'text-center' },
                                    { mData: 'REKENING_KODE' },
                                    { mData: 'REKENING_NAMA' },
                                    { mData: 'KUNCI' },
                                    { mData: 'aksi' }
                                    ]}" class="table table-striped b-t b-b" id="table-rekening">
                                    <thead>
                                      <tr>
                                        <th>No</th>
                                        <th>Kode Rekening</th>
                                        <th>Nama Rekening</th>
                                        <th width="1%">Kunci</th>
                                        <th width="15%">Aksi</th>
                                      </tr>
                                      <tr>
                                        <th colspan="5" class="th_search">
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
        <h5 id="judul-form">Tambah Rekening</h5>
          <div class="form-group">
            <label for="kode_rekening" class="col-md-3">Kode Rekening</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Kode Rekening" name="kode_rekening" id="kode_rekening" value="">          
              <input type="hidden" class="form-control" value="{{ csrf_token() }}" name="_token" id="token">          
              <input type="hidden" class="form-control" name="id_rekening" id="id_rekening">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_rekening" class="col-md-3">Nama Rekening</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Nama Rekening" name="nama_rekening" id="nama_rekening" value="">          
            </div> 
          </div>

          <hr class="m-t-xl">
         <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanRekening()"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
      </div>
    </form>
  </div>
 </div>
@endsection

@section('plugin')
<script type="text/javascript">
  function simpanRekening(){
    var kode_rekening  = $('#kode_rekening').val();
    var nama_rekening  = $('#nama_rekening').val();
    var id_rekening    = $('#id_rekening').val();
    var token        = $('#token').val();
    if(kode_rekening == "" || nama_rekening == ""){
      $.alert('Form harap diisi!');
    }else{
      if(id_rekening == '') uri = "{{ url('/') }}/harga/{{ $tahun }}/rekening/add/submit";
      else uri = "{{ url('/') }}/harga/{{ $tahun }}/pengaturan/rekening/edit/submit";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'kode_rekening'     : kode_rekening, 
              'tahun'           : '{{$tahun}}', 
              'id_rekening'       : id_rekening, 
              'nama_rekening'     : nama_rekening},
        success: function(msg){
            if(msg == 1){
              $('#kode_rekening').val('');
              $('#nama_rekening').val('');
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
                      url: "{{ url('/') }}/harga/{{ $tahun }}/rekening/delete",
                      type: "POST",
                      data: {'_token'         : token,
                            'id_rekening'       : id},
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
    $('#judul-form').text('Ubah Rekening');        
    $.ajax({
      url: "{{ url('/') }}/harga/{{ $tahun }}/rekening/getData/"+id,
      type: "GET",
      success: function(msg){
        $('#kode_rekening').val(msg[0]['REKENING_KODE']);
        $('#nama_rekening').val(msg[0]['REKENING_NAMA']);
        $('#id_rekening').val(msg[0]['REKENING_ID']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-btl').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        });
      }
    });    
  }

  $('a.tutup-form').click(function(){
        $('#judul-form').text('Tambah Rekening');        
        $('#kode_rekening').val('');
        $('#nama_rekening').val('');
        $('#id_rekening').val('');
  });

  function buka(id){
    var token        = $('#token').val();
    $.ajax({
      url: "{{ url('/') }}/harga/{{$tahun}}/rekening/0",              
      type: "POST",
      data: {'_token'         : token,
              'REKENING_ID'   : id},
      success: function(msg){
        $('#table-rekening').DataTable().ajax.reload();
        $.alert(msg);
      }
    });
  } 

  function kunci(id){
    var token        = $('#token').val();
    $.ajax({
      url: "{{ url('/') }}/harga/{{$tahun}}/rekening/1",              
      type: "POST",
      data: {'_token'         : token,
              'REKENING_ID'   : id},
      success: function(msg){
        $('#table-rekening').DataTable().ajax.reload();
        $.alert(msg);
      }
    });
  }
</script>
@endsection


