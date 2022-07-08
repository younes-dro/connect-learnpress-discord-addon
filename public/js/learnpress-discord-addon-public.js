(function( $ ) {
	'use strict';

	$('.learnpress-discord-btn-disconnect').on('click', function (e) {
            
		e.preventDefault();
		var userId = $(this).data('user-id');
		$.ajax({
			type: "POST",
			dataType: "JSON",
			url: etsLearnPressParams.admin_ajax,
			data: { 'action': 'learnpress_disconnect_from_discord', 'user_id': userId, 'ets_learnpress_discord_nonce': etsLearnPressParams.ets_learnpress_discord_nonce },
			beforeSend: function () {
				$(".ets-spinner").addClass("ets-is-active");
			},
			success: function (response) {
				if (response.status == 1) {
					window.location = window.location.href.split("?")[0];
				}
			},
			error: function (response ,  textStatus, errorThrown) {
				console.log( textStatus + " :  " + response.status + " : " + errorThrown );
			}
		});
	});         

})( jQuery );
