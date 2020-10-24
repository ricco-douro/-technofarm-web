(function( root, $, factory ) {
	joms.view || (joms.view = {});
	joms.view.poll = factory( root, $ );
})( window, joms.jQuery, function( window, $ ) {

var html;

html = $('#joms-template-poll-option__input').html();

function addOption(elm) {
	$(html).insertBefore(elm);
	$('.poll-input').last().focus();
}

function removeOption(elm) {
	var $option = $(elm).parents('.joms-poll-option'),
		$hiddenInput = $option.find('[name="pollItemId[]"]');
	if ($hiddenInput.length) {
		var itemid = $hiddenInput.val();
		joms.popup.poll.removeoption( itemid );
	} else {
		$option.remove();
	}
}

function deletePoll ( id ) {
	joms.popup.poll.delete( id );
}

function vote( poll_id, option_id ) {
	var $container = $('.joms-poll__container-'+poll_id),
		$loader = $container.find('.joms-poll__loader'),
		$option = $container.find('.joms-poll__option-'+option_id),
		$input = $option.siblings('input'),
		input_type = $input.attr('type');

	$input.prop('checked', !$input.prop('checked'));
	ajaxVote(poll_id, option_id);
}

function inputVote( poll_id, option_id ) {
	var $container = $('.joms-poll__container-'+poll_id),
		$loader = $container.find('.joms-poll__loader');
	
	ajaxVote(poll_id, option_id);
}

function clearOtherVote( $container, option_id ) {
	var $inputs = $container.find('.joms-poll_input').not('.joms-poll_input-'+option_id);

	$inputs.each(function(index, el) {
		$(el).is(':checked') && $(el).prop('checked', false);
	});
}

function ajaxVote( poll_id, option_id ) {
	var $container = $('.joms-poll__container-'+poll_id),
		$loader = $container.find('.joms-poll__loader'),
		$list = $('.joms-poll__option-list-' + poll_id),
		collapse = $list.attr('data-collapse');

	$loader.fadeIn(300);
	joms.ajax({
		func: 'polls,ajaxPollVote',
		data: [ poll_id, option_id, collapse ],
		callback: function( json ) {
			$loader.fadeOut(300);
			if (json.success) {
				$container.html(json.html);
			} else {
				alert('ajax vote error! Please contact your admin.');
			}
		}
	});
}

function showVotedUsers( poll_id, option_id ) {
	joms.popup.poll.voted( poll_id, option_id );
}

function moreOptions( poll_id ) {
	var $list = $('.joms-poll__option-list-' + poll_id),
		$moreBtn = $('.joms-poll__more-' + poll_id);
	
	$list.attr('data-collapse', 1);
	$list.find('li').show();
	$moreBtn.hide();
}

return {
	addOption: addOption,
	removeOption: removeOption,
	delete: deletePoll,
	vote: vote,
	inputVote: inputVote,
	showVotedUsers: showVotedUsers,
	moreOptions: moreOptions
}

});