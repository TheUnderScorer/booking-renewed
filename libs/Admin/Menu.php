<?php

namespace WPBR\App\Admin;

use WPBR\App\Settings;

/**
 * @author Przemysław Żydek
 */
class Menu {

    /** @var string Menu slug */
    protected $slug;

    /** @var array Stores subMenus */
    protected $submenus = [];

    /**
     * Menu constructor
     *
     * @param string $slug
     */
    public function __construct( string $slug ) {
        $this->slug = $slug;

        add_action( 'admin_menu', [ $this, 'register' ] );
    }

    /**
     * Registers admin menus
     *
     * @return void
     */
    public function register() {

        $menuName = str_replace( ' ', '_', $this->slug );

        add_menu_page(
            $menuName,
            $menuName,
            'manage_options',
            $this->slug,
            [ $this, 'renderMenu' ],
            apply_filters( "wpbr/{$this->slug}/admin/menu/iconUrl", '' )
        );

        do_action("wpbr/{$this->slug}/admin/menu/beforeRender", $this);

        foreach ( $this->submenus as $submenu ) {

            if ( $submenu[ 'is_acf' ] && Settings::isAcf() ) {

                acf_add_options_sub_page( [
                    'page_title'  => $submenu[ 'page_title' ],
                    'menu_title'  => $submenu[ 'menu_title' ],
                    'parent_slug' => $this->slug,
                    'capability'  => $submenu[ 'capability' ],
                    'menu_slug'   => $submenu[ 'menu_slug' ],
                ] );

            } else {

                add_submenu_page(
                    $this->slug,
                    $submenu[ 'page_title' ],
                    $submenu[ 'menu_title' ],
                    $submenu[ 'capability' ],
                    $submenu[ 'menu_slug' ],
                    $submenu[ 'callback' ]
                );

            }

        }

    }

    /**
     * Renders menu content
     *
     * @return void
     */
    public function renderMenu() {
        do_action( "wpbr/{$this->slug}/menu/render", $this );
    }

    /**
     * Adds new submenu to
     *
     * @param string $pageTitle
     * @param string $menuTitle
     * @param string $capability
     * @param string $menuSlug
     * @param mixed  $callback
     * @param bool   $isAcf Whenever menu should be registered as ACF options page
     *
     * @return Menu
     */
    public function addSubmenu( string $pageTitle, string $menuTitle, string $capability, string $menuSlug, $callback, bool $isAcf = false ): self {

        $this->submenus[] = [
            'page_title' => $pageTitle,
            'menu_title' => $menuTitle,
            'capability' => $capability,
            'menu_slug'  => $menuSlug,
            'callback'   => $callback,
            'is_acf'     => $isAcf,
        ];

        return $this;

    }

}
