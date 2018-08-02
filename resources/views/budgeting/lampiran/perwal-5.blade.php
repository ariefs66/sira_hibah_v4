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
	<h5>LAMPIRAN V &nbsp; &nbsp; &nbsp; Peraturan Walikota Bandung</h5>
	<h5>NOMOR : {{(isset($nomor_ttd) ? (strlen($nomor_ttd)>0?$nomor_ttd:'') : '')}}</h5>
	<h5>TANGGAL : {{(isset($tgl_ttd) ? (strlen($tgl_ttd)>0?$tgl_ttd:$tgl.' '.$bln.' '.$thn) : '')}}</h5>
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
			<h3>REKAPITULASI BELANJA DAERAH UNTUK KESELARASAN DAN KETERPADUAN</h3>
			<h3>URUSAN PEMERINTAHAN DAERAH DAN FUNGSI DALAM KERANGKA PENGELOLAAN KEUANGAN NEGARA</h3>
			<h4>TAHUN ANGGARAN {{ $tahun }}</h4>
		</td>
	</tr>
	<tr> <td colspan="2"></td> </tr>
</table>
<br>
<table class="rincian">
	<tbody>
	<tr class="border headrincian">
		<td class="border tengah" rowspan="2" colspan="3">KODE </td>
		<td class="border tengah" rowspan="2">URAIAN</td>
		<td class="border tengah" colspan="3">BELANJA LANGSUNG </td>
		<td class="border tengah" rowspan="2">JUMLAH </td>
	</tr>	
	<tr class="border headrincian">
		<td class="border tengah">PEGAWAI </td>
		<td class="border tengah">BARANG & JASA</td>
		<td class="border tengah">MODAL</td>
	</tr>	
	<tr class="border">
		<td class="border tengah" colspan="3">1</td>
		<td class="border tengah">2</td>
		<td class="border tengah">3</td>
		<td class="border tengah">4</td>
		<td class="border tengah">5</td>
		<td class="border tengah">6 = 3+4+5</td>
	</tr>
	@foreach($detil as $rs)
        @if($rs->kode_fungsi_ok=='t')
        <tr>
            <td class="text_blok" width="5">{{ $rs->URUSAN_KAT1_KODE }}</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="text_blok" width="5">&nbsp;</td>
            <td class="border text_blok">{{ $rs->URUSAN_KAT1_NAMA }}</td>
            <td class="border text_blok kanan">{{ number_format($rs->subfungsi_pegawai_murni,0,'.',',') }}</td>
            <td class="border text_blok kanan">{{ number_format($rs->subfungsi_jasa_murni,0,'.',',') }}</td>
            <td class="border text_blok kanan">{{ number_format($rs->subfungsi_modal_murni,0,'.',',') }}</td>
            <td class="border text_blok kanan">{{ number_format(($rs->subfungsi_pegawai_murni+$rs->subfungsi_jasa_murni+$rs->subfungsi_modal_murni),0,'.',',') }}</td>
        </tr>
        @endif
        @if($rs->kode_urusan_ok=='t')
        <tr>
            <td class="" width="5">{{ $rs->URUSAN_KAT1_KODE }}</td>
            <td class="" width="5">{{ substr($rs->URUSAN_KODE,0,1) }}</td>
            <td class="" width="5">{{ substr($rs->URUSAN_KODE,2,2) }}</td>
            <td class="border">{{ $rs->URUSAN_NAMA }}</td>
            <td class="border kanan">{{ number_format($rs->pegawaimurni,0,'.',',') }}</td>
            <td class="border kanan">{{ number_format($rs->jasamurni,0,'.',',') }}</td>
            <td class="border kanan">{{ number_format($rs->modalmurni,0,'.',',') }}</td>
            <td class="border kanan">{{ number_format(($rs->pegawaimurni+$rs->jasamurni+$rs->modalmurni),0,'.',',') }}</td>
        </tr>
        @endif
    @endforeach
    <tr>
        <td class="text_blok kanan" colspan="4">TOTAL</td>
        <td class="border text_blok kanan">{{ number_format($totalpegawaimurni,0,'.',',') }}</td>
        <td class="border text_blok kanan">{{ number_format($totaljasamurni,0,'.',',') }}</td>
        <td class="border text_blok kanan">{{ number_format($totalmodalmurni,0,'.',',') }}</td>
        <td class="border text_blok kanan">{{ number_format(($totalpegawaimurni+$totaljasamurni+$totalmodalmurni),0,'.',',') }}</td>
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
		<td>Bandung, {{(isset($tgl_ttd) ? (strlen($tgl_ttd)>0?$tgl_ttd:$tgl.' '.$bln.' '.$thn) : '')}}</td>
	</tr>
	<tr>
		<td></td>
		<td><b>{{(isset($jabatan_ttd) ? (strlen($jabatan_ttd)>0?$jabatan_ttd:'WALIKOTA BANDUNG') : 'WALIKOTA BANDUNG')}}</b></td>
	</tr>
	<tr>
		<td></td>
		<td><br><br><br><br><br></td>
	</tr>
	<tr>
		<td></td>
		<td><b>{{(isset($nama_ttd) ? (strlen($nama_ttd)>0?$nama_ttd:'MOCHAMAD RIDWAN KAMIL') : 'MOCHAMAD RIDWAN KAMIL')}}</b></td>
	</tr>
	<tr>
		<td></td>
		<td><br><br></td>
	</tr>
</table>
</div>
</body>
</html>