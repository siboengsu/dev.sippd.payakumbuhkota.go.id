<?php
$NMTAHUN = $this->session->NMTAHUN;
 $TANGGAL=date('Y-m-d');
$title = "Laporan SSH";
$this->output->set_header("Content-type: application/octet-stream");
$this->output->set_header("Content-Disposition: attachment; filename=$title.doc");
$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_update).' GMT');
$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
$this->output->set_header("Pragma: no-cache");


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
 <script>
 window.onload = window.print;
 </script>
</head>
<body>

<div class="f14 w100">
	<p class="text-center" style="font-size:12px; font-weight:bold;">
		Laporan Satuan Standar Harga (SSH)<br> TAHUN ANGGARAN <?php echo $NMTAHUN; ?><br>

	</p>


	<table class="table f12" style ="width='90%'">
	<thead>
	<tr>
		<th class="w1px tr">No.</th>
		<th class="tr">Kode SSH</th>
		<th class="tr">Kode Rekening</th>
		<th class="tr">Nama SSH</th>
    <th class="tr">Spesifikasi</th>
    <th class="tr">Satuan</th>
    <th class="tr">Harga</th>

	</tr>
	<tr>
		<th class="w1px th">1</th>
		<th class="th">2</th>
		<th class="th">3</th>
		<th class="th">4</th>
    <th class="th">5</th>
    <th class="th">6</th>
    <th class="th">7</th>

	</tr>
	</thead>
	<tbody>
	<?php
	$total = 0;
	$no = 0;

	foreach($cetakSSH as $data): $no++;

	?>
	<tr>
		<td class="va-top text-nowrap text-center"><?php echo $no; ?>.</td>
		<td class="va-top"><?php echo $data['KDSSH'] ?></td>
    <td class="va-top"><?php echo $data['KDREK'] ?></td>
    <td class="va-top"><?php echo $data['SSH_NAMA'] ?></td>
    <td class="va-top"><?php echo $data['SSH_SPEK'] ?></td>
    <td class="va-top"><?php echo $data['SSH_SATUAN'] ?></td>
		<td class="va-top text-right"><?php echo number_format($data['SSH_HARGA'], 0, ',', '.')?></td>

	</tr>

	<?php


	endforeach; ?>



	</tbody>
	</table>


	<div class="clear"></div>
</div>
</body>
</html>