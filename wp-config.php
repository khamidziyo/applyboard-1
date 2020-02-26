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
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         '-na2hNk!~:~:&`W0Skbe{ZLdXLAEF@I<>cq2:L;/02HU&$_4O0b<=t?qcnqVr8>F' );
define( 'SECURE_AUTH_KEY',  '-}D2W|< @($j*?V;e=L(Th[v1v!isb?q0LtP&iOiU]>P]S,<RgYI ~}-&m~kh+{o' );
define( 'LOGGED_IN_KEY',    '&$JSY/5p^S<nx B=yzCJI~,}~B+9n*e+auGOc19%7}DqA}=UYV$>P!RCrm34nLGf' );
define( 'NONCE_KEY',        'WpBmKj7o1Jz0Loc!*jm~VY$$|QG9`1ip=hUz7I_^2Sm*eTfZvu)m9!8T.Eo5:l?>' );
define( 'AUTH_SALT',        ':#AL=LvG=#,S#Z7$?>%Xc;Oi!R>{8t7xc(>8)IfS>O^iq$s~fHb>WIr_s[Z9%S^k' );
define( 'SECURE_AUTH_SALT', 'a=>^KfC&4u,6P67Ygak44K@ 9lPXBT~j+CG9bY>!]9?rUZ0u`XdNsa*jC.!JB,uy' );
define( 'LOGGED_IN_SALT',   '}J*B2qaU2A?>t^*^z|GKK>#<`fi#=bm;@8a<]Z_xLFF+l<nf){klbVSig,;LDSxX' );
define( 'NONCE_SALT',       'T3(c#?^8]f$HM6Fm8~qslFN1w:>{!KUBT-rjc~BGns]>B{K9ZQx/.2: O;vRi0lw' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}


/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

define('FS_METHOD', 'direct');
