<?php

namespace WPBR\App\Utility;

use WPBR\App\Utility\Logger;

/**
 * Helper function for creating new post types
 *
 * @author Przemysław Żydek
 */
class PostType {

    /** @var array Post type args */
    protected $args = [];

    /** @var string Post type slug */
    protected $slug = [];

    /**
     * PostType constructor.
     *
     * @param string $slug
     * @param array  $args
     */
    public function __construct( string $slug, array $args = [] ) {
        $this->slug = $slug;
        $this->args = $args;

        add_action( 'init', [ $this, 'register' ] );
    }

    /**
     * Register post type. Hooked into init
     *
     * @return void
     */
    public function register() {
        register_post_type( $this->slug, $this->args );
    }

    /**
     * Add new column to admin view
     *
     * @param string $columnSlug
     * @param string $columnName
     * @param \Closure Callback for column content
     *
     * @return self
     */
    public function addAdminColumn( string $columnSlug, string $columnName, \Closure $callback ): self {

        add_filter( "manage_{$this->slug}_posts_columns", function ( $columns = [] ) use ( $columnSlug, $columnName ) {
            $columns[ $columnSlug ] = $columnName;

            return $columns;
        } );

        add_action( "manage_{$this->slug}_posts_custom_column", function ( $column, $postId ) use ( $callback, $columnSlug ) {
            if ( $column === $columnSlug ) {
                $callback( $postId );
            }
        }, 10, 2 );

        return $this;
    }

    /**
     * @param string   $columnSlug
     * @param string   $columnName
     * @param \Closure $contentCallback
     * @param array    $args Args for filtering (meta_key and orderby)
     *
     * @return self
     */
    public function addSortableColumn( string $columnSlug, string $columnName, \Closure $contentCallback, array $args = [] ): self {

        $args = wp_parse_args( $args, [
            'meta_key' => $columnSlug,
            'orderby'  => 'meta_value_num',
        ] );

        //Add column to view
        add_filter( "manage_{$this->slug}_posts_columns", function ( $columns = [] ) use ( $columnSlug, $columnName ) {
            $columns[ $columnSlug ] = $columnName;

            return $columns;
        } );

        //Make column sortable
        add_filter( "manage_edit-{$this->slug}_sortable_columns", function ( $columns = [] ) use ( $columnSlug, $columnName ) {
            $columns[ $columnSlug ] = $columnSlug;

            return $columns;
        } );

        //Callback for column content
        add_action( "manage_{$this->slug}_posts_custom_column", function ( $column, $postId ) use ( $contentCallback, $columnSlug ) {
            if ( $column === $columnSlug ) {
                $contentCallback( $postId );
            }
        }, 10, 2 );

        //Ordering
        add_action( 'pre_get_posts', function ( \WP_Query $query ) use ( $args ) {

            if ( ! is_admin() ) {
                return;
            }

            $queryOrder = $query->get( 'orderby' );

            if ( $queryOrder === $args[ 'meta_key' ] ) {

                foreach ( $args as $key => $item ) {
                    $query->set( $key, $item );
                }

                $test = null;

            }

        } );

        return $this;

    }

    /**
     * Remove admin column from table
     *
     * @param string $columnSlug
     *
     * @return self
     */
    public function removeAdminColumn( string $columnSlug ): self {

        add_filter( "manage_{$this->slug}_posts_columns", function ( $columns = [] ) use ( $columnSlug ) {
            unset( $columns[ $columnSlug ] );

            return $columns;
        } );

        return $this;

    }

    /**
     * @param  string  $action
     * @param  string  $label
     * @param \Closure $callback Callback for this bulk action called with params $redirectTo and $postIDS
     *
     * @return self
     */
    public function addBulkAction( string $action, string $label, \Closure $callback ): self {

        add_filter( "bulk_actions-edit-{$this->slug}", function ( $actions ) use ( $action, $label ) {
            $actions[ $action ] = $label;

            return $actions;
        } );

        add_filter( "handle_bulk_actions-edit-{$this->slug}", function ( $redirectTo, $calledAction, $postIDS ) use ( $action, $callback ) {

            if ( $calledAction !== $action ) {
                return $redirectTo;
            }

            return $callback( $redirectTo, $postIDS );

        }, 10, 3 );

        return $this;

    }

    /**
     * Add submenu to post page
     *
     * @param string $pageTitle
     * @param string $menuTitle
     * @param string $slug
     *
     * @return self
     */
    public function addSubmenu( string $pageTitle, string $menuTitle, string $slug ): self {

        add_action( 'admin_menu', function () use ( $pageTitle, $menuTitle, $slug ) {
            add_submenu_page( "edit.php?post_type={$this->slug}", $pageTitle, $menuTitle, 'manage_options', $slug );
        } );

        return $this;

    }

    /**
     * @param string $taxonomy
     * @param array  $args
     *
     * @return self
     */
    public function addTaxonomy( string $taxonomy, array $args = [] ): self {

        $taxonomy = register_taxonomy( $taxonomy, $this->slug, $args );

        if ( is_wp_error( $taxonomy ) ) {
            Logger::log( $taxonomy->get_error_message(), __METHOD__ );
        }

        return $this;

    }

}
