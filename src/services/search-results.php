<?php
// search-results.php - Display search results stored in session

if (isset($_SESSION['search_error'])) {
    echo "<tr><td colspan='6' class='text-center text-danger'>" . htmlspecialchars($_SESSION['search_error']) . "</td></tr>";
    unset($_SESSION['search_error']);
    return;
}

if (isset($_SESSION['search_results']) && !empty($_SESSION['search_results'])) {
    $table = $_SESSION['search_table'] ?? 'customer';
    
    foreach ($_SESSION['search_results'] as $row) {
        echo "<tr>";
        
        if ($table == 'customer') {
            echo "<td>" . htmlspecialchars($row['customer_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['contact_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at'] ?? 'N/A') . "</td>";
            
        } elseif ($table == 'items' || $table == 'item') {
            echo "<td>" . htmlspecialchars($row['item_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
            echo "<td>" . htmlspecialchars($row['price']) . "</td>";
            echo "<td>" . htmlspecialchars($row['stock_quantity']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at'] ?? 'N/A') . "</td>";
            
        } elseif ($table == 'transactions' || $table == 'transaction') {
            echo "<td>" . htmlspecialchars($row['transaction_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['customer_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['item_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
            echo "<td>" . htmlspecialchars($row['total_amount']) . "</td>";
            echo "<td>" . htmlspecialchars($row['transaction_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['date_added'] ?? 'N/A') . "</td>";
        }
        
        echo "</tr>";
    }
    
    // Clear search results from session after displaying
    unset($_SESSION['search_results']);
    unset($_SESSION['search_table']);
    
} else {
    $searchTerm = $_SESSION['search_term'] ?? $_GET['search'] ?? '';
    $colCount = ($_SESSION['search_table'] ?? 'customer') == 'customer' ? 6 : 6; // Adjust based on table
    echo "<tr><td colspan='$colCount' class='text-center'>No results found for '" . htmlspecialchars($searchTerm) . "'</td></tr>";
    
    // Clear search term from session
    unset($_SESSION['search_term']);
}
?>