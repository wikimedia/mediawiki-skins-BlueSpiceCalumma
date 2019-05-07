( function( d, $, mw ) {
	$( d ).ready( function(){
		/* scroll to jumpmark on load */
		var hash = window.location.hash;

		if ( hash !== '' ) {
			var jumpmarkId = hash.replace( '#', '' );

			var jumpmark = d.getElementById( jumpmarkId );
			if ( !jumpmark ) {
				return;
			}

			var position = $( jumpmark ).position().top;

			$( 'body, html').animate(
				{
					scrollTop: position
				},
				100
			);
		};

		/* scroll to jumpmark on click (TOC, imagemap, ...) */
		$( '#bodyContent' ).on( 'click', 'a, map area', function( e ) {
			var hash = this.hash;

			if( $( this ).hasClass( 'external' ) ) {
				return;
			}

			if( this.pathname !== mw.util.getUrl() ) {
				return;
			}

			if ( hash !== '' ) {
				var jumpmarkId = hash.replace( '#', '' );

				var jumpmark = d.getElementById( jumpmarkId );
				if ( !jumpmark ) {
					return;
				}

				var position = $( jumpmark ).position().top;

				$( 'body, html').animate(
					{
						scrollTop: position
					},
					400
				);

				return false;
			}
		});
	});
}) ( document, jQuery, mediaWiki);
