# Change Log

## [release] - 2024-02-07
### :bug: Bug Fixes
- [`a11a6c2`](https://github.com/forumone/wp-cfm/commit/a11a6c2e4c8618726cffea4a994217be8943ec5f) - **Admin AJAX**: Fixes CSRF issue in AJAX requests due to missing nonces *(commit by [@timnolte](https://github.com/timnolte))*

### :wrench: Chores
- [`4377104`](https://github.com/forumone/wp-cfm/commit/4377104208239b15f2e4d4edae700b1c2af38885) - **ci**: Fixes Release Tag Format *(PR [#170](https://github.com/forumone/wp-cfm/pull/170) by [@timnolte](https://github.com/timnolte))*
- [`648ae5a`](https://github.com/forumone/wp-cfm/commit/648ae5abc2fdcbdbf5e5487979ae113c2c5c543d) - **docs**: Updates Contributors List *(PR [#171](https://github.com/forumone/wp-cfm/pull/171) by [@timnolte](https://github.com/timnolte))*
- [`272e452`](https://github.com/forumone/wp-cfm/commit/272e452721b7f57542fd9a4637b9bf25b84d81cb) - **PHPStan**: Fixes PHPStan linting errors in security patch *(PR [#172](https://github.com/forumone/wp-cfm/pull/172) by [@timnolte](https://github.com/timnolte))*
- [`5d3b9e8`](https://github.com/forumone/wp-cfm/commit/5d3b9e846baa51d9119c3f196acd23cbe57ce14e) - **README**: Updates WordPress version compatibility *(PR [#173](https://github.com/forumone/wp-cfm/pull/173) by [@timnolte](https://github.com/timnolte))*


## 1.7.8 - 20230-10-31
### :bug: Bug Fixes
- [`4e13f08`](https://github.com/forumone/wp-cfm/commit/4e13f08df6419656742e25f89f73dc9af826aeb5) - **deploy**: Fixes GHA for WP.org Deployments *(PR #167 by @timnolte)*

### :wrench: Chores
- [`9ae1ae3`](https://github.com/forumone/wp-cfm/commit/9ae1ae31872e748d6aefd2c9489ca0a2ddf67cbc) - **release**: Updates and builds for release 1.7.8 *(commit by @f1builder)*

## 1.7.7 - 2023-10-31
### :bug: Bug Fixes
- [`0c26ca5`](https://github.com/forumone/wp-cfm/commit/0c26ca566cc07e11f490f1bd7ee58c9ed714a1c3) - **ci**: Trigger New WordPress.org Release *(PR #164 by @timnolte)*

### :wrench: Chores
- [`b59eea4`](https://github.com/forumone/wp-cfm/commit/b59eea4b23bef692fa9bac88e010cfc98700543f) - **ci**: Updates Deployment Workflow for Manual & Automated Deployments to WP.org *(commit by @timnolte)*
- [`e25a5ab`](https://github.com/forumone/wp-cfm/commit/e25a5abc6058a8fe4e8f839630596cc4e65f6e7d) - **release**: Updates and builds for release 1.7.7 *(commit by @f1builder)*

## 1.7.6 - 2023-10-31
### :bug: Bug Fixes
- [`de3cac5`](https://github.com/forumone/wp-cfm/commit/de3cac52607d7879b07e62a42bac62dc7925e98a) - **readwrite**: Add check to skip pulling a bundle if there was no matching bundle file *(PR [#132](https://github.com/forumone/wp-cfm/pull/132) by [@jessedyck](https://github.com/jessedyck))*
- [`e6fd2c7`](https://github.com/forumone/wp-cfm/commit/e6fd2c7e14041a9c0d79d850f31d25d593cafae4) - **core**: Swap instances of filter_input for sanitize_text_field *(PR [#136](https://github.com/forumone/wp-cfm/pull/136) by [@gbeezus](https://github.com/gbeezus))*
- [`d5a6122`](https://github.com/forumone/wp-cfm/commit/d5a6122cc1661268eadcf076e5f71809529d60b4) - **PHP**: Fixes PHP related deprecation notices & build bugs *(PR [#146](https://github.com/forumone/wp-cfm/pull/146) by [@timnolte](https://github.com/timnolte))*
  - :arrow_lower_right: *fixes issue [#135](undefined) opened by [@gbeezus](https://github.com/gbeezus)*
  - :arrow_lower_right: *fixes issue [#147](undefined) opened by [@timnolte](https://github.com/timnolte)*

### :wrench: Chores
- [`49b2694`](https://github.com/forumone/wp-cfm/commit/49b269405aa51b536a891a55aa672434be60a0c9) - **ci**: Adds Build/Release Workflows & Updates for Automation *(commit by [@timnolte](https://github.com/timnolte))*
- [`e7cccf9`](https://github.com/forumone/wp-cfm/commit/e7cccf9bfb93f532274a50d62e53f72870a51458) - **linting**: Adds Linting Support & Sets a Baseline *(PR [#145](https://github.com/forumone/wp-cfm/pull/145) by [@timnolte](https://github.com/timnolte))*
- [`d41216b`](https://github.com/forumone/wp-cfm/commit/d41216b2c39d9fcaee4ad791f067537fc5582221) - **ci**: Fixes Release Build SemVer Changes Lookup *(PR [#150](https://github.com/forumone/wp-cfm/pull/150) by [@timnolte](https://github.com/timnolte))*
- [`f81429d`](https://github.com/forumone/wp-cfm/commit/f81429d365e6409b59b67ef0b5a5d4bf9548bb45) - **ci**: Fixes New Release Changelog Generation GitHub Actions Step *(PR [#152](https://github.com/forumone/wp-cfm/pull/152) by [@timnolte](https://github.com/timnolte))*
- [`711fba6`](https://github.com/forumone/wp-cfm/commit/711fba63773f8f18f4891d05999fc497326fcbb0) - **ci**: Fixes new Release Build Workflow to Update the readme.txt *(PR [#154](https://github.com/forumone/wp-cfm/pull/154) by [@timnolte](https://github.com/timnolte))*
- [`769ad58`](https://github.com/forumone/wp-cfm/commit/769ad58be7487d684ed5c11a4067418668042e30) - **ci**: Fixes Missing Quotes During Temp Changelog Output *(PR [#156](https://github.com/forumone/wp-cfm/pull/156) by [@timnolte](https://github.com/timnolte))*
- [`d605935`](https://github.com/forumone/wp-cfm/commit/d605935e48ae0d768d2ada1493bc9924d4c4009e) - **ci**: Forces Use of Release Branch for New Release Commit *(PR [#158](https://github.com/forumone/wp-cfm/pull/158) by [@timnolte](https://github.com/timnolte))*
- [`1993231`](https://github.com/forumone/wp-cfm/commit/199323147701dded5a28cb4f50dff920617bbf99) - **ci**: Changes the GitHub Action Used for Release Commits/Pushes *(PR [#160](https://github.com/forumone/wp-cfm/pull/160) by [@timnolte](https://github.com/timnolte))*


## 1.7.5 - 09-08-2022
* Fix: Tested with latest WordPress release v6.0.2. Removing the plugin outdated notice at WordPress.org plugin repository.

## 1.7.4 - 01-24-2022
* Fix: Pantheon Quick Silver hooks silently failing to pull updated configuration.

## 1.7.3 - 12-01-2021
* Fix: PHP notice "WP_Scripts::localize was called incorrectly". The $l10n parameter must be an array.

## 1.7.2 - 02-16-2021
* Improved: Fix Warning invalid argument supplied on CLI command.

## 1.7.1 - 02-15-2021
* Improved: Cache results of WPCFM_Registry::get_configuration_items() (props @fariasf).
* New: Added `--format` parameter for the bundles command to retrieve bundle information from the CLI (props @gilzow).
* Improved: Use `home_url()` instead of `WP_HOME` since this one isn't always guaranteed to be set (props @kyletaylored).
* Fix: Warning invalid argument supplied for foreach() on CLI command (props @adnoh).

## 1.6 - 08-29-2019
* Improved: Following WordPress best practices. Including CSS and JS scripts for plugin admin page using `admin_enqueue_scripts` action.
* New: Filters `wpcfm_multi_env` and `wpcfm_current_env` to activate multiple environments functionality.
* New: Detects Pantheon hosting automatically and activates the multiple environments functionality. Registers out of the box `dev`, `test` and `live` for Pantheon.
* New: Filter `wpcfm_is_ssot` adds capability to set configuration bundles as the Single Source of Truth (SSOT), rather than the database, for all tracked options.

## 1.5.1 - 06-07-2019
* Fix: Tested with latest WordPress 5.2.1. Removing the plugin outdated notice at WordPress.org plugin repository.

## 1.5
* New: Toggle to show/hide already registered options (props @mickaelperrin)
* New: `wpcfm_config_format` filter allow export configuration as YAML files (props @mickaelperrin)
* New: Check configuration file exist before import (props @mickaelperrin)
* Fix: File bundle properties are not checked (props @mickaelperrin)
* Fix: Import wp-cfm settings (props @mickaelperrin)
* Fix: Bad PHP Version comparison (props @mickaelperrin)
* Fix: Undefined constant WPCFM_CONFIG_FORMAT_REQUESTED (props @mickaelperrin)
* Improved: Better Custom Field Suite plugin detection (props @s3ththompson)

## 1.4.5
* Fix: only the first taxonomy was showing in the admin UI (props @Rebenton)

## 1.4.4
* New: `wpcfm_config_dir` filter (customize config dir)
* New: `wpcfm_config_url` filter (customize config url)
* Fix: issue with .dot files in config directory
* Fix: PHP7 warning

## 1.4.3
* Fixed: WP-CLI diff (props @mortana42)

## 1.4.2
* Fixed: pulls broken due to taxonomy bug
* Fixed: Custom Field Suite support
* Improved: code formatting

## 1.4.1
* Wrapped CFS integration into a class
* Removed unnecessary diff code
* Code formatting tweaks

## 1.4
* New: support for taxonomy terms
* Improved: better usability for response messages
* Improved: replaced multiselect UI tool
* Fixed: diff viewer highlighting issues
* Added screenshots

## 1.3.2
* wp-cli diff support (props @joshkoenig)
* wp-cli show bundles support (props @joshkoenig)
* wp-cli get bundle details (props @joshkoenig)

## 1.3.1
* Fix for bundle deletion
* Better WP-CLI network support
* Now using "wp_mkdir_p" to check for writable config folder
* Updated translations

## 1.3
* Multisite support (props @alfreddatakillen)
* Added download link for each bundle (props @alfreddatakillen)
* Notification when the same option is stored in multiple bundles
* Subclasses can be accessed as base class variables
* Fix: ensure that "old_value" exists
* Updated translations

## 1.2
* Made "diff viewer" close button appear clickable
* Fixed bug with Custom Field Suite and loop sub-fields

## 1.1
* Added support for a config option label
* Added `get_facet_by_name` helper method
* Admin UI now recognizes file bundles
* Better error handling

## 1.0.5
* Synchronize bundle config options list with file during Pull

## 1.0.4
* Added i18n support
* Fallback for JSON_PRETTY_PRINT when PHP < 5.4
* Fixed PHP notices when doing Pulls
* Excluded some unnecessary CFS config options

## 1.0.3
* Added relative plugin URL (props @tormjens)
* Added subtle admin screen animations
* Better file error handling
* CFS integration - each field group now has its own configuration item
* Added `wpcfm_pull_callback` filter
* Moved the "all" bundle handler from the ajax class to readwrite

## 1.0.2
* Fix: error when Custom Field Suite isn't active

## 1.0.1
* Custom Field Suite integration

[release]: https://github.com/forumone/wp-cfm/compare/1.7.5...release

[release]: https://github.com/forumone/wp-cfm/compare/1.7.8...release