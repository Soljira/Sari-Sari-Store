<?php
    // TODO: Input validation
    // TODO: Filter and sanitize

    /*
        1. Create the table to be filled with data (already done in the prev page)
        2. Fetch the data from the database (using MySQLi); table should be determined by the current page
        3. Populate the table with the fetched data
    */
    function readTable($table, $conn) {
        // I don't need to include the db-connect.php file because I already passed $conn as an argument

        // 2. Fetch the data from the database (using MySQLi)
        $sql = "SELECT * FROM $table";
        $result = mysqli_query($conn, $sql);

        // 3. Populate the table with the fetched data
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                switch ($table) {
                    case "customer":
                        ?> <!-- Closes the first php tag to do some HTML -->
                        <tr>
                            <!-- This will create rows accdg. to the database data -->
                            <td><?php echo htmlspecialchars($row['customer_id']) ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']) ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']) ?></td>
                            <td><?php echo htmlspecialchars($row['contact_number']) ?></td>
                            <td><?php echo htmlspecialchars($row['address']) ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']) ?></td>
                            <td class="text-end">
                                <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>src/services/crud/edit-item.php?customer_id=<?php echo $row['customer_id']?>">Edit</a>
                                <form method="POST" action="../actions/delete.php" style="display:inline;">
                                    <input type="hidden" name="authorID" value="<?php echo $row['authorID']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this?');">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php // Back to php; the closing tag should be the one in the end
                        break;
                    case "items":
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['item_id']) ?></td>
                            <td><?php echo htmlspecialchars($row['item_name']) ?></td>
                            <td><?php echo htmlspecialchars($row['category']) ?></td>
                            <td><?php echo htmlspecialchars($row['price']) ?></td>
                            <td><?php echo htmlspecialchars($row['stock_quantity']) ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']) ?></td>
                            <td class="text-end">
                                <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>src/services/crud/edit-item.php?item_id=<?php echo $row['item_id']?>">Edit</a> 
                                <form method="POST" action="../actions/delete.php" style="display:inline;">
                                    <input type="hidden" name="bookID" value="<?php echo $row['bookID']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this?');">
                                        Delete
                                    </button>
                                </form>                                
                            </td>                            
                        </tr>
                        <?php
                        break;
                    case "transactions":
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['transaction_id']) ?></td>
                            <td><?php echo htmlspecialchars($row['customer_id']) ?></td>
                            <td><?php echo htmlspecialchars($row['item_id']) ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']) ?></td>
                            <td><?php echo htmlspecialchars($row['total_amount']) ?></td>
                            <td><?php echo htmlspecialchars($row['transaction_date']) ?></td>
                            <td><?php echo htmlspecialchars($row['date_added']) ?></td>
                            <td class="text-end">
                                <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>src/services/crud/edit-item.php?transaction_id=<?php echo $row['transaction_id']?>">Edit</a>
                                <form method="POST" action="../actions/delete.php" style="display:inline;">
                                    <input type="hidden" name="orderID" value="<?php echo $row['orderID']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this?');">
                                        Delete
                                    </button>
                                </form>                                
                            </td>                            
                        </tr>
                        <?php
                        break;
                    default:
                        echo "Table not found";
                        break;
                }
            }
        } else {
            echo '<tr><td colspan="8">No data found.</td></tr>';
        }
    }
?>