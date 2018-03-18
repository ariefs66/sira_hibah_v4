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
				(DPPA SKPD)<br> <br>
			TAHUN ANGGARAN {{$tahun}} <br> <br> <br></h4> 		
		</td>
		<td class="tengah">&nbsp;</td>
	</tr>
	<tr>
		<td class="tengah">
			<h1>PENERIMAAN PEMBIAYAAN</h1><br>
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
					<td class=""><b>No DPPA SKPD</b></td>
					<td class="border">{{ $urusan->URUSAN_KODE }}</td>
					<td class="border">{{ substr($skpd->SKPD_KODE,5,2) }}</td>
					<td class="border">00</td>
					<td class="border">00</td>
					<td class="border">6</td>
					<td class="border">1</td>
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
					<td class=""><br><br>{{ $urusan->URUSAN_KODE }} {{ $urusan->URUSAN_NAMA }}</td>
				</tr>
				<tr class="">
					<td class="">ORGANISASI</td>
					<td class="">{{ $skpd->SKPD_KODE }} {{ $skpd->SKPD_NAMA }}</td>
				</tr>
				<tr class="">
					<td class="">SUB UNIT ORGANISASI</td>
					<td class="">{{ $skpd->SKPD_KODE }}.{{ $skpd->SUB_KODE }} {{ $skpd->SUB_NAMA }} </td>
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

<p style="page-break-after: always;">&nbsp;</p>
<p style="page-break-before: always;">&nbsp;</p>

<table class="header">
	<tr class="border">
		<td class="border" width="85%" rowspan="2">
			<h4>DOKUMEN PELAKSANAAN ANGGARAN<br>SATUAN KERJA PERANGKAT DAERAH </h4>
		</td>
		<td class="border" colspan="6">
			<h4>Nomor DPPA SKPD</h4>
		</td>
		<td rowspan="2" class="border">
			<h4>Formulir<br>DPPA-SKPD 3.1</h4>
		</td>
	</tr>
	<tr class="border">
		<td class="border"> <h4>{{ $urusan->URUSAN_KODE }}</h4> </td>
		<td class="border"> <h4>{{ substr($skpd->SKPD_KODE,5,2) }}</h4> </td>
		<td class="border"> <h4>00</h4> </td>
		<td class="border"> <h4>00</h4> </td>
		<td class="border"> <h4>6</h4> </td>
		<td class="border"> <h4>1</h4> </td>
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
		<td colspan="10"><h4>Rincian Penerimaan Pembiayaan</h4></td>
	</tr>
	<tr class="border headrincian">
		<td class="border" rowspan="2">Kode Rekening</td>
		<td class="border" rowspan="2">Uraian</td>
		<td class="border" colspan="2">Jumlah (Rp)</td>	
		<td class="border" colspan="2">Bertambah / (Berkurang)</td>
	</tr>
	<tr class="border headrincian">
		<td class="border">Sebelum @if($status=='pergeseran')Pergeseran @else Perubahan @endif</td>
		<td class="border">Sesudah @if($status=='pergeseran')Pergeseran @else Perubahan @endif</td>	
		<td class="border">(Rp)</td>
		<td class="border">%</td>
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

	@foreach($pembiayaan as $pem)
		@php $total += $pem->PEMBIAYAAN_TOTAL; @endphp
	@endforeach


	@php $total_p=0; @endphp

	@foreach($pembiayaan_p as $pem)
		@php $total_p += $pem->PEMBIAYAAN_TOTAL; @endphp
	@endforeach
	<tr>
		<td class="border-rincian kiri border"> 6</td>
		<td class="border-rincian border"> <b>Pembiayaan Daerah</b>  </td>
		<td class="border-rincian kanan border"><b>{{ number_format($total,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($total_p,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>
			@php $slh = $total_p-$total @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		</b></td>
		<td class="border-rincian kanan border"><b>
			@if($total !=0)
			@php $per = ($slh * 100)/$total @endphp
			{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
			@else
			-
			@endif
		</b></td>
	</tr>

	<tr>
		<td class="border-rincian kiri border"> 6.1</td>
		<td class="border-rincian border"> <b> &nbsp; Penerimaan Pembiayaan Daerah</b>  </td>
		<td class="border-rincian kanan border"><b>{{ number_format($total,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($total_p,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>
			@php $slh = $total_p-$total @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		</b></td>
		<td class="border-rincian kanan border"><b>
			@if($total !=0)
			@php $per = ($slh * 100)/$total @endphp
			{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
			@else
			-
			@endif
		</b></td>
	</tr>

	@foreach($pembiayaan as $pem)
	@foreach($pembiayaan_p as $pem_p)
	@if($pem_p->rekening->REKENING_KODE == $pem->rekening->REKENING_KODE)
	<tr>
		<td class="border-rincian kiri border"> {{$pem->rekening->REKENING_KODE}} </td>
		<td class="border-rincian border"> &nbsp; &nbsp; {{$pem->rekening->REKENING_NAMA}} </td>
		<td class="border-rincian kanan border">{{ number_format($pem->PEMBIAYAAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan border">{{ number_format($pem_p->PEMBIAYAAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan border">
			@php $slh1 = $pem_p->PEMBIAYAAN_TOTAL-$pem->PEMBIAYAAN_TOTAL @endphp
			@if($slh1 < 0)
				({{ trim(number_format($slh1,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh1,0,',','.'),"-") }}
			@endif
		</td>
		<td class="border-rincian kanan border">
			@if($pem->PEMBIAYAAN_TOTAL != 0)
			@php $per = ($slh1 * 100)/$pem->PEMBIAYAAN_TOTAL @endphp
			{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
			@else
			-
			@endif
		</td>
	</tr>
	@endif	
	@endforeach		
	@endforeach		
	
	
	<tr class="border">
		<td class="border kanan" colspan="2"><b>Jumlah</b></td>
		<td class="border kanan"><b>{{ number_format($total,0,',','.') }}</b></td>
		<td class="border kanan"><b>{{ number_format($total_p,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>
			@php $slh = $total_p-$total @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		</b></td>
		<td class="border-rincian kanan border"><b>
			@if($total != 0)
			@php $per = ($slh * 100)/$total @endphp
			{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
			@else
			-
			@endif
		</b></td>
	</tr>
	
	<tr class="border">
		<td class="border kiri" colspan="6"> &nbsp; &nbsp; &nbsp; <b> Rencana Penerimaan Per Triwulan </b></td>
	</tr>
	</tbody>	
</table>
<table class="ttd">
	<tr>
		<td class="tengah">Triwulan I</td>
		<td class="kiri">Rp. {{ number_format($akb_pem->tri1,0,',','.') }}</td>
		<td width="50%"> </td>
		<td>Bandung, 20 Maret 2018</td>
	</tr>
	<tr>
		<td class="tengah">Triwulan II</td>
		<td class="kiri">Rp. {{ number_format($akb_pem->tri2,0,',','.') }}</td>
		<td width="50%"> </td>
		<td><b>Plh Pejabat Pengelola Keuangan Daerah</b></td>
	</tr>
	<tr>
		<td class="tengah">Triwulan III</td>
		<td class="kiri">Rp. {{ number_format($akb_pem->tri3,0,',','.') }}</td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="tengah">Triwulan IV</td>
		<td class="kiri">Rp. {{ number_format($akb_pem->tri4,0,',','.') }}</td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="kanan"><b>Jumlah</b></td>
		<td class="kiri"><b>Rp. {{ number_format($akb_pem->tri1+$akb_pem->tri2+$akb_pem->tri3+$akb_pem->tri4,0,',','.') }}</b></td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="kiri"> </td>
		<td class="kiri"> </td>
		<td width="50%"> <br><br><br><br><br><br></td>
<!-- 		<td><span style="border-bottom: 1px solid #000;padding-bottom: 1px;">Drs. DADANG SUPRIATNA, MH <br> <br> </span> NIP. 19610308 199103 1 009 </td>
 -->		<td><span style="border-bottom: 1px solid #000;padding-bottom: 1px;">Drs. R Budhi Rukmana, M.AP <br><br> </span> NIP. 19690712 198910 1 001  </td>

	</tr>
</table>
</div>
</body>
</html>