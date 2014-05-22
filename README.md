#### WP-CFM: Configuration Management for WordPress

Deploying database changes in WordPress is hard, especially when working on teams with multiple developers. This project aims at solving this problem by storing database configuration in the filesystem. It's like Drupal's "Features" module for WordPress.

#### What does this mean for me?

* There will be less need to copy the database. If you make changes, **Push** your bundle to the filesystem. To load changes, **Pull** the bundle into your database.
* No need to manually apply database settings changes. No more "fire drills" where you're rushing to figure out which settings you forgot to change.

#### Terminology

**Bundle**: A group of settings that you want to track. This could be a single setting, or the entirety of your site.

**Push**: Export configuration from your database to the filesystem.

**Pull**: Import configuration from the filesystem into your database.
