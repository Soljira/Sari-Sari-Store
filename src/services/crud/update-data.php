<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once(__DIR__ . "/../../../assets/scripts/config.php");
    require_once(BASE_PATH . "src/services/start-session.php");

    // TODO: Merge insert-data and update-data
    // just use isset($_POST['newItemSubmit']) and isset($_POST['editItemSubmit'])

    /*
     * 1. Get the current table /
     * 2. Get all the columns from the prev form /
     * 3. Get the auto-incremented ID from the form (changing disabled to readonly will send the data) /
     * 4. Compile all data from the previous page /
     * 5. Insert the compiled data into database /
     * 6. Redirect to the previous table page /
     */
    if (isset($_POST['editItemSubmit'])) {
        $selectedTable = $_SESSION['selectedTable'];

        // Build update query from $_POST data
        $idField = ''; 
        $idValue = '';

        $updates = [];
        foreach ($_POST as $column => $value) {
            if ($column == 'editItemSubmit') continue; // skip the button since $POST will also get the button name

             // this will store the primary key value to be used for the query later
            //  also prevents table ids and createdAt columns from being altered
            if (stripos($column, '_id') !== false) {
                $idField = $column;
                $idValue = $value;
                continue;
            }
            
            //  prevents createdAt columns from being altered
            if ($column == 'created_at') {
                continue; // skip createdAt
            }

            $updates[] = "$column = '" . mysqli_real_escape_string($conn, $value) . "'";
        }  // end of foreach block
        
        // For sql formatting purpose
        // It should look like this: column1 = value1, column2 = value2, ...
        $updatesString = implode(", ", $updates);

        $sql = "UPDATE $selectedTable 
                SET $updatesString 
                WHERE $idField = '$idValue'";

        // 6. Redirect to the previous table page 
        if (mysqli_query($conn, $sql)) {
            $_SESSION['Success'] = ["New record created successfully"];
            header("Location: " . $_SESSION['selectedTablePage']);
        } else {
            $_SESSION['errors'] = ["Invalid data"];
            header("Location: " . $_SESSION['selectedTablePage']);
            echo "Error updating record: " . mysqli_error($conn);
        }
        
        $sql = "ANALYZE TABLE $selectedTable";
        mysqli_query($conn, $sql);
        mysqli_close($conn);
        exit();
    }

    // TODO: move insert-data.php here
    if (isset($_POST['newItemSubmit'])) {
    }
?>