<?php
defined('BASEPATH') or exit('No direct script access allowed');
class c_matrik51perperangkatdaerah extends CI_Controller
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

    public function contr_matrik51perperangkatdaerah($unitkey, $nomor)
    {
        $this->load->library('libs_matrik51perperangkatdaerah');
        ob_end_clean(); //    the buffer and never prints or returns anything.
        ob_start();
        $UNITKEY = $unitkey;
        //$UNITKEY =  $this->input->post('f-unitkey');
        //$nomor = $this->input->post('f-nomor');
        $pdf = new libs_matrik51perperangkatdaerah('L', 'mm', 'A4');
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
        $matrik51perperangkatdaerah = $this->m_set->matrik51perperangkatdaerah($UNITKEY);
        $no				=0;
        $NMUNIT 		= null;
        $detailpagu 	= 0;
        $TYPE  			= null;
    	$totalpagu		= 0;
    	$paguplustot 	= 0;
    	$detail_pagu 	= 0;
    	$paguplus1 		= 0;
		$detailplus		= 0;
		$paguplustot1	= 0;
        $data=[] ;
        $pdf->tablewidths = array(21,35,8,20,38,13,38,13,28,13,20,20,13);
        $pdf->SetAligns(array('L','L','L','L','L','L','L','L','L','L','L','R','R','L'));
        foreach ($matrik51perperangkatdaerah as $row) {
        $TYPE =	$row['TYPE'];
        $jab = $row['JAB'];
        $nip = $row['NIP'] ;
        $nama_KEPALA = $row['NAMA_KEPALA_SKPD'] ;
		$TYPE =	$row['TYPE'];
		if ($TYPE =='H') {
		$bold = 'H';
		$detailpagu = null;
		$NMUNIT = $row['SKPD'];
		$paguplus1= null;
		$detail = 0;
		$detailplus = 0;
		} else if ($TYPE =='SH') {
		$bold = 'H';
		$detailpagu = $row['PAGU'];
		$NMUNIT = null;
		$paguplus1= $row['PAGUPLUS'];
		$detail =0;
		$detailplus =0;
		} else {
		$bold = 'D';
		$detailpagu = $row['PAGU'];
		$NMUNIT = null;
		$paguplus1= $row['PAGUPLUS'];
		$detail =$detailpagu;
		$detailplus =$paguplus1;
		}

        $data[] =array('jenis'=>$bold, 'array'=>array(
			  $row['KODE'],
			  $row['NAMA'],
			  $row['PRIORITAS'],
			//   $row['SASARAN'],
			  $row['LOKASI'],
			  $row['CP_TOLOKUR'],
			  $row['CP_TARGET'],
			  $row['KEL_TOLOKUR'],
			  $row['KEL_TARGET'],
              $row['HSL_TOLOKUR'],
              $row['HSL_TARGET'],
			  number_format($row['PAGU'], 0, ',', '.'),
			  number_format($row['PAGUPLUS'], 0, ',', '.'),
			  $row['JNS_KEGIATAN']
		));
		$totalpagu +=  $detail;
        $paguplustot += $detailplus;
        
        }
		$totalpagu = number_format($totalpagu, 0, ',', '.');
        $paguplustot1 =  number_format($paguplustot, 0, ',', '.');
        $pdf->setTotal($totalpagu);
        $pdf->setTotalPlus($paguplustot1);
        $pdf->morepagestable($data);
        $pdf->isFinished = false;
		$pdf->Cell(13,8,"",1,0,'R');
        // $datakota = $this->m_set->getKota();
        
        // $pdf->Ln(10);
        // $pdf->SetFont('Arial','B',8);
        // $pdf->Cell(200,5,"",0,'C');
        // $pdf->Cell(80,5,'Payakumbuh, '.tanggal_indo($this->tgl),0,0,'C');
        // $pdf->Ln(4);
        // $pdf->Cell(200,5,"",0,'C');
        // $pdf->MultiCell(80,5,"$jab",0,'C');
        // $pdf->SetFont('Arial','B',9);
        // $pdf->MultiCell(30,5,' ',0,'R');
        // $pdf->MultiCell(30,5,' ',0,'R');
        // $pdf->MultiCell(30,5,' ',0,'R');
        // $pdf->MultiCell(30,5,' ',0,'R');
        // $pdf->SetFont('Arial','B',8);
        // $pdf->Ln(0);
        // $pdf->SetFont('Arial','BU',8);
        // $pdf->Cell(200,5,"",0,'C');
        // $pdf->Cell(80,5,"($nama_KEPALA)",0,0,'C');
        // $pdf->Ln(4);
        // $pdf->SetFont('Arial','B',8);
        // $pdf->Cell(200,5,"",0,'C');
        // $pdf->Cell(80,5,"           NIP. $nip",0,0,'C');
        $pdf->Output();
    }
}
