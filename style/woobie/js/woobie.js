jQuery(function() {

	/*
	 * Hide notifications
	 */

	jQuery('.error .hideLink a, .notice .hideLink a, .warning .hideLink a, .success .hideLink a').click(function() {

		jQuery(this).parent().parent().slideUp('fast');

	});

});


