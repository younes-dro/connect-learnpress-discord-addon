jQuery(function($){
//	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
        
        if (etsLearnPressParams.is_admin) {
		$('#ets_learnpress_discord_redirect_url').select2({});

		/*Load all roles from discord server*/
		$.ajax({
			type: "POST",
			dataType: "JSON",
			url: etsLearnPressParams.admin_ajax,
			data: { 'action': 'ets_learnpress_discord_load_discord_roles', 'ets_learnpress_discord_nonce': etsLearnPressParams.ets_learnpress_discord_nonce },
			beforeSend: function () {
				$(".learnpress-discord-roles .spinner").addClass("is-active");
				$(".initialtab.spinner").addClass("is-active");
			},
			success: function (response) {
				if (response != null && response.hasOwnProperty('code') && response.code == 50001 && response.message == 'Missing Access') {
					$(".learnpress-btn-connect-to-bot").show();
				} else if (response == null || response.message == '401: Unauthorized' || response.hasOwnProperty('code') || response == 0) {
					$("#learnpress-connect-discord-bot").show().html("Error: Please check all details are correct").addClass('error-bk');
				} else {
					if ($('.ets-tabs button[data-identity="level-mapping"]').length) {
						$('.ets-tabs button[data-identity="level-mapping"]').show();
					}
					$("#learnpress-connect-discord-bot").show().html("Bot Connected <i class='fab fa-discord'></i>").addClass('not-active');

					var activeTab = localStorage.getItem('activeTab');
					if ($('.ets-tabs button[data-identity="level-mapping"]').length == 0 && activeTab == 'level-mapping') {
						$('.ets-tabs button[data-identity="settings"]').trigger('click');
					}
					$.each(response, function (key, val) {
						var isbot = false;
						if (val.hasOwnProperty('tags')) {
							if (val.tags.hasOwnProperty('bot_id')) {
								isbot = true;
							}
						}

						if (key != 'previous_mapping' && isbot == false && val.name != '@everyone') {
							$('.learnpress-discord-roles').append('<div class="makeMeDraggable" style="background-color:#'+val.color.toString(16)+'" data-learnpress_role_id="' + val.id + '" >' + val.name + '</div>');
							$('#learnpress-defaultRole').append('<option value="' + val.id + '" >' + val.name + '</option>');
							makeDrag($('.makeMeDraggable'));
						}
					});
					var defaultRole = $('#selected_default_role').val();
					if (defaultRole) {
						$('#learnpress-defaultRole option[value=' + defaultRole + ']').prop('selected', true);
					}

					if (response.previous_mapping) {
						var mapjson = response.previous_mapping;
					} else {
						var mapjson = localStorage.getItem('learnpress_mappingjson');
					}

					$("#ets_learnpress_mapping_json_val").html(mapjson);
					$.each(JSON.parse(mapjson), function (key, val) {
						var arrayofkey = key.split('id_');
						var preclone = $('*[data-learnpress_role_id="' + val + '"]').clone();
						if(preclone.length>1){
							preclone.slice(1).hide();
						}
						
						if (jQuery('*[data-learnpress_course_id="' + arrayofkey[1] + '"]').find('*[data-learnpress_role_id="' + val + '"]').length == 0) {
							$('*[data-learnpress_course_id="' + arrayofkey[1] + '"]').append(preclone).attr('data-drop-learnpress_role_id', val).find('span').css({ 'order': '2' });
						}
						if ($('*[data-learnpress_course_id="' + arrayofkey[1] + '"]').find('.makeMeDraggable').length >= 1) {
							$('*[data-learnpress_course_id="' + arrayofkey[1] + '"]').droppable("destroy");
						}

						preclone.css({ 'width': '100%', 'left': '0', 'top': '0', 'margin-bottom': '0px', 'order': '1' }).attr('data-learnpress_course_id', arrayofkey[1]);
						makeDrag(preclone);
					});
				}

			},
			error: function (response) {
				$("#learnpress-connect-discord-bot").show().html("Error: Please check all details are correct").addClass('error-bk');
				console.error(response);
			},
			complete: function () {
				$(".learnpress-discord-roles .spinner").removeClass("is-active").css({ "float": "right" });
				$("#skeletabsTab1 .spinner").removeClass("is-active").css({ "float": "right", "display": "none" });
			}
		});
                var discordWindow;
		$('.learnpress-btn-connect-to-bot').click(function (e) {
			e.preventDefault();
			discordWindow = window.open($(this).attr('href'), "", "height=650,width=500,directories=no,titlebar=no,toolbar=no,location=no,resizable=yes");
 
		});    
                 var queryString = window.location.search;
                 var urlParams = new URLSearchParams(queryString);
                 var via = urlParams.get('via');
                 if( via == 'learnpress-discord-bot'){
                     window.opener.location.reload();
                     window.close();
                 }                
		/*Clear log log call-back*/
		$('#ets-learnpress-clrbtn').click(function (e) {
			e.preventDefault();
			$.ajax({
				url: etsLearnPressParams.admin_ajax,
				type: "POST",
				data: { 'action': 'ets_learnpress_discord_clear_logs', 'ets_learnpress_discord_nonce': etsLearnPressParams.ets_learnpress_discord_nonce },
				beforeSend: function () {
					$(".clr-log.spinner").addClass("is-active").show();
				},
				success: function (data) {
         
					if (data.error) {
						// handle the error
						alert(data.error.msg);
					} else {
                                            
						$('.error-log').html("Clear logs Sucesssfully !");
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
					$(".clr-log.spinner").removeClass("is-active").hide();
				}
			});
		});                
		/*RUN API */
		$('.ets-learnpress-discord-run-api').click(function (e) {
			e.preventDefault();
			$.ajax({
				url: etsLearnPressParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'ets_learnpress_discord_run_api', 'ets_learnpress_discord_user_id': $(this).data('user-id') , 'ets_learnpress_discord_nonce': etsLearnPressParams.ets_learnpress_discord_nonce },
				beforeSend: function () {
					$(this).siblings("div.run-api-success").html("");
					$(this).siblings('span.spinner').addClass("is-active").show();
				},
				success: function (data) {         
					if (data.error) {
						// handle the error
						alert(data.error.msg);
					} else {
                                            
						$(this).siblings("div.run-api-success").html("Update Discord Roles Sucesssfully !");
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
					$(this).siblings('span.spinner').removeClass("is-active").hide();
				}
			});
		});               
		/*Flush settings from local storage*/
		$("#revertMapping").on('click', function () {
			localStorage.removeItem('learnpress_mapArray');
			localStorage.removeItem('learnpress_mappingjson');
			window.location.href = window.location.href;
		});        
   
		/*Create droppable element*/
		function init() {
			if($('.makeMeDroppable').length){
				$('.makeMeDroppable').droppable({
					drop: handleDropEvent,
					hoverClass: 'hoverActive',
				});
			}
			if($('.learnpress-discord-roles-col').length){                        
				$('.learnpress-discord-roles-col').droppable({
					drop: handlePreviousDropEvent,
					hoverClass: 'hoverActive',
				});
			}
		}

		$(init);

		/*Create draggable element*/
		function makeDrag(el) {
			// Pass me an object, and I will make it draggable
			el.draggable({
				revert: "invalid",
				helper: 'clone',
				start: function(e, ui) {
				ui.helper.css({"width":"45%"});
				}
			});
		}

		/*Handel droppable event for saved mapping*/
		function handlePreviousDropEvent(event, ui) {
			var draggable = ui.draggable;
			if(draggable.data('learnpress_course_id')){
				$(ui.draggable).remove().hide();
			}
			$(this).append(draggable);
			$('*[data-drop-learnpress_role_id="' + draggable.data('learnpress_role_id') + '"]').droppable({
				drop: handleDropEvent,
				hoverClass: 'hoverActive',
			});
			$('*[data-drop-learnpress_role_id="' + draggable.data('learnpress_role_id') + '"]').attr('data-drop-learnpress_role_id', '');

			var oldItems = JSON.parse(localStorage.getItem('learnpress_mapArray')) || [];
			$.each(oldItems, function (key, val) {
				if (val) {
					var arrayofval = val.split(',');
					if (arrayofval[0] == 'learnpress_course_id_' + draggable.data('learnpress_course_id') && arrayofval[1] == draggable.data('learnpress_role_id')) {
						delete oldItems[key];
					}
				}
			});
			var jsonStart = "{";
			$.each(oldItems, function (key, val) {
				if (val) {
					var arrayofval = val.split(',');
					if (arrayofval[0] != 'learnpress_course_id_' + draggable.data('learnpress_course_id') || arrayofval[1] != draggable.data('learnpress_role_id')) {
						jsonStart = jsonStart + '"' + arrayofval[0] + '":' + '"' + arrayofval[1] + '",';
					}
				}
			});
			localStorage.setItem('learnpress_mapArray', JSON.stringify(oldItems));
			var lastChar = jsonStart.slice(-1);
			if (lastChar == ',') {
				jsonStart = jsonStart.slice(0, -1);
			}

			var learnpress_mappingjson = jsonStart + '}';
			$("#ets_learnpress_mapping_json_val").html(learnpress_mappingjson);
			localStorage.setItem('learnpress_mappingjson', learnpress_mappingjson);
			draggable.css({ 'width': '100%', 'left': '0', 'top': '0', 'margin-bottom': '10px' });
		}

		/*Handel droppable area for current mapping*/
		function handleDropEvent(event, ui) {
			var draggable = ui.draggable;
			var newItem = [];
			var newClone = $(ui.helper).clone();
			if($(this).find(".makeMeDraggable").length >= 1){
				return false;
			}
			$('*[data-drop-learnpress_role_id="' + newClone.data('learnpress_role_id') + '"]').droppable({
				drop: handleDropEvent,
				hoverClass: 'hoverActive',
			});
			$('*[data-drop-learnpress_role_id="' + newClone.data('learnpress_role_id') + '"]').attr('data-drop-learnpress_role_id', '');
			if ($(this).data('drop-learnpress_role_id') != newClone.data('learnpress_role_id')) {
				var oldItems = JSON.parse(localStorage.getItem('learnpress_mapArray')) || [];
				$(this).attr('data-drop-learnpress_role_id', newClone.data('learnpress_role_id'));
				newClone.attr('data-learnpress_course_id', $(this).data('learnpress_course_id'));

				$.each(oldItems, function (key, val) {
					if (val) {
						var arrayofval = val.split(',');
						if (arrayofval[0] == 'learnpress_course_id_' + $(this).data('learnpress_course_id')) {
							delete oldItems[key];
						}
					}
				});

				var newkey = 'learnpress_course_id_' + $(this).data('learnpress_course_id');
				oldItems.push(newkey + ',' + newClone.data('learnpress_role_id'));
				var jsonStart = "{";
				$.each(oldItems, function (key, val) {
					if (val) {
						var arrayofval = val.split(',');
						if (arrayofval[0] == 'learnpress_course_id_' + $(this).data('learnpress_course_id') || arrayofval[1] != newClone.data('learnpress_role_id') && arrayofval[0] != 'learnpress_course_id_' + $(this).data('learnpress_course_id') || arrayofval[1] == newClone.data('learnpress_role_id')) {
							jsonStart = jsonStart + '"' + arrayofval[0] + '":' + '"' + arrayofval[1] + '",';
						}
					}
				});

				localStorage.setItem('learnpress_mapArray', JSON.stringify(oldItems));
				var lastChar = jsonStart.slice(-1);
				if (lastChar == ',') {
					jsonStart = jsonStart.slice(0, -1);
				}

				var learnpress_mappingjson = jsonStart + '}';
				localStorage.setItem('learnpress_mappingjson', learnpress_mappingjson);
				$("#ets_learnpress_mapping_json_val").html(learnpress_mappingjson);
			}

			$(this).append(newClone);
			$(this).find('span').css({ 'order': '2' });
			if (jQuery(this).find('.makeMeDraggable').length >= 1) {
				$(this).droppable("destroy");
			}
			makeDrag($('.makeMeDraggable'));
			newClone.css({ 'width': '100%', 'left': '0', 'top': '0', 'margin-bottom': '0px', 'position':'unset', 'order': '1' });
		}

		$(document.body).on('change', '#ets_learnpress_discord_redirect_url', function(e){
			var page_url = $(this).find(':selected').data('page-url');
                        $('p.redirect-url').html('<b>'+page_url+'</b>');
		});                
                
	}
        

});
if ( etsLearnPressParams.is_admin ) {
	/*Tab options*/
	if( typeof(skeletabs) !=='undefined' ){
		jQuery.skeletabs.setDefaults({
			keyboard: false
		});
	}
}
