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



$title = "REKAPITULASI RENCANA KERJA PERANGKAT DAERAH  ";
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$title$NMTAHUN.doc");
header("Pragma: no-cache");
header("Expires: 0");

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
	margin: 2.4cm 0.3cm 2.4cm 2.4cm;
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
</head>
<body>


<div class="f14 w100" >
	
	<table class="table f12" border="1">
	<thead>
		<tr style="border:0">
			<td colspan="5" class="text-center bold" style="border:0">
				PAYAKUMBUH<br>
				PREKAPITULASI RENCANA KERJA PERANGKAT DAERAH <br>
				TAHUN ANGGARAN <?php echo $NMTAHUN; ?>
			</td>
		</tr>
	<tr>
		<th class="w1px">No.</th>
		<th>SKPD</th>
		<th>PAGU SKPD</th>
		<th>PAGU INDIKATIF</th>
		<th>SELISIH</th>

	</tr>
	<tr>
		<th class="w1px">1</th>
		<th>2</th>
		<th>3</th>
		<th>4</th>
		<th>5=(4-3)</th>

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
		<td class="va-top"><?php echo $data['NMUNIT'] ?></td>
		<td class="va-top  text-right"><?php echo number_format($data['PAGU'], 0, ',', '.')?></td>
		<td class="va-top  text-right"><?php echo number_format($data['PAGUUSED'], 0, ',', '.')?></td>
		<?php
		$pagusisa0= $data['PAGU'] - $data['PAGUUSED'];

		 if($pagusisa0 == 0)
		 {
			$pagusisa = "-";
			 $p= $pagusisa;
		 }
			 else
			{
				$pagusisa = $pagusisa0;
				 $p = number_format($pagusisa, 0, ',', '.');
		 }

		?>
		<td class="va-top text-right"><?php echo $p ?> </td>
	</tr>

	<?php

	$totalpagu += $data['PAGU'];
	$totalpagudigunakan += $data['PAGUUSED'];
	$totalpagusisa += $pagusisa0 ;

	endforeach; ?>

	<tr>
		<td></td>
		<td>TOTAL</td>
		<td><?php echo number_format($totalpagu, 0, ',', '.')?></td>
		<td><?php echo number_format($totalpagudigunakan, 0, ',', '.')?></td>
		<td class="text-right"><?php echo number_format($totalpagusisa, 0, ',', '.')?></td>

	 </tr>

	</tbody>
	</table>

	<div class="clear"></div>
</div>
</body>
</html>
