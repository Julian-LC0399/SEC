<?php /*DB ADMIN CODE AS400(DB2)*/


	include_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Constant.php");

    /*FUNCION PARA CONECTARSE AL AS400*/
	function GetDBDelegateAs400(){
		$system = base64_decode(As400::system);
		$dsn ="DRIVER={iSeries Access ODBC Driver};SYSTEM=".$system.";";
		$usr = base64_decode(As400::userAs400);
		$pass = base64_decode(As400::passwordDb);
		$dbcnx = odbc_connect($dsn, $usr, $pass);
		return $dbcnx;
	}
	
    /*FUNCION PARA REALIZAR SELECT*/  
    function ExecuteSQLRequestAS400($Sentence){
        $rows = array();
        $cnxodbc= GetDBDelegateAs400();     
        $cnxodbcrs = odbc_exec($cnxodbc, $Sentence);
        while ($row = odbc_fetch_array($cnxodbcrs)) {
            $rows[] = $row;
        }
        odbc_close($cnxodbc);
        return $rows;
    }	
?>