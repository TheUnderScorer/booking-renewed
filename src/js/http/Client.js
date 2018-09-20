import axios from 'axios';
import { Vars } from '../constants/vars';

export default class Client {

    static axios = axios.create( {
        baseURL: Vars.ajaxUrl,
        withCredentials: true
    } );

    /**
     * @return {Promise}
     * */
    static post( data = {} ) {
        return this.axios.post( '/', new URLSearchParams( data ) );
    }

    /**
     * @return {Promise}
     * */
    static get( query = {} ) {

        let queryString = '?';

        if ( Object.keys( query ).length ) {
            for ( let prop in query ) {
                queryString += `${prop}=${query[ prop ]}&`;
            }
        }

        return this.axios.get( `/${queryString}` );

    }

}
