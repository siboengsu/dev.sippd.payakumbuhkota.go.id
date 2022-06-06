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
 <script>
 window.onload = window.print;
 </script>
</head>
<body>


<div class="f14 w100" >
	<p class="text-center" style="font-size:12px; font-weight:bold; margin-bottom:30px">
		
		REKAPITULASI RENCANA<br>
		PROGRAM DAN KEGIATAN PRIORITAS DAERAH<br>
		TAHUN ANGGARAN <?php echo $NMTAHUN; ?> <br>
		MENURUT PERANGKAT DAERAH
	</p>
	<table class="table f12">
	<thead>
	<tr>
		<th class="w1px">No.</th>
		<th>PERANGKAT DAERAH</th>
		<th>PAGU</th>
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
	$no1 = $no;
	$p = 0;
	$pu = 0;
	$s = 0;
	$nama = "";
	$p = 0;
	$pu = 0;
	$s = 0;
	$nama1 = "";
	$p1 = 0;
	$pu1 = 0;
	$s1 = 0;
	$nama2 = "";
	$p2 = 0;
	$pu2 = 0;
	$s2 = 0;
	$nama3 = "";
	$p3 = 0;
	$pu3 = 0;
	$s3 = 0;
	$nama4 = "";
	$p4 = 0;
	$pu4 = 0;
	$s4 = 0;
	$pagusisa1 = 0;
	$pg = 0;
	$pg1 = 0;
	$pg2 = 0;
	$pg3 = 0;
	$pg4 = 0;
	$pll = 0;
	$pl2 = 0;
	$pl3 = 0;

	foreach($paguskpdall as $data): $no++;
	$kdlevel = $data['KDLEVEL'];
	$unitkey =  $data['UNITKEY'];
	if ($kdlevel == 3 ) {
		$pl1 = $data['PAGU'];
		$pl2 = $data['PAGUUSED'];
		$pl3 = $data['SELISIH'];

		?>
		<tr>
		<td class="va-top text-nowrap text-center"><?php echo $no; ?>.</td>
		<td class="va-top"><?php echo $data['NMUNIT'] ?></td>
		<td class="va-top  text-right"><?php echo number_format($pl1, 0, ',', '.')?></td>
		<td class="va-top  text-right"><?php echo number_format($pl2, 0, ',', '.')?></td>
		<?php
		$pagusisa0= $pl3;
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

}
$p4 += $pl1;
$pu4 += $pl2;
$s4 += $pagusisa0;
endforeach;

$no1 = $no;

foreach($pagukec as $pagukec): $no1++;
$kodeunit = $pagukec['KODEUNIT'];

if ($kodeunit == "6.00.01.01." ) {
	$nama = "KECAMATAN PAYAKUMBUH BARAT";
}
	elseif ($kodeunit == "6.00.01.03." ) {
		$nama = "KECAMATAN PAYAKUMBUH UTARA";
	}
	elseif ($kodeunit == "6.00.01.02." ) {
		$nama = "KECAMATAN PAYAKUMBUH TIMUR";
	}
	elseif ($kodeunit == "6.00.01.04." ) {
		$nama = "KECAMATAN PAYAKUMBUH SELATAN";
	}
	elseif ($kodeunit == "6.00.01.05." ) {
		$nama = "KECAMATAN LAMPOSI TIGO NAGORI";
	}

?>



<tr>
		<td class="va-top text-nowrap text-center"><?php echo $no1; ?>.</td>
		<td class="va-top"><?php echo $nama ; ?></td>
		<td class="va-top  text-right"><?php echo number_format($pagukec['PAGUKEC'], 0, ',', '.');  ?></td>
		<td class="va-top  text-right"><?php echo number_format($pagukec['PAGUUSEKEC'], 0, ',', '.'); ?></td>
		<?php
		$pagusisa0= $pagukec['PAGUKEC'] -  $pagukec['PAGUUSEKEC'] ;

		 if($pagusisa0 == 0)
		 {
			$pagusisa = "-";
			 $pg= $pagusisa;
		 }
			 else
			{
				$pagusisa = $pagusisa0;
				 $pg = number_format($pagusisa, 0, ',', '.');
		 }
		?>
		<td class="va-top text-right"><?php echo $pg ?> </td>


	</tr>

<?php

		$pg2 += $pagukec['PAGUKEC'];
		$pg3 += $pagukec['PAGUUSEKEC'];
		$pg4 + $pagusisa0;


			endforeach;

		$totalpagu = 	$p4 + $pg2;
		$totalpagudigunakan =	$pu4 + $pg3;
		$pagusisa = 	$s4 + $pg4 ;

		 ?>

		 <tr>
				<td></td>
				<td class="text-center text-bold">TOTAL</td>
				<td class="text-right text-bold"><?php echo number_format($totalpagu, 0, ',', '.')?></td>
				<td class="text-right text-bold"><?php echo number_format($totalpagudigunakan, 0, ',', '.')?></td>
				<td class="text-right text-bold"> <?php echo number_format($pagusisa, 0, ',', '.')?></td>

			 </tr>

	</tbody>
	</table>

	<div class="clear"></div>
</div>
</body>
</html>
