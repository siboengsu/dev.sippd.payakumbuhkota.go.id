<?php
defined('BASEPATH') or exit('No direct script access allowed');
class c_lap_matrikrpjmd extends CI_Controller
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

    public function contr_matrikrpjmd($nomor)
    {
        $this->load->library('libs_matrikrpjmd');
        ob_end_clean(); //    the buffer and never prints or returns anything.
        ob_start();
        $pdf = new libs_matrikrpjmd('L', 'mm', 'A4');
        $title='Matrik 5.1';
		//  $pdf->tahun = '2019';
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

        $matrik51 = $this->db->query('SELE');
        $no=0;
        $NMUNIT = null;
        $detailpagu = null;
        $TYPE  = null;
        $paguplus1 = null;
		$totalpagu = null;
		$paguplustot = null;
		$detail = 0;
		$paguplus1 = 0;

        $pdf->tablewidths = array(33, 35, 8, 26, 13, 36,22,30,13,22,22,20);
        $pdf->SetAligns(array('L','L','L','L','L','L','L','L','L','R','R','L'));
        foreach ($matrik51 as $row) {
            $TYPE =	$row['TYPE'];
            if ($TYPE =='H') {
                $bold = 'H';
                $detailpagu = null;

                $NMUNIT = $row['SKPD'];
                $paguplus1= null;
				$detail = 0;
				$detailplus = 0;
            } else {
                $bold = 'D';
                $detailpagu = $row['PAGU'];

                $NMUNIT = null;
                $paguplus1= $row['PAGUPLUS'];
				$detail =$detailpagu;
				$detailplus =$paguplus1;
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
					 $row['TARGETLUAR1'],
					 $row['HASIL'],
					 $row['TARGETHASIL'],
					 number_format($row['PAGU'], 0, ',', '.'),
					  number_format($row['PAGUPLUS'], 0, ',', '.'),
					  $NMUNIT
					));
					 $totalpagu +=  $detail;
                     $paguplustot += $detailplus;

        }
					$totalpagu = number_format($totalpagu, 0, ',', '.');
                    $paguplustot =  number_format($paguplustot, 0, ',', '.');
                    $pdf->setTotal($totalpagu);
                    $pdf->setTotalPlus($paguplustot);
        $pdf->morepagestable($data);
        //print_r($data);
        $pdf->isFinished = false;
        $pdf->Output();
    }
}
