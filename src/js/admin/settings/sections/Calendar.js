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

class Calendar extends MenuSection {

    state = {
        updated:   false,
        isLoading: false,
        action:    'wpbr_save_calendar',
        fields:    Vars.fields.calendar
    };

    static propTypes = {
        toggleSnackBar: func.isRequired,
        getSettings:    func.isRequired,
        messages:       object,
        fetched:        bool.isRequired
    };

    static mapStateToProps = state => ({
        settings: state.settings,
        fetched:  state.settings.fetched,
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

            //Refresh server vars
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
            <form onSubmit={this.handleSubmit} name="wpbr_calendar" id="wpbr_calendar" className="wpbr-settings-section">
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
                <SnackBar/>
                {this.renderDropdown( 'firstDay', 'days' )}
            </form>
        )

    }

}

export default connect( Calendar.mapStateToProps, { toggleSnackBar, getSettings } )( Calendar );
