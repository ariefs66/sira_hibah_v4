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
                <li class="active"><i class="fa fa-angle-right"></i>Sub Unit</li>                                
              </ul>
          </div>
          <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                    <button class="pull-right btn m-t-n-sm btn-success open-form-btl"><i class="m-r-xs fa fa-plus"></i> Tambah Sub Unit</button>
                    <h5 class="inline font-semibold text-orange m-n ">Sub Unit Tahun {{ $tahun }}</h5>
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
                                    sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/pengaturan/subunit/getData',
                                    aoColumns: [
                                    { mData: 'no',class:'text-center' },
                                    { mData: 'SUB_TAHUN' },
                                    { mData: 'SUB_KODE' },
                                    { mData: 'SUB_NAMA' },
                                    { mData: 'SKPD_KODE' },
                                    { mData: 'SKPD_NAMA' },
                                    { mData: 'aksi' }
                                    ]}" class="table table-striped b-t b-b">
                                    <thead>
                                      <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Kode Sub</th>
                                        <th>Nama Sub</th>
                                        <th>Kode SKPD</th>
                                        <th>Nama SKPD</th>
                                        <th width="15%">Aksi<br>
                                        @if(substr(Auth::user()->mod,1,1) == 1 or Auth::user()->level == 8)                        
                                          <label class="i-switch bg-danger m-t-xs m-r buka-giat"><input type="checkbox" onchange="return kunciAll()" id="kunciall"><i></i></label>
                                        @endif 
                                      </th>
                                      </tr>
                                      <tr>
                                        <th colspan="7" class="th_search">
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
        <h5 id="judul-form">Tambah Sub Unit</h5>
          <div class="form-group">
            <label for="kode_urusan" class="col-md-3">Kode Sub unit</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Kode Sub Unit" name="kode_sub" id="kode_sub" value="">          
              <input type="hidden" class="form-control" value="{{ csrf_token() }}" name="_token" id="token">          
              <input type="hidden" class="form-control" name="id_sub" id="id_sub">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Nama Sub unit</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Nama Sub Unit" name="nama_sub" id="nama_sub" value="">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Tahun Sub Unit</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Tahun" name="tahun" id="" value="{{$tahun}}" readonly="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">SKPD</label>
            <div class="col-sm-9">
              <select ui-jq="chosen" class="w-full" id="skpd" name="skpd" multiple="">
                @foreach($skpd as $s)
                  <option value="{{ $s->SKPD_ID }}">{{ $s->SKPD_KODE }} - {{ $s->SKPD_NAMA }}</option>
                @endforeach
              </select>
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
    var id_sub       = $('#id_sub').val();
    var kode_sub       = $('#kode_sub').val();
    var nama_sub       = $('#nama_sub').val();
    var skpd            = $('#skpd').val();
    var token           = $('#token').val();

    /*alert(pagu);
    alert(kode_skpd);*/
    if(kode_sub == "" || nama_sub == "" || skpd == "" ){
      $.alert('Form harap diisi!');
    }else{
      if(id_sub == '') uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/subunit/add/submit";
      else uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/subunit/edit/submit";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'         : token,
              'kode_sub'        : kode_sub, 
              'nama_sub'        : nama_sub, 
              'skpd'            : skpd, 
              'tahun'           : '{{$tahun}}', 
              'id_sub'          : id_sub},
        success: function(msg){
            if(msg == 1){
              $('#judul-form').text('Tambah Sub Unit');        
              $('#kode_sub').val('');
              $('#nama_sub').val('');
              $('#skpd').val('');
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
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/subunit/delete",
                      type: "POST",
                      data: {'_token'         : token,
                            'id_sub'         : id},
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
      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/subunit/getData/"+id,
      type: "GET",
      success: function(msg){
        $('#kode_sub').val(msg[0]['SUB_KODE']);
        $('#nama_sub').val(msg[0]['SUB_NAMA']);
        $('.overlay').fadeIn('fast',function(){
          $('.input-btl').animate({'right':'0'},"linear");  
          $("html, body").animate({ scrollTop: 0 }, "slow");
        });
      }
    });    
  }

  $('a.tutup-form').click(function(){
        $('#judul-form').text('Tambah Sub Unit');        
        $('#kode_sub').val('');
        $('#nama_sub').val('');
        $('#skpd').val('');
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


