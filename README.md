#### WP-CFM: Configuration Management for WordPress

[Download on WordPress.org](http://wordpress.org/plugins/wp-cfm/)

Deploying database changes in WordPress is hard, especially when working on teams with multiple developers. This project aims at solving this problem by storing database configuration in the filesystem. It's like Drupal's "Features" module for WordPress.

![Admin Screen](http://i.imgur.com/opQhDUa.png)

[Watch the introduction screencast (4 minutes)](http://screencast.com/t/HGmkd8S44P7s)

#### What does this mean for me?

* Less need to copy the database. If you make changes, **Push** your bundle to the filesystem. To load changes, **Pull** the bundle into your database.
* No need to manually apply database settings changes. No more "fire drills" where you're rushing to figure out which settings you forgot to change.
* Track and migrate configuration files using git, subversion, etc.

#### Terminology

* **Bundle**: A group of settings to track. This could be a single setting, or all the site's available settings.
* **Push**: Export configuration from your database to the filesystem.
* **Pull**: Import configuration from the filesystem into your database.

#### Developer Hooks

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

#### Is that it?

Almost! WP-CFM automatically handles configuration within the `wp_options` table, and `wp_options` for multisite network-wide site options. If your plugin stores settings elsewhere, then use the above `callback` parameter to tell WP-CFM how to properly import (Pull) configuration into the database.

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

#### WP-CLI

WP-CFM supports pulling / pushing bundles from the command-line using [WP-CLI](http://wp-cli.org/):

```php
wp config pull <bundle_name>
wp config push <bundle_name>
```

You can optionally set `bundle_name` to "all" to push / pull all bundles at once.

To pull multisite site options, append --network to the arguments:

```php
wp config pull <bundle_name> --network
wp config push <bundle_name> --network
```


