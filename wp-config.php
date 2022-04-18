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

define('FS_METHOD', 'direct');


// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'plantaspatraldocom' );

/** MySQL database username */
define( 'DB_USER', 'plantaspatraldocom' );

/** MySQL database password */
define( 'DB_PASSWORD', 'admin' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY',         'O*uO2I-J^ddgE?T<|ECFi#<2c^<ki{a3SOn#W;+f6T:w,ob-HS@Rpw6mFe=4X>i(');
define('SECURE_AUTH_KEY',  '!fl?o&`dc1%O;9wFt@kH{T1+Cyz5t,Uz,]iFkQOe!p+Tqy f-PanHhZ6-c<h[aG+');
define('LOGGED_IN_KEY',    'l3)7;13s]-}|w_I5(|m-hSq,<^1;UecgVv_1:2?!P,f,-yJDn28Pr]RnC|9Go?I>');
define('NONCE_KEY',        'wl#3kg%Lt7<Iz`%V1+|ljmJ=Q&qkDjAE0c(UxX?TjMDO=64?cjn!CW3Sr*?W[ZGt');
define('AUTH_SALT',        '#zM/dLznJ.yc+~r_6%a7hBKQy@Zx$HwmE,z0y[Q5y_.~|n ~C#ba+sF7Q$P`e0Ev');
define('SECURE_AUTH_SALT', 'N0ucg8]nha!f<LWExujLvfE?[Nqb-|hTzJ^]=`8Wj uD}`eqM~mXqW91lo{d+U|4');
define('LOGGED_IN_SALT',   'yNoHG|}1oV/~a&64l_J`ef@a~>*_#$6@8XXH}Y{IT-b92-zt/-RaTh`Tm?7A.x|h');
define('NONCE_SALT',       '?Z}=hOE6}FQ3pJ)g?AhSdfXXKBvk<{f[lq@ _X](YV~i}LH-H}b*{nhXW2~trfbW');
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
define( 'WP_DEBUG', true);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
