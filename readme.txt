=== WP-CFM ===
Contributors: mgibbs189
Donate link: http://forumone.com/
Tags: configuration, settings, configuration management, features, drupal
Requires at least: 4.0
Tested up to: 4.3
Stable tag: trunk
License: GPL2

Manage and deploy WordPress configuration changes

== Description ==

WP-CFM lets you copy database configuration to / from the filesystem. Easily deploy configuration changes without needing to copy the entire database. WP-CFM is similar to Drupal's Features module.

= How will WP-CFM benefit me? =

* Less need to copy over the entire database.
* No more rushing to figure out which settings you forgot to change.
* Easily track and version configuration changes via git, subversion, etc.

= Which data does WP-CFM support? =

* WP settings (`wp_options` table)
* Multisite settings (`wp_sitemeta` table)
* Taxonomy terms
* Custom Field Suite field groups
* Coming soon: Advanced Custom Fields support

= Terminology =

* Bundle - A group of (one or more) settings to track
* Push - Export database settings to the filesystem
* Pull - Import file-based settings into the database

= WP-CLI =

WP-CFM supports [pull / push / diff] of bundles from the command-line using [WP-CLI](http://wp-cli.org/):

<pre>
wp config pull [bundle_name]
wp config push [bundle_name]
wp config diff [bundle_name]
wp config bundles
wp config show_bundle [bundle_name]
</pre>

You can optionally set `bundle_name` to "all" to include all bundles.

Append the `--network` flag to include multisite bundles.

= How to add custom configuration =

<pre>
add_filter( 'wpcfm_configuration_items', 'my_configuration_items' );
add_filter( 'wpcfm_pull_callback', 'my_pull_callback' );


/**
 * Register custom settings
 *
 * @param array $items Associative array of configuration items
 * @return array
 */
function my_configuration_items( $items ) {
    $items['myprefix_data'] = array(
        'value'     => 'YOUR CONFIGURATION DATA',
        'label'     => 'The value label', // optional
        'group'     => 'The Group Name', // optional
    );
    return $items;
}


/**
 * Tell WP-CFM how to import custom settings
 *
 * $params['name']          The option name
 * $params['group']         The option group
 * $params['old_value']     The current DB value that will get overwritten
 * $params['new_value']     The new DB value
 */
function my_pull_callback( $params ) {
    if ( 'myprefix_data' == $params['name'] ) {
        // Import the data
    }
}
</pre>

== Installation ==

1. Download and activate the plugin.
2. Browse to `Settings > WP-CFM` to configure.

== Screenshots ==
1. The admin management interface
2. Editing a configuration "bundle"
3. Diff viewer to track changes within a bundle

== Changelog ==

= 1.4.3 =
* Fixed: WP-CLI diff (props @mortana42)

= 1.4.2 =
* Fixed: pulls broken due to taxonomy bug
* Fixed: Custom Field Suite support
* Improved: code formatting

= 1.4.1 =
* Wrapped CFS integration into a class
* Removed unnecessary diff code
* Code formatting tweaks

= 1.4 =
* New: support for taxonomy terms
* Improved: better usability for response messages
* Improved: replaced multiselect UI tool
* Fixed: diff viewer highlighting issues
* Added screenshots

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
