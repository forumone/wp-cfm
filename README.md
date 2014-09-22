#### WP-CFM: Configuration Management for WordPress

WP-CFM lets you copy database configuration to / from the filesystem. Easily deploy configuration changes without needing to copy the entire database. WP-CFM is similar to Drupal's [Features](https://www.drupal.org/project/features) module.

![Admin Screen](http://i.imgur.com/opQhDUa.png)

#### How will WP-CFM benefit me?

* Less need to copy over the entire database.
* No more rushing to figure out which settings you forgot to change.
* Easily track and version configuration changes via git, subversion, etc.

#### Terminology

* **Bundle**: A group of (one or more) settings to track.
* **Push**: Export database settings to the filesystem.
* **Pull**: Import file-based settings into the database.

#### How to add custom configuration

The `wpcfm_configuration_items` hook lets you register custom configuration items.

```php
function my_configuration_items( $items ) {
    $items['cfs_field_groups'] = array(
        'value' => 'MY CONFIGURATION DATA',
        'group' => 'WP Options', // optional
        'callback' => 'my_pull_handler', // optional
    );
    return $items;
}
add_filter( 'wpcfm_configuration_items', 'my_configuration_items' );
```

This filter contains an associative array of all configuration options. Each option has a unique key, and supports several parameters:

* **value**: (required) The configuration data to store.
* **group**: (optional) A group name, allowing multiple configuration options to be grouped together. This is only used in the admin UI. Default = "WP Options"
* **callback**: (optional) If the configuration data is **not** stored within `wp_options`, then WP-CFM needs to know how to Pull it into the database. This parameter accepts a (string) function name or (array) method. A `$params` array is passed into the callback function (see below).

```php
/**
 * $params['name']          The option name
 * $params['group']         The option group
 * $params['old_value']     The current DB value that will get overwritten
 * $params['new_value']     The new DB value
 */
function my_pull_handler( $params ) {
    // Save something
}
```

#### Which configuration does WP-CFM support?

Out-of-the-box, WP-CFM supports the `wp_options` table (incl. multisite).

#### WP-CLI

WP-CFM supports pulling / pushing bundles from the command-line using [WP-CLI](http://wp-cli.org/):

```php
wp config pull <bundle_name>
wp config push <bundle_name>
```

You can optionally set `bundle_name` to "all" to include all bundles. Also, append the `--network` flag to include multisite bundles.

### Download

[Download on WordPress.org](http://wordpress.org/plugins/wp-cfm/)
