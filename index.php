<?php
/*
Plugin Name: Wordpress Booking Renewed
Plugin URI:
Description: Completly renewed booking experience for WordPress platform
Author: Przemysław Żydek
Author URI: https://github.com/TheUnderScorer
Version: 0.0
Text Domain: wpbr
*/

namespace WPBR\App;

//Require necessary files
use WPBR\App\Storage\Instances;

define( 'WPBR_DIR', plugin_dir_path( __FILE__ ) );

require_once WPBR_DIR . 'includes/http.php';
require_once WPBR_DIR . 'vendor/autoload.php';

/**
 * @return void
 */
function requireFiles() {

    require_once WPBR_DIR . 'includes/controllers.php';

    require_once WPBR_DIR . 'includes/enqueue.php';

}

/**
 * Helper function for accessing Core.
 *
 * @return Core
 *
 * @author Przemysław Żydek
 */
function Core(): Core {

    $plugin = Instances::get( 'core' );

    if ( ! $plugin instanceof Core ) {

        $plugin = Instances::add(
            'core',
            new Core( 'WPBR', __FILE__ )
        );

        requireFiles();

    }

    return $plugin;
}

Core();
