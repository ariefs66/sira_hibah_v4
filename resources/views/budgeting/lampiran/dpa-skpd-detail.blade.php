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
				DOKUMEN PELAKSANAAN ANGGARAN <br> 
				SATUAN KERJA PERANGKAT DAERAH <br> 
				(DPA SKPD)<br> <br>
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
					<td class="border">DPA-SKPD</td>
					<td class="border">Ringkasan Dokumen Pelaksanaan Anggaran Satuan Kerja Perangkat Daerah</td>
				</tr>
				<tr class="border">
					<td class="border">DPA-SKPD1</td>
					<td class="border">Rincian Dokumen Pelaksanaan Anggaran Pendapatan Satuan Kerja Perangkat Daerah</td>
				</tr>
				<tr class="border">
					<td class="border">DPA-SKPD 2.1</td>
					<td class="border">Rincian Dokumen Pelaksanaan Anggaran Belanja Tidak Langsung Satuan Kerja Perangkat Daerah</td>
				</tr>
				<tr class="border">
					<td class="border">DPA-SKPD 2.2</td>
					<td class="border">Rekapitulasi Belanja Langsung Menurut Program dan Kegiatan Satuan Kerja Perangkat Daerah </td>
				</tr>
				<tr class="border">
					<td class="border">DPA-SKPD 2.2.1</td>
					<td class="border">Rincian Dokumen Pelaksanaan Anggaran Belanja Langsung Program dan Per Kegiatan Satuan Kerja Perangkat Daerah</td>
				</tr>
				<tr class="border">
					<td class="border">DPA-SKPD 3.1</td>
					<td class="border">Rincian Penerimaan Pembiayaan</td>
				</tr>
				<tr class="border">
					<td class="border">DPA-SKPD 3.2</td>
					<td class="border">Rincian Pengeluaran Pembiayaan</td>
				</tr>
			</table>
		</td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
	</tr>
</table>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br>


<table class="header">
	<tr class="border">
		<td class="border" width="85%">
			<h4>RINGKASAN DOKUMEN PELAKSANAAN ANGGARAN<br>SATUAN KERJA PERANGKAT DAERAH <br> TAHUN ANGGARAN {{$tahun}}</h4>
		</td>
		<td rowspan="2" class="border">
			<h4>Formulir<br>DPA-SKPD</h4>
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
		<td class="border">Kode Rekening</td>
		<td class="border">Uraian</td>
		<td class="border">Jumlah<br>(Rp)</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
	</tr>

	@php $total=0; @endphp

	<!-- pendapatan -->
	@if($pendapatan != 0)
	<tr>
		<td class="border-rincian kiri "> <br> 4</td>
		<td class="border-rincian "><b> <br> Pendapatan</b></td>
		<td class="border-rincian kanan border"> <br> {{ number_format($pendapatan,0,',','.') }}</td>
	</tr>
		<!-- satu -->
		@if($pendapatan1 != 0)
		<tr>
			<td class="border-rincian kiri ">4.1</td>
			<td class="border-rincian "><b> &nbsp; Pandapatan Asli Daerah</b></td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan1,0,',','.') }} </td>
		</tr>

		<tr>
			<td class="border-rincian kiri ">4.1.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Pajak Daerah </td>
			<td class="kanan">{{ number_format($pendapatan11,0,',','.') }} </td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.1.2</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Retribusi Daerah </td>
			<td class="kanan">{{ number_format($pendapatan12,0,',','.') }} </td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.1.3</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan </td>
			<td class="kanan">{{ number_format($pendapatan13,0,',','.') }} </td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.1.4</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Lain-lain Pendapatan Asli Daerah yang Sah </td>
			<td class="kanan">{{ number_format($pendapatan14,0,',','.') }} </td>
		</tr>
		@endif
		<!-- dua -->
		@if($pendapatan2 != 0)
		<tr>
			<td class="border-rincian kiri ">4.2</td>
			<td class="border-rincian "><b> &nbsp; Dana Perimbangan </b></td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan2,0,',','.') }} </td>
		</tr>

		<tr>
			<td class="border-rincian kiri ">4.2.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Bagi Hasil Pajak/Bagi Hasil Bukan Pajak </td>
			<td class="kanan">{{ number_format($pendapatan21,0,',','.') }} </td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.2.2</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Alokasi Umum </td>
			<td class="kanan">{{ number_format($pendapatan22,0,',','.') }} </td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.2.3</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Alokasi Khusus </td>
			<td class="kanan">{{ number_format($pendapatan23,0,',','.') }} </td>
		</tr>
		@endif	
		<!-- tiga -->
		@if($pendapatan3 != 0)
		<tr>
			<td class="border-rincian kiri ">4.3</td>
			<td class="border-rincian "><b> &nbsp; Lain-lain Pendapatan yang Sah </b></td>
			<td class="border-rincian kanan border">{{ number_format($pendapatan3,0,',','.') }} </td>
		</tr>

		<tr>
			<td class="border-rincian kiri ">4.3.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Pendapatan Hibah </td>
			<td class="kanan">{{ number_format($pendapatan31,0,',','.') }} </td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.3.3</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Bagi Hasil Pajak dari Provinsi dan Pemerintah Daerah Lainnya </td>
			<td class="kanan">{{ number_format($pendapatan33,0,',','.') }} </td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.3.4</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Dana Penyesuaian dan Otonomi Khusus </td>
			<td class="kanan">{{ number_format($pendapatan34,0,',','.') }} </td>
		</tr>	
		<tr>
			<td class="border-rincian kiri ">4.3.5</td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; Bantuan Keuangan dari Provinsi atau Pemerintah Daerah Lainnya </td>
			<td class="kanan">{{ number_format($pendapatan35,0,',','.') }} </td>
		</tr>	
		@endif
	@endif	

	<tr>
		<td class="border-rincian kiri ">  <br> 5 </td>
		<td class="border-rincian "><b> <br> Belanja</b></td>
		<td class="border-rincian kanan border"> <br> {{ number_format($btl+$bl,0,',','.') }},00</td>
	</tr>
	<!-- belanja tidak langsung -->
	@if($btl !=0)
	<tr>
		<td class="border-rincian kiri ">5.1</td>
		<td class="border-rincian "><b> &nbsp; Belanja Tidak Langsung</b></td>
		<td class="border-rincian kanan border">{{ number_format($btl,0,',','.') }},00</td>
	</tr>

	<tr>
		<td class="border-rincian kiri ">5.1.1</td>
		<td class="border-rincian ">&nbsp; &nbsp; Belanja Pegawai</td>
		<td class="border-rincian kanan ">{{ number_format($btl1,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian kiri ">5.1.3</td>
		<td class="border-rincian ">&nbsp; &nbsp; Belanja Subsidi </td>
		<td class="border-rincian kanan ">{{ number_format($btl2,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian kiri ">5.1.4</td>
		<td class="border-rincian ">&nbsp; &nbsp; Belanja Hibah </td>
		<td class="border-rincian kanan ">{{ number_format($btl3,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian kiri ">5.1.7</td>
		<td class="border-rincian ">&nbsp; &nbsp; Belanja Bantuan Keuangan Kepada Provinsi/kabupaten/kota Dan Pemerintahan Desa </td>
		<td class="border-rincian kanan ">{{ number_format($btl4,0,',','.') }},00</td>
	</tr>
	<tr>
		<td class="border-rincian kiri ">5.1.8</td>
		<td class="border-rincian ">&nbsp; &nbsp; Belanja Tidak Terduga </td>
		<td class="border-rincian kanan ">{{ number_format($btl5,0,',','.') }},00</td>
	</tr>
	@endif

	<!-- belanja langsung-->
	@if($bl !=0)
	<tr>
		<td class="border-rincian kiri ">5.2</td>
		<td class="border-rincian "><b> &nbsp; Belanja Langsung</b></td>
		<td class="border-rincian kanan border">{{ number_format($bl,0,',','.') }},00</td>
	</tr>
		<tr>
			<td class="border-rincian kiri ">5.2.1</td>
			<td class="border-rincian ">&nbsp; &nbsp; Belanja Pegawai</td>
			<td class="border-rincian kanan ">{{ number_format($bl1,0,',','.') }},00</td>
		</tr>
		<tr>
			<td class="border-rincian kiri ">5.2.2</td>
			<td class="border-rincian ">&nbsp; &nbsp; Belanja Barang dan Jasa</td>
			<td class="border-rincian kanan ">{{ number_format($bl2,0,',','.') }},00</td>
		</tr>
		<tr>
			<td class="border-rincian kiri ">5.2.3</td>
			<td class="border-rincian ">&nbsp; &nbsp; Belanja Modal</td>
			<td class="border-rincian kanan ">{{ number_format($bl3,0,',','.') }},00</td>
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
			,00</b></td>
		</tr>


	@if($pem != 0 || $peng!=0)	
	<tr>
		<td class="border-rincian kiri "> <br> 6</td>
		<td class="border-rincian "><b> <br> Pembiayaan Daerah</b></td>
		<td class="border-rincian kanan border"> <br> {{ number_format($pem+$peng,0,',','.') }}</td>
	</tr>

		<tr>
			<td class="border-rincian kiri ">6.1</td>
			<td class="border-rincian "><b> &nbsp; Penerimaan Pembiayaan Daerah</b></td>
			<td class="border-rincian kanan border">{{ number_format($pem,0,',','.') }} </td>
		</tr>
		<tr>
			<td class="border-rincian kiri ">6.1.1</td>
			<td class="border-rincian "> &nbsp; &nbsp; Sisa Lebih Perhitungan Anggaran Tahun Anggaran Sebelumnya </td>
			<td class="border-rincian kanan">{{ number_format($pem,0,',','.') }} </td>
		</tr>
		<tr>
			<td class="border-rincian kiri ">6.2</td>
			<td class="border-rincian "><b> &nbsp; Pengeluaran Pembiayaan Daerah</b></td>
			<td class="border-rincian kanan border">{{ number_format($peng,0,',','.') }} </td>
		</tr>
		<tr>
			<td class="border-rincian kiri ">6.2.1</td>
			<td class="border-rincian ">&nbsp; &nbsp; Penyertaan Modal (Investasi) Pemerintah Daerah </td>
			<td class="border-rincian kanan">{{ number_format($peng,0,',','.') }} </td>
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
		<td class="border kanan">{{ number_format($akb_bl->tri4,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_bl->tri1+$akb_bl->tri2+$akb_bl->tri3+$akb_bl->tri4,0,',','.') }}</td>
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
		<td>Bandung, 2 Januari 2018</td>
	</tr>
	<tr>
		<td></td>
		<td>SEKERTARIS DAERAH</td>
	</tr>
	<tr>
		<td></td>
		<td><br><br><br><br><br></td>
	</tr>
	<tr>
		<td></td>
		<td><span style="border-bottom: 1px solid #000;padding-bottom: 1px;">
		Dr. H. YOSSI IRIANTO, M.Si</span></td>
	</tr>
	<tr>
		<td></td>
		<td>NIP. 19620429 198509 1 001</td>
	</tr>
</table>
</div>
</body>
</html>