window.__text_connect = {
	addons: textConnect.addons,
	visitor: textConnect.visitor,
	getCartContent: function (success) {
		if (!this.addons?.includes('woocommerce')) {
			return false;
		}

		let xhr;

		try {
			xhr = new XMLHttpRequest();
		} catch (e) {
			return false;
		}

		xhr.open('POST', textConnect.ajax_url, true);
		xhr.setRequestHeader(
			'Content-Type',
			'application/x-www-form-urlencoded; charset=UTF-8'
		);
		xhr.onreadystatechange = function () {
			if (xhr.readyState > 3 && xhr.status === 200 && success) {
				success(xhr.responseText);
			}
		};
		xhr.send('action=text-refresh-cart');

		return xhr;
	}
}
