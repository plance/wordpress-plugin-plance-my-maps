<?php
/**
 * Plugin class.
 *
 * @package Plance\Plugin\My_Maps
 */

namespace Plance\Plugin\My_Maps;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin class.
 */
class Plugin {

	/**
	 * Activate.
	 *
	 * @return bool
	 */
	public static function activate() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta(
			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}plance_msm_maps` (
			`id` INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`title` VARCHAR(255) NOT NULL,
			`address` TEXT NOT NULL,
			`date_create` INT(10) UNSIGNED NOT NULL
			) {$wpdb->get_charset_collate()};"
		);

		return true;
	}

	/**
	 * Uninstall.
	 *
	 * @return bool
	 */
	public static function uninstall() {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange -- intentional schema change on uninstall
		$wpdb->query( 'DROP TABLE IF EXISTS `' . $wpdb->prefix . 'plance_msm_maps`' );

		return true;
	}
}
