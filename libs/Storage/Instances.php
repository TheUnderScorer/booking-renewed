<?php

namespace WPBR\App\Storage;

/**
 * Storage class for storing other classes instances
 *
 * @author Przemysław Żydek
 */
class Instances {

    /** @var array */
    protected static $instances = [];

    /**
     * Get instance of provided class if it is stored
     *
     * @param string $key
     *
     * @return bool|mixed
     */
    public static function get( string $key ) {
        return self::$instances[ $key ] ?? false;
    }

    /**
     * Add new instance to storage
     *
     * @param string $key
     * @param mixed  $instance
     *
     * @return mixed Instance of provided class
     */
    public static function add( string $key, $instance ) {

        if ( ! self::get( $key ) ) {
            self::$instances[ $key ] = $instance;
        }

        return $instance;

    }

}
