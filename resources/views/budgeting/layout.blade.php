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
</head>
@if(Auth::user()->password == '$2y$10$oDOpQp8JIQkStQxRKP/uPuLOg8qYYBRWyblH95odj0.ngqlF93ysS')
<body class="bg-light" onload="return showaccountsetting()" id="budgeting">
@else
<body class="bg-light" id="budgeting">
@endif
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
                <a onclick="return showaccountsetting()">
                  <i class="icon-bdg_setting3"></i>
                  <span>Ubah Password</span>
                </a>
              </li>              
              <li class="divider"></li>
              @if(Auth::user()->level == 2)
              <li>
                <!-- <a href="{{ url('/') }}/musrenbang/{{$tahun}}"> -->
                <a onclick="$.alert('Ditutup!')">
                  <i class="icon-bdg_uikit"></i>
                  <span>Musrenbang</span>
                </a>
              </li>              
              <li>
                <!-- <a href="{{ url('/') }}/reses/{{$tahun}}"> -->
                <a onclick="$.alert('Ditutup!')">
                  <i class="icon-bdg_uikit"></i>
                  <span>Reses</span>
                </a>
              </li>
              @endif
              @if(Auth::user()->level == 2 or 
                  substr(Auth::user()->mod,3,1) == 1 or 
                  substr(Auth::user()->mod,4,1) == 1 or
                  substr(Auth::user()->mod,5,1) == 1 or
                  substr(Auth::user()->mod,6,1) == 1)              
              <li>
                <a href="{{ url('/') }}/main/{{$tahun}}/murni/">
                  <i class="icon-bdg_uikit"></i>
                  <span>PlanningBudgeting</span>
                </a>
              </li>
              <li>
                <a onclick="$.alert('Ditutup!')">
                  <i class="icon-bdg_uikit"></i>
                  <span>EHarga</span>
                </a>
              </li>
              @endif              
             @if(Auth::user()->level == 8)
              <li>
                <a href="{{ url('/') }}/asosiasi/{{$tahun}}" onclick="$.alert('Ditutup!')">
                  <i class="icon-bdg_uikit"></i>
                  <span>Asosiasi</span>
                </a>
              </li>
              @endif
              <li class="divider"></li>
              <li>
                <a href="/keluar/{{ Auth::user()->id }}">
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
                <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}" class="auto padding-l-r-lg">                  
                  <i class="icon-bdg_dashboard"></i>
                  <span class="font-semibold">Dashboard</span>
                </a>                
              </li>
              @if(Auth::user()->level == 8 or Auth::user()->level == 0 or Auth::user()->level == 0 or substr(Auth::user()->mod,1,1) == 1)
             <!--  <li>
                <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/ringkasan" class="auto padding-l-r-lg">                  
                  <i class="fa fa-quote-right"></i>
                  <span class="font-semibold">Ringkasan</span>
                </a>                
              </li> -->
              <li>
                <a href="#" class="auto padding-l-r-lg parent">                  
                  <i class="fa fa-quote-right"></i>
                  <span class="pull-right text-heading">
                    <i class="text8 icon-bdg_arrow1 text"></i>
                    <i class="text8 icon-bdg_arrow2 text-active"></i>
                  </span>
                  <span class="font-semibold">Ringkasan</span>
                </a>                
                 <ul class="nav nav-sub dk">
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/ringkasan/sebelum" class="padding-l-r-lg " target="_blank">
                       <span> Sebelum</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/ringkasan" class="padding-l-r-lg " target="_blank">
                       <span> Sesudah</span>
                    </a>
                  </li>
                </ul>
              </li>
              @endif
              <li >
                <a href="#" class="auto padding-l-r-lg parent">                  
                  <i class="icon-bdg_uikit"></i>
                  <span class="pull-right text-heading">
                    <i class="text8 icon-bdg_arrow1 text"></i>
                    <i class="text8 icon-bdg_arrow2 text-active"></i>
                  </span>
                  <span class="font-semibold">Belanja</span>
                </a>                

                 <ul class="nav nav-sub dk">  
                  @if( $tahun !='' &&  $status != '')               
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung" class="padding-l-r-lg ">                      
                       <span >Belanja Langsung</span>
                    </a>
                  </li>
                  @else
                  <li>
                    <a onclick="$.alert('Masih Ditutup untuk tahun : {{ $tahun }} dan status : {{ $status }}')" class="padding-l-r-lg ">                    
                       <span >Belanja Langsung</span>
                    </a>
                  </li>
                  @endif

                  <li>
                    @if((Auth::user()->level == 8 or Auth::user()->level == 9)
                        or substr(Auth::user()->mod,10,1) == 1 
                        or Auth::user()->level == 0 
                        or substr(Auth::user()->mod,1,1) == 1 
                        or substr(Auth::user()->mod,0,1) == 1)
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-tidak-langsung" class="padding-l-r-lg ">
                    @else
                    <a onclick="$.alert('Masih Ditutup')" class="padding-l-r-lg ">
                    @endif
                       <span >Belanja Tidak Langsung</span>
                    </a>
                  </li>
                  @if(Auth::user()->level == 2 or Auth::user()->level == 8)
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/arsip/belanja-langsung" class="padding-l-r-lg ">     
                       <span >Arsip</span>
                    </a>
                  </li>
                  @endif
                  @if(Auth::user()->level == 8)
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/usulan-pagu" class="padding-l-r-lg ">     
                       <span>Usulan Pagu</span>
                    </a>
                  </li>
                  @endif              
                  </ul>
              </li>
               <li>
                  @if((Auth::user()->level == 8 or Auth::user()->level == 9)
                      or substr(Auth::user()->mod,10,1) == 1 
                      or Auth::user()->level == 0 
                      or substr(Auth::user()->mod,1,1) == 1
                      or substr(Auth::user()->mod,0,1) == 1)
                  <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pendapatan" class="auto padding-l-r-lg">
                  @else                  
                  <a onclick="$.alert('Masih Ditutup')" class="padding-l-r-lg ">
                  @endif
                  <i class="icon-bdg_invoice"></i>
                  <span class="font-semibold">Pendapatan</span>
                </a>                
              </li>
               <li >
                <!-- <a href="{{ url('/') }}/main/{{ $tahun }}/murni/pembiayaan" class="auto padding-l-r-lg"> -->  
                  <a onclick="$.alert('Masih Ditutup')" class="padding-l-r-lg ">
                 <!--  <a<!--  class="padding-l-r-lg" href="">   -->  
                  <i class="icon-bdg_form"></i>
                  <span class="font-semibold">Pembiayaan</span>
                </a>                
              </li>
               <li >
                <!-- <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/usulan" class="auto padding-l-r-lg">   -->                
                  <a onclick="$.alert('Masih Ditutup')" class="padding-l-r-lg ">                  
                  <i class="icon-bdg_table"></i>
                  <span class="font-semibold">Musrenbang & Reses</span>
                </a>                
              </li>
              @if(Auth::user()->level == 2 or substr(Auth::user()->mod,3,1) == 1 or Auth::user()->level == 8 && Auth::user()->email != 'SKPKD')
              <li>
                <a href="{{ url('/') }}/harga/{{ $tahun }}/usulan" class="auto padding-l-r-lg" id="usulan-komponen">                  
                  <!-- <a onclick="$.alert('Masih Ditutup')" class="padding-l-r-lg ">                   -->
                  <i class="icon-bdg_table"></i>
                  <span class="font-semibold">Usulan Komponen</span>
                </a>                
              </li>
              @endif
              @if(Auth::user()->level != 0)
              <li >
                <a href="#" class="auto padding-l-r-lg parent">                  
                  <i class="icon-bdg_setting3"></i>
                  <span class="pull-right text-heading">
                    <i class="text8 icon-bdg_arrow1 text"></i>
                    <i class="text8 icon-bdg_arrow2 text-active"></i>
                  </span>
                  <span class="font-semibold">Pengaturan</span>
                </a>                

                 <ul class="nav nav-sub dk">
                 @if(Auth::user()->level == 8)                
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/tahapan" class="padding-l-r-lg ">               
                       <span >Tahapan</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/urusan" class="padding-l-r-lg ">               
                       <span >Urusan</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/skpd" class="padding-l-r-lg ">            
                       <span >Perangkat Daerah</span>
                    </a>
                  </li>
                  @endif
                  @if(Auth::user()->level == 8 or substr(Auth::user()->mod,1,1) == 1)            
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/program" class="padding-l-r-lg ">              
                       <span >Program & Kegiatan Urusan</span>
                    </a>
                  </li> 
                  @endif
                  @if(Auth::user()->level == 8)                
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/adum/program" class="padding-l-r-lg ">              
                       <span >Program & Kegiatan Non Urusan</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/staff" class="padding-l-r-lg ">
                       <span >Akun</span>
                    </a>
                  </li>  
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/penyelia" class="padding-l-r-lg ">
                       <span >Penyelia</span>
                    </a>
                  </li>                 
                  @endif
                  @if(Auth::user()->level == 2)                 
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/staff" class="padding-l-r-lg ">
                       <span >Staff</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/program" class="padding-l-r-lg ">              
                       <span >Program & Kegiatan Urusan</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/pengaturan/adum/program" class="padding-l-r-lg ">              
                       <span >Program & Kegiatan Non Urusan</span>
                    </a>
                  </li>
                  @endif
                  </ul>
              </li>
              @endif
              <li >
                <a href="#" class="auto padding-l-r-lg parent">                  
                  <i class="fa fa-bookmark-o"></i>
                  <span class="pull-right text-heading">
                    <i class="text8 icon-bdg_arrow1 text"></i>
                    <i class="text8 icon-bdg_arrow2 text-active"></i>
                  </span>
                  <span class="font-semibold">Referensi</span>
                </a>                

                 <ul class="nav nav-sub dk">                  
                  <li>
                    <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/referensi/kegiatan" class="padding-l-r-lg ">
                       <span>Kegiatan</span>
                    </a>
                  </li>                 
                  <li>
                    <a onclick="$.alert('Fitur Ditutup!')" class="padding-l-r-lg ">
                    <!-- <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/referensi/rekening" class="padding-l-r-lg "> -->
                       <span >Rekening</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/referensi/komponen" class="padding-l-r-lg ">
                       <span >Komponen</span>
                    </a>
                  </li>                 
                  </ul>
              </li>              
              @if(Auth::user()->level == 8 or Auth::user()->level == 2)
              <li>
                <a href="#" class="auto padding-l-r-lg parent">                  
                  <i class="fa fa-folder-o"></i>
                  <span class="pull-right text-heading">
                    <i class="text8 icon-bdg_arrow1 text"></i>
                    <i class="text8 icon-bdg_arrow2 text-active"></i>
                  </span>
                  <span class="font-semibold">Lampiran</span>
                </a>                

                 <ul class="nav nav-sub dk">                  
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/rkpd" class="padding-l-r-lg ">                      
                       <span >RKPD</span>
                    </a>
                  </li>
                  <li>
                    <!-- <a onclick="$.alert('Ditutup!')" class="padding-l-r-lg ">                        -->
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/lampiran/ppas" class="padding-l-r-lg ">                       
                       <span >PPAS</span>
                    </a>
                  </li>                 
                  <li>
                    <a href="#" class="padding-l-r-lg">                  
                    <span class="pull-right">
                      <i class="text8 icon-bdg_arrow1 text"></i>
                      <i class="text8 icon-bdg_arrow2 text-active"></i>
                    </span>
                    <span>PERDA APBD</span>
                  </a>                
                   <ul class="nav nav-sub dk">
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/perda/1" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 1</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/perda/2" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 2</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/perda/3" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 3 </span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/perda/4" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 4</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/perda/5" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 5</span>
                      </a>
                    </li>
                    <!-- <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/6" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 6</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/7" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 7</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/8" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 8</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/9" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 9</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/10" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 10</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/11" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 11</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/12" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 12</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/13" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 13</span>
                      </a>
                    </li> -->
                </ul>
                  </li> 
                  <li>
                    <a href="#" class="padding-l-r-lg">                  
                    <span class="pull-right">
                      <i class="text8 icon-bdg_arrow1 text"></i>
                      <i class="text8 icon-bdg_arrow2 text-active"></i>
                    </span>
                    <span>PERWAL APBD</span>
                  </a>                
                   <ul class="nav nav-sub dk">
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/perwal/1" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 1</span>
                      </a>
                    </li> 
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/perwal/2" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 2</span>
                      </a>
                    </li> 
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/perwal/3" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 3</span>
                      </a>
                    </li> 
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/apbd/perwal/4" class="padding-l-r-lg " target="_blank">
                         <span>Lampiran 4</span>
                      </a>
                    </li> 
                  </ul>
                </li>
                  <li>
                    <a href="#" class="padding-l-r-lg">                  
                    <span class="pull-right">
                      <i class="text8 icon-bdg_arrow1 text"></i>
                      <i class="text8 icon-bdg_arrow2 text-active"></i>
                    </span>
                    <span>RKA</span>
                  </a>                
                   <ul class="nav nav-sub dk">
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/rka/skpd" class="padding-l-r-lg " target="_blank">
                         <span>RKA-SKPD</span>
                      </a>
                    </li> 
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/rka/skpd1" class="padding-l-r-lg " target="_blank">
                         <span>RKA-SKPD 1</span>
                      </a>
                    </li> 
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/rka/skpd21" class="padding-l-r-lg " target="_blank">
                         <span>RKA-SKPD 2.1</span>
                      </a>
                    </li> 
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/rka/skpd22" class="padding-l-r-lg " target="_blank">
                         <span>RKA-SKPD 2.2</span>
                      </a>
                    </li> 
                    <li>
                      <a onclick="$.alert('Ditutup!')" class="padding-l-r-lg " target="_blank">
                         <span>RKA-SKPD 2.2.1</span>
                      </a>
                    </li> 
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/rka/skpd31" class="padding-l-r-lg " target="_blank">
                         <span>RKA-SKPD 3.1</span>
                      </a>
                    </li> 
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/rka/skpd32" class="padding-l-r-lg " target="_blank">
                         <span>RKA-SKPD 3.2</span>
                      </a>
                    </li> 
                    </ul>
                    </li>   
                    <li>
                    <a href="#" class="padding-l-r-lg">                  
                    <span class="pull-right">
                      <i class="text8 icon-bdg_arrow1 text"></i>
                      <i class="text8 icon-bdg_arrow2 text-active"></i>
                    </span>
                    <span>DPA</span>
                  </a>                
                   <ul class="nav nav-sub dk">
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/dpa/skpd" class="padding-l-r-lg " target="_blank">
                         <span>DPA-SKPD</span>
                      </a>
                    </li> 
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/dpa/skpd1" class="padding-l-r-lg " target="_blank">
                         <span>DPA-SKPD 1</span>
                      </a>
                    </li> 
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/dpa/skpd21" class="padding-l-r-lg " target="_blank">
                         <span>DPA-SKPD 2.1</span>
                      </a>
                    </li> 
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/dpa/skpd22" class="padding-l-r-lg " target="_blank">
                         <span>DPA-SKPD 2.2</span>
                      </a>
                    </li> 
                    <li>
                      <a onclick="$.alert('Ditutup!')" class="padding-l-r-lg " target="_blank">
                         <span>DPA-SKPD 2.2.1</span>
                      </a>
                    </li> 
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/dpa/skpd31" class="padding-l-r-lg " target="_blank">
                         <span>DPA-SKPD 3.1</span>
                      </a>
                    </li> 
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/lampiran/dpa/skpd32" class="padding-l-r-lg " target="_blank">
                         <span>DPA-SKPD 3.2</span>
                      </a>
                    </li> 
                    </ul>
                    </li>               
                  </ul>
              </li>
              @endif
              @if(Auth::user()->level == 8 or Auth::user()->level == 0 or substr(Auth::user()->mod,1,1) == 1)
              <li>
                <a href="#" class="auto padding-l-r-lg parent">                  
                  <i class="fa fa-area-chart"></i>
                  <span class="pull-right text-heading">
                    <i class="text8 icon-bdg_arrow1 text"></i>
                    <i class="text8 icon-bdg_arrow2 text-active"></i>
                  </span>
                  <span class="font-semibold">Statistik</span>
                </a>                
                 <ul class="nav nav-sub dk">
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/urusan" class="padding-l-r-lg ">
                       <span>Urusan</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/perangkat-daerah" class="padding-l-r-lg ">       
                       <span>Perangkat Daerah</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/program" class="padding-l-r-lg ">       
                       <span>Program</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/kegiatan" class="padding-l-r-lg ">       
                       <span>Kegiatan</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/kegiatanadum" class="padding-l-r-lg ">       
                       <span>Kegiatan Non Urusan</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/paket" class="padding-l-r-lg ">       
                       <span>Paket</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/indikator" class="padding-l-r-lg ">       
                       <span>Indikator</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/musrenbang" class="padding-l-r-lg ">       
                       <span>Musrenbang</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/tagging" class="padding-l-r-lg ">       
                       <span>Tagging</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/pagu" class="padding-l-r-lg ">       
                       <span>Kategori Pagu</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/rekening" class="padding-l-r-lg ">       
                       <span>Rekening</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/komponen" class="padding-l-r-lg ">       
                       <span>Komponen</span>
                    </a>
                  </li>                 
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/porsi-apbd" class="padding-l-r-lg ">       
                       <span>Porsi APBD</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/statistik/perangkat-daerah-input" class="padding-l-r-lg ">       
                       <span>Inputing PD</span>
                    </a>
                  </li>                 
                 </ul>
              </li>              
              @endif
              @if(Auth::user()->level == 8)
              <li>
                <a href="#" class="auto padding-l-r-lg parent">                  
                  <i class="fa fa-road"></i>
                  <span class="pull-right text-heading">
                    <i class="text8 icon-bdg_arrow1 text"></i>
                    <i class="text8 icon-bdg_arrow2 text-active"></i>
                  </span>
                  <span class="font-semibold">API SIRA</span>
                </a>                
                 <ul class="nav nav-sub dk">
                  <li>
                    <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/usulan/api" class="padding-l-r-lg " target="_blank">
                       <span>MUSRENBANG</span>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="padding-l-r-lg " target="_blank">
                       <span>RESES</span>
                    </a>
                  </li>
                   <li>
                    <a href="{{ url('/') }}/simda/{{ $tahun }}" class="padding-l-r-lg " target="_blank">
                       <span>SIMDA</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{$tahun}}/api/monev/{kode}" class="padding-l-r-lg " target="_blank">
                       <span>MONEV</span>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="padding-l-r-lg">                  
                    <span class="pull-right">
                      <i class="text8 icon-bdg_arrow1 text"></i>
                      <i class="text8 icon-bdg_arrow2 text-active"></i>
                    </span>
                    <span>SIRUP</span>
                  </a>                
                   <ul class="nav nav-sub dk">
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/api/sirupKegiatan" class="padding-l-r-lg " target="_blank">
                         <span>Kegiatan</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/api/sirupProgram" class="padding-l-r-lg " target="_blank">
                         <span>Program</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/api/sirupPenyedia" class="padding-l-r-lg " target="_blank">
                         <span>Belanja Penyedia </span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/api/sirupSwakelola" class="padding-l-r-lg " target="_blank">
                         <span>Belanja Swakelola</span>
                      </a>
                    </li>
                </ul>
                  </li>
                </ul>
              </li>
              <li>
                <a href="#" class="auto padding-l-r-lg parent">                  
                  <i class="fa fa-file"></i>
                  <span class="pull-right text-heading">
                    <i class="text8 icon-bdg_arrow1 text"></i>
                    <i class="text8 icon-bdg_arrow2 text-active"></i>
                  </span>
                  <span class="font-semibold">Rekap Pivot</span>
                </a>                
                 <ul class="nav nav-sub dk">
                  <li>
                    <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/rekapAll" class="padding-l-r-lg " target="_blank">
                       <span>Rekap Semua</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/rekapBerbeda/paguRincian" class="padding-l-r-lg " target="_blank">
                       <span>Berbeda Pagu & Rincian</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/rekapBelanja" class="padding-l-r-lg " target="_blank">
                       <span>Belanja</span>
                    </a>
                  </li>
                </ul>
              </li>

             <li>
                <a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/rkaLogAll" class="auto padding-l-r-lg" target="_blank">
                  <i class="fa fa-step-forward"></i>
                  <span class="font-semibold">Log RAPBD </span>
                </a>                
              </li>
              
              @endif
            </ul>
          </nav>
          <!-- nav -->
        </div>

        <div class="navi-wrap navi-footer navi-footer-white ">
          <nav ui-nav class="navi clearfix ">
            <ul class="nav">
              <!-- <li class="hidden-folded text-heading text-xs">
                <a href="" class="padding-l-r-lg">
                  <span>Download Manual</span>
                </a>
              </li>  -->            
              <li class="hidden-folded text-heading text-xs">
                <span>Made with <i class="fa fa-heart text-xs text-danger"></i> in Bandung | V2.0</span>
              </li>


            </ul>
          </nav>
        </div>
      </div>
  </aside>
  <!-- / aside -->

<!-- content -->
@yield('content')  



</div>

<div class="modal fade" id="chpass" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Ubah Password</h5>
        <hr>
        <input type="Password" id="password" class="form-control">
        <button class="btn btn-warning m-t-md" onclick="return chpass()">Simpan</button>
      </div>
    </div>
  </div>
</div>
{{-- {{ Auth::user() }} --}}
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
    document.location.href = "{{ url('/') }}/main/"+$('#tahun-anggaran').val();
  });

  $(function(){
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/gettahun/{{$tahun}}/{{$status}}",
      success : function(data){
        $('#tahun-anggaran').append(data);
      }
    }); 
  });
</script>
@yield('plugin')
<script type="text/javascript">
  $(function() {
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/main/{{$tahun}}/usulan/getNotif",
      success : function (data) {
        if(data != 0) $('#usulan-komponen').append('<span class="badge badge-sm up bg-danger pull-right-xs">'+data+'</span>');
      }
    });
  })

  function showaccountsetting(){
    $('#chpass').modal('show');
  }

  function chpass(){
    password  = $('#password').val();
    if(password == "") $.alert('Isi Password');
    else{
      $.ajax({
          url: "{{ url('/') }}/chpass",
          type: "POST",
          data: {'_token'             : '{{ csrf_token() }}',
                  'password'          : password},
          success: function(msg){
            $.alert(msg);
            $('#chpass').modal('hide');
          }
        });
    }
  }
  $( "#budgeting" ).mousemove(function( event ) {
    
  });

  function off(){
    $.ajax({
      type  : "get",
      url   : "{{ url('/') }}/off/{{Auth::user()->id}}"
    }); 
  }
  
  jQuery(window).bind(
    "beforeunload", 
    function() { 
        return off(); 
    }
)
</script>
</body>
</html>
