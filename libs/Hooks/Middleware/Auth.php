<?php

namespace WPBR\App\Hooks\Middleware;

use function WPBR\App\response;

/**
 * Helper auth class that checks user login status
 *
 * @author Przemysław Żydek
 */
class Auth implements Middleware {

    /**
     * @param array $params
     *
     * @return void
     */
    public function handle( array $params = [] ) {
        response()->checkUserLoggedIn();
    }

}
