@extends('public.layout')

@section('content')
<div id="content" class="" role="main">
  <div class="hbox hbox-auto-xs hbox-auto-sm ng-scope">
    <div class="col">
      <div class="app-content-body ">
        <div class="bg-light lter">    
          <ul class="breadcrumb bg-white m-b-none">
            <li>
              <a href="/" class="btn no-shadow">
                <i class="icon-bdg_expand1 text"></i>
              </a>   
            </li>
            <li>
              <a href= "{{ url('/') }}/public/{{$tahun}}/{{$status}}">Dashboard</a>
            </li>
          </ul>
        </div>
        <div class="wrapper-lg padder-bottom-none">
          <div class="row">
              <div class="col-md-12">
              <div class="row">
                <div class="col-md-3">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h2 class="m-t-xs text-orange font-semibold m-b-sm">{{ number_format($pagu,0,',','.') }}</h2>
                      <p> <a href= "{{ url('/') }}/public/{{$tahun}}/{{$status}}/belanja-langsung">Belanja Langsung</a></p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h2 class="m-t-xs text-orange font-semibold m-b-sm">{{ number_format($btl,0,',','.') }}</h2>
                      <p><a href= "{{ url('/') }}/public/{{$tahun}}/{{$status}}/belanja-tidak-langsung">Belanja Tidak Langsung</a></p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h2 class="m-t-xs text-orange font-semibold m-b-sm">{{ number_format($pdp,0,',','.') }}</h2>
                      <p>Pendapatan</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h2 class="m-t-xs text-orange font-semibold m-b-sm">{{ number_format($pby,0,',','.') }}</h2>
                      <p>Pembiayaan</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-body no-padder">
                  <div class="col-xs-6">
                    <div class="col-xs-12">
                                <div class="wrapper text-center">
                                    <div class="line pull-in"></div>
                                    <div ui-jq="plot" ui-options="
                                      [{label : 'Belanja Pegawai',data: [{{ $b1 }}]},{label : 'Belanja Barang & Jasa', data: [{{ $b2 }}]}, {label : 'Belanja Modal', data: [{{ $b3 }} ]}],
                                      {
                                        series: { pie: { show: true, innerRadius: 0.5, stroke: { width: 0 }, label: { show: false, threshold: 0.05 } } },
                                        colors: ['#00b0ff','#ff7e00','#8560a8'],
                                        grid: { hoverable: true, clickable: true, borderWidth: 0, color: '#ccc' },   
                                        tooltip: true,
                                        tooltipOpts: { content: '%s: %p.0%' },
                                        legend: {show: false}
                                      }
                                    " style="height:240px"></div>
                                </div>
                              </div>
                  </div>
                <div class="col-xs-6">
                  <div class="wrapper text-left">
                    <p class="border-bottom border-bottom padder-v m-b-md m-t-sm"><span class="m-b-xs">Total Belanja Pegawai</span> <br/> 
                    <span class="text16 font-semibold" style="color: #00b0ff">Rp. {{ number_format($b1,2,',','.') }}</span></p>
                    <p class="border-bottom border-bottom padder-v m-b-md m-t-sm"><span>Total Belanja Barang dan Jasa </span> <br/> 
                    <span class="text16 font-semibold" style="color: #ff7e00">Rp. {{ number_format($b2,2,',','.') }}</span></p>
                    <p class="border-bottom border-bottom padder-v m-b-md m-t-sm"><span>Total Belanja Modal </span> <br/> 
                    <span class="text16 font-semibold" style="color: #8560a8">Rp. {{ number_format($b3,2,',','.') }}</span></p>
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
</div>

<div class="modal fade" id="chprofile" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Ubah Profile</h5>
        <hr>
        <input type="alamat" id="alamat" class="form-control" placeholder="Alamat OPD">
        <input type="pangkat" id="pangkat" class="form-control m-t-md" placeholder="Pangkat Kepala OPD">
        <button class="btn btn-warning m-t-md" onclick="return chprofile()">Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="info" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">{{$info}}</h5>
        <hr>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="pengumuman" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Penyempurnaan Rincian Kegiatan RPPAS 2018</h5>
        <table class="table m-t-md">
          <tbody>
            @if($pengumuman)
            @foreach($pengumuman as $p)
            <tr>
              <td>{{ $p->bl->kegiatan->KEGIATAN_NAMA }}</td>
              <td><a href="{{ url('/') }}/main/{{ $tahun }}/{{ $status }}/belanja-langsung/detail/{{ $p->BL_ID }}" target="_blank">Buka</a></td>
            </tr>
            @endforeach
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="video" tabindex="-1" role="dialog">
  <div class="modal-dialog bg-white">
    <div class="panel panel-default">
      <div class="wrapper-lg">
        <h5 class="inline font-semibold text-orange m-n text16 ">Tutorial</h5>
        <hr>
        <video width="540px" controls>
            <source src="{{url('/')}}/video/OUTPUT.mp4" type="video/mp4">
            Your browser does not support HTML5 video.
          </video>
      </div>
    </div>
  </div>
</div>
@endsection

@section('plugin')

<script type="text/javascript">
 
</script>
@endsection


