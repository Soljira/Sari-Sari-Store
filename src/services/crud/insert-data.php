<?php
    require_once(__DIR__ . "/../../../assets/scripts/config.php");
    require_once(BASE_PATH . "src/services/start-session.php");

    /*
    * 1. Get the current table /
    * 2. Get all the columns from the prev form /
    * 3. Get the auto-incremented ID from the form (changing disabled to readonly will send the data) /
    * 4. Compile all data from the previous page /
    * 5. Insert the compiled data into database /
    * 6. Redirect to the previous table page /
    */    

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newItemSubmit'])) {
        // echo "Selected Table: " . $_SESSION['selectedTable'] . "<br>";  // This is set in create.php in switch statements (line 46)
        $selectedTable = $_SESSION['selectedTable'];  // 1. Get the current table 
    }

        // Since this file doesn't exactly know which table it's pulling the data from unless the user submitted create.php, use arrays instead
        // this will initialize the arrays because array_push wont work if it didn't
        $columnArrayFields = array();
        $columnArrayValues = array();

        // 2. Retrieves data from user input
        // 3. Get the auto-incremented ID from the form (changing disabled to readonly will send the data)
        // remove created_at
        $sql = "SHOW COLUMNS FROM $selectedTable";
        
        $result = mysqli_query($conn, $sql);    
        while ($row = mysqli_fetch_assoc($result)) {
            $columnName = $row['Field'];
            // This will remove the createdAt input field because createdAt is purely for keeping integrity
            // MySQL will take care of the createdAt field even without specifying it in INSERT INTO so dont worry            
            if ($columnName != "created_at") {
                $$columnName = $_POST[$columnName]; 

                array_push($columnArrayFields, $columnName);
                array_push($columnArrayValues, $$columnName);

                // Debugging print statement
                // echo $columnName . ": " .  $$columnName . "<br>";                
            }            
        }

        // combine all strings from the foreach into one variable and just insert it to the parameter because i cant use a foreach loop in the insert into sql statement        
        $combinedColumns = "";
        // skip the first element because its usually an id, and mysql takes care of auto-incrementing it
        for ($x = 1; $x < count($columnArrayFields); $x++) {
            if (empty($combinedColumns)) {
                $combinedColumns = $combinedColumns . $columnArrayFields[$x];
            } else {
                $combinedColumns = $combinedColumns . "," . $columnArrayFields[$x];
            }            
        }

        // TODO: Filter and sanitize inputs because mysql gives an error when you type \ 
        // echo "Columns: " . $combinedColumns . "<br>";
        $combinedValues = "";
        for ($y = 1; $y < count($columnArrayValues); $y++) {
            if (empty($combinedValues)) {
                $combinedValues = "'" . $columnArrayValues[$y] . "'";
            } else {
                $combinedValues = $combinedValues . ", '" . $columnArrayValues[$y] . "'";
            }            
        }
        
        // echo "Values: " . $combinedValues . "<br>";
        // echo $selectedTable($combinedColumns);
        
        /*
         *  MAYBE MODULARIZE THE CODE ABOVE BC ITS JUST GETTING DATA FROM THE PREV PAGE
         */

        // TODO: Error handling for duplicate values (such as username)

        // 5. Insert the compiled data into database
        $sql = "INSERT INTO $selectedTable($combinedColumns) VALUES ($combinedValues)";

        // 6. Redirect to the previous table page 
        if (mysqli_query($conn, $sql)) {
            $_SESSION['Success'] = ["New record created successfully"];
            header("Location: " . $_SESSION['selectedTablePage']);  // The set variable in create.php line 68 (there surely must be a better way to handle page redirects)
        } else {
            $_SESSION['errors'] = ["Invalid data"];
            header("Location: " . $_SESSION['selectedTablePage']);
            // echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        $sql = "ANALYZE TABLE $selectedTable";  // i need this so the auto-incremented keys can actually update
        mysqli_query($conn, $sql);
        mysqli_close($conn);
        exit();
?>