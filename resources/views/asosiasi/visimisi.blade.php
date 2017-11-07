@extends('asosiasi.layout')

@section('content')
<div id="content" class="app-content" role="main">
  <div class="hbox hbox-auto-xs hbox-auto-sm ng-scope">
    <div class="col">
      <div class="app-content-body ">
        <div class="bg-light lter">    
          <ul class="breadcrumb bg-white m-b-none">
            <li>
              <a href="#" class="btn no-shadow" ui-toggle-class="app-aside-folded" target=".app">
                <i class="icon-bdg_expand1 text"></i>
                <i class="icon-bdg_expand2 text-active"></i>
              </a>   
            </li>
            <li>
              <a href= "{{ url('/') }}/asosiasi/{{$tahun}}">Dashboard</a>
            </li>
            <li class="active"><i class="fa fa-angle-right"></i>Visi Misi</li>
          </ul>
        </div>
        <div class="wrapper-lg padder-bottom-none">
          <div class="row">
              <div class="col-md-12">
              <div class="row">
                <div class="col-md-12">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h2 class="m-t-xs text-orange font-semibold m-b-sm">Visi</h2>
                      <p>TERWUJUDNYA KOTA BANDUNG YANG UNGGUL, NYAMAN, DAN SEJAHTERA </p>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>   
        <div class="wrapper-lg padder-bottom-none">
          <div class="row">
              <div class="col-md-12">
              <div class="row">
                <div class="col-md-12">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h2 class="m-t-xs text-orange font-semibold m-b-sm">Misi</h2>
                      <p>1.Mewujudkan Bandung nyaman melalui perencanaan tataruang, pembangunan infrastruktur serta pengendalian &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;pemanfaatan ruang yang berkualitas dan berwawasan lingkungan.<br>
                      2.Menghadirkan tata kelola pemerintahan yang akuntabel, bersih dan melayani.<br>
                      3.Membangun masyarakat yang mandiri, berkualitas dan berdaya saing.<br>
                      4.Membangun perekonomian yang kokoh, maju, dan berkeadilan. </p>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div> 
        <div class="wrapper-lg padder-bottom-none">
          <div class="row">
              <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h3 class="m-t-xs text-orange font-semibold m-b-sm">Tujuan</h3>
                      <p>... </p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h3 class="m-t-xs text-orange font-semibold m-b-sm">Indikator Tujuan</h3>
                      <p>... </p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h3 class="m-t-xs text-orange font-semibold m-b-sm">Target Tujuan</h3>
                      <p>... </p>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div> 
        <div class="wrapper-lg padder-bottom-none">
          <div class="row">
              <div class="col-md-12">
              <div class="row">
                <div class="col-md-12">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h3 class="m-t-xs text-orange font-semibold m-b-sm">Strategi</h3>
                      <p>... </p>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div> 
        <div class="wrapper-lg padder-bottom-none">
          <div class="row">
              <div class="col-md-12">
              <div class="row">
                <div class="col-md-12">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h3 class="m-t-xs text-orange font-semibold m-b-sm">Arah Kebijakan</h3>
                      <p>... </p>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div> 
         <div class="wrapper-lg padder-bottom-none">
          <div class="row">
              <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h4 class="m-t-xs text-orange font-semibold m-b-sm">Program</h4>
                      <p>... </p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h5 class="m-t-xs text-orange font-semibold m-b-sm">Indikator Program</h5>
                      <p>... </p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h5 class="m-t-xs text-orange font-semibold m-b-sm">Target Program</h5>
                      <p>... </p>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div> 
           <div class="wrapper-lg padder-bottom-none">
          <div class="row">
              <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h4 class="m-t-xs text-orange font-semibold m-b-sm">Kegiatan</h4>
                      <p>... </p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h5 class="m-t-xs text-orange font-semibold m-b-sm">Indikator Kegiatan</h5>
                      <p>... </p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h5 class="m-t-xs text-orange font-semibold m-b-sm">Target Kegiatan</h5>
                      <p>... </p>
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
@endsection

@section('plugin')
@endsection


