<?php

namespace WPBR\App\Models;

use WPBR\App\Interfaces;

/**
 * @author Przemysław Żydek
 */
class Collection implements Interfaces\Jsonable, Interfaces\Arrayable {

    /** @var array */
    protected $items = [];

    /**
     * Collection constructor.
     *
     * @param mixed $items
     */
    public function __construct( $items = [] ) {
        $this->items = $this->getArrayItems( $items );
    }

    /**
     * Parses items to array
     *
     * @param mixed $items
     *
     * @return array
     */
    protected function getArrayItems( $items ) {
        return (array) $items;
    }

    /**
     * @param mixed $items
     *
     * @return static
     */
    public static function make( $items = [] ) {
        return new static( $items );
    }

    /**
     * @return array
     */
    public function all() {
        return $this->items;
    }

    /**
     * @return int
     */
    public function count() {
        return count( $this->items );
    }

    /**
     * @return bool
     */
    public function empty() {
        return empty( $this->items );
    }

    /**
     * Check if items contain selected value by key
     *
     * @param mixed $key
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function contains( $key, $value, $strict = false ) {

        foreach ( $this->all() as $item ) {
            if ( isset( $item->$key ) ) {

                if ( $strict && $item->$key === $value ) {
                    return true;
                } else if ( $item->$key == $value ) {
                    return true;
                }

            }
        }

        return false;

    }

    /**
     * Execute a callback over each item.
     *
     * @param \Closure $callback
     *
     * @return $this
     */
    public function each( \Closure $callback ) {

        foreach ( $this->items as $key => $item ) {
            if ( $callback( $item, $key ) === false ) {
                break;
            }
        }

        return $this;

    }

    /**
     * Get first item from collection
     *
     * @return bool|Entity
     */
    public function first() {
        return isset( $this->items[ 0 ] ) ? $this->items[ 0 ] : false;
    }

    /**
     * @return string
     */
    public function toJson(): string {
        return (string) json_encode( $this->items );
    }

    /**
     * @return array
     */
    public function toArray(): array {
        return $this->items;
    }
}
