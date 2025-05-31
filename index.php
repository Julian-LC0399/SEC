<?php
  require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Utils/Functions.php");
?>

<!Doctype html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="Graphic/Icon/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="Graphic/Icon/favicon.ico" type="image/x-icon" />
    <!-- Bootstrap CSS -->
    <link href="Style/Vendor/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style/style.css">
    <link rel="stylesheet" href="Style/alert.css">
    <link rel="stylesheet" href="Graphic/fontAwesome/css/all.css">
    <title>Sistema Estado de Cuenta</title>
  </head>
  <body>
 
      <div class="container-fluid">
              <!--ENCABEZADO -->
          <header>
              <div id="container-fluid">
                  <div class="row">               
                      <div class="col-6">
                      <img class="Logo" src="Graphic/Image/LogoBanco.png"></img>
                      </div>
                  </div>
              </div>            
          </header>  
          <div class="rotator">
                  <div class="marquee">
                      <marquee class="marquee-content">
                          <p>Bienvenido al Sistema de Estado de Cuenta </p>
                          <p>Banco Caroni Banco Universal</p>
                          <p>Hoy es <?php echo $dayOfWeek[date('w')]." ".date('d')." de ".$month[date('n')-1]. " del ".date('Y') ;?></p>
                      </marquee>
                  </div>
            </div>
              <!-- FIN ENCABEZADO-->

            <div class="container login-wrap">
              <div class="row d-flex align-items-center justify-content-center h-100 text-center">
                <div class="col-md-7 col-lg-5 col-xl-5">
                    <h2 class="title">SEC</h2>
                    <h4 class="mb-5 slogan">Sistema Estado de Cuenta</h4>
                        <form id="loginform">
                          <!-- Username input -->
                          <div class="form-outline mb-4 form-floating text-center">
                            <input type="text" class="form-control form-control-lg form-login" name="username" id="username" placeholder="Usuario" autocomplete="off" required>
                            <label for="username" class="input-login">
                              <i class="fas fa-user-tie"> </i> Usuario</label>
                          </div>
                          <!-- Password input -->
                          <div class="form-outline mb-4 form-floating">
                            <input type="password" class="form-control form-login" name="password" id="password" placeholder="Contraseña" autocomplete="off" required>
                            <label for="password" class="input-login">
                            <i class="fas fa-lock"> </i> Contraseña</label>
                          </div>
                          <!-- Submit button -->
                          <button type="submit" class="btn btn-lg btn-block text-center btn-login" id="signIn">Iniciar Sesión</button>
                        </form>
                </div>

              </div>
            </div>

            <div id="loader"style="display:none;"></div>
            <div id="msg" class="message">
              <span class="text">msg</span>
              <button id="closeAlert" class="icon">i</button>
            </div>
      </div>
     
    <script type="text/javascript" src="Script/Vendor/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Script/Vendor/jquery.min.js"></script>
    <script type="text/javascript" src="Script/index.js"></script>
    <script type="text/javascript" src="Script/alert.js"></script>
    <script>
        $(document).ready(function() {
                $('#loginform').submit(function(e) {
                  e.preventDefault();
                  var urlAjx = "Engine/Core/Ajax.php";
                  window.parent.AjaxSender("action=LogIn&" + $(this).serialize(),urlAjx, true).then(function (_respond){
                    if(!_respond.error){
                      var url = "Page/index.php";    
                      $(location).attr('href',url); }
                  });
                });
        });
	  </script>
   
  </body>
</html>


