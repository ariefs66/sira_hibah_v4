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
			font-size: 80%;
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
		.total{
			border: 1px solid;
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
			vertical-align: middle;
			font-weight: bold;
		}
		.ttd{
			text-align: center;
		}
		table{
			width: 100%;
		}
		h3, h4, h5{
			text-align: center;
			margin-top: 0px;
			margin-bottom: 0px;
		}
		h5{
			size: 110%;
		}
		.kanan{
			text-align: right;
			padding-right: 5px;
		}
		.tengah{
			text-align: center;
		}
    	@media print {
		    footer {page-break-after: always;}
			table { page-break-inside:auto; }
	    	tr    { page-break-inside:auto; page-break-after:auto }
	    	thead { display:table-header-group; }
	    	tfoot { display:table-footer-group; }		    
		}
	</style>
</head>
<body onload="window.print()">
<div class="cetak">
	<div style="margin-left: 330px;">
	<h5>LAMPIRAN III &nbsp; &nbsp; &nbsp; Rancangan Peraturan Wali Kota Bandung</h5>
	<h5>NOMOR : </h5>
	<h5>TANGGAL : {{ $tgl }} {{ $bln }} {{ $thn }}</h5>
	</div>
	<br>
<table class="header">
	<tr class="">
		<td class="" colspan="2"></td>
	</tr>
	<tr>	
		<td class="">
			<img src="{{ url('/') }}/assets/img/bandung.png" width="80px" style="margin:3px">
		</td>	
		<td>
			<h4 style="margin-left: -1000px">PEMERINTAH KOTA BANDUNG</h4>
			<h3 style="margin-left: -1000px">PENJABARAN APBD <br> DAFTAR BELANJA HIBAH</h3>
			<h5 style="margin-left: -1000px">TAHUN ANGGARAN {{ $tahun }}</h5>
		</td>
	</tr>
	<tr> <td colspan="2"></td> </tr>
</table>
<table class="rincian">
	<tbody>
	<tr class="border headrincian">
		<td class="border tengah" >NOMOR REKENING </td>
		<td class="border tengah" >URAIAN</td>
		<td class="border tengah" >JUMLAH</td>
		<td class="border tengah" >KETERANGAN</td>
	</tr>		
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
	</tr>
	<tr style="font-size: 5px;">
		<td class="border-rincian">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
	</tr>


	@php $tot1=0; $tot2=0; @endphp
	@foreach($btl1_1 as $btl)
		@php $tot1+=$btl->pagu; @endphp
	@endforeach
	@foreach($btl1_2 as $btl)
		@php $tot2+=$btl->pagu; @endphp
	@endforeach


	<tr>
		<td class="border-rincian">5.1.4</td>
		<td class="border-rincian"><b>BELANJA HIBAH</b></td>
		<td class="border-rincian kanan"><b>{{ number_format($tot1+$tot2,0,',','.') }}</b></td>
		<td class="border-rincian kanan"></td>
	</tr>
	<tr>
		<td class="border-rincian">{{$btl_rek_1->REKENING_KODE}}</td>
		<td class="border-rincian"><b>{{$btl_rek_1->REKENING_NAMA}} </b></td>
		<td class="border-rincian kanan"><b>{{ number_format($tot1,0,',','.') }}</b></td>
		<td class="border-rincian kanan"></td>
	</tr>
	@foreach($btl1_1 as $btl)
	<tr>
		<td class="border-rincian">{{$btl->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp;{{$btl->REKENING_NAMA}}</td>
		<td class="border-rincian kanan">{{ number_format($btl->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan">{{$btl->BTL_DASHUK}}</td>
	</tr>
		@foreach($btlz as $btlzx)
		@if($btlzx->REKENING_KODE == $btl->REKENING_KODE)
		<tr>
			<td class="border-rincian"></td>
			<td class="border-rincian">
				&nbsp; {{$btlzx->BTL_NAMA}}
			</td>
			<td class="border-rincian kanan">{{ number_format($btlzx->BTL_TOTAL,0,',','.') }}</td>
			<td class="border-rincian kanan">{{$btlzx->BTL_DASHUK}}</td>
		</tr>
		@endif
		@endforeach
	@endforeach

	<tr>
		<td class="border-rincian">{{$btl_rek_2->REKENING_KODE}}</td>
		<td class="border-rincian"><b>{{$btl_rek_2->REKENING_NAMA}} </b></td>
		<td class="border-rincian kanan"><b>{{ number_format($tot2,0,',','.') }}</b></td>
		<td class="border-rincian kanan"></td>
	</tr>
	@foreach($btl1_2 as $btl)
	<tr>
		<td class="border-rincian">{{$btl->REKENING_KODE}}</td>
		<td class="border-rincian">
			&nbsp;{{$btl->REKENING_NAMA}}
		</td>
		<td class="border-rincian kanan">{{ number_format($btl->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan">{{$btl->BTL_DASHUK}}</td>
	</tr>
		@foreach($btlz as $btlzx)
		@if($btlzx->REKENING_KODE == $btl->REKENING_KODE)
		<tr>
			<td class="border-rincian"></td>
			<td class="border-rincian">
				&nbsp; {{$btlzx->BTL_NAMA}}
			</td>
			<td class="border-rincian kanan">{{ number_format($btlzx->BTL_TOTAL,0,',','.') }}</td>
			<td class="border-rincian kanan">{{$btlzx->BTL_DASHUK}}</td>
		</tr>
		@endif
		@endforeach
	@endforeach

	

	

	<tr style="font-size: 5px;">
		<td class="border-rincian">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
	</tr>		
	</tbody>	
</table>
<table class="ttd">
	<tr>
		<td></td>
		<td><br></td>
	</tr>
	<tr>
		<td width="60%"></td>
		<td>Bandung, {{ $tgl }} {{ $bln }} {{ $thn }}</td>
	</tr>
	<tr>
		<td></td>
		<td><b>WALIKOTA BANDUNG</b></td>
	</tr>
	<tr>
		<td></td>
		<td><br><br><br><br><br></td>
	</tr>
	<tr>
		<td></td>
		<td><b>MOCHAMAD RIDWAN KAMIL</b></td>
	</tr>
	<tr>
		<td></td>
		<td><br><br></td>
	</tr>
</table>
</div>
</body>
</html>
