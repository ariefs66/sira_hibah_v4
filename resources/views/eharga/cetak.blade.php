<!DOCTYPE html>
<html>
<head>
	<title>Surat Usulan E-Komponen TA 2018</title>
	<style type="text/css">
		html{
			font-size: 16px;
		}
		body{
			font-family: Bookman Old Style;
		}
		h3{
			margin-top: -23px;
		}
		h4{
			margin-top: -15px;
		}
		h2,h3,h4{
			margin-left: -60px;
		}
		.table{
			border: 1px solid;
			border-collapse: collapse;
		}
		.border{
			border: 1px solid;
			text-align: center;
			font-size: 11px;
		}
		.page-break {
		    page-break-after: always;
		}
	</style>
</head>
<body onload="return print()">
<table style="text-align: center;" width="100%">
	<tr>
		<td rowspan="3"><img src="{{ url('/') }}/assets/img/bandung.png" width="100"></td>
		<td><h2>PEMERINTAH KOTA BANDUNG</h2></td>
	</tr>
	<tr>
		<td><h3>{{ $skpd->skpd->SKPD_NAMA }}</h3></td>
	</tr>
	<tr>
		<td><h4>{{ $skpd->skpd->SKPD_ALAMAT }}</h4></td>
	</tr>
</table>
<hr>
<table width="100%">
	<tr>
		<td colspan="5"><p style="text-align: right;margin-right: 20px;">Bandung, {{ $date }}<p></td>
	</tr>
	<tr>
		<td>Nomor</td>
		<td colspan="4">:</td>
	</tr>
	<tr>
		<td>Sifat</td>
		<td colspan="3">:</td>
		<td>Kepada</td>
	</tr>
	<tr>
		<td>Lampiran</td>
		<td>:</td>
		<td width="45%">1 (satu) berkas</td>
		<td>Yth</td>
		<td>Kepala Badan Pengelolaan</td>
	</tr>
	<tr>
		<td style="vertical-align: top">Perihal</td>
		<td style="vertical-align: top">:</td>
		<td>Usulan e-Komponen untuk Penyusunan Keputusan Walikota Bandung tentang Standar Satuan Harga dan Analisa Standar Belanja</td>
		<td></td>
		<td style="vertical-align: top">Keuangan Dan Aset Kota Bandung Di BANDUNG</td>
	</tr>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td colspan="3" style="text-align: justify;font-size: 16px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dipermaklumkan dengan hormat, menindak lanjuti Surat Edaran Sekertaris Daerah Kota Bandung Nomor @if($thn == 2018) 903/SE.009 – BPKA Tanggal 26 Januari 2018 @else 027/SE. 088 – BPKA Tanggal 6 Juli 2017 @endif tentang Pelaksanaan Anggaran Pendapatan dan Belanja Daerah Tahun Anggaran {{ $thn }}, bersama ini kami mengajukan permohonan penambahan/perubahan komponen agar dapat dimasukkan ke dalam Keputusan Walikota termaksud (daftar terlampir).<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Demikian kami sampaikan, atas perhatiannya  kami ucapkan terima kasih.</td>
	</tr>
</table>
<table width="100%" style="margin-top: 40px;">
	<tr>
		<td width="50%"></td>
		<td style="text-align: center"><b>{{$skpd->skpd->SKPD_BENDAHARA}},</b></td>
	</tr>
	<tr>
		<td></td>
		<td><br><br><br><br></td>
	</tr>
	<tr>
		<td width="50%"></td>
		<td style="text-align: center"><b>{{ $skpd->skpd->SKPD_KEPALA }}</b></td>
	</tr>
	<tr>
		<td width="50%"></td>
		<td style="text-align: center">{{ $skpd->skpd->SKPD_JABATAN }}</td>
	</tr>
	<tr>
		<td width="50%"></td>
		<td style="text-align: center">{{ $skpd->skpd->SKPD_KEPALA_NIP }}</td>
	</tr>
</table>
<br><br>
Tembusan :<br>
1. Walikota Bandung (sebagai laporan);<br>
2. Wakil Walikota Bandung (sebagai laporan);<br>
3. Sekretaris Daerah Kota Bandung (sebagai laporan);<br>
4. Asisten Pemerintahan Kota Bandung;<br>
5. Asisten Administrasi Kota Bandung;<br>
<img style="margin-top: 20px" src="{{ url('/') }}/qr/{{$surat->SURAT_NO}}.png"><br>
<p style="margin-top: -20px;margin-left: 40px;">{{$surat->SURAT_NO}}</p>
<div class="page-break"></div>
<table style="text-align: center;" width="100%">
	<tr>
		<td><b>LAMPIRAN USULAN STANDAR SATUAN HARGA & ANALISA STANDAR BELANJA<br>PEMERINTAH KOTA BANDUNG<br>TAHUN ANGGARAN {{$thn}}</b></td>
	</tr>
</table>
<br><br>
<table width="100%" class="table">
	<thead>
		<tr>
			<td class="border" width="1%"><b>NO</b></td>
			<td class="border" width="1$"><b>Jenis Usulan</b></td>
			<td class="border" width="1%"><b>JENIS KOMPONEN</b></td>
			<td class="border"><b>NAMA KOMPONEN / SPESFIKASI</b></td>
			<td class="border" width="1%"><b>SATUAN</b></td>
			<td class="border" width="1%"><b>HARGA</b></td>
			<td class="border" width="1%"><b>KODE REKENING</b></td>
		</tr>
		<tr>
			<td class="border"><b>1</b></td>
			<td class="border"><b>2</b></td>
			<td class="border"><b>3</b></td>
			<td class="border"><b>4</b></td>
			<td class="border"><b>5</b></td>
			<td class="border"><b>6</b></td>
			<td class="border"><b>7</b></td>
		</tr>
		<tr>
			<td class="border"><b></b></td>
			<td class="border"><b></b></td>
			<td class="border"><b></b></td>
			<td class="border"><b></b></td>
			<td class="border"><b></b></td>
			<td class="border"><b></b></td>
			<td class="border"><b></b></td>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $d)
		<tr>
			<td class="border">{{ $x++ }}</td>
			@if($d->USULAN_TYPE == 1)
			<td class="border">Komponen Baru</td>
			@elseif($d->USULAN_TYPE == 2)
			<td class="border">Ubah Komponen</td>
			@elseif($d->USULAN_TUPE == 3)
			<td class="border">Tambah Rekening</td>
			@endif
			@if(substr($d->katkom->KATEGORI_KODE,0,1) == 1)
			<td class="border">SSH</td>
			@elseif(substr($d->katkom->KATEGORI_KODE,0,1) == 2)
			<td class="border">HSPK</td>
			@elseif(substr($d->katkom->KATEGORI_KODE,0,1) == 3)
			<td class="border">ASB</td>
			@endif
			<td class="border" style="text-align: left">&nbsp;{{ $d->USULAN_NAMA }}<br>&nbsp;Spesfikiasi : {{ $d->USULAN_SPESIFIKASI }}</td>
			<td class="border">{{ $d->USULAN_SATUAN }}</td>
			<td class="border" style="text-align: right;">{{ number_format($d->USULAN_HARGA,0,',','.') }}&nbsp;</td>
			<td class="border">{{ $d->rekening->REKENING_KODE }}</td>
		</tr>
		@endforeach
	</tbody>
</table>
</body>
</html>