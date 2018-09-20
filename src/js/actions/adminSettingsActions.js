import { CHANGE_SETTINGS_PAGE } from './types';

export const changeSettingsPage = page => dispatch => dispatch( {
    type: CHANGE_SETTINGS_PAGE,
    page
} );
