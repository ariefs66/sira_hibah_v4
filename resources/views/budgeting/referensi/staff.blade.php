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
                <li><a><i class="fa fa-angle-right"></i>Pengaturan</a></li>
                <li class="active"><i class="fa fa-angle-right"></i>Staff</li>                                
              </ul>
          </div>
          <div class="wrapper-lg">
            <div class="row">
              <div class="col-md-12">
                <div class="panel bg-white">
                  <div class="wrapper-lg">
                    <div class="dropdown dropdown-blend pull-right m-t-n-sm">
                    <button class="btn btn-success dropdown-toggle " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                      <li><a class="open-tahapan">Tambah Staff</a></li>
                      <li><a class="open-staff">Set Admin EHarga</a></li>
                    </ul>
                  </div>                  
                    <h5 class="inline font-semibold text-orange m-n ">Staff Tahun {{ $tahun }}</h5>
                  </div>           
                  <div class="tab-content tab-content-alt-1 bg-white">
                        <div role="tabpanel" class="active tab-pane" id="tab-1">  
                            <div class="table-responsive dataTables_wrapper">
                             <table ui-jq="dataTable" ui-options="{
                                      sAjaxSource: '{{ url('/') }}/main/{tahun}/{status}/pengaturan/staff/getData',
                                      aoColumns: [
                                      { mData: 'USER_ID',class:'hide'}, 
                                      { mData: 'NO'},
                                      { mData: 'skpd'},
                                      { mData: 'USER_NIP'},
                                      { mData: 'USER_NAMA'},
                                      { mData: 'aksi'}
                                    ]}" class="table table-striped b-t b-b" id="table-tahapan">
                                    <thead>
                                      <tr>
                                        <th class="hide"></th>
                                        <th width="1%">No</th>
                                        <th width="1%">OPD</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th width="20%">Aksi</th>
                                      </tr>
                                      <tr>
                                        <th class="hide"></th>
                                        <th colspan="5" class="th_search">
                                            <i class="icon-bdg_search"></i>
                                            <input type="search" class="table-search form-control b-none w-full" placeholder="Cari Tahapan" aria-controls="DataTables_Table_0">
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
<div class="bg-white wrapper-lg input-sidebar input-tahapan">
<a href="#" class="tutup-form"><i class="icon-bdg_cross"></i></a>
    <form id="form-urusan" class="form-horizontal">
      <div class="input-wrapper">
        <h5 id="judul-form">Tambah Staff</h5>
          <div class="form-group">
            <label for="kode_urusan" class="col-md-3">NIP</label>          
            <div class="col-sm-9">
              <input type="number" class="form-control" placeholder="Masukan NIP Staff" name="nip" id="nip" value="">          
              <input type="hidden" class="form-control" value="{{ csrf_token() }}" name="_token" id="token">          
              <input type="hidden" class="form-control" name="id_user" id="id_user">          
            </div> 
          </div>

          <div class="form-group">
            <label for="nama_urusan" class="col-md-3">Nama Staff</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" placeholder="Masukan Nama Staff" name="nama_staff" id="nama_staff" value="">       
            </div> 
          </div>

          <hr class="m-t-xl">
          <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanStaff()"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
      </div>
    </form>
  </div>
 </div>

 <div class="overlay"></div>
<div class="bg-white wrapper-lg input-sidebar set-staff">
<a href="#" class="tutup-form"><i class="icon-bdg_cross"></i></a>
    <form id="form-urusan" class="form-horizontal">
      <div class="input-wrapper">
        <h5 id="judul-form">Set Staff Eharga</h5>
          <div class="form-group">
            <label class="col-sm-3">Staff</label>
            <div class="col-sm-9">
              <select ui-jq="chosen" class="w-full" id="staff">
                @if($userharga == NULL)
                <option value="">Pilih Staff</option>
                @else
                <option value="{{$userharga->id}}">{{ $userharga->email }} - {{ $userharga->name }}</option>
                @endif
              </select>
            </div>
          </div>
          <hr class="m-t-xl">
          <a class="btn input-xl m-t-md btn-success pull-right" onclick="return simpanHarga()"><i class="fa fa-plus m-r-xs "></i>Simpan</a>
      </div>
    </form>
  </div>
 </div>
@endsection

@section('plugin')
<script type="text/javascript">
    $('.open-staff').on('click',function(){
      @if($userharga == NULL)      
      $('#staff').find('option').remove().end().append('<option>Pilih Staff</option>');
      @else 
      $('#staff').find('option').remove().end().append('<option value="{{ $userharga->id }}">{{ $userharga->email }} - {{ $userharga->name }}</option>');
      @endif
      $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/staff/getStaff",
      success : function (data) {
          $('#staff').append(data).trigger('chosen:updated');      
        }
      });
      $('.overlay').fadeIn('fast',function(){
        $('.set-staff').animate({'right':'0'},"linear");  
        $("html, body").animate({ scrollTop: 0 }, "slow");
      }); 
    }); 

  function simpanStaff(){
    var nip         = $('#nip').val();
    var nama        = $('#nama_staff').val();
    var id          = $('#id_user').val();
    var token       = $('#token').val();
    if(nip == "" || nama == ""){
      $.alert('Form harap diisi!');
    }else{
      if(id == '') uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/staff/submitAdd";
      else uri = "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/staff/submitEdit";
      $.ajax({
        url: uri,
        type: "POST",
        data: {'_token'       : token,
              'NIP'           : nip, 
              'NAMA'          : nama, 
              'ID'            : id},
        success: function(msg){
              $.alert(msg);
              $('#nama_staff').val('');
              $('#nip').val('');
              $('#id_user').val('');
              $('.table').DataTable().ajax.reload();
              $('.input-tahapan').animate({'right':'-1050px'},function(){
                $('.overlay').fadeOut('fast');
              });
          }
        });
    }
  }

  function simpanHarga(){
    var staff       = $('#staff').val();
    var token       = $('#token').val();
    if(staff == ""){
      $.alert('Form harap diisi!');
    }else{
      $.ajax({
        url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/staff/submitEharga",
        type: "POST",
        data: {'_token'       : token, 
              'ID'            : staff},
        success: function(msg){
            $.alert(msg);
            $('.table').DataTable().ajax.reload();
            $('.set-staff').animate({'right':'-1050px'},function(){
              $('.overlay').fadeOut('fast');
            });
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
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/staff/hapus",
                      type: "POST",
                      data: {'_token'         : token,
                            'id'              : id},
                      success: function(msg){
                          $('.table').DataTable().ajax.reload();
                          $.alert(msg);
                        }
                  });
                }
            },
            Tidak: function () {
            }
        }
    });
  }
  function reset(id){
    var token        = $('#token').val();    
    $.confirm({
        title: 'Reset Password!',
        content: 'Yakin reset password?',
        buttons: {
            Ya: {
                btnClass: 'btn-danger',
                action: function(){
                  $.ajax({
                      url: "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/staff/reset",
                      type: "POST",
                      data: {'_token'         : token,
                            'id'              : id},
                      success: function(msg){
                          $('.table').DataTable().ajax.reload();
                          $.alert(msg);
                        }
                  });
                }
            },
            Tidak: function () {
            }
        }
    });
  }

  function ubah(id){
    $('#id_user').val(id);
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/staff/getStaff/"+id,
      success : function (data) {
          $('#nama_staff').val(data['name']);
          $('#nip').val(data['email']);
      }
    });    
    $('.overlay').fadeIn('fast',function(){
        $('.input-tahapan').animate({'right':'0'},"linear");  
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });    
  }
</script>
@endsection


