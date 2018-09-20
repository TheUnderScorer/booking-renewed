<?php

namespace WPBR\App\Storage;

/**
 * Manages custom caching
 *
 * @author Przemysław Żydek
 */
class Cache {

    /** @var bool Decides if cache is active or not */
    const ENABLED = true;

    /** @var string */
    protected $uniqueID;

    /** @var string */
    protected $expiration;

    /**
     * Cache constructor.
     *
     * @param string $uniqueID Unique cache identifier
     * @param string $expiration Date parsable by strtotime()
     */
    public function __construct( string $uniqueID, string $expiration = '+3 hours' ) {

        $this->uniqueID   = $uniqueID;
        $this->expiration = $expiration;

    }

    /**
     * Create unique key for cache value
     *
     * @param string $key
     *
     * @return string
     */
    protected function createUniqueKey( string $key ): string {
        return $this->uniqueID . $key;
    }


    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function add( string $key, $value ): bool {

        if ( ! self::ENABLED || empty( $value ) ) {
            return false;
        }

        $uniqueKey = $this->createUniqueKey( $key );

        set_transient( $uniqueKey, $value, strtotime( $this->expiration ) );

        return true;

    }

    /**
     * @param string $key
     *
     * @return bool|mixed
     */
    public function get( $key ) {

        if ( ! self::ENABLED ) {
            return false;
        }

        $uniqueKey = $this->createUniqueKey( $key );
        $value     = get_transient( $uniqueKey );

        return $value ? $value : false;

    }

}
