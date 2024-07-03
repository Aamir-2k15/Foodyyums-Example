<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '?*y}u~q2{ohC-I^Csl1+ZvKbUG-GYT2cSy#&a<T,a%weK:~&L8@RFuc?{Gg1}Jh#' );
define( 'SECURE_AUTH_KEY',   '1h!MsMm0~TaWu2Cz3x)}5R#sZbFP$lNRx-<9e>52SaMV1qB/2_$[Zy8UK-umFx_$' );
define( 'LOGGED_IN_KEY',     'w]7~m@v.$6G,Q_6%pHTCFOa>$Aj%g.=5z3t-_@1?~Uya#@_or  c[vhAL-7Ycy0Z' );
define( 'NONCE_KEY',         'J&g}kr?E&h!`1}l:wO1tt(.X tKIBnC}]}6x~E[c-ZgIVjj]m[72P#56}r6JAp1d' );
define( 'AUTH_SALT',         '>.R*/1HHHBnaJACyG%gwl[Q>%|0uKwQbScz=!ifx8Ree;|-5R[[2z9L]uM9}EL5s' );
define( 'SECURE_AUTH_SALT',  'RsH15Fo,Y9F;V:wJ5rpYDp4H9EqB6(=Rwy6Gw ;ONieAT{L|_da6d-FBtDqL>YSX' );
define( 'LOGGED_IN_SALT',    '1MfveaNir^t>GxpC@Mo@;cP3uq~kqNF.krb#3fcXf cu?!%=GRX<s$_795abT[Ap' );
define( 'NONCE_SALT',        'Yja.BA,]~qs!uc^)C>whVPI!3nDi6l)cl*AHxGh(}6,nKz_P5??QNoFq0!b_ij/{' );
define( 'WP_CACHE_KEY_SALT', '^^d.2j2#vV5{!oED`O.cxsKEDxC_} ;;`/K;O+@3o-t9fjXG*DVzT;]_vujo`M,(' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
define( 'WP_DEBUG', false );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
