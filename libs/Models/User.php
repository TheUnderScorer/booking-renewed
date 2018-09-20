<?php

namespace WPBR\App\Models;

use WPBR\App\Models\Traits;
use WPBR\App\Utility;

/**
 * Model class for WordPress users
 *
 * @author Przemysław Żydek
 */
class User extends Entity {

    use Traits\HandleMetaFields;

    /** @var array */
    const TYPES = [
        'influencer',
        'company',
    ];

    /** @var \WP_User */
    public $entity;

    public $attributes = [];

    protected $entityClass = \WP_User::class;

    protected $entityInstanceMethod = 'get_data_by';

    protected $getMetaFunction = 'get_user_meta';

    protected $updateMetaFunction = 'update_user_meta';

    protected $addMetaFunction = 'add_user_meta';

    protected $deleteMetaFunction = 'delete_user_meta';

    /**
     * User constructor.
     *
     * @param mixed $attributes
     */
    public function __construct( $attributes = null ) {

        if ( is_numeric( $attributes ) ) {
            $this->entity = new \WP_User( $attributes );
        } else {
            parent::__construct( $attributes );
        }

    }

    /**
     * @param string $firstName
     *
     * @return self
     */
    public function firstName( $firstName ) {

        $this->attributes[ 'first_name' ] = $firstName;

        return $this;

    }

    /**
     * @param string $lastName
     *
     * @return self
     */
    public function lastName( $lastName ) {

        $this->attributes[ 'last_name' ] = $lastName;

        return $this;

    }

    /**
     * @param string $login
     *
     * @return self
     */
    public function login( $login ) {

        $this->attributes[ 'login' ] = $login;

        return $this;

    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function email( $email ) {

        $this->attributes[ 'user_email' ] = $email;

        return $this;

    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function password( $password ) {

        if ( $this->hasEntity() ) {

            wp_password_change_notification( $this->entity );
            wp_set_password( $password, $this->ID );
            wp_set_auth_cookie( $this->ID, true );

        } else {

            $this->attributes[ 'password' ] = $password;

        }

        return $this;

    }

    /**
     * @param string|array $cap
     *
     * @return self
     */
    public function cap( $cap ) {

        if ( is_array( $cap ) ) {
            $this->attributes[ 'cap' ] = array_merge( $this->attributes[ 'cap' ], $cap );
        } else {

            $this->attributes[ 'cap' ][] = $cap;

        }

        return $this;

    }

    /**
     * @param string|array $role
     *
     * @return self
     */
    public function role( $role ) {

        if ( is_array( $role ) ) {
            $this->attributes[ 'role' ] = array_merge( $this->attributes[ 'role' ], $role );
        } else {

            $this->attributes[ 'role' ][] = $role;

        }

        return $this;

    }

    /**
     * @return bool|static
     */
    public function create() {

        $attributes = $this->attributes;

        if ( empty( $attributes[ 'password' ] ) ) {
            $attributes[ 'password' ] = wp_generate_password();
        }

        $user = wp_create_user( $attributes[ 'login' ], $attributes[ 'password' ], $attributes[ 'email' ] );

        if ( is_wp_error( $user ) ) {
            Utility\Logger::log( $user->get_error_messages(), 'USER_CREATE_ERROR' );

            return false;
        }

        $user = new static( $user );

        foreach ( $this->attributes[ 'role' ] as $role ) {
            $user->entity->add_role( $role );
        }

        foreach ( $this->attributes[ 'cap' ] as $cap ) {
            $user->entity->add_cap( $cap );
        }

        $user->addMetas( $this->attributes[ 'meta_input' ] );

        return $user;

    }

    /**
     * @param int $amount
     *
     * @return self
     */
    public function perPage( $amount ) {
        $this->attributes[ 'per_page' ] = $amount;

        return $this;
    }

    /**
     * @param int $page
     *
     * @return self
     */
    public function page( $page ) {
        $this->attributes[ 'page' ] = $page;

        return $this;
    }

    /**
     * Execute query
     *
     * @return Collection
     */
    public function get() {

        $result = [];

        $query   = new \WP_User_Query( $this->attributes );
        $results = $query->get_results();

        //If we have pagination params, build main query with them
        if ( ! empty( $this->attributes[ 'per_page' ] ) ) {

            $totalUsers = count( $results );

            $page    = empty( $this->attributes[ 'page' ] ) ? 1 : $this->attributes[ 'page' ];
            $perPage = $this->attributes[ 'per_page' ];

            $offset     = $perPage * ( $page - 1 );
            $totalPages = ceil( $totalUsers / $perPage );

            $this->attributes[ 'number' ]      = $perPage;
            $this->attributes[ 'total_pages' ] = $totalPages;
            $this->attributes[ 'offset' ]      = $offset;

        }

        $results = ( new \WP_User_Query( $this->attributes ) )->get_results();

        foreach ( $results as $post ) {
            $result[] = new static( $post );
        }

        return Collection::make( $result );

    }

    /**
     * @return self
     */
    public static function current() {
        return self::find( get_current_user_id() );
    }

    /**
     * @return self|\WP_Error
     */
    public function update() {

        $this->attributes[ 'ID' ] = $this->ID;

        $update = wp_update_user( $this->attributes );

        if ( is_wp_error( $update ) ) {
            Utility\Logger::log( $update->get_error_messages(), 'UPDATE_USER_ERROR' );

            return $update;
        }

        //Return updated model
        return new static( $this->ID );

    }

    /**
     * @return bool
     */
    public function delete() {
        return (bool) wp_delete_user( $this->ID );
    }

}
