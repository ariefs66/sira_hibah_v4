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
			<h4>Formulir<br>RKA-SKPD</h4>
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
		<td colspan="10"><h4>Ringkasan Anggaran Pendapatan, Belanja dan Pembiayaan <br>
		Satuan Kerja Perangkat Daerah</h4></td>
	</tr>
	<tr class="border headrincian">
		<td class="border">Kode Rekening</td>
		<td class="border">Uraian</td>
		<td class="border">Jumlah<br>(Rp)</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
	</tr>

	@php $total=0; @endphp

	<!-- pendapatan -->
	@if( empty($pendapatan) )
	<tr>
		<td class="border-rincian kiri border"><b>1</b></td>
		<td class="border-rincian border"><b> Pendapatan</b></td>
		<td class="border-rincian tengah"></td>
	</tr>
		
		@foreach($pendapatan as $pen)
		<tr>
			<td class="border-rincian kiri border"><b>{{ $urusan->URUSAN_KODE }}.{{ $skpd->SKPD_KODE }}.1.{{$pen->rekening->REKENING_KODE}}</b></td>
			<td class="border-rincian border"> {{$pen->PENDAPATAN_NAMA}} </td>
			<td class="border-rincian kanan">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }} </td>
			@php $total += $pen->PENDAPATAN_TOTAL; @endphp
		</tr>	
		@endforeach

	@endif

	<!-- belanja -->
	<tr>
		<td class="border-rincian kiri border"><b>5</b></td>
		<td class="border-rincian border"><b> Belanja</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($btl+$bl,0,',','.') }}</b></td>
	</tr>
	<!-- belanja tidak langsung -->
	<tr>
		<td class="border-rincian kiri border"><b>5.1</b></td>
		<td class="border-rincian border"><b> &nbsp; Belanja Tidak Langsung</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($btl,0,',','.') }}</b></td>
	</tr>

	<tr>
		<td class="border-rincian kiri border">5.1.1</td>
		<td class="border-rincian border">&nbsp; &nbsp; Belanja Pegawai</td>
		<td class="border-rincian kanan border">{{ number_format($btl1,0,',','.') }}</td>
	</tr>


	<!-- belanja langsung-->
	<tr>
		<td class="border-rincian kiri border"><b>5.2</b></td>
		<td class="border-rincian border"><b> &nbsp; Belanja Langsung</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($bl,0,',','.') }}</b></td>
	</tr>
		<tr>
			<td class="border-rincian kiri border">5.2.1</td>
			<td class="border-rincian border">&nbsp; &nbsp; Belanja Pegawai</td>
			<td class="border-rincian kanan border">{{ number_format($bl1,0,',','.') }}</td>
		</tr>
		<tr>
			<td class="border-rincian kiri border">5.2.2</td>
			<td class="border-rincian border">&nbsp; &nbsp; Belanja Barang dan Jasa</td>
			<td class="border-rincian kanan border">{{ number_format($bl2,0,',','.') }}</td>
		</tr>
		<tr>
			<td class="border-rincian kiri border">5.2.3</td>
			<td class="border-rincian border">&nbsp; &nbsp; Belanja Modal</td>
			<td class="border-rincian kanan border">{{ number_format($bl3,0,',','.') }}</td>
		</tr>


	<!-- pembiayaan -->
	<!-- <tr>
		<td class="border-rincian kiri border"><b>{{ $urusan->URUSAN_KODE }}.{{ $skpd->SKPD_KODE }}.3</b></td>
		<td class="border-rincian border"><b> Pembiayaan</b></td>
		<td class="border-rincian kanan border"><b>,00</b></td>
	</tr> -->	

	<tr class="border">
		<td class="border kanan" colspan="2"><b>Surplus / (Defisit)</b></td>
		<td class="border kanan"><b>
			@php $tot = $total-($btl + $bl); @endphp
			@if($tot < 0)
			 ({{ number_format(trim($tot,"-"),0,',','.') }})
			@else
			  {{ number_format($tot,0,',','.') }}
			@endif  
		</b></td>
	</tr>
	</tbody>	
</table>
<table class="ttd">
	<tr>
		<td width="75%"></td>
		<td>Bandung, 28 Desember 2017 </td>
	</tr>
	<tr>
		<td></td>
		<td>{{ $skpd->SKPD_BENDAHARA }} </td>
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