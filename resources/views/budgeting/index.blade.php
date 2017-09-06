@extends('budgeting.layout')

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
              <a href= "{{ url('/') }}/main/{{$tahun}}/{{$status}}">Dashboard</a>
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
                      <h2 class="m-t-xs text-orange font-semibold m-b-sm">{{ number_format($pagu,0,',','.') }}</h2>
                      <p>Belanja Langsung</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h2 class="m-t-xs text-orange font-semibold m-b-sm">{{ number_format($btl,0,',','.') }}</h2>
                      <p>Belanja Tidak Langsung</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-body wrapper-sm">
                      <h2 class="m-t-xs text-orange font-semibold m-b-sm">{{ number_format($pdp,0,',','.') }}</h2>
                      <p>Pendapatan</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="panel panel-default">
                <div class="panel-body no-padder">
                  <div class="col-xs-6">
                    <div class="wrapper text-center">
                      @if($blv == 0 and $bln == 0)
                      <div ui-jq="easyPieChart" ui-options="{
                      percent: 0,
                      lineWidth: 25,
                      trackColor: '#e5e6ec',
                      barColor: '#00b0ff',
                      scaleColor: '#ffffff',
                      size: 230,
                      lineCap: 'butt',
                      animate: 1000
                    }" class="easyPieChart inline text-center" style="width: 230px; height: 230px; line-height: 230px;">
                      @else
                      <div ui-jq="easyPieChart" ui-options="{
                      percent: {{$blv/$bln*100}},
                      lineWidth: 25,
                      trackColor: '#e5e6ec',
                      barColor: '#00b0ff',
                      scaleColor: '#ffffff',
                      size: 230,
                      lineCap: 'butt',
                      animate: 1000
                    }" class="easyPieChart inline text-center" style="width: 230px; height: 230px; line-height: 230px;">
                      @endif
                    <div>
                      @if($blv == 0 and $bln == 0)                    
                      <span class="h2 m-l-sm step">{{ number_format(0,0,',','.') }}%</span>
                      @else
                      <span class="h2 m-l-sm step">{{ number_format($blv/$bln*100,0,',','.') }}%</span>
                      @endif
                    </div>
                    <canvas width="230" height="230"></canvas></div>
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="wrapper text-left">
                    <p class="border-bottom border-bottom padder-v m-b-md m-t-sm"><span class="m-b-xs">Total Belanja Langsung</span> <br> <span class="text-success text16 font-semibold">{{ $bln }} Belanja</span></p>
                    <p><span>Total Belanja Langsung Divalidasi</span> <br> <span class="text-success text16 font-semibold">{{ $blv }} Belanja</span></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
              <div class="panel panel-default" style="height: 290px;overflow: scroll;">
                <div class="panel-body no-padder">
                  <div class="wrapper-lg m-t-n-sm">
                    <h5 class="inline font-semibold text-orange m-n ">Info Tahapan</h5>
                  </div>
                  <div class="tab-content tab-content-alt bg-white m-t-n-md">
                  <table class="table">
                    <tr>
                      <th>Tahapan</th>
                      <th>Tanggal</th>
                      <th>Status</th>
                    </tr>
                    <tr>
                      <td>RKPD</td>
                      @if(empty($rkpd))
                      <td>-</td>
                      @else
                      <td>{{ date('d M',strtotime($rkpd->TAHAPAN_AWAL)) }} - {{ date('d M',strtotime($rkpd->TAHAPAN_AKHIR)) }}</td>
                      @endif
                      @if(empty($rkpd)) <td class="text-danger">-</td>
                      @elseif($rkpd->TAHAPAN_SELESAI == '0') <td class="text-info"><i class="fa fa-refresh"></i></td>
                      @elseif($rkpd->TAHAPAN_SELESAI == '1') <td class="text-success"><i class="fa fa-check"></i></td>
                      @endif
                    </tr>
                    <tr>
                      <td>RKUA</td>
                      @if(empty($rkua))
                      <td>-</td>
                      @else
                      <td>{{ date('d M',strtotime($rkua->TAHAPAN_AWAL)) }} - {{ date('d M',strtotime($rkua->TAHAPAN_AKHIR)) }}</td>
                      @endif
                      @if(empty($rkua)) <td class="text-danger">-</td>
                      @elseif($rkua->TAHAPAN_SELESAI == '0') <td class="text-info"><i class="fa fa-refresh"></i></td>
                      @elseif($rkua->TAHAPAN_SELESAI == '1') <td class="text-success"><i class="fa fa-check"></i></td>
                      @endif
                    </tr>
                    <tr>
                      <td>KUA/PPAS</td>
                      @if(empty($ppas))
                      <td>-</td>
                      @else
                      <td>{{ date('d M',strtotime($ppas->TAHAPAN_AWAL)) }} - {{ date('d M',strtotime($ppas->TAHAPAN_AKHIR)) }}</td>
                      @endif
                      @if(empty($ppas)) <td class="text-danger">-</td>
                      @elseif($ppas->TAHAPAN_SELESAI == '0') <td class="text-info"><i class="fa fa-refresh"></i></td>
                      @elseif($ppas->TAHAPAN_SELESAI == '1') <td class="text-success"><i class="fa fa-check"></i></td>
                      @endif
                    </tr>
                    <tr>
                      <td>RAPBD</td>
                      @if(empty($rapbd))
                      <td>-</td>
                      @else
                      <td>{{ date('d M',strtotime($rapbd->TAHAPAN_AWAL)) }} - {{ date('d M',strtotime($rapbd->TAHAPAN_AKHIR)) }}</td>
                      @endif
                      @if(empty($rapbd)) <td class="text-danger">-</td>
                      @elseif($rapbd->TAHAPAN_SELESAI == '0') <td class="text-info"><i class="fa fa-refresh"></i></td>
                      @elseif($rapbd->TAHAPAN_SELESAI == '1') <td class="text-success"><i class="fa fa-check"></i></td>
                      @endif
                    </tr>
                    <tr>
                      <td>APBD</td>
                      @if(empty($apbd))
                      <td>-</td>
                      @else
                      <td>{{ date('d M',strtotime($apbd->TAHAPAN_AWAL)) }} - {{ date('d M',strtotime($apbd->TAHAPAN_AKHIR)) }}</td>
                      @endif
                      @if(empty($apbd)) <td class="text-danger">-</td>
                      @elseif($apbd->TAHAPAN_SELESAI == '0') <td class="text-info"><i class="fa fa-refresh"></i></td>
                      @elseif($apbd->TAHAPAN_SELESAI == '1') <td class="text-success"><i class="fa fa-check"></i></td>
                      @endif
                    </tr>
                  </table>
                  </div>                  
                </div>
              </div>
            </div>
            @if(Auth::user()->level != 8 and Auth::user()->level != 0)
            <div class="col-md-8">
            @else
            <div class="col-md-12">
            @endif
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
              <div class="panel panel-default">
                <div class="panel-body no-padder">
                  <div class="col-xs-6">
                    <div class="col-xs-12">
                                <div class="wrapper text-center">
                                    <div class="line pull-in"></div>
                                    <div ui-jq="plot" ui-options="
                                      [{label : 'Usulan Belum Masuk',data: [{{ $musren-$musrenin }}]},{label : 'Usulan Masuk', data: [{{ $musrenin }}]}],
                                      {
                                        series: { pie: { show: true, innerRadius: 0.5, stroke: { width: 0 }, label: { show: false, threshold: 0.05 } } },
                                        colors: ['#ff0000','#00ff00'],
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
                    <p class="border-bottom border-bottom padder-v m-b-md m-t-sm"><span class="m-b-xs">Usulan Musrenbang</span> <br/> 
                    <span class="text16 font-semibold" style="color: #00ff00">{{ number_format($musrenin,0,',','.') }}</span> / 
                    <span class="text16 font-semibold" style="color: #ff0000">{{ number_format($musren,0,',','.') }}</span></p>
                    <p class="border-bottom border-bottom padder-v m-b-md m-t-sm"><span>Total Usulan Musrenbang</span> <br/> 
                    <span class="text16 font-semibold" style="color: #00ff00">Rp. {{ number_format($musrentotalin,0,',','.') }}</span> / 
                    <span class="text16 font-semibold" style="color: #ff0000">Rp. {{ number_format($musrentotal,0,',','.') }}</span></p>
                  </div>
                </div>
                </div>
              </div>
            </div>
          @if(Auth::user()->level != 8 and Auth::user()->level != 0)            
          <div class="col-md-4">
              <div class="panel panel-default" style="height: 725px;overflow: scroll;">
                <div class="panel-body no-padder">
                  <div class="wrapper-lg m-t-n-sm">
                  @if(Auth::user()->level == 1)                    
                    <h5 class="inline font-semibold text-orange m-n ">Info Kegiatan Anda</h5>
                  @else
                    <h5 class="inline font-semibold text-orange m-n ">Info Kegiatan Belum Validasi</h5>
                  @endif
                  </div>
                  <div class="tab-content tab-content-alt bg-white m-t-n-sm">
                  <table class="table">
                  @if(Auth::user()->level == 1)
                    @foreach($staff as $st)
                    <tr>
                      <td><a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/belanja-langsung/detail/{{$st->BL_ID}}" target="_blank">{{ $st->bl->kegiatan->KEGIATAN_NAMA }}</a></td>
                    </tr>
                    @endforeach
                  @elseif(Auth::user()->level == 2 or Auth::user()->level == 3 or Auth::user()->level == 4)
                    @foreach($staff as $st)
                    <tr>
                      <td><a href="{{ url('/') }}/main/{{$tahun}}/{{$status}}/belanja-langsung/detail/{{$st->BL_ID}}" target="_blank">{{ $st->kegiatan->KEGIATAN_NAMA }}</a></td>
                    </tr>
                    @endforeach
                  @endif
                  </table>
                  </div>                  
                </div>
              </div>
            </div>
            @endif
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
 
  @if($info != '')
  $(document).ready(function() {
      $('#info').modal('show');
  });
  @endif

  @if(Auth::user()->level == 2)
  $(document).ready(function() {
    @if($pengumuman)
      $('#pengumuman').modal('show');
    @endif
  });
  @endif
  @if(empty($jabatan) or empty($alamat))
  $(document).ready(function() {
      $('#chprofile').modal('show');
  });
  @endif
  function chprofile(){
    pangkat  = $('#pangkat').val();
    alamat  = $('#alamat').val();
    if(alamat == "" || pangkat == "") $.alert('Isi Alamat');
    else{
      $.ajax({
          url: "{{ url('/') }}/chprofile",
          type: "POST",
          data: {'_token'             : '{{ csrf_token() }}',
                  'PANGKAT'           : pangkat,
                  'ALAMAT'            : alamat},
          success: function(msg){
            $.alert(msg);
            $('#chprofile').modal('hide');
          }
        });
    }
  }
</script>
@endsection


