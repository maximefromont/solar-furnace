<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "e";
$dbname = "fsp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id_data, date_data, water_temp FROM Data ORDER BY id_data ASC";
$result = $conn->query($sql);
$waterTempData = [];
while ($row = $result->fetch_assoc()) {
    $waterTempData[] = ['id_data' => $row['id_data'], 'x' => date("Y-m-d\TH:i:s\Z",strtotime($row['date_data'])), 'y' => $row['water_temp']];
}

$sql = "SELECT date_target, target_temp FROM target ORDER BY id_target DESC";
$result = $conn->query($sql);
$targetTempData = [];
while ($row = $result->fetch_assoc()) {
    $targetTempData[] = ['x' => date("Y-m-d\TH:i:s\Z",strtotime($row['date_target'])), 'y' => $row['target_temp']];
}

// Close the connection
$conn->close();

echo json_encode(['waterTempData' => $waterTempData, 'targetTempData' => $targetTempData]);
?>
