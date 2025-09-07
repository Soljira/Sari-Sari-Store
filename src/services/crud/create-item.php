<?php
    // For bug reports only
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    require_once(__DIR__ . "/../../../assets/scripts/config.php");
    require_once(BASE_PATH . "src/services/start-session.php");
    $_SESSION['selectedTablePage'] = $_SERVER['HTTP_REFERER'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Item</title>
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>assets/images/logo-no-text.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous" defer></script>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/styles/bootstrap-adjustments.css">

</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Tutorial used: https://www.youtube.com/watch?v=NqP0-UkIQS4 -->
    <?php
        include(BASE_PATH . "src/components/header.php");
        include(BASE_PATH . "src/components/navigation.php");
    ?>

    <div class="container mx-auto">

        <main class="mb-5">
            <?php
                $selectedTable = $_SESSION['selectedTable'];

                // This will print the errors from insert-data.php
                foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach;      
                            
                $tables = array("customer","items","transactions");  
                $tableIndex = 0;            
                
                // $_SERVER['HTTP_REFERER'] contains the URL of the page that linked to the current page.
                $_SESSION['selectedTablePage'] = $_SERVER['HTTP_REFERER'];  // This is for referencing the table name for page navigation purposes in insert-data.php

                switch ($selectedTable) {
                    case "customer":
                        $tableIndex = 0;
                        break;
                    case "items":
                        $tableIndex = 1;
                        break;
                    case "transactions":
                        $tableIndex = 2;
                        break;
                }


                // 2. Fetch the data from the database (using MySQLi)
                $sql = "SHOW COLUMNS FROM $selectedTable";
                
                // THIS WILL ONLY FETCH THE TABLE COLUMN NAMES
                // i forgot what this does
                $result = mysqli_query($conn, $sql);    
                while ($row = mysqli_fetch_assoc($result)) {
                    $columnName = $row['Field'];
                    // foreach ($columnName as $value) {
                    //     echo $value;

                    // }
                    // echo $columnName;
                    $$columnName = null;

                    // if (isset($_POST[$columnName])) {
                    //     $$columnName = $_POST[$columnName];  // $$ is a variable variable
                    //     // echo "test";
                    // } else {
                    //     // echo "test";

                    //     $$columnName = null;
                    // }
                }

                // Resets $row DO NOT COMMENT THIS OUT BECAUSE TEXT INPUTS WONT WORK
                $result = mysqli_query($conn, $sql); 
            ?>

            <div class="text-center my-3">
                <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (mysqli_num_rows($result) > 0) {
                            switch ($_POST['newItem']) {
                                case "customerNewItem":
                                    ?><h1 class="text-center my-3">New Customer</h1><?php         
                                    break;
                                case "itemNewItem":
                                    ?><h1>New Inventory Item</h1><?php
                                    break;
                                case "transactionNewItem":
                                    ?><h1>New Transaction</h1><?php
                                    break;
                            } // End of switch statements 
                        }
                    }
                ?>
            </div>

            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (mysqli_num_rows($result) > 0) {
                        $autoIncrementQuery = "SHOW TABLE STATUS LIKE '$selectedTable'";
                        $autoIncrementResult = mysqli_query($conn, $autoIncrementQuery);
                        $autoIncrementRow = mysqli_fetch_assoc($autoIncrementResult);
                        $nextID = $autoIncrementRow['Auto_increment'];

                        // Debugging print statements
                        // echo "Selected table " . $selectedTable . "<br>";
                        // echo "Next ID: " . $nextID;

                        // This will print all table columns
            ?>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="newItemForm" method="POST" action="./insert-data.php">
                                            <?php
                                                // INPUT VALIDATIONS HERE
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    // $tableCount = 0;
                                                    // echo $tables[$tableIndex];
                                                    $columnName = htmlspecialchars($row['Field']);
                                                    // $columnName;
                                            ?>
                                                    <div class="row mb-3">
                                                        <label class="col-sm-3 col-form-label">
                                                            <?php 
                                                                // This will remove the createdAt input field because createdAt is purely for keeping integrity
                                                                if ($columnName != "created_at") {
                                                                    echo $columnName;
                                                                }
                                                            ?>
                                                        </label>
                                                        <div class="col-sm-9">
                                                            <?php if ($columnName == "created_at") {
                                                                continue;
                                                            } elseif ($tables[$tableIndex] == "orderItemTable") {   //whats this??>
                                                                <input type="text" class="form-control" 
                                                                    name="<?php echo $columnName; ?>" 
                                                                    value="">                                    
                                                            <?php                                     
                                                            } elseif ($columnName == "transaction_date" || $columnName == "date_added") { ?>
                                                                <input type="date" class="form-control" 
                                                                    name="<?php echo $columnName; ?>" 
                                                                    value="">                                    
                                                            <?php 
                                                            } elseif (stripos($columnName, "_id") !== false) { // TODO: RECHECK THIS AND TRY id LATER?>
                                                                <input type="text" class="form-control" 
                                                                    name="<?php echo $columnName; ?>" 
                                                                    value="<?php echo $nextID; ?>" readonly>                                    
                                                            <?php  // COME BACK TO THIS BECAUSE WTF
                                                            } elseif ($columnName == "stock_quantity" || 
                                                                    $columnName == "customer_id" || 
                                                                    $columnName == "item_id" || 
                                                                    $columnName == "transaction_id" || 
                                                                    $columnName == "quantity") { // WHY TF IS ARRAY_KEY_EXISTS NOT WORKING ?>
                                                                <input type="number" min="0" class="form-control" 
                                                                    name="<?php echo $columnName; ?>" 
                                                                    value="<?php echo $value; ?>">                                    
                                                            <?php  
                                                            } elseif ($columnName == "total_amount" || 
                                                                    $columnName == "price") { ?>
                                                                <input type="number" step="0.01" min="0" class="form-control" 
                                                                    name="<?php echo $columnName; ?>" 
                                                                    value="<?php echo $value; ?>">                                    
                                                            <?php  
                                                            } else { ?>
                                                                <input type="text" class="form-control" 
                                                                    name="<?php echo $columnName; ?>" 
                                                                    value="">
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>

                                            </div>
                                            <div class="row justify-content-end">
                                                <!-- <label class="col-sm-3 col-form-label">Name</label> -->
                                                <div class="col-sm-3 d-grid">
                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-primary" name="newItemSubmit">Submit</button>
                                                        <a href="<?php echo $_SESSION['selectedTablePage']?>" class="btn btn-outline-secondary" role="button">Cancel</a>
                                                        <!-- <input type="text" class="form-control" name="name" value=""> -->
                                                    </div>
                                                    <!-- <input type="text" class="form-control" name="name" value=""> -->
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php                           
                    } else {
                        echo '<tr><td colspan="8">No data found.</td></tr>';
                    }

                } else {
                    echo "Not POST";
                }
            ?>

        </main>
    </div>

    <?php
        include(BASE_PATH . "src/components/footer.php");
    ?>
    
</body>
</html>