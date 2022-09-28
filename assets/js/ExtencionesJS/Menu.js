var total_headers;
var current_header;
$(function () {
	setServicesColumnsEqualHeight();
});

function setServicesColumnsEqualHeight() {
	var items = $('.MenuPage-Header-menu .Menu div.item');
	items.hover(
		function () {
			var tallestColumn = 0;
			$('.child', $(this)).show();
			var childwidth = $('.child', $(this)).width();
			var pos = $(this).position().left;
			if(pos < childwidth){
				$(this).removeClass( "item-left" );
			}else{
				$(this).addClass( "item-left" );
			}
			var columns = $('.service', $(this));
			columns.each(function () {
				var currentHeight = $(this).height();
				if (currentHeight > tallestColumn) {
					tallestColumn = currentHeight;
				}
			});
			columns.last().addClass('last');
			columns.height(tallestColumn);
		},
		function () {
			$('.child', $(this)).hide();
		}
	);
	var header = document.getElementById("menu");
	if(header == null ){
		return;
	}
	var btns = header.getElementsByClassName("title");
	for (var i = 0; i < btns.length; i++) {
		btns[i].addEventListener("click", function() {
			var current = document.getElementsByClassName("active");
			current[0].className = current[0].className.replace(" active", "");
			this.className += " active";
		});
	}
}
