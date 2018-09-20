<?php

namespace WPBR\App\Http;

/**
 * @author Przemysław Żydek
 */
class Request extends \Symfony\Component\HttpFoundation\Request {

    /**
     * Helper function for getting post param
     *
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function post( $key, $default = null ) {
        return $this->request->get( $key, $default );
    }

    /**
     * Get file from request
     *
     * @param string $key
     * @param bool   $default
     *
     * @return bool|array
     */
    public function file( $key, $default = false ) {
        return $this->files->get( $key, $default );
    }

    /**
     * Helper function for getting query param
     *
     * @param mixed $key
     * @param null  $default
     *
     * @return mixed|null
     */
    public function query( $key, $default = null ) {
        return $this->query->get( $key, $default );
    }

    /**
     * @param mixed $key
     * @param null  $default
     *
     * @return null
     */
    public function cookie( $key, $default = null ) {
        return $this->cookies->get( $key, $default );
    }

    /**
     * @return bool
     */
    public function isAjax(): bool {

        return wp_doing_ajax() ||
               isset( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) &&
               strtolower( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) === 'xmlhttprequest';

    }

}

