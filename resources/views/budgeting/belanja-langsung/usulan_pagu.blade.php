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
            <li class="active"><i class="fa fa-angle-right"></i>Usulan Pagu</li>                                
          </ul>
        </div>

        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Usulan Pagu</h5>
                </div>
                <div class="tab-content tab-content-alt-1 bg-white">
                  <div role="tabpanel" class="active tab-pane" id="tab-1">
                    <div class="table-responsive dataTables_wrapper">
                     <table ui-jq="dataTable" class="table table-jurnal table-striped b-t b-b" id="table-usulan">
                   <thead>
                    <tr>
                      <th style="width: 1%">#</th>                                      
                      <th>Uraian</th>                                     
                      <th>Pagu Saat Ini</th>                                     
                      <th>Usulan Pagu</th>                                     
                      <th>Nomor Surat / Alasan</th>                                     
                      <th class="text text-center">Aksi</th>                                     
                    </tr>
                    <tr>
                      <th colspan="6" class="th_search">
                        <i class="icon-bdg_search"></i>
                        <input type="search" class="table-search form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($bl as $bl)
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>OPD     : <span class="text text-orange">{{ $bl->subunit->skpd->SKPD_NAMA }}</span><br>
                          SUB     : <span class="text text-info">{{ $bl->subunit->SUB_NAMA }}</span><br>
                          Program :<span class="text text-success">{{ $bl->kegiatan->program->PROGRAM_NAMA }}</span><br>
                          Kegiatan :<span class="text text-danger">{{ $bl->kegiatan->KEGIATAN_NAMA }}</span>
                      </td>
                      <td class="text text-right">{{ number_format($bl->BL_PAGU,0,'.',',') }}</td>
                      <td class="text text-right">{{ number_format($bl->BL_PAGU_USULAN,0,'.',',') }}</td>
                      <td><span class="text text-orange">{{ $bl->BL_PAGU_SURAT }}</span><br>{{ $bl->BL_PAGU_CATATAN }}</td>
                      <td class="text text-center">
                        <button class="btn btn-success" onclick="return terima('{{ $bl->BL_ID }}')"><i class="fa fa-check"></i></button>
                        <button class="btn btn-danger" onclick="return tolak('{{ $bl->BL_ID }}')"><i class="fa fa-close"></i></button>
                      </td>
                    </tr>
                    @endforeach
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
</div>

<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
@endsection

@section('plugin')
<script type="text/javascript">
  function terima(id){
    token   = $('#token').val();
    $.confirm({
        title: 'Usulan!',
        content: 'Terima Usulan?',
        buttons: {
            Ya: {
                btnClass: 'btn-success',
                action: function(){
                  $.ajax({
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/usulan-pagu/terima",
                      type: "POST",
                      data: {'_token'         : token,
                            'BL_ID'           : id},
                      success: function(msg){
                        $.alert(msg);
                        location.reload();
                      }
                  });
                }
            },
            Tidak: function () {
            }
        }
    });
  }

  function tolak(id){
    token   = $('#token').val();
    $.confirm({
        title: 'Usulan!',
        content: 'Tolak Usulan?',
        buttons: {
            Ya: {
                btnClass: 'btn-danger',
                action: function(){
                  $.ajax({
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/usulan-pagu/tolak",
                      type: "POST",
                      data: {'_token'         : token,
                            'BL_ID'           : id},
                      success: function(msg){
                        $.alert(msg);
                        location.reload();                        
                      }
                  });
                }
            },
            Tidak: function () {
            }
        }
    });
  }
</script>
@endsection