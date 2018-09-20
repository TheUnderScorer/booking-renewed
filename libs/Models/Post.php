<?php

namespace WPBR\App\Models;

use WPBR\App\Models\Traits;
use WPBR\App\Utility;

/**
 * Model class for WordPress posts
 *
 * @author Przemysław Żydek
 */
class Post extends Entity {

    use Traits\Post\HandleTaxonomy,
        Traits\HandleMetaFields;

    protected $entityClass = \WP_Post::class;

    protected $entityInstanceMethod = 'get_instance';

    protected $queryClass = \WP_Query::class;

    protected $queryResultsMethod = 'get_posts';

    protected $getMetaFunction = 'get_post_meta';

    protected $updateMetaFunction = 'update_post_meta';

    protected $addMetaFunction = 'add_post_meta';

    protected $deleteMetaFunction = 'delete_post_meta';

    /**
     * @return string
     */
    public function __toString() {
        return $this->hasEntity() ? $this->post_content : '';
    }

    /**
     * @return bool|static
     */
    public function create() {

        if ( empty( $this->attributes ) ) {
            return false;
        }

        $taxInput = [];

        if ( ! empty( $this->attributes[ 'tax_input' ] ) ) {
            $taxInput = $this->attributes[ 'tax_input' ];
            unset( $this->attributes[ 'tax_input' ] );
        }

        $this->parseCreationAttributes();

        $post = wp_insert_post( $this->attributes );

        /*
         * Manually add new taxonomies.
         * The reason for that is "tax_input" in wp_insert_post must be passed as terms id, and sometimes we want to use slug or name instead :>
         * */
        if ( $post ) {

            $post = new static( $post );

            if ( ! empty( $taxInput ) ) {
                foreach ( $taxInput as $taxonomy => $terms ) {
                    $post->setTerms( $terms, $taxonomy );
                }
            }

        }

        return $post;

    }

    /**
     * Add post status to query
     *
     * @param string|array $status
     *
     * @return self
     */
    public function status( $status ) {

        $this->attributes[ 'post_status' ] = $status;

        return $this;

    }

    /**
     * @param string|array $type
     *
     * @return self
     */
    public function type( $type ) {

        $this->attributes[ 'post_type' ] = $type;

        return $this;

    }

    /**
     *
     * @param string $title
     *
     * @return self
     */
    public function title( $title ) {

        $this->attributes[ 'post_title' ] = $title;

        return $this;

    }

    /**
     * @param string $content
     *
     * @return Post
     */
    public function content( $content ) {

        $this->attributes[ 'post_content' ] = $content;

        return $this;

    }

    /**
     * @param string $fields
     *
     * @return self
     */
    public function fields( $fields ) {

        $this->attributes[ 'fields' ] = $fields;

        return $this;

    }

    /**
     * @param int $amount
     *
     * @return self
     */
    public function perPage( $amount ) {

        $this->attributes[ 'posts_per_page' ] = $amount;

        return $this;

    }

    /**
     * @param int $page
     *
     * @return self
     */
    public function paged( $page ) {

        $this->attributes[ 'paged' ] = $page;

        return $this;

    }

    /**
     * Set order of posts. Must be called before @see Entity::get()
     *
     * @param array|string $by
     * @param string       $how
     * @param string|null  $metaKey Optional, if order should be set by meta key
     *
     * @return self
     */
    public function order( $by, $how = 'ASC', $metaKey = null ) {

        $this->attributes[ 'order' ]   = $how;
        $this->attributes[ 'orderby' ] = $by;

        if ( ! empty( $metaKey ) ) {
            $this->attributes[ 'meta_key' ] = $metaKey;
        }

        return $this;

    }

    /**
     * @param int $authorID
     *
     * @return self
     */
    public function author( $authorID ) {

        $this->attributes[ 'author' ] = $authorID;

        return $this;

    }

    /**
     * @return self
     */
    public function deleteThumbnail() {
        delete_post_thumbnail( $this->ID );

        return $this;
    }

    /**
     * @return string
     */
    public function getThumbnailUrl() {
        return get_the_post_thumbnail_url( $this->ID );
    }

    /**
     * Set post thumbnail
     *
     * @param int $attachmentID
     *
     * @return bool
     */
    public function thumnbail( $attachmentID ) {
        return set_post_thumbnail( $this->ID, $attachmentID );
    }

    /**
     * Execute query
     *
     * @return Collection
     */
    public function get() {

        $result = [];

        $query = new \WP_Query( $this->attributes );

        foreach ( $query->get_posts() as $post ) {
            $result[] = new static( $post );
        }

        return Collection::make( $result );

    }

    /**
     * @return self
     */
    public function update() {

        $this
            ->parseCreationAttributes()
            ->attributes[ 'ID' ] = $this->ID;

        $result = wp_update_post( $this->attributes );

        if ( is_wp_error( $result ) ) {
            Utility::log( $result->get_error_messages(), 'POST_UPDATE_ERROR' );

            return $this;
        }

        return new static( $this->ID );

    }

    /**
     * @return bool
     */
    public function delete() {
        return (bool) wp_delete_post( $this->ID, true );
    }

    /**
     * Rename certain attributes so that they will fit in @see wp_insert_post()
     *
     * @return self
     */
    protected function parseCreationAttributes() {

        if ( ! empty( $this->attributes[ 'author' ] ) ) {
            $this->attributes[ 'post_author' ] = $this->attributes[ 'author' ];
        }

        return $this;

    }

}
