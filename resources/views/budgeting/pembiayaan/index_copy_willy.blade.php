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
            <li class="active"><i class="fa fa-angle-right"></i>Pembiayaan</li>                                
          </ul>
        </div>
        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  @if(Auth::user()->level == 7)
                  <button class="pull-right btn m-t-n-sm btn-success open-form-pembiayaan"><i class="m-r-xs fa fa-plus"></i> Tambah Pembiayaan</button>
                  @endif
                  <h5 class="inline font-semibold text-orange m-n ">Pembiayaan</h5>
                  <div class="col-sm-1 pull-right m-t-n-sm">
                   <select ui-jq="chosen" class="form-control">
                     <option value="">Baris</option>
                     <option value="kegiatanA">10</option>
                     <option value="kegiatanA">25</option>
                     <option value="kegiatanA">50</option>
                     <option value="kegiatanA">100</option>
                   </select>
                 </div>                    
               </div>           
               <div class="tab-content tab-content-alt-1 bg-white">
                <div role="tabpanel" class="active tab-pane" id="tab-1">  
                  <div class="table-responsive dataTables_wrapper table-pembiayaan">
                   <table ui-jq="dataTable" ui-options="{
                   sAjaxSource: '{{ url('/') }}/main/{{$tahun}}/{{$status}}/pembiayaan/getData',
                   aoColumns: [
                   { mData: 'ID',class:'hide'},
                   { mData: 'KODE'},
                   { mData: 'NAMA'},
                   { mData: 'TOTAL'}
                   ]}" class="table table-btl table-striped b-t b-b table-pembiayaan" id="table-pembiayaan">
                   <thead>
                    <tr>
                      <th class="hide">ID</th>
                      <th>Kode Perangkat Daerah</th>
                      <th>Nama Perangkat Daerah</th>
                      <th>Anggaran</th>
                    </tr>
                    <tr>
                      <th class="hide"></th>
                      <th colspan="3" class="th_search">
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
<div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar input-pembiayaan">
  <a href="#" class="close"><i class="icon-bdg_cross"></i></a>
  <form class="form-horizontal">
    <div class="input-wrapper">
      <h5>Tambah Pembiayaan</h5>
      <div class="form-group">
        <label class="col-sm-3">Perangkat Daerah</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="skpd-pembiayaan" required="">
            <option value="">Silahkan Pilih SKPD</option>
            @foreach($skpd as $s)
            <option value="{{ $s->SKPD_ID }}">{{ $s->SKPD_NAMA }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3">Rekening</label>
        <div class="col-sm-9">
          <select ui-jq="chosen" class="w-full" id="rekening-pembiayaan" required="">
            <option value="">Silahkan Pilih Rekening</option>
            @foreach($rekening as $rek)
            <option value="{{ $rek->REKENING_ID }}">{{ $rek->REKENING_KODE }} - {{ $rek->REKENING_NAMA }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Peruntukan</label>          
        <div class="col-sm-9">
          <input type="text" class="form-control" placeholder="Masukan Keterangan" id="nama-pembiayaan" required="">          
        </div> 
      </div>

      <div class="form-group">
        <label for="no_spp" class="col-md-3">Anggaran</label>          
        <div class="col-sm-9">
          <input type="number" class="form-control" placeholder="Masukan Anggaran" id="total-pembiayaan" required="">          
        </div> 
      </div>

      <hr class="m-t-xl">
      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">      
      <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanPembiayaan()"><i class="fa fa-plus m-r-xs "></i>Tambah Pembiayaan</a>
    </div>
  </form>
</div>
</div>

<div id="table-detail-pembiayaan" class="hide bg-white">
  <table ui-jq="dataTable" class="table table-detail-pembiayaan-isi table-striped b-t b-b">
    <thead>
      <tr>
        <th>No</th>                                    
        <th>Rekening</th>                          
        <th>Rincian</th>                       
        <th>Anggaran</th>                                       
        <th>#</th>                                       
      </tr> 
      <tr>
        <th class="hide"></th>                    
        <th colspan="5" class="th_search">
          <i class="icon-bdg_search"></i>
          <input type="search" class="cari-detail form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
        </th>
      </tr>                                       
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
@endsection

@section('plugin')
<script type="text/javascript">
  function simpanPembiayaan(){
    var token           = $('#token').val();    
    var SKPD_ID         = $('#skpd-pembiayaan').val();
    var REKENING_ID     = $('#rekening-pembiayaan').val();
    var PEMBIAYAAN_NAMA = $('#nama-pembiayaan').val();
    var PEMBIAYAAN_TOTAL= $('#total-pembiayaan').val();
    if(SKPD_ID == "" || REKENING_ID == "" || PEMBIAYAAN_NAMA == "" || PEMBIAYAAN_TOTAL == ""){
      $.alert('Form harap diisi!');
    }else{
      $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pembiayaan/simpan",
        type: "POST",
        data: {'_token'         : token,
              'SKPD_ID'         : SKPD_ID, 
              'REKENING_ID'     : REKENING_ID,
              'PEMBIAYAAN_NAMA' : PEMBIAYAAN_NAMA, 
              'PEMBIAYAAN_TOTAL': PEMBIAYAAN_TOTAL},
        success: function(msg){
          $('#table-pembiayaan').DataTable().ajax.reload();
          $.alert(msg);
          $('.input-pembiayaan,.input-sidebar').animate({'right':'-1050px'},function(){
              $('.overlay').fadeOut('fast');
          });
        }
      });
    }
  } 
</script>
@endsection