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
            <li class="active"><i class="fa fa-angle-right"></i>Tambah Belanja Langsung</li>                                
          </ul>
        </div>

        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">

              <div class="panel bg-white">

                <div class="panel-heading wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Tambah Belanja Langsung</h5>
                </div>

                <div class="tab-content tab-content-alt-1 bg-white">
                  <div class="bg-white wrapper-lg input-jurnal">
                    <form action="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/detail/simpan" method="POST" class="form-horizontal">
                      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">                            
                      <div class="input-wrapper">
                        <div class="form-group">
                          <label class="col-sm-1">Sub Unit</label>
                          <div class="col-sm-11">
                            <select ui-jq="chosen" class="w-full program" name="sub_id" id="sub_id" required="">
                              <option value="">Pilih Sub Unit</option>
                              @foreach($subunit as $su)
                              <option value="{{ $su->SUB_ID }}">{{ $su->SUB_KODE }} - {{ $su->SUB_NAMA }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-1">Program</label>
                          <div class="col-sm-5">
                            <select ui-jq="chosen" class="w-full program" id="program" name="program" required="">
                              <option value="">Pilih Program</option>
                              @foreach($program as $pg)
                              <option value="{{ $pg->program->PROGRAM_ID }}">{{ $pg->program->PROGRAM_KODE }} - {{ $pg->program->PROGRAM_NAMA }}</option>
                              @endforeach
                            </select>
                          </div>
                          <label class="col-sm-1">Kegiatan</label>
                          <div class="col-sm-5">
                            <select ui-jq="chosen" class="w-full kegiatan" id="kegiatan" name="kegiatan" required="">
                              <option value="">Pilih Kegiatan</option>
                            </select>
                          </div>
                        </div>
                        <hr class="m-t-xl">
                        <div class="form-group">
                          <h5 class="text-orange">Detail Kegiatan</h5>
                          <label class="col-sm-1">Jenis Kegiatan</label>
                          <div class="col-sm-5">
                            <select ui-jq="chosen" class="w-full jenis-kegiatan" id="jenis-kegiatan" name="jenis-kegiatan" required="">
                              <option value="">Pilih Jenis</option>
                              @foreach($jenis as $jenis)
                              <option value="{{ $jenis->JENIS_KEGIATAN_ID }}">{{ $jenis->JENIS_KEGIATAN_NAMA }}</option>
                              @endforeach
                            </select>
                          </div>
                          <label class="col-sm-1">Sumber Dana</label>
                          <div class="col-sm-5">
                            <select ui-jq="chosen" class="w-full sumber-dana" id="sumber-dana" name="sumber-dana" required="">
                              <option value="">Pilih Sumber Dana</option>
                              @foreach($sumber as $sumber)
                              <option value="{{ $sumber->DANA_ID }}">{{ $sumber->DANA_NAMA }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-1">Kategori Pagu</label>
                          <div class="col-sm-5">
                            <select ui-jq="chosen" class="w-full kategori-pagu" id="kategori-pagu" name="kategori-pagu" required="">
                              <option value="">Pilih Pagu</option>
                              @foreach($pagu as $pagu)
                              <option value="{{ $pagu->PAGU_ID }}">{{ $pagu->PAGU_NAMA }}</option>
                              @endforeach
                            </select>
                          </div>
                          <label class="col-sm-1">Waktu Kegiatan</label>
                          <div class="col-sm-2">
                            <select ui-jq="chosen" class="w-full waktu-awal" id="waktu-awal" name="waktu-awal" required="">
                              <option value="">Dari Bulan</option>
                              @foreach($bulan as $bl)
                              <option value="{{ $bl }}">{{ $bl }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-sm-2">
                            <select ui-jq="chosen" class="w-full waktu-akhir" id="waktu-akhir" name="waktu-akhir" required="">
                              <option value="">Sampai Bulan</option>
                              @foreach($bulan as $bl)
                              <option value="{{ $bl }}">{{ $bl }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-1">Kelompok Sasaran</label>
                          <div class="col-sm-5">
                            <select ui-jq="chosen" class="w-full sasaran" id="sasaran" name="sasaran" required="">
                              <option value="">Pilih Sasaran</option>
                              @foreach($sasaran as $sasaran)
                              <option value="{{ $sasaran->SASARAN_ID }}">{{ $sasaran->SASARAN_NAMA }}</option>
                              @endforeach
                            </select>
                          </div>
                          <label class="col-sm-1">Lokasi Kegiatan</label>
                          <div class="col-sm-5">
                            <select ui-jq="chosen" class="w-full lokasi" id="lokasi" name="lokasi" required="">
                              <option value="">Pilih Lokasi</option>
                              @foreach($lokasi as $lokasi)
                              <option value="{{ $lokasi->LOKASI_ID }}">{{ $lokasi->LOKASI_NAMA }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>                                  

                        <div class="form-group">
                          <label class="col-sm-1">Tagging Kegiatan</label>
                          <div class="col-sm-11 m-t-sm">
                            <select ui-jq="chosen" class="w-full tag" id="tag" name="tag[]" required="" multiple="" placeholder="Pilih Tag">
                              @foreach($tag as $tg)
                              <option value="{{ $tg->TAG_ID }}">{{ $tg->TAG_NAMA }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>                                  

                        <hr class="m-t-xl">
                        <div class="table-responsive dataTables_wrapper">
                          <table ui-jq="dataTable" ui-options="" class="table table-striped b-t b-b"  id="tabel-indikator">
                            <thead>
                              <tr>
                                <th width="20%">Indikator</th>
                                <th>Tolok Ukur</th>
                                <th width="15%">Target</th>
                              </tr>
                              <tr>
                                <th colspan="3" class="th_search">
                                  <i class="icon-bdg_search"></i>
                                  <input type="search" class="cari-detail form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>                        
                          </table>
                        </div>
                        <hr class="m-t-xl">
                        <div class="form-group">
                          <div class="col-md-12">
                           <a class="btn input-xl m-t-md btn-danger pull-right" href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung"><i class="fa fa-close m-r-xs"></i>Batal</a>
                           <button class="btn input-xl m-t-md btn-success pull-right m-r-md" type="submit"><i class="fa fa-check m-r-xs"></i>Simpan</button>
                         </div>
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
</script>
@endsection