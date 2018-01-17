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
	<h5 style="margin-right: -450px;">LAMPIRAN I Peraturan Daerah Kota Bandung</h5>
	<h5 style="margin-right: -495px;">NOMOR &nbsp; &nbsp; : 12 Tahun 2017</h5>
	<h5 style="margin-right: -517px;">TANGGAL &nbsp;: 29 Desember 2017 </h5>
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
			<h4 style="margin-right: 200px">PEMERINTAH KOTA BANDUNG</h4>
			<h3 style="margin-right: 200px">RINGKASAN APBD</h3>
			<h4 style="margin-right: 200px">TAHUN ANGGARAN {{ $tahun }}</h4>
		</td>
	</tr>
	<tr> <td colspan="2"></td> </tr>		
</table>
<table class="rincian">
	<tbody>
	<tr class="border headrincian">
		<td class="border tengah">NOMOR<br>URUT</td>
		<td class="border tengah">URAIAN</td>
		<td class="border tengah">JUMLAH</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
	</tr>
	<tr style="font-size: 5px;">
		<td class="border-rincian">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	<tr>
		<td class="border-rincian">1</td>
		<td class="border-rincian"><b>PENDAPATAN</b></td>
		<td class="border-rincian kanan total">{{ number_format((float)$pendapatan, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">1.1</td>
		<td class="border-rincian">&nbsp;<b>PENDAPATAN ASLI DAERAH</b></td>
		<td class="border-rincian kanan rekening">{{ number_format((float)$pad, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">1.1.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Pajak Daerah</td>
		<td class="border-rincian kanan">{{ number_format((float)$pad1, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">1.1.2</td>
		<td class="border-rincian">&nbsp;&nbsp;Retribusi Daerah</td>
		<td class="border-rincian kanan">{{ number_format((float)$pad2, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">1.1.3</td>
		<td class="border-rincian">&nbsp;&nbsp;Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan</td>
		<td class="border-rincian kanan">{{ number_format((float)$pad3, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">1.1.4</td>
		<td class="border-rincian">&nbsp;&nbsp;Lain-lain Pendapatan Asli Daerah yang Sah</td>
		<td class="border-rincian kanan">{{ number_format((float)$pad4, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">1.2</td>
		<td class="border-rincian">&nbsp;<b>DANA PERIMBANGAN</b></td>
		<td class="border-rincian kanan rekening">{{ number_format((float)$ibg, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">1.2.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Bagi Hasil Pajak/Bagi Hasil Bukan Pajak</td>
		<td class="border-rincian kanan">{{ number_format((float)$ibg1, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">1.2.2</td>
		<td class="border-rincian">&nbsp;&nbsp;Dana Alokasi Umum</td>
		<td class="border-rincian kanan">{{ number_format((float)$ibg2, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">1.2.3</td>
		<td class="border-rincian">&nbsp;&nbsp;Dana Alokasi Khusus</td>
		<td class="border-rincian kanan">{{ number_format((float)$ibg3, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">1.3</td>
		<td class="border-rincian">&nbsp;<b>LAIN-LAIN PENDAPATAN DAERAH YANG SAH</b></td>
		<td class="border-rincian kanan rekening">{{ number_format((float)$pdl, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">1.3.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Pendapatan Hibah</td>
		<td class="border-rincian kanan">{{ number_format((float)$pdl1, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">1.3.2</td>
		<td class="border-rincian">&nbsp;&nbsp;Dana Bagi Hasil Pajak dari Provinsi dan Pemerintah Daerah Lainnya</td>
		<td class="border-rincian kanan">{{ number_format((float)$pdl2, 2, ',', '.') }}</td>
	</tr>
	<tr style="font-size: 5px;">
		<td class="border-rincian">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
	</tr>
	<tr>
		<td class="border-rincian">2</td>
		<td class="border-rincian"><b>BELANJA</b></td>
		<td class="border-rincian kanan total">
		
		@php $totbelanja =($bl1+$bl2+$bl3)+$btl; @endphp
		{{ number_format((float)$totbelanja, 2, ',', '.') }}

		</td>
	</tr>
	<tr>
		<td class="border-rincian">2.1</td>
		<td class="border-rincian">&nbsp;<b>BELANJA TIDAK LANGSUNG </b></td>
		<td class="border-rincian kanan rekening">{{ number_format((float)$btl, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">2.1.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Pegawai</td>
		<td class="border-rincian kanan"> {{ number_format((float)$btl1, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">2.1.2</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Subsidi</td>
		<td class="border-rincian kanan">{{ number_format((float)$btl2, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">2.1.3</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Hibah</td>
		<td class="border-rincian kanan">{{ number_format((float)$btl3, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">2.1.4</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Bantuan Keuangan Kepada Provinsi/kabupaten/kota Dan Pemerintahan Desa</td>
		<td class="border-rincian kanan">{{ number_format((float)$btl4, 2, ',', '.') }}</td>
	</tr>	
	<tr>
		<td class="border-rincian">2.1.5</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Tidak Terduga</td>
		<td class="border-rincian kanan">{{ number_format((float)$btl5, 2, ',', '.') }}</td>
	</tr>	
	<tr>
		<td class="border-rincian">2.2</td>
		<td class="border-rincian">&nbsp;<b>BELANJA LANGSUNG</b></td>
		<td class="border-rincian kanan rekening">{{ number_format((float)$bl1+$bl2+$bl3, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">2.2.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Pegawai</td>
		<td class="border-rincian kanan">{{ number_format((float)$bl1, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">2.2.2</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Barang dan Jasa</td>
		<td class="border-rincian kanan">{{ number_format((float)$bl2, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">2.2.3</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Modal</td>
		<td class="border-rincian kanan">{{ number_format((float)$bl3, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"><b>SURPLUS/(DEFISIT)</b></td>
		<td class="border kanan total"><b>
		@php $surdef = $pendapatan-$totbelanja  @endphp	
		@if($surdef<0)
		({{ trim(number_format((float)$surdef, 2, ',', '.'),"-") }})
		@else
		{{ number_format((float)$surdef, 2, ',', '.') }}
		@endif
		</b></td>
	</tr>
	<tr style="font-size: 5px;">
		<td class="border-rincian">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
	</tr>
	<tr>
		<td class="border-rincian">3</td>
		<td class="border-rincian"><b>PEMBIAYAAN DAERAH</b></td>
		<td class="border-rincian kanan total"></td>
	</tr>
	<tr>
		<td class="border-rincian">3.1</td>
		<td class="border-rincian">&nbsp;<b>PENERIMAAN PEMBIAYAAN DAERAH</b></td>
		<td class="border-rincian kanan rekening">{{ number_format((float)$pmb1, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">3.1.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Sisa Lebih Perhitungan Anggaran Tahun Anggaran Sebelumnya </td>
		<td class="border-rincian kanan">{{ number_format((float)$pmb1, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">3.2</td>
		<td class="border-rincian">&nbsp;<b>PENGELUARAN PEMBIAYAAN DAERAH</b></td>
		<td class="border-rincian kanan rekening">{{ number_format((float)$pmb2, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian">3.2.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Penyertaan Modal (Investasi) Pemerintah Daerah</td>
		<td class="border-rincian kanan">{{ number_format((float)$pmb2, 2, ',', '.') }}</td>
	</tr>
	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"><b>PEMBIAYAAN NETTO</b></td>
		<td class="border kanan total"><b>{{ number_format((float)$pmb1-$pmb2, 2, ',', '.') }}</b></td>
	</tr>
	<tr style="font-size: 5px;">
		<td class="border-rincian">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"><b>SISA LEBIH PEMBIAYAAN ANGGARAN TAHUN BERKENAAN</b></td>
		<td class="border kanan total"><b>
		@php $totRingkasan = $surdef+($pmb1-$pmb2); @endphp
		@if($totRingkasan < 0)
		({{ trim(number_format((float)$totRingkasan, 2, ',', '.'),"-") }})
		@else
		{{ number_format((float)$totRingkasan, 2, ',', '.') }}
		@endif
	</tr>
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
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td><b>WALI KOTA BANDUNG</b></td>
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