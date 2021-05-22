<?php
ob_start();
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://example.com
 * @since             1.0.0
 * @package           Lsv_Login
 *
 * @wordpress-plugin
 * Plugin Name:       Live Services Viewers Login
 * Plugin URI:        example.com/lsv-login
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Benakhigbe
 * Author URI:        https://example.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lsv-login
 * Domain Path:       /languages
 */
if (!isset($_SESSION)) session_start();
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define('LSV_PATH', plugin_dir_path( __FILE__ ));
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LSV_LOGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-lsv-login-activator.php
 */
function activate_lsv_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lsv-login-activator.php';
	Lsv_Login_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-lsv-login-deactivator.php
 */
function deactivate_lsv_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lsv-login-deactivator.php';
	Lsv_Login_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_lsv_login' );
register_deactivation_hook( __FILE__, 'deactivate_lsv_login' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-lsv-login.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lsv_login() {

	$plugin = new Lsv_Login();
	$plugin->run();

}
run_lsv_login();
