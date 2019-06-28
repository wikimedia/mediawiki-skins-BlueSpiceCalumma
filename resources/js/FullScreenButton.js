( function( d, $, mw ) {
	$( d ).on( 'click', '.calumma-full-screen-button', function( e ){
		e.preventDefault();

		var target =  $( this ).attr( 'data-toggle' );

		if ( $( this ).hasClass( target ) ){
			$( this ).removeClass( target );
			$( 'body.mediawiki .wrapper .bs-content.container' ).removeClass( target );
		} else{
			$( 'body.mediawiki .wrapper .bs-content.container' ).addClass( target );
			$( this ).addClass( target );
		}
	});
})( document, jQuery, mediaWiki );

