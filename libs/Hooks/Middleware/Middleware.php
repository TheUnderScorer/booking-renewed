<?php

namespace WPBR\App\Hooks\Middleware;

/**
 * @author Przemysław Żydek
 */
interface Middleware {

    /**
     * Calls middleware action
     *
     * @param array $params Optional middleware parameters
     *
     * @return void
     */
    public function handle( array $params = [] );

}
