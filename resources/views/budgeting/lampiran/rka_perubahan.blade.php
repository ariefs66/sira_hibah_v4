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
			<h4>RENCANA KERJA DAN ANGGARAN PERUBAHAN<br>SATUAN KERJA PERANGKAT DAERAH</h4>
		</td>
		<td rowspan="2" class="border">
			<h4>Formulir<br>RKAP-SKPD 2.2.1</h4>
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
		<td width="25%">: {{ $bl->kegiatan->program->urusan->URUSAN_KODE }}</td> 
		<td>{{ $bl->kegiatan->program->urusan->URUSAN_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Organisasi</td>
		<td>: {{ $bl->kegiatan->program->urusan->URUSAN_KODE }}.{{ $bl->subunit->skpd->SKPD_KODE }}</td> 
		<td>{{ $bl->subunit->skpd->SKPD_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Sub Unit</td>
		<td>: {{ $bl->kegiatan->program->urusan->URUSAN_KODE }}.{{ $bl->subunit->skpd->SKPD_KODE }}.{{ $bl->subunit->SUB_KODE }}</td> 
		<td>{{ $bl->subunit->SUB_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Program</td>
		<td>: {{ $bl->kegiatan->program->urusan->URUSAN_KODE }}.{{ $bl->subunit->skpd->SKPD_KODE }}.{{ $bl->subunit->SUB_KODE }}.{{ $bl->kegiatan->program->PROGRAM_KODE }}</td> 
		<td>{{ $bl->kegiatan->program->PROGRAM_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Kegiatan</td>
		<td>: {{ $bl->kegiatan->program->urusan->URUSAN_KODE }}.{{ $bl->subunit->skpd->SKPD_KODE }}.{{ $bl->subunit->SUB_KODE }}.{{ $bl->kegiatan->program->PROGRAM_KODE }}.{{ $bl->kegiatan->KEGIATAN_KODE }}</td> 
		<td>{{ $bl->kegiatan->KEGIATAN_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Lokasi</td>
		<td colspan="2">: {{ $bl->lokasi->LOKASI_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Jumlah Tahun n-1</td>
		<td colspan="2">: Rp. 0</td>
	</tr>
	<tr class="border">
		<td>Jumlah Tahun n</td>
		<td colspan="2">: Rp. {{ number_format($bl->BL_PAGU,0,',','.') }}</td>
	</tr>
	<tr class="border">
		<td>Jumlah Tahun n+1</td>
		<td colspan="2">: Rp. 0</td>
	</tr>
</table>
<table class="indikator">
	<tbody>
	<tr>
		<td colspan="5"><h4>Indikator dan Tolok Ukur Kinerja Belanja Langsung</h4></td>
	</tr>
	<tr class="headrincian">
		<td width="15%" rowspan="2">Indikator</td>
		<td width="50%" colspan="2">Tolok Ukur Kinerja</td>
		<td width="35%" colspan="2">Target Kinerja</td>
	</tr>
	<tr>
		<td>Sebelum Perubahan</td>
		<td>Setelah Perubahan</td>
		<td>Sebelum Perubahan</td>
		<td>Setelah Perubahan</td>
	</tr>	
	@if($bl->kegiatan->program->outcome)
	@foreach($bl->kegiatan->program->outcome as $oc)
	@php $outcome=""; $target=""; @endphp
	<tr>
		<td>Capaian Program</td>
		@foreach($bl_murni->kegiatan->program->outcome as $oc_murni)
		@if($oc_murni->OUTPUT_ID==$oc->OUTCOME_ID)
			@php $outcome=$oc_murni->OUTPUT_TOLAK_UKUR; @endphp
			@php $target=$oc_murni->OUTCOME_TARGET .' '. $oc_murni->satuan->SATUAN_NAMA; @endphp
		@endif
		@endforeach	
		<td>{{(empty($outcome) ? '-' : $outcome)}}</td>
		<td>{{ $oc->OUTCOME_TOLAK_UKUR }}</td>
		<td>{{(empty($target) ? '-' : $target)}}</td>
		<td>{{ $oc->OUTCOME_TARGET }} {{ $oc->satuan->SATUAN_NAMA }}</td>
	</tr>
	@endforeach
	@endif
	<tr>
		<td>Masukan</td>
		<td>Dana yang dibutuhkan</td>
		<td>Dana yang dibutuhkan/td>
			<td>Rp. {{ number_format((empty($bl_murni->BL_PAGU) ? 0 : $bl_murni->BL_PAGU),0,',','.') }},00</td>
		<td>Rp. {{ number_format($bl->BL_PAGU,0,',','.') }},00</td>
	</tr>
	@if($bl->output)
	@foreach($bl->output as $out)
	@php $output=""; $target=""; @endphp
	<tr>
		<td>Keluaran</td>
		@if(!empty($bl_murni))
		@foreach($bl_murni->output as $out_murni)
		@if($out_murni->OUTPUT_ID==$out->OUTPUT_ID)
			@php $output=$out_murni->OUTPUT_TOLAK_UKUR; @endphp
			@php $target=$out_murni->OUTPUT_TARGET .' '. $out_murni->satuan->SATUAN_NAMA; @endphp
		@endif
		@endforeach		
		@endif
		<td>{{(empty($output) ? '-' : $output)}}</td>
		<td>{{ $out->OUTPUT_TOLAK_UKUR }}</td>
		<td>{{(empty($target) ? '-' : $target)}}</td>
		<td>{{ $out->OUTPUT_TARGET }} {{ $out->satuan->SATUAN_NAMA }}</td>
	</tr>
	@endforeach		
	@endif
	@if($bl->kegiatan->program->impact)
	@foreach($bl->kegiatan->program->impact as $im)
	@php $impact=""; $target=""; @endphp
	<tr>
		<td>Hasil</td>
		@if(!empty($bl_murni))
		@foreach($bl_murni->kegiatan->program->impact as $im_murni)
		@if($im_murni->IMPACT_ID==$im->IMPACT_ID)
			@php $impact= $im_murni->IMPACT_TOLAK_UKUR; @endphp
			@php $target= $im_murni->IMPACT_TARGET .' '. $im_murni->satuan->SATUAN_NAMA; @endphp
		@endif
		@endforeach		
		@endif
		<td>{{(empty($impact) ? '-' : $impact)}}</td>
		<td>{{ $im->IMPACT_TOLAK_UKUR }}</td>
		<td>{{(empty($target) ? '-' : $target)}}</td>
		<td>{{ $im->IMPACT_TARGET }} {{ $im->satuan->SATUAN_NAMA }}</td>
	</tr>
	@endforeach
	@endif
	</tbody>
	<tr>
		<td>Jumlah Dana</td>
		<td>Jumlah Dana</td>
		<td>Jumlah Dana</td>
		<td>Rp.</td>
		<td>Rp.</td>
	</tr>	
</table>
<table>
	<tr class="border">
		<td width="25%">Kelompok Sasaran Kegiatan</td>
		<td> : {{ $bl->sasaran->SASARAN_NAMA }}</td>
	</tr>
</table>
<table class="rincian">
	<tbody>
	<tr class="border">
		<td colspan="14"><h4>Rincian Anggaran Belanja Langsung<br>Menurut Program dan Per Kegiatan Satuan Kerja Perangkat Daerah</h4></td>
	</tr>
	<tr class="border headrincian">
		<td class="border" rowspan="2">Kode Rekening</td>
		<td class="border" rowspan="2">Uraian</td>
		<td class="border" colspan="4">Rincian Perhitungan Murni</td>
		<td class="border" rowspan="2">Jumlah Murni<br>(Rp)</td>
		<td class="border" colspan="4">Rincian Perhitungan Perubahan</td>
		<td class="border" rowspan="2">Jumlah Perubahan<br>(Rp)</td>
		<td class="border" colspan="2">Bertambah / (Berkurang)</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border">Volume</td>
		<td class="border">Satuan</td>
		<td class="border">Harga Satuan</td>
		<td class="border">PPN</td>
		<td class="border">Volume</td>
		<td class="border">Satuan</td>
		<td class="border">Harga Satuan</td>
		<td class="border">PPN</td>
		<td class="border">(Rp)</td>
		<td class="border">%</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
		<td class="border">5</td>
		<td class="border">6</td>
		<td class="border">7 = (3 x 5) + (3 x 6)</td>
		<td class="border">8</td>
		<td class="border">9</td>
		<td class="border">10</td>
		<td class="border">11</td>
		<td class="border">12 = (8 x 10) + (8 x 11)</td>
        <td class="border">13</td>
		<td class="border">14</td>

	</tr>
	<tr>
		<td class="border-rincian kiri"><b>5</b></td>
		<td class="border-rincian"><b>Belanja</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalbl_murni,0,',','.') }},00</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalbl,0,',','.') }},00</b></td>
		@if($totalbl - $totalbl_murni < 0)
		<td class="border-rincian kanan border"><b>({{ number_format(abs($totalbl - $totalbl_murni),0,',','.') }},00)</b></td>
		@else
		<td class="border-rincian kanan border"><b>{{ number_format(($totalbl - $totalbl_murni),0,',','.') }},00</b></td>
		@endif
		@if(!empty($totalbl_murni) and $totalbl_murni!=0)
		<td class="border-rincian tengah border"><b> {{ trim(number_format( ( ( $totalbl - $totalbl_murni) * 100)/$totalbl_murni, 2, ',', ' '),"-") }}% </b></td>
		@elseif(!empty($totalbl) and $totalbl!=0 and (empty($totalbl_murni) or $totalbl_murni==0))
		<td class="border-rincian tengah border"><b> {{ trim(number_format( ( ( $totalbl) * 100)/$totalbl, 2, ',', ' '),"-") }}% </b></td>
		@else
		<td class="border-rincian tengah border"><b> 0,00% </b></td>
		@endif
	</tr>
	</tr>	
	<tr>
		<td class="border-rincian kiri"><b>5.2</b></td>
		<td class="border-rincian"><b>&nbsp;Belanja Langsung</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalbl_murni,0,',','.') }},00</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalbl,0,',','.') }},00</b></td>
		@if($totalbl - $totalbl_murni < 0)
		<td class="border-rincian kanan border"><b>({{ number_format(abs($totalbl - $totalbl_murni),0,',','.') }},00)</b></td>
		@else
		<td class="border-rincian kanan border"><b>{{ number_format(($totalbl - $totalbl_murni),0,',','.') }},00</b></td>
		@endif
		@if(!empty($totalbl_murni) and $totalbl_murni!=0)
		<td class="border-rincian tengah border"><b> {{ trim(number_format( ( ( $totalbl - $totalbl_murni) * 100)/$totalbl_murni, 2, ',', ' '),"-") }}% </b></td>
		@elseif(!empty($totalbl) and $totalbl!=0 and (empty($totalbl_murni) or $totalbl_murni==0))
		<td class="border-rincian tengah border"><b> {{ trim(number_format( ( ( $totalbl) * 100)/$totalbl, 2, ',', ' '),"-") }}% </b></td>
		@else
		<td class="border-rincian tengah border"><b> 0,00% </b></td>
		@endif
	</tr>
	
	@foreach($rekening as $r)
	@if($s != 0 and !empty($rek[$s-1]->REKENING_KODE))
	@if($reke[$s-1]->REKENING_KODE != $reke[$s]->REKENING_KODE)
	<tr>
		<td class="border-rincian kiri"><b>{{ $reke[$s]->REKENING_KODE }}</b></td>
		<td class="border-rincian"><b>&nbsp;&nbsp;{{ $reke[$s]->REKENING_NAMA }}</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalreke_murni[$s],0,',','.') }},00</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalreke[$s],0,',','.') }},00</b></td>
		@if($totalreke[$s] - $totalreke_murni[$s] < 0)
		<td class="border-rincian kanan border"><b>({{ number_format(abs($totalreke[$s] - $totalreke_murni[$s]),0,',','.') }},00)</b></td>
		@else
		<td class="border-rincian kanan border"><b>{{ number_format(($totalreke[$s] - $totalreke_murni[$s]),0,',','.') }},00</b></td>
		@endif
		@if(empty($totalreke_murni[$s]) or $totalreke_murni[$s]==0)
		<td class="border-rincian tengah border"><b> - </b></td>
		@else
		<td class="border-rincian tengah border"><b> {{ trim(number_format( ( ( $totalreke[$s] - $totalreke_murni[$s]) * 100)/$totalreke_murni[$s], 2, ',', ' '),"-") }}% </b></td>
		@endif
	</tr>
	@endif
	@else
	<tr>
		<td class="border-rincian kiri"><b>{{ ((!empty($reke[$s]->REKENING_KODE)) ? $reke[$s]->REKENING_KODE : '') }}</b></td>
		<td class="border-rincian"><b>&nbsp;&nbsp;&nbsp;{{ (!empty($reke[$s]->REKENING_NAMA) ? $reke[$s]->REKENING_NAMA : '') }}</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalreke_murni[$s],0,',','.') }},00</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalreke[$s],0,',','.') }},00</b></td>
		@if($totalreke[$s] - $totalreke_murni[$s]<0)
		<td class="border-rincian kanan border"><b>({{ number_format(abs($totalreke[$s] - $totalreke_murni[$s]),0,',','.') }},00)</b></td>
		@else
		<td class="border-rincian kanan border"><b>{{ number_format(($totalreke[$s] - $totalreke_murni[$s]),0,',','.') }},00</b></td>
		@endif
		@if(empty($totalreke_murni[$s]) or $totalreke_murni[$s]==0)
			@if(!empty($totalreke[$s]) or $totalreke[$s]!=0)
		<td class="border-rincian tengah border"><b> {{ trim(number_format( ( ( $totalreke[$s]) * 100)/$totalreke[$s], 2, ',', ' '),"-") }}% </b></td>
			@else
		<td class="border-rincian tengah border"><b> - </b></td>
			@endif
		@else
		<td class="border-rincian tengah border"><b> {{ trim(number_format( ( ( $totalreke[$s] - $totalreke_murni[$s]) * 100)/$totalreke_murni[$s], 2, ',', ' '),"-") }}% </b></td>
		@endif
	</tr>
	@endif

	@if($q != 0 and !empty($rek[$q-1]->REKENING_KODE))
	@if($rek[$q-1]->REKENING_KODE != $rek[$q]->REKENING_KODE)
	<tr>
		<td class="border-rincian kiri"><b>{{ $rek[$q]->REKENING_KODE }}</b></td>
		<td class="border-rincian"><b>&nbsp;&nbsp;&nbsp;&nbsp;{{ $rek[$q]->REKENING_NAMA }}</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalrek_murni[$q],0,',','.') }},00</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalrek[$q],0,',','.') }},00</b></td>
		@if($totalrek[$q] - $totalrek_murni[$q]<0)
		<td class="border-rincian kanan border"><b>({{ number_format(abs($totalrek[$q] - $totalrek_murni[$q]),0,',','.') }},00)</b></td>
		@else
		<td class="border-rincian kanan border"><b>{{ number_format(($totalrek[$q] - $totalrek_murni[$q]),0,',','.') }},00</b></td>
		@endif
		@if(empty($totalrek_murni[$q]) or $totalrek_murni[$q]==0)
		@if(!empty($totalrek[$q]) or $totalrek[$q]!=0)
			<td class="border-rincian tengah border"><b> {{ trim(number_format( ( ( $totalrek[$q] ) * 100)/$totalrek[$q], 2, ',', ' '),"-") }}% </b></td>
			@else
			<td class="border-rincian tengah border"><b> - </b></td>
			@endif
		@else
		<td class="border-rincian tengah border"><b> {{ trim(number_format( ( ( $totalrek[$q] - $totalrek_murni[$q] ) * 100)/$totalrek_murni[$q], 2, ',', ' '),"-") }}% </b></td>
		@endif
	</tr>
	@endif
	@else
	<tr>
		<td class="border-rincian kiri"><b>{{ (!empty($rek[$q]->REKENING_KODE) ? $rek[$q]->REKENING_KODE : '') }}</b></td>
		<td class="border-rincian"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ (!empty($rek[$q]->REKENING_NAMA) ? $rek[$q]->REKENING_NAMA : '') }}</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalrek_murni[$q],0,',','.') }},00</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalrek[$q],0,',','.') }},00</b></td>
		@if($totalrek[$q] - $totalrek_murni[$q]<0)
		<td class="border-rincian kanan border"><b>({{ number_format( abs($totalrek[$q] - $totalrek_murni[$q]),0,',','.') }},00)</b></td>
		@else
		<td class="border-rincian kanan border"><b>{{ number_format( ($totalrek[$q] - $totalrek_murni[$q]),0,',','.') }},00</b></td>
		@endif
		@if(empty($totalrek_murni[$q]) or $totalrek_murni[$q]==0)
			@if(!empty($totalrek[$q]) or $totalrek[$q]!=0)
			<td class="border-rincian tengah border"><b> {{ trim(number_format( ( ( $totalrek[$q] ) * 100)/$totalrek[$q], 2, ',', ' '),"-") }}% </b></td>
			@else
			<td class="border-rincian tengah border"><b> - </b></td>
			@endif
		@else
		<td class="border-rincian tengah border"><b> {{ trim(number_format( ( ( $totalrek[$q] - $totalrek_murni[$q] ) * 100)/$totalrek_murni[$q], 2, ',', ' '),"-") }}% </b></td>
		@endif
	</tr>
	@endif
	<?php $q++;$s++;?>
	<tr>
		<td class="border-rincian kiri"><b>{{ $r->rekening->REKENING_KODE }}</b></td>
		<td class="border-rincian"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $r->rekening->REKENING_NAMA }}</b></td>
		<!--END RINCIAN PERHITUNGAN MURNI -->
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($r->TOTAL_MURNI,0,',','.') }},00</b></td>
		<!--END RINCIAN PERHITUNGAN PERUBAHAN -->
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($r->TOTAL,0,',','.') }},00</b></td>
		@if($r->TOTAL - $r->TOTAL_MURNI<0)
		<td class="border-rincian kanan border"><b> ({{ number_format(abs( $r->TOTAL - $r->TOTAL_MURNI) ,0,',','.') }},00)</b></td>
		@else
		<td class="border-rincian kanan border"><b> {{ number_format(( $r->TOTAL - $r->TOTAL_MURNI) ,0,',','.') }},00</b></td>
		@endif
		@if((empty($r->TOTAL_MURNI) or $r->TOTAL_MURNI==0) and (!empty($r->TOTAL) and $r->TOTAL!=0))
		<td class="border-rincian tengah border"><b>{{ trim(number_format( ( ( $r->TOTAL) * 100)/$r->TOTAL , 2, ',', ' '),"-") }}%</b></td>
		@elseif((!empty($r->TOTAL_MURNI) and $r->TOTAL_MURNI!=0) and (empty($r->TOTAL) or $r->TOTAL==0))
		<td class="border-rincian tengah border"><b>
			@if(!empty($r->TOTAL_MURNI) && $r->TOTAL_MURNI !== 0 )
			{{ trim(number_format( ( ( $r->TOTAL_MURNI) * 100)/$r->TOTAL_MURNI , 2, ',', ' '),"-") }}
			@else
			-
			@endif
			%</b></td>
		@elseif((empty($r->TOTAL_MURNI) or $r->TOTAL_MURNI==0) and (empty($r->TOTAL) or $r->TOTAL==0))
		<td class="border-rincian tengah border"><b>0%</td>
		@else
		<td class="border-rincian tengah border">{{ trim(number_format( ( ( $r->TOTAL - $r->TOTAL_MURNI) * 100)/$r->TOTAL_MURNI , 2, ',', ' '),"-") }}%</td>
		@endif
	</tr>
	
	<?php $l=0;?>
	@foreach($paket[$m] as $p)

	<tr>
	  <td class="border-rincian tengah"></td>
	  <td class="border-rincian">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $p->subrincian->SUBRINCIAN_NAMA }}<b></td>
	  <td class="border-rincian tengah"></td>
	  <td class="border-rincian tengah"></td>
	  <td class="border-rincian kanan"></td>
	  <td class="border-rincian kanan"></td>
	  <td class="border-rincian kanan rekening">{{ number_format($p->TOTAL_MURNI,0,',','.') }},00</td>
	  <td class="border-rincian tengah"></td>
	  <td class="border-rincian tengah"></td>
	  <td class="border-rincian kanan"></td>
	  <td class="border-rincian kanan"></td>
	  <td class="border-rincian kanan rekening">{{ number_format($p->TOTAL,0,',','.') }},00</td>
	  @if($p->TOTAL-$p->TOTAL_MURNI<0)
	  <td class="border-rincian kanan rekening">({{ number_format(abs($p->TOTAL-$p->TOTAL_MURNI),0,',','.') }},00)</td>
	  @else
	  <td class="border-rincian kanan rekening">{{ number_format($p->TOTAL-$p->TOTAL_MURNI,0,',','.') }},00</td>
	  @endif
	  @if((empty($p->TOTAL_MURNI) or $p->TOTAL_MURNI==0) and (!empty($p->TOTAL) and $p->TOTAL!=0))
		<td class="border-rincian tengah rekening">{{ trim(number_format( ( ( $p->TOTAL) * 100)/$p->TOTAL , 2, ',', ' '),"-") }}%</td>
		@elseif((!empty($p->TOTAL_MURNI) and $p->TOTAL_MURNI!=0) and (empty($p->TOTAL) or $p->TOTAL==0))
		<td class="border-rincian tengah rekening">{{ trim(number_format( ( ( $p->TOTAL_MURNI) * 100)/$p->TOTAL_MURNI , 2, ',', ' '),"-") }}%</td>
		@elseif((empty($p->TOTAL_MURNI) or $p->TOTAL_MURNI==0) and (empty($p->TOTAL) or $p->TOTAL==0))
		<td class="border-rincian tengah rekening">0%></td>
		@else
		<td class="border-rincian tengah rekening">{{ trim(number_format( ( ( $p->TOTAL - $p->TOTAL_MURNI) * 100)/$p->TOTAL_MURNI , 2, ',', ' '),"-") }}%</td>
		@endif
	 </tr>

	@foreach($komponen[$m][$l++] as $k)
	<tr>
	  <td class="border-rincian tengah"></td>
	  @if($k->PEKERJAAN_ID == 4 or $k->PEKERJAAN_ID == 5)
	  <td class="border-rincian">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>- @foreach(explode('#', $k->RINCIAN_KETERANGAN) as $info) 
    	{{$info}}@break
  	  @endforeach
  	  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;({{ $k->RINCIAN_KOEFISIEN }})</i>
  	  </td>
	  @else
	  <td class="border-rincian">
	  	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>- {{ $k->RINCIAN_KOMPONEN }}
	  	<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;({{ $k->RINCIAN_KOEFISIEN }})</i>
	  	  	  </td>

	  @endif

	<!-- RINCIAN PERHITUNGAN MURNI -->
	  @if(empty($k->RINCIAN_VOLUME_MURNI))
	  <td class="border-rincian tengah">-</td>
	  <td class="border-rincian tengah">-</td>
	  <td class="border-rincian kanan">0,00</td>
	  <td class="border-rincian kanan">0,00</td>
	  <td class="border-rincian kanan">0,00</td>
	  @else  
	  <td class="border-rincian tengah">{{ $k->RINCIAN_VOLUME_MURNI }}</td>
	  <td class="border-rincian tengah">{{ preg_replace("/[^A-Za-z]/"," ",$k->RINCIAN_KOEFISIEN) }}</td>
	  <td class="border-rincian kanan">{{ number_format($k->RINCIAN_HARGA_MURNI,0,',','.') }},00</td>

	  @if($k->RINCIAN_PAJAK == 0)
	  <td class="border-rincian kanan">0,00</td>
	  @else
	  <td class="border-rincian kanan">{{ number_format($k->RINCIAN_HARGA_MURNI/10,0,',','.') }},00 </td>
	  @endif

	  <td class="border-rincian kanan">{{ number_format($k->RINCIAN_TOTAL_MURNI,0,',','.') }},00 </td>
	  @endif
	<!--END RINCIAN PERHITUNGAN MURNI -->


	<!--RINCIAN PERHITUNGAN PERUBAHAN-->
	  <td class="border-rincian tengah">{{ $k->RINCIAN_VOLUME }}</td>
	  <td class="border-rincian tengah">{{ preg_replace("/[^A-Za-z]/"," ",$k->RINCIAN_KOEFISIEN) }}</td>
	  <td class="border-rincian kanan">{{ number_format($k->RINCIAN_HARGA,0,',','.') }},00</td>

	  @if($k->RINCIAN_PAJAK == 0)
	  <td class="border-rincian kanan">0,00</td>
	  @else
	  <td class="border-rincian kanan">{{ number_format($k->RINCIAN_HARGA/10,0,',','.') }},00 </td>
	  @endif
	  <td class="border-rincian kanan">{{ number_format($k->RINCIAN_TOTAL,0,',','.') }},00 </td>

	  @if(empty($k->RINCIAN_TOTAL_MURNI))
	  <td class="border-rincian kanan">{{ number_format(( $k->RINCIAN_TOTAL),0,',','.') }},00 </td>
	  <td class="border-rincian tengah">100,00%</td>
	  @else
	  @if($k->RINCIAN_TOTAL - $k->RINCIAN_TOTAL_MURNI<0)
	  <td class="border-rincian kanan">({{ number_format(abs( $k->RINCIAN_TOTAL - $k->RINCIAN_TOTAL_MURNI),0,',','.') }},00)</td>
	  @else
	  <td class="border-rincian kanan">{{ number_format(( $k->RINCIAN_TOTAL - $k->RINCIAN_TOTAL_MURNI),0,',','.') }},00 </td>
	  @endif
	  <td class="border-rincian tengah">-</td>	
	  @endif
	<!--END RINCIAN PERHITUNGAN PERUBAHAN -->
	  

	 </tr>
	@endforeach
	@endforeach
	<?php $m++;?>
	@endforeach
	<tr class="border">
		<td class="border kanan" colspan="6"><b>Jumlah</b></td>
		<td class="border kanan"><b>{{ number_format($total_murni,0,',','.') }},00</b></td>
		<td class="border kanan" colspan="4"><b>Jumlah</b></td>
		<td class="border kanan"><b>{{ number_format($total,0,',','.') }},00</b></td>
		<td class="border kanan"> <b> 
			@if($selisih < 0) 
			({{ number_format(trim($selisih,"-"), 2, ',', ' ') }}) 
			@else {{ number_format($selisih, 2, ',', ' ') }} 
			@endif</b></td>
		<td class="border tengah"><b> {{number_format(trim($persen,"-"), 2, ',', ' ')}}% </b></td>
	</tr>
	</tbody>	
</table>
<table class="ttd" style="page-break-inside: avoid;">
	<tr>
		<td width="75%"></td>
		<td>Bandung, {{(isset($tgl_ttd) ? (strlen($tgl_ttd)>0?$tgl_ttd:'15 Maret 2018') : '15 Maret 2018')}}</td>
	</tr>
	<tr>
		<td></td>
		<td>{{ $bl->subunit->skpd->SKPD_BENDAHARA }}</td>
	</tr>
	<tr>
		<td></td>
		<td><br><br><br><br><br></td>
	</tr>
	<tr>
		<td></td>
		<td><span style="border-bottom: 1px solid #000;padding-bottom: 1px;">{{ $bl->subunit->skpd->SKPD_KEPALA }}</span></td>
	</tr>
	<tr>
		<td></td>
		<td>NIP. {{ $bl->subunit->skpd->SKPD_KEPALA_NIP }}</td>
	</tr>
</table>
</div>
</body>
</html>
