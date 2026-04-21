window.onload = function () {
	const button = document.getElementById(
		'deactivate-wp-live-chat-software-for-wordpress'
	);

	const { deactivationFeedbackUrl } = deactivationHandler;

	if (button && deactivationFeedbackUrl) {
		button.addEventListener('click', function () {
			window.open(deactivationFeedbackUrl, '_blank').focus();
		});
	}
};
