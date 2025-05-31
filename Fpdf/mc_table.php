<?php
//call main fpdf file
date_default_timezone_set('America/Caracas');

require('fpdf.php');
//require_once('../Engine/Utils/Functions.php');

//create new class extending fpdf class
class PDF_MC_Table extends FPDF {

    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }

	function Header()
{

    $month = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

    if (isset($_GET['account']) && !empty($_GET['account'])){
        $cuenta =$_GET['account'];
        
    }
    // Logo
    //$saldoInicial = 0;
    $sumaDebito = 0;
    $sumaCredito =0;
    $SaldoFinal = 0;
    // $saldoInicial = $_GET['saldoinicial'] <= 0 ? '0,00' : formatMoney($_GET['saldoinicial']);
    $saldoInicial = formatMoney($_GET['saldoinicial']);
    $this->Image('../Graphic/Image/LogoBanco.png',5,12,60);
    $this->SetFont('Arial','',10);
    $this->setFillColor(233,233,233);
    $this->SetDrawColor(0,0,0);
    $this->SetY(-5);
    $this->SetX(70);
    $this->setFillColor(184,172,127);
    $this->SetY(12);
    $this->SetX(70);
    $this->SetFont('Arial','B',12);
    $this->Cell(135,5,$_GET['nombre'],0,1,'R',0);
    $this->SetY(18);
    $this->SetX(70);
    $this->SetFont('Arial','',8);
    $direccion = explode(",",$_GET['direccion'] );
    $this->SetX(70);
    $this->Cell(135,3,$direccion[0],0,1,'R',0);
    $this->SetX(70);
    $this->Cell(135,3,$direccion[1],0,1,'R',0);
    $this->SetX(70);
    $this->Cell(135,3,$direccion[2],0,1,'R',0);
    //$this->SetY(22);
    //$this->SetX(70);
   
    $this->SetY(40);
    $this->SetX(40);
    $this->SetFont('Arial','',14);
    $this->SetTextColor(123,125,125);
     /* Encabezado de la tabla*/
     $this->SetTextColor(0,0,0); 
     $this->SetFont('Arial','B',12);
     $this->setFillColor(233,233,233);
     $this->SetY(35);
     $this->SetX(2);
     $this->Cell(205,10,'ESTADO DE CUENTA '.strtoupper($month[$_GET['mes']-1]).' '.$_GET['year'],0,1,'C',1);
     $this->setFillColor(184,172,127); 
          //TABLA RESUMEN
      $this->SetY(30);
      $this->SetX(2);
      $this->SetFont('Arial','',9);
      $Now = date('d-m-Y H:i A');
      $this->Cell(76,5,'Fecha Emisión: '.$Now,0,1,'L',0);
      $this->SetY(30);
      $this->SetX(130);
      $this->SetFont('Arial','',9);
      $this->Cell(76,5,'NUMERO DE CUENTA: '.$cuenta,0,1,'L',0);
      $this->SetFont('Arial','',9);
      
      if ($this->PageNo() === 1){
        //SALDO INICIAL  
        $this->SetY(45);
        $this->SetX(2);
        $this->setFillColor(245,245,245);
        $this->Cell(205,5,'SALDO INICIAL:                 '.$saldoInicial ,0,1,'R',1);
        //FIN DE SALDO INICIAL
        $this->setFillColor(184,172,127); 
        $this->SetY(50);
        $this->SetX(2);
        $this->Cell(25,5,'Fecha',1,0,'C',1);
        $this->Cell(20,5,'Serial',1,0,'C',1);
        $this->Cell(55,5,'Descripción',1,0,'C',1);
        $this->Cell(35,5,'Débito',1,0,'C',1);
        $this->Cell(35,5,'Crédito',1,0,'C',1);
        $this->Cell(35,5,'Saldo',1,0,'C',1);
        $this->Ln(10);
     }else{
    //    //ENCABEZADO DE LA TABLA
        $this->setFillColor(184,172,127); 
        $this->SetY(45);
        $this->SetX(2);
        $this->Cell(25,5,'Fecha',1,0,'C',1);
        $this->Cell(20,5,'Serial',1,0,'C',1);
        $this->Cell(55,5,'Descripción',1,0,'C',1);
        $this->Cell(35,5,'Débito',1,0,'C',1);
        $this->Cell(35,5,'Crédito',1,0,'C',1);
        $this->Cell(35,5,'Saldo',1,0,'C',1);
        $this->Ln(7);
        //FIN DE ENCABEZADO DE LA TABLA
    }
    //MARCA DE AGUA 
    // $this->SetFont('Arial','B',55);
    // $this->SetTextColor(234,230,229);
    //$this->RotatedText(22,200,'C O N F I D E N C I A L',32);
    // $this->RotatedText(55,200,'  P R U E B A ',32);
    //FIN DE MARCA DE AGUA  

}


var $angle=0;
 
function Rotate($angle,$x=-1,$y=-1)
{
    if($x==-1)
        $x=$this->x;
    if($y==-1)
        $y=$this->y;
    if($this->angle!=0)
        $this->_out('Q');
    $this->angle=$angle;
    if($angle!=0)
    {
        $angle*=M_PI/180;
        $c=cos($angle);
        $s=sin($angle);
        $cx=$x*$this->k;
        $cy=($this->h-$y)*$this->k;
        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    }
}
 
function _endpage()
{
    if($this->angle!=0)
    {
        $this->angle=0;
        $this->_out('Q');
    }
    parent::_endpage();
}

function Footer($lastPage = true)
{
    //Posición: a 1,5 cm del final
    // $saldoPromedio = formatMoney($_GET['saldopromedio']);
    $saldoPromedio = $_GET['saldopromedio'];
    // $saldoPromedio = $_GET['saldopromedio'] <=0 ? '0,00' : formatMoney($_GET['saldopromedio']);
    // $totalDebitos = formatMoney($_GET['totaldebitos']);
    $totalDebitos = $_GET['totaldebitos'];
    // $totalDebitos = $_GET['totaldebitos'] <=0 ? '0,00' : formatMoney($_GET['totaldebitos']);
    // $totalCreditos = formatMoney($_GET['totalcredito']);
    $totalCreditos = $_GET['totalcredito'];
    // $totalCreditos = $_GET['totalcredito'] <=0 ? '0,00' : formatMoney($_GET['totalcredito']);
    // $saldoFinal = formatMoney($_GET['saldofinal']);
    $saldoFinal = $_GET['saldofinal'];
    // $saldoFinal = $_GET['saldofinal'] <=0 ? '0,00' : formatMoney($_GET['saldofinal']);
    $moneda = str_replace('EUR', utf8_encode(chr(128)), $_GET['moneda']);

    //Número de página
    if (!$lastPage){
        $this->SetY(-10);
        //Arial italic 8
        $this->SetFont('Arial','B',9);
        $this->Cell(0,10,'Página '.$this->PageNo().' / {nb}',0,0,'C');
    }
    else{
        $this->SetFont('Arial','',9);
        $this->SetDrawColor(0,0,0);
        $this->setFillColor(184,172,127);
        $this->SetY(-20);
        $this->SetX(5); 
        $this->Cell(50,5,'Saldo Promedio',1,1,'C',1);
        $this->SetX(5);
        $this->Cell(50,5,$moneda.' '.$saldoPromedio,1,1,'R',0);
        /*$this->Cell(50,5,$saldoPromedio.' '.$moneda,1,1,'R',0);*/
    
        $this->SetY(-20);
        $this->SetX(55);
        $this->Cell(50,5,'Total Débitos',1,1,'C',1);
        $this->SetX(55);
        $this->Cell(50,5,$moneda.' '.$totalDebitos,1,1,'R',0);
        /*$this->Cell(50,5,$totalDebitos.' '.$moneda,1,1,'R',0);*/
        
        $this->SetY(-20);
        $this->SetX(105);
        $this->Cell(50,5,'Total Créditos',1,1,'C',1);
        $this->SetX(105);
        $this->Cell(50,5,$moneda.' '.$totalCreditos,1,1,'R',0);
        /*$this->Cell(50,5,$totalCreditos.' '.$moneda,1,1,'R',0);*/

        $this->SetY(-20);
        $this->SetX(155);
        $this->Cell(50,5,'Saldo Final',1,1,'C',1);
        $this->SetX(155);
        $this->Cell(50,5,$moneda.' '. $saldoFinal,1,1,'R',0);
        /*$this->Cell(50,5,$saldoFinal.' '.$moneda,1,1,'R',0);*/
        $this->SetY(-10);
        //Arial italic 8
        $this->SetFont('Arial','B',9);
        $this->Cell(0,10,'Página '.$this->PageNo().' / {nb}',0,0,'C');
    }
} 

// variable to store widths and aligns of cells, and line height
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
        $this->MultiCell($w,5,$data[$i],0,$a);
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