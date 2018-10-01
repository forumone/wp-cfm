function getCookie(name) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : null;
}

function setCookie(name, value, days) {
    var d = new Date;

    // Time is in milliseconds.
    d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
    document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
}

var consent = getCookie('woocart-gdpr');

if(consent !== null) {
	document.querySelector('.wc-defaults-gdpr').style.display = 'none';
}

document.addEventListener('click', function(event) {
	if(!event.target.matches('#wc-defaults-ok')) {
		return;
	}

	// Prevent default click event.
	event.preventDefault();

	// Set cookie for 180 days.
	setCookie('woocart-gdpr', 'agree', 180);

	// Hide consent bar.
	document.querySelector('.wc-defaults-gdpr').style.display = 'none';
}, false);
