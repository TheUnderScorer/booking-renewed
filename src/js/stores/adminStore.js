import { createStore, applyMiddleware, combineReducers } from 'redux';
import thunk from 'redux-thunk';
import adminSettingsReducer from '../reducers/adminSettingsReducer';
import snackBarReducer from '../reducers/snackBarReducer';
import settingsReducer from '../reducers/settingsReducer';

const InitialState = {};

const Middleware = [ thunk ];

export default createStore(
    combineReducers( {
        admin:    adminSettingsReducer,
        snackBar: snackBarReducer,
        settings: settingsReducer
    } ),
    InitialState,
    applyMiddleware( ...Middleware ),
);
