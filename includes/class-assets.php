<?php
/**
 * Assets class.
 *
 * @package Plance\Plugin\My_Maps
 */

namespace Plance\Plugin\My_Maps;

defined( 'ABSPATH' ) || exit;

/**
 * Assets class.
 */
class Assets {
	use Singleton;

	/**
	 * Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_filter( 'script_loader_tag', array( $this, 'script_loader_tag' ), 10, 3 );
	}

	/**
	 * Hook: admin_enqueue_scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style(
			'my-maps',
			PLANCE_PLUGIN_MY_MAPS_URL . '/assets/css/admin-style.css',
			array(),
			VERSION
		);

		$api_key = get_option( FIELD_API_KEY, false );
		if ( empty( $api_key ) ) {
			return;
		}

		wp_register_script(
			'my-maps-init',
			null,
			array(
				'my-maps',
				'vendor-maps-googleapis-com',
			),
			VERSION,
			true
		);

		wp_register_script(
			'my-maps',
			PLANCE_PLUGIN_MY_MAPS_URL . '/assets/javascript/admin-javascript.js',
			array(),
			VERSION,
			false
		);

		$language = 'en';
		$locale   = get_locale();
		$lang     = substr( $locale, 0, 2 );
		if ( $lang ) {
			$language = $lang;
		}

		wp_register_script(
			'vendor-maps-googleapis-com',
			'https://maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places&callback=initMap&language=' . $language,
			array(),
			'1.0.0',
			false
		);
	}

	/**
	 * Hook: script_loader_tag
	 *
	 * @param  string $tag Tag.
	 * @param  string $handle Handle.
	 * @param  string $src Src.
	 * @return string
	 */
	public function script_loader_tag( $tag, $handle, $src ) {
		if ( 'vendor-maps-googleapis-com' === $handle ) {
			return '<script src="' . esc_url( $src ) . '" async defer></script>'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		}

		return $tag;
	}
}
