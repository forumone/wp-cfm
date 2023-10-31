<?php
/**
 * Phpstan bootstrap file.
 *
 * @category  General
 * @package   WPCFM
 * @author    Forum One <wordpress@forumone.com>
 * @copyright 2016 Forum One
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link      https://github.com/forumone
 */

// Define whether running under WP-CLI.
defined( 'WP_CLI' ) || define( 'WP_CLI', false );

// Define WordPress language directory.
defined( 'WP_LANG_DIR' ) || define( 'WP_LANG_DIR', 'languages/' );

defined( 'COOKIE_DOMAIN' ) || define( 'COOKIE_DOMAIN', 'localhost' );
defined( 'COOKIEPATH' ) || define( 'COOKIEPATH', '/');

// Define Plugin Globals.
defined( 'PANTHEON_ENVIRONMENT' ) || define( 'PANTHEON_ENVIRONMENT', 'test' );

