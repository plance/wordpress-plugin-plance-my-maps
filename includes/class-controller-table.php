<?php
/**
 * Controller_Table class.
 *
 * @package Plance\Plugin\My_Maps
 */

namespace Plance\Plugin\My_Maps;

defined( 'ABSPATH' ) || exit;

/**
 * Controller_Table class.
 */
class Controller_Table {
	use Singleton;

	const SLUG = 'plance-my-maps-table';

	/**
	 * Table.
	 *
	 * @var Table_Maps
	 */
	private $table;

	/**
	 * Action.
	 *
	 * @return void
	 */
	public function action() {
		add_screen_option(
			'per_page',
			array(
				'label'   => __( 'Records', 'my-maps' ),
				'default' => 10,
				'option'  => 'my_maps_per_page',
			)
		);

		$this->table = new Table_Maps();
		$action      = $this->table->current_action();

		if ( empty( $action ) ) {
			return;
		}

		$map_ids = array();
		if ( ! empty( $_GET['map_id'] ) && is_array( $_GET['map_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$map_ids = filter_input( INPUT_GET, 'map_id', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY );
		} else {
			$map_ids = array( filter_input( INPUT_GET, 'map_id', FILTER_SANITIZE_NUMBER_INT ) );
		}

		if ( empty( $map_ids ) ) {
			return;
		}

		global $wpdb;

		switch ( $action ) {
			case 'delete':
				foreach ( $map_ids as $map_id ) {
					$wpdb->delete( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
						$wpdb->prefix . 'plance_msm_maps',
						array( 'id' => (int) $map_id ),
						array( '%d' )
					);
				}
				Flash::redirect( add_query_arg( array( 'page' => self::SLUG ), admin_url( 'admin.php' ) ), __( 'Map(s) deleted', 'my-maps' ) );
				break;
		}
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$this->table->prepare_items();
		?>
		<div class="wrap">
			<h2>
				<?php esc_html_e( 'List Maps', 'my-maps' ); ?>
				<a href="<?php echo esc_url( add_query_arg( array( 'page' => Controller_Form::SLUG ) ) ); ?>" class="page-title-action">
					<?php esc_html_e( 'Add Map', 'my-maps' ); ?>
				</a>
			</h2>
			<form method="get">
				<input type="hidden" name="page" value="<?php echo esc_attr( self::SLUG ); ?>" />
				<?php $this->table->search_box( __( 'Search', 'my-maps' ), 'search_id' ); ?>
				<?php $this->table->display(); ?>
			</form>
		</div>
		<?php
	}
}
