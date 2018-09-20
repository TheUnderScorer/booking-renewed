import { GET_SETTINGS } from './types';
import Client from '../http/Client';

export const getSettings = () => dispatch => {

    const Data = { action: 'wpbr_get_settings' };

    const handleResponse = response => {
        dispatch( {
            payload: response.data.result,
            type:    GET_SETTINGS
        } )
    };

    const handleError = error => {
        console.error( error );

        dispatch( {
            type:    GET_SETTINGS,
            payload: {
                error: true
            }
        } )
    };

    Client.post( Data )
        .then( handleResponse )
        .catch( handleError )

};
