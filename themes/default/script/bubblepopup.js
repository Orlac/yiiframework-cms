$(document).ready(function(){
	$("#menupopup li").hover(function() {
		$(this).children("div").animate({opacity: "show", top: "-65"}, "slow");
	}, function() {
		$(this).children("div").animate({opacity: "hide", top: "-90"}, "fast");
	});
});