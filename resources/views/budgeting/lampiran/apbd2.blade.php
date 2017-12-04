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
<table class="header">
	<tr class="border">
		<td class="border">
			<h4>PEMERINTAH KOTA BANDUNG</h4>
			<h3>RINGKASAN RANCANGAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI</h3>
			<h5>TAHUN ANGGARAN {{ $tahun }}</h5>
		</td>
</table>
<table class="rincian">
	<tbody>
	<tr class="border headrincian">
		<td class="border tengah" rowspan="2">KODE</td>
		<td class="border tengah" rowspan="2">URUSAN PEMERINTAHAN DAERAH</td>
		<td class="border tengah" rowspan="2">PENDAPATAN</td>
		<td class="border tengah" colspan="3">BELANJA</td>
	</tr>	
	<tr>
		<td class="border tengah">TIDAK LANGSUNG</td>
		<td class="border tengah">LANGSUNG</td>
		<td class="border tengah">JUMLAH BELANJA</td>
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
	<tr>
		<td class="border-rincian">1</td>
		<td class="border-rincian"><b>Urusan Wajib Pelayanan Dasar</b></td>
		<td class="border-rincian kanan total">-</td>
		<td class="border-rincian kanan total">-</td>
		<td class="border-rincian kanan total">-</td>
		<td class="border-rincian kanan total">-</td>
	</tr>
	@foreach($urusan as $urusan)
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}</td>
		<td class="border-rincian">&nbsp;<b>{{$urusan->URUSAN_NAMA}}</b></td>
		<td class="border-rincian kanan rekening">-</td>
		<td class="border-rincian kanan rekening">-</td>
		<td class="border-rincian kanan rekening">-</td>
		<td class="border-rincian kanan rekening">-</td>
	</tr>
	@endforeach

	@foreach($bl as $bl)
	<tr>
		<td class="border-rincian">{{$bl->SKPD_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; {{$bl->SKPD_NAMA}}</td>
		<td class="border-rincian kanan rekening">-</td>
		<td class="border-rincian kanan rekening">-</td>
		<td class="border-rincian kanan rekening">{{ number_format($bl->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan rekening">-</td>
	</tr>
	@endforeach

	@foreach($btl as $btl)
	<tr>
		<td class="border-rincian">{{$btl->SKPD_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; {{$btl->SKPD_NAMA}}</td>
		<td class="border-rincian kanan rekening">-</td>
		<td class="border-rincian kanan rekening">{{ number_format($btl->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan rekening">-</td>
		<td class="border-rincian kanan rekening">-</td>
	</tr>
	@endforeach

	@foreach($pendapatan as $pendapatan)
	<tr>
		<td class="border-rincian">{{$pendapatan->SKPD_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; {{$pendapatan->SKPD_NAMA}}</td>
		<td class="border-rincian kanan rekening">{{ number_format($pendapatan->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan rekening">-</td>
		<td class="border-rincian kanan rekening">-</td>
		<td class="border-rincian kanan rekening">-</td>
	</tr>
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