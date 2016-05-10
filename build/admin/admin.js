var settingsNavCtrl = function(){
	var $ = jQuery;

	this.init = function(){
		var that = this;
		$(".afbia-settings-page").each(function(){
			var $container = $(this);

			// find the first active item
			var firstSection = $(".navigation a", $container).first().attr("href").replace("#", "");
			that.setActive(firstSection, $container);

			$(".navigation a", $container).click(function(){
				var section = $(this).attr("href").replace("#", "");
				that.setActive(section, $container);
			});

		});
	}

	this.setActive = function(sectionName, $container){
		$(".navigation a", $container).parent().removeClass("active");
		$(".navigation a[href='#"+sectionName+"']", $container).parent().addClass("active");

		$(".section", $container).hide();
		$("#section-"+sectionName, $container).show();
	}

	this.init();
}

jQuery(document).ready(function($){
	var settings_ctrl = new settingsNavCtrl();
});