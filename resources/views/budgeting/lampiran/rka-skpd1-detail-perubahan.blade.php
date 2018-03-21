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
			font-size: 60%;
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
		}
		.ttd{
			text-align: center;
		}
		table{
			width: 100%;
		}
		h4, h5{
			text-align: center;
			margin-top: 0px;
			margin-bottom: 0px;
		}
		h5{
			size: 110%;
		}
		.kanan{
			text-align: right;
			vertical-align: top;			
			padding-right: 5px;
		}
		.kiri{
			text-align: left;
			vertical-align: top;			
			padding-right: 5px;
		}
		.tengah{
			text-align: center;
			vertical-align: top;
		}
    	@media print {
		    footer {page-break-after: always;}
			table { page-break-inside:auto; }
	    	tr    { page-break-inside:avoid; page-break-after:auto }
	    	td    { page-break-inside:avoid; page-break-after:auto }
	    	thead { display:table-header-group; }
	    	tfoot { display:table-footer-group; }		    
		}
	</style>
</head>
<body onload="window.print()">
<div class="cetak">


<table class="header">
	<tr class="border">
		<td class="border" width="85%">
			<h4>RENCANA KERJA DAN ANGGARAN PERUBAHAN<br>SATUAN KERJA PERANGKAT DAERAH</h4>
		</td>
		<td rowspan="2" class="border">
			<h4>Formulir<br>RKAP-SKPD 1</h4>
		</td>
	</tr>
	<tr>
		<td>
			<h4>Kota Bandung<br>Tahun Anggaran {{$tahun}}</h4>
		</td>
	</tr>
</table>
<table class="detail">	
	<tr class="border">
		<td width="18%">Urusan Pemerintahan</td>
		<td width="25%">: {{ $urusan->URUSAN_KODE }}</td> 
		<td>{{ $urusan->URUSAN_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Organisasi</td>
		<td>: {{ $urusan->URUSAN_KODE }}.{{ $skpd->SKPD_KODE }}</td> 
		<td>{{ $skpd->SKPD_NAMA }}</td>
	</tr>
</table>

<table class="rincian">
	<tbody>
	<tr class="border">
		<td colspan="12"><h4>Rincian Anggaran Pendapatan 
		Satuan Kerja Perangkat Daerah</h4></td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" rowspan="3">Kode Rekening</td>
		<td class="border" rowspan="3">Uraian</td>
		<td class="border" colspan="4">Sebelum @if($status=='pergeseran') Pergeseran @else Perubahan @endif</td>
		<td class="border" colspan="4">Sesudah @if($status=='pergeseran') Pergeseran @else Perubahan @endif</td>
		<td class="border" colspan="2">Bertambah/<br>(Berkurang)</td>
	</tr>
	<tr class="border headrincian">
		<td class="border" colspan="3">Rincian Perhitungan</td>
		<td class="border" rowspan="2">Jumlah</td>
		<td class="border" colspan="3">Rincian Perhitungan</td>
		<td class="border" rowspan="2">Jumlah</td>
		<td class="border" rowspan="2">(Rp)</td>
		<td class="border" rowspan="2">%</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border">Volume</td>
		<td class="border">Satuan</td>
		<td class="border">Harga<br>Harga</td>
		<td class="border">Volume</td>
		<td class="border">Satuan</td>
		<td class="border">Harga<br>Harga</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
		<td class="border">5</td>
		<td class="border">6 = (3x5)</td>
		<td class="border">7</td>
		<td class="border">8</td>
		<td class="border">9</td>
		<td class="border">10 = (7x9)</td>
		<td class="border">11</td>
		<td class="border">12</td>
	</tr>

	@php
		$totpen11=0;$totpen12=0;$totpen13=0;$totpen14=0;
		$totpen21=0;$totpen22=0;$totpen23=0;
		$totpen31=0;$totpen33=0;$totpen34=0;$totpen35=0;
	@endphp
	@foreach($pendapatan11 as $pen11)
		@php $totpen11+=$pen11->total; @endphp
	@endforeach
	@foreach($pendapatan12 as $pen12)
		@php $totpen12+=$pen12->total; @endphp
	@endforeach
	@foreach($pendapatan13 as $pen13)
		@php $totpen13+=$pen13->total; @endphp
	@endforeach
	@foreach($pendapatan14 as $pen14)
		@php $totpen14+=$pen14->total; @endphp
	@endforeach
	@foreach($pendapatan21 as $pen21)
		@php $totpen21+=$pen21->total; @endphp
	@endforeach
	@foreach($pendapatan22 as $pen22)
		@php $totpen22+=$pen22->total; @endphp
	@endforeach
	@foreach($pendapatan23 as $pen23)
		@php $totpen23+=$pen23->total; @endphp
	@endforeach
	@foreach($pendapatan31 as $pen31)
		@php $totpen31+=$pen31->total; @endphp
	@endforeach
	@foreach($pendapatan33 as $pen33)
		@php $totpen33+=$pen33->total; @endphp
	@endforeach
	@foreach($pendapatan34 as $pen34)
		@php $totpen34+=$pen34->total; @endphp
	@endforeach
	@foreach($pendapatan35 as $pen35)
		@php $totpen35+=$pen35->total; @endphp
	@endforeach



	@php
		$totpen11_p=0;$totpen12_p=0;$totpen13_p=0;$totpen14_p=0;
		$totpen21_p=0;$totpen22_p=0;$totpen23_p=0;
		$totpen31_p=0;$totpen33_p=0;$totpen34_p=0;$totpen35_p=0;
	@endphp
	@foreach($pendapatan11_p as $pen11_p)
		@php $totpen11_p+=$pen11_p->total; @endphp
	@endforeach
	@foreach($pendapatan12_p as $pen12_p)
		@php $totpen12_p+=$pen12_p->total; @endphp
	@endforeach
	@foreach($pendapatan13_p as $pen13_p)
		@php $totpen13_p+=$pen13_p->total; @endphp
	@endforeach
	@foreach($pendapatan14_p as $pen14_p)
		@php $totpen14_p+=$pen14_p->total; @endphp
	@endforeach
	@foreach($pendapatan21_p as $pen21_p)
		@php $totpen21_p+=$pen21_p->total; @endphp
	@endforeach
	@foreach($pendapatan22_p as $pen22_p)
		@php $totpen22_p+=$pen22_p->total; @endphp
	@endforeach
	@foreach($pendapatan23_p as $pen23_p)
		@php $totpen23_p+=$pen23_p->total; @endphp
	@endforeach
	@foreach($pendapatan31_p as $pen31_p)
		@php $totpen31_p+=$pen31_p->total; @endphp
	@endforeach
	@foreach($pendapatan33_p as $pen33_p)
		@php $totpen33_p+=$pen33_p->total; @endphp
	@endforeach
	@foreach($pendapatan34_p as $pen34_p)
		@php $totpen34_p+=$pen34_p->total; @endphp
	@endforeach
	@foreach($pendapatan35_p as $pen35_p)
		@php $totpen35_p+=$pen35_p->total; @endphp
	@endforeach


	<!-- pendapatan -->
	@if($pendapatan != 0)
	<tr>
		<td class="border-rincian kiri "> <br> 4</td>
		<td class="border-rincian "><b> <br> Pendapatan</b></td>
		<td class="border-rincian "></td>
		<td class="border-rincian "></td>
		<td class="border-rincian "></td>
		<td class="border-rincian kanan border"> <br> {{ number_format($pendapatan,0,',','.') }}</td>
		<td class="border-rincian "></td>
		<td class="border-rincian "></td>
		<td class="border-rincian "></td>
		<td class="border-rincian kanan border"> <br> {{ number_format($pendapatan_p,0,',','.') }}</td>
		<td class="border-rincian kanan border"> <br> {{ number_format($pendapatan_p-$pendapatan,0,',','.') }}</td>
		<td class="border-rincian kanan border"> <br> 0 %</td>
	</tr>
		<!-- satu -->
		@if($pendapatan1 != 0)
		<tr>
			<td class="border-rincian kiri ">4.1</td>
			<td class="border-rincian "><b> &nbsp; Pandapatan Asli Daerah</b></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan1,0,',','.') }} </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan1_p,0,',','.') }} </td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan1_p-$pendapatan1,0,',','.') }} </td>
			<td class="border-rincian kanan border">0 % </td>
		</tr>
		<tr>
			<td class="border-rincian kiri ">4.1.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Pajak Daerah </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen11,0,',','.') }}</td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen11_p,0,',','.') }}</td>
			<td class="kanan border-rincian ">{{ number_format($totpen11_p-$totpen11,0,',','.') }}</td>
			<td class="kanan border-rincian ">0 %</td>
		</tr>
		@foreach($pendapatan11 as $pen11)
		@foreach($pendapatan11_p as $pen11_p)
			@if($pen11->REKENING_KODE == $pen11_p->REKENING_KODE)
			<tr>
				<td class="border-rincian kiri ">{{$pen11->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen11->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen11->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen11->total,0,',','.') }} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen11_p->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen11_p->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> {{ number_format($pen11_p->total-$pen11->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> 0 % </td>
			</tr>
			@endif
		@endforeach
		@endforeach
		<!-- 12 -->
		<tr>
			<td class="border-rincian kiri ">4.1.2</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Retribusi Daerah </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen12,0,',','.') }}</td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen12_p,0,',','.') }}</td>
			<td class="kanan border-rincian ">{{ number_format($totpen12_p-$totpen12,0,',','.') }}</td>
			<td class="kanan border-rincian ">0 %</td>
		</tr>	
		@foreach($pendapatan12 as $pen12)
		@foreach($pendapatan12_p as $pen12_p)
			@if($pen12_p->REKENING_KODE == $pen12->REKENING_KODE)
			<tr>
				<td class="border-rincian kiri ">{{$pen12->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen12->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen12->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen12->total,0,',','.') }} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen12_p->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen12_p->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> {{ number_format($pen12_p->total-$pen12->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> 0 % </td>
			</tr>
			@endif
		@endforeach
		@endforeach
		<!-- 13 -->
		<tr>
			<td class="border-rincian kiri ">4.1.3</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen13,0,',','.') }} </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen13_p,0,',','.') }} </td>
			<td class="kanan border-rincian ">{{ number_format($totpen13_p-$totpen13,0,',','.') }} </td>
			<td class="kanan border-rincian ">0 % </td>
		</tr>	
		@foreach($pendapatan13 as $pen13)
		@foreach($pendapatan13_p as $pen13_p)
		    @if($pen13->REKENING_KODE == $pen13_p->REKENING_KODE)
			<tr>
				<td class="border-rincian kiri ">{{$pen13->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen13->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen13->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen13->total,0,',','.') }} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen13_p->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen13_p->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> {{ number_format($pen13_p->total-$pen13->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> 0 %</td>
			</tr>
			@endif
		@endforeach
		@endforeach
		<tr>
			<td class="border-rincian kiri ">4.1.4</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen14,0,',','.') }} </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen14_p,0,',','.') }} </td>
			<td class="kanan border-rincian ">{{ number_format($totpen14_p-$totpen14,0,',','.') }} </td>
			<td class="kanan border-rincian "> 0 % </td>
		</tr>
		@foreach($pendapatan14 as $pen14)
		@foreach($pendapatan14_p as $pen14_p)
			@if($pen14_p->REKENING_KODE==$pen14->REKENING_KODE)
			<tr>
				<td class="border-rincian kiri ">{{$pen14->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen14->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen14->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen14->total,0,',','.') }} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen14_p->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen14_p->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> {{ number_format($pen14_p->total-$pen14->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> 0 % </td>
			</tr>
			@endif
		@endforeach
		@endforeach
		@endif
		<!-- dua -->
		@if($pendapatan2 != 0)
		<tr>
			<td class="border-rincian kiri ">4.2</td>
			<td class="border-rincian "><b> &nbsp; Dana Perimbangan </b></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan2,0,',','.') }} </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan2_p,0,',','.') }} </td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan2_p-$pendapatan2,0,',','.') }} </td>
			<td class="border-rincian kanan border">0 % </td>
		</tr>
		<!-- 2.1 -->
		<tr>
			<td class="border-rincian kiri ">4.2.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Bagi Hasil Pajak/Bagi Hasil Bukan Pajak </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen21,0,',','.') }} </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen21_p,0,',','.') }} </td>
			<td class="kanan border-rincian ">{{ number_format($totpen21_p-$totpen21,0,',','.') }} </td>
			<td class="kanan border-rincian ">0 % </td>
		</tr>	
		@foreach($pendapatan21 as $pen21)
		@foreach($pendapatan21_p as $pen21_p)
			@if($pen21_p->REKENING_KODE == $pen21->REKENING_KODE)
			<tr>
				<td class="border-rincian kiri ">{{$pen21->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen21->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen21->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen21->total,0,',','.') }} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen21_p->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen21_p->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> {{ number_format($pen21_p->total-$pen21->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> 0 % </td>
			</tr>
			@endif
		@endforeach
		@endforeach
		<!-- 2.2 -->
		<tr>
			<td class="border-rincian kiri ">4.2.2</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Alokasi Umum </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen22,0,',','.') }} </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen22_p,0,',','.') }} </td>
			<td class="kanan border-rincian ">{{ number_format($totpen22_p-$totpen22,0,',','.') }} </td>
			<td class="kanan border-rincian ">0 % </td>
		</tr>	
		@foreach($pendapatan22 as $pen22)
		@foreach($pendapatan22_p as $pen22_p)
			@if($pen22_p->REKENING_KODE == $pen22->REKENING_KODE)
			<tr>
				<td class="border-rincian kiri ">{{$pen22->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen22->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen22->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen22->total,0,',','.') }} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen22_p->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen22_p->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> {{ number_format($pen22_p->total-$pen22->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> 0 % </td>
			</tr>
			@endif
		@endforeach
		@endforeach
		<!-- 2.3 -->
		<tr>
			<td class="border-rincian kiri ">4.2.3</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Alokasi Khusus </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen23,0,',','.') }} </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen23_p,0,',','.') }} </td>
			<td class="kanan border-rincian ">{{ number_format($totpen23_p-$totpen23,0,',','.') }} </td>
			<td class="kanan border-rincian ">0 % </td>
		</tr>
		@foreach($pendapatan23 as $pen23)
		@foreach($pendapatan23_p as $pen23_p)
			@if($pen23_p->REKENING_KODE == $pen22->REKENING_KODE)
			<tr>
				<td class="border-rincian kiri ">{{$pen23->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen23->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen23->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen23->total,0,',','.') }} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen23_p->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen23_p->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> {{ number_format($pen23_p->total-$pen23->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> 0 % </td>
			</tr>
			@endif
		@endforeach
		@endforeach
		@endif	
		<!-- tiga -->
		@if($pendapatan3 != 0)
		<tr>
			<td class="border-rincian kiri ">4.3</td>
			<td class="border-rincian "><b> &nbsp; Lain-lain Pendapatan yang Sah </b></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan3,0,',','.') }} </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan3_p,0,',','.') }} </td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan3_p-$pendapatan3,0,',','.') }} </td>
			<td class="border-rincian kanan border">0 % </td>
		</tr>
		<!-- 3.1 -->
		<tr>
			<td class="border-rincian kiri ">4.3.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Pendapatan Hibah </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen31,0,',','.') }} </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen31_p,0,',','.') }} </td>
			<td class="kanan border-rincian ">{{ number_format($totpen31_p-$totpen31,0,',','.') }} </td>
			<td class="kanan border-rincian ">0 % </td>
		</tr>	
		@foreach($pendapatan31 as $pen31)
		@foreach($pendapatan31_p as $pen31_p)
			@if($pen31_p->REKENING_KODE == $pen31->REKENING_KODE)
			<tr>
				<td class="border-rincian kiri ">{{$pen31->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen31->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen31->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen31->total,0,',','.') }} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen31_p->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen31_p->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> {{ number_format($pen31_p->total-$pen31->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> 0 % </td>
			</tr>
			@endif
		@endforeach
		@endforeach
		<!-- 3.3 -->
		<tr>
			<td class="border-rincian kiri ">4.3.3</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Bagi Hasil Pajak dari Provinsi dan Pemerintah Daerah Lainnya </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen33,0,',','.') }} </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen33_p,0,',','.') }} </td>
			<td class="kanan border-rincian ">{{ number_format($totpen33_p-$totpen33,0,',','.') }} </td>
			<td class="kanan border-rincian ">0 % </td>
		</tr>	
		@foreach($pendapatan33 as $pen33)
		@foreach($pendapatan33_p as $pen33_p)
			@if($pen33_p->REKENING_KODE == $pen33->REKENING_KODE)
			<tr>
				<td class="border-rincian kiri ">{{$pen33->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen33->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen33->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen33->total,0,',','.') }} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen33_p->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen33_p->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> {{ number_format($pen33_p->total-$pen33->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> 0 % </td>
			</tr>
			@endif
		@endforeach
		@endforeach
		<!-- 3.4 -->
		<tr>
			<td class="border-rincian kiri ">4.3.4</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Penyesuaian dan Otonomi Khusus </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>	
			<td class="kanan border-rincian ">{{ number_format($totpen34,0,',','.') }} </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>	
			<td class="kanan border-rincian ">{{ number_format($totpen34_p,0,',','.') }} </td>
			<td class="kanan border-rincian ">{{ number_format($totpen34_p-$totpen34,0,',','.') }} </td>
			<td class="kanan border-rincian ">0 %</td>
		</tr>
		@foreach($pendapatan34 as $pen34)
		@foreach($pendapatan34_p as $pen34_p)
			@if($pen34_p->REKENING_KODE == $pen34->REKENING_KODE)
			<tr>
				<td class="border-rincian kiri ">{{$pen34->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen34->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen34->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen34->total,0,',','.') }} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen34_p->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen34_p->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> {{ number_format($pen34_p->total-$pen34->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> 0 % </td>
			</tr>
			@endif
		@endforeach	
		@endforeach	
		<!-- 3.5 -->
		<tr>
			<td class="border-rincian kiri ">4.3.5</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Bantuan Keuangan dari Provinsi atau Pemerintah Daerah Lainnya </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen35,0,',','.') }} </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen35_p,0,',','.') }} </td>
			<td class="kanan border-rincian ">{{ number_format($totpen35_p,0,',','.') }} </td>
			<td class="kanan border-rincian ">0 % </td>
		</tr>	
		@foreach($pendapatan35 as $pen35)
		@foreach($pendapatan35_p as $pen35_p)
			@if($pen35_p->REKENING_KODE == $pen35->REKENING_KODE)
			<tr>
				<td class="border-rincian kiri ">{{$pen35->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen35->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen35->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen35->total,0,',','.') }} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen35_p->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen35_p->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> {{ number_format($pen35_p->total-$pen35->total,0,',','.') }} </td>
				<td class="kanan border-rincian "> 0 % </td>
			</tr>
			@endif
		@endforeach
		@endforeach
		@endif
	@endif	

	
	
	<tr class="border">
		<td class="border kanan" colspan="5"><b>Jumlah</b></td>
		<td class="border kanan"><b> 
			@php
				$total = $totpen11+$totpen12+$totpen13+$totpen14+
				         $totpen21+$totpen22+$totpen23+
						 $totpen31+$totpen33+$totpen34+$totpen35;
			@endphp
			{{ number_format($total,0,',','.') }}
		 </b></td>
		 <td class="border kanan" colspan="3"><b>Jumlah</b></td>
		 <td class="border kanan"><b> 
			@php
				$total_p = $totpen11_p+$totpen12_p+$totpen13_p+$totpen14_p+
				           $totpen21_p+$totpen22_p+$totpen23_p+
						   $totpen31_p+$totpen33_p+$totpen34_p+$totpen35_p;
			@endphp
			{{ number_format($total_p,0,',','.') }}
		 </b></td>
		 <td class="border kanan"><b> 
			@php
				$total_slh = $total_p-$total;
			@endphp
			{{ number_format($total_slh,0,',','.') }}
		 </b></td>
		 <td class="border kanan"><b> 
			0 %
		 </b></td>
	</tr>

	<tr class="border">
		<td class="border kanan" colspan="12"> </td>
	</tr>

	</tbody>	
</table>

<table class="ttd">
	<tr>
		<td width="75%"></td>
		<td>Bandung, 15 Maret 2018</td>
	</tr>
	<tr>
		<td></td>
		<td>{{ $skpd->SKPD_BENDAHARA }}</td>
	</tr>
	<tr>
		<td></td>
		<td><br><br><br><br><br></td>
	</tr>
	<tr>
		<td></td>
		<td><span style="border-bottom: 1px solid #000;padding-bottom: 1px;">{{ $skpd->SKPD_KEPALA }}</span></td>
	</tr>
	<tr>
		<td></td>
		<td>NIP. {{ $skpd->SKPD_KEPALA_NIP }}</td>
	</tr>
</table>
<table class="detail">	
	<tr class="border">
		<td width="18%">Keterangan</td>
		<td width="25%">: </td> 
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr class="border">
		<td>Tanggal Pembahasan</td>
		<td>: </td> 
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr class="border">
		<td>Catatan Hasil Pembahasan</td>
		<td>: </td> 
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr class="border">
		<td>1.</td>
		<td></td> 
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr class="border">
		<td>2.</td>
		<td></td> 
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr class="border">
		<td>Dst</td>
		<td></td> 
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
<table class="detail">
	<tr class="border">
		<td colspan="5" class="tengah"><b>Tim Anggaran Pemerintah Daerah : </b></td>
	</tr>
	<tr class="border">
		<td class="border tengah">No</td>
		<td class="border tengah">Nama</td> 
		<td class="border tengah">NIP</td>
		<td class="border tengah">Jabatan</td>
		<td class="border tengah">Tandatangan</td>
	</tr>
	<tr class="border">
		<td class="border">1.</td>
		<td class="border"></td> 
		<td class="border"></td>
		<td class="border"></td>
		<td class="border"></td>
	</tr>
	<tr class="border">
		<td class="border">2.</td>
		<td class="border"></td> 
		<td class="border"></td>
		<td class="border"></td>
		<td class="border"></td>
	</tr>
	<tr class="border">
		<td class="border">Dst</td>
		<td class="border"></td> 
		<td class="border"></td>
		<td class="border"></td>
		<td class="border"></td>
	</tr>
</table>

</div>
</body>
</html>