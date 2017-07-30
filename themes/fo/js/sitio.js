/*!
 *  by Georgel@w-mexico.net
 */
$(document).ready(function() {
	$(".gxnav").gxNav();
	$('#menu1 .with-submenu>a').click(function(e){ e.preventDefault(); });
	$('#inicio0 .sfnav').superfish({delay:300,disableHI:true});
	$('#inicio0 .sf-arrows>li>.sf-with-ul').append('<i class="sfnav-icon fa fa-caret-down"></i>');
	$('#buscar0 form').submit(function(e) {
    	if ($.trim($("#buscar0 input").val()) === "") { e.preventDefault(); }
	});
});