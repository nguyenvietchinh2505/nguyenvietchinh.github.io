jQuery(document).ready(function($){
    $('.cnb-color-field').wpColorPicker();
	$("#cnb_settings").click(function() {
		$("#settings").slideDown();
		$("#cnb_settings").remove();
	});
	$("span.check-settings").click(function() {
		if($("#settings").is(":hidden")) {
			$("#settings").slideDown('fast');
			$("div#cnb_settings").remove();
		}
		$("tr.appearance input").addClass("red-background").focus();
		$('html, body').animate({
        	scrollTop: $("tr.appearance").offset().top
    	}, 500);
    	$("span.check-settings").remove();
	});
	$(".cnb-switch-back").click(function() {		
		if($("#settings").is(":hidden")) {
			$("#settings").slideDown('fast');
			$("div#cnb_settings").remove();
		}
		$("tr.classic ").addClass("red-background").focus();
		$('html, body').animate({
        	scrollTop: $("tr.classic").offset().top
    	}, 500);
	});
});