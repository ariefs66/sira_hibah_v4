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
	<h5>LAMPIRAN III &nbsp; &nbsp; &nbsp; Rancangan Peraturan Daerah</h5>
	<h5>NOMOR : </h5>
	<h5>TANGGAL :</h5>
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
			<h3>RINCIAN RANCANGAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PENDAPATAN, BELANJA DAN PEMBIAYAAN</h3>
			<h5>TAHUN ANGGARAN {{ $tahun }}</h5>
		</td>
	</tr>
	<tr> <td colspan="2"></td> </tr>
</table>
<table class="rincian">
	<tbody>
	<tr class="border "> 
		<td colspan="4"><b>Urusan Pemerintah : </b> {{$urusan->URUSAN_KODE}} &nbsp; &nbsp; &nbsp; {{$urusan->URUSAN_KAT1_NAMA}}<br> 
		<b>Organisasi &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;: </b>{{$skpd->SKPD_KODE}} &nbsp; {{$skpd->SKPD_NAMA}} </td> 
	</tr>	
	<tr class="border headrincian">
		<td class="border tengah" >KODE <br> REKENING </td>
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
	@foreach($bl as $b)
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}</td>
		<td class="border-rincian"> &nbsp; &nbsp; <b>{{$b->PROGRAM_NAMA}}</b> <br>  &nbsp; &nbsp; &nbsp; {{$b->KEGIATAN_NAMA}} <br> &nbsp; &nbsp; &nbsp; &nbsp; {{$b->REKENING_NAMA}} </td>
		<td class="border-rincian kanan total">{{ number_format($b->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan total">-</td>
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