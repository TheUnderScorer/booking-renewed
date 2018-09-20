import { CHANGE_SETTINGS_PAGE } from '../actions/types';

const InitialState = {
    page: window.location.hash ? window.location.hash.replace( '#', '' ) : 'general',
};

export default function( state = InitialState, action ) {

    switch ( action.type ) {

        case CHANGE_SETTINGS_PAGE:

            return {
                ...state,
                ...action.payload
            };

        default:
            return state;

    }

};
