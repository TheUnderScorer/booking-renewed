<?php

namespace WPBR\App\Utility;

/**
 * @author Przemysław Żydek
 */
class Logger {

    /**
     * Perform log
     *
     * @param mixed  $log
     * @param string $title
     *
     * @return void
     */
    public static function log( $log, string $title = '' ) {

        $date = date( 'd-m-Y H:i' );

        error_log( $date );

        if ( ! empty( $title ) ) {
            error_log( $title );
        }

        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
        } else {
            error_log( $log );
        }

    }

    /**
     * Perform console log with provided value
     *
     * @param mixed $value
     *
     * @return void
     */
    public static function consoleLog( $value ) {

        $debug = debug_backtrace();
        $debug = $debug[ 0 ][ 'file' ] . ' Line ' . $debug[ 0 ][ 'line' ];

        $hook = is_admin() ? 'admin_footer' : 'wp_footer';

        add_action( $hook, function () use ( $debug, $value ) {
            ?>
            <script>
                'use strict';
                console.log(<?php echo json_encode( $debug ) ?>);
                console.log(<?php echo json_encode( $value ) ?>);
            </script>
            <?php
        } );

    }

}
