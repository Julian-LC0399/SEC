<?php
    session_start();
    require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Utils/Functions.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Utils/SessionManager.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Constant.php");
    if($_SESSION['profile']!== Profile::admin){
        session_unset();
        session_destroy();              
        header("Location: ../index.php");
        exit();
    }
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
    <link rel="stylesheet" href="../Graphic/fontAwesome/css/all.css">
    <title>Sistema Estado de Cuenta</title>
  </head>
  <body id="body">
        <h5 class="form-title">Simulador de Estado de Cuenta</h5>
        <div class="text-center form">
                <form class="row text-center row-form" id="Form" name="Form">
                <input type="text" id="action" class="" name="action" style="display: none;" value="BySimulator"/>
                <div class="col-md-4">
                    <label for="Account" class="">NUMERO DE CUENTA</label>
                    <input type="text" class="form-control form-control-sm text-center form-search" id="Account" name="account" maxlength="23" required autocomplete="off">
                </div>
                <div class="col-md-3">
                <label for="Month" class="">MES</label>
                    <select id="Month" class="form-select form-select-sm form-search" name="month" style="width: 60%;margin: 0 auto;">
                        <?php echo GetMonth();?>
                    </select>
                </div>

                <div class="col-md-3">
                <label for="Year" class="">AÑO</label>
                    <select id="Year" class="form-select form-select-sm form-search" name="year" style="width: 60%;margin: 0 auto;" >
                        <?php echo GetYear();?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" id="send-data" class="btn  text-center btn-search" style="margin-top:-4px;width:40%">
                        <i class="fas fa-search"> </i>
                    </button>
                </div>
                </form>
        </div>

        <div class="errores"></div>
        <div class="table-responsive" id="tableDinamic"></div>

    
  </body>
  <script type="text/javascript" src="../Script/Vendor/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="../Script/Vendor/jquery.min.js"></script>
  <script type="text/javascript" src="../Script/Vendor/jquery.mask.js"></script>
  <script type="text/javascript" src="../Script/Vendor/moment.js"></script>
  <script type="text/javascript" src="../Script/Vendor/accounting.js"></script>
  <script type="text/javascript" src="../Script/Vendor/jquery.validate.min.js"></script>
  <script type="text/javascript" src="../Script/tableSimulador.js?1"></script>

<script type="text/javascript">

$(document).ready(function () {
        $('#Account').mask('####-####-##-##########');
        $('#Account').change(function () {
                var valor = $(this).val();  
                $(this).val(valor);
        });  

        $("form[name='Form']").validate({
        rules: {
            account: {
                required:true,
                minlength: 23
            }
        },
        messages: {
            account: {
                required:" Por favor, introduzca un número de cuenta",
                minlength:" La longitud de la cuenta debe ser de 20 dígitos"
                }
        },
    
        submitHandler: function(form) {
                    var formdata = $('#Form').serialize();
                    var url = "../Engine/Core/Ajax.php";
                        window.parent.AjaxSender(formdata,url).then(function (_respond){                                    
                                if(!_respond.error){ 
                                    $('.error').remove();
                                    $('#tableDinamic').remove();
                                     $('#body').append('<div class="table-responsive pt-4" id="tableDinamic"></div>'); 
                                    var header    = _respond.header; //Encabezado
                                    var movements = _respond.movements; //Movimientos
                                    console.log(header);
                                    console.log(movements);
                                    fillTable(header,movements);
                                }else{
                                $('.error').remove();
                                $('.errores').append("<p class='error text-center pt-2'>"+_respond.details+"</p>")
                                $('#tableDinamic').attr('class','d-none');
                                }
                        });                                 
    
        }

        });

});   
</script>
  
</html>
