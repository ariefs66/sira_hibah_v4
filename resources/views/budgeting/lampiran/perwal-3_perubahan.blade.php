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
			font-family: bookman old style;
			font-size: 100%;
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
		.garis{
			border: 1px dashed;
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
	<h5 style="margin-right: -450px;">LAMPIRAN III Peraturan Wali Kota Bandung</h5>
	<h5 style="margin-right: -530px;">NOMOR &nbsp; &nbsp; : {{(isset($nomor_ttd) ? (strlen($nomor_ttd)>0?$nomor_ttd:'') : '')}}<!-- 475 Tahun 2018 --></h5>
	<h5 style="margin-right: -535px;">TANGGAL &nbsp;: {{(isset($tgl_ttd) ? (strlen($tgl_ttd)>0?$tgl_ttd:$tgl.' '.$bln.' '.$thn) : '')}}<!-- 16 Maret 2018 --></h5>
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
			<h4 style="margin-left: -1300px">PEMERINTAH KOTA BANDUNG</h4>
			<h3 style="margin-left: -1300px">PENJABARAN PERUBAHAN APBD <br> DAFTAR BELANJA HIBAH</h3>
			<h5 style="margin-left: -1300px">TAHUN ANGGARAN {{ $tahun }}</h5>
		</td>
	</tr>
	<tr> <td colspan="2"></td> </tr>
</table>
<table class="rincian">
	<tbody>
	<tr class="border headrincian">
		<td class="border tengah" rowspan="2">NOMOR REKENING </td>
		<td class="border tengah" rowspan="2">URAIAN</td>
		<td class="border tengah" colspan="2">JUMLAH</td>
		<td class="border tengah" colspan="2">SELISIH</td>
		<td class="border tengah" rowspan="2">KETERANGAN</td>
	</tr>
	<tr class="border headrincian">
		<td class="border tengah" >SEBELUM </td>
		<td class="border tengah" >SESUDAH</td>
		<td class="border tengah" >Rp </td>
		<td class="border tengah" >%</td>
	</tr>		
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
		<td class="border">5</td>
		<td class="border">6</td>
		<td class="border">7</td>
	</tr>
	<tr style="font-size: 5px;">
		<td class="border-rincian">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
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

	@php $tot1p=0; $tot2p=0; @endphp
	@foreach($btl1_1_p as $btl)
		@php $tot1p+=$btl->pagu; @endphp
	@endforeach
	@foreach($btl1_2_p as $btl)
		@php $tot2p+=$btl->pagu; @endphp
	@endforeach
	@php $persen=((($tot1p+$tot2p)-($tot1+$tot2))/($tot1p+$tot2p)) * 100; @endphp

	<tr>
		<td class="border-rincian"><b><br><br>5.1.4</b></td>
		<td class="border-rincian"><b><br><br>BELANJA HIBAH</b></td>
		<td class="border-rincian kanan total"><b><br><br>{{ number_format($tot1+$tot2,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b><br><br>{{ number_format($tot1p+$tot2p,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b><br><br>{{ number_format(($tot1p+$tot2p)-($tot1+$tot2),0,',','.') }}</b></td>
		@if($persen<0)
			<td class="border-rincian kanan total"><b><br><br>({{ number_format(abs($persen),0,',','.') }}%)</b></td>
		@else
		<td class="border-rincian kanan total"><b><br><br>{{ number_format($persen,0,',','.') }}%</b></td>
		@endif
		<td class="border-rincian kanan"><br><br></td>
	</tr>
	<tr>
		<td class="border-rincian"><b>{{$btl_rek_1->REKENING_KODE}}</b></td>
		<td class="border-rincian"><b>{{$btl_rek_1->REKENING_NAMA}} </b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($tot1,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($tot1+26587800000,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format(26587800000,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format(26587800000/($tot1+26587800000)*100,0,',','.') }}%</b></td>
		<td class="border-rincian kanan"></td>
	</tr>
	@foreach($btl1_1 as $btl)
	<tr>
		<td class="border-rincian">{{$btl->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp;{{$btl->REKENING_NAMA}}</td>
		<td class="border-rincian kanan garis">{{ number_format($btl->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($btl->pagu+26587800000,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format(26587800000,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format(26587800000/(26587800000+$btl->pagu)*100,0,',','.') }}%</td>
		<td class="border-rincian kanan">{{$btl->BTL_DASHUK}}</td>
	</tr>
		@foreach($btlz as $btlzx)
		@foreach($btlz_p as $btlzx_p)
		@if($btlzx->BTL_ID == $btlzx_p->BTL_ID)
		@if($btlzx->REKENING_KODE == $btl->REKENING_KODE)
		<tr>
			<td class="border-rincian"></td>
			<td class="border-rincian">
				&nbsp; {{$btlzx->BTL_NAMA}}
			</td>
			<td class="border-rincian kanan">{{ number_format($btlzx->BTL_TOTAL,0,',','.') }}</td>
			<td class="border-rincian kanan">{{ number_format($btlzx_p->BTL_TOTAL,0,',','.') }}</td>
			<td class="border-rincian kanan">{{ number_format($btlzx_p->BTL_TOTAL-$btlzx->BTL_TOTAL,0,',','.') }}</td>
			@if(!empty($btlzx_p->BTL_TOTAL))
			<td class="border-rincian kanan">{{ number_format(
				(($btlzx_p->BTL_TOTAL-$btlzx->BTL_TOTAL)/$btlzx_p->BTL_TOTAL)*100,0,',','.') }}%</td>
			@else
			<td class="border-rincian kanan">{{ number_format(0,0,',','.') }}%</td>
			@endif
			<td class="border-rincian kanan">{{$btlzx->BTL_DASHUK}}</td>
		</tr>
		@endif
		@endif
		@endforeach
		@endforeach
	@endforeach

	<tr>
		<td class="border-rincian"><b><br><br>{{$btl_rek_2->REKENING_KODE}}</b></td>
		<td class="border-rincian"><b><br><br>{{$btl_rek_2->REKENING_NAMA}} </b></td>
		<td class="border-rincian kanan total"><b><br><br>{{ number_format($tot2,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b><br><br>{{ number_format($tot2,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b><br><br>{{ number_format(0,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b><br><br>{{ number_format(0,0,',','.') }}%</b></td>
		<td class="border-rincian kanan"></td>
	</tr>
	@foreach($btl1_2 as $btl)
	<tr>
		<td class="border-rincian">{{$btl->REKENING_KODE}}</td>
		<td class="border-rincian">
			&nbsp;{{$btl->REKENING_NAMA}}
		</td>
		<td class="border-rincian kanan garis">{{ number_format($btl->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format($btl->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format(0,0,',','.') }}</td>
		<td class="border-rincian kanan garis">{{ number_format(0,0,',','.') }}%</td>
		<td class="border-rincian kanan">{{$btl->BTL_DASHUK}}</td>
	</tr>
		@foreach($btlz as $btlzx)
		@foreach($btlz_p as $btlzx_p)
		@if($btlzx->BTL_ID == $btlzx_p->BTL_ID)
		@if($btlzx->REKENING_KODE == $btl->REKENING_KODE)
		<tr>
			<td class="border-rincian"></td>
			<td class="border-rincian">
				&nbsp; {{$btlzx->BTL_NAMA}}
			</td>
			<td class="border-rincian kanan">{{ number_format($btlzx->BTL_TOTAL,0,',','.') }}</td>
			<td class="border-rincian kanan">{{ number_format($btlzx_p->BTL_TOTAL,0,',','.') }}</td>
			<td class="border-rincian kanan">{{ number_format(
				$btlzx_p->BTL_TOTAL-$btlzx->BTL_TOTAL
				,0,',','.') }}</td>
				
			@if(!empty($btlzx_p->BTL_TOTAL))
			<td class="border-rincian kanan">{{ number_format(
				(($btlzx_p->BTL_TOTAL-$btlzx->BTL_TOTAL)/$btlzx_p->BTL_TOTAL)*100
				,0,',','.') }}%</td>
			@else
			<td class="border-rincian kanan">{{ number_format(0,0,',','.') }}%</td>
			@endif
			
			<td class="border-rincian kanan">{{$btlzx->BTL_DASHUK}}</td>
		</tr>
		@endif
		@endif
		@endforeach
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
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td><b>{{(isset($jabatan_ttd) ? (strlen($jabatan_ttd)>0?$jabatan_ttd:'PJs WALIKOTA BANDUNG') : 'PJs WALIKOTA BANDUNG')}}</b></td>
	</tr>
	<tr>
		<td></td>
		<td><br><br><br><br><br></td>
	</tr>
	<tr>
		<td></td>
		<td><b>{{(isset($nama_ttd) ? (strlen($nama_ttd)>0?$nama_ttd:'MUHAMAD SOLIHIN') : 'MUHAMAD SOLIHIN')}}</b></td>
	</tr>
	<tr>
		<td></td>
		<td><br></td>
	</tr>
</table>
</div>
</body>
</html>
