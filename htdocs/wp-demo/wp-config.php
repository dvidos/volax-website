<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'forthnet_volax_gr' );

/** MySQL database username */
define( 'DB_USER', 'dimitris' );

/** MySQL database password */
define( 'DB_PASSWORD', 'svdk!!49' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'RJ=(2oQ)EMQ1IRJ)P3WIFQg)fbQI!k9%yBP{atIaI,fDQ&v1i|LOqTt,biUVv3g#' );
define( 'SECURE_AUTH_KEY',  'i4uFCn )(FFk@?}z1E551& #fW|Ydla49[dfem.NHo`Vwv:A}NW5IA3t!A+23D#/' );
define( 'LOGGED_IN_KEY',    'fMnU19R&:Q!BgFwIij ,`#5fumw^X9j24e@Kfyw=Jx3+b71pce Stj`o!<r]{]j3' );
define( 'NONCE_KEY',        '$,)i+>R4-D&YB~<$Ee/YWZ(T7zdejovi gcY>fiL1TM-d[oJQ-jne:5iU)a]GEZD' );
define( 'AUTH_SALT',        'zm6MY.wOLCPzlhHVAbS|(|GP@zpGiKxb}ATgH;Pi#rwlat|}ib)Z*=Q#.NENf*Zy' );
define( 'SECURE_AUTH_SALT', 'z!4a3&njY-KP1n-4ZzXzN)j.ylqSlfhX:(R%m@u1QCK`_vJxVx*2dK@#JlVqe7?q' );
define( 'LOGGED_IN_SALT',   'T(i*&n35Ie1||v9 xfTa==ITG?7f/gOad09(x7BYN0k=7o>w-{M|o9E f3!n& Q6' );
define( 'NONCE_SALT',       '&9!0o> yUNd?GzWg99EDj=UlipF9,V?S,v+|5>7?S_Y|cG6WY!6;03>)PT^eccXG' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

