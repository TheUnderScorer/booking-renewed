<?php


namespace WPBR\App\Utility;

/**
 * Helper class for removing tags
 *
 * @author Przemysław Żydek
 */
class HookRemover {

    /** @var array Contains actions to remove */
    protected $actions = [];

    /** @var array Contains filters to remove */
    protected $filters = [];

    /**
     * HookRemover constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'remove' ] );
    }

    /**
     * Add new tag to remove
     *
     * @param string $tag
     * @param string $function
     * @param int    $priority
     * @param mixed  $replacement
     *
     * @return self
     */
    public function removeAction( string $tag, string $function, int $priority = 10, $replacement = false ): self {

        $this->actions[] = [
            'tag'        => $tag,
            'function'   => $function,
            'priority'   => $priority,
            'replacment' => $replacement,
        ];

        return $this;

    }

    /**
     * Add new tag to remove
     *
     * @param string $tag
     * @param string $function
     * @param int    $priority
     * @param mixed  $replacement
     *
     * @return self
     */
    public function removeFilter( string $tag, string $function, int $priority = 10, $replacement = false ): self {

        $this->filters[] = [
            'tag'        => $tag,
            'function'   => $function,
            'priority'   => $priority,
            'replacment' => $replacement,
        ];

        return $this;

    }

    /**
     * Launches on WP Init, removes desired tags
     *
     * @return void
     */
    public function remove() {

        foreach ( $this->actions as $action ) {
            extract( $action );

            /**
             * @var string $tag
             * @var string $function
             * @var int    $priority
             * @var mixed  $replacment
             */

            remove_action( $tag, $function, $priority );

            if ( $replacment ) {
                add_action( $tag, $replacment, $priority );
            }
        }

        foreach ( $this->filters as $filter ) {
            extract( $filter );

            /**
             * @var string $tag
             * @var string $function
             * @var int    $priority
             * @var mixed  $replacment
             */

            remove_filter( $tag, $function, $priority );

            if ( $replacment ) {
                add_filter( $tag, $replacment, $priority );
            }
        }

    }

}
