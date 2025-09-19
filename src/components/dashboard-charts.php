<!-- src/components/dashboard-charts.php -->
<div class="row g-4">
    <!-- Daily Sales Trend Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daily Sales Trend</h5>
                    <small class="text-muted">Last 7 Days</small>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monthly Revenue Pie Chart -->
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

<!-- Monthly Transactions Chart -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Monthly Transaction Overview</h5>
                    <small class="text-muted">Current Year</small>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlyTransactionsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pass chart data to JavaScript -->
<script>
    // Makes chart data available globally
    // See line 5 in dashboards-charts.js
    window.chartData = <?php echo json_encode($chartData); ?>;
</script>