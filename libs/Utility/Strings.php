<?php

namespace WPBR\App\Utility;

/**
 * String related utilities
 *
 * @author Przemysław Żydek
 */
class Strings {

    /**
     * Check if string contains provided substring
     *
     * @param string       $string
     * @param string|array $substring
     *
     * @return bool
     */
    public static function contains( string $string, string $substring ): bool {

        if ( is_array( $substring ) ) {

            foreach ( $substring as $item ) {
                if ( strpos( $string, $item ) === false ) {
                    return false;
                }
            }

            return true;
        } else {
            return strpos( $string, $substring ) !== false;
        }

    }

}
