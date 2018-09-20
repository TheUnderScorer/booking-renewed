<?php

namespace WPBR\App;

use WPBR\App\Admin\Menu;

/**
 * WP Kraken core class
 *
 * @author Przemysław Żydek
 */
class Core {

    /** @var string */
    const REQUIRED_PHP_VERSION = '7.0';

    /** @var string Slug used for translations */
    public $slug;

    /** @var string Stores path to this plugin directory */
    public $dir;

    /** @var string Stores url to this plugin directory */
    public $url;

    /** @var string Main plugin file */
    public $file;

    /** @var Utility */
    public $utility;

    /** @var Enqueue */
    public $enqueue;

    /** @var View */
    public $view;

    /** @var Menu */
    public $menu;

    /** @var Settings */
    public $settings;

    /** @var Determines whenever core has loaded bool */
    protected $loaded = false;

    /**
     * Core constructor
     *
     * @param string $slug Plugin slug
     * @param string $file Main plugin file
     */
    public function __construct( string $slug, string $file = null ) {

        $this->slug = $slug;
        $this->file = empty( $file ) ? __FILE__ : $file;
        $this->url  = plugin_dir_url( $this->file );
        $this->dir  = plugin_dir_path( $this->file );

        $this->enqueue  = new Enqueue( $this->slug, $this->getUrl( 'assets' ) );
        $this->view     = new View( $this->getPath( 'views' ) );
        $this->menu     = new Menu( $this->slug );
        $this->settings = new Settings( $this->slug );

        $this->registerActivationHooks();

        $this->loaded = true;

        do_action( 'wpbr/core/loaded', $this );

        return $this;

    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getPath( string $path = '' ): string {
        return "{$this->dir}/{$path}";
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getUrl( string $path ): string {
        return "{$this->url}/$path";
    }

    /**
     * @return bool
     */
    public function hasLoaded(): bool {
        return $this->loaded;
    }

    /**
     * @return void
     */
    public function checkPHPVersion() {

        if ( ! version_compare( PHP_VERSION, self::REQUIRED_PHP_VERSION, '>' ) ) {
            wp_die(
                sprintf(
                    __( 'This plugin requires PHP with version at least <strong>%s</strong>. Provided version is <strong>%s</strong>', 'wpbr' ),
                    self::REQUIRED_PHP_VERSION,
                    PHP_VERSION
                )
            );
        }

    }

    /**
     * @return Core
     */
    private function registerActivationHooks(): self {
        register_activation_hook( $this->file, [ $this, 'checkPHPVersion' ] );

        return $this;
    }


}
