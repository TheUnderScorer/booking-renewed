import { $ } from '../constants/jquery';
import { getVar } from './vars';

/**
 * Perform ajax call.
 *
 * @param {Object} args Additional settings for ajax
 * */
export function ajax( args = [] ) {

	let settings = {
		type:        'POST',
		url:         getVar( 'ajax_url' ),
		processData: false,
		contentType: false,
		error( xhr, ajaxOptions, thrownError ) {
			console.error( thrownError );
		},
	};

	settings = $.extend( settings, args );

	return $.ajax( settings );

}
