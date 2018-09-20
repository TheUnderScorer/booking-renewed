<?php


namespace WPBR\App\Models\Traits\Post;

use WPBR\App\Utility;

/**
 * Helper trait for handling taxonomies
 *
 * @method bool hasEntity()
 * @property array $attributes
 *
 * @author Przemysław Żydek
 */
trait HandleTaxonomy {

    /**
     * @return array
     */
    public function taxonomies() {
        return get_object_taxonomies( $this->ID );
    }

    /**
     * @param string|array $taxonomy
     * @param array        $args
     *
     * @return array|\WP_Error
     */
    public function terms( $taxonomy, $args = [] ) {
        return wp_get_object_terms( $this->ID, $taxonomy, $args );
    }

    /**
     * @param array|string $terms
     * @param string       $taxonomy
     *
     * @return $this
     */
    public function setTerms( $terms, $taxonomy ) {

        if ( $this->hasEntity() ) {
            $result = wp_set_object_terms( $this->ID, $terms, $taxonomy );

            if ( is_wp_error( $result ) ) {
                Utility\Logger::log( $result->get_error_messages(), 'SET_TERM_ERROR' );
            }
        } else {

            $this->attributes[ 'tax_input' ][ $taxonomy ] = (array) $terms;

        }

        return $this;

    }

    /**
     * @param mixed  $terms
     * @param string $taxonomy
     *
     * @return $this
     */
    public function addTerms( $terms, $taxonomy ) {

        if ( $this->hasEntity() ) {
            $result = wp_add_object_terms( $this->ID, $terms, $taxonomy );

            if ( is_wp_error( $result ) ) {
                Utility\Logger::log( $result->get_error_messages(), 'ADD_TERM_ERROR' );
            }
        } else {

            $this->attributes[ 'tax_input' ][ $taxonomy ] = array_merge( $this->attributes[ 'tax_input' ][ $taxonomy ], (array) $terms );

        }

        return $this;

    }

    /**
     * Builds advanced tax query
     *
     * @param array $args
     *
     * @return $this
     */
    public function advancedTax( $args ) {

        $this->attributes[ 'tax_query' ][] = $args;

        return $this;

    }

}

