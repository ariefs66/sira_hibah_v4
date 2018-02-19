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
          <li><i class="fa fa-angle-right"></i>Belanja Tidak Langsung</li>                                
          <li class="active"><i class="fa fa-angle-right"></i>Anggaran Kas Bulanan </li>                           
        </ul>
      </div>

      <div class="wrapper-lg">
        
      </div>


      <div class="wrapper-lg" style="margin-top: -75px;">
        <div class="row">
          <div class="col-md-12">
            <div class="panel bg-white">
              <div class="wrapper-lg">
                <h5 class="inline font-semibold text-orange m-n ">AKB BTL | SKPD : {{$skpd->SKPD_NAMA}} </h5>
                @if($thp == 0)
                <h5 class="pull-right font-semibold text-info m-t-n-xs"><i class="fa fa-info-circle"></i> Tahapan masih ditutup!</h5>
                @elseif(Auth::user()->active == 0)
                <h5 class="pull-right font-semibold text-info m-t-n-xs"><i class="fa fa-info-circle"></i> User Tidak Aktif!</h5>
                @else
                <a class="pull-right btn m-t-n-sm btn-success" href="{{url('/')}}/main/{{$tahun}}/{{$status}}/lampiran/akb/btl/{{$skpd->SKPD_ID}}" target="_blank">Print AKB</a>
                @endif
                
                <div class="col-sm-1 pull-right m-t-n-sm">
                  <select class="form-control selectrincian" id="selectrincian">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                  </select>
                </div>  
              </div>

            

            <div class="tab-content tab-content-alt-1 bg-white" id="tab-detail">
            <!-- Tab1 -->
              <div role="tabpanel" class="active tab-pane" id="tab-1">  
                <div class="table-responsive dataTables_wrapper">
                 <table ui-jq="dataTable" ui-options="{
                 sAjaxSource: '{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/data/akb/{{ $skpd->SKPD_ID }}',
                 aoColumns: [
                 { mData: 'NO',class:'text-center' },
                 { mData: 'REKENING' },
                 { mData: 'TOTAL' },
                 { mData: 'JANUARI' },
                 { mData: 'FEBRUARI' },
                 { mData: 'MARET' },
                 { mData: 'TRIWULAN1' },
                 { mData: 'APRIL' },
                 { mData: 'MEI' },
                 { mData: 'JUNI' },                
                 { mData: 'TRIWULAN2' },
                 { mData: 'JULI' },
                 { mData: 'AGUSTUS' },
                 { mData: 'SEPTEMBER' },
                 { mData: 'TRIWULAN3' },
                 { mData: 'OKTOBER' },
                 { mData: 'NOVEMBER' },
                 { mData: 'DESEMBER' },
                 { mData: 'TRIWULAN4' }]
               }" class="table table-striped b-t b-b tabel-detail ">
               <thead>
                <tr>
                  <th style="width: 1%">#</th>
                  <th>Rekening</th>
                  <th>Jumlah</th>
                  <th style="width: 5%">Januari</th>
                  <th style="width: 5%">Februari</th>
                  <th style="width: 5%">Maret</th>
                  <th style="width: 5%">Triwulan 1</th>
                  <th style="width: 5%">April</th>
                  <th style="width: 5%">Mei</th>
                  <th style="width: 5%">Juni</th>
                  <th style="width: 5%">Triwulan 2</th>
                  <th style="width: 5%">Juli</th>
                  <th style="width: 5%">Agustus</th>
                  <th style="width: 5%">September</th>
                  <th style="width: 5%">Triwulan 3</th>
                  <th style="width: 5%">Oktober</th>
                  <th style="width: 5%">November</th>
                  <th style="width: 5%">Desember</th>
                  <th style="width: 5%">Triwulan 4</th>
                </tr>
                <tr>
                  <th colspan="19" class="th_search">
                    <i class="icon-bdg_search"></i>
                    <input type="search" class="cari-detail form-control b-none w-full" placeholder="Cari" aria-controls="DataTables_Table_0">
                  </th>
                </tr>
              </thead>
              <tbody>
              </tbody>                        
            </table>
          </div>
          
          
       </div>
       <!-- Tab2 -->
      
       <!-- Tab3 -->
       
       <!-- Tab4 -->
       
       <!-- Tab5 -->

     </div>
   </div>
 </div>
</div>
</div>
</div>
</div>
</div>


<div class="overlay"></div>


<div class="bg-white wrapper-lg input-sidebar input-btl">
<a href="#" class="tutup-form"><i class="icon-bdg_cross"></i></a>
    <form id="form-urusan" class="form-horizontal">
      <div class="input-wrapper">
        <h5 id="judul-form">Tambah Anggaran Kas Bulanan</h5>
          <div class="form-group">
            <label for="kode_urusan" class="col-md-3">Kode Rekening</label>          
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Kode Rekening" name="kode_rek" id="kode_rek" value="" readonly="">          
              <input type="hidden" class="form-control" value="{{ csrf_token() }}" name="_token" id="token">          
              <input type="hidden" class="form-control" name="id_btl" id="id_btl">          
              <input type="hidden" class="form-control" name="id_rek" id="id_rek">  
              <input type="hidden" class="form-control" name="total" id="total">        
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Nama Rekening</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Nama Rekening" name="nama_rek" id="nama_rek" value="" readonly="">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Total Nominal</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Total Nominal" name="total_view" id="total_view" value="" readonly="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Januari</label>
            <div class="col-sm-9">
               <input type="text" class="form-control" placeholder="Nominal" name="jan" id="jan" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value="" readonly="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Februari</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="feb" id="feb" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value="" readonly="">
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Maret</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="mar" id="mar" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value="" readonly="">            
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">April</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="apr" id="apr" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value="">        
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Mei</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="mei" id="mei" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value="">         
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Juni </label>
            <div class="col-sm-9">
             <input type="text" class="form-control" placeholder="Nominal" name="jun" id="jun" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Juli </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="jul" id="jul" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Agustus </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="agu" id="agu" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">September </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="sep" id="sep" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Oktober </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="okt" id="okt" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">November </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="nov" id="nov" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Desember </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Nominal" name="des" id="des" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  value=""> 
            </div> 
          </div>
         

          <hr class="m-t-xl">
         <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanAKB()"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
      </div>
    </form>
  </div>


</div>

</div>

@endsection

@section('plugin')


<script type="text/javascript">

  function simpanAKB(){
    var btl_id    = $('#id_btl').val();
    var rek_id    = $('#id_rek').val();
    var jan       = $('#jan').val();
    var feb       = $('#feb').val();
    var mar       = $('#mar').val();
    var apr       = $('#apr').val();
    var mei       = $('#mei').val();
    var jun       = $('#jun').val();
    var jul       = $('#jul').val();
    var agu       = $('#agu').val();
    var sep       = $('#sep').val();
    var okt       = $('#okt').val();
    var nov       = $('#nov').val();
    var des       = $('#des').val();
    var total     = $('#total').val();
    var token     = $('#token').val();

   total_akb = parseInt(jan)+parseInt(feb)+parseInt(mar)+parseInt(apr)+parseInt(mei)+parseInt(jun)+parseInt(jul)+parseInt(agu)+parseInt(sep)+parseInt(okt)+parseInt(nov)+parseInt(des);
   
   total = parseInt(total);
   selisih = total-total_akb;
    /*alert("Total Input : "+total_akb);    
    alert("Total : "+total);
    alert("Selisih : "+selisih);*/

    if(jan == "" || feb == "" || mar == "" || apr == "" || mei == "" || jun == "" || jul == "" || agu == "" || sep == "" || nov == "" || des == "" ){
      $.alert('Form harap diisi atau di nol kan!');
    }else{
      if(total != total_akb){
         uri = "";
        $.alert('total AKB yang di input tidak sesuai!');
        $.alert("Total Rek Belanja : <b>"+total+"</b>");
        $.alert("Total Input AKB : <b>"+total_akb+"</b>");
        $.alert("selisih : <b>"+selisih+"</b>");
      }
      else uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/akb/ubah";
     // uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/akb/ubah";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token' : token,
              //'akb_id'  : akb_id, 
              'btl_id'  : btl_id, 
              'rek_id'  : rek_id, 
              'jan'     : jan, 
              'feb'     : feb, 
              'mar'     : mar, 
              'apr'     : apr, 
              'mei'     : mei, 
              'jun'     : jun, 
              'jul'     : jul, 
              'agu'     : agu, 
              'sep'     : sep, 
              'okt'     : okt, 
              'nov'     : nov, 
              'des'     : des, 
              'total'   : total, 
              'tahun'   : '{{$tahun}}', 
            },
        success: function(msg){
            if(msg == 1){
              $('#judul-form').text('Tambah AKB');        
              $('#jan').val('');
              $('#feb').val('');
              $('#mar').val('');
              $('#apr').val('');
              $('#mei').val('');
              $('#jun').val('');
              $('#jul').val('');
              $('#agu').val('');
              $('#sep').val('');
              $('#okt').val('');
              $('#nov').val('');
              $('#des').val('');  
              $('#total').val('');                    
              $.alert({
                title:'Info',
                content: 'Data berhasil disimpan',
                autoClose: 'ok|1000',
                buttons: {
                    ok: function () {
                      $('.input-spp,.input-spp-langsung,.input-sidebar').animate({'right':'-1050px'},function(){
                        $('.overlay').fadeOut('fast');
                        $('.tabel-detail').DataTable().ajax.reload();
                      });                      
                    }
                }
              });
            }else{
              $.alert('Data gagal di input !');
            }
          }
        });
    }
  }

  function ubah(BTL_ID, REKENING_ID){
    $('#judul-form').text('Ubah Anggaran Kas Bulanan');
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/akb/detail/"+BTL_ID+"/"+REKENING_ID,
      success : function (data) {
        console.log(data);
        $('#id_btl').val(data['BTL_ID']);
        $('#nama_rek').val(data['REKENING_NAMA']);
        $('#id_rek').val(data['REKENING_ID']);
        $('#kode_rek').val(data['REKENING_KODE']);
        $('#total').val(data['TOTAL']);
        $('#total_view').val(data['TOTAL_VIEW']);
        $('#jan').val(data['AKB_JAN']);
        $('#feb').val(data['AKB_FEB']);
        $('#mar').val(data['AKB_MAR']);
        $('#apr').val(data['AKB_APR']);
        $('#mei').val(data['AKB_MEI']);
        $('#jun').val(data['AKB_JUN']);
        $('#jul').val(data['AKB_JUL']);
        $('#agu').val(data['AKB_AUG']);
        $('#sep').val(data['AKB_SEP']);
        $('#okt').val(data['AKB_OKT']);
        $('#nov').val(data['AKB_NOV']);
        $('#des').val(data['AKB_DES']);
      }
    });
    $('.overlay').fadeIn('fast',function(){
      $('.input-btl').animate({'right':'0'},"linear");  
      $("html, body").animate({ scrollTop: 0 }, "slow");
    });
  }

  $('a.tutup-form').click(function(){
        $('#judul-form').text('Tambah AKB');        
        $('#jan').val('');
        $('#feb').val('');
        $('#mar').val('');
        $('#apr').val('');
        $('#mei').val('');
        $('#jun').val('');
        $('#jul').val('');
        $('#agu').val('');
        $('#sep').val('');
        $('#okt').val('');
        $('#nov').val('');
        $('#des').val('');
        $('#total').val('');
  }); 


  function hapus(btl,rek){
    var token        = $('#token').val();    
    $.confirm({
      title: 'Hapus Data!',
      content: 'Yakin hapus data?',
      buttons: {
        Ya: {
          btnClass: 'btn-danger',
          action: function(){
            $.ajax({
              url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung/akb/hapus",
              type: "POST",
              data: {'_token'         : token,
              'BTL_ID'           : btl,
              'REKENING_ID'     : rek
              },
              success: function(msg){
                $('.tabel-detail').DataTable().ajax.reload();                          
                $.alert('Hapus Berhasil!');
                $('#rincian-total').text(msg);
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