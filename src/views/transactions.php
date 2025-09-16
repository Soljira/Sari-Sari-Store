<?php
    require_once(__DIR__ . "/../../assets/scripts/config.php");
    require_once(BASE_PATH . "src/services/start-session.php");
    // echo 'connected successfully to database';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>assets/images/logo-no-text.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Aclonica&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../../assets/styles/styles.css">

</head>
<body>
    <?php
        include(BASE_PATH . "src/components/header.php");
        include(BASE_PATH . "src/components/navigation.php");
    ?>

    <div class="container mx-auto">
        <h1 class="text-center my-3 aclonica-regular">Transactions</h1>

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-2 mb-3 border-bottom">
            <form class="d-flex flex-grow-1 me-2" role="search" method="GET" action="<?= BASE_URL ?>src/services/search.php">
                <input type="hidden" name="table" value="transaction">
                <input class="form-control me-2" type="search" name="query" placeholder="Search transactions..." aria-label="Search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button class="btn btn-primary" type="submit">Search</button>
                <?php if (!empty($_GET['search'])): ?>
                    <a href="?" class="btn btn-secondary ms-2">Clear</a>
                <?php endif; ?>
            </form>
            <form method="POST" action="<?= BASE_URL ?>src/services/crud/create-item.php?transaction">
                <button type="submit" name="newItem" value="transactionNewItem" class="btn btn-success">+ New Item</button>
            </form>
        </div>
                
        <div class="table-responsive" >
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Customer ID</th>
                        <th>Item ID</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <th>Transaction Date</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // include(BASE_PATH . "src/services/crud/read-table.php");
                        // readTable("transactions", $conn);
                        // $_SESSION['selectedTable'] = "transactions";
                        // mysqli_close($conn);

                        $searchTerm = $_GET['search'] ?? '';

                        if (!empty($searchTerm)) {
                            include(BASE_PATH . "src/services/search-results.php");
                        } else {
                            include(BASE_PATH . "src/services/crud/read-table.php");
                            readTable("transactions", $conn);
                        }

                        $_SESSION['selectedTable'] = "transactions";
                        mysqli_close($conn);
                    ?>
                </tbody>
            </table>
            <!-- TODO: Pagination -->
        </div>
        <?php if (!empty($searchTerm)): ?>
            <div class="alert alert-info">
                Showing search results for: <strong><?= htmlspecialchars($searchTerm) ?></strong>
            </div>
        <?php endif; ?>
        <!-- <h2 class="text-center">Pagination here</h2> -->


    </div>
    <?php
        include(BASE_PATH . "src/components/footer.php");
    ?>
    
</body>
</html>