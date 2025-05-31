var ENVIRONMENT = 0; //0 Desarrollo
					 //1 Producción

function AjaxSender(formdata){
	return new Promise(function(success, error){
		var Ajax = $.ajax({
			type: "POST",
			url: "../Engine/Core/Ajax.php",
			data: formdata
		});
	
		Ajax.done(function(_resp){
			var _response = JSON.parse(_resp);
			console.log(_resp);
			
			var dev = _response.dev;     //detalles para nerds
			var msg = _response.details; //mensaje a mostrar en la notificación
			var dat = _response.data;	 //Datos de la operación si son necesarios de lo contrario null o  0 según sea el caso
			var typ = _response.msgtype; //tipo de mensaje a mostrar
			var ier = _response.error;   //es error el mensaje

			if (ier && ENVIRONMENT == 0) { console.log(dev); }
			success(_response);
		});
		Ajax.fail(function(_jqXHR, textStatus){
			console.log(textStatus);
			error(false);
		});
	});
}

