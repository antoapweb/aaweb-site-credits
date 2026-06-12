<?php
/**
 * Uninstall cleanup.
 *
 * @package AAWEB_Site_Credits
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'aaweb_site_credits_options' );
