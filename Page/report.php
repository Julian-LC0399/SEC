<?php
    include_once("../FPDF/mc_table.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Core/Sync.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Utils/Functions.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Utils/SessionManager.php");


    $pdf = new PDF_MC_Table(); 
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->setMargins(2,2,2,2);
    $pdf->SetFont('Arial','', 9);
    $pdf->SetWidths(array(25,20,55,35,35,35));
    $pdf->SetAligns(Array('C','R','L','R','R','R'));
    $pdf->SetLineHeight(7);

    $months = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    
    $account=$_GET['account']; 
    $month=$_GET['mes'];
    $year=$_GET['year'];
    
    $rs = MovementsConsult($account,$month,$year)['data'];
    $pdf->setY(56);
    $pdf->setX(2);
    $pdf->SetLineWidth(0);
    $pdf->SetDrawColor(254,254,254);
        if (count($rs)>0){
                $sum = $_GET['saldoinicial']; 
                foreach ($rs as  $rsi) {
                    $amount= $rsi['MONTO'];
                    if($rsi['TIPO']==="0"){
                        $sum = $sum - $amount;
                    }else{
                        $sum = $sum + $amount;
                    }
                    $pdf->Row(array($rsi['FECHA_PROCESO'],$rsi['SERIAL'],strtoupper($rsi['DESCRIPCION']),($rsi['TIPO']=="0")?formatMoney($amount):" ",($rsi['TIPO']=="5")?formatMoney($amount):"",formatMoney($sum)));
                }
        }else{
                $pdf->Cell(215,10,'NO SE ENCONTRARON MOVIMIENTOS PARA ESTE MES ',0,0,'C',0);;
        }
            
    $pdf->Ln(7);
    $pdf->Output($account.' '.strtoupper($months[$month-1]).' '.$year.'.pdf','I');
?>
