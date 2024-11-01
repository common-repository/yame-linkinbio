<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://yame.be
 * @since             1.0.0
 * @package           Linkinbio
 *
 * @wordpress-plugin
 * Plugin Name:       LinkInBio
 * Plugin URI:        https://yame.be/linkinbio
 * Description:       Generates an Instagram page where you can showcase your Instagram posts with a custom link and text.
 * Version:           0.9.0
 * Author:            Yame
 * Author URI:        https://yame.be
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       yame-linkinbio
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LINKINBIO_VERSION', '0.9.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-linkinbio-activator.php
 */
function activate_linkinbio() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-linkinbio-activator.php';
	Linkinbio_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-linkinbio-deactivator.php
 */
function deactivate_linkinbio() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-linkinbio-deactivator.php';
	Linkinbio_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_linkinbio' );
register_deactivation_hook( __FILE__, 'deactivate_linkinbio' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-linkinbio.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_linkinbio() {

	$plugin = new Linkinbio();
	$plugin->run();

}
run_linkinbio();
