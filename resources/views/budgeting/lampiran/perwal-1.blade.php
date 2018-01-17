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
	<h5>NOMOR : </h5>
	<h5>TANGGAL : {{ $tgl }} {{ $bln }} {{ $thn }}</h5>
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
		<td class="border tengah" >JUMLAH</td>
		<td class="border tengah" >DASAR HUKUM</td>
	</tr>		
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
	</tr>
	<tr style="font-size: 5px;">
		<td class="border-rincian">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
	</tr>

	@php $tot=0; @endphp	
	@foreach($pendapatan as $pen)
		@php $tot += $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach

	<tr>
		<td class="border-rincian">4</td>
		<td class="border-rincian"><b>PENDAPATAN</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($tot,0,',','.') }}</b></td>
		<td class="border-rincian kanan"></td>
	</tr>

	<tr>
		<td class="border-rincian">4.1</td>
		<td class="border-rincian"><b>&nbsp;PENDAPATAN ASLI DAERAH</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad,0,',','.') }}</b></td>
		<td class="border-rincian kanan"><b></b></td>
	</tr>	

	<tr>
		<td class="border-rincian">4.1.1</td>
		<td class="border-rincian"><b>&nbsp; &nbsp;Pajak Daerah</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totpad1,0,',','.') }}</b></td>
		<td class="border-rincian kanan"></td>
	</tr>
	@php $totP1=0 @endphp	
	@foreach($pendapatan1 as $pen)
		@php $totP1 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.1.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Hotel</td>
		<td class="border-rincian kanan border">{{ number_format($totP1,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan1 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"> {{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach

	@php $totP2=0 @endphp	
	@foreach($pendapatan2 as $pen)
		@php $totP2 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.1.02</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Restoran</td>
		<td class="border-rincian kanan border">{{ number_format($totP2,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan2 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach

	@php $totP3=0 @endphp	
	@foreach($pendapatan3 as $pen)
		@php $totP3 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.1.03</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Restoran</td>
		<td class="border-rincian kanan border">{{ number_format($totP3,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan3 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach

	@php $totP4=0 @endphp	
	@foreach($pendapatan4 as $pen)
		@php $totP4 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.1.04</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Reklame</td>
		<td class="border-rincian kanan border">{{ number_format($totP4,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan4 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach

	@php $totP5=0 @endphp	
	@foreach($pendapatan5 as $pen)
		@php $totP5 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.1.05</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Reklame</td>
		<td class="border-rincian kanan border">{{ number_format($totP5,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan5 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach

	@php $totP6=0; @endphp	
	@foreach($pendapatan6 as $pen)
		@php $totP6 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.1.07</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Reklame</td>
		<td class="border-rincian kanan border">{{ number_format($totP6,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan6 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach

	@php $totP7=0; @endphp	
	@foreach($pendapatan7 as $pen)
		@php $totP7 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.1.08</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Reklame</td>
		<td class="border-rincian kanan border">{{ number_format($totP7,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan7 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach

	@php $totP8=0; @endphp	
	@foreach($pendapatan8 as $pen)
		@php $totP8 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.1.11</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Reklame</td>
		<td class="border-rincian kanan border">{{ number_format($totP8,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan8 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach

	@php $totP9=0; @endphp	
	@foreach($pendapatan9 as $pen)
		@php $totP9 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.1.12</td>
		<td class="border-rincian">&nbsp; &nbsp; Pajak Reklame</td>
		<td class="border-rincian kanan border">{{ number_format($totP9,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan9 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach

	<!-- pajak retribusi -->
	<tr>
		<td class="border-rincian">4.1.2</td>
		<td class="border-rincian"><b>&nbsp; &nbsp;Retribusi Daerah</b></td>
		<td class="border-rincian kanan">{{ number_format($totpad2,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>
	@php $totP10=0; @endphp	
	@foreach($pendapatan10 as $pen)
		@php $totP10 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.2.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Retribusi Jasa Umum</td>
		<td class="border-rincian kanan border">{{ number_format($totP10,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan10 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach

	@php $totP11=0; @endphp	
	@foreach($pendapatan11 as $pen)
		@php $totP11 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.2.02</td>
		<td class="border-rincian">&nbsp; &nbsp; Retribusi Jasa Usaha</td>
		<td class="border-rincian kanan border">{{ number_format($totP11,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan11 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach



	@php $totP12=0; @endphp	
	@foreach($pendapatan12 as $pen)
		@php $totP12 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.2.02</td>
		<td class="border-rincian">&nbsp; &nbsp; Retribusi Perizinan Tertentu</td>
		<td class="border-rincian kanan border">{{ number_format($totP12,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan12 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach
	<!--  Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan -->
	<tr>
		<td class="border-rincian">4.1.3</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan</b></td>
		<td class="border-rincian kanan">{{ number_format($totpad3,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>
	@php $totP13=0; @endphp	
	@foreach($pendapatan13 as $pen)
		@php $totP13 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.3.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan </td>
		<td class="border-rincian kanan border">{{ number_format($totP13,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan13 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach

	<!--  Lain-lain Pendapatan Asli Daerah yang Sah -->
	<tr>
		<td class="border-rincian">4.1.4</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah</b></td>
		<td class="border-rincian kanan">{{ number_format($totpad4,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>
	@php $totP14=0; @endphp	
	@foreach($pendapatan14 as $pen)
		@php $totP14 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.4.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah </td>
		<td class="border-rincian kanan border">{{ number_format($totP14,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan14 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach
	@php $totP15=0; @endphp	
	@foreach($pendapatan15 as $pen)
		@php $totP15 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.4.02</td>
		<td class="border-rincian">&nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah </td>
		<td class="border-rincian kanan border">{{ number_format($totP15,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan15 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach
	@php $totP16=0; @endphp	
	@foreach($pendapatan16 as $pen)
		@php $totP16 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.4.11</td>
		<td class="border-rincian">&nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah </td>
		<td class="border-rincian kanan border">{{ number_format($totP16,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan16 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach
	@php $totP17=0; @endphp	
	@foreach($pendapatan17 as $pen)
		@php $totP17 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.4.14</td>
		<td class="border-rincian">&nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah </td>
		<td class="border-rincian kanan border">{{ number_format($totP14,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan17 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach
	@php $totP18=0; @endphp	
	@foreach($pendapatan18 as $pen)
		@php $totP18 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.4.16</td>
		<td class="border-rincian">&nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah </td>
		<td class="border-rincian kanan border">{{ number_format($totP18,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan18 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach

	<tr>
		<td class="border-rincian">4.2</td>
		<td class="border-rincian"><b>&nbsp;DANA PERIMBANGAN</b></td>
		<td class="border-rincian kanan">{{ number_format($totpad5,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	

	<tr>
		<td class="border-rincian">4.2.1</td>
		<td class="border-rincian"><b>&nbsp; &nbsp;Bagi Hasil Pajak/Bagi Hasil Bukan Pajak</b></td>
		<td class="border-rincian kanan">{{ number_format($totpad6,0,',','.') }}</td>
	<tr>
	@php $totP19=0; @endphp	
	@foreach($pendapatan19 as $pen)
		@php $totP19 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.2.1.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Bagi Hasil Pajak/Bagi Hasil Bukan Pajak </td>
		<td class="border-rincian kanan border">{{ number_format($totP19,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan19 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach	
	@php $totP20=0; @endphp	
	@foreach($pendapatan20 as $pen)
		@php $totP20 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.2.1.02</td>
		<td class="border-rincian">&nbsp; &nbsp; Bagi Hasil Pajak/Bagi Hasil Bukan Pajak </td>
		<td class="border-rincian kanan border">{{ number_format($totP20,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan20 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach	
	<tr>
		<td class="border-rincian">4.2.2</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Dana Alokasi Umum</b></td>
		<td class="border-rincian kanan">{{ number_format($totpad7,0,',','.') }}</td>
	<tr>
	@php $totP21=0; @endphp	
	@foreach($pendapatan21 as $pen)
		@php $totP21 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.2.2.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Bagi Hasil Pajak/Bagi Hasil Bukan Pajak </td>
		<td class="border-rincian kanan ">{{ number_format($totP21,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan21 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
	</tr>	
	@endforeach		

	<tr>
		<td class="border-rincian">4.2.3</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Dana Alokasi Khusus</b></td>
		<td class="border-rincian kanan border">{{ number_format($totpad8,0,',','.') }}</td>
	<tr>
		@php $totP22=0; @endphp	
		@foreach($pendapatan22 as $pen)
			@php $totP22 += $pen->PENDAPATAN_TOTAL @endphp
		@endforeach
		<tr>
			<td class="border-rincian">4.2.3.01</td>
			<td class="border-rincian">&nbsp; &nbsp; Dana Alokasi Khusus </td>
			<td class="border-rincian kanan border">{{ number_format($totP22,0,',','.') }}</td>
			<td class="border-rincian kanan"></td>
		</tr>	
		@foreach($pendapatan22 as $pen)
		<tr>
			<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
			<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
			<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
			<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
		</tr>	
		@endforeach	

	<tr>
		<td class="border-rincian">4.3</td>
		<td class="border-rincian"><b>&nbsp; LAIN-LAIN PENDAPATAN DAERAH YANG SAH</b></td>
		<td class="border-rincian kanan"><b>{{number_format($totpad9,0,',','.') }}</b></td>
		<td class="border-rincian kanan"></td>
	</tr>	

	<tr>
		<td class="border-rincian">4.3.1</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Pendapatan Hibah</b></td>
		<td class="border-rincian kanan"><b>{{number_format($totpad10,0,',','.') }}</b></td>
	<tr>

		@php $totP23=0; @endphp	
		@foreach($pendapatan23 as $pen)
			@php $totP23 += $pen->PENDAPATAN_TOTAL @endphp
		@endforeach
		<tr>
			<td class="border-rincian">4.3.1.01</td>
			<td class="border-rincian">&nbsp; &nbsp; &nbsp; Pendapatan Hibah Pemerintah</td>
			<td class="border-rincian kanan border">{{ number_format($totP23,0,',','.') }}</td>
			<td class="border-rincian kanan"></td>
		</tr>	
		@foreach($pendapatan23 as $pen)
		<tr>
			<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
			<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
			<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
			<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
		</tr>	
		@endforeach


		<tr>
		<td class="border-rincian">4.3.3</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Dana Bagi Hasil Pajak dari Provinsi dan Pemerintah Daerah Lainnya</b></td>
		<td class="border-rincian kanan"><b>{{number_format($totpad11,0,',','.') }}</b></td>
	<tr>	
		@php $totP24=0; @endphp	
		@foreach($pendapatan24 as $pen)
			@php $totP24 += $pen->PENDAPATAN_TOTAL @endphp
		@endforeach
		<tr>
			<td class="border-rincian">4.3.3.01</td>
			<td class="border-rincian">&nbsp; &nbsp; Dana Bagi Hasil Pajak Dari Provinsi </td>
			<td class="border-rincian kanan border">{{ number_format($totP24,0,',','.') }}</td>
			<td class="border-rincian kanan"></td>
		</tr>	
		@foreach($pendapatan24 as $pen)
		<tr>
			<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
			<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
			<td class="border-rincian kanan "><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
			<td class="border-rincian kanan">{{ $pen->PENDAPATAN_DASHUK }}</td>
		</tr>	
		@endforeach

		<tr>
		<td class="border-rincian">5</td>
		<td class="border-rincian"><b>BELANJA</b></td>
		<td class="border-rincian kanan total"><b>-</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	<tr>
		<td class="border-rincian">5.1</td>
		<td class="border-rincian"><b>&nbsp; BELANJA TIDAK LANGSUNG</b></td>
		<td class="border-rincian kanan total"><b>-</b></td>
		<td class="border-rincian kanan "></td>
	</tr>

	@php $totbt1=0 @endphp
	@foreach($btl1 as $bt1)
		@php $totbt1+=$bt1->pagu @endphp
	@endforeach
	@if($totbt1!=0)
	<tr>
		<td class="border-rincian">{{$rek1->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; <b>{{$rek1->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($totbt1,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	<tr>
		<td class="border-rincian">{{$rek11->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$rek11->REKENING_NAMA}} </td>
		<td class="border-rincian kanan total">{{ number_format($totbt1,0,',','.') }}</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@foreach($btl1 as $bt1)
		<tr>
			<td class="border-rincian">{{$bt1->REKENING_KODE}}</td>
			<td class="border-rincian"> &nbsp; &nbsp; &nbsp; &nbsp; {{$bt1->REKENING_NAMA}}</td>
			<td class="border-rincian kanan">{{ number_format($bt1->pagu,0,',','.') }}</td>
			<td class="border-rincian kanan "></td>
		</tr>
		
	@endforeach
	@endif

	@php $totbt2=0 @endphp
	@foreach($btl2 as $bt2)
		@php $totbt2+=$bt2->pagu @endphp
	@endforeach
	@if($totbt2!=0)
	<tr>
		<td class="border-rincian">{{$rek2->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; <b>{{$rek2->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($totbt2,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	<tr>
		<td class="border-rincian">{{$rek12->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$rek12->REKENING_NAMA}} </td>
		<td class="border-rincian kanan total">{{ number_format($totbt1,0,',','.') }}</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@foreach($btl2 as $bt2)
		<tr>
			<td class="border-rincian">{{$bt2->REKENING_KODE}}</td>
			<td class="border-rincian"> &nbsp; &nbsp; &nbsp; &nbsp; {{$bt2->REKENING_NAMA}}</td>
			<td class="border-rincian kanan">{{ number_format($bt2->pagu,0,',','.') }}</td>
			<td class="border-rincian kanan "></td>
		</tr>
			
	@endforeach
	@endif


	@php $totbt4=0 @endphp
	@foreach($btl4 as $bt4)
		@php $totbt4 += $bt4->pagu @endphp
	@endforeach
	@if($totbt4!=0)
	<tr>
		<td class="border-rincian">{{$rek4->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; <b>{{$rek4->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($totbt4,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	<tr>
		<td class="border-rincian">{{$rek14->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$rek14->REKENING_NAMA}} </td>
		<td class="border-rincian kanan total">{{ number_format($totbt4,0,',','.') }}</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@foreach($btl4 as $bt4)
		<tr>
			<td class="border-rincian">{{$bt4->REKENING_KODE}}</td>
			<td class="border-rincian"> &nbsp; &nbsp; &nbsp; &nbsp; {{$bt4->REKENING_NAMA}}</td>
			<td class="border-rincian kanan">{{ number_format($bt4->pagu,0,',','.') }}</td>
			<td class="border-rincian kanan "></td>
		</tr>
			
	@endforeach
	@endif



	@php $totbt5=0 @endphp
	@foreach($btl5 as $bt5)
		@php $totbt5 += $bt5->pagu @endphp
	@endforeach
	@if($totbt5!=0)
	<tr>
		<td class="border-rincian">{{$rek5->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; <b>{{$rek5->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($totbt5,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	<tr>
		<td class="border-rincian">{{$rek15->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$rek15->REKENING_NAMA}} </td>
		<td class="border-rincian kanan total">{{ number_format($totbt5,0,',','.') }}</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@foreach($btl5 as $bt5)
		<tr>
			<td class="border-rincian">{{$bt5->REKENING_KODE}}</td>
			<td class="border-rincian"> &nbsp; &nbsp; &nbsp; &nbsp; {{$bt5->REKENING_NAMA}}</td>
			<td class="border-rincian kanan">{{ number_format($bt5->pagu,0,',','.') }}</td>
			<td class="border-rincian kanan "></td>
		</tr>
			
	@endforeach
	@endif


	@php $totbt6=0 @endphp
	@foreach($btl6 as $bt6)
		@php $totbt6 += $bt6->pagu @endphp
	@endforeach
	@if($totbt6!=0)
	<tr>
		<td class="border-rincian">{{$rek6->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; <b>{{$rek6->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($totbt6,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	<tr>
		<td class="border-rincian">{{$rek16->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$rek16->REKENING_NAMA}} </td>
		<td class="border-rincian kanan total">{{ number_format($totbt6,0,',','.') }}</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@foreach($btl6 as $bt6)
		<tr>
			<td class="border-rincian">{{$bt6->REKENING_KODE}}</td>
			<td class="border-rincian"> &nbsp; &nbsp; &nbsp; &nbsp; {{$bt6->REKENING_NAMA}}</td>
			<td class="border-rincian kanan">{{ number_format($bt6->pagu,0,',','.') }}</td>
			<td class="border-rincian kanan "></td>
		</tr>
			
	@endforeach
	@endif


	@php $totbt7=0 @endphp
	@foreach($btl7 as $bt7)
		@php $totbt7 += $bt7->pagu @endphp
	@endforeach
	@if($totbt7!=0)
	<tr>
		<td class="border-rincian">{{$rek7->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; <b>{{$rek7->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($totbt7,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	<tr>
		<td class="border-rincian">{{$rek17->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$rek17->REKENING_NAMA}} </td>
		<td class="border-rincian kanan total">{{ number_format($totbt7,0,',','.') }}</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@foreach($btl7 as $bt7)
		<tr>
			<td class="border-rincian">{{$bt7->REKENING_KODE}}</td>
			<td class="border-rincian"> &nbsp; &nbsp; &nbsp; &nbsp; {{$bt7->REKENING_NAMA}}</td>
			<td class="border-rincian kanan">{{ number_format($bt7->pagu,0,',','.') }}</td>
			<td class="border-rincian kanan "></td>
		</tr>
			
	@endforeach
	@endif

	@php $totbt8=0 @endphp
	@foreach($btl8 as $bt8)
		@php $totbt8 += $bt8->pagu @endphp
	@endforeach
	@if($totbt8!=0)
	<tr>
		<td class="border-rincian">{{$rek8->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; <b>{{$rek8->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($totbt8,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	<tr>
		<td class="border-rincian">{{$rek18->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$rek18->REKENING_NAMA}} </td>
		<td class="border-rincian kanan total">{{ number_format($totbt8,0,',','.') }}</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@foreach($btl8 as $bt8)
		<tr>
			<td class="border-rincian">{{$bt8->REKENING_KODE}}</td>
			<td class="border-rincian"> &nbsp; &nbsp; &nbsp; &nbsp; {{$bt8->REKENING_NAMA}}</td>
			<td class="border-rincian kanan">{{ number_format($bt8->pagu,0,',','.') }}</td>
			<td class="border-rincian kanan "></td>
		</tr>
			
	@endforeach
	@endif



	<tr>
		<td class="border-rincian">5.2</td>
		<td class="border-rincian"><b>&nbsp; BELANJA LANGSUNG</b></td>
		<td class="border-rincian kanan total">-</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@php $totbl1=0 @endphp
	@foreach($bl1 as $b1)
		@php $totbl1+=$b1->pagu @endphp
	@endforeach
	<tr>
		<td class="border-rincian">5.2.1</td>
		<td class="border-rincian"> &nbsp; &nbsp; <b>Belanja Pegawai</b></td>
		<td class="border-rincian kanan total">{{ number_format($totbl1,0,',','.') }}</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@foreach($bl1 as $b1)
	<tr>
		<td class="border-rincian">{{$b1->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$b1->REKENING_NAMA}}</td>
		<td class="border-rincian kanan ">{{ number_format($b1->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@endforeach


	@php $totbl2=0 @endphp
	@foreach($bl2 as $b2)
		@php $totbl2+=$b2->pagu @endphp
	@endforeach
	<tr>
		<td class="border-rincian">{{$rb2->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; <b>{{$rb2->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan total">{{ number_format($totbl2,0,',','.') }}</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@foreach($bl2 as $b2)
	<tr>
		<td class="border-rincian">{{$b2->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$b2->REKENING_NAMA}}</td>
		<td class="border-rincian kanan ">{{ number_format($b2->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@endforeach

	@php $totbl3=0 @endphp
	@foreach($bl3 as $b3)
		@php $totbl3+=$b3->pagu @endphp
	@endforeach
	<tr>
		<td class="border-rincian">{{$rb3->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; <b>{{$rb3->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($totbl3,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	@foreach($bl3 as $b3)
	<tr>
		<td class="border-rincian">{{$b3->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$b3->REKENING_NAMA}}</td>
		<td class="border-rincian kanan ">{{ number_format($b3->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan "></td>
	</tr>
	@endforeach

	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"> <b>Surpluss / (Defisit)</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format(1,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>


	<tr>
		<td class="border-rincian">6</td>
		<td class="border-rincian"> <b>PEMBIAYAAN DAERAH</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format(1,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	<tr>
		<td class="border-rincian">6.1</td>
		<td class="border-rincian"> <b>&nbsp;PENERIMAAN PEMBIAYAAN DAERAH</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format(1,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	@foreach($pmb1 as $pm1)
	<tr>
		<td class="border-rincian">{{$pm1->rekening->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$pm1->rekening->REKENING_NAMA}}</td>
		<td class="border-rincian kanan total"><b>{{ number_format($pm1->PEMBIAYAAN_TOTAL,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	@endforeach

	<tr>
		<td class="border-rincian">6.2</td>
		<td class="border-rincian"> <b>&nbsp;PENGELUARAN PEMBIAYAAN DAERAH</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format(1,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	@foreach($pmb2 as $pm2)
	<tr>
		<td class="border-rincian">{{$pm2->rekening->REKENING_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; &nbsp;{{$pm2->rekening->REKENING_NAMA}}</td>
		<td class="border-rincian kanan total"><b>{{ number_format($pm2->PEMBIAYAAN_TOTAL,0,',','.') }}</b></td>
		<td class="border-rincian kanan "></td>
	</tr>
	@endforeach

	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"> <b>PEMBIAYAAN NETTO</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format(1,0,',','.') }}</b></td>
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
		
		{{ number_format(1,0,',','.') }}

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
		<td>Bandung, {{ $tgl }} {{ $bln }} {{ $thn }}</td>
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