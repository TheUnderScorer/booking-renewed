<?php

namespace WPBR\App\Http;

use function WPBR\App\Core;
use WPBR\App\Models\Post;
use WPBR\App\Models\User;
use function WPBR\App\request;
use WPBR\App\Utility\Strings;

/**
 * Used to standarize request response.
 *
 * @author Przemysław Żydek
 */
class Response {

    /** @var int */
    const SUCCESS = 0;

    /** @var int */
    const NOTICE = 1;

    /** @var int */
    const MESSAGE = 2;

    /** @var int */
    const ERROR = 3;

    /** @var array */
    const TYPES = [ 'success', 'notice', 'message', 'error' ];

    /** @var bool True if there was error with submission (empty required fields etc.) */
    protected $error = false;

    /** @var array Array of arrays with messages */
    protected $messages = [];

    /** @var array Contains additional data, useful for debuging. */
    protected $additional = [];

    /** @var string Additional url that user will be redirected after ajax call */
    protected $redirectUrl = '';

    /** @var mixed Result of request */
    protected $result = false;

    /**
     * Add error to response
     *
     * @param string $message Error message
     * @param bool   $send Whenever send response after adding error.
     *
     * @return self
     */
    public function addError( string $message, bool $send = false ): self {

        $this->error      = true;
        $this->messages[] = [
            'message' => $message,
            'type'    => self::ERROR,
        ];

        if ( $send ) {
            $this->sendJson();
        }

        return $this;

    }

    /**
     * @param mixed $result
     *
     * @return self
     */
    public function setResult( $result ): self {

        $this->result = $result;

        return $this;

    }

    /**
     * Add message to response
     *
     * @param string $message Error message
     * @param int    $type Message type (error|notice|message|success)
     *
     * @return self
     */
    public function addMessage( string $message, int $type = self::MESSAGE ): self {

        $this->messages[] = [
            'message' => $message,
            'type'    => $type,
        ];

        return $this;

    }

    /**
     * Add additional data to response
     *
     * @param mixed $key
     * @param mixed $data
     *
     * @return self
     */
    public function addAdditionalData( $key, $data ): self {

        $this->additional[ $key ] = $data;

        return $this;

    }

    /**
     * Add messages from WP_Error to our response
     *
     * @param \WP_Error $error
     *
     * @return self
     */
    public function handleWpError( \WP_Error $error ): self {

        foreach ( $error->get_error_messages() as $errorMessage ) {
            $this->addError( $errorMessage );
        }

        return $this;

    }

    /**
     * Check if provided user is campaign author
     *
     * @param User $user
     * @param Post $post
     *
     * @return void
     */
    public function validateAuthor( User $user, Post $post ) {

        if ( $user->ID != $post->post_author ) {
            $this->addError( esc_html__( 'You can\'t do that', 'wpbr' ), true );
        }

    }

    /**
     * Checks if we have errors in our response
     *
     * @return bool
     */
    public function hasErrors(): bool {
        return $this->error;
    }

    /**
     * Set redirect url
     *
     * @param string $url
     *
     * @return self
     */
    public function setRedirectUrl( string $url ): self {

        $this->redirectUrl = $url;

        return $this;

    }

    /**
     * @param string $action
     * @param string $queryParam
     *
     * @return void
     */
    public function checkNonce( string $action, string $queryParam ) {

        if ( ! check_ajax_referer( $action, $queryParam, false ) ) {
            $this->addError( 'Security error', true );
        }

    }

    /**
     *
     * @return void
     */
    public function checkUserLoggedIn() {

        if ( ! is_user_logged_in() ) {

            if ( request()->isAjax() ) {
                $this->addError( esc_html__( 'You must be logged in to do that', 'wpbr' ), true );
            } else {
                $this->render404();
            }

        }
    }

    /**
     * @param int $status
     *
     * @return void
     */
    public function redirect( int $status = 302 ) {
        wp_redirect( $this->redirectUrl, $status );

        die();
    }

    /**
     * Send json with our response
     *
     * @return void
     */
    public function sendJson() {

        //Prase integers to actualy message type
        array_walk( $this->messages, function ( array &$message ) {
            $message[ 'type' ] = self::TYPES[ $message[ 'type' ] ];
        } );

        $result = [
            'messages'     => $this->messages,
            'error'        => $this->error,
            'additional'   => $this->additional,
            'redirect_url' => $this->redirectUrl,
            'result'       => $this->result,
        ];

        wp_send_json( $result );

    }


}
