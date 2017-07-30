$(document).ready(function(){
	$('#pColor').colorPicker({
    	colors: ['#00BBB3','#179e50','#2980b9','#8e44ad','#444444','#cc0077','#f39c12','#d35400','#ee4036','#888888'],
    	itemwidth:45,
    	itemheight:35,
    	rowitem:5,
    	alignment:'tl',
    	buttonclose:true,
    	buttonfullscreen:false,
        onSelect: function(ui, color){
            $('#color').css("color", color);
            $('#pColor').val(color);
        }
    });
});
