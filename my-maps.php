<?php
/**
 * Main plugin file.
 *
 * @package Plance\Plugin\My_Maps
 *
 * Plugin Name: My Maps
 * Description: Creating shortcode maps, using friendly interface
 * Plugin URI:  https://plance.top/
 * Version:     1.1.1
 * Author:      plance
 * Author URI:  http://plance.top/
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: my-maps
 * Domain Path: /languages/
 */

namespace Plance\Plugin\My_Maps;

defined( 'ABSPATH' ) || exit;


/**
 * Bootstrap.
 */
require_once __DIR__ . '/bootstrap.php';

/**
 * Actions.
 */
require_once __DIR__ . '/actions.php';
