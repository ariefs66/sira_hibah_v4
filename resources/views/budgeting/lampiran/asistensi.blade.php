<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<html>
<head>
	<title>Daftar Asistensi</title>
	<style type="text/css">
		* {
	      margin: 0;
	      padding: 0;
	    }
		body{
			font-family: Tahoma;
			font-size: 60%;
		}
		table{
			width: 100%;
		}
		td{
			padding-left: 3px;
		}
		table, tr, td{
			border-collapse: collapse;
		}
		.detail{
			margin-top: 5px;
		}
		.detail > tbody > tr > td{
			padding: 3px;		
			border:thin solid black;
		}
		.header{
			margin-top: 20px;
		}
		h4, h5{
			text-align: center;
			margin-top: 0px;
			margin-bottom: 0px;
		}
		.kanan{
			text-align: right;
			padding-right: 5px;
		}
		.tengah{
			text-align: center;
			vertical-align: middle;
		}
		.noborder{
			border-style: none;
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>
<body onload="//return download()">
<button id="btnExport">Export to xls</button>
<h4> 
	Daftar Asistensi Belanja Langsung Tahun {{ $tahun }}<br>Kota Bandung</h4>
<table class="header">
	<tr class="noborder">
	</tr>
</table>
<table class="detail">
	<tbody>
	<tr class="tengah header">
		<td>SKPD</td>
		<td>Kegiatan / Program</td>
		<td>Asistensi</td>
		<td>Catatan</td>
	</tr>
	@foreach($aaData as $o)
	<tr class="tengah header">
		<td>{!! $o['SKPD'] !!}</td>
		<td>{!! $o['URAIAN'] !!}</td>
		<td>{!! $o['KONTEN'] !!}</td>
		<td>{!! $o['CATATAN'] !!}</td>
	</tr>
	@endforeach
	</tbody>
</table>
</div>
</body>
<script type="text/javascript">
	$(document).ready(function() {
		  $("#btnExport").click(function(e) {
		    e.preventDefault();
		    console.log(e);
		    //getting data from our table
		    var data_type = 'data:application/vnd.ms-excel';
		    var table_div = document.getElementById('table_wrapper');
		    var table_html = table_div.outerHTML.replace(/ /g, '%20');
			var header = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
			header = header + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
			header = header + '<x:Name>Error Messages</x:Name>';

			header = header + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
			header = header + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
			var footer = '</body></html>';

		    var a = document.createElement('a');
		    a.href = data_type + ', ' + header + table_html +footer;
		    a.download = 'DAFTAR ASISTENSI.xls';
		    a.click();
		  });
	});

	function download(){
		$("#btnExport").trigger('click');
	}
</script>
</html>
