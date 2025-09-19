<?php
// Uncommenting echo will mess up login.php
// echo "Starting db-connect.php" . "\n";

// Get database configuration from environment variables
// I DONT HAVE AN .ENV FILE
// REMINDER: change password to whatever the host's db password is. may require you to remove it entirely.
$host = getenv('DB_HOST') ?: 'db';  // or localhost
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: 'password';
$db   = getenv('DB_NAME') ?: 'sari_sari_store';
$port = getenv('DB_PORT') ?: 3306;

// This is not needed anymore FOR NOW
// // TODO: Only accept connection if getenv('ENVIRONMENT') === 'development
// if (empty($pass)) {
//     $errorMsg = "CONFIGURATION ERROR: DB_PASSWORD not found. ";
//     if (!$envLoaded) {
//         $errorMsg .= "Could not locate .env file. Please ensure .env file exists in project root.";
//     } else {
//         $errorMsg .= "Please check your .env file contains DB_PASSWORD.";
//     }
//     die($errorMsg);
// }

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
}
?>