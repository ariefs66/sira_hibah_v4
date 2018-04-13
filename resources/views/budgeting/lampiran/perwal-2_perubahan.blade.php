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
	<h5 style="margin-right: -450px;">LAMPIRAN II Peraturan Wali Kota Bandung</h5>
	<h5 style="margin-right: -525px;">NOMOR &nbsp; &nbsp; : <!-- 475 Tahun 2018 --></h5>
	<h5 style="margin-right: -530px;">TANGGAL &nbsp;: <!-- 16 Maret 2018 --></h5>
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
			<h3>PENJABARAN PERUBAHAN APBD</h3>
			<h5>TAHUN ANGGARAN {{ $tahun }}</h5>
		</td>
	</tr>
	<tr> <td colspan="2"></td> </tr>
</table>
<table class="rincian">
	<tbody>
	<tr class="border "> 
		<td colspan="7"><b>Urusan Pemerintah : </b> {{$urusan->URUSAN_KODE}} &nbsp; &nbsp; &nbsp; {{$urusan->URUSAN_KAT1_NAMA}}<br> 
		<b>Organisasi &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;: </b>{{$skpd->SKPD_KODE}} &nbsp; {{$skpd->SKPD_NAMA}} </td> 
	</tr>	
	<tr class="border headrincian">
		<td class="border tengah" >KODE <br> REKENING </td>
		<td class="border tengah" >URAIAN</td>
		<td class="border tengah" >MURNI</td>
		<td class="border tengah" >{{strtoupper($status)}}</td>
		<td class="border tengah" >SELISIH</td>
		<td class="border tengah" >DASAR HUKUM</td>
	</tr>		
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
		<td class="border">5</td>
		<td class="border">6</td>
	</tr>
	<tr style="font-size: 5px;">
		<td class="border-rincian">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
	</tr>	


	@if($tot_pen != 0)
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.00.00.{{$skpd->SKPD_KODE}}.4</td>
		<td class="border-rincian"><b>PENDAPATAN</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($tot_pen,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($tot_penp,0,',','.') }}</b></td>
		@if($tot_pens<0)
		<td class="border-rincian kanan total"><b>({{ number_format(abs($tot_pens),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan total"><b>{{ number_format($tot_pens,0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan total"></td>
	</tr>
	@if($pad != 0)
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.4.1</td>
		<td class="border-rincian">&nbsp;<b>PENDAPATAN ASLI DAERAH</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($pad,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($padp,0,',','.') }}</b></td>
		@if($pads<0)
		<td class="border-rincian kanan total"><b>({{ number_format(abs($pads),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan total"><b>{{ number_format($pads,0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan total"></td>
	</tr>
	@endif
	@if($pd != 0)
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.4.1.1</td>
		<td class="border-rincian">&nbsp; &nbsp;<b>Pajak Daerah</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($pd,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($pdp,0,',','.') }}</b></td>
		@if($pds<0)
		<td class="border-rincian kanan total"><b>({{ number_format(abs($pds),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan total"><b>{{ number_format($pds,0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan total"></td>
	</tr>
	@endif
	@php $pendapatnp = array(count($pendapatanp)); $i=0; @endphp
	@foreach($pendapatanp as $pen)
		@php $pendapatnp[] = $pen->PENDAPATAN_TOTAL; @endphp
	@endforeach
	@php $i=1;@endphp;
	@foreach($pendapatan as $pen)
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.{{$pen->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp;{{$pen->REKENING_NAMA}}</td>
		<td class="border-rincian kanan total">{{ number_format($pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
		<td class="border-rincian kanan total">{{ number_format($pendapatnp[$i],0,',','.') }}</td>
		@if(($pendapatnp[$i]-$pen->PENDAPATAN_TOTAL)<0)
		<td class="border-rincian kanan total">({{ number_format(abs($pendapatnp[$i]-$pen->PENDAPATAN_TOTAL),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan total">{{ number_format($pendapatnp[$i]-$pen->PENDAPATAN_TOTAL,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan total">{{$pen->PENDAPATAN_DASHUK}}</td>
	</tr>
	@php $i+=1;@endphp	
	@endforeach


	@endif


	@php $tot1 = 0; @endphp
	@foreach($btl1_1 as $btl)
		@php $tot1 += $btl->pagu; @endphp
	@endforeach
	@php $tot2 = 0; @endphp
	@foreach($btl1_2 as $btl)
		@php $tot2 += $btl->pagu; @endphp
	@endforeach
	@php $tot1p = 0; @endphp
	@foreach($btl1_1p as $btl)
		@php $tot1p += $btl->pagu; @endphp
	@endforeach
	@php $tot2p = 0; @endphp
	@foreach($btl1_2p as $btl)
		@php $tot2p += $btl->pagu; @endphp
	@endforeach

	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.00.00.{{$skpd->SKPD_KODE}}.5</td>
		<td class="border-rincian"><b>BELANJA</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($tot1+$tot2+$bl,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($tot1p+$tot2p+$blp,0,',','.') }}</b></td>
		@if(($tot1p+$tot2p+$blp)-($tot1+$tot2+$bl)<0)
		<td class="border-rincian kanan total"><b>({{ number_format(abs(($tot1p+$tot2p+$blp)-($tot1+$tot2+$bl)),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan total"><b>{{ number_format(($tot1p+$tot2p+$blp)-($tot1+$tot2+$bl),0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan total"></td>
	</tr>

	
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.5.1</td>
		<td class="border-rincian"><b>&nbsp; BELANJA TIDAK LANGSUNG</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($tot1+$tot2,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($tot1p+$tot2p,0,',','.') }}</b></td>
		@if(($tot1p+$tot2p)-($tot1+$tot2)<0)
		<td class="border-rincian kanan total"><b>{{ number_format(abs(($tot1p+$tot2p)-($tot1+$tot2)),0,',','.') }}</b></td>
		@else
		<td class="border-rincian kanan total"><b>{{ number_format(($tot1p+$tot2p)-($tot1+$tot2),0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan total"></td>
	</tr>

	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.00.00.{{$btl_rek_1->REKENING_KODE}}</td>
		<td class="border-rincian"><b>&nbsp; &nbsp; {{$btl_rek_1->REKENING_NAMA}}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($tot1+$tot2,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($tot1p+$tot2p,0,',','.') }}</b></td>
		@if(($tot1p+$tot2p)-($tot1+$tot2)<0)
		<td class="border-rincian kanan total"><b>({{ number_format(abs(($tot1p+$tot2p)-($tot1+$tot2)),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan total"><b>{{ number_format(($tot1p+$tot2p)-($tot1+$tot2),0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan total"></td>
	</tr>

	
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.00.00.
			{{$btl_rek_1_1->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$btl_rek_1_1->REKENING_NAMA}}</td>
		<td class="border-rincian kanan total">{{ number_format($tot1,0,',','.') }}</td>
		<td class="border-rincian kanan total">{{ number_format($tot1p,0,',','.') }}</td>
		@if($tot1p-$tot1<0)
		<td class="border-rincian kanan total">{{ number_format(abs($tot1p-$tot1),0,',','.') }}</td>
		@else
		<td class="border-rincian kanan total">{{ number_format(($tot1p-$tot1),0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan total"></td>
	</tr>
	@php $btlp = array(count($btl1_1p)); $i=0; @endphp
	@foreach($btl1_1p as $btl)
	@php	$btlp[] = $btl->pagu; @endphp
	@endforeach
	@php $i=1; @endphp
	@foreach($btl1_1 as $btl)
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.00.00.
			{{$btl->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; &nbsp; {{$btl->REKENING_NAMA}}</td>
		<td class="border-rincian kanan">{{ number_format($btl->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan">{{ number_format($btlp[$i],0,',','.') }}</td>
		@if($btlp[$i]-$btl->pagu<0)
		<td class="border-rincian kanan">({{ number_format(abs($btlp[$i]-$btl->pagu),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($btlp[$i]-$btl->pagu,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan">{{$btl->BTL_DASHUK}}</td>
	</tr>
	@php $i+=1; @endphp
	@endforeach

	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.00.00.
			{{$btl_rek_1_2->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; {{$btl_rek_1_2->REKENING_NAMA}}</td>
		<td class="border-rincian kanan total">{{ number_format($tot2,0,',','.') }}</td>
		<td class="border-rincian kanan total">{{ number_format($tot2p,0,',','.') }}</td>
		@if($tot2p-$tot2<0)
		<td class="border-rincian kanan total">({{ number_format(abs($tot2p-$tot2),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan total">{{ number_format($tot2p-$tot2,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan total"></td>
	</tr>
	
	@php $btl1_2pp = array(count($btl1_2p)); $i=0; @endphp
	@foreach($btl1_2p as $btl)
	@php	$btl1_2pp[] = $btl->pagu; @endphp
	@endforeach
	@php $i=1; @endphp
	@foreach($btl1_2 as $btl)
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.00.00.
			{{$btl->REKENING_KODE}}</td>
		<td class="border-rincian">&nbsp; &nbsp; &nbsp; &nbsp; {{$btl->REKENING_NAMA}}</td>
		<td class="border-rincian kanan">{{ number_format($btl->pagu,0,',','.') }}</td>
		<td class="border-rincian kanan">{{ number_format($btl1_2pp[$i],0,',','.') }}</td>
		@if($btl1_2pp[$i]-$btl->pagu<0)
		<td class="border-rincian kanan">({{ number_format(abs($btl1_2pp[$i]-$btl->pagu),0,',','.') }})</td>
		@else
		<td class="border-rincian kanan">{{ number_format($btl1_2pp[$i]-$btl->pagu,0,',','.') }}</td>
		@endif
		<td class="border-rincian kanan">{{$btl->BTL_DASHUK}}</td>
	</tr>
	@php $i+=1; @endphp
	@endforeach
	

<!-- belanja langsung -->
	<tr>
		<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.5.2</td>
		<td class="border-rincian"><b>&nbsp; BELANJA LANGSUNG</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($bl,0,',','.') }}</b></td>
		<td class="border-rincian kanan total"><b>{{ number_format($blp,0,',','.') }}</b></td>
		@if($bls<0)
		<td class="border-rincian kanan total"><b>({{ number_format(abs($bls),0,',','.') }})</b></td>
		@else
		<td class="border-rincian kanan total"><b>{{ number_format($bls,0,',','.') }}</b></td>
		@endif
		<td class="border-rincian kanan total"></td>
	</tr>
	@foreach($bl_prog as $bp)
		@php
			$pagu_prog = 0;
			$pagu_progp = 0;
		@endphp

		@foreach($bl_keg as $bk)
			@if($bp->PROGRAM_ID == $bk->PROGRAM_ID)
			<!-- kegiatan  -->
			@php $bkp = 0; $br_murni = 0; @endphp
			@foreach($bl_rek as $br)
				@php $brp = 0; @endphp
				@if($bk->KEGIATAN_ID == $br->KEGIATAN_ID && $bk->SUB_KODE == $br->SUB_KODE)
					<!-- rekening  -->
					@php $br_murni += $br->pagu @endphp
					@foreach($bl_rekp as $brpt)
						@php 
						if($bk->KEGIATAN_ID == $brpt->KEGIATAN_ID && $bk->SUB_KODE == $brpt->SUB_KODE && $brpt->REKENING_KODE == $br->REKENING_KODE){
							$brp += $brpt->pagu;
						}
						 @endphp
					@endforeach
					@php $bkp += $brp; @endphp
				@endif
			@endforeach
			@php
			$pagu_prog += $br_murni;
			$pagu_progp += $bkp;
			@endphp
			@endif	
		@endforeach

	<tr>
			<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.{{$bp->PROGRAM_KODE}}.5.2</td>
			<td class="border-rincian"> &nbsp; &nbsp; <b>{{$bp->PROGRAM_NAMA}}</b></td>
			<td class="border-rincian kanan total"><b>{{ number_format($pagu_prog,0,',','.') }}</b></td>
			<td class="border-rincian kanan total"><b>{{ number_format($pagu_progp,0,',','.') }}</b></td>
			@if($pagu_progp-$pagu_prog<0)
			<td class="border-rincian kanan total"><b>({{ number_format(abs($pagu_progp-$pagu_prog),0,',','.') }})</b></td>
			@else
			<td class="border-rincian kanan total"><b>{{ number_format($pagu_progp-$pagu_prog,0,',','.') }}</b></td>
			@endif
			<td class="border-rincian kanan total"></td>
		</tr>
		@foreach($bl_keg as $bk)
			@if($bp->PROGRAM_ID == $bk->PROGRAM_ID)
			<!-- kegiatan  -->
			@php $bkp = 0; $br_murni = 0; @endphp
			@foreach($bl_rek as $br)
				@php $brp = 0; @endphp
				@if($bk->KEGIATAN_ID == $br->KEGIATAN_ID && $bk->SUB_KODE == $br->SUB_KODE)
					<!-- rekening  -->
					@php $br_murni += $br->pagu @endphp
					@foreach($bl_rekp as $brpt)
						@php 
						if($bk->KEGIATAN_ID == $brpt->KEGIATAN_ID && $bk->SUB_KODE == $brpt->SUB_KODE && $brpt->REKENING_KODE == $br->REKENING_KODE){
							$brp += $brpt->pagu;
						}
						 @endphp
					@endforeach
					@php $bkp += $brp; @endphp
				@endif
			@endforeach
			<tr>
				<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.{{$bp->PROGRAM_KODE}}.{{$bk->KEGIATAN_KODE}}.5.2</td>
				<td class="border-rincian"> &nbsp; &nbsp; &nbsp; {{$bk->KEGIATAN_NAMA}}</td>
				<td class="border-rincian kanan total">{{ number_format($br_murni,0,',','.') }}</td>
				<td class="border-rincian kanan total">{{ number_format($bkp,0,',','.') }}</td>
				@if($bkp-$br_murni<0)
						<td class="border-rincian kanan total">({{ number_format(abs($bkp-$br_murni),0,',','.') }})</td>
				@else
						<td class="border-rincian kanan total">{{ number_format($bkp-$br_murni,0,',','.') }}</td>
				@endif
				<td class="border-rincian kanan total"></td>
			</tr>

				@foreach($bl_rek as $br)
					@if($bk->KEGIATAN_ID == $br->KEGIATAN_ID && $bk->SUB_KODE == $br->SUB_KODE)
					<!-- rekening  -->
					@php $brp = 0; @endphp
					@foreach($bl_rekp as $brpt)
						@php 
						if($bk->KEGIATAN_ID == $brpt->KEGIATAN_ID && $bk->SUB_KODE == $brpt->SUB_KODE && $brpt->REKENING_KODE == $br->REKENING_KODE){
							$brp = $brpt->pagu;
						}
						 @endphp
					@endforeach
					<tr>
						<td class="border-rincian">{{$urusan->URUSAN_KODE}}.{{$skpd->SKPD_KODE}}.{{$bp->PROGRAM_KODE}}.{{$bk->KEGIATAN_KODE}}.{{$br->REKENING_KODE}}</td>
						<td class="border-rincian"> &nbsp; &nbsp; &nbsp; &nbsp; {{$br->REKENING_NAMA}}</td>
						<td class="border-rincian kanan ">{{ number_format($br->pagu,0,',','.') }}</td>
						<td class="border-rincian kanan ">{{ number_format($brp,0,',','.') }}</td>
						@if($brp-$br->pagu < 0)
						 <td class="border-rincian kanan ">({{ number_format(abs($brp-$br->pagu),0,',','.') }})</td>
						@else
						<td class="border-rincian kanan ">{{ number_format($brp-$br->pagu,0,',','.') }}</td>
						@endif
						<td class="border-rincian kanan "></td>
						@php $found=false; @endphp
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
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
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
		<td><!-- Bandung, 16 {{ $bln }} {{ $thn }} --></td>
	</tr>
	<tr>
		<td></td>
		<td><b>PJs WALIKOTA BANDUNG</b></td>
	</tr>
	<tr>
		<td></td>
		<td><br><br><br><br><br></td>
	</tr>
	<tr>
		<td></td>
		<td><b>MUHAMAD SOLIHIN</b></td>
	</tr>
	<tr>
		<td></td>
		<td><br><br></td>
	</tr>
</table>
</div>
</body>
</html>
