<?php
/**
 * Dependesy class.
 *
 * @package Plance\Plugin\My_Maps
 */

namespace Plance\Plugin\My_Maps;

defined( 'ABSPATH' ) || exit;

/**
 * Dependesy class.
 */
class Dependency {
	use Singleton;

	/**
	 * Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Hook: admin_notices.
	 *
	 * @return void
	 */
	public function admin_notices() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		if ( Controller_Table::SLUG !== get_current_screen()->parent_base ) {
			return;
		}

		if ( ! empty( get_option( FIELD_API_KEY, false ) ) ) {
			return;
		}

		?>
			<div class="notice notice-error">
				<p>
					<?php
						printf(
							__( 'For the plugin to work, you need to specify the "<a href="%s">Google API Key</a>"', 'my-maps' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.WP.I18n.MissingTranslatorsComment
							add_query_arg( array( 'page' => Controller_Settings::SLUG ), admin_url( 'admin.php' ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						);
					?>
				</p>
			</div>
		<?php
	}
}
