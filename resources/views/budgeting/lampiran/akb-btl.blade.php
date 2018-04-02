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
			<h4>ANGGARAN KAS BULANAN<br>SATUAN KERJA PERANGKAT DAERAH</h4>
		</td>
		<td rowspan="2" class="border">
			<h4>Formulir<br>AKB-SKPD</h4>
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
		<td width="18%">Nama SKPD</td>
		<td width="55%">: {{$skpd->SKPD_NAMA}}</td> 
		<td></td>
	</tr>
	
</table>

<table class="rincian">
	<tbody>
	<tr class="border">
		<td colspan="19"><h4>Belanja Tidak Langsung</h4></td>
	</tr>
	<tr class="border headrincian">
		<td class="border">Kode Rekening</td>
		<td class="border">Uraian</td>
		<td class="border">Januari<br>(Rp)</td>
		<td class="border">Februari<br>(Rp)</td>
		<td class="border">Maret<br>(Rp)</td>
		<td class="border">Triwulan 1<br>(Rp)</td>
		<td class="border">April<br>(Rp)</td>
		<td class="border">Mei<br>(Rp)</td>
		<td class="border">Juni<br>(Rp)</td>
		<td class="border">Triwulan 2<br>(Rp)</td>
		<td class="border">July<br>(Rp)</td>
		<td class="border">Agustus<br>(Rp)</td>
		<td class="border">September<br>(Rp)</td>
		<td class="border">Triwulan 3<br>(Rp)</td>
		<td class="border">Oktober<br>(Rp)</td>
		<td class="border">November<br>(Rp)</td>
		<td class="border">Desember<br>(Rp)</td>
		<td class="border">Triwulan 4<br>(Rp)</td>
		<td class="border">Total Triwulan<br>(Rp)</td>
	</tr>	
	@php $total=0; @endphp
	@foreach($akb as $akbbl)
	<tr class="border headrincian">
		<td class="border" width="8%">{{$akbbl->REKENING_KODE}}</td>
		<td class="border" width="8%">{{$akbbl->REKENING_NAMA}}</td>
		<td class="border" width="8%">{{ number_format($akbbl->AKB_JAN,0,',','.') }}</td>
		<td class="border" width="8%">{{ number_format($akbbl->AKB_FEB,0,',','.') }}</td>
		<td class="border" width="8%">{{ number_format($akbbl->AKB_MAR,0,',','.') }}</td>
		@php $tri1 = $akbbl->AKB_JAN+$akbbl->AKB_FEB+$akbbl->AKB_MAR @endphp
		<td class="border" width="8%">{{ number_format($tri1,0,',','.') }}</td>
		<td class="border" width="8%">{{ number_format($akbbl->AKB_APR,0,',','.') }}</td>
		<td class="border" width="8%">{{ number_format($akbbl->AKB_MEI,0,',','.') }}</td>
		<td class="border" width="8%">{{ number_format($akbbl->AKB_JUN,0,',','.') }}</td>
		@php $tri2 = $akbbl->AKB_APR+$akbbl->AKB_MEI+$akbbl->AKB_JUN @endphp
		<td class="border" width="8%">{{ number_format($tri2,0,',','.') }}</td>
		<td class="border" width="8%">{{ number_format($akbbl->AKB_JUL,0,',','.') }}</td>
		<td class="border" width="8%">{{ number_format($akbbl->AKB_AUG,0,',','.') }}</td>
		<td class="border" width="8%">{{ number_format($akbbl->AKB_SEP,0,',','.') }}</td>
		@php $tri3 = $akbbl->AKB_JUL+$akbbl->AKB_AUG+$akbbl->AKB_SEP @endphp
		<td class="border" width="8%">{{ number_format($tri3,0,',','.') }}</td>
		<td class="border" width="8%">{{ number_format($akbbl->AKB_OKT,0,',','.') }}</td>
		<td class="border" width="8%">{{ number_format($akbbl->AKB_NOV,0,',','.') }}</td>
		<td class="border" width="8%">{{ number_format($akbbl->AKB_DES,0,',','.') }}</td>
		@php $tri4 = $akbbl->AKB_OKT+$akbbl->AKB_NOV+$akbbl->AKB_DES @endphp
		<td class="border" width="8%">{{ number_format($tri4,0,',','.') }}</td>
		<td class="border" width="8%">{{ number_format($tri1+$tri2+$tri3+$tri4,0,',','.') }}</td>
		@php $total += $tri1+$tri2+$tri3+$tri4 @endphp
	</tr>
	@endforeach

	
	<tr class="border">
		<td class="border kanan" colspan="18"><b>Jumlah</b></td>
		<td class="border kanan"><b>{{ number_format($total,0,',','.') }}</b></td>
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
		<td>Kepala {{$skpd->SKPD_NAMA}}</td>
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
		<td>NIP. {{$skpd->SKPD_KEPALA_NIP}}</td>
	</tr>
</table>
</div>
</body>
</html>