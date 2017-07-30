$(document).ready(function(){
	$('.productos-s').slick({autoplay:true,autoplaySpeed:10000,infinite:true,slidesToShow:4,slidesToScroll:1,
		responsive: [
    {breakpoint: 950,settings: {slidesToShow: 3,slidesToScroll: 1}},
    {breakpoint: 600,settings: {slidesToShow: 2,slidesToScroll: 1}}
    	]
		,arrows:true,dots:false}).show();
});