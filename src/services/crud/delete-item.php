<?php
    require_once(__DIR__ . "/../../../assets/scripts/config.php");
    require_once(BASE_PATH . "src/services/start-session.php");


// TUTORIAL USED: https://www.youtube.com/watch?v=NqP0-UkIQS4

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $table = $_SESSION['selectedTable'];

    $idMap = [
        "customer" => "customer_id",
        "items" => "item_id",
        "transactions" => "transaction_id",
    ];

    if (!array_key_exists($table, $idMap)) {
        $_SESSION['errors'] = ["Unknown table for deletion."];
        header("Location: " . $_SESSION['selectedTablePage']);
        exit();
    }

    $idField = $idMap[$table];

    if (!isset($_POST[$idField])) {
        $_SESSION['errors'] = ["No ID provided for deletion."];
        header("Location: " . $_SESSION['selectedTablePage']);
        exit();
    }

    $idValue = intval($_POST[$idField]); 

    $sql = "DELETE FROM $table WHERE $idField = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idValue);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['Success'] = ["Record deleted successfully"];
    } else {
        $_SESSION['errors'] = ["Error deleting record: " . mysqli_error($conn)];
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header("Location: " . $_SESSION['selectedTablePage']);
    exit();

} else {
    $_SESSION['errors'] = ["Invalid request method."];
    header("Location: " . $_SESSION['selectedTablePage']);
    exit();
}
?>
