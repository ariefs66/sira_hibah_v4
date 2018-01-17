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
	<h5 style="margin-right: -450px;">LAMPIRAN II Peraturan Daerah Kota Bandung</h5>
	<h5 style="margin-right: -500px;">NOMOR &nbsp; &nbsp; : 12 Tahun 2017</h5>
	<h5 style="margin-right: -523px;">TANGGAL &nbsp;: 29 Desember 2017</h5>
	</div>
	<br>
<table class="header">
	<tr class="">
		<td class="" colspan="5"></td>
	</tr>
	<tr>	
		<td class="">
			<img src="{{ url('/') }}/assets/img/bandung.png" width="80px" style="margin:3px">
		</td>	
		<td>
		<h4>PEMERINTAH KOTA BANDUNG</h4> 
			<h3>RINGKASAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI</h3> 
			<h5>TAHUN ANGGARAN {{ $tahun }}</h5>
		</td>
	</tr>
	<tr> <td colspan="5"></td> </tr>	
</table>
<table class="rincian">
	<tbody>
	<tr class="border headrincian">
		<td class="border tengah" rowspan="2" colspan="3">KODE</td>
		<td class="border tengah" rowspan="2">URUSAN PEMERINTAHAN DAERAH</td>
		<td class="border tengah" rowspan="2">PENDAPATAN</td>
		<td class="border tengah" colspan="3">BELANJA</td>
	</tr>	
	<tr>
		<td class="border tengah">TIDAK LANGSUNG</td>
		<td class="border tengah">LANGSUNG</td>
		<td class="border tengah">JUMLAH BELANJA</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" width="8%" colspan="3">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
		<td class="border">5</td>
		<td class="border">6</td>
	</tr>
	<tr style="font-size: 5px;">
		<td class="border-rincian" colspan="3">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
	</tr>	
	@foreach($detil as $rs)
        @if($rs->urusan_ok=='t')
        <tr>
            <td class="text_blok" width="5">{{ substr($rs->URUSAN_KODE,0,1) }}</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="border-rincian text_blok">{{ $rs->URUSAN_KAT1_NAMA }}</td>
            <td class="border-rincian text_blok kanan">{{ number_format($rs->subtotal1_pendapatan_murni,2,',','.') }}</td>
            <td class="border-rincian text_blok kanan">{{ number_format($rs->subtotal1_btl_murni,2,',','.') }}</td>
            <td class="border-rincian text_blok kanan">{{ number_format($rs->subtotal1_bl_murni,2,',','.') }}</td>
            <td class="border-rincian text_blok kanan">{{ number_format(($rs->subtotal1_btl_murni+$rs->subtotal1_bl_murni),2,',','.') }}</td>
        </tr>
        @endif
        @if($rs->kode_urusan_ok=='t')
        <tr>
            <td class="text_blok" width="5">{{ substr($rs->URUSAN_KODE,0,1) }}</td>
            <td class="text_blok" width="5">{{ substr($rs->URUSAN_KODE,2,2) }}</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="border-rincian text_blok" style='padding-left: 10px'>{{ $rs->URUSAN_NAMA }}</td>
            <td class="border-rincian text_blok kanan">{{ number_format($rs->subtotal_pendapatan_murni,2,',','.') }}</td>
            <td class="border-rincian text_blok kanan">{{ number_format($rs->subtotal_btl_murni,2,',','.') }}</td>
            <td class="border-rincian text_blok kanan">{{ number_format($rs->subtotal_bl_murni,2,',','.') }}</td>
            <td class="border-rincian text_blok kanan">{{ number_format(($rs->subtotal_btl_murni+$rs->subtotal_bl_murni),2,',','.') }}</td>
        </tr>
        @endif
        @if($rs->kode_unit_ok=='t')
        <tr>
            <td class="" width="5">{{ substr($rs->URUSAN_KODE,0,1) }}</td>
            <td class="" width="5">{{ substr($rs->URUSAN_KODE,2,2) }}</td>
            <td class="" width="5">{{ $rs->SKPD_KODE }}</td>
            <td class="border-rincian" style='padding-left: 20px'>{{ $rs->SKPD_NAMA }}</td>
            <td class="border-rincian kanan">{{ number_format($rs->PENDAPATAN_MURNI,2,',','.') }}</td>
            <td class="border-rincian kanan">{{ number_format($rs->BTL_MURNI,2,',','.') }}</td>
            <td class="border-rincian kanan">{{ number_format($rs->BL_MURNI,2,',','.') }}</td>
            <td class="border-rincian kanan">{{ number_format(($rs->BTL_MURNI+$rs->BL_MURNI),2,',','.') }}</td>
        </tr>
        @endif
    @endforeach
    <tr>
        <td class="text_blok kanan" colspan="4">TOTAL</td>
        <td class="border-rincian text_blok kanan">{{ number_format($totalpendapatanmurni,2,',','.') }}</td>
        <td class="border-rincian text_blok kanan">{{ number_format($totalbtlmurni,2,',','.') }}</td>
        <td class="border-rincian text_blok kanan">{{ number_format(round($totalblmurni+0.01,2),2,',','.') }}</td>
        <td class="border-rincian text_blok kanan">{{ number_format(round(($totalbtlmurni+$totalblmurni+0.01),2),2,',','.') }}</td>
    </tr>
	

	<tr style="font-size: 5px;">
		<td class="" colspan="5">&nbsp;</td>
		<td class="border-rincian"></td>
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