@extends('monev.layout')

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
            <li><a href= "{{ url('/monev/'.$tahun) }}">Dashboard</a></li>
            <li class="active"><i class="fa fa-angle-right"></i>Ringkasan</li>                                
          </ul>
        </div>

        <div class="wrapper-lg">
          <div class="row">
            <div class="col-md-12">
              <div class="panel bg-white">
                <div class="wrapper-lg">
                  <h5 class="inline font-semibold text-orange m-n ">Statistik Monev {{$tahun}}</h5>
                </div>               
                <div class="tab-content tab-content-alt-1 bg-white">
                  <div role="tabpanel" class="active tab-pane" id="tab-1">
                    <div id="table-ringkasan">
                      <table class="table">
                        <thead>
                          <tr>
                            <th width="1%">Kode</th>
                            <th>Nama Dinas</th>
                            <th width="20%" class="text-right">Total Program</th>
                            <th width="20%" class="text-right">Sudah Diisi</th>
                            <th width="20%" class="text-right">Belum Diisi</th>
                          </tr>
                        </thead>
                        <tbody>
@foreach($skpd as $data)
                          <tr>
                            <td><b>{{ $data['KODE'] }}</b></td>
                            <td><b>{{ $data['NAMA'] }} </b></td>
                            <td class="text-right"><b> {{ number_format($data['TOTAL'],0,',','.') }}</b></td>
                            <td class="text-right"><b> {{ number_format($data['ISI'],0,',','.') }}</b></td>
                            <td class="text-right"><b> {{ number_format($data['TOTAL']-$data['ISI'],0,',','.') }}</b></td>
                          </tr>
@endforeach
                          <tr>
                            <td colspan="2" class="text-right"><b>TOTAL </b></td>
                            <td class="text-right"><b> {{ number_format($program,0,',','.') }}</b></td>
                            <td class="text-right"><b> {{ number_format($isi,0,',','.') }}</b></td>
                            <td class="text-right"><b> {{ number_format($selisih,0,',','.') }}</b></td>
                          </tr> 
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
    </div>
  </div>
  @endsection

  @section('plugin')
  <script type="text/javascript">
  </script>
  @endsection