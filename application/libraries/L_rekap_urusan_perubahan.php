<?php
include_once APPPATH . '/third_party/fpdf/fpdf.php';
class L_rekap_urusan_perubahan extends FPDF {
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
    public function Footer()
    {
        // Check if Footer for this page already exists (do the same for Header())
        if (!isset($this->footerset[$this->page])) {
            if ($this->isFinished) {
                $this->SetY(-35);
                $this->Cell(263, 6, "", 'B', 1, 'C');
                $this->Cell(10, 8, '', 0, 1);
            }else {
                $this->SetY(-35);
            }
            $this->Ln();
            $num = intval($this->nomor) + $this->PageNo();
            
            if ($this->PageNo()==5){
                $this->Cell(263, 10, 'V -  '.$num , 0, 0, 'R');
            }else{
                $this->Cell(263, -10, 'V -  '.$num , 0, 0, 'R');
            }
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

    public function setTotaldpa($input2){
        $this->total_pagu_dpa = $input2;
    }

    public function setTotalSelisih($input3){
        $this->total_pagu_selisih = $input3;
    }

    public function setNumber($input4){
        $this->nomor =$input4;
    }

    public function Header()
    {
        if (!isset($this->headerset[$this->page])) {
            //  $this->Cell(100, 4, "$this->tahun", 'B', 1, 'C');
            $this->Image(base_url('assets/img/logo-kota-payakumbuh.png'), 16.5, 10, 10);
            // Arial bold 15
            $this->SetFont('Arial', 'B', 8);
            // Move to the right
            if ($this->PageNo()==1){
                $this->Cell(199.5);
                $this->MultiCell(65, 4, 'Rencana Kerja Pemerintah Daerah (RKPD) Perubahan Kota Payakumbuh Tahun ' .$this->data , 0, 'R');
                $this->Ln(2);
            }else{
                $this->SetY(10);
                $this->Cell(199.5);
                $this->MultiCell(65, 4, 'Rencana Kerja Pemerintah Daerah (RKPD) Perubahan Kota Payakumbuh Tahun ' .$this->data , 0, 'R');
                $this->Ln(2);
            }
            // Line break
            $this->Ln(4);
            if ($this->PageNo()==1){
                $this->SetY(20);
                // mencetak string
                $this->Cell(263, 5, 'Tabel V.2 ', 0, 1, 'C');
                $this->Cell(263, 5, "REKAPITULASI RENCANA PROGRAM DAN KEGIATAN PRIORITAS DAERAH TAHUN ANGARAN $this->data", 0, 1, 'C');
                $this->Cell(263, 5, "MENURUT URUSAN", 0, 1, 'C');
                $this->Cell(10, 5, '', 0, 1);
                $this->SetFont('Arial', 'B', 9);
                $tinggi = 40;
            }else{
                $this->SetY(30);
                $tinggi = 35;
            }
            if ($this->PageNo()==3){
                $this->SetY(30);
            }
            if ($this->PageNo()==4){
                $this->SetY(30);
                //$this->SetX(10);
            }

            $A = 35;
            $B = 85;
            $C = 65;
            $D = 26;
            $E = 26;
            $F = 26;

            $this->SetWidths = array($A,$B,$C,$D);
            $x =$this->GetX();
            $y =$this->GetY();
            $this->SetFont('Arial', 'B', 8);
            //line 1
            $this->Cell($A, 10, 'No', 1, 0, 'C');
            $this->Cell($B, 10, 'URUSAN', 1, 0, 'C');
            $this->Cell($C, 10, 'PERANGKAT DAERAH', 1, 0, 'C');
            $this->Cell($D, 10, 'PAGU SEBELUM', 1, 0, 'C');
            $this->Cell($E, 10, 'PAGU SESUDAH', 1, 0, 'C');
            $this->Cell($F, 10, 'SELISIH', 1, 0, 'C');

            $this->Ln();
            $this->SetFont('Arial', 'B', 7);
            $this->Cell($A, 5, '1', '1', 0, 'C');
            $this->Cell($B, 5, '2', '1', 0, 'C');
            $this->Cell($C, 5, '3', '1', 0, 'C');
            $this->Cell($D, 5, '4', '1', 0, 'C');
            $this->Cell($E, 5, '5', '1', 0, 'C');
            $this->Cell($F, 5, '6', '1', 0, 'C');

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
        }else{
            $orientation = strtoupper($orientation[0]);
        }
        if ($size=='') {
            $size = $this->DefPageSize;
        }else{
            $size = $this->_getpagesize($size);
        }
        if ($orientation!=$this->CurOrientation || $size[0]!=$this->CurPageSize[0] || $size[1]!=$this->CurPageSize[1]) {
            // New size or orientation
            if ($orientation=='P') {
                $this->w = $size[0];
                $this->h = $size[1];
            }else{
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


    public function morepagestable($datas, $lineheight=6)
    {
        // some things to set and 'remember'
        $l = $this->lMargin;
        $this->SetTopMargin(45);
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
                    $this->SetFont('Arial', 'B', 8);
                } else {
                    $this->SetFont('Arial', '', 8);
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
            $this->SetXY($l, $h, $fullwidth+$l, $h);
        $this->SetFont('Arial','B',8);
        $this->Cell(185,8,'Total',1,0,'C');
        $this->Cell(26,8,"$this->total_pagu_dpa",1,0,'R');
        $this->Cell(26,8,"$this->total_pagu",1,0,'R');
        $this->Cell(26,8,"$this->total_pagu_selisih",1,0,'R');

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
?>
