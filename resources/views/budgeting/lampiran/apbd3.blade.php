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
	<h5>LAMPIRAN III &nbsp; &nbsp; &nbsp; Peraturan Daerah</h5>
	<h5>NOMOR : </h5>
	<h5>TANGGAL :</h5>
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
			<h3>RINCIAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PENDAPATAN, BELANJA DAN PEMBIAYAAN</h3>
			<h5>TAHUN ANGGARAN {{ $tahun }}</h5>
		</td>
	</tr>
	<tr> <td colspan="2"></td> </tr>
</table>
<table class="rincian">
	<tbody>
	<tr class="border "> 
		<td colspan="14"><b>Urusan Pemerintah : </b> {{$urusan->URUSAN_KODE}} &nbsp; &nbsp; &nbsp; {{$urusan->URUSAN_KAT1_NAMA}}<br> 
		<b>Organisasi &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;: </b>{{$skpd->SKPD_KODE}} &nbsp; {{$skpd->SKPD_NAMA}} </td> 
	</tr>	
	<tr class="border headrincian">
		<td class="border tengah" colspan="10" >KODE <br> REKENING </td>
		<td class="border tengah" >URAIAN</td>
		<td class="border tengah" >JUMLAH</td>
		<td class="border tengah" >DASAR HUKUM</td>
	</tr>		
	<tr class="border headrincian">
		<td class="border" colspan="10" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
	</tr>
	<tr style="font-size: 5px;">
		<td class="border-rincian" colspan="10">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
	</tr>

	@foreach($rincian as $rs)
        @if ($rs['tingkat']==1)
        <tr>
            <td class="text_blok" width="5">{{ $rs['kodeurusan'] }}</td>
            <td class="text_blok" width="5">{{ $rs['kodeskpd'] }}</td>
            <td class="text_blok" width="5">{{ $rs['kodeprogram'] }}</td>
            <td class="text_blok" width="5">{{ $rs['nogiat'] }}</td>
            <td class="text_blok" width="5">{{ $rs['akun1'] }}</td>
            <td class="text_blok" width="5">{{ $rs['akun2'] }}</td>
            <td class="text_blok" width="5">{{ $rs['akun3'] }}</td>
            <td class="text_blok" width="5">{{ $rs['akun4'] }}</td>
            <td class="text_blok" width="5">{{ $rs['akun5'] }}</td>
            <td class="text_blok" width="5">{{ $rs['akun6'] }}</td>
            <td class="border-rincian text_blok">{!! str_replace('&apos;',"'",$rs['namarekening']) !!}</td>
            <td class="border-rincian kanan text_blok">&nbsp;</td>
            <td class="border-rincian"></td>
        </tr>
        @endif
        @if ($rs['tingkat']==2)
        <tr>
            <td class="text_blok" width="5">{{ $rs['kodeurusan'] }}</td>
            <td class="text_blok" width="5">{{ $rs['kodeskpd'] }}</td>
            <td class="text_blok" width="5">{{ $rs['kodeprogram'] }}</td>
            <td class="text_blok" width="5">{{ $rs['nogiat'] }}</td>
            <td class="text_blok" width="5">{{ $rs['akun1'] }}</td>
            <td class="text_blok" width="5">{{ $rs['akun2'] }}</td>
            <td class="text_blok" width="5">{{ $rs['akun3'] }}</td>
            <td class="text_blok" width="5">{{ $rs['akun4'] }}</td>
            <td class="text_blok" width="5">{{ $rs['akun5'] }}</td>
            <td class="text_blok" width="5">{{ $rs['akun6'] }}</td>
            <td class="border-rincian text_blok" style='padding-left: 10px'>{!! str_replace('&apos;',"'",$rs['namarekening']) !!}</td>
            <td class="border-rincian kanan text_blok">{{ number_format($rs['totalrekening'],2,',','.') }}</td>
            <td class="border-rincian"></td>
        </tr> 
        @endif
        @if ($rs['tingkat']==3)
        <tr>
            <td class="" width="5">{{ $rs['kodeurusan'] }}</td>
            <td class="" width="5">{{ $rs['kodeskpd'] }}</td>
            <td class="" width="5">{{ $rs['kodeprogram'] }}</td>
            <td class="" width="5">{{ $rs['nogiat'] }}</td>
            <td class="" width="5">{{ $rs['akun1'] }}</td>
            <td class="" width="5">{{ $rs['akun2'] }}</td>
            <td class="" width="5">{{ $rs['akun3'] }}</td>
            <td class="" width="5">{{ $rs['akun4'] }}</td>
            <td class="" width="5">{{ $rs['akun5'] }}</td>
            <td class="" width="5">{{ $rs['akun6'] }}</td>
            <td class="border-rincian" style='padding-left: 20px'>{!! str_replace('&apos;',"'",$rs['namarekening']) !!}</td>
            <td class="border-rincian kanan">{{ number_format($rs['totalrekening'],2,',','.') }}</td>
            <td class="border-rincian">{{ $rs['dashuk'] }}</td>
        </tr>
        @endif
        @if ($rs['tingkat']==4)
        <tr>
            <td class="" width="5">{{ $rs['kodeurusan'] }}</td>
            <td class="" width="5">{{ $rs['kodeskpd'] }}</td>
            <td class="" width="5">{{ $rs['kodeprogram'] }}</td>
            <td class="" width="5">{{ $rs['nogiat'] }}</td>
            <td class="" width="5">{{ $rs['akun1'] }}</td>
            <td class="" width="5">{{ $rs['akun2'] }}</td>
            <td class="" width="5">{{ $rs['akun3'] }}</td>
            <td class="" width="5">{{ $rs['akun4'] }}</td>
            <td class="" width="5">{{ $rs['akun5'] }}</td>
            <td class="" width="5">{{ $rs['akun6'] }}</td>
            <td class="border-rincian" style='padding-left: 30px'>{!! str_replace('&apos;',"'",$rs['namarekening']) !!}</td>
            <td class="border-rincian kanan">{{ number_format($rs['totalrekening'],2,',','.') }}</td>
            <td class="border-rincian">{{ $rs['dashuk'] }}</td>
        </tr>
        @endif
        @if ($rs['tingkat']==5)
        <tr>
        	<td class="border-rincian text_blok" colspan="10"></td>
            <td class="border-rincian text_blok">{{ $rs['namajumlah'] }}</td>
            <td class="border-rincian kanan text_blok">
                @if($rs['totaljumlah']<0)
                ({{ number_format(abs($rs['totaljumlah']),2,',','.') }})
                @else
                {{ number_format($rs['totaljumlah'],2,',','.') }}
                @endif
            </td>
            <td class="border-rincian"></td>
        </tr>
        @endif
    @endforeach

	<tr style="font-size: 5px;">
		<td class="" colspan="10">&nbsp;</td>
		<td class="border-rincian"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian"></td>
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