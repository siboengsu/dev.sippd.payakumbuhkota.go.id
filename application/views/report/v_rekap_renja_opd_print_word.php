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



$title = "MATRIK RENJA SOPD OUTCOME - ";
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

<div style=" font-weight:bold;"> </div>
	<table class="table f12" border="1">
	<thead>
		<tr class="text-center bold" style="border:0;">
				<td colspan="13" class="text-center bold" style="border:0;">
					TABEL IV.I <br>
					PEMERINTAH KOTA PAYAKUMBUH <br>
					RENCANA PROGRAM DAN KEGIATAN PERANGKAT DAERAH DENGAN PRIORITAS DAERAH TAHUN <?php echo $NMTAHUN; ?> <br>

				</td>
		</tr>
		<tr style="border:0;">
			<td style="border:0;">

			</td>
		</tr>

		<tr>
			<?php
				$aa= NULL;
			foreach ($getRenjaAll as $k)
				$aa = $k->SKPD;

			?>
<td colspan="13" class="bold" style="border:0; font-size:12px;">
	Organisasi / PD :<?php echo $aa ?>
</td>
	<tr style="border:0;">
		<td style="border:0;">
		</td>
	</tr>
	<tr>

		<th rowspan="3" class="normal">No.</th>
		<th rowspan="3" class="normal">Program / Kegiatan </th>
		<th rowspan="3" class="normal">Prioritas</th>
		<th rowspan="3" class="normal">Sasaran</th>
		<th rowspan="3" class="normal">Lokasi</th>
		<th colspan="6" class="normal">Indikator Kinerja</th>
		<th rowspan="3" class="normal">Dana Indikatif Tahun <?php echo $NMTAHUN; ?> </th>
		<th rowspan="3" class="normal">Prakiraan Maju Tahun <?php echo $NMTAHUN + 1; ?>  </th>
		<th rowspan="3" class="normal">Jenis Kegiatan</th>
	</tr>
	<tr>
	<th colspan="2" class="normal">Capaian Program (Indikator Sasaran)</th>
		<th colspan="2" class="normal">Keluaran (Output)</th>
		<th colspan="2" class="normal">Hasil (Outcome)</th>

	</tr>
	<tr>
		<th class="normal">Tolok Ukur</th>
		<th class="normal">Target</th>
		<th class="normal">Tolok Ukur</th>
		<th class="normal">Target</th>
		<th class="normal">Tolok Ukur</th>
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
		<th class="normal">12</th>
		<th class="normal">13</th>
		<th class="normal">14</th>


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
	foreach($getRenjaAll as $data) : $no++;
		$NMUNIT = $data->SKPD;
		$pagu1= $data->PAGU1;
		$jab = $data->JAB;
		$nip = $data->NIP;
		$nama = $data->NAMA;
		$NMTAHAP = $data->NMTAHAP;
		IF ($pagu1 <= 0)
		 { $NM ="-" ; }
		 ELSE
		 {$NM = number_format($data->PAGU1, 0, ',', '.');}
	 $KEG= $data->SIFATKEG;
		IF ($KEG == 1)
		 {
			 $NMKEG ="Baru" ;
		 }
		 ELSEIF ($KEG == 2)
		 {
			$NMKEG ="Lanjutan" ;
		} ELSEIF ($KEG == 3)
		{$NMKEG ="Rutin" ;}
		ELSE {
			$NMKEG ="" ;
		}

		$TYPE =	$data->TYPE;
		IF ($TYPE =='H')
		{
			$detailpagu = NULL;
					}
		ELSE
		{
			$detailpagu = $data->PAGU;
		}
	?>
	<tr>

		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->KODE ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->NMPRGRM ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->PRIODAERAH ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->SASARAN ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->LOKASI ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->INDIKATOR ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->TCPAPAIPGR ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->KELUARAN ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->TARGETLUAR ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->OUTCOMETOLOKUR ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $data->OUTCOMETARGET ?></td>
		<td class="va-top text-right <?php if($TYPE=="H"){ echo "text-bold"; } ?>" style="text-align:right"><?php echo number_format($data->PAGU, 0, ',', '.') ?></td>
		<td class="va-top text-right <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $NM ?></td>
		<td class="va-top <?php if($TYPE=="H"){ echo "text-bold"; } ?>"><?php echo $NMKEG ?></td>
		<td style="display:none; border:0;" >  <?php echo number_format($detailpagu, 0, ',', '.') ?></td>


	</tr>

	<?php
	$totalpagu +=  $detailpagu;
	endforeach; ?>

	<tr>

		<td colspan= "11" class="text-center">TOTAL</td>
		<td><?php echo number_format($totalpagu, 0, ',', '.')?></td>

		<td colspan= "2"></td>
		<td style="border:0;"> </td>
	 </tr>
	 <?php
 		foreach($nmakota as $k):
 	 ?>
	 <tr style="margin-top:20px; margin-right: 30px; border:0;">
		<td colspan="11" style="border:0;"></td>
		<td colspan="3"  style="border:0;" class="text-center">Payakumbuh, <?php echo tanggal_indo($TANGGAL); ?>

			<br><?php echo $jab ?>

				<br>
				<br>
				<br>
				<br>
				<br><u><?php echo $nama ?></u>
				<br>NIP. <?php echo $nip ?>


			</td>
					<td style="border:0;"> </td>
	</tr>
	<?php
	endforeach; ?>


	</tbody>
	</table>




	<div class="clear"></div>
</div>
</body>
</html>
