<?php


namespace WPBR\App\Models\Traits;

/**
 * Helper trait for adding new fields
 *
 * @method bool hasEntity()
 * @property array  $attributes
 * @property string $getMetaFunction
 * @property string $updateMetaFunction
 * @property string $addMetaFunction
 * @property string $deleteMetaFunction
 *
 * @author Przemysław Żydek
 */
trait HandleMetaFields {

    /**
     * @param array $items
     *
     * @return static
     */
    public function updateMetas( $items ) {

        if ( ! $this->hasEntity() ) {
            return $this;
        }

        foreach ( $items as $key => $value ) {
            $this->updateMeta( $key, $value );
        }

        return $this;

    }

    /**
     * @param mixed $key
     * @param mixed $value
     *
     * @return $this
     */
    public function addMeta( $key, $value ) {

        if ( $this->hasEntity() ) {
            call_user_func( $this->addMetaFunction, $this->ID, $key, $value );
        } else {
            $this->attributes[ 'meta_input' ][ $key ] = $value;
        }

        return $this;

    }

    /**
     * @param array $metaInput
     *
     * @return $this
     */
    public function addMetas( $metaInput = [] ) {

        foreach ( $metaInput as $key => $value ) {
            $this->addMeta( $key, $value );
        }

        return $this;

    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function updateMeta( $key, $value ) {

        if ( $this->hasEntity() ) {
            call_user_func( $this->updateMetaFunction, $this->ID, $key, $value );
        } else {
            $this->addMeta( $key, $value );
        }

        return $this;

    }

    /**
     * @param mixed $key
     * @param bool  $single
     *
     * @return mixed
     */
    public function meta( $key, $single = true ) {
        return $this->hasEntity() ? call_user_func( $this->getMetaFunction, $this->ID, $key, $single ) : false;
    }

    /**
     * @param mixed  $key
     * @param mixed  $value
     * @param string $compare
     *
     * @return $this
     */
    public function hasMetaValue( $key, $value, $compare = '=' ) {

        $this->attributes[ 'meta_query' ][] = self::parseMeta( $key, $value, $compare );

        return $this;

    }

    /**
     * @param mixed $key
     * @param int   $value
     *
     * @return HandleMetaFields
     */
    public function metaBiggerThan( $key, $value ) {
        return $this->hasMetaValue( $key, $value, '>' );
    }

    /**
     * @param mixed $key
     * @param int   $value
     *
     * @return HandleMetaFields
     */
    public function metaLesserThan( $key, $value ) {
        return $this->hasMetaValue( $key, $value, '<' );
    }


    /**
     * @param mixed $key
     *
     * @return $this|bool
     */
    public function hasMeta( $key ) {

        if ( $this->hasEntity() ) {
            return ! empty( $this->meta( $key ) );
        }

        $this->attributes[ 'meta_query' ][] = [
            'key'     => $key,
            'compare' => 'EXISTS',
        ];

        return $this;

    }

    /**
     * @param mixed $key
     *
     * @return $this|bool
     */
    public function hasNotMeta( $key ) {

        if ( $this->hasEntity() ) {
            return empty( $this->meta( $key ) );
        }

        $this->attributes[ 'meta_query' ][] = [
            'key'     => $key,
            'compare' => 'NOT EXISTS',
        ];


        return $this;

    }

    /**
     * @param array  $metas
     * @param string $relation
     *
     * @return $this
     */
    public function hasMetaRelation( $metas, $relation = 'OR' ) {

        self::parseMetaInput( $metas );

        $this->attributes[ 'meta_query' ][] = [
            $relation,
            $metas,
        ];

        return $this;

    }

    /**
     * Builds advanced meta query
     *
     * @param array $args
     *
     * @return $this
     */
    public function advancedMeta( array $args ) {

        $this->attributes[ 'meta_query' ][] = $args;

        return $this;

    }

    /**
     * @param mixed  $key
     * @param mixed  $value
     * @param string $compare
     *
     * @return array
     */
    protected static function parseMeta( $key, $value, $compare = '=' ) {

        $type = 'CHAR';

        if ( is_int( $value ) || is_float( $value ) ) {
            $type = 'NUMERIC';
        } //Check if value is in datetime format
        else if ( \DateTime::createFromFormat( 'Y-m-d G:i:s', $value ) ) {
            $type = 'DATETIME';
        } //Check if value is simple date
        else if ( \DateTime::createFromFormat( 'Y-m-d', $value ) ) {
            $type = 'DATE';
        }

        return [
            'key'     => $key,
            'value'   => $value,
            'compare' => $compare,
            'type'    => $type,
        ];

    }

    /**
     * Parse metas input into WP_Meta_Query format
     *
     * @param $metas
     *
     * @return void
     */
    protected static function parseMetaInput( &$metas ) {

        foreach ( $metas as $key => $meta ) {

            $compare = '=';

            if ( is_array( $meta ) ) {
                list( $meta, $compare ) = $meta;
            }

            $metas[ $key ] = self::parseMeta( $key, $meta, $compare );
        }

    }

    /**
     * @param $key
     *
     * @return self
     */
    public function deleteMeta( $key ) {
        call_user_func( $this->deleteMetaFunction, $this->ID, $key );

        return $this;
    }

}
