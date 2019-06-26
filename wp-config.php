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
define( 'DB_NAME', 'transparencia' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'password' );

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
define( 'AUTH_KEY',         'qYW`#rml6KE2U8K_8^K>Pf+`oX4];Z<C8[dBJ[p2 uSl~VT?.c_N;JyjKvvxrKhj' );
define( 'SECURE_AUTH_KEY',  '9Sz_VjiARkZs{)e)bvK)QY/%3/x<c^y%t/ >i+OFq Se;Eh~#CsZ`s4MB:REa-92' );
define( 'LOGGED_IN_KEY',    '*DQv:[diAf/P,EM:&AZik/N*_H-OU-3#4W41?ev8ebSY(o0X,&= 9J}@^JtG8]y4' );
define( 'NONCE_KEY',        'yCoYE1B;Bl6LO5br[kP@H0?>$ wjP)w?lO3I}SMNnp9Lf]Nf<>yG=jzp]vVTF.w:' );
define( 'AUTH_SALT',        'ykk:8|y/HP5PzT:()6;7}dB59[/g|tJpfsYOH;Pc UTUbP5$TQ-//)Ky+QOK7LDY' );
define( 'SECURE_AUTH_SALT', 'Ua;F Qr%NlSxLOa65M1+<o{AYeM|KM,G?Ic0zoz|~aR]2*j@:[x>@x`PhC!$u3Cn' );
define( 'LOGGED_IN_SALT',   'Obmp~T,VE,7~9|rG1FGbS,<MrOq3#?.gb.Rp!.jb|kwmsv{XKRW`Xnk.)_23qy:/' );
define( 'NONCE_SALT',       'D/,UlSVlP)Q9$6!IJ}BrEBwvLxnk:xov5%Yx&R?@]w4rj/A%%P)(3LBhz6|!RyB2' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'tr_';

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
define( 'WP_DEBUG', false );
//define( 'WP_DEBUG', true );
//define( 'WP_DEBUG_LOG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

define (‘WPLANG’, ‘wp-5.1.x-es-ar’);