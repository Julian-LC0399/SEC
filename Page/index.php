<?php
    session_start();
    require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Utils/Functions.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Utils/SessionManager.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Constant.php");
    $ShowingPage = (!isset($_SESSION['authuser'])) ? '../index.php': 'home.html';
?>
<!Doctype html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../Graphic/Icon/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="../Graphic/Icon/favicon.ico" type="image/x-icon" />
    <!-- Bootstrap CSS -->
    <link href="../Style/Vendor/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Style/style.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css"> -->
    <link rel="stylesheet" href="../Graphic/fontAwesome/css/all.css">
    <title>Sistema Estado de Cuenta</title>
  </head>
  <body>
   
        <!-- Vertical navbar -->
        <div class="vertical-nav bg-white" id="sidebar">
        <div class="py-2 px-2 mb-4 bg-light">
            <div class="media d-flex align-items-center">
                <a href=""><img src="../Graphic/Image/LogoBanco.png" width="100%"></a>
            </div>
        </div>

        <p class=" text-center text-gray font-weight-bold text-uppercase py-4 mb-0">CONSULTAS</p>

        <ul class="nav flex-column bg-white mb-0">
            <li class="nav-item">
            <a href="byRange.php" class="nav-link text-dark" target="ContentPage">
                        <!-- <i class="bi bi-search mr-3 text-wine"> </i> -->
                        <i class="fas fa-search mr-3 text-wine"> </i>
                        Consulta por rango
                    </a>
            </li>
            <li class="nav-item">
            <a href="byMonth.php" class="nav-link text-dark" target="ContentPage">
                        <!-- <i class="bi bi-calendar mr-3 text-wine"> </i> -->
                        <i class="fas fa-calendar mr-3 text-wine"></i>
                        Consulta por mes
                    </a>
            </li>
            <?php if ($_SESSION['profile']===Profile::admin){?>
            <li class="nav-item">
            <a href="simulator.php" class="nav-link text-dark" target="ContentPage">
                        <!-- <i class="bi bi-file-earmark-check mr-3 text-wine"> </i> -->
                        <i class="fas fa-clone mr-3 text-wine"></i>
                        Simulador  
                    </a>
            </li> 
            <?php };?>
        </ul>

        <p class="text-center text-gray font-weight-bold text-uppercase py-4 mb-0">PERFIL</p>

        <ul class="nav flex-column bg-white mb-0">
             <li class="nav-item">
            </li> 
            <li class="nav-item">
            <a href="#" class="nav-link text-dark"  id="logout">
                        <!-- <i class="bi bi-box-arrow-in-right mr-3 text-wine"> </i> -->
                        <i class="fas fa-sign-out-alt mr-3 text-wine"></i>
                        Cerrar sesión
                    </a>
            </li>
        </ul>
        </div>
        
        <!-- End vertical navbar -->
        
        <div id="loader"style="display:none;"></div>
        <iframe id="ContentPage" name="ContentPage" src=<?php echo $ShowingPage; ?>>
            <!--style="width: 100%; height: 90.5vh; overflow-x: auto; overflow-y: auto; border: none;"-->				
        </iframe>
  </body>
  <script type="text/javascript" src="../Script/Vendor/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="../Script/Vendor/jquery.min.js"></script>
  <script type="text/javascript" src="../Script/Vendor/jquery.mask.js"></script>
  <script type="text/javascript" src="../Script//index.js"></script>
  <script type="text/javascript" src="../Script//alert.js"></script>
  <script type="text/javascript">
        $(document).ready(function () {
                $('#Account').mask('####-####-##-##########');
                $('#Account').change(function () {
                var valor = $(this).val();  
                    $(this).val(valor);
                });
            });
                // $.ajax({
                // type: "POST",
                // url: "Fpdf/mc_table.php",
                // data: { name: "John", location: "Boston" }
                // }).done(function( msg ) {
                // alert( "Data Saved: " + msg );
                // });
  </script>
  
</html>

