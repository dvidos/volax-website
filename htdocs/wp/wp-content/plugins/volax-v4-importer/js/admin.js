/* sample script here */

jQuery(document).ready(function($) {
	
	// Handle the AJAX field save action
	$('#volax-importer-ajax-form').on('submit', function(e) {
		e.preventDefault();
		$("#ajax-loader").show();
		$("input[type=submit]").attr("disabled", true);
		
		// var ajax_field_value = $('#vi_option_from_ajax').val();
		var field_value = 123;
		
		$.post(ajaxurl, {
			data: {'field_value': field_value },
			action: 'posted_ajax_form'
		}, function(response) {
			$("#ajax-loader").hide();
			$("input[type=submit]").attr("disabled", false);
			
			
			$('#ajax-response').html(response);
		});
	});
});
