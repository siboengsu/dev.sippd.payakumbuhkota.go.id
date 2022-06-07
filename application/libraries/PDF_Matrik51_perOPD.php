<?php
//call main fpdf file
include_once APPPATH . '/third_party/fpdf/fpdf.php';
//require('fpdf/fpdf.php');
//create new class extending fpdf class
class PDF_Matrik51_perOPD extends FPDF {
// variable to store widths and aligns of cells, and line height
public $data;
public $opd;
var $widths;
var $aligns;
var $lineHeight;
//Set the array of column widths
function SetWidths($w){
    $this->widths=$w;
}
//Set the array of column alignments
function SetAligns($a){
    $this->aligns=$a;
}
public function setData($input){
    $this->data = $input;
}
public function setDataskpd($d){
    $this->opd = $d;
}
function Header()
{
    // Logo
    $this->Image('http://172.18.4.2:7000/assets/img/logo-kota-payakumbuh.png',10,6,10);
    // Arial bold 15
    $this->SetFont('Arial','B',7);
    // Move to the right
    $this->Cell(220);
    // Title
    $this->MultiCell(70,2,"Perubahan Rencana Kerja Pemerintah Daerah (RKPD) Kota Payakumbuh Tahun $this->opd",0,'R');

  //  $this->Cell(200,10,'(RKPD) Kota Payakumbuh Tahun 2019',0,0,'R');
    // Line break
    $this->Ln(2);
    if($this->PageNo()==1){
      // mencetak string
      $this->Ln(1);
      $this->Cell(285,4,'Tabel V.I ',0,1,'C');
      $this->Cell(285,4,"RENCANA PROGRAM DAN KEGIATAN PRIORITAS DAERAH PADA PERUBAHAN RKPD TAHUN $this->opd",0,1,'C');
      $this->Cell(285,4,'PEMERINTAHAN KOTA PAYAKUMBUH',0,1,'C');
      $this->Cell(10,2,'',0,1);
      $this->SetFont('Arial','B',7);
      $this->Cell(15,3,"Organisasi / PD : $this->data",0,0,'L');
      $this->Ln(3);
      $tinggi = 37;
    }else {
      $tinggi = 23;
      $this->Ln(4);
    }
    $A = 25;
    $B = 28;
    $C = 8;
    $D = 20;
    $E = 10;
    $F = 25;
    $G = 13;
    $N = 13;
    $H = 20;
    $I = 13;
    $O = 13;
    $J = 22;
    $K = 22;
    $L = 22;
    $P = 22;
    $M = 15;
  
    //set width for each column (12 column)
    $this->SetWidths(Array($A,$B,$C,$D,$E,$F,$G,$N,$H,$I,$O,$J,$K,$L,$P,$M));
    //set line height
    $this->SetLineHeight(4);
    $x =$this->GetX();
    $y =$this->GetY();
    $this->SetFont('Arial','B',7);
      //line 1

      $this->Cell($A,21,'No',1,0,'C');
      $this->Cell($B,3.5,'','T,R',0);
      $this->Cell($C,6,'','T',0);
      $this->Cell($D+$E+$F+$G+$N+$H+$I+$O,5,'Indikasi Kinerja Program/Kegiatan/ Sub Kegiatan',1,0,'C');
      $this->Cell($J+$K+$L,5,"Pagu Indikatif Tahun $this->opd",1,0,'C');
      //$this->Cell(25,4,'','T,R',0);
      //	$this->Cell(25,6,'','T,R',0);
      $this->Cell($P,6,'','T,R',0);
      $this->Cell($M,6,'','T,R',0);
      $this->Cell(0,4,'',0,1);
      //line 2
      $this->Cell($A,4,'',0,0);
      $this->MultiCell($B,3,'Urusan Pemerintah Daerah/ Program/ Kegiatan/ Sub Kegiatan',0,'C');
      $this->SetXY($x +$A+$B, $tinggi);
      $this->MultiCell($C,5,'Prioritas','L,R','C');
      $this->SetXY($x +$A+$B+$C, $tinggi+2);
      $this->MultiCell($D+$E,5,'IKU (OPD)','L,R','C');
      $this->Ln(0);
      $this->SetXY($x +$A+$B+$C+$D+$E, $tinggi+2);
      $this->MultiCell($F+$G+$N,3,'Indikator Kegiatan (outcome) /
Indikator Subkegiatan (output)','L,R','C');
      $this->Ln(0);
      $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$N, $tinggi+2);
      $this->MultiCell($H+$I+$O,6,'Indikator Program','L,R','C');
      $this->Ln(0);
      $this->SetXY($x  +$A+$B+$C+$D+$E+$F+$G+$N+$H+$I+$O, $tinggi+2);
      $this->MultiCell($J,3.5,'                   Sebelum Perubahan','L,R','C');
      $this->Ln(0);
      $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$N+$H+$I+$O+$J, $tinggi+2);
      $this->MultiCell($K,3.5,'                   Setelah Perubahan','L,R','C');
      $this->Ln(0);
      $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$N+$H+$I+$O+$J+$K, $tinggi+2);
      $this->MultiCell($L,3.5,'                     Jumlah Perubahan (+/-)','L,R','C');
      $this->Ln(0);
      $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$N+$H+$I+$O+$J+$K+$L, $tinggi+4);
      $this->MultiCell($P,4,' Pagu 2022','L,R','C');
      $this->Ln(0);
      $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$N+$H+$I+$O+$J+$K+$L+$P, $tinggi);
      $this->MultiCell($M,4,'Penanggung Jawab','L,R','C');
      $this->Ln(0);
      $this->Cell(0,5,'',0,1);
      //line 3
      $this->Cell($A,4,'',0,0);
      $this->Ln(0);
      $this->SetXY($x+$A, $tinggi+8);
      $this->Cell($B,10,'','B',0);
      $this->Ln(0);
      $this->SetXY($x +$A+$B, $tinggi+8);
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
      $this->MultiCell($N,5,'Target Sebelum','T,L,R','C');
      $this->Ln(0);
      $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$N, $tinggi+8);
      $this->Cell($H,10,'Tolok Ukur',1,'T,L,B','C');
      $this->Ln(0);
      $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$N+$H, $tinggi+8);
      $this->MultiCell($I,5,'Target Sebelum','T,L,R','C');
      $this->Ln(0);
      $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$N+$H+$I, $tinggi+8);
      $this->MultiCell($O,5,'Target Sesudah','T,L,R','C');
      $this->Ln(0);
      $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$N+$H+$I+$O, $tinggi+8);
      $this->Cell($J,10,'','B,R',0);
      $this->Ln(0);
      $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$N+$H+$I+$O+$J, $tinggi+8);
      $this->Cell($K,10,'','B,R',0);
      $this->Ln(0);
      $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$N+$H+$I+$O+$J+$K, $tinggi+8);
      $this->Cell($L,10,'','B,R',0);
      $this->Ln(0);
      $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$N+$H+$I+$O+$J+$K+$L, $tinggi+8);
      $this->Cell($P,10,'','B,R',0);
      $this->Ln();
      $this->SetXY($x +$A+$B+$C+$D+$E+$F+$G+$N+$H+$I+$O+$J+$K+$L+$P, $tinggi+8);
      $this->Cell($M,10,'','B,R',0);
      $this->Ln();
      $this->Cell($A,5,'1','1',0,'C');
      $this->Cell($B,5,'2','1',0,'C');
      $this->Cell($C,5,'3','1',0,'C');
      $this->Cell($D,5,'4','1',0,'C');
      $this->Cell($E,5,'5','1',0,'C');
      $this->Cell($F,5,'6','1',0,'C');
      $this->Cell($G,5,'7','1',0,'C');
      $this->Cell($N,5,'8','1',0,'C');
      $this->Cell($H,5,'9','1',0,'C');
      $this->Cell($I,5,'10','1',0,'C');
      $this->Cell($O,5,'11','1',0,'C');
      $this->Cell($J,5,'12','1',0,'C');
      $this->Cell($K,5,'13','1',0,'C');
      $this->Cell($L,5,'14 = (13-12)','1',0,'C');
      $this->Cell($P,5,'15','1',0,'C');
      $this->Cell($M,5,'16','1',0,'C');
      $this->Ln();
}
//Set line height
function SetNB($h){
    $this->nb=$h;
}
// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-20);
    // Arial italic 8
    $this->SetFont('Arial','I',7);
    // Page number
    $this->Cell(0,10,'V -  '.$this->PageNo(),0,0,'R');
}
//Set line height
function SetLineHeight($h){
    $this->lineHeight=$h;
}
//Calculate the height of the row
function Row($data)
{
    // number of line
    $nb=0;
    // loop each data to find out greatest line number in a row.
    for($i=0;$i<count($data);$i++){
        // NbLines will calculate how many lines needed to display text wrapped in specified width.
        // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    }

    //multiply number of line with line height. This will be the height of current row
    $h=$this->lineHeight * $nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of current row
    for($i=0;$i<count($data);$i++)
    {
        // width of the current col
        $w=$this->widths[$i];
        // alignment of the current col. if unset, make it left.
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,4,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}
function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}
function NbLines($w,$txt)
{
    //calculate the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
}
?>