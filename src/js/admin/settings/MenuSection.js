import React, { Component } from 'react';
import MenuItem from '@material-ui/core/MenuItem/MenuItem';
import FormSection from '../../components/form/FormSection';
import Select from '@material-ui/core/Select/Select';
import { Vars } from '../../constants/vars';
import FormControlLabel from '@material-ui/core/FormControlLabel';

export default class MenuSection extends Component {

    renderDropdownOptions( name, varsKey ) {

        const Options = [];

        let items = this.state.fields[ varsKey ],
            index = 0;

        for ( let prop in items ) {
            Options.push(
                <MenuItem key={index} value={prop}>
                    {items[ prop ]}
                </MenuItem>
            );
            index++;
        }

        return Options;

    }

    renderDropdown( name, varsKey ) {

        let { messages } = Vars;

        return (
            <FormSection title={messages[ name ]} subtitle={messages[ `${name}Sub` ]}>
                <Select
                    value={this.state[ name ]}
                    onChange={this.handleChange}
                    inputProps={{
                        name: name,
                        id:   name
                    }}>
                    {this.renderDropdownOptions( name, varsKey )}
                </Select>
            </FormSection>
        );

    }

}
