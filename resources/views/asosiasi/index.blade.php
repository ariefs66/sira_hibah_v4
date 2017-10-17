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
              <a href= "{{ url('/') }}/harga/{{$tahun}}">Dashboard</a>
            </li>
          </ul>
        </div>
        <div class="wrapper-lg padder-bottom-none">
          <div class="row">
              <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h2 class="m-t-xs text-orange font-semibold m-b-sm">-</h2>
                      <p>Belanja Langsung Tervalidasi</p>
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


