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
	<h5 style="margin-right: -450px;">LAMPIRAN IV Peraturan Daerah Kota Bandung</h5>
	<h5 style="margin-right: -503px;">NOMOR &nbsp; &nbsp; : 12 Tahun 2017</h5>
	<h5 style="margin-right: -523px;">TANGGAL &nbsp;: 29 Desember 2017</h5>
	</div>
	<br>
<table class="header">
	<tr class="">
		<td class="" colspan="8"></td>
	</tr>
	<tr>	
		<td class="">
			<img src="{{ url('/') }}/assets/img/bandung.png" width="80px" style="margin:3px">
		</td>	
		<td>
			<h4>PEMERINTAH KOTA BANDUNG</h4>
			<h3>REKAPITULASI BELANJA MENURUT URUSAN PEMERINTAH DAERAH</h3>
			<h3>ORGANISASI, PROGRAM DAN KEGIATAN</h3>
			<h4>TAHUN ANGGARAN {{ $tahun }}</h4>
		</td>
	</tr>
	<tr> <td colspan="8"></td> </tr>	
</table>
<br>
<table class="rincian">
	<tbody>
	<tr class="border headrincian">
		<td class="border tengah" rowspan="2" colspan="5">KODE </td>
		<td class="border tengah" rowspan="2">URAIAN URUSAN, ORGANISASI PROGRAM DAN KEGIATAN </td>
		<td class="border tengah" colspan="3">JENIS BELANJA </td>
		<td class="border tengah" rowspan="2"> JUMLAH </td>
	</tr>	
	<tr class="border headrincian">
		<td class="border tengah">PEGAWAI </td>
		<td class="border tengah">BARANG & JASA</td>
		<td class="border tengah">MODAL</td>
	</tr>	
	<tr class="border">
		<td class="border tengah" colspan="5">1</td>
		<td class="border tengah">2</td>
		<td class="border tengah">3</td>
		<td class="border tengah">4</td>
		<td class="border tengah">5</td>
		<td class="border tengah">6 = 3+4+5</td>
	</tr>
	@foreach($detil as $rs)
        @if($rs->urusan_ok=='t')
        <tr>
            <td class="text_blok" width="5">{{ substr($rs->URUSAN_KODE,0,1) }}</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="border text_blok">{{ $rs->URUSAN_KAT1_NAMA }}</td>
            <td class="border text_blok">&nbsp;</td>
            <td class="border text_blok">&nbsp;</td>
            <td class="border text_blok">&nbsp;</td>
            <td class="border text_blok">&nbsp;</td>
        </tr>
        @endif
        @if($rs->kode_urusan_ok=='t')
        <tr>
            <td class="text_blok" width="5">{{ substr($rs->URUSAN_KODE,0,1) }}</td>
            <td class="text_blok" width="5">{{ $rs->URUSAN_KODE }}</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="border text_blok">{{ $rs->URUSAN_NAMA }}</td>
            <td class="border text_blok kanan">{{ number_format($rs->suburusan_pegawai_murni,2,',','.') }}</td>
            <td class="border text_blok kanan">{{ number_format($rs->suburusan_jasa_murni,2,',','.') }}</td>
            <td class="border text_blok kanan">{{ number_format($rs->suburusan_modal_murni,2,',','.') }}</td>
            <td class="border text_blok kanan">{{ number_format(($rs->suburusan_pegawai_murni+$rs->suburusan_jasa_murni+$rs->suburusan_modal_murni),2,',','.') }}</td>
        </tr>
        @endif
        @if($rs->kode_unit_ok=='t')
        <tr>
            <td class="text_blok" width="5">{{ substr($rs->URUSAN_KODE,0,1) }}</td>
            <td class="text_blok" width="5">{{ $rs->URUSAN_KODE }}</td>
            <td class="text_blok" width="5">{{ $rs->SKPD_KODE }}</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="border text_blok">{{ $rs->SKPD_NAMA }}</td>
            <td class="border text_blok kanan">{{ number_format($rs->subunit_pegawai_murni,2,',','.') }}</td>
            <td class="border text_blok kanan">{{ number_format($rs->subunit_jasa_murni,2,',','.') }}</td>
            <td class="border text_blok kanan">{{ number_format($rs->subunit_modal_murni,2,',','.') }}</td>
            <td class="border text_blok kanan">{{ number_format(($rs->subunit_pegawai_murni+$rs->subunit_jasa_murni+$rs->subunit_modal_murni),2,',','.') }}</td>
        </tr>
        @endif
        @if($rs->kode_program_ok=='t')
        <tr>
            <td class="text_blok" width="5">{{ substr($rs->URUSAN_KODE,0,1) }}</td>
            <td class="text_blok" width="5">{{ $rs->URUSAN_KODE }}</td>
            <td class="text_blok" width="5">{{ $rs->SKPD_KODE }}</td>
            <td class="text_blok" width="5">{{ $rs->PROGRAM_KODE }}</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="border text_blok">{{ $rs->PROGRAM_NAMA }}</td>
            <td class="border text_blok kanan">{{ number_format($rs->subprogram_pegawai_murni,2,',','.') }}</td>
            <td class="border text_blok kanan">{{ number_format($rs->subprogram_jasa_murni,2,',','.') }}</td>
            <td class="border text_blok kanan">{{ number_format($rs->subprogram_modal_murni,2,',','.') }}</td>
            <td class="border text_blok kanan">{{ number_format(($rs->subprogram_pegawai_murni+$rs->subprogram_jasa_murni+$rs->subprogram_modal_murni),2,',','.') }}</td>
        </tr>
        @endif
        @if($rs->kode_giat_ok=='t')
        <tr>
            <td class="" width="5">{{ substr($rs->URUSAN_KODE,0,1) }}</td>
            <td class="" width="5">{{ $rs->URUSAN_KODE }}</td>
            <td class="" width="5">{{ $rs->SKPD_KODE }}</td>
            <td class="" width="5">{{ $rs->PROGRAM_KODE }}</td>
            <td class="" width="5">{{ $rs->KEGIATAN_KODE }}</td>
            <td class="border">{{ $rs->KEGIATAN_NAMA }}</td>
            <td class="border kanan">{{ number_format($rs->BL_PEGAWAI_MURNI,2,',','.') }}</td>
            <td class="border kanan">{{ number_format($rs->BL_JASA_MURNI,2,',','.') }}</td>
            <td class="border kanan">{{ number_format($rs->BL_MODAL_MURNI,2,',','.') }}</td>
            <td class="border kanan">{{ number_format(($rs->BL_PEGAWAI_MURNI+$rs->BL_JASA_MURNI+$rs->BL_MODAL_MURNI),2,',','.') }}</td>
        </tr>
        @endif
    @endforeach
    <tr>
	    <td class="border text_blok kanan" colspan="6">TOTAL</td>
	    <td class="border text_blok kanan">{{ number_format($bl1,2,',','.') }}</td>
	    <td class="border text_blok kanan">{{ number_format($bl2,2,',','.') }}</td>
	    <td class="border text_blok kanan">{{ number_format($bl3,2,',','.') }}</td>
	    <td class="border text_blok kanan">{{ number_format($bl,2,',','.') }}</td>
	</tr>
	</tbody>	
</table>
<br><br>
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