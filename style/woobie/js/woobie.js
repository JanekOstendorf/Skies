$(function () {

	/*
	 * Hide notifications
	 */

	$('.errors .hideLink a, .notice .hideLink a, .warning .hideLink a, .success .hideLink a').click(function () {

		$(this).parent().parent().slideUp('fast');

	});

	/* 
	 * Navigation
	 */
	$('#hornav').find('div.submenu').hide();

	$('#hornav').find('li.dropdown').mouseenter(function () {
		$(this).children('div.submenu').fadeIn('fast');
	});

	$('#hornav').find('li.dropdown').mouseleave(function () {
		$(this).children('div.submenu').fadeOut('fast');
	});

	/*
	 * Login box
	 */
	$('#loginBox').hide();

	$('#loginLink').click(function(e) {

		e.preventDefault();

		$('#loginBox').slideToggle('fast');

	});

});


