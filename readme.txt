=== My Maps ===
Contributors: plance
Tags: shortcode, map, google maps, location, embed
Requires at least: 4.0.0
Tested up to: 6.8
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

My Maps is a simple and lightweight WordPress plugin that allows you to easily embed Google Maps on your site using shortcodes.

== Description ==
My Maps is a simple and lightweight WordPress plugin that allows you to easily embed Google Maps on your site using shortcodes.

With this plugin, you can:
- Create custom maps and assign them to shortcodes.
- Add a single location to the map by specifying a valid address.
- Display the map anywhere on your site using the shortcode: `[my-map id="8"]`, where `id` is the unique identifier of the map.

Shortcode also supports the following optional parameters:
- `width` – sets the width of the map
- `height` – sets the height of the map
- `zoom` – sets the zoom level of the map

Example:
`[my-map id="8" width="800" height="600" zoom="14"]`

Perfect for contact pages, location previews, or any situation where a map with a single address pin is required.

== Installation ==
1. Upload the plugin to the `/wp-content/plugins/` directory or install it using the WordPress plugin installer.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the "My Maps" menu in the admin panel to create your maps and generate shortcodes.

== Frequently Asked Questions ==

= How do I embed a map on my site? =
Use the shortcode `[my-map id="X"]`, where `X` is the ID of the map you've created. You can optionally add parameters: `width`, `height`, `zoom`.

= Can I add more than one location to the map? =
Currently, the plugin supports only one location per map based on an address.

== Screenshots ==
1. Admin table listing all created map shortcodes.
2. Map creation form where you enter the map title and address.

== Changelog ==

= 1.1.1 =
* Change help.

= 1.1.0 =
* Complete code refactoring.

= 1.0 =
* Initial release – allows creation of maps with single address markers and embedding via shortcode.
