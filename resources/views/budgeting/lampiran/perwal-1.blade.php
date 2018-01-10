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
		@php $tot += $pen->PENDAPATAN_TOTAL @endphp
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
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
	</tr>	

	<tr>
		<td class="border-rincian">4.1.1</td>
		<td class="border-rincian"><b>&nbsp; &nbsp;Pajak Daerah</b></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
	</tr>
	@php $totP1=0; @endphp	
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@endforeach

	@php $totP2=0; @endphp	
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@endforeach

	@php $totP3=0; @endphp	
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@endforeach

	@php $totP4=0; @endphp	
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@endforeach

	@php $totP5=0; @endphp	
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@endforeach
	<!-- pajak retribusi -->
	<tr>
		<td class="border-rincian">4.1.2</td>
		<td class="border-rincian"><b>&nbsp; &nbsp;Retribusi Daerah</b></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
	</tr>
	@php $totP10=0; @endphp	
	@foreach($pendapatan10 as $pen)
		@php $totP10 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.2.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Retribusi 1</td>
		<td class="border-rincian kanan border">{{ number_format($totP10,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan10 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@endforeach

	@php $totP11=0; @endphp	
	@foreach($pendapatan11 as $pen)
		@php $totP11 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.2.02</td>
		<td class="border-rincian">&nbsp; &nbsp; Retribusi 2</td>
		<td class="border-rincian kanan border">{{ number_format($totP11,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan11 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@endforeach

	@php $totP12=0; @endphp	
	@foreach($pendapatan12 as $pen)
		@php $totP12 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.1.2.02</td>
		<td class="border-rincian">&nbsp; &nbsp; Retribusi 3</td>
		<td class="border-rincian kanan border">{{ number_format($totP12,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan12 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@endforeach
	<!--  Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan -->
	<tr>
		<td class="border-rincian">4.1.3</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan</b></td>
		<td class="border-rincian kanan"></td>
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@endforeach

	<!--  Lain-lain Pendapatan Asli Daerah yang Sah -->
	<tr>
		<td class="border-rincian">4.1.4</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah</b></td>
		<td class="border-rincian kanan"></td>
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@endforeach

	<tr>
		<td class="border-rincian">4.2</td>
		<td class="border-rincian"><b>&nbsp;DANA PERIMBANGAN</b></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
	</tr>	

	<tr>
		<td class="border-rincian">4.2.1</td>
		<td class="border-rincian"><b>&nbsp; &nbsp;Bagi Hasil Pajak/Bagi Hasil Bukan Pajak</b></td>
		<td class="border-rincian kanan"></td>
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
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
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@endforeach	
	<tr>
		<td class="border-rincian">4.2.2</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Dana Alokasi Umum</b></td>
		<td class="border-rincian kanan"></td>
	<tr>
	@php $totP21=0; @endphp	
	@foreach($pendapatan21 as $pen)
		@php $totP21 += $pen->PENDAPATAN_TOTAL @endphp
	@endforeach
	<tr>
		<td class="border-rincian">4.2.2.01</td>
		<td class="border-rincian">&nbsp; &nbsp; Bagi Hasil Pajak/Bagi Hasil Bukan Pajak </td>
		<td class="border-rincian kanan border">{{ number_format($totP21,0,',','.') }}</td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($pendapatan21 as $pen)
	<tr>
		<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@endforeach		

	<tr>
		<td class="border-rincian">4.2.3</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Dana Alokasi Khusus</b></td>
		<td class="border-rincian kanan"></td>
	<tr>
		@php $totP22=0; @endphp	
		@foreach($pendapatan22 as $pen)
			@php $totP22 += $pen->PENDAPATAN_TOTAL @endphp
		@endforeach
		<tr>
			<td class="border-rincian">4.2.3.01</td>
			<td class="border-rincian">&nbsp; &nbsp; DAK </td>
			<td class="border-rincian kanan border">{{ number_format($totP22,0,',','.') }}</td>
			<td class="border-rincian kanan"></td>
		</tr>	
		@foreach($pendapatan22 as $pen)
		<tr>
			<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
			<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
			<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
			<td class="border-rincian kanan"></td>
		</tr>	
		@endforeach	

	<tr>
		<td class="border-rincian">4.3</td>
		<td class="border-rincian"><b>&nbsp; LAIN-LAIN PENDAPATAN DAERAH YANG SAH</b></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
	</tr>	

	<tr>
		<td class="border-rincian">4.3.1</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Pendapatan Hibah</b></td>
		<td class="border-rincian kanan"></td>
	<tr>
		@php $totP22=0; @endphp	
		@foreach($pendapatan22 as $pen)
			@php $totP22 += $pen->PENDAPATAN_TOTAL @endphp
		@endforeach
		<tr>
			<td class="border-rincian">4.2.3.01</td>
			<td class="border-rincian">&nbsp; &nbsp; DAK </td>
			<td class="border-rincian kanan border">{{ number_format($totP22,0,',','.') }}</td>
			<td class="border-rincian kanan"></td>
		</tr>	
		@foreach($pendapatan22 as $pen)
		<tr>
			<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
			<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
			<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
			<td class="border-rincian kanan"></td>
		</tr>	
		@endforeach

	<tr>
		<td class="border-rincian">4.3.3</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; Dana Bagi Hasil Pajak dari Provinsi dan Pemerintah Daerah Lainnya</b></td>
		<td class="border-rincian kanan"></td>
	<tr>	
		@php $totP24=0; @endphp	
		@foreach($pendapatan24 as $pen)
			@php $totP24 += $pen->PENDAPATAN_TOTAL @endphp
		@endforeach
		<tr>
			<td class="border-rincian">4.3.3.01</td>
			<td class="border-rincian">&nbsp; &nbsp; DAK </td>
			<td class="border-rincian kanan border">{{ number_format($totP24,0,',','.') }}</td>
			<td class="border-rincian kanan"></td>
		</tr>	
		@foreach($pendapatan24 as $pen)
		<tr>
			<td class="border-rincian">{{$pen->REKENING_KODE}}</td>
			<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$pen->REKENING_NAMA}}</b></td>
			<td class="border-rincian kanan border"><i>{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</i></td>
			<td class="border-rincian kanan"></td>
		</tr>	
		@endforeach


	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.5</td>
		<td class="border-rincian"><b>BELANJA</b></td>
		<td class="border-rincian kanan total">-</td>
		<td class="border-rincian kanan total">-</td>
	</tr>
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.5.1</td>
		<td class="border-rincian"><b>&nbsp; BELANJA TIDAK LANGSUNG</b></td>
		<td class="border-rincian kanan total">-</td>
		<td class="border-rincian kanan total">-</td>
	</tr>
	@foreach($btl as $bt)
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.5.1.1</td>
		<td class="border-rincian"> &nbsp; &nbsp; Belanja Pegawai</td>
		<td class="border-rincian kanan total">{{ number_format($bt->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan total">-</td>
	</tr>
	@endforeach


	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.5.2</td>
		<td class="border-rincian"><b>&nbsp; BELANJA LANGSUNG</b></td>
		<td class="border-rincian kanan total">-</td>
		<td class="border-rincian kanan total">-</td>
	</tr>

	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian"> &nbsp; &nbsp; Belanja Pegawai</td>
		<td class="border-rincian kanan total">{{ number_format(1,0,',','.') }}</td>
		<td class="border-rincian kanan total">-</td>
	</tr>

	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian"> &nbsp; &nbsp; Belanja Barang & Jasa</td>
		<td class="border-rincian kanan total">{{ number_format(1,0,',','.') }}</td>
		<td class="border-rincian kanan total">-</td>
	</tr>

	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian"> &nbsp; &nbsp; Belanja Modal</td>
		<td class="border-rincian kanan total">{{ number_format(1,0,',','.') }}</td>
		<td class="border-rincian kanan total">-</td>
	</tr>

	@foreach($bl_prog as $bp)
		@php
			$pagu_prog = 0;
		@endphp
		@foreach($bl_keg as $bk)
			@if($bp->PROGRAM_ID == $bk->PROGRAM_ID)
			@php
				$pagu_prog += $bk->BL_PAGU ;
			@endphp
			@endif
		@endforeach	
	
					
	<tr>
			<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.{{$bp->PROGRAM_KODE}}</td>
			<td class="border-rincian"> &nbsp; &nbsp; <b>{{$bp->PROGRAM_NAMA}}</b></td>
			<td class="border-rincian kanan total">{{ number_format($pagu_prog,0,',','.') }}</td>
			<td class="border-rincian kanan total">-</td>
		</tr>
		
		@foreach($bl_keg as $bk)
			@if($bp->PROGRAM_ID == $bk->PROGRAM_ID)
			<tr>
				<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.{{$bp->PROGRAM_KODE}}.{{$bk->KEGIATAN_KODE}}</td>
				<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$bk->KEGIATAN_NAMA}} </td>
				<td class="border-rincian kanan total">{{ number_format($bk->BL_PAGU,0,',','.') }}</td>
				<td class="border-rincian kanan total">-</td>
			</tr>

				@foreach($bl_rek as $br)
					@if($bk->KEGIATAN_ID == $br->KEGIATAN_ID)
					<tr>
						<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.{{$bp->PROGRAM_KODE}}.{{$bk->KEGIATAN_KODE}}.{{$br->REKENING_KODE}}</td>
						<td class="border-rincian"> &nbsp; &nbsp; &nbsp; &nbsp; {{$br->REKENING_NAMA}} </td>
						<td class="border-rincian kanan total">{{ number_format($br->pagu,0,',','.') }}</td>
						<td class="border-rincian kanan total">-</td>
					</tr>
					@endif
				@endforeach

			@endif	
		@endforeach
	@endforeach 

	<tr style="font-size: 5px;">
		<td class="border-rincian">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
	</tr>		
	</tbody>	
</table>
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