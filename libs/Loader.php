<?php

namespace WPBR\App;

/**
 * Helper class for loading class instances
 *
 * @author Przemysław Żydek
 */
class Loader {

    /**
     * Load provided classes
     *
     * @param array $items
     *
     * @return bool
     */
    public static function load( array $items ): bool {

        foreach ( $items as $item ) {
            new $item();
        }

        return true;

    }

}
