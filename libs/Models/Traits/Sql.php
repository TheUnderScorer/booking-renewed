<?php


namespace WPBR\App\Models\Traits;

/**
 * Trait for model based purely on sql queries
 *
 * @author Przemysław Żydek
 *
 * @property array  $attributes
 * @property string $table
 *
 */
trait Sql {

    /**
     * Add value that will be saved in database
     *
     * @param mixed $key DB column
     * @param       $value
     *
     * @return Sql
     */
    public function value( $key, $value ) {
        $this->attributes[ 'values' ][ $key ] = $value;

        return $this;
    }

    /**
     * @return self
     */
    public function allColumns() {
        $this->attributes[ 'columns' ] = '*';

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return self
     */
    public function columns( $columns ) {
        $this->attributes[ 'columns' ] = $columns;

        return $this;
    }

    /**
     * @param string $column
     *
     * @return self
     */
    public function addColumn( $column ) {
        $this->attributes[ 'columns' ][] = $column;

        return $this;
    }

    /**
     * @param $column
     * @param $value
     *
     * @return self
     */
    public function where( $column, $value ) {
        $this->attributes[ 'where' ][ $column ] = $value;

        return $this;
    }

    /**
     * @return string
     */
    protected function getTable() {
        global $wpdb;

        return $wpdb->prefix . $this->table;
    }

    /**
     * Builds mysql query
     *
     * @return string
     */
    protected function buildResultsQuery() {

        //DB columns

        $columns = implode( ',', $this->attributes[ 'columns' ] );
        $query   = "SELECT $columns from {$this->getTable()}";

        //"Where" section
        if ( ! empty( $this->attributes[ 'where' ] ) ) {

            $where = $this->attributes[ 'where' ];

            $last = last( $where );

            $count = 0;

            foreach ( $where as $column => $value ) {

                $query .= "WHERE $column = $value";

                if ( $count > 0 && $value !== $last ) {
                    $query .= 'AND';
                }

            }

        }

        return $query;

    }
}
