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
			<h3>RINGKASAN RANCANGAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI</h3> 
			<h5>TAHUN ANGGARAN {{ $tahun }}</h5>
		</td>
	</tr>
	<tr> <td colspan="2"></td> </tr>	
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
	@foreach($kat1 as $k1)
	<tr>
		<td class="border-rincian">{{$k1->URUSAN_KAT1_KODE}}</td>
		<td class="border-rincian"><b>{{$k1->URUSAN_KAT1_NAMA}}</b></td>
		<td class="border-rincian kanan total">-</td>
		<td class="border-rincian kanan total">-</td>
		<td class="border-rincian kanan total">-</td>
		<td class="border-rincian kanan total">-</td>
	</tr>
		@foreach($urusan as $u)
			@if($k1->URUSAN_KAT1_ID == $u->URUSAN_KAT1_ID)
			<tr>
				<td class="border-rincian">{{$u->URUSAN_KODE}}</td>
				<td class="border-rincian">&nbsp; &nbsp; <b>{{$u->URUSAN_NAMA}}</b></td>
				<td class="border-rincian kanan rekening">-</td>
				<td class="border-rincian kanan rekening">-</td>
				<td class="border-rincian kanan rekening">-</td>
				<td class="border-rincian kanan rekening">-</td>
			</tr>

				@foreach($bl as $b)
					@if($u->URUSAN_KODE == $b->URUSAN_KODE)
					<tr>
						<td class="border-rincian">{{$u->URUSAN_KODE}} . {{$b->SKPD_KODE}}</td>
						<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$b->SKPD_NAMA}}</td>
						<!-- pendapatan -->
						@php
							$pendapatanFound = false;
						@endphp
						@foreach($pendapatan as $key=>$p)
							@if($b->SKPD_KODE == $p->SKPD_KODE)
								@php
									$pendapatanFound = true;
								@endphp
								<td class="border-rincian kanan rekening">{{ number_format($p->pagu,0,',','.') }}</td>
							@elseif ($key == count($pendapatan)-1 && !$pendapatanFound)
								<td class="border-rincian kanan rekening">-</td>
							@endif
						@endforeach
						<!-- BTL -->
						@php
							$btlFound = false;
							$jumlah = $b->pagu;
						@endphp
						@foreach($btl as $key=>$bt)
							@if($b->SKPD_KODE == $bt->SKPD_KODE)
								@php
									$btlFound = true;
									$jumlah += $bt->pagu;
								@endphp
								<td class="border-rincian kanan rekening">{{ number_format($bt->pagu,0,',','.') }}</td>
							@elseif ($key == count($btl)-1 && !$btlFound)
								<td class="border-rincian kanan rekening">-</td>
							@endif
						@endforeach
						<!-- BL -->
						<td class="border-rincian kanan rekening">{{ number_format($b->pagu,0,',','.') }}</td>
						<td class="border-rincian kanan rekening">{{ number_format($jumlah,0,',','.') }}</td>
					</tr>
					@endif
				@endforeach

			@endif
				
		@endforeach	
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