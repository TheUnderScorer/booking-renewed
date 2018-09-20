import { GET_SETTINGS } from '../actions/types';

const InitialState = {
    fetched: false,
};

export default function( state = InitialState, action ) {

    switch ( action.type ) {

        case GET_SETTINGS:

            action.payload.fetched = true;

            return {
                ...state,
                ...action.payload
            };

        default:

            return state;

    }

}
