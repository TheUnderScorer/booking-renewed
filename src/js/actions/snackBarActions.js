import { TOGGLE_SNACK_BAR } from './types';

export const toggleSnackBar = ( { message, onClick = false, onClose = false, undo = false, open = true } ) => dispatch => dispatch( {
    type:    TOGGLE_SNACK_BAR,
    payload: {
        message,
        onClick,
        undo,
        open,
        onClose
    }
} );
