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

var settingsMultiCtrl = function(){
	var $ = jQuery;

	this.init = function(){
		var that = this;
		$(".settings-container fieldset.multi").each(function(){
			var $fieldset = $(this);
			var $template = $(".multi-input", $fieldset).first().clone();
			$("input", $template).val("");

			$($fieldset).on("click", ".multi-input .add-input", function(){
				that.addInput($template.clone(), $fieldset);
			});


			$($fieldset).on("click", ".multi-input .remove-input", function(e){
				var $target = $(e.currentTarget).parents(".multi-input");
				if($(".multi-input", $fieldset).length > 1){
					$target.remove();
				}
			});

		});
	}

	this.addInput = function($template, $fieldset){
		$template.insertAfter($(".multi-input", $fieldset).last());
	}

	this.init();
}

jQuery(document).ready(function($){
	var settings_nav_ctrl = new settingsNavCtrl();
	var settings_multi_ctrl = new settingsMultiCtrl();
});