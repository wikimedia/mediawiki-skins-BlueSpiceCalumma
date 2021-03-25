( function ( d, $, mw ) {
	var cookieName = 'CalummaSidebar_scroll';
	var panel = [];

	$( d ).ready( function( e ){
		var activePanels = $( 'aside .tab-content .tab-pane.active' );

		var panelCookie = bs.calumma.cookie.get( cookieName );
		if ( panelCookie ) {
			panel = panelCookie;
		}

		for ( var j = 0; j < activePanels.length; j++ ) {
			var curPanelNr = 0;
			var id = activePanels[j].id;
			var panelInCookie = false;

			if( $( d ).width() > 721 ) {
				for ( var i = 0; i < panel.length; i++ ) {
					if ( panel[i].hasOwnProperty( 'id' ) && panel[i].id === id ) {
						panelInCookie = true;
						curPanelNr = i;
						$( '#' + id ).parent( '.tab-content' ).scrollTop( panel[i].top );
					}
				}
			}

			var curPanel = {
				id: id,
				top: $( this ).scrollTop()
			};

			if ( !panelInCookie ) {
				curPanelNr = panel.length;
				panel.push( curPanel );
			}
		}
	} );

	$( d ).on( 'click', '.bs-tabs a[data-toggle="tab"]', function( e ) {
		var curPanelNr = 0;
		var target = $( this ).attr( 'href' );
		var id = $( target ).attr( 'id' );

		var curPanel = {
			id: id,
			top: $( target ).parent( '.tab-content' ).scrollTop()
		};

		var panelCookie = bs.calumma.cookie.get( cookieName );
		if ( panelCookie ) {
			panel = panelCookie;
		}

		var panelInCookie = false;
		if( $( d ).width() > 721 ) {
			for ( var i = 0; i < panel.length; i++ ) {
				if ( panel[i].hasOwnProperty( 'id' ) && panel[i].id === id ) {
					panelInCookie = true;
					curPanelNr = i;
					$( '#' + id ).parent( '.tab-content' ).scrollTop( panel[i].top );
				}
			}
		}

		if ( !panelInCookie ) {
			curPanelNr = panel.length;
 			panel.push( curPanel );
		}
	} );


	$( 'aside .tab-content' ).on( 'scroll', function( e ) {
		e.preventDefault();

		var id = $( this ).children( '.tab-pane.active' ).first().attr( 'id' );

		for ( var i = 0; i < panel.length; i++ ) {
			if ( panel[i].hasOwnProperty( 'id' ) && panel[i].id === id ) {
				panel[i].top = $( this ).scrollTop();
				bs.calumma.cookie.set( cookieName, panel );
			}
		}
	} );
})( document, jQuery, mediaWiki );