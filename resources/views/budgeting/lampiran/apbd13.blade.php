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
	<h5>LAMPIRAN XIII &nbsp; &nbsp; &nbsp; Rancangan Peraturan Daerah</h5>
	<h5>NOMOR : </h5>
	<h5>TANGGAL :</h5>
	</div>
	<br>
<table class="header">
	<tr class="border">
		<td class="border">
			<h4>PEMERINTAH KOTA BANDUNG</h4>
			<h3>DAFTAR PINJAMAN DAERAH</h3>
			<h4>TAHUN ANGGARAN {{ $tahun }}</h4>
		</td>
	</tr>	
</table>
<br>
<table class="rincian">
	<tbody>
	<tr class="border headrincian">
		<td class="border tengah" rowspan="2">No </td>
		<td class="border tengah" rowspan="2">Sumber Pinjaman Daerah</td>
		<td class="border tengah" rowspan="2">Dasar Hukum Pinjaman/Obligasi</td>
		<td class="border tengah" rowspan="2"> Tanggal/Tahun Perjanjian Pinjaman/Obligasi</td>
		<td class="border tengah" rowspan="2"> Jumlah Pinjaman/Nilai Nominal Obligasi (Rp) </td>
		<td class="border tengah" rowspan="2"> Jangka Waktu Pinjaman (Tahun) </td>
		<td class="border tengah" rowspan="2"> Persentase Bunga Pinjaman (%) </td>
		<td class="border tengah" rowspan="2"> Tujuan Penggunaan Pinjaman </td>
		<td class="border tengah" colspan="2"> Jumlah Pembayaran Tahun ini </td>
		<td class="border tengah" colspan="2"> Jumlah Sisa Pembayaran </td>
	</tr>		
	<tr class="border">
		<td class="border tengah">Pokok Pinjaman Daerah (Rp)</td>
		<td class="border tengah">Bunga (Rp)</td>
		<td class="border tengah">Pokok Pinjaman Daerah</td>
		<td class="border tengah">Bunga (Rp)</td>

	</tr>
	<tr>
		<td class="border-rincian kanan total">&nbsp;</td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
	</tr>
	<tr>
		<td class="border-rincian kanan total">&nbsp;</td>
		<td class="border-rincian kiri total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
	</tr>
	<tr>
		<td class="border-rincian kanan total">&nbsp;</td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
	</tr>
	<tr>
		<td class="border-rincian kanan total">&nbsp;</td>
		<td class="border-rincian kiri total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
	</tr>
	<tr>
		<td class="border-rincian kanan total">&nbsp;</td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
	</tr>
	<tr>
		<td class="border-rincian kanan total">&nbsp;</td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
		<td class="border-rincian kanan total"></td>
	</tr>
	</tbody>	
</table>
<br><br>
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