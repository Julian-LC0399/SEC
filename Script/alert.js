$(document).ready(function() {			
	$('#closeAlert').click(function (e) {
		if (intrvlCA != null) { clearInterval(intrvlCA); }
		$('#msg').fadeOut();
		window.setTimeout(function () { $('#msg').removeClass(); $('#msg').addClass('message'); }, 1000);
	});		
});


function serverAd(message, type){
	$('#msg span').text(message);
	customAlert(type);
}

var intrvlCA;
function customAlert(type) {
	var style;
	$('#msg').css('left', ((100 - ((($('#msg').width() + 48) * 100) / $('body').width())) / 2).toString() + '%');
	switch (type) {
		case 0:
			style = 'alert-danger'
			break;
		case 1:
			style = 'alert-warning'
			break;
		case 2:
			style = 'alert-info'
			break;
		case 3:
			style = 'alert-success'
		break;
	}

	$('#msg').addClass(style);
	$('#msg').fadeIn(500);

	var n = 0;

	if (intrvlCA != null) { clearInterval(intrvlCA); }
	intrvlCA = setInterval(function () {
		n++;
		if (n > 3) { $('#msg').fadeOut(300); }
		if (n > 5) {
			$('#msg').removeClass(style);
			clearInterval(intrvlCA);
		}
	}, 1000);
}