<?php

namespace WPBR\App\Hooks\Controllers\Admin;

use function WPBR\App\Core;
use WPBR\App\Hooks\Controllers\Controller;
use WPBR\App\Admin\Menu as AdminMenu;
use function WPBR\App\response;

/**
 * Handles admin menu actions
 *
 * @author Przemysław Żydek
 */
class Menu extends Controller {

    /**
     * Performs controller setup
     *
     * @return void
     */
    protected function setup() {

        $slug = Core()->slug;

        $this->setupScripts();

        add_action( "wpbr/{$slug}/admin/menu/beforeRender", [ $this, 'createMenus' ] );
        add_action( 'wp_ajax_wpbr_save_general', [ $this, 'saveGeneral' ] );

    }

    public function saveGeneral() {
        response()->sendJson();
    }

    /**
     * @return self
     */
    protected function setupScripts(): self {

        $this->scripts = [
            [
                'slug'     => 'wpbr-admin-settings',
                'fileName' => 'wpbr-adminSettings',
                'inFooter' => true,
                'admin'    => true,
                'vars'     => [
                    'messages'        => [
                        'serverError'          => __( 'Unexpected server error.', 'wpbr' ),
                        'changesMade'          => __( 'Changes were made, you should save!', 'wpbr' ),
                        'save'                 => __( 'Save settings', 'wpbr' ),
                        'slotLength'           => __( 'Default slot length', 'wpbr' ),
                        'slotLengthSub'        => __( 'Select default slot length that will be used to build timeslots for all services (this option is also customizable per service).', 'wpbr' ),
                        'cancellationLimit'    => __( 'Cancellation limit', 'wpbr' ),
                        'cancellationLimitSub' => __( 'To prevent services from being cancelled too late you can pick time limit after which service won\'t be cancelable anymore (this option is also customizable per service).', 'wpbr' ),
                        'defaultStatus'        => __( 'Default booking status', 'wpbr' ),
                        'defaultStatusSub'     => __( 'Select default status that booking will have after being created.', 'wpbr' ),
                        'requiredFields'       => __( 'Required fields', 'wpbr' ),
                        'requiredFieldsSub'    => __( 'Select fields that will be required while creating bookings', 'wpbr' ),
                    ],
                    'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
                    'slotLengths'     => [
                        '15 minutes' => __( '15 mins', 'wpbr' ),
                        '30 minutes' => __( '30 mins', 'wpbr' ),
                        '45 minutes' => __( '45 mins', 'wpbr' ),
                        '60 minutes' => __( '1 hour', 'wpbr' ),
                        '2 hours'    => __( '1 hours', 'wpbr' ),
                        '3 hours'    => __( '3 hours', 'wpbr' ),
                        '6 hours'    => __( '6 hours', 'wpbr' ),
                        '12 hours'   => __( '12 hours', 'wpbr' ),
                    ],
                    'cancelLengths'   => [
                        '15 minutes' => __( '15 mins', 'wpbr' ),
                        '30 minutes' => __( '30 mins', 'wpbr' ),
                        '45 minutes' => __( '45 mins', 'wpbr' ),
                        '60 minutes' => __( '1 hour', 'wpbr' ),
                        '2 hours'    => __( '1 hours', 'wpbr' ),
                        '3 hours'    => __( '3 hours', 'wpbr' ),
                        '6 hours'    => __( '6 hours', 'wpbr' ),
                        '12 hours'   => __( '12 hours', 'wpbr' ),
                        '24 hours'   => __( '24 hours', 'wpbr' ),
                        '2 days'     => __( '2 days', 'wpbr' ),
                        '3 days'     => __( '3 days', 'wpbr' ),
                        '5 days'     => __( '5 days', 'wpbr' ),
                        '6 days'     => __( '6 days', 'wpbr' ),
                        '1 week'     => __( '1 week', 'wpbr' ),
                        '2 weeks'    => __( '2 weeks', 'wpbr' ),
                        '3 weeks'    => __( '3 weeks', 'wpbr' ),
                        '4 weeks'    => __( '4 weeks', 'wpbr' ),
                        '5 weeks'    => __( '5 weeks', 'wpbr' ),
                        '6 weeks'    => __( '6 weeks', 'wpbr' ),
                    ],
                    'bookingStatuses' => [
                        'pending'  => __( 'Pending', 'wpbr' ),
                        'approved' => __( 'Approved', 'wpbr' ),
                    ],
                    'fields'          => [
                        'firstName'   => __( 'First name', 'wpbr' ),
                        'lastName'    => __( 'Last name', 'wpbr' ),
                        'phoneNumber' => __( 'Phone number', 'wpbr' ),
                    ],
                ],
            ],
        ];

        return $this;

    }

    /**
     * @param AdminMenu $menu
     *
     * @return void
     */
    public function createMenus( AdminMenu $menu ) {

        $this->loadScripts();

        $menu->addSubmenu(
            __( 'Settings', 'wpbr' ),
            __( 'Settings', 'wpbr' ),
            'manage_options',
            'wpbr_settings',
            function () {
                echo Core()->view->renderAppContainer( 'wpbr_settings' );
            }
        );

    }

}
