<?php  // Moodle configuration file (env-driven, no secrets committed)

unset($CFG);
global $CFG;
$CFG = new stdClass();

function env($name, $default = null) {
    $val = getenv($name);
    return ($val === false || $val === '') ? $default : $val;
}

// === MariaDB/MySQL DB ===
$CFG->dbtype    = 'mariadb';     // or 'mysqli'
$CFG->dblibrary = 'native';

$CFG->dbhost    = env('DB_HOST', 'mariadb');
$CFG->dbname    = env('DB_NAME', 'moodle');
$CFG->dbuser    = env('DB_USER', 'moodle_user');
$CFG->dbpass    = env('DB_PASS', '');

$CFG->prefix    = env('DB_PREFIX', 'mdl_');

$CFG->dboptions = array(
    'dbpersist'   => 0,
    'dbport'      => (int) env('DB_PORT', 3306),
    'dbsocket'    => '',
    'dbcollation' => 'utf8mb4_unicode_ci',
);

// URLs & paths
$CFG->wwwroot   = env('MOODLE_WWWROOT', 'http://localhost:8080');
$CFG->dataroot  = env('MOODLE_DATAROOT', '/var/moodledata');
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

$sslproxy = env('MOODLE_SSLPROXY', '0');
$CFG->sslproxy = ($sslproxy === '1' || strtolower($sslproxy) === 'true');

$cookiesecure = env('MOODLE_COOKIESECURE', '0');
$CFG->cookiesecure = ($cookiesecure === '1' || strtolower($cookiesecure) === 'true');

$CFG->debug = E_ALL | E_STRICT;
$CFG->debugdisplay = true;
$CFG->dblogerror = true;

require_once(__DIR__ . '/lib/setup.php');
