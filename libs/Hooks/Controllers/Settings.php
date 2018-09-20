<?php

namespace WPBR\App\Hooks\Controllers;

use function WPBR\App\Core;
use function WPBR\App\request;
use function WPBR\App\response;

/**
 * @author Przemysław Żydek
 */
class Settings extends Controller {

    /**
     * Performs controller setup
     *
     * @return void
     */
    protected function setup() {
        add_action( 'wp_ajax_wpbr_get_settings', [ $this, 'handle' ] );
        add_action( 'wp_ajax_nopriv_wpbr_get_settings', [ $this, 'handle' ] );
    }

    /**
     * Get plugin settings and send them in response
     *
     * @return void
     */
    public function handle() {
        response()->setResult( Core()->settings->getAll() )->sendJson();
    }

}
