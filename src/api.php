<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$conn = new mysqli('db:3306', 'admin', 'admin', 'database');

$data = array(
    '$_REQUEST' => $_REQUEST,
    '$_SERVER' => $_SERVER,
    'conn' => $conn,
);

$conn->close();
echo json_encode($data);
