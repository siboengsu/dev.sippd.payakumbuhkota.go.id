<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report_matrik extends CI_Controller {
private $json = ['cod' => NULL, 'msg' => NULL, 'link' => NULL];
private $KDTAHUN = NULL;
private $KDTAHAP = NULL;
private $UNITKEY = NULL;
private $KEGRKPDKEY = NULL;
private $TANGGAL = NULL;
private $tgl = NULL;
private $HOSTNAME = NULL;
private $USERNAME = NULL;
private $PASSWORD = NULL;
private $DATABASE = NULL;
private $FORMAT = NULL;
private $EXT = NULL;
private $PARAMETER = [];
private $PARAMETER_DATE = [];
function __construct()
{
parent::__construct();
$this->sip->is_logged();
$this->load->model(['m_set', 'm_user', 'm_rka', 'm_program', 'm_kegiatan']);
$this->KDTAHUN = $this->session->KDTAHUN;
$this->KDTAHAP = $this->session->KDTAHAP;
$this->NMTAHUN = $this->session->NMTAHUN;
$this->TANGGAL = date('d-m-Y');
$this->tgl = date('Y-m-d');
$this->HOSTNAME = $this->db->hostname;
$this->DATABASE = $this->db->database;
$this->USERNAME = $this->db->username;
$this->PASSWORD = $this->db->password;
}

public function induk($nama = '', $act = '')
{
$format				= $this->input->post('f-format');
$this->UNITKEY		= $this->sip->unitkey($this->input->post('f-unitkey'));
$this->KEGRKPDKEY	= $this->input->post('f-kegrkpdkey');
$this->TANGGAL		= $this->input->post('f-tanggal');
if($format == '1'):
$this->FORMAT = 31;
$this->EXT = 'pdf';
elseif($format == '2'):
$this->FORMAT = 36;
$this->EXT = 'xls';
endif;
switch(trim($nama)):
case 'matrik51_perubahan'							: $this->_matrik51_perubahan($act); break;
case 'matrik51_perubahan_opd'							: $this->_matrik51_perubahan_opd($act); break;
endswitch;
}
public function matrik51_perubahan_print($act = '')
{
$this->load->library('PDF_MC_Table');
ob_end_clean(); //    the buffer and never prints or returns anything.
ob_start();
$pdf = new PDF_MC_Table('L','mm','A4');
//$pdf->SetMargins(10,10,20);
$pdf->setData($this->NMTAHUN);
if($this->KDTAHAP =="4"){
  $pdf->setDataTahap("");
}else {
  $pdf->setDataTahap("Perubahan");
}
// membuat halaman baru
$pdf->AddPage();
// setting jenis font yang akan digunakan
$pdf->SetFont('Arial','B',10);
//set line height
$pdf->SetLineHeight(4);
$x =$pdf->GetX();
$y =$pdf->GetY();
$pdf->SetAligns(Array('','','','','','','','','','R','R','R',''));
$matrik51 = $this->m_set->matrik51_perubahan();
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
foreach ($matrik51 as $data){
$TYPE =	$data->TYPE;
IF ($TYPE =='H')
{
$detailpagu = NULL;
$NMUNIT = $data->SKPD;
$paguplus1= NULL;
$pdf->SetFont('Arial','B',6);
}
ELSE
{
$detailpagu = $data->PAGU;
$pdf->SetFont('Arial','',6);
$NMUNIT = NULL;
$paguplus1= $data->PAGUPLUS;
}
$pdf->Row(Array(
$data->KODE,
$data->NMPRGRM,
$data->NOPRIO,
$data->INDIKATOR,
$data->TCPAPAIPGR,
$data->KELUARAN,
$data->TARGETLUAR1,
$data->HASIL,
$data->TARGETHASIL,
number_format($data->PAGUDPA, 0, ',', '.'),
number_format($data->PAGU, 0, ',', '.'),
number_format(($data->PAGU - $data->PAGUDPA ), 0, ',', '.'),
$NMUNIT
));
$totalpagu +=  $detailpagu;
$paguplustot += $paguplus1;
}
$pdf->Output();
}
public function cetak_matrik_renja_opd_perubahan($act = '')
{
  $this->load->library('PDF_Matrik51_perOPD');
  ob_end_clean(); //    the buffer and never prints or returns anything.
  ob_start();
  $unitkey =  $act;
  $pdf = new PDF_Matrik51_perOPD('L','mm','A4');
  $pdf->SetMargins(3,10,3);
  $matrik51 = $this->m_set->matrik51_perubahan_peropd($unitkey);
  $aa= NULL;
  foreach ($matrik51 as $k){
    $aa = $k->SKPD;
  }
  $pdf->setData($aa);
  $pdf->setDataskpd($this->NMTAHUN);
  // membuat halaman baru
  $pdf->AddPage();
  //set line height
  $pdf->SetLineHeight(4);
  $x =$pdf->GetX();
  $y =$pdf->GetY();
  $pdf->SetAligns(Array('L','L','L','L','L','L','L','L','L','L','L','R','R','R','R','L'));
  $total = 0;
  $no=0;
  $pagusisa = 0;
  $totalpagu = 0;
  $totalpagudigunakan = 0;
  $totalpagusisa = 0;
  $totpagudpa = 0;
  $totpaguplus = 0;
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
  $paguplustotot = NULL;
  $n = 0;
  foreach ($matrik51 as $data){
    $jab = $data->JAB;
    $nip = $data->NIP;
    $nama = $data->NAMA_KEPALA_SKPD;
    $TYPE =	$data->TYPE;
    $priorg =NULL;
    IF ($TYPE =='H')
  {
    $detailpagu = NULL;
    $totpagudpa = NULL;
    $NMUNIT = $data->SKPD;
    $paguplus1= NULL;
    $pdf->SetFont('Arial','B',7);
    $priorg = $data->PRIORITAS;
  }
  ELSE IF ($TYPE =='SH')
  {
    $totpagudpa = NULL;
    $detailpagu = NULL;
    $NMUNIT = NULL;
    $pdf->SetFont('Arial','B',7);
    $paguplus1= NULL;
    $n = NULL;
  }
  ELSE
  {
    $totpagudpa = $data->PAGUDPA;
    $detailpagu = $data->PAGU;
    $pdf->SetFont('Arial','',7);
    $NMUNIT = NULL;
    $priorg = $data->RESGENDER;
    $paguplus1= $data->PAGUPLUS;
    $n = $totpagudpa -$detailpagu;
  }
  IF ($priorg == 1)
  {
    $priorg = "RG";
  }
    $pdf->Row(Array(
    $data->KODE,
    $data->NAMA,
    $priorg,
    $data->CP_TOLOKUR,
    $data->CP_TARGET,
    $data->KEL_TOLOKUR,
    $data->KEL_TARGET,
    $data->TARGETSE,
    $data->HSL_TOLOKUR,
    $data->HSL_TARGET,
    $data->CP_TARGETSEP,
    number_format($data->PAGUDPA, 0, ',', '.'),
    number_format($data->PAGU, 0, ',', '.'),
    $NILAI = number_format(($data->PAGU - $data->PAGUDPA ), 0, ',', '.'),
    number_format($data->PAGUPLUS, 0, ',', '.'),
    $NMUNIT
  ));
    $totalpagu +=  $totpagudpa;
    $paguplustot += $detailpagu;
    $totalpagusisa += $detailpagu - $totpagudpa;
    $totpaguplus += $paguplus1;
  }
$totalpagu = number_format($totalpagu, 0, ',', '.');
$paguplustot =  number_format($paguplustot, 0, ',', '.');
$totalpagusisa =  number_format($totalpagusisa, 0, ',', '.');
$paguplustotot = number_format($totpaguplus, 0, ',', '.');
$pdf->SetFont('Arial','B',8);
//$pdf->Cell(3,5,'',0,0,'C');
$pdf->Cell(188,5,'Total',1,0,'C');
$pdf->Cell(22,5,"$totalpagu",1,0,'R');
$pdf->Cell(22,5,"$paguplustot",1,0,'R');
$pdf->Cell(22,5,"$totalpagusisa",1,0,'R');
$pdf->Cell(22,5,"$paguplustotot",1,0,'R');
$pdf->Cell(15,5,"",1,0,'R');
$datakota = $this->m_set->getKota();
$pdf->Ln();

$pdf->Output();
}
public function matrik51_print($act = '')
{
$this->load->library('P_Matrik51');
ob_end_clean(); //    the buffer and never prints or returns anything.
ob_start();
$pdf = new P_Matrik51('L','mm','A4');
//$pdf->SetMargins(10,10,20);
$pdf->setData($this->NMTAHUN);
// membuat halaman baru
$pdf->AddPage();
// setting jenis font yang akan digunakan
$pdf->SetFont('Arial','B',10);
//set line height
$pdf->SetLineHeight(4);
$x =$pdf->GetX();
$y =$pdf->GetY();
$pdf->SetAligns(Array('','','','','','','','','','R','R','R',''));
$matrik51 = $this->m_set->matrik51all();
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
foreach ($matrik51 as $data){
$TYPE =	$data->TYPE;
IF ($TYPE =='H')
{
$detailpagu = NULL;
$NMUNIT = $data->SKPD;
$paguplus1= NULL;
$pdf->SetFont('Arial','B',7);
}
ELSE
{
$detailpagu = $data->PAGU;
$pdf->SetFont('Arial','',7);
$NMUNIT = NULL;
$paguplus1= $data->PAGUPLUS;
}
$pdf->Row(Array(
$data->KODE,
$data->NMPRGRM,
$data->NOPRIO,
$data->INDIKATOR,
$data->TCPAPAIPGR,
$data->KELUARAN,
$data->TARGETLUAR1,
$data->HASIL,
$data->TARGETHASIL,
number_format($data->PAGU, 0, ',', '.'),
number_format($data->PAGUPLUS, 0, ',', '.'),
$NMUNIT
));
$totalpagu +=  $detailpagu;
$paguplustot += $paguplus1;
}
$pdf->Output();
}
public function cetak_matrik_renja_opd_perubahan_skpd($act = '')
{
$this->load->library('PDF_Matrik51_perOPD_skpd');
ob_end_clean(); //    the buffer and never prints or returns anything.
ob_start();
$unitkey =  $act;
$pdf = new PDF_Matrik51_perOPD_skpd('L','mm','A4');
//$pdf->SetMargins(10,10,10);
$matrik51 = $this->m_set->matrik51_perubahan_peropd_SKPD($unitkey);
$aa= NULL;
foreach ($matrik51 as $k){
  $aa = $k->SKPD1;
}
$pdf->setData($aa);
$pdf->setDataskpd($this->NMTAHUN);
// membuat halaman baru
$pdf->AddPage();
//set line height
$pdf->SetLineHeight(4);
$x =$pdf->GetX();
$y =$pdf->GetY();
$pdf->SetAligns(Array('','','','','','R','R','R','C','L','R','',''));
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
foreach ($matrik51 as $data){
$jab = $data->JAB;
$nip = $data->NIP;
$nama = $data->NAMA;
$TYPE =	$data->TYPE;
IF ($TYPE =='H')
{
$detailpagu = NULL;
$NMUNIT = $data->SKPD;
$paguplus1= NULL;
$pdf->SetFont('Arial','B',7);
}
ELSE
{
$detailpagu = $data->PAGU;
$pdf->SetFont('Arial','',7);
$NMUNIT = NULL;
$paguplus1= $data->PAGUPLUS;
}
$pdf->Row(Array(
$data->KODE,
$data->NMPRGRM,
$data->KELUARAN,
$data->KELOMPOK_SASARAN,
$data->TARGETLUAR1,
number_format($data->PAGUDPA, 0, ',', '.'),
number_format($data->PAGU, 0, ',', '.'),
$NILAI = number_format(($data->PAGU - $data->PAGUDPA ), 0, ',', '.'),
$data->NMDANA,
$data->TARGETLUAR2,
number_format($data->PAGU2, 0, ',', '.'),
$data->NMSIFAT,
$NMUNIT
));
$totalpagu +=  $detailpagu;
$paguplustot += $paguplus1;
}
$datakota = $this->m_set->getKota();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln(10);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(200,5,"",0,'C');
$pdf->Cell(80,5,'Payakumbuh, '.tanggal_indo($this->tgl),0,0,'C');
$pdf->Ln(4);
$pdf->Cell(200,5,"",0,'C');
$pdf->MultiCell(80,5,"$jab",0,'C');
$pdf->SetFont('Arial','B',9);
$pdf->MultiCell(30,5,' ',0,'R');
$pdf->MultiCell(30,5,' ',0,'R');
$pdf->MultiCell(30,5,' ',0,'R');
$pdf->MultiCell(30,5,' ',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->Ln(0);
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(200,5,"",0,'C');
$pdf->Cell(80,5,"($nama)",0,0,'C');
$pdf->Ln(4);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(200,5,"",0,'C');
$pdf->Cell(80,5,"           NIP. $nip",0,0,'C');
$pdf->Output();
}
// public function c_rekapitulasi_pagu_indikatif($act = '')
// {
// $this->load->library('L_rekapitulasi_pagu_indikatif');
// ob_end_clean(); //    the buffer and never prints or returns anything.
// ob_start();
// $pdf = new L_rekapitulasi_pagu_indikatif('P','mm','A4');
// //$pdf->SetMargins(10,10,20);
// $pdf->setData($this->NMTAHUN);
// $nmr = $act;
// $pdf->setNumber($nmr-1);
// if($this->KDTAHAP =="4"){
  // $pdf->setDataTahap("");
// }else {
  // $pdf->setDataTahap("Perubahan");
// }
// // membuat halaman baru
// $pdf->AddPage();
// // setting jenis font yang akan digunakan
// $pdf->SetFont('Arial','',8);
// //set line height
// $pdf->SetLineHeight(7);
// $x =$pdf->GetX();
// $y =$pdf->GetY();
// $pdf->SetAligns(Array('C','L','R','R','R'));
// $paguskpdall = $this->m_set->paguSKPD();
// $total = 0;
// $no=0;
// $pl1 = 0;
// $pl2 = 0;
// $pl3 = 0;
// $p4 = 0;
// $pu4 =0;
// $s4 = 0 ;
// $p = 0 ;
// $NILAI = 0;
// $totalpagu = 0;
// $paguindikatif = 0;
// $paguselisih = 0;
// $psisa = 0;
// foreach ($paguskpdall as $data){
// $kdlevel = $data['KDLEVEL'];
// $unitkey =  $data['UNITKEY'];
// if ($kdlevel == 3 ) {
// $pl1 = $data['PAGU'];
// $pl2 = $data['PAGUUSED'];
// $pl3 = $data['SELISIH'];
// $pagusisa0= $pl3;
// if($pagusisa0 == 0)
// {
// $pagusisa = "-";
// $p= $pagusisa;
// }
// else
// {
// $pagusisa = $pagusisa0;
// $p = number_format($pagusisa, 0, ',', '.');
// $psisa = $p;
// }
// $no++;
// $pdf->Cell(3,4,'',0,0,'C');
// $pdf->SetFont('Arial','',8);
// $pdf->Row(Array(
// $no,
// $data['NMUNIT'],
// number_format($pl1, 0, ',', '.'),
// number_format($pl2, 0, ',', '.'),
// $p
// ));
// $p4 += $pl1;
// $pu4 += $pl2;
// $s4 += $psisa;
// }
// }
// $no1 = $no;
// $pg2 =0;
// $pg3  =0;
// $pg4  =0;
// $pgsisa2 = 0;
// $pagusisatotal = 0;
// $pagukec = $this->m_set->pagukec();
// foreach($pagukec as $pagukec){ $no1++;
// $kodeunit = $pagukec['KODEUNIT'];
// if ($kodeunit == "6.00.01.01." ) {
// $nama = "KECAMATAN PAYAKUMBUH BARAT";
// }
// elseif ($kodeunit == "6.00.01.03." ) {
// $nama = "KECAMATAN PAYAKUMBUH UTARA";
// }
// elseif ($kodeunit == "6.00.01.02." ) {
// $nama = "KECAMATAN PAYAKUMBUH TIMUR";
// }
// elseif ($kodeunit == "6.00.01.04." ) {
// $nama = "KECAMATAN PAYAKUMBUH SELATAN";
// }
// elseif ($kodeunit == "6.00.01.05." ) {
// $nama = "KECAMATAN LAMPOSI TIGO NAGORI";
// }
// $pagusisa0= $pagukec['PAGUKEC'] -  $pagukec['PAGUUSEKEC'] ;
// if($pagusisa0 == 0)
// {
// $pagusisa = "-";
// $pg= $pagusisa;
// }
// else
// {
// $pagusisa = $pagusisa0;
// $pg = number_format($pagusisa, 0, ',', '.');
// $pgsisa2 = $pg;
// }
// $pdf->Cell(3,4,'',0,0,'C');
// $pdf->Row(Array(
// $no1,
// $nama,
// number_format($pagukec['PAGUKEC'], 0, ',', '.'),
// number_format($pagukec['PAGUUSEKEC'], 0, ',', '.'),
// $pg
// ));
// $pg2 += $pagukec['PAGUKEC'];
// $pg3 += $pagukec['PAGUUSEKEC'];
// $pg4 += $pgsisa2;
// }
// $totalpagu = 	$p4 + $pg2;
// $totalpagudigunakan =	$pu4 + $pg3;
// $pagusisatotal = 	$s4 + $pg4 ;
// $totalpagu = number_format($totalpagu, 0, ',', '.');
// $totalpagudigunakan =  number_format($totalpagudigunakan, 0, ',', '.');
// $pagusisatotal = number_format($pagusisatotal, 0, ',', '.');
// $pdf->SetFont('Arial','B',8);
// $pdf->Cell(3,5,'',0,0,'C');
// $pdf->Cell(104,5,'Total',1,0,'C');
// $pdf->Cell(28,5,"$totalpagu",1,0,'R');
// $pdf->Cell(28,5,"$totalpagudigunakan",1,0,'R');
// $pdf->Cell(23,5,"$pagusisa",1,0,'R');
// $pdf->Output();
// }

public function c_rekapitulasi_pagu_indikatif($act = '')
{
$this->load->library('L_rekapitulasi_pagu_indikatif');
ob_end_clean(); //    the buffer and never prints or returns anything.
ob_start();
$pdf = new L_rekapitulasi_pagu_indikatif('P','mm','A4');
//$pdf->SetMargins(10,10,20);
$pdf->setData($this->NMTAHUN);
$nmr = $act;
$pdf->setNumber($nmr-1);
if($this->KDTAHAP =="4"){
  $pdf->setDataTahap("");
}else {
  $pdf->setDataTahap("Perubahan");
}
// membuat halaman baru
$pdf->AddPage();
// setting jenis font yang akan digunakan
$pdf->SetFont('Arial','',8);
//set line height
$pdf->SetLineHeight(7);
$x =$pdf->GetX();
$y =$pdf->GetY();
$pdf->SetAligns(Array('C','L','R','R','R'));
$paguskpdall = $this->m_set->getPaguOPDGabung2021();
$no=0;
$totalpagu = 0;
$totalpagudigunakan = 0;
$paguselisih = 0;
foreach ($paguskpdall as $data){
$no++;
$pdf->Cell(3,4,'',0,0,'C');
$pdf->SetFont('Arial','',8);
$pdf->Row(Array(
$no,
$data['NMUNIT'],
number_format($data['NILAI'], 0, ',', '.'),
number_format($data['PAGUUSED'], 0, ',', '.'),
number_format($data['SELISIH'], 0, ',', '.')
));
$totalpagu +=$data['NILAI'];
$totalpagudigunakan += $data['PAGUUSED'];
$paguselisih += $data['SELISIH'];
}
$totalpagu = number_format($totalpagu, 0, ',', '.');
$totalpagudigunakan =  number_format($totalpagudigunakan, 0, ',', '.');
$paguselisih = number_format($paguselisih, 0, ',', '.');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(3,5,'',0,0,'C');
$pdf->Cell(99,5,'Total',1,0,'C');
$pdf->Cell(28,5,"$totalpagu",1,0,'R');
$pdf->Cell(28,5,"$totalpagudigunakan",1,0,'R');
$pdf->Cell(28,5,"$paguselisih",1,0,'R');
$pdf->Output();
}






public function c_rekapitulasi_pagu_indikatif_nonADK($act = '')
{
$this->load->library('L_rekapitulasi_pagu_indikatif');
ob_end_clean(); //    the buffer and never prints or returns anything.
ob_start();
$pdf = new L_rekapitulasi_pagu_indikatif('P','mm','A4');
$pdf->setData($this->NMTAHUN);
if($this->KDTAHAP =="4"){
  $pdf->setDataTahap("");
}else {
  $pdf->setDataTahap("Perubahan");
}
$pdf->AddPage();
// setting jenis font yang akan digunakan
$pdf->SetFont('Arial','',8);
//set line height
$pdf->SetLineHeight(7);
$x =$pdf->GetX();
$y =$pdf->GetY();
$pdf->SetAligns(Array('C','L','R','R','R'));
$paguskpdall = $this->m_set->m_pagu_indikatif_nonADK();
$total = 0;
$no=0;
$pl1 = 0;
$pl2 = 0;
$pl3 = 0;
$p4 = 0;
$pu4 =0;
$s4 = 0 ;
$NILAI = 0;
$totalpagu = 0;
$paguindikatif = 0;
$paguselisih = 0;
$totalpagudigunakan = 0;
foreach ($paguskpdall as $data){
$kdlevel = $data['KDLEVEL'];
$unitkey =  $data['UNITKEY'];
$pl1 = $data['PAGU'];
$pl2 = $data['PAGUUSED'];
$pl3 = $data['SELISIH'];
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
$no++;
$pdf->Cell(3,4,'',0,0,'C');
$pdf->SetFont('Arial','',8);
$pdf->Row(Array(
$no,
$data['NMUNIT'],
number_format($pl1, 0, ',', '.'),
number_format($pl2, 0, ',', '.'),
$p
));
$totalpagu +=  $pl1;
$paguindikatif += $pl2;
$paguselisih += $totalpagu - $paguindikatif;
}
$totalpagu = number_format($totalpagu, 0, ',', '.');
$totalpagudigunakan =  number_format($paguindikatif, 0, ',', '.');
$pagusisa = number_format($paguselisih, 0, ',', '.');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(3,4,'',0,0,'C');
$pdf->Cell(104,5,'Total',1,0,'C');
$pdf->Cell(28,5,"$totalpagu",1,0,'R');
$pdf->Cell(28,5,"$totalpagudigunakan",1,0,'R');
$pdf->Cell(23,5,"$pagusisa",1,0,'R');
$pdf->Output();
}


//15 nov 2019
 public function c_rekap_urusan($nomor = '')
 {
 $this->load->library('L_rekap_urusan');
 ob_end_clean(); //    the buffer and never prints or returns anything.
 ob_start();
 $pdf = new L_rekap_urusan('P','mm','A4');
 $title='Matrik Per Urusan';
 $pdf->setData($this->NMTAHUN);
 $nmr = $nomor;
 $pdf->setNumber($nmr-1);
 $pdf->SetTitle($title);
 $pdf->isFinished = false;
 $pdf->AliasNbPages();
 $pdf->AddPage();
 // // Add your page contents here
 $pdf->isFinished = true;
 $pdf->SetFont('Times', 'B', 12);
 $pdf->SetAutoPageBreak(true, 29);
 $getUrusanAll = $this->m_set->getUrusanAll();
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
 $pdf->tablewidths = array(31, 65, 67, 26);
 $pdf->SetAligns(array('L','L','L','R'));
 foreach ($getUrusanAll as $row){
 $kdlevel = $row['KDLEVEL'];
   $TYPE =	$row['TYPE'];
   IF ($TYPE =='H')
   {
     $NM = $row['NMPRGRM'];
     $PAGU2 = $row['PAGU'];
     $NAMA =NULL;
     $PAGU1= NULL;
     $bold = 'H';
   }
   ELSE
   {
     $NAMA = $row['NMPRGRM'];
     $PAGU1 = $row['PAGU'];
     $NM = NULL;
     $PAGU2= NULL;
     $bold = 'D';
 }
 $pdf->Cell(3,4,'',0,0,'C');
 $pdf->SetFont('Arial','',8);
$data[] =array('jenis'=>$bold, 'array'=>array(
  $row['KODE'],
  $NM,
  $NAMA,
  number_format($row['PAGU'], 0, ',', '.')
));

 $total += $PAGU1;

 }
 $totalpagu = number_format($total, 0, ',', '.');

 $pdf->setTotal($totalpagu);
 $pdf->morepagestable($data);
 $pdf->isFinished = false;
  $pdf->Output();
 }

public function c_skpd_opd($act = '')
{
$this->load->library('L_skpd_opd');
ob_end_clean(); //    the buffer and never prints or returns anything.
ob_start();
$pdf = new L_skpd_opd('P','mm','A4');
//$pdf->SetMargins(10,10,20);
$pdf->setData($this->NMTAHUN);
$nmr = $act;
$pdf->setNumber($nmr-1);
if($this->KDTAHAP =="4"){
  $pdf->setDataTahap("");
}else {
  $pdf->setDataTahap("Perubahan");
}
// membuat halaman baru
$pdf->AddPage();
// setting jenis font yang akan digunakan
$pdf->SetFont('Arial','',8);
//set line height
$pdf->SetLineHeight(7);
$x =$pdf->GetX();
$y =$pdf->GetY();
$pdf->SetAligns(Array('C','L','R'));
$skpdpaguall = $this->m_set->rekapitulasi_pagu_opd();
$total = 0;
$no = 0;
$totalpagu = 0;
foreach ($skpdpaguall as $data){
$no++;

$pdf->Cell(3,4,'',0,0,'C');
$pdf->SetFont('Arial','',8);
$pdf->Row(Array(
$no,
strtoupper($data['NMUNIT']),
number_format($data['NILAI'], 0, ',', '.')

));
	$total += $data['NILAI'];
}

$totalpagu = number_format($total, 0, ',', '.');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(3,7,'',0,0,'C');
$pdf->Cell(155,7,'Total',1,0,'C');
$pdf->Cell(30,7,"$totalpagu",1,0,'R');

$pdf->Output();
}

public function c_rekap_urusan_perubahan($nomor = '')
{
  $this->load->library('L_rekap_urusan_perubahan');
  ob_end_clean(); 
  ob_start();
  $pdf = new L_rekap_urusan_perubahan('L','mm','A4');
  $title='Matrik Per Urusan';
  $pdf->SetMargins(17,10,40);
  $pdf->setData($this->NMTAHUN);
  $nmr = $nomor;
  $pdf->setNumber($nmr-1);
  $pdf->SetTitle($title);
  $pdf->isFinished = false;
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->isFinished = true;
  $pdf->SetFont('Times', 'B', 12);
  $pdf->SetAutoPageBreak(true, 29);
  $getUrusanAllPerubahan = $this->m_set->getUrusanAllPerubahan();
  $total = 0;
  $total1 = 0;
  $total2 = 0;
  $no = 0;
  $pagusisa = 0;
  $totalpagu = 0;
  $totalpagudpa = 0;
  $NM = NULL;
  $NAMA = NULL;
  $TYPE = NULL;
  $PAGU1=NULL;
  $PAGU2=NULL;
  $PAGUDPA1=NULL;
  $PAGUDPA2=NULL;
  $total_pagu_dpa = 0;
  $pagudpa = 0;
  $selisih2= 0;
  $total_pagu_selisih = 0;
  $totalpaguselisih = 0;
  $pdf->tablewidths = array(35, 85, 65, 26, 26, 26);
  $pdf->SetAligns(array('L','L','L','R','R','R'));
  foreach ($getUrusanAllPerubahan as $row){
      $kdlevel = $row->KDLEVEL;
      $TYPE =	$row->TYPE;
      IF ($TYPE =='H')
      {
        $NM = $row->NMPRGRM;
        $PAGU2 = $row->PAGU;
        $PAGUDPA2 = $row->PAGUDPA;
        $pagudpa = 0;
        $NAMA =NULL;
        $PAGU1= NULL;
        $PAGUDPA1 = NULL;
        $bold = 'H';
      }
      ELSE
      {
        $NAMA = $row->NMPRGRM;
        $PAGU1 = $row->PAGU;
        $PAGUDPA1 = $row->PAGUDPA;
        $NM = NULL;
        $PAGU2= NULL;
        $PAGUDPA2 = NULL;
        $pagudpa = 0;
        $bold = 'D';
      }
      $pdf->Cell(3,4,'',0,0,'C');
      $pdf->SetFont('Arial','',8);
      $data[] = array('jenis'=>$bold, 'array'=>array(
      $row->KODE,
      $NM,
      $NAMA,
      number_format($row->PAGUDPA, 0, ',', '.'),
      number_format($row->PAGU, 0, ',', '.'),
      number_format($row->PAGU - $row->PAGUDPA, 0, ',', '.'),
      ));
      $total += $PAGU1;
      $total_pagu_dpa += $PAGUDPA1 ;
  }
  $selisih2 = $total - $total_pagu_dpa;
  $totalpagu = number_format($total, 0, ',', '.');
  $totalpagudpa = number_format($total_pagu_dpa, 0, ',', '.');
  $totalpaguselisih = number_format($selisih2, 0, ',', '.');
  $pdf->setTotal($totalpagu);
  $pdf->setTotaldpa($totalpagudpa);
  $pdf->setTotalSelisih($totalpaguselisih);
  $pdf->morepagestable($data);
  $pdf->isFinished = false;
  $pdf->Output();
}

public function c_rekapitulasi_matrik53($act = '')
{
$this->load->library('L_rekap_matrik53');
ob_end_clean(); 
ob_start();

$pdf = new L_rekap_matrik53('P','mm','A4');
$pdf->SetMargins(10,10,12);
$pdf->setData($this->NMTAHUN);
$nmr = $act;
$pdf->setNumber($nmr-1);

if($this->KDTAHAP =="4"){
  $pdf->setDataTahap("");
}else {
  $pdf->setDataTahap("Perubahan");
}

$pdf->AddPage();
$pdf->SetFont('Arial','',8);
$pdf->SetLineHeight(6);
$x =$pdf->GetX();
$y =$pdf->GetY();
$pdf->SetAligns(Array('C','L','R','R','R'));
$paguskpdall = $this->m_set->paguSKPDPerubahan();

$no=0;
$tpagudpa = 0;
$tpagu = 0;
$tspagu = 0;
$pagu = 0;
$pagudpa = 0;
$spagu = 0;

foreach ($paguskpdall as $data){
    $kodeunit =  $data['KODEUNIT'];
    $nama = $data['NMUNIT'];
    if ($data['KODEUNIT'] == "7.01.0.00.0.00.01") {
      $nama = "KECAMATAN PAYAKUMBUH BARAT";
    }
    elseif ($kodeunit == "7.01.0.00.0.00.02" ) {
      $nama = "KECAMATAN PAYAKUMBUH TIMUR";
    }
    elseif ($kodeunit == "7.01.0.00.0.00.03" ) {
      $nama = "KECAMATAN PAYAKUMBUH UTARA";
    }
    elseif ($kodeunit == "7.01.0.00.0.00.04" ) {
      $nama = "KECAMATAN PAYAKUMBUH SELATAN";
    }
    elseif ($kodeunit == "7.01.0.00.0.00.05" ) {
      $nama = "KECAMATAN LAMPOSI TIGO NAGORI";
    }
    elseif ($kodeunit == "1.02.0.00.0.00.01" ) {
      $nama = "DINAS KESEHATAN";
    }
    elseif ($kodeunit == "1.03.2.10.0.00.01" ) {
      $nama = "DINAS PEKERJAAN UMUM DAN PENATAAN RUANG";
    }
	elseif ($kodeunit == "2.07.3.31.0.00.02" ) {
      $nama = "DINAS TENAGA KERJA DAN PERINDUSTRIAN";
    }
    elseif ($kodeunit == "2.11.0.00.0.00.01" ) {
      $nama = "DINAS LINGKUNGAN HIDUP";
    }
    elseif ($kodeunit == "2.15.0.00.0.00.01" ) {
      $nama = "DINAS PERHUBUNGAN";
    }
    elseif ($kodeunit == "3.27.0.00.0.00.01" ) {
      $nama = "DINAS PERTANIAN";
    }
    elseif ($kodeunit == "5.02.0.00.0.00.10" ) {
      $nama = "BADAN KEUANGAN DAERAH";
    }
	elseif ($kodeunit == "3.26.2.19.2.22.02" ) {
      $nama = "DINAS PARIWISATA PEMUDA DAN OLAHRAGA";
    }
	elseif ($kodeunit == "2.17.3.30.0.00.01" ) {
      $nama = "DINAS KOPERASI USAHA KECIL DAN MENENGAH";
    }

    $pagudpa = $data['PAGUDPA'];
    $pagu = $data['PAGUKEC'];
    $no++;
    $pdf->Cell(3,4,'',0,0,'C');
    $pdf->SetFont('Arial','',8);
    $pdf->Row(Array(
      $no,
      $nama,
      number_format($data['PAGUDPA'], 0, ',', '.'),
      number_format($data['PAGUKEC'], 0, ',', '.'),
      number_format($data['PAGUKEC'] - $data['PAGUDPA'], 0, ',', '.'),
    ));

      //$spagu = $data['PAGUKEC'] - $data['PAGUDPA'];
      $tpagudpa += $pagudpa;
      $tpagu += $pagu;
      $spagu = $tpagu - $tpagudpa;
    }

    $tpagudpa = number_format($tpagudpa, 0, ',', '.');
    $tpagu = number_format($tpagu, 0, ',', '.');
    $spagu = number_format($spagu, 0, ',', '.');
    $pdf->setT($tpagudpa);
    $pdf->setTdpa($tpagu);
    $pdf->setTSelisih($spagu);
    $pdf->SetFont('Arial','B',8);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(3,7,'',0,0,'C');
    $pdf->Cell(100,7,'Total',1,0,'C');
    $pdf->Cell(28,7,"$tpagudpa",1,0,'R');
    $pdf->Cell(28,7,"$tpagu",1,0,'R');
    $pdf->Cell(28,7,"$spagu",1,0,'R');
    $pdf->Output();
  }
}

