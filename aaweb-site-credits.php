<?php
/**
 * Plugin Name: AAWEB Site Credits
 * Plugin URI: https://antoapweb.gr/aaweb-site-credits/
 * Description: Elementor widget, Gutenberg block and shortcode for displaying professional website credits with the current year.
 * Version: 1.1.1
 * Author: AAWEB Apostolou Antonios
 * Author URI: https://antoapweb.gr
 * Text Domain: aaweb-site-credits
 * Requires at least: 6.8
 * Tested up to: 7.0
 * Requires PHP: 7.4
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package AAWEB_Site_Credits
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AAWEB_SITE_CREDITS_VERSION', '1.1.1' );
define( 'AAWEB_SITE_CREDITS_FILE', __FILE__ );
define( 'AAWEB_SITE_CREDITS_DIR', plugin_dir_path( __FILE__ ) );
define( 'AAWEB_SITE_CREDITS_URL', plugin_dir_url( __FILE__ ) );

require_once AAWEB_SITE_CREDITS_DIR . 'includes/class-aaweb-site-credits-core.php';

add_action(
	'plugins_loaded',
	static function () {
		AAWEB_Site_Credits_Core::init();
	}
);
