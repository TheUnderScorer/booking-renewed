import React, { Component } from 'react';
import { string } from 'prop-types';

export default class Container extends Component {

    static defaultProps = {
        width:     '100%',
        height:    '100%',
        classList: ''
    };

    static propTypes = {
        width:     string,
        height:    string,
        classList: string
    };

    render() {

        let { width, height, classList, children } = this.props;

        classList += ' wpbr-container';

        return (
            <div
                className={classList} style={{
                width, height
            }}>
                {children}
            </div>
        );
    }

}
