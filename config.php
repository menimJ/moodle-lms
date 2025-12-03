<?php  // Moodle configuration file (env-driven, no secrets committed)

unset($CFG);
global $CFG;
$CFG = new stdClass();

/**
 * Small helper to read environment variables with a default.
 */
function env($name, $default = null) {
    $val = getenv($name);
    return ($val === false || $val === '') ? $default : $val;
}

/* ======================================================================
 *  DATABASE: MariaDB / MySQL (SkySQL or local)
 * ==================================================================== */

$CFG->dbtype    = 'mysqli';      // mysqli driver (works with MariaDB/MySQL)
$CFG->dblibrary = 'native';

$CFG->dbhost    = env('DB_HOST', 'mariadb');      // e.g. serverless-us-east-2.sysp0000.db1.skysql.com
$CFG->dbname    = env('DB_NAME', 'moodle');       // e.g. moodle
$CFG->dbuser    = env('DB_USER', 'moodle_user');  // e.g. dbpwf03843267
$CFG->dbpass    = env('DB_PASS', '');             // password from env only

$CFG->prefix    = env('DB_PREFIX', 'mdl_');

/**
 * Optional SSL to MariaDB (needed for MariaDB Cloud).
 * Set DB_SSL=1 in Koyeb env to turn this on.
 */
$dbssl = env('DB_SSL', '0');
$clientflags = 0;
if ($dbssl === '1' || strtolower($dbssl) === 'true') {
    if (defined('MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT')) {
        $clientflags = MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT;
    } elseif (defined('MYSQLI_CLIENT_SSL')) {
        $clientflags = MYSQLI_CLIENT_SSL;
    }
}

$CFG->dboptions = array(
    'dbpersist'    => 0,
    'dbport'       => (int) env('DB_PORT', 3306),   // 4000 on SkySQL
    'dbsocket'     => '',
    'dbcollation'  => 'utf8mb4_unicode_ci',
    'dbclientflags'=> $clientflags,
);

/* ======================================================================
 *  WEB URL & PATHS
 * ==================================================================== */

// Public URL of your Moodle site.
// Koyeb: https://dselevura-academy.koyeb.app/moodle
// Local: http://localhost:8080/moodle
$CFG->wwwroot = rtrim(env('MOODLE_WWWROOT', 'http://localhost:8080/moodle'), '/');

// Moodle data directory (must be writable, not in web root)
$CFG->dataroot = env('MOODLE_DATAROOT', '/var/moodledata');

// Admin URL path (leave as "admin" unless you purposely change it)
$CFG->admin = 'admin';

// File / directory permissions for new files
$CFG->directorypermissions = 0777;

/* ======================================================================
 *  PROXY / HTTPS
 * ==================================================================== */

$CFG->reverseproxy = true;  // Koyeb sits in front, so treat as reverse proxy

$sslproxy = env('MOODLE_SSLPROXY', '1');
$CFG->sslproxy = ($sslproxy === '1' || strtolower($sslproxy) === 'true');

$cookiesecure = env('MOODLE_COOKIESECURE', '1');
$CFG->cookiesecure = ($cookiesecure === '1' || strtolower($cookiesecure) === 'true');

/* ======================================================================
 *  DEBUGGING (you can turn these off in production)
 * ==================================================================== */

$CFG->debug       = E_ALL | E_STRICT;
$CFG->debugdisplay = true;
$CFG->dblogerror   = true;

/* ======================================================================
 *  BOOTSTRAP MOODLE
 * ==================================================================== */

require_once(__DIR__ . '/lib/setup.php');
