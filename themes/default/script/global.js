//Close button:
	
$(function(){
	
    $(".tiptop").tipTip({ defaultPosition: 'top' });
	$(".tiptopfocus").tipTip({ defaultPosition: 'top', activation: 'focus', maxWidth: 'auto' });

	$(".close").click(
		function () {
			$(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
				$(this).slideUp(400);
			});
			return false;
		}
	);
	
	$(document).mouseup(function(e) {
		$('#pageoptions ul').hide('medium');
	});
	
	$("#optionsbutton").click(
		function () {
			$('#pageoptions ul').slideToggle('medium');
			return false;
		}
	);

});	