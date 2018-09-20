<?php

namespace WPBR\App\Models;

/**
 * Base entity class for models
 *
 * @author Przemysław Żydek
 */
abstract class Entity {

    /** @var \stdClass Base class of entity */
    protected $entityClass = \stdClass::class;

    /** @var string Method of entity class that creates it */
    protected $entityInstanceMethod;

    /** @var \stdClass Stores entity instance */
    protected $entity;

    /** @var array Args for query or new entity */
    protected $attributes = [];

    /**
     * Post constructor.
     *
     * @param mixed $attributes
     */
    public function __construct( $attributes = null ) {

        if ( ! empty( $attributes ) ) {

            if ( $attributes instanceof $this->entityClass ) {

                $this->entity = $attributes;

            } else if ( is_numeric( $attributes ) && ! empty( $this->entityInstanceMethod ) ) {

                $this->entity = call_user_func( [ $this->entityClass, $this->entityInstanceMethod ], $attributes );

            } else if ( is_array( $attributes ) ) {

                $this->attributes = $attributes;

            }

        }

    }

    /**
     * Helper function for initing entity without any attributes
     *
     * @param mixed $attributes
     *
     * @return static
     */
    public static function init( $attributes = [] ) {
        return new static( $attributes );
    }

    /**
     * @param array $attributes
     *
     * @return self
     */
    public function addAttributes( $attributes = [] ) {

        $this->attributes = array_merge( $this->attributes, $attributes );

        return $this;

    }

    /**
     * @param mixed $key
     *
     * @return self
     */
    public function removeAttribute( $key ) {

        unset( $this->attributes[ $key ] );

        return $this;

    }

    /**
     * Magic __get method overlap, grab WP_Post property if possible
     *
     * @param $key
     *
     * @return mixed
     */
    public function __get( $key ) {

        if ( property_exists( $this, $key ) ) {
            return $this->$key;
        } else {
            return $this->entity->$key;
        }

    }

    /**
     * Get post by its id
     *
     * @param int $entityID
     *
     * @return static
     */
    public static function find( $entityID ) {
        return new static( $entityID );
    }

    /**
     * @return bool|static
     */
    abstract public function create();


    /**
     * @return bool
     */
    protected function hasEntity() {
        return $this->entity instanceof $this->entityClass;
    }

    /**
     * Execute query
     *
     * @return Collection
     */
    abstract public function get();

    /**
     * @return mixed
     */
    abstract public function update();

    /**
     * @return bool
     */
    abstract public function delete();

}
