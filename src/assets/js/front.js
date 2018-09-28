(function( $ ) {
	var consent = Cookies.get( 'woocart-gdpr' );

	if( consent ) {
		$( '.wc-defaults-gdpr' ).hide();
	}

	// On clicking consent button
	$( document ).on( 'click', '#wc-defaults-ok', function( e ) {
		e.preventDefault();

		// Set the cookie (with expiry after 180 days)
		Cookies.set( 'woocart-gdpr', 'agree', { expires: 180 } );

		// Hide the notification
		$( '.wc-defaults-gdpr' ).fadeOut();
	} );
})( jQuery );