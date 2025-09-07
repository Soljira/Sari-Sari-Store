<?php
    session_start();
    require_once(__DIR__ . "/db-connect.php");   // __DIR__ means current directory

    $errors = [];
    if (!empty($_SESSION['errors'])) {
        $errors = $_SESSION['errors'];
        unset($_SESSION['errors']);
    }

    $success = [];
    if (!empty($_SESSION['success'])) {
        $errors = $_SESSION['success'];
        unset($_SESSION['success']);
    }
?>