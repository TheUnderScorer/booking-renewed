<?php

namespace WPBR\Tests;

abstract class TestCase extends \WP_Ajax_UnitTestCase {

    /**
     * @param string $role
     *
     * @return void
     */
    protected function login( string $role = 'administrator' ) {

        $user = $this->factory()->user->create( [
            'role' => $role,
        ] );

        wp_set_current_user( $user );

    }

    /**
     * @return array|null
     */
    protected function getLastResponse() {
        return json_decode( $this->_last_response, true );
    }

}
