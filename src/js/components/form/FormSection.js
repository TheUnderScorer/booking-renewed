import React, { Component } from 'react';
import { string } from 'prop-types';
import './scss/form-section.scss';

const FormSection = ( { title, subtitle = '', children } ) => {

    return (
        <div className="wpbr-form-section">
            <h2 className="wpbr-section-title">
                {title}
            </h2>
            {subtitle && <small className="wpbr-section-subtitle">{subtitle}</small>}
            <div className="wpbr-section-inner">
                {children}
            </div>
        </div>
    );

};

FormSection.propTypes = {
    title:    string.isRequired,
    subtitle: string,
};

export default FormSection;
