@extends('budgeting.layout')

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
          <li><a href= "{{ url('/') }}/main">Dashboard</a></li>
          <li><i class="fa fa-angle-right"></i>Belanja</li>                               
          <li><i class="fa fa-angle-right"></i>Belanja Langsung</li>                                
          <li class="active"><i class="fa fa-angle-right"></i>Arsip</li>                                
        </ul>
      </div>

      <div class="wrapper-lg">
        <div class="row">
          <div class="col-md-12">
            <div class="panel bg-white">
              <div class="wrapper-lg">
                <h5 class="inline font-semibold text-orange m-n">Arsip Belanja Langsung</h5>                
                <div class="col-sm-1 pull-right">
                  <select class="form-control selectrincian" id="selectrincian">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                  </select>
                </div>  
              </div>
            <div class="tab-content tab-content-alt-1 bg-white m-t-sm" id="tab-detail">
            <!-- Tab1 -->
              <div role="tabpanel" class="active tab-pane" id="tab-1">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                 sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rincian/arsip/{{ $bl }}',
                 aoColumns: [
                 { mData: 'NO',class:'text-center' },
                 { mData: 'REKENING' },
                 { mData: 'KOMPONEN' },
                 { mData: 'SUB' },
                 { mData: 'HARGA' },
                 { mData: 'PAJAK' },
                 { mData: 'TOTAL' }]
               }" class="table table-striped b-t b-b tabel-detail">
               <thead>
                <tr>
                  <th style="width: 1%">#</th>
                  <th>Rekening</th>
                  <th>Komponen</th>
                  <th>Paket Pekerjaan<br>Keterangan</th>
                  <th style="width: 10%">Harga / Koefisien</th>
                  <th style="width: 5%">Pajak</th>
                  <th style="width: 5%">Total</th>
                </tr>
                <tr>
                  <th colspan="7" class="th_search">
                    <i class="icon-bdg_search"></i>
                    <input type="search" class="cari-detail form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                  </th>
                </tr>
              </thead>
              <tbody>
              </tbody>                        
            </table>
          </div>
          @if(Auth::user()->level == 8)
          <a class="btn input-xl m-t-md btn-success pull-right m-r-md" onclick="return hapuscb()"><i class="fa fa-refresh m-r-xs"></i>Kembalikan</a>
          @endif
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
  $(document).ready(function(){
    $("#app").trigger('click');
  });
</script>

<script type="text/javascript">
  function hapuscb(){
    var token        = $('#token').val();    
    $.confirm({
      title: 'Kembalikan Data!',
      content: 'Yakin kembalikan data?',
      buttons: {
        Ya: {
          btnClass: 'btn-success',
          action: function(){
            var val = [];
            $(':checkbox.cb:checked').each(function(i){
              val[i] = $(this).val();
            });
            if(val.length == 0){
              $.alert('Pilih Komponen!');
            }else{
              $.ajax({
                url: "{{ url('/') }}/main/{{$tahun}}/{{$status}}/belanja-langsung/rincian/back-cb",
                type: "POST",
                data: {'_token'            : token,
                      'BL_ID'              : '{{$bl}}',
                      'RINCIAN_ID'         : val},
                success: function(msg){
                  $('.tabel-detail').DataTable().ajax.reload();                          
                  $.alert('Kembalikan Berhasil!');
                  $('#rincian-total').text(msg);
                }
              });
            }
          }
        },
        Tidak: function () {
        }
      }
    }); 
  }
</script>
@endsection