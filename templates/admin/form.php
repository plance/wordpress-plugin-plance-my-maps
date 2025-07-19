<?php
/**
 * Form.
 *
 * @package Plance\Plugin\My_Maps
 */

use const Plance\Plugin\My_Maps\SECURITY;

defined( 'ABSPATH' ) || exit;

$map_data = $args['map'];
?>

<div class="wrap">
	<h2><?php echo esc_attr( $args['form_title'] ); ?></h2>
	<form method="post" action="<?php echo esc_url( $args['form_action'] ); ?>" class="plance-plugin-my-maps-form">
		<?php wp_nonce_field( SECURITY ); ?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Title', 'my-maps' ); ?></th>
				<td>
					<input name="map_data[title]" type="text" value="<?php echo esc_attr( $map_data['title'] ); ?>">
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Address', 'my-maps' ); ?></th>
				<td>
					<input name="map_data[address]" type="text" id="my-map-address" value="<?php echo esc_attr( $map_data['address'] ); ?>">
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
	<div id="my-map"></div>
</div>
