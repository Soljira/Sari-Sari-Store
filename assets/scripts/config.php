<?php
// define('BASE_URL', '/sari-sari-store/');    // for redirect links
// define('BASE_PATH', __DIR__ . '/../../');   // used in reusable components

// BASE_URL is for the Web URL; used for browser/client-side operations
// BASE_PATH is for the file system path in the server; used for server-side PHP operations

// stores the base folder of the project
$projectFolder = '/sari-sari-store';

// FOR DOCKER url STUFF 
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
if (strpos($scriptName, $projectFolder) === false) {
    $baseUrl = '/';
} else {
    $baseUrl = $projectFolder . '/';
}

define('BASE_URL', $baseUrl);
define('BASE_PATH', dirname(__DIR__, 2) . '/');
?>