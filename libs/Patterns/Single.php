<?php

namespace WPBR\App\Patterns;

/**
 * Helper Single class. This class should be extended by any class that should be instanted only once
 *
 * @author Przemysław Żydek
 */
trait Single {

    /** @var static */
    protected static $instance;

    /**
     * Single constructor
     */
    protected function __construct() {
    }

    /**
     * No serializing
     */
    protected function __sleep() {
    }

    /**
     * No unserializing
     */
    protected function __wakeup() {
    }

    /**
     * @return mixed
     */
    public static function getInstance() {

        if ( empty( static::$instance ) ) {
            static::$instance = new static();
        }

        return static::$instance;

    }

}
