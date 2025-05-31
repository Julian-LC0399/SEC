<?php

	/*CONSTANTES PARA CONEXIÓN SQL SERVER*/
	class Db {
		const serverName     = 'MTAuMTAuMS4xNjJcU1JWX0FQTElD';
	 	const userDb         = 'VXNyQ05YRw==';
	 	const passwordDb     = 'VXNyQ05YRw==';
	 	const dbNameSecurity = 'GLOBAL_SECURITY';
	 	const dbNameAudit    = 'EDO_CUENTA'; 
	 }
	

	/*CONSTANTES PARA CONEXIÓN AS400*/
	/*class As400 {
		const system      = 'MTAuMTAuMS4yNDM='; //10.10.1.243
		const userAs400   = 'UFJVR1JBTw==';
		const passwordDb  = 'QVhFODIw';
	}*/

	class As400 {
		const system      = 'MTAuMTAuMS43Mw==';
		const userAs400   = 'VFJBTkZFUg==';
		const passwordDb  = 'Y2Fyb25pMTA=';
	}
	
	/*CONSTANTES PARA STATUS DE USUARIOS*/
	class Status {
		const __default    = self::Active;

		const Active       = 'ACTI';
		const Locked       = 'BLOQ';
        const Disabled     = 'DESC';
	}

	/*PERFIL*/
	class Profile{
		const admin    = '3';
	}

	/*CONSTANTES PARA MENSAJES DEL SISTEMA*/
	class MSG {
		const AccountDisabled  		= "Su cuenta se encuentra deshabilitada";
		const AccountLocked    		= "Su cuenta se encuentra bloqueada";
		const LoginFailed      		= "Error en la autenticación, revise los datos y vuelva a intentar";
		const Login            		= "Recibida la petición de inicio de sesión";
		const LoginSuccess     		= "La información es correcta, ha iniciado sesión";
		const LoginFailed3     		= "El usuario es válido, pero carece de autorización para iniciar sesión";

		const AccountReader    		= "Recibida la solicitud de consulta de numero de cuenta";
		const HeaderReader     		= "Recibida la solicitud de consulta encabezado de la cuenta";
 		const MovementsReader  		= "Recibida la solicitud de consulta de Movimientos";
		const MonthReader      		= "Recibida la solicitud de consulta por mes";
		const RangeReader      		= "Recibida la solicitud de consulta por rango";
		const SimulatorMonthReader  = "Recibida la solicitud de consulta por mes (Simulador)";

		const InvalidAccount   		= "Número de cuenta inválido o no existe";
		const MissingHeader    		= "El mes consultado no posee encabezado";
		const MissingDetails   		= "El mes consultado no posee movimientos";
	}
?>