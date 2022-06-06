<?php
require(APPPATH . '/third_party/fpdf/fpdf.php');

class libs_matrik51perperangkatdaerahopdbluduptd extends FPDF
{
    public $tablewidths;
    public $footerset;
    public $headerset;
    public $aligns;
    public $data;
    public $tahun;
	public $total_pagu;
    public $total_pagu_plus;
    public $nomor;
    public $SKPD;

	public function setkunci($input3){
        $this->kunci = $input3;
    }
    public function Footer()
    {
        // Check if Footer for this page already exists (do the same for Header())
        if (!isset($this->footerset[$this->page])) {
            $this->SetY(-33);
            if ($this->isFinished) {
                $this->Cell(280, 4, "", 'B', 1, 'C');
            }
		if ($this->kunci == '80_')
		{
			if ($this->PageNo()==6){
				$this->Cell(280, 4, "", 'B', 1, 'C');
			}
		}
					
            // Page number
            $num = intval($this->nomor) + $this->PageNo();
            //$this->SetFont('Arial','B',6);
            $this->Cell(0, 10, 'IV -  '.$num , 0, 0, 'R');
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

    public function setNumber($input4){
       $this->nomor =$input4;

      }

      public function setSKPD($skpd){
          $this->SKPD = $skpd;
      }

    public function Header()
    {

        if (!isset($this->headerset[$this->page])) {
            $this->SetY(10);
            //  $this->Cell(100, 4, "$this->tahun", 'B', 1, 'C');
            $this->Image(base_url('assets/img/logo-kota-payakumbuh.png'), 10, 6, 10);
            // Arial bold 15
            $this->SetFont('Arial', 'B', 6);
            // Move to the right
            $this->Cell(225);
            // Title
            $this->MultiCell(55, 4, 'Rencana Kerja Tahun ' .$this->data , 0, 'R');
            $this->Ln(2);
            // Line break

            $this->Ln(4);
            if ($this->PageNo()==1) {
                // mencetak string
                $this->Cell(285, 4, 'Tabel IV.I ', 0, 1, 'C');
                $this->Cell(285, 4, 'PEMERINTAH KOTA PAYAKUMBUH', 0, 1, 'C');
                $this->Cell(285, 4, "RENCANA PROGRAM DAN KEGIATAN PERANGKAT DAERAH DENGAN PRIORITAS DAERAH TAHUN ANGGARAN $this->data ", 0, 1, 'C');
                //$this->Cell(10, 2, '', 0, 1);
                $this->SetFont('Arial', 'B', 6);

                  $this->Cell(285, 4, "$this->SKPD ", 0, 1, 'L');
                $tinggi = 39;



            } else {
                $tinggi = 23;
            }
            $A = 15;
            $B = 37;
            $C = 8;
            $D = 18;
            $E = 18;
            $F = 25;
            $G = 13;
            $H = 28;
            $I = 13;
            $J = 25;
            $K = 13;
            $L = 20;
            $M = 20;
            $N = 13;
            $O = 14;
            $this->SetWidths = array($A,$B,$C,$D,$E,$F,$G,$H,$I,$J,$K,$L,$M,$N,$O);
            $x =$this->GetX();
            $y =$this->GetY();
            $this->SetFont('Arial', 'B', 6);
            //line 1
            $this->Cell($A, 16, 'No', 1, 0, 'C');
            $this->Cell($B, 3.5, '', 'T,R', 0);
            $this->Cell($C, 6, '', 'T,R', 0);
            $this->Cell($D, 6, '', 'T,R', 0);
            $this->Cell($E, 6, '', 'T,R', 0);
            $this->Cell($F+$G+$H+$I+$J+$K, 5, 'Indikasi Kinerja Program/Kegiatan/Sub Kegiatan', 1, 0, 'C');
            $this->Cell($L, 5, '', 'T,R', 0, 'C');
            $this->Cell($M, 5, '', 'T,R', 0, 'C');
            $this->Cell($N, 5, '', 'T,R', 0, 'C');
            $this->Cell($O, 5, '', 'T,R', 0, 'C');
            $this->Cell(0, 4, '', 0, 1);
            //line 2
            $this->Cell($A, 4, '', 0, 0);
            $this->MultiCell($B, 3, 'Urusan Pemerintah Daerah/Program/Kegiatan/Sub Kegiatan', 0, 'C');
            $this->SetXY($x +$A+$B, $tinggi);
            $this->MultiCell($C, 5, 'Prioritas', 'L,R', 'C');
            $this->SetXY($x +$A+$B+$C, $tinggi);
            $this->MultiCell($D, 8, 'Sasaran', 'L,R', 'C');
            $this->SetXY($x +$A+$B+$C+$D, $tinggi);
            $this->MultiCell($E, 8, 'Lokasi', 'L,R', 'C');
            $this->SetXY($x +$A+$B+$C+$D+$E, $tinggi+2);
            $this->MultiCell($F+$G, 6, 'IKU (OPD)', 'L,R', 'C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G, $tinggi+2);
            $this->MultiCell($H+$I, 3, 'Indikator Kegiatan (outcome)/ Indikator Subkegiatan (output)', 'L,R', 'C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$H+$I, $tinggi+2);
            $this->MultiCell($J+$K, 6, 'Indikator Program', 'L,R', 'C');
            $this->Ln(0);
            $this->SetXY($x  +$A+$B+$C+$D+$E+$F+$G+$H+$I+$J+$K, $tinggi+2);
            $this->MultiCell($L, 4.5, "Dana Indikatif Tahun $this->data ", 'L,R', 'C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$H+$I+$J+$K+$L, $tinggi+2);
            $this->MultiCell($M, 4.5, "Prakiraan Maju Tahun $this->dataplus", 'L,R', 'C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$H+$I+$J+$K+$L+$M, $tinggi+2);
            $this->MultiCell($N, 4.5, 'Jenis Kegiatan', 'L,R', 'C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$H+$I+$J+$K+$L+$M+$N, $tinggi+2);
            $this->MultiCell($O, 4.5, 'Jenis Kegiatan', 'L,R', 'C');
            $this->Ln(0);
            $this->Cell(0, 5, '', 0, 1);
            //line 3
            $this->Cell($A, 4, '', 0, 0);
            $this->Ln(0);
            $this->SetXY($x+$A, $tinggi+8);
            $this->Cell($B, 5, '', 'B', 0);
            $this->Ln(0);
            $this->SetXY($x +$A+$B, $tinggi+8);
            $this->Cell($C, 5, '', 'B,L', 0);
            $this->SetXY($x +$A+$B+$C, $tinggi+8);
            $this->Cell($D, 5, '', 'B,L', 0);
            $this->SetXY($x +$A+$B+$C+$D, $tinggi+8);
            $this->Cell($E, 5, '', 'B,L', 0);
            $this->SetXY($x +$A+$B+$C+$D+$E, $tinggi+8);
            $this->Cell($F, 5, 'Tolok Ukur', 1, 'T,L,B', 'C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F, $tinggi+8);
            $this->Cell($G, 5, 'Target', 1, 'T,L,B', 'C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G, $tinggi+8);
            $this->Cell($H, 5, 'Tolok Ukur', 1, 'T,L,B', 'C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$H, $tinggi+8);
            $this->Cell($I, 5, 'Target', 1, 'T,L,B', 'C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$H+$I, $tinggi+8);
            $this->Cell($J, 5, 'Tolok Ukur', 1, 'T,L,B', 'C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$H+$I+$J, $tinggi+8);
            $this->Cell($K, 5, 'Target', 1, 'T,L,B', 'C');
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$H+$I+$J+$K, $tinggi+8);
            $this->Cell($L, 5, '', 'B,R', 0);
            $this->Ln(0);
            $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$H+$I+$J+$K+$L, $tinggi+8);
            $this->Cell($M, 5, '', 'B,R', 0);
            $this->Ln(0);
            $this->SetXY($x  +$A+$B+$C+$D+$E+$F+$G+$H+$I+$J+$K+$L+$M, $tinggi+8);
            $this->Cell($N, 5, '', 'B,R', 0);
            $this->Ln(0);
            $this->SetXY($x  +$A+$B+$C+$D+$E+$F+$G+$H+$I+$J+$K+$L+$M+$N, $tinggi+8);
            $this->Cell($O, 5, '', 'B,R', 0);
            $this->Ln();
            $this->Cell($A, 5, '1', '1', 0, 'C');
            $this->Cell($B, 5, '2', '1', 0, 'C');
            $this->Cell($C, 5, '3', '1', 0, 'C');
            $this->Cell($D, 5, '4', '1', 0, 'C');
            $this->Cell($E, 5, '5', '1', 0, 'C');
            $this->Cell($F, 5, '6', '1', 0, 'C');
            $this->Cell($G, 5, '7', '1', 0, 'C');
            $this->Cell($H, 5, '8', '1', 0, 'C');
            $this->Cell($I, 5, '9', '1', 0, 'C');
            $this->Cell($J, 5, '10', '1', 0, 'C');
            $this->Cell($K, 5, '11', '1', 0, 'C');
            $this->Cell($L, 5, '12', '1', 0, 'C');
            $this->Cell($M, 5, '13', '1', 0, 'C');
            $this->Cell($N, 5, '14', '1', 0, 'C');
            $this->Cell($O, 5, '15', '1', 0, 'C');
            // $this->Cell($M,5,'13','1',0,'C');
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
        $this->SetTopMargin(41);
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
                    $this->SetFont('Arial', 'B', 6);
                } else {
                    $this->SetFont('Arial', '', 6);
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
      //  $this->Line($l, $h, $fullwidth+$l, $h);

        //$this->Cell(13,5,"",1,0,'R');
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
        $this->SetXY($l, $h, $fullwidth+$l, $h);
        //$this->Cell(13,8,"",1,0,'R');
      //  $this->Ln(10);
      //  $this->SetFont('Arial','B',7);
      //  $this->Cell(227,8,'Total',1,0,'C');
      //  $this->Cell(20,8,"$this->total_pagu",1,0,'R');
      //  $this->Cell(20,8,"$this->total_pagu_plus",1,0,'R');
    }
}
