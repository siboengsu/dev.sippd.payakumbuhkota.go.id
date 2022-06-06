<?php
//call main fpdf file
include_once APPPATH . '/third_party/fpdf/fpdf.php';
//require('fpdf/fpdf.php');
//create new class extending fpdf class
class L_rekapitulasi_pagu_indikatif extends FPDF {
// variable to store widths and aligns of cells, and line height
public $data;
public $Tahap;
var $widths;
var $aligns;
var $lineHeight;
public $nomor;
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
public function setDataTahap($d){
    $this->Tahap = $d;
}
public function setNumber($input4){
   $this->nomor =$input4;

  }
function Header()
{
    // Logo
    $this->Image(base_url('assets/img/logo-kota-payakumbuh.png'), 10, 6, 10);
    // Arial bold 15
    $this->SetFont('Arial','B',8);
    // Move to the right

    // Line break
     if ($this->Tahap =="Perubahan"){
         $this->Cell(120);
       $this->MultiCell(70,3,"$this->Tahap Rencana Kerja Pemerintah Daerah (RKPD) Kota Payakumbuh Tahun $this->data",0,'R');

     }else {
         $this->Cell(133);
       $this->MultiCell(55,3,"$this->Tahap Rencana Kerja Pemerintah Daerah (RKPD) Kota Payakumbuh Tahun $this->data",0,'R');
     }

    $this->Ln(2);
      // mencetak string
      $this->Ln(2);
      if($this->PageNo()==1){
        // mencetak string
        $this->Ln(1);

        $this->Cell(205,4,' ',0,1,'C');
        $this->Cell(205,4,"REKAPITULASI RENCANA PROGRAM DAN KEGIATAN PRIORITAS DAERAH TAHUN ANGGARAN $this->data",0,1,'C');
        $this->Cell(205,4,'MENURUT PERANGKAT DAERAH',0,1,'C');
        $this->Cell(205,4,'',0,1,'C');
      //  $this->Cell(10,2,'',0,1);
        $this->Ln(3);
        $tinggi = 37;
      }else {
        $tinggi = 23;
        $this->Ln(4);
      }

    $A = 10;
    $B = 89;
    $C = 28;
    $D = 28;
    $E = 28;

  //  $H = 30;
  //  $I = 10;
//	$J = 22;
//    $K = 22;
    //set width for each column (12 column)
    $this->SetWidths(Array($A,$B,$C,$D,$E));
    //set line height
    $this->SetLineHeight(7);
    $x =$this->GetX();
    $y =$this->GetY();

    $this->SetFont('Arial','B',8);
    //line 1
    $this->Cell(3,4,'',0,0,'C');
    $this->Cell($A,6,'No',1,0,'C');
    $this->Cell($B,6,'PERANGKAT DAERAH',1,0,'C');
    $this->Cell($C,6,'PAGU',1,0,'C');
  	$this->Cell($D,6,'PAGU INDIKATIF',1,0,'C');
  	$this->Cell($E,6,'SELISIH',1,0,'C');
    $this->Ln();
    $this->SetFont('Arial','B',6);
    $this->Cell(3,4,'',0,0,'C');
    $this->Cell($A,5,'1','1',0,'C');
    $this->Cell($B,5,'2','1',0,'C');
    $this->Cell($C,5,'3','1',0,'C');
    $this->Cell($D,5,'4','1',0,'C');
    $this->Cell($E,5,'5=(3-4)','1',0,'C');
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
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',7);
    // Page number
    $num = intval($this->nomor) + $this->PageNo();

    $this->Cell(0, 10, 'V -  '.$num , 0, 0, 'R');

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
        $this->MultiCell($w,7,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
    //$this->Cell(3,5,'',0,0,'C');
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
    $wmax=($w-5*$this->cMargin)*1000/$this->FontSize;
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
