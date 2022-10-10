(function($){
"use strict";

	if ( $('select[name="wcwar_pa_warranty"]').length > 0 ) {

		if ( $('select[name="wcwar_pa_warranty"] option:first-child').val() == '' ) {
			$('select[name="wcwar_pa_warranty"] option:first-child').prop('selected', true);
		}

		var curr = $('select[name="wcwar_pa_warranty"]').val();

		$('.war_warranty.war_paid .war_option[data-selected="'+curr+'"]').fadeIn();

	}

	$(document).on('change', 'select[name="wcwar_pa_warranty"]', function() {
		var curr = $('select[name="wcwar_pa_warranty"]').val();
		$('.war_warranty.war_paid .war_option').hide();
		$('.war_warranty.war_paid .war_option[data-selected="'+curr+'"]').show();
	});

	$(document).on('change', '.variations_form input[name=variation_id]', function() {
		if ( $('.variations_form input[name=variation_id]').val() == '' ) {
			return;
		}
		var curr = $('.variations_form input[name=variation_id]').val();

		$('.variations_form .war_warranty').hide();
		$('.variations_form .war_warranty[data-id='+curr+']').show();
	});

})(jQuery);