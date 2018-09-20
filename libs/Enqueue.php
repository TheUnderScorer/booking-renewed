<?php

namespace WPBR\App;

use WPBR\App\Utility\Strings;

/**
 * Handles styles and scripts enqueue.
 *
 * @author Przemysław Żydek
 */
class Enqueue {

    /** @var string Stores url to assets folder */
    public $assetsUrl;

    /**@var string Stores url to css folder */
    public $cssUrl;

    /** @var string Stores url to js folder */
    public $jsUrl;

    /** @var string Stores url to vendor folder */
    public $vendorUrl;

    /** @var array */
    protected $scripts = [];

    /** @var array */
    protected $styles = [];

    /** @var string */
    protected $slug;

    /**
     * Enqueue constructor.
     *
     * @param string $slug
     * @param string $assetsUrl URL to assets directory
     */
    public function __construct( string $slug, string $assetsUrl ) {

        $this->slug = $slug;

        $this->setupPaths( $assetsUrl );
        $this->setupHooks();

    }

    /**
     * Setup project enqueue paths
     *
     * @param string $assetsUrl URL to assets directory
     *
     * @return void
     */
    protected function setupPaths( $assetsUrl ) {

        $this->assetsUrl = $assetsUrl;
        $this->cssUrl    = $this->getAssetsPath( 'css' );
        $this->jsUrl     = $this->getAssetsPath( 'js' );
        $this->vendorUrl = $this->getAssetsPath( 'vendor' );

    }

    /**
     * Helper function for getting assets path
     *
     * @param string $path
     *
     * @return string
     */
    public function getAssetsPath( $path ) {

        return "{$this->assetsUrl}/$path";

    }

    /**
     * Setup class hooks
     *
     * @return void
     */
    protected function setupHooks() {

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueueStyles' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ] );
        add_action( 'wp_print_scripts', [ $this, 'dequeueScripts' ] );

        //Admin enqueue
        add_action( 'admin_enqueue_scripts', [ $this, 'adminEnqueueScripts' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'adminEnqueueStyles' ] );
        add_action( 'admin_print_scripts', [ $this, 'adminDequeueScripts' ] );

    }

    /**
     * Enqueue styles
     *
     * @return void
     */
    public function enqueueStyles() {

        foreach ( $this->styles as $style ) {

            extract( $style );

            /**
             * @var string     $slug
             * @var string     $fileName
             * @var array      $deps
             * @var string|int $ver
             */

            $url = $this->cssUrl;


            if ( ! Strings::contains( $fileName, 'http' ) ) {
                $fileName = str_replace( '.css', '', $fileName );
                $fileName = "$url/$fileName.css";
            }

            wp_enqueue_style( $slug, $fileName, $deps, $ver );

        }

    }

    /**
     * Enqueue admin styles
     *
     * @return void
     */
    public function adminEnqueueStyles() {


    }

    /**
     * @param array  $items
     * @param string $type
     *
     * @return self
     */
    protected function enqueue( array $items, string $type = 'scripts' ): self {

        foreach ( $items as $item ) {

            extract( $item );

            /**
             * @var string $slug
             * @var string $fileName
             * @var array  $deps
             * @var string $ver
             * @var bool   $inFooter
             * @var array  $vars
             */

            $url = $this->jsUrl;

            if ( $type === 'scripts' ) {

                if ( ! Strings::contains( $fileName, 'http' ) ) {
                    $fileName = str_replace( '.js', '', $fileName );
                    $fileName = "$url/$fileName.js";
                }

                wp_enqueue_script( $slug, $fileName, $deps, $ver, $inFooter );

                if ( ! empty( $vars ) ) {
                    wp_localize_script(
                        $slug,
                        //Fix issue with invalid variable when "-" is present
                        sprintf( '%s_vars', str_replace( '-', '_', $slug ) ),
                        $vars );
                }


            }

        }

        return $this;

    }

    /**
     * Enqueue scripts
     *
     * @return void
     */
    public function enqueueScripts() {

        $scripts = array_filter( $this->scripts, function ( $item ) {
            return ! $item[ 'admin' ];
        } );

        $this->enqueue( $scripts );

    }

    /**
     * Enqueue admin scripts
     *
     * @return void
     */
    public function adminEnqueueScripts() {

        $scripts = array_filter( $this->scripts, function ( $item ) {
            return $item[ 'admin' ];
        } );

        $this->enqueue( $scripts );

    }

    /**
     * Helper function for enqueueing styles
     *
     * @param array args
     *
     * @return self
     */
    public function enqueueStyle( array $args = [] ): self {

        $slug = $this->slug;

        $args = wp_parse_args( $args, [
            'slug'     => $slug,
            'fileName' => $slug,
            'deps'     => [],
            'ver'      => '1.0',
            'admin'    => false,
        ] );

        $this->styles[] = $args;

        return $this;

    }

    /**
     * Helper function for enqueuing scripts
     *
     * @param array $args
     *
     * @return self
     */
    public function enqueueScript( array $args = [] ): self {

        $slug = $this->slug;

        $args = wp_parse_args( $args, [
            'slug'           => $slug,
            'fileName'       => $slug,
            'deps'           => [ 'jquery' ],
            'ver'            => '1.0',
            'inFooter'       => false,
            'vars'           => [],
            'admin'          => false,
            'instantEnqueue' => false,
        ] );

        if ( $args[ 'instantEnqueue' ] ) {

            $this->enqueue([$args]);

        } else {

            $this->scripts[] = $args;

        }

        return $this;

    }

    /**
     * Dequeues scripts.
     *
     * @return void
     */
    public function dequeueScripts() {
    }

    /**
     * Dequeues admin scripts.
     *
     * @return void
     */
    public function adminDequeueScripts() {

    }

}

