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
			<h4>Formulir<br>RKAP-SKPD 3.2</h4>
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
		<td colspan="10"><h4>Rincian Pengeluaran Pembiayaan</h4></td>
	</tr>

	<tr class="border headrincian">
		<td class="border" rowspan="2">Kode Rekening</td>
		<td class="border" rowspan="2">Uraian</td>
		<td class="border" colspan="2">Jumlah (Rp)</td>	
		<td class="border" colspan="2">Bertambah / (Berkurang)</td>
	</tr>
	<tr class="border headrincian">
		<td class="border">Sebelum @if($status=='pergeseran')Pergeseran @else Perubahan @endif</td>
		<td class="border">Sesudah @if($status=='pergeseran')Pergeseran @else Perubahan @endif</td>	
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
	</tr>
	
	@php $total=0; @endphp

	@foreach($pembiayaan as $pem)
		@php $total += $pem->PEMBIAYAAN_TOTAL; @endphp
	@endforeach

	@php $total_p=0; @endphp

	@foreach($pembiayaan_p as $pem)
		@php $total_p += $pem->PEMBIAYAAN_TOTAL; @endphp
	@endforeach
	<tr>
		<td class="border-rincian kiri border"> 6</td>
		<td class="border-rincian border"> <b>Pembiayaan Daerah</b>  </td>
		<td class="border-rincian kanan border"><b>{{ number_format($total,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($total_p,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>
			@php $slh = $total_p-$total @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		</b></td>
		<td class="border-rincian kanan border"><b>
			@if($total !=0)
			@php $per = ($slh * 100)/$total @endphp
			{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
			@else
			-
			@endif
		</b></td>
	</tr>

	<tr>
		<td class="border-rincian kiri border"> 6.2</td>
		<td class="border-rincian border"> <b> &nbsp; Pengeluaran Pembiayaan Daerah</b>  </td>
		<td class="border-rincian kanan border"><b>{{ number_format($total,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($total_p,0,',','.') }}</b></td>
		<td class="border-rincian kanan border"><b>
			@php $slh = $total_p-$total @endphp
			@if($slh < 0)
				({{ trim(number_format($slh,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh,0,',','.'),"-") }}
			@endif
		</b></td>
		<td class="border-rincian kanan border"><b>
			@if($total !=0)
			@php $per = ($slh * 100)/$total @endphp
			{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
			@else
			-
			@endif

		</b></td>
	</tr>

	@foreach($pembiayaan as $pem)
	@foreach($pembiayaan_p as $pem_p)
	@if($pem_p->rekening->REKENING_KODE == $pem->rekening->REKENING_KODE)
	<tr>
		<td class="border-rincian kiri border"> {{$pem->rekening->REKENING_KODE}} </td>
		<td class="border-rincian border"> &nbsp; &nbsp; {{$pem->rekening->REKENING_NAMA}} </td>
		<td class="border-rincian kanan border">{{ number_format($pem->PEMBIAYAAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan border">{{ number_format($pem_p->PEMBIAYAAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan border">
			@php $slh1 = $pem_p->PEMBIAYAAN_TOTAL-$pem->PEMBIAYAAN_TOTAL @endphp
			@if($slh1 < 0)
				({{ trim(number_format($slh1,0,',','.'),"-") }})
			@else
				{{ trim(number_format($slh1,0,',','.'),"-") }}
			@endif
		</td>
		<td class="border-rincian kanan border">
			@php $per = ($slh1 * 100)/$pem->PEMBIAYAAN_TOTAL @endphp
			{{ trim(number_format( $per, 2, ',', ' '),"-") }} %
		</td>
	</tr>
	@endif	
	@endforeach		
	@endforeach	
	
	</tbody>	
</table>

<table class="ttd">
	<tr>
		<td width="75%"></td>
		<td>Bandung, {{(isset($tgl_ttd) ? (strlen($tgl_ttd)>0?$tgl_ttd:$tgl.' '.$bln.' '.$thn) : '15 Maret 2018')}}</td>
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
<table class="detail">	
	<tr class="border">
		<td width="18%">Keterangan</td>
		<td width="25%">: </td> 
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr class="border">
		<td>Tanggal Pembahasan</td>
		<td>: </td> 
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr class="border">
		<td>Catatan Hasil Pembahasan</td>
		<td>: </td> 
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr class="border">
		<td>1.</td>
		<td></td> 
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr class="border">
		<td>2.</td>
		<td></td> 
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr class="border">
		<td>Dst</td>
		<td></td> 
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
<table class="detail">
	<tr class="border">
		<td colspan="5" class="tengah"><b>Tim Anggaran Pemerintah Daerah : </b></td>
	</tr>
	<tr class="border">
		<td class="border tengah">No</td>
		<td class="border tengah">Nama</td> 
		<td class="border tengah">NIP</td>
		<td class="border tengah">Jabatan</td>
		<td class="border tengah">Tandatangan</td>
	</tr>
	<tr class="border">
		<td class="border">1.</td>
		<td class="border"></td> 
		<td class="border"></td>
		<td class="border"></td>
		<td class="border"></td>
	</tr>
	<tr class="border">
		<td class="border">2.</td>
		<td class="border"></td> 
		<td class="border"></td>
		<td class="border"></td>
		<td class="border"></td>
	</tr>
	<tr class="border">
		<td class="border">Dst</td>
		<td class="border"></td> 
		<td class="border"></td>
		<td class="border"></td>
		<td class="border"></td>
	</tr>
</table>

</div>
</body>
</html>