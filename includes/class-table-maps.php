<?php
/**
 * Table_Shortcodes class.
 *
 * @package Plance\Plugin\My_Maps
 */

namespace Plance\Plugin\My_Maps;

defined( 'ABSPATH' ) || exit;

use WP_List_Table;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Table_Maps class.
 */
class Table_Maps extends WP_List_Table {
	/**
	 * Prepares the list of items for displaying.
	 *
	 * @return void
	 */
	public function prepare_items() {
		global $wpdb;

		$total_items = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			"SELECT COUNT(`id`)
			FROM `{$wpdb->prefix}plance_msm_maps`
			{$this->get_part_sql_where()}" // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		);

		$per_page = $this->get_items_per_page( 'my_maps_per_page', 10 );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			)
		);

		$this->items = $this->table_data();
	}

	/**
	 * Return columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'          => '<input type="checkbox" />',
			'id'          => __( 'ID', 'my-maps' ),
			'title'       => __( 'Title', 'my-maps' ),
			'address'     => __( 'Address', 'my-maps' ),
			'shortcode'   => __( 'Shortcode', 'my-maps' ),
			'date_create' => __( 'Date create', 'my-maps' ),
		);
	}

	/**
	 * Return sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return array(
			'id'          => array( 'id', false ),
			'title'       => array( 'title', false ),
			'address'     => array( 'address', false ),
			'shortcode'   => array( 'id', false ),
			'date_create' => array( 'date_create', false ),
		);
	}

	/**
	 * Return table data.
	 *
	 * @return array
	 */
	private function table_data() {
		global $wpdb;

		$per_page = (int) $this->get_pagination_arg( 'per_page' );
		$pagenum  = (int) $this->get_pagenum();
		$order_ar = $this->get_sortable_columns();

		$order   = 'DESC';
		$orderby = 'date_create';

		$input_order   = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$input_orderby = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( ! empty( $input_order ) ) {
			$order = 'asc' === $input_order ? 'ASC' : 'DESC';
		}

		if ( ! empty( $input_orderby ) && ! empty( $order_ar[ $input_orderby ] ) ) {
			$orderby = $input_orderby;
		}

		$sql = "
			SELECT *
			FROM `{$wpdb->prefix}plance_msm_maps`
			{$this->get_part_sql_where()}
			ORDER BY `{$orderby}` {$order}
			LIMIT %d, %d
		";

		$sql_prepared = $wpdb->prepare(
			$sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			array(
				( ( $pagenum - 1 ) * $per_page ),
				$per_page,
			)
		);

		$itetms = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$sql_prepared, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			ARRAY_A
		);

		return $itetms;
	}

	/**
	 * Print no items.
	 *
	 * @return void
	 */
	public function no_items() {
		esc_html_e( 'Data not found', 'my-maps' );
	}

	/**
	 * Print column like default.
	 *
	 * @param  array  $item Item.
	 * @param  string $column_name Column name.
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
			case 'address':
				return isset( $item[ $column_name ] ) ? $item[ $column_name ] : '-';
		}
	}

	/**
	 * Create checkbox.
	 *
	 * @param object|array $item Item.
	 * @return string
	 */
	public function column_cb( $item ) {
		return '<input type="checkbox" name="map_id[]" value="' . $item['id'] . '" />';
	}

	/**
	 * Return title column.
	 *
	 * @param array $item Item.
	 * @return string
	 */
	public function column_title( $item ) {
		$url_edit = add_query_arg(
			array(
				'page'   => Controller_Form::SLUG,
				'map_id' => $item['id'],
			)
		);

		$url_delete = add_query_arg(
			array(
				'page'   => Controller_Table::SLUG,
				'action' => 'delete',
				'map_id' => $item['id'],
			)
		);

		return $item['title'] . ' ' . $this->row_actions(
			array(
				'edit'   => '<a href="' . $url_edit . '">' . __( 'edit', 'my-maps' ) . '</a>',
				'delete' => '<a href="' . $url_delete . '" onclick="return confirm(\'' . __( 'Continue?', 'my-maps' ) . '\')">' . __( 'delete', 'my-maps' ) . '</a>',
			)
		);
	}

	/**
	 * Return shortcode.
	 *
	 * @param  array $item Item.
	 * @return string
	 */
	public function column_shortcode( $item ) {
		return '[my-map id="' . $item['id'] . '"]';
	}

	/**
	 * Return column date create.
	 *
	 * @param  array $item Item.
	 * @return string
	 */
	public function column_date_create( $item ) {
		return wp_date( get_option( 'date_format', 'd.m.Y' ) . ' ' . get_option( 'time_format', 'H:i' ), $item['date_create'] );
	}

	/**
	 * Return bulk actions.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		return array(
			'delete' => __( 'Delete', 'my-maps' ),
		);
	}

	/**
	 * Get "where" for sql.
	 *
	 * @return string
	 */
	private function get_part_sql_where() {
		global $wpdb;

		$where = '';
		$input = filter_input( INPUT_GET, 's', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( ! empty( $input ) ) {

			$like  = '%' . $wpdb->esc_like( $input ) . '%';
			$where = $wpdb->prepare(
				'WHERE
				`title` LIKE %s
					OR
				`address` LIKE %s',
				array(
					$like,
					$like,
				)
			);
		}

		return $where;
	}
}
