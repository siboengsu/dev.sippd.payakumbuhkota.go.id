<?php
defined('BASEPATH') or exit('No direct script access allowed');
class c_matrik51perubahanperperangkatdaerahopduptdblud extends CI_Controller
{
    private $json = ['cod' => null, 'msg' => null, 'link' => null];
    private $KDTAHUN = null;
    private $KDTAHAP = null;
    private $UNITKEY = null;
    private $KEGRKPDKEY = null;
    private $TANGGAL = null;
    private $tgl = null;
    public function __construct()
    {
        parent::__construct();
        $this->sip->is_logged();
        $this->load->model(['m_set', 'm_user', 'm_rka', 'm_program', 'm_kegiatan']);
        $this->KDTAHUN = $this->session->KDTAHUN;
        $this->KDTAHAP = $this->session->KDTAHAP;
        $this->NMTAHUN = $this->session->NMTAHUN;
        $this->TANGGAL = date('d-m-Y');
        $this->tgl = date('Y-m-d');
    }

    public function contr_matrik51perperangkatdaerahopdbluduptd($unitkey, $nomor)
    {
        $this->load->library('libs_matrik51perubahanperperangkatdaerahopdbluduptd');
        ob_end_clean(); //    the buffer and never prints or returns anything.
        ob_start();
        $UNITKEY = $unitkey;
        //$UNITKEY =  $this->input->post('f-unitkey');
        //$nomor = $this->input->post('f-nomor');
        $pdf = new libs_matrik51perubahanperperangkatdaerahopdbluduptd('L', 'mm', 'A4');
        $pdf->SetMargins(5,6,5);
        $title='Matrik 5.1 Per Perangkat Daerah';
       //$pdf->tahun = '2019';
        $pdf->setData($this->NMTAHUN);
        $query = $this->db->query("SELECT KDUNIT+' -'+NMUNIT AS SKPD FROM DAFTUNIT WHERE UNITKEY = '{$UNITKEY}'")->row_array()['SKPD'];
        $SKPD = ucwords($query);
        $pdf->setSKPD($SKPD);
        $nmr = $nomor;
        $pdf->setNumber($nmr-1);
        $pdf->SetTitle($title);
        $pdf->isFinished = false;
        $pdf->AliasNbPages();
        $pdf->AddPage();
        // Add your page contents here
        $pdf->isFinished = true;
        $pdf->SetFont('Times', 'B', 12);
        $pdf->SetAutoPageBreak(true, 29);
        $matrik51perperangkatdaerah = $this->m_set->matrik51perubahanperperangkatdaerahopdbluduptd($UNITKEY);


        $no				= 0;
        $NMUNIT 		= null;
        $detailpagu 	= 0;
        $TYPE  			= null;
    	$totalpagu		= 0;
		$totaldpa		= 0;
    	$paguplustot 	= 0;
    	$detail_pagu 	= 0;
    	$paguplus1 		= 0;
		$detailplus		= 0;
		$detaildpa		= 0;
		$paguplustot1	= 0;
		$detaldpa		= 0;
        $data=[] ;
        $pdf->tablewidths = array(20,26,8,22,11,30,13,13,26,13,13,20,21,21,20,10);
        $pdf->SetAligns(array('L','L','L','L','L','L','L','L','L','L','L','R','R','R','R'));
        foreach ($matrik51perperangkatdaerah as $row) {
        $TYPE =	$row['TYPE'];
        $jab = $row['JAB'];
        $nip = $row['NIP'] ;
        $nama_KEPALA = $row['NAMA_KEPALA_SKPD'] ;
		$TYPE =	$row['TYPE'];
		if ($TYPE =='H') {
		$bold = 'H';
		$NMUNIT = $row['SKPD'];
		$detaildpa = null;
		$detailpagu = null;
		$paguplus1= null;
		$detaldpa = 0;
		$detail = 0;
		$detailplus = 0;
		} else if ($TYPE =='SH') {
		$bold = 'H';
		$NMUNIT = null;
		$detaildpa = $row['PAGUDPA'];
		$detailpagu = $row['PAGU'];
		$paguplus1= $row['PAGUPLUS'];
		$detaldpa = 0;
		$detail =0;
		$detailplus =0;
		} else {
		$bold = 'D';
		$NMUNIT = null;
		$detaildpa = $row['PAGUDPA'];
		$detailpagu = $row['PAGU'];
		$paguplus1= $row['PAGUPLUS'];
		$detail = $detailpagu;
		$detailplus = $paguplus1;
		$detaldpa = $detaildpa;
		}

        $data[] =array('jenis'=>$bold, 'array'=>array(
			  $row['KODE'],
			  $row['NAMA'],
			  $row['PRIORITAS'],
			  $row['CP_TOLOKUR'],
			  $row['CP_TARGET'],
			  $row['KEL_TOLOKUR'],
              $row['TARGETSE'],
 			  $row['KEL_TARGET'],
              $row['HSL_TOLOKUR'],
			  $row['TARGETSEP'],
              $row['HSL_TARGET'],
			  number_format($row['PAGUDPA'], 2, ',', '.'),
              number_format($row['PAGU'], 2, ',', '.'),
			  number_format($row['PAGU'] - $row['PAGUDPA'], 2, ',', '.'),
              number_format($row['PAGUPLUS'], 2, ',', '.'),
              $row['NAMA_OPD']
		));
		$totalpagu +=  $detail;
        $paguplustot += $detailplus;
		$totaldpa += $detaldpa;
		
        }
        $totsel = $totalpagu - $totaldpa;
		$totaldpa = number_format($totaldpa, 0, ',', '.');
		$totalpagu = number_format($totalpagu, 0, ',', '.');
        $totsel = number_format($totsel, 0, ',', '.');
        $paguplustot1 =  number_format($paguplustot, 0, ',', '.');
        $pdf->setTotal($totalpagu);
        $pdf->setTotalPlus($paguplustot1);
        $pdf->morepagestable($data);
        $pdf->Ln(0);
        $pdf->isFinished = false;

        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(195,8,'Total',1,0,'C');
		$pdf->Cell(20,8,"$totaldpa",1,0,'R');
        $pdf->Cell(21,8,"$totalpagu",1,0,'R');
		$pdf->Cell(21,8,"$totsel",1,0,'R');
        $pdf->Cell(20,8,"$paguplustot1",1,0,'R');
        $pdf->Cell(10,8,"",1,0,'R');
        $datakota = $this->m_set->getKota();
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
        $pdf->Cell(80,5,"($nama_KEPALA)",0,0,'C');
        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(200,5,"",0,'C');
        $pdf->Cell(80,5,"           NIP. $nip",0,0,'C');
        $pdf->Output();
    }
}
