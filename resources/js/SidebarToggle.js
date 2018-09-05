( function( d, $, mw ) {
	$('.sidebar-toggle').on( 'click', function(e){
		e.preventDefault();

		var target =  $(this).attr('data-toggle');

		if( $('body').hasClass( target ) ){
			$('body').removeClass( target );
			mw.cookie.set( 'Calumma_'+target, 'false' );
			var close = target.replace( '-sticky', '-collapse' );
			if ( close !== target ){
				mw.cookie.set( 'Calumma_'+close, 'false' );
				$('body').addClass( close );
			}
		}
		else{
			$('body').addClass( target );
			mw.cookie.set( 'Calumma_'+target, 'true' );
		}
	});

	$('.bs-tabs').on( 'click', '.active a[data-toggle="tab"]', function(e){
		var $anchor = $(this);
		var targetId = $anchor.attr( 'href' ).substring( 1 ); //cut off leading '#'
		var $tabsContainer = $anchor.closest( '.bs-tabs' );
		var tabsContainerId = $tabsContainer.attr( 'id' );

		var asideTrigger = $anchor.closest( 'aside' ).attr('data-toggle-by');

		var activeTab = mw.cookie.get( 'CalummaTab_'+tabsContainerId );
		if ( activeTab === targetId ){
			$('body').addClass( asideTrigger );
			mw.cookie.set( 'Calumma_' + asideTrigger, 'true' );

			/*close graphicallist*/
			var $graphicalListTrigger = $anchor.closest( 'aside' ).find( '.dynamic-graphical-list-visible a.title' );
			$graphicalListTrigger.trigger( 'click' );
		}
	});
})( document, jQuery, mediaWiki );
