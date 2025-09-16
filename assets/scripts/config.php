<?php
// define('BASE_URL', '/sari-sari-store/');    // for redirect links
// define('BASE_PATH', __DIR__ . '/../../');   // used in reusable components

// Detect the base folder of the project
$projectFolder = '/sari-sari-store'; // adjust this if your project folder has a fixed name

// If running under Docker at root, strip it
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
if (strpos($scriptName, $projectFolder) === false) {
    $baseUrl = '/';
} else {
    $baseUrl = $projectFolder . '/';
}

define('BASE_URL', $baseUrl);
define('BASE_PATH', dirname(__DIR__, 2) . '/');
?>