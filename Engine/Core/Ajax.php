<?php
	/*/Recibe, procesa y responde las peticiones o solicitudes asíncronas /*/
	require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Core/SQL.php");

	$params = $_POST;
	$action = $_POST['action'];
	$_POST = array();

	switch($action){
		case 'LogIn': //INICIA UNA SESIÓN
			echo json_encode(Login($params));
			break;
		case 'SignOff': //FINALIZA TODAS LAS SESIONES DEL SISTEMA EN EL EQUIPO CLIENTE
			echo json_encode(SingOff());
			break;
		case 'ByMonth': //CONSULTA UNA CUENTA POR MES  
			echo json_encode(ConsultbyMonth($params,1));
			break;
		case 'ByRange': //CONSULTA UNA CUENTA POR RANGO
			echo json_encode(ConsultbyRange($params));
			break;
		case 'BySimulator': //CONSULTA UNA CUENTA POR EL SIMULADOR
				echo json_encode(ConsultSimulador($params));
			break;	
	}
	exit();
?>