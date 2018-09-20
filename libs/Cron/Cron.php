<?php

namespace WPBR\App\Cron;

/**
 * Helper class for creating cron tasks
 *
 * @author Przemysław Żydek
 */
abstract class Cron {

    /** @var string Hook used for cron task */
    const HOOK = '';

    /**
     * Cron constructor.
     */
    public function __construct() {
        add_action( static::HOOK, [ $this, 'handle' ] );
    }

    /**
     * Handles creating new cron task
     *
     * @param array $params
     *
     * @return void
     */
    abstract public function handle( array $params = [] );

    /**
     * Handles removing cron task
     *
     * @param mixed $param
     *
     * @return void
     */
    abstract public function remove( $param );

}
