<?php

namespace WPBR\App\Hooks\Controllers\Admin;

use function WPBR\App\Core;
use WPBR\App\Hooks\Controllers\Controller;
use WPBR\App\Admin\Menu as AdminMenu;
use WPBR\App\Models\Service;
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

        $servicePostType = Service::POST_TYPE;

        $menu->addSubmenu(
            __( 'Settings', 'wpbr' ),
            __( 'Settings', 'wpbr' ),
            'manage_options',
            'wpbr_settings',
            function () {
                echo Core()->view->renderAppContainer( 'wpbr_settings' );
            }
        )->addSubmenu(
            __( 'Services', 'wpbr' ),
            __( 'Services', 'wpbr' ),
            'edit_posts',
            "edit.php?post_type=$servicePostType",
            null
        );

    }

}
