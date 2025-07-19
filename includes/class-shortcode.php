<?php
/**
 * Shortcode class.
 *
 * @package Plance\Plugin\My_Maps
 */

namespace Plance\Plugin\My_Maps;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode class.
 */
class Shortcode {
	use Singleton;

	/**
	 * Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_shortcode( 'my-map', array( $this, 'shortcode' ) );
	}

	/**
	 * Shortcode.
	 *
	 * @param  array $atts Attributes.
	 * @return string
	 */
	public function shortcode( $atts = array() ) {
		global $wpdb;

		$atts = shortcode_atts(
			array(
				'id'     => 0,
				'width'  => 640,
				'height' => 480,
				'zoom'   => 10,
			),
			$atts
		);

		$atts['id'] = (int) $atts['id'];

		if ( ! empty( $atts['id'] ) ) {

			$sql = "
				SELECT `title`, `address`
				FROM `{$wpdb->prefix}plance_msm_maps`
				WHERE `id` = %d
				LIMIT 1
			";

			$sql_prepared = $wpdb->prepare(
				$sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				array(
					$atts['id'],
				)
			);

			$map = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$sql_prepared, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				ARRAY_A
			);

			if ( ! empty( $map ) ) {

				$language = 'en';
				$locale   = get_locale();
				$lang     = substr( $locale, 0, 2 );
				if ( $lang ) {
					$language = $lang;
				}

				$params = array(
					'{YOUR_API_KEY}' => get_option( FIELD_API_KEY, '' ),
					'{WIDTH}'        => $atts['width'],
					'{HEIGHT}'       => $atts['height'],
					'{ZOOM}'         => $atts['zoom'],
					'{ADDRESS}'      => $map['address'],
					'{ALT}'          => esc_attr( $map['title'] ),
					'{LANGUAGE}'     => $language,
				);

				$template = '<img src="https://maps.googleapis.com/maps/api/staticmap?key={YOUR_API_KEY}&size={WIDTH}x{HEIGHT}&zoom={ZOOM}&maptype=roadmap&markers=color:red|label:A|{ADDRESS}&language={LANGUAGE}" width="{WIDTH}" height="{HEIGHT}" alt="{ALT}">';

				return strtr( $template, $params );

			}
		}
	}
}
