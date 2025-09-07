<?php
    // without existing data
    $selectedTable = $_SESSION['selectedTable'];

    // TODO: turn category into a dropdown list if may time

    function generateCreateFields($table, $conn) {
        $dateColumns = array("created_at", "date_added", "transaction_date");
        $intColumns = array("stock_quantity", "quantity");
        $floatColumns = array("price", "total_amount");   
        ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
        <?php
            $sql = "SHOW COLUMNS FROM $table";
            $result = mysqli_query($conn, $sql);    
            
            $autoIncrementQuery = "SHOW TABLE STATUS LIKE '$table'";
            $autoIncrementResult = mysqli_query($conn, $autoIncrementQuery);
            $autoIncrementRow = mysqli_fetch_assoc($autoIncrementResult);
            $nextID = $autoIncrementRow['Auto_increment'];
            ?>
                        <form id="newItemForm" method="POST" action="<?= BASE_URL ?>src/services/crud/insert-data.php">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                // echo htmlspecialchars($row['Field']) . "<br>";
                $columnName = htmlspecialchars($row['Field']);
                // maybe the action should have a variable to specify redirects
                ?>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">
                                    <?php 
                                        // This will remove the created_at input field because created_at is purely for keeping integrity
                                        if ($columnName != "created_at") {
                                            echo $columnName;
                                        }
                                    ?>
                                </label>
                                <div class="col-sm-9">
                                    <?php if ($columnName == "created_at") {
                                        continue;
                                    } elseif (stripos($columnName, "ID") !== false) { ?>
                                        <input type="text" class="form-control" 
                                            name="<?php echo $columnName; ?>" 
                                            value="<?php echo $nextID; ?>" readonly>                                    
                                    <?php 
                                    } else { ?>
                                        <input type="text" class="form-control" 
                                            name="<?php echo $columnName; ?>" 
                                            value="">
                                    <?php } ?>
                                </div>
                            </div>
                <?php
            }?>
                            <div class="row mb-3">
                                <!-- <label class="col-sm-3 col-form-label">Name</label> -->
                                <div class="col-sm-9 offset-sm-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" name="newItemSubmit">Submit</button>
                                        <a href="<?php echo $_SESSION['selectedTablePage']?>" class="btn btn-outline-secondary" role="button">Cancel</a>
                                        <!-- <input type="text" class="form-control" name="name" value=""> -->
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php              
    } // end of generateCreateFields function

    // with existing data
    function generateEditFields($table, $rowData, $conn) {
        $dateColumns = array("created_at", "date_added", "transaction_date");
        $intColumns = array("stock_quantity", "quantity");
        $floatColumns = array("price", "total_amount");        
        ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form id="editItemForm" method="POST" action="<?= BASE_URL ?>src/services/crud/update-data.php">
        <?php
            foreach ($rowData as $key => $value) {
                // maybe the action should have a variable to specify redirects
                ?>
                            <div class="row mb-3">
                                <!-- Prints column names -->
                                <label class="col-sm-3 col-form-label">
                                    <?php 
                                        // This will remove the created_at input field because created_at is purely for keeping integrity
                                        if ($key != "created_at") {
                                            echo $key;
                                        }
                                    ?>
                                </label>
                                <!-- Displays input fields -->
                                <div class="col-sm-9">
                                    <?php if ($key == "created_at") {
                                        continue;
                                    } elseif (stripos($key, "_id") !== false) { ?>
                                        <input type="text" class="form-control" 
                                            name="<?php echo $key; ?>" 
                                            value="<?php echo $value; ?>" readonly>                                    
                                    <?php 
                                    } elseif ($key == "created_at" || 
                                              $key == "date_added" || 
                                              $key == "transaction_date") { ?>
                                        <input type="date" class="form-control" 
                                            name="<?php echo $key; ?>" 
                                            value="<?php echo $value; ?>">                                    
                                    <?php 
                                    } elseif ($key == "stock_quantity" || 
                                              $key == "quantity") { // WHY TF IS ARRAY_KEY_EXISTS NOT WORKING ?>
                                        <input type="number" min="0" class="form-control" 
                                            name="<?php echo $key; ?>" 
                                            value="<?php echo $value; ?>">                                    
                                    <?php  
                                    } elseif ($key == "price" || 
                                              $key == "total_amount") { ?>
                                        <input type="number" step="0.01" min="0" class="form-control" 
                                            name="<?php echo $key; ?>" 
                                            value="<?php echo $value; ?>">                                    
                                    <?php  
                                    } else { ?>
                                        <input type="text" class="form-control" 
                                            name="<?php echo $key; ?>" 
                                            value="<?php echo $value; ?>">
                                    <?php } ?>        
                                </div>            
                            </div>
                    <?php
            }  // end of foreach block ?>
                            <div class="row mb-3">
                                <!-- <label class="col-sm-3 col-form-label">Name</label> -->
                                <div class="col-sm-9 offset-sm-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" name="editItemSubmit">Submit</button>
                                        <a href="<?php echo $_SESSION['selectedTablePage']?>" class="btn btn-outline-secondary" role="button">Cancel</a>
                                        <!-- <input type="text" class="form-control" name="name" value=""> -->
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php              
    } // end of generateCreateFields function
?>