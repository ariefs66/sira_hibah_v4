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
	<tr class="">
		<td class="" colspan="2"></td>
	</tr>
	<tr>	
		<td class="">
			<img src="{{ url('/') }}/assets/img/bandung.png" width="80px" style="margin:3px">
		</td>	
		<td>
			<h4>PEMERINTAH KOTA BANDUNG</h4>
			<h3>RINGKASAN RANCANGAN APBD</h3>
			<h4>TAHUN ANGGARAN {{ $tahun }}</h4>
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
		<td class="border-rincian kanan total">{{ number_format($pendapatan,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">1.1</td>
		<td class="border-rincian">&nbsp;<b>PENDAPATAN ASLI DAERAH</b></td>
		<td class="border-rincian kanan rekening">{{ number_format($pad,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">1.1.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Pajak Daerah</td>
		<td class="border-rincian kanan">{{ number_format($pad1,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">1.1.2</td>
		<td class="border-rincian">&nbsp;&nbsp;Retribusi Daerah</td>
		<td class="border-rincian kanan">{{ number_format($pad2,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">1.1.3</td>
		<td class="border-rincian">&nbsp;&nbsp;Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan</td>
		<td class="border-rincian kanan">{{ number_format($pad3,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">1.1.4</td>
		<td class="border-rincian">&nbsp;&nbsp;Lain-lain Pendapatan Asli Daerah yang Sah</td>
		<td class="border-rincian kanan">{{ number_format($pad4,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">1.2</td>
		<td class="border-rincian">&nbsp;<b>DANA PERIMBANGAN</b></td>
		<td class="border-rincian kanan rekening">{{ number_format($ibg,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">1.2.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Bagi Hasil Pajak/Bagi Hasil Bukan Pajak</td>
		<td class="border-rincian kanan">{{ number_format($ibg1,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">1.2.2</td>
		<td class="border-rincian">&nbsp;&nbsp;Dana Alokasi Umum</td>
		<td class="border-rincian kanan">{{ number_format($ibg2,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">1.2.3</td>
		<td class="border-rincian">&nbsp;&nbsp;Dana Alokasi Khusus</td>
		<td class="border-rincian kanan">{{ number_format($ibg3,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">1.3</td>
		<td class="border-rincian">&nbsp;<b>LAIN-LAIN PENDAPATAN DAERAH YANG SAH</b></td>
		<td class="border-rincian kanan rekening">{{ number_format($pdl,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">1.3.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Pendapatan Hibah</td>
		<td class="border-rincian kanan">{{ number_format($pdl1,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">1.3.2</td>
		<td class="border-rincian">&nbsp;&nbsp;Dana Bagi Hasil Pajak dari Provinsi dan Pemerintah Daerah Lainnya</td>
		<td class="border-rincian kanan">{{ number_format($pdl2,0,',','.') }},00</td>
	</tr>
	<tr style="font-size: 5px;">
		<td class="border-rincian">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
	</tr>
	<tr>
		<td class="border-rincian">2</td>
		<td class="border-rincian"><b>BELANJA</b></td>
		<td class="border-rincian kanan total">{{ number_format($btl+$bl,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">2.1</td>
		<td class="border-rincian">&nbsp;<b>BELANJA TIDAK LANGSUNG</b></td>
		<td class="border-rincian kanan rekening">{{ number_format($btl,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">2.1.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Pegawai</td>
		<td class="border-rincian kanan">{{ number_format($btl1,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">2.1.2</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Subsidi</td>
		<td class="border-rincian kanan">{{ number_format($btl2,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">2.1.3</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Hibah</td>
		<td class="border-rincian kanan">{{ number_format($btl3,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">2.1.4</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Bantuan Keuangan Kepada Provinsi/kabupaten/kota Dan Pemerintahan Desa</td>
		<td class="border-rincian kanan">{{ number_format($btl4,0,',','.') }},00</td>
	</tr>	
	<tr>
		<td class="border-rincian">2.1.5</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Tidak Terduga</td>
		<td class="border-rincian kanan">{{ number_format($btl5,0,',','.') }},00</td>
	</tr>	
	<tr>
		<td class="border-rincian">2.2</td>
		<td class="border-rincian">&nbsp;<b>BELANJA LANGSUNG</b></td>
		<td class="border-rincian kanan rekening">{{ number_format($bl,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">2.2.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Pegawai</td>
		<td class="border-rincian kanan">{{ number_format($bl1,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">2.2.2</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Barang dan Jasa</td>
		<td class="border-rincian kanan">{{ number_format($bl2,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">2.2.3</td>
		<td class="border-rincian">&nbsp;&nbsp;Belanja Modal</td>
		<td class="border-rincian kanan">{{ number_format($bl3,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"><b>SURPLUS/(DEFISIT)</b></td>
		<td class="border kanan total"><b>{{ number_format(($bl+$btl1+$btl2+$btl3+$btl4+$btl5)-$pad,0,',','.') }},00</b></td>
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
		<td class="border-rincian kanan rekening">{{ number_format($pmb1,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">3.1.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Sisa Lebih Perhitungan Anggaran Tahun Anggaran Sebelumnya </td>
		<td class="border-rincian kanan">{{ number_format($pmb1,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">3.2</td>
		<td class="border-rincian">&nbsp;<b>PENGELUARAN PEMBIAYAAN DAERAH</b></td>
		<td class="border-rincian kanan rekening">{{ number_format($pmb2,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian">3.2.1</td>
		<td class="border-rincian">&nbsp;&nbsp;Penyertaan Modal (Investasi) Pemerintah Daerah</td>
		<td class="border-rincian kanan">{{ number_format($pmb2,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"><b>PEMBIAYAAN NETTO</b></td>
		<td class="border kanan total"><b>{{ number_format($pmb1-$pmb2,0,',','.') }},00</b></td>
	</tr>
	<tr style="font-size: 5px;">
		<td class="border-rincian">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"><b>SISA LEBIH PEMBIAYAAN ANGGARAN TAHUN BERKENAAN</b></td>
		<td class="border kanan total"><b>{{ number_format($pendapatan-($btl+$bl)+($pmb1-$pmb2),0,',','.') }},00</b></td>
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