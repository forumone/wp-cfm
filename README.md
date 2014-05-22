#### WP-CFM: Configuration Management for WordPress

Deploying database changes in WordPress is hard, especially when working on teams with multiple developers. This project aims at solving this problem by storing database configuration in the filesystem. It's like Drupal's "Features" module for WordPress.

![Admin Screen](http://i.imgur.com/VkYVoSW.png)

#### What does this mean for me?

* Less need to copy the database. If you make changes, **Push** your bundle to the filesystem. To load changes, **Pull** the bundle into your database.
* No need to manually apply database settings changes. No more "fire drills" where you're rushing to figure out which settings you forgot to change.

#### Terminology

**Bundle**: A group of settings that you want to track. This could be a single setting, or the entirety of your site.

**Push**: Export configuration from your database to the filesystem.

**Pull**: Import configuration from the filesystem into your database.

#### Developer Hooks

* **wpcfm_namespaces** - Register a custom namespace

```php
function my_custom_namespace( $namespaces ) {
    $namespaces['custom_field_suite'] = 'Custom Field Suite';
    return $namespaces;
}
add_filter( 'wpcfm_namespaces', 'my_custom_namespaces' );
```

* **wpcfm_configuration_items** - Add custom configuration settings to the pile

```php
function wpcfm_configuration_items( items ) {
    $items['custom_field_suite']['field_groups'] = 'SOME DATA';
    return items;
}
add_filter( 'wpcfm_configuration_items', 'my_custom_configuration_items' );
```
