<?php

namespace WPBR\App\Hooks\Controllers\Admin;

use function WPBR\App\Core;
use WPBR\App\Hooks\Controllers\Controller;
use function WPBR\App\request;
use function WPBR\App\response;

/**
 * Handles general section of settings
 *
 * @author Przemysław Żydek
 */
class MenuSections extends Controller {

    /**
     * Performs controller setup
     *
     * @return void
     */
    protected function setup() {
        add_action( 'wp_ajax_wpbr_save_general', [ $this, 'saveGeneral' ] );
        add_action( 'wp_ajax_wpbr_save_calendar', [ $this, 'saveCalendar' ] );
        add_action( 'wp_ajax_wpbr_save_payment', [ $this, 'savePayment' ] );
        add_action( 'wp_ajax_wpbr_save_integrations', [ $this, 'saveIntegrations' ] );
    }

    /**
     * @return void
     */
    public function saveGeneral() {

        $optionKeys = apply_filters( 'wpbr/settings/generalFields', [
            'slotLength',
            'cancellationLimit',
            'defaultStatus',
        ] );

        $this->saveSection( $optionKeys );

    }

    /**
     * @return void
     */
    public function saveCalendar() {

        $optionKeys = apply_filters( 'wpbr/settings/calendarFields', [
            'firstDay',
        ] );

        $this->saveSection( $optionKeys );

    }

    /**
     * @return void
     */
    public function savePayment() {

        $optionKeys = apply_filters( 'wpbr/settings/paymentFields', [
            'paypalEnabled',
            'paypalApiName',
            'paypalApiNameSandbox',
            'paypalApiSecret',
            'paypalApiSecretSandbox',
            'paypalType',
            'paymentOnArrival',
        ] );

        $this->saveSection( $optionKeys );

    }

    /**
     * @return void
     */
    public function saveIntegrations() {

        $optionKeys = apply_filters( 'wpbr/settings/integrationFields', [
            'googleEnabled',
            'googleApi',
            'googleOAuthLogin',
            'googleOAuthSecret',
        ] );

        $this->saveSection( $optionKeys );

    }

    /**
     * Saves section using provided data with provided keys
     *
     * @param array $optionKeys
     *
     * @return void
     */
    private function saveSection( array $optionKeys ) {

        $settings = Core()->settings;

        $data = [];

        foreach ( request()->request->all() as $key => $item ) {
            if ( in_array( $key, $optionKeys ) ) {

                //Parse string to actual boolean value
                if ( $item === 'true' ) {
                    $item = true;
                } else if ( $item === 'false' ) {
                    $item = false;
                }

                $data[ $key ] = $item;
            }
        }

        $settings->updateData( $data );

        response()->setResult( true )->sendJson();

    }

}
