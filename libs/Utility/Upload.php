<?php

namespace WPBR\App\Utility;

/**
 * Middleware for handling upload
 *
 * @author Przemysław Żydek
 */
class Upload {

    /**
     * Create attachment from uploaded file
     *
     * @param array $file
     *
     * @return bool|int
     */
    public static function createAttachment( array $file ) {

        $upload = wp_handle_upload( $file, [ 'test_form' => false ] );

        if ( ! isset( $upload[ 'error' ] ) ) {

            $attachment = [
                'post_mime_type' => $upload[ 'type' ],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $upload[ 'url' ] ) ),
                'post_content'   => '',
                'post_status'    => 'inherit',
                'guid'           => $upload[ 'url' ],
            ];

            $attachID = wp_insert_attachment( $attachment, $upload[ 'file' ] );

            if ( is_wp_error( $attachID ) ) {
                return false;
            }

            require_once( ABSPATH . 'wp-admin/includes/image.php' );

            $attachmentData = wp_generate_attachment_metadata( $attachID, $upload[ 'file' ] );

            wp_update_attachment_metadata( $attachID, $attachmentData );

            return $attachID;

        }

        return false;

    }

}
