@extends('eharga.layout')

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
      </div>
    </div>
  </div>
</div>
</div>
@endsection

@section('plugin')
@endsection


