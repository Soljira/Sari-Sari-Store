<?php
require_once(__DIR__ . "/../../../assets/scripts/config.php");
require_once(BASE_PATH . "src/services/start-session.php");

// Store the referring page URL to enable the 'Cancel' button to go back.
if (isset($_SERVER['HTTP_REFERER'])) {
    $_SESSION['selectedTablePage'] = $_SERVER['HTTP_REFERER'];
}

$selectedTable = $_SESSION['selectedTable'] ?? null;
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']); // Clear errors after displaying them

// If no table is selected, prevent errors by redirecting or showing a message.
if (!$selectedTable) {
    // You can redirect to a default page or show an error message.
    die("Error: No table selected.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New <?= htmlspecialchars(ucfirst($selectedTable)) ?></title>
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>assets/images/logo-no-text.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/styles/bootstrap-adjustments.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Aclonica&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../../assets/styles/styles.css">

</head>
<body class="d-flex flex-column min-vh-100">
<?php
include(BASE_PATH . "src/components/header.php");
include(BASE_PATH . "src/components/navigation.php");
?>

<div class="container my">
    <main>
        <?php
        // Get table columns
        $sql = "SHOW COLUMNS FROM `$selectedTable`";
        $result = mysqli_query($conn, $sql);

        // Get the next auto-increment ID for the primary key field
        $autoIncrementQuery = "SHOW TABLE STATUS LIKE '$selectedTable'";
        $autoIncrementResult = mysqli_query($conn, $autoIncrementQuery);
        $autoIncrementRow = mysqli_fetch_assoc($autoIncrementResult);
        $nextID = $autoIncrementRow['Auto_increment'];
        ?>

        <h1 class="text-center my-3 aclonica-regular">New <?= htmlspecialchars(ucfirst(rtrim($selectedTable, 's'))) ?></h1>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php
                        // Show errors from the form submission if any
                        if (!empty($errors)) {
                            echo '<div class="alert alert-danger">';
                            foreach ($errors as $error) {
                                echo '<div>' . htmlspecialchars($error) . '</div>';
                            }
                            echo '</div>';
                        }
                        ?>
                        <form id="newItemForm" method="POST" action="./insert-data.php">
                            <?php while ($row = mysqli_fetch_assoc($result)):
                                $columnName = $row['Field'];
                                $isIdColumn = stripos($columnName, "_id") !== false;

                                // Skip the 'created_at' column entirely
                                if ($columnName == "created_at") continue;
                                ?>
                                <div class="row mb-3">
                                    <label for="<?= $columnName ?>" class="col-sm-3 col-form-label"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $columnName))) ?></label>
                                    <div class="col-sm-9">
                                        <?php
                                        // Special dropdowns for foreign keys in the 'transactions' table
                                        if ($selectedTable == "transactions" && ($columnName == "customer_id" || $columnName == "item_id")):
                                            $foreignTable = ($columnName == "customer_id") ? "customer" : "items";
                                            $foreignKey = $columnName;
                                            $foreignQuery = ($foreignTable == "customer")
                                                ? "SELECT customer_id, CONCAT(first_name, ' ', last_name) as name FROM customer ORDER BY name"
                                                : "SELECT item_id, item_name as name FROM items ORDER BY name";
                                            $foreignResult = mysqli_query($conn, $foreignQuery);
                                            ?>
                                            <select class="form-select" name="<?= $columnName ?>" id="<?= $columnName ?>" required>
                                                <option value="" disabled selected>Select <?= htmlspecialchars(ucfirst(rtrim($foreignTable, 's'))) ?></option>
                                                <?php while ($fRow = mysqli_fetch_assoc($foreignResult)): ?>
                                                    <option value="<?= $fRow[$foreignKey] ?>"><?= $fRow[$foreignKey] . ' - ' . htmlspecialchars($fRow['name']) ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        <?php // Read-only input for the primary key of the current table
                                        elseif ($isIdColumn && $columnName == rtrim($selectedTable, 's') . '_id'): ?>
                                            <input type="text" class="form-control" name="<?= $columnName ?>" id="<?= $columnName ?>" value="<?= $nextID ?>" readonly>
                                        <?php // Date input for date columns
                                        elseif (in_array($columnName, ['transaction_date', 'date_added'])): ?>
                                            <input type="date" class="form-control" name="<?= $columnName ?>" id="<?= $columnName ?>">
                                        <?php // Number input for integer columns
                                        elseif (in_array($columnName, ['quantity', 'stock_quantity'])): ?>
                                            <input type="number" min="0" class="form-control" name="<?= $columnName ?>" id="<?= $columnName ?>">
                                        <?php // Number input with decimals for price columns
                                        elseif (in_array($columnName, ['price', 'total_amount', 'total_price'])): ?>
                                            <input type="number" step="0.01" min="0" class="form-control" name="<?= $columnName ?>" id="<?= $columnName ?>">
                                        <?php // Default text input for all other columns
                                        else: ?>
                                            <input type="text" class="form-control" name="<?= $columnName ?>" id="<?= $columnName ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="submit" class="btn btn-primary" name="newItemSubmit">Submit</button>
                                <a href="<?= htmlspecialchars($_SESSION['selectedTablePage'] ?? BASE_URL) ?>" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include(BASE_PATH . "src/components/footer.php"); ?>
</body>
</html>