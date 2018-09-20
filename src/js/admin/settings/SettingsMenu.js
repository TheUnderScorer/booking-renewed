import React, { Component } from 'react';
import { string, func } from 'prop-types';
import { connect } from 'react-redux';
import AppBar from '@material-ui/core/AppBar';
import Typography from '@material-ui/core/Typography';
import Toolbar from '@material-ui/core/Toolbar';
import { changeSettingsPage } from '../../actions/adminSettingsActions';
import Drawer from '@material-ui/core/Drawer';
import General from './General';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import SettingsIcon from '@material-ui/icons/Settings';
import CalendarIcon from '@material-ui/icons/CalendarToday';
import IntegrationsIcon from '@material-ui/icons/LibraryAdd';
import './scss/settings-menu.scss';

class SettingsMenu extends Component {

    static propTypes = {
        page:              string.isRequired,
        changeSettingsTab: func.isRequired
    };

    static mapStateToProps = state => ({
        ...state.admin
    });

    handleMenuListClick = e => {

        let { page } = e.currentTarget.dataset;

        window.location.hash = page;

        this.props.changeSettingsTab( page );

    };

    renderMenuList() {

        let { page } = this.props;

        let index = -1;

        const renderList = ( name, icon ) => {

            let display = name.replace( '_', ' ' );
            display = display[ 0 ].toUpperCase() + display.slice( 1 );

            index++;

            return (
                <div key={index}>
                    <ListItem selected={page === name} onClick={this.handleMenuListClick} data-page={name} button>
                        <ListItemIcon>
                            {icon}
                        </ListItemIcon>
                        <ListItemText primary={display}/>
                    </ListItem>
                </div>
            )
        };

        return [
            renderList( 'general', <SettingsIcon/> ),
            renderList( 'calendar', <CalendarIcon/> ),
            renderList( 'integrations', <IntegrationsIcon/> )
        ]
    }

    renderTabContent() {

        let component;

        switch ( this.props.page ) {

            case 'general':
                component = (<General/>);
                break;

        }

        return (
            <Typography className="wpbr-settings-page" component="div" style={{ padding: 8 * 3 }}>
                {component}
            </Typography>
        )
    }

    render() {

        return (
            <section className="wpbr-settings-menu">
                <AppBar position="static" className="wpbr-appbar">
                    <Toolbar>
                        <Typography variant="title" color="inherit" noWrap>
                            Booking Renewed Settings
                        </Typography>
                    </Toolbar>
                </AppBar>
                <Drawer className="wpbr-settings-drawer" variant="permanent">
                    <List>
                        {this.renderMenuList()}
                    </List>
                </Drawer>
                {this.renderTabContent()}
            </section>
        );
    }

}

export default connect( SettingsMenu.mapStateToProps, { changeSettingsTab: changeSettingsPage } )( SettingsMenu )
