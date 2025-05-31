<?php /*Procesa todas las sentencias SQL*/
	require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Utils/AS400.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Utils/MSSQL.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Constant.php");

	date_default_timezone_set("America/Caracas");

	/*/METODOS DE SESSION/*/
        /*VALIDA SI EL USUARIO Y LA CLAVE SON CORRECTOS*/
        function Auth($user,$password){
            $q = "SELECT dbo.CLAVECORRECTA('$user','$password') as result";
            $rs = ExecuteSQLRequestMSSQL($q,Db::dbNameSecurity);
                if(count($rs)>0 && $rs[0]['result'] === 1){ 
                    return true;
                }else{ 
                    return false;
                } 
        } 
		/*VALIDA SI EL USUARIO TIENE PERMISOS PARA INGRESAR EN EL SISTEMA */
		function Login($params) {
			$err = true;
			$msg = MSG::Login;
			$typ = 0;
			$dat = null;
			SingOff();

			$username = $params['username'];
			$password = $params['password'];

			if(Auth($username,$password)){
              
				$q = sprintf("SELECT u.id_usuario AS id,
				              u.nombre AS name,
							  u.login as username,
							  u.estatus_u AS status,
							  u.cod_agencia AS agency,
							  u.cedula as passport,
							  p.id_perfil as profile
							  FROM usuario AS u, OPCIONES_USR_SIS_PER as p
                              WHERE u.login = '%s'
							  AND  p.id_usuario = u.id_usuario
                              AND p.id_sistema =  '5' ;",$username);

				$rs = ExecuteSQLRequestMSSQL($q,Db::dbNameSecurity);
                if(count($rs) > 0 && $rs[0]['status'] === Status::Active){
                    $userid = $rs[0]['id'];
					$usernm = $rs[0]['name'];
					$usernk = $rs[0]['username'];
					$useragc = $rs[0]['agency'];
					$userpass = $rs[0]['passport'];
					$profile = $rs[0]['profile'];
					$isAuth = true;
                    $msg= msg::LoginSuccess; 
                    session_start();
					session_regenerate_id();
					$_SESSION['nameuser']    = $usernk;/*Usuario para logearse*/
					$_SESSION['authuser']    = $isAuth;/*Si el usuario esta autenticado*/
					$_SESSION['nameU']        = $usernm; /*Nombre del usuario*/
					$_SESSION['iduser']      = $userid;/*Id Usuario*/
                    $_SESSION['agcuser']     = $useragc;/*Agencia*/
					$_SESSION['passport']    = $userpass; /*Cedula*/
					$_SESSION['profile']     = $profile; /*Para saber si es administrador*/
					$_SESSION['timeAccess']= time(); /*Hora al acceder al sistema*/
					session_write_close();
					$err = false;
					$typ = 0;
					
                  }elseif (count($rs) > 0 && $rs[0]['status'] === Status::Locked){
						$msg = msg::AccountLocked;
						$typ = 1;
                  }elseif (count($rs) > 0 && $rs[0]['status'] === Status::Disabled){
						$msg = msg::AccountDisabled;
						$typ = 1;
                }else{
					$msg = msg::LoginFailed3;
					$typ = 0;
				}
			} else {
				$msg = msg::LoginFailed;
				$typ = 0;
			}
			return array('error' => $err, 'details' => $msg, 'data' => $dat, 'dev' => ($err)? debug_backtrace(): null, 'msgtype' => $typ);
		}

		/*OBTENER DATOS DE LA CUENTA*/
		function GetAccountTen($account){
			$err = false;
			$msg = MSG::AccountReader;
			$typ = 0;
			$dat = null;

			$q = "SELECT 
					a.acrnac AS CUENTA_10,
					a.acrrac AS CUENTA_20,
					c.cusidn AS CEDULA_RIF,
					REPLACE(TRIM(c.cusna1),'#','N') AS NOMBRE,
					REPLACE(TRIM(c.cusna2),'#','N') || ', ' ||
					REPLACE(TRIM(c.cusna3),'#','N') || ', ' ||
					REPLACE(TRIM(c.cusna4),'#','N') AS DIRECCION,
					CASE WHEN b.acmccy = 'USD' THEN '$'
						 WHEN b.acmccy = 'EUR' THEN 'EUR' 
					ELSE 'Bs.'
					END AS MONEDA  
				FROM PRDCYFILES.acref a
				     LEFT JOIN PRDCYFILES.acmst b ON b.acmacc = a.acrnac
				     JOIN PRDCYFILES.cumst c ON c.cuscun = b.acmcun
			   WHERE a.acrrac = '$account'" ;
			
			$dat = ExecuteSQLRequestAS400($q);
			$err = (!count($dat) > 0) ? true : false;
			$msg = (!count($dat) > 0) ? MSG::InvalidAccount : '';
			return array('error' => $err, 'details' => $msg, 'data' => utf8_string_array_encode($dat), 'dev' => ($err)? debug_backtrace(): null, 'msgtype' => $typ, 'count'=>count($dat));
		}


		/*FUNCION PARA CONSULTA DE LOS MOVIMIENTOS*/
		function Movements($account,$month,$year){
			$err = false;
			$msg = MSG::MovementsReader;
			$dat = null;

			$rs = GetAccountTen(str_replace('-','',$account));
					for ($i = 0; $i < count($rs['data']); $i++){
						$account = $rs['data'][$i]['CUENTA_10'];
					}
			$month   = substr(str_repeat(0, 2).$month, - 2);
			$year    = substr($year, -2);

			$lib = ($year <= 12) ? 'PRPHISTO.STDTL' : 'PRDHISTO.STDTL'; 
		
			$q = "SELECT 
			((CASE WHEN STDBDD < 10 THEN '0' ELSE '' END)
			|| STDBDD || '/' || 
			(CASE WHEN STDBDM < 10 THEN '0' ELSE '' END)
			|| STDBDM || '/' || 
			(CASE WHEN STDBDY < 10 THEN '200' ELSE '20' END)
			|| STDBDY)  as FECHA_PROCESO,
			  ((CASE WHEN STDVDD < 10 THEN '0' ELSE '' END)
				|| STDVDD || '/' || 
				(CASE WHEN STDVDM < 10 THEN '0' ELSE '' END)
				|| STDVDM || '/' || 
				(CASE WHEN STDVDY < 10 THEN '200' ELSE '20' END)
				|| STDVDY) as FECHA_TRANS,
				CASE WHEN TRIM(stdna1) = ''  THEN SUBSTR(TRIM(STDNAR),1,25) ELSE SUBSTR(TRIM(STDNA1),1,25) END as DESCRIPCION,   
				/*SUBSTR(TRIM(STDNA1),1,25) as DESCRIPCION,*/
				STDAMT as MONTO,
				STDDCC AS TIPO,
				'' AS DEBITO,
				'' AS CREDITO,
				STDCKN AS SERIAL,
				STDTIM AS HORA
				FROM $lib
				WHERE 
				STDACC = $account
				AND STDBDM = $month 
				AND STDBDY = $year
				order by FECHA_PROCESO,HORA";
			
			$dat = ExecuteSQLRequestAS400($q);
			$err = (!count($dat) > 0) ? true : false;
			$msg = (!count($dat) > 0) ? MSG::MissingDetails : '';

			return array('error' => $err, 'details' => $msg, 'data' => utf8_string_array_encode($dat));;
		}
		
		/*FUNCION PARA CONSULTA DEL ENCABEZADO*/
		function Headers($account,$month,$year){
			$err = false;
			$msg = MSG::HeaderReader;
			$dat = null;

			/*$q ="SELECT STHACC AS CUENTA,
					REPLACE(TRIM(STHMA1),'#','N') AS NOMBRE,
					REPLACE(TRIM(STHMA2) || ', ' ||
					TRIM(STHMA3) || ', ' ||
					TRIM(STHMA4),'#','N') AS DIRECCION,
					STHLSN AS SALDOINICIAL,
					( CASE WHEN STHNAV < 0 THEN STHNAV * -1 ELSE STHNAV END ) AS SALDOPROMEDIO,
					( CASE WHEN STHLSB < 0 THEN STHLSB * -1 ELSE STHLSB END ) AS SALDOINICIALS,
					STHNBL AS SALDOFINAL,
					STHRDM as MES,
					STHRDY as YEARS
				FROM PRDHISTO.STHDR 
				WHERE STHACC = $account
				AND STHRDM =$month
				AND STHRDY = $year";*/

				$lib = ($year <= 12) ? 'PRPHISTO.STHDR  a' : 'PRDHISTO.STHDR    a '; 

				// SE LE INCLUYE LECTURA AL ARCHIVO CUMST CUANDO EL NOMBRE Y LA DIRECCION VIENEN EN BLANCO 07/04/2022 
				 $q ="SELECT STHACC AS CUENTA,
						REPLACE(TRIM(c.cusna1),'#','N') AS NOMBRE,
						REPLACE(TRIM(c.cusna2),'#','N') || ', ' ||
						REPLACE(TRIM(c.cusna3),'#','N') || ', ' ||
						REPLACE(TRIM(c.cusna4),'#','N') AS DIRECCION,
						STHNAV * -1 AS SALDOPROMEDIO,
						STHLSB * -1 AS SALDOINICIALS,
						CASE WHEN STHCCY = 'USD' THEN '$'
						 WHEN STHCCY = 'EUR' THEN 'EUR' 
						ELSE 'Bs.'
						END AS MONEDA,
						STHNBL AS SALDOFINAL,
						STHRDM as MES,
						STHRDY as YEARS
					FROM $lib
						JOIN prdcyfiles.acmst B ON b.acmacc = $account
						JOIN prdcyfiles.cumst C ON c.cuscun = b.acmcun
					WHERE STHACC = $account
					AND STHRDM = $month
					AND STHRDY = $year";
		
			$dat = ExecuteSQLRequestAS400($q);
			$err = (!count($dat) > 0) ? true : false;
			$msg = (!count($dat) > 0) ? MSG::MissingHeader : $msg;
			
			return array('error' => $err, 'details' => $msg, 'data' => utf8_string_array_encode($dat));
			}

			/*FUNCION PARA CONSULTA POR RANGO*/
			function ConsultbyRange($params){

				$err = false;
				$account   = $params['account'];
				$starMonth = $params['starMonth'];
				$endMonth  = $params['endMonth'];
				$year      = $params['year'];
				
				$mes = array();
				for ($i=$starMonth;$i<=$endMonth;$i++){
					$params = array('account'=>$account,'month'=>$i,'year'=>$year);
					$mes[$i] = ConsultbyMonth($params,0);
				}

				$err = ($mes[1]['error'])? true:false;
				$msg = ($err) ? MSG::InvalidAccount : MSG::RangeReader;
				
				if(!$err){
					$starMonthFormat = substr(str_repeat(0, 2).$starMonth, - 2);
					$endMonthFormat = substr(str_repeat(0, 2).$endMonth, - 2);
					$yearFormat = substr($year, -2);
					RegisterAudit($account,$starMonthFormat,$endMonthFormat,$yearFormat); //Registrar en la tabla de auditoria
				}
				
				return array('error'=>$err,'details'=>$msg,'data' => $mes);
			}

			/*FUNCION PARA CONSULTA POR MES*/
			function ConsultbyMonth($params,$register){
				$err = false;
				$msg = MSG::MonthReader;

				$rs = GetAccountTen(str_replace('-','',$params['account']));
				if($rs['error']!== true){
					$account = $rs['data'][0]['CUENTA_10'];
					$month   = substr(str_repeat(0, 2).$params['month'], - 2);
					$year    = substr($params['year'], -2);


					$header     = Headers($account,$month,$year);    //Consultar Encabezado
					$movements  = Movements($account,$month,$year); //Consultar Movimientos 

					if($register === 1){
						RegisterAudit($params['account'],$month,'',$year); //Registrar en la tabla de auditoria
					}
				}else{
					$err   = true;
					$msg   = $rs['details'];
				}
				
				return array('error'=>$err, 'details' =>$msg, 'header'=> $header, 'movements'=>$movements);
			}

			function ConsultSimulador($params){
				$err = false;
				$msg = MSG::SimulatorMonthReader;

				$rs = GetAccountTen(str_replace('-','',$params['account']));
				if($rs['error']!== true){
					$account   = $rs['data'][0]['CUENTA_10'];
					$name      = $rs['data'][0]['NOMBRE'];
					$direccion = $rs['data'][0]['DIRECCION'];
					$moneda    = $rs['data'][0]['MONEDA'];
					$month   = substr(str_repeat(0, 2).$params['month'], - 2);
					$year    = substr($params['year'], -2);


					$rsheader     = Headers($account,$month,$year);
					if($rsheader['error']!==true){
						$header = $rsheader;
					}else{
						$header = array('error'=>true,'data'=>array('CUENTA'=>$account,'NOMBRE'=>$name,'DIRECCION'=>$direccion,'MONEDA'=>$moneda));
					}    //Consultar Encabezado
					$movements  = Movements($account,$month,$year); //Consultar Movimientos 
				}else{
						$err   = true;
						$msg   = $rs['details'];
					}
				return array('error'=>$err, 'details' =>$msg, 'header'=> $header, 'movements'=>$movements);
			}


			/*FUNCION INSERTAR EN LA TABLA EDO_CUENTA PARA AUDITORIA*/
			function RegisterAudit($account,$starMonth,$endMonth,$year){

				session_start();
				$type_consult = 'C';
				$dateNow = date('Y-m-d H:i:s');
				$user = $_SESSION['nameuser'];

				
				$q = "INSERT INTO CONSULTA_CUENTA 
				(CUENTA_CONSULTA,
				TIPO_CONSULTA,
				FECHA_CONSULTA,
				USUARIO_CONSULTA,
				RANGO_INICIO_CONSULTA,
				RANGO_FIN_CONSULTA,
				YY_CONSULTA)
    			 VALUES ('".$account."',
				 '".$type_consult."',
				 '".$dateNow."',
				 '".$user."',
				 '".$starMonth."',
				 '".$endMonth."',
				 '".$year."')";

				$dat = ExecuteSQLCommand($q,Db::dbNameAudit);
			}

			/*FUNCION PARA CONVERTIR ARREGLOS EN CARACTERES UTF-8*/
			function utf8_string_array_encode(&$array){
				$func = function(&$value,&$key){
					if(is_string($value)){
						$value = utf8_encode($value);
					}
					if(is_string($key)){
						$key = utf8_encode($key);
					}
					if(is_array($value)){
						utf8_string_array_encode($value);
					}
				};
				array_walk($array,$func);
				return $array;
			}

			/*FUNCION PARA QUE FUERZE EL CIERRE DE SESION*/
				function SingOff() {
					@session_start();
					// Destruir todas las variables de sesión.
					$_SESSION = array();
					// Si se desea destruir la sesión completamente, borre también la cookie de sesión.
					// Nota: ¡Esto destruirá la sesión, y no la información de la sesión!
					if (ini_get("session.use_cookies")) {
							$params = session_get_cookie_params();
							setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
					}
					// Finalmente, destruir la sesión.
					session_destroy();
					$err = !isset($_SESSION['iduser']);
					$msg = $err ? "Sesión Finalizada": "No se finalizó la sesión correctamente";
					$dat = null;
					$typ = $err ? 0: 1;
					return array('error' => isset($_SESSION['iduser']), 'details' => $msg, 'data' => $dat);
				}
?>