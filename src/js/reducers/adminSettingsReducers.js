import { CHANGE_SETTINGS_PAGE } from '../actions/types';

const InitialState = {
    page: 'general',
    vars: wpbr_admin_settings_vars,
};

export default function( state = InitialState, action ) {

    switch ( action.type ) {

        case CHANGE_SETTINGS_PAGE:

            return {
                ...state,
                ...action
            };

        default:
            return state;

    }

};
