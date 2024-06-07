<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://chakramanijoshi.com.np/
 * @since             1.0.0
 * @package           Wc_Integrate_Qr
 *
 * @wordpress-plugin
 * Plugin Name:       WC Integrate QR
 * Plugin URI:         https://chakramanijoshi.com.np/
 * Description:       Streamline WooCommerce sales with QR pass integration. Create and manage event tickets, memberships, and coupons effortlessly. Enhance customer convenience and ensure smooth entry processes with secure QR codes.
 * Version:           1.0.0
 * Author:            CNS
 * Author URI:        https://chakramanijoshi.com.np//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-integrate-qr
 * Domain Path:       /languages
 * Requires Plugins: woocommerce
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WC_INTEGRATE_QR_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-integrate-qr-activator.php
 */
function activate_wc_integrate_qr()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wc-integrate-qr-activator.php';
    Wc_Integrate_Qr_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wc-integrate-qr-deactivator.php
 */
function deactivate_wc_integrate_qr()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wc-integrate-qr-deactivator.php';
    Wc_Integrate_Qr_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wc_integrate_qr');
register_deactivation_hook(__FILE__, 'deactivate_wc_integrate_qr');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wc-integrate-qr.php';
require plugin_dir_path(__FILE__) . 'includes/wc-integrate-qr-api-call.php';
require plugin_dir_path(__FILE__) . 'admin/class-wc-integrate-qr-admin-menus.php';
require plugin_dir_path(__FILE__) . 'public/pdf-email-template.php';
require plugin_dir_path(__FILE__) . 'includes/test.php';

require plugin_dir_path(__FILE__) . 'includes/cns-checkout-customizer.php';

// Hook into WooCommerce to override templates
add_filter('woocommerce_locate_template', 'my_custom_woocommerce_locate_template', 10, 3);

function my_custom_woocommerce_locate_template($template, $template_name, $template_path)
{
    global $woocommerce;

    $_template = $template;
    if (!$template_path) $template_path = $woocommerce->template_url;

    $plugin_path = plugin_dir_path(__FILE__) . 'woocommerce/';

    // Look within the plugin first
    $template = $plugin_path . $template_name;

    if (!file_exists($template)) {
        $template = $_template; // If not found, use default template
    }

    return $template;
}


add_action('admin_enqueue_scripts', 'enqueue_scripts_mutiselect_js');
function enqueue_scripts_mutiselect_js()
{
    wp_enqueue_script('enqueue_scripts_mutiselect_js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', array('jquery'), rand(), 'all');
}


add_action('admin_enqueue_scripts', 'enqueue_scripts_mutiselect_css');
function enqueue_scripts_mutiselect_css()
{
    wp_enqueue_style('enqueue_scripts_multiselect_css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css', array(), rand(), 'all');
}





/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_integrate_qr()
{

    $plugin = new Wc_Integrate_Qr();
    $plugin->run();
}
run_wc_integrate_qr();
