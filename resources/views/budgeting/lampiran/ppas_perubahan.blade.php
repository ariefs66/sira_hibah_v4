<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<html>
<head>
	<title>{{ $skpd->SKPD_ID }} {{ $skpd->SKPD_NAMA }}</title>
	<style type="text/css">
		* {
	      margin: 0;
	      padding: 0;
	    }
		body{
			font-family: Tahoma;
			font-size: 60%;
		}
		table{
			width: 100%;
		}
		td{
			padding-left: 3px;
		}
		table, tr, td{
			border-collapse: collapse;
		}
		.detail{
			margin-top: 5px;
		}
		.detail > tbody > tr > td{
			padding: 3px;
			border : 1px solid;
		}
		.header{
			margin-top: 20px;
		}
		h4, h5{
			text-align: center;
			margin-top: 0px;
			margin-bottom: 0px;
		}
		.kanan{
			text-align: right;
			padding-right: 5px;
		}
		.tengah{
			text-align: center;
			vertical-align: middle;
		}
		.noborder{
			border-style: none;
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
<h4>Plafon Anggaran Sementara Berdasarkan Program dan Kegiatan Tahun Anggaran {{ $tahun }}</h4>
<table class="header">
	<tr class="noborder">
		<td class="noborder"><b> Nama Perangkat Daerah : {{ $skpd->SKPD_KODE }} {{ $skpd->SKPD_NAMA }} </b></td>
		<td class="kanan noborder"><b>Total Pagu : {{ number_format($pagu->sum('BL_PAGU'),0,',','.') }} </b></td>
	</tr>
</table>
<table class="detail">
	<tbody>
	<tr class="tengah">
		<td colspan="4" rowspan="3">Nomor</td>
		<td rowspan="3">Urusan/ Bidang Urusan Pemerintahan Daerah <br/>dan Program / Kegiatan</td>
		<td rowspan="2" colspan="2">Indikator Kinerja Program / Kegiatan</td>
		<td colspan="5">TAHUN {{ $tahun}}</td>
	</tr>
	<tr>
		<td colspan="2" class="tengah">Target Pencapaian Kinerja</td>
		<td colspan="3" class="tengah">Pagu Indikatif</td>
	</tr>
	<tr>
		<td class="tengah">Sebelum Perubahan</td>	
		<td class="tengah">Sesudah Perubahan</td>	
		<td class="tengah">Sebelum Perubahan</td>	
		<td class="tengah">Sesudah Perubahan</td>	
		<td class="tengah">Sebelum Perubahan</td>	
		<td class="tengah">Sesudah Perubahan</td>	
		<td class="tengah">Jumlah Perubahan (+/-)</td>	
	</tr>
	<tr class="tengah">
		<td class="tengah" colspan="4">(1)</td>
		<td class="tengah">(2)</td>
		<td class="tengah">(3)</td>
		<td class="tengah">(4)</td>
		<td class="tengah">(5)</td>
		<td class="tengah">(6)</td>
		<td class="tengah">(7)</td>
		<td class="tengah">(8)</td>
		<td class="tengah">(9)</td>
	</tr>
	<tr>
	@foreach($program as $p)
	@if($urusankode != substr($p->urusan->URUSAN_KODE,0,1))
	<?php $urusankode = substr($p->urusan->URUSAN_KODE,0,1); ?>
	<tr>
		<td width="1%"><b>{{ substr($p->urusan->URUSAN_KODE,0,1) }}</b></td>
		<td width="1%"><b></b></td>
		<td width="1%"><b></b></td>
		<td width="1%"><b></b></td>
		<td colspan="8"><b>
			@if(substr($p->urusan->URUSAN_KODE,0,1) == 1)Urusan Wajib Pelayanan Dasar
			@elseif(substr($p->urusan->URUSAN_KODE,0,1) == 2)Urusan Wajib Bukan Pelayanan Dasar
			@elseif(substr($p->urusan->URUSAN_KODE,0,1) == 3)Urusan Pilihan
			@elseif(substr($p->urusan->URUSAN_KODE,0,1) == 4)Urusan Penunjang
			@endif</b>
		</td>
	</tr>
	@endif
	@if($bidangkode != $p->urusan->URUSAN_KODE )
	<?php $bidangkode = $p->urusan->URUSAN_KODE; ?>
	<tr>
		<td width="1%"><b>{{ substr($p->urusan->URUSAN_KODE,0,1) }}</b></td>
		<td width="1%"><b>{{ substr($p->urusan->URUSAN_KODE,2,3) }}</b></td>
		<td width="1%"><b></b></td>
		<td width="1%"><b></b></td>
		<td colspan="8">&nbsp;<b>{{ $p->urusan->URUSAN_NAMA }}</b></td>
	</tr>
	@endif
	<tr>
		<td width="1%"><b>{{ substr($p->urusan->URUSAN_KODE,0,1) }}</b></td>
		<td width="1%"><b>{{ substr($p->urusan->URUSAN_KODE,2,3) }}</b></td>
		<td width="1%"><b>{{ $p->PROGRAM_KODE }}</b></td>
		<td width="1%"><b></b></td>
		<td width="29%">&nbsp;&nbsp;<b>{{ $p->PROGRAM_NAMA }}</b></td>
		@php $targetmurni = ''; $target = ''; $count = 0; @endphp
		@foreach($programmurni as $pm)
		@if($pm->PROGRAM_ID==$p->PROGRAM_ID)
		@if(count($pm->impact) != '0')
		@php $targetmurni = ' ';$count++; @endphp
		<td width="24%">
		@foreach($pm->impact as $o)
			<b>{{ $o->IMPACT_TOLAK_UKUR }}</b><br/>
			@php $targetmurni .= '<b>'.$o->IMPACT_TARGET.' '.$o->satuan->SATUAN_NAMA.'</b><br/>'; @endphp
		@endforeach
		@else
		<td width="24%"><b>-</b></td>
		@endif
		@endif
		@endforeach
		@if(count($p->impact) != '0')
		@php $target = ' '; @endphp
		<td width="24%">
		@foreach($p->impact as $o)
			<b>{{ $o->IMPACT_TOLAK_UKUR }}</b><br/>
			@php $target .= '<b>'.$o->IMPACT_TARGET.' '.$o->satuan->SATUAN_NAMA.'</b><br/>'; @endphp
		@endforeach
		</td>
		<td width="10%">
			{!! $targetmurni !!}
		</td>
		<td width="10%">
			{!! $target !!}
		</td>
		@else
		<td width="24%"><b></b></td>
		<td width="10%"><b></b></td>
		<td width="10%"><b></b></td>
		@endif
		<td width="10%" class="kanan"><b>Rp.{{ number_format($paguprogrammurni[$i]->sum('pagu'),0,',','.') }}</b></td>
		<td width="10%" class="kanan"><b>Rp.{{ number_format($paguprogram[$i]->sum('pagu'),0,',','.') }}</b></td>
		<td width="10%" class="kanan">
			@if(($paguprogram[$i]->sum('pagu') - $paguprogrammurni[$i]->sum('pagu'))<0)
			<b>({{ number_format(abs(($paguprogram[$i]->sum('pagu') - $paguprogrammurni[$i]->sum('pagu'))),0,',','.') }})</b>
			@else
			<b>{{ number_format(($paguprogram[$i]->sum('pagu') - $paguprogrammurni[$i]->sum('pagu')),0,',','.') }}</b>
			@endif
		</td>
	</tr>
	@foreach($paguprogram[$i] as $pp)
	@php $indikator='';$indikatormurni='';$targetmurni='';$target='';$pagumurni=0;$pagu=0; @endphp
		@foreach($paguprogrammurni[$i] as $ppm)
		@if($ppm->KEGIATAN_ID == $pp->KEGIATAN_ID)
		@if(count($ppm->kegiatan->bl[0]->output) != '0')
			@foreach($ppm->kegiatan->bl[0]->output as $out)
				@php $indikatormurni = "&nbsp;".$out->OUTPUT_TOLAK_UKUR."<br>";
					 $targetmurni = "&nbsp;".$out->OUTPUT_TARGET." ".$out->satuan->SATUAN_NAMA."<br/>";
				$pagumurni = $ppm->pagu; @endphp
			@endforeach
			@endif
		@endif
		@endforeach
		@if(count($pp->kegiatan->bl[0]->output) != '0')
			@foreach($pp->kegiatan->bl[0]->output as $out)
				@php $indikator = "&nbsp;".$out->OUTPUT_TOLAK_UKUR."<br>";
					$target = "&nbsp;<i>".$out->OUTPUT_TARGET." ".$out->satuan->SATUAN_NAMA."</i><br/>";
				$pagu = $pp->pagu; @endphp
			@endforeach
			@endif		
	@if($pagumurni > 0 or $pagu > 0)
	<tr>
		<td width="1%">{{ substr($p->urusan->URUSAN_KODE,0,1) }}</td>
		<td width="1%">{{ substr($p->urusan->URUSAN_KODE,2,3) }}</td>
		<td width="1%">{{ $p->PROGRAM_KODE }}</td>
		<td width="1%">{{ $pp->kegiatan->KEGIATAN_KODE }}</td>
		<td style="padding-left: 15px">{{ $pp->kegiatan->KEGIATAN_NAMA }}</td>
		<td>{!! $indikatormurni !!}</td>
		<td>{!! $indikator !!}</td>
		<td>{!! $targetmurni !!}</td>
		<td>{!! $target !!}</td>
		<td class="kanan">Rp.{{ number_format($pagumurni,0,',','.') }}</td>
		<td class="kanan">Rp.{{ number_format($pagu,0,',','.') }}</td>
		<td class="kanan">
			@if(($pagu - $pagumurni)<0)
			({{ number_format(abs($pagu - $pagumurni),0,',','.') }})
			@else
			{{ number_format($pagu - $pagumurni,0,',','.') }}
			@endif
		</td>
	</tr>
	@endif
	@endforeach
	<?php $i++;?>
	@endforeach
	</tbody>
</table>
</div>
</body>
</html>