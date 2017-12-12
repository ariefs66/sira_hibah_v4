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
	<h5>LAMPIRAN V &nbsp; &nbsp; &nbsp; Rancangan Peraturan Daerah</h5>
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
			<h3>REKAPITULASI RANCANGAN BELANJA DAERAH UNTUK KESELARASAN DAN KETERPADUAN</h3>
			<h3>URUSAN PEMERINTAHAN DAERAH DAN FUNGSI DALAM KERANGKA PENGELOLAAN KEUANGAN NEGARA</h3>
			<h4>TAHUN ANGGARAN {{ $tahun }}</h4>
		</td>
	</tr>
	<tr> <td colspan="2"></td> </tr>
</table>
<br>
<table class="rincian">
	<tbody>
	<tr class="border headrincian">
		<td class="border tengah" rowspan="2">KODE </td>
		<td class="border tengah" rowspan="2">URAIAN</td>
		<td class="border tengah" colspan="2">BELANJA TIDAK LANGSUNG </td>
		<td class="border tengah" colspan="3">BELANJA LANGSUNG </td>
		<td class="border tengah" rowspan="2"> JUMLAH </td>
	</tr>	
	<tr class="border headrincian">
		<td class="border tengah">PEGAWAI </td>
		<td class="border tengah">LAINNYA</td>
		<td class="border tengah">PEGAWAI </td>
		<td class="border tengah">BARANG & JASA</td>
		<td class="border tengah">MODAL</td>
	</tr>	
	<tr class="border">
		<td class="border tengah">1</td>
		<td class="border tengah">2</td>
		<td class="border tengah">3</td>
		<td class="border tengah">4</td>
		<td class="border tengah">5</td>
		<td class="border tengah">6</td>
		<td class="border tengah">7</td>
		<td class="border tengah">8 = 3+4+5+6+7</td>
	</tr>
	@foreach($kat2 as $k2)
	<tr>
		<td class="border-rincian">{{$k2->URUSAN_KAT2_KODE}}</td>
		<td class="border-rincian"><b>{{$k2->URUSAN_KAT2_NAMA}}</b></td>
		<td class="border-rincian"></td>
		<td class="border-rincian"></td>
		<td class="border-rincian"></td>
		<td class="border-rincian"></td>
		<td class="border-rincian"></td>
		<td class="border-rincian"></td>
	</tr>
		@foreach($urusan as $u)
		@if($k2->URUSAN_KAT2_ID == $u->URUSAN_KAT2_ID)
		<tr>
			<td class="border-rincian">{{$k2->URUSAN_KAT2_KODE}}.{{$u->URUSAN_KODE}}</td>
			<td class="border-rincian">&nbsp; {{$u->URUSAN_NAMA}}</td>
			@foreach($btl_p as $btp)
				@if($u->URUSAN_KODE == $btp->URUSAN_KODE)
				<td class="border-rincian">{{ number_format($btp->total,0,',','.') }}</td>
				@endif
			@endforeach
			@foreach($btl_l as $btl)
				@if($u->URUSAN_KODE == $btl->URUSAN_KODE)
				<td class="border-rincian">{{ number_format($btl->total,0,',','.') }}</td>
				@endif
			@endforeach
			@foreach($pegawai as $peg)
				@if($u->URUSAN_KODE == $peg->URUSAN_KODE)
				<td class="border-rincian">{{ number_format($peg->total,0,',','.') }}</td>
				@endif
			@endforeach
			@foreach($barangJasa as $bj)
				@if($u->URUSAN_KODE == $bj->URUSAN_KODE)
				<td class="border-rincian">{{ number_format($bj->total,0,',','.') }}</td>
				@endif
			@endforeach
			@foreach($modal as $mod)
				@if($u->URUSAN_KODE == $mod->URUSAN_KODE)
				<td class="border-rincian">{{ number_format($mod->total,0,',','.') }}</td>
				@endif
			@endforeach
			<td class="border-rincian"></td>
		</tr>
		@endif
		@endforeach
	@endforeach
	
	
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