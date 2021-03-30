( function( d, $, mw ) {
	$( d ).on( 'click', '#bs-em-print', function( e ) {
		e.preventDefault();
		window.print();
	} );
} )( document, jQuery, mediaWiki );

