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
	<br><br><br><br><br><br><br>
	<br><br><br><br><br><br><br>
	<tr>
		<td class="tengah">
			<img src="{{ url('/') }}/assets/img/bandung.png" width="80px" style="margin:3px">
		</td>
	</tr>
	<br>
	<tr class="">
		<td class="" width="%">
			<h4><br>PEMERINTAH KOTA BANDUNG<br><br>
				DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN <br> 
				SATUAN KERJA PERANGKAT DAERAH <br> 
				(DPPA SKPD)<br> <br>
				{{ $skpd->SKPD_NAMA }} <br>
			TAHUN ANGGARAN {{$tahun}} <br> <br> <br></h4> 
		</td>
		<td rowspan="" class="">
		</td>
	</tr>
</table>
<table class="">
	<tr class="">
		<td>&nbsp; </td>
		<td>&nbsp; </td>
		<td width="500px">
			<table class="border" width="100px">
				<tr class="border">
					<td class="border tengah"><b>Kode</b></td>
					<td class="border tengah"><b>Nama Formulir</b></td>
				</tr>
				<tr class="border-rincian">
					<td class="border">DPPA-SKPD</td>
					<td class="border">Ringkasan Dokumen Pelaksanaan Anggaran Satuan Kerja Perangkat Daerah</td>
				</tr>
				<tr class="border">
					<td class="border">DPPA-SKPD1</td>
					<td class="border">Rincian Dokumen Pelaksanaan Anggaran Pendapatan Satuan Kerja Perangkat Daerah</td>
				</tr>
				<tr class="border">
					<td class="border">DPPA-SKPD 2.1</td>
					<td class="border">Rincian Dokumen Pelaksanaan Anggaran Belanja Tidak Langsung Satuan Kerja Perangkat Daerah</td>
				</tr>
				<tr class="border">
					<td class="border">DPPA-SKPD 2.2</td>
					<td class="border">Rekapitulasi Belanja Langsung Menurut Program dan Kegiatan Satuan Kerja Perangkat Daerah </td>
				</tr>
				<tr class="border">
					<td class="border">DPPA-SKPD 2.2.1</td>
					<td class="border">Rincian Dokumen Pelaksanaan Anggaran Belanja Langsung Program dan Per Kegiatan Satuan Kerja Perangkat Daerah</td>
				</tr>
				<tr class="border">
					<td class="border">DPPA-SKPD 3.1</td>
					<td class="border">Rincian Penerimaan Pembiayaan</td>
				</tr>
				<tr class="border">
					<td class="border">DPPA-SKPD 3.2</td>
					<td class="border">Rincian Pengeluaran Pembiayaan</td>
				</tr>
			</table>
		</td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
	</tr>
</table>

<p style="page-break-after: always;">&nbsp;</p>
<p style="page-break-before: always;">&nbsp;</p>

<table class="header">
	<tr class="border">
		<td class="border" width="85%">
			<h4>RINGKASAN DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN<br>SATUAN KERJA PERANGKAT DAERAH <br> TAHUN ANGGARAN {{$tahun}}</h4>
		</td>
		<td rowspan="2" class="border">
			<h4>Formulir<br>DPPA-SKPD</h4>
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
	<tr class="border headrincian">
		<td class="border" rowspan="2">Kode Rekening</td>
		<td class="border" rowspan="2">Uraian</td>
		<td class="border" colspan="2">Jumlah<br>(Rp)</td>
		<td class="border" colspan="2">Bertambah/<br>(Berkurang)</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border">Sebelum Pergeseran</td>
		<td class="border">Setelah Pergeseran</td>
		<td class="border">(Rp)</td>
		<td class="border">(%)</td>
	</tr>
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
		<td class="border">5</td>
		<td class="border">6</td>
	</tr>

	@php $total=0; @endphp

	<!-- pendapatan -->
	@if($pendapatan != 0)
	<tr>
		<td class="border-rincian kiri "> <br> 4</td>
		<td class="border-rincian "><b> <br> Pendapatan</b></td>
		<td class="border-rincian kanan border"> <br> {{ number_format($pendapatan,0,',','.') }}</td>
		<td class="border-rincian kanan border"> <br> {{ number_format($pendapatan_p,0,',','.') }}</td>
		<td class="border-rincian border kanan"> <br>
			@php $slh = $pendapatan_p-$pendapatan @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 </td>
		<td class="border-rincian border kanan"><br>
			@if($pendapatan != 0)
			@php $per = ($slh * 100)/$pendapatan @endphp
				{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
			@else
			-
			@endif	
		</td>
	</tr>
		<!-- satu -->
		@if($pendapatan1 != 0)
		<tr>
			<td class="border-rincian kiri ">4.1</td>
			<td class="border-rincian "><b> &nbsp; Pandapatan Asli Daerah</b></td>
			<td class="border-rincian kanan ">{{ number_format($pendapatan1,0,',','.') }} </td>
			<td class="border-rincian kanan ">{{ number_format($pendapatan1_p,0,',','.') }} </td>
			<td class="border-rincian kanan"> 
			@php $slh = $pendapatan1_p-$pendapatan1 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 </td>
		<td class="border-rincian kanan">
			@if($pendapatan1 != 0)
			@php $per = ($slh * 100)/$pendapatan1 @endphp
				{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
			@else
			-
			@endif	
		</td>
		</tr>

		<tr>
			<td class="border-rincian kiri ">4.1.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Pajak Daerah </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan11,0,',','.') }} </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan11_p,0,',','.') }} </td>
			<td class="border-rincian kanan"> 
			@php $slh = $pendapatan11_p-$pendapatan11 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($pendapatan11 != 0)
				@php $per = ($slh * 100)/$pendapatan11 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.1.2</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Retribusi Daerah </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan12,0,',','.') }} </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan12_p,0,',','.') }} </td>
			<td class="border-rincian kanan"> 
			@php $slh = $pendapatan12_p-$pendapatan12 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($pendapatan12 != 0)
				@php $per = ($slh * 100)/$pendapatan12 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.1.3</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan13,0,',','.') }} </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan13_p,0,',','.') }} </td>
			<td class="border-rincian kanan"> 
			@php $slh = $pendapatan13_p-$pendapatan13 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($pendapatan13 != 0)
				@php $per = ($slh * 100)/$pendapatan13 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.1.4</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan14,0,',','.') }} </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan14_p,0,',','.') }} </td>
			<td class="border-rincian kanan"> 
			@php $slh = $pendapatan14_p-$pendapatan14 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($pendapatan14 != 0)
				@php $per = ($slh * 100)/$pendapatan14 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>
		@endif
		<!-- dua -->
		@if($pendapatan2 != 0)
		<tr>
			<td class="border-rincian kiri ">4.2</td>
			<td class="border-rincian "><b> &nbsp; Dana Perimbangan </b></td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan2,0,',','.') }} </td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan2_p,0,',','.') }} </td>
			<td class="border-rincian kanan border"> 
			@php $slh = $pendapatan2_p-$pendapatan2 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan border">
				@if($pendapatan2 != 0)
				@php $per = ($slh * 100)/$pendapatan2 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>

		<tr>
			<td class="border-rincian kiri ">4.2.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Bagi Hasil Pajak/Bagi Hasil Bukan Pajak </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan21,0,',','.') }} </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan21_p,0,',','.') }} </td>
			<td class="border-rincian kanan"> 
			@php $slh = $pendapatan21_p-$pendapatan21 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($pendapatan21 != 0)
				@php $per = ($slh * 100)/$pendapatan21 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.2.2</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Alokasi Umum </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan22,0,',','.') }} </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan22_p,0,',','.') }} </td>
			<td class="border-rincian kanan"> 
			@php $slh = $pendapatan22_p-$pendapatan22 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($pendapatan22 != 0)
				@php $per = ($slh * 100)/$pendapatan22 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.2.3</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Alokasi Khusus </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan23,0,',','.') }} </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan23_p,0,',','.') }} </td>
			<td class="border-rincian kanan"> 
			@php $slh = $pendapatan23_p-$pendapatan23 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($pendapatan23 != 0)
				@php $per = ($slh * 100)/$pendapatan23 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>
		@endif	
		<!-- tiga -->
		@if($pendapatan3 != 0)
		<tr>
			<td class="border-rincian kiri ">4.3</td>
			<td class="border-rincian "><b> &nbsp; Lain-lain Pendapatan yang Sah </b></td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan3,0,',','.') }} </td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan3_p,0,',','.') }} </td>
			<td class="border-rincian kanan border"> 
			@php $slh = $pendapatan3_p-$pendapatan3 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan border">
				@if($pendapatan3 != 0)
				@php $per = ($slh * 100)/$pendapatan3 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>

		<tr>
			<td class="border-rincian kiri ">4.3.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Pendapatan Hibah </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan31,0,',','.') }} </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan31_p,0,',','.') }} </td>
			<td class="border-rincian kanan"> 
			@php $slh = $pendapatan31_p-$pendapatan31 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($pendapatan31 != 0)
				@php $per = ($slh * 100)/$pendapatan31 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.3.3</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Bagi Hasil Pajak dari Provinsi dan Pemerintah Daerah Lainnya </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan33,0,',','.') }} </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan33_p,0,',','.') }} </td>
			<td class="border-rincian kanan"> 
			@php $slh = $pendapatan33_p-$pendapatan33 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($pendapatan33 != 0)
				@php $per = ($slh * 100)/$pendapatan33 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.3.4</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Penyesuaian dan Otonomi Khusus </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan34,0,',','.') }} </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan34_p,0,',','.') }} </td>
			<td class="border-rincian kanan"> 
			@php $slh = $pendapatan34_p-$pendapatan34 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($pendapatan34 != 0)
				@php $per = ($slh * 100)/$pendapatan34 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.3.5</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Bantuan Keuangan dari Provinsi atau Pemerintah Daerah Lainnya </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan35,0,',','.') }} </td>
			<td class="border-rincian kanan">{{ number_format($pendapatan35_p,0,',','.') }} </td>
			<td class="border-rincian kanan"> 
			@php $slh = $pendapatan35_p-$pendapatan35 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($pendapatan35 != 0)
				@php $per = ($slh * 100)/$pendapatan35 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>	
		@endif
	@endif	

	<tr>
		<td class="border-rincian kiri ">  <br> 5 </td>
		<td class="border-rincian "><b> <br> Belanja</b></td>
		<td class="border-rincian kanan border"> <br> {{ number_format($btl+$bl,0,',','.') }},00</td>
		<td class="border-rincian kanan border"> <br> {{ number_format($btl_p+$bl_p,0,',','.') }},00</td>
		<td class="border-rincian kanan border"> <br>
			@php $slh = ($btl_p+$bl_p)-($btl+$bl) @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan border"> <br>
				@if(($btl+$bl) != 0)
				@php $per = ($slh * 100)/($btl+$bl) @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
	</tr>
	<!-- belanja tidak langsung -->
	@if($btl !=0)
	<tr>
		<td class="border-rincian kiri ">5.1</td>
		<td class="border-rincian "><b> &nbsp; Belanja Tidak Langsung</b></td>
		<td class="border-rincian kanan border">{{ number_format($btl,0,',','.') }},00</td>
		<td class="border-rincian kanan border">{{ number_format($btl_p,0,',','.') }},00</td>
		<td class="border-rincian kanan border"> 
			@php $slh = $btl_p-$btl @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan border">
				@if($btl != 0)
				@php $per = ($slh * 100)/$btl @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
	</tr>

	<tr>
		<td class="border-rincian kiri ">5.1.1</td>
		<td class="border-rincian ">&nbsp; &nbsp; Belanja Pegawai</td>
		<td class="border-rincian kanan ">{{ number_format($btl1,0,',','.') }},00</td>
		<td class="border-rincian kanan ">{{ number_format($btl1_p,0,',','.') }},00</td>
		<td class="border-rincian kanan"> 
			@php $slh = $btl1_p-$btl1 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($btl1 != 0)
				@php $per = ($slh * 100)/$btl1 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
	</tr>
	<tr>
		<td class="border-rincian kiri ">5.1.3</td>
		<td class="border-rincian ">&nbsp; &nbsp; Belanja Subsidi </td>
		<td class="border-rincian kanan ">{{ number_format($btl2,0,',','.') }},00</td>
		<td class="border-rincian kanan ">{{ number_format($btl2_p,0,',','.') }},00</td>
		<td class="border-rincian kanan"> 
			@php $slh = $btl2_p-$btl2 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($btl2 != 0)
				@php $per = ($slh * 100)/$btl2 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
	</tr>
	<tr>
		<td class="border-rincian kiri ">5.1.4</td>
		<td class="border-rincian ">&nbsp; &nbsp; Belanja Hibah </td>
		<td class="border-rincian kanan ">{{ number_format($btl3,0,',','.') }},00</td>
		<td class="border-rincian kanan ">{{ number_format($btl3_p,0,',','.') }},00</td>
		<td class="border-rincian kanan"> 
			@php $slh = $btl3_p-$btl3 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($btl3 != 0)
				@php $per = ($slh * 100)/$btl3 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
	</tr>
	<tr>
		<td class="border-rincian kiri ">5.1.7</td>
		<td class="border-rincian ">&nbsp; &nbsp; Belanja Bantuan Keuangan Kepada Provinsi/kabupaten/kota Dan Pemerintahan Desa </td>
		<td class="border-rincian kanan ">{{ number_format($btl4,0,',','.') }},00</td>
		<td class="border-rincian kanan ">{{ number_format($btl4_p,0,',','.') }},00</td>
		<td class="border-rincian kanan"> 
			@php $slh = $btl4_p-$btl4 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($btl4 != 0)
				@php $per = ($slh * 100)/$btl4 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
	</tr>
	<tr>
		<td class="border-rincian kiri ">5.1.8</td>
		<td class="border-rincian ">&nbsp; &nbsp; Belanja Tidak Terduga </td>
		<td class="border-rincian kanan ">{{ number_format($btl5,0,',','.') }},00</td>
		<td class="border-rincian kanan ">{{ number_format($btl5_p,0,',','.') }},00</td>
		<td class="border-rincian kanan"> 
			@php $slh = $btl5_p-$btl5 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($btl5 != 0)
				@php $per = ($slh * 100)/$btl5 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
	</tr>
	@endif

	<!-- belanja langsung-->
	@if($bl !=0)
	<tr>
		<td class="border-rincian kiri ">5.2</td>
		<td class="border-rincian "><b> &nbsp; Belanja Langsung</b></td>
		<td class="border-rincian kanan border">{{ number_format($bl,0,',','.') }},00</td>
		<td class="border-rincian kanan border">{{ number_format($bl_p,0,',','.') }},00</td>
		<td class="border-rincian kanan">
			@php $slh = $bl_p-$bl @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($bl != 0)
				@php $per = ($slh * 100)/$bl @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
	</tr>
		<tr>
			<td class="border-rincian kiri ">5.2.1</td>
			<td class="border-rincian ">&nbsp; &nbsp; Belanja Pegawai</td>
			<td class="border-rincian kanan ">{{ number_format($bl1,0,',','.') }},00</td>
			<td class="border-rincian kanan ">{{ number_format($bl1_p,0,',','.') }},00</td>
			<td class="border-rincian kanan"> 
			@php $slh = $bl1_p-$bl1 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($bl1 != 0)
				@php $per = ($slh * 100)/$bl1 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>
		<tr>
			<td class="border-rincian kiri ">5.2.2</td>
			<td class="border-rincian ">&nbsp; &nbsp; Belanja Barang dan Jasa</td>
			<td class="border-rincian kanan ">{{ number_format($bl2,0,',','.') }},00</td>
			<td class="border-rincian kanan ">{{ number_format($bl2_p,0,',','.') }},00</td>
			<td class="border-rincian kanan"> 
			@php $slh = $bl2_p-$bl2 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($bl2 != 0)
				@php $per = ($slh * 100)/$bl2 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>
		<tr>
			<td class="border-rincian kiri ">5.2.3</td>
			<td class="border-rincian ">&nbsp; &nbsp; Belanja Modal</td>
			<td class="border-rincian kanan ">{{ number_format($bl3,0,',','.') }},00</td>
			<td class="border-rincian kanan ">{{ number_format($bl3_p,0,',','.') }},00</td>
			<td class="border-rincian kanan"> 
			@php $slh = $bl3_p-$bl3 @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($bl3 != 0)
				@php $per = ($slh * 100)/$bl3 @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>
	@endif

		<tr>
			<td class="border-rincian kiri"></td>
			<td class="kanan"><b>Surplus / (Defisit)</b></td>
			<td class="border kanan"><b>
				@php $tot = $pendapatan-($btl+$bl); @endphp
				@if($tot < 0)
				 ({{ number_format(trim($tot,"-"),0,',','.') }})
				@else
				 {{ number_format($tot,0,',','.') }}
				@endif  
			</b></td>
			<td class="border kanan"><b>
				@php $tot_p = $pendapatan_p-($btl_p+$bl_p); @endphp
				@if($tot_p < 0)
				 ({{ number_format(trim($tot_p,"-"),0,',','.') }})
				@else
				 {{ number_format($tot_p,0,',','.') }}
				@endif 
			</b></td>
			<td class="border kanan"><b>
				@php $tot_sls = $tot_p-$tot; @endphp
				@if($tot_sls < 0)
				 ({{ number_format(trim($tot_sls,"-"),0,',','.') }})
				@else
				 {{ number_format($tot_sls,0,',','.') }}
				@endif  
			</b></td>
			<td class="border kanan"><b>
			  @if($tot != 0)
				@php $per = ($tot_sls * 100)/$tot @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</b></td>
		</tr>


	@if($pem != 0 || $peng!=0)	
	<tr>
		<td class="border-rincian kiri "> <br> 6</td>
		<td class="border-rincian "><b> <br> Pembiayaan Daerah</b></td>
		<td class="border-rincian kanan"> <br> {{ number_format($pem+$peng,0,',','.') }}</td>
		<td class="border-rincian kanan"> <br> {{ number_format($pem_p+$peng_p,0,',','.') }}</td>
		<td class="border-rincian kanan"> <br>
			@php $slh = ($pem_p+$peng_p)-($pem+$peng) @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan"> <br>
				@if(($pem+$peng) != 0)
				@php $per = ($slh * 100)/($pem+$peng) @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
	</tr>

		<tr>
			<td class="border-rincian kiri ">6.1</td>
			<td class="border-rincian "><b> &nbsp; Penerimaan Pembiayaan Daerah</b></td>
			<td class="border-rincian kanan border">{{ number_format($pem,0,',','.') }} </td>
			<td class="border-rincian kanan border">{{ number_format($pem_p,0,',','.') }} </td>
			<td class="border-rincian kanan border">
			@php $slh = $pem_p-$pem @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan border">
				@if($pem != 0)
				@php $per = ($slh * 100)/$pem @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>
		<tr>
			<td class="border-rincian kiri ">6.1.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; Sisa Lebih Perhitungan Anggaran Tahun Anggaran Sebelumnya </td>
			<td class="border-rincian kanan">{{ number_format($pem,0,',','.') }} </td>
			<td class="border-rincian kanan">{{ number_format($pem_p,0,',','.') }} </td>
			<td class="border-rincian kanan">
			@php $slh = $pem_p-$pem @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($pem != 0)
				@php $per = ($slh * 100)/$pem @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>
		<tr>
			<td class="border-rincian kiri ">6.2</td>
			<td class="border-rincian "><b> &nbsp; Pengeluaran Pembiayaan Daerah</b></td>
			<td class="border-rincian kanan border">{{ number_format($peng,0,',','.') }} </td>
			<td class="border-rincian kanan border">{{ number_format($peng_p,0,',','.') }} </td>
			<td class="border-rincian kanan ">
			@php $slh = $peng_p-$peng @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan ">
				@if($peng != 0)
				@php $per = ($slh * 100)/$peng @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>
		<tr>
			<td class="border-rincian kiri ">6.2.1</td>
			<td class="border-rincian ">&nbsp; &nbsp; Penyertaan Modal (Investasi) Pemerintah Daerah </td>
			<td class="border-rincian kanan">{{ number_format($peng,0,',','.') }} </td>
			<td class="border-rincian kanan">{{ number_format($peng_p,0,',','.') }} </td>
			<td class="border-rincian kanan">
			@php $slh = $peng_p-$peng @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		 	</td>
			<td class="border-rincian kanan">
				@if($peng != 0)
				@php $per = ($slh * 100)/$peng @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</td>
		</tr>

	<tr>
			<td class="border-rincian kiri"></td>
			<td class="kanan"><b>Pembiayaan Netto</b></td>
			<td class="border kanan"><b>
				@php $netto = $pem - $peng; @endphp
				@if($netto < 0)
				 ({{ number_format(trim($netto,"-"),0,',','.') }})
				@else
				 {{ number_format($netto,0,',','.') }}
				@endif  
			,00</b></td>
			<td class="border kanan"><b>
				@php $netto_p = $pem_p - $peng_p; @endphp
				@if($netto_p < 0)
				 ({{ number_format(trim($netto_p,"-"),0,',','.') }})
				@else
				 {{ number_format($netto_p,0,',','.') }}
				@endif  
			,00</b></td>
			<td class="border kanan"><b>
				@php $netto_sls = ($pem_p - $peng_p)-($pem - $peng); @endphp
				@if($netto_sls < 0)
				 ({{ number_format(trim($netto_sls,"-"),0,',','.') }})
				@else
				 {{ number_format($netto_sls,0,',','.') }}
				@endif  
			,00</b></td>
			<td class="border kanan"><b>
				@if($netto != 0)
				@php $per = ($netto_sls * 100)/$netto @endphp
					{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
				@else
				-
				@endif	
			</b></td>
		</tr>	

		<tr>
			<td class="border-rincian kiri"></td>
			<td class="kanan"><b>Sisa Lebih Pembiayaan Anggaran Tahun Berkenaan</b></td>
			<td class="border kanan"><b>
				@php $sisa = $tot - $netto; @endphp
				@if($sisa < 0)
				 ({{ number_format(trim($sisa,"-"),0,',','.') }})
				@else
				 {{ number_format($sisa,0,',','.') }}
				@endif  
			,00</b></td>
			<td class="border kanan"><b>
				@php $sisa_p = $tot_p - $netto_p; @endphp
				@if($sisa_p < 0)
				 ({{ number_format(trim($sisa_p,"-"),0,',','.') }})
				@else
				 {{ number_format($sisa_p,0,',','.') }}
				@endif  
			,00</b></td>
			<td class="border kanan"><b>
				@php $sisa_sls = $tot_sls - $netto_sls; @endphp
				@if($sisa_sls < 0)
				 ({{ number_format(trim($sisa_sls,"-"),0,',','.') }})
				@else
				 {{ number_format($sisa_sls,0,',','.') }}
				@endif  
			,00</b></td>
			<td class="border kanan"><b>
				0 % 
			</b></td>
		</tr>
	@endif			

	</tbody>	
</table>
<table class="detail">
	<tr class="border">
		<td colspan="7" class="tengah"><b>Rencana Pelaksanaan Anggaran <br> Satuan Kerja Perangkat Daerah Per Triwulan </b></td>
	</tr>
	<tr class="border">
		<td class="border tengah" rowspan="2">No</td>
		<td class="border tengah" rowspan="2">Uraian</td> 
		<td class="border tengah" colspan="5">Triwulan</td>
	</tr>
	<tr class="border">
		<td class="border tengah">I</td>
		<td class="border tengah">II</td> 
		<td class="border tengah">III</td>
		<td class="border tengah">IV</td>
		<td class="border tengah">Jumlah</td>
	</tr>
	<tr class="border">
		<td class="border tengah">1</td>
		<td class="border tengah">2</td> 
		<td class="border tengah">3</td>
		<td class="border tengah">4</td>
		<td class="border tengah">5</td>
		<td class="border tengah">6</td>
		<td class="border tengah">7=3+4+5+6</td>
	</tr>
	<tr class="border">
		<td class="border">1</td>
		<td class="border">Pendapatan</td> 
		<td class="border kanan">{{ number_format($akb_pend->tri1,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pend->tri2,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pend->tri3,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pend->tri4,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pend->tri1+$akb_pend->tri2+$akb_pend->tri3+$akb_pend->tri4,0,',','.') }}</td>
	</tr>
	<tr class="border">
		<td class="border">2.1</td>
		<td class="border">Belanja Tidak Langsung</td> 
		<td class="border kanan">{{ number_format($akb_btl->tri1,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_btl->tri2,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_btl->tri3,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_btl->tri4,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_btl->tri1+$akb_btl->tri2+$akb_btl->tri3+$akb_btl->tri4,0,',','.') }}</td>	
	</tr>
	<tr class="border">
		<td class="border">2.2</td>
		<td class="border">Belanja Langsung</td> 
		<td class="border kanan">{{ number_format($akb_bl->tri1,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_bl->tri2,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_bl->tri3,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($bl_p-($akb_bl->tri1+$akb_bl->tri2+$akb_bl->tri3),0,',','.') }}</td>
		<!-- <td class="border kanan">{{ number_format($akb_bl->tri1+$akb_bl->tri2+$akb_bl->tri3+$akb_bl->tri4,0,',','.') }}</td> -->
		<td class="border kanan">{{ number_format($bl_p,0,',','.') }}</td>
	</tr>
	<tr class="border">
		<td class="border">3.1</td>
		<td class="border">Penerimaan Pembiayaan</td> 
		<td class="border kanan">{{ number_format($akb_pem->tri1,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pem->tri2,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pem->tri3,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pem->tri4,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pem->tri1+$akb_pem->tri2+$akb_pem->tri3+$akb_pem->tri4,0,',','.') }}</td>
	</tr>
	<tr class="border">
		<td class="border">3.2</td>
		<td class="border">Pengeluaran Pembiayaan</td> 
		<td class="border kanan">{{ number_format($akb_peng->tri1,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_peng->tri2,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_peng->tri3,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_peng->tri4,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_peng->tri1+$akb_peng->tri2+$akb_peng->tri3+$akb_peng->tri4,0,',','.') }}</td>
	</tr>
</table>
<table class="ttd">
	<tr>
		<td width="75%"></td>
		<td>Bandung, {{(isset($tgl_ttd) ? (strlen($tgl_ttd)>0?$tgl_ttd:$tgl.' '.$bln.' '.$thn) : '31 Mei 2018')}}</td>
	</tr>
	<tr>
		<td></td>
		<td>Menyetujui<br>{{(isset($jabatan_ttd) ? (strlen($jabatan_ttd)>0?$jabatan_ttd:'Pj. SEKRETARIS DAERAH') : 'Pj. SEKRETARIS DAERAH')}}</td>
	</tr>
	<tr>
		<td></td>
		<td><br><br><br><br><br></td>
	</tr>
	<tr>
		<td></td>
		<!--<td><span style="border-bottom: 1px solid #000;padding-bottom: 1px;">
		Dr. Hj. Evi Syaefini Shaleha, M.Pd</span></td>-->
                <td><span style="border-bottom: 1px solid #000;padding-bottom: 1px;">
                {{(isset($nama_ttd) ? (strlen($nama_ttd)>0?$nama_ttd:'Drs. DADANG SUPRIATNA, MH ') : 'Drs. DADANG SUPRIATNA, MH ')}}</span></td>
	</tr>
	<tr>
		<td></td>
		<!--<td>NIP. 19581228 197804 2 002 </td>-->
                <td>{{(isset($nip_ttd) ? (strlen($nip_ttd)>0?'NIP. '.$nip_ttd:'NIP. 19610308 199103 1 009 ') : 'NIP. 19610308 199103 1 009 ')}}</td>
	</tr>
</table>
</div>
</body>
</html>
