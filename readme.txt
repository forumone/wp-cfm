=== WP-CFM ===
Contributors: mgibbs189, elvismdev, mickaelperrin, alfreddatakillen, joshlevinson, jmslbam, gbeezus, tnolte
Tags: configuration, settings, configuration management, features, wordpress, wp-cli
Donate link: http://forumone.com/
Requires at least: 4.7
Tested up to: 6.3.2
Requires PHP: 7.4
Stable tag: 1.7.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage and deploy WordPress configuration changes.

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

= Filters =

See the filters reference page at the [GitHub Wiki](https://github.com/forumone/wp-cfm/wiki/Filters-Reference).


== Installation ==

1. Download and activate the plugin.
2. Browse to `Settings > WP-CFM` to configure.

== Screenshots ==
1. The admin management interface
2. Editing a configuration "bundle"
3. Diff viewer to track changes within a bundle

== Changelog ==

<!-- [START AUTO UPDATE] -->
<!-- Please keep comment here to allow auto-update -->
= 1.7.6 =
### :bug: Bug Fixes
- [](https://github.com/forumone/wp-cfm/commit/de3cac52607d7879b07e62a42bac62dc7925e98a) - **readwrite**: Add check to skip pulling a bundle if there was no matching bundle file *(PR #132 by @jessedyck)*
- [](https://github.com/forumone/wp-cfm/commit/e6fd2c7e14041a9c0d79d850f31d25d593cafae4) - **core**: Swap instances of filter_input for sanitize_text_field *(PR #136 by @gbeezus)*
- [](https://github.com/forumone/wp-cfm/commit/d5a6122cc1661268eadcf076e5f71809529d60b4) - **PHP**: Fixes PHP related deprecation notices & build bugs *(PR #146 by @timnolte)*
  - :arrow_lower_right: *fixes issue #135 opened by @gbeezus*
  - :arrow_lower_right: *fixes issue #147 opened by @timnolte*

### :wrench: Chores
- [](https://github.com/forumone/wp-cfm/commit/49b269405aa51b536a891a55aa672434be60a0c9) - **ci**: Adds Build/Release Workflows & Updates for Automation *(commit by @timnolte)*
- [](https://github.com/forumone/wp-cfm/commit/e7cccf9bfb93f532274a50d62e53f72870a51458) - **linting**: Adds Linting Support & Sets a Baseline *(PR #145 by @timnolte)*
- [](https://github.com/forumone/wp-cfm/commit/d41216b2c39d9fcaee4ad791f067537fc5582221) - **ci**: Fixes Release Build SemVer Changes Lookup *(PR #150 by @timnolte)*
- [](https://github.com/forumone/wp-cfm/commit/f81429d365e6409b59b67ef0b5a5d4bf9548bb45) - **ci**: Fixes New Release Changelog Generation GitHub Actions Step *(PR #152 by @timnolte)*
- [](https://github.com/forumone/wp-cfm/commit/711fba63773f8f18f4891d05999fc497326fcbb0) - **ci**: Fixes new Release Build Workflow to Update the readme.txt *(PR #154 by @timnolte)*
- [](https://github.com/forumone/wp-cfm/commit/769ad58be7487d684ed5c11a4067418668042e30) - **ci**: Fixes Missing Quotes During Temp Changelog Output *(PR #156 by @timnolte)*
- [](https://github.com/forumone/wp-cfm/commit/d605935e48ae0d768d2ada1493bc9924d4c4009e) - **ci**: Forces Use of Release Branch for New Release Commit *(PR #158 by @timnolte)*
- [](https://github.com/forumone/wp-cfm/commit/199323147701dded5a28cb4f50dff920617bbf99) - **ci**: Changes the GitHub Action Used for Release Commits/Pushes *(PR #160 by @timnolte)*
<!-- [END AUTO UPDATE] -->

--------

[See the previous changelogs here](https://github.com/forumone/wp-cfm/blob/main/CHANGELOG.md#changelog)
