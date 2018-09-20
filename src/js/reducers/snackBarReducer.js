import { TOGGLE_SNACK_BAR } from '../actions/types';

const InitialState = {
    message: '',
    open:    false,
    undo:    false,
    onClick: false,
    onClose: false,
};

export default function( state = InitialState, action ) {

    switch ( action.type ) {

        case TOGGLE_SNACK_BAR:

            return {
                ...state,
                ...action
            };

        default:
            return state;

    }

};
