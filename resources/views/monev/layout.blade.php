<!DOCTYPE html>
<html lang="en" class="">
<head>
  <meta charset="utf-8" />
  <title>.bdg Asosiasi</title>
  <meta name="description" content="Bandung Eharga" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/assets/animate.css/animate.css" type="text/css" />
  <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  {{-- <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/assets/simple-line-icons/css/simple-line-icons.css" type="text/css" /> --}}
  <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/jquery/bootstrap/dist/css/bootstrap.css" type="text/css" /> 
  <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/jquery/jquery.confirm/css/jquery-confirm.css" type="text/css" />
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.11.0/css/bootstrap-tour.css">
  <link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>
  <link rel="stylesheet" href="{{ url('/') }}/assets/css/font.css" type="text/css" />
  <link rel="stylesheet" href="{{ url('/') }}/assets/css/style.css" type="text/css" />
  <style type="text/css">
    #pilih_tahun{
      z-index: 11111;
    }
    .tour-step-background{
      z-index: 1;
    }
  </style>
</head>
<body class="bg-light">
<div class="app app-header-fixed ">
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
          <img src="{{ url('/') }}/assets/img/logo-harga@2x-.png" srcset="{{ url('/') }}/assets/img/logo-harga@2x-.png 2x" alt="." class="small-logo hide">
          <img src="{{ url('/') }}/assets/img/logo-harga@2x-.png" srcset="{{ url('/') }}/assets/img/logo-harga@2x-.png 2x" alt="." class="large-logo">
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
              <p class="font-semibold m-t-lg text-white pull-left">Tahun Anggaran : </p>
             <div class="m-t-md pull-right" id="pilih_tahun">
                  <select name="year" id="tahun-anggaran" class="form-control transparent-select">
                    {{-- <option value="{{$tahun-1}}">{{$tahun-1}}</option> --}}
                    {{-- <option value="{{$tahun}}" selected="">{{$tahun}}</option> --}}
                    {{-- <option value="{{$tahun+1}}">{{$tahun+1}}</option> --}}
                  </select>
              </div>
           </li>

           <li class="dropdown">
            <div class="separator"></div>
          </li>

          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="bg-none profile-header dropdown-toggle clear" data-toggle="dropdown">
             
              <span class="hidden-sm hidden-md m-r-xl">{{ Auth::user()->name }}</span> <i class="text14 icon-bdg_setting3 pull-right"></i>
            </a>
            <!-- dropdown -->
            <ul class="dropdown-menu animated fadeIn w-ml">             
              <li>
                <a href>
                  <i class="icon-bdg_chat"></i>
                  <span>Help</span>
                </a>
              </li>
              <li>
                <a href>
                  <i class="icon-bdg_people"></i>
                  <span>Profile</span>
                </a>
              </li>
              <li>
                <a href>
                  <i class="icon-bdg_setting3"></i>
                  <span>Account Settings</span>
                </a>
              </li>
              <li class="divider"></li>
              @if(Auth::user()->level == 2)
              <li>
                <a href="{{ url('/') }}/musrenbang/{{$tahun}}">
                  <i class="icon-bdg_uikit"></i>
                  <span>Musrenbang</span>
                </a>
              </li>              
              <li>
                <a href="{{ url('/') }}/reses/{{$tahun}}">
                  <i class="icon-bdg_uikit"></i>
                  <span>Reses</span>
                </a>
              </li>
              @endif)              
              <li>
                <a href="{{ url('/') }}/main/{{$tahun}}/murni/">
                  <i class="icon-bdg_uikit"></i>
                  <span>PlanningBudgeting</span>
                </a>
              </li>
              <li>
                <a href="{{ url('/') }}/harga/{{$tahun}}">
                  <i class="icon-bdg_uikit"></i>
                  <span>EHarga</span>
                </a>
              </li>
             
              <li class="divider"></li>
              <li>
                <a href="/logout">
                  <i class="icon-bdg_arrow4"></i>
                  <span>Logout</span>
                </a>
              </li>
            </ul>
            <!-- / dropdown -->
          </li>
        </ul>
        <!-- / navbar right -->
      </div>
      <!-- / navbar collapse -->
  </header>
  <!-- / header -->


    <!-- aside -->
  <aside id="aside" class="app-aside hidden-xs bg-white">
      <div class="aside-wrap">
        <div class="navi-wrap">         
         <!-- nav -->
          <nav ui-nav class="navi white-navi clearfix">
            <ul class="nav">
              <li>
                <a href="{{ url('/') }}/asosiasi/{{ $tahun }}/" class="auto padding-l-r-lg">                  
                  <i class="icon-bdg_dashboard"></i>
                  <span class="font-semibold">Dashboard</span>
                </a>                
              </li>
              <!-- <li >
                <a href="#" class="auto padding-l-r-lg parent">                  
                  <i class="fa fa-laptop"></i>
                  <span class="pull-right text-heading">
                    <i class="text8 icon-bdg_arrow1 text"></i>
                    <i class="text8 icon-bdg_arrow2 text-active"></i>
                  </span>
                  <span class="font-semibold">Program</span>
                </a>                

                 <ul class="nav nav-sub dk">                  
                  <li>
                    <a href="{{ url('/') }}/asosiasi/{{ $tahun }}/program" class="padding-l-r-lg ">
                       <span>Program</span>
                    </a>
                  </li> 
                  <li>
                    <a href="{{ url('/') }}/asosiasi/{{ $tahun }}/program" class="padding-l-r-lg ">
                       <span >Target Outcome</span>
                    </a>
                  </li>                 
                  </ul>
              </li>  
              <li >
                <a href="#" class="auto padding-l-r-lg parent">                  
                  <i class="fa fa-laptop"></i>
                  <span class="pull-right text-heading">
                    <i class="text8 icon-bdg_arrow1 text"></i>
                    <i class="text8 icon-bdg_arrow2 text-active"></i>
                  </span>
                  <span class="font-semibold">Kegiatan</span>
                </a>                

                 <ul class="nav nav-sub dk">                  
                  <li>
                    <a href="{{ url('/') }}/asosiasi/{{ $tahun }}/kegiatan" class="padding-l-r-lg ">
                       <span>Kegiatan</span>
                    </a>
                  </li>  
                  <li>
                    <a href="{{ url('/') }}/asosiasi/{{ $tahun }}/kegiatan" class="padding-l-r-lg ">
                       <span >Target Output</span>
                    </a>
                  </li>                 
                  </ul>
              </li>   -->          
            </ul>
          </nav>
          <!-- nav -->
        </div>


      </div>
  </aside>
  <!-- / aside -->

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
    document.location.href = "{{ url('/') }}/harga/"+$('#tahun-anggaran').val()+"/usulan";
  });
  $(function(){
    @if($tahun == 2017) $('#o2017').prop('selected',true);
    @elseif($tahun == 2018) $('#o2018').prop('selected',true);
    @endif
  });
</script>
@yield('plugin')
</body>
</html>