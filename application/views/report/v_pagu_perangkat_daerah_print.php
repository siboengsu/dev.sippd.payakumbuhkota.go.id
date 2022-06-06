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



$title = "Pagu Perangkat Daerah";
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
	margin: 2cm 0.3cm 2.4cm 2.4cm;
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


<div class="f14 w100" >
	<p class="text-center" style="font-size:12px; font-weight:bold; margin-bottom:30px">
		Tabel 4.2<br>
		PLAFON ANGGARAN SEMENTARA PAGU PERANGKAT DAERAH TAHUN  <?php echo $NMTAHUN; ?>
		<br>

	</p>
	<table class="table f12">
	<thead>
	<tr>
		<th class="w1px">No.</th>
		<th>KODE</th>
		<th>PERANGKAT DAERAH</th>
		<th>PAGU</th>
	</tr>
	<tr>
		<th class="w1px">1</th>
		<th>2</th>
		<th>3</th>
		<th>4</th>

	</tr>
	</thead>
	<tbody>
	<?php
	$total = 0;
	$no = 0;
	$pagusisa = 0;
	$totalpagu = 0;
	$totalpagudigunakan = 0;
	$totalpagusisa = 0;
	$p = 0;
	$pagusisa0 = 0;
	foreach($paguskpdall as $data): $no++;

	?>
	<tr>
		<td class="va-top text-nowrap text-center"><?php echo $no; ?>.</td>
		<td class="va-top"><?php echo $data['KDUNIT'] ?></td>
		<td class="va-top"><?php echo $data['NMUNIT'] ?></td>
		<td class="va-top  text-right"><?php echo number_format($data['PAGU'], 0, ',', '.')?></td>

	<?php

	$totalpagu += $data['PAGU'];
	endforeach; ?>

	<tr>
		<td colspan="3" class="text-center">TOTAL</td>
		<td><?php echo number_format($totalpagu, 0, ',', '.')?></td>
	 </tr>

	</tbody>
	</table>
	<table style="float:right; margin-top:20px; margin-right: 30px;">

			<tr>
				<td class="text-center">Payakumbuh, <?php echo tanggal_indo($TANGGAL); ?>

					<br>Kepala Bappeda

						<br>
						<br>
						<br>
						<br>
						<br><u>Drs.H.RIDA ANANDA, M.Si</u>
						<br>NIP. 19680607 198809 1 001
				</td>
			</tr>

</table>


	<div class="clear"></div>
</div>
</body>
</html>
