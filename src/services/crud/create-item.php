<?php
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/styles/bootstrap-adjustments.css">
</head>
<body class="d-flex flex-column min-vh-100">
<?php
include(BASE_PATH . "src/components/header.php");
include(BASE_PATH . "src/components/navigation.php");
?>

<div class="container mx-auto">
    <main class="mb-5">
        <?php
        $selectedTable = $_SESSION['selectedTable'];

        // Show errors from insert-data.php if any
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<p class="text-danger">' . htmlspecialchars($error) . '</p>';
            }
        }

        $sql = "SHOW COLUMNS FROM $selectedTable";
        $result = mysqli_query($conn, $sql);

        // Get next auto-increment ID
        $autoIncrementQuery = "SHOW TABLE STATUS LIKE '$selectedTable'";
        $autoIncrementResult = mysqli_query($conn, $autoIncrementQuery);
        $autoIncrementRow = mysqli_fetch_assoc($autoIncrementResult);
        $nextID = $autoIncrementRow['Auto_increment'];
        ?>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form id="newItemForm" method="POST" action="./insert-data.php">

                            <?php while ($row = mysqli_fetch_assoc($result)):
                                $columnName = $row['Field'];

                                if ($columnName == "created_at") continue;
                                ?>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label"><?= $columnName ?></label>
                                    <div class="col-sm-9">
                                        <?php
                                        // Special case for transactions table
                                        if ($selectedTable == "transactions" && ($columnName == "customer_id" || $columnName == "item_id")):
                                            $foreignTable = $columnName == "customer_id" ? "customer" : "items";
                                            $foreignKey = $columnName;
                                            $foreignQuery = $foreignTable == "customer"
                                                ? "SELECT customer_id, CONCAT(first_name,' ',last_name) as name FROM customer"
                                                : "SELECT item_id, item_name as name FROM items";
                                            $foreignResult = mysqli_query($conn, $foreignQuery);
                                            ?>
                                            <select class="form-select" name="<?= $columnName ?>" required>
                                                <option value="">Select <?= $columnName ?></option>
                                                <?php while ($fRow = mysqli_fetch_assoc($foreignResult)): ?>
                                                    <option value="<?= $fRow[$foreignKey] ?>"><?= htmlspecialchars($fRow['name']) ?></option>
                                                <?php endwhile; ?>
                                            </select>

                                        <?php elseif (stripos($columnName, "_id") !== false): ?>
                                            <input type="text" class="form-control" name="<?= $columnName ?>" value="<?= $nextID ?>" readonly>

                                        <?php elseif (in_array($columnName, ['transaction_date', 'date_added'])): ?>
                                            <input type="date" class="form-control" name="<?= $columnName ?>" value="">

                                        <?php elseif (in_array($columnName, ['quantity', 'stock_quantity'])): ?>
                                            <input type="number" min="0" class="form-control" name="<?= $columnName ?>" value="">

                                        <?php elseif (in_array($columnName, ['price', 'total_amount'])): ?>
                                            <input type="number" step="0.01" min="0" class="form-control" name="<?= $columnName ?>" value="">

                                        <?php else: ?>
                                            <input type="text" class="form-control" name="<?= $columnName ?>" value="">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                            <div class="row justify-content-end">
                                <div class="col-sm-3 d-grid">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" name="newItemSubmit">Submit</button>
                                        <a href="<?= $_SESSION['selectedTablePage'] ?>" class="btn btn-outline-secondary">Cancel</a>
                                    </div>
                                </div>
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
