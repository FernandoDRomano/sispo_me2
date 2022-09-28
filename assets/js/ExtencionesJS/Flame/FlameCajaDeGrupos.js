$(document).ready(function(){
	$(".btn-minimize-CajaDeGrupos").click(function(){
		$(this).toggleClass('btn-plus');
		var Elemento = $(this).attr("for");
		$("#"+Elemento).slideToggle();
	});
});
