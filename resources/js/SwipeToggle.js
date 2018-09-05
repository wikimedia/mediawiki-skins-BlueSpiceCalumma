$( document ).on( "pagecreate", "body", function(){

	// is this a mobile device?
	if( $( document ).width() < 1025) {

		// close navigation_main
		$( ".navigation-main" ).on( "swipeleft", function() {
			if( !$( 'body' ).hasClass( 'navigation-main-collapse' ) ) {
				$( 'body' ).addClass( 'navigation-main-collapse' );
			}
		});

		// open navigation_main
		$( ".content-wrapper" ).on( "swiperight", function() {
			if( $( 'body' ).hasClass( 'navigation-main-collapse' ) ) {
				$( 'body' ).removeClass( 'navigation-main-collapse' );
			}
		});
	}

	if( $( document ).width() < 361) {
		// close sitetools_main
		$( ".sitetools-main" ).on( "swiperight", function() {
			if( !$( 'body' ).hasClass( 'sitetools-main-collapse' ) ) {
				$( 'body' ).addClass( 'sitetools-main-collapse' );
			}
		});

		// open sitetools_main
		$( ".content-wrapper").on( "swipeleft", function() {
			if( $( 'body' ).hasClass( 'sitetools-main-collapse' ) ) {
				$( 'body' ).removeClass( 'sitetools-main-collapse' );
			}
		});
	}
});