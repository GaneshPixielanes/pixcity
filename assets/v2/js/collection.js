/*Collection page sidebar height js*/
	var maxHeight = 0;
	$(".regionCards").each(function(){
	   if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
	});

	$(".map-sidebar").height(maxHeight);