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
			<h1>BELANJA LANGSUNG</h1><br>
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
					<td class="border">{{ $skpd->SUB_KODE }}</td>
					<td class="border">{{ $bl->kegiatan->program->PROGRAM_KODE }}</td>
					<td class="border">{{ $bl->kegiatan->KEGIATAN_KODE }}</td>
					<td class="border">5</td>
					<td class="border">2</td>
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
					<td class=""><br>URUSAN PEMERINTAHAN</td>
					<td class=""><br>{{ $urusan->URUSAN_KODE }} {{ $urusan->URUSAN_NAMA }}</td>
				</tr>
				<tr class="">
					<td class="">ORGANISASI</td>
					<td class="">{{ $skpd->SKPD_KODE }} {{ $skpd->SKPD_NAMA }}</td>
				</tr>
				<tr class="">
					<td class="">SUB UNIT ORGANISASI</td>
					<td class="">{{ $skpd->SKPD_KODE }}.{{ $skpd->SUB_KODE }} {{ $skpd->SUB_NAMA }}</td>
				</tr>
				<tr class="">
					<td class="">PROGRAM</td>
					<td class="">{{ $bl->kegiatan->program->urusan->URUSAN_KODE }}.{{ $bl->subunit->skpd->SKPD_KODE }}.{{ $bl->subunit->SUB_KODE }}.{{ $bl->kegiatan->program->PROGRAM_KODE }}
						{{ $bl->kegiatan->program->PROGRAM_NAMA }}
					</td>
				</tr>
				<tr class="">
					<td class="">KEGIATAN</td>
					<td class="">
						{{ $bl->kegiatan->program->urusan->URUSAN_KODE }}.{{ $bl->subunit->skpd->SKPD_KODE }}.{{ $bl->subunit->SUB_KODE }}.{{ $bl->kegiatan->program->PROGRAM_KODE }}.{{ $bl->kegiatan->KEGIATAN_KODE }} {{ $bl->kegiatan->KEGIATAN_NAMA }}
					</td>
				</tr>
				<tr class="">
					<td class="">LOKASI KEGIATAN</td>
					<td class="">{{ $bl->lokasi->LOKASI_NAMA }}</td>
				</tr>
				<tr class="">
					<td class="">SUMBER DANA</td>
					<td class="">APBD</td>
				</tr>
				<tr class="">
					<td class="">JUMLAH ANGGARAN</td>
					<td class="">{{ number_format($totalbl,0,',','.') }}</td>
				</tr>
				<tr class="">
					<td class=""><br>Pengguna Anggaran / <br>Kuasa Pengguna Anggaran</td>
					<td class=""></td>
				</tr>
				<tr class="">
					<td class="">NAMA</td>
					<td class="">{{ $skpd->SKPD_KEPALA }}</td>
				</tr>
				<tr class="">
					<td class="">NIP</td>
					<td class="">{{ $skpd->SKPD_KEPALA_NIP }}</td>
				</tr>
				<tr class="">
					<td class="">JABATAN</td>
					<td class="">{{ $skpd->SKPD_BENDAHARA }}</td>
				</tr>
			</table>
		</td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
	</tr>
</table>

<p style="page-break-after: always;">&nbsp;</p>
<p style="page-break-before: always;">&nbsp;</p>

<table class="header">
	<tr class="border">
		<td class="border" width="85%" rowspan="2">
			<h4>DOKUMEN PELAKSANAAN ANGGARAN<br>SATUAN KERJA PERANGKAT DAERAH </h4>
		</td>
		<td class="border" colspan="7">
			<h4>Nomor DPA SKPD</h4>
		</td>
		<td rowspan="2" class="border">
			<h4>Formulir<br>DPA-SKPD 2.2.1</h4>
		</td>
	</tr>
	<tr class="border">		
		<td class="border">{{ $urusan->URUSAN_KODE }}</td>
		<td class="border">{{ substr($skpd->SKPD_KODE,5,2) }}</td>
		<td class="border">{{ $skpd->SUB_KODE }}</td>
		<td class="border">{{ $bl->kegiatan->program->PROGRAM_KODE }}</td>
		<td class="border">{{ $bl->kegiatan->KEGIATAN_KODE }}</td>
		<td class="border">5</td>
		<td class="border">2</td>
	</tr>
	<tr>
		<td colspan="10">
			<h4>Kota Bandung<br>Tahun Anggaran {{$tahun}}</h4>
		</td>
	</tr>
</table>

<table class="detail">	
	<tr class="border">
		<td width="18%">Urusan Pemerintahan</td>
		<td width="25%">: {{ $bl->kegiatan->program->urusan->URUSAN_KODE }}</td> 
		<td>{{ $bl->kegiatan->program->urusan->URUSAN_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Organisasi</td>
		<td>: {{ $bl->kegiatan->program->urusan->URUSAN_KODE }}.{{ $bl->subunit->skpd->SKPD_KODE }}</td> 
		<td>{{ $bl->subunit->skpd->SKPD_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Sub Unit</td>
		<td>: {{ $bl->kegiatan->program->urusan->URUSAN_KODE }}.{{ $bl->subunit->skpd->SKPD_KODE }}.{{ $bl->subunit->SUB_KODE }}</td> 
		<td>{{ $bl->subunit->SUB_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Program</td>
		<td>: {{ $bl->kegiatan->program->urusan->URUSAN_KODE }}.{{ $bl->subunit->skpd->SKPD_KODE }}.{{ $bl->subunit->SUB_KODE }}.{{ $bl->kegiatan->program->PROGRAM_KODE }}</td> 
		<td>{{ $bl->kegiatan->program->PROGRAM_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Kegiatan</td>
		<td>: {{ $bl->kegiatan->program->urusan->URUSAN_KODE }}.{{ $bl->subunit->skpd->SKPD_KODE }}.{{ $bl->subunit->SUB_KODE }}.{{ $bl->kegiatan->program->PROGRAM_KODE }}.{{ $bl->kegiatan->KEGIATAN_KODE }}</td> 
		<td>{{ $bl->kegiatan->KEGIATAN_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Lokasi</td>
		<td colspan="2">: {{ $bl->lokasi->LOKASI_NAMA }}</td>
	</tr>
	<tr class="border">
		<td>Jumlah</td>
		<td colspan="2">: Rp. {{ number_format($totalbl,0,',','.') }}</td>
	</tr>
</table>
<table class="indikator">
	<tbody>
	<tr>
		<td colspan="3"><h4>Indikator dan Tolak Ukur Kinerja Belanja Langsung</h4></td>
	</tr>
	<tr class="headrincian">
		<td width="15%">Indikator</td>
		<td width="50%">Tolak Ukur Kinerja</td>
		<td width="35%">Target Kinerja</td>
	</tr>
	@if($bl->kegiatan->program->outcome)
	@foreach($bl->kegiatan->program->outcome as $oc)
	<tr>
		<td>Capaian Program</td>
		<td>{{ $oc->OUTCOME_TOLAK_UKUR }}</td>
		<td>{{ $oc->OUTCOME_TARGET }} {{ $oc->satuan->SATUAN_NAMA }}</td>
	</tr>
	@endforeach
	@endif
	<tr>
		<td>Masukan</td>
		<td>Dana yang dibutuhkan</td>
		<td>Rp. {{ number_format($totalbl,0,',','.') }},00</td>
	</tr>
	@php $output = \App\Model\OutputMaster::where('KEGIATAN_ID',$bl->KEGIATAN_ID)->get(); $referensi = FALSE; @endphp
	@if($tahun>2018 && $referensi)
	@if(count($output) != '0')
	@foreach($output as $out)
	<tr>
		<td>Keluaran</td>
		<td>{{ $out->OUTPUT_TOLAK_UKUR }}</td>
		<td>{{ $out->OUTPUT_TARGET }} {{ $out->satuan->SATUAN_NAMA }}</td>
	</tr>
	@endforeach	
	@endif
	@else
	@if($bl->output)
	@foreach($bl->output as $out)
	<tr>
		<td>Keluaran</td>
		<td>{{ $out->OUTPUT_TOLAK_UKUR }}</td>
		<td>{{ $out->OUTPUT_TARGET }} {{ $out->satuan->SATUAN_NAMA }}</td>
	</tr>
	@endforeach	
	@endif
	@endif
	@if($bl->kegiatan->program->impact)
	@foreach($bl->kegiatan->program->impact as $im)
	<tr>
		<td>Hasil</td>
		<td>{{ $im->IMPACT_TOLAK_UKUR }}</td>
		<td>{{ $im->IMPACT_TARGET }} {{ $im->satuan->SATUAN_NAMA }}</td>
	</tr>
	@endforeach	
	@endif
	</tbody>	
</table>
<table>
	<tr class="border">
		<td width="30%">Kelompok Sasaran Kegiatan</td>
		<td> : {{ $bl->sasaran->SASARAN_NAMA }}</td>
	</tr>
</table>
<table class="rincian">
	<tbody>
	<tr class="border">
		<td colspan="10"><h4>Rincian Anggaran Belanja Langsung<br>Menurut Program dan Per Kegiatan Satuan Kerja Perangkat Daerah</h4></td>
	</tr>
	<tr class="border headrincian">
		<td class="border" rowspan="2">Kode Rekening</td>
		<td class="border" rowspan="2">Uraian</td>
		<td class="border" colspan="4">Rincian Perhitungan</td>
		<td class="border" rowspan="2">Jumlah<br>(Rp)</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border">Volume</td>
		<td class="border">Satuan</td>
		<td class="border">Harga Satuan</td>
		<td class="border">PPN</td>
	</tr>	
	<tr class="border headrincian">
		<td class="border" width="8%">1</td>
		<td class="border">2</td>
		<td class="border">3</td>
		<td class="border">4</td>
		<td class="border">5</td>
		<td class="border">6</td>
		<td class="border">7 = (3 x 5) + (3 x 6)</td>
	</tr>
	<tr>
		<td class="border-rincian kiri"><b>5</b></td>
		<td class="border-rincian"><b>Belanja</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalbl,0,',','.') }},00</b></td>
	</tr>	
	<tr>
		<td class="border-rincian kiri"><b>5.2</b></td>
		<td class="border-rincian"><b>&nbsp;Belanja Langsung</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalbl,0,',','.') }},00</b></td>
	</tr>	
	@foreach($rekening as $r)
	@if($s != 0)
	@if($reke[$s-1]->REKENING_KODE != $reke[$s]->REKENING_KODE)
	<tr>
		<td class="border-rincian kiri"><b>{{ $reke[$s]->REKENING_KODE }}</b></td>
		<td class="border-rincian"><b>&nbsp;&nbsp;{{ $reke[$s]->REKENING_NAMA }}</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalreke[$s],0,',','.') }},00</b></td>
	</tr>
	@endif
	@else
	<tr>
		<td class="border-rincian kiri"><b>{{ $reke[$s]->REKENING_KODE }}</b></td>
		<td class="border-rincian"><b>&nbsp;&nbsp;&nbsp;{{ $reke[$s]->REKENING_NAMA }}</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalreke[$s],0,',','.') }},00</b></td>
	</tr>
	@endif

	@if($q != 0)
	@if($rek[$q-1]->REKENING_KODE != $rek[$q]->REKENING_KODE)
	<tr>
		<td class="border-rincian kiri"><b>{{ $rek[$q]->REKENING_KODE }}</b></td>
		<td class="border-rincian"><b>&nbsp;&nbsp;&nbsp;&nbsp;{{ $rek[$q]->REKENING_NAMA }}</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalrek[$q],0,',','.') }},00</b></td>
	</tr>
	@endif
	@else
	<tr>
		<td class="border-rincian kiri"><b>{{ $rek[$q]->REKENING_KODE }}</b></td>
		<td class="border-rincian"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $rek[$q]->REKENING_NAMA }}</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($totalrek[$q],0,',','.') }},00</b></td>
	</tr>
	@endif
	<?php $q++;$s++;?>
	<tr>
		<td class="border-rincian kiri"><b>{{ $r->rekening->REKENING_KODE }}</b></td>
		<td class="border-rincian"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $r->rekening->REKENING_NAMA }}</b></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian tengah"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan"></td>
		<td class="border-rincian kanan border"><b>{{ number_format($r->total,0,',','.') }},00</b></td>
	</tr>
	<?php $l=0;?>
	@foreach($paket[$m] as $p)
	<tr>
	  <td class="border-rincian tengah"></td>
	  <td class="border-rincian">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $p->subrincian->SUBRINCIAN_NAMA }}<b></td>
	  <td class="border-rincian tengah"></td>
	  <td class="border-rincian tengah"></td>
	  <td class="border-rincian kanan"></td>
	  <td class="border-rincian kanan"></td>
	  <td class="border-rincian kanan rekening">{{ number_format($p->total,0,',','.') }},00</td>
	 </tr>
	@foreach($komponen[$m][$l++] as $k)
	<tr>
	  <td class="border-rincian tengah"></td>
	  @if($k->PEKERJAAN_ID == 4 or $k->PEKERJAAN_ID == 5)
	  <td class="border-rincian">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>- @foreach(explode('#', $k->RINCIAN_KETERANGAN) as $info) 
    	{{$info}}@break
  	  @endforeach
  	  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;({{ $k->RINCIAN_KOEFISIEN }})</i></td>
	  @else
	  <td class="border-rincian">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>- {{ $k->RINCIAN_KOMPONEN }}  {{ $k->komponen->KOMPONEN_NAMA }}
	  	<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;({{ $k->RINCIAN_KOEFISIEN }})</i></td>
	  @endif
	  <td class="border-rincian tengah">{{ $k->RINCIAN_VOLUME }}</td>
	  <td class="border-rincian tengah">{{ $k->komponen->KOMPONEN_SATUAN }}</td>
	  <td class="border-rincian kanan">{{ number_format($k->RINCIAN_HARGA,0,',','.') }},00</td>
	  @if($k->RINCIAN_PAJAK == 0)
	  <td class="border-rincian kanan">0,00</td>
	  @else
	  <td class="border-rincian kanan">{{ number_format(($k->RINCIAN_HARGA * $k->RINCIAN_VOLUME)*10/100 ,0,',','.') }},00 </td>
	  @endif
	  <td class="border-rincian kanan">{{ number_format($k->RINCIAN_TOTAL,0,',','.') }},00 </td>
	 </tr>
	@endforeach
	@endforeach
	<?php $m++;?>
	@endforeach
	<tr class="border">
		<td class="border kanan" colspan="6"><b>Jumlah</b></td>
		<td class="border kanan"><b>{{ number_format($total,0,',','.') }},00</b></td>
	</tr>
	</tbody>	
</table>
@if($bl->BL_ID == 2945 || $bl->BL_ID == 5703) <br><br><br> <br><br><br><br><br><br>@endif
<table class="ttd">
	<tr>
		<td class="tengah">Triwulan I</td>
		<td class="kiri">Rp. {{ number_format($akb_bl->tri1,0,',','.') }}</td>
		<td width="50%"> </td>
		<td>Bandung, {{(isset($tgl_ttd) ? (strlen($tgl_ttd)>0?$tgl_ttd:$tgl.' '.$bln.' '.$thn) : '2 Januari 2018')}}</td>
	</tr>
	<tr>
		<td class="tengah">Triwulan II</td>
		<td class="kiri">Rp. {{ number_format($akb_bl->tri2,0,',','.') }} </td>
		<td width="50%"> </td>
		<td><b>{{(isset($jabatan_ttd) ? (strlen($jabatan_ttd)>0?$jabatan_ttd:'Pejabat Pengelola Keuangan Daerah') : 'Pejabat Pengelola Keuangan Daerah')}}</b></td>
	</tr>
	<tr>
		<td class="tengah">Triwulan III</td>
		<td class="kiri">Rp. {{ number_format($akb_bl->tri3,0,',','.') }} </td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="tengah">Triwulan IV</td>
		<td class="kiri">Rp. {{ number_format($akb_bl->tri4,0,',','.') }}</td>
		<td width="50%"> </td>
		<td></td>
	</tr>
	<tr>
		<td class="kanan"><b>Jumlah</b></td>
		<td class="kiri"><b>Rp. {{ number_format($akb_bl->tri1+$akb_bl->tri2+$akb_bl->tri3+$akb_bl->tri4,0,',','.') }}</b></td>
		<td width="50%"> </td>
		<td></td>
	</tr>

	<tr>
		<td class="kiri"> </td>
		<td class="kiri"> </td>
		<td width="50%"> <br><br><br><br><br><br></td>
		<td><span style="border-bottom: 1px solid #000;padding-bottom: 1px;">{{(isset($nama_ttd) ? (strlen($nama_ttd)>0?$nama_ttd:'Drs. DADANG SUPRIATNA, MH') : 'Drs. DADANG SUPRIATNA, MH')}} <br><br> </span> {{(isset($nip_ttd) ? (strlen($nip_ttd)>0?'NIP. '.$nip_ttd:'NIP. 19610308 199103 1 009') : 'NIP. 19610308 199103 1 009')}}  </td>
	</tr>
</table>
</div>
</body>
</html>