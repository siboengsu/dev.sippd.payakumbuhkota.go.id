<?php
defined('BASEPATH') or exit('No direct script access allowed');
class c_lap_matrik51_perubahan extends CI_Controller
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

    public function contr_matrik51_perubahan($nomor)
    {
        $this->load->library('libs_matrik51_perubahan');
        ob_end_clean(); //    the buffer and never prints or returns anything.
        ob_start();
        $pdf = new libs_matrik51_perubahan('L', 'mm', 'A4');
        $pdf->SetMargins(8,6,12);
        $title='Matrik 5.1 Perubahan';
        $pdf->tahun = '2019';
        $pdf->setData($this->NMTAHUN);
		$nmr = $nomor;
		$pdf->setNumber($nmr-1);
        $pdf->SetTitle($title);
        $pdf->isFinished = false;
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Add your page contents here
        $pdf->isFinished = true;
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetAutoPageBreak(true, 29);

        $matrik51 = $this->m_set->matrik51_perubahan();
        $no=0;
        $NMUNIT = null;
        $detailpagu = null;
        $TYPE  = null;
        $paguplus1 = null;
        $paguplusA = null;
		$detail = 0;
		$paguplus1 = 0;
		$paguplus1 = 0;
		$totalpagu = 0;
		$paguplustot = 0;
		$paguplustot1 = 0;
		$PAGUSISA2 = 0;
		$totpagusisa = 0;
        $dpagupluasA = 0;
        $totpaguplusA = 0;

        $pdf->tablewidths = array(14, 27, 7, 22, 11, 26, 13, 13, 22, 13, 13, 21, 21, 21, 21, 9);
        $pdf->SetAligns(array('L','L','L','L','L','L','L','L','L','L','L','R','R','R','R','L',));
        foreach ($matrik51 as $row) {
			$RESPP = null;
            $TYPE =	$row['TYPE'];
            if ($TYPE =='H') {
                $bold = 'H';
                $detailpagu = null;
                $NMUNIT = $row['SKPD'];
                $paguplus1= null;
                $paguplusA = null;
				$detail = 0;
				$detailplus = 0;
            } else {
                $bold = 'D';
                $detailpagu = $row['PAGU'];
                $NMUNIT = null;
                $paguplus1= $row['PAGUDPA'];
                $paguplusA = $row['PAGUPLUS'];
				$detail = $detailpagu;
				$detailplus = $paguplus1;
				$PAGUSISA2 = $detail- $detailplus;
                $dpagupluasA = $paguplusA;
            }
			$RESG = $row['RESGENDER'];
            if ($RESG == 1)
            {
                $RESPP = "RG";
            }else{
                $RESPP = $row['NOPRIO'];
            }

            $data[] =array('jenis'=>$bold, 'array'=>array(
            $row['KODE'],
            $row['NMPRGRM'],
            $RESPP,
            $row['INDIKATOR'],
            $row['TCPAPAIPGR'],
            $row['KELUARAN'],
            $row['TARGETSE'],
            $row['TARGETLUAR1'],
            $row['HASIL'],
            $row['TARGETSEP'],
            $row['TARGETHASIL'],
            number_format($row['PAGUDPA'], 0, ',', '.'),
            number_format($row['PAGU'], 0, ',', '.'),
            number_format(($row['PAGU'] - $row['PAGUDPA']), 0, ',', '.'),
            number_format($row['PAGUPLUS'], 0, ',', '.'),
            $NMUNIT
            ));
			$totalpagu +=  $detail;
            $paguplustot += $detailplus;
			$totpagusisa += $detail - $detailplus ;
            $totpaguplusA += $paguplusA;
        }
		$totalpagu = number_format($totalpagu, 0, ',', '.');
		$paguplustot1 =  number_format($paguplustot, 0, ',', '.');
		$totpagusisa =  number_format($totpagusisa, 0, ',', '.');
        $totpaguplusA =  number_format($totpaguplusA, 0, ',', '.');
		$pdf->setTotal($totalpagu);
		$pdf->setTotalPlus($paguplustot1); 
		$pdf->setTotalPaguSisa($totpagusisa); 
        $pdf->setTotalPlusA($totpaguplusA); 
        $pdf->morepagestable($data);
        $pdf->isFinished = false;
        $pdf->Output();
    }
}
