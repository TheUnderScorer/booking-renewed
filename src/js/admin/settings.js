import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import adminSettingsStore from '../stores/adminStore';
import Container from '../components/container/Container';
import Tabs from './settings/SettingsMenu';
import { getSettings } from '../actions/settingsActions';

adminSettingsStore.dispatch( getSettings() );

const Markup = (
    <Provider store={adminSettingsStore}>
        <Container classList="wpbr-settings-container">
            <Tabs/>
        </Container>
    </Provider>
);


ReactDOM.render( Markup, document.getElementById( 'wpbr_settings' ) );
