<?php
/**
 * This should work regardless if you run this with host php or via docker
 * To run with php in host, type in terminal:
 *  php public/api/db-connect.php
 * To run with docker, type in terminal:
 *  docker exec -it bookstore-php-1 php /var/www/html/api/db-connect.php
 */


// Uncommenting echo will mess up login.php
// echo "Starting db-connect.php" . "\n";

/**
 * Simple .env file loader
 * Looks for .env file going up the directory tree
 * This is for running with host php. You don't need this if you have docker php
 */
// function loadEnvFile() {
//     // Start from current directory and go up
//     $dir = __DIR__;
//     $maxLevels = 5; // Prevent infinite loop
    
//     for ($i = 0; $i < $maxLevels; $i++) {
//         $envFile = $dir . '/.env';
        
//         if (file_exists($envFile)) {
//             $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
//             foreach ($lines as $line) {
//                 // Skip comments
//                 if (strpos($line, '#') === 0) continue;
                
//                 // Parse KEY=VALUE format
//                 if (strpos($line, '=') !== false) {
//                     list($key, $value) = explode('=', $line, 2);
//                     $key = trim($key);
//                     $value = trim($value);
                    
//                     // Remove quotes if present
//                     $value = trim($value, '"\'');
                    
//                     // Set environment variable if not already set
//                     if (!getenv($key)) {
//                         putenv("$key=$value");
//                     }
//                 }
//             }
//             return true; // Found and loaded .env file
//         }
        
//         // Go up one directory
//         $dir = dirname($dir);
        
//         // Stop if we've reached the root
//         if ($dir === dirname($dir)) break;
//     }
    
//     return false; // .env file not found
// }

// // Load .env file
// $envLoaded = loadEnvFile();

// Get database configuration from environment variables
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: 'password';
$db   = getenv('DB_NAME') ?: 'sari_sari_store';
$port = getenv('DB_PORT') ?: 3306;

// Validate that required environment variables are set

// TODO: Only accept connection if getenv('ENVIRONMENT') === 'development
if (empty($pass)) {
    $errorMsg = "CONFIGURATION ERROR: DB_PASSWORD not found. ";
    if (!$envLoaded) {
        $errorMsg .= "Could not locate .env file. Please ensure .env file exists in project root.";
    } else {
        $errorMsg .= "Please check your .env file contains DB_PASSWORD.";
    }
    die($errorMsg);
}

try {
    $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch as assoc arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                  // use native prepares
    ];

    $pdo = new PDO($dsn, 
                $user, 
                $pass, 
                $options);

    // redundant, but i need mysqli for my queries. i havent mastered pdo yet
    $conn = mysqli_connect($host,
                           $user, 
                           $pass, 
                           $db, 
                           $port);
    // echo "Connected successfully\n";  

} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please check your configuration (db-connect.php). <br>Make sure you're connected to the MySQL server.");
}

// Success check
// Uncommenting echo will mess up login.php
if (getenv('ENVIRONMENT') === 'development') {
    // echo "Connected successfully to database: $db" . "<br>";
    // if ($envLoaded) {
    //     echo " (.env file loaded)";
    // }
}
?>