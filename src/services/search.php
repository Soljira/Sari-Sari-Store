<?php

$conn = new mysqli("localhost", "root", "", "sari_sari_store");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentpage = $_GET['page'] ?? 'customer';
$itemToBeSearched = $_POST['search'] ?? '';


if ($currentpage == "customer") {
    $sql = "SELECT customer_id, first_name, last_name, contact_number, address 
            FROM customer 
            WHERE first_name LIKE ? 
               OR last_name LIKE ? 
               OR contact_number LIKE ? 
               OR address LIKE ?";
    $stmt = $conn->prepare($sql);

    $param = "%$itemToBeSearched%";
    $stmt->bind_param("ssss", $param, $param, $param, $param);

} elseif ($currentpage == "items") {
    $sql = "SELECT item_id, item_name, category, price, stock_quantity, createdat
            FROM item
            WHERE item_id LIKE ? 
               OR item_name LIKE ? 
               OR category LIKE ? 
               OR price LIKE ?
               OR stock_quantity LIKE ?
               OR createdat LIKE ?";
    $stmt = $conn->prepare($sql);

    $param = "%$itemToBeSearched%";
    $stmt->bind_param("ssssss", $param, $param, $param, $param, $param, $param);

} elseif ($currentpage == "transactions") {
    $sql = "SELECT transaction_id, customer_id, item_id, quantity, total_price, createdat
            FROM transactions
            WHERE transaction_id LIKE ? 
               OR customer_id LIKE ? 
               OR item_id LIKE ?
               OR quantity LIKE ? 
               OR total_price LIKE ?
               OR createdat LIKE ?";
    $stmt = $conn->prepare($sql);

    $param = "%$itemToBeSearched%";
    $stmt->bind_param("ssssss", $param, $param, $param, $param, $param, $param);
}

// Execute
$stmt->execute();
$result = $stmt->get_result();


echo "<table border='1' cellpadding='5'>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    foreach ($row as $col) {
        echo "<td>" . htmlspecialchars($col) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

$stmt->close();
$conn->close();
?>
