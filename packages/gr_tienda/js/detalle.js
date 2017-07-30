$(document).ready(function(){
	$('#d-imagenes').slick({autoplay:true,autoplaySpeed:10000,infinite:true,slidesToShow:3,slidesToScroll:1,
		responsive: [
    {breakpoint: 600,settings: {slidesToShow: 2,slidesToScroll: 1}}
    	]
		,arrows:true,dots:false}).show();
	$('#d-imagenes .t-img a').click(function(e){
		e.preventDefault();
		var img = new Image();
		href = this.getAttribute("href");
		$('#d-imagen img').attr('src','/files/tienda/l.png');
		$('#d-imagen a').attr('href',href);
		img.onload = function() { $('#d-imagen img').attr('src',img.src); };
		img.src = href;
	});
	$('.detalle form.comprar').submit(function(e) {
    	if ($.trim($("form.comprar input[name=id]:checked").val()) === "") { e.preventDefault(); }
	});
});