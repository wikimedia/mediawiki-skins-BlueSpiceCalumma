( function( d, $, mw ) {
	$( d ).on( 'click',
			'#bs-breadcrumbs-pages:not(.open) .dropdown-toggle, #bs-breadcrumbs-subpages:not(.open) .dropdown-toggle',
			function( e ) {

		var node = ':' + $( this ).data( 'node' );

		var $target = $( this ).parent().children( '.dropdown-menu' ).children( 'ul' );

		$target.html( '' );

		var api = new mw.Api();
		api.abort();
        api.get(
				{
					"format": "json",
					"action": "bs-wikisubpage-treestore",
					"node": node,
					"limit": "-1"
				}
			)
            .done( function( response ){
				var list = '';
				for ( var i = 0; i < response.children.length; i++ ) {
					$( $target ).append( '<li>' + response.children[i].page_link + '</li>' );
				};
			});
	});
})( document, jQuery, mediaWiki );
