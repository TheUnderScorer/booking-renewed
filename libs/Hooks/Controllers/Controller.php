<?php

namespace WPBR\App\Hooks\Controllers;

use function WPBR\App\Core;
use WPBR\App\Enqueue;
use WPBR\App\Hooks\Middleware\Middleware;

/**
 * @author Przemysław Żydek
 */
abstract class Controller {

    /** @var array Array with middleware classes to use */
    protected $middleware = [];

    /**
     * @var array Array of scripts to load.
     * @see Enqueue::enqueueScript() for args
     */
    protected $scripts = [];

    /**
     * Controller constructor.
     */
    public function __construct() {

        $this->setup();
        $this->loadMiddleware();

    }

    /**
     * Performs controller setup
     *
     * @return void
     */
    abstract protected function setup();

    /**
     * Perform load of middleware modules
     *
     * @return self
     */
    protected function loadMiddleware(): self {

        foreach ( $this->middleware as $key => $middleware ) {
            $this->middleware[ $key ] = new $middleware();
        }

        return $this;

    }

    /**
     * Loads controller scripts
     *
     * @return Controller
     */
    protected function loadScripts(): self {

        $defaultArgs = [
            'slug'           => '',
            'fileName'       => '',
            'deps'           => [ 'jquery' ],
            'ver'            => '1.0',
            'inFooter'       => true,
            'vars'           => [],
            'admin'          => false,
            'instantEnqueue' => true,
        ];

        $enqueue = Core()->enqueue;

        foreach ( $this->scripts as $script ) {

            $args = wp_parse_args( $script, $defaultArgs );
            $enqueue->enqueueScript( $args );

        }

        return $this;

    }

    /**
     * Calls controller middleware
     *
     * @param array|string $middleware If empty all middlewares will be used
     * @param array        $params Optional parameters for middleware
     *
     * @return self
     */
    protected function middleware( $middleware = null, ...$params ): self {

        //Call all middlewares
        if ( empty( $middleware ) ) {
            $middleware = array_keys( $this->middleware );
        }

        foreach ( (array) $middleware as $key ) {
            if ( isset( $this->middleware[ $key ] ) ) {
                $this->callMiddleware( $this->middleware[ $key ], $params );
            }
        }

        return $this;

    }

    /**
     * @param Middleware $middleware
     * @param array      $params
     *
     * @return void
     */
    private function callMiddleware( Middleware $middleware, array $params = [] ) {
        $middleware->handle( $params );
    }

}
