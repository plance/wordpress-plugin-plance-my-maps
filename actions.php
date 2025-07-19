<?php
/**
 * Actions.
 *
 * @package Plance\Plugin\My_Maps
 */

namespace Plance\Plugin\My_Maps;

defined( 'ABSPATH' ) || exit;


register_activation_hook( __DIR__ . '/my-maps.php', array( Plugin::class, 'activate' ) );
register_uninstall_hook( __DIR__ . '/my-maps.php', array( Plugin::class, 'uninstall' ) );


add_action( 'plugins_loaded', array( Flash::class, 'instance' ) );
add_action( 'plugins_loaded', array( Assets::class, 'instance' ) );
add_action( 'plugins_loaded', array( Shortcode::class, 'instance' ) );
add_action( 'plugins_loaded', array( Admin_Menu::class, 'instance' ) );
add_action( 'plugins_loaded', array( Dependency::class, 'instance' ) );
