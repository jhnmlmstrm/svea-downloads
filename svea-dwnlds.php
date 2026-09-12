<?php
/**
 * Plugin Name:       Svea Checkout Plugin
 * Description:       Hämtar och visar antal nedladdningar för SVEA Checkout från Wordpress.org.
 * Version:           1.0.0
 * Author:            JM
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       svea-dwnlds
 *
 * @package Svea_Dwnlds
 */

/* Prevent direct access to this file */
if (!defined('ABSPATH')) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-svea-dwnlds-api.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/class-svea-dwnlds-admin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-svea-dwnlds.php';
new Svea_Dwnlds();
