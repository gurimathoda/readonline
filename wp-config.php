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
define('DB_NAME', 'poeteywp_read');

/** MySQL database username */
define('DB_USER', 'poeteywp_read');

/** MySQL database password */
define('DB_PASSWORD', 'read$&123');

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
define('AUTH_KEY',         '{GE9gYtL,CDX}J6?$3]+F%?EZ8h/qC+_|Nx9]o_hBFC,Xwxd-Gq>x5B5BC<w}|}w');
define('SECURE_AUTH_KEY',  '_sDbrLI_k7L;CnSlhYR/`*E 56We<Gi9w.jh2ta6;Ypac}Vo3JBmnc/&D3h^Qxj2');
define('LOGGED_IN_KEY',    'oV0Z O )pTX,;dvukD=,1%;&Vn$40 EZh/kLDIXx-l-,*M6~XNygV)$<{R+kCvq_');
define('NONCE_KEY',        '3<B%0*;1)o<rS#C}LbQppwHB7y%rE@bS!tOF=u>w/TM&y#!udEyQ>Dv^cM*,LF=o');
define('AUTH_SALT',        ':rW*+pQN(;H}/tB&yS!A0b8xB86ynUUMnQudR#-lxESJ/v<}$%V9mfMF2gx;I_^z');
define('SECURE_AUTH_SALT', ':f*bBG9Uq]Q?yj?J|f#q{4VdvCu^_ITgE>Mi0ZSO[]#H.i9IFuwj~{x;s1A:0bfI');
define('LOGGED_IN_SALT',   'qaOZ[/I$88&:-NR|x{cMK6r}QCUm#t[dkwjM?]RLTM3xQSo}T}*Gih@t[T$7+b8L');
define('NONCE_SALT',       '[Bgifvji2%n~G=X>PY^@}|ZBA/d_6&OGUJ2hTJPhV5U&6k@/R^i:UdSPr~ICP|qT');

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
