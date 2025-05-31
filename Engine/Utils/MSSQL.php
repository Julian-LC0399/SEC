<?php /*DB ADMIN CODE Microsoft Sql Server*/
     include_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Constant.php");

     /*FUNCION PARA CONECTARSE A SQL SERVER*/
    function GetDBDelegateMSSQL($dbName){
        $serverName = base64_decode(Db::serverName);
        $connectionInfo = array("Database"=>$dbName, "UID"=>base64_decode(Db::userDb), "PWD"=>base64_decode(Db::passwordDb));
        $dbcnx = sqlsrv_connect($serverName, $connectionInfo);
        if($dbcnx === false ) {
             die( print_r( sqlsrv_errors(), true));
            }
        return $dbcnx;
    }

    /*FUNCION PARA REALIZAR LOS SELECT*/
    function ExecuteSQLRequestMSSQL($Sentence,$dbName){
        $rows = array();
        $cnxsql= GetDBDelegateMSSQL($dbName);
        $cnxsqlrs = sqlsrv_query($cnxsql,$Sentence);
        while ($row = sqlsrv_fetch_array($cnxsqlrs)) {
            $rows[] = $row;
        }
        sqlsrv_close($cnxsql);
        return $rows;
    }

    /*FUNCION PARA REALIZAR LOS INSERT, UPDATE Y DELETE*/
    function ExecuteSQLCommand($Sentence,$dbName){
		$rows = -1;
		$cnxsql = GetDBDelegateMSSQL($dbName);
        $cnxsqlrs = sqlsrv_query($cnxsql,$Sentence);
		$rows = sqlsrv_rows_affected($cnxsqlrs);
		sqlsrv_close($cnxsql);
		return $rows;
	}

    
?>