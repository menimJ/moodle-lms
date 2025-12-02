<?php  // Moodle configuration file (env-driven, no secrets committed)

unset($CFG);
global $CFG;
$CFG = new stdClass();

// Helper to read env vars with defaults
function env($name, $default = null) {
    $val = getenv($name);
    return ($val === false || $val === '') ? $default : $val;
}

// === Database (from environment) ===
$CFG->dbtype    = 'pgsql';
$CFG->dblibrary = 'native';

$CFG->dbhost    = env('DB_HOST', 'db');
$CFG->dbname    = env('DB_NAME', 'moodle');
$CFG->dbuser    = env('DB_USER', 'moodle');
$CFG->dbpass    = env('DB_PASS', '');      // password comes only from env

$CFG->prefix    = env('DB_PREFIX', 'mdl_');

$CFG->dboptions = array(
    'dbpersist'        => 0,
    'dbport'           => (int) env('DB_PORT', 5432),
    'dbsocket'         => '',
    'dbhandlesoptions' => false,
    // Neon enforces SSL; if you ever need special flags you can add here
);

// === URLs & paths (from environment) ===
// Public URL of the site
$CFG->wwwroot   = env('MOODLE_WWWROOT', 'http://localhost:8080/moodle');

// Data directory inside the container
$CFG->dataroot  = env('MOODLE_DATAROOT', '/var/moodledata');

// Admin path (rarely changed)
$CFG->admin     = 'admin';

// File/directory permissions
$CFG->directorypermissions = 0777;

// HTTPS / proxy settings
$sslproxy = env('MOODLE_SSLPROXY', '0');
$CFG->sslproxy = ($sslproxy === '1' || strtolower($sslproxy) === 'true');

$cookiesecure = env('MOODLE_COOKIESECURE', '0');
$CFG->cookiesecure = ($cookiesecure === '1' || strtolower($cookiesecure) === 'true');

require_once(__DIR__ . '/lib/setup.php');
