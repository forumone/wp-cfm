=== WP-CFM ===
Contributors: mgibbs189
Donate link: http://forumone.com/
Tags: configuration, settings, configuration management, features, drupal
Requires at least: 3.9
Tested up to: 4.0
Stable tag: trunk
License: GPL2

WP-CFM: Configuration Management for WordPress

== Description ==

WP-CFM is a plugin for tracking database configuration. It writes database configuration to the filesystem so it can be easily versioned and deployed (using Git).

It does the dirty work of deploying configuration changes so you don't have to.

[See Screenshot](http://i.imgur.com/opQhDUa.png)

= Terminology =

* Bundle - a group of configuration settings
* Push - export database configuration to the filesystem
* Pull - import file configuration into the database

= How does it work? =

* Create some bundles within the admin screen.
* Push the bundles to filesystem.
* Move the bundle files to your other site(s).
* On the other site(s), run "Pull" to import the configuration bundles.

= WP-CLI Support =

WP-CFM supports pulling / pushing bundles from the command-line using [WP-CLI](http://wp-cli.org/):

`
wp config pull <bundle_name>
wp config push <bundle_name>
`

= Useful links =

[Developer documentation](http://forumone.github.io/wp-cfm/)

== Installation ==

1. Download and activate the plugin.
2. Browse to `Settings > WP-CFM` to configure.

== Changelog ==

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
