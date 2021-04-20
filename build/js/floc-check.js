const checkFloc = function () {
	jQuery('.js--check-floc-icons .dashicons').hide();
	jQuery('.js--check-floc-icons .wpm-loading').show();
	jQuery('.js--check-floc-result').html(null);

	jQuery.get(
		wpApiSettings.root +
			'wpmfloc/v1/check-flock?_wpnonce=' +
			wpApiSettings.nonce,
		(data) => {
			$('.js--check-floc-icons .wpm-loading').hide();
			if (data.response && data.success) {
				$('.js--check-floc-icons .wpm-success').show();
				jQuery('.js--check-floc-result').html(data.response);
			} else if (data.response && !data.success) {
				$('.js--check-floc-icons .wpm-error').show();
				jQuery('.js--check-floc-result').html(data.response);
			}
		}
	);
};

jQuery(document).ready(function ($) {
	$('.js--check-floc').on('click', () => {
		checkFloc();
	});
});
