<?php
/**
 * Settings.
 *
 * @package Plance\Plugin\My_Maps
 */

use Plance\Plugin\My_Maps\Controller_Settings;

defined( 'ABSPATH' ) || exit;
?>

<div class="wrap">
	<h2><?php esc_html_e( 'Settings', 'my-maps' ); ?></h2>
	<form action="options.php" method="post">
		<?php settings_fields( Controller_Settings::OPTION_GROUP ); ?>
		<?php do_settings_sections( Controller_Settings::SLUG ); ?>

		<h3><?php esc_html_e( 'Help', 'my-maps' ); ?></h3>
		<p><?php esc_html_e( 'In the console at console.cloud.google.com, enable the following APIs:', 'my-maps' ); ?></p>
		<ul>
			<li>— <?php esc_html_e( 'Maps JavaScript API', 'my-maps' ); ?><li>
			<li>— <?php esc_html_e( 'Places API', 'my-maps' ); ?><li>
			<li>— <?php esc_html_e( 'Geocoding API', 'my-maps' ); ?><li>
			<li>— <?php esc_html_e( 'Maps Static API', 'my-maps' ); ?><li>
		</ul>
		<?php submit_button(); ?>
	</form>
</div>
