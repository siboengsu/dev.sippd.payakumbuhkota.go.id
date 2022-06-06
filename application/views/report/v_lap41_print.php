<?php
setlocale (LC_TIME,
	'id_ID.UTF8',
	'id_ID.UTF-8',
	'id_ID.8859-1',
	'id_ID',
	'IND.UTF8',
	'IND.UTF-8',
	'IND.8859-1',
	'IND',
	'Indonesian.UTF8',
	'Indonesian.UTF-8',
	'Indonesian.8859-1',
	'Indonesian',
	'Indonesia',
	'id',
	'ID');
	

$NMTAHUN = $this->session->NMTAHUN;
$TANGGAL=date('Y-m-d');
$title = "REKAPITULASI RENCANA KERJA SKPD ";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $title; ?></title>
<style type="text/css">
@page {
	size: A4;
	margin:2.4cm 2.4cm 2.4cm 2.4cm;
	size: potrait;
}

@media screen {
	body {
		font-family: Arial-Narrow;
		font-size : 12px;
		margin: 10px auto !important;
		padding: 20px 20px !important;
	}

	.page-break { padding: 15px 0 15px 0; }
}

@media print {
	* {
		-webkit-print-color-adjust: exact;
		print-color-adjust: exact;
		size: legal;
		}

	body {
		font-family: Arial-Narrow;
		font-size : 12px;
		margin: 10 !important;
		padding: 0 !important;
	}
.table th,
.table td {
	padding: 3px;
	padding-top:7px;
	padding-bottom:7px;
	border: 1px solid black;
}
	.judul {font-size:12px; font-weight:bold;}
	.page-break { page-break-after: always; }
}

table {
	margin: 0;
	border-collapse: collapse;
	font-weight: normal;
}
.table thead tr {
	//background-color: #cccccc;
}
.table tr {
	padding:20px
}
.table tbody tr.one {
	background-color: #dddddd;
}
.table tbody tr.two {
	background-color: #eeeeee;
}
.table th,
.table td {
	padding: 3px;
	padding-top:7px;
	padding-bottom:7px;
	border: 1px solid black;
}
.normal {
	font-weight:normal;
	font-size : 12px;
}

h3 { margin: 10px 0 10px 0 }
h4 { margin: 0 0 10px 0 }
.clear { clear: both; }
.text-bold { font-weight: bold; }
.text-center { text-align: center; }
.text-right { text-align: right; }
.text-justify { text-align: justify; }
.text-nowrap { white-space: nowrap; }
.va-top { vertical-align: top !important; }
.va-bot { vertical-align: bottom !important; }
.va-mid { vertical-align: middle !important; }
.para { text-indent: 50px; }
.w100 { width:100%; }
.w1px { width:1px; }
</style>
 <script>
	window.onload = window.print;
 </script>
</head>
<body>

<div class="f14 w100 landscape" >
	<p class="text-center judul" style="font-size:14px; font-weight:bold;">
		Tabel 4.1 <br>
		Plafon Anggaran Sementara Menurut Urusan Pemerintahan Daerah, Program, Kegiatan, dan Sub Kegiatan<br>
	</p>
	
	<div style="font-size:12px; font-weight:bold;"></div>
	<table style="margin-left:auto;margin-right:auto" class="table f12">
	<thead>
	<tr>
	
	</tr>
	<tr>
		<th colspan="2"><b>URUSAN PEMERINTAHAN DAERAH DAN PROGRAM, KEGIATAN DAN SUB KEGIATAN</b></th>
		<th >PLAFON ANGGARAN SEMENTARA (Rp)</th>
		<th>KET</th>
	</tr>
	<tr>
		<th class="normal">1</th>
		<th class="normal">2</th>
		<th class="normal">3</th>
		<th class="normal">4</th>
	</tr>
	</thead>
	
	<tbody>
	<?php
	foreach($matrik51all as $data) :
	$TYPE = $data['TYPE'];
	?>
	<tr>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; }?>"><?php echo $data['KODE']; ?><br></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; }?>"><?php echo $data['NMPRGRM']; ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; }?>" style="text-align:right;"><?php echo number_format($data['PAGU'], 0, ',', '.'); ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; }?>"></td>
	<?php
	endforeach; ?>
	
	</tbody>
	</table>
</div>
</body>
</html>