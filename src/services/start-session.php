<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once(__DIR__ . "/db-connect.php");   // __DIR__ means current directory

    $errors = [];
    if (!empty($_SESSION['errors'])) {
        $errors = $_SESSION['errors'];
        unset($_SESSION['errors']);
    }

    $success = [];
    if (!empty($_SESSION['success'])) {
        $success = $_SESSION['success'];
        unset($_SESSION['success']);
    }
?>