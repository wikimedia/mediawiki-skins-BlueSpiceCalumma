( function ( d, $, mw ) {
	var curPanelNr = 0;
	var id = null;
	var lastTouch = null;
	var maxY = 0;
	var panel = [];

	$( 'aside .tab-content .tab-pane' ).on( 'touchstart', function() {
		maxY = $( this ).height();
		id = $( this ).attr( 'id' );

		var curPanel = {
			id: id,
			top: $( this ).scrollTop()
		};

		if ( panel.length === 0 ) {
			panel.push( curPanel );
		}

		for ( var i = 0; i < panel.length; i++ ) {
			if ( panel[i].hasOwnProperty( 'id' ) && panel[i].id === id ) {
				curPanelNr = i;
				$( this ).closest( '.tab-content' ).scrollTop( panel[i].top );
			}
		}
	} );

	$( 'aside .tab-content .tab-pane' ).on( 'touchmove', function( e ) {
		e.preventDefault();

		var touchEvent = ( typeof e.originalEvent === 'undefined')? e:e.originalEvent;
		var touch = touchEvent.touches[0] || touchEvent.changedTouches[0];
		var curTouch = {
			pageY: touch.pageY
		}

		if ( lastTouch === null ) {
			lastTouch = curTouch;
		}

		if ( ( curTouch.pageY < lastTouch.pageY ) ) {
			var deltaY = lastTouch.pageY - curTouch.pageY;
			var newPanelPos = panel[curPanelNr].top + deltaY;
		}

		if ( curTouch.pageY > lastTouch.pageY ) {
			var deltaY = curTouch.pageY - lastTouch.pageY ;
			var newPanelPos = panel[curPanelNr].top - deltaY;
		}

		lastTouch = curTouch;

		if ( newPanelPos >= 0 ) {
			panel[ curPanelNr ].top = newPanelPos;
		}

		$( this ).closest( '.tab-content' ).scrollTop( panel[curPanelNr].top );
	} );

	$( 'aside .tab-content .tab-pane' ).on( 'touchend', function() {
		lastTouch = null;
		id = null;
	} );


	// Reset position for mobile devices
	if ( $( d ).width() <= 721 ) {
		$( d ).on( 'click', '.bs-tabs a[data-toggle="tab"]', function( e ) {
			var panelId = $( this ).attr( 'href' );
 			$( panelId ).parent( '.tab-content' ).scrollTop( 0 );
		} );

		$( d ).on( 'click', '.sidebar-toggle', function( e ){
			var activePanels = $( 'aside .tab-content .tab-pane.active' );
			for ( var i = 0; i > activePanels.length; i++ ) {
				activePanels[i].scrollTop( 0 );
			}
		} );
	}
})( document, jQuery, mediaWiki );