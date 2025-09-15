<?php
session_start();
header('Content-Type: application/json'); // always return JSON
include_once 'dao/config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "No data received"]);
    exit;
}

$userid = $_SESSION['userId'];
$sessionId = $_SESSION['sessionId'];
$organizationId = $_SESSION['organizationId'];

$cards = mysqli_real_escape_string($con, json_encode($data['cards'], JSON_UNESCAPED_UNICODE));
$reading = mysqli_real_escape_string($con, $data['reading']);
$timestamp = date('Y-m-d H:i:s');

// ✅ If record exists → update, else insert
$check = "SELECT id FROM stat WHERE userid='$userid' AND sessionId='$sessionId' AND organizationId='$organizationId' LIMIT 1";
$result = execute_query($check);

if (mysqli_num_rows($result) > 0) {
    // Update existing row
    $query = "UPDATE stat 
              SET cards='$cards', reading='$reading', timestamp_end='$timestamp' 
              WHERE userid='$userid' AND sessionId='$sessionId' AND organizationId='$organizationId'";
} else {
    // Insert new row with required fields
    $query = "INSERT INTO stat (userid, sessionId, organizationId, cards, reading, timestamp_start) 
              VALUES ('$userid','$sessionId','$organizationId','$cards','$reading','$timestamp')";
}

if (execute_query($query)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => mysqli_error($con), "query" => $query]);
}