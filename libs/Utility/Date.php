<?php

namespace WPBR\App\Utility;

/**
 * Helper class related to dates
 *
 * @author Przemysław Żydek
 */
class Date {

    /**
     * Get date range from provided dates and return them in selected format.
     *
     * @param \DateTime     $from
     * @param \DateTime     $to
     * @param \DateInterval $interval
     * @param string        $format
     *
     * @return array
     */
    public static function getDateRange( \DateTime $from, \DateTime $to, \DateInterval $interval, string $format = 'Y-m-d' ): array {

        $range  = new \DatePeriod( $from, $interval, $to );
        $result = [];


        foreach ( $range as $item ) {
            $result[] = $item->format( $format );
        }
        try {
            $last = new \DateTime( end( $result ) );
            $last->modify( 'tomorrow' );
        } catch ( \Exception $e ) {
            $current_year = date( 'Y' );
            $last         = new \DateTime( $current_year . '-' . end( $result ) );
            $last->modify( 'tomorrow' );
        }
        $result[] = $last->format( $format );


        return $result;

    }

}
