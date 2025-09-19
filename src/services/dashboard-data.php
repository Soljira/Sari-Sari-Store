<?php
require_once(BASE_PATH . "src/services/db-connect.php");

/**
 * Data Retrieval Phase
 * 
 * This is where $metrics, $todaysData and $lowStockItems in dashboard-cards.php come from
 * The actual logic in supplying dashboard-cards.php and dashboard-charts.php the required data
 * 
 * 1. Include database connection file
 * 2. Call getDashboardMetrics($conn) → store result in $metrics
 * 3. Call getDailySalesData($conn) → store result in $salesData
 * 4. Call getMonthlySalesData($conn) → store result in $monthlyData
 * 5. Call getTodaysSales($conn) → store result in $todaysData
 * 6. Call getLowStockItems($conn) → store result in $lowStockItems
 * 7. Call prepareChartData($salesData, $monthlyData) → store result in $chartData
 * 8. All variables are now ready for use in dashboard components
 */


/**
 * Gets all dashboard metrics
 * 
 * 1. Create empty array called $metrics
 * 2. Get Total Items:
 *   a. Run SQL: "SELECT COUNT(*) as total_items FROM items"
 *   b. Execute query using mysqli_query()
 *   c. Fetch result and store in $metrics['total_items']
 * 3. Get Total Sales:
 *   a. Run SQL: "SELECT COUNT(*) as total_sales FROM transactions"
 *   b. Execute query using mysqli_query()
 *   c. Fetch result and store in $metrics['total_sales']
 * 4. Get Total Customers:
 *   a. Run SQL: "SELECT COUNT(*) as total_customers FROM customer"
 *   b. Execute query using mysqli_query()
 *   c. Fetch result and store in $metrics['total_customers']
 * 5. Get Hot Items:
 *   a. Run SQL: JOIN transactions with items, GROUP BY item, ORDER BY total quantity sold, LIMIT 5
 *   b. Execute query using mysqli_query()
 *   c. Create empty array $metrics['hot_items']
 *   d. Loop through each result row:
 *       - Add row to $metrics['hot_items'] array
 * 6. Return $metrics array
 */
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

/**
 * Gets daily sales data for the last 7 days
 * 
 * 1. Create SQL query:
 *  - SELECT date, count of transactions, sum of revenue
 *  - FROM transactions table
 *  - WHERE date is within last 7 days
 *  - GROUP BY date
 *  - ORDER BY date
 * 2. Execute query using mysqli_query()
 * 3. Create empty array called $sales_data
 * 4. Loop through each result row:
 * a. Add entire row to $sales_data array
 * 5. Return $sales_data array
 */
function getDailySalesData($conn) {
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

/**
 * Gets monthly sales comparison for current year
 * 
 * 1. Create SQL query:
 *   - SELECT month number, month name, count of transactions, sum of revenue
 *   - FROM transactions table
 *   - WHERE year equals current year
 *   - GROUP BY month and month name
 *   - ORDER BY month number
 * 2. Execute query using mysqli_query()
 * 3. Create empty array called $monthly_data
 * 4. Loop through each result row:
 *   a. Add entire row to $monthly_data array
 * 5. Return $monthly_data array
 */
function getMonthlySalesData($conn) {
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

/**
 * Gets today's sales summary
 * 
 * 1. Create SQL query:
 *   - SELECT count of transactions, sum of total amount (with COALESCE for null safety)
 *   - FROM transactions table
 *   - WHERE date equals today's date
 * 2. Execute query using mysqli_query()
 * 3. Fetch single result row using mysqli_fetch_assoc()
 * 4. Return the result row directly
 */
function getTodaysSales($conn) {
    $query = "SELECT 
                COUNT(*) as today_transactions,
                COALESCE(SUM(total_amount), 0) as today_revenue
              FROM transactions 
              WHERE DATE(transaction_date) = CURDATE()";
    
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

/**
 * Gets low stock items (stock < 20)
 * Configure if needed for presentation
 * 
 * 1. Create SQL query:
 *  - SELECT item name, stock quantity, price
 *  - FROM items table
 *  - WHERE stock quantity is less than 20
 *  - ORDER BY stock quantity ascending (lowest first)
 *  - LIMIT to 5 items
 * 2. Execute query using mysqli_query()
 * 3. Create empty array called $low_stock
 * 4. Loop through each result row:
 *   a. Add entire row to $low_stock array
 * 5. Return $low_stock array
 */
function getLowStockItems($conn) {
    $query = "SELECT item_name, stock_quantity, price 
              FROM items 
              WHERE stock_quantity < 20 
              ORDER BY stock_quantity ASC 
              LIMIT 5";
    
    $result = mysqli_query($conn, $query);
    $low_stock = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $low_stock[] = $row;
    }
    
    return $low_stock;
}

/**
 * Prepares chart data for JavaScript
 * 
 * 1. Process Daily Data:
 *   a. Create 3 empty arrays: $dailyLabels, $dailyTransactions, $dailyRevenue
 *   b. Loop through each item in $salesData:
 *     - Convert sale_date to "M j" format (e.g., "Sep 12") → add to $dailyLabels
 *     - Extract transactions_count → add to $dailyTransactions
 *     - Convert daily_revenue to float → add to $dailyRevenue
 *
 * 2. Process Monthly Data:
 *   a. Create 3 empty arrays: $monthlyLabels, $monthlyTransactions, $monthlyRevenue
 *   b. Loop through each item in $monthlyData:
 *       - Extract month_name → add to $monthlyLabels
 *       - Extract transactions → add to $monthlyTransactions
 *       - Convert revenue to float → add to $monthlyRevenue
 *
 * 3. Create Return Structure:
 *   a. Build nested associative array with two main keys: 'daily' and 'monthly'
 *   b. Under 'daily': include labels, transactions, revenue arrays
 *   c. Under 'monthly': include labels, transactions, revenue arrays
 *   
 * 4. Return the nested array
 */
function prepareChartData($salesData, $monthlyData) {
    // Daily chart data
    $dailyLabels = [];
    $dailyTransactions = [];
    $dailyRevenue = [];
    
    foreach ($salesData as $data) {
        $dailyLabels[] = date('M j', strtotime($data['sale_date']));
        $dailyTransactions[] = $data['transactions_count'];
        $dailyRevenue[] = floatval($data['daily_revenue']);
    }
    
    // Monthly chart data
    $monthlyLabels = [];
    $monthlyTransactions = [];
    $monthlyRevenue = [];
    
    foreach ($monthlyData as $data) {
        $monthlyLabels[] = $data['month_name'];
        $monthlyTransactions[] = $data['transactions'];
        $monthlyRevenue[] = floatval($data['revenue']);
    }
    
    // Associative array, just in case I forget
    // This is not a lambda function
    return [
        'daily' => [
            'labels' => $dailyLabels,
            'transactions' => $dailyTransactions,
            'revenue' => $dailyRevenue
        ],
        'monthly' => [
            'labels' => $monthlyLabels,
            'transactions' => $monthlyTransactions,
            'revenue' => $monthlyRevenue
        ]
    ];
}

// Data is now ready to be used across all project files
$metrics = getDashboardMetrics($conn);
$salesData = getDailySalesData($conn);
$monthlyData = getMonthlySalesData($conn);
$todaysData = getTodaysSales($conn);
$lowStockItems = getLowStockItems($conn);

$chartData = prepareChartData($salesData, $monthlyData);
?>