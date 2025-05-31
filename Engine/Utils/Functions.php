<?php

setlocale(LC_ALL,"es_ES");
$dayOfWeek = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
$month = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

    /*FUNCION PARA LLENAR LOS SELECT DEL MES*/
    function GetMonth(){
        $month = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            for ($i=1; $i<=12; $i++) {
                $html .= '<option value="'.$i.'">'.$month[($i)-1].'</option>';
            }return $html;
        }
    
    /*FUNCION PARA LLENAR LOS AÑOS*/
    function GetYear(){
        $currentDate = date('Y');
        $oldDate = strtotime ('-20 year' , strtotime ($currentDate)) ;
        $oldDate = date ( 'Y' ,$oldDate);
            for($i=$currentDate;$i>=$oldDate;$i--) { 
                $html .='<option value="'.$i.'">'.$i.'</option>'; 
            } return $html;
         }

    /*FUNCION PARA FORMATEAR UN MONTO*/    
    function formatMoney($monto){
        return number_format($monto, 2, ',', '.');
    }   
        

?>