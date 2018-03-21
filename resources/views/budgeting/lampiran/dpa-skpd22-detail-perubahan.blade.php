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
	<tr class="">
		<td style="border-bottom: 1px solid;" style class="tengah">
			<img src="{{ url('/') }}/assets/img/bandung.png" width="40px" style="margin:3px">
		</td>
		<td style="border-bottom: 1px solid;"  width="85%">
			<h4>DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN<br>SATUAN KERJA PERANGKAT DAERAH </h4>
		</td>
		<td class="border" width="10%">
			<h4>Formulir<br>DPPA SKPD<br>2.2</h4>
		</td>
	</tr>
	<tr>
		<td colspan=3>
			<h4>PEMERINTAH KOTA BANDUNG<br/>Tahun Anggaran {{$tahun}}</h4>
		</td>
	</tr>
</table>
<table class="border-rincian">	
	<tr>
		<td width="17%">Urusan Pemerintahan</td>
		<td width="10%">: {{ $kat1->URUSAN_KAT1_KODE }}</td> 
		<td>{{ $kat1->URUSAN_KAT1_NAMA }}</td>
	</tr>
	<tr>
		<td>Bidang Pemerintahan</td>
		<td>: {{ $urusan->URUSAN_KODE }}</td> 
		<td>{{ $urusan->URUSAN_NAMA }}</td>
	</tr>
	<tr>
		<td>Unit Organisasi</td>
		<td>: {{ $skpd->SKPD_KODE }}</td> 
		<td>{{ $skpd->SKPD_NAMA }}</td>
	</tr>
</table>
<table class="rincian">
	<tbody>
	<tr class="border">
		<td colspan="10"><h5>REKAPITULASI DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN BELANJA LANGSUNG<br> 
		MENURUT PROGRAM DAN KEGIATAN SATUAN KERJA PERANGKAT DAERAH</h5></td>
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
	@foreach($bl_p_pp as $belp)
		@php $pagu=0; @endphp
		@foreach($bl as $bel)
			@if($belp->PROGRAM_ID == $bel->PROGRAM_ID)
				@php $pagu += $bel->pagu; @endphp
			@endif	
		@endforeach

	<tr>
		<td class="border-rincian kiri "> {{$belp->PROGRAM_KODE}} </td>
		<td class="border-rincian kiri ">  </td>
		<td class="border-rincian "> &nbsp; <b> {{$belp->PROGRAM_NAMA}} </b></td>
		<td class="border-rincian "> &nbsp;  </td>
		<td class="border-rincian "> &nbsp;  </td>
		<td class="border-rincian "> &nbsp; </td>
		<td class="border-rincian kanan border"> &nbsp;  {{ number_format($pagu,0,',','.') }}</td>
		<td class="border-rincian kanan border"> &nbsp;  {{ number_format($belp->pagu,0,',','.') }}</td>
		@if($belp->pagu-$pagu<0)
		<td class="border-rincian kanan border"> ({{ number_format(abs($belp->pagu-$pagu),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan border"> {{ number_format($belp->pagu-$pagu,0,',','.') }}</td>
		@endif
		@if($pagu>0)
			@if($belp->pagu-$pagu<0)
		<td class="border-rincian kanan border"> ({{ number_format(abs(($belp->pagu-$pagu) / $belp->pagu * 100),2,',','.') }}%) </td>
			@else
		<td class="border-rincian kanan border"> {{ number_format(($belp->pagu-$pagu) / $belp->pagu * 100,2,',','.') }}% </td>
			@endif
		@else
		<td class="border-rincian kanan border"> {{ number_format(0,2,',','.') }}% </td>
		@endif
	</tr>	

		@php $total=0; @endphp
		@foreach($bl_pp as $bel)

			@if($belp->PROGRAM_ID == $bel->PROGRAM_ID)
			<tr>
				<td class="border-rincian kiri "> {{$bel->PROGRAM_KODE}} </td>
				<td class="border-rincian kiri "> {{$bel->KEGIATAN_KODE}} </td>
				<td class="border-rincian "> &nbsp; &nbsp; {{$bel->KEGIATAN_NAMA}} </td>
				<td class="border-rincian "> &nbsp; {{$bel->LOKASI_NAMA}} </td>

				<td class="border-rincian "> 
				@php $found = false; @endphp
				@foreach($bl_idk_pp as $bl_i)
					@if($bl_i->BL_ID == $bel->BL_ID)
						{{$bl_i->OUTPUT_TARGET}} {{$bl_i->SATUAN_NAMA}}
						@php $found = true; @endphp
					@endif
					@if(!$found)
					@endif		
				@endforeach	
				 </td>
							

				<td class="border-rincian "> APBD </td>
				@php $found = false; $selisih=0;@endphp
				@foreach($bl as $akb)
					@if($bel->BL_ID == $akb->BL_ID)
							<td class="border-rincian kanan"> {{ number_format($akb->pagu,0,',','.') }}</td>
						@php $found = true; $selisih=$akb->pagu; @endphp
					@endif
				@endforeach
				@if(!$found)
					<td class="border-rincian kanan">0</td>
				@endif
				@php $found = false; //Mengambil belanja perubahan @endphp
				@foreach($bl_pp as $akb)
					@if($bel->BL_ID == $akb->BL_ID)
							<td class="border-rincian kanan"> {{ number_format($akb->pagu,0,',','.') }}</td>
							@if($akb->pagu-$selisih<0)
							<td class="border-rincian kanan"> ({{ number_format(abs($akb->pagu-$selisih),0,',','.') }})</td>
							@else
							<td class="border-rincian kanan"> {{ number_format($akb->pagu-$selisih,0,',','.') }}</td>
							@endif
							@if($akb->pagu>0)
								@if($akb->pagu-$selisih<0)
							<td class="border-rincian kanan border"> ({{ number_format(abs(($akb->pagu-$selisih) / $akb->pagu * 100),2,',','.') }}%) </td>
								@else
							<td class="border-rincian kanan border"> {{ number_format(($akb->pagu-$selisih) / $akb->pagu * 100,2,',','.') }}% </td>
								@endif
							@else
							<td class="border-rincian kanan border"> {{ number_format(0,2,',','.') }}% </td>
							@endif
						@php $found = true; @endphp
					@endif
				@endforeach
				@if(!$found)
					<td class="border-rincian"></td>
					<td class="border-rincian"></td>
					<td class="border-rincian kanan border"></td>
				@endif

				@php $total += $bel->BL_PAGU; @endphp
			</tr>
			@endif

		@endforeach	
	@endforeach	

		
	<tr class="border">
		<td class="border kanan" colspan="4"><b>Jumlah</b></td>
		<td class="border kanan"></td>
		<td class="border kanan"><b></b></td>
		<td class="border kanan"><b>{{ number_format($totbl,0,',','.') }}</b></td>
		<td class="border kanan"><b>{{ number_format($totbl_pp,0,',','.') }}</b></td>
		@if($totbl_pp-$totbl<0)
		<td class="border kanan"><b>({{ number_format(abs($totbl_pp-$totbl),0,',','.') }})</b></td>
		@else
		<td class="border kanan"><b>{{ number_format($totbl_pp-$totbl,0,',','.') }}</b></td>
		@endif
		@if($totbl_pp-$totbl<0)
		<td class="border kanan"><b>({{ number_format(abs(($totbl_pp-$totbl)/$totbl_pp*100),2,',','.') }}%)</b></td>
		@else
		<td class="border kanan"><b>{{ number_format(abs(($totbl_pp-$totbl)/$totbl_pp*100),2,',','.') }}%</b></td>
		@endif		
	</tr>

	
	</tbody>	
</table>
<table class="ttd">
	<tr>
		<td width="75%"></td>
		<td>Bandung, 20 Maret 2018</td>
	</tr>
	<tr>
		<td></td>
		<td><b>Plh. Pejabat Pengelola Keuangan Daerah</b></td>
	</tr>
	<tr>
		<td></td>
		<td><br><br><br><br><br></td>
	</tr>
	<tr>
		<td></td>
		<td><span style="border-bottom: 1px solid #000;padding-bottom: 1px;">
			<!-- Drs. DADANG SUPRIATNA, MH  -->
			Drs. R Budhi Rukmana, M.AP
			 </span></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<!-- NIP. 19610308 199103 1 009 -->
			NIP. 19690712 198910 1 001
		</td>
	</tr>
</table>
</div>
</body>
</html>