import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import adminSettingsStore from '../stores/adminSettingsStore';
import Container from '../components/container/Container';
import Tabs from './settings/SettingsMenu';

const Markup = (
    <Provider store={adminSettingsStore}>
        <Container classList="wpbr-settings-container">
            <Tabs/>
        </Container>
    </Provider>
);


ReactDOM.render( Markup, document.getElementById( 'wpbr_settings' ) );
