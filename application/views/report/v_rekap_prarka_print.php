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
	margin: 2.4cm 0.3cm 2.4cm 2.4cm;
}

@media screen {
	body {
		font-family: Tahoma;
		font-size : 12px;
		margin: 5px auto !important;
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
		font-family: Tahoma;
		font-size : 12px;
		margin: 10 !important;
		padding: 0 !important;
	}



	.judul {font-size:12px; font-weight:bold;}

	.page-break { page-break-after: always; }
}

table {

	margin: 0;
	border-collapse: collapse;
	font-weight: normal;
	width: 100%;
}
.table thead tr {
	//background-color: #cccccc;
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
	border: 1px solid black;
}
.normal {
	font-weight:normal;
	font-size : 12px;
}
.bold {font-weight:bold;}
.judul {font-size:12px; font-weight:bold;}
.sub-judul {font-size:12px; font-weight:bold;}
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
.border-left {border-left: none !important;}
.border-right {border-right: none !important;}
</style>
 <script>
 window.onload = window.print;
 </script>
</head>
<body>


<div class="f14 w100" >
	<table class="table f12">
	<thead>

	<tr>

	<td rowspan="2"><img alt="Logo Kota Payakumbuh" src="../../../../assets/img/logo-rpt.png" height="70" style="display:block; margin:0 auto;"></td>
	<td class="text-center judul">DOKUMEN RENCANA KERJA ANGGARAN  <br>
		PERANGKAT DAERAH</td>
	<td rowspan="2" class="judul text-center">Formulir<br>RKA - PD 2.2.1<br>
			(Pra - RKA)
			</td>
	</tr>
	<tr>
		<td class="text-center judul">PEMERINTAH KOTA PAYAKUMBUH
		<br>TAHUN ANGGARAN <?php echo $NMTAHUN; ?>
		</td>
	</tr>
			<tr>
			<td class="va-top border-right" style="width:18%;" >Urusan
			</td>
			<td colspan="2" class="va-top border-left"> :  <?php echo $getPraRKAAll ?>
			</td>

		</tr>

		<tr>
			<td class="va-top border-right">Organisasi
			</td>
			<td colspan="2" class="va-top border-left"> : <?php echo $getUnitName ?>
			</td>
		</tr>
		<tr>
			<td class="va-top border-right">Program
			</td>
			<?php
				$nuprgrm = null;
				$nmprgrm = null;
				$pr = new stdClass();
				foreach($getProgramReport as $pr):;
					$nuprgrm = $pr->NUPRGRM;
					$nmprgrm = $pr->NMPRGRM;
			?>
			<td colspan="2" class="va-top border-left"> : <?php echo $nuprgrm  ?> <?php echo $nmprgrm ?>
			</td>
		</tr>
		<tr>
			<td class="va-top border-right">Kegiatan
			</td>
			<td colspan="2" class="va-top border-left"> : <?php echo $pr->NUKEG ?> <?php echo $getKegiatanName ?>
			</td>
		</tr>
		<?php
	endforeach;
				 $lokasi = null;
				 $LL = new stdClass();
			foreach($getLokasi as $LL):;
				 $lokasi = $LL->LOKASI;
		?>
		<tr>
			<td class="va-top border-right">Lokasi Kegiatan
			</td>
			<td colspan="2" class="va-top border-left"> : <?php echo $lokasi; ?>
			</td>
		</tr>

		<tr>
			<td class="border-right">Jumlah Tahun n-1
			</td>
			<td colspan="2" class="border-left"> : Rp. <?php echo  $getRPagu ?> ( <?php echo $getRPaguterbilang ?>  rupiah )
			</td>
		</tr>
		<?php
		$ttl = null;
				$tp = new stdClass();
		 foreach($getTotalPrarka as $tp);
					$ttl = $tp->total;
		?>
		<tr>
			<td class="border-right">Jumlah Tahun n
			</td>
			<td colspan="2" class="border-left"> : Rp. <?php echo number_format($ttl, 2, ',', '.') ?> ( <?php echo $paguterbilang ?>  rupiah )
			</td>
		</tr>
		<tr>
			<td class="border-right">Jumlah Tahun n + 1
			</td class="border-right">
			<td colspan="2" class="border-left"> : Rp. <?php echo number_format($LL->PAGUPLUS, 2, ',', '.') ?> ( <?php echo $paguplusterbilang ?>  rupiah )
			</td>
		</tr>
		<?php
				endforeach;
		?>
		
			<tr>
			<td class="border-right">Sumber Dana
			</td class="border-right">
			<td colspan="2" class="border-left"> : <?php echo $getDana  ?>
			</td>
		</tr>


					<table class="table f12" id="kinkeg">
					<thead>
							<tr>
							<td colspan="3" class="text-center text-bold" style="padding-top:30px;">
								Indikator & Tolok Ukur Kinerja Belanja Langsung
							</td>
							</tr>

							<tr>

								<th rowspan="3" class="text-bold"  style="width:18%;">Indikator</th>
								<th rowspan="3" class="text-bold">Tolok Ukur</th>
								<th rowspan="3" class="text-bold">Target Kinerja</th>
							</tr>
						</thead>
						<tbody>
								<?php

								 $jkk = null;
								 $urjkk = null;
								 $tolokur = null;
								 $target = null;
								  $urjkk1 = null;
								 $tolokur1 = null;
								 $target1 = null;
								 foreach($getTOLAKUKUR as $getTOLAKUKUR):;
								 $jkk = $getTOLAKUKUR->KDJKK;

								?>
							<tr>
							<?php
							 if ($jkk =='04') {
									 $urjkk1 = $getTOLAKUKUR ->URJKK;
									 $tolokur1 = $getTOLAKUKUR->TOLOKUR;
									 $target1 = $getTOLAKUKUR->TARGET;
									 ?>
										<td class="va-top" style="display:none"><?php echo $urjkk ?></td>
										<td class="va-top" style="display:none"><?php echo $tolokur ?></td>
										<td class="va-top" style="display:none"><?php echo $target ?></td>

								<?php

							}
									 else
									 {
									 $urjkk = $getTOLAKUKUR ->URJKK;
									 $tolokur = $getTOLAKUKUR->TOLOKUR;
									 $target = $getTOLAKUKUR->TARGET;
									 ?>
								<td class="va-top"><?php echo $urjkk ?></td>
								<td class="va-top"><?php echo $tolokur ?></td>
								<td class="va-top"><?php echo $target ?></td>
								<?php
									 }

							?>
							</tr>
							<?php
								 endforeach;
								?>
							<tr>
								<?php
								 if ($tolokur1 ==NULL){
									 		$tolokur1 = "-";
								 }
								 ?>
							<td class="va-top" colspan="3">Kelompok Sasaran Kegiatan :   &emsp;&emsp; <?php echo $tolokur1 ?></td>

							</tr>
					</tbody>
				  </table>
			  	<table class="table f12">
							<thead>
								<tr>
										<th colspan="7" class="text-center text-bold" style="padding-top:10px; padding-bottom:10px;">
											Rincian Anggaran Belanja Langsung
											<br>
											Menurut Program dan Per Kegiatan Perangkat Daerah
										</th>
						  	</tr>
							<tr>
								<th rowspan="2" class="text-center text-bold" style="width:18%;">Kode Rekening</th>
								<th rowspan="2" class="text-center text-bold">Uraian</th>
								<th colspan="3" class="text-center text-bold">Rincian Perhitungan</th>
								<th rowspan="2" class="text-center text-bold">Jumlah (Rp)</th>
							</tr>
							<tr>
								<th class="text-center text-bold">Volume</th>
								<th class="text-center text-bold">Satuan</th>
								<th class="text-center text-bold">Harga satuan</th>
							</tr>
							<tr>
									<th class="va-top text-center text-bold">1</th>
									<th class="va-top text-center text-bold">2</th>
									<th class="va-top text-center text-bold">3</th>
									<th class="va-top text-center text-bold">4</th>
									<th class="va-top text-center text-bold">5</th>
									<th class="va-top text-center text-bold">6 = (3 x 5)</th>

							</tr>
							</thead>
							<tbody>
								<?php

								$jumlah	= 0;
								$type = null;
								$kode = null;
								$kode1 = null;
								$uraian = null;
								$volume = 0;
								$satuan = null;
								$hargasatuan = 0;
								$volume1 = 0;
								$totjumlah = 0;
								$nip =  NULL;
								$jab =  NULL;
								$nama =  NULL;
								foreach($getDetailPraRKA as $d):;
									$kode = $d->KODE;
									$jumlah = $d->JUMLAH;
									$type = $d->TYPE;
									$uraian = $d->URAIAN;
									$volume = $d->VOLUME;
									$satuan = $d->SATUAN;
									$hargasatuan = $d->HARGASATUAN;
									$jumlah =	number_format($jumlah, 2, ',', '.');
									$hargasatuan =	number_format($hargasatuan, 2, ',', '.');
									$volume =	number_format($volume, 2, ',', '.');
									$jab = $d->JAB;
									$nip = $d->NIP;
									$nama = $d->NAMA;

									if ($hargasatuan == 0) {
										$hargasatuan = "-";}
										else {
										$hargasatuan = $hargasatuan;
										}
									if ($volume == 0) {
										$volume1 = "-";}
										else {
										$volume1 = $volume;
										}
									if (strlen($kode) > 12 ) {
										$kode1 = "";
										}
										else {
											$kode1 = $kode;
										}


								 ?>
											<tr>
												<?php
												if ($jumlah <= 0 && $type =="H"){
																$kode1 = "";
																$uraian = "";
																$volume1 = "";
																$satuan = "";
																$hargasatuan = " ";
																$jumlah = "";

												 ?>
												 <td class="va-top" style="display:none"><?php echo $kode1 ?></td>
												 <td class="va-top" style="display:none"><?php echo $uraian ?></td>
												 <td class="va-top" style="display:none"><?php echo $volume1 ?></td>
												 <td class="va-top" style="display:none"><?php echo $satuan ?></td>
												 <td class="va-top" style="display:none"><?php echo $hargasatuan ?></td>
												 <td class="va-top" style="display:none"><?php echo $jumlah ?></td>
										<?php
											}else {

																$uraian = $uraian;
																$volume1 = $volume1;
																$satuan = $satuan;
																$hargasatuan = $hargasatuan;

										 ?>
												<td class="va-top <?php if($type=="H"){ echo "text-bold"; } ?>"><?php echo $kode1 ?></td>
												<td class="va-top <?php if($type=="H"){ echo "text-bold"; }?>"><?php echo $uraian ?></td>
												<td class="va-top text-center <?php if($type=="H"){echo "text-bold"; } ?>"><?php echo $volume1 ?></td>
												<td class="va-top text-center <?php if($type=="H"){echo "text-bold"; } ?>"><?php echo $satuan ?></td>
												<td class="va-top text-right <?php if($type=="H"){echo "text-bold"; } ?>"><?php echo $hargasatuan ?></td>
												<td class="va-top text-right <?php if($type=="H"){echo "text-bold"; } ?>"><?php echo $jumlah ?></td>
											<?php
											} ?>
								</tr>
									<?php

									if ($type == "D")
									{

									}
									endforeach;
									$ttl = null;
									$tp = new stdClass();
							 		foreach($getTotalPrarka as $tp);
										$ttl = $tp->total;
								?>
								<tr>


									<td colspan="5" class="text-right bold">
											Jumlah
									</td>
									<td  class="text-right bold">
										<?php echo number_format($ttl, 2, ',', '.'); ?>
									</td>
								</tr>
								<tr>
									<table style="float:right !important; width: auto; margin-top:30px; margin-right: 30px;" >
										<?php
											foreach($nmakota as $k):
										 ?>
												<tr>
													<td class="text-center">Payakumbuh, <?php echo tanggal_indo($TANGGAL); ?>

													<br><?php echo $jab ?>

														<br>
														<br>
														<br>
														<br>
														<br>
														<br>
														<br><u><?php echo $nama ?></u>
														<br>NIP. <?php echo $nip ?>
													</td>
												</tr>
												<?php
												endforeach; ?>
									</table>
								</tr>


						</tbody>
				</table>


		    	</tbody>
	      	</table>


	<div class="clear"></div>
</div>

</body>
</html>
