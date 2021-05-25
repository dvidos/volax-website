/* sample script here */

jQuery(document).ready(function($) {
	
	$('#vi-ajax-import-form').on('submit', function(e) {
		e.preventDefault();
		$("#ajax-loader").show();
		$("input[type=submit]").attr("disabled", true);
		$('#ajax-response').html('');
		
		var what = $('#what').val();
		var identities = $('#identities').val();
		var skip_dry_run = $('#skip-dry-run').is(":checked") ? 1 : 0;
		var overwrite = $('#overwrite').is(":checked") ? 1 : 0;
		
		$.ajax({
			url: ajaxurl,
			type: "POST",
			data: {
				action: 'posted_ajax_form',
				data: {
					'what': what, 
					'identities': identities, 
					'skip_dry_run': skip_dry_run, 
					'overwrite': overwrite 
				},
			},
			success: function(response) {
				$("#ajax-loader").hide();
				$("input[type=submit]").attr("disabled", false);
				$('#ajax-response').html(response);
				$('#ajax-response').show();
			},
			error: function(xhr, status, error) {
				var errorMessage = xhr.status + ': ' + xhr.statusText
				// alert('Error - ' + errorMessage);
				
				$("#ajax-loader").hide();
				$("input[type=submit]").attr("disabled", false);
				$('#ajax-response').html(errorMessage);
				$('#ajax-response').show();
			}
		});		
		/*$.post(ajaxurl, {
			data: {'what': what, 'identities': identities, 'skip_dry_run': skip_dry_run, 'overwrite': overwrite },
			action: 'posted_ajax_form_1234'
		}, function(response) {
			alert(response);
		});*/
	});
	
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
