<?php
include 'db_config.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Fetch all vehicles
    $result = $conn->query("SELECT * FROM vehicles ORDER BY created_at DESC");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));

} elseif ($method === 'POST') {
    // Add a vehicle
    $data = json_decode(file_get_contents('php://input'), true);
    $year = $conn->real_escape_string($data['year']);
    $make = $conn->real_escape_string($data['make']);
    $model = $conn->real_escape_string($data['model']);

    $sql = "INSERT INTO vehicles (year, make, model) VALUES ('$year', '$make', '$model')";
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }

} elseif ($method === 'DELETE') {
    // Remove a vehicle
    $id = $_GET['id'];
    $sql = "DELETE FROM vehicles WHERE id = $id";
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}

$conn->close();
?>