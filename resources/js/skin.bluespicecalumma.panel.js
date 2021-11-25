(function( mw, $, d, bs, undefined ) {
	$(d).on( 'click', '.bs-panel a.title, .panel a.title', function( e ) {
		var $title = $(this);
		var collapeState = false;
		setTimeout( function() {
			var collapsingPanel = $title.attr( 'aria-controls' );
			if ( $title.attr( 'aria-expanded' ) === 'false' ) {
				collapeState = true;
			}
			bs.calumma.cookie.set( 'Calumma_CollapsePanel_'+ collapsingPanel, collapeState );
		},
		500 )
	} );
})( mediaWiki, jQuery, document, blueSpice );


(function( mw, $, d, bs, undefined ) {
	$(d).on( 'click', '.bs-panel a.title', function( e ) {
		e.preventDefault();
		var $title = $(this);
		var $panel = $title.parents( '.bs-panel' );

		_handlePanelHeaderClick( $title, $panel );
	} );

	function _handlePanelHeaderClick( $title, $panel ) {
		if( _isFlyoutTrigger( $title ) ) {
			_showFlyout( $panel );
		}
		else {
			//Collapse/Expand is handled by Bootstrap JS
		}
	}

	function _isFlyoutTrigger( $title) {
		return $title.find( '.trigger' ).hasClass( 'flyout' );
	}

	function _showFlyout( $panel ) {
		var data = $panel.data();
		var direction = _calcFlyoutDirection( $panel );
		var $header = $panel.find( '.panel-heading' );

		$header.dynamicGraphicalList({
			title: data.flyoutTitle,
			intro: data.flyoutIntro,
			body: function( $elem, $body ) {
				var dfd = $.Deferred();
				mw.loader.using( data.triggerRlDeps ).done( function() {
					bs.util
						.runCallback( data.triggerCallback, [ $body, data ], $elem )
						.done( function() {
							dfd.resolve();
						});
				});
				return dfd.promise();
			},
			direction: direction
		}).toggle();

		return false;
	}

	function _calcFlyoutDirection( $panel ) {
		var direction = 'east';
		var $parentDataContainer = $panel.parents( '*[data-flyout-direction]' );
		if( $parentDataContainer.length > 0 ) {
			direction = $parentDataContainer.data( 'flyout-direction' );
		}

		return direction;
	}

})( mediaWiki, jQuery, document, blueSpice );
