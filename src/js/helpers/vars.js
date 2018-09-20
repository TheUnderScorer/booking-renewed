import { Vars } from '../constants/vars';
import { isset } from './checkers';

/**
 * Get message from script vars
 *
 * @param {String} message
 *
 * @return String
 * */
export function getMessage( message ) {
	return isset( Vars.messages[ message ] ) ? Vars.messages[ message ] : '';
}

/**
 * Helper function for getting var
 *
 * @param {mixed} key
 * @param {mixed} def Default value is this var don't exist
 *
 * @return mixed
 * */
export function getVar( key, def = false ) {
	return isset( Vars[ key ] ) ? Vars[ key ] : def;
}
