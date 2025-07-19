<?php
/**
 * Controller_Form class.
 *
 * @package Plance\Plugin\My_Maps
 */

namespace Plance\Plugin\My_Maps;

defined( 'ABSPATH' ) || exit;

/**
 * Controller_Form class.
 */
class Controller_Form {
	use Singleton;

	const SLUG = 'plance-my-maps-form';

	/**
	 * Map
	 *
	 * @var array
	 */
	private $map = array(
		'title'   => '',
		'address' => '',
	);

	/**
	 * Action.
	 *
	 * @return void
	 */
	public function action() {
		global $wpdb;

		$is_ajax_request = strtolower( filter_input( INPUT_SERVER, 'HTTP_X_REQUESTED_WITH', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) ) === 'xmlhttprequest';
		if ( $is_ajax_request ) {
			return;
		}

		$map_id          = (int) filter_input( INPUT_GET, 'map_id', FILTER_SANITIZE_NUMBER_INT );
		$is_post_request = strtolower( filter_input( INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) ) === 'post';

		if ( $is_post_request ) {
			$input_wpnonce = filter_input( INPUT_POST, '_wpnonce', FILTER_CALLBACK, array( 'options' => 'sanitize_text_field' ) );
			if ( ! wp_verify_nonce( $input_wpnonce, SECURITY ) ) {
				Flash::redirect( add_query_arg( array( 'page' => Controller_Table::SLUG ), admin_url( 'admin.php' ) ), __( 'Wrong `wpnonce` value!', 'my-maps' ), false );
			}

			$input = filter_input( INPUT_POST, 'map_data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$input = filter_var_array(
				$input,
				array(
					'title'   => array(
						'filter'  => FILTER_CALLBACK,
						'options' => 'sanitize_text_field',
					),
					'address' => FILTER_DEFAULT,
				)
			);

			$errors = $this->validate( $input );

			if ( empty( $errors ) ) {
				if ( $map_id ) {
					// Update.
					$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
						$wpdb->prefix . 'plance_msm_maps',
						array(
							'title'   => $input['title'],
							'address' => $input['address'],
						),
						array( 'id' => $map_id ),
						array( '%s', '%s' ),
						array( '%d' )
					);

					$query_args = array(
						'page'   => self::SLUG,
						'map_id' => $map_id,
					);
					Flash::redirect( add_query_arg( $query_args, admin_url( 'admin.php' ) ), __( 'Map updated', 'my-maps' ) );
				}

				// Create.
				$wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
					$wpdb->prefix . 'plance_msm_maps',
					array(
						'title'       => $input['title'],
						'address'     => $input['address'],
						'date_create' => time(),
					),
					array( '%s', '%s', '%d' )
				);

				Flash::redirect( add_query_arg( array( 'page' => self::SLUG ), admin_url( 'admin.php' ) ), __( 'Map created', 'my-maps' ) );
			} else {
				Flash::instance()->print( $errors, false );
				$this->map = array_merge( $this->map, $input );
			}

			return;
		}

		if ( $map_id ) {
			$sql = "
				SELECT *
				FROM `{$wpdb->prefix}plance_msm_maps`
				WHERE `id` = %d
			";

			$sql_prepared = $wpdb->prepare(
				$sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				array(
					$map_id,
				)
			);

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$this->map = $wpdb->get_row(
				$sql_prepared, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				ARRAY_A
			);

			if ( empty( $this->map ) ) {
				Flash::redirect( add_query_arg( array( 'page' => self::SLUG ), admin_url( 'admin.php' ) ), __( 'Map not found!', 'my-maps' ), false );
			}
		}
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		wp_enqueue_script( 'my-maps-init' );

		$map_id     = (int) filter_input( INPUT_GET, 'map_id', FILTER_SANITIZE_NUMBER_INT );
		$query_args = array( 'page' => self::SLUG );

		if ( $map_id ) {
			$form_title           = __( 'Editing map', 'my-maps' );
			$query_args['map_id'] = $map_id;
		} else {
			$form_title = __( 'Creating map', 'my-maps' );
		}

		load_template(
			PATH . '/templates/admin/form.php',
			false,
			array(
				'form_title'  => $form_title,
				'form_action' => add_query_arg( $query_args, admin_url( 'admin.php' ) ),
				'map'         => $this->map,
			)
		);
	}

	/**
	 * Validate.
	 *
	 * @param  array $data Data.
	 * @return array
	 */
	private function validate( $data ) {
		$errors = array();
		$labels = array(
			'title'   => __( 'Title', 'my-maps' ),
			'address' => __( 'Address', 'my-maps' ),
		);

		$data = array_map( 'trim', $data );
		foreach ( array( 'title', 'address' ) as $field ) {
			if ( empty( $data[ $field ] ) ) {
				// phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
				$errors[] = sprintf( __( '"%s" must not be empty', 'my-maps' ), $labels[ $field ] );
			}
		}

		if ( ! empty( $data['title'] ) && mb_strlen( $data['title'] ) > 255 ) {
			// phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
			$errors[] = sprintf( __( '"%1$s" must not exceed %1$d characters long', 'my-maps' ), $labels['title'], 255 );
		}

		if ( ! empty( $data['address'] ) && mb_strlen( $data['address'] ) > 255 ) {
			// phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
			$errors[] = sprintf( __( '"%1$s" must not exceed %1$d characters long', 'my-maps' ), $labels['address'], 255 );
		}

		return $errors;
	}
}
