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
	<br><br><br><br><br><br><br>
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
			TAHUN ANGGARAN {{$tahun}} <br> <br> <br></h4> 
		</td>
		<td class="tengah">&nbsp;</td>
	</tr>
	<tr>
		<td class="tengah">
			<h1>BELANJA TIDAK LANGSUNG</h1><br>
		</td>
	</tr>	
</table>
<table class="">
	<tr class="">
		<td>&nbsp; </td>
		<td>&nbsp; </td>
		<td width="200px">
			<table class="" width="100px">
				<tr class="">
					<td class=""><b>No DPA SKPD</b></td>
					<td class="border">{{ $urusan->URUSAN_KODE }}</td>
					<td class="border">{{ substr($skpd->SKPD_KODE,5,2) }}</td>
					<td class="border">00</td>
					<td class="border">00</td>
					<td class="border">5</td>
					<td class="border">1</td>
				</tr>
			</table>
		</td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
	</tr>
</table>
<table class="">
	<tr class="">
		<td>&nbsp; </td>
		<td>&nbsp; </td>
		<td width="500px">
			<table class="" width="100px">
				<tr class="">
					<td class=""><br><br> URUSAN PEMERINTAHAN</td>
					<td class=""><br><br> {{ $urusan->URUSAN_KODE }} {{ $urusan->URUSAN_NAMA }}</td>
				</tr>
				<tr class="">
					<td class="">ORGANISASI</td>
					<td class="">{{ $skpd->SKPD_KODE }} {{ $skpd->SKPD_NAMA }}</td>
				</tr>
				<tr class="">
					<td class=""><br>Pengguna Anggaran /<br> Kuasa Pengguna Anggaran</td>
					<td class=""></td>
				</tr>
				<tr class="">
					<td class="">Nama</td>
					<td class="">{{ $skpd->SKPD_KEPALA }}</td>
				</tr>
				<tr class="">
					<td class="">NIP</td>
					<td class="">{{ $skpd->SKPD_KEPALA_NIP }}</td>
				</tr>
				<tr class="">
					<td class="">Jabatan</td>
					<td class="">{{ $skpd->SKPD_BENDAHARA }}</td>
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
<br><br><br><br><br><br>
<br><br><br><br><br><br>
<br><br><br><br><br><br>


<table class="header">
	<tr class="border">
		<td class="border" width="85%" rowspan="2">
			<h4>DOKUMEN PELAKSANAAN ANGGARAN<br>SATUAN KERJA PERANGKAT DAERAH </h4>
		</td>
		<td class="border" colspan="6">
			<h4>Nomor DPA SKPD</h4>
		</td>
		<td rowspan="2" class="border">
			<h4>Formulir<br>DPA-SKPD 2.1</h4>
		</td>
	</tr>
	<tr class="border">
		<td class="border"> <h4>{{ $urusan->URUSAN_KODE }}</h4> </td>
		<td class="border"> <h4>{{ substr($skpd->SKPD_KODE,5,2) }}</h4> </td>
		<td class="border"> <h4>00</h4> </td>
		<td class="border"> <h4>00</h4> </td>
		<td class="border"> <h4>5</h4> </td>
		<td class="border"> <h4>1</h4> </td>
	</tr>
	<tr>
		<td colspan="8">
			<h4>Kota Bandung<br>Tahun Anggaran {{$tahun}}</h4>
		</td>
	</tr>
</table>
<table class="detail">
	<tr class="border">
		<td width="18%">Urusan Pemerintahan</td>
		<td width="25%">: {{ $urusan->URUSAN_KODE }}</td> 
		<td>{{ $urusan->URUSAN_NAMA }}</td>
		<td></td>
	</tr>
	<tr class="border">
		<td>Organisasi</td>
		<td>: {{ $urusan->URUSAN_KODE }}.{{ $skpd->SKPD_KODE }}</td> 
		<td>{{ $skpd->SKPD_NAMA }}</td>
		<td></td>
	</tr>
</table>

<table class="rincian">
	<tbody>
	<tr class="border">
		<td colspan="6"><h4>Rincian Dokumen Pelaksanaan Anggaran <br> 
		Belanja Tidak Langsung Satuan Kerja Perangkat Daerah</h4></td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" rowspan="2">Kode Rekening</td>
		<td class="border" rowspan="2">Uraian</td>
		<td class="border" colspan="3">Rincian Perhitungan</td>
		<td class="border" rowspan="2">Jumlah<br>(Rp)</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border">Volume</td>
		<td class="border">Satuan</td>
		<td class="border">Harga Satuan</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
		<td class="border">5</td>
		<td class="border">6 = (3x5)</td>
	</tr>
	
	

	<tr>
		<td class="border-rincian kiri "><b>5</b></td>
		<td class="border-rincian "> <b> Belanja </b></td>
		<td class="border-rincian ">   </td>
		<td class="border-rincian "> </td>
		<td class="border-rincian "> </td>
		<td class="border-rincian border kanan"> <b>{{ number_format($totbtl,0,',','.') }}</b> </td>
	</tr>	

	<tr>
		<td class="border-rincian kiri "><b>5.1</b></td>
		<td class="border-rincian "> <b> &nbsp; Belanja Tidak Langsung</b></td>
		<td class="border-rincian ">   </td>
		<td class="border-rincian "> </td>
		<td class="border-rincian "> </td>
		<td class="border-rincian border kanan"> <b>{{ number_format($totbtl,0,',','.') }}</b> </td>
	</tr>
	@if($totbtl1 !=0)
		<tr>
			<td class="border-rincian kiri "><b>5.1.1</b></td>
			<td class="border-rincian "> <b> &nbsp; &nbsp; Belanja Pegawai </b></td>
			<td class="border-rincian ">   </td>
			<td class="border-rincian "> </td>
			<td class="border-rincian "> </td>
			<td class="border-rincian border kanan"> <b>{{ number_format($totbtl1,0,',','.') }}</b> </td>
		</tr>
			@foreach($btl1 as $btll1)
			<tr>
				<td class="border-rincian kiri "> {{$btll1->rekening->REKENING_KODE}} </td>
				<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp;{{$btll1->BTL_NAMA}} </td>
				<td class="border-rincian tengah ">   {{$btll1->BTL_VOLUME}} </td>
				<td class="border-rincian tengah "> {{$btll1->BTL_KOEFISIEN}} </td>
				<td class="border-rincian kanan "> {{ number_format($btll1->BTL_TOTAL,0,',','.') }} </td>
				<td class="border-rincian kanan border"> {{ number_format($btll1->BTL_TOTAL,0,',','.') }}</td>
			</tr>	
			@endforeach	
	@endif		

	@if($totbtl3 !=0)
		<tr>
			<td class="border-rincian kiri "><b>5.1.3</b></td>
			<td class="border-rincian "> <b> &nbsp; &nbsp;  Belanja Subsidi </b></td>
			<td class="border-rincian ">   </td>
			<td class="border-rincian "> </td>
			<td class="border-rincian "> </td>
			<td class="border-rincian border kanan"> <b>{{ number_format($totbtl3,0,',','.') }}</b> </td>
		</tr>
		@foreach($btl3 as $btll3)
		<tr>
			<td class="border-rincian kiri "> {{$btll3->rekening->REKENING_KODE}} </td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp;{{$btll3->BTL_NAMA}} </td>
			<td class="border-rincian tengah ">   {{$btll3->BTL_VOLUME}} </td>
			<td class="border-rincian tengah "> {{$btll3->BTL_KOEFISIEN}} </td>
			<td class="border-rincian kanan "> {{ number_format($btll3->BTL_TOTAL,0,',','.') }} </td>
			<td class="border-rincian kanan border"> {{ number_format($btll3->BTL_TOTAL,0,',','.') }}</td>
		</tr>	
		@endforeach	
	@endif	

	@if($totbtl4 !=0)
		<tr>
			<td class="border-rincian kiri "><b>5.1.4</b></td>
			<td class="border-rincian "> <b> &nbsp; &nbsp;  Belanja Hibah </b></td>
			<td class="border-rincian ">   </td>
			<td class="border-rincian "> </td>
			<td class="border-rincian "> </td>
			<td class="border-rincian border kanan"> <b>{{ number_format($totbtl4,0,',','.') }}</b> </td>
		</tr>
		@foreach($btl4 as $btll4)
		<tr>
			<td class="border-rincian kiri "> {{$btll4->rekening->REKENING_KODE}} </td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp;{{$btll4->BTL_NAMA}} </td>
			<td class="border-rincian tengah ">   {{$btll4->BTL_VOLUME}} </td>
			<td class="border-rincian tengah "> {{$btll4->BTL_KOEFISIEN}} </td>
			<td class="border-rincian kanan "> {{ number_format($btll4->BTL_TOTAL,0,',','.') }} </td>
			<td class="border-rincian kanan border"> {{ number_format($btll4->BTL_TOTAL,0,',','.') }}</td>
		</tr>	
		@endforeach	
	@endif	

	@if($totbtl7 !=0)
		<tr>
			<td class="border-rincian kiri "><b>5.1.7</b></td>
			<td class="border-rincian "> <b> &nbsp; &nbsp;  Belanja Bantuan Keuangan Kepada Provinsi/kabupaten/kota Dan Pemerintahan Desa </b></td>
			<td class="border-rincian ">   </td>
			<td class="border-rincian "> </td>
			<td class="border-rincian "> </td>
			<td class="border-rincian border kanan"> <b>{{ number_format($totbtl7,0,',','.') }}</b> </td>
		</tr>
		@foreach($btl7 as $btll7)
		<tr>
			<td class="border-rincian kiri "> {{$btll7->rekening->REKENING_KODE}} </td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp;{{$btll7->BTL_NAMA}} </td>
			<td class="border-rincian tengah ">   {{$btll7->BTL_VOLUME}} </td>
			<td class="border-rincian tengah "> {{$btll7->BTL_KOEFISIEN}} </td>
			<td class="border-rincian kanan "> {{ number_format($btll7->BTL_TOTAL,0,',','.') }} </td>
			<td class="border-rincian kanan border"> {{ number_format($btll7->BTL_TOTAL,0,',','.') }}</td>
		</tr>	
		@endforeach	
	@endif	

	@if($totbtl8 !=0)
		<tr>
			<td class="border-rincian kiri "><b>5.1.8</b></td>
			<td class="border-rincian "> <b> &nbsp; &nbsp;  Belanja Tidak Terduga</b></td>
			<td class="border-rincian ">   </td>
			<td class="border-rincian "> </td>
			<td class="border-rincian "> </td>
			<td class="border-rincian border kanan"> <b>{{ number_format($totbtl8,0,',','.') }}</b> </td>
		</tr>
		@foreach($btl8 as $btll8)
		<tr>
			<td class="border-rincian kiri "> {{$btll8->rekening->REKENING_KODE}} </td>
			<td class="border-rincian "> &nbsp; &nbsp; &nbsp; &nbsp;{{$btll8->BTL_NAMA}} </td>
			<td class="border-rincian tengah ">   {{$btll8->BTL_VOLUME}} </td>
			<td class="border-rincian tengah "> {{$btll8->BTL_KOEFISIEN}} </td>
			<td class="border-rincian kanan "> {{ number_format($btll8->BTL_TOTAL,0,',','.') }} </td>
			<td class="border-rincian kanan border"> {{ number_format($btll8->BTL_TOTAL,0,',','.') }}</td>
		</tr>	
		@endforeach	
	@endif	


	
	
	<tr class="border">
		<td class="border kanan" colspan="5"><b>Jumlah</b></td>
		<td class="border kanan"><b>{{ number_format($totbtl,0,',','.') }},00</b></td>
	</tr>

	<tr class="border">
		<td class="border kanan" colspan="6"> </td>
	</tr>
	<tr class="border">
		<td class="border kiri" colspan="6"> &nbsp; &nbsp; &nbsp;  Rencana Penarikan Per Triwulan</td>
	</tr>
	</tbody>	
</table>
<table class="ttd">
	<tr>
		<td class="tengah">Triwulan I</td>
		<td class="kiri">Rp. {{ number_format($akb_btl->tri1,0,',','.') }}</td>
		<td width="50%"> </td>
		<td>Bandung, {{(isset($tgl_ttd) ? (strlen($tgl_ttd)>0?$tgl_ttd:$tgl.' '.$bln.' '.$thn) : $tgl.' '.$bln.' '.$thn)}}<!--2 Januari 2018--></td>
	</tr>
	<tr>
		<td class="tengah">Triwulan II</td>
		<td class="kiri">Rp. {{ number_format($akb_btl->tri2,0,',','.') }} </td>
		<td width="50%"> </td>
		<td><b>{{(isset($jabatan_ttd) ? (strlen($jabatan_ttd)>0?$jabatan_ttd:'Pejabat Pengelola Keuangan Daerah') : 'Pejabat Pengelola Keuangan Daerah')}}</b></td>
	</tr>
	<tr>
		<td class="tengah">Triwulan III</td>
		<td class="kiri">Rp. {{ number_format($akb_btl->tri3,0,',','.') }} </td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="tengah">Triwulan IV</td>
		<td class="kiri">Rp. {{ number_format($akb_btl->tri4,0,',','.') }} </td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="kanan"><b>Jumlah</b></td>
		<td class="kiri"><b> Rp. {{ number_format($akb_btl->tri1+$akb_btl->tri2+$akb_btl->tri3+$akb_btl->tri4,0,',','.') }}</b></td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="kiri"> </td>
		<td class="kiri"> </td>
		<td width="50%"> <br><br><br><br><br><br></td>
		<td><span style="border-bottom: 1px solid #000;padding-bottom: 1px;">{{(isset($nama_ttd) ? (strlen($nama_ttd)>0?$nama_ttd:'Drs. DADANG SUPRIATNA, MH ') : 'Drs. DADANG SUPRIATNA, MH ')}}<br>
			<br> </span>{{(isset($nip_ttd) ? (strlen($nip_ttd)>0?'NIP. '.$nip_ttd:' NIP. 19610308 199103 1 009') : ' NIP. 19610308 199103 1 009')}}</td>
	</tr>
</table>
</div>
</body>
</html>