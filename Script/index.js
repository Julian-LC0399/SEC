
$( document ).ready(function() {
	$(document.getElementById("logout")).on("click", function (){
		AjaxSender("action=SignOff","../Engine/Core/Ajax.php","La sesión se cerrará").then(function (isSuccess){
			if(isSuccess){ location.href = '../index.php'; }
		});
	});
});					 

//FUNCION PARA REALIZAR LAS SOLICITUDES AJAX
function AjaxSender(formdata, url,notify = true){
	loader(show = true);
	return new Promise(function(success, error){
		var Ajax = $.ajax({
			type: "POST",
			url:  url,
			data: formdata
		});
	
		Ajax.done(function(_resp){
			var _response = JSON.parse(_resp);
			//console.log(_resp);
			
			var msg = _response.details; //mensaje a mostrar en la notificación
			var dat = _response.data;	 //Datos de la operación si son necesarios de lo contrario null o  0 según sea el caso
			var typ = _response.msgtype; //tipo de mensaje a mostrar
			var ier = _response.error;   //es error el mensaje

			if (ier){ 
				serverAd(msg, typ); 
			}
			loader(false);
			success(_response);
		});
		Ajax.fail(function(_jqXHR, textStatus){
			console.log(textStatus);
			loader(false);
			error(false);
		});
	});
}

//FUNCION PARA CARGAR O OCULTAR EL LOADER
function loader(show){
	if(!show)
		$("#loader").hide();
	else
		$("#loader").show();
}

