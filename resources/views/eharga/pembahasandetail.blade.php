@extends('eharga.layout')

@section('content')
<div id="content" class="app-content" role="main">
  <div class="hbox hbox-auto-xs hbox-auto-sm ng-scope">
    <div class="col">
      <div class="app-content-body ">

        <div class="bg-light lter">    
          <ul class="breadcrumb bg-white m-b-none">
            <li><a href="#" class="btn no-shadow" ui-toggle-class="app-aside-folded" target=".app" id="app">
              <i class="icon-bdg_expand1 text"></i>
              <i class="icon-bdg_expand2 text-active"></i>
            </a>   
          </li>
            <li><a href= "{{ url('/') }}/harga/{{$tahun}}">Eharga</a></li>
            <li>Pembahasan</a></li>
            <li class="active"><i class="fa fa-angle-right"></i>Detail</li>
        </ul>
      </div>

      <div class="wrapper-lg">
        <div class="row">
          <div class="col-md-12">
            <div class="panel bg-white">
              <div class="panel-heading wrapper-lg">
                <h5 class="inline font-semibold text-orange m-n ">Detail Usulan : {{ $usulan->USULAN_NAMA }}</h5>
              </div>
              <div class="tab-content tab-content-alt-1 bg-white">
                <div class="bg-white wrapper-lg">
                  <div class="input-wrapper">
                    @if(substr($usulan->katkom->KATEGORI_KODE,0,1) == 2)
                    <form action="{{url('/')}}/harga/{{$tahun}}/usulan/pembahasan/uploadHSPK" method="post" class="form-horizontal" enctype="multipart/form-data">
                    @else
                    <form action="{{url('/')}}/harga/{{$tahun}}/usulan/pembahasan/uploadASB" method="post" class="form-horizontal" enctype="multipart/form-data">
                    @endif
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                      <div class="form-group">
                        <label class="col-sm-2">Perangkat Daerah</label>
                        <label class="col-sm-9 font-semibold">: {{ $usulan->user->userbudget[0]->skpd->SKPD_KODE }} - {{ $usulan->user->userbudget[0]->skpd->SKPD_NAMA }}</label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Tipe</label>
                        <label class="col-sm-9 font-semibold">: 
                          @if($usulan->USULAN_TYPE == 1) Usulan Baru
                          @elseif($usulan->USULAN_TYPE == 2) Ubah Komponen
                          @elseif($usulan->USULAN_TYPE == 3) Rekening Baru
                          @endif
                        </label>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-2">Jenis Komponen</label>
                        <label class="col-sm-9 font-semibold">: 
                          @if(substr($usulan->katkom->KATEGORI_KODE,0,1) == 2) HSPK
                          @elseif(substr($usulan->katkom->KATEGORI_KODE,0,1) == 3) ASB
                          @endif
                        </label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Nama</label>
                        <label class="col-sm-9 font-semibold">: {{ $usulan->USULAN_NAMA }}</label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Kode</label>
                        <label class="col-sm-9 font-semibold">: {{ $usulan->KOMPONEN_KODE }}</label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Kategori</label>
                        <label class="col-sm-9 font-semibold">: {{ $usulan->katkom->KATEGORI_KODE }} - {{ $usulan->katkom->KATEGORI_NAMA }}</label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Spesifikasi</label>
                        <label class="col-sm-9 font-semibold">: {{ $usulan->USULAN_SPESIFIKASI }}</label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Harga Usulan / Satuan</label>
                        <label class="col-sm-9 font-semibold">: Rp. {{ number_format($usulan->USULAN_HARGA,2,',','.') }} / {{ $usulan->USULAN_SATUAN }}</label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Rekening</label>
                        <label class="col-sm-9 font-semibold">: {{ $usulan->rekening->REKENING_KODE }} - {{ $usulan->rekening->REKENING_NAMA }}</label>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Data Dukung</label>
                        <div class="col-sm-10">
                        <object data="{{ url('/') }}/uploads/komponen/{{$tahun}}/{{$usulan->datadukung->DD_PATH}}/dd.pdf" type="application/pdf" class="w-full" height="1000px">
                        </object>
                        </div> 
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Rincian</label>
                      </div>                      
                      <div class="form-group">
                        <div class="col-sm-12 table-responsive dataTables_wrapper">
                          <table class="table table-striped" ui-jq="dataTable">
                            <thead>
                              <tr>
                                <th>Kode</th>
                                <th>Komponen</th>
                                <th>Koefisien</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Total</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($member as $member)
                              <tr>
                                @if(substr($member->usulan->katkom->KATEGORI_KODE,0,1) == "2")
                                <td>{{ $member->KOMPONEN_KODE }}</td>
                                <td>{{ $member->KOMPONEN_URAIAN }}</td>
                                @elseif(substr($member->usulan->katkom->KATEGORI_KODE,0,1) == "3")
                                <td>{{ $member->KOMPONEN_KODE }}</td>
                                <td>{{ $member->KOMPONEN_URAIAN }}</td>                                
                                @endif
                                <td>{{ $member->MEMBER_KOEF }}</td>
                                <td>{{ $member->MEMBER_SATUAN }}</td>
                                <td>{{ $member->MEMBER_HARGA }}</td>
                                <td>{{ $member->MEMBER_JUMLAH }}</td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div> 
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2">Catatan</label>
                        <div class="col-sm-10">
                          @if($usulan->USULAN_POSISI != 9)
                          <label class="col-sm-9 font-semibold">: {{ $usulan->USULAN_CATATAN }}</label>
                          @else
                          <textarea class="form-control" rows="5" placeholder="Catatan" required="" name="catatan">{{ $usulan->USULAN_CATATAN }}</textarea> 
                          @endif                         
                        </div>
                      </div>                                            
                      @if($usulan->USULAN_POSISI == 9)
                      <div class="form-group">
                        <label class="col-sm-2">Upload <span class="text text-danger">(xls)</span></label>
                        <div class="col-sm-10"><input type="file" class="form-control m-t-sm" name="file" required="" accept="application/vnd.ms-excel"></div> 
                      </div>
                      <div class="form-group">
                        <input type="hidden" name="id" value="{{ $usulan->USULAN_ID }}">
                        <div class="col-sm-12">
                            <button class="btn btn-warning pull-right" value="1" name="btn-simpan"><i class="fa fa-check"></i> Selesai</button>
                            <button class="btn btn-success pull-right m-r-sm" value="0" name="btn-simpan"><i class="fa fa-save"></i> Simpan</button>
                        </div> 
                      </div>
                      @endif
                    </form>
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
</script>
@endsection