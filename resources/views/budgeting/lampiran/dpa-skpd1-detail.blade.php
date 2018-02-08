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


<table class="">
	<br><br><br><br><br><br>
	<br><br><br><br><br>
	<tr>
		<td class="tengah">
			<img src="{{ url('/') }}/assets/img/bandung.png" width="80px" style="margin:3px">
		</td>
	</tr>
	<br>
	<tr class="">
		<td class="" width="%">
			<h4><br>PEMERINTAH KOTA BANDUNG<br><br>
				DOKUMEN PELAKSANAAN ANGGARAN <br> 
				SATUAN KERJA PERANGKAT DAERAH <br> 
				(DPA SKPD)<br> <br>
			TAHUN ANGGARAN {{$tahun}} <br> <br> <br></h4> 
		</td>
		<td class="tengah">&nbsp;</td>
	</tr>
	<tr>
		<td class="tengah">
			<h1>PENDAPATAN</h1><br>
		</td>
	</tr>	
</table>
<table class="">
	<tr class="">
		<td>&nbsp; </td>
		<td>&nbsp; </td>
		<td width="200px">
			<table class="" width="100px">
				<tr class="">
					<td class=""><b>No DPA SKPD</b></td>
					<td class="border">{{ $urusan->URUSAN_KODE }}</td>
					<td class="border">{{ substr($skpd->SKPD_KODE,5,2) }}</td>
					<td class="border">00</td>
					<td class="border">00</td>
					<td class="border">4</td>
				</tr>
			</table>
		</td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
	</tr>
</table>
<table class="">
	<tr class="">
		<td>&nbsp; </td>
		<td>&nbsp; </td>
		<td width="500px">
			<table class="" width="100px">
				<tr class="">
					<td class=""><br><br>URUSAN PEMERINTAHAN</td>
					<td class=""><br><br> {{ $urusan->URUSAN_KODE }} {{ $urusan->URUSAN_NAMA }}</td>
				</tr>
				<tr class="">
					<td class="">ORGANISASI</td>
					<td class="">{{ $skpd->SKPD_KODE }} {{ $skpd->SKPD_NAMA }}</td>
				</tr>
				<tr class="">
					<td class=""><br>Pengguna Anggaran / <br>Kuasa Pengguna Anggaran</td>
					<td class=""></td>
				</tr>
				<tr class="">
					<td class="">Nama</td>
					<td class="">{{ $skpd->SKPD_KEPALA }}</td>
				</tr>
				<tr class="">
					<td class="">NIP</td>
					<td class="">{{ $skpd->SKPD_KEPALA_NIP }}</td>
				</tr>
				<tr class="">
					<td class="">Jabatan</td>
					<td class="">{{ $skpd->SKPD_BENDAHARA }}</td>
				</tr>
			</table>
		</td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
	</tr>
</table>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br>


<table class="header">
	<tr class="border">
		<td class="border" width="85%" rowspan="2">
			<h4>DOKUMEN PELAKSANAAN ANGGARAN<br>SATUAN KERJA PERANGKAT DAERAH </h4>
		</td>
		<td class="border" colspan="5">
			<h4>Nomor DPA SKPD</h4>
		</td>
		<td rowspan="2" class="border">
			<h4>Formulir<br>DPA-SKPD 1</h4>
		</td>
	</tr>
	<tr class="border">
		<td class="border"> <h4>{{ $urusan->URUSAN_KODE }}</h4> </td>
		<td class="border"> <h4>{{ substr($skpd->SKPD_KODE,5,2) }}</h4> </td>
		<td class="border"> <h4>00</h4> </td>
		<td class="border"> <h4>00</h4> </td>
		<td class="border"> <h4>4</h4> </td>
	</tr>
	<tr>
		<td colspan="8">
			<h4>Kota Bandung<br>Tahun Anggaran {{$tahun}}</h4>
		</td>
	</tr>
</table>
<table class="detail">
	<tr class="border">
		<td width="18%">Urusan Pemerintahan</td>
		<td width="19%">: {{ $urusan->URUSAN_KODE }}</td> 
		<td>{{ $urusan->URUSAN_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Organisasi</td>
		<td>: {{ $skpd->SKPD_KODE }}</td> 
		<td>{{ $skpd->SKPD_NAMA }}</td>
	</tr>
</table>

<table class="rincian">
	<tbody>
	<tr class="border">
		<td colspan="7"><h4>Rincian Dokumen Pelaksanaan Anggaran <br> 
		Pendapatan Satuan Kerja Perangkat Daerah</h4></td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" rowspan="2">Kode Rekening</td>
		<td class="border" rowspan="2">Uraian</td>
		<td class="border" colspan="3">Rincian Perhitungan</td>
		<td class="border" rowspan="2">Jumlah<br>(Rp)</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border">Volume</td>
		<td class="border">Satuan</td>
		<td class="border">Tarif / <br>Harga</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
		<td class="border">5</td>
		<td class="border">6 = (3x5)</td>
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


	<!-- pendapatan -->
	@if($pendapatan != 0)
	<tr>
		<td class="border-rincian kiri "> <br> 4</td>
		<td class="border-rincian "><b> <br> Pendapatan</b></td>
		<td class="border-rincian "></td>
		<td class="border-rincian "></td>
		<td class="border-rincian "></td>
		<td class="border-rincian kanan border"> <br> {{ number_format($pendapatan,0,',','.') }}</td>
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
		</tr>
		<tr>
			<td class="border-rincian kiri ">4.1.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Pajak Daerah </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen11,0,',','.') }}</td>
		</tr>
		@foreach($pendapatan11 as $pen11)
			<tr>
				<td class="border-rincian kiri ">{{$pen11->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen11->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen11->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen11->total,0,',','.') }} </td>
			</tr>
		@endforeach
		<!-- 12 -->
		<tr>
			<td class="border-rincian kiri ">4.1.2</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Retribusi Daerah </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen12,0,',','.') }}</td>
		</tr>	
		@foreach($pendapatan12 as $pen12)
			<tr>
				<td class="border-rincian kiri ">{{$pen12->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen12->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen12->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen12->total,0,',','.') }} </td>
			</tr>
		@endforeach
		<!-- 13 -->
		<tr>
			<td class="border-rincian kiri ">4.1.3</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen13,0,',','.') }} </td>
		</tr>	
		@foreach($pendapatan13 as $pen13)
			<tr>
				<td class="border-rincian kiri ">{{$pen13->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen13->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen13->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen13->total,0,',','.') }} </td>
			</tr>
		@endforeach
		<tr>
			<td class="border-rincian kiri ">4.1.4</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen14,0,',','.') }} </td>
		</tr>
		@foreach($pendapatan14 as $pen14)
			<tr>
				<td class="border-rincian kiri ">{{$pen14->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen14->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen14->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen14->total,0,',','.') }} </td>
			</tr>
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
		</tr>
		<!-- 2.1 -->
		<tr>
			<td class="border-rincian kiri ">4.2.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Bagi Hasil Pajak/Bagi Hasil Bukan Pajak </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen21,0,',','.') }} </td>
		</tr>	
		@foreach($pendapatan21 as $pen21)
			<tr>
				<td class="border-rincian kiri ">{{$pen21->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen21->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen21->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen21->total,0,',','.') }} </td>
			</tr>
		@endforeach
		<!-- 2.2 -->
		<tr>
			<td class="border-rincian kiri ">4.2.2</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Alokasi Umum </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen22,0,',','.') }} </td>
		</tr>	
		@foreach($pendapatan22 as $pen22)
			<tr>
				<td class="border-rincian kiri ">{{$pen22->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen22->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen22->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen22->total,0,',','.') }} </td>
			</tr>
		@endforeach
		<!-- 2.3 -->
		<tr>
			<td class="border-rincian kiri ">4.2.3</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Alokasi Khusus </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen23,0,',','.') }} </td>
		</tr>
		@foreach($pendapatan23 as $pen23)
			<tr>
				<td class="border-rincian kiri ">{{$pen23->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen23->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen23->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen23->total,0,',','.') }} </td>
			</tr>
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
		</tr>
		<!-- 3.1 -->
		<tr>
			<td class="border-rincian kiri ">4.3.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Pendapatan Hibah </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen23,0,',','.') }} </td>
		</tr>	
		@foreach($pendapatan31 as $pen31)
			<tr>
				<td class="border-rincian kiri ">{{$pen31->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen31->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen31->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen31->total,0,',','.') }} </td>
			</tr>
		@endforeach
		<!-- 3.3 -->
		<tr>
			<td class="border-rincian kiri ">4.3.3</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Bagi Hasil Pajak dari Provinsi dan Pemerintah Daerah Lainnya </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen23,0,',','.') }} </td>
		</tr>	
		@foreach($pendapatan33 as $pen33)
			<tr>
				<td class="border-rincian kiri ">{{$pen33->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen33->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen33->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen33->total,0,',','.') }} </td>
			</tr>
		@endforeach
		<!-- 3.4 -->
		<tr>
			<td class="border-rincian kiri ">4.3.4</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Penyesuaian dan Otonomi Khusus </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>	
			<td class="kanan border-rincian ">{{ number_format($totpen34,0,',','.') }} </td>
		</tr>
		@foreach($pendapatan34 as $pen34)
			<tr>
				<td class="border-rincian kiri ">{{$pen34->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen34->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen34->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen34->total,0,',','.') }} </td>
			</tr>
		@endforeach	
		<!-- 3.5 -->
		<tr>
			<td class="border-rincian kiri ">4.3.5</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Bantuan Keuangan dari Provinsi atau Pemerintah Daerah Lainnya </td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="border-rincian "></td>
			<td class="kanan border-rincian ">{{ number_format($totpen35,0,',','.') }} </td>
		</tr>	
		@foreach($pendapatan35 as $pen35)
			<tr>
				<td class="border-rincian kiri ">{{$pen35->REKENING_KODE}}</td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp; {{$pen35->REKENING_NAMA}} </td>
				<td class="border-rincian tengah ">{{$vol}}</td>
				<td class="border-rincian tengah">{{$satuan}}</td>
				<td class="border-rincian kanan">{{ number_format($pen35->total,0,',','.') }}</td>
				<td class="kanan border-rincian "> {{ number_format($pen35->total,0,',','.') }} </td>
			</tr>
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
	</tr>

	<tr class="border">
		<td class="border kanan" colspan="6"> </td>
	</tr>
	<tr class="border">
		<td class="border kiri" colspan="6"> &nbsp; &nbsp; &nbsp;  Rencana Pendapatan Per Triwulan</td>
	</tr>
	</tbody>	
</table>
<table class="ttd">
	<tr>
		<td class="tengah">Triwulan I</td>
		<td class="kiri">Rp. {{ number_format($akb_pen->tri1,0,',','.') }}</td>
		<td width="50%"> </td>
		<td>Bandung, 2 Januari 2018</td>
	</tr>
	<tr>
		<td class="tengah">Triwulan II</td>
		<td class="kiri">Rp. {{ number_format($akb_pen->tri2,0,',','.') }} </td>
		<td width="50%"> </td>
		<td><b>Pejabat Pengelola Keuangan Daerah</b></td>
	</tr>
	<tr>
		<td class="tengah">Triwulan III</td>
		<td class="kiri">Rp. {{ number_format($akb_pen->tri3,0,',','.') }} </td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="tengah">Triwulan IV</td>
		<td class="kiri">Rp. {{ number_format($akb_pen->tri4,0,',','.') }}</td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="kanan"><b>Jumlah</b></td>
		<td class="kiri"><b>Rp. {{ number_format($akb_pen->tri1+$akb_pen->tri2+$akb_pen->tri3+$akb_pen->tri4,0,',','.') }}</b></td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="kiri"> </td>
		<td class="kiri"> </td>
		<td width="50%"> <br><br><br><br><br><br></td>
		<td><span style="border-bottom: 1px solid #000;padding-bottom: 1px;">Drs. DADANG SUPRIATNA, MH <br><br></span>  NIP. 19610308 199103 1 009  </td>
	</tr>
</table>
</div>
</body>
</html>