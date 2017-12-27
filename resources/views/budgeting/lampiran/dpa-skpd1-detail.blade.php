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
		<td class="border"> <h4>x.xx</h4> </td>
		<td class="border"> <h4>xx</h4> </td>
		<td class="border"> <h4>00</h4> </td>
		<td class="border"> <h4>00</h4> </td>
		<td class="border"> <h4>4</h4> </td>
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
		<td colspan="6"><h4>Rincian Dokumen Pelaksanaan Anggaran <br> 
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
	
	@php $total=0; @endphp

	@foreach($pendapatan as $pen)
	<tr>
		<td class="border-rincian kiri border"><b>{{ $urusan->URUSAN_KODE }}.{{ $skpd->SKPD_KODE }}.1.{{$pen->rekening->REKENING_KODE}}</b></td>
		<td class="border-rincian border"> {{$pen->rekening->REKENING_NAMA}} </td>
		<td class="border-rincian border"> - </td>
		<td class="border-rincian border"> - </td>
		<td class="border-rincian kanan">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }},00 </td>
		<td class="border-rincian kanan">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }},00 </td>
		@php $total += $pen->PENDAPATAN_TOTAL; @endphp
	</tr>	
	@endforeach
	
	
	<tr class="border">
		<td class="border kanan" colspan="5"><b>Jumlah</b></td>
		<td class="border kanan"><b> {{ number_format($total,0,',','.') }},00 </b></td>
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
		<td class="kiri">Rp. </td>
		<td width="50%"> </td>
		<td>Bandung, {{$tgl}} {{$bln}} {{$tahun}}</td>
	</tr>
	<tr>
		<td class="tengah">Triwulan II</td>
		<td class="kiri">Rp. </td>
		<td width="50%"> </td>
		<td>Kepala {{ $skpd->SKPD_NAMA }}</td>
	</tr>
	<tr>
		<td class="tengah">Triwulan III</td>
		<td class="kiri">Rp. </td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="tengah">Triwulan IV</td>
		<td class="kiri">Rp. </td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="kanan">Jumlah</td>
		<td class="kiri">Rp. </td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="kiri"> </td>
		<td class="kiri"> </td>
		<td width="50%"> <br><br><br><br><br><br></td>
		<td><span style="border-bottom: 1px solid #000;padding-bottom: 1px;">{{ $skpd->SKPD_KEPALA }}<br><br>NIP. {{ $skpd->SKPD_KEPALA_NIP }}</span></td>
	</tr>
</table>
</div>
</body>
</html>