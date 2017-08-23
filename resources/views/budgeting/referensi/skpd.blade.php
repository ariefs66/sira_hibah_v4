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
                <li class="active"><i class="fa fa-angle-right"></i>Perangkat Daerah</li>                                
              </ul>
          </div>
          <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                    <button class="pull-right btn m-t-n-sm btn-success open-form-btl"><i class="m-r-xs fa fa-plus"></i> Tambah Perangkat Daerah</button>
                    <h5 class="inline font-semibold text-orange m-n ">Perangkat Daerah Tahun {{ $tahun }}</h5>
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
                                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/pengaturan/skpd/getData',
                                    aoColumns: [
                                    { mData: 'no',class:'text-center' },
                                    { mData: 'SKPD_KODE' },
                                    { mData: 'SKPD_NAMA' },
                                    { mData: 'SKPD_KEPALA' },
                                    { mData: 'SKPD_BENDAHARA' },
                                    { mData: 'aksi' }
                                    ]}" class="table table-striped b-t b-b">
                                    <thead>
                                      <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Kepala</th>
                                        <th>Bendahara</th>
                                        <th width="15%">Aksi<br>
                                        @if(substr(Auth::user()->mod,1,1) == 1 or Auth::user()->level == 8)                        
                                          <label class="i-switch bg-danger m-t-xs m-r buka-giat"><input type="checkbox" onchange="return kunciAll()" id="kunciall"><i></i></label>
                                        @endif 
                                      </th>
                                      </tr>
                                      <tr>
                                        <th colspan="6" class="th_search">
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
        <h5 id="judul-form">Tambah Perangkat Daerah</h5>
          <div class="form-group">
            <label for="kode_urusan" class="col-md-3">Kode SKPD</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Kode SKPD" name="kode_skpd" id="kode_skpd" value="">          
              <input type="hidden" class="form-control" value="{{ csrf_token() }}" name="_token" id="token">          
              <input type="hidden" class="form-control" name="id_skpd" id="id_skpd">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Nama SKPD</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Nama SKPD" name="nama_skpd" id="nama_skpd" value="">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">NIP Kepala</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan NIP Kepala SKPD" name="kepala_nip" id="kepala_nip" value="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Kepala SKPD</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Kepala SKPD" name="kepala_skpd" id="kepala_skpd" value="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Pangkat Kepala</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Pangkat Kepala SKPD" name="pangkat" id="pangkat" value="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">NIP Bendahara</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Nip Bendahara SKPD" name="bendahara_nip" id="bendahara_nip" value="">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Bendahara SKPD</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Bendahara SKPD" name="bendahara_skpd" id="bendahara_skpd" value="">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Alamat SKPD</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Alamat SKPD" name="alamat" id="alamat" value="">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Pagu SKPD</label>
            <div class="col-sm-9">
              <input type="number" class="form-control" placeholder="Masukan Pagu SKPD" name="pagu" id="pagu" value="">          
            </div> 
          </div>

          <hr class="m-t-xl">
         <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanSKPD()"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
      </div>
    </form>
  </div>
 </div>
@endsection

@section('plugin')
<script type="text/javascript">
  function simpanSKPD(){
    var kode_skpd       = $('#kode_skpd').val();
    var nama_skpd       = $('#nama_skpd').val();
    var kepala_nip      = $('#kepala_nip').val();
    var kepala_skpd     = $('#kepala_skpd').val();
    var pangkat         = $('#pangkat').val();
    var bendahara_nip   = $('#bendahara_nip').val();
    var bendahara_skpd  = $('#bendahara_skpd').val();
    var alamat          = $('#alamat').val();
    var pagu            = $('#pagu').val();
    var id_skpd         = $('#id_skpd').val();
    var token           = $('#token').val();
    if(kode_skpd == "" || nama_skpd == "" || kepala_nip == "" || pangkat == "" || kepala_skpd == "" || bendahara_nip == "" || bendahara_skpd == ""){
      $.alert('Form harap diisi!');
    }else{
      if(id_skpd == '') uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/skpd/add/submit";
      else uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/skpd/edit/submit";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'kode_skpd'       : kode_skpd, 
              'nama_skpd'       : nama_skpd, 
              'kepala_nip'      : kepala_nip, 
              'kepala_skpd'     : kepala_skpd, 
              'pangkat'         : pangkat, 
              'alamat'          : alamat, 
              'bendahara_nip'   : bendahara_nip, 
              'bendahara_skpd'  : bendahara_skpd, 
              'pagu'            : pagu,
              'tahun'           : '{{$tahun}}', 
              'id_skpd'         : id_skpd},
        success: function(msg){
            if(msg == 1){
              $('#judul-form').text('Tambah SKPD');        
              $('#kode_skpd').val('');
              $('#nama_skpd').val('');
              $('#kepala_nip').val('');
              $('#kepala_skpd').val('');
              $('#pangkat').val('');
              $('#alamat').val('');
              $('#bendahara_nip').val('');
              $('#bendahara_skpd').val('');
              $('#pagu').val('');
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
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/skpd/delete",
                      type: "POST",
                      data: {'_token'         : token,
                            'id_skpd'         : id},
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
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/skpd/getData/"+id,
      type: "GET",
      success: function(msg){
        $('#kode_skpd').val(msg[0]['SKPD_KODE']);
        $('#nama_skpd').val(msg[0]['SKPD_NAMA']);
        $('#kepala_nip').val(msg[0]['SKPD_KEPALA_NIP']);
        $('#kepala_skpd').val(msg[0]['SKPD_KEPALA']);
        $('#bendahara_nip').val(msg[0]['SKPD_BENDAHARA_NIP']);
        $('#bendahara_skpd').val(msg[0]['SKPD_BENDAHARA']);
        $('#pangkat').val(msg[0]['SKPD_JABATAN']);
        $('#alamat').val(msg[0]['SKPD_ALAMAT']);
        $('#id_skpd').val(msg[0]['SKPD_ID']);
        $('#pagu').val(msg[0]['SKPD_PAGU']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-btl').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        });
      }
    });    
  }

  $('a.tutup-form').click(function(){
        $('#judul-form').text('Tambah SKPD');        
        $('#kode_skpd').val('');
        $('#nama_skpd').val('');
        $('#kepala_nip').val('');
        $('#kepala_skpd').val('');
        $('#pangkat').val('');
        $('#bendahara_nip').val('');
        $('#bendahara_skpd').val('');
        $('#pagu').val('');
  }); 

  function kunciRincianSKPD(id){
    var token        = $('#token').val();  
    if($('#kuncirincian-'+id).is(':checked')){
      //kunci
      $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/kuncirincianskpd",
        type: "POST",
        data: {'_token'         : token,
                 'STATUS'         : 1,
                 'SKPD_ID'        : id},
        success: function(msg){
          $('.table').DataTable().ajax.reload();                          
          $.alert(msg);
        }
      });
    }else{
      //buka
      $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/kuncirincianskpd",
        type: "POST",
        data: {'_token'         : token,
                 'STATUS'         : 0,
                 'SKPD_ID'        : id},
        success: function(msg){
          $('.table').DataTable().ajax.reload();
          $.alert(msg);
        }
      });
    }
  }

  function kunciAll(){
    var token        = $('#token').val();  
    if($('#kunciall').is(':checked')){
      //kunci
      $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/kunciall",
        type: "POST",
        data: {'_token'         : token,
                 'STATUS'         : 1},
        success: function(msg){
          $('.table').DataTable().ajax.reload();                          
          $.alert(msg);
        }
      });
    }else{
      //buka
      $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/kunciall",
        type: "POST",
        data: {'_token'         : token,
                 'STATUS'         : 0},
        success: function(msg){
          $('.table').DataTable().ajax.reload();
          $.alert(msg);
        }
      });
    }
  }

</script>
@endsection


