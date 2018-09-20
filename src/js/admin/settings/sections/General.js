import React from 'react';
import MenuSection from '../MenuSection';
import { connect } from 'react-redux';
import { object, func, string, bool } from 'prop-types';
import Button from '@material-ui/core/Button';
import SnackBar from '../../../components/snack-bar/SnackBar';
import { toggleSnackBar } from '../../../actions/snackBarActions';
import { getSettings } from '../../../actions/settingsActions';
import CircularProgress from '@material-ui/core/CircularProgress';
import Client from '../../../http/Client';
import { Vars } from '../../../constants/vars';

class General extends MenuSection {

    state = {
        updated:           false,
        isLoading:         false,
        slotLength:        '',
        cancellationLimit: '',
        defaultStatus:     '',
        action:            'wpbr_save_general',
        fields:            Vars.fields.general
    };

    static propTypes = {
        toggleSnackBar:    func.isRequired,
        getSettings:       func.isRequired,
        settings:          object,
        fetched:           bool.isRequired
    };

    static mapStateToProps = state => ({
        settings: state.settings,
        fetched:  state.settings.fetched
    });

    handleChange = e => {

        let { name, value } = e.target;

        switch ( name ) {

            default:
                this.setState( prevState => {

                    const State = {
                        [ name ]: value
                    };

                    if ( prevState[ name ] !== value ) {
                        State.updated = true;
                    }

                    return State;

                } );

                break;

        }

    };

    handleSubmit = e => {

        e.preventDefault();

        this.setState( {
            isLoading: true,
            updated:   false,
        } );

        let { serverError, saved } = Vars.messages;

        const handleResponse = response => {

            let { data } = response;

            if ( !data.result ) {
                handleError( data );

                return;
            }

            this.setState( {
                isLoading: false,
                updated:   false,
            } );

            //Refresh server settings
            this.props.getSettings();

            this.props.toggleSnackBar( {
                message: saved
            } );

        };

        const handleError = error => {

            console.error( error );

            this.setState( {
                isLoading: false,
                updated:   false,
            } );

            this.props.toggleSnackBar( {
                message: serverError
            } )

        };

        Client.post( this.state )
            .then( handleResponse )
            .catch( handleError );

    };

    componentWillReceiveProps( props ) {

        console.log( props );

        if ( props.fetched ) {
            this.setState( {
                loaded: true,
                ...props.settings
            } )
        }

    }

    componentDidMount() {

        if ( this.props.fetched ) {
            this.setState( {
                loaded: true,
                ...this.props.settings
            } )
        }

    }

    render() {

        let { isLoading, updated, loaded } = this.state;

        if ( !loaded ) {
            return <div>{Vars.messages.pleaseWait}</div>
        }

        let { messages } = Vars;

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

export default connect( General.mapStateToProps, { toggleSnackBar, getSettings } )( General );
