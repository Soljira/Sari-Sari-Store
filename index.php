<?php
    require_once("./assets/scripts/config.php");
    require_once(BASE_PATH . "src/services/start-session.php");
    // echo 'connected successfully to database';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/x-icon" href="./assets/images/logo-no-text.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php
        include("./src/components/header.php");
        include("./src/components/navigation.php");
    ?>

    <!-- TODO: Change div into something semantic -->
    <div class="card">
        <div class="card-body">
            <nav>
                <ul class="list-group">
                    <li class="list-group-item">Total Items</li>
                    <li class="list-group-item"># of Sales</li>
                    <li class="list-group-item">Hot Items</li>
                    <li class="list-group-item">Number of Customers</li>
                </ul>
            </nav>
        </div>
    </div>

    <h2>Analytics</h2>
    <!-- insert data here -->

    <div class="model" tabindex="-1">
    </div>

    <?php
        include("./src/components/footer.php");
    ?>



    
</body>
</html>