<?php
require_once("./assets/scripts/config.php");
require_once(BASE_PATH . "src/services/start-session.php");
require_once(BASE_PATH . "src/services/db-connect.php");

function getDashboardMetrics($conn) {
    $metrics = [];
    
    // 1. Total Items
    $query = "SELECT COUNT(*) as total_items FROM items";
    $result = mysqli_query($conn, $query);
    $metrics['total_items'] = mysqli_fetch_assoc($result)['total_items'];
    
    // 2. Number of Sales (total transactions)
    $query = "SELECT COUNT(*) as total_sales FROM transactions";
    $result = mysqli_query($conn, $query);
    $metrics['total_sales'] = mysqli_fetch_assoc($result)['total_sales'];
    
    // 3. Number of Customers
    $query = "SELECT COUNT(*) as total_customers FROM customer";
    $result = mysqli_query($conn, $query);
    $metrics['total_customers'] = mysqli_fetch_assoc($result)['total_customers'];
    
    // 4. Hot Items (top 5 by quantity sold)
    $query = "SELECT i.item_name, SUM(t.quantity) as total_sold 
              FROM transactions t 
              JOIN items i ON t.item_id = i.item_id 
              GROUP BY t.item_id, i.item_name 
              ORDER BY total_sold DESC 
              LIMIT 5";
    $result = mysqli_query($conn, $query);
    $metrics['hot_items'] = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $metrics['hot_items'][] = $row;
    }
    
    return $metrics;
}

function getSalesData($conn) {
    // Daily sales for the last 7 days
    $query = "SELECT 
                DATE(transaction_date) as sale_date, 
                COUNT(*) as transactions_count,
                SUM(total_amount) as daily_revenue
              FROM transactions 
              WHERE transaction_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
              GROUP BY DATE(transaction_date) 
              ORDER BY sale_date";
    
    $result = mysqli_query($conn, $query);
    $sales_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $sales_data[] = $row;
    }
    
    return $sales_data;
}

function getMonthlySales($conn) {
    $query = "SELECT 
                MONTH(transaction_date) as month,
                MONTHNAME(transaction_date) as month_name,
                COUNT(*) as transactions,
                SUM(total_amount) as revenue
              FROM transactions 
              WHERE YEAR(transaction_date) = YEAR(CURDATE())
              GROUP BY MONTH(transaction_date), MONTHNAME(transaction_date)
              ORDER BY month";
    
    $result = mysqli_query($conn, $query);
    $monthly_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $monthly_data[] = $row;
    }
    
    return $monthly_data;
}

$metrics = getDashboardMetrics($conn);
$salesData = getSalesData($conn);
$monthlyData = getMonthlySales($conn);

$dailyLabels = [];
$dailyTransactions = [];
$dailyRevenue = [];

foreach ($salesData as $data) {
    $dailyLabels[] = date('M j', strtotime($data['sale_date']));
    $dailyTransactions[] = $data['transactions_count'];
    $dailyRevenue[] = $data['daily_revenue'];
}

$monthlyLabels = [];
$monthlyTransactions = [];
$monthlyRevenue = [];

foreach ($monthlyData as $data) {
    $monthlyLabels[] = $data['month_name'];
    $monthlyTransactions[] = $data['transactions'];
    $monthlyRevenue[] = $data['revenue'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sari-Sari Store</title>
    <link rel="icon" type="image/x-icon" href="./assets/images/logo-no-text.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <style>
        .metric-card {
            transition: transform 0.2s;
        }
        .metric-card:hover {
            transform: translateY(-2px);
        }
        .hot-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .hot-item:last-child {
            border-bottom: none;
        }
        .chart-container {
            position: relative;
            height: 400px;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php
    include("./src/components/header.php");
    include("./src/components/navigation.php");
    ?>

    <main class="container-fluid px-4 py-4">
        <!-- <h1 class="h2 mb-4">Dashboard Overview</h1> -->
        
        <!-- Metrics Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card metric-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-box-seam text-primary mb-3" style="font-size: 2rem;"></i>
                        <h3 class="card-title text-primary"><?php echo number_format($metrics['total_items']); ?></h3>
                        <p class="card-text text-muted">Total Items</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card metric-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-graph-up text-success mb-3" style="font-size: 2rem;"></i>
                        <h3 class="card-title text-success"><?php echo number_format($metrics['total_sales']); ?></h3>
                        <p class="card-text text-muted">Total Sales</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card metric-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-people text-info mb-3" style="font-size: 2rem;"></i>
                        <h3 class="card-title text-info"><?php echo number_format($metrics['total_customers']); ?></h3>
                        <p class="card-text text-muted">Total Customers</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card metric-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-fire text-warning me-2" style="font-size: 1.5rem;"></i>
                            <h5 class="card-title mb-0">Hot Items</h5>
                        </div>
                        <div class="hot-items">
                            <?php foreach ($metrics['hot_items'] as $index => $item): ?>
                                <div class="hot-item">
                                    <span class="fw-bold text-warning">#<?php echo $index + 1; ?></span>
                                    <span class="text-truncate me-2" style="max-width: 120px;" title="<?php echo htmlspecialchars($item['item_name']); ?>">
                                        <?php echo htmlspecialchars($item['item_name']); ?>
                                    </span>
                                    <span class="badge bg-warning text-dark"><?php echo $item['total_sold']; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Daily Sales Trend (Last 7 Days)</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="dailySalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Monthly Revenue</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlyRevenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Analytics Row -->
        <div class="row g-4 mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Monthly Transaction Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlyTransactionsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Daily Sales Chart
        const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
        new Chart(dailySalesCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dailyLabels); ?>,
                datasets: [{
                    label: 'Transactions',
                    data: <?php echo json_encode($dailyTransactions); ?>,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#007bff',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Monthly Revenue Chart (Doughnut)
        const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(monthlyRevenueCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($monthlyLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($monthlyRevenue); ?>,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#FF6384',
                        '#C9CBCF',
                        '#4BC0C0',
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Monthly Transactions Chart (Bar)
        const monthlyTransactionsCtx = document.getElementById('monthlyTransactionsChart').getContext('2d');
        new Chart(monthlyTransactionsCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($monthlyLabels); ?>,
                datasets: [{
                    label: 'Transactions',
                    data: <?php echo json_encode($monthlyTransactions); ?>,
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    borderColor: '#28a745',
                    borderWidth: 1,
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>

    <?php include("./src/components/footer.php"); ?>
</body>
</html>