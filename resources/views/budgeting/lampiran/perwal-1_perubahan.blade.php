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
			line-height: 1.5;
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
		.text_blok{
            font-weight: bold;
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
	<h5 style="margin-right: -450px;">LAMPIRAN I Peraturan Wali Kota Bandung</h5>
	<h5 style="margin-right: -520px;">NOMOR &nbsp; &nbsp; : 1320 Tahun 2018</h5>
	<h5 style="margin-right: -525px;">TANGGAL &nbsp;: 16 Maret 2018</h5>
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
	<tr class="border headrincian">
		<td class="border tengah" >NOMOR <br> URUT </td>
		<td class="border tengah" >URAIAN</td>
		<td class="border tengah" >MURNI</td>
		<td class="border tengah" >PERUBAHAN</td>
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

	@foreach($detil as $rs)
    @if ($rs['tingkat']==1)
    <tr>
        <td class="border-rincian text_blok">{{ $rs['koderekening'] }}</td>
        <td class="border-rincian text_blok">{{ $rs['namarekening'] }}</td>
        @if($rs['koderekening']==6)
        <td class="border-rincian text_blok kanan total">{{ number_format((float)$totalpenerimaan-$totalpengeluaran,2,'.',',') }}</td>
        @elseif($rs['koderekening']==5)
        <td class="border-rincian text_blok kanan total">{{ number_format(ceil($rs['totalrekening']),2,'.',',') }}</td>
        @else
        <td class="border-rincian text_blok kanan total">{{ number_format((float)$rs['totalrekening'],2,'.',',') }}</td>
        @endif
		@if($rs['koderekening']==6)
        <td class="border-rincian text_blok kanan total">{{ number_format((float)$totalpenerimaanp-$totalpengeluaranp,2,'.',',') }}</td>
        @elseif($rs['koderekening']==5)
        <td class="border-rincian text_blok kanan total">{{ number_format(ceil($rs['totalrekeningp']),2,'.',',') }}</td>
        @else
        <td class="border-rincian text_blok kanan total">{{ number_format((float)$rs['totalrekeningp'],2,'.',',') }}</td>
        @endif
        @if($rs['koderekening']==6)
		@php $selisih = ($totalpenerimaanp-$totalpengeluaranp) - ($totalpenerimaan-$totalpengeluaran)@endphp
        <td class="border-rincian text_blok kanan total">{{ number_format((float)($selisih),2,'.',',') }}</td>
        @elseif($rs['koderekening']==5)
        <td class="border-rincian text_blok kanan total">{{ number_format(ceil($rs['totalrekeningp']-$rs['totalrekening']),2,'.',',') }}</td>
        @else
        <td class="border-rincian text_blok kanan total">{{ number_format((float)$rs['totalrekeningp']-$rs['totalrekening'],2,'.',',') }}</td>
        @endif
        <td class="border-rincian kanan "></td>
    </tr>
    @endif
    @if ($rs['tingkat']==2)
    <tr>
        <td class="border-rincian text_blok">{{ $rs['koderekening'] }}</td>
        <td class="border-rincian text_blok" style='padding-left: 10px'>{{ $rs['namarekening'] }}</td>
        @if($rs['koderekening']=='5.2')
        <td class="border-rincian text_blok kanan total">{{ number_format((float)($rs['totalrekening']+0.01),2,'.',',') }}</td>
        @else
        <td class="border-rincian text_blok kanan total">{{ number_format((float)$rs['totalrekening'],2,'.',',') }}</td>
        @endif
		@if($rs['koderekening']=='5.2')
        <td class="border-rincian text_blok kanan total">{{ number_format((float)($rs['totalrekeningp']+0.01),2,'.',',') }}</td>
        @else
        <td class="border-rincian text_blok kanan total">{{ number_format((float)$rs['totalrekeningp'],2,'.',',') }}</td>
        @endif
		@if($rs['koderekening']=='5.2')
        <td class="border-rincian text_blok kanan total">{{ number_format((float)(($rs['totalrekeningp']-$rs['totalrekening'])+0.01),2,'.',',') }}</td>
        @else
        <td class="border-rincian text_blok kanan total">{{ number_format((float)$rs['totalrekeningp']-$rs['totalrekening'],2,'.',',') }}</td>
        @endif
        <td class="border-rincian">{{ $rs['dashuk'] }}</td>
    </tr>
    @endif
    @if ($rs['tingkat']==3)
    <tr>
        <td class="border-rincian">{{ $rs['koderekening'] }}</td>
        @if(strlen($rs['koderekening'])==5)
        <td class="border-rincian" style='padding-left: 20px'>{{ $rs['namarekening'] }}</td>
        @elseif(strlen($rs['koderekening'])==8)
        <td class="border-rincian" style='padding-left: 30px'>{{ $rs['namarekening'] }}</td>
        @else
        <td class="border-rincian" style='padding-left: 40px'>{{ $rs['namarekening'] }}</td>
        @endif
        @if($rs['koderekening']=='5.2.2' or $rs['koderekening']=='5.2.3')
        <td class="border-rincian text_blok kanan total">{{ number_format((float)($rs['totalrekening']+0.01),2,'.',',') }}</td>
        @else
        <td class="border-rincian kanan">{{ number_format((float)$rs['totalrekening'],2,'.',',') }}</td>
        @endif
        @if($rs['koderekening']=='5.2.2' or $rs['koderekening']=='5.2.3')
        <td class="border-rincian text_blok kanan total">{{ number_format((float)($rs['totalrekeningp']+0.01),2,'.',',') }}</td>
        @else
        <td class="border-rincian kanan">{{ number_format((float)$rs['totalrekeningp'],2,'.',',') }}</td>
        @endif
        @if($rs['koderekening']=='5.2.2' or $rs['koderekening']=='5.2.3')
			@if($rs['totalrekeningp']-$rs['totalrekening']<0)
			<td class="border-rincian text_blok kanan total">({{ number_format(abs((float)(($rs['totalrekeningp']-$rs['totalrekening'])+0.01)),2,'.',',') }})</td>
        	@else
			<td class="border-rincian text_blok kanan total">{{ number_format((float)(($rs['totalrekeningp']-$rs['totalrekening'])+0.01),2,'.',',') }}</td>
        	@endif
        @else
			@if($rs['totalrekeningp']-$rs['totalrekening']<0)
			<td class="border-rincian kanan">({{ number_format(abs((float)$rs['totalrekeningp']-$rs['totalrekening']),2,'.',',') }})</td>
        	@else
			<td class="border-rincian kanan">{{ number_format((float)$rs['totalrekeningp']-$rs['totalrekening'],2,'.',',') }}</td>
        	@endif
        @endif
        <td class="border-rincian">{{ $rs['dashuk'] }}</td>
    </tr>
    @endif
    @if ($rs['tingkat']==4)
    <tr>
        <td class="border-rincian">&nbsp;</td>
        <td class="border-rincian text_blok kanan">{{ $rs['namajumlah'] }}</td>
        <td class="border-rincian text_blok kanan total">
            @if($rs['totaljumlah']<0)
            ({{ number_format(abs((float)$rs['totaljumlah']),2,'.',',') }})
            @else
            {{ number_format((float)$rs['totaljumlah'],2,'.',',') }}
            @endif
        </td>
		<td class="border-rincian text_blok kanan total">
            @if($rs['totaljumlahp']<0)
            ({{ number_format(abs((float)$rs['totaljumlahp']),2,'.',',') }})
            @else
            {{ number_format((float)$rs['totaljumlahp'],2,'.',',') }}
            @endif
        </td>
		<td class="border-rincian text_blok kanan total">
		@php $tots = ($rs['totaljumlahp']-$rs['totaljumlah']); @endphp	
            @if($tots<0)
            ({{ number_format(abs((float)$tots),2,'.',',') }})
            @else
            {{ number_format((float)$tots,2,'.',',') }}
            @endif
        </td>
        <td class="border-rincian kanan "></td>
    </tr>
    @endif
    @endforeach
	<tr>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"> <b>SISA LEBIH PEMBIAYAAN ANGGARAN TAHUN BERKENAAN</b></td>
		<td class="border-rincian kanan total"><b>
		@php $tot = ($totalpendapatan-$totalbelanja)+($totalpenerimaan-$totalpengeluaran); @endphp	
		@if($tot < 0)
			({{ trim(number_format((float)round($tot),2,',','.'),"-") }})
		@else
			{{ number_format((float)round($tot), 2, ',', '.') }}
		@endif
		</b></td>
		<td class="border-rincian kanan total"><b>
		@php $totp = ($totalpendapatanp-$totalbelanjap)+($totalpenerimaanp-$totalpengeluaranp); @endphp	
		@if($totp < 0)
			({{ trim(number_format((float)round($totp),2,',','.'),"-") }})
		@else
			{{ number_format((float)round($totp), 2, ',', '.') }}
		@endif
		</b></td>
		<td class="border-rincian kanan total"><b>
		@php $tots = $totp-$tot; @endphp	
		@if($tots < 0)
			({{ trim(number_format((float)round($tots),2,',','.'),"-") }})
		@else
			{{ number_format((float)round($tots), 2, ',', '.') }}
		@endif
		</b></td>
		<td class="border-rincian kanan "></td>
	</tr>


</tbody>	
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
		<td><b>WALI KOTA BANDUNG</b></td>
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