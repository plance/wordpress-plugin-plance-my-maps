<?php
/**
 * Controller_Settings class.
 *
 * @package Plance\Plugin\My_Maps
 */

namespace Plance\Plugin\My_Maps;

defined( 'ABSPATH' ) || exit;

/**
 * Controller_Settings class.
 */
class Controller_Settings {
	use Singleton;

	const SLUG          = 'plance-my-maps-settings';
	const OPTION_GROUP  = 'plance-my-maps-settings-option-group';
	const GROUP_DEFAULT = 'plance-my-maps-settings-option-group-default';

	/**
	 * Hook: admin_init.
	 *
	 * @return void
	 */
	public function admin_init() {
		add_settings_section(
			self::GROUP_DEFAULT,
			'',
			null,
			self::SLUG
		);

		register_setting( self::OPTION_GROUP, FIELD_API_KEY );
		add_settings_field(
			FIELD_API_KEY,
			__( 'Google API Key', 'my-maps' ),
			function() {
				echo '<input
					type="text"
					style="width: 400px"
					name="' . esc_attr( FIELD_API_KEY ) . '"
					value="' . esc_attr( get_option( FIELD_API_KEY, '' ) ) . '">';
			},
			self::SLUG,
			self::GROUP_DEFAULT
		);
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		load_template( PATH . '/templates/admin/settings.php', false );
	}
}
