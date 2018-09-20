import React, { Component } from 'react';
import { connect } from 'react-redux';
import { object, func } from 'prop-types';
import Drawer from '@material-ui/core/Drawer';
import Button from '@material-ui/core/Button';
import List from '@material-ui/core/List';
import Divider from '@material-ui/core/Divider';
import FormSection from '../../components/form/FormSection';
import FormControl from '@material-ui/core/FormControl';
import Select from '@material-ui/core/Select';
import InputLabel from '@material-ui/core/InputLabel';
import MenuItem from '@material-ui/core/MenuItem';
import SnackBar from '../../components/snack-bar/SnackBar';
import { toggleSnackBar } from '../../actions/snackBarActions';
import CircularProgress from '@material-ui/core/CircularProgress';
import Client from '../../http/Client';

class General extends Component {

    state = {
        updated:           false,
        isLoading:         false,
        slotLength:        '15 minutes',
        cancellationLimit: '15 minutes',
        defaultStatus:     'pending',
        action:            'wpbr_save_general'
    };

    static propTypes = {
        vars:           object.isRequired,
        toggleSnackBar: func.isRequired,
    };

    static mapStateToProps = state => ({
        vars: state.admin.vars,
    });

    constructor( props ) {

        super( props );

        this.client = new Client( this.props.vars.ajaxUrl )

    }


    handleChange = e => {

        let { name, value } = e.target;

        this.setState( prevState => {

            const State = {};

            State[ name ] = value;

            if ( prevState[ name ] !== value ) {
                State.updated = true;
            }

            return State;

        } );

    };

    renderDropdownOptions( varsKey ) {

        const Items = [];

        let items = this.props.vars[ varsKey ],
            index = 0;

        for ( let prop in items ) {
            Items.push(
                <MenuItem key={index} value={prop}>
                    {items[ prop ]}
                </MenuItem>
            );
            index++;
        }

        return Items;

    }

    renderDropdown( name, varsKey ) {

        let { vars }     = this.props,
            { messages } = vars;

        return (
            <FormSection title={messages[ name ]} subtitle={messages[ `${name}Sub` ]}>
                <Select
                    value={this.state[ name ]}
                    onChange={this.handleChange}
                    inputProps={{
                        name: name,
                        id:   name
                    }}
                >
                    {this.renderDropdownOptions( varsKey )}
                </Select>
            </FormSection>
        );

    }

    handleSubmit = e => {

        e.preventDefault();

        this.setState( {
            isLoading: true,
            updated:   false,
        } );

        const handleResponse = response => {
            console.log( response );

            this.setState( {
                isLoading: false,
                updated:   false,
            } );

            this.props.toggleSnackBar( {
                message: 'Settings saved!'
            } )

        };

        const handleError = error => {

            this.setState( {
                isLoading: false,
                updated:   false,
            } );

            this.props.toggleSnackBar( {
                message: this.props.vars.messages.serverError
            } )

        };

        this.client.post( this.state )
            .then( handleResponse )
            .catch( handleError );

    };

    render() {

        let { isLoading, updated } = this.state,
            { vars }               = this.props,
            { messages }           = vars;

        return (
            <form onSubmit={this.handleSubmit} name="wpbr_general" id="wpbr_general" className="wpbr-settings-section">
                <section className="wpbr-submit-section">
                    <Button
                        type="submit"
                        variant="raised"
                        color="primary">
                        {isLoading ? <CircularProgress
                                color="secondary"
                                size={20}/> :
                            messages.save

                        }
                    </Button>
                    {updated && !isLoading && <small>{messages.changesMade}</small>}
                </section>
                {this.renderDropdown( 'slotLength', 'slotLengths' )}
                {this.renderDropdown( 'cancellationLimit', 'cancelLengths' )}
                {this.renderDropdown( 'defaultStatus', 'bookingStatuses' )}
                <SnackBar/>
            </form>
        )

    }

}

export default connect( General.mapStateToProps, { toggleSnackBar } )( General );
