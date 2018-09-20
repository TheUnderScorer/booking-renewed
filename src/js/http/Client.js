import axios from 'axios';

export default class Client {

    constructor( url ) {

        this.axios = axios.create( {
            baseURL:         url,
            headers:         {
                'Content-Type': 'application/json'
            },
            withCredentials: true
        } )

    }

    /**
     * @return {Promise}
     * */
    post( data ) {
        return this.axios.post( '/', data );
    }

    /**
     * @return {Promise}
     * */
    get( query = {} ) {

        let queryString = '?';

        if ( Object.keys( query ).length ) {
            for ( let prop in query ) {
                queryString += `${prop}=${query[ prop ]}&`;
            }
        }

        return this.axios.get( `/${queryString}` );

    }

}
