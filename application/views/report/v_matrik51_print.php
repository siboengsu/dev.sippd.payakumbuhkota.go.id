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
$prakiraan = $NMTAHUN +1;

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
	margin: 2.4cm 0.3cm 2.4cm 2.4cm;
	size: landscape;
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
	
	<p class="text-center judul" style="font-size:12px; font-weight:bold;">
	Tabel V.I <br>
		REKAPITULASI RENCANA PROGRAM DAN KEGIATAN PRIORITAS DAERAH TAHUN ANGGARAN <?php echo $NMTAHUN; ?> <br>
		PEMERINTAHAN KOTA PAYAKUMBUH



	</p>


	<table class="table f12">
	<thead>

	<tr>

	</tr>
	<tr>

		<th rowspan="3" >No</th>
		<th rowspan="3" >Urusan Pemerintahan Daerah/ <br>
		Program/ Kegiatan</th>
		<th rowspan="3" >Prioritas</th>
		<th colspan="6" >Indikator Kinerja</th>
		<th rowspan="3" >Dana Indikatif Tahun <?php echo $NMTAHUN; ?></th>
		<th rowspan="3" >Prakiraan Maju Tahun <?php echo $prakiraan ?></th>
		<th rowspan="3" >PD Penanggung Jawab</th>

	</tr>
	<tr>
	<th colspan="2" >Capaian Program (Indikator Sasaran)</th>
		<th colspan="2" >Keluaran (Output)</th>
		<th colspan="2" >Hasil (Outcome)</th>

	</tr>
	<tr>
		<th >Tolok Ukur</th>
		<th >Target</th>
		<th >Tolok Ukur</th>
		<th >Target</th>
		<th >Tolok Ukur</th>
		<th >Target</th>

	</tr>

	<tr>
		<th class="w1px">1</th>
		<th >2</th>
		<th >3</th>
		<th >4</th>
		<th >5</th>
		<th >6</th>
		<th >7</th>
		<th >8</th>
		<th >9</th>
		<th >10</th>
		<th >11</th>
		<th >12</th>


	</tr>
	</thead>
	<tbody>
	<?php
	$total = 0;
	$no=0;
	$pagusisa = 0;
	$totalpagu = 0;
	$totalpagudigunakan = 0;
	$totalpagusisa = 0;
	$NM = NULL;
	$KEG = NULL;
	$NMKEG = NULL;
	$NMUNIT = NULL;
	$detailpagu = NULL;
	$TYPE  = NULL;
	$nip =  NULL;
	$jab =  NULL;
	$nama =  NULL;
	$NMTAHAP = NULL;
	$paguplus1 = NULL;
	$paguplustot = NULL;
	foreach($matrik51all as $data) : $no++;

		$TYPE =	$data->TYPE;
		IF ($TYPE =='H')
		{
			$detailpagu = NULL;
				$NMUNIT = $data->SKPD;
				$paguplus1= NULL;
					}
		ELSE
		{
			$detailpagu = $data->PAGU;
			$NMUNIT = NULL;
				$paguplus1= $data->PAGUPLUS;
		}
	?>
	<tr>

		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->KODE ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->NMPRGRM ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->NOPRIO ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->INDIKATOR ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->TCPAPAIPGR ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->KELUARAN ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->TARGETLUAR1 ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->HASIL ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->TARGETHASIL ?></td>
		<td class="va-top text-right <?php if($TYPE=="H"){ echo "text-bold"; } ?>" style="text-align:right"><?php echo number_format($data->PAGU, 0, ',', '.') ?></td>
		<td class="va-top text-right <?php if($TYPE=="H"){ echo "text-bold"; } ?>" style="text-align:right"><?php echo number_format($data->PAGUPLUS, 0, ',', '.') ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $NMUNIT ?></td>



	</tr>

	<?php
	$totalpagu +=  $detailpagu;
	$paguplustot += $paguplus1;
	endforeach; ?>

	<tr>

		<td colspan= "9" class="text-center text-bold">TOTAL</td>
		<td class="text-bold text-right"><?php echo number_format($totalpagu, 0, ',', '.')?></td>
		<td class="text-bold text-right"><?php echo number_format($paguplustot, 0, ',', '.')?></td>
		<td></td>

	 </tr>

	</tbody>
	</table>


	<div class="clear"></div>
</div>
</body>
</html>
