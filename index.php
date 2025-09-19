<?php
require_once("./assets/scripts/config.php");
require_once(BASE_PATH . "src/services/start-session.php");
require_once(BASE_PATH . "src/services/dashboard-data.php");
?>

<!-- 
    Algorithm:
    1. Connect to database
    2. Fetch all data from the database to be processed 
    (and organize it into groups: metrics, salesData, monthlyData, todaysData, lowStockItems)
    3. Load the UI (HTML/CSS)
    4. Insert the data into the UI components

    Data Flow:

    Database Tables (items, transactions, customer)
        ↓
    SQL Queries (executed by functions)
        ↓
    Raw Arrays ($salesData, $monthlyData, etc.)
        ↓
    Processed Data ($metrics, $chartData, etc.)
        ↓
    Dashboard Components (cards, charts)
        ↓
    HTML Output with JavaScript Charts
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sari-Sari Store</title>
    <link rel="icon" type="image/x-icon" href="./assets/images/logo-no-text.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Aclonica&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="./assets/styles/dashboard.css">
    <link rel="stylesheet" href="./assets/styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php
    include("./src/components/header.php");
    include("./src/components/navigation.php");
    ?>

    <main class="container-fluid px-4 py-4 dashboard-main">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="aclonica-regular">Dashboard Overview</h1>
        </div>
        
        <!-- Dashboard Cards -->
        <?php include(BASE_PATH . "src/components/dashboard-cards.php"); ?>
        
        <!-- Dashboard Charts -->
        <?php include(BASE_PATH . "src/components/dashboard-charts.php"); ?>
    </main>

    <!-- Chart.js -->
     <!-- https://www.chartjs.org/docs/latest/ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <!-- Dashboard Charts Script para clean -->
    <script src="./assets/scripts/dashboard-charts.js"></script>

    <?php include("./src/components/footer.php"); ?>
</body>
</html>