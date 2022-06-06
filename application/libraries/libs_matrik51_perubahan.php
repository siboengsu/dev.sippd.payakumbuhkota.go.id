<?php
require(APPPATH . '/third_party/fpdf/fpdf.php');

class libs_matrik51_perubahan extends FPDF
{
    public $tablewidths;
    public $footerset;
    public $headerset;
    public $aligns;
    public $data;
    public $tahun;
	public $total_pagu;
    public $total_pagu_plus;
	public $total_pagu_sisa;
	public $nomor;

    public function Footer()
    {
        // Check if Footer for this page already exists (do the same for Header())
        if (!isset($this->footerset[$this->page])) {
            $this->SetY(-33);
            if ($this->isFinished) {
                $this->Cell(274, 4, "", 'B', 1, 'C');
            }
            $this->Ln(6);
            // Page number
			$num = intval($this->nomor) + $this->PageNo();

            $this->Cell(0, 10, 'V -  '.$num , 0, 0, 'R');
            // set footerset
            $this->footerset[$this->page] = true;
        }
    }
    public function setData($input){
        $this->data = $input;
    	$this->dataplus = $input+ 1;
    }
	
	public function setTotal($input1){
		$this->total_pagu = $input1;
    }
    public function setTotalPlus($input2){
        $this->total_pagu_plus = $input2;
    }

    public function setTotalPlusA($input8){
        $this->total_pagu_plus_a = $input8;
    }
	
	public function setTotalPaguSisa($input3){
        $this->total_pagu_sisa = $input3;
    }
	
	public function setNumber($input4){
		 $this->nomor =$input4;
		
    }


    public function Header()
    {
        if (!isset($this->headerset[$this->page])) {


            $this->SetY(10);
            //  $this->Cell(100, 4, "$this->tahun", 'B', 1, 'C');

            $this->Image(base_url('assets/img/logo-kota-payakumbuh.png'),12,6,10);
            // Arial bold 15
            $this->SetFont('Arial', 'B', 7);
            // Move to the right
            $this->Cell(223);
            // Title
            $this->MultiCell(52, 3, 'Rencana Kerja Pemerintah Daerah (RKPD) Perubahan Kota Payakumbuh Tahun ' .$this->data , 0, 'R');
            // $this->Ln(2);
			// Line break

            $this->Ln(4);
            if ($this->PageNo()==1) {
                // mencetak string
                $this->Cell(285, 4, 'Tabel V.I ', 0, 1, 'C');
                $this->Cell(285, 4, "RENCANA PROGRAM DAN KEGIATAN PRIORITAS DAERAH PADA PERUBAHAN RKPD TAHUN $this->data ", 0, 1, 'C');
                $this->Cell(285, 4, 'PEMERINTAHAN KOTA PAYAKUMBUH', 0, 1, 'C');
                $this->Cell(10, 2, '', 0, 1);
                $this->SetFont('Arial', 'B', 9);
                $tinggi = 37;
            } else {
                $tinggi = 23;
            }
            $A = 14;
            $B = 27;
            $C = 7;
            $D = 22;
            $E = 11;
            $F = 26;
            $G = 13;
            $O = 13;
            $H = 22;
            $I = 13;
            $N = 13;
            $J = 21;
            $K = 21;
            $L = 21;
            $P = 21;
            $M = 9;

            $this->SetWidths = array($A,$B,$C,$D,$E,$F,$G,$O,$H,$I,$N,$J,$K,$L,$P,$M);

            $x =$this->GetX();
            $y =$this->GetY();
            $this->SetFont('Arial', 'B', 7);
            //line 1
            $this->Cell($A,21,'No',1,0,'C');
            $this->Cell($B,3.5,'','T,R',0);
            $this->Cell($C,6,'','T',0);
            $this->Cell($D+$E+$F+$G+$H+$I+$O+$N,5,'Indikasi Kinerja Program/Kegiatan/Sub Kegiatan',1,0,'C');
            $this->Cell($J+$K+$L,5,"Pagu Indikatif Tahun $this->data",1,0,'C');
            //$this->Cell(25,4,'','T,R',0);
            //	$this->Cell(25,6,'','T,R',0);
            $this->Cell($P,6,'','T,R',0);
            $this->Cell($M,6,'','T,R',0);
            $this->Cell(0,4,'',0,1);
            //line 2
            $this->Cell($A,4,'',0,0);
            $this->MultiCell($B,3,'Urusan Pemerintah Daerah/Program/Kegiatan/Sub Kegiatan',0,'C');
            $this->SetXY($x +$A+$B, $tinggi);
            $this->MultiCell($C,3,'Prioritas/ RG','L,R','C');
            $this->SetXY($x +$A+$B+$C, $tinggi+2);
            $this->MultiCell($D+$E,5,'IKU (OPD)','L,R','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E, $tinggi+2);
            $this->MultiCell($F+$G+$O,3,'Indikator Kegiatan (outcome)/Indikator Subkegiatan (output)','L,R','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$O, $tinggi+2);
            $this->MultiCell($H+$I+$N,6,'Indikator Program ','L,R','C');
            $this->Ln(0);
            $this->SetXY($x  +$A+$B+$C+$D+$E+$F+$G+$O+$H+$I+$N, $tinggi+2);
            $this->MultiCell($J,4,'                      Sebelum Perubahan','L,R','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$O+$H+$I+$N+$J, $tinggi+2);
            $this->MultiCell($K,4,'                      Setelah Perubahan','L,R','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$O+$H+$I+$N+$J+$K, $tinggi+2);
            $this->MultiCell($L,4,'                      Jumlah Perubahan (+/-)','L,R','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$O+$H+$I+$N+$J+$K+$L, $tinggi+2);
            $this->MultiCell($P,3.5,'                    Pagu 2022','L,R','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$O+$H+$I+$N+$J+$K+$L+$P, $tinggi);
            $this->MultiCell($M,2.8,'Penanggung Jawab','L,R','C');
            $this->Ln(0);
            $this->Cell(0,5,'',0,1);
            //line 3
            $this->Cell($A,4,'',0,0);
            $this->Ln(0);
            $this->SetXY($x+$A, $tinggi+8);
            $this->Cell($B,10,'','B',0);
            $this->Ln(0);
            $this->SetXY($x +$A+$B , $tinggi+8);
            $this->Cell($C,10,'','B,L',0);
            $this->SetXY($x +$A+$B+$C, $tinggi+8);
            $this->Cell($D,10,'Tolok Ukur',1,'T,L,B','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D, $tinggi+8);
            $this->Cell($E,10,'Target',1,'T,L,B','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E, $tinggi+8);
            $this->Cell($F,10,'Tolok Ukur',1,'T,L,B','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F, $tinggi+8);
            $this->MultiCell($G,5,'Target Sebelum','T,L,R','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G, $tinggi+8);
            $this->MultiCell($O,5,'Target Sesudah','T,L,R','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$O, $tinggi+8);
            $this->Cell($H,10,'Tolok Ukur',1,'T,L,B','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$O+$H, $tinggi+8);
            $this->MultiCell($I,5,'Target Sebelum','T,L,R','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$O+$H+$I, $tinggi+8);
            $this->MultiCell($N,5,'Target Sesudah','T,L,R','C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$O+$H+$I+$N, $tinggi+8);
            $this->Cell($J,10,'','B,R',0);
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$O+$H+$I+$N+$J, $tinggi+8);
            $this->Cell($K,10,'','B,R',0);
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$O+$H+$I+$N+$J+$K, $tinggi+8);
            $this->Cell($L,10,'','B,R',0);
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$O+$H+$I+$N+$J+$K+$L, $tinggi+8);
            $this->Cell($P,10,'','B,R',0);
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$O+$H+$I+$N+$J+$K+$L+$P, $tinggi+8);
            $this->Cell($M,10,'','B,R',0);
            $this->Ln();
            $this->Cell($A,5,'1','1',0,'C');
            $this->Cell($B,5,'2','1',0,'C');
            $this->Cell($C,5,'3','1',0,'C');
            $this->Cell($D,5,'4','1',0,'C');
            $this->Cell($E,5,'5','1',0,'C');
            $this->Cell($F,5,'6','1',0,'C');
            $this->Cell($G,5,'7','1',0,'C');
            $this->Cell($O,5,'8','1',0,'C');
            $this->Cell($H,5,'9','1',0,'C');
            $this->Cell($I,5,'10','1',0,'C');
            $this->Cell($N,5,'11','1',0,'C');
            $this->Cell($J,5,'12','1',0,'C');
            $this->Cell($K,5,'13','1',0,'C');
            $this->Cell($L,5,'14 = (13-12)','1',0,'C');
            $this->Cell($P,5,'15','1',0,'C');
            $this->Cell($M,5,'16','1',0,'C');

            $this->Ln();
            $this->headerset[$this->page] = true;
        }
    }
    public function SetAligns($a)
    {
        $this->aligns=$a;
    }

    //Set line height
    public function SetNB($h)
    {
        $this->nb=$h;
    }

    public function _beginpage($orientation, $size, $rotation)
    {
        $this->page++;
        if (!isset($this->pages[$this->page])) { // solves the problem of overwriting a page if it already exists
            $this->pages[$this->page] = '';
        }
        $this->state = 2;
        $this->x = $this->lMargin;
        $this->y = $this->tMargin;
        $this->FontFamily = '';
        // Check page size and orientation
        if ($orientation=='') {
            $orientation = $this->DefOrientation;
        } else {
            $orientation = strtoupper($orientation[0]);
        }
        if ($size=='') {
            $size = $this->DefPageSize;
        } else {
            $size = $this->_getpagesize($size);
        }
        if ($orientation!=$this->CurOrientation || $size[0]!=$this->CurPageSize[0] || $size[1]!=$this->CurPageSize[1]) {
            // New size or orientation
            if ($orientation=='P') {
                $this->w = $size[0];
                $this->h = $size[1];
            } else {
                $this->w = $size[1];
                $this->h = $size[0];
            }
            $this->wPt = $this->w*$this->k;
            $this->hPt = $this->h*$this->k;
            $this->PageBreakTrigger = $this->h-$this->bMargin;
            $this->CurOrientation = $orientation;
            $this->CurPageSize = $size;
        }
        if ($orientation!=$this->DefOrientation || $size[0]!=$this->DefPageSize[0] || $size[1]!=$this->DefPageSize[1]) {
            $this->PageInfo[$this->page]['size'] = array($this->wPt, $this->hPt);
        }
        if ($rotation!=0) {
            if ($rotation%90!=0) {
                $this->Error('Incorrect rotation value: '.$rotation);
            }
            $this->CurRotation = $rotation;
            $this->PageInfo[$this->page]['rotation'] = $rotation;
        }
    }


    public function morepagestable($datas, $lineheight=4)
    {
        // some things to set and 'remember'
        $l = $this->lMargin;
        $this->SetTopMargin(46);
        $startheight = $h = $this->GetY();
        $startpage = $currpage = $maxpage = $this->page;

        // calculate the whole width
        $fullwidth = 0;
        foreach ($this->tablewidths as $width) {
            $fullwidth += $width;
        }

        // Now let's start to write the table
        foreach ($datas as $row => $data1) {
            $this->page = $currpage;

            // write the horizontal borders
            $this->Line($l, $h, $fullwidth+$l, $h);
            // write the content and remember the height of the highest col

            foreach ($data1 as $col1 => $data) {
                $TYPE = $data1['jenis'];
                //print_r($TYPE);
                if ($TYPE =='H') {
                    $this->SetFont('Arial', 'B', 7);
                } else {
                    $this->SetFont('Arial', '', 7);
                }

				if (is_array($data) || is_object($data))
				{
					foreach ($data as $col => $txt) {
						//	print_r($data);
						$this->page = $currpage;
						$this->SetXY($l, $h);
						$a=isset($this->aligns[$col]) ? $this->aligns[$col] : 'L';
						$this->MultiCell($this->tablewidths[$col], $lineheight, $txt, 0, $a);
						$l += $this->tablewidths[$col];

						if (!isset($tmpheight[$row.'-'.$this->page])) {
							$tmpheight[$row.'-'.$this->page] = 0;
						}
						if ($tmpheight[$row.'-'.$this->page] < $this->GetY()) {
							$tmpheight[$row.'-'.$this->page] = $this->GetY();
						}
						if ($this->page > $maxpage) {
							$maxpage = $this->page;
						}
					}
				}
            }

            // get the height we were in the last used page
            $h = $tmpheight[$row.'-'.$maxpage];
            // set the "pointer" to the left margin
            $l = $this->lMargin;
            // set the $currpage to the last page
            $currpage = $maxpage;
        }
        // draw the borders
        // we start adding a horizontal line on the last page
        $this->page = $maxpage;
        $this->Line($l, $h, $fullwidth+$l, $h);
		$this->SetXY($l, $h, $fullwidth+$l, $h);
        $this->SetFont('Arial','B',7);
        $this->Cell(181,5,'Total',1,0,'C');
        $this->Cell(21,5,"$this->total_pagu_plus",1,0,'R');
        $this->Cell(21,5,"$this->total_pagu",1,0,'R');
        $this->Cell(21,5,"$this->total_pagu_sisa",1,0,'R');
        $this->Cell(21,5,"$this->total_pagu_plus_a",1,0,'R');
		$this->Cell(9,5,"",1,0,'R');
		$this->Ln();
		$this->Ln();
        $this->Cell(0,0,'** RG = Responsive Gender',00,'L');
        // now we start at the top of the document and walk down
        for ($i = $startpage; $i <= $maxpage; $i++) {
            $this->page = $i;
            $l = $this->lMargin;
            $t  = ($i == $startpage) ? $startheight : $this->tMargin;
            $lh = ($i == $maxpage)   ? $h : $this->h-$this->bMargin;
            $this->Line($l, $t, $l, $lh);
            foreach ($this->tablewidths as $width) {
                $l += $width;
                $this->Line($l, $t, $l, $lh);
            }
        }
        // set it to the last page, if not it'll cause some problems
        $this->page = $maxpage;
    }
}
