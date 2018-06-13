<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<html>
<head>
	<title></title>
	<style type="text/css">
		* {
	      margin: 0;
	      padding: 0;
	    }

		body{
			font-family: Tahoma;
			font-size: 80%;
		}

		td{
			padding-left: 3px;
		}
	
		table, tr, td{
			border-collapse: collapse;
		}
		.rekening{
			border: 1px solid;
			border-style: dashed;
		}		
		.total{
			border: 1px solid;
		}		
		.garis{
			border: 1px dashed;
		}		
		.header, .border, .detail, .indikator, .rincian, .ttd  {
			border : 1px solid;
		}
		.indikator > tbody > tr > td {
			border: 1px solid;			
		}		
		.border-rincian{
			border-left: 1px solid;
			border-right: 1px solid;
		}
		.headrincian{
			text-align: center;
			vertical-align: middle;
			font-weight: bold;
		}
		.ttd{
			text-align: center;
		}
		table{
			width: 100%;
		}
		h3, h4, h5{
			text-align: center;
			margin-top: 0px;
			margin-bottom: 0px;
		}
		h5{
			size: 110%;
		}
		.kanan{
			text-align: right;
			padding-right: 5px;
		}
		.tengah{
			text-align: center;
		}
    	@media print {
		    footer {page-break-after: always;}
			table { page-break-inside:auto; }
	    	tr    { page-break-inside:auto; page-break-after:auto }
	    	thead { display:table-header-group; }
	    	tfoot { display:table-footer-group; }		    
		}
	</style>
</head>
<body onload="window.print()">
<div class="cetak">
	<div style="margin-left: 330px;">
	<h5>LAMPIRAN Ia &nbsp; &nbsp; &nbsp; Rancangan Peraturan Wali Kota Bandung</h5>
	<h5>NOMOR : 475 Tahun 2018</h5>
	<h5>TANGGAL : 16 {{ $bln }} {{ $thn }}</h5>
	</div>
	<br>
	
<table class="header">
	<tr class="">
		<td class="" colspan="2"></td>
	</tr>
	<tr>	
		<td class="">
			<img src="{{ url('/') }}/assets/img/bandung.png" width="80px" style="margin:3px">
		</td>	
		<td>
			<h4>PEMERINTAH KOTA BANDUNG</h4>
			<h3>RINGKASAN APBD BERDASARKAN RINCIAN OBYEK PENDAPATAN, BELANJA DAN PEMBIAYAAN</h3>
			<h5>TAHUN ANGGARAN {{ $tahun }}</h5>
		</td>
	</tr>
	<tr> <td colspan="2"></td> </tr>
</table>
<table class="rincian">
	<tbody>
	<tr class="border headrincian">
		<td class="border tengah" >NOMOR <br> URUT </td>
		<td class="border tengah" >URAIAN</td>
		<td class="border tengah" >MURNI</td>
		<td class="border tengah" >{{strtoupper($status)}}</td>
		<td class="border tengah" >SELISIH</td>
		<td class="border tengah" >DASAR HUKUM</td>
	</tr>		
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
		<td class="border">5</td>
		<td class="border">6</td>
	</tr>
	<tr style="font-size: 5px;">
		<td class="border-rincian">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
	</tr>

	@php $tot=0; @endphp	
	@foreach($pendapatan as $pen)
		@php $tot += $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $totp=0; @endphp	
	@foreach($pendapatanp as $pen)
		@php $totp += $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $tots=$totp-$tot; @endphp
	<tr>
		<td class="border-rincian"><b>4</b></td>
		<td class="border-rincian"><b>PENDAPATAN</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($tot,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totp,0,',','.') }}</b></td>
		@if ($tots<0)
		<td class="border-rincian kanan border"><b>({{ number_format(abs($tots),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan border"><b>{{ number_format($tots,0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>

	<tr>
		<td class="border-rincian"><b>4.1</b></td>
		<td class="border-rincian"><b>&nbsp;PENDAPATAN ASLI DAERAH</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpadp,0,',','.') }}</b></td>
		@if ($totpads<0)
		<td class="border-rincian kanan border"><b>({{ number_format(abs($totpads),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan border"><b>{{ number_format($totpads,0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan"><b></b></td>
	</tr>	

	<tr>
		<td class="border-rincian">4.1.1</td>
		<td class="border-rincian"><b>&nbsp; &nbsp;Pajak Daerah</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad1,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad1p,0,',','.') }}</b></td>
		@if ($totpad1s<0)
		<td class="border-rincian kanan border"><b>({{ number_format(abs($totpad1s),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan border"><b>{{ number_format($totpad1s,0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>
	@php $totP1=0 @endphp	
	@foreach($pendapatan1 as $pen)
		@php $totP1 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP1p=0 @endphp	
	@foreach($pendapatan1p as $pen)
		@php $totP1p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP1s=$totP1p-$totP1; @endphp
	<tr>
		<td class="border-rincian">4.1.1.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Hotel</td>
		<td class="border-rincian kanan garis">{{ number_format($totP1,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP1p,0,',','.') }}</td>
		@if ($totP1s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP1s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP1s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	

	@php $pendapatan_totalp = array(count($pendapatan1p)); $i=0; @endphp
	@foreach($pendapatan1p as $pen)
		@php $pendapatan_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp

	@foreach($pendapatan1 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan">{{ number_format($pendapatan_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan1s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan1s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan1s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach

	@php $totP2=0 @endphp	
	@foreach($pendapatan2 as $pen)
		@php $totP2 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach

	@php $totP2p=0 @endphp	
	@foreach($pendapatan2p as $pen)
		@php $totP2p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP2s=$totP2p-$totP2; @endphp

	<tr>
		<td class="border-rincian">4.1.1.02</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Restoran</td>
		<td class="border-rincian kanan garis">{{ number_format($totP2,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP2p,0,',','.') }}</td>
		@if ($totP2s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP2s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP2s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	

	@php $pendapatan2_totalp = array(count($pendapatan2p)); $i=0; @endphp
	@foreach($pendapatan2p as $pen)
		@php $pendapatan2_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp

	@foreach($pendapatan2 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan">{{ number_format($pendapatan2_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan2s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan2s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan2s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach

	@php $totP3=0 @endphp	
	@foreach($pendapatan3 as $pen)
		@php $totP3 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach

	@php $totP3p=0 @endphp	
	@foreach($pendapatan3p as $pen)
		@php $totP3p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP3s=$totP3p-$totP3; @endphp
	<tr>
	<td class="border-rincian">4.1.1.03</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Hiburan</td>
		<td class="border-rincian kanan garis">{{ number_format($totP3,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP3p,0,',','.') }}</td>
		@if ($totP3s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP3s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP3s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan3_totalp = array(count($pendapatan3p)); $i=0; @endphp
	@foreach($pendapatan3p as $pen)
		@php $pendapatan3_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan3 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan">{{ number_format($pendapatan3_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan3s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan3s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan3s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach

	@php $totP4=0 @endphp	
	@foreach($pendapatan4 as $pen)
		@php $totP4 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP4p=0 @endphp	
	@foreach($pendapatan4p as $pen)
		@php $totP4p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP4s=$totP4p-$totP4; @endphp

	<tr>
		<td class="border-rincian">4.1.1.04</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Reklame</td>
		<td class="border-rincian kanan garis">{{ number_format($totP4,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP4p,0,',','.') }}</td>
		@if ($totP4s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP4s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP4s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan4_totalp = array(count($pendapatan4p)); $i=0; @endphp
	@foreach($pendapatan4p as $pen)
		@php $pendapatan4_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan4 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan">{{ number_format($pendapatan4_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan4s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan4s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan4s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
	@php $totP5=0 @endphp	
	@foreach($pendapatan5 as $pen)
		@php $totP5 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP5p=0 @endphp	
	@foreach($pendapatan5p as $pen)
		@php $totP5p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP5s=$totP5p-$totP5; @endphp

	<tr>
		<td class="border-rincian">4.1.1.05</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Penerangan Jalan</td>
		<td class="border-rincian kanan garis">{{ number_format($totP5,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP5p,0,',','.') }}</td>
		@if ($totP5s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP5s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP5s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan5_totalp = array(count($pendapatan5p)); $i=0; @endphp
	@foreach($pendapatan5p as $pen)
		@php $pendapatan5_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan5 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan">{{ number_format($pendapatan5_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan5s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan5s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan5s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
    @php $totP6=0 @endphp	
	@foreach($pendapatan6 as $pen)
		@php $totP6 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP6p=0 @endphp	
	@foreach($pendapatan6p as $pen)
		@php $totP6p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP6s=$totP6p-$totP6; @endphp

	<tr>
		<td class="border-rincian">4.1.1.07</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Parkir</td>
		<td class="border-rincian kanan garis">{{ number_format($totP6,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP6p,0,',','.') }}</td>
		@if ($totP6s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP6s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP6s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan6_totalp = array(count($pendapatan6p)); $i=0; @endphp
	@foreach($pendapatan6p as $pen)
		@php $pendapatan6_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan6 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan">{{ number_format($pendapatan6_totalp[$i],0,',','.') }}</td>
		@if ($pendapatan6s<0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan6s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan6s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@php $i+=1;@endphp
	@endforeach
	
	
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
		<td class="border-rincian">&nbsp; &nbsp; Pajak Air Bawah Tanah</td>
		<td class="border-rincian kanan garis">{{ number_format($totP7,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP7p,0,',','.') }}</td>
		@if ($totP7s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP7s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP7s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan7_totalp = array(count($pendapatan7p)); $i=0; @endphp
	@foreach($pendapatan7p as $pen)
		@php $pendapatan7_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan7 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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
		<td class="border-rincian">&nbsp; &nbsp; Pajak Bumi dan Bangunan (PBB)</td>
		<td class="border-rincian kanan garis">{{ number_format($totP8,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP8p,0,',','.') }}</td>
		@if ($totP8s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP8s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP8s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan8_totalp = array(count($pendapatan8p)); $i=0; @endphp
	@foreach($pendapatan8p as $pen)
		@php $pendapatan8_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan8 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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
		<td class="border-rincian">4.1.1.12</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Reklame</td>
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
	@php $i=1;@endphp
	@foreach($pendapatan9 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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
	
	<!-- pajak retribusi -->
	<tr>
		<td class="border-rincian">4.1.2</td>
		<td class="border-rincian"><b>&nbsp; &nbsp;Hasil Retribusi Daerah</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad2,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad2,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format(0,0,',','.') }}</b></td>
		<td class="border-rincian kanan"></td>
	</tr>
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
		<td class="border-rincian kanan garis">({{ number_format(abs($totP10s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP10s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan10_totalp = array(count($pendapatan10p)); $i=0; @endphp
	@foreach($pendapatan10p as $pen)
		@php $pendapatan10_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan10 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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
		<td class="border-rincian kanan garis">({{ number_format(abs($totP11s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP11s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan11_totalp = array(count($pendapatan11p)); $i=0; @endphp
	@foreach($pendapatan11p as $pen)
		@php $pendapatan11_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan11 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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
		<td class="border-rincian kanan garis">({{ number_format(abs($totP12s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP12s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan12_totalp = array(count($pendapatan12p)); $i=0; @endphp
	@foreach($pendapatan12p as $pen)
		@php $pendapatan12_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan12 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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
		<td class="border-rincian kanan border"><b>{{ number_format($totpad3,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad3p,0,',','.') }}</b></td>
		@if ($totpad3s<0)
		<td class="border-rincian kanan border"><b>({{ number_format(abs($totpad3s),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan border"><b>{{ number_format($totpad3s,0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>
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
		<td class="border-rincian">&nbsp; &nbsp; Bagian Laba Atas Penyertaan Modal pada Perusahaan Milik Daerah / BUMD </td>
		<td class="border-rincian kanan garis">{{ number_format($totP13,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP13p,0,',','.') }}</td>
		@if ($totP13s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP13s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP13s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan13_totalp = array(count($pendapatan13p)); $i=0; @endphp
	@foreach($pendapatan13p as $pen)
		@php $pendapatan13_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan13 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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
		<td class="border-rincian kanan border"><b>{{ number_format($totpad4,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad4p,0,',','.') }}</b></td>
		@if ($totpad4s<0)
		<td class="border-rincian kanan border"><b>({{ number_format(abs($totpad4s),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan border"><b>{{ number_format($totpad4s,0,',','.') }}</b></td>
		@endif
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

	@php $pendapatan14_totalp = array(count($pendapatan14p)); $i=0; @endphp
	@foreach($pendapatan14p as $pen)
		@php $pendapatan14_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan14 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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
		<td class="border-rincian">&nbsp; &nbsp; Penerimaan Jasa Giro </td>
		<td class="border-rincian kanan garis">{{ number_format($totP15,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP15p,0,',','.') }}</td>
		@if ($totP15s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP15s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP15s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan15_totalp = array(count($pendapatan15p)); $i=0; @endphp
	@foreach($pendapatan15p as $pen)
		@php $pendapatan15_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan15 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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
		<td class="border-rincian">&nbsp; &nbsp; Fasilitas Sosial dan Fasilitas Umum </td>
		<td class="border-rincian kanan garis">{{ number_format($totP16,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP16p,0,',','.') }}</td>
		@if ($totP16s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP16s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP16s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan16_totalp = array(count($pendapatan16p)); $i=0; @endphp
	@foreach($pendapatan16p as $pen)
		@php $pendapatan16_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan16 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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
		<td class="border-rincian">4.1.4.14</td>
		<td class="border-rincian">&nbsp; &nbsp; Pendapatan dari Pemanfaatan Kekayaan Daerah </td>
		<td class="border-rincian kanan garis">{{ number_format($totP17,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP17p,0,',','.') }}</td>
		@if ($totP17s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP17s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP17s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan17_totalp = array(count($pendapatan17p)); $i=0; @endphp
	@foreach($pendapatan17p as $pen)
		@php $pendapatan17_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan17 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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
		<td class="border-rincian">4.1.4.16</td>
		<td class="border-rincian">&nbsp; &nbsp; Pendapatan BLUD </td>
		<td class="border-rincian kanan garis">{{ number_format($totP18,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP18p,0,',','.') }}</td>
		@if ($totP18s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP18s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP18s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	

	<tr>
		<td class="border-rincian">4.1.4.16.01</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp;Pendapatan Jasa Layanan Umum BLUD</b></td>
		<td class="border-rincian kanan ">{{ number_format($totP18,0,',','.') }}</td>
		<td class="border-rincian kanan">{{ number_format($totP18p,0,',','.') }}</td>
		@if ($pendapatan18s<0)
		<td class="border-rincian kanan">({{ number_format(abs($totP18s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($totP18s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> </td>
	</tr>
	@php $i+=1; $totpad5s=$totpad5p-$totpad5; @endphp
	<tr>
		<td class="border-rincian">4.2</td>
		<td class="border-rincian"><b>&nbsp;DANA PERIMBANGAN</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad5,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad5p,0,',','.') }}</b></td>
		@if ($totpad5s < 0)
		<td class="border-rincian kanan border"><b>({{ number_format(abs($totpad5s),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan border"><b>{{ number_format($totpad5s,0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	

	<tr>
		<td class="border-rincian">4.2.1</td>
		<td class="border-rincian"><b>&nbsp; &nbsp;Bagi Hasil Pajak/Bagi Hasil Bukan Pajak</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad6,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad6p,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad6p-$totpad6,0,',','.') }}</b></td>
		<td class="border-rincian kanan"></td>
	<tr>
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
		<td class="border-rincian">4.2.1.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Bagi Hasil Pajak </td>
		<td class="border-rincian kanan garis">{{ number_format($totP19,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP19p,0,',','.') }}</td>
		@if ($totP19s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP19s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP19s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan19_totalp = array(count($pendapatan19p)); $i=0; @endphp
	@foreach($pendapatan19p as $pen)
		@php $pendapatan19_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan19 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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
		<td class="border-rincian">4.2.1.02</td>
		<td class="border-rincian">&nbsp; &nbsp; Bagi Hasil Pajak/Bagi Hasil Bukan Pajak </td>
		<td class="border-rincian kanan garis">{{ number_format($totP20,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP20p,0,',','.') }}</td>
		@if ($totP20s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP20s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP20s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan20_totalp = array(count($pendapatan20p)); $i=0; @endphp
	@foreach($pendapatan20p as $pen)
		@php $pendapatan20_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan20 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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
	<tr>
		<td class="border-rincian">4.2.2</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Dana Alokasi Umum</b></td>
		<td class="border-rincian kanan total"><b>1.643.076.905.000}</b></td>
		<td class="border-rincian kanan total"><b>1.643.076.905.000</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($totpad7s,0,',','.') }}</b></td>
		<td class="border-rincian kanan"></td>
	<tr>
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
		<td class="border-rincian">4.2.2.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Dana Alokasi Umum </td>
		<td class="border-rincian kanan garis">{{ number_format($totP21,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($totP21p,0,',','.') }}</td>
		@if ($totP21s<0)
		<td class="border-rincian kanan garis">({{ number_format(abs($totP21s),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan garis">{{ number_format($totP21s,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	
	@php $pendapatan21_totalp = array(count($pendapatan21p)); $i=0; @endphp
	@foreach($pendapatan21p as $pen)
		@php $pendapatan21_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp
	@foreach($pendapatan21 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
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

	@php $totP22=0 @endphp	
	@foreach($pendapatan22 as $pen)
		@php $totP22 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP22p=0 @endphp	
	@foreach($pendapatan22p as $pen)
		@php $totP22p += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	@php $totP22s=$totP22p-$totP22; @endphp
	<tr>
			<td class="border-rincian"><b>4.2.3</b></td>
			<td class="border-rincian">&nbsp; &nbsp; <b>Dana Alokasi Khusus </b></td>
		<td class="border-rincian kanan border"><b>359.111.975.000</b></td>
		<td class="border-rincian kanan border"><b>493.647.642.000</b></td>
		<td class="border-rincian kanan border"><b>134.535.667.000</b></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	<tr>
			<td class="border-rincian">4.2.3.01</td>
			<td class="border-rincian">&nbsp; &nbsp; Dana Alokasi Khusus </td>
		<td class="border-rincian kanan garis">{{ number_format($dakFisik_murni,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($dakFisik,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($dakFisik-$dakFisik_murni,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	

	@foreach($dakFisik_detail as $pen)
	@foreach($dakFisik_detail_murni as $pen_m)
	@if($pen_m->PENDAPATAN_ID==$pen->PENDAPATAN_ID)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen_m->PENDAPATAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
		@if ($pen->PENDAPATAN_TOTAL-$pen_m->PENDAPATAN_TOTAL < 0)
		<td class="border-rincian kanan">({{ number_format(abs($pen->PENDAPATAN_TOTAL-$pen_m->PENDAPATAN_TOTAL),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pen->PENDAPATAN_TOTAL-$pen_m->PENDAPATAN_TOTAL,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@endif
	@endforeach
	@endforeach
	

	@php $pendapatan223_totalp = array(count($pendapatan223p)); $i=0; @endphp
	@foreach($pendapatan223p as $pen)
		@php $pendapatan223_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp

	<tr>
			<td class="border-rincian">4.2.3.02</td>
			<td class="border-rincian">&nbsp; &nbsp; Dana Alokasi Khusus (Non Fisik) </td>
		<td class="border-rincian kanan garis">{{ number_format($dakNonFisik_murni,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($dakNonFisik,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($dakNonFisik-$dakNonFisik_murni,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	

	@foreach($dakNonFisik_detail as $pen)
	@foreach($dakNonFisik_detail_murni as $pen_m)
	@if($pen_m->PENDAPATAN_ID==$pen->PENDAPATAN_ID)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan ">{{ number_format($pen_m->PENDAPATAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
		@if ($pen->PENDAPATAN_TOTAL-$pen_m->PENDAPATAN_TOTAL < 0)
		<td class="border-rincian kanan">({{ number_format(abs($pen->PENDAPATAN_TOTAL-$pen_m->PENDAPATAN_TOTAL),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pen->PENDAPATAN_TOTAL-$pen_m->PENDAPATAN_TOTAL,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
	@endif
	@endforeach
	@endforeach
	

	<tr>
		<td class="border-rincian"><b>4.3</b></td>
		<td class="border-rincian"><b>&nbsp; LAIN-LAIN PENDAPATAN DAERAH YANG SAH</b></td>
		<td class="border-rincian kanan"><b>{{number_format($totpad9,0,',','.') }}</b></td>
		<td class="border-rincian kanan"><b>{{number_format($totpad9p,0,',','.') }}</b></td>
		@if ($totpad9p<0)
		<td class="border-rincian kanan"><b>({{ number_format(abs($totpad9s),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan"><b>{{number_format($totpad9s,0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>	

	<tr>
		<td class="border-rincian"><b>4.3.1</b></td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Pendapatan Hibah</b></td>
		<td class="border-rincian kanan"><b>{{number_format($totpad10,0,',','.') }}</b></td>
		<td class="border-rincian kanan"><b>{{number_format($totpad10p,0,',','.') }}</b></td>
		@if ($totpad10s<0)
		<td class="border-rincian kanan"><b>({{ number_format(abs($totpad10s),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan"><b>{{number_format($totpad10s,0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan"></td>
	<tr>

	@php $pendapatan23_totalp = array(count($pendapatan23p)); $i=0; $totP23p=0; @endphp
	@foreach($pendapatan23p as $pen)
			@php $totP23p += $pen->PENDAPATAN_TOTAL @endphp
		@php $pendapatan23_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1; @endphp
		@php $totP23=0; @endphp	
		@foreach($pendapatan23 as $pen)
			@php $totP23 += $pen->PENDAPATAN_TOTAL @endphp
		@endforeach
		<tr>
			<td class="border-rincian">4.3.1.01</td>
			<td class="border-rincian">&nbsp; &nbsp; &nbsp; Pendapatan Hibah Pemerintah</td>
			<td class="border-rincian kanan garis">{{ number_format($totP23,0,',','.') }}</td>
			<td class="border-rincian kanan garis">{{ number_format($totP23p,0,',','.') }}</td>
			@if ($totP23p-$totP23<0)
			<td class="border-rincian kanan garis">({{ number_format(abs($totP23p-$totP23),0,',','.') }})</td>
		@else
			<td class="border-rincian kanan garis">{{ number_format($totP23p-$totP23,0,',','.') }}</td>
		@endif
			<td class="border-rincian kanan"></td>
		</tr>	
		@foreach($pendapatan23 as $pen)
		<tr>
			<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
			<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
			<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
			<td class="border-rincian kanan ">{{ number_format($pendapatan23_totalp[$i],0,',','.') }}</td>
			@if ($pendapatan23_totalp[$i]-$pen->PENDAPATAN_TOTAL<0)
		<td class="border-rincian kanan"><b>({{ number_format(abs($pendapatan23_totalp[$i]-$pen->PENDAPATAN_TOTAL),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan"><b>{{ number_format($pendapatan23_totalp[$i]-$pen->PENDAPATAN_TOTAL,0,',','.') }}</b></td>
		@endif
			<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
		</tr>	
	@php $i+=1;@endphp
		@endforeach


		<tr>
		<td class="border-rincian"><b>4.3.3</b></td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Dana Bagi Hasil Pajak dari Provinsi dan Pemerintah Daerah Lainnya</b></td>
		<td class="border-rincian kanan"><b>{{number_format($totpad11,0,',','.') }}</b></td>
		<td class="border-rincian kanan"><b>{{ number_format($totpad11p,0,',','.') }}</b></td>
		@if ($totpad11p<0)
		<td class="border-rincian kanan"><b>({{ number_format(abs(0),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan"><b>{{ number_format(0,0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>
		@php $totP24=0; @endphp	
	@php $pendapatan24_totalp = array(count($pendapatan24p)); $i=0; @endphp
		@foreach($pendapatan24 as $pen)
			@php $totP24 += $pen->PENDAPATAN_TOTAL @endphp
		@endforeach
		@php $totP24p=0; @endphp	
		@php $i=1;@endphp
		@foreach($pendapatan24p as $pen)
		
			@php $pendapatan24_totalp[] = $pen->PENDAPATAN_TOTAL; @endphp
			@php $totP24p += $pen->PENDAPATAN_TOTAL @endphp
		@endforeach
		@php $totP24s=$totP24p-$totP24; @endphp
		<tr>
			<td class="border-rincian">4.3.3.01</td>
			<td class="border-rincian">&nbsp; &nbsp; Dana Bagi Hasil Pajak Dari Provinsi </td>
			<td class="border-rincian kanan garis">{{ number_format($totP24,0,',','.') }}</td>
			<td class="border-rincian kanan garis">{{ number_format($totP24p,0,',','.') }}</td>
			@if ($totP24s<0)
		<td class="border-rincian kanan garis"><b>({{ number_format(abs($totP24s),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan garis"><b>{{ number_format($totP24s,0,',','.') }}</b></td>
		@endif
			<td class="border-rincian kanan"></td>
		</tr>	
		@php $i=1; @endphp
		@foreach($pendapatan24 as $pen)
		<tr>
			<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
			<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
			<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
			<td class="border-rincian kanan ">{{ number_format($pendapatan24_totalp[$i],0,',','.') }}</td>
			@if ($pendapatan24_totalp[$i]-$pen->PENDAPATAN_TOTAL < 0)
		<td class="border-rincian kanan">({{ number_format(abs($pendapatan24_totalp[$i]-$pen->PENDAPATAN_TOTAL),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pendapatan24_totalp[$i]-$pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
		@endif
			<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
		</tr>	
		@php $i+=1;@endphp
		@endforeach



		<tr>
			<td class="border-rincian">4.3.5</td>
			<td class="border-rincian">&nbsp; &nbsp; Bantuan keuangan dari Provinsi Jawa Barat </td>
			<td class="border-rincian kanan garis">{{ number_format($banprov_murni,0,',','.') }}</td>
			<td class="border-rincian kanan garis">{{ number_format($banprov,0,',','.') }}</td>
			@if ($banprov-$banprov_murni < 0)
		<td class="border-rincian kanan garis"><b>({{ number_format(abs($banprov-$banprov_murni),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan garis"><b>{{ number_format($banprov-$banprov_murni,0,',','.') }}</b></td>
		@endif
			<td class="border-rincian kanan"></td>
		</tr>	
		
		@foreach($banprov_detail_murni as $pen_m)
		@foreach($banprov_detail as $pen)
		@if($pen_m->PENDAPATAN_ID == $pen->PENDAPATAN_ID)
		<tr>
			<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
			<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</td>
			<td class="border-rincian kanan ">{{ number_format($pen_m->PENDAPATAN_TOTAL,0,',','.') }}</td>
			<td class="border-rincian kanan ">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
			@if($pen->PENDAPATAN_TOTAL-$pen_m->PENDAPATAN_TOTAL < 0)
		<td class="border-rincian kanan">({{ number_format(abs($pen->PENDAPATAN_TOTAL-$pen_m->PENDAPATAN_TOTAL),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($pen->PENDAPATAN_TOTAL-$pen_m->PENDAPATAN_TOTAL,0,',','.') }}</td>
		@endif
			<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
		</tr>	
		@endif
		@endforeach
		@endforeach
		<tr>
		<td class="border-rincian"><b><br>5</b></td>
		<td class="border-rincian"><b><br>BELANJA</b></td>
		<td class="border-rincian kanan total"><b><br>{{ number_format($jumBelanja_murni,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b><br>{{ number_format($jumBelanja,0,',','.') }}</b></td>
		@if($jumBelanja-$jumBelanja_murni < 0)
		<td class="border-rincian kanan total"><b><br>({{ number_format(abs($jumBelanja-$jumBelanja_murni),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan total"><b><br>{{ number_format($jumBelanja-$jumBelanja_murni,0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan total"><br></td>
	</tr>
	@foreach($btlfull as $btl)
	@if($btl->pagu > 0 || $btl->pagu_murni > 0)
	@if(strlen($btl->REKENING_KODE)<6)
	@php $style = '<b>'; $styleClose = '</b>'; $styleNumber = 'total' @endphp
	@elseif(strlen($btl->REKENING_KODE)<9)
	@php $style = ''; $styleClose = ''; $styleNumber = 'garis' @endphp
	@else
	@php $style = '&nbsp; &nbsp; &nbsp; '; $styleClose = ''; $styleNumber = '' @endphp
	@endif
	<tr>
		<td class="border-rincian">{!! $style.$btl->REKENING_KODE.$styleClose !!}</td>
		<td class="border-rincian">{!! $style.$btl->REKENING_NAMA.$styleClose !!}</td>
		<td class="border-rincian kanan {{$styleNumber}}">{!! $style.number_format($btl->pagu_murni,0,',','.').$styleClose !!}</td>
		<td class="border-rincian kanan {{$styleNumber}}">{!! $style.number_format($btl->pagu,0,',','.').$styleClose !!}</td>
		@if($btl->pagu-$btl->pagu_murni < 0)
		<td class="border-rincian kanan {{$styleNumber}}">({!! $style.number_format(abs($btl->pagu-$btl->pagu_murni),0,',','.').$styleClose !!})</td>
		@else
		<td class="border-rincian kanan {{$styleNumber}}">{!! $style.number_format($btl->pagu-$btl->pagu_murni,0,',','.').$styleClose !!}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>
	@endif
	@endforeach
	@foreach($blfull as $bl)
	@if($bl->pagu > 0 || $bl->pagu_murni > 0)
	@if(strlen($bl->REKENING_KODE)<6)
	@php $style = '<b>'; $styleText = '&nbsp; '; $styleClose = '</b>'; $styleNumber = 'total' @endphp
	@elseif(strlen($bl->REKENING_KODE)<9)
	@php $style = ''; $styleText = '&nbsp; '; $styleClose = ''; $styleNumber = 'garis' @endphp
	@else
	@php $style = ''; $styleText = '&nbsp; '; $styleClose = ''; $styleNumber = '' @endphp
	@endif
	<tr>
		<td class="border-rincian">{!! $style.$bl->REKENING_KODE.$styleClose !!}</td>
		<td class="border-rincian">{!! $style.str_pad($input, 10)$bl->REKENING_NAMA.$styleClose !!}</td>
		<td class="border-rincian kanan {{$styleNumber}}">{!! $style.number_format($bl->pagu_murni,0,',','.').$styleClose !!}</td>
		<td class="border-rincian kanan {{$styleNumber}}">{!! $style.number_format($bl->pagu,0,',','.').$styleClose !!}</td>
		@if($bl->pagu-$bl->pagu_murni < 0)
		<td class="border-rincian kanan {{$styleNumber}}">({!! $style.number_format(abs($bl->pagu-$bl->pagu_murni),0,',','.').$styleClose !!})</td>
		@else
		<td class="border-rincian kanan {{$styleNumber}}">{!! $style.number_format($bl->pagu-$bl->pagu_murni,0,',','.').$styleClose !!}</td>
		@endif
		<td class="border-rincian kanan"></td>
	</tr>
	@endif
	@endforeach

	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"> <b>Surpluss / (Defisit)</b></td>
		<td class="border-rincian kanan total"><b>(415.333.370.810)</b></td>
		<td class="border-rincian kanan total"><b>(415.333.370.810)</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format(0,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>


	<tr>
		<td class="border-rincian">6</td>
		<td class="border-rincian"> <b>PEMBIAYAAN DAERAH</b></td>
		<td class="border-rincian kanan total"><b>787.195.062.912</b></td>
		<td class="border-rincian kanan total"><b>787.195.062.912</b></td>
		<td class="border-rincian kanan total"><b>0</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	<tr>
		<td class="border-rincian">6.1</td>
		<td class="border-rincian"> <b>&nbsp;PENERIMAAN PEMBIAYAAN DAERAH</b></td>
		<td class="border-rincian kanan total"><b>787.195.062.912</b></td>
		<td class="border-rincian kanan total"><b>787.195.062.912</b></td>
		<td class="border-rincian kanan total"><b>0</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	@foreach($pmb1 as $pm1)
	@if($pm1->PEMBIAYAAN_TOTAL != 0)
	<tr>
		<td class="border-rincian">{{$pm1->rekening->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$pm1->rekening->REKENING_NAMA}}</td>
		<td class="border-rincian kanan total">{{ number_format($pm1->PEMBIAYAAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan total">{{ number_format($pm1->PEMBIAYAAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan total">0</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@endif
	@endforeach

	<tr>
		<td class="border-rincian">6.2</td>
		<td class="border-rincian"> <b>&nbsp;PENGELUARAN PEMBIAYAAN DAERAH</b></td>
		<td class="border-rincian kanan total"><b>220.000.000.000</b></td>
		<td class="border-rincian kanan total"><b>220.000.000.000</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format(0,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	@php $pmb2pz = array(count($pmb2p)); @endphp
	@foreach($pmb2p as $pm2)
	@php $pmb2pz[] = $pm2->PEMBIAYAAN_TOTAL; @endphp
	@endforeach
	@php $i=1; @endphp
	@foreach($pmb2 as $pm2)
	@if($pm2->PEMBIAYAAN_TOTAL != 0)
	<tr>
		<td class="border-rincian">{{$pm2->rekening->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp;{{$pm2->rekening->REKENING_NAMA}}</td>
		<td class="border-rincian kanan total"> {{ number_format($pm2->PEMBIAYAAN_TOTAL,0,',','.') }} </td>
		<td class="border-rincian kanan total"> {{ number_format($pmb2pz[$i],0,',','.') }} </td>
		@if($pmb2pz[$i]-$pm2->PEMBIAYAAN_TOTAL<0)
		<td class="border-rincian kanan total"> {{ number_format(abs($pmb2pz[$i]-$pm2->PEMBIAYAAN_TOTAL),0,',','.') }} </td>
		@else
		<td class="border-rincian kanan total"> {{ number_format($pmb2pz[$i]-$pm2->PEMBIAYAAN_TOTAL,0,',','.') }} </td>
		@endif
		<td class="border-rincian kanan "></td>
	</tr>
	@endif
	@php $i+=1; @endphp
	@endforeach

	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"> <b>PEMBIAYAAN NETTO</b></td>
		<td class="border-rincian kanan total"><b>567.195.062.912</b></td>
		<td class="border-rincian kanan total"><b>567.195.062.912</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format(0,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>

	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"> </td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan "></td>
	</tr>
	<tr>
		<td class="border-rincian"></td> 
		<td class="border-rincian kanan"> <b>SISA LEBIH PEMBIAYAAN ANGGARAN TAHUN BERKENAAN</b></td>
		<td class="border-rincian kanan total"><b>
		151.861.692.102
		</b></td>
		<td class="border-rincian kanan total"><b>
		74.664.395.140,0
		</b></td>
		<td class="border-rincian kanan total"><b>
		(77.197.296.962)
		</b></td>
		<td class="border-rincian kanan "></td>
	</tr>


</tbody>	
<table class="ttd">
	<tr>
		<td></td>
		<td><br></td>
	</tr>
	<tr>
		<td width="60%"></td>
		<td>Bandung, 16 {{ $bln }} {{ $thn }}</td>
	</tr>
	<tr>
		<td></td>
		<td><b>WALIKOTA BANDUNG</b></td>
	</tr>
	<tr>
		<td></td>
		<td><br><br><br><br><br></td>
	</tr>
	<tr>
		<td></td>
		<td><b>MOCHAMAD RIDWAN KAMIL</b></td>
	</tr>
	<tr>
		<td></td>
		<td><br><br></td>
	</tr>
</table>
</div>
</body>
</html>


