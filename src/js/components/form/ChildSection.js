import React from 'react';
import { string } from 'prop-types';
import './scss/child-section.scss';

const ChildSection = ( { className = '', children } ) => {

    className += ' wpbr-child-section';

    return (
        <section className={className}>
            {children}
        </section>
    );

};

ChildSection.propTypes = {
    className: string
};

export default ChildSection;
