import { $ } from '../constants/jquery';

/**
 * Helper function for creating modal
 *
 * @param  {String} content Content of modal
 * @param  {Boolean} fade Whenever modal should have fade in animation
 * @param  {Object} css Additional css for modal
 *
 * @return {Promise}
 */
export function modal( { content, fade = false, css = {} } ) {

	return new Promise( function( resolve ) {

		let $container = $( '<div class="wpk-modal"></div>' ),
			overlay    = '<div class="wpk-modal-overlay"></div>',
			$body      = $( '.body' );


		$container.html( content );

		$body.append( $container );
		$body.append( overlay );

		let $modal      = $( '.wpk-modal' ),
			//Callback for removing modal
			removeModal = function() {
				$modal.fadeOut( 400, function() {
					$( this ).remove();
					$( '.wpk-modal-overlay' ).remove();
				} );
			};

		$modal.css( css );

		if ( fade ) {
			$modal.fadeIn( 400, function() {
				resolve( $modal );
			} );

		} else {
			$modal.show();
			resolve( $modal );
		}

		//Close modal when clicking overlay
		$( 'body, .wpk-modal-overlay' ).on( 'click', removeModal );

		$modal.on( 'click', e => e.stopPropagation() );

	} );

}