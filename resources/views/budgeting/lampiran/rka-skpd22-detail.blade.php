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
			<h4>Formulir<br>RKA-SKPD 2.2</h4>
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
		<td class="border" rowspan="3" colspan="2">Kode</td>
		<td class="border" rowspan="3">Uraian</td>
		<td class="border" rowspan="3">Lokasi Kegiatan</td>
		<td class="border" rowspan="3">Target Kinerja (Kuantitatif)</td>
		<td class="border" colspan="5">Jumlah</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" colspan="4">Tahun n</td>
		<td class="border" rowspan="2">Tahun<br> n+1</td>
	</tr>
	<tr class="border headrincian">
		<td class="border">Belanja<br>Pegawai</td>
		<td class="border">Barang<br>& Jasa</td>
		<td class="border">Modal</td>
		<td class="border">Jumlah</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" colspan="2">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
		<td class="border">5</td>
		<td class="border">6</td>
		<td class="border">7</td>
		<td class="border">8</td>
		<td class="border">9=6+7+8</td>
	</tr>			


	@php 
	 $totpro_bl1=0; 
	 $totpro_bl2=0; 
	 $totpro_bl3=0; 
	@endphp
	
	@foreach($bl_p as $belp)

		@php 
		 $tot_bl1=0; 
		 $tot_bl2=0; 
		 $tot_bl3=0; 
		@endphp
		@foreach($bl as $bel)
			@if($belp->PROGRAM_ID == $bel->PROGRAM_ID)
					@foreach($bl1 as $bel1)
						@if($bel->BL_ID == $bel1->BL_ID )
							@php $tot_bl1 += $bel1->total; @endphp
						@endif 
					@endforeach
					
					@foreach($bl2 as $bel2)
					@if($bel->BL_ID == $bel2->BL_ID )
						@php $tot_bl2 += $bel2->total; @endphp
					@endif 
					@endforeach
					
					@foreach($bl3 as $bel3)
					@if($bel->BL_ID == $bel3->BL_ID )
						@php $tot_bl3 += $bel3->total; @endphp
					@endif 
					@endforeach
			@endif	
		@endforeach		


	<tr>
		<td class="border-rincian kiri "> {{$belp->PROGRAM_KODE}} </td>
		<td class="border-rincian kiri ">  </td>
		<td class="border-rincian "> &nbsp; <b> {{$belp->PROGRAM_NAMA}} </b></td>
		<td class="border-rincian "> &nbsp;  </td>
		<td class="border-rincian "> &nbsp;  </td>
		<td class="border-rincian border kanan"> {{ number_format($tot_bl1,0,',','.') }} </td>
		<td class="border-rincian border kanan"> {{ number_format($tot_bl2,0,',','.') }} </td>
		<td class="border-rincian border kanan"> {{ number_format($tot_bl3,0,',','.') }} </td>
		<td class="border-rincian kanan border"> <b> {{ number_format($belp->pagu,0,',','.') }} </b></td>
		<td class="border-rincian kanan border"> <b> {{ number_format($belp->pagu,0,',','.') }}</b></td>
	</tr>	

		@php $total=0; @endphp
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
				<td class="border-rincian kanan"> 
				@php
					$found = false;
				@endphp	
				@foreach($bl1 as $bel1)
				@if($bel->BL_ID == $bel1->BL_ID )
					&nbsp; {{ number_format($bel1->total,0,',','.') }}
					@php $found = true; @endphp
				@endif 
				@endforeach
				</td>

				<td class="border-rincian kanan"> 
				@php
					$found = false;
				@endphp	
				@foreach($bl2 as $bel2)
				@if($bel->BL_ID == $bel2->BL_ID )
					&nbsp; {{ number_format($bel2->total,0,',','.') }}
					@php $found = true; @endphp
				@endif 
				@endforeach
				</td>
				<td class="border-rincian kanan"> 
				@php
					$found = false;
				@endphp	
				@foreach($bl3 as $bel3)
				@if($bel->BL_ID == $bel3->BL_ID )
					&nbsp; {{ number_format($bel3->total,0,',','.') }}
					@php $found = true; @endphp
				@endif 
				@endforeach
				</td>
				<td class="border-rincian kanan "> {{ number_format($bel->pagu,0,',','.') }}</td>
				<td class="border-rincian kanan border"> {{ number_format($bel->pagu,0,',','.') }}</td>
				@php $total += $bel->BL_PAGU; @endphp
			</tr>
			@endif

		@endforeach	

		@php 
		 $totpro_bl1 += $tot_bl1; 
		 $totpro_bl2 += $tot_bl2; 
		 $totpro_bl3 += $tot_bl3;  
		@endphp

	@endforeach	

		

	
	<tr class="border">
		<td class="border kanan" colspan="5"><b>Jumlah</b></td>
		<td class="border kanan"><b>{{ number_format($totpro_bl1,0,',','.') }}</b></td>
		<td class="border kanan"><b>{{ number_format($totpro_bl2,0,',','.') }}</b></td>
		<td class="border kanan"><b>{{ number_format($totpro_bl3,0,',','.') }}</b></td>
		<td class="border kanan"><b>{{ number_format($totbl,0,',','.') }}</b></td>
		<td class="border kanan"><b>{{ number_format($totbl,0,',','.') }}</b></td>
	</tr>
	</tbody>	
</table>
<table class="ttd">
	<tr>
		<td width="75%"></td>
		<td>Bandung, {{(isset($tgl_ttd) ? (strlen($tgl_ttd)>0?$tgl_ttd:$tgl.' '.$bln.' '.$thn) : '28 Desember 2017')}}</td>
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