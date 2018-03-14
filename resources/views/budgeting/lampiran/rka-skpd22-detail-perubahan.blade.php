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

<table class="header">
	<tr class="border">
		<td class="border" width="85%">
			<h4>RENCANA KERJA DAN ANGGARAN<br>SATUAN KERJA PERANGKAT DAERAH</h4>
		</td>
		<td rowspan="2" class="border">
			<h4>Formulir<br>RKAP-SKPD 2.2</h4>
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
	<tr class="border">
		<td colspan="10"><h4>Rincian Anggaran Belanja Langsung 
		Berdasarkan Program dan Kegiatan</h4></td>
	</tr>
	<tr class="border headrincian">
		<td class="border" rowspan="2" colspan="2" width="5%"><h5>KODE PROG./ KEG.</h5></td>
		<td class="border" rowspan="2" width="35%"><h5>URAIAN</h5></td>
		<td class="border" rowspan="2" width="10%"><h5>LOKASI<br>KEGIATAN</h5></td>
		<td class="border" rowspan="2" width="10%"><h5>TARGET<br>KINERJA</h5></td>
		<td class="border" rowspan="2" width="5%"><h5>Sumber Dana</h5></td>
		<td class="border" colspan="2" width="20%"><h5>JUMLAH (Rp)</h5></td>
	    <td class="border" colspan="2" width="15%"><h5>BERTAMBAH/(BERKURANG)</h5></td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" width="10%"><h5>SEBELUM<br>PERUBAHAN</h5></td>
		<td class="border" width="10%"><h5>SETELAH<br>PERUBAHAN<h5></td>
		<td class="border" width="10%"><h5>(Rp)<h5></td>
		<td class="border" width="5%"><h5>%</h5></td>
	</tr>	
	<tr class="border headrincian">
		<td class="border"><h5>1</h5></td>
		<td class="border"><h5>2</h5></td>
		<td class="border"><h5>3</h5></td>
		<td class="border"><h5>4</h5></td>
		<td class="border"><h5>5</h5></td>
		<td class="border"><h5>6</h5></td>
		<td class="border"><h5>7</h5></td>
		<td class="border"><h5>8</h5></td>
		<td class="border"><h5>9</h5></td>
		<td class="border"><h5>10</h5></td>
	</tr>
	@php
	$totmurni = 0;
	$totperubahan = 0;
	$totselisih = 0;
	$totpersen = 0;
	@endphp
	@foreach($bl_p as $belp)
	<tr>
		<td class="border-rincian kiri "> {{$belp->PROGRAM_KODE}} </td>
		<td class="border-rincian kiri ">  </td>
		<td class="border-rincian "> &nbsp; <b> {{$belp->PROGRAM_NAMA}} </b></td>
		<td class="border-rincian "> &nbsp; </td>
		<td class="border-rincian "> &nbsp; </td>
		<td class="border-rincian "> &nbsp; </td>
		<td class="border-rincian kanan border"> &nbsp;  {{ number_format($belp->pagu_murni,2,',','.') }}</td>
		<td class="border-rincian kanan border"> &nbsp;  {{ number_format($belp->pagu_perubahan,2,',','.') }}</td>
		@php $selisih = $belp->pagu_perubahan-$belp->pagu_murni;
		$format = $selisih >= 0 ? number_format($selisih,2,',','.') : number_format(abs($selisih),2,',','.');
		$persen = $selisih >= 0 ? number_format(abs($selisih / $belp->pagu_murni * 100),2,',','.') : '('.number_format(abs($selisih / $belp->pagu_murni * 100),2,',','.').')'; 
		$totmurni = $totmurni + $belp->pagu_murni;
		$totperubahan = $totperubahan + $belp->pagu_perubahan;
		$totselisih = $totselisih + $selisih;
		$totpersen = $totpersen + ($selisih / $belp->pagu_murni * 100);
		@endphp
		<td class="border-rincian kanan border"> {{ $format }}</td>
		<td class="border-rincian kanan border"> {{ $persen }}</td>
	</tr>	
		@foreach($bl as $bel)

			@if($belp->PROGRAM_ID == $bel->PROGRAM_ID)
			<tr>
				<td class="border-rincian kiri "> {{$bel->PROGRAM_KODE}} </td>
				<td class="border-rincian kiri "> {{$bel->KEGIATAN_KODE}} </td>
				<td class="border-rincian "> &nbsp; &nbsp; {{$bel->KEGIATAN_NAMA}} </td>
				<td class="border-rincian "> &nbsp; {{$bel->LOKASI_NAMA}} </td>

				<td class="border-rincian "> 
				@php $found = false; @endphp
				@foreach($bl_idk as $bl_i)
					@if($bl_i->BL_ID == $bel->BL_ID)
						{{$bl_i->OUTPUT_TARGET}} {{$bl_i->SATUAN_NAMA}}
						@php $found = true; @endphp
					@endif
					@if(!$found)
					@endif		
				@endforeach	
				 </td>
							

				<td class="border-rincian "> APBD </td>
				<td class="border-rincian kanan border"> &nbsp;  {{ number_format($bel->pagu_murni,2,',','.') }}</td>
				<td class="border-rincian kanan border"> &nbsp;  {{ number_format($bel->pagu_perubahan,2,',','.') }}</td>
				@php $selisih = $bel->pagu_perubahan-$bel->pagu_murni;
				$format = $selisih >= 0 ? number_format($selisih,2,',','.') : '('.number_format(abs($selisih),2,',','.').')';
				$persen = $selisih >= 0 ? number_format(abs($selisih / $bel->pagu_murni * 100),2,',','.') : '('.number_format(abs($selisih / $bel->pagu_murni * 100),2,',','.').')'; @endphp
				<td class="border-rincian kanan border"> {{ $format }}</td>
				<td class="border-rincian kanan border"> {{ $persen }}</td>
			</tr>
			@endif

		@endforeach	
	@endforeach	

		

	
	<tr class="border">
		<td class="border kanan" colspan="4"><b>Jumlah</b></td>
		<td class="border kanan"></td>
		<td class="border kanan"><b></b></td>
		<td class="border kanan"><b>{{ number_format($totmurni,2	,',','.') }}</b></td>
		<td class="border kanan"><b>{{ number_format($totperubahan,2,',','.') }}</b></td>
		<td class="border kanan"><b>{{ $totselisih >=0 ? number_format($totselisih,2,',','.') : '('.number_format(abs($totselisih),2,',','.').')' }}</b></td>
		<td class="border kanan"><b>{{ $totpersen >= 0 ? number_format($totpersen,2,',','.') : '('.number_format(abs($totpersen),2,',','.').')' }}</b></td>
	</tr>

	
	</tbody>	
</table>
<table class="ttd">
	<tr>
		<td width="75%"></td>
		<td>Bandung, 15 Maret 2018</td>
	</tr>
	<tr>
		<td></td>
		<td>{{ $skpd->SKPD_BENDAHARA }}</td>
	</tr>
	<tr>
		<td></td>
		<td><br><br><br><br><br></td>
	</tr>
	<tr>
		<td></td>
		<td><span style="border-bottom: 1px solid #000;padding-bottom: 1px;">{{ $skpd->SKPD_KEPALA }}</span></td>
	</tr>
	<tr>
		<td></td>
		<td>NIP. {{ $skpd->SKPD_KEPALA_NIP }}</td>
	</tr>
</table>
</div>
</body>
</html>