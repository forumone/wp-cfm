=== WP-CFM ===
Contributors: mgibbs189
Donate link: http://forumone.com/
Tags: configuration, settings, configuration management, features, drupal
Requires at least: 3.9
Tested up to: 4.2
Stable tag: trunk
License: GPL2

Manage and deploy WordPress configuration changes

== Description ==

WP-CFM lets you copy database configuration to / from the filesystem. Easily deploy configuration changes without needing to copy the entire database. WP-CFM is similar to Drupal's Features module.
It does the dirty work of deploying configuration changes so you don't have to.

= How will WP-CFM benefit me? =

* Less need to copy over the entire database.
* No more rushing to figure out which settings you forgot to change.
* Easily track and version configuration changes via git, subversion, etc.

= Terminology =

* Bundle - A group of (one or more) settings to track
* Push - Export database settings to the filesystem
* Pull - Import file-based settings into the database

= How to add custom configuration =

The `wpcfm_configuration_items` hook lets you register custom configuration items.

<pre>
function my_configuration_items( $items ) {
    $items['cfs_field_groups'] = array(
        'value' => 'MY CONFIGURATION DATA',
        'group' => 'WP Options', // optional
        'callback' => 'my_pull_handler', // optional
    );
    return $items;
}
add_filter( 'wpcfm_configuration_items', 'my_configuration_items' );
</pre>

This filter contains an associative array of all configuration options. Each option has a unique key, and supports several parameters:

* **value**: (required) The configuration data to store.
* **group**: (optional) A group name, allowing multiple configuration options to be grouped together. This is only used in the admin UI. Default = "WP Options"
* **callback**: (optional) If the configuration data is **not** stored within `wp_options`, then WP-CFM needs to know how to Pull it into the database. This parameter accepts a (string) function name or (array) method. A `$params` array is passed into the callback function (see below).

<pre>
/**
 * $params['name']          The option name
 * $params['group']         The option group
 * $params['old_value']     The current DB value that will get overwritten
 * $params['new_value']     The new DB value
 */
function my_pull_handler( $params ) {
    // Save something
}
</pre>

= Which configuration does WP-CFM support? =

Out-of-the-box, WP-CFM supports the `wp_options` table (incl. multisite).

= WP-CLI =

WP-CFM supports [pull / push / diff] of bundles from the command-line using [WP-CLI](http://wp-cli.org/):

<pre>
wp config pull <bundle_name>
wp config push <bundle_name>
wp config diff <bundle_name>
wp config bundles
wp config show_bundle <bundle_name>
</pre>

You can optionally set `bundle_name` to "all" to include all bundles. Also, append the `--network` flag to include multisite bundles.

== Installation ==

1. Download and activate the plugin.
2. Browse to `Settings > WP-CFM` to configure.

== Screenshots ==
1. The admin management interface
2. Editing a configuration "bundle"
3. Diff viewer to track changes within a bundle

== Changelog ==

= 1.3.2 =
* wp-cli diff support (props @joshkoenig)
* wp-cli show bundles support (props @joshkoenig)
* wp-cli get bundle details (props @joshkoenig)

= 1.3.1 =
* Fix for bundle deletion
* Better WP-CLI network support
* Now using "wp_mkdir_p" to check for writable config folder
* Updated translations

= 1.3 =
* Multisite support (props @alfreddatakillen)
* Added download link for each bundle (props @alfreddatakillen)
* Notification when the same option is stored in multiple bundles
* Subclasses can be accessed as base class variables
* Fix: ensure that "old_value" exists
* Updated translations

= 1.2 =
* Made "diff viewer" close button appear clickable
* Fixed bug with Custom Field Suite and loop sub-fields

= 1.1 =
* Added support for a config option label
* Added `get_facet_by_name` helper method
* Admin UI now recognizes file bundles
* Better error handling

= 1.0.5 =
* Synchronize bundle config options list with file during Pull

= 1.0.4 =
* Added i18n support
* Fallback for JSON_PRETTY_PRINT when PHP < 5.4
* Fixed PHP notices when doing Pulls
* Excluded some unnecessary CFS config options

= 1.0.3 =
* Added relative plugin URL (props @tormjens)
* Added subtle admin screen animations
* Better file error handling
* CFS integration - each field group now has its own configuration item
* Added `wpcfm_pull_callback` filter
* Moved the "all" bundle handler from the ajax class to readwrite

= 1.0.2 =
* Fix: error when Custom Field Suite isn't active

= 1.0.1 =
* Custom Field Suite integration
