<?php

namespace WPBR\App\Utility;

/**
 * Helper class related to number operations
 *
 * @author Przemysław Żydek
 */
class Numbers {

    /**
     * Round provided number
     *
     * @param int|float $value
     *
     * @return string
     */
    public static function round( $value ): string {

        $value    = floatval( $value );
        $exploded = str_split( (string) $value );

        if ( $value >= 1000000 ) {
            return $exploded[ 0 ] . 'm';
        } else if ( $value >= 100000 ) {
            return $exploded[ 0 ] . $exploded[ 1 ] . $exploded[ 2 ] . 'k';
        } else if ( $value >= 10000 ) {
            return $exploded[ 0 ] . $exploded[ 1 ] . 'k';
        } else if ( $value > 999 ) {
            return $exploded[ 0 ] . 'k';
        }

        return $value;

    }

}
