<?php

namespace WPBR\Tests;

// disable xdebug backtrace
if ( function_exists( 'xdebug_disable' ) ) {
    xdebug_disable();
}

if ( false !== getenv( 'WP_PLUGIN_DIR' ) ) {
    define( 'WP_PLUGIN_DIR', getenv( 'WP_PLUGIN_DIR' ) );
}

if ( false !== getenv( 'WP_THEMES_DIR' ) ) {
    define( 'WP_THEMES_DIR', getenv( 'WP_THEMES_DIR' ) );
}

if ( false !== getenv( 'WP_DEVELOP_DIR' ) ) {
    require getenv( 'WP_DEVELOP_DIR' ) . 'tests/phpunit/includes/bootstrap.php';
}

require_once getenv( 'CORE_PLUGIN_DIR' ) . 'index.php';
require_once getenv( 'CORE_PLUGIN_DIR' ) . 'wpk-abc12345/index.php';

