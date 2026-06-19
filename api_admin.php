<?php
include 'db_config.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'getStats':
        // Total Users
        $totalUsersResult = $conn->query("SELECT COUNT(*) AS total FROM users");
        $totalUsers = $totalUsersResult->fetch_assoc()['total'];

        // Pending Orders
        $pendingOrdersResult = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'pending'");
        $pendingOrders = $pendingOrdersResult->fetch_assoc()['total'];

        // Monthly Revenue (for current month)
        $currentMonth = date('Y-m');
        $monthlyRevenueResult = $conn->query("SELECT SUM(amount) AS total FROM orders WHERE DATE_FORMAT(order_date, '%Y-%m') = '$currentMonth' AND status = 'completed'");
        $monthlyRevenue = $monthlyRevenueResult->fetch_assoc()['total'] ?? 0;

        echo json_encode([
            'totalUsers' => $totalUsers,
            'pendingOrders' => $pendingOrders,
            'monthlyRevenue' => number_format($monthlyRevenue, 2),
        ]);
        break;

    case 'getRecentOrders':
        $recentOrdersResult = $conn->query("SELECT id, customer_name, order_date, amount, status FROM orders ORDER BY order_date DESC LIMIT 5");
        $recentOrders = [];
        while ($row = $recentOrdersResult->fetch_assoc()) {
            $recentOrders[] = $row;
        }
        echo json_encode($recentOrders);
        break;

    case 'getUsers':
        $usersResult = $conn->query("SELECT id, username, email, role, status FROM users ORDER BY created_at DESC");
        $users = [];
        while ($row = $usersResult->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode($users);
        break;

    case 'getInventory':
        $inventoryResult = $conn->query("SELECT id, name, category, price, stock, status FROM products ORDER BY name ASC");
        $inventory = [];
        while ($row = $inventoryResult->fetch_assoc()) {
            $inventory[] = $row;
        }
        echo json_encode($inventory);
        break;

    case 'getAllOrders':
        $allOrdersResult = $conn->query("SELECT id, customer_name, order_date, amount, status FROM orders ORDER BY order_date DESC");
        $allOrders = [];
        while ($row = $allOrdersResult->fetch_assoc()) {
            $allOrders[] = $row;
        }
        echo json_encode($allOrders);
        break;

    case 'getReports':
        // Sales Report (Total Revenue)
        $totalRevenueResult = $conn->query("SELECT SUM(amount) AS total FROM orders WHERE status = 'completed'");
        $totalRevenue = $totalRevenueResult->fetch_assoc()['total'] ?? 0;

        // Order Volume (Total Orders)
        $totalOrdersResult = $conn->query("SELECT COUNT(*) AS total FROM orders");
        $totalOrders = $totalOrdersResult->fetch_assoc()['total'];

        // Transaction History (Recent Transactions)
        $transactionsResult = $conn->query("SELECT id, order_date, customer_name AS user, amount, 'Credit Card' AS method FROM orders WHERE status = 'completed' ORDER BY order_date DESC LIMIT 5");
        $transactions = [];
        while ($row = $transactionsResult->fetch_assoc()) {
            $transactions[] = $row;
        }

        echo json_encode([
            'salesReport' => number_format($totalRevenue, 2),
            'orderVolume' => $totalOrders,
            'transactionHistory' => $transactions,
        ]);
        break;

    default:
        echo json_encode(["error" => "Invalid action"]);
        break;
}

$conn->close();
?>