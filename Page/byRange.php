<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Utils/Functions.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SEC/Engine/Utils/SessionManager.php");
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

        <h5 class="form-title">Estado De Cuenta Por Rango</h5>
        
        <div class="text-center form">
                <form class="row text-center row-form" id="Form" name="Form">
                <input type="text" id="action" class="" name="action" style="display: none;" value="ByRange"/>
                <div class="col-md-4">
                    <label for="Account" class="">NUMERO DE CUENTA</label>
                    <input type="text" class="form-control form-control-sm text-center form-search" id="Account" name="account" maxlength="23" required autocomplete="off">
                </div>
                <div class="col-md-2">
                <label for="StarMonth" class="">DESDE</label>
                    <select id="StarMonth" class="form-select form-select-sm form-search" id="starMonth" name="starMonth"> 
                        <?php echo GetMonth();?>
                    </select>
                </div>
                <div class="col-md-2">
                <label for="EndMonth" class="">HASTA</label>
                    <select id="EndMonth" class="form-select form-select-sm form-search" id="endMonth" name="endMonth">
                        <?php echo GetMonth();?>
                    </select>
                </div>
                <div class="col-md-2">
                <label for="Year" class="">AÑO</label>
                    <select id="Year" class="form-select form-select-sm form-search" name="year">
                        <?php echo GetYear();?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn  text-center btn-search" style="margin-top:-4px;width:40%">
                        <i class="fas fa-search"> </i>
                    </button>
                </div>
                </form>
        </div>

        <div class="errores"></div>
        <div class="table-responsive" id="tableDinamic"> </div>
  
  </body>
  <script type="text/javascript" src="../Script/Vendor/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="../Script/Vendor/jquery.min.js"></script>
  <script type="text/javascript" src="../Script/Vendor/jquery.mask.js"></script>
  <script type="text/javascript" src="../Script/Vendor/moment.js"></script>
  <script type="text/javascript" src="../Script/Vendor/accounting.js"></script>
  <script type="text/javascript" src="../Script/Vendor/jquery.validate.min.js"></script>
  <script type="text/javascript" src="../Script/tableRange.js?1"></script>
  <script type="text/javascript">
        $(document).ready(function () {
                $('#Account').mask('####-####-##-##########');
                $('#Account').change(function () {
                var valor = $(this).val();  
                    $(this).val(valor);
                });


                let start = $("select[name='starMonth']").val();
                $("select[name='starMonth']").change(function(){
                    start = $(this).val();
                    
                });
                jQuery.validator.addMethod("mesFinal", function(value, element) {
                    //return this.optional( element ) || value >= start; 
                    /*Cambio 280422 para poder validar que el mes fin debe ser mayor que el mes inicio*/
                    return element.value >= parseInt(start) ;
                }, 'Debe ser mayor o igual al mes Inicio.');

                    $("form[name='Form']").validate({
                        rules: {
                            account : {required:true,minlength: 23},
                            endMonth: {mesFinal:true}
                        },
                        messages: {
                            account: { required: " Por favor, introduzca un número de cuenta",
                                       minlength:" La longitud de la cuenta debe ser de 20 dígitos"},

                        },
                    
                        submitHandler: function(form) {
                            var formdata = $('#Form').serialize();
                            var url = "../Engine/Core/Ajax.php";
                            window.parent.AjaxSender(formdata,url).then(function (_respond){        
                                                    
                                if(!_respond.error){

                                    let starMonth = $('#StarMonth').val();
                                    let endMonth  = $('#EndMonth').val();
                                    $('.error').remove();
                                    $('#tableDinamic').remove();
                                    $('#printAll').remove();
                                   
                                    var  btnPrintAll= "<a class='btn' id='printAll'><i class='fas fa-print'></i> Imprimir Todo</a>";
                                    $('#body').append(btnPrintAll);
                                    $('#body').append('<div class="table-responsive" id="tableDinamic" style="margin-top:36px;"></div>'); 
                    
                                        for(i=parseInt(starMonth);i<=parseInt(endMonth);i++){
                                            let header = _respond['data'][i]['header'];
                                            let movements = _respond['data'][i]['movements'];
                                            fillTable(i,header,movements); 
                                        }

                                    $(document.getElementById("printAll")).on("click", function (e){
                                        e.preventDefault();
                                        $(".btn-print").each(function(){
                                            var href = $(this).attr('href');
                                            downloadURI(href,'');
                                        });
                                    });
                                } else{
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

