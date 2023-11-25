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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'demo28');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '%KQAIj^Qv!8`+F~2tY_WU!aTqb-V6hT_i0zfJ/:klXd.)Tr*D>Sj>!}BcP[.I9W8');
define('SECURE_AUTH_KEY',  '~*blsAq3>>rerd@5)RERHRwb+|VCg(,d7|X/bJpV%m6/vxuy$Q&E^6sB#)e2zg:?');
define('LOGGED_IN_KEY',    '[7.;e<p#k=X%2j{N7]D.d|NcG^c3$tf;(sUkAPP3Hn?Ldu?cz)i-SKT%i$o2UM<3');
define('NONCE_KEY',        '*!vTq`F}EO(Hy/>DT%&YwD(3=weA7T|*ScC2[4V]cz} HYc8)TpPsV5~wj{N-eaU');
define('AUTH_SALT',        ';)PF[%?L[Ia<8VufBrq~4tMc.Z<xc0s!nD#VDTgaR}$h.4dQ`/{t0xWlQi`M6Wyc');
define('SECURE_AUTH_SALT', 'Uv Hoc6aG83FI)xMZ,-.M%x<!eJnfYK3j{J)LF_LJ7Kmjsq4Bp,$oi|<KN#Omsf;');
define('LOGGED_IN_SALT',   'PZ7k&ciRAnIzPs4ZHvtZ@3jYw%j6<iTVMEFQ`R,)nX{Q2}GO@wV.G/i&jsnX<g@4');
define('NONCE_SALT',       'wuS5(,)Le!-yiouNIbAPCm5B/`&X.k gIrQyb1D-ygKe}1mm.tarz*3KEuhp-j?u');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
