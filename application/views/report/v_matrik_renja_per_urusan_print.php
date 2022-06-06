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



$title = "PLAFON ANGGARAN MATRIK ";
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
    Tabel 4.3 <br>
		PLAFON ANGGARAN SEMENTARA BERDASARKAN PROGRAM DAN KEGIATAN
		<br>TAHUN ANGGARAN <?php echo $NMTAHUN; ?> <br>

	</p>

	<table class="table f12">
	<thead>

	<tr>

	</tr>
	<tr>
		<th rowspan="3" class="normal">Perangkat Daerah</th>
		<th rowspan="3" class="normal">No</th>
		<th rowspan="3" class="normal">Urusan Pemerintah Daerah /
      <br>Program / Kegiatan </th>
		<th rowspan="3" class="normal">Sumber Dana</th>
		<th colspan="6" class="normal">Indikator Kinerja</th>
		<th rowspan="3" class="normal">NILAI</th>
	</tr>
	<tr>
	<th colspan="2" class="normal">Capaian Program</th>
		<th colspan="2" class="normal">Keluaran Kegiatan</th>
		<th colspan="2" class="normal">Hasil Kegiatan</th>

	</tr>
	<tr>
		<th class="normal">Tolak Ukur</th>
		<th class="normal">Target</th>
		<th class="normal">Tolak Ukur</th>
		<th class="normal">Target</th>
		<th class="normal">Tolak Ukur</th>
		<th class="normal">Target</th>

	</tr>

	<tr>
		<th class="w1px normal">1</th>
		<th class="normal">2</th>
		<th class="normal">3</th>
		<th class="normal">4</th>
		<th class="normal">5</th>
		<th class="normal">6</th>
		<th class="normal">7</th>
		<th class="normal">8</th>
		<th class="normal">9</th>
		<th class="normal">10</th>
		<th class="normal">11</th>
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
	foreach($getMatrikRenjaUrusanAll as $data) : $no++;

		$NMUNIT = $data->SKPD;

		$TYPE =	$data->TYPE;

	?>
	<tr>
		<td class="va-top"><?php echo $NMUNIT ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->KODE ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->NMPRGRM ?></td>
	  <td class="va-top text-center"><?php echo $data->NMDANA ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->INDIKATOR ?></td>
		<td class="va-top"><?php echo $data->TCPAPAIPGR ?></td>
		<td class="va-top"><?php echo $data->KELUARAN ?></td>
		<td class="va-top"><?php echo $data->TARGETLUAR1 ?></td>
		<td class="va-top"><?php echo $data->HASIL ?></td>
		<td class="va-top"><?php echo $data->TARGETHASIL ?></td>
		<td class="va-top text-right" style="text-align:right"><?php echo number_format($data->PAGU, 0, ',', '.') ?></td>



	</tr>

	<?php
	$totalpagu +=  $data->PAGU;
	endforeach; ?>

	<tr>

		<td colspan= "9" class="text-center">TOTAL</td>
		<td colspan= "3"><?php echo number_format($totalpagu, 0, ',', '.')?></td>


	 </tr>

	</tbody>
	</table>

	<table style="float:right; margin-top:20px; margin-right: 30px; width: 200px;" >
		<?php
 			foreach($nmakota as $k):
		 ?>
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
				<?php
				endforeach; ?>
	</table>
	<table >
				<tr>
					<td> <?php echo $NMTAHAP ?> </td>
				</tr>

	</table>

	<div class="clear"></div>
</div>
</body>
</html>
