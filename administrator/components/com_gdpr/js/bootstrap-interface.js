// Turn radios into btn-group
jQuery(function($) {
	$('input[type=radio]~label').addClass('radio btn btn-default');
	$('input[type=radio]:first-child').next('label').css({'border-top-left-radius':'5px', 'border-bottom-left-radius':'5px','border-right':'none'});
	$('input[type=radio]~label:last-child').css({'border-top-right-radius':'5px', 'border-bottom-right-radius':'5px','border-left':'none'});
	$('fieldset.mutex label').css({'border-left':'1px solid #bbb', 'border-right':'1px solid #bbb'});
	
	$("input[type=radio]~label:not(.active)").click(
		function(event) {
			var label = $(this);
			var input = $(label).prev();

			if (!input.prop('checked')) {
				label.parent('div, td, span, fieldset').find("label").removeClass('active btn-success btn-danger btn-primary');
				// Mutex handling
				if($(this).parent().hasClass('mutex')) {
					$('fieldset.mutex input').prop('checked', false);
					$('fieldset.mutex label').removeClass('active btn-success btn-danger btn-primary');
				}
				
				if (input.val() == '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else {
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
			}
		});
	$("input[type=radio][checked=checked]").each(
		function() {
			if ($(this).val() == '') {
				$(this).next('label').addClass(
						'active btn-primary');
			} else if ($(this).val() == 0) {
				$(this).next('label').addClass(
						'active btn-danger');
			} else {
				$(this).next('label').addClass(
						'active btn-success');
			}
	});
	
	/**
	 * Enables bootstrap popover
	 */
	$('#updatestatus label.hasPopover, #checker_start').popover({trigger:'hover', placement:'right', html:1});
	$('a.hasPopover.google, span.hasPopover.google').popover({trigger:'hover', placement:'bottom', html:1});
	$('label.hasPopover, button.hasPopover, div.hasPopover, span.hasPopover, img.hasPopover').popover({trigger:'hover', placement:'top', html:1});
	
	/**
	 * Calendars
	 */
	if($.datepicker) {
		$('input[data-role=calendar]').datepicker({
			dateFormat:'yy-mm-dd'
		}).prev('span').on('click', function(){
			$(this).datepicker('show');
		});
	}
	
	/**
	 * Remove empty ordering spans
	 */
	$('.order > span').filter(function(){
		var hasChild = !$('a', this).length;
		return hasChild;
	}).remove();
	// Recover the legacy save order button in async way on the next cycle
	setTimeout(function(){
		$('a.saveorder').removeAttr('onclick').removeAttr('style');
	}, 1);
	
	/**
	 * Enables bootstrap tooltip
	 */
	$('label.hasTooltip, img.hasTooltip, a.hasTooltip, span.hasTooltip, a.hasTip').tooltip({trigger:'hover', placement:'top'});
	 
	// Open the window iframe including the URL to debug the redirects
	$('a.windowopen_iframe').on('click', function(jqEvent){
		var targetLink = $(this).attr('href');
		window.open(targetLink, '_blank', 'width=1280, height=800, left=100, top=100, location=1, scrollbars=1')
		return false;
	});
	
	/**
	 * Accordion panels local storage memoize and set open
	 */
	var defaultAccordionObject = {'gdpr_accordion_cpanel':'gdpr_stats'};
	$('div.accordion').on('shown.bs.collapse', function (event) {
		if(!$(event.target).hasClass('accordion-body')) {
			return;
		}
		event.stopPropagation();
		$('div.accordion-heading').removeClass('opened');
		var localStorageAccordion = $.jStorage.get('gdprAccordionOpened', defaultAccordionObject);
		localStorageAccordion[this.id] = event.target.id;
		$.jStorage.set('gdprAccordionOpened', localStorageAccordion);
		
		// Scroll to accordion header if needed
		if(document.body.scrollHeight > window.innerHeight) {
			$('html, body').animate({ scrollTop: parseInt($("#"+event.target.id).prev().offset().top) - 185}, 500);
		}
		// Add open state
		$(event.target).prev().addClass('opened');
	}).on('hidden.bs.collapse', function (event){
		// Check if stored accordions are managed as array of opened slides or mutex exclusions
		if(!$(event.target).hasClass('accordion-body')) {
			return;
		}
		event.stopPropagation();
		var localStorageAccordion = $.jStorage.get('gdprAccordionOpened', defaultAccordionObject);
		if(localStorageAccordion[this.id] == event.target.id) {
			delete localStorageAccordion[this.id];
			$.jStorage.set('gdprAccordionOpened', localStorageAccordion);
		}
		// Remove open state
		$(event.target).prev().removeClass('opened');
	});
	
	$.each($.jStorage.get('gdprAccordionOpened', defaultAccordionObject), function(namespace, element) {
		if($('#'+element, '#'+namespace).length) {
			$('#'+element, '#'+namespace).addClass('in').prev().addClass('opened');
		}
	});
	
	// Replace :: for old legacy Mootools title
	$('label.hasTip').each(function(indes, elem){
		var currentTitle = $(elem).attr('title');
		var replacedTitle = currentTitle.replace("::", " - ");
		$(elem).attr('title', replacedTitle);
		$(elem).tooltip({trigger:'hover', placement:'top'});
	});
	
	/**
	 * Prevent default scrolling hover main accordion body and scroll programmatically the document
	 */
	$('div.accordion-body').on('wheel', function(jqEvent){
		if (jqEvent.originalEvent && jqEvent.originalEvent.wheelDelta) {
			if (jqEvent.originalEvent.wheelDelta) jqEvent.delta = jqEvent.originalEvent.wheelDelta;
		
			var newBodyScroll = $(document).scrollTop() - jqEvent.delta;
			$(document).scrollTop(newBodyScroll);
			jqEvent.preventDefault();
			return false;
		}
	});

	// Slide down and hide advanced controls
	var advancedExternal = $('select[name=params\\[external_blocking_mode\\]]');
	if(advancedExternal.val() != 'advanced') {
		$('*.external_advanced').parents('div.control-group').hide();
	}
	$(advancedExternal).on('change', function(){
		if($(this).val() == 'advanced') {
			$('*.external_advanced').parents('div.control-group').slideDown();
		} else {
			$('*.external_advanced').parents('div.control-group').slideUp();
		}
	});
	
	var profileButtonsWorkingMode = $('select[name=params\\[userprofile_buttons_workingmode\\]]');
	if(parseInt(profileButtonsWorkingMode.val()) == 1) {
		$('#params_userprofile_self_delete_confirmation').parents('div.control-group').hide();
	}
	$(profileButtonsWorkingMode).on('change', function(){
		if(parseInt($(this).val()) == 1) {
			$('#params_userprofile_self_delete_confirmation').parents('div.control-group').slideUp();
		} else {
			$('#params_userprofile_self_delete_confirmation').parents('div.control-group').slideDown();
		}
	});
	
	var toolbarPosition = $('select#params_position');
	if(toolbarPosition.val() != 'center') {
		$('*.center_modal_block').parents('div.control-group').hide();
	}
	$(toolbarPosition).on('change', function(){
		if($(this).val() == 'center') {
			$('*.center_modal_block').parents('div.control-group').show();
		} else {
			$('*.center_modal_block').parents('div.control-group').hide();
		}
	});
	
	/**
	 * Tab panels local storage memoize and set open
	 */
	var defaultTabObject = {'tab_configuration':'preferences', 'permissions-sliders': 'permissions-1'};
	$('.nav.nav-tabs').on('shown.bs.tab', function (event) {
		var localStorageTab = $.jStorage.get('tabOpened', defaultTabObject);
		var assignedID = this.id ? this.id : $(this).parent().attr('id');
		var assignedValue = $(event.target).data('element') ? $(event.target).data('element') : $(event.target).attr('href').substr(1)
		localStorageTab[assignedID] = assignedValue;
		$.jStorage.set('gdprTabOpened', localStorageTab);
	});
	
	// Parse query string to search if any anchor force tab opening
	var hashQueryString = window.location.hash.substr(2);
	if(hashQueryString) {
		$('ul.nav.nav-tabs li a[data-element=' + hashQueryString + ']').tab('show');
	}
	
	if(hashQueryString == 'licensepreferences') {
		$('a[data-element=preferences]').tab('show');
		$('#params_registration_email-lbl').css('color', 'red');
		$('#params_registration_email').css('border', '2px solid red');
	}
	
	$.each($.jStorage.get('gdprTabOpened', defaultTabObject), function(namespace, element) {
		$('a[data-element='+element+']', '#'+namespace).tab('show');
		$('a[href=\\#'+element+']', '#'+namespace).tab('show');
	});
	
	// Add class to envelope button
	$('.icon-envelope').parent().addClass('btn-primary');
	
	// Add confirmation to the mass mail data breach process
	$('#toolbar-envelope button').removeAttr('onclick').on('click', function(jqEvent){
		if (document.adminForm.boxchecked.value == 0) { 
			alert( COM_GDPR_ERROR_RECORDS_EMPTY_JSMESSAGE ); 
		} else {
			if(confirm( COM_GDPR_SURE_TO_SEND_EMAIL )) {
				Joomla.submitbutton('users.notifyDataBreach'); 
			}
		}
	});
	
	// Simulate dropdown for datalist input fields when the arrow is clicked
	$('td.right_details input[name*=fields\\[]').on('click', function(jqEvent){
		var currentVal = $(this).val();
		if(!currentVal) {
			return true;
		}
		var currentElement = $(this);
		// Force the datalist to show again
		$(this).val('');
		
		jqEvent.stopPropagation();
		
		$(document).one('click', function(jqEvent){
			if(!currentElement.val()) {
				currentElement.val(currentVal);
			}
		});
	});
	
	$('td.right_details input[name*=fields\\[] + button.btn').on('click', function(jqEvent){
		$(this).prev('input').val('');
		return false;
	});
	
	// Add a hidden field and copy button for the custom revokable button code
	if(typeof(COM_GDPR_CUSTOM_COPY_CODE) !== 'undefined' && $('#tab_configuration').length) {
		$('#params_custom_revokable_button').after('<input data-role="copyclipboard" type="text" value="&lt;a class=&quot;cc-custom-revoke&quot;&gt;' + COM_GDPR_OPEN_COOKIE_TOOLBAR + '&lt;/a&gt;">' +
												   '<button data-role="copyclipboard" data-success="' + COM_GDPR_CUSTOM_COPIED_CODE + '" class="btn btn-mini btn-success">' + COM_GDPR_CUSTOM_COPY_CODE + '</button>');
	}
	
	// Support for copy Clipoard buttons, new API and legacy API
	if(navigator.clipboard) {
		$('button[data-role=copyclipboard]').on('click', function(jqEvent){
			navigator.clipboard.writeText($('input[data-role=copyclipboard]').val())
			.then(function() {
				var currentText = $(jqEvent.target).text();
				var copiedText = $(jqEvent.target).data('success');
				$(jqEvent.target).text(copiedText).removeClass('btn-success').addClass('btn-warning');
				setTimeout(function(){
					$(jqEvent.target).text(currentText).removeClass('btn-warning').addClass('btn-success');
				}, 2000);
			})
			.catch(function(err) {
			});
			return false;
		});
	} else {
		$('button[data-role=copyclipboard]').on('click', function(jqEvent){
			try {  
				var placeholderInput = $('input[data-role=copyclipboard]').get(0).select();  
				// Now that we've selected the text, execute the copy command  
				var successful = document.execCommand('copy');  
				if(successful) {
					var currentText = $(this).text();
					var copiedText = $(this).data('success');
					$(this).text(copiedText).removeClass('btn-success').addClass('btn-warning');
					setTimeout(function(){
						$(jqEvent.target).text(currentText).removeClass('btn-warning').addClass('btn-success');
					}, 2000);
				}
				// Remove the selections - NOTE: Should use
				// removeRange(range) when it is supported  
				window.getSelection().removeAllRanges();  
			} catch(err) {  
			}
			return false;
		});
	}
	
	// Add the button to reset all consents status in the #__user_profiles table
	if(typeof(COM_GDPR_RESET_ALL_CONSENTS) !== 'undefined' && $('#tab_configuration').length) {
		$('#params_block_privacypolicy').addClass('pull-left').after('<label class="label label-important nospacer resetconsents hasPopover" data-title="' + COM_GDPR_RESET_ALL_CONSENTS_TITLE + '" data-content="' + COM_GDPR_RESET_ALL_CONSENTS_DESC + '" onclick="Joomla.submitbutton(\'config.resetConsents\');"><span class="icon icon-warning-2"></span> ' + COM_GDPR_RESET_ALL_CONSENTS + '</label>');
		$('label.resetconsents').popover({trigger:'hover', placement:'right', html:1});
		
		// Manage the hide/show of subcontrols for custom images tags
		var blockPrivacyPolicy = $('input[name=params\\[block_privacypolicy\\]]:checked').val();
		if(blockPrivacyPolicy == 0) {
			$('label.resetconsents').hide();
		}
		$('input[name=params\\[block_privacypolicy\\]]').on('click', function(){
			if($(this).val() == 1) {
				$('label.resetconsents').show();
			} else {
				$('label.resetconsents').hide();
			}
		});
	}
});