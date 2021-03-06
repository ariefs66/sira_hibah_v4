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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>	
</head>
<body onload="return download()">
<button id="btnExport">Export to xls</button>	
<div class="cetak" id="table_wrapper">
<h4>Plafon Anggaran Sementara Berdasarkan Program dan Kegiatan Tahun Anggaran {{ $tahun }}</h4>
<table class="header">
	<tr class="noborder">
		<td class="noborder">Nama Perangkat Daerah : {{ $skpd->SKPD_KODE }} {{ $skpd->SKPD_NAMA }}</td>
		<td class="kanan noborder">Total Pagu : {{ number_format($pagu->sum('BL_PAGU'),0,',','.') }}</td>
	</tr>
</table>
<table class="detail" border="1px">
	<tbody>
	<tr class="tengah">
		<td colspan="4">Nomor</td>
		<td>Program/Kegiatan</td>
		<td>Indikator</td>
		<td>Target</td>
		<td>Plafon Anggaran Sementara ﴾Rp.﴿</td>
	</tr>
	<tr class="tengah">
		<td class="tengah" colspan="4">(1)</td>
		<td class="tengah">(2)</td>
		<td class="tengah">(3)</td>
		<td class="tengah">(4)</td>
		<td class="tengah">(5)</td>
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
		<td colspan="4"><b>
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
		<td colspan="4">&nbsp;<b>{{ $p->urusan->URUSAN_NAMA }}</b></td>
	</tr>
	@endif
	<tr>
		<td width="1%"><b>{{ substr($p->urusan->URUSAN_KODE,0,1) }}</b></td>
		<td width="1%"><b>{{ substr($p->urusan->URUSAN_KODE,2,3) }}</b></td>
		<td width="1%"><b>{{ $p->PROGRAM_KODE }}</b></td>
		<td width="1%"><b></b></td>
		<td width="29%">&nbsp;&nbsp;<b>{{ $p->PROGRAM_NAMA }}</b></td>
		@if(count($p->impact) != '0')
		<td width="24%">
		@foreach($p->impact as $o)
			<b>- {{ $o->IMPACT_TOLAK_UKUR }}<br></b>
		@endforeach
		</td>
		<td width="10%">
		@foreach($p->impact as $o)
			<b>- {{ $o->IMPACT_TARGET }} {{ $o->satuan->SATUAN_NAMA }}<br></b>
		@endforeach			
		</td>
		@else
		<td width="24%"><b>-</b></td>
		<td width="10%"><b>-</b></td>
		@endif
		<td width="10%" class="kanan"><b>{{ number_format($paguprogram[$i]->sum('pagu'),0,',','.') }}</b></td>
	</tr>
	@foreach($paguprogram[$i] as $pp)
	<tr>
		<td width="1%">{{ substr($p->urusan->URUSAN_KODE,0,1) }}</td>
		<td width="1%">{{ substr($p->urusan->URUSAN_KODE,2,3) }}</td>
		<td width="1%">{{ $p->PROGRAM_KODE }}</td>
		<td width="1%">{{ $pp->kegiatan->KEGIATAN_KODE }}</td>
		<td style="padding-left: 15px"><i>{{ $pp->kegiatan->KEGIATAN_NAMA }}</i></td>
		<td>
			@if(count($pp->kegiatan->bl[0]->output) != '0')
			@foreach($pp->kegiatan->bl[0]->output as $out)
				&nbsp;<i>- {{ $out->OUTPUT_TOLAK_UKUR }}</i><br>
			@endforeach
			@endif
		</td>
		<td>
			@if(count($pp->kegiatan->bl[0]->output) != '0')
			@foreach($pp->kegiatan->bl[0]->output as $out)
				&nbsp;<i>- {{ $out->OUTPUT_TARGET }}{{ $out->satuan->SATUAN_NAMA }}</i><br>
			@endforeach
			@endif
		</td>
		<td class="kanan"><i>{{ number_format($pp->pagu,0,',','.') }}</i></td>
	</tr>
	@endforeach
	<?php $i++;?>
	@endforeach
	</tbody>
</table>
</div>
</body>
<script type="text/javascript">
	$(document).ready(function() {
		  $("#btnExport").click(function(e) {
		    e.preventDefault();
		    console.log(e);
		    //getting data from our table
		    var data_type = 'data:application/vnd.ms-excel';
		    var table_div = document.getElementById('table_wrapper');
		    var table_html = table_div.outerHTML.replace(/ /g, '%20');

		    var a = document.createElement('a');
		    a.href = data_type + ', ' + table_html;
		    a.download = 'PPAS {{ $skpd->SKPD_KODE }} {{ $skpd->SKPD_NAMA }}.xls';
		    a.click();
		  });
	});

	function download(){
		$("#btnExport").trigger('click');
	}
</script>
</html>