const checkFloc = function () {
	jQuery.get(
		wpApiSettings.root +
			'wpmfloc/v1/check-flock?_wpnonce=' +
			wpApiSettings.nonce,
		(data) => {
			if (data.response) {
				/* eslint-disable no-alert */
				alert(data.response);
				/* eslint-enable no-alert */
			}
		}
	);
};

jQuery(document).ready(function ($) {
	$('.js--check-floc').click(() => {
		checkFloc();
	});
});
