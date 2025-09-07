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
    <title>Customers</title>
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>assets/images/logo-no-text.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</head>
<body>
    <?php
        include(BASE_PATH . "src/components/header.php");
        include(BASE_PATH . "src/components/navigation.php");
    ?>
    <p>Customers Page</p>



        <div class="container">
        <div class="row">
            <main class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1>Books</h1>
                    <!-- TODO: Export to .cvs button and funcitonality -->

                    <form method="POST" action="../actions/create.php?bookTable">
                        <button type="submit" name="newItem" value="bookNewItem" class="btn btn-success">+ New Item</button>
                    </form>
                </div>
                
                <!-- 1. Create the table to be filled with data -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Publisher</th>
                                <th>Publication Date</th>
                                <th>Genre</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include("../php-scripts/populate-table.php");
                                populateTable("bookTable", $conn);
                                $_SESSION['selectedTable'] = "bookTable";
                                mysqli_close($conn);
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    


    <?php
        include(BASE_PATH . "src/components/footer.php");
    ?>
    
</body>
</html>