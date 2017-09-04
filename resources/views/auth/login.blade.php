<!DOCTYPE html>
<html lang="en" class="">
<head>
  <meta charset="utf-8" />
  <title>.bdg Planning & Budgeting</title>
  <meta name="description" content="Bandung SIRA" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/assets/animate.css/animate.css" type="text/css" />
  <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/assets/simple-line-icons/css/simple-line-icons.css" type="text/css" />
  <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/jquery/bootstrap/dist/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="{{ url('/') }}/assets/css/font.css" type="text/css" />
  <link rel="stylesheet" href="{{ url('/') }}/assets/fonts/simdaicon/simdaicon.css" type="text/css" />
  <link rel="stylesheet" href="{{ url('/') }}/assets/css/style.css" type="text/css" />
  <link rel="stylesheet" href="{{ url('/') }}/assets/css/homepage.css" type="text/css" />
</head>

<body>
<div class="notification bg-success text-red" style="display: none;">
  <div class="container text-white">
    <i class="mi-warning pull-left "></i>
    <p class="pull-left">Ini Merupakan area pengumuman, user dengan bebas bisa klik link yang ada di pengumuman, atau menutup jendela pengumuman dengan klik icon x, atau bisa diseting menutup otomatis</p>
    <a href="javascript:void(0);" class="close-notification"><i class="icon-bdg_cross pull-right"></i></a>
  </div>
</div>

<div class="main-container">
  <header id="homepage-header">
    <div class="container no-padder">
      
      <div class="logo pull-left">
        {{-- <a href="/"><img src="{{ url('/') }}/assets/img/logo-small@2x.png.png" srcset="{{ url('/') }}/assets/img/logo-small@2x.png.png" alt=""></a> --}}
      </div>

      <nav class="pull-right">
        <ul class="nav-menu">
          <!-- <li><a href="#">FAQ</a></li> -->
         <!--  <li><a href="http://apbd.bandung.go.id:8000/">APBD 2017</a></li> -->
          <li><a href="#">APBD 2017</a></li>
          <li class="sub-menu">
            <a href="#">Download <i class="mi-caret-down"></i></a>

            <ul>
              <!-- <li><a href="#">Dasar hukum</a></li> -->
              <li><a href="{{ url('/') }}/doc/manual.pdf" target="_blank">Manual Guide</a></li>
              <!-- <li><a href="#">Video Tutorial</a></li> -->
            </ul>
          </li>
        </ul>
      </nav>

    </div>
  </header>
</div>

<div class="main-page">
  <div class="container">
    <div class="row">
      
      <div class="col-md-8 app-slider">
        <div id="ebudgetting_hype_container" style="margin:auto;position:relative;width:800px;height:475px;overflow:hidden;" aria-live="polite">
          <script type="text/javascript" charset="utf-8" src="E-Budgetting.hyperesources/ebudgetting_hype_generated_script.js?24505"></script>
        </div>
      </div>

      <div class="col-md-4 text-white no-padder m-t-xl">
        <h4 class="m-r-xxl text text-right">Sistem Informasi<br>Perencanaan & Penganggaran Kota Bandung.</h4>        
        <!-- Form Login -->
        <form class="form-horizontal m-l-sm" role="form" method="POST" action="{{ url('/login') }}">
          {{ csrf_field() }}
          <div class="form-group">
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-9">
                <input type="text" name="email" placeholder="Username" class="input-xl form-control input-dark m-b-md m-l-xl" required="">
                </div>
                <div class="col-md-3"></div>
              </div>
              <div class="col-md-12">
                <div class="col-md-9">
                <input type="password" name="password" placeholder="Password" class="input-xl form-control w-full input-dark m-b-md m-l-xl" required="">
                </div>
                <div class="col-md-3"></div>
              </div>
              <div class="col-md-12">
                <div class="col-md-6"></div>
                <div class="col-md-4">
                  <button class="btn btn-warning w-full input-xl pull-right" type="submit">LOGIN</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="info" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Pengumuman!</h5>
        <hr>
        <p>Download manual book RENJA <a href="{{ url('/') }}/doc/manual.pdf" target="_blank">disini</a></p>
        <p>Download manual book USULAN KOMPONEN <a href="{{ url('/') }}/doc/manualkomponen.pdf" target="_blank">disini</a></p>
        <p>Latihan input <a href="http://128.199.82.1:88" target="_blank">disini</a></p>
      </div>
      </div>
    </div>
  </div>
</div>

<footer style="bottom: 0;position: absolute;" class="w-full">
  <div class="container">
    <p class="pull-left">Copyright &copy; 2016 BDGWebkit</p>
    <p class="pull-right">Made with <i class="mi-heart text-danger"></i> in Bandung</p>
  </div>
</footer>

<script src="{{ url('/') }}/libs_dashboard/jquery/jquery/dist/jquery.js"></script>
<script src="{{ url('/') }}/libs_dashboard/jquery/bootstrap/dist/js/bootstrap.js"></script>
<script src="{{ url('/') }}/assets/js/ui-load.js"></script>
<script src="{{ url('/') }}/assets/js/ui-jp.config.js"></script>
<script src="{{ url('/') }}/assets/js/ui-jp.js"></script>
<script src="{{ url('/') }}/assets/js/ui-nav.js"></script>
<script src="{{ url('/') }}/assets/js/ui-toggle.js"></script>
<script src="{{ url('/') }}/assets/js/ui-client.js"></script>
<script src="{{ url('/') }}/assets/js/custom.js"></script>

</body>
<script type="text/javascript">
  $(function(){
    // $('#info').modal('show');
  })
</script>
</html>