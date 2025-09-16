<?php
require_once(__DIR__ . "/../../assets/scripts/config.php");
require_once(BASE_PATH . "src/services/start-session.php");

$searchTerm = trim($_GET['query'] ?? $_GET['search'] ?? '');
$table = $_GET['table'] ?? $_SESSION['selectedTable'] ?? 'customer';

if (empty($searchTerm)) {
    switch($table) {
        case 'customer':
            header("Location: " . BASE_URL . "src/views/customers.php");
            break;
        case 'items':
        case 'item':
            header("Location: " . BASE_URL . "src/views/items.php");
            break;
        case 'transactions':
        case 'transaction':
            header("Location: " . BASE_URL . "src/views/transactions.php");
            break;
        default:
            header("Location: " . BASE_URL . "src/views/customers.php");
    }
    exit();
}

$searchResults = [];
$hasResults = false;

try {
    if ($table == "customer") {
        $sql = "SELECT customer_id, first_name, last_name, contact_number, address, created_at 
                FROM customer 
                WHERE first_name LIKE ? 
                OR last_name LIKE ? 
                OR contact_number LIKE ? 
                OR address LIKE ?
                OR created_at LIKE ?
                ORDER BY customer_id DESC";
        
        $stmt = $conn->prepare($sql);
        $param = "%$searchTerm%";
        $stmt->bind_param("sssss", $param, $param, $param, $param, $param);
        
    } elseif ($table == "items" || $table == "item") {
        $sql = "SELECT item_id, item_name, category, price, stock_quantity, created_at
                FROM items
                WHERE item_name LIKE ?
                OR category LIKE ?
                OR CAST(price AS CHAR) LIKE ?
                OR CAST(stock_quantity AS CHAR) LIKE ?
                OR created_at LIKE ?
                ORDER BY item_id DESC";
        
        $stmt = $conn->prepare($sql);
        $param = "%$searchTerm%";
        // $stmt->bind_param("ssss", $param, $param, $param, $param);
        $stmt->bind_param("sssss", $param, $param, $param, $param, $param);

        
    } elseif ($table == "transactions" || $table == "transaction") {
        $sql = "SELECT transaction_id, customer_id, item_id, quantity, total_amount, transaction_date, date_added
                FROM transactions
                WHERE CAST(customer_id AS CHAR) LIKE ?
                OR CAST(item_id AS CHAR) LIKE ?
                OR CAST(quantity AS CHAR) LIKE ?
                OR CAST(total_amount AS CHAR) LIKE ?
                OR transaction_date LIKE ?
                OR date_added LIKE ?
                ORDER BY transaction_id DESC";
        
        $stmt = $conn->prepare($sql);
        $param = "%$searchTerm%";
        $stmt->bind_param("ssssss", $param, $param, $param, $param, $param, $param);
    }
    
    if (isset($stmt)) {
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Store results in session for display
        $_SESSION['search_results'] = [];
        $_SESSION['search_term'] = $searchTerm;
        $_SESSION['search_table'] = $table;
        
        while ($row = $result->fetch_assoc()) {
            $_SESSION['search_results'][] = $row;
        }
        
        $hasResults = count($_SESSION['search_results']) > 0;
        $stmt->close();
    }
    
} catch (Exception $e) {
    error_log("Search error: " . $e->getMessage());
    $_SESSION['search_error'] = "Search failed. Please try again.";
}

$conn->close();

switch($table) {
    case 'customer':
        header("Location: " . BASE_URL . "src/views/customers.php?search=" . urlencode($searchTerm));
        break;
    case 'items':
    case 'item':
        header("Location: " . BASE_URL . "src/views/items.php?search=" . urlencode($searchTerm));
        break;
    case 'transactions':
    case 'transaction':
        header("Location: " . BASE_URL . "src/views/transactions.php?search=" . urlencode($searchTerm));
        break;
    default:
        header("Location: " . BASE_URL . "src/views/customers.php?search=" . urlencode($searchTerm));
}
exit();
?>