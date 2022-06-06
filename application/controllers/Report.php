<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	private $json = ['cod' => NULL, 'msg' => NULL, 'link' => NULL];

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;
	private $UNITKEY = NULL;
	private $KEGRKPDKEY = NULL;
	private $TANGGAL = NULL;

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
		$this->load->library('PDF_MC_Table');
		$this->sip->is_logged();

		$this->load->model(['m_set', 'm_user', 'm_rka', 'm_program', 'm_kegiatan']);


		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
		$this->NMTAHUN = $this->session->NMTAHUN;
		$this->TANGGAL = date('d-m-Y');

		$this->HOSTNAME = $this->db->hostname;
		$this->DATABASE = $this->db->database;
		$this->USERNAME = $this->db->username;
		$this->PASSWORD = $this->db->password;
	}

	public function index($file = '')
	{

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
			case 'rkpd_matrik'										: $this->_rkpd_matrik($act); break;
			case 'rkpd_rekap_opd'									: $this->_rkpd_rekap_opd($act); break;
			case 'rkpd_rekap_urusan'								: $this->_rkpd_rekap_urusan($act); break;
			case 'rkpd_pagu_opd'									: $this->_rkpd_pagu_opd($act); break;
			case 'matrik51'											: $this->_matrik51($act); break;
			case 'rka_prarka'										: $this->_rka_prarka($act); break;
			case 'rkpd_matrik_opd'									: $this->_rkpd_matrik_opd($act); break;
			case 'matrik43'											: $this->_matrik43($act); break;
			case 'pagu_opdnonkelurahan'								: $this->_pagu_opdnonkelurahan($act); break;
			case 'ssh'												: $this->_ssh($act); break;
			case 'matrik51_perubahan'								: $this->_matrik51_perubahan($act); break;
			//201903050925
			case 'program_kegiatan'									: $this->_program_kegiatan_($act); break;
			case 'matrik51_perubahan_opd'							: $this->_matrik51_perubahan_opd($act); break;
			case 'matrik51_perubahan_skpd'							: $this->_matrik51_perubahan_skpd($act); break;
			case 'matrik52_perubahan'								: $this->_matrik52_perubahan($act); break;
			case 'matrik53_perubahan'								: $this->_matrik53_perubahan($act); break;
			case 'matrik51_opd_uptd_blud'							: $this->_matrik51_opd_uptd_blud($act); break;
			case 'matrik51_opd_uptd_blud_perubahan'					: $this->_matrik51_opd_uptd_blud_perubahan($act); break;
			//=======================

			case 'matrik_renja_perangkat_daerah'					: $this->_matrik_renja_perangkat_daerah($act); break;
			case 'per_urusan'										: $this->_per_urusan($act); break;
			case 'pagu_perangkat_daerah'							: $this->_pagu_perangkat_daerah($act); break;
			case 'matrik_renja_per_urusan'							: $this->_matrik_renja_per_urusan($act); break;
			case 'cetak_lap41'										: $this->_cetak_lap41_($act); break;

		endswitch;
	}
	
	public function _pagu_opdnonkelurahan($act = '')
	{
		$this->sip->is_menu('090109');
		if($act != 'open'):
		$this->load->view('report/v_rkpd_rekap_urusan');
		endif;
	}
	public function _matrik51_perubahan($act = '')
	{
		$this->sip->is_menu('090111');
		if($act != 'open'):
		$this->load->view('report/v_matrik51_perubahan');
		endif;
	}
		
	public function _matrik51_perubahan_opd($act = '')
	{
		$this->sip->is_menu('090112');
		if($act != 'open'):
		$this->load->view('report/v_matrik51_perubahan_perskpd');
		endif;
	}
		
	public function _matrik51_perubahan_skpd($act = '')
	{
		$this->sip->is_menu('090119');
		if($act != 'open'):
		$this->load->view('report/v_matrik51_perubahan_skpd');
		endif;
	}

	public function _matrik52_perubahan($act = '')
	{
		$this->sip->is_menu('090115');
		if($act != 'open'):
		$this->load->view('report/v_matrik52_perubahan');
		endif;
	}

	public function _matrik53_perubahan($act = '')
	{
		$this->sip->is_menu('090116');
		if($act != 'open'):
		$this->load->view('report/v_matrik53_perubahan');
		endif;
	}
	
	public function _matrik51_opd_uptd_blud($act = '')
	{
		$this->sip->is_menu('090117');
		if($act != 'open'):
		$this->load->view('report/v_rkpd_rekap_opd');
		endif;
	}
	
	public function _matrik51_opd_uptd_blud_perubahan($act = '')
	{
		$this->sip->is_menu('090113');
		if($act != 'open'):
		$this->load->view('report/v_matrik51_opd_uptd_blud_perubahan');
		endif;
	}


public function matrik51_perubahan_print($act = '')
{
ob_end_clean(); //    the buffer and never prints or returns anything.
ob_start();
$pdf = new PDF_MC_Table('L','mm','A4');
// membuat halaman baru
$pdf->AddPage();
// setting jenis font yang akan digunakan
$pdf->SetFont('Arial','B',10);
$A = 33;
$B = 40;
$C = 8;
$D = 20;
$E = 10;
$F = 25;
$G = 25;
$H = 20;
$I = 10;
$J = 25;
$K = 25;
$L = 25;
$M = 15;
//set width for each column (12 column)
$pdf->SetWidths(Array($A,$B,$C,$D,$E,$F,$G,$H,$I,$J,$K,$L,$M));
//set line height
$pdf->SetLineHeight(4);
$x =$pdf->GetX();
$y =$pdf->GetY();
$pdf->SetAligns(Array('','','','','','','','','','R','R','R',''));
// mencetak string
$pdf->Cell(285,4,'Tabel V.I ',0,1,'C');
$pdf->Cell(285,4,"RENCANA PROGRAM DAN KEGIATAN PRIORITAS DAERAH PADA PERUBAHAN RKPD TAHUN {$this->NMTAHUN}",0,1,'C');
$pdf->Cell(285,4,'PEMERINTAHAN KOTA PAYAKUMBUH',0,1,'C');
$pdf->Cell(10,2,'',0,1);
$pdf->SetFont('Arial','B',9);
//$pdf->Cell(285,7,'',0,1,'C');
// Memberikan space kebawah agar tidak terlalu rapat
//$pdf->Cell(10,7,'',0,1);
$pdf->SetFont('Arial','B',7);
//line 1
$pdf->Cell($A,16,'No',1,0,'C');
$pdf->Cell($B,3.5,'','T,R',0);
$pdf->Cell($C,6,'','T',0);
$pdf->Cell(110,5,'Indikasi Kinerja Program/Kegiatan',1,0,'C');
$pdf->Cell(75,5,"Pagu Indikatif Tahun {$this->NMTAHUN}",1,0,'C');
//$pdf->Cell(25,4,'','T,R',0);
//	$pdf->Cell(25,6,'','T,R',0);
$pdf->Cell(15,6,'','T,R',0);
$pdf->Cell(0,4,'',0,1);
//line 2
$pdf->Cell($A,4,'',0,0);
$pdf->MultiCell($B,4,'Urusan Pemerintah Daerah/ Program / Kegiatan',0,'C');
$pdf->SetXY($x +73, 40);
$pdf->MultiCell($C,5,'Prioritas','L,R','C');
$pdf->SetXY($x +81, 42);
$pdf->MultiCell(30,3,'Capaian Program (Indikator sasaran)','L,R','C');
$pdf->Ln(0);
$pdf->SetXY($x +111, 42);
$pdf->MultiCell(50,6,'Keluaran (Output)','L,R','C');
$pdf->Ln(0);
$pdf->SetXY($x +161, 42);
$pdf->MultiCell(30,6,'Hasil(Outcome)','L,R','C');
$pdf->Ln(0);
$pdf->SetXY($x +191, 42);
$pdf->MultiCell(25,4.5,'Sebelum Perubahan','L,R','C');
$pdf->Ln(0);
$pdf->SetXY($x +216, 44);
$pdf->MultiCell(25,8,'Setelah Perubahan','L,R','C');
$pdf->Ln(0);
$pdf->SetXY($x +241, 42);
$pdf->MultiCell(25,4,'Jumlah Perubahan (+/-)','L,R','C');
$pdf->Ln(0);
$pdf->SetXY($x +266, 40);
$pdf->MultiCell(15,4,'Penanggung Jawab','L,R','C');
$pdf->Ln(0);
$pdf->Cell(0,5,'',0,1);
//line 3
$pdf->Cell(33,4,'',0,0);
$pdf->Ln(0);
$pdf->SetXY($x +33, 48);
$pdf->Cell(40,5,'','B',0);
$pdf->Ln(0);
$pdf->SetXY($x +73, 48);
$pdf->Cell(8,5,'','B,L',0);
$pdf->SetXY($x +81, 48);
$pdf->Cell(20,5,'Tolok Ukur',1,'T,L,B','C');
$pdf->Ln(0);
$pdf->SetXY($x +101, 48);
$pdf->Cell(10,5,'Target',1,'T,L,B','C');
$pdf->Ln(0);
$pdf->SetXY($x +111, 48);
$pdf->Cell(25,5,'Tolok Ukur',1,'T,L,B','C');
$pdf->Ln(0);
$pdf->SetXY($x +136, 48);
$pdf->Cell(25,5,'Target',1,'T,L,B','C');
$pdf->Ln(0);
$pdf->SetXY($x +161, 48);
$pdf->Cell(20,5,'Tolok Ukur',1,'T,L,B','C');
$pdf->Ln(0);
$pdf->SetXY($x +181, 48);
$pdf->Cell(10,5,'Target',1,'T,L,B','C');
$pdf->Ln(0);
$pdf->SetXY($x +191, 48);
$pdf->Cell(25,5,'','B,R',0);
$pdf->Ln(0);
$pdf->SetXY($x +216, 48);
$pdf->Cell(25,5,'','B,R',0);
$pdf->Ln(0);
$pdf->SetXY($x +241, 48);
$pdf->Cell(25,5,'','B,R',0);
$pdf->Ln(0);
$pdf->SetXY($x +266, 48);
$pdf->Cell(15,5,'','B,R',0);
$pdf->Ln();
$pdf->Cell(33,5,'1','1',0,'C');
$pdf->Cell(40,5,'2','1',0,'C');
$pdf->Cell(8,5,'3','1',0,'C');
$pdf->Cell(20,5,'4','1',0,'C');
$pdf->Cell(10,5,'5','1',0,'C');
$pdf->Cell(25,5,'6','1',0,'C');
$pdf->Cell(25,5,'7','1',0,'C');
$pdf->Cell(20,5,'8','1',0,'C');
$pdf->Cell(10,5,'9','1',0,'C');
$pdf->Cell(25,5,'10','1',0,'C');
$pdf->Cell(25,5,'11','1',0,'C');
$pdf->Cell(25,5,'12 = (11-10)','1',0,'C');
$pdf->Cell(15,5,'13','1',0,'C');
$pdf->Ln();
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
number_format($data->PAGUDPA, 0, ',', '.'),
number_format($data->PAGU, 0, ',', '.'),
number_format($NILAI = ($data->PAGU - $data->PAGUDPA ), 0, ',', '.'),
$NMUNIT
));
$totalpagu +=  $detailpagu;
$paguplustot += $paguplus1;
}
$pdf->Output();
}

	
	
		public function print_pagu_opd_($act = ''){


					$data ['paguskpdall'] = $this->m_set->paguSKPD();

					$data ['pagukec'] = $this->m_set->pagukec();


			if($act == 'print')
			{
				$this->load->view('report/v_pagu_opd_print_new', $data);
			}
			else
			{
				$this->load->view('report/v_pagu_opd_print_new', $data);
			}


		}

	
		public function _ssh($act = '')
	{
		$this->sip->is_menu('090110');
		if($act != 'open'):
		$this->load->view('report/v_laporan_ssh');
		endif;
	}

	public function cetak_ssh($act = '',  $id =''){

		$kdrek =  $id;

		if ($kdrek == ""){
			$filter = "";
		}
		else{
			$filter = "AND KDREK = '{$kdrek}'";
		}
		$data['cetakSSH'] = $this->m_set->CetakSSH($filter);

		if($act == 'print')
		{
			$this->load->view('report/v_cetak_SSH_print', $data);
		}
		else
		{
			$this->load->view('report/v_cetak_SSH_print', $data);
		}
	}


	public function cetak_SSH_word($act = '',  $id ='')
	{
		$kdrek =  $id;

		if ($kdrek == ""){
			$filter = "";
		}
		else{
			$filter = "AND KDREK = '{$kdrek}'";
		}
		$data['cetakSSH'] = $this->m_set->CetakSSH($filter);

				if($act == 'print')
		{
			$this->load->view('report/v_cetak_SSH_word', $data);
		}
		else
		{
			$this->load->view('report/v_cetak_SSH_word', $data);
		}

	}

	public function perubahan($nama = '', $act = '')
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
			case 'rkpd_pagu_opd'	: $this->_rkpd_pagu_opd_perubahan($act); break;

			case 'rka_prarka'		: $this->_rka_prarka_perubahan($act); break;
		endswitch;
	}

	public function _rkpd_matrik($act = '')
	{
		$this->sip->is_menu('090101');
		if($act != 'open'):
		$this->load->view('report/v_pagu_opdnonkelurahan');
		endif;
	}

	public function _rkpd_rekap_opd($act = '')
	{
		$this->sip->is_menu('090102');
		if($act != 'open'):
		$this->load->view('report/v_matrik51');
		endif;
	}



	public function cetak_pagu_opd($act = '')
	{

		$this->load->model('m_set');
		$data['paguskpdall'] = $this->m_set->paguSKPD();

		if($act == 'print')
		{
			$this->load->view('report/v_pagu_opd_print', $data);
		}
		else
		{
			$this->load->view('report/v_pagu_opd_print', $data);
		}

	}


	public function cetak_rekap_urusan($act = '')
	{

		$data['getUrusanAll'] = $this->m_set->getUrusanAll();
		//$data ['nmakota'] = $this->m_set->getKota();
		$data ['pagukec'] = $this->m_set->pagukec();

		if($act == 'print')
		{
			$this->load->view('report/v_rekap_urusan_print', $data);
		}
		else
		{
			$this->load->view('report/v_rekap_urusan_print', $data);
		}

	}


	public function cetak_matrik_renja($act = '',$id ='', $nama='')
	{
		$unit =  $id;
		$data['getRenjaAll'] = 	 $this->m_set->getRenjaAll($unit);
		$data ['nmakota'] = $this->m_set->getKota();
		if($data){
				if($act == 'print')
		{
			$this->load->view('report/v_rekap_renja_print', $data);
		}
		else
		{
			$this->load->view('report/v_rekap_renja_print', $data);
		}
		}
	}
	
	public function _cetak_lap41_($act = '')
	{
		$this->sip->is_menu('090118');
		if($act != 'open'):
		$this->load->view('report/v_lap41');
		endif;
	}
	
	public function cetak_lap41($act = '')
	{
		$data['matrik51all'] = $this->m_set->matrik51all();
	//	print_r($data['matrik51all']);
		if($act == 'print')
		{
			$this->load->view('report/v_lap41_print', $data);
		}
		else
		{
			$this->load->view('report/v_lap41_print', $data);
		}

	}
	
	//20180806
	public function cetak_matrik_renja_word($act = '',$id ='', $nama='')
	{
		$unit =  $id;
		$data['getRenjaAll'] = 	 $this->m_set->getRenjaAll($unit);
		$data ['nmakota'] = $this->m_set->getKota();
		if($data){
				if($act == 'print')
		{
			$this->load->view('report/v_cetak_matrik_renja_word', $data);
		}
		else
		{
			$this->load->view('report/v_cetak_matrik_renja_word', $data);
		}
		}
	}
	
	
	public function cetak_skpd_opd_word($act = '')
	{
		$this->load->model('m_set');
		$data['skpdpaguall'] = $this->m_set->getAllPagu();
		$data ['nmakota'] = $this->m_set->getKota();

		if($act == 'print')
		{
			$this->load->view('report/v_cetak_skpd_opd_word', $data);
		}
		else
		{
			$this->load->view('report/v_cetak_skpd_opd_word', $data);
		}

	}
	
	public function cetak_pagu_opd_word($act = '')
	{

		$this->load->model('m_set');
		$data['paguskpdall'] = $this->m_set->paguSKPD();

		if($act == 'print')
		{
			$this->load->view('report/v_pagu_opd_print_word', $data);
		}
		else
		{
			$this->load->view('report/v_pagu_opd_print_word', $data);
		}

	}
	
	public function cetak_rekap_urusan_word($act = '')
	{

		$data['getUrusanAll'] = $this->m_set->getUrusanAll();
		$data ['nmakota'] = $this->m_set->getKota();

		if($act == 'print')
		{
			$this->load->view('report/v_rekap_urusan_print_word', $data);
		}
		else
		{
			$this->load->view('report/v_rekap_urusan_print_word', $data);
		}

	}
	
	public function matrik51_print_word($act = '')
	{
		$data['matrik51all'] = $this->m_set->matrik51all();
	//	print_r($data['matrik51all']);
		if($act == 'print')
		{
			$this->load->view('report/v_matrik51_print_word', $data);
		}
		else
		{
			$this->load->view('report/v_matrik51_print_word', $data);
		}

	}
	
	public function cetak_matrik_renja_opd_word($act = '',$id ='', $nama='')
{
	$unit =  $id;
	$data['getRenjaAll'] = 	 $this->m_set->getRenjaAll($unit);
	$data ['nmakota'] = $this->m_set->getKota();
	if($data){
			if($act == 'print')
	{
		$this->load->view('report/v_rekap_renja_opd_print_word', $data);
	}
	else
	{
		$this->load->view('report/v_rekap_renja_opd_print_word', $data);
	}
	}
}
	
	
	
	
		public function _matrik51($act = '')
	{
		$this->sip->is_menu('090106');
		if($act != 'open'):
		$this->load->view('report/v_rkpd_matrik');
		endif;
	}
	
	//public function matrik51_print($act = '')
	//{
		//$data['matrik51all'] = $this->m_set->matrik51all();
		
	//	if($act == 'print')
	//	{
	//		$this->load->view('report/v_matrik51_print', $data);
	//	}
	//	else
	//	{
	//		$this->load->view('report/v_matrik51_print', $data);
	//	}

	//}


		public function cetak_prarka($act = '',$id='', $kegrkpdkey='')
			{
				$newtarget= '';
				$praraskr='';
				$newpraraskr='';
				$target='';
				$id = $id;
				$kegrkpdkey = $kegrkpdkey;
				$check =$this->m_set->adjustmentkinkegrka($id,$kegrkpdkey);
				foreach ($check as $check){

							$target 	=  substr($check->TARGET,0,-3);
							//$target 	= $check->TARGET;
							$praraskr =  substr($check->PRARASKR,0,-3);
							$keg = substr($check->KEG,0,-3);
						

						}
				if (count($check) > 0){

							if(substr($target,0,4) == "Rp. ") {
								$newtarget = substr($target,4);
							}else {
								$newtarget = $target;
							}
							$newtarget	= preg_replace('/[^A-Za-z0-9]/', '', $newtarget);
							$newpraraskr	= preg_replace('/[^A-Za-z0-9]/', '', $praraskr);
							$newKEG	= preg_replace('/[^A-Za-z0-9]/', '', $keg);

						
					if ( $newtarget == $newpraraskr  &&  $newtarget == $newKEG ) {

								$sql = $this->m_set->cKinkeg($id,$kegrkpdkey);
									foreach ($sql as $sql){
											$total = $sql->TKDJKK;
								}
								if($total < 6)
								{
									?>
								 <script type="text/javascript">
								 var question = confirm("Cetak RKA gagal Karena Belum Melakukan Pengentrian Capaian Program, Masukan, Keluaran, Hasil, Kelompok Sasaran Kegiatan dan Latar Belakang Kegiatan Pada Kinerja Kegiatan. Silahkan Lengkapi Kinerja Kegiatan agar dapat mencetak Pra-RKA, Terima Kasih.");
										if (question === true) {
												window.close();
										} else {
											 window.close();
										}
								 </script>
						 <?php

								}
								else {

								$data['getPraRKAAll'] =$this->m_set->getPraRKAAll($id);
								$data ['getUnitName'] = $this->m_user->getUnitName($id);
								$data ['getKegiatanName'] = $this->m_kegiatan->getKegiatanName($kegrkpdkey);
								$data ['getProgramReport'] = $this->m_set->getProgramReport($kegrkpdkey);
								$data ['getTOLAKUKUR'] = $this->m_set->getTOLAKUKUR($kegrkpdkey, $id);
								$data ['getDetailPraRKA'] = $this->m_set->getDetailPraRKA($id, $kegrkpdkey);
								$data ['getTotalPrarka'] = $this->m_set->getTotalPrarka($id, $kegrkpdkey);
								$data ['getLokasi'] = $this->m_set->getLokasi($id, $kegrkpdkey);
								$data ['getDana'] = $this->m_set->cDana($id, $kegrkpdkey);
									$pagumin = $this->m_set->getRPagu($id, $kegrkpdkey);
										if(substr($pagumin,0,4) == "Rp. ") {
												$pagumin1 = substr($pagumin,4);
												$pagumins = substr($pagumin1,-3,1);
												if ($pagumins == ","){
														$pagumin2 = substr($pagumin1,0,-3);
														$pagumin3	= preg_replace('/[^A-Za-z0-9]/', '', $pagumin2);
														$data ['getRPagu'] = $pagumin1;
														$data ['getRPaguterbilang'] = terbilang($pagumin3);
														
												}else {
													$pagumin2 = $pagumin1;
													$pagumin3	= preg_replace('/[^A-Za-z0-9]/', '', $pagumin2);
													$data ['getRPagu'] = $pagumin1.",00";
													$data ['getRPaguterbilang'] = terbilang($pagumin3);
													
												}
										}else {
											$pagumin1 = $pagumin;
											$pagumin2 = substr($pagumin1,0,-3);
											$pagumin3	= preg_replace('/[^A-Za-z0-9]/', '', $pagumin2);
											if (is_numeric($pagumin3)== TRUE ){
											$pagumin1 = $pagumin;
											$pagumins = substr($pagumin1,-3,1);
											if ($pagumins == ","){
													$pagumin2 = substr($pagumin1,0,-3);
													$pagumin3	= preg_replace('/[^A-Za-z0-9]/', '', $pagumin2);
													$data ['getRPagu'] = $pagumin1;
													$data ['getRPaguterbilang'] = terbilang($pagumin3);
											}else {
												$pagumin2 = $pagumin1;
												$pagumin3	= preg_replace('/[^A-Za-z0-9]/', '', $pagumin2);
												$data ['getRPagu'] = $pagumin1.",00";
												$data ['getRPaguterbilang'] = terbilang($pagumin3);
											}
											} else {
											?>
										 <script type="text/javascript">
										 var question = confirm("Khusus untuk masukan pada Kinerja Kegiatan harus diisi dengan Angka.");
												if (question === true) {
														window.close();
												} else {
													 window.close();
												}
										 </script>
								 <?php

										}
									}
									
										$aa = $data ['getTotalPrarka'];
										foreach ($aa as $row){ $tot =  $row->total;	}
										$data ['paguterbilang'] = terbilang($tot);
										$bb = $data ['getLokasi'];
										foreach ($bb as $row):
											{ $total1 =  $row->PAGUPLUS;
												$data ['paguplusterbilang'] = terbilang($total1);
											}
										endforeach;
										$data ['nmakota'] = $this->m_set->getKota();
										if($data){
													if($act == 'print')
													{
														$this->load->view('report/v_rekap_prarka_print', $data );
													}
													else
													{
														$this->load->view('report/v_rekap_prarka_print', $data);
													}

					} else {
										?>
									 <script type="text/javascript">
									 var question = confirm("Data kinerja kegiatan (masukan) tidak sama dengan total nilai Pra-RKA dan total pagu kegiatan di renja, Silahkan cocokkan kembali kenerja kegiatan, Pra-RKA dan pagu kegiatan renja, Terima Kasih");
											if (question === true) {
													window.close();
											} else {
												 window.close();
											}
									 </script>
							 <?php
						 }
							}
								}

				}else {
									?>
								 <script type="text/javascript">
								 var question = confirm("Data Kinerja Kegiatan/ Pra-RKA belum ada, Silahkan periksa kembali kenerja kegiatan dan Pra-RKA, Terima Kasih");
										if (question === true) {
												window.close();
										} else {
											 window.close();
										}
								 </script>
						 <?php
					 }
			}
			
			
	public function _rkpd_rekap_urusan($act = '')
	{
		$this->sip->is_menu('090103');

		if($act != 'open'):
		$this->load->view('report/v_rkpd_matrik_opd');
		endif;
	}

	public function _rkpd_pagu_opd($act = '')
	{
		$this->sip->is_menu('090104');
		if($act != 'open'):
		$this->load->view('report/v_rkpd_pagu_opd');
		endif;
	}

	public function _rkpd_pagu_opd_perubahan($act = '')
	{
		$this->sip->is_menu('090105');
		if($act != 'open'):
		$this->load->view('report/v_rkpd_pagu_opd_p');
		endif;
	}

	public function _rka_prarka($act = '')
	{
		$this->sip->is_menu('090201');
		if($act != 'open'):
		$this->load->view('report/v_rka_prarka');
		endif;
	}

	public function _rka_prarka_perubahan($act = '')
	{
		$this->sip->is_menu('090202');
		if($act != 'open'):
		$this->load->view('report/v_rka_prarka_p');
		endif;
	}

	public function _matrik_renja_perangkat_daerah($act = '')
	{
		$this->sip->is_menu('090301');
		if($act != 'open'):
		$this->load->view('report/v_matrik_renja_perangkat_daerah');
		endif;
	}

	public function matrik_renja_perangkat_daerah_print($act = '', $id ='', $nama='')
	{
		$unit =  $id;
		$data['getRenjaAll'] = $this->m_set->getRenjaAll($unit);
		$data ['nmakota'] = $this->m_set->getKota();

		if($act == 'print')
		{
			$this->load->view('report/v_matrik_renja_perangkat_daerah_print', $data);
		}
		else
		{
			$this->load->view('report/v_matrik_renja_perangkat_daerah_print', $data);
		}

	}


	public function _per_urusan($act = '')
	{
		$this->sip->is_menu('090302');
		if($act != 'open'):
		$this->load->view('report/v_per_urusan');
		endif;
	}

	public function per_urusan_print($act = '')
	{

		$data['getUrusanAll'] = $this->m_set->getUrusanAll();
		$data ['nmakota'] = $this->m_set->getKota();

		if($act == 'print')
		{
			$this->load->view('report/v_per_urusan_print', $data);
		}
		else
		{
			$this->load->view('report/v_per_urusan_print', $data);
		}

	}


	public function _pagu_perangkat_daerah($act = '')
	{
		$this->sip->is_menu('090303');
		if($act != 'open'):
		$this->load->view('report/v_pagu_perangkat_daerah');
		endif;
	}

	public function pagu_perangkat_daerah_print($act = '')
	{

		$this->load->model('m_set');
		$data['paguskpdall'] = $this->m_set->paguSKPD();

		if($act == 'print')
		{
			$this->load->view('report/v_pagu_perangkat_daerah_print', $data);
		}
		else
		{
			$this->load->view('report/v_pagu_perangkat_daerah_print', $data);
		}

	}


	public function _matrik_renja_per_urusan($act = '')
	{
		$this->sip->is_menu('090304');
		if($act != 'open'):
		$this->load->view('report/v_matrik_renja_per_urusan');
		endif;
	}

	public function matrik_renja_per_urusan_print($act = '')
	{

		$data['getMatrikRenjaUrusanAll'] = $this->m_set->getMatrikRenjaUrusanAll();
		$data ['nmakota'] = $this->m_set->getKota();

		if($act == 'print')
		{
			$this->load->view('report/v_matrik_renja_per_urusan_print', $data);
		}
		else
		{
			$this->load->view('report/v_matrik_renja_per_urusan_print', $data);
		}

	}
	
	public function _rkpd_matrik_opd($act = ''){
	$this->sip->is_menu('090107');
	if($act != 'open'):
	$this->load->view('report/v_matrik51_opd_uptd_blud');
	endif;
}

public function cetak_matrik_renja_opd($act = '',$id ='', $nama='')
{
	$unit =  $id;
	$data['getRenjaAll'] = 	 $this->m_set->getRenjaAll($unit);
	$data ['nmakota'] = $this->m_set->getKota();
	if($data){
			if($act == 'print')
	{
		$this->load->view('report/v_rekap_renja_opd_print', $data);
	}
	else
	{
		$this->load->view('report/v_rekap_renja_opd_print', $data);
	}
	}
}

public function _matrik43($act = ''){
	$this->sip->is_menu('090305');
	if($act != 'open'):
	$this->load->view('report/v_matrik43');
	endif;
}

public function cetak_matrik43($act = '',$id ='', $nama='')
{
	$unit =  $id;
	$data['getRenjaAll'] = 	 $this->m_set->getRenjaAll($unit);
	$data ['nmakota'] = $this->m_set->getKota();
	if($data){
			if($act == 'print')
	{
		$this->load->view('report/v_matrik43_print', $data);
	}
	else
	{
		$this->load->view('report/v_matrik43_print', $data);
	}
	}
}

//===========================================
//17-07-2018
//===========================================
function style_column(){
	// set style
	$style_col = array(
	'font' => array('bold' => true), // Set font nya jadi bold
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
	),
	'borders' => array(
		'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
		'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
		'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
		'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
	)
);
return $style_col;
}
// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
function style_rows(){
$style_row = array(
	'alignment' => array(
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
	),
	'borders' => array(
		'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
		'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
		'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
		'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
	)
);
return $style_row;
}

public function createXLSpagu_opd() {
 // create file name
		 $fileName = 'REKAPITULASI RENCANA KERJA PERANGKAT DAERAH.xlsx';
 // load excel library
		 $this->load->library('excel');
		 $empInfo = $this->m_set->paguSKPD();
		 $objPHPExcel = new PHPExcel();
		 $objPHPExcel->setActiveSheetIndex(0);


		 // judul
		 $objPHPExcel->getActiveSheet()->setCellValue('A1', "PAYAKUMBUH"); // Set kolom A1 dengan tulisan "DATA SISWA"
		 $objPHPExcel->getActiveSheet()->setCellValue('A2', "REKAPITULASI RENCANA KERJA PERANGKAT DAERAH");
		 $objPHPExcel->getActiveSheet()->setCellValue('A3', "TAHUN ANGGARAN {$this->NMTAHUN}");
		 $objPHPExcel->getActiveSheet()->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
		 $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
		 $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
		 $objPHPExcel->getActiveSheet()->getStyle('A1:E6')->getFont()->setBold(TRUE); // Set bold kolom A1
		 $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
		 $objPHPExcel->getActiveSheet()->getStyle('A1:E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
		 $objPHPExcel->getActiveSheet()->getStyle('A6:E6')->getFont()->setSize(9);


		 // set Header
		  $objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('D6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('E6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($this->style_rows());

		 $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'No');
		 $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'SKPD');
		 $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'PAGU SKPD');
		 $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'PAGU INDIKATIF');
		 $objPHPExcel->getActiveSheet()->SetCellValue('E5', 'SELISIH');

		 $objPHPExcel->getActiveSheet()->SetCellValue('A6', '1');
		$objPHPExcel->getActiveSheet()->SetCellValue('B6', '2');
		$objPHPExcel->getActiveSheet()->SetCellValue('C6', '3');
		$objPHPExcel->getActiveSheet()->SetCellValue('D6', '4');
		$objPHPExcel->getActiveSheet()->SetCellValue('E6', '5=(4-3)');
		 // set Row
		 $rowCount = 7;
		 	$no = 1;
			$pagusisa0 = 0;
			$totalpagu = 0;
			$totalpagudigunakan = 0;
			$totalpagusisa = 0;
		 foreach ($empInfo as $element) {

				 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $no );
				 $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['NMUNIT']);
				 $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['PAGU']);
				 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['PAGUUSED']);
				 $pagusisa0= $element['PAGU'] - $element['PAGUUSED'];
					if($pagusisa0 == 0)
					{
					 $pagusisa = "-";
						$p= $pagusisa;
					}
						else
					 {
						 $pagusisa = $pagusisa0;
							$p =  $pagusisa;
					}
				 $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $p);
				 $rowCount++;
				$no++;
				$totalpagu += $element['PAGU'];
				$totalpagudigunakan += $element['PAGUUSED'];
				$totalpagusisa += $pagusisa0 ;


				 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
			   $objPHPExcel->getActiveSheet()->getStyle('E'.($rowCount))->applyFromArray($this->style_rows());
				//$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':E'.($rowCount))->applyFromArray($style_col);

		 }
		  $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'TOTAL');
		  $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $totalpagu);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $totalpagudigunakan);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $totalpagusisa);
		  $objPHPExcel->getActiveSheet()->mergeCells('A'.($rowCount).':B'.($rowCount));
			 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':B'.($rowCount))->applyFromArray($this->style_column());

			// Set width kolom
		    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
		    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50); // Set width kolom B
		    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); // Set width kolom C
		    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D
		    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20); // Set width kolom E
		    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

				// Set orientasi kertas jadi LANDSCAPE
  //  $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


		 $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		 $objWriter->save($fileName);
 // download file
		 header("Content-Type: application/vnd.ms-excel");
		 redirect(site_url().$fileName);
 }

 //-------------------------------------------------------------------------------------------


 public function createXLSmatrik_renja($unit='') {
  // create file name
 		 $fileName = 'RENCANA PROGRAM DAN KEGIATAN PERANGKAT DAERAH.xlsx';
  // load excel library
 		 $this->load->library('excel');
 		 $empInfo = $this->m_set->getRenjaAll($unit);
		 $nmakota = $this->m_set->getKota();
 		 $objPHPExcel = new PHPExcel();
 		 $objPHPExcel->setActiveSheetIndex(0);
		 foreach ($empInfo as $k){
			 $aa = $k->SKPD;
		 }

 		 // judul
 		 $objPHPExcel->getActiveSheet()->setCellValue('A1', "PEMERINTAH KOTA PAYAKUMBUH "); // Set kolom A1 dengan tulisan "DATA SISWA"
 		 $objPHPExcel->getActiveSheet()->setCellValue('A2', "RENCANA PROGRAM DAN KEGIATAN PERANGKAT DAERAH DENGAN PRIORITAS DAERAH TAHUN {$this->NMTAHUN}");
 		 $objPHPExcel->getActiveSheet()->setCellValue('A4', "Organisasi / PD : {$aa} " );
 		 $objPHPExcel->getActiveSheet()->mergeCells('A1:N1'); // Set Merge Cell pada kolom A1 sampai E1
 		 $objPHPExcel->getActiveSheet()->mergeCells('A2:N2');
 		 $objPHPExcel->getActiveSheet()->mergeCells('A3:N3');
 		 $objPHPExcel->getActiveSheet()->getStyle('A1:N8')->getFont()->setBold(TRUE); // Set bold kolom A1
 		 $objPHPExcel->getActiveSheet()->getStyle('A1:N6')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
 		 $objPHPExcel->getActiveSheet()->getStyle('A1:N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
		 $objPHPExcel->getActiveSheet()->getStyle('A5:N8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
 		 $objPHPExcel->getActiveSheet()->getStyle('A8:N8')->getFont()->setSize(9);


 		 // set Header
 		  $objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($this->style_rows());
 		  $objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($this->style_rows());
 		  $objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($this->style_rows());
 		  $objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($this->style_rows());
 		  $objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($this->style_rows());
 		  $objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('I5')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('J5')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('K5')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('L5')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('M5')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('N5')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('F6')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('H6')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('J6')->applyFromArray($this->style_rows());

			$objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('C8')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('D8')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('E8')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('F8')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('G8')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('H8')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('I8')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('J8')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('K8')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('L8')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('M8')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('N8')->applyFromArray($this->style_rows());

			$objPHPExcel->getActiveSheet()->getStyle('A9')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('B9')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('C9')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('D9')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('E9')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('F9')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('G9')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('H9')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('I9')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('J9')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('K9')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('L9')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('M9')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('N9')->applyFromArray($this->style_rows());

			$objPHPExcel->getActiveSheet()->mergeCells('A5:A7'); // Set Merge Cell pada kolom A1 sampai E1
			$objPHPExcel->getActiveSheet()->mergeCells('B5:B7');
			$objPHPExcel->getActiveSheet()->mergeCells('C5:C7');
			$objPHPExcel->getActiveSheet()->mergeCells('D5:D7');
			$objPHPExcel->getActiveSheet()->mergeCells('E5:E7');
			$objPHPExcel->getActiveSheet()->mergeCells('F5:K5');
			$objPHPExcel->getActiveSheet()->mergeCells('F6:G6');
			$objPHPExcel->getActiveSheet()->mergeCells('H6:I6');
			$objPHPExcel->getActiveSheet()->mergeCells('J6:K6');
			$objPHPExcel->getActiveSheet()->mergeCells('L5:L7');
			$objPHPExcel->getActiveSheet()->mergeCells('M5:M7');
			$objPHPExcel->getActiveSheet()->mergeCells('N5:N7');


 		 $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'KODE');
 		 $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'Urusan/Bidang Urusan Pemerintah Daerah dan');
 		 $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Prioritas');
 		 $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'Sasaran');
 		 $objPHPExcel->getActiveSheet()->SetCellValue('E5', 'Lokasi');
		 $objPHPExcel->getActiveSheet()->SetCellValue('F5', 'Indikator Kinerja Program/Kegiatan');
		 $objPHPExcel->getActiveSheet()->SetCellValue('L5', 'Pagu Indikatif');
		 $objPHPExcel->getActiveSheet()->SetCellValue('M5', 'Prakiraan Maju');
		 $objPHPExcel->getActiveSheet()->SetCellValue('N5', 'Jenis Kegiatan');
		 $objPHPExcel->getActiveSheet()->SetCellValue('F6', 'Capaian Program (Indikator Sasaran)');
		 $objPHPExcel->getActiveSheet()->SetCellValue('H6', 'Keluaran (Output)');
		 $objPHPExcel->getActiveSheet()->SetCellValue('J6', 'Hasil (Outcome)');
		 $objPHPExcel->getActiveSheet()->SetCellValue('F7', 'Tolak Ukur');
		 $objPHPExcel->getActiveSheet()->SetCellValue('G7', 'Target');
		 $objPHPExcel->getActiveSheet()->SetCellValue('H7', 'Tolak Ukur');
		 $objPHPExcel->getActiveSheet()->SetCellValue('I7', 'Target');
		 $objPHPExcel->getActiveSheet()->SetCellValue('J7', 'Tolak Ukur');
		 $objPHPExcel->getActiveSheet()->SetCellValue('K7', 'Target');


 		 $objPHPExcel->getActiveSheet()->SetCellValue('A8', '1');
		 $objPHPExcel->getActiveSheet()->SetCellValue('B8', '2');
		 $objPHPExcel->getActiveSheet()->SetCellValue('C8', '3');
		 $objPHPExcel->getActiveSheet()->SetCellValue('D8', '4');
		 $objPHPExcel->getActiveSheet()->SetCellValue('E8', '5');
		 $objPHPExcel->getActiveSheet()->SetCellValue('F8', '6');
		 $objPHPExcel->getActiveSheet()->SetCellValue('G8', '7');
		 $objPHPExcel->getActiveSheet()->SetCellValue('H8', '8');
		 $objPHPExcel->getActiveSheet()->SetCellValue('I8', '9');
		 $objPHPExcel->getActiveSheet()->SetCellValue('J8', '10');
		 $objPHPExcel->getActiveSheet()->SetCellValue('K8', '11');
		 $objPHPExcel->getActiveSheet()->SetCellValue('L8', '12');
		 $objPHPExcel->getActiveSheet()->SetCellValue('M8', '13');
		 $objPHPExcel->getActiveSheet()->SetCellValue('N8', '14');

 		 // set Row
 		 $rowCount = 9;
 		 	$no = 1;
			$total = 0;
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
 		 foreach ($empInfo as $element) {
			 $KODE = $element->KODE;
			 $NMUNIT = $element->SKPD;
			 $pagu1= $element->PAGU1;
			 $jab = $element->JAB;
			 $nip = $element->NIP;
			 $nama = $element->NAMA;
			 $NMTAHAP = $element->NMTAHAP;
			 IF ($pagu1 <= 0){
				  $NM ="-";
				}
				ELSE
				{
				$NM = $element->PAGU1;
			}
			$KEG= $element->SIFATKEG;
			 IF ($KEG == 1)
				{
					$NMKEG ="Baru" ;
				}
				ELSEIF ($KEG == 2)
				{
				 $NMKEG ="Lanjutan" ;
			 } ELSE{$NMKEG ="Rutin" ;}

			 $TYPE =	$element->TYPE;
			 IF ($TYPE =='H')
			 {
				 $detailpagu = NULL;
						 }
			 ELSE
			 {
				 $detailpagu = $element->PAGU;
			 }

 				 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element->KODE);
 				 $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element->NMPRGRM);
 				 $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element->PRIODAERAH);
 				 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element->SASARAN);
				 $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element->LOKASI);
				 $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element->INDIKATOR);
				 $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $element->TCPAPAIPGR);
				 $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $element->KELUARAN);
				 $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $element->TARGETLUAR);
				 $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $element->HASIL);
				 $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $element->TARGETHASIL);
				 $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $element->PAGU);
				 $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $NM);
				 $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $NMKEG);


					$totalpagu +=  $detailpagu;
					$rowCount++;
				 $no++;

 				 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
 				 $objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
 				 $objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
 				 $objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
 			   $objPHPExcel->getActiveSheet()->getStyle('E'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('F'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('G'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('H'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('I'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('J'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('K'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('L'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('M'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('N'.($rowCount))->applyFromArray($this->style_rows());
 				//$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':E'.($rowCount))->applyFromArray($style_col);

 		 }
 		  	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'TOTAL');
 		  	$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $totalpagu);
 				$objPHPExcel->getActiveSheet()->mergeCells('A'.($rowCount).':K'.($rowCount));
 		    $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':K'.($rowCount))->applyFromArray($this->style_column());


				  			$row = $rowCount+5;
				 				$TANGGAL=tanggal_indo(date('Y-m-d'));
				 				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, "Payakumbuh, {$TANGGAL}");
				 				$objPHPExcel->getActiveSheet()->mergeCells('L'.($row).':M'.($row));
				 				$objPHPExcel->getActiveSheet()->getStyle('L'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				 				$row1 = $row+1;
				 				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $row1, $jab);
				 				$objPHPExcel->getActiveSheet()->mergeCells('L'.($row1).':M'.($row1));
				 				$objPHPExcel->getActiveSheet()->getStyle('L'.($row1).':M'.($row1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				 				$row  = $row1+5;
				 				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, $nama);
								$objPHPExcel->getActiveSheet()->mergeCells('L'.($row).':M'.($row));
								 $objPHPExcel->getActiveSheet()->getStyle('L'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$row  = $row+1;
								$objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, $nip);
				 			  $objPHPExcel->getActiveSheet()->mergeCells('L'.($row).':M'.($row));
				 		    $objPHPExcel->getActiveSheet()->getStyle('L'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


 			// Set width kolom
 		    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
 		    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
 		    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
 		    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
 		    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
			  $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(40);
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
 		    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
 		    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-2);

 				// Set orientasi kertas jadi LANDSCAPE
   //  $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


 		 $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
 		 $objWriter->save($fileName);
  // download file
 		 header("Content-Type: application/vnd.ms-excel");
 		 redirect(site_url().$fileName);
  }

  //-------------------------------------------------------------------------------------------

	public function createXLSmatrik_renjaoutcome($unit='') {
	 // create file name
			$fileName = 'RENCANA PROGRAM DAN KEGIATAN PERANGKAT DAERAH.xlsx';
	 // load excel library
			$this->load->library('excel');
			$empInfo = $this->m_set->getRenjaAll($unit);
			 $nmakota = $this->m_set->getKota();
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			foreach ($empInfo as $k){
				$aa = $k->SKPD;
			}

			// judul
			$objPHPExcel->getActiveSheet()->setCellValue('A1', "PEMERINTAH KOTA PAYAKUMBUH "); // Set kolom A1 dengan tulisan "DATA SISWA"
			$objPHPExcel->getActiveSheet()->setCellValue('A2', "RENCANA PROGRAM DAN KEGIATAN PERANGKAT DAERAH DENGAN PRIORITAS DAERAH TAHUN {$this->NMTAHUN}");
			$objPHPExcel->getActiveSheet()->setCellValue('A4', "Organisasi / PD : {$aa} " );
			$objPHPExcel->getActiveSheet()->mergeCells('A1:N1'); // Set Merge Cell pada kolom A1 sampai E1
			$objPHPExcel->getActiveSheet()->mergeCells('A2:N2');
			$objPHPExcel->getActiveSheet()->mergeCells('A3:N3');
			$objPHPExcel->getActiveSheet()->getStyle('A1:N8')->getFont()->setBold(TRUE); // Set bold kolom A1
			$objPHPExcel->getActiveSheet()->getStyle('A1:N6')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
			$objPHPExcel->getActiveSheet()->getStyle('A1:N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
			$objPHPExcel->getActiveSheet()->getStyle('A5:N8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
			$objPHPExcel->getActiveSheet()->getStyle('A8:N8')->getFont()->setSize(9);


			// set Header
			 $objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('I5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('J5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('K5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('L5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('M5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('N5')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('F6')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('H6')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('J6')->applyFromArray($this->style_rows());

			 $objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('C8')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('D8')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('E8')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('F8')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('G8')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('H8')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('I8')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('J8')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('K8')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('L8')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('M8')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('N8')->applyFromArray($this->style_rows());

			 $objPHPExcel->getActiveSheet()->getStyle('A9')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('B9')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('C9')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('D9')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('E9')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('F9')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('G9')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('H9')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('I9')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('J9')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('K9')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('L9')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('M9')->applyFromArray($this->style_rows());
			 $objPHPExcel->getActiveSheet()->getStyle('N9')->applyFromArray($this->style_rows());

			 $objPHPExcel->getActiveSheet()->mergeCells('A5:A7'); // Set Merge Cell pada kolom A1 sampai E1
			 $objPHPExcel->getActiveSheet()->mergeCells('B5:B7');
			 $objPHPExcel->getActiveSheet()->mergeCells('C5:C7');
			 $objPHPExcel->getActiveSheet()->mergeCells('D5:D7');
			 $objPHPExcel->getActiveSheet()->mergeCells('E5:E7');
			 $objPHPExcel->getActiveSheet()->mergeCells('F5:K5');
			 $objPHPExcel->getActiveSheet()->mergeCells('F6:G6');
			 $objPHPExcel->getActiveSheet()->mergeCells('H6:I6');
			 $objPHPExcel->getActiveSheet()->mergeCells('J6:K6');
			 $objPHPExcel->getActiveSheet()->mergeCells('L5:L7');
			 $objPHPExcel->getActiveSheet()->mergeCells('M5:M7');
			 $objPHPExcel->getActiveSheet()->mergeCells('N5:N7');


			$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'KODE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B5', 'Urusan/Bidang Urusan Pemerintah Daerah dan');
			$objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Prioritas');
			$objPHPExcel->getActiveSheet()->SetCellValue('D5', 'Sasaran');
			$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'Lokasi');
			$objPHPExcel->getActiveSheet()->SetCellValue('F5', 'Indikator Kinerja Program/Kegiatan');
			$objPHPExcel->getActiveSheet()->SetCellValue('L5', 'Pagu Indikatif');
			$objPHPExcel->getActiveSheet()->SetCellValue('M5', 'Prakiraan Maju');
			$objPHPExcel->getActiveSheet()->SetCellValue('N5', 'Jenis Kegiatan');
			$objPHPExcel->getActiveSheet()->SetCellValue('F6', 'Capaian Program (Indikator Sasaran)');
			$objPHPExcel->getActiveSheet()->SetCellValue('H6', 'Keluaran (Output)');
			$objPHPExcel->getActiveSheet()->SetCellValue('J6', 'Hasil (Outcome)');
			$objPHPExcel->getActiveSheet()->SetCellValue('F7', 'Tolak Ukur');
			$objPHPExcel->getActiveSheet()->SetCellValue('G7', 'Target');
			$objPHPExcel->getActiveSheet()->SetCellValue('H7', 'Tolak Ukur');
			$objPHPExcel->getActiveSheet()->SetCellValue('I7', 'Target');
			$objPHPExcel->getActiveSheet()->SetCellValue('J7', 'Tolak Ukur');
			$objPHPExcel->getActiveSheet()->SetCellValue('K7', 'Target');


			$objPHPExcel->getActiveSheet()->SetCellValue('A8', '1');
			$objPHPExcel->getActiveSheet()->SetCellValue('B8', '2');
			$objPHPExcel->getActiveSheet()->SetCellValue('C8', '3');
			$objPHPExcel->getActiveSheet()->SetCellValue('D8', '4');
			$objPHPExcel->getActiveSheet()->SetCellValue('E8', '5');
			$objPHPExcel->getActiveSheet()->SetCellValue('F8', '6');
			$objPHPExcel->getActiveSheet()->SetCellValue('G8', '7');
			$objPHPExcel->getActiveSheet()->SetCellValue('H8', '8');
			$objPHPExcel->getActiveSheet()->SetCellValue('I8', '9');
			$objPHPExcel->getActiveSheet()->SetCellValue('J8', '10');
			$objPHPExcel->getActiveSheet()->SetCellValue('K8', '11');
			$objPHPExcel->getActiveSheet()->SetCellValue('L8', '12');
			$objPHPExcel->getActiveSheet()->SetCellValue('M8', '13');
			$objPHPExcel->getActiveSheet()->SetCellValue('N8', '14');

			// set Row
			$rowCount = 9;
			 $no = 1;
			 $total = 0;
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
			foreach ($empInfo as $element) {
				$KODE = $element->KODE;

				$NMUNIT = $element->SKPD;
				$pagu1= $element->PAGU1;
				$jab = $element->JAB;
				$nip = $element->NIP;
				$nama = $element->NAMA;
				$NMTAHAP = $element->NMTAHAP;
				IF ($pagu1 <= 0){
					 $NM ="-";
				 }
				 ELSE
				 {
				 $NM = $element->PAGU1;
			 }
			 $KEG= $element->SIFATKEG;
				IF ($KEG == 1)
				 {
					 $NMKEG ="Baru" ;
				 }
				 ELSEIF ($KEG == 2)
				 {
					$NMKEG ="Lanjutan" ;
				} ELSE{$NMKEG ="Rutin" ;}

				$TYPE =	$element->TYPE;
				IF ($TYPE =='H')
				{
					$detailpagu = NULL;
							}
				ELSE
				{
					$detailpagu = $element->PAGU;
				}

					$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element->KODE);
					$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element->NMPRGRM);
					$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element->PRIODAERAH);
					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element->SASARAN);
					$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element->LOKASI);
					$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element->INDIKATOR);
					$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $element->TCPAPAIPGR);
					$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $element->KELUARAN);
					$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $element->TARGETLUAR);
					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $element->OUTCOMETOLOKUR);
					$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $element->OUTCOMETARGET);
					$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $element->PAGU);
					$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $NM);
					$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $NMKEG);


					 $totalpagu +=  $detailpagu;
					 $rowCount++;
					$no++;

					$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('E'.($rowCount))->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('F'.($rowCount))->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('G'.($rowCount))->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('H'.($rowCount))->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('I'.($rowCount))->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('J'.($rowCount))->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('K'.($rowCount))->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('L'.($rowCount))->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('M'.($rowCount))->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('N'.($rowCount))->applyFromArray($this->style_rows());
				 //$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':E'.($rowCount))->applyFromArray($style_col);

			}
				 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'TOTAL');
				 $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $totalpagu);
				 $objPHPExcel->getActiveSheet()->mergeCells('A'.($rowCount).':K'.($rowCount));
				 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':K'.($rowCount))->applyFromArray($this->style_column());


  			$row = $rowCount+5;
 				$TANGGAL=tanggal_indo(date('Y-m-d'));
 				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, "Payakumbuh, {$TANGGAL}");
 				$objPHPExcel->getActiveSheet()->mergeCells('L'.($row).':M'.($row));
 				$objPHPExcel->getActiveSheet()->getStyle('L'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

 				$row1 = $row+1;
 				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $row1, $jab);
 				$objPHPExcel->getActiveSheet()->mergeCells('L'.($row1).':M'.($row1));
 				$objPHPExcel->getActiveSheet()->getStyle('L'.($row1).':M'.($row1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 				$row  = $row1+5;
 				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, $nama);
				$objPHPExcel->getActiveSheet()->mergeCells('L'.($row).':M'.($row));
				 $objPHPExcel->getActiveSheet()->getStyle('L'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$row  = $row+1;
				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, $nip);
 			  $objPHPExcel->getActiveSheet()->mergeCells('L'.($row).':M'.($row));
 		    $objPHPExcel->getActiveSheet()->getStyle('L'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


			 // Set width kolom
				 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
				 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
				 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
				 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
				 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
				 $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
				 $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
				 $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
				 $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
				 $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(40);
				 $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
				 $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
				 $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
				 $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
				 // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
				 $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-2);

				 // Set orientasi kertas jadi LANDSCAPE
		//  $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($fileName);
	 // download file
			header("Content-Type: application/vnd.ms-excel");
			redirect(site_url().$fileName);
	 }

	 //-------------------------------------------------------------------------------------------



	public function createXLSskpd_opd() {
	 // create file name
			 $fileName = 'REKAPITULASI RENCANA PROGRAM DAN KEGIATAN PRIORITAS DAERAH MENURUT PERANGKAT DAERAH.xlsx';
	 // load excel library
			 $this->load->library('excel');
			 $empInfo = $this->m_set->getAllPagu();
			 $nmakota = $this->m_set->getKota();
			 $objPHPExcel = new PHPExcel();
			 $objPHPExcel->setActiveSheetIndex(0);


			 // judul
			 $objPHPExcel->getActiveSheet()->setCellValue('A1', "REKAPITULASI RENCANA"); // Set kolom A1 dengan tulisan "DATA SISWA"
			 $objPHPExcel->getActiveSheet()->setCellValue('A2', "PROGRAM DAN KEGIATAN PRIORITAS DAERAH TAHUN ANGGARAN {$this->NMTAHUN}");
			 $objPHPExcel->getActiveSheet()->setCellValue('A3', "MENURUT PERANGKAT DAERAH");
			 $objPHPExcel->getActiveSheet()->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
			 $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
			 $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
			 $objPHPExcel->getActiveSheet()->getStyle('A1:E6')->getFont()->setBold(TRUE); // Set bold kolom A1
			 $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
			 $objPHPExcel->getActiveSheet()->getStyle('A1:E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
			 $objPHPExcel->getActiveSheet()->getStyle('A6:E6')->getFont()->setSize(9);


			 // set Header
			  $objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($this->style_rows());
			  $objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($this->style_rows());
			  $objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($this->style_rows());
			  $objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($this->style_rows());
			  $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($this->style_rows());
				$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($this->style_rows());
				$objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray($this->style_rows());
				$objPHPExcel->getActiveSheet()->getStyle('D6')->applyFromArray($this->style_rows());
				$objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($this->style_rows());
				$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($this->style_rows());
				$objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($this->style_rows());
				$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($this->style_rows());

			 $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'No');
			 $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'PERANGKAT DAERAH');
			 $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'PAGU');
			 $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'KET');

			 $objPHPExcel->getActiveSheet()->SetCellValue('A6', '1');
		   $objPHPExcel->getActiveSheet()->SetCellValue('B6', '2');
			 $objPHPExcel->getActiveSheet()->SetCellValue('C6', '3');
		   $objPHPExcel->getActiveSheet()->SetCellValue('D6', '4');
			 // set Row
			 $rowCount = 7;
			 	$no = 1;

				$totalpagu = 0;

			 foreach ($empInfo as $element) {

					 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $no );
					 $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['NMUNIT']);
					 $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['PAGUTIF']);

					  $rowCount++;
				  	$no++;
						$total += $element['PAGUTIF'];


					 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
					//$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':E'.($rowCount))->applyFromArray($style_col);

			 }
			  $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'TOTAL');
			  $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $total);
			  $objPHPExcel->getActiveSheet()->mergeCells('A'.($rowCount).':B'.($rowCount));
				$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':B'.($rowCount))->applyFromArray($this->style_column());


 				$row = $rowCount+5;
				$TANGGAL=tanggal_indo(date('Y-m-d'));
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, "Payakumbuh, {$TANGGAL}");
				$objPHPExcel->getActiveSheet()->mergeCells('C'.($row).':D'.($row));
					 $objPHPExcel->getActiveSheet()->getStyle('C'.($row).':D'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				foreach($nmakota as $k){
					$jabatan = $k['JABATAN'];
					$namapimpinan = $k['NMPIMPINAN'];
				}
				$row1 = $row+1;
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row1, $jabatan);
				$objPHPExcel->getActiveSheet()->mergeCells('C'.($row1).':D'.($row1));
				$objPHPExcel->getActiveSheet()->getStyle('C'.($row1).':D'.($row1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$row  = $row1+5;
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $namapimpinan);
			  $objPHPExcel->getActiveSheet()->mergeCells('C'.($row).':D'.($row));
		    $objPHPExcel->getActiveSheet()->getStyle('C'.($row).':D'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// Set width kolom
			    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
			    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50); // Set width kolom B
			    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); // Set width kolom C
			    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D
			    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
			    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

					// Set orientasi kertas jadi LANDSCAPE
	  //  $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


			 $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			 $objWriter->save($fileName);
	 // download file
			 header("Content-Type: application/vnd.ms-excel");
			 redirect(site_url().$fileName);
	 }

	 //-------------------------------------------------------------------------------------------

	 public function createXLSrekap_urusan() {
	  // create file name
	 		 $fileName = 'REKAPITULASI RENCANA PROGRAM KEGIATAN MENURUT URUSAN.xlsx';
	  // load excel library
	 		 $this->load->library('excel');
	 		 $empInfo = $this->m_set->getUrusanAll();
			 $pagukec = $this->m_set->pagukec();
			 //$nmakota = $this->m_set->getKota();
	 		 $objPHPExcel = new PHPExcel();
	 		 $objPHPExcel->setActiveSheetIndex(0);


	 		 // judul
			 $objPHPExcel->getActiveSheet()->setCellValue('A1', "MATRIK V.II"); 
	 		 $objPHPExcel->getActiveSheet()->setCellValue('A2', "REKAPITULASI RENCANA"); // Set kolom A1 dengan tulisan "DATA SISWA"
	 		 $objPHPExcel->getActiveSheet()->setCellValue('A3', "PROGRAM DAN KEGIATAN PRIORITAS DAERAH TAHUN ANGGARAN {$this->NMTAHUN}");
	 		 $objPHPExcel->getActiveSheet()->setCellValue('A4', "MENURUT URUSAN");
	 		 $objPHPExcel->getActiveSheet()->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
	 		 $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
	 		 $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
			 $objPHPExcel->getActiveSheet()->mergeCells('A4:E4');
	 		 $objPHPExcel->getActiveSheet()->getStyle('A1:E6')->getFont()->setBold(TRUE); // Set bold kolom A1
	 		 $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
	 		 $objPHPExcel->getActiveSheet()->getStyle('A1:E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
	 		 $objPHPExcel->getActiveSheet()->getStyle('A6:E6')->getFont()->setSize(9);


	 		 // set Header
	 		  $objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($this->style_rows());
	 		  $objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($this->style_rows());
	 		  $objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($this->style_rows());
	 		  $objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($this->style_rows());
	 		  $objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($this->style_rows());
	 		  $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('D6')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('E6')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($this->style_rows());

	 		// $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'No');
	 		 $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'KODE');
	 		 $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'URUSAN');
	 		 $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'SKPD');
	 		 $objPHPExcel->getActiveSheet()->SetCellValue('E5', 'PAGU');

	 		// $objPHPExcel->getActiveSheet()->SetCellValue('A6', '1');
	 		$objPHPExcel->getActiveSheet()->SetCellValue('B6', '1');
	 		$objPHPExcel->getActiveSheet()->SetCellValue('C6', '2');
	 		$objPHPExcel->getActiveSheet()->SetCellValue('D6', '3');
	 		$objPHPExcel->getActiveSheet()->SetCellValue('E6', '4');
	 		 // set Row
	 		 $rowCount = 7;
			 $total = 0;
			 $no = 1;
			 $pagusisa = 0;
			 $totalpagu = 0;
			 $NM = NULL;
			 $NAMA = NULL;
			 $TYPE = NULL;
			 $PAGU1=NULL;
			 $PAGU2=NULL;
			 $total =0;
			 $total2 = 0 ;
	 		 foreach ($empInfo as $element) {
				 $kdlevel = $element->KDLEVEL;
					if ($kdlevel != 4) {
				 $TYPE =	$element->TYPE;
				 IF ($TYPE =='H')
				 {
					 $NM = $element->NMPRGRM;
					 $PAGU2 = $element->PAGU;
					 $NAMA =NULL;
					 $PAGU1= NULL;

				 }
				 ELSE
				 {
					 $NAMA = $element->NMPRGRM;
					 $PAGU1 = $element->PAGU;
					 $NM = NULL;
					 $PAGU2= NULL;
				 }

	 				// $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $no );
	 				 $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element->KODE);
	 				 $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $NM);
	 				 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $NAMA);
					 $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element->PAGU);
	 				 $rowCount++;
	 			   $no++;
	 		  	$total += $PAGU1;
	 				 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
	 				 $objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
	 				 $objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
	 				 $objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
	 			   $objPHPExcel->getActiveSheet()->getStyle('E'.($rowCount))->applyFromArray($this->style_rows());
	 				//$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':E'.($rowCount))->applyFromArray($style_col);
					}
	 		 }
			 
			 
				foreach($pagukec as $pagukec){
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
						
						// $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $no );
						 $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $kodeunit);
						 $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, '');
						 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $nama);
						 $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $pagukec['PAGUKEC']);
						 $rowCount++;
					   $no++;
					$total2 += $pagukec['PAGUKEC'];
						 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
						 $objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
						 $objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
						 $objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
					   $objPHPExcel->getActiveSheet()->getStyle('E'.($rowCount))->applyFromArray($this->style_rows());
						//$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':E'.($rowCount))->applyFromArray($style_col);
				}
				$totalpagu = $total2 + $total ;	
	 		  $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'TOTAL');
	 		  $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $totalpagu);
	 		  $objPHPExcel->getActiveSheet()->mergeCells('A'.($rowCount).':D'.($rowCount));
	 			 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':D'.($rowCount))->applyFromArray($this->style_column());

	 			// Set width kolom
	 		    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
	 		    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30); // Set width kolom B
	 		    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50); // Set width kolom C
	 		    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(80); // Set width kolom D
	 		    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20); // Set width kolom E
	 		    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
	 		    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

	 				// Set orientasi kertas jadi LANDSCAPE
	   //  $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


	 		 $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	 		 $objWriter->save($fileName);
	  // download file
	 		 header("Content-Type: application/vnd.ms-excel");
	 		 redirect(site_url().$fileName);
	  }

	  //-------------------------------------------------------------------------------------------

		public function createXLSmatrik_51() {
				        // create file name
				  		 $fileName = 'RENCANA PROGRAM DAN KEGIATAN PERANGKAT DAERAH.xlsx';
				       // load excel library
				  		 $this->load->library('excel');
				  		 $empInfo = $this->m_set->matrik51all();
				 		 	 $nmakota = $this->m_set->bappedadata();
				  		 $objPHPExcel = new PHPExcel();
				  		 $objPHPExcel->setActiveSheetIndex(0);

				  		 // judul
				  		 $objPHPExcel->getActiveSheet()->setCellValue('A1', "Tabel 5.1 "); // Set kolom A1 dengan tulisan "DATA SISWA"
				  		 $objPHPExcel->getActiveSheet()->setCellValue('A2', "RENCANA PROGRAM DAN KEGIATAN PRIORITAS DAERAH TAHUN ANGGARAN {$this->NMTAHUN}");
				  		 $objPHPExcel->getActiveSheet()->setCellValue('A3', "PEMERINTAHAN KOTA PAYAKUMBUH" );
				  		 $objPHPExcel->getActiveSheet()->mergeCells('A1:M1'); // Set Merge Cell pada kolom A1 sampai E1
				  		 $objPHPExcel->getActiveSheet()->mergeCells('A2:M2');
				  		 $objPHPExcel->getActiveSheet()->mergeCells('A3:M3');
				  		 $objPHPExcel->getActiveSheet()->getStyle('A1:M8')->getFont()->setBold(TRUE); // Set bold kolom A1
				  		 $objPHPExcel->getActiveSheet()->getStyle('A1:M6')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
				  		 $objPHPExcel->getActiveSheet()->getStyle('A1:M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
				 		 	 $objPHPExcel->getActiveSheet()->getStyle('A5:M8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
				  		 $objPHPExcel->getActiveSheet()->getStyle('A8:M8')->getFont()->setSize(9);


	  		 				// set Header
				  		  $objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($this->style_rows());
				  		  $objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($this->style_rows());
				  		  $objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($this->style_rows());
				  		  $objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($this->style_rows());
				  		  $objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($this->style_rows());
				  		  $objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($this->style_rows());
				  			$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($this->style_rows());
				  			$objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($this->style_rows());
				  			$objPHPExcel->getActiveSheet()->getStyle('I5')->applyFromArray($this->style_rows());
				  			$objPHPExcel->getActiveSheet()->getStyle('J5')->applyFromArray($this->style_rows());
				  			$objPHPExcel->getActiveSheet()->getStyle('K5')->applyFromArray($this->style_rows());
				  			$objPHPExcel->getActiveSheet()->getStyle('L5')->applyFromArray($this->style_rows());
				  			$objPHPExcel->getActiveSheet()->getStyle('M5')->applyFromArray($this->style_rows());
				  			$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($this->style_rows());
				 				$objPHPExcel->getActiveSheet()->getStyle('F6')->applyFromArray($this->style_rows());
				  			$objPHPExcel->getActiveSheet()->getStyle('H6')->applyFromArray($this->style_rows());
				  			$objPHPExcel->getActiveSheet()->getStyle('J6')->applyFromArray($this->style_rows());

				 			$objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('C8')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('D8')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('E8')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('F8')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('G8')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('H8')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('I8')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('J8')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('K8')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('L8')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('M8')->applyFromArray($this->style_rows());

				 			$objPHPExcel->getActiveSheet()->getStyle('A9')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('B9')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('C9')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('D9')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('E9')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('F9')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('G9')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('H9')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('I9')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('J9')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('K9')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('L9')->applyFromArray($this->style_rows());
				 			$objPHPExcel->getActiveSheet()->getStyle('M9')->applyFromArray($this->style_rows());

				 			$objPHPExcel->getActiveSheet()->mergeCells('A5:A7'); // Set Merge Cell pada kolom A1 sampai E1
				 			$objPHPExcel->getActiveSheet()->mergeCells('B5:B7');
				 			$objPHPExcel->getActiveSheet()->mergeCells('C5:C7');
				 			$objPHPExcel->getActiveSheet()->mergeCells('D5:D7');
				 			$objPHPExcel->getActiveSheet()->mergeCells('E5:E7');
				 			$objPHPExcel->getActiveSheet()->mergeCells('F5:K5');
				 			$objPHPExcel->getActiveSheet()->mergeCells('F6:G6');
				 			$objPHPExcel->getActiveSheet()->mergeCells('H6:I6');
				 			$objPHPExcel->getActiveSheet()->mergeCells('J6:K6');
				 			$objPHPExcel->getActiveSheet()->mergeCells('L5:L7');
				 			$objPHPExcel->getActiveSheet()->mergeCells('M5:M7');


			  		 $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'KODE');
			  		 $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'Urusan/Bidang Urusan Pemerintah Daerah dan');
			  		 $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Prioritas');
			  		 $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'Sasaran');
			  		 $objPHPExcel->getActiveSheet()->SetCellValue('E5', 'Lokasi');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('F5', 'Indikator Kinerja Program/Kegiatan');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('L5', 'Pagu Indikatif');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('M5', 'Perangkat Daerah');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('F6', 'Capaian Program (Indikator Sasaran)');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('H6', 'Keluaran (Output)');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('J6', 'Hasil (Outcome)');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('F7', 'Tolak Ukur');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('G7', 'Target');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('H7', 'Tolak Ukur');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('I7', 'Target');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('J7', 'Tolak Ukur');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('K7', 'Target');


			  		 $objPHPExcel->getActiveSheet()->SetCellValue('A8', '1');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('B8', '2');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('C8', '3');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('D8', '4');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('E8', '5');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('F8', '6');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('G8', '7');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('H8', '8');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('I8', '9');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('J8', '10');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('K8', '11');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('L8', '12');
				 		 $objPHPExcel->getActiveSheet()->SetCellValue('M8', '13');

		  		 	// set Row
		  		 	$rowCount = 9;
		  		 	$no = 1;
			 			$total = 0;
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
			  		 foreach ($empInfo as $element) {
			 			 $KODE = $element->KODE;
			 			 $NMUNIT = $element->SKPD;
						 $NMUNIT = $element->SKPD;
						 $TYPE =	$element->TYPE;
						 IF ($TYPE =='H')
						 {
							 $detailpagu = NULL;
									 }
						 ELSE
						 {
							 $detailpagu = $element->PAGU;
						 }

	  				 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element->KODE);
	  				 $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element->NMPRGRM);
	  				 $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element->NOPRIO);
	  				 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element->SASARAN);
		 				 $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element->LOKASI);
		 				 $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element->INDIKATOR);
		 				 $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $element->TCPAPAIPGR);
		 				 $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $element->KELUARAN);
		 				 $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $element->TARGETLUAR1);
		 				 $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $element->HASIL);
		 				 $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $element->TARGETHASIL);
		 				 $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $element->PAGU);
		 				 $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $element->SKPD);


	 					$totalpagu +=  $detailpagu;
	 					$rowCount++;
	 				 	$no++;

	  				 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
	  				 $objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
	  				 $objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
	  				 $objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
	  			   $objPHPExcel->getActiveSheet()->getStyle('E'.($rowCount))->applyFromArray($this->style_rows());
		 				 $objPHPExcel->getActiveSheet()->getStyle('F'.($rowCount))->applyFromArray($this->style_rows());
		 				 $objPHPExcel->getActiveSheet()->getStyle('G'.($rowCount))->applyFromArray($this->style_rows());
		 				 $objPHPExcel->getActiveSheet()->getStyle('H'.($rowCount))->applyFromArray($this->style_rows());
		 				 $objPHPExcel->getActiveSheet()->getStyle('I'.($rowCount))->applyFromArray($this->style_rows());
		 				 $objPHPExcel->getActiveSheet()->getStyle('J'.($rowCount))->applyFromArray($this->style_rows());
		 				 $objPHPExcel->getActiveSheet()->getStyle('K'.($rowCount))->applyFromArray($this->style_rows());
		 				 $objPHPExcel->getActiveSheet()->getStyle('L'.($rowCount))->applyFromArray($this->style_rows());
		 				 $objPHPExcel->getActiveSheet()->getStyle('M'.($rowCount))->applyFromArray($this->style_rows());
	  				//$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':E'.($rowCount))->applyFromArray($style_col);

	  		 }
	  		  	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'TOTAL');
	  		  	$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $totalpagu);
	  				$objPHPExcel->getActiveSheet()->mergeCells('A'.($rowCount).':K'.($rowCount));
	  		    $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':K'.($rowCount))->applyFromArray($this->style_column());


		  			$row = $rowCount+5;
		 				$TANGGAL=tanggal_indo(date('Y-m-d'));
		 				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, "Payakumbuh, {$TANGGAL}");
		 				$objPHPExcel->getActiveSheet()->mergeCells('L'.($row).':M'.($row));
		 				$objPHPExcel->getActiveSheet()->getStyle('L'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						foreach($nmakota as $k){
							$jabatan = $k->JABATAN;
							$namapimpinan = $k->NAMA;
							$nip = $k->NIP;
						}
		 				$row1 = $row+1;
		 				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $row1, $jabatan);
		 				$objPHPExcel->getActiveSheet()->mergeCells('L'.($row1).':M'.($row1));
		 				$objPHPExcel->getActiveSheet()->getStyle('L'.($row1).':M'.($row1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		 				$row  = $row1+5;
		 				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, $namapimpinan);
						$objPHPExcel->getActiveSheet()->mergeCells('L'.($row).':M'.($row));
						 $objPHPExcel->getActiveSheet()->getStyle('L'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$row  = $row+1;
						$objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, $nip);
		 			  $objPHPExcel->getActiveSheet()->mergeCells('L'.($row).':M'.($row));
		 		    $objPHPExcel->getActiveSheet()->getStyle('L'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


	  			// Set width kolom
	  		    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	  		    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
	  		    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	  		    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
	  		    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
		 				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
		 			  $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
		 				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
		 				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
		 				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(40);
		 				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
		 				$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		 				$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	  		    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
	  		    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-2);

	  				//Set orientasi kertas jadi LANDSCAPE
	     			$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


	  		 $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	  		 $objWriter->save($fileName);
	   // download file
	  		 header("Content-Type: application/vnd.ms-excel");
	  		 redirect(site_url().$fileName);
	   }

		 //-----------------------------------------------------------------------------------------------------
		 public function createXLSmatrik_renja_perangkat_daerah($unit='') {
			// create file name
					$fileName = 'PLAFON ANGGARAN MATRIK PERANGKAT DAERAH.xlsx';
			// load excel library
					$this->load->library('excel');
					$empInfo = $this->m_set->getRenjaAll($unit);
				$nmakota = $this->m_set->bappedadata();
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);
				foreach ($empInfo as $k){
					$aa = $k->SKPD;
				}

					// judul
					$objPHPExcel->getActiveSheet()->setCellValue('A2', "PLAFON ANGGARAN MATRIK RENJA PERANGKAT DAERAH TAHUN {$this->NMTAHUN} "); // Set kolom A1 dengan tulisan "DATA SISWA"
					$objPHPExcel->getActiveSheet()->setCellValue('A4', "Organisasi / PD : {$aa} " );
					$objPHPExcel->getActiveSheet()->mergeCells('A1:N1'); // Set Merge Cell pada kolom A1 sampai E1
					$objPHPExcel->getActiveSheet()->mergeCells('A2:N2');
					$objPHPExcel->getActiveSheet()->mergeCells('A3:N3');
					$objPHPExcel->getActiveSheet()->getStyle('A1:N8')->getFont()->setBold(TRUE); // Set bold kolom A1
					$objPHPExcel->getActiveSheet()->getStyle('A1:N6')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
					$objPHPExcel->getActiveSheet()->getStyle('A1:N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
					$objPHPExcel->getActiveSheet()->getStyle('A5:N8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
					$objPHPExcel->getActiveSheet()->getStyle('A8:N8')->getFont()->setSize(9);


					// set Header
					 $objPHPExcel->getActiveSheet()->getStyle('A5:A7')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('B5:B7')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('C5:C7')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('D5:D7')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('E5:E7')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('G7')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('I5')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('J5')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('K5')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('K7')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('L5:L7')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('M5:M7')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('N5:N7')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('F6')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('H6')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('J6')->applyFromArray($this->style_rows());

					 $objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('C8')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('D8')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('E8')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('F8')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('G8')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('H8')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('I8')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('J8')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('K8')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('L8')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('M8')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('N8')->applyFromArray($this->style_rows());

					 $objPHPExcel->getActiveSheet()->getStyle('A9')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('B9')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('C9')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('D9')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('E9')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('F9')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('G9')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('H9')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('I9')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('J9')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('K9')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('L9')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('M9')->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('N9')->applyFromArray($this->style_rows());

					 $objPHPExcel->getActiveSheet()->mergeCells('A5:A7'); // Set Merge Cell pada kolom A1 sampai E1
					 $objPHPExcel->getActiveSheet()->mergeCells('B5:B7');
					 $objPHPExcel->getActiveSheet()->mergeCells('C5:C7');
					 $objPHPExcel->getActiveSheet()->mergeCells('D5:D7');
					 $objPHPExcel->getActiveSheet()->mergeCells('E5:E7');
					 $objPHPExcel->getActiveSheet()->mergeCells('F5:K5');
					 $objPHPExcel->getActiveSheet()->mergeCells('F6:G6');
					 $objPHPExcel->getActiveSheet()->mergeCells('H6:I6');
					 $objPHPExcel->getActiveSheet()->mergeCells('J6:K6');
					 $objPHPExcel->getActiveSheet()->mergeCells('L5:L7');
					 $objPHPExcel->getActiveSheet()->mergeCells('M5:M7');
					 $objPHPExcel->getActiveSheet()->mergeCells('N5:N7');


					$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'KODE');
					$objPHPExcel->getActiveSheet()->SetCellValue('B5', 'Urusan/Bidang Urusan Pemerintah Daerah dan');
					$objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Prioritas');
					$objPHPExcel->getActiveSheet()->SetCellValue('D5', 'Sasaran');
					$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'Lokasi');
					$objPHPExcel->getActiveSheet()->SetCellValue('F5', 'Indikator Kinerja Program/Kegiatan');
					$objPHPExcel->getActiveSheet()->SetCellValue('L5', 'Pagu Indikatif');
					$objPHPExcel->getActiveSheet()->SetCellValue('M5', 'Prakiraan Maju');
					$objPHPExcel->getActiveSheet()->SetCellValue('N5', 'Jenis Kegiatan');
					$objPHPExcel->getActiveSheet()->SetCellValue('F6', 'Capaian Program (Indikator Sasaran)');
					$objPHPExcel->getActiveSheet()->SetCellValue('H6', 'Keluaran (Output)');
					$objPHPExcel->getActiveSheet()->SetCellValue('J6', 'Hasil (Outcome)');
					$objPHPExcel->getActiveSheet()->SetCellValue('F7', 'Tolak Ukur');
					$objPHPExcel->getActiveSheet()->SetCellValue('G7', 'Target');
					$objPHPExcel->getActiveSheet()->SetCellValue('H7', 'Tolak Ukur');
					$objPHPExcel->getActiveSheet()->SetCellValue('I7', 'Target');
					$objPHPExcel->getActiveSheet()->SetCellValue('J7', 'Tolak Ukur');
					$objPHPExcel->getActiveSheet()->SetCellValue('K7', 'Target');


					$objPHPExcel->getActiveSheet()->SetCellValue('A8', '1');
					$objPHPExcel->getActiveSheet()->SetCellValue('B8', '2');
					$objPHPExcel->getActiveSheet()->SetCellValue('C8', '3');
					$objPHPExcel->getActiveSheet()->SetCellValue('D8', '4');
					$objPHPExcel->getActiveSheet()->SetCellValue('E8', '5');
					$objPHPExcel->getActiveSheet()->SetCellValue('F8', '6');
					$objPHPExcel->getActiveSheet()->SetCellValue('G8', '7');
					$objPHPExcel->getActiveSheet()->SetCellValue('H8', '8');
					$objPHPExcel->getActiveSheet()->SetCellValue('I8', '9');
					$objPHPExcel->getActiveSheet()->SetCellValue('J8', '10');
					$objPHPExcel->getActiveSheet()->SetCellValue('K8', '11');
					$objPHPExcel->getActiveSheet()->SetCellValue('L8', '12');
					$objPHPExcel->getActiveSheet()->SetCellValue('M8', '13');
					$objPHPExcel->getActiveSheet()->SetCellValue('N8', '14');

					// set Row
					 $rowCount = 9;
					 $no = 1;
					 $total = 0;
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
					foreach ($empInfo as $element) {
					$KODE = $element->KODE;
					$NMUNIT = $element->SKPD;
					$pagu1= $element->PAGU1;
					$jab = $element->JAB;
					$nip = $element->NIP;
					$nama = $element->NAMA;
					$NMTAHAP = $element->NMTAHAP;
					IF ($pagu1 <= 0){
						 $NM ="-";
					 }
					 ELSE
					 {
					 $NM = $element->PAGU1;
				 }
				 $KEG= $element->SIFATKEG;
					IF ($KEG == 1)
					 {
						 $NMKEG ="Baru" ;
					 }
					 ELSEIF ($KEG == 2)
					 {
						$NMKEG ="Lanjutan" ;
					} ELSE{$NMKEG ="Rutin" ;}

					$TYPE =	$element->TYPE;
					IF ($TYPE =='H')
					{
						$detailpagu = NULL;
								}
					ELSE
					{
						$detailpagu = $element->PAGU;
					}

							$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element->KODE);
							$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element->NMPRGRM);
							$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element->PRIODAERAH);
							$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element->SASARAN);
							$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element->LOKASI);
							$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element->INDIKATOR);
							$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $element->TCPAPAIPGR);
							$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $element->KELUARAN);
							$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $element->TARGETLUAR);
							$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $element->HASIL);
							$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $element->TARGETHASIL);
							$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $element->PAGU);
							$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $NM);
							$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $NMKEG);


							$totalpagu +=  $detailpagu;
							$rowCount++;
							$no++;

							$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
							$objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
							$objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
							$objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
							$objPHPExcel->getActiveSheet()->getStyle('E'.($rowCount))->applyFromArray($this->style_rows());
							$objPHPExcel->getActiveSheet()->getStyle('F'.($rowCount))->applyFromArray($this->style_rows());
							$objPHPExcel->getActiveSheet()->getStyle('G'.($rowCount))->applyFromArray($this->style_rows());
							$objPHPExcel->getActiveSheet()->getStyle('H'.($rowCount))->applyFromArray($this->style_rows());
							$objPHPExcel->getActiveSheet()->getStyle('I'.($rowCount))->applyFromArray($this->style_rows());
							$objPHPExcel->getActiveSheet()->getStyle('J'.($rowCount))->applyFromArray($this->style_rows());
							$objPHPExcel->getActiveSheet()->getStyle('K'.($rowCount))->applyFromArray($this->style_rows());
							$objPHPExcel->getActiveSheet()->getStyle('L'.($rowCount))->applyFromArray($this->style_rows());
							$objPHPExcel->getActiveSheet()->getStyle('M'.($rowCount))->applyFromArray($this->style_rows());
							$objPHPExcel->getActiveSheet()->getStyle('N'.($rowCount))->applyFromArray($this->style_rows());
						 //$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':E'.($rowCount))->applyFromArray($style_col);

						}
							 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'TOTAL');
							 $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $totalpagu);
							 $objPHPExcel->getActiveSheet()->mergeCells('A'.($rowCount).':K'.($rowCount));
							 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':K'.($rowCount))->applyFromArray($this->style_column());


							 $row = $rowCount+5;
							 $TANGGAL=tanggal_indo(date('Y-m-d'));
							 $objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, "Payakumbuh, {$TANGGAL}");
							 $objPHPExcel->getActiveSheet()->mergeCells('L'.($row).':M'.($row));
							 $objPHPExcel->getActiveSheet()->getStyle('L'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							 foreach($nmakota as $k){
								 $jab = $k->JABATAN;
								 $nama = $k->NAMA;
								 $nip = $k->NIP;
							 }
							 $row1 = $row+1;
							 $objPHPExcel->getActiveSheet()->SetCellValue('L' . $row1, $jab);
							 $objPHPExcel->getActiveSheet()->mergeCells('L'.($row1).':M'.($row1));
							 $objPHPExcel->getActiveSheet()->getStyle('L'.($row1).':M'.($row1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							 $row  = $row1+5;
							 $objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, $nama);
							 $objPHPExcel->getActiveSheet()->mergeCells('L'.($row).':M'.($row));
								$objPHPExcel->getActiveSheet()->getStyle('L'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							 $row  = $row+1;
							 $objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, $nip);
							 $objPHPExcel->getActiveSheet()->mergeCells('L'.($row).':M'.($row));
							 $objPHPExcel->getActiveSheet()->getStyle('L'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


					 // Set width kolom
							 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
							 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
							 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
							 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
							 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
							 $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
							 $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
							 $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
							 $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
							 $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(40);
							 $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
							 $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
							 $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
							 $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
							// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
							$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-2);

							// Set orientasi kertas jadi LANDSCAPE
							//  $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


							$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
							$objWriter->save($fileName);
							// download file
							header("Content-Type: application/vnd.ms-excel");
							redirect(site_url().$fileName);
		}

//============================================================================================================
public function createXLS_rekapurusan() {
 // create file name
		$fileName = 'TABEL 4.1 MENURUT URUSAN.xlsx';
 // load excel library
		$this->load->library('excel');
		$empInfo = $this->m_set->getUrusanAll();
		$nmakota = $this->m_set->bappedadata();
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);


		// judul
		$objPHPExcel->getActiveSheet()->setCellValue('A1', "TABEL 4.1 "); // Set kolom A1 dengan tulisan "DATA SISWA"
		$objPHPExcel->getActiveSheet()->setCellValue('A2', "PLAFON ANGGARAN SEMENTARA BERDASARKAN URUSAN PEMERINTAHAN TAHUN {$this->NMTAHUN}");
		$objPHPExcel->getActiveSheet()->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
		$objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
		$objPHPExcel->getActiveSheet()->getStyle('A1:E6')->getFont()->setBold(TRUE); // Set bold kolom A1
		$objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
		$objPHPExcel->getActiveSheet()->getStyle('A1:E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
		$objPHPExcel->getActiveSheet()->getStyle('A6:E6')->getFont()->setSize(9);


		// set Header
		 $objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('D6')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('E6')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($this->style_rows());
		 $objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($this->style_rows());

		$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'No');
		$objPHPExcel->getActiveSheet()->SetCellValue('B5', 'KODE');
		$objPHPExcel->getActiveSheet()->SetCellValue('C5', 'URUSAN');
		$objPHPExcel->getActiveSheet()->SetCellValue('D5', 'SKPD');
		$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'PAGU');

		$objPHPExcel->getActiveSheet()->SetCellValue('A6', '1');
		$objPHPExcel->getActiveSheet()->SetCellValue('B6', '2');
		$objPHPExcel->getActiveSheet()->SetCellValue('C6', '3');
		$objPHPExcel->getActiveSheet()->SetCellValue('D6', '4');
		$objPHPExcel->getActiveSheet()->SetCellValue('E6', '5');
		// set Row
		$rowCount = 7;
		$total = 0;
		$no = 1;
		$pagusisa = 0;
		$totalpagu = 0;
		$NM = NULL;
		$NAMA = NULL;
		$TYPE = NULL;
		$PAGU1=NULL;
		$PAGU2=NULL;
		foreach ($empInfo as $element) {
			$TYPE =	$element->TYPE;
			IF ($TYPE =='H')
			{
				$NM = $element->NMPRGRM;
				$PAGU2 = $element->PAGU;
				$NAMA =NULL;
				$PAGU1= NULL;

			}
			ELSE
			{
				$NAMA = $element->NMPRGRM;
				$PAGU1 = $element->PAGU;
				$NM = NULL;
				$PAGU2= NULL;
			}

				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $no );
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element->KODE);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $NM);
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $NAMA);
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element->PAGU);
				$rowCount++;
				$no++;
				$total += $PAGU1;
				$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
				$objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
				$objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
				$objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
				$objPHPExcel->getActiveSheet()->getStyle('E'.($rowCount))->applyFromArray($this->style_rows());
			 //$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':E'.($rowCount))->applyFromArray($style_col);

				}
				 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'TOTAL');
				 $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $total);
				 $objPHPExcel->getActiveSheet()->mergeCells('A'.($rowCount).':D'.($rowCount));
					$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':D'.($rowCount))->applyFromArray($this->style_column());

					$row = $rowCount+5;
					$TANGGAL=tanggal_indo(date('Y-m-d'));
					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, "Payakumbuh, {$TANGGAL}");
					//$objPHPExcel->getActiveSheet()->mergeCells('C'.($row).':D'.($row));
					$objPHPExcel->getActiveSheet()->getStyle('D'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					foreach($nmakota as $k){
					 $jab = $k->JABATAN;
					 $nama = $k->NAMA;
					 $nip = $k->NIP;
				 }
				 $row1 = $row+1;
				 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $row1, $jab);
				 $objPHPExcel->getActiveSheet()->mergeCells('D'.($row1).':M'.($row1));
				 $objPHPExcel->getActiveSheet()->getStyle('D'.($row1).':M'.($row1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				 $row  = $row1+5;
				 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $nama);
				 $objPHPExcel->getActiveSheet()->mergeCells('D'.($row).':M'.($row));
					$objPHPExcel->getActiveSheet()->getStyle('D'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				 $row  = $row+1;
				 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $nip);
				 $objPHPExcel->getActiveSheet()->mergeCells('D'.($row).':M'.($row));
				 $objPHPExcel->getActiveSheet()->getStyle('D'.($row).':M'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



				// Set width kolom
				 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
				 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30); // Set width kolom B
				 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50); // Set width kolom C
				 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(80); // Set width kolom D
				 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20); // Set width kolom E
				 // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
				 $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

				 // Set orientasi kertas jadi LANDSCAPE
				//  $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				$objWriter->save($fileName);
				// download file
				header("Content-Type: application/vnd.ms-excel");
				redirect(site_url().$fileName);
 }

 //-------------------------------------------------------------------------------------------

 public function createXLSpagu_perangkatdaerah() {
  // create file name
 		 $fileName = 'REKAPITULASI RENCANA KERJA PERANGKAT DAERAH-'.time().'.xlsx';
  // load excel library
 		 $this->load->library('excel');
 		 $empInfo = $this->m_set->paguSKPD();
		 	$nmakota = $this->m_set->bappedadata();
 		 $objPHPExcel = new PHPExcel();
 		 $objPHPExcel->setActiveSheetIndex(0);


 		 // judul
 		 $objPHPExcel->getActiveSheet()->setCellValue('A1', "Tabel 4.2"); // Set kolom A1 dengan tulisan "DATA SISWA"
 		 $objPHPExcel->getActiveSheet()->setCellValue('A2', "PLAFON ANGGARAN SEMENTARA PAGU PERANGKAT DAERAH TAHUN {$this->NMTAHUN}");
 		 $objPHPExcel->getActiveSheet()->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
 		 $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
 		 $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
 		 $objPHPExcel->getActiveSheet()->getStyle('A1:E6')->getFont()->setBold(TRUE); // Set bold kolom A1
 		 $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
 		 $objPHPExcel->getActiveSheet()->getStyle('A1:E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
 		 $objPHPExcel->getActiveSheet()->getStyle('A6:E6')->getFont()->setSize(9);


 		 // set Header
 		  $objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($this->style_rows());
 		  $objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($this->style_rows());
 		  $objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($this->style_rows());
 		  $objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($this->style_rows());
 		  $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('D6')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($this->style_rows());
 			$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($this->style_rows());


 		 $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'No');
 		 $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'KODE');
 		 $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'PERANGKAT DAERAH');
 		 $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'PAGU');

 		 $objPHPExcel->getActiveSheet()->SetCellValue('A6', '1');
 		$objPHPExcel->getActiveSheet()->SetCellValue('B6', '2');
 		$objPHPExcel->getActiveSheet()->SetCellValue('C6', '3');
 		$objPHPExcel->getActiveSheet()->SetCellValue('D6', '4');
 		 // set Row
 		 $rowCount = 7;
 		 	$no = 1;
 			$pagusisa0 = 0;
 			$totalpagu = 0;
 			$totalpagudigunakan = 0;
 			$totalpagusisa = 0;
 		 foreach ($empInfo as $element) {

 				 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $no );
 				 $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['KDUNIT']);
 				 $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['NMUNIT']);
 				 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['PAGU']);
 				 $rowCount++;
 			   $no++;
 				 $totalpagu += $element['PAGU'];

 				 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
 				 $objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
 				 $objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
 				 $objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
 				//$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':E'.($rowCount))->applyFromArray($style_col);

 		 }
 		  $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'TOTAL');
 		  $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $totalpagu);
 		  $objPHPExcel->getActiveSheet()->mergeCells('A'.($rowCount).':C'.($rowCount));
 			 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':C'.($rowCount))->applyFromArray($this->style_column());


			 			$row = $rowCount+5;
			 			$TANGGAL=tanggal_indo(date('Y-m-d'));
			 			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, "Payakumbuh, {$TANGGAL}");
			 			//$objPHPExcel->getActiveSheet()->mergeCells('C'.($row).':D'.($row));
			 			$objPHPExcel->getActiveSheet()->getStyle('D'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			 			foreach($nmakota as $k){
			  			 $jab = $k->JABATAN;
			  			 $nama = $k->NAMA;
			  			 $nip = $k->NIP;
			  		 }
			  		 $row1 = $row+1;
			  		 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $row1, $jab);
			  		 $objPHPExcel->getActiveSheet()->getStyle('D'.($row1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			  		 $row  = $row1+5;
			  		 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $nama);
			  			$objPHPExcel->getActiveSheet()->getStyle('D'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			  		 $row  = $row+1;
			  		 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $nip);
			  		 $objPHPExcel->getActiveSheet()->getStyle('D'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

 			// Set width kolom
 		    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
 		    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15); // Set width kolom B
 		    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40); // Set width kolom C
 		    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D
 		    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
 		    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

 				// Set orientasi kertas jadi LANDSCAPE
   //  $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


 		 $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
 		 $objWriter->save($fileName);
  // download file
 		 header("Content-Type: application/vnd.ms-excel");
 		 redirect(site_url().$fileName);
  }

  //-------------------------------------------------------------------------------------------

	public function createXLSmatrik43($unit='') {
	 // create file name
			 $fileName = 'TABEL 43 PPAS-'.time().'.xlsx';
	 // load excel library
			 $this->load->library('excel');
			 $empInfo = $this->m_set->getRenjaAll($unit);
		   $nmakota = $this->m_set->bappedadata();
			 $objPHPExcel = new PHPExcel();
			 $objPHPExcel->setActiveSheetIndex(0);
					 // judul
			 $objPHPExcel->getActiveSheet()->setCellValue('A1', "TABEL 4.3"); // Set kolom A1 dengan tulisan "DATA SISWA"
			 $objPHPExcel->getActiveSheet()->setCellValue('A2', "TAHUN {$this->NMTAHUN} ");
			 $objPHPExcel->getActiveSheet()->mergeCells('A1:K1'); // Set Merge Cell pada kolom A1 sampai E1
			 $objPHPExcel->getActiveSheet()->mergeCells('A2:K2');
			 $objPHPExcel->getActiveSheet()->mergeCells('A3:K3');
			 $objPHPExcel->getActiveSheet()->getStyle('A1:K8')->getFont()->setBold(TRUE); // Set bold kolom A1
			 $objPHPExcel->getActiveSheet()->getStyle('A1:K6')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
			 $objPHPExcel->getActiveSheet()->getStyle('A1:K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
			 $objPHPExcel->getActiveSheet()->getStyle('A5:K8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
			 $objPHPExcel->getActiveSheet()->getStyle('A8:K8')->getFont()->setSize(9);


			 // set Header
	 				$objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('I5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('J5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('K5')->applyFromArray($this->style_rows());

	 				$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('F7')->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('G7')->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('H7')->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('I7')->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('J7')->applyFromArray($this->style_rows());
	 		  	$objPHPExcel->getActiveSheet()->getStyle('F6')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('H6')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('J6')->applyFromArray($this->style_rows());

	 			$objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('C8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('D8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('E8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('F8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('G8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('H8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('I8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('J8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('K8')->applyFromArray($this->style_rows());


	 			$objPHPExcel->getActiveSheet()->getStyle('A9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('B9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('C9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('D9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('E9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('F9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('G9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('H9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('I9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('J9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('K9')->applyFromArray($this->style_rows());

	 			$objPHPExcel->getActiveSheet()->mergeCells('A5:A7'); // Set Merge Cell pada kolom A1 sampai E1
	 			$objPHPExcel->getActiveSheet()->mergeCells('B5:B7');
	 			$objPHPExcel->getActiveSheet()->mergeCells('C5:C7');
	 			$objPHPExcel->getActiveSheet()->mergeCells('D5:D7');
	 			$objPHPExcel->getActiveSheet()->mergeCells('E5:J5');
	 			$objPHPExcel->getActiveSheet()->mergeCells('E6:F6');
	 			$objPHPExcel->getActiveSheet()->mergeCells('G6:H6');
	 			$objPHPExcel->getActiveSheet()->mergeCells('I6:J6');
	 			$objPHPExcel->getActiveSheet()->mergeCells('K5:K7');

				$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'PERANGKAT DAERAH');
				 $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'NO');
				 $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Urusan/Bidang Urusan Pemerintah Daerah dan');
				 $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'Sumber Dana');
				 $objPHPExcel->getActiveSheet()->SetCellValue('E5', 'Indikator Kinerja Program/Kegiatan');
				 $objPHPExcel->getActiveSheet()->SetCellValue('K5', 'Nilai');
				 $objPHPExcel->getActiveSheet()->SetCellValue('E6', 'Capaian Program (Indikator Sasaran)');
				 $objPHPExcel->getActiveSheet()->SetCellValue('G6', 'Keluaran (Output)');
				 $objPHPExcel->getActiveSheet()->SetCellValue('I6', 'Hasil (Outcome)');
				 $objPHPExcel->getActiveSheet()->SetCellValue('E7', 'Tolak Ukur');
				 $objPHPExcel->getActiveSheet()->SetCellValue('F7', 'Target');
				 $objPHPExcel->getActiveSheet()->SetCellValue('G7', 'Tolak Ukur');
				 $objPHPExcel->getActiveSheet()->SetCellValue('H7', 'Target');
				 $objPHPExcel->getActiveSheet()->SetCellValue('I7', 'Tolak Ukur');
				 $objPHPExcel->getActiveSheet()->SetCellValue('J7', 'Target');

				 $objPHPExcel->getActiveSheet()->SetCellValue('A8', '1');
				 $objPHPExcel->getActiveSheet()->SetCellValue('B8', '2');
				 $objPHPExcel->getActiveSheet()->SetCellValue('C8', '3');
				 $objPHPExcel->getActiveSheet()->SetCellValue('D8', '4');
				 $objPHPExcel->getActiveSheet()->SetCellValue('E8', '5');
				 $objPHPExcel->getActiveSheet()->SetCellValue('F8', '6');
				 $objPHPExcel->getActiveSheet()->SetCellValue('G8', '7');
				 $objPHPExcel->getActiveSheet()->SetCellValue('H8', '8');
				 $objPHPExcel->getActiveSheet()->SetCellValue('I8', '9');
				 $objPHPExcel->getActiveSheet()->SetCellValue('J8', '10');
				 $objPHPExcel->getActiveSheet()->SetCellValue('K8', '11');

				 // set Row
				$rowCount = 9;
				$no = 1;
			 $totalpagu = 0;
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
				foreach ($empInfo as $element) {
				$NMUNIT = $element->SKPD;
				$pagu1= $element->PAGU1;
				$jab = $element->JAB;
				$nip = $element->NIP;
				$nama = $element->NAMA;
				$NMTAHAP = $element->NMTAHAP;
				IF ($pagu1 <= 0){
					 $NM ="-" ;
				  }
				 ELSE
				 {
					 $NM = $element->PAGU1;
				 }
				$TYPE =	$element->TYPE;
				IF ($TYPE =='H')
				{
					$detailpagu = NULL;
							}
				ELSE
				{
					$detailpagu = $element->PAGU;
				}

					$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element->SKPD);
					$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element->KODE);
					$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element->NMPRGRM);
					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element->SBDANA);
					$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element->INDIKATOR);
					$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element->TCPAPAIPGR);
					$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $element->KELUARAN);
					$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $element->TARGETLUAR);
					$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $element->OUTCOMETOLOKUR);
					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $element->OUTCOMETARGET);
					$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $element->PAGU);

					 $totalpagu +=  $detailpagu;
					 $rowCount++;
					 $no++;

					 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('E'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('F'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('G'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('H'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('I'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('J'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('K'.($rowCount))->applyFromArray($this->style_rows());

 			 }
			 		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'TOTAL');
					$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $totalpagu);
					$objPHPExcel->getActiveSheet()->mergeCells('A'.($rowCount).':J'.($rowCount));
					$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':J'.($rowCount))->applyFromArray($this->style_column());

					$row = $rowCount+5;
					$TANGGAL=tanggal_indo(date('Y-m-d'));
					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $row, "Payakumbuh, {$TANGGAL}");
					$objPHPExcel->getActiveSheet()->mergeCells('J'.($row).':K'.($row));
					$objPHPExcel->getActiveSheet()->getStyle('J'.($row).':K'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					foreach($nmakota as $k){
						$jab = $k->JABATAN;
						$nama = $k->NAMA;
						$nip = $k->NIP;
					}
					$row1 = $row+1;
					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $row1, $jab);
					$objPHPExcel->getActiveSheet()->mergeCells('J'.($row1).':K'.($row1));
					$objPHPExcel->getActiveSheet()->getStyle('J'.($row1).':K'.($row1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$row  = $row1+5;
					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $row, $nama);
					$objPHPExcel->getActiveSheet()->mergeCells('J'.($row).':K'.($row));
					 $objPHPExcel->getActiveSheet()->getStyle('J'.($row).':K'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$row  = $row+1;
					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $row, $nip);
					$objPHPExcel->getActiveSheet()->mergeCells('J'.($row).':K'.($row));
					$objPHPExcel->getActiveSheet()->getStyle('J'.($row).':K'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
						$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
						$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
						$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
						$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
						$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
						$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
						$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);


			 $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			 $objWriter->save($fileName);
	 // download file
			 header("Content-Type: application/vnd.ms-excel");
			 redirect(site_url().$fileName);

 }

//============================================================================================================


	public function createXLSmatrik_renja_per_urusan() {
	 // create file name
			 $fileName = 'TABEL 43 PLAFON ANGGARAN SEMENTARA BERDASARKAN PROGRAM DAN KEGIATAN -'.time().'.xlsx';
	 // load excel library
			 $this->load->library('excel');
			 $empInfo = $this->m_set->getMatrikRenjaUrusanAll();
		   $nmakota = $this->m_set->bappedadata();
			 $objPHPExcel = new PHPExcel();
			 $objPHPExcel->setActiveSheetIndex(0);
					 // judul
			 $objPHPExcel->getActiveSheet()->setCellValue('A1', "TABEL 4.3"); // Set kolom A1 dengan tulisan "DATA SISWA"
			 $objPHPExcel->getActiveSheet()->setCellValue('A2', "PLAFON ANGGARAN SEMENTARA BERDASARKAN PROGRAM DAN KEGIATAN");
			 $objPHPExcel->getActiveSheet()->setCellValue('A3', "TAHUN ANGGARAN  {$this->NMTAHUN} ");
			 $objPHPExcel->getActiveSheet()->mergeCells('A1:K1'); // Set Merge Cell pada kolom A1 sampai E1
			 $objPHPExcel->getActiveSheet()->mergeCells('A2:K2');
			 $objPHPExcel->getActiveSheet()->mergeCells('A3:K3');
			 $objPHPExcel->getActiveSheet()->getStyle('A1:K8')->getFont()->setBold(TRUE); // Set bold kolom A1
			 $objPHPExcel->getActiveSheet()->getStyle('A1:K6')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
			 $objPHPExcel->getActiveSheet()->getStyle('A1:K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
			 $objPHPExcel->getActiveSheet()->getStyle('A5:K8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
			 $objPHPExcel->getActiveSheet()->getStyle('A8:K8')->getFont()->setSize(9);


			 // set Header
	 				$objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('I5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('J5')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('K5')->applyFromArray($this->style_rows());

	 				$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('F7')->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('G7')->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('H7')->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('I7')->applyFromArray($this->style_rows());
					$objPHPExcel->getActiveSheet()->getStyle('J7')->applyFromArray($this->style_rows());
	 		  	$objPHPExcel->getActiveSheet()->getStyle('F6')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('H6')->applyFromArray($this->style_rows());
	 				$objPHPExcel->getActiveSheet()->getStyle('J6')->applyFromArray($this->style_rows());

	 			$objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('C8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('D8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('E8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('F8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('G8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('H8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('I8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('J8')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('K8')->applyFromArray($this->style_rows());


	 			$objPHPExcel->getActiveSheet()->getStyle('A9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('B9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('C9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('D9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('E9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('F9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('G9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('H9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('I9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('J9')->applyFromArray($this->style_rows());
	 			$objPHPExcel->getActiveSheet()->getStyle('K9')->applyFromArray($this->style_rows());

	 			$objPHPExcel->getActiveSheet()->mergeCells('A5:A7'); // Set Merge Cell pada kolom A1 sampai E1
	 			$objPHPExcel->getActiveSheet()->mergeCells('B5:B7');
	 			$objPHPExcel->getActiveSheet()->mergeCells('C5:C7');
	 			$objPHPExcel->getActiveSheet()->mergeCells('D5:D7');
	 			$objPHPExcel->getActiveSheet()->mergeCells('E5:J5');
	 			$objPHPExcel->getActiveSheet()->mergeCells('E6:F6');
	 			$objPHPExcel->getActiveSheet()->mergeCells('G6:H6');
	 			$objPHPExcel->getActiveSheet()->mergeCells('I6:J6');
	 			$objPHPExcel->getActiveSheet()->mergeCells('K5:K7');

				$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'PERANGKAT DAERAH');
				 $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'NO');
				 $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Urusan Pemerintah Daerah / Program / Kegiatan ');
				 $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'Sumber Dana');
				 $objPHPExcel->getActiveSheet()->SetCellValue('E5', 'Indikator Kinerja');
				 $objPHPExcel->getActiveSheet()->SetCellValue('K5', 'Nilai');
				 $objPHPExcel->getActiveSheet()->SetCellValue('E6', 'Capaian Program');
				 $objPHPExcel->getActiveSheet()->SetCellValue('G6', 'Keluaran Kegiatan');
				 $objPHPExcel->getActiveSheet()->SetCellValue('I6', 'Hasil Kegiatan');
				 $objPHPExcel->getActiveSheet()->SetCellValue('E7', 'Tolak Ukur');
				 $objPHPExcel->getActiveSheet()->SetCellValue('F7', 'Target');
				 $objPHPExcel->getActiveSheet()->SetCellValue('G7', 'Tolak Ukur');
				 $objPHPExcel->getActiveSheet()->SetCellValue('H7', 'Target');
				 $objPHPExcel->getActiveSheet()->SetCellValue('I7', 'Tolak Ukur');
				 $objPHPExcel->getActiveSheet()->SetCellValue('J7', 'Target');

				 $objPHPExcel->getActiveSheet()->SetCellValue('A8', '1');
				 $objPHPExcel->getActiveSheet()->SetCellValue('B8', '2');
				 $objPHPExcel->getActiveSheet()->SetCellValue('C8', '3');
				 $objPHPExcel->getActiveSheet()->SetCellValue('D8', '4');
				 $objPHPExcel->getActiveSheet()->SetCellValue('E8', '5');
				 $objPHPExcel->getActiveSheet()->SetCellValue('F8', '6');
				 $objPHPExcel->getActiveSheet()->SetCellValue('G8', '7');
				 $objPHPExcel->getActiveSheet()->SetCellValue('H8', '8');
				 $objPHPExcel->getActiveSheet()->SetCellValue('I8', '9');
				 $objPHPExcel->getActiveSheet()->SetCellValue('J8', '10');
				 $objPHPExcel->getActiveSheet()->SetCellValue('K8', '11');

				 // set Row
				$rowCount = 9;
				$no = 1;
			 $totalpagu = 0;
			 $NMUNIT = NULL;
				foreach ($empInfo as $element) {
				$NMUNIT = $element->SKPD;

					$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $NMUNIT);
					$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element->KODE);
					$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element->NMPRGRM);
					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element->NMDANA);
					$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element->INDIKATOR);
					$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element->TCPAPAIPGR);
					$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $element->KELUARAN);
					$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $element->TARGETLUAR1);
					$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $element->HASIL);
					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $element->TARGETHASIL);
					$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $element->PAGU);

					 $totalpagu +=  $element->PAGU;
					 $rowCount++;
					 $no++;

					 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('E'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('F'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('G'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('H'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('I'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('J'.($rowCount))->applyFromArray($this->style_rows());
					 $objPHPExcel->getActiveSheet()->getStyle('K'.($rowCount))->applyFromArray($this->style_rows());

 			 }
			 		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'TOTAL');
					$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $totalpagu);
					$objPHPExcel->getActiveSheet()->mergeCells('A'.($rowCount).':J'.($rowCount));
					$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':J'.($rowCount))->applyFromArray($this->style_column());

					$row = $rowCount+5;
					$TANGGAL=tanggal_indo(date('Y-m-d'));
					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $row, "Payakumbuh, {$TANGGAL}");
					$objPHPExcel->getActiveSheet()->mergeCells('J'.($row).':K'.($row));
					$objPHPExcel->getActiveSheet()->getStyle('J'.($row).':K'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					foreach($nmakota as $k){
						$jab = $k->JABATAN;
						$nama = $k->NAMA;
						$nip = $k->NIP;
					}
					$row1 = $row+1;
					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $row1, $jab);
					$objPHPExcel->getActiveSheet()->mergeCells('J'.($row1).':K'.($row1));
					$objPHPExcel->getActiveSheet()->getStyle('J'.($row1).':K'.($row1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$row  = $row1+5;
					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $row, $nama);
					$objPHPExcel->getActiveSheet()->mergeCells('J'.($row).':K'.($row));
					$objPHPExcel->getActiveSheet()->getStyle('J'.($row).':K'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$row  = $row+1;
					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $row, $nip);
					$objPHPExcel->getActiveSheet()->mergeCells('J'.($row).':K'.($row));
					$objPHPExcel->getActiveSheet()->getStyle('J'.($row).':K'.($row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
						$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
						$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
						$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
						$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
						$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
						$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
						$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);


			 $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			 $objWriter->save($fileName);
	 // download file
			 header("Content-Type: application/vnd.ms-excel");
			 redirect(site_url().$fileName);

 }
 //==========================================================================================================


 public function createXLSprarka($id='', $kegrkpdkey='') {
		// create file name
		$fileName = 'PRA RKA -'.time().'.xlsx';
		// load excel library
		$this->load->library('excel');
		$empInfo = $this->m_set->getPraRKAAll($id);
		$data ['getUnitName'] = $this->m_user->getUnitName($id);
 		$data ['getKegiatanName'] = $this->m_kegiatan->getKegiatanName($kegrkpdkey);
 		$data ['getProgramReport'] = $this->m_set->getProgramReport($kegrkpdkey);
 		$data ['getTOLAKUKUR'] = $this->m_set->getTOLAKUKUR($kegrkpdkey, $id);
 		$data ['getDetailPraRKA'] = $this->m_set->getDetailPraRKA($id, $kegrkpdkey);
 		$data ['getTotalPrarka'] = $this->m_set->getTotalPrarka($id, $kegrkpdkey);
 		$data ['getLokasi'] = $this->m_set->getLokasi($id, $kegrkpdkey);
		$data ['getDana'] = $this->m_set->cDana($id, $kegrkpdkey);
			$pagumin = $this->m_set->getRPagu($id, $kegrkpdkey);
			if(substr($pagumin,0,4) == "Rp. ") {
					$pagumin1 = substr($pagumin,4);
					$pagumins = substr($pagumin1,-3,1);
					if ($pagumins == ","){
							$pagumin2 = substr($pagumin1,0,-3);
							$pagumin3	= preg_replace('/[^A-Za-z0-9]/', '', $pagumin2);
							$data ['getRPagu'] = $pagumin1;
							$data ['getRPaguterbilang'] = terbilang($pagumin3);
					}else {
						$pagumin2 = $pagumin1;
						$pagumin3	= preg_replace('/[^A-Za-z0-9]/', '', $pagumin2);
						$data ['getRPagu'] = $pagumin1.",00";
						$data ['getRPaguterbilang'] = terbilang($pagumin3);
					}
			}else {
				$pagumin1 = $pagumin;
				$pagumin2 = substr($pagumin1,0,-3);
				$pagumin3	= preg_replace('/[^A-Za-z0-9]/', '', $pagumin2);
				if (is_numeric($pagumin3)== TRUE ){
				$pagumin1 = $pagumin;
				$pagumins = substr($pagumin1,-3,1);
				if ($pagumins == ","){
						$pagumin2 = substr($pagumin1,0,-3);
						$pagumin3	= preg_replace('/[^A-Za-z0-9]/', '', $pagumin2);
						$data ['getRPagu'] = $pagumin1;
						$data ['getRPaguterbilang'] = terbilang($pagumin3);
				}else {
					$pagumin2 = $pagumin1;
					$pagumin3	= preg_replace('/[^A-Za-z0-9]/', '', $pagumin2);
					$data ['getRPagu'] = $pagumin1.",00";
					$data ['getRPaguterbilang'] = terbilang($pagumin3);
				}
				} else {
				?>
			 <script type="text/javascript">
			 var question = confirm("Khusus untuk masukan pada Kinerja Kegiatan harus diisi dengan Angka.");
					if (question === true) {
							window.close();
					} else {
						 window.close();
					}
			 </script>
	 <?php

			}
		}
 		$aa = $data ['getTotalPrarka'];
 		foreach ($aa as $row){ $tot =  $row->total;	}
 		$data ['paguterbilang'] = terbilang($tot);
 		$bb = $data ['getLokasi'];
 		foreach ($bb as $row)
 		{ $total1 =  $row->PAGUPLUS;
 			$data ['paguplusterbilang'] = terbilang($total1);
 		}

 		$nmakota = $this->m_set->bappedadata();
 		$objPHPExcel = new PHPExcel();
 		$objPHPExcel->setActiveSheetIndex(0);

		  		 // judul

		$objPHPExcel->getActiveSheet()->setCellValue('A1', "DOKUMEN RENCANA KERJA ANGGARAN "); // Set kolom A1 dengan tulisan "DATA SISWA"
		$objPHPExcel->getActiveSheet()->setCellValue('A2', "PERANGKAT DAERAH");
		$objPHPExcel->getActiveSheet()->setCellValue('A3', "PEMERINTAH KOTA PAYAKUMBUH");
		$objPHPExcel->getActiveSheet()->setCellValue('A3', "TAHUN ANGGARAN {$this->NMTAHUN}");
		$objPHPExcel->getActiveSheet()->setCellValue('F1', "Formulir"); // Set kolom A1 dengan tulisan "DATA SISWA"
		$objPHPExcel->getActiveSheet()->setCellValue('F2', "RKA - PD 2.2.1");
		$objPHPExcel->getActiveSheet()->setCellValue('F3', "(Pra - RKA)");
		$objPHPExcel->getActiveSheet()->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
		$objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getFont()->setBold(TRUE); // Set bold kolom A1
		$objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
		$objPHPExcel->getActiveSheet()->getStyle('A1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

		$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'Urusan');
		$objPHPExcel->getActiveSheet()->SetCellValue('B5', $empInfo);

		$objPHPExcel->getActiveSheet()->SetCellValue('A6', 'Organisasi');
		$objPHPExcel->getActiveSheet()->SetCellValue('B6',  $data ['getUnitName']);

		$nuprgrm = null;
		$nmprgrm = null;
		$pr = new stdClass();
		foreach($data ['getProgramReport'] as $pr){
			 $nuprgrm = $pr->NUPRGRM;
			 $nmprgrm = $nuprgrm . $pr->NMPRGRM;
			 $objPHPExcel->getActiveSheet()->SetCellValue('A7', 'Program');
			 $objPHPExcel->getActiveSheet()->SetCellValue('B7', $nmprgrm );
			 $objPHPExcel->getActiveSheet()->SetCellValue('A8', 'Kegiatan');
			 $objPHPExcel->getActiveSheet()->SetCellValue('B8', $pr->NUKEG . 	$data ['getKegiatanName'] );
		}

		$lokasi = null;
		$LL = new stdClass();
		foreach($data ['getLokasi']  as $LL){ $lokasi = $LL->LOKASI;
			$PAGUPLUS = number_format($LL->PAGUPLUS, 2, ',', '.');
		}

		$objPHPExcel->getActiveSheet()->SetCellValue('A9', 'Lokasi Kegiatan');
		$objPHPExcel->getActiveSheet()->SetCellValue('B9', $lokasi);

		$getRPagu = number_format($data ['getRPagu'], 2, ',', '.');
		$objPHPExcel->getActiveSheet()->SetCellValue('A10', 'Jumlah Tahun n-1');
		$objPHPExcel->getActiveSheet()->SetCellValue('B10', 'Rp.' . $getRPagu .'(' . 	$data ['getRPaguterbilang'] . ' rupiah )' );

		$ttl = null;
		$tp = new stdClass();
		foreach($data ['getTotalPrarka'] as $tp){
					$ttl = number_format($tp->total, 2, ',', '.');
		}


		$objPHPExcel->getActiveSheet()->SetCellValue('A11', 'Jumlah Tahun n');
		$objPHPExcel->getActiveSheet()->SetCellValue('B11', 'Rp.' . $ttl .'(' . $data ['paguterbilang'] . ' rupiah )' );

		$objPHPExcel->getActiveSheet()->SetCellValue('A12', 'Jumlah Tahun n+1');
		$objPHPExcel->getActiveSheet()->SetCellValue('B12', 'Rp.' . $PAGUPLUS .'(' . $data ['paguplusterbilang'] . ' rupiah )' );

		$objPHPExcel->getActiveSheet()->SetCellValue('A14', 'Indikator & Tolok Ukur Kinerja Belanja Langsung');
		$objPHPExcel->getActiveSheet()->mergeCells('A14:F14');

		$objPHPExcel->getActiveSheet()->SetCellValue('A15', 'Indikator');
		$objPHPExcel->getActiveSheet()->mergeCells('A15:B15');
		$objPHPExcel->getActiveSheet()->SetCellValue('C15', 'Tolok Ukur');
		$objPHPExcel->getActiveSheet()->mergeCells('C15:D15');
		$objPHPExcel->getActiveSheet()->SetCellValue('E15', 'Target Kinerja');
		$objPHPExcel->getActiveSheet()->mergeCells('E15:F15');

		$objPHPExcel->getActiveSheet()->getStyle('A14:F15')->getFont()->setBold(TRUE); // Set bold kolom A1
		$objPHPExcel->getActiveSheet()->getStyle('A14:F15')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
		$objPHPExcel->getActiveSheet()->getStyle('A14:F15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1


		$rowCount = 16;
		$jkk = null;
		$urjkk = null;
		$tolokur = null;
		$target = null;
		 $urjkk1 = null;
		$tolokur1 = null;
		$target1 = null;
		foreach($data ['getTOLAKUKUR'] as $getTOLAKUKUR){
			$jkk = $getTOLAKUKUR->KDJKK;
			if ($jkk =='04') {
					$urjkk1 = $getTOLAKUKUR ->URJKK;
					$tolokur1 = $getTOLAKUKUR->TOLOKUR;
					$target1 = $getTOLAKUKUR->TARGET;


				} else {
					$urjkk = $getTOLAKUKUR ->URJKK;
					$tolokur = $getTOLAKUKUR->TOLOKUR;
					$target = $getTOLAKUKUR->TARGET;

					$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $urjkk);
					$objPHPExcel->getActiveSheet()->mergeCells('A'.($rowCount).':B'.($rowCount));
					$objPHPExcel->getActiveSheet()->SetCellValue('C' .$rowCount, $tolokur);
					$objPHPExcel->getActiveSheet()->mergeCells('C'.($rowCount).':D'.($rowCount));
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $target);
					$objPHPExcel->getActiveSheet()->mergeCells('E'.($rowCount).':F'.($rowCount));
					$rowCount++;
				}
		}
		$row = $rowCount++;
		if ($tolokur1 ==NULL){
				 $tolokur1 = "-";
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, 'Kelompok Sasaran Kegiatan : '. $tolokur1);
		$objPHPExcel->getActiveSheet()->mergeCells('A'.($row).':F'.($row));

		$row1 = $row+1;
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row1, 'Rincian Anggaran Belanja Langsung ');
		$objPHPExcel->getActiveSheet()->mergeCells('A'.($row1).':F'.($row1));
		$row2 = $row1+1;
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row2, 'Menurut Program dan Per Kegiatan Perangkat Daerah');
		$objPHPExcel->getActiveSheet()->mergeCells('A'.($row2).':F'.($row2));

		$row3 = $row2+1;
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row3, "Kode Rekening");
		$objPHPExcel->getActiveSheet()->mergeCells('A'.($row3).':A'.($row3+1));

		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row3, "Uraian");
		$objPHPExcel->getActiveSheet()->mergeCells('B'.($row3).':B'.($row3+1));

		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row3, "Rincian Perhitungan");
		$objPHPExcel->getActiveSheet()->mergeCells('C'.($row3).':E'.($row3));

		$objPHPExcel->getActiveSheet()->setCellValue('F'.$row3, "Jumlah (Rp)");
		$objPHPExcel->getActiveSheet()->mergeCells('F'.($row3).':F'.($row3 + 1));

		$objPHPExcel->getActiveSheet()->setCellValue('C'.($row3 + 1), "Volume");
		$objPHPExcel->getActiveSheet()->setCellValue('D'.($row3 + 1), "Satuan");
		$objPHPExcel->getActiveSheet()->setCellValue('E'.($row3 + 1), "Harga satuan");

		$row4 = $row3+2;
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row4, '1');
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row4, '2');
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row4, '3');
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row4, '4');
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row4, '5');
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row4, '6 = (3 x 5)');

		$objPHPExcel->getActiveSheet()->getStyle('A'.($row1).':F'.($row4) )->getFont()->setBold(TRUE); // Set bold kolom A1
		$objPHPExcel->getActiveSheet()->getStyle('A'.($row1).':F'.($row4))->getFont()->setSize(12); // Set font size 15 untuk kolom A1
		$objPHPExcel->getActiveSheet()->getStyle('A'.($row1).':F'.($row4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1


		$row5 = $row4+2;
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
		foreach($data ['getDetailPraRKA'] as $d){
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
				if ($jumlah <= 0 && $type =="H"){
								$kode1 = "";
								$uraian = "";
								$volume1 = "";
								$satuan = "";
								$hargasatuan = " ";
								$jumlah = "";

							}else {

												$uraian = $uraian;
												$volume1 = $volume1;
												$satuan = $satuan;
												$hargasatuan = $hargasatuan;
								$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row5, $kode1);
								$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row5, $uraian);
								$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row5, $volume1);
								$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row5, $satuan);
								$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row5, $hargasatuan);
								$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row5, $jumlah);
						}
							$row5++;

			}
			$row6 = $row5++;
			$ttl = null;
			$tp = new stdClass();
			foreach($data ['getTotalPrarka'] as $tp){
				$ttl = number_format($tp->total, 2, ',', '.');
			}
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row6, 'Jumlah');
				$objPHPExcel->getActiveSheet()->mergeCells('A'.($row6).':E'.($row6));
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row6, $ttl);

				$rowS = $row6+5;
				$TANGGAL=tanggal_indo(date('Y-m-d'));
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowS, "Payakumbuh, {$TANGGAL}");
				$objPHPExcel->getActiveSheet()->mergeCells('E'.($rowS).':F'.($rowS));
				$objPHPExcel->getActiveSheet()->getStyle('E'.($rowS).':F'.($rowS))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$rowS21 = $rowS+1;
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowS21, $jab);
				$objPHPExcel->getActiveSheet()->mergeCells('E'.($rowS21).':F'.($rowS21));
				$objPHPExcel->getActiveSheet()->getStyle('E'.($rowS21).':F'.($rowS21))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$rowS22  = $rowS21+5;
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowS22, $nama);
				$objPHPExcel->getActiveSheet()->mergeCells('E'.($rowS22).':F'.($rowS22));
				 $objPHPExcel->getActiveSheet()->getStyle('E'.($rowS22).':F'.($rowS22))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$rowS23  = $rowS22+1;
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowS23, $nip);
				$objPHPExcel->getActiveSheet()->mergeCells('E'.($rowS23).':F'.($rowS23));
				$objPHPExcel->getActiveSheet()->getStyle('E'.($rowS23).':F'.($rowS23))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);

				 $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				 $objWriter->save($fileName);
				// download file
				 header("Content-Type: application/vnd.ms-excel");
				 redirect(site_url().$fileName);

 }
		  //==========================================================================================================


	//201903050925

	public function _program_kegiatan_($act = '')
	{
		$this->sip->is_menu('090108');
		if($act != 'open'):
		$this->load->view('report/v_program_kegiatan');
		endif;
	}

	public function cetak_program_kegiatan_($act = '',$id ='', $nama='')
	{
		$unit =  $id;
		$data['getProgramKegiatanAll'] = 	 $this->m_set->getProgramKegiatanAll($unit);
		$data ['nmakota'] = $this->m_set->getKota();
		if($data){
				if($act == 'print')
		{
			$this->load->view('report/v_program_kegiatan_print', $data);
		}
		else
		{
			$this->load->view('report/v_program_kegiatan_print', $data);
		}
		}
	}

//===============================

public function createXLSSH() {
 // create file name
		 $fileName = 'Laporan SSH.xlsx';
 // load excel library
		 $this->load->library('excel');
		 $empInfo = $this->m_set->CetakSSH();
		 $objPHPExcel = new PHPExcel();
		 $objPHPExcel->setActiveSheetIndex(0);


		 // judul
		 $objPHPExcel->getActiveSheet()->setCellValue('A1', "Laporan Satuan Standar Harga (SSH) TAHUN ANGGARAN {$this->NMTAHUN} "); // Set kolom A1 dengan tulisan "DATA SISWA"
		 $objPHPExcel->getActiveSheet()->mergeCells('A1:G1'); // Set Merge Cell pada kolom A1 sampai E1
		 $objPHPExcel->getActiveSheet()->mergeCells('A2:G2');

		 $objPHPExcel->getActiveSheet()->getStyle('A1:G6')->getFont()->setBold(TRUE); // Set bold kolom A1
		 $objPHPExcel->getActiveSheet()->getStyle('A1:G3')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
		 $objPHPExcel->getActiveSheet()->getStyle('A1:G6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
		 $objPHPExcel->getActiveSheet()->getStyle('A6:G6')->getFont()->setSize(9);


		 // set Header
		  $objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('D6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('E6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('F6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('G7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('F7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('G7')->applyFromArray($this->style_rows());

		 $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'No');
		 $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'Kode SSH');
		 $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Kode Rekening');
		 $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'Nama SSH');
		 $objPHPExcel->getActiveSheet()->SetCellValue('E5', 'Spesifikasi');
		 $objPHPExcel->getActiveSheet()->SetCellValue('F5', 'Satuan');
		 $objPHPExcel->getActiveSheet()->SetCellValue('G5', 'Harga');

		 $objPHPExcel->getActiveSheet()->SetCellValue('A6', '1');
		 $objPHPExcel->getActiveSheet()->SetCellValue('B6', '2');
	   $objPHPExcel->getActiveSheet()->SetCellValue('C6', '3');
	  $objPHPExcel->getActiveSheet()->SetCellValue('D6', '4');
		$objPHPExcel->getActiveSheet()->SetCellValue('E6', '5');
		$objPHPExcel->getActiveSheet()->SetCellValue('F6', '6');
		$objPHPExcel->getActiveSheet()->SetCellValue('G6', '7');
		 // set Row

		 $rowCount = 7;
		 	$no = 1;

		 foreach ($empInfo as $element) {

				 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $no );
				 $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['KDSSH']);
				 $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['KDREK']);
				 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['SSH_NAMA']);
				 $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['SSH_SPEK']);
				 $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element['SSH_SATUAN']);
				 $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $element['SSH_HARGA']);

				  $rowCount++;
					$no++;

 		 	 	$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
 			 	$objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
 			 	$objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
 				 $objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
 			   $objPHPExcel->getActiveSheet()->getStyle('E'.($rowCount))->applyFromArray($this->style_rows());
 				 $objPHPExcel->getActiveSheet()->getStyle('F'.($rowCount))->applyFromArray($this->style_rows());
 				 $objPHPExcel->getActiveSheet()->getStyle('G'.($rowCount))->applyFromArray($this->style_rows());


		 }

			// Set width kolom
		    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
		    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50); // Set width kolom B
		    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30); // Set width kolom C
		    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50); // Set width kolom D
		    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20); // Set width kolom E
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20); // Set width kolom E
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20); // Set width kolom E
		    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

				// Set orientasi kertas jadi LANDSCAPE
  //  $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


		 $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		 $objWriter->save($fileName);
 // download file
		 header("Content-Type: application/vnd.ms-excel");
		 redirect(site_url().$fileName);
 }

 //-------------------------------------------------------------------------------------------
 
 public function cetak_pagu_opd_word1($act = '')
{


	$data['paguskpdall'] = $this->m_set->paguSKPD();
	$data ['pagukec'] = $this->m_set->pagukec();

	if($act == 'print')
	{
		$this->load->view('report/v_pagu_opd_print_new_word', $data);
	}
	else
	{
		$this->load->view('report/v_pagu_opd_print_new_word', $data);
	}

}
 
 public function createXLSpagu_opdkec() {
 // create file name
		 $fileName = 'REKAPITULASI RENCANA KERJA PERANGKAT DAERAH.xlsx';
 // load excel library
		 $this->load->library('excel');
		 $empInfo = $this->m_set->paguSKPD();
		 $empInfo1 = $this->m_set->pagukec();

		 $objPHPExcel = new PHPExcel();
		 $objPHPExcel->setActiveSheetIndex(0);


		 // judul
		 $objPHPExcel->getActiveSheet()->setCellValue('A1', "PAYAKUMBUH"); // Set kolom A1 dengan tulisan "DATA SISWA"
		 $objPHPExcel->getActiveSheet()->setCellValue('A2', "REKAPITULASI RENCANA KERJA PERANGKAT DAERAH");
		 $objPHPExcel->getActiveSheet()->setCellValue('A3', "TAHUN ANGGARAN {$this->NMTAHUN}");
		 $objPHPExcel->getActiveSheet()->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
		 $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
		 $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
		 $objPHPExcel->getActiveSheet()->getStyle('A1:E6')->getFont()->setBold(TRUE); // Set bold kolom A1
		 $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
		 $objPHPExcel->getActiveSheet()->getStyle('A1:E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
		 $objPHPExcel->getActiveSheet()->getStyle('A6:E6')->getFont()->setSize(9);


		 // set Header
		  $objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($this->style_rows());
		  $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('D6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('E6')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($this->style_rows());
			$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($this->style_rows());

		 $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'No');
		 $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'SKPD');
		 $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'PAGU SKPD');
		 $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'PAGU INDIKATIF');
		 $objPHPExcel->getActiveSheet()->SetCellValue('E5', 'SELISIH');

		 $objPHPExcel->getActiveSheet()->SetCellValue('A6', '1');
		$objPHPExcel->getActiveSheet()->SetCellValue('B6', '2');
		$objPHPExcel->getActiveSheet()->SetCellValue('C6', '3');
		$objPHPExcel->getActiveSheet()->SetCellValue('D6', '4');
		$objPHPExcel->getActiveSheet()->SetCellValue('E6', '5=(4-3)');
		 // set Row
		 $rowCount = 7;
		 	$no = 1;
			$pagusisa0 = 0;
			$totalpagu = 0;
			$totalpagudigunakan = 0;
			$totalpagusisa = 0;
			$pagusisa1 = 0;
			$totalpagu1 = 0;
			$totalpagudigunakan1 = 0;
			$totalpagusisa1 = 0;
		 foreach ($empInfo as $element) {

				 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $no );
				 $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['NMUNIT']);
				 $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['PAGU']);
				 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['PAGUUSED']);
				 $pagusisa0= $element['PAGU'] - $element['PAGUUSED'];
					if($pagusisa0 == 0)
					{
					 $pagusisa = "-";
						$p= $pagusisa;
					}
						else
					 {
						 $pagusisa = $pagusisa0;
							$p =  $pagusisa;
					}
				 $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $p);
				 $rowCount++;
				$no++;
				$totalpagu += $element['PAGU'];
				$totalpagudigunakan += $element['PAGUUSED'];
				$totalpagusisa += $pagusisa0 ;


				 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
			   $objPHPExcel->getActiveSheet()->getStyle('E'.($rowCount))->applyFromArray($this->style_rows());
				//$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':E'.($rowCount))->applyFromArray($style_col);

		 }
		 $no1 = $no;
		 foreach ($empInfo1 as $element1) {
			 $kodeunit = $element1['KODEUNIT'];

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

				 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $no1 );
				 $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $nama ) ;
				 $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element1['PAGUKEC']);
				 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element1['PAGUUSEKEC']);
				 $pagusisa0= $element1['PAGUKEC'] - $element1['PAGUUSEKEC'];
					if($pagusisa0 == 0)
					{
					 $pagusisa = "-";
						$p= $pagusisa;
					}
						else
					 {
						 $pagusisa = $pagusisa0;
							$p =  $pagusisa;
					}
				 $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $p);
				 $rowCount++;
				$no1++;
				$totalpagu1 += $element1['PAGUKEC'];
				$totalpagudigunakan1 += $element1['PAGUUSEKEC'];
				$totalpagusisa1 += $pagusisa0 ;


				 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('B'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('C'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('D'.($rowCount))->applyFromArray($this->style_rows());
				 $objPHPExcel->getActiveSheet()->getStyle('E'.($rowCount))->applyFromArray($this->style_rows());
				//$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':E'.($rowCount))->applyFromArray($style_col);

		 }
		 $ttl1 = $totalpagu +$totalpagu1 ;
		 $ttl2 = $totalpagudigunakan + $totalpagudigunakan1 ;
		 $ttl3 = $totalpagusisa + $totalpagusisa1 ;




		  $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'TOTAL');
		  $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $ttl1);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $ttl2);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $ttl3);
		  $objPHPExcel->getActiveSheet()->mergeCells('A'.($rowCount).':B'.($rowCount));
			 $objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount).':B'.($rowCount))->applyFromArray($this->style_column());

			// Set width kolom
		    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
		    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50); // Set width kolom B
		    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); // Set width kolom C
		    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D
		    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20); // Set width kolom E
		    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

				// Set orientasi kertas jadi LANDSCAPE
  //  $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


		 $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		 $objWriter->save($fileName);
 // download file
		 header("Content-Type: application/vnd.ms-excel");
		 redirect(site_url().$fileName);
 }

 //-------------------------------------------------------------------------------------------








}
