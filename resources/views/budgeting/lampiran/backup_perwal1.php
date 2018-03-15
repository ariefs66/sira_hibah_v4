
    @php $totP7=0 @endphp	
	@foreach($pendapatan7 as $pen)
		@php $totP7 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP7p=0 @endphp	
	@foreach($pendapatan7p as $pen)
		@php $totP7p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP7s=$totP7p-$totP7; @endphp

	<tr>
		<td class="border-rincian">4.1.1.08</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Reklame</td>
		<td class="border-rincian kanan garis">{{ number_format($totP7,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP7p,0,',','.') }}</td>
		@if ($totP7s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP7s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP7s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan7_totalp = array(count($pendapatan7p)); $i=0; @endphp
	@foreach($pendapatan7p as $pen)
		@php $pendapatan7_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan7 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan7_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan7s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan7s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan7s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
    @php $totP8=0 @endphp	
	@foreach($pendapatan8 as $pen)
		@php $totP8 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP8p=0 @endphp	
	@foreach($pendapatan8p as $pen)
		@php $totP8p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP8s=$totP8p-$totP8; @endphp

	<tr>
		<td class="border-rincian">4.1.1.11</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Reklame</td>
		<td class="border-rincian kanan garis">{{ number_format($totP8,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP8p,0,',','.') }}</td>
		@if ($totP8s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP8s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP8s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan8_totalp = array(count($pendapatan8p)); $i=0; @endphp
	@foreach($pendapatan8p as $pen)
		@php $pendapatan8_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan8 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan8_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan8s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan8s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan8s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
    @php $totP9=0 @endphp	
	@foreach($pendapatan9 as $pen)
		@php $totP9 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP9p=0 @endphp	
	@foreach($pendapatan9p as $pen)
		@php $totP9p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP9s=$totP9p-$totP9; @endphp

	<tr>
		<td class="border-rincian">4.1.2</td>
		<td class="border-rincian"><b>&nbsp; &nbsp;Retribusi Daerah</b></td>
		<td class="border-rincian kanan garis">{{ number_format($totP9,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP9p,0,',','.') }}</td>
		@if ($totP9s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP9s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP9s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan9_totalp = array(count($pendapatan9p)); $i=0; @endphp
	@foreach($pendapatan9p as $pen)
		@php $pendapatan9_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan9 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan9_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan9s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan9s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan9s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
    @php $totP10=0 @endphp	
	@foreach($pendapatan10 as $pen)
		@php $totP10 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP10p=0 @endphp	
	@foreach($pendapatan10p as $pen)
		@php $totP10p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP10s=$totP10p-$totP10; @endphp

	<tr>
		<td class="border-rincian">4.1.2.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Retribusi Jasa Umum</td>
		<td class="border-rincian kanan garis">{{ number_format($totP10,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP10p,0,',','.') }}</td>
		@if ($totP10s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP10s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP10s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan10_totalp = array(count($pendapatan10p)); $i=0; @endphp
	@foreach($pendapatan10p as $pen)
		@php $pendapatan10_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan10 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan10_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan10s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan10s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan10s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
    @php $totP11=0 @endphp	
	@foreach($pendapatan11 as $pen)
		@php $totP11 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP11p=0 @endphp	
	@foreach($pendapatan11p as $pen)
		@php $totP11p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP11s=$totP11p-$totP11; @endphp

	<tr>
		<td class="border-rincian">4.1.2.02</td>
		<td class="border-rincian">&nbsp; &nbsp; Retribusi Jasa Usaha</td>
		<td class="border-rincian kanan garis">{{ number_format($totP11,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP11p,0,',','.') }}</td>
		@if ($totP11s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP11s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP11s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan11_totalp = array(count($pendapatan11p)); $i=0; @endphp
	@foreach($pendapatan11p as $pen)
		@php $pendapatan11_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan11 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan11_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan11s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan11s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan11s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
    @php $totP12=0 @endphp	
	@foreach($pendapatan12 as $pen)
		@php $totP12 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP12p=0 @endphp	
	@foreach($pendapatan12p as $pen)
		@php $totP12p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP12s=$totP12p-$totP12; @endphp

	<tr>
	    <td class="border-rincian">4.1.2.02</td>
		<td class="border-rincian">&nbsp; &nbsp; Retribusi Perizinan Tertentu</td>
		<td class="border-rincian kanan garis">{{ number_format($totP12,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP12p,0,',','.') }}</td>
		@if ($totP12s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP12s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP12s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan12_totalp = array(count($pendapatan12p)); $i=0; @endphp
	@foreach($pendapatan12p as $pen)
		@php $pendapatan12_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan12 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan12_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan12s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan12s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan12s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
	<!--  Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan -->
	<tr>
		<td class="border-rincian">4.1.3</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan</b></td>
		<td class="border-rincian kanan">{{ number_format($totpad3,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>
	@php $totP13=0; @endphp	

    @php $totP13=0 @endphp	
	@foreach($pendapatan13 as $pen)
		@php $totP13 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP13p=0 @endphp	
	@foreach($pendapatan13p as $pen)
		@php $totP13p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP13s=$totP13p-$totP13; @endphp

	<tr>
		<td class="border-rincian">4.1.3.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan </td>
		<td class="border-rincian kanan garis">{{ number_format($totP13,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP13p,0,',','.') }}</td>
		@if ($totP13s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP13s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP13s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan13_totalp = array(count($pendapatan13p)); $i=0; @endphp
	@foreach($pendapatan13p as $pen)
		@php $pendapatan13_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan13 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan13_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan13s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan13s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan13s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
		<!--  Lain-lain Pendapatan Asli Daerah yang Sah -->
        <tr>
		<td class="border-rincian">4.1.4</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah</b></td>
		<td class="border-rincian kanan">{{ number_format($totpad4,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	    </tr>
    @php $totP14=0 @endphp	
	@foreach($pendapatan14 as $pen)
		@php $totP14 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP14p=0 @endphp	
	@foreach($pendapatan14p as $pen)
		@php $totP14p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP14s=$totP14p-$totP14; @endphp

	<tr>
		<td class="border-rincian">4.1.4.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah </td>
		<td class="border-rincian kanan garis">{{ number_format($totP14,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP14p,0,',','.') }}</td>
		@if ($totP14s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP14s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP14s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan14_totalp = array(count($pendapatan14p)); $i=0; @endphp
	@foreach($pendapatan14p as $pen)
		@php $pendapatan14_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan14 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan14_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan14s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan14s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan14s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
    @php $totP15=0 @endphp	
	@foreach($pendapatan15 as $pen)
		@php $totP15 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP15p=0 @endphp	
	@foreach($pendapatan15p as $pen)
		@php $totP15p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP15s=$totP15p-$totP15; @endphp

	<tr>
		<td class="border-rincian">4.1.4.02</td>
		<td class="border-rincian">&nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah </td>
		<td class="border-rincian kanan garis">{{ number_format($totP15,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP15p,0,',','.') }}</td>
		@if ($totP15s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP15s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP15s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan15_totalp = array(count($pendapatan15p)); $i=0; @endphp
	@foreach($pendapatan15p as $pen)
		@php $pendapatan15_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan15 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan15_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan15s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan15s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan15s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
    @php $totP16=0 @endphp	
	@foreach($pendapatan16 as $pen)
		@php $totP16 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP16p=0 @endphp	
	@foreach($pendapatan16p as $pen)
		@php $totP16p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP16s=$totP16p-$totP16; @endphp

	<tr>
		<td class="border-rincian">4.1.4.11</td>
		<td class="border-rincian">&nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah </td>
		<td class="border-rincian kanan garis">{{ number_format($totP16,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP16p,0,',','.') }}</td>
		@if ($totP16s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP16s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP16s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan16_totalp = array(count($pendapatan16p)); $i=0; @endphp
	@foreach($pendapatan16p as $pen)
		@php $pendapatan16_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan16 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan16_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan16s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan16s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan16s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
    @php $totP17=0 @endphp	
	@foreach($pendapatan17 as $pen)
		@php $totP17 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP17p=0 @endphp	
	@foreach($pendapatan17p as $pen)
		@php $totP17p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP17s=$totP17p-$totP17; @endphp

	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian">&nbsp; &nbsp; </td>
		<td class="border-rincian kanan garis">{{ number_format($totP17,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP17p,0,',','.') }}</td>
		@if ($totP17s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP17s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP17s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan17_totalp = array(count($pendapatan17p)); $i=0; @endphp
	@foreach($pendapatan17p as $pen)
		@php $pendapatan17_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan17 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan17_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan17s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan17s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan17s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
    @php $totP18=0 @endphp	
	@foreach($pendapatan18 as $pen)
		@php $totP18 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP18p=0 @endphp	
	@foreach($pendapatan18p as $pen)
		@php $totP18p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP18s=$totP18p-$totP18; @endphp

	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian">&nbsp; &nbsp; </td>
		<td class="border-rincian kanan garis">{{ number_format($totP18,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP18p,0,',','.') }}</td>
		@if ($totP18s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP18s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP18s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan18_totalp = array(count($pendapatan18p)); $i=0; @endphp
	@foreach($pendapatan18p as $pen)
		@php $pendapatan18_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan18 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan18_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan18s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan18s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan18s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
    @php $totP19=0 @endphp	
	@foreach($pendapatan19 as $pen)
		@php $totP19 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP19p=0 @endphp	
	@foreach($pendapatan19p as $pen)
		@php $totP19p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP19s=$totP19p-$totP19; @endphp

	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian">&nbsp; &nbsp; </td>
		<td class="border-rincian kanan garis">{{ number_format($totP19,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP19p,0,',','.') }}</td>
		@if ($totP19s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP19s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP19s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan19_totalp = array(count($pendapatan19p)); $i=0; @endphp
	@foreach($pendapatan19p as $pen)
		@php $pendapatan19_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan19 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan19_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan19s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan19s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan19s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
    @php $totP20=0 @endphp	
	@foreach($pendapatan20 as $pen)
		@php $totP20 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP20p=0 @endphp	
	@foreach($pendapatan20p as $pen)
		@php $totP20p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP20s=$totP20p-$totP20; @endphp

	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian">&nbsp; &nbsp; </td>
		<td class="border-rincian kanan garis">{{ number_format($totP20,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP20p,0,',','.') }}</td>
		@if ($totP20s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP20s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP20s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan20_totalp = array(count($pendapatan20p)); $i=0; @endphp
	@foreach($pendapatan20p as $pen)
		@php $pendapatan20_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan20 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan20_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan20s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan20s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan20s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
    @php $totP21=0 @endphp	
	@foreach($pendapatan21 as $pen)
		@php $totP21 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP21p=0 @endphp	
	@foreach($pendapatan21p as $pen)
		@php $totP21p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP21s=$totP21p-$totP21; @endphp

	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian">&nbsp; &nbsp; </td>
		<td class="border-rincian kanan garis">{{ number_format($totP21,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP21p,0,',','.') }}</td>
		@if ($totP21s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP21s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP21s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan21_totalp = array(count($pendapatan21p)); $i=0; @endphp
	@foreach($pendapatan21p as $pen)
		@php $pendapatan21_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan21 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ number_format($pendapatan21_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan21s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan21s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan21s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	