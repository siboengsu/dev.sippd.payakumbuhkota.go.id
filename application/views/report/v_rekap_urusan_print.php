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
//$TANGGAL= $this->input->post('f-tanggal');


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
	<p class="text-center bold" style="font-size:12px; font-weight: bold;">
		TABEL V.2 <br>
		REKAPITULASI RENCANA <br>
		PROGRAM DAN KEGIATAN PRIORITAS DAERAH TAHUN ANGGARAN <?php echo $NMTAHUN; ?> <br>
		MENURUT URUSAN
	</p>

	<table class="table f12">
	<thead>
	<tr>
		<th class="w1px">No.</th>

		<th>URUSAN</th>
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
	$total1 = 0;
	$total2 = 0;
	$no = 0;
	$pagusisa = 0;
	$totalpagu = 0;
	$NM = NULL;
	$NAMA = NULL;
	$TYPE = NULL;
	$PAGU1=NULL;
	$PAGU2=NULL;

	foreach($getUrusanAll as $data): $no++;
		$kdlevel = $data->KDLEVEL;
		if ($kdlevel != 4) {
			$TYPE =	$data->TYPE;
			IF ($TYPE =='H')
			{
				$NM = $data->NMPRGRM;
				$PAGU2 = $data->PAGU;
				$NAMA =NULL;
				$PAGU1= NULL;

			}
			ELSE
			{
				$NAMA = $data->NMPRGRM;
				$PAGU1 = $data->PAGU;
				$NM = NULL;
				$PAGU2= NULL;
			}
		?>
		<tr>

			<td class="va-top"><?php echo $data->KODE ?></td>
			<td class="va-top"><?php echo $NM ?></td>
			<td class="va-top"><?php echo $NAMA ?></td>
			<td class="va-top text-right"><?php echo number_format($data->PAGU, 0, ',', '.') ?></td>
			<td class="va-top" style="display:none"><?php echo number_format($PAGU1, 0, ',', '.') ?></td>

		</tr>

		<?php
		$total += $PAGU1;
		}

	endforeach;



	foreach($pagukec as $pagukec):
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
	 		<td class="va-top text-nowrap "><?php echo $kodeunit; ?></td>
	 		<td class="va-top"></td>
	 		<td class="va-top"><?php echo $nama ;  ?></td>
	 		<td class="va-top text-right"><?php echo number_format($pagukec['PAGUKEC'], 0, ',', '.'); ?></td>
	 	</tr>

	 <?php
	 	$total2 += $pagukec['PAGUKEC'];
	 	endforeach;

		$totalpagu = $total2 + $total ;
	  ?>

	<tr>

		<td colspan="3" class="text-center bold">TOTAL</td>
		<td class="text-right bold"><?php echo number_format($totalpagu, 0, ',', '.')?></td>

	 </tr>

	</tbody>
	</table>

	

	<div class="clear"></div>
</div>


</body>
</html>
