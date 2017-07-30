$(document).ready(function(){
	var dtp= $("#dtPrecios").DataTable({
		"ordering": false,
		"searching": false,
		"paging": false,
		"info": false,
		"columnDefs":[{"width":"25%",targets:[0, 1]}]
	});
	$('#dtPrecios_wrapper').append('<div class="row right"><button type="button" id="dtPreciosMas">Añadir otro renglón precio</button></div>');
	$('#dtPreciosMas').on('click', function(){
		r= $('#dtPrecios>tbody>tr').length;
		dtp.row.add([
		'<input name="Precios['+r+'][precio]" type="text" value="">',
		'<input name="Precios['+r+'][promo]" type="text" value="">',
		'<input name="Precios['+r+'][info]" type="text" value="">'
		]).draw(false);
    });
    var dti= $("#dtImagenes").DataTable({
		"ordering": false,
		"searching": false,
		"paging": false,
		"info": false
	});
	$('#dtImagenes_wrapper').append('<div class="row right"><button type="button" id="dtImagenesMas">Añadir otro renglón imagen</button></div>');
	$('#dtImagenesMas').on('click', function(){
		r= $('#dtImagenes>tbody>tr').length;
		dti.row.add([
		'<div class="imagen"></div>',
		'<div class="upload button tiny radius"><span>Buscar archivo</span><input name="ImagenArchivo['+r+']" type="file" /></div><input name="Imagenes['+r+'][n]" type="hidden" value="'+r+'" />',
		'<input name="ImagenPrincipal" type="radio" value="'+r+'" />',
		'<input name="Imagenes['+r+'][eliminar]" type="checkbox" value="1" />'
		]).draw(false);
		$('#dtImagenes .upload>input').change(function(){
	    	$( this ).parent().css( "background-color", "#c0c0c0" );
	    });
    });
    $('#dtImagenes .upload>input').change(function(){
    	$( this ).parent().css( "background-color", "#c0c0c0" );
    });
});