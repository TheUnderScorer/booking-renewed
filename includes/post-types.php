<?php

namespace WPBR\App;

use WPBR\App\Creators\PostType;
use WPBR\App\Models\Service;

PostType::create(
    Service::POST_TYPE,
    [
        'labels'             => [
            'name'               => _x( 'Services', 'post type general name', 'wpbr' ),
            'singular_name'      => _x( 'Service', 'post type singular name', 'wpbr' ),
            'menu_name'          => _x( 'Services', 'admin menu', 'wpbr' ),
            'name_admin_bar'     => _x( 'Service', 'add new on admin bar', 'wpbr' ),
            'add_new'            => _x( 'Add New', 'Add new service', 'wpbr' ),
            'add_new_item'       => __( 'Add New Service', 'wpbr' ),
            'new_item'           => __( 'New Service', 'wpbr' ),
            'edit_item'          => __( 'Edit Service', 'wpbr' ),
            'view_item'          => __( 'View Service', 'wpbr' ),
            'all_items'          => __( 'All Services', 'wpbr' ),
            'search_items'       => __( 'Search Services', 'wpbr' ),
            'parent_item_colon'  => __( 'Parent Services:', 'wpbr' ),
            'not_found'          => __( 'No services found.', 'wpbr' ),
            'not_found_in_trash' => __( 'No services found in Trash.', 'wpbr' ),
        ],
        'description'        => __( 'Service description.', 'wpbr' ),
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'query_var'          => true,
        'rewrite'            => [ 'slug' => Service::POST_TYPE ],
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
    ]
);
