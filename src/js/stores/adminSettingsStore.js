import { createStore, applyMiddleware, combineReducers } from 'redux';
import thunk from 'redux-thunk';
import adminSettingsReducers from '../reducers/adminSettingsReducers';
import snackBarReducer from '../reducers/snackBarReducer';

const InitialState = {};

const Middleware = [ thunk ];

export default createStore(
    combineReducers( {
        admin:    adminSettingsReducers,
        snackBar: snackBarReducer
    } ),
    InitialState,
    applyMiddleware( ...Middleware ),
);
