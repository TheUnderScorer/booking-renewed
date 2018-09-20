<?php

namespace WPBR\App;

use WPBR\App\Utility\Strings;

/**
 * View wrapper class
 *
 * @author Przemysław Żydek
 */
class View {

    /** @var string Path to views directory */
    protected $path;

    /**
     * View constructor.
     *
     * @param string|array $path Paths to views directory
     */
    public function __construct( string $path ) {

        $this->path = $path;

    }

    /**
     * Render view
     *
     * @param string $file
     *
     * @return string
     */
    public function render( string $file ): string {

        if ( ! Strings::contains( $file, '.php' ) ) {
            $file = "$file.php";
        }

        $file = "{$this->path}/{$file}";

        ob_start();

        require_once $file;

        return ob_get_clean();

    }

    /**
     * @param string $id
     *
     * @return string
     */
    public function renderAppContainer( string $id ): string {

        $container = $this->render( 'app-container' );
        $container = str_replace( 'app_container', $id, $container );

        return $container;

    }

}
