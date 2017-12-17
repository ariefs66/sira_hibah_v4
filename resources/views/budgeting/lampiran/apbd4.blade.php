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
	<h5>LAMPIRAN IV &nbsp; &nbsp; &nbsp; Rancangan Peraturan Daerah</h5>
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
			<h3>REKAPITULASI RANCANGAN BELANJA MENURUT URUSAN PEMERINTAH DAERAH</h3>
			<h3>ORGANISASI, PROGRAM DAN KEGIATAN</h3>
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
		<td class="border tengah" rowspan="2">URAIAN URUSAN, ORGANISASI PROGRAM DAN KEGIATAN </td>
		<td class="border tengah" colspan="3">JENIS BELANJA </td>
		<td class="border tengah" rowspan="2"> JUMLAH </td>
	</tr>	
	<tr class="border headrincian">
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
		<td class="border tengah">6 = 3+4+5</td>
	</tr>
	@foreach($kat1 as $k1)
	<tr>
		<td class="border-rincian kiri total">{{$k1->URUSAN_KAT1_KODE}}</td>
		<td class="border-rincian kiri total"><b>{{$k1->URUSAN_KAT1_NAMA}}</b></td>
		<td class="border-rincian kiri total"></td>
		<td class="border-rincian kiri total"></td>
		<td class="border-rincian kiri total"></td>
		<td class="border-rincian kiri total"></td>
	</tr>
		@foreach($urusan as $u)
		@if($k1->URUSAN_KAT1_ID == $u->URUSAN_KAT1_ID)
		<tr>
			<td class="border-rincian kiri total">{{$k1->URUSAN_KAT1_KODE}}.{{$u->URUSAN_KODE}}</td>
			<td class="border-rincian kiri total">&nbsp;<b>{{$u->URUSAN_NAMA}}</b></td>
			<td class="border-rincian kiri total"></td>
			<td class="border-rincian kiri total"></td>
			<td class="border-rincian kiri total"></td>
			<td class="border-rincian kiri total"></td>
		</tr>
			@foreach($skpd as $s)
			@if($s->URUSAN_KODE == $u->URUSAN_KODE)
			<tr>
				<td class="border-rincian kiri total">{{$k1->URUSAN_KAT1_KODE}}.{{$u->URUSAN_KODE}}.{{$s->SKPD_KODE}}</td>
				<td class="border-rincian kiri total">&nbsp; &nbsp; <b>{{$s->SKPD_NAMA}}</b></td>
				<td class="border-rincian kiri total"></td>
				<td class="border-rincian kiri total"></td>
				<td class="border-rincian kiri total"></td>
				<td class="border-rincian kiri total"></td>
			</tr>
				@foreach($program as $p)

					@if($p->URUSAN_ID == $s->URUSAN_ID)



					<tr>
						<td class="border-rincian kiri total">{{$k1->URUSAN_KAT1_KODE}}.{{$u->URUSAN_KODE}}.{{$s->SKPD_KODE}}.{{$p->PROGRAM_KODE}}</td>
						<td class="border-rincian kiri total">&nbsp; &nbsp; &nbsp; <b>{{$p->PROGRAM_NAMA}}</b></td>
						<td class="border-rincian kiri total"></td>
						<td class="border-rincian kiri total"></td>
						<td class="border-rincian kiri total"></td>
						<td class="border-rincian kiri total"></td>
					</tr>
						@foreach($kegiatan as $k)
							@if($p->PROGRAM_ID == $k->PROGRAM_ID)
							<tr>
								<td class="border-rincian kiri total">{{$k1->URUSAN_KAT1_KODE}}.{{$u->URUSAN_KODE}}.{{$s->SKPD_KODE}}.{{$p->PROGRAM_KODE}}.{{$k->KEGIATAN_KODE}}</td>
								<td class="border-rincian kiri total">&nbsp; &nbsp; &nbsp; &nbsp; {{$k->KEGIATAN_NAMA}}</td>

								@php 
									$total =0;
								@endphp

								@php
									$found = false;
									@endphp
								@foreach($pegawai as $peg)
									

									@if($k->KEGIATAN_ID == $peg->KEGIATAN_ID)
									<td class="border-rincian">{{ number_format($peg->total,0,',','.') }}</td>
									@php 
									$total += $peg->total; 
									$found = true;
									@endphp
									@endif

									
								@endforeach
								@if(!$found)
									<td class="border-rincian"></td>
									@endif


									@php
										$found = false;
									@endphp
								@foreach($barangJasa as $br)
									@if($k->KEGIATAN_ID == $br->KEGIATAN_ID)
									<td class="border-rincian">{{ number_format($br->total,0,',','.') }}</td>
									@php $total += $br->total;$found = true; @endphp
									@endif
								@endforeach
								@if(!$found)
									<td class="border-rincian"></td>
									@endif

									@php
										$found = false;
									@endphp
								@foreach($modal as $mod)
									@if($k->KEGIATAN_ID == $mod->KEGIATAN_ID)
									<td class="border-rincian">{{ number_format($mod->total,0,',','.') }}</td>
									@php $total += $mod->total;$found = true; @endphp
									@endif
								@endforeach
								@if(!$found)
									<td class="border-rincian"></td>
									@endif
								<td class="border-rincian kiri total"> {{$total}}</td>
							</tr>
							@endif
					@endforeach
					@endif
				@endforeach
			@endif
			@endforeach
		@endif
		@endforeach
		<!-- limit untuk lokal -->
		@php
		break;
		@endphp
		<!-- end limit untuk lokal -->		
	@endforeach
	
	
	
	<tr>
		<td class="border-rincian kanan total">&nbsp;</td>
		<td class="border-rincian kanan total">Jumlah</td>
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