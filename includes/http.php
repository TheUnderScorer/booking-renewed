<?php

namespace WPBR\App;

use WPBR\App\Http\Request;
use WPBR\App\Http\Response;

/**
 * Get global response object
 *
 * @return Response
 */
function response(): Response {

    global $wpk_response;

    if ( empty( $wpk_response ) ) {
        $wpk_response = new Response();
    }

    return $wpk_response;

}

/**
 * Get global request object
 *
 * @return Request
 */
function request(): Request {

    global $wpk_request;

    if ( empty( $wpk_request ) ) {
        $wpk_request = Request::createFromGlobals();
    }

    return $wpk_request;

}
