$(document).ready(function(){
	$('#factura').on('change', function(e){
		if ($.trim($("input[id=factura]:checked").val()) === "") $('#facturaCampos').hide(150);
		else $('#facturaCampos').show(300);
	});
});