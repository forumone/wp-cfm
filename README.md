# WP-CFM #
**Contributors:** [forum1](https://profiles.wordpress.org/forum1/), [mgibbs189](https://profiles.wordpress.org/mgibbs189/), [elvismdev](https://profiles.wordpress.org/elvismdev/), [mickaelperrin](https://profiles.wordpress.org/mickaelperrin/), [alfreddatakillen](https://profiles.wordpress.org/alfreddatakillen/), [joshlevinson](https://profiles.wordpress.org/joshlevinson/), [jmslbam](https://profiles.wordpress.org/jmslbam/), [gbeezus](https://profiles.wordpress.org/gbeezus/), [tnolte](https://profiles.wordpress.org/tnolte/), [dsteersf1](https://profiles.wordpress.org/dsteersf1/), [jgleisner](https://profiles.wordpress.org/jgleisner/)  
**Tags:** configuration, settings, configuration management, features, wordpress, wp-cli  
**Donate link:** http://forumone.com/  
**Requires at least:** 4.7  
**Tested up to:** 6.4.3  
**Requires PHP:** 7.4  
**Stable tag:** 1.7.9  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Manage and deploy WordPress configuration changes.

## Description ##

WP-CFM lets you copy database configuration to / from the filesystem. Easily deploy configuration changes without needing to copy the entire database. WP-CFM is similar to Drupal's Features module.

### How will WP-CFM benefit me? ###

* Less need to copy over the entire database.
* No more rushing to figure out which settings you forgot to change.
* Easily track and version configuration changes via git, subversion, etc.

### Which data does WP-CFM support? ###

* WP settings (`wp_options` table)
* Multisite settings (`wp_sitemeta` table)
* Taxonomy terms
* Custom Field Suite field groups

### Terminology ###

* Bundle - A group of (one or more) settings to track
* Push - Export database settings to the filesystem
* Pull - Import file-based settings into the database

### WP-CLI ###

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

### Filters ###

See the filters reference page at the [GitHub Wiki](https://github.com/forumone/wp-cfm/wiki/Filters-Reference).


## Installation ##

1. Download and activate the plugin.
2. Browse to `Settings > WP-CFM` to configure.

## Screenshots ##
1. The admin management interface
2. Editing a configuration "bundle"
3. Diff viewer to track changes within a bundle

## Changelog ##

<!-- [START AUTO UPDATE] -->
<!-- Please keep comment here to allow auto-update -->
### 1.7.9 ###
### :bug: Bug Fixes
- [](https://github.com/forumone/wp-cfm/commit/a11a6c2e4c8618726cffea4a994217be8943ec5f) - **Admin AJAX**: Fixes CSRF issue in AJAX requests due to missing nonces *(commit by @timnolte)*

### :wrench: Chores
- [](https://github.com/forumone/wp-cfm/commit/4377104208239b15f2e4d4edae700b1c2af38885) - **ci**: Fixes Release Tag Format *(PR #170 by @timnolte)*
- [](https://github.com/forumone/wp-cfm/commit/648ae5abc2fdcbdbf5e5487979ae113c2c5c543d) - **docs**: Updates Contributors List *(PR #171 by @timnolte)*
- [](https://github.com/forumone/wp-cfm/commit/272e452721b7f57542fd9a4637b9bf25b84d81cb) - **PHPStan**: Fixes PHPStan linting errors in security patch *(PR #172 by @timnolte)*
- [](https://github.com/forumone/wp-cfm/commit/5d3b9e846baa51d9119c3f196acd23cbe57ce14e) - **README**: Updates WordPress version compatibility *(PR #173 by @timnolte)*
<!-- [END AUTO UPDATE] -->

--------

[See the previous changelogs here](https://github.com/forumone/wp-cfm/blob/main/CHANGELOG.md#changelog)
