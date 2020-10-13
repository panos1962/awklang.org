FAQ = {};

Globals.main = function() {
	FAQ.expandDOM = $('#expand');

	Globals.bodyDOM.
	on('click', '.question', function(e) {
		e.preventDefault();

		var visible = $(this).data('visible');

		if (visible)
		$(this).removeClass('questionVisible').
		removeData('visible').next('.response').css('display', 'none');

		else
		$(this).addClass('questionVisible').
		data('visible', true).next('.response').css('display', 'block');
	});

	if (Globals.phpRequest.hasOwnProperty('expand'))
	FAQ.expandAll();

	else
	FAQ.shrinkAll();

	FAQ.expandDOM.on('click', function(e) {
		e.preventDefault();

		if (Globals.phpRequest.hasOwnProperty('expand'))
		FAQ.shrinkAll();

		else
		FAQ.expandAll();
	});
		
};

FAQ.expandAll = function() {
	$('.question').addClass('questionVisible').data('visible', true);
	$('.response').css('display', 'block');
	Globals.phpRequest.expand = true;
	FAQ.expandDOM.text('Shrink');
};

FAQ.shrinkAll = function() {
	$('.question').removeClass('questionVisible').removeData('visible');
	$('.response').css('display', 'none');
	delete Globals.phpRequest.expand;
	FAQ.expandDOM.text('Expand');
};
