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
			<h4>DOKUMEN PELAKSANAAN ANGGARAN<br>SATUAN KERJA PERANGKAT DAERAH </h4>
		</td>
		<td rowspan="2" class="border">
			<h4>Formulir<br>DPA-SKPD 2.2</h4>
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
		<td colspan="10"><h4>Rekapitulasi Belanja Langsung 
		Berdasarkan Program dan Kegiatan</h4></td>
	</tr>
	<tr class="border headrincian">
		<td class="border" colspan="2">Kode</td>
		<td class="border" rowspan="2">Uraian</td>
		<td class="border" rowspan="2">Lokasi Kegiatan</td>
		<td class="border" rowspan="2">Target Kinerja (Kuantitatif)</td>
		<td class="border" rowspan="2">Sumber Dana</td>
		<td class="border" colspan="4">Triwulan</td>
	    <td class="border" rowspan="2">Jumlah</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border">Program</td>
		<td class="border">Kegiatan</td>
		<td class="border">I</td>
		<td class="border">II</td>
		<td class="border">III</td>
		<td class="border">IV</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
		<td class="border">5</td>
		<td class="border">6</td>
		<td class="border">7</td>
		<td class="border">8</td>
		<td class="border">9</td>
		<td class="border">10</td>
		<td class="border">11=7+8+9+10</td>
	</tr>
	<tr>
		<td class="border-rincian kiri"><b> </b></td>
		<td class="border-rincian"><b></b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
	</tr>	
	<tr>
		<td class="border-rincian kiri"><b> </b></td>
		<td class="border-rincian"><b>&nbsp;</b></td>
		<td class="border-rincian kanan border"><b>,00</b></td>
		<td class="border-rincian kanan border"><b>,00</b></td>
		<td class="border-rincian kanan border"><b>,00</b></td>
		<td class="border-rincian kanan border"><b>,00</b></td>
		<td class="border-rincian kanan border"><b>,00</b></td>
		<td class="border-rincian kanan border"><b>,00</b></td>
		<td class="border-rincian kanan border"><b>,00</b></td>
		<td class="border-rincian kanan border"><b>,00</b></td>
		<td class="border-rincian kanan border"><b>,00</b></td>
	</tr>	
	
	
	<tr class="border">
		<td class="border kanan" colspan="6"><b>Jumlah</b></td>
		<td class="border kanan"><b>,00</b></td>
		<td class="border kanan"><b>,00</b></td>
		<td class="border kanan"><b>,00</b></td>
		<td class="border kanan"><b>,00</b></td>
		<td class="border kanan"><b>,00</b></td>
	</tr>
	</tbody>	
</table>
<table class="ttd">
	<tr>
		<td width="75%"></td>
		<td>Bandung, {{$tgl}} {{$bln}} {{$tahun}}</td>
	</tr>
	<tr>
		<td></td>
		<td>Kepala {{ $skpd->SKPD_NAMA }}</td>
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
</div>
</body>
</html>