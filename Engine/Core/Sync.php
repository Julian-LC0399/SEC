<?php

/* Procesa y responde las solicitudes síncronas */
require_once($_SERVER["DOCUMENT_ROOT"]."/Sec/Engine/Core/SQL.php");

        function MovementsConsult($account, $month,$year){
                return Movements($account,$month,$year);
        }

?>