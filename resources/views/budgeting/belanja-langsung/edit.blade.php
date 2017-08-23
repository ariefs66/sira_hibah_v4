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
                  <h5 class="inline font-semibold text-orange m-n ">Ubah Belanja Langsung</h5>
                </div>

                <div class="tab-content tab-content-alt-1 bg-white">
                  <div class="bg-white wrapper-lg input-jurnal">
                    <form action="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/detail/ubah/simpan/" method="POST" class="form-horizontal">
                      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">                            
                      <div class="input-wrapper">
                        <div class="form-group">
                          <label class="col-sm-1">Sub Unit</label>
                          <div class="col-sm-11">
                              <input type="hidden" name="bl_id" value="{{ $id }}" />
                            <select ui-jq="chosen" class="w-full program" name="sub_id" id="sub_id" required="">
                              @foreach($subunit as $su)
                              @if($bl->SUB_ID == $su->SUB_ID)
                              <option value="{{ $su->SUB_ID }}" selected="">{{ $su->SUB_KODE }} - {{ $su->SUB_NAMA }}</option>
                              @else
                              <option value="{{ $su->SUB_ID }}">{{ $su->SUB_KODE }} - {{ $su->SUB_NAMA }}</option>
                              @endif
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-1">Program</label>
                          <div class="col-sm-5">
                            <select ui-jq="chosen" class="w-full program" id="program" name="program" required="">
                              @foreach($program as $pg)
                              @if($bl->kegiatan->program->PROGRAM_ID == $pg->program->PROGRAM_ID)
                              <option value="{{ $pg->program->PROGRAM_ID }}" selected="">{{ $pg->program->PROGRAM_KODE }} - {{ $pg->program->PROGRAM_NAMA }}</option>
                              @else
                              <option value="{{ $pg->program->PROGRAM_ID }}">{{ $pg->program->PROGRAM_KODE }} - {{ $pg->program->PROGRAM_NAMA }}</option>
                              @endif
                              @endforeach
                            </select>
                          </div>
                          <label class="col-sm-1">Kegiatan</label>
                          <div class="col-sm-5">
                            <select ui-jq="chosen" class="w-full kegiatan" id="kegiatan" name="kegiatan" required="">
                              <option value="{{ $bl->KEGIATAN_ID }}">{{ $bl->kegiatan->KEGIATAN_KODE }} - {{ $bl->kegiatan->KEGIATAN_NAMA }}</option>
                            </select>
                          </div>
                        </div>
                        <hr class="m-t-xl">
                        <div class="form-group">
                          <h5 class="text-orange">Detail Kegiatan</h5>
                          <label class="col-sm-1">Jenis Kegiatan</label>
                          <div class="col-sm-5">
                            <select ui-jq="chosen" class="w-full jenis-kegiatan" id="jenis-kegiatan" name="jenis-kegiatan" required="">
                              @foreach($jenis as $jenis)
                              @if($bl->JENIS_ID == $jenis->JENIS_KEGIATAN_ID)
                              <option value="{{ $jenis->JENIS_KEGIATAN_ID }}" selected="">{{ $jenis->JENIS_KEGIATAN_NAMA }}</option>
                              @else
                              <option value="{{ $jenis->JENIS_KEGIATAN_ID }}">{{ $jenis->JENIS_KEGIATAN_NAMA }}</option>
                              @endif
                              @endforeach
                            </select>
                          </div>
                          <label class="col-sm-1">Sumber Dana</label>
                          <div class="col-sm-5">
                            <select ui-jq="chosen" class="w-full sumber-dana" id="sumber-dana" name="sumber-dana" required="">
                              @foreach($sumber as $sumber)
                              @if($bl->SUMBER_ID == $sumber->DANA_ID)
                              <option value="{{ $sumber->DANA_ID }}" selected="">{{ $sumber->DANA_NAMA }}</option>
                              @else
                              <option value="{{ $sumber->DANA_ID }}">{{ $sumber->DANA_NAMA }}</option>
                              @endif
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
                              @if($bl->PAGU_ID == $pagu->PAGU_ID)
                              <option value="{{ $pagu->PAGU_ID }}" selected="">{{ $pagu->PAGU_NAMA }}</option>
                              @else
                              <option value="{{ $pagu->PAGU_ID }}">{{ $pagu->PAGU_NAMA }}</option>
                              @endif
                              @endforeach
                            </select>
                          </div>
                          <label class="col-sm-1">Waktu Kegiatan</label>
                          <div class="col-sm-2">
                            <select ui-jq="chosen" class="w-full waktu-awal" id="waktu-awal" name="waktu-awal" required="">
                              <option value="">Dari Bulan</option>
                              @foreach($bulan as $bln)
                              @if($bl->BL_AWAL == $bln)
                              <option value="{{ $bln }}" selected="">{{ $bln }}</option>
                              @else
                              <option value="{{ $bln }}">{{ $bln }}</option>
                              @endif
                              @endforeach
                            </select>
                          </div>
                          <div class="col-sm-2">
                            <select ui-jq="chosen" class="w-full waktu-akhir" id="waktu-akhir" name="waktu-akhir" required="">
                              <option value="">Sampai Bulan</option>
                              @foreach($bulan as $bln)
                              @if($bl->BL_AKHIR == $bln)
                              <option value="{{ $bln }}" selected="">{{ $bln }}</option>
                              @else
                              <option value="{{ $bln }}">{{ $bln }}</option>
                              @endif
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-1">Sasaran Kegiatan</label>
                          <div class="col-sm-5">
                            <select ui-jq="chosen" class="w-full sasaran" id="sasaran" name="sasaran" required="">
                              <option value="">Pilih Sasaran</option>
                              @foreach($sasaran as $sasaran)
                              @if($bl->SASARAN_ID == $sasaran->SASARAN_ID)
                              <option value="{{ $sasaran->SASARAN_ID }}" selected="">{{ $sasaran->SASARAN_NAMA }}</option>
                              @else
                              <option value="{{ $sasaran->SASARAN_ID }}">{{ $sasaran->SASARAN_NAMA }}</option>
                              @endif
                              @endforeach
                            </select>
                          </div>
                          <label class="col-sm-1">Lokasi Kegiatan</label>
                          <div class="col-sm-5">
                            <select ui-jq="chosen" class="w-full lokasi" id="lokasi" name="lokasi" required="">
                              <option value="">Pilih Lokasi</option>
                              @foreach($lokasi as $lokasi)
                              @if($bl->LOKASI_ID == $lokasi->LOKASI_ID)
                              <option value="{{ $lokasi->LOKASI_ID }}" selected>{{ $lokasi->LOKASI_NAMA }}</option>
                              @else
                              <option value="{{ $lokasi->LOKASI_ID }}">{{ $lokasi->LOKASI_NAMA }}</option>
                              @endif
                              @endforeach
                            </select>
                          </div>
                        </div>                                  

                        <div class="form-group">
                          <label class="col-sm-1">Tagging Kegiatan</label>
                          <div class="col-sm-11 m-t-sm">
                            <select ui-jq="chosen" class="w-full tag" id="tag" name="tag[]" required="" multiple="" placeholder="Pilih Tag">
                              @foreach($tagused as $tu)
                              <option value="{{ $tu }}" selected="">{{ $tagname[$x++] }}</option>
                              @endforeach

                              @foreach($tag as $tg)
                              <option value="{{ $tg->TAG_ID }}">{{ $tg->TAG_NAMA }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>                                  
                        <hr class="m-t-xl">
                        <div id="out1">
                        <div class="form-group">
                          <label class="col-sm-1">Output</label>
                          <div class="col-sm-6">
                            <input type="text" class="form-control" name="keluaran[]" placeholder="Keluaran">
                          </div>
                          <div class="col-sm-2">
                            <input type="number" class="form-control" name="target-keluaran[]" placeholder="Target">
                          </div>
                          <div class="col-sm-2">
                            <select ui-jq="chosen" class="w-full" id="select-out-1" name="satuan-keluaran[]">
                              <option value="">Satuan</option>
                              @foreach($satuan as $st)
                              <option value="{{ $st->SATUAN_ID }}">{{ $st->SATUAN_NAMA }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-sm-1">
                            <a class="btn btn-success" id="tambah-output"><i class="fa fa-plus"></i></a>
                          </div>
                        </div>
                        </div>
                        <hr class="m-t-xl">
                        <div class="table-responsive dataTables_wrapper">
                          <table ui-jq="dataTable" ui-options="{
                                sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/capaian/{{ $bl->KEGIATAN_ID }}',
                                aoColumns: [
                                  { mData: 'INDIKATOR' },
                                  { mData: 'TOLAK_UKUR' },
                                  { mData: 'TARGET',class:'text-right' }]
                              }" class="table table-striped b-t b-b"  id="tabel-indikator">
                            <thead>
                              <tr>
                                <th width="20%">Indikator</th>
                                <th>Tolak Ukur</th>
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
                           <a class="btn input-xl m-t-md btn-danger pull-right"><i class="fa fa-close m-r-xs"></i>Batal</a>
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

  $('#tambah-output').on('click', function(){
    $('#out1').append('<div class="form-group"><label class="col-sm-1"></label><div class="col-sm-6"><input type="text" class="form-control" name="keluaran[]" placeholder="Keluaran" required=""></div><div class="col-sm-2"><input type="number" class="form-control" name="target-keluaran[]" placeholder="Target" required=""></div><div class="col-sm-2"><select class="w-full output-satuan" name="satuan-keluaran[]" required=""></select></div><div class="col-sm-1"></div></div>');

    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/satuan/getData",
      success : function (data) {
        $('.output-satuan').find('option').remove().end().append(data).trigger('chosen:updated');
        $('.output-satuan').chosen();
      }
    });
  })
</script>
@endsection