<!DOCTYPE html>
<html lang="en" class="">
<head>
  <meta charset="utf-8" />
  <link rel="icon" href="{{ url('/') }}/assets/img/logo-small.png">
  <title>.bdg Planning & Budgeting</title>
  <meta name="description" content="Bandung SIRA" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/assets/animate.css/animate.css" type="text/css" />
  <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/jquery/bootstrap/dist/css/bootstrap.css" type="text/css" /> 
  <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/jquery/jquery.confirm/css/jquery-confirm.css" type="text/css" />
  <link rel="stylesheet" href="{{ url('/') }}/assets/css/font.css" type="text/css" />
  <link rel="stylesheet" href="{{ url('/') }}/assets/css/style.css" type="text/css" />
  <style type="text/css">
    /*----------------------- Preloader -----------------------*/
    body.preloader-site {
        overflow: hidden;
    }

    .preloader-wrapper {
        height: 100%;
        width: 100%;
        background: #FFF;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 9999999;
    }

    .preloader-wrapper .preloader {
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        width: 120px;
    }
  </style>
</head>
<body class="bg-light" id="budgeting">
<div class="preloader-wrapper" style="display: none;">
  <div class="preloader">
    <img src="{{ URL::asset('assets/img/load.gif') }}" alt="NILA">
  </div>
</div>
<div class="app app-header-fixed">
    <!-- header -->
  <header id="header" class="app-header navbar" role="menu">
      <!-- navbar header -->
      <div class="navbar-header bg-info">
        <button class="pull-right visible-xs dk" ui-toggle-class="show" target=".navbar-collapse">
          <i class="glyphicon glyphicon-cog"></i>
        </button>
        <button class="pull-right visible-xs" ui-toggle-class="off-screen" target=".app-aside" ui-scroll="app">
          <i class="glyphicon glyphicon-align-justify"></i>
        </button>
        <!-- brand -->
        <a href="#" class="navbar-brand text-lt">          
          <img src="{{ url('/') }}/assets/img/logo-budgeting-small@2x.png" srcset="{{ url('/') }}/assets/img/logo-budgeting-small@2x.png 2x" alt="." class="small-logo hide">
          <img src="{{ url('/') }}/assets/img/logo-budgeting-small@2x.png" srcset="{{ url('/') }}/assets/img/logo-budgeting-small@2x.png 2x" alt="." class="large-logo">
        </a>
        <!-- / brand -->
      </div>
      <!-- / navbar header -->

      <!-- navbar collapse -->
      <div class="collapse pos-rlt navbar-collapse bg-info">
        <!-- buttons -->
        <div class="nav navbar-nav hidden-xs">
                  
        </div>
        <!-- / buttons -->

        

        <!-- nabar right -->
        <ul class="nav navbar-nav navbar-right">
         <li class="dropdown">
            <!-- <a href="#" data-toggle="dropdown" class="dropdown-toggle">
              <i class="icon-bdg_alert text14"></i>
              <span class="visible-xs-inline">Notifikasi</span>
              <span class="badge badge-sm up bg-danger pull-right-xs">2</span>
            </a> -->
            <!-- dropdown -->            
            <div class="dropdown-menu w-xl animated fadeIn">
              <div class="panel bg-white">                
                <div class="list-group">
                  <a href="" class="list-group-item">
                    <span class="pull-left m-r-sm">
                      <i class="fa fa-circle text-warning"></i>
                    </span>
                    <span class="clear block m-l-md m-b-none">
                      Data SPP telah diinput, silahkan lakukan verifikasi
                    </span>
                  </a>
                  <a href="" class="list-group-item">
                    <span class="clear block m-b-none m-l-md">
                      Data SPP telah diinput, silahkan lakukan verifikasi
                    </span>
                  </a>
                  <a href="" class="list-group-item text-center">
                    <span class="clear block m-b-none text-primary" >
                      Lihat Semua
                    </span>
                  </a>
                </div>
                
              </div>
            </div>
            <!-- / dropdown -->
          </li>
        
         
          <li class="dropdown">
            <div class="separator"></div>
          </li>

           <li class="dropdown padder-md">
              <p class="font-semibold m-t-lg pull-left" style="color:black;">Tahun Anggaran : </p>
             <div class="m-t-md pull-right">
                  <select name="year" id="tahun-anggaran" class="form-control transparent-select" style="color:black;">
                  </select>
              </div>
           </li>

        </ul>
        <!-- / navbar right -->
      </div>
      <!-- / navbar collapse -->
  </header>
  <!-- / header -->

<!-- content -->
@yield('content')
</div>

 <!-- Popup Belanja Komponen -->
<script src="{{ url('/') }}/libs_dashboard/jquery/jquery/dist/jquery.js"></script>
<script src="{{ url('/') }}/libs_dashboard/jquery/bootstrap/dist/js/bootstrap.js"></script>
<script src="{{ url('/') }}/libs_dashboard/jquery/jquery.confirm/js/jquery-confirm.js"></script>
<script src="{{ url('/') }}/assets/js/ui-load.js"></script>
<script src="{{ url('/') }}/assets/js/ui-jp.config.js"></script>
<script src="{{ url('/') }}/assets/js/ui-jp.js"></script>
<script src="{{ url('/') }}/assets/js/ui-nav.js"></script>
<script src="{{ url('/') }}/assets/js/ui-toggle.js"></script>
<script src="{{ url('/') }}/assets/js/ui-client.js"></script>
<script src="{{ url('/') }}/assets/js/numeral.js"></script>
<script src="{{ url('/') }}/assets/js/custom.js"></script>
<script type="text/javascript">
  $('#tahun-anggaran').on('change',function(){
    document.location.href = "{{ url('/') }}/public/"+$('#tahun-anggaran').val();
  });

  $(function(){
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/public/gettahun/{{$tahun}}/{{$status}}",
      success : function(data){
        $('#tahun-anggaran').append(data);
      }
    }); 
  });
</script>
@yield('plugin')
</body>
</html>
