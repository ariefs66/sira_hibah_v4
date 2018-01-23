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

<table class="">
	<br><br><br><br><br>
	<tr>
		<td class="tengah">
			<img src="{{ url('/') }}/assets/img/bandung.png" width="80px" style="margin:3px">
		</td>
	</tr>
	<br>
	<tr class="">
		<td class="" width="%">
			<h4><br>PEMERINTAH KOTA BANDUNG<br><br>
				DOKUMEN PELAKSANAAN ANGGARAN <br> 
				SATUAN KERJA PERANGKAT DAERAH <br> 
				(DPA SKPD)<br> <br>
				{{ $skpd->SKPD_NAMA }} <br>
			TAHUN ANGGARAN {{$tahun}} <br> <br> <br></h4> 
		</td>
		<td rowspan="" class="">
		</td>
	</tr>
</table>
<table class="">
	<tr class="">
		<td>&nbsp; </td>
		<td>&nbsp; </td>
		<td width="500px">
			<table class="border" width="100px">
				<tr class="border">
					<td class="border tengah"><b>Kode</b></td>
					<td class="border tengah"><b>Nama Formulir</b></td>
				</tr>
				<tr class="border-rincian">
					<td class="border">DPA-SKPD</td>
					<td class="border">Ringkasan Dokumen Pelaksanaan Anggaran Satuan Kerja Perangkat Daerah</td>
				</tr>
				<tr class="border">
					<td class="border">DPA-SKPD1</td>
					<td class="border">Rincian Dokumen Pelaksanaan Anggaran Pendapatan Satuan Kerja Perangkat Daerah</td>
				</tr>
				<tr class="border">
					<td class="border">DPA-SKPD 2.1</td>
					<td class="border">Rincian Dokumen Pelaksanaan Anggaran Belanja Tidak Langsung Satuan Kerja Perangkat Daerah</td>
				</tr>
				<tr class="border">
					<td class="border">DPA-SKPD 2.2</td>
					<td class="border">Rekapitulasi Belanja Langsung Menurut Program dan Kegiatan Satuan Kerja Perangkat Daerah </td>
				</tr>
				<tr class="border">
					<td class="border">DPA-SKPD 2.2.1</td>
					<td class="border">Rincian Dokumen Pelaksanaan Anggaran Belanja Langsung Program dan Per Kegiatan Satuan Kerja Perangkat Daerah</td>
				</tr>
				<tr class="border">
					<td class="border">DPA-SKPD 3.1</td>
					<td class="border">Rincian Penerimaan Pembiayaan</td>
				</tr>
				<tr class="border">
					<td class="border">DPA-SKPD 3.2</td>
					<td class="border">Rincian Pengeluaran Pembiayaan</td>
				</tr>
			</table>
		</td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
	</tr>
</table>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br>


<table class="header">
	<tr class="border">
		<td class="border" width="85%">
			<h4>RINGKASAN DOKUMEN PELAKSANAAN ANGGARAN<br>SATUAN KERJA PERANGKAT DAERAH <br> TAHUN ANGGARAN {{$tahun}}</h4>
		</td>
		<td rowspan="2" class="border">
			<h4>Formulir<br>DPA-SKPD</h4>
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
			<td class="border-rincian kanan">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }},00 </td>
			@php $total += $pen->PENDAPATAN_TOTAL; @endphp
		</tr>	
		@endforeach

	@endif

	<tr>
		<td class="border-rincian kiri border"><b>5</b></td>
		<td class="border-rincian border"><b> Belanja</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($btl+$bl,0,',','.') }},00</b></td>
	</tr>
	<!-- belanja tidak langsung -->
	<tr>
		<td class="border-rincian kiri border"><b>5.1</b></td>
		<td class="border-rincian border"><b> &nbsp; Belanja Tidak Langsung</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($btl,0,',','.') }},00</b></td>
	</tr>

	<tr>
		<td class="border-rincian kiri border">5.1.1</td>
		<td class="border-rincian border">&nbsp; &nbsp; Belanja Pegawai</td>
		<td class="border-rincian kanan border">{{ number_format($btl1,0,',','.') }},00</td>
	</tr>


	<!-- belanja langsung-->
	<tr>
		<td class="border-rincian kiri border"><b>5.2</b></td>
		<td class="border-rincian border"><b> &nbsp; Belanja Langsung</b></td>
		<td class="border-rincian kanan border"><b>{{ number_format($bl,0,',','.') }},00</b></td>
	</tr>
		<tr>
			<td class="border-rincian kiri border">5.2.1</td>
			<td class="border-rincian border">&nbsp; &nbsp; Belanja Pegawai</td>
			<td class="border-rincian kanan border">{{ number_format($bl1,0,',','.') }},00</td>
		</tr>
		<tr>
			<td class="border-rincian kiri border">5.2.2</td>
			<td class="border-rincian border">&nbsp; &nbsp; Belanja Barang dan Jasa</td>
			<td class="border-rincian kanan border">{{ number_format($bl2,0,',','.') }},00</td>
		</tr>
		<tr>
			<td class="border-rincian kiri border">5.2.3</td>
			<td class="border-rincian border">&nbsp; &nbsp; Belanja Modal</td>
			<td class="border-rincian kanan border">{{ number_format($bl3,0,',','.') }},00</td>
		</tr>

		<tr class="border">
		<td class="border kanan" colspan="2"><b>Surplus / (Defisit)</b></td>
		<td class="border kanan"><b>
			@php $tot = $total - $btl + $bl; @endphp
			@if($tot < 0)
			 ({{ number_format(trim($tot,"-"),0,',','.') }})
			@else
			 {{ number_format($tot,0,',','.') }}
			@endif  
		,00</b></td>
	</tr>

	</tbody>	
</table>
<table class="detail">
	<tr class="border">
		<td colspan="7" class="tengah"><b>Rencana Pelaksanaan Anggaran <br> Satuan Kerja Perangkat Daerah Per Triwulan </b></td>
	</tr>
	<tr class="border">
		<td class="border tengah" rowspan="2">No</td>
		<td class="border tengah" rowspan="2">Uraian</td> 
		<td class="border tengah" colspan="5">Triwulan</td>
	</tr>
	<tr class="border">
		<td class="border tengah">I</td>
		<td class="border tengah">II</td> 
		<td class="border tengah">III</td>
		<td class="border tengah">IV</td>
		<td class="border tengah">Jumlah</td>
	</tr>
	<tr class="border">
		<td class="border tengah">1</td>
		<td class="border tengah">2</td> 
		<td class="border tengah">3</td>
		<td class="border tengah">4</td>
		<td class="border tengah">5</td>
		<td class="border tengah">6</td>
		<td class="border tengah">7=3+4+5+6</td>
	</tr>
	<tr class="border">
		<td class="border">1</td>
		<td class="border">Pendapatan</td> 
		<td class="border kanan">{{ number_format($akb_pend_t1->total,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pend_t2->total,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pend_t3->total,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pend_t4->total,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pend_t1->total+$akb_pend_t2->total+$akb_pend_t3->total+$akb_pend_t4->total,0,',','.') }}</td>
	</tr>
	<tr class="border">
		<td class="border">2.1</td>
		<td class="border">Belanja Tidak Langsung</td> 
		<td class="border kanan">{{ number_format($akb_btl_t1->total,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_btl_t2->total,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_btl_t3->total,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_btl_t4->total,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_btl_t1->total+$akb_btl_t2->total+$akb_btl_t3->total+$akb_btl_t4->total,0,',','.') }}</td>	
	</tr>
	<tr class="border">
		<td class="border">2.2</td>
		<td class="border">Belanja Langsung</td> 
		<td class="border kanan">{{ number_format($akb_bl_t1->total,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_bl_t2->total,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_bl_t3->total,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_bl_t4->total,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_bl_t1->total+$akb_bl_t2->total+$akb_bl_t3->total+$akb_bl_t4->total,0,',','.') }}</td>
	</tr>
	<tr class="border">
		<td class="border">3.1</td>
		<td class="border">Penerimaan Pembiayaan</td> 
		<td class="border kanan">{{ number_format($akb_pem->tri1,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pem->tri2,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pem->tri3,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pem->tri4,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_pem->tri1+$akb_pem->tri2+$akb_pem->tri3+$akb_pem->tri4,0,',','.') }}</td>
	</tr>
	<tr class="border">
		<td class="border">3.2</td>
		<td class="border">Pengeluaran Pembiayaan</td> 
		<td class="border kanan">{{ number_format($akb_peng->tri1,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_peng->tri2,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_peng->tri3,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_peng->tri4,0,',','.') }}</td>
		<td class="border kanan">{{ number_format($akb_peng->tri1+$akb_peng->tri2+$akb_peng->tri3+$akb_peng->tri4,0,',','.') }}</td>
	</tr>
</table>
<table class="ttd">
	<tr>
		<td width="75%"></td>
		<td>Bandung, {{$tgl}} {{$bln}} {{$tahun}}</td>
	</tr>
	<tr>
		<td></td>
		<td>Kepala {{ $skpd->SKPD_NAMA }}</td>
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