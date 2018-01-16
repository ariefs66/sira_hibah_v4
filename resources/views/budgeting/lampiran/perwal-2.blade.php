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
	<h5>LAMPIRAN Ia &nbsp; &nbsp; &nbsp; Rancangan Peraturan Wali Kota Bandung</h5>
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
			<h4>PEMERINTAH KOTA BANDUNG</h4>
			<h3>RINGKASAN APBD BERDASARKAN RINCIAN OBYEK PENDAPATAN, BELANJA DAN PEMBIAYAAN</h3>
			<h5>TAHUN ANGGARAN {{ $tahun }}</h5>
		</td>
	</tr>
	<tr> <td colspan="2"></td> </tr>
</table>
<table class="rincian">
	<tbody>
	<tr class="border "> 
		<td colspan="4"><b>Urusan Pemerintah : </b> {{$urusan->URUSAN_KODE}} &nbsp; &nbsp; &nbsp; {{$urusan->URUSAN_KAT1_NAMA}}<br> 
		<b>Organisasi &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;: </b>{{$skpd->SKPD_KODE}} &nbsp; {{$skpd->SKPD_NAMA}} </td> 
	</tr>	
	<tr class="border headrincian">
		<td class="border tengah" >KODE <br> REKENING </td>
		<td class="border tengah" >URAIAN</td>
		<td class="border tengah" >JUMLAH</td>
		<td class="border tengah" >DASAR HUKUM</td>
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

	@php $tot1 = 0; @endphp
	@foreach($btl1_1 as $btl)
		@php $tot1 += $btl->pagu; @endphp
	@endforeach
	@php $tot2 = 0; @endphp
	@foreach($btl1_2 as $btl)
		@php $tot2 += $btl->pagu; @endphp
	@endforeach

	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.5</td>
		<td class="border-rincian"><b>BELANJA</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($tot1+$tot2+$bl,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"></td>
	</tr>

	
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.5.1</td>
		<td class="border-rincian"><b>&nbsp; BELANJA TIDAK LANGSUNG</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($tot1+$tot2,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"></td>
	</tr>

	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.{{$btl_rek_1->REKENING_KODE}}</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; {{$btl_rek_1->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($tot1+$tot2,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"></td>
	</tr>

	
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.
			{{$btl_rek_1_1->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$btl_rek_1_1->REKENING_NAMA}}</td>
		<td class="border-rincian kanan total">{{ number_format($tot1,0,',','.') }}</td>
		<td class="border-rincian kanan total"></td>
	</tr>
	@foreach($btl1_1 as $btl)
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.
			{{$btl->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; &nbsp; {{$btl->REKENING_NAMA}}</td>
		<td class="border-rincian kanan">{{ number_format($btl->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan">{{$btl->BTL_DASHUK}}</td>
	</tr>
	@endforeach

	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.
			{{$btl_rek_1_2->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$btl_rek_1_2->REKENING_NAMA}}</td>
		<td class="border-rincian kanan total">{{ number_format($tot2,0,',','.') }}</td>
		<td class="border-rincian kanan total"></td>
	</tr>
	@foreach($btl1_2 as $btl)
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.
			{{$btl->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; &nbsp; {{$btl->REKENING_NAMA}}</td>
		<td class="border-rincian kanan">{{ number_format($btl->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan">{{$btl->BTL_DASHUK}}</td>
	</tr>
	@endforeach
	

<!-- belanja langsung -->
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.5.2</td>
		<td class="border-rincian"><b>&nbsp; BELANJA LANGSUNG</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($bl,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"></td>
	</tr>
	@foreach($bl_prog as $bp)
		@php
			$pagu_prog = 0;
		@endphp
		@foreach($bl_keg as $bk)
			@if($bp->PROGRAM_ID == $bk->PROGRAM_ID)
			@php
				$pagu_prog += $bk->BL_PAGU ;
			@endphp
			@endif
		@endforeach	
	
					
	<tr>
			<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.5.2.{{$bp->PROGRAM_KODE}}</td>
			<td class="border-rincian"> &nbsp; &nbsp; <b>{{$bp->PROGRAM_NAMA}}</b></td>
			<td class="border-rincian kanan total"><b>{{ number_format($pagu_prog,0,',','.') }}</b></td>
			<td class="border-rincian kanan total"></td>
		</tr>
		
		@foreach($bl_keg as $bk)
			@if($bp->PROGRAM_ID == $bk->PROGRAM_ID)
			<!-- kegiatan  -->
			<tr>
				<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.5.2.{{$bp->PROGRAM_KODE}}.{{$bk->KEGIATAN_KODE}}</td>
				<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$bk->KEGIATAN_NAMA}} </td>
				<td class="border-rincian kanan total">{{ number_format($bk->BL_PAGU,0,',','.') }}</td>
				<td class="border-rincian kanan total"></td>
			</tr>

				@foreach($bl_rek as $br)
					@if($bk->KEGIATAN_ID == $br->KEGIATAN_ID)
					<!-- rekening  -->
					<tr>
						<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.{{$bp->PROGRAM_KODE}}.{{$bk->KEGIATAN_KODE}}.{{$br->REKENING_KODE}}</td>
						<td class="border-rincian"> &nbsp; &nbsp; &nbsp; &nbsp; {{$br->REKENING_NAMA}} </td>
						<td class="border-rincian kanan ">{{ number_format($br->pagu,0,',','.') }}</td>
						<td class="border-rincian kanan "></td>
					</tr>
					@endif
				@endforeach

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
