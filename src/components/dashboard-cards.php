<!-- Data Presentation Phase -->
<!-- Called by index.php; to be partnered with dashboard-data.php -->
<!-- This is not about charts. Actual chart.js code is found in /assets/scripts/dashboard-charts.js  -->


<!--  -->
<div class="row g-4 mb-5">
    <!-- Total Items Card -->
    <div class="col-md-3">
        <div class="card metric-card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-box-seam text-primary mb-3" style="font-size: 2rem;"></i>
                <h3 class="card-title text-primary"><?php echo number_format($metrics['total_items']); ?></h3>
                <p class="card-text text-muted">Total Items</p>
                <small class="text-muted">In inventory</small>
            </div>
        </div>
    </div>
    
    <!-- Total Sales Card -->
    <div class="col-md-3">
        <div class="card metric-card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-graph-up text-success mb-3" style="font-size: 2rem;"></i>
                <h3 class="card-title text-success"><?php echo number_format($metrics['total_sales']); ?></h3>
                <p class="card-text text-muted">Total Sales</p>
                <small class="text-muted">All time transactions</small>
            </div>
        </div>
    </div>
    
    <!-- Total Customers Card -->
    <div class="col-md-3">
        <div class="card metric-card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-people text-info mb-3" style="font-size: 2rem;"></i>
                <h3 class="card-title text-info"><?php echo number_format($metrics['total_customers']); ?></h3>
                <p class="card-text text-muted">Total Customers</p>
                <small class="text-muted">Registered customers</small>
            </div>
        </div>
    </div>
    
    <!-- Hot Items Card -->
    <div class="col-md-3">
        <div class="card metric-card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-fire text-warning me-2" style="font-size: 1.5rem;"></i>
                    <h5 class="card-title mb-0">Hot Items</h5>
                </div>
                <div class="hot-items">
                    <?php if (!empty($metrics['hot_items'])): ?>
                        <?php foreach ($metrics['hot_items'] as $index => $item): ?>
                            <div class="hot-item">
                                <span class="fw-bold text-warning">#<?php echo $index + 1; ?></span>
                                <span class="text-truncate me-2" style="max-width: 120px;" 
                                      title="<?php echo htmlspecialchars($item['item_name']); ?>">
                                    <?php echo htmlspecialchars($item['item_name']); ?>
                                </span>
                                <span class="badge bg-warning text-dark"><?php echo $item['total_sold']; ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted small">No sales data available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Performance Row -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-calendar-day text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Today's Sales</h6>
                        <h4 class="mb-0"><?php echo number_format($todaysData['today_transactions']); ?></h4>
                        <small class="text-muted">Transactions</small>
                    </div>
                    <div class="ms-auto">
                        <h5 class="text-success mb-0">₱<?php echo number_format($todaysData['today_revenue'], 2); ?></h5>
                        <small class="text-muted">Revenue</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Low Stock Alert</h6>
                            <h4 class="mb-0"><?php echo count($lowStockItems); ?></h4>
                            <small class="text-muted">Items below 20 stock</small>
                        </div>
                    </div>
                    <?php if (!empty($lowStockItems)): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-warning btn-sm dropdown-toggle" type="button" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                View Items
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($lowStockItems as $item): ?>
                                    <li class="dropdown-item d-flex justify-content-between">
                                        <span class="text-truncate" style="max-width: 120px;">
                                            <?php echo htmlspecialchars($item['item_name']); ?>
                                        </span>
                                        <span class="badge bg-warning text-dark ms-2">
                                            <?php echo $item['stock_quantity']; ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>