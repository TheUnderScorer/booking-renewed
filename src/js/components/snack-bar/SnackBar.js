import React, { Component } from 'react';
import { bool, string, func, oneOfType } from 'prop-types';
import { connect } from 'react-redux';
import Button from '@material-ui/core/Button';
import Snackbar from '@material-ui/core/Snackbar';
import IconButton from '@material-ui/core/IconButton';
import CloseIcon from '@material-ui/icons/Close'
import { toggleSnackBar } from '../../actions/snackBarActions';


class SnackBar extends Component {

    static propTypes = {
        open:           bool.isRequired,
        message:        string.isRequired,
        undo:           bool.isRequired,
        toggleSnackBar: func.isRequired,
        onClick:        oneOfType( [ bool, func ] ).isRequired,
        onClose:        oneOfType( [ bool, func ] ).isRequired,
    };

    static mapStateToProps = state => ({
        ...state.snackBar
    });

    handleClose = e => {

        let { onClose } = this.props;

        if ( typeof onClose === 'function' ) {
            onClose( e );
        }

        this.props.toggleSnackBar( {
            message: '',
            open:    false,
        } )
    };

    handleUndo = e => {

    };

    getAction() {

        const Actions = [];

        if ( this.props.undo ) {
            Actions.push(
                <Button key="undo" color="secondary" size="small" onClick={this.handleUndo}>
                    UNDO
                </Button>,
            )
        }

        Actions.push(
            <IconButton
                key="close"
                aria-label="Close"
                color="inherit"
                className="close"
                onClick={this.handleClose}
            >
                <CloseIcon/>
            </IconButton>
        );

        return Actions;

    }

    render() {

        let { open, message } = this.props;

        return (
            <Snackbar
                anchorOrigin={{
                    vertical:   'bottom',
                    horizontal: 'right',
                }}
                open={open}
                autoHideDuration={6000}
                onClose={this.handleClose}
                message={<span>{message}</span>}
                action={this.getAction()}
            />
        );
    }

}

export default connect( SnackBar.mapStateToProps, { toggleSnackBar } )( SnackBar );
